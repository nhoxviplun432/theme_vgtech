<?php
namespace Vgtech\ThemeVgtech\Providers;

use Vgtech\ThemeVgtech\Hookable;

class AssetsProvider implements Hookable
{
    public function register(): void
    {

        add_filter('script_loader_tag', [$this, 'addSwupAttributes'], 10, 3);
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue(): void {
        $theme_ver  = wp_get_theme()->get('Version');
        $theme_url  = trailingslashit( get_stylesheet_directory_uri() );
        $theme_path = trailingslashit( get_stylesheet_directory() );

        // Bootstrap (nếu bạn muốn vẫn dùng)
        wp_enqueue_style(
            'vgtech-bootstrap',
            $theme_url . 'assets/bootstrap/css/bootstrap.min.css',
            [],
            $theme_ver
        );

        // CSS custom riêng (vgtech.css)
        $css_rel  = 'src/css/vgtech.css';
        $css_url  = $theme_url . $css_rel;
        $css_path = $theme_path . $css_rel;
        $css_ver  = file_exists($css_path) ? filemtime($css_path) : $theme_ver;

        wp_enqueue_style(
            'vgtech-style',
            $css_url,
            [], // ← để [] nếu bạn muốn file này độc lập, không phụ thuộc bootstrap
            $css_ver
        );

        // Bootstrap Icons
        wp_enqueue_style(
            'bootstrap-icons',
            'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css',
            [],
            '1.11.3'
        );

        // Bootstrap JS
        wp_enqueue_script(
            'vgtech-bootstrap',
            $theme_url . 'assets/bootstrap/js/bootstrap.bundle.min.js',
            [],
            $theme_ver,
            true
        );

        // JS custom riêng (vgtech.js)
        $js_rel  = 'src/js/vgtech.js';
        $js_url  = $theme_url . $js_rel;
        $js_path = $theme_path . $js_rel;
        $js_ver  = file_exists($js_path) ? filemtime($js_path) : $theme_ver;

        wp_enqueue_script(
            'vgtech-theme',
            $js_url,
            ['vgtech-bootstrap'], // ← để [] nếu bạn muốn JS này độc lập
            $js_ver,
            true
        );
    }



    public function addSwupAttributes($tag, $handle, $src)
    {
        $handles_to_persist = [
            'vgtech-swup',              // bundle của bạn
            'wc-order-attribution',     // WooCommerce Order Attribution
        ];

        if (in_array($handle, $handles_to_persist, true)) {
            $tag = str_replace(
                '<script ',
                '<script data-swup-persist data-swup-ignore-script ',
                $tag
            );

            // riêng vgtech-swup thì thêm type="module"
            if ($handle === 'vgtech-swup') {
                $tag = str_replace(
                    '<script ',
                    '<script type="module" ',
                    $tag
                );
            }
        }

        // bắt thêm script WooCommerce nếu không chắc handle
        if (strpos($src, 'order-attribution') !== false) {
            $tag = str_replace('<script ', '<script data-swup-persist data-swup-ignore-script ', $tag);
        }

        return $tag;
    }
}
