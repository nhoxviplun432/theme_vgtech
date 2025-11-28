<?php

namespace Vgtech\ThemeVgtech\Providers;

use Vgtech\ThemeVgtech\Hookable;

final class ThemeSupportAndChildProvider implements Hookable
{
    public function register(): void
    {
        // -------------------------------
        // 1) Theme Supports
        // -------------------------------
        add_action('after_setup_theme', function () {
            add_theme_support('title-tag');
            add_theme_support('post-thumbnails');
            add_theme_support('html5', [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption'
            ]);
        });

        // -------------------------------
        // 2) Child Theme Workflow
        // -------------------------------
        add_action('wp_enqueue_scripts', [$this, 'enqueueChildTheme'], 20);
    }

    /**
     * Load Parent Theme + Child Theme CSS theo chuẩn WordPress
     */
    public function enqueueChildTheme(): void
    {
        // Lấy "Template" từ style.css (slug của theme cha)
        $parent_slug = wp_get_theme()->get('Template');

        // Nếu có parent theme → load parent style.css
        if (!empty($parent_slug)) {
            $parent_theme = wp_get_theme($parent_slug);

            if ($parent_theme->exists()) {
                $parent_style_uri  = $parent_theme->get_stylesheet_directory_uri() . '/style.css';
                $parent_style_path = $parent_theme->get_stylesheet_directory() . '/style.css';

                wp_enqueue_style(
                    'vgtech-parent-style',
                    $parent_style_uri,
                    [],
                    file_exists($parent_style_path)
                        ? filemtime($parent_style_path)
                        : $parent_theme->get('Version')
                );
            }
        }

        // Luôn load child theme style.css
        $child_style_uri  = get_stylesheet_directory_uri() . '/style.css';
        $child_style_path = get_stylesheet_directory() . '/style.css';

        wp_enqueue_style(
            'vgtech-child-style',
            $child_style_uri,
            $parent_slug ? ['vgtech-parent-style'] : [],
            file_exists($child_style_path)
                ? filemtime($child_style_path)
                : wp_get_theme()->get('Version')
        );
    }
}
