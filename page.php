<?php get_header(); ?>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article <?php post_class('mb-5'); ?>>
			<h1 class="h2 mb-4"><?php the_title(); ?></h1>
			<div class="content">
				<?php the_content(); ?>
			</div>
		</article>
		<?php comments_template(); ?>
	<?php endwhile; endif; ?>
<?php get_footer(); ?>