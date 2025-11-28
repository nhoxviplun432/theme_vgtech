<?php

namespace Vgtech\ThemeVgtech\Providers;

use Vgtech\ThemeVgtech\Hookable;

class SettingProvider implements Hookable
{
    /** Chạy toàn bộ theme */
    public function register(): void
    {
        // $this->loadComposer();
        $this->defineConstants();

        add_action('after_setup_theme', [$this, 'bootApp']);

        add_action('after_setup_theme', [$this, 'loadThemeMeta']);
        add_action('admin_init', [$this, 'syncStyleHeader']);
        add_action('login_enqueue_scripts', [$this, 'customLoginLogo']);
        add_action('admin_init', [$this, 'adminSettingParentTheme']);
        add_action('after_switch_theme', [$this, 'onThemeActivated']);

        // admin
        // Đăng ký Customizer Panels / Sections / Settings / Controls
        add_action('customize_register', [$this, 'registerCustomizer']);

    }

    /** =============================
     *  1) Load Composer
     * =============================*/
    private function loadComposer()
    {
        $autoloadPaths = [
            __DIR__ . '/../vendor/autoload.php',
            WP_CONTENT_DIR . '/vendor/autoload.php',
            ABSPATH . '../vendor/autoload.php',
        ];

        foreach ($autoloadPaths as $path) {
            if (file_exists($path)) {
                require_once $path;
                break;
            }
        }
    }

    /** =============================
     *  2) Define Constants
     * =============================*/
    private function defineConstants()
    {
        if (!defined('VGTECH_THEME_DIR')) {
            define('VGTECH_THEME_DIR', trailingslashit(dirname(__DIR__, 1)));
        }
        if (!defined('VGTECH_THEME_URL')) {
            define('VGTECH_THEME_URL', trailingslashit(get_stylesheet_directory_uri()));
        }
    }

    /** =============================
     *  3) Load meta từ composer.json
     * =============================*/
    public function loadThemeMeta()
    {
        $default_meta = [
            'name'    => 'Vgtech Theme',
            'version' => '1.0.0',
            'author'  => 'Vgtech Team',
            'desc'    => '',
            'parent'  => get_option('vgtech_parent_theme', ''),
        ];

        $composer_path = get_stylesheet_directory() . '/composer.json';

        if (!file_exists($composer_path)) {
            $GLOBALS['vgtech_theme_meta'] = $default_meta;
            error_log('[VGTECH THEME] composer.json not found, using default metadata.');
            return;
        }

        $json = file_get_contents($composer_path);
        $composer = json_decode($json, true);

        if (!is_array($composer)) {
            $GLOBALS['vgtech_theme_meta'] = $default_meta;
            error_log('[VGTECH THEME] composer.json invalid JSON, using default metadata.');
            return;
        }

        // Lấy metadata an toàn
        $GLOBALS['vgtech_theme_meta'] = [
            'name'    => $composer['extra']['theme-name'] ?? ($composer['name'] ?? $default_meta['name']),
            'version' => $composer['version'] ?? $default_meta['version'],
            'author'  => $composer['authors'][0]['name'] ?? $default_meta['author'],
            'desc'    => $composer['description'] ?? $default_meta['desc'],
            'parent'  => get_option('vgtech_parent_theme', ''),
        ];
    }


    /** =============================
     *  3b) Sync style.css header
     * =============================*/
    public function syncStyleHeader()
    {
        if (!current_user_can('manage_options')) return;

        $meta = $GLOBALS['vgtech_theme_meta'] ?? null;

        // Fallback nếu meta rỗng
        if (!$meta || !is_array($meta)) {
            $meta = [
                'name'    => 'Vgtech Theme',
                'version' => '1.0.0',
                'author'  => 'Vgtech Team',
                'desc'    => '',
                'parent'  => '',
            ];
            error_log('[VGTECH THEME] No meta loaded, using default metadata.');
        }

        $style_path = get_stylesheet_directory() . '/style.css';

        if (!file_exists($style_path)) {
            if (file_put_contents($style_path, "/* */") === false) {
                error_log('[VGTECH THEME] Cannot create style.css!');
                return;
            }
        }

        if (!is_writable($style_path)) {
            error_log('[VGTECH THEME] style.css not writable: ' . $style_path);
            return;
        }

        // If parent theme selected → add Template line
        $template_line = $meta['parent'] ? "\tTemplate: {$meta['parent']}\n" : "";

        $new_header = "/*
    \tTheme Name: {$meta['name']}
    {$template_line}\tDescription: {$meta['desc']}
    \tAuthor: {$meta['author']}
    \tVersion: {$meta['version']}
    \tRequires PHP: 8.0+
    \tLicense: GNU General Public License v2 or later
    \tLicense URI: https://www.gnu.org/licenses/gpl-2.0.html
    */";

        $existing = file_get_contents($style_path);

        if (preg_match('#^/\*.*?\*/#s', $existing)) {
            $updated = preg_replace('#^/\*.*?\*/#s', $new_header, $existing, 1);
        } else {
            $updated = $new_header . "\n" . $existing;
        }

        if ($updated !== $existing) {
            file_put_contents($style_path, $updated);
            if (function_exists('wp_clean_themes_cache')) {
                wp_clean_themes_cache();
            }
        }
    }

