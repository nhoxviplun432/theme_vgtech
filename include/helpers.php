<?php


add_filter('script_loader_tag', function($tag, $handle) {
    if ($handle === 'wc-order-attribution') {
        $tag = str_replace('<script ', '<script data-turbo-permanent ', $tag);
    }
    return $tag;
}, 10, 2);

add_action('wp_body_open', function () {

    // ======================================================
    // 0️⃣ BẢO VỆ: Chỉ chạy ở frontend & không phá plugin
    // ======================================================

    // ❌ Không chạy trong admin dashboard
    if (is_admin()) return;

    // ❌ Không chạy trong AJAX (admin-ajax or frontend ajax)
    if (defined('DOING_AJAX') && DOING_AJAX) return;

    // ❌ Không chạy trong REST API
    if (defined('REST_REQUEST') && REST_REQUEST) return;

    // ❌ Không chạy trong RSS/ATOM feed
    if (is_feed()) return;

    // ❌ Không chạy khi đang dùng Page Builders (Elementor, Flatsome…)
    $qs = $_GET ?? [];

    // Elementor Preview mode
    if (isset($qs['elementor-preview'])) return;

    // Elementor Editor
    if (isset($qs['action']) && $qs['action'] === 'elementor') return;

    // Elementor Template Backend Editor
    if (is_admin() && isset($qs['post']) && ($qs['action'] ?? '') === 'elementor') return;

    // Flatsome UX Builder
    if (isset($qs['ux_builder_editor']) || isset($qs['uxb_iframe'])) return;

    // Beaver Builder
    if (isset($qs['fl_builder'])) return;

    // Divi Builder
    if (isset($qs['et_fb'])) return;

    // ❌ Không chạy nếu request là JSON / XML
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (str_contains($uri, '.json') || str_contains($uri, '.xml')) return;


    // ======================================================
    // 1️⃣ Load Customizer Setting (Nếu popup tắt → return)
    // ======================================================

    $enabled = get_theme_mod('vgtech_enable_page_loading', false);
    if (!$enabled) {
        return;
    }

    // Background color
    $bg_color = get_theme_mod('vgtech_loading_bg_color', 'rgba(0,0,0,0.97)');

    // Custom loading image
    $custom_img  = get_theme_mod('vgtech_loading_image', '');
    $default_img = get_stylesheet_directory_uri() . '/assets/public/media/loading.gif';
    $image_url   = $custom_img ?: $default_img;


    // ======================================================
    // 2️⃣ Render Turbo Loader (Frontend Only)
    // ======================================================
    ?>
    <!-- 🔥 Turbo Loader (Frontend Only) -->
    <div 
        id="turbo-loader"
        data-turbo-permanent
        aria-hidden="true"
        hidden
        style="
            background: <?php echo esc_attr($bg_color); ?>;
            position: fixed;
            inset: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
        "
    >
        <img 
            src="<?php echo esc_url($image_url); ?>" 
            alt="Loading..."
            style="max-width: 120px; height: auto;"
        >
    </div>
    <?php
});
