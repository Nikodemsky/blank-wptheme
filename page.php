<?php get_header(); ?>

<main id="primary" class="site-main">

	<!-- Page content / Zawartosc podstrony -->
	<section id="zawartosc" class="default-page-wrapper">

		<div class="container">

			<article>
				<h1 class="page-title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</article>

			<div class="dp-goback-wrapper">
				<a href="<?php echo get_home_url(); ?>" class="default-btn"><span><?php _e('Home','wg-blank'); ?></span></a>
			</div>

		</div>

	</section>

</main><!-- #main -->

<?php
get_footer();