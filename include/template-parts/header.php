<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>

		<!-- Navbar Bootstrap 5 -->
		<header>
			<nav class="navbar navbar-expand-lg navbar-light bg-light">
				<div class="container">
					<a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
						<?php bloginfo('name'); ?>
					</a>

					<button class="navbar-toggler" type="button" data-bs-toggle="collapse"
							data-bs-target="#mainNavbar" aria-controls="mainNavbar"
							aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation'); ?>">
						<span class="navbar-toggler-icon"></span>
					</button>

					<div class="collapse navbar-collapse" id="mainNavbar">
						<?php
						wp_nav_menu([
							'theme_location' => 'primary',
							'menu_class'     => 'navbar-nav ms-auto mb-2 mb-lg-0',
							'container'      => false,
							'walker'         => class_exists('Bootstrap_Navwalker') ? new Vgtech\VgtechTheme\Navigation\BootstrapNavwalker() : null,
						]);
						?>
					</div>
				</div>
			</nav>
		</header>

		<!-- Swup container: mọi template phải render bên trong -->
		<main id="swup" class="transition-fade">
