<?php

namespace Pterodactyl\Http\Controllers\Extensions\Bsdkv1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Models\Server;

class InstallerController extends Controller
{
    /**
     * Search for plugins/mods from multiple providers
     */
    public function search(Request $request, int $serverId)
    {
        $server = Server::findOrFail($serverId);
        $this->authorize('view', $server);

        $validated = $request->validate([
            'query' => 'nullable|string|max:100',
            'provider' => 'nullable|string|in:modrinth,curseforge,spigot,hangar,polymart',
            'category' => 'nullable|string|in:plugin,mod,modpack,resourcepack,shader',
            'game_version' => 'nullable|string|max:20',
            'loader' => 'nullable|string|max:50',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $provider = $validated['provider'] ?? 'modrinth';
        $category = $validated['category'] ?? 'plugin';
        $query = $validated['query'] ?? '';
        $page = $validated['page'] ?? 1;
        $limit = $validated['limit'] ?? 20;

        $results = match ($provider) {
            'modrinth' => $this->searchModrinth($query, $category, $validated['game_version'] ?? null, $validated['loader'] ?? null, $page, $limit),
            'curseforge' => $this->searchCurseForge($query, $category, $validated['game_version'] ?? null, $page, $limit),
            'spigot' => $this->searchSpigot($query, $category, $page, $limit),
            'hangar' => $this->searchHangar($query, $category, $page, $limit),
            'polymart' => $this->searchPolymart($query, $category, $page, $limit),
            default => ['data' => [], 'meta' => ['total' => 0]],
        };

        return response()->json($results);
    }

    /**
     * Install a plugin/mod to a server
     */
    public function install(Request $request, int $serverId)
    {
        $server = Server::findOrFail($serverId);
        $this->authorize('file.create', $server);

        $validated = $request->validate([
            'id' => 'required|string',
            'provider' => 'required|string|in:modrinth,curseforge,spigot,hangar,polymart',
            'category' => 'required|string|in:plugin,mod,modpack,resourcepack,shader',
            'version_id' => 'nullable|string',
        ]);

        $downloadUrl = match ($validated['provider']) {
            'modrinth' => $this->getModrinthDownload($validated['id'], $validated['version_id'] ?? null, $validated['category']),
            'curseforge' => $this->getCurseForgeDownload($validated['id'], $validated['version_id'] ?? null),
            'spigot' => $this->getSpigotDownload($validated['id']),
            'hangar' => $this->getHangarDownload($validated['id'], $validated['version_id'] ?? null),
            'polymart' => $this->getPolymartDownload($validated['id'], $validated['version_id'] ?? null),
            default => null,
        };

        if (!$downloadUrl) {
            return response()->json(['message' => 'Could not retrieve download URL'], 404);
        }

        // Determine target directory based on category
        $targetDir = match ($validated['category']) {
            'plugin' => 'plugins',
            'mod' => 'mods',
            'modpack' => null, // Modpacks need special handling
            'resourcepack' => 'resourcepacks',
            'shader' => 'shaderpacks',
            default => 'plugins',
        };

        if ($targetDir === null) {
            return response()->json(['message' => 'Modpack installation requires server recreation']), 400);
        }

        // Download the file
        try {
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 60,
            ])->get($downloadUrl);

            if (!$response->successful()) {
                return response()->json(['message' => 'Failed to download file from provider']), 500;
            }

            $filename = $this->getFilenameFromResponse($response, $validated['id']);
            $path = "$targetDir/$filename";

            // Store the file on the server
            $server->data()->create([
                'file' => $path,
                'contents' => $response->body(),
            ]);

            return response()->json([
                'message' => 'Plugin installed successfully',
                'filename' => $filename,
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Installation failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * List installed plugins
     */
    public function installed(int $serverId)
    {
        $server = Server::findOrFail($serverId);
        $this->authorize('view', $server);

        $directories = ['plugins', 'mods', 'resourcepacks', 'shaderpacks'];
        $installed = [];

        foreach ($directories as $dir) {
            try {
                $files = $server->data()->where('file', 'LIKE', "$dir/%")->get();
                foreach ($files as $file) {
                    $installed[] = [
                        'filename' => basename($file->file),
                        'directory' => $dir,
                        'path' => $file->file,
                    ];
                }
            } catch (\Exception $e) {
                // Directory might not exist
            }
        }

        return response()->json($installed);
    }

    /**
     * Remove an installed plugin
     */
    public function remove(Request $request, int $serverId)
    {
        $server = Server::findOrFail($serverId);
        $this->authorize('file.delete', $server);

        $validated = $request->validate([
            'filename' => 'required|string',
            'directory' => 'nullable|string|in:plugins,mods,resourcepacks,shaderpacks',
        ]);

        $directory = $validated['directory'] ?? 'plugins';
        $path = "$directory/" . $validated['filename'];

        $server->data()->where('file', $path)->delete();

        return response()->json(['message' => 'Plugin removed successfully']);
    }

    // ── Provider Search Methods ──

    private function searchModrinth(string $query, string $category, ?string $gameVersion, ?string $loader, int $page, int $limit): array
    {
        $facets = [];
        
        $projectType = match ($category) {
            'plugin' => 'plugin',
            'mod' => 'mod',
            'modpack' => 'modpack',
            'resourcepack' => 'resourcepack',
            'shader' => 'shader',
            default => 'plugin',
        };
        
        $facets[] = ["project_type:$projectType"];
        
        if ($gameVersion) {
            $facets[] = ["versions:$gameVersion"];
        }
        
        if ($loader) {
            $facets[] = ["categories:$loader"];
        }

        $params = [
            'query' => $query,
            'limit' => $limit,
            'offset' => ($page - 1) * $limit,
            'facets' => json_encode($facets),
        ];

        try {
            $response = Http::timeout(10)->get('https://api.modrinth.com/v2/search', $params);
            
            if (!$response->successful()) {
                return ['data' => [], 'meta' => ['total' => 0]];
            }

            $data = $response->json();
            $results = [];

            foreach ($data['hits'] ?? [] as $hit) {
                $results[] = [
                    'id' => $hit['slug'] ?? $hit['project_id'],
                    'name' => $hit['title'],
                    'slug' => $hit['slug'],
                    'description' => $hit['description'] ?? '',
                    'author' => $hit['author'],
                    'downloads' => $hit['downloads'] ?? 0,
                    'icon' => $hit['icon_url'] ?? '',
                    'provider' => 'modrinth',
                    'category' => $category,
                    'versions' => $hit['versions'] ?? [],
                    'loaders' => $hit['categories'] ?? [],
                    'dateUpdated' => $hit['date_modified'] ?? '',
                ];
            }

            return [
                'data' => $results,
                'meta' => ['total' => $data['total_hits'] ?? 0],
            ];
        } catch (\Exception $e) {
            return ['data' => [], 'meta' => ['total' => 0]];
        }
    }

    private function searchCurseForge(string $query, string $category, ?string $gameVersion, int $page, int $limit): array
    {
        $classId = match ($category) {
            'plugin' => 5,   // Bukkit Plugins
            'mod' => 6,      // Mods
            'modpack' => 12, // Modpacks
            'resourcepack' => 12, // Resource Packs
            'shader' => 65,  // Shaders
            default => 6,
        };

        $apiKey = config('services.curseforge.key', '');
        
        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'Accept' => 'application/json',
            ])->timeout(10)->get('https://api.curseforge.com/v1/mods/search', [
                'gameId' => 432, // Minecraft
                'classId' => $classId,
                'searchFilter' => $query,
                'pageSize' => $limit,
                'index' => ($page - 1) * $limit,
                ...( $gameVersion ? ['gameVersion' => $gameVersion] : []),
            ]);

            if (!$response->successful()) {
                return ['data' => [], 'meta' => ['total' => 0]];
            }

            $data = $response->json();
            $results = [];

            foreach ($data['data'] ?? [] as $mod) {
                $latestFile = $mod['latestFiles'][0] ?? null;
                $results[] = [
                    'id' => (string) $mod['id'],
                    'name' => $mod['name'],
                    'slug' => $mod['slug'],
                    'description' => $mod['summary'] ?? '',
                    'author' => $mod['authors'][0]['name'] ?? 'Unknown',
                    'downloads' => $mod['downloadCount'] ?? 0,
                    'icon' => $mod['logo']['thumbnailUrl'] ?? '',
                    'provider' => 'curseforge',
                    'category' => $category,
                    'versions' => $latestFile ? [$latestFile['gameVersion'][0] ?? ''] : [],
                    'loaders' => [],
                    'dateUpdated' => $mod['dateModified'] ?? '',
                ];
            }

            return [
                'data' => $results,
                'meta' => ['total' => $data['pagination']['totalCount'] ?? 0],
            ];
        } catch (\Exception $e) {
            return ['data' => [], 'meta' => ['total' => 0]];
        }
    }

