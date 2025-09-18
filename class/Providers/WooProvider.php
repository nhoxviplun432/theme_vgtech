<?php
namespace Vgtech\ThemeVgtech\Providers;

use Vgtech\ThemeVgtech\Hookable;

final class WooProvider implements Hookable
{
    public function register(): void
    {
        if (!class_exists('\WooCommerce')) return;

        add_action('after_setup_theme', function () {
            add_theme_support('woocommerce');
        });
    }
}
