<?php
namespace Vgtech\ThemeVgtech\Providers;

class SwupProvider
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    // ... giữ nguyên namespace & class
	public function enqueue(): void
	{
		if (is_admin()) return;

		// CSS transition fade
		wp_register_style('vgtech-swup-style', false);
		wp_enqueue_style('vgtech-swup-style');
		wp_add_inline_style('vgtech-swup-style', '
			.transition-fade { opacity: 1; transition: opacity .3s ease; }
			html.is-leaving .transition-fade { opacity: 0; }
		');

		$theme_dir = get_stylesheet_directory();
		$theme_uri = get_stylesheet_directory_uri();

		$css_rel = '/assets/swup/css/app.bundle.css';
		$js_rel  = '/assets/swup/js/app.bundle.js';
		$loader_rel = '/assets/swup/app.loader.js';

		$css_path   = $theme_dir . $css_rel;
		$js_path    = $theme_dir . $js_rel;
		$loader_path = $theme_dir . $loader_rel;

		// Nếu có bản build local (vite) thì dùng local
		if (file_exists($css_path)) {
			wp_enqueue_style('vgtech-swup', $theme_uri . $css_rel, [], filemtime($css_path));
		}
		if (file_exists($js_path)) {
			wp_enqueue_script('vgtech-swup', $theme_uri . $js_rel, [], filemtime($js_path), true);
			if (function_exists('wp_script_add_data')) wp_script_add_data('vgtech-swup', 'defer', true);
			return;
		}

		// Nếu chưa build local, fallback bằng loader CDN
		if (file_exists($loader_path)) {
			wp_enqueue_script('vgtech-swup-loader', $theme_uri . $loader_rel, [], filemtime($loader_path), true);
		}
	}

}
