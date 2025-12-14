<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>

	<!-- Zapis schema dla wpisow blogowych / Schema markup for posts -->
	<?php /* if (is_singular('post') && function_exists('tsf')) :
		get_template_part( 'template-parts/schema/schema.php', 'post' ); // needs further edits on the file itself
	endif; */ ?>

	<!-- Zapis schema dla strony glownej - typ Localbusiness / Schema for homepage - LocalBusiness -->
	<?php /* if (is_page_template('template-kontakt.php') && function_exists('tsf')) : ?>
		get_template_part( 'template-parts/schema/schema.php', 'business' ); // needs further edits on the file itself
	<?php endif; */ ?>

</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Go to content', 'wg-blank' ); ?></a>

	<header id="masthead" class="site__header">

		<?php the_custom_logo(); ?>

		<nav class="top-navigation">
			<?php wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'menu_id' => 'primary-menu-header',
			)); ?>
		</nav><!-- #site-navigation -->

	</header><!-- #masthead -->
