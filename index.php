<?php get_header(); ?>

	<main class="site__main">

		<?php if ( have_posts() ) :  while ( have_posts() ) : the_post();
			//get_template_part( 'template-parts/content', get_post_type() );
		endwhile;
	
			//the_posts_navigation();
            //wp_pagenavi();

		else :
			esc_html_e( 'No posts found.', 'wg-blank' );
		endif; ?>

	</main><!-- #main -->

<?php
get_footer();
