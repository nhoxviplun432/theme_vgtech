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

		// 0) CSS transition fade cho Swup container (giữ nguyên)
		wp_register_style('vgtech-swup-style', false);
		wp_enqueue_style('vgtech-swup-style');
		wp_add_inline_style('vgtech-swup-style', '
			.transition-fade { opacity: 1; transition: opacity .3s ease; }
			html.is-leaving .transition-fade { opacity: 0; }
			/* Loader overlay cơ bản */
			#swup-loader { position:fixed; inset:0; display:none; align-items:center; justify-content:center; background:rgba(255,255,255,.92); z-index:9999; }
			#swup-loader img { width:64px; height:64px; }
			/* Nếu bạn dùng class active để fade */
			#swup-loader.active { display:flex; }
		');

		$theme_dir   = get_stylesheet_directory();
		$theme_uri   = get_stylesheet_directory_uri();

		$css_rel     = '/assets/swup/css/app.bundle.css';
		$js_rel      = '/assets/swup/js/app.bundle.js';   // build Vite (đã include Swup + code của bạn)
		$loader_rel  = '/assets/swup/app.loader.js';      // file fallback chỉ có logic loader + init Swup
		$loader_gif  = '/assets/public/media/loading.gif';

		$css_path    = $theme_dir . $css_rel;
		$js_path     = $theme_dir . $js_rel;
		$loader_path = $theme_dir . $loader_rel;

		// 1) Nếu có bản build local (Vite) thì dùng local
		if (file_exists($css_path)) {
			wp_enqueue_style('vgtech-swup', $theme_uri . $css_rel, [], filemtime($css_path));
		}
		if (file_exists($js_path)) {
			// app.bundle.js của bạn nên đã include cả Swup + init
			wp_enqueue_script('vgtech-swup', $theme_uri . $js_rel, [], filemtime($js_path), true);
			if (function_exists('wp_script_add_data')) wp_script_add_data('vgtech-swup', 'defer', true);

			// Truyền URL ảnh loader cho JS (window.vgtechTheme.loader)
			wp_localize_script('vgtech-swup', 'vgtechTheme', [
				'loader' => $theme_uri . $loader_gif,
			]);
			return;
		}

		// 2) Fallback: CHƯA build local → nạp Swup từ CDN + sau đó nạp app.loader.js phụ thuộc Swup
		//    (chỉ chạy khi tồn tại file app.loader.js)
		if (file_exists($loader_path)) {
			// 2.1 Nạp Swup (CDN). Chọn một phiên bản ổn (v4 ổn định). Bạn có thể cố định version.
			wp_enqueue_script(
				'swup',
				'https://cdn.jsdelivr.net/npm/swup@4/dist/swup.min.js',
				[],
				null,
				true
			);
			if (function_exists('wp_script_add_data')) wp_script_add_data('swup', 'defer', true);

			// 2.2 Nạp app.loader.js và phụ thuộc vào 'swup' để đảm bảo Swup có mặt trước.
			wp_enqueue_script(
				'vgtech-swup-loader',
				$theme_uri . $loader_rel,
				['swup'],
				filemtime($loader_path),
				true
			);
			if (function_exists('wp_script_add_data')) wp_script_add_data('vgtech-swup-loader', 'defer', true);

			// 2.3 Truyền URL ảnh loader cho JS
			wp_localize_script('vgtech-swup-loader', 'vgtechTheme', [
				'loader' => $theme_uri . $loader_gif,
			]);
		}
	}


}
