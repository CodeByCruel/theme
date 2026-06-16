<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BsdkThemeController extends Controller
{
    private function getSettings(): array
    {
        $settings = [];
        try {
            if (Schema::hasTable('bsdkv1_settings')) {
                $rows = DB::table('bsdkv1_settings')->get();
                foreach ($rows as $row) {
                    $settings[$row->setting_key] = $row->setting_value;
                }
            }
        } catch (\Exception $e) {}
        return $settings;
    }

    private function getDefaults(): array
    {
        return [
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
            'border_radius' => '8px',
            'font_family' => 'Inter',
            'font_mono' => 'JetBrains Mono',
            'panel_name' => 'BSDK Panel',
            'panel_tagline' => 'Game Server Management',
            'logo_path' => '/assets/bsdk/logo.svg',
            'favicon_path' => '/assets/bsdk/favicon.svg',
            'login_bg' => '/assets/bsdk/background.svg',
            'glow_enabled' => 'true',
            'animations_enabled' => 'true',
            'gradient_enabled' => 'true',
            'particle_bg' => 'true',
            'compact_mode' => 'false',
            'sidebar_style' => 'modern',
            'card_style' => 'glass',
            'button_style' => 'rounded',
            'custom_css' => '',
            'custom_js' => '',
        ];
    }

    private function saveSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            DB::table('bsdkv1_settings')->updateOrInsert(
                ['setting_key' => $key],
                ['setting_value' => $value, 'updated_at' => now()]
            );
        }
    }

    public function index()
    {
        $settings = array_merge($this->getDefaults(), $this->getSettings());
        $presets = config('bsdk-presets', []);
        $activePreset = $settings['active_preset'] ?? 'bsdk-default';

        return view('admin.bsdk-theme', compact('settings', 'presets', 'activePreset'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'primary_color' => 'nullable|string',
            'secondary_color' => 'nullable|string',
            'accent_color' => 'nullable|string',
            'danger_color' => 'nullable|string',
            'warning_color' => 'nullable|string',
            'bg_primary' => 'nullable|string',
            'bg_secondary' => 'nullable|string',
            'bg_card' => 'nullable|string',
            'bg_elevated' => 'nullable|string',
            'text_primary' => 'nullable|string',
            'text_secondary' => 'nullable|string',
            'text_muted' => 'nullable|string',
            'border_color' => 'nullable|string',
            'border_hover' => 'nullable|string',
            'border_radius' => 'nullable|string',
            'font_family' => 'nullable|string',
            'font_mono' => 'nullable|string',
            'panel_name' => 'nullable|string|max:100',
            'panel_tagline' => 'nullable|string|max:200',
            'logo_path' => 'nullable|string',
            'favicon_path' => 'nullable|string',
            'login_bg' => 'nullable|string',
            'glow_enabled' => 'nullable|string|in:true,false',
            'animations_enabled' => 'nullable|string|in:true,false',
            'gradient_enabled' => 'nullable|string|in:true,false',
            'particle_bg' => 'nullable|string|in:true,false',
            'compact_mode' => 'nullable|string|in:true,false',
            'sidebar_style' => 'nullable|string|in:modern,classic,minimal',
            'card_style' => 'nullable|string|in:glass,elevated,flat',
            'button_style' => 'nullable|string|in:rounded,pill,square',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
            'active_preset' => 'nullable|string',
        ]);

        $this->saveSettings($data);

        return redirect()->route('admin.bsdk-theme')
            ->with('success', 'Theme settings saved successfully!');
    }

    public function applyPreset(string $preset)
    {
        $presets = config('bsdk-presets', []);

        if (!isset($presets[$preset])) {
            return redirect()->route('admin.bsdk-theme')
                ->with('error', 'Preset not found.');
        }

        $settings = $presets[$preset]['settings'];
        $settings['active_preset'] = $preset;
        $this->saveSettings($settings);

        return redirect()->route('admin.bsdk-theme')
            ->with('success', "Preset \"{$presets[$preset]['name']}\" applied successfully!");
    }

    public function reset()
    {
        try {
            DB::table('bsdkv1_settings')->truncate();
        } catch (\Exception $e) {
            DB::table('bsdkv1_settings')->delete();
        }

        return redirect()->route('admin.bsdk-theme')
            ->with('success', 'Theme reset to defaults.');
    }

    public function preview()
    {
        $settings = array_merge($this->getDefaults(), $this->getSettings());
        return response()->json($settings);
    }
}
