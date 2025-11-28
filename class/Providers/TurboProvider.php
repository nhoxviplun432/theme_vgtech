<?php

namespace Vgtech\ThemeVgtech\Providers;

use Vgtech\ThemeVgtech\Hookable;

class TurboProvider implements Hookable
{
    public function register(): void
    {
        add_action('wp_enqueue_scripts', function () {

            // 1️⃣ Không dùng trong admin
            if (is_admin()) {
                return;
            }

            // 2️⃣ Không dùng khi đang edit bằng Elementor, Flatsome, Builder
            if ($this->isPageBuilderEditing()) {
                return;
            }

            // // 3️⃣ Không dùng cho request đặc biệt (AJAX, REST API)
            // if ($this->isSpecialRequest()) {
            //     return;
            // }

            // ✔ Nếu thoả điều kiện → load Turbo
            $this->enqueue();
        });
    }

    /**
     * Detect Elementor / Flatsome / Page Builders
     */
    private function isPageBuilderEditing(): bool
    {
        // Elementor Editor or Preview
        if (isset($_GET['elementor-preview']) || isset($_GET['action']) && $_GET['action'] === 'elementor') {
            return true;
        }

        // Elementor backend template editor
        if (is_admin() && isset($_GET['post']) && ($_GET['action'] ?? '') === 'elementor') {
            return true;
        }

        // Flatsome UX Builder
        if (isset($_GET['uxb_iframe']) || isset($_GET['ux_builder_editor'])) {
            return true;
        }

        // SiteOrigin / Beaver / Divi
        if (isset($_GET['fl_builder']) || isset($_GET['et_fb'])) {
            return true;
        }

        return false;
    }

    /**
     * Disable Turbo on:
     * - AJAX Requests
     * - REST API Calls
     * - Non-HTML requests (json, xml, feeds)
     */
    private function isSpecialRequest(): bool
    {
        // REST API
        if (defined('REST_REQUEST') && REST_REQUEST) {
            return true;
        }

        // AJAX (admin-ajax OR frontend ajax)
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return true;
        }

        // Feeds
        if (is_feed()) {
            return true;
        }

        // JSON / XML requests
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (str_contains($uri, '.json') || str_contains($uri, '.xml')) {
            return true;
        }

        return false;
    }

    /**
     * Enqueue Turbo + Vite bundle
     */
    public function enqueue(): void
    {
        $theme_dir = get_stylesheet_directory();
        $theme_uri = get_stylesheet_directory_uri();

        $js_path  = '/assets/turbo/js/app.bundle.js';
        $css_path = '/assets/turbo/css/app.bundle.css';

        // JS
        if (file_exists($theme_dir . $js_path)) {
            $handle = 'vgtech-turbo';
            wp_enqueue_script(
                $handle,
                $theme_uri . $js_path,
                [],
                filemtime($theme_dir . $js_path),
                true
            );
            wp_script_add_data($handle, 'data-turbo-track', 'reload');
            wp_add_inline_script($handle, $this->inlineConfig(), 'before');
        }

        // CSS
        if (file_exists($theme_dir . $css_path)) {
            $handle = 'vgtech-turbo-style';
            wp_enqueue_style(
                $handle,
                $theme_uri . $css_path,
                [],
                filemtime($theme_dir . $css_path)
            );
            wp_style_add_data($handle, 'data-turbo-track', 'reload');
        }
    }

    /**
     * Inline config for Turbo
     */
    private function inlineConfig(): string
    {
        return '
            window.vgtechTheme = {
                ajaxUrl: "' . admin_url('admin-ajax.php') . '",
                homeUrl: "' . home_url('/') . '",
                isTurbo: true
            };

            // Tắt Progress Bar của Turbo
            if (window.Turbo) {
                window.Turbo.setProgressBarDelay(-1);
            }
        ';
    }
}