    /** =============================
     *  4) Boot App (OOP)
     * =============================*/
    public function bootApp()
    {
        if (class_exists(\Vgtech\ThemeVgtech\App::class)) {
            (new \Vgtech\ThemeVgtech\App())->boot();
        }
    }

    /** =============================
     *  5) Login Logo
     * =============================*/
    public function customLoginLogo()
    {
        ?>
        <style type="text/css">
            body.login div#login h1 a {
                background-image: url('<?php echo esc_url($this->getCustomLogoUrl()); ?>');
                background-position: center;
                background-repeat: no-repeat;
                background-size: contain;
                height: 78px;
                width: auto;
            }
        </style>
        <?php
    }

    private function getCustomLogoUrl()
    {
        $custom_logo_id = get_theme_mod('custom_logo');
        $image = wp_get_attachment_image_src($custom_logo_id, 'full');
        return $image[0] ?? '';
    }

    public function adminSettingParentTheme(){
        register_setting('general', 'vgtech_parent_theme', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => ''
        ]);

        add_settings_field(
            'vgtech_parent_theme',
            'Theme Parent',
            [$this, 'displayParentThemeField'],
            'general'
        );
    }

    public function displayParentThemeField()
    {
        $themes   = wp_get_themes();
        $current  = wp_get_theme()->get_stylesheet(); // slug theme hiện tại (child)
        $selected = get_option('vgtech_parent_theme', '');

        echo '<select name="vgtech_parent_theme">';
        echo '<option value="">-- Không chọn --</option>';

        foreach ($themes as $slug => $theme) {
            // Loại theme hiện tại (child) khỏi danh sách
            if ($slug === $current) {
                continue;
            }

            printf(
                '<option value="%1$s" %3$s>%2$s (%1$s)</option>',
                esc_attr($slug),
                esc_html($theme->get('Name')),
                selected($selected, $slug, false)
            );
        }

        echo '</select>';
    }

    public function onThemeActivated() {
        $themes = wp_get_themes();
        $current_slug = wp_get_theme()->get_stylesheet();

        // Loại bỏ theme hiện tại khỏi danh sách
        $parent_candidates = array_filter($themes, function($theme) use ($current_slug) {
            return $theme->get_stylesheet() !== $current_slug;
        });

        // -------- Auto select parent theme ----------
        if (!empty($parent_candidates)) {
            $first = reset($parent_candidates);
            update_option('vgtech_parent_theme', $first->get_stylesheet());
        } else {
            update_option('vgtech_parent_theme', '');
        }

        // Redirect admin để user có thể thay đổi lại
        wp_safe_redirect(admin_url('options-general.php'));
    }

    public function registerCustomizer(\WP_Customize_Manager $wp_customize): void
    {
        // ==========================
        // SECTION giống như "CSS bổ sung"
        // (không panel, top-level item)
        // ==========================
        $wp_customize->add_section('vgtech_theme_settings', [
            'title'       => __('Vgtech Theme Settings', 'vgtech'),
            'priority'    => 160, // gần giống Additional CSS (thường là 200)
            'description' => '',
        ]);

        // ==========================
        // 1) Checkbox bật popup loading
        // ==========================
        $wp_customize->add_setting('vgtech_enable_page_loading', [
            'default'           => false,
            'sanitize_callback' => fn($v) => (bool) $v,
            'transport'         => 'refresh',
        ]);

        $wp_customize->add_control('vgtech_enable_page_loading_control', [
            'label'    => __('Hiển thị loading', 'vgtech'),
            'section'  => 'vgtech_theme_settings',
            'settings' => 'vgtech_enable_page_loading',
            'type'     => 'checkbox',
        ]);

        // ==========================
        // 2) Ảnh loading
        // ==========================
        $wp_customize->add_setting('vgtech_loading_image', [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ]);

        $wp_customize->add_control(new \WP_Customize_Image_Control(
            $wp_customize,
            'vgtech_loading_image_control',
            [
                'label'    => __('Icon loading (gif/png/svg)', 'vgtech'),
                'section'  => 'vgtech_theme_settings',
                'settings' => 'vgtech_loading_image',
            ]
        ));

        // ==========================
        // 3) Màu nền popup
        // ==========================
        $wp_customize->add_setting('vgtech_loading_bg_color', [
            'default'           => '#000000',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ]);

        $wp_customize->add_control(new \WP_Customize_Color_Control(
            $wp_customize,
            'vgtech_loading_bg_color_control',
            [
                'label'    => __('Màu nền', 'vgtech'),
                'section'  => 'vgtech_theme_settings',
                'settings' => 'vgtech_loading_bg_color',
            ]
        ));
    }

}