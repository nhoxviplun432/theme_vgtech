<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="swup-loader" aria-hidden="true">
  <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/public/media/loading.gif' ); ?>" alt="Loading...">
</div>

<header class="border-0 bg-transparent">
    <nav class="navbar py-3">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand m-0 p-0" href="<?php echo esc_url(home_url('/')); ?>">
                <?php
                if (function_exists('the_custom_logo') && has_custom_logo()) {
                    the_custom_logo();
                } else {
                    bloginfo('name');
                }
                ?>
            </a>

            <button class="btn p-2 border-0" type="button"
                    data-bs-toggle="modal" data-bs-target="#vgtechMenuModal"
                    aria-label="<?php esc_attr_e('Open menu'); ?>">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <rect x="3" y="6" width="18" height="2" rx="1"></rect>
                    <rect x="3" y="11" width="18" height="2" rx="1"></rect>
                    <rect x="3" y="16" width="18" height="2" rx="1"></rect>
                </svg>
            </button>
        </div>
    </nav>
</header>

<!-- Modal fullscreen menu (nằm ngoài #swup nhưng bên trong <body>) -->
<div class="modal fade" id="vgtechMenuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-light">
            <div class="modal-body d-flex flex-column justify-content-center align-items-center text-center">
                <?php
                $walker = null;
                if ( class_exists('\Vgtech\ThemeVgtech\Navigation\Nav') ) {
                    $walker = new \Vgtech\ThemeVgtech\Navigation\Nav();
                } else {
                    error_log('[VGTECH] Walker class not found: \\Vgtech\\ThemeVgtech\\Navigation\\Nav');
                }

                // Nếu chưa có helper vgtech_get_primary_menu_args(), có thể truyền mảng trực tiếp cho wp_nav_menu.
                echo wp_nav_menu( function_exists('vgtech_get_primary_menu_args') ? vgtech_get_primary_menu_args([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'navbar-nav list-unstyled d-grid gap-3 fs-3 fw-medium m-0 vgtech-menu',
                    'depth'          => 3,
                    'walker'         => $walker,
                    'echo'           => true,
                ]) : [
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'navbar-nav list-unstyled d-grid gap-3 fs-3 fw-medium m-0 vgtech-menu',
                    'depth'          => 3,
                    'walker'         => $walker,
                    'echo'           => true,
                ] );
                ?>

                <button type="button" class="btn btn-outline-dark mt-5 rounded-4 px-4 py-2 vgtech-theme-close"
                        data-bs-dismiss="modal" aria-label="<?php esc_attr_e('Close'); ?>">
                    <span class="d-inline-flex align-items-center gap-2">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Swup container: MỞ ở đây, ĐÓNG ở footer -->
<main id="swup" class="transition-fade vgtech-container">
