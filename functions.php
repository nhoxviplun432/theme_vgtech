<?php
// Không cho truy cập trực tiếp
if (!defined('ABSPATH')) exit;

require_once trailingslashit(__DIR__) . "setting.php";

add_action('wp_print_styles', function () {
    global $wp_styles;
    $h = 'vgtech-style';
    if (isset($wp_styles->registered[$h])) {
        error_log('vgtech-style ver=' . $wp_styles->registered[$h]->ver);
    } else {
        error_log('vgtech-style NOT registered');
    }
}, 9999);