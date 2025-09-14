<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>

	<!-- Zapis schema dla wpisow blogowych / Schema markup for posts -->
	<?php if (is_singular('post') && function_exists('tsf')) : ?>
	<script type="application/ld+json">
	{
	"@context": "https://schema.org",
	"@type": "Article",
	"headline": "<?php echo single_post_title(); ?>",
	"description": "<?php echo the_seo_framework()->get_description(); ?>",
	"image": "<?php echo get_the_post_thumbnail_url( '', 'full' ); ?>",  
	"author": {
		"@type": "Organization",
		"name": " Business / Author name",
		"url": "https://website-url.com"
	},  
	"publisher": {
		"@type": "Organization",
		"name": "Business",
		"logo": {
		"@type": "ImageObject",
		"url": "<?php if (has_custom_logo()) : echo esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] ); endif; ?>"
		}
	},
	"datePublished": "<?php echo get_the_date('d.m.Y'); ?>",
	"dateModified": "<?php echo the_modified_date('d.m.Y'); ?>"
	}
	</script>
	<?php endif; ?>

	<!-- Zapis schema dla strony glownej - typ Localbusiness / Schema for homepage - LocalBusiness -->
	<?php /* if (is_page_template('template-kontakt.php') && function_exists('tsf')) ?>
	<script type="application/ld+json">
	{
	"@context": "https://schema.org",
	"@type": "Attorney",
	"name": "Business name",
	"image": "https://url-image.com",
	"@id": "",
	"url": "https://website-url.com",
	"telephone": "+48111222333",
	"address": {
		"@type": "PostalAddress",
		"streetAddress": "example street 1/2",
		"addressLocality": "City",
		"postalCode": "11-222",
		"addressCountry": "PL"
	},
	"geo": {
		"@type": "GeoCoordinates",
		"latitude": 1,
		"longitude": 2
	} ,
	"sameAs": [
		"https://www.facebook.com/",
		"https://www.instagram.com/",
		"https://www.youtube.com/",
		"https://www.linkedin.com/"
	] 
	}
	</script>
	<?php endif; */ ?>

</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Przejdź do zawartości', 'wg-blank' ); ?></a>

	<header id="masthead" class="site-header">

		<?php the_custom_logo(); ?>

		<nav id="site-navigation" class="main-navigation">
			<?php wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'menu_id' => 'primary-menu',
			)); ?>
		</nav><!-- #site-navigation -->

	</header><!-- #masthead -->
