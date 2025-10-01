<?php
namespace Vgtech\ThemeVgtech\Providers;

class SwupProvider
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue(): void
	{
		if (is_admin()) return;

		// 0) CSS inline cơ bản cho Swup transition + loader
		wp_register_style('vgtech-swup-style', false);
		wp_enqueue_style('vgtech-swup-style');
		wp_add_inline_style('vgtech-swup-style', '
			.transition-fade { opacity: 1; transition: opacity .3s ease; }
			html.is-leaving .transition-fade { opacity: 0; }
			#swup-loader { position:fixed; inset:0; display:none; align-items:center; justify-content:center; background:rgba(255,255,255,.92); z-index:9999; }
			#swup-loader img { width:64px; height:64px; }
			#swup-loader.active { display:flex; }
		');

		$theme_dir   = get_stylesheet_directory();
		$theme_uri   = get_stylesheet_directory_uri();

		$css_rel     = '/assets/swup/css/app.bundle.css';
		$js_rel      = '/assets/swup/js/app.bundle.js';   // file bundle do Vite build
		$loader_rel  = '/assets/swup/app.loader.js';      // fallback loader (chưa build)
		$loader_gif  = '/assets/public/media/loading.gif';

		$css_path    = $theme_dir . $css_rel;
		$js_path     = $theme_dir . $js_rel;
		$loader_path = $theme_dir . $loader_rel;

		// 1) Nếu đã build bằng Vite → load bundle local
		if (file_exists($js_path)) {
			if (file_exists($css_path)) {
				wp_enqueue_style(
					'vgtech-swup',
					$theme_uri . $css_rel,
					[],
					filemtime($css_path)
				);
			}

			wp_enqueue_script(
				'vgtech-swup',
				$theme_uri . $js_rel,
				[],
				filemtime($js_path),
				true
			);

			// Gắn type="module" cho bundle
			add_filter('script_loader_tag', function ($tag, $handle, $src) {
				if ($handle === 'vgtech-swup') {
					return '<script type="module" src="' . esc_url($src) . '"></script>';
				}
				return $tag;
			}, 10, 3);

			wp_localize_script('vgtech-swup', 'vgtechTheme', [
				'loader' => $theme_uri . $loader_gif,
			]);

			return; // Ưu tiên dùng bundle, không cần fallback
		}

		// 2) Nếu chưa có bundle → fallback: CDN Swup + loader riêng
		if (file_exists($loader_path)) {
			wp_enqueue_script(
				'swup',
				'https://cdn.jsdelivr.net/npm/swup@4/dist/swup.min.js',
				[],
				null,
				true
			);
			wp_script_add_data('swup', 'defer', true);

			wp_enqueue_script(
				'vgtech-swup-loader',
				$theme_uri . $loader_rel,
				['swup'],
				filemtime($loader_path),
				true
			);
			wp_script_add_data('vgtech-swup-loader', 'defer', true);

			wp_localize_script('vgtech-swup-loader', 'vgtechTheme', [
				'loader' => $theme_uri . $loader_gif,
			]);
		}
	}

}
