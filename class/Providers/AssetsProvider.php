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

        // Bootstrap 5.0.2 CSS
        wp_enqueue_style(
            'vgtech-bootstrap-css',
            $theme_url . 'assets/bootstrap/css/bootstrap.min.css',
            [],
            '5.0.2' // ghi rõ version BS để dễ kiểm tra
        );

        // CSS custom (cache-busting theo filemtime)
        $css_rel  = 'src/css/vgtech.css';
        $css_url  = $theme_url . $css_rel;
        $css_path = $theme_path . $css_rel;
        $css_ver  = file_exists($css_path) ? filemtime($css_path) : $theme_ver;

        wp_enqueue_style('vgtech-style', $css_url, ['vgtech-bootstrap-css'], $css_ver);

        // Bootstrap Icons (không cần JS)
        wp_enqueue_style(
            'bootstrap-icons',
            'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css',
            [],
            '1.11.3'
        );

        // Bootstrap 5.0.2 JS (bundle có Popper)
        wp_enqueue_script(
            'vgtech-bootstrap-js',
            $theme_url . 'assets/bootstrap/js/bootstrap.bundle.min.js',
            [],           // BS5 không phụ thuộc jQuery
            '5.0.2',
            true
        );

        // JS custom (phụ thuộc BS5 để chắc chắn load sau)
        $js_rel  = 'src/js/vgtech.js';
        $js_url  = $theme_url . $js_rel;
        $js_path = $theme_path . $js_rel;
        $js_ver  = file_exists($js_path) ? filemtime($js_path) : $theme_ver;

        wp_enqueue_script(
            'vgtech-theme',
            $js_url,
            ['vgtech-bootstrap-js'],
            $js_ver,
            true
        );


        // (tùy chọn) defer cho JS custom nếu bạn muốn
        wp_script_add_data('vgtech-theme', 'strategy', 'defer');
    }




    public function addSwupAttributes($tag, $handle, $src)
    {
        $persist_handles = [
            'vgtech-swup',          // swup bundle
            'wc-order-attribution', // Woo attribution
        ];

        // Cho các script cần persist
        if (in_array($handle, $persist_handles, true)) {
            $tag = str_replace(
                '<script ',
                '<script data-swup-persist data-swup-ignore-script ',
                $tag
            );
        }

        // Riêng file JS theme của bạn → type="module"
        if ($handle === 'vgtech-theme') {
            $tag = str_replace(
                '<script ',
                '<script type="module" ',
                $tag
            );
        }

        // Bắt thêm order-attribution nếu load với handle khác
        if (strpos($src, 'order-attribution') !== false) {
            $tag = str_replace(
                '<script ',
                '<script data-swup-persist data-swup-ignore-script ',
                $tag
            );
        }

        return $tag;
    }
}
