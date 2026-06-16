<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BsdkSettingsController extends Controller
{
    private function getDefaults(): array
    {
        return [
            'theme_color' => '#df3050',
            'bg_color' => '#0c0a09',
            'text_color' => '#ffffff',
            'sidebar_color' => '#191919',
            'panel_name' => 'BSDK Panel',
            'panel_tagline' => 'Game Server Management',
            'logo_url' => '',
            'favicon_url' => '',
            'background_url' => '',
            'login_background_url' => '',
            'font_family' => 'Poppins',
            'font_mono' => 'JetBrains Mono',
            'glow_enabled' => true,
            'animations_enabled' => true,
            'particles_enabled' => true,
            'gradient_enabled' => true,
            'compact_mode' => false,
            'sidebar_style' => 'modern',
            'card_style' => 'glass',
            'button_style' => 'rounded',
            'header_style' => 'default',
            'footer_enabled' => true,
            'footer_text' => 'Powered by BSDK',
            'breadcrumb_enabled' => true,
            'server_icons_enabled' => true,
            'server_banners_enabled' => true,
            'console_font_size' => '14px',
            'console_max_lines' => '2000',
            'custom_css' => '',
            'custom_js' => '',
            'seo_title' => '',
            'seo_description' => '',
            'seo_keywords' => '',
            'og_image' => '',
            'discord_url' => '',
            'github_url' => '',
            'documentation_url' => '',
            'support_url' => '',
            'pwa_enabled' => false,
            'pwa_name' => '',
            'pwa_short_name' => '',
            'pwa_theme_color' => '',
            'pwa_background_color' => '',
        ];
    }

    public function index()
    {
        $settings = $this->getDefaults();

        try {
            if (Schema::hasTable('bsdkv1_settings')) {
                $rows = DB::table('bsdkv1_settings')->get();
                foreach ($rows as $row) {
                    $settings[$row->setting_key] = $row->setting_value;
                }
            }
        } catch (\Exception $e) {}

        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        foreach ($data as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            DB::table('bsdkv1_settings')->updateOrInsert(
                ['setting_key' => $key],
                ['setting_value' => $value, 'updated_at' => now()]
            );
        }

        return response()->json(['success' => true, 'message' => 'Settings updated']);
    }

    public function reset()
    {
        try {
            DB::table('bsdkv1_settings')->delete();
        } catch (\Exception $e) {}

        return response()->json(['success' => true, 'message' => 'Settings reset to defaults']);
    }
}
