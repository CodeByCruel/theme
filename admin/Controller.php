<?php

namespace Pterodactyl\Http\Controllers\Admin\Extensions\Bsdkv1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Models\Server;

class Bsdkv1ExtensionController extends Controller
{
    private const SETTINGS_TABLE = 'bsdkv1_settings';

    private const DEFAULT_SETTINGS = [
        'primary_color' => '#00d4ff',
        'secondary_color' => '#7b68ee',
        'accent_color' => '#00ff88',
        'danger_color' => '#ff4466',
        'warning_color' => '#ffaa00',
        'bg_primary' => '#0a0e17',
        'bg_secondary' => '#111827',
        'bg_card' => '#1a2332',
        'bg_elevated' => '#243044',
        'text_primary' => '#e4e8f0',
        'text_secondary' => '#8899aa',
        'text_muted' => '#5a6a7a',
        'border_color' => 'rgba(255, 255, 255, 0.08)',
        'border_hover' => 'rgba(255, 255, 255, 0.15)',
        'panel_name' => 'BSDK Panel',
        'panel_tagline' => 'Game Server Management',
        'logo_path' => '/extensions/bsdkv1/assets/logo.svg',
        'favicon_path' => '/extensions/bsdkv1/assets/favicon.ico',
        'login_bg' => '/extensions/bsdkv1/assets/background.svg',
        'font_family' => 'Inter',
        'font_mono' => 'JetBrains Mono',
        'border_radius' => '8px',
        'glow_enabled' => 'true',
        'animations_enabled' => 'true',
        'compact_mode' => 'false',
        'sidebar_style' => 'modern',
        'card_style' => 'glass',
        'button_style' => 'rounded',
        'gradient_enabled' => 'true',
        'particle_bg' => 'true',
        'custom_css' => '',
        'custom_js' => '',
    ];

    public function index(Request $request)
    {
        $settings = $this->getSettings();
        $servers = Server::count();

        return view('admin.extensions.bsdkv1.view', [
            'settings' => $settings,
            'server_count' => $servers,
            'default_settings' => self::DEFAULT_SETTINGS,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'accent_color' => 'nullable|string|max:7',
            'danger_color' => 'nullable|string|max:7',
            'warning_color' => 'nullable|string|max:7',
            'bg_primary' => 'nullable|string|max:7',
            'bg_secondary' => 'nullable|string|max:7',
            'bg_card' => 'nullable|string|max:7',
            'bg_elevated' => 'nullable|string|max:7',
            'text_primary' => 'nullable|string|max:7',
            'text_secondary' => 'nullable|string|max:7',
            'text_muted' => 'nullable|string|max:7',
            'border_color' => 'nullable|string|max:50',
            'border_hover' => 'nullable|string|max:50',
            'panel_name' => 'nullable|string|max:255',
            'panel_tagline' => 'nullable|string|max:255',
            'logo_path' => 'nullable|string|max:500',
            'favicon_path' => 'nullable|string|max:500',
            'login_bg' => 'nullable|string|max:500',
            'font_family' => 'nullable|string|max:100',
            'font_mono' => 'nullable|string|max:100',
            'border_radius' => 'nullable|string|max:20',
            'glow_enabled' => 'nullable|string|max:5',
            'animations_enabled' => 'nullable|string|max:5',
            'compact_mode' => 'nullable|string|max:5',
            'sidebar_style' => 'nullable|string|max:20',
            'card_style' => 'nullable|string|max:20',
            'button_style' => 'nullable|string|max:20',
            'gradient_enabled' => 'nullable|string|max:5',
            'particle_bg' => 'nullable|string|max:5',
            'custom_css' => 'nullable|string|max:10000',
            'custom_js' => 'nullable|string|max:10000',
        ]);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                DB::table(self::SETTINGS_TABLE)
                    ->updateOrInsert(
                        ['setting_key' => $key],
                        ['setting_value' => $value, 'updated_at' => now()]
                    );
            }
        }

        return redirect()->route('admin.extensions.bsdkv1')
            ->with('success', 'Theme settings updated successfully!');
    }

    public function reset(Request $request)
    {
        DB::table(self::SETTINGS_TABLE)->truncate();

        foreach (self::DEFAULT_SETTINGS as $key => $value) {
            DB::table(self::SETTINGS_TABLE)->insert([
                'setting_key' => $key,
                'setting_value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.extensions.bsdkv1')
            ->with('success', 'Theme settings reset to defaults!');
    }

    public function export(Request $request)
    {
        $settings = $this->getSettings();

        return response()->json($settings)
            ->header('Content-Disposition', 'attachment; filename="bsdkv1-theme.json"');
    }

    public function import(Request $request)
    {
        $request->validate(['theme' => 'required|json']);

        $settings = json_decode($request->input('theme'), true);
        if (!is_array($settings)) {
            return back()->withErrors(['theme' => 'Invalid theme JSON']);
        }

        foreach ($settings as $key => $value) {
            if (isset(self::DEFAULT_SETTINGS[$key])) {
                DB::table(self::SETTINGS_TABLE)
                    ->updateOrInsert(
                        ['setting_key' => $key],
                        ['setting_value' => $value, 'updated_at' => now()]
                    );
            }
        }

        return redirect()->route('admin.extensions.bsdkv1')
            ->with('success', 'Theme imported successfully!');
    }

    private function getSettings(): array
    {
        $rows = DB::table(self::SETTINGS_TABLE)->get();
        $settings = self::DEFAULT_SETTINGS;

        foreach ($rows as $row) {
            $settings[$row->setting_key] = $row->setting_value;
        }

        return $settings;
    }
}
