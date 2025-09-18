<?php
// 1. Khai báo location 'primary'
add_action('after_setup_theme', function () {
    register_nav_menus([
        'primary' => __('Primary Menu', 'vgtech'),
    ]);
});

// 2. Customizer: chọn 1 menu cụ thể để override location 'primary'
add_action('customize_register', function ($wp_customize) {
    $menus   = wp_get_nav_menus();
    $choices = ['' => __('— Dùng menu gán cho vị trí "Primary" —', 'vgtech')];
    foreach ($menus as $m) { $choices[$m->term_id] = $m->name; }

    $wp_customize->add_section('vgtech_nav', [
        'title'    => __('Chọn menu chính', 'vgtech'),
        'priority' => 30,
    ]);

    $wp_customize->add_setting('vgtech_primary_menu', [
        'default'           => '',
        'sanitize_callback' => function($v){ return $v === '' ? '' : absint($v); },
    ]);

    $wp_customize->add_control('vgtech_primary_menu', [
        'label'   => __('Primary menu (override)', 'vgtech'),
        'section' => 'vgtech_nav',
        'type'    => 'select',
        'choices' => $choices,
    ]);
});

// 3. Helper: lấy args cho wp_nav_menu() theo tuỳ chọn ở trên
function vgtech_get_primary_menu_args(array $overrides = []) {
    $menu_id = get_theme_mod('vgtech_primary_menu', '');

    $args = [
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'navbar-nav ms-auto gap-3', // cho tiện
        'depth'          => 3,
        'fallback_cb'    => false,
        'walker'         => class_exists('\Vgtech\ThemeVgtech\Navigation\Nav') ? new \Vgtech\ThemeVgtech\Navigation\Nav() : null,
    ];

    if ($menu_id !== '') {
        $args['menu'] = (int) $menu_id;
        unset($args['theme_location']);
    }

    // ❶ Loại khóa có giá trị null (tránh đè walker=null)
    $overrides = array_filter(
        $overrides,
        static fn($v) => $v !== null
    );

    // ❷ Nếu vẫn muốn truyền 'walker' từ ngoài, chỉ chấp nhận khi là instance hợp lệ
    if (array_key_exists('walker', $overrides) && !($overrides['walker'] instanceof \Walker_Nav_Menu)) {
        unset($overrides['walker']);
    }

    return array_replace($args, $overrides);
}


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
    }
}, 100);

add_action( 'customize_register', function( $wp_customize ) {
    // Xóa panel "Menus" mặc định
    $wp_customize->remove_panel( 'nav_menus' );
}, 20 );

add_action( 'customize_controls_print_styles', function() {
    echo '<style>#accordion-panel-nav_menus { display:none !important; }</style>';
});