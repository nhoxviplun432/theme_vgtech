<?php get_header(); ?>
    <header class="mb-4">
        <h1 class="h3 mb-1"><?php the_archive_title(); ?></h1>
        <p class="text-muted"><?php the_archive_description(); ?></p>
    </header>
<?php if ( have_posts() ) : ?>
    <div class="row g-4">
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="col-12 col-md-6 col-lg-4">
                <article id="post-<?php the_ID(); ?>" <?php post_class('card h-100'); ?>>
                    <div class="card-body">
                        <h2 class="h5 card-title"><a class="stretched-link text-decoration-none" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p class="card-text text-body-secondary mb-0"><?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 26 ) ); ?></p>
                    </div>
                </article>
            </div>
        <?php endwhile; ?>
    </div>
    
    <nav class="mt-4" aria-label="<?php esc_attr_e('Posts navigation', 'vgtech-bs5'); ?>">
        <?php the_posts_pagination([
            'class' => 'pagination justify-content-center',
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
        ]); ?>
    </nav>
    <?php else : ?>
        <div class="alert alert-info">
            <?php _e('No posts found.', 'vgtech-bs5'); ?>
        </div>
<?php endif; ?>
<?php get_footer(); ?>
