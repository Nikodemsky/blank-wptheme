<?php get_header(); ?>

<main id="primary" class="site__main">

	<!-- Page content / Zawartosc podstrony -->
	<section class="default-page-wrapper">

		<div class="container">

			<article>
				<h1 class="page-title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</article>

			<div class="default-page-wrapper__goback">
				<a href="<?php echo get_home_url(); ?>" class="default-btn"><span><?php _e('Home','wg-blank'); ?></span></a>
			</div>

		</div>

	</section>

</main><!-- #main -->

<?php
get_footer();
