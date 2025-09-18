<?php get_header(); ?>
    <div class="container py-4">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class('mb-4'); ?>>
            <h1 class="h3"><?php the_title(); ?></h1>
            <div><?php the_content(); ?></div>
        </article>
        <?php endwhile; else: ?>
            <p>Chưa có nội dung.</p>
        <?php endif; ?>
    </div>
<?php get_footer(); ?>
    </main>

    <?php wp_footer(); ?>
</body>