    private function searchSpigot(string $query, string $category, int $page, int $limit): array
    {
        try {
            $response = Http::timeout(10)->get('https://api.spiget.org/v2/search/resources/' . urlencode($query), [
                'size' => $limit,
                'page' => $page,
                'sort' => '-downloads',
            ]);

            if (!$response->successful()) {
                return ['data' => [], 'meta' => ['total' => 0]];
            }

            $data = $response->json();
            $results = [];

            foreach ($data as $resource) {
                $results[] = [
                    'id' => (string) $resource['id'],
                    'name' => $resource['name'],
                    'slug' => $resource['slug'] ?? $resource['id'],
                    'description' => $resource['description'] ?? '',
                    'author' => $resource['author']['name'] ?? 'Unknown',
                    'downloads' => $resource['downloads'] ?? 0,
                    'icon' => isset($resource['icon']['url']) ? 'https://spiget.org' . $resource['icon']['url'] : '',
                    'provider' => 'spigot',
                    'category' => $category,
                    'versions' => [],
                    'loaders' => [],
                    'dateUpdated' => date('c', $resource['updateDate'] ?? time()),
                ];
            }

            return [
                'data' => $results,
                'meta' => ['total' => count($data)],
            ];
        } catch (\Exception $e) {
            return ['data' => [], 'meta' => ['total' => 0]];
        }
    }

