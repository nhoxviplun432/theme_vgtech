<?php
namespace Vgtech\ThemeVgtech\Providers;

use Vgtech\ThemeVgtech\Hookable;

class AssetsProvider implements Hookable
{
    public function register(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
        add_filter('script_loader_tag', [$this, 'addModuleAttribute'], 10, 3);
    }

    public function enqueue(): void
    {
        $theme_ver  = wp_get_theme()->get('Version');
        $theme_url  = trailingslashit(get_stylesheet_directory_uri());
        $theme_path = trailingslashit(get_stylesheet_directory());

        // =======================
        // 1️⃣ Custom Theme CSS
        // =======================
        $css_rel  = 'src/css/vgtech.css';
        $css_url  = $theme_url . $css_rel;
        $css_path = $theme_path . $css_rel;
        $css_ver  = file_exists($css_path) ? filemtime($css_path) : $theme_ver;

        wp_enqueue_style(
            'vgtech-style',
            $css_url,
            [],
            $css_ver
        );

        // =======================
        // 2️⃣ Turbo JS (Vite build)
        // =======================
        $turbo_js_rel  = 'assets/turbo/js/app.bundle.js';
        $turbo_js_url  = $theme_url . $turbo_js_rel;
        $turbo_js_path = $theme_path . $turbo_js_rel;
        $turbo_js_ver  = file_exists($turbo_js_path) ? filemtime($turbo_js_path) : $theme_ver;

        if (file_exists($turbo_js_path)) {
            wp_enqueue_script(
                'vgtech-turbo',
                $turbo_js_url,
                [],
                $turbo_js_ver,
                true
            );
        }

        // =======================
        // 3️⃣ Custom JS (vgtech.js)
        // =======================
        $js_rel  = 'src/js/vgtech.js';
        $js_url  = $theme_url . $js_rel;
        $js_path = $theme_path . $js_rel;
        $js_ver  = file_exists($js_path) ? filemtime($js_path) : $theme_ver;

        wp_enqueue_script(
            'vgtech-theme',
            $js_url,
            [],   // ❌ không phụ thuộc bootstrap-js nữa
            $js_ver,
            true
        );

        wp_script_add_data('vgtech-theme', 'strategy', 'defer');
    }


    /**
     * Đặt type="module" cho các script hiện đại (Turbo & vgtech)
     */
    public function addModuleAttribute($tag, $handle, $src)
    {
        $module_handles = ['vgtech-theme', 'vgtech-turbo'];

        if (in_array($handle, $module_handles, true)) {
            $tag = str_replace('<script ', '<script type="module" ', $tag);
        }

        return $tag;
    }
}
