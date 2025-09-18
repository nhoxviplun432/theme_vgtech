<?php
if (!defined('ABSPATH')) exit;

// 1) Tải autoload của Composer
$autoloadPaths = [
    __DIR__ . '/vendor/autoload.php',        // cài trong theme
    WP_CONTENT_DIR . '/vendor/autoload.php', // cài ở wp-content (monorepo)
    ABSPATH . '../vendor/autoload.php',      // kiểu Bedrock
];
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) { require_once $path; break; }
}

// 2) Định nghĩa hằng dùng chung
if (!defined('VGTECH_THEME_DIR')) define('VGTECH_THEME_DIR', trailingslashit(__DIR__));
if (!defined('VGTECH_THEME_URL')) define('VGTECH_THEME_URL', trailingslashit(get_stylesheet_directory_uri()));

// 3) Đọc composer.json → set meta dùng nội bộ
add_action('after_setup_theme', function () {
    $composer_path = get_stylesheet_directory() . '/composer.json';
    if (!file_exists($composer_path)) {
        error_log('[VGTECH THEME] composer.json not found at: ' . $composer_path);
        return;
    }
    $json = file_get_contents($composer_path);
    $composer = json_decode($json, true);
    if (!is_array($composer)) {
        error_log('[VGTECH THEME] composer.json is invalid JSON');
        return;
    }

    $GLOBALS['vgtech_theme_meta'] = [
        'name'    => $composer['extra']['theme-name'] ?? ($composer['name'] ?? 'Vgtech Theme'),
        'version' => $composer['version'] ?? '1.0.0',
        'author'  => $composer['authors'][0]['name'] ?? 'Vgtech Team',
        'desc'    => $composer['description'] ?? '',
    ];
});

// 3b) Đồng bộ header style.css từ composer.json (để WP nhận đúng info trong Appearance)
add_action('admin_init', function () {
    if (!current_user_can('manage_options')) return;

    $meta = $GLOBALS['vgtech_theme_meta'] ?? null;
    if (!$meta) return;

    $style_path = get_stylesheet_directory() . '/style.css';
    if (!file_exists($style_path)) {
        // tạo file rỗng nếu thiếu
        file_put_contents($style_path, "/* */");
    }
    if (!is_writable($style_path)) {
        error_log('[VGTECH THEME] style.css is not writable: ' . $style_path);
        return;
    }

    $new_header = "/*
        \tTheme Name: {$meta['name']}
        \tDescription: {$meta['desc']}
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
        // clear cache theme để WP thấy ngay
        if (function_exists('wp_clean_themes_cache')) wp_clean_themes_cache();
    }
});

// 4) Khởi động App (OOP)
add_action('after_setup_theme', function () {
    // ĐÚNG namespace theo composer.json
    if (class_exists(\Vgtech\ThemeVgtech\App::class)) {
        (new \Vgtech\ThemeVgtech\App())->boot();
    }
});


function vgtech_is_elementor_page() {
    if (function_exists('\Elementor\Plugin')) {
        $post_id = get_queried_object_id();
        return \Elementor\Plugin::$instance->db->is_built_with_elementor($post_id);
    }
    return false;
}


add_action('wp_print_scripts', function () {
    if (is_admin()) return;

    if (!vgtech_is_elementor_page()) {
        // Cẩn thận: chỉ dequeue khi chắc chắn trang này không cần
        $handles = [
            'elementor-frontend',        // Elementor free
            'elementor-common',          // Common
            'elementor-pro-frontend',    // Elementor Pro
            'elementor-sticky',          // ví dụ các module con
        ];
        foreach ($handles as $h) {
            if (wp_script_is($h, 'enqueued')) wp_dequeue_script($h);
        }
        // Giữ lại jQuery & jQuery UI của WP nếu bạn cần draggable/datepicker nội bộ
        // KHÔNG dequeue 'jquery', 'jquery-ui-core', 'jquery-ui-mouse', 'jquery-ui-draggable' nếu site bạn dùng.
    }
}, 100);