    private function searchHangar(string $query, string $category, int $page, int $limit): array
    {
        try {
            $response = Http::timeout(10)->get('https://hangar.papermc.io/api/v1/projects', [
                'search' => $query,
                'limit' => $limit,
                'offset' => ($page - 1) * $limit,
                'sort' => 'DOWNLOADS',
            ]);

            if (!$response->successful()) {
                return ['data' => [], 'meta' => ['total' => 0]];
            }

            $data = $response->json();
            $results = [];

            foreach ($data['result'] ?? [] as $project) {
                $results[] = [
                    'id' => $project['name'],
                    'name' => $project['name'],
                    'slug' => $project['name'],
                    'description' => $project['description'] ?? '',
                    'author' => $project['owner']['name'] ?? 'Unknown',
                    'downloads' => $project['stats']['downloads'] ?? 0,
                    'icon' => $project['avatarUrl'] ?? '',
                    'provider' => 'hangar',
                    'category' => $category,
                    'versions' => [],
                    'loaders' => ['Paper'],
                    'dateUpdated' => $project['lastUpdated'] ?? '',
                ];
            }

            return [
                'data' => $results,
                'meta' => ['total' => $data['pagination']['totalCount'] ?? 0],
            ];
        } catch (\Exception $e) {
            return ['data' => [], 'meta' => ['total' => 0]];
        }
    }

