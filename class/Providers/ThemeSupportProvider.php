<?php
namespace Vgtech\ThemeVgtech\Providers;

use Vgtech\ThemeVgtech\Hookable;

final class ThemeSupportProvider implements Hookable
{
    public function register(): void
    {
        add_action('after_setup_theme', function () {
            add_theme_support('title-tag');
            add_theme_support('post-thumbnails');
            add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption']);
        });
    }
}
