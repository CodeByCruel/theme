<?php

namespace App\Http\Controllers\Api\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Models\Server;

class InstallerController extends Controller
{
    public function search(Request $request, int $serverId)
    {
        $server = Server::findOrFail($serverId);
        $this->authorize('view', $server);

        $query = $request->input('query', '');
        $provider = $request->input('provider', 'modrinth');
        $category = $request->input('category', 'plugin');
        $gameVersion = $request->input('game_version');
        $loader = $request->input('loader');
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);

        return match ($provider) {
            'modrinth' => $this->searchModrinth($query, $category, $gameVersion, $loader, $page, $limit),
            'curseforge' => $this->searchCurseForge($query, $category, $gameVersion, $page, $limit),
            'spigot' => $this->searchSpigot($query, $page, $limit),
            'hangar' => $this->searchHangar($query, $page, $limit),
            default => response()->json(['data' => [], 'total' => 0]),
        };
    }

    public function install(Request $request, int $serverId)
    {
        $server = Server::findOrFail($serverId);
        $this->authorize('file.create', $server);

        $id = $request->input('id');
        $provider = $request->input('provider');
        $category = $request->input('category', 'plugin');

        $url = match ($provider) {
            'modrinth' => $this->getModrinthUrl($id, $category),
            'curseforge' => $this->getCurseForgeUrl($id),
            'spigot' => $this->getSpigotUrl($id),
            'hangar' => $this->getHangarUrl($id),
            default => null,
        };

        if (!$url) {
            return response()->json(['error' => 'Could not get download URL'], 404);
        }

        $dir = match ($category) {
            'plugin' => 'plugins',
            'mod' => 'mods',
            'resourcepack' => 'resourcepacks',
            'shader' => 'shaderpacks',
            default => 'plugins',
        };

        try {
            $response = Http::timeout(60)->get($url);
            if (!$response->successful()) {
                return response()->json(['error' => 'Download failed'], 500);
            }

            $filename = $this->getFilename($response, $id);
            $path = "$dir/$filename";

            $server->data()->create([
                'file' => $path,
                'contents' => $response->body(),
            ]);

            return response()->json(['success' => true, 'filename' => $filename, 'path' => $path]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function installed(int $serverId)
    {
        $server = Server::findOrFail($serverId);
        $this->authorize('view', $server);

        $dirs = ['plugins', 'mods', 'resourcepacks', 'shaderpacks'];
        $installed = [];

        foreach ($dirs as $dir) {
            $files = $server->data()->where('file', 'LIKE', "$dir/%")->get();
            foreach ($files as $file) {
                $installed[] = [
                    'filename' => basename($file->file),
                    'directory' => $dir,
                ];
            }
        }

        return response()->json($installed);
    }

    public function remove(Request $request, int $serverId)
    {
        $server = Server::findOrFail($serverId);
        $this->authorize('file.delete', $server);

        $filename = $request->input('filename');
        $directory = $request->input('directory', 'plugins');

        $server->data()->where('file', "$directory/$filename")->delete();

        return response()->json(['success' => true]);
    }

    // ── Modrinth ──
    private function searchModrinth(string $query, string $category, ?string $version, ?string $loader, int $page, int $limit): \Illuminate\Http\JsonResponse
    {
        $type = match ($category) {
            'plugin' => 'plugin', 'mod' => 'mod', 'modpack' => 'modpack',
            'resourcepack' => 'resourcepack', 'shader' => 'shader', default => 'plugin',
        };

        $facets = [["project_type:$type"]];
        if ($version) $facets[] = ["versions:$version"];
        if ($loader) $facets[] = ["categories:$loader"];

        try {
            $res = Http::timeout(10)->get('https://api.modrinth.com/v2/search', [
                'query' => $query,
                'limit' => $limit,
                'offset' => ($page - 1) * $limit,
                'facets' => json_encode($facets),
            ]);

            if (!$res->successful()) return response()->json(['data' => [], 'total' => 0]);

            $data = $res->json();
            $results = collect($data['hits'] ?? [])->map(fn($h) => [
                'id' => $h['slug'] ?? $h['project_id'],
                'name' => $h['title'],
                'slug' => $h['slug'],
                'description' => $h['description'] ?? '',
                'author' => $h['author'],
                'downloads' => $h['downloads'] ?? 0,
                'icon' => $h['icon_url'] ?? '',
                'provider' => 'modrinth',
                'versions' => $h['versions'] ?? [],
                'loaders' => $h['categories'] ?? [],
            ])->toArray();

            return response()->json(['data' => $results, 'total' => $data['total_hits'] ?? 0]);
        } catch (\Exception $e) {
            return response()->json(['data' => [], 'total' => 0]);
        }
    }

    private function searchCurseForge(string $query, string $category, ?string $version, int $page, int $limit): \Illuminate\Http\JsonResponse
    {
        $classId = match ($category) {
            'plugin' => 5, 'mod' => 6, 'modpack' => 12, 'shader' => 65, default => 6,
        };

        try {
            $res = Http::withHeaders(['x-api-key' => config('services.curseforge.key', '')])
                ->timeout(10)
                ->get('https://api.curseforge.com/v1/mods/search', [
                    'gameId' => 432, 'classId' => $classId, 'searchFilter' => $query,
                    'pageSize' => $limit, 'index' => ($page - 1) * $limit,
                ]);

            if (!$res->successful()) return response()->json(['data' => [], 'total' => 0]);

            $data = $res->json();
            $results = collect($data['data'] ?? [])->map(fn($m) => [
                'id' => (string) $m['id'],
                'name' => $m['name'],
                'slug' => $m['slug'],
                'description' => $m['summary'] ?? '',
                'author' => $m['authors'][0]['name'] ?? 'Unknown',
                'downloads' => $m['downloadCount'] ?? 0,
                'icon' => $m['logo']['thumbnailUrl'] ?? '',
                'provider' => 'curseforge',
                'versions' => [],
                'loaders' => [],
            ])->toArray();

            return response()->json(['data' => $results, 'total' => $data['pagination']['totalCount'] ?? 0]);
        } catch (\Exception $e) {
            return response()->json(['data' => [], 'total' => 0]);
        }
    }

    private function searchSpigot(string $query, int $page, int $limit): \Illuminate\Http\JsonResponse
    {
        try {
            $res = Http::timeout(10)->get("https://api.spiget.org/v2/search/resources/" . urlencode($query), [
                'size' => $limit, 'page' => $page, 'sort' => '-downloads',
            ]);

            if (!$res->successful()) return response()->json(['data' => [], 'total' => 0]);

            $results = collect($res->json())->map(fn($r) => [
                'id' => (string) $r['id'],
                'name' => $r['name'],
                'slug' => $r['id'],
                'description' => $r['description'] ?? '',
                'author' => $r['author']['name'] ?? 'Unknown',
                'downloads' => $r['downloads'] ?? 0,
                'icon' => isset($r['icon']['url']) ? 'https://spiget.org' . $r['icon']['url'] : '',
                'provider' => 'spigot',
                'versions' => [],
                'loaders' => [],
            ])->toArray();

            return response()->json(['data' => $results, 'total' => count($results)]);
        } catch (\Exception $e) {
            return response()->json(['data' => [], 'total' => 0]);
        }
    }

    private function searchHangar(string $query, int $page, int $limit): \Illuminate\Http\JsonResponse
    {
        try {
            $res = Http::timeout(10)->get('https://hangar.papermc.io/api/v1/projects', [
                'search' => $query, 'limit' => $limit, 'offset' => ($page - 1) * $limit,
            ]);

            if (!$res->successful()) return response()->json(['data' => [], 'total' => 0]);

            $data = $res->json();
            $results = collect($data['result'] ?? [])->map(fn($p) => [
                'id' => $p['name'],
                'name' => $p['name'],
                'slug' => $p['name'],
                'description' => $p['description'] ?? '',
                'author' => $p['owner']['name'] ?? 'Unknown',
                'downloads' => $p['stats']['downloads'] ?? 0,
                'icon' => $p['avatarUrl'] ?? '',
                'provider' => 'hangar',
                'versions' => [],
                'loaders' => ['Paper'],
            ])->toArray();

            return response()->json(['data' => $results, 'total' => $data['pagination']['totalCount'] ?? 0]);
        } catch (\Exception $e) {
            return response()->json(['data' => [], 'total' => 0]);
        }
    }

    // ── Download URLs ──
    private function getModrinthUrl(string $slug, string $category): ?string
    {
        try {
            $res = Http::timeout(10)->get("https://api.modrinth.com/v2/project/{$slug}/version");
            if (!$res->successful()) return null;
            $versions = $res->json();
            return $versions[0]['files'][0]['url'] ?? null;
        } catch (\Exception $e) { return null; }
    }

    private function getCurseForgeUrl(string $modId): ?string
    {
        try {
            $res = Http::withHeaders(['x-api-key' => config('services.curseforge.key', '')])
                ->timeout(10)
                ->get("https://api.curseforge.com/v1/mods/{$modId}/files", ['pageSize' => 1]);
            if (!$res->successful()) return null;
            return $res->json()['data'][0]['downloadUrl'] ?? null;
        } catch (\Exception $e) { return null; }
    }

    private function getSpigotUrl(string $resourceId): ?string
    {
        try {
            $res = Http::timeout(10)->get("https://api.spiget.org/v2/resources/{$resourceId}/download");
            return $res->successful() ? $res->effectiveUri() : null;
        } catch (\Exception $e) { return null; }
    }

    private function getHangarUrl(string $projectName): ?string
    {
        try {
            $res = Http::timeout(10)->get("https://hangar.papermc.io/api/v1/projects/{$projectName}/versions", ['limit' => 1]);
            if (!$res->successful()) return null;
            $versions = $res->json()['result'] ?? [];
            $latest = $versions[0] ?? null;
            return $latest ? "https://hangar.papermc.io/api/v1/projects/{$projectName}/versions/{$latest['name']}/download" : null;
        } catch (\Exception $e) { return null; }
    }

    private function getFilename($response, string $id): string
    {
        $disposition = $response->header('Content-Disposition');
        if ($disposition && preg_match('/filename="?([^";\s]+)"?/', $disposition, $m)) {
            return $m[1];
        }
        return $id . '.jar';
    }
}
