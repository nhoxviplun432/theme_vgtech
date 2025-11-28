<?php
// Không cho truy cập trực tiếp
if (!defined('ABSPATH')) exit;

$autoloadPaths = [
    __DIR__ . '/vendor/autoload.php',
    WP_CONTENT_DIR . '/vendor/autoload.php',
    ABSPATH . '/vendor/autoload.php',
];

foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        require_once $path;
        break;
    }
}

(new \Vgtech\ThemeVgtech\App())->boot();