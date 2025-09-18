<?php
/**
 * Single template — Bootstrap flavored (Hello-style)
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'site-main container py-5' ); ?>>
        <?php if ( apply_filters( 'vgtech_page_title', true ) ) : ?>
            <header class="page-header mb-4">
                <h1 class="entry-title display-5 fw-semibold mb-2"><?php the_title(); ?></h1>
                <div class="entry-meta text-muted small">
                    <span class="me-3"><i class="bi bi-person"></i> <?php echo esc_html( get_the_author() ); ?></span>
                    <span class="me-3"><i class="bi bi-calendar3"></i> <?php echo esc_html( get_the_date() ); ?></span>
                    <?php if ( has_category() ) : ?>
                        <span class="me-3"><i class="bi bi-folder2"></i> <?php the_category( ', ' ); ?></span>
                    <?php endif; ?>
                </div>
            </header>
        <?php endif; ?>

        <?php if ( has_post_thumbnail() ) : ?>
            <div class="entry-thumbnail mb-4">
                <?php the_post_thumbnail( 'large', ['class' => 'img-fluid rounded-3 w-100'] ); ?>
            </div>
        <?php endif; ?>

        <div class="page-content mb-4">
            <?php
            the_content();
            wp_link_pages( [
                'before'      => '<nav class="entry-pagination my-4" aria-label="'.esc_attr__('Page','vgtech').'"><div class="btn-group" role="group">',
                'after'       => '</div></nav>',
                'link_before' => '<span class="btn btn-outline-secondary">',
                'link_after'  => '</span>',
                'separator'   => '',
            ] );
            ?>
        </div>

        <hr class="my-5">

        <div class="post-navigation d-flex justify-content-between gap-3">
            <div class="prev-post">
                <?php previous_post_link('%link', '&larr; %title', true); ?>
            </div>
            <div class="next-post text-end">
                <?php next_post_link('%link', '%title &rarr;', true); ?>
            </div>
        </div>

        <?php
        if ( comments_open() || get_comments_number() ) :
            echo '<hr class="my-5">';
            comments_template();
        endif;
        ?>
    </article>
<?php endwhile;

get_footer();
