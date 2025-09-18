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
        $themeVer = wp_get_theme()->get('Version');

        // Bootstrap từ assets/
        wp_enqueue_style('vgtech-bootstrap', VGTECH_THEME_URL . 'assets/bootstrap/css/bootstrap.min.css', [], $themeVer);
        // CSS chính của theme
        wp_enqueue_style('vgtech-style', VGTECH_THEME_URL . 'assets/public/css/vgtech.css', ['vgtech-bootstrap'], rand());
        // JS (đặt ở footer)
        wp_enqueue_script('vgtech-bootstrap', VGTECH_THEME_URL . 'assets/bootstrap/js/bootstrap.bundle.min.js', ['jquery'], $themeVer, true);

        wp_enqueue_script('vgtech-theme',  VGTECH_THEME_URL . 'assets/public/js/vgtech.js', ['jquery', 'vgtech-bootstrap'],rand(), true);
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