    private function searchPolymart(string $query, string $category, int $page, int $limit): array
    {
        try {
            $response = Http::timeout(10)->post('https://api.polymart.org/v1', [
                'query' => 'search',
                'search' => [
                    'query' => $query,
                    'limit' => $limit,
                    'offset' => ($page - 1) * $limit,
                ],
            ]);

            if (!$response->successful()) {
                return ['data' => [], 'meta' => ['total' => 0]];
            }

            $data = $response->json();
            $results = [];

            foreach ($data['resources'] ?? [] as $resource) {
                $results[] = [
                    'id' => (string) $resource['id'],
                    'name' => $resource['title'],
                    'slug' => $resource['slug'] ?? $resource['id'],
                    'description' => $resource['description'] ?? '',
                    'author' => $resource['author']['name'] ?? 'Unknown',
                    'downloads' => $resource['downloads'] ?? 0,
                    'icon' => $resource['icon']['url'] ?? '',
                    'provider' => 'polymart',
                    'category' => $category,
                    'versions' => [],
                    'loaders' => [],
                    'dateUpdated' => $resource['updated'] ?? '',
                ];
            }

            return [
                'data' => $results,
                'meta' => ['total' => $data['total'] ?? 0],
            ];
        } catch (\Exception $e) {
            return ['data' => [], 'meta' => ['total' => 0]];
        }
    }

    // ── Download URL Methods ──

    private function getModrinthDownload(string $slug, ?string $versionId, string $category): ?string
    {
        try {
            $projectType = match ($category) {
                'plugin' => 'plugin',
                'mod' => 'mod',
                'modpack' => 'modpack',
                'resourcepack' => 'resourcepack',
                'shader' => 'shader',
                default => 'plugin',
            };

            $response = Http::timeout(10)->get("https://api.modrinth.com/v2/project/{$slug}/version", [
                'loaders' => json_encode(['paper', 'spigot', 'bukkit']),
            ]);

            if (!$response->successful()) return null;

            $versions = $response->json();
            $version = $versionId 
                ? collect($versions)->firstWhere('version_id', $versionId)
                : $versions[0] ?? null;

            return $version['files'][0]['url'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getCurseForgeDownload(string $modId, ?string $fileId): ?string
    {
        $apiKey = config('services.curseforge.key', '');
        
        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
            ])->timeout(10)->get("https://api.curseforge.com/v1/mods/{$modId}/files", [
                'pageSize' => 1,
            ]);

            if (!$response->successful()) return null;

            $files = $response->json()['data'] ?? [];
            return $files[0]['downloadUrl'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getSpigotDownload(string $resourceId): ?string
    {
        try {
            $response = Http::timeout(10)->get("https://api.spiget.org/v2/resources/{$resourceId}/download", [], [
                'follow_redirects' => true,
            ]);

            return $response->successful() ? $response->effectiveUri() : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getHangarDownload(string $projectName, ?string $version): ?string
    {
        try {
            $response = Http::timeout(10)->get("https://hangar.papermc.io/api/v1/projects/{$projectName}/versions", [
                'limit' => 1,
            ]);

            if (!$response->successful()) return null;

            $versions = $response->json()['result'] ?? [];
            $latest = $versions[0] ?? null;
            return $latest ? "https://hangar.papermc.io/api/v1/projects/{$projectName}/versions/{$latest['name']}/download" : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getPolymartDownload(string $resourceId, ?string $versionId): ?string
    {
        try {
            $response = Http::timeout(10)->post('https://api.polymart.org/v1', [
                'query' => 'resource',
                'resource' => ['id' => $resourceId],
            ]);

            if (!$response->successful()) return null;

            $resource = $response->json()['resource'] ?? null;
            return $resource['download']['url'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    // ── Helper Methods ──

    private function getFilenameFromResponse($response, string $id): string
    {
        $disposition = $response->header('Content-Disposition');
        if ($disposition && preg_match('/filename="?([^";\s]+)"?/', $disposition, $matches)) {
            return $matches[1];
        }

        $contentType = $response->header('Content-Type');
        $ext = match (true) {
            str_contains($contentType ?? '', 'zip') => 'zip',
            str_contains($contentType ?? '', 'java') => 'jar',
            default => 'jar',
        };

        return $id . '.' . $ext;
    }
}
