<?php

/*********** CORE DIRECTIVES - DO NOT MODIFY ***********/

if(!defined('_S_VERSION')){define('_S_VERSION','1.0.0');}

require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/customizer.php';

if(!function_exists('wgblank_setup')):function wgblank_setup(){load_theme_textdomain('wg-blank',get_template_directory().'/languages');add_theme_support('automatic-feed-links');add_theme_support('title-tag');add_theme_support('post-thumbnails');register_nav_menus(array('menu-1'=>esc_html__('Main menu','wg-blank'),));add_theme_support('html5',array('search-form','comment-form','comment-list','gallery','caption','style','script',));add_theme_support('custom-background',apply_filters('wgblank_custom_background_args',array('default-color'=>'ffffff','default-image'=>'',)));add_theme_support('customize-selective-refresh-widgets');add_theme_support('custom-logo',array('height'=>250,'width'=>250,'flex-width'=>true,'flex-height'=>true,));}
endif;add_action('after_setup_theme','wgblank_setup');

function wgblank_content_width(){$GLOBALS['content_width'] = apply_filters( 'wgblank_content_width', 1025 );}
add_action( 'after_setup_theme', 'wgblank_content_width', 0 );

function wgblank_scripts(){wp_enqueue_style('wgblank-style',get_stylesheet_uri(),array(),_S_VERSION);wp_style_add_data('wgblank-style','rtl','replace');}
add_action('wp_enqueue_scripts','wgblank_scripts');

/*********** HELPERS ***********/

// Custom login page 
function login_stylesheet() {
  wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/assets/login/login.css' );
}
add_action( 'login_enqueue_scripts', 'login_stylesheet' );

// Removes WP version info from rss
remove_action('wp_head', 'wp_generator');

function my_secure_generator( $generator, $type ) {
	return '';
}
add_filter( 'the_generator', 'my_secure_generator', 10, 2 );

function my_remove_src_version( $src ) {
	global $wp_version;

	$version_str = '?ver='.$wp_version;
	$offset = strlen( $src ) - strlen( $version_str );

	if ( $offset >= 0 && strpos($src, $version_str, $offset) !== FALSE )
		return substr( $src, 0, $offset );

	return $src;
}
add_filter( 'script_loader_src', 'my_remove_src_version' );
add_filter( 'style_loader_src', 'my_remove_src_version' );

// Addon supports
add_theme_support( 'custom-line-height' );

/*********** CUSTOM STYLING ***********/

// Template styles
function wg_styles() {

    // Globals
    $theme_dir = get_stylesheet_directory_uri();

    wp_register_style( 'css-normalize-substrate-system', $theme_dir . '/assets/css/css-normalize.css', array(), '03.2025' ); 
    wp_register_style( 'utilities', $theme_dir . '/assets/css/utilities.css', array(), '1.1' );
    wp_register_style( 'wg-css', $theme_dir . '/assets/css/wg.min.css', array(), '1.00' ); 
    wp_register_style( 'responsive-767', $theme_dir . '/assets/css/responsive-767.min.css', array(), '1.00' ); 
    wp_register_style( 'responsive-1024', $theme_dir . '/assets/css/responsive-1024.min.css', array(), '1.00' );
    
    wp_enqueue_style( 'css-normalize-substrate-system' );
    wp_enqueue_style( 'utilities' );
    wp_enqueue_style( 'wg-css' );
    wp_enqueue_style( 'responsive-767' );
    wp_enqueue_style( 'responsive-1024' );

}
add_action( 'wp_enqueue_scripts', 'wg_styles' );

/*********** CUSTOM FUNCTIONS ***********/

// Removes categories and tags from blogposts
/*function unregister_default_categories_taxonomy() {
    unregister_taxonomy_for_object_type('category', 'post');
    unregister_taxonomy_for_object_type('post_tag', 'post');
}
add_action('init', 'unregister_default_categories_taxonomy');*/

// Remove "no taxonomy" for radio taxonomy plugin
/*add_filter( 'radio_buttons_for_taxonomies_no_term_grupa', '__return_false' );*/

// Custom image sizes
//add_image_size( 'size-1', 150, 150, false );
//add_image_size( 'size-2', 300, 300, true );

// Removes unused image sizes
function disable_core_image_sizes() {
    remove_image_size('medium_large');
    remove_image_size('1536x1536');
    remove_image_size('2048x2048');
}
add_action('init', 'disable_core_image_sizes');

function disable_core_image_sizes_settings($sizes) {
    unset($sizes['medium_large']);
    unset($sizes['1536x1536']);
    unset($sizes['2048x2048']);
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'disable_core_image_sizes_settings');

add_filter('intermediate_image_sizes', function($sizes) {
    return array_diff($sizes, ['medium_large']);
});

// IF ACF
/*function remove_unused_image_sizes_from_acf_wysiwyg($sizes) {

	if (array_filter(['advanced-custom-fields-pro/acf.php', 'advanced-custom-fields/acf.php'], 'is_plugin_active')) :

	    unset($sizes['medium_large']);
	    unset($sizes['large']);
	    unset($sizes['thumbnail']);
	    
	    return $sizes;

	endif;
 
}
add_filter('image_size_names_choose', 'remove_unused_image_sizes_from_acf_wysiwyg');*/

// Register custom nav menus
/*function add_nav_menus() {
    register_nav_menus( array(
        'header-helpers'=> __( 'Helper menu - header', 'wg-blank' ),
        'footer-menu'=> __( 'Footer menu', 'wg-blank' ),
    ));
}
add_action('init', 'add_nav_menus');*/

// ACF options page
//if( function_exists('acf_add_options_page') ) { acf_add_options_page('Opcje witryny'); }

// ACF options page - full version with array
/*add_action('acf/init', 'my_acf_op_init');
function my_acf_op_init() {
    if( function_exists('acf_add_options_page') ) {
        $option_page = acf_add_options_page(array(
            'page_title'    => __('CTA Settings', 'wg-blank'),
            'menu_title'    => __('CTA Settings', 'wg-blank'),
            'menu_slug'     => 'theme-general-settings',
            'capability'    => 'edit_posts',
            'update_button' => __('Save settings', 'wg-blank'),
            'icon_url'      => 'dashicons-megaphone',
            'autoload'      => true,
            'position'      => 45,
        ));
    }
}*/

// Remove <p> and <br/> from Contact Form 7
//add_filter('wpcf7_autop_or_not', '__return_false');

// Custom scripts
function loadk_scripts() {

    // Globals
    //$theme_dir = get_stylesheet_directory_uri();
    //$q_id = get_queried_object_id();

    // Menu toggle
    //wp_enqueue_script( 'menu-toggle', $theme_dir.'/assets/js/menu-toggle.js', array(), '1.0', true );

    // SVG loader - global
    //wp_enqueue_script( 'svg-loader', $theme_dir . '/assets/js/svg-loader.js', array(), '', true );

    // Smoothscroll - only as fallback/alternative to browser's native scroll-behaviour:smooth
    //wp_enqueue_script( 'scroll-to-id', $theme_dir . '/assets/js/scrolltoid.js', array(), '', true );
    /* use as: onclick="smoothScrollToId('your-section-id', 500, 30); return false;" on <a> elements;
    first parameter is ID, second is duration and third is offset */

    // Splide - Home
    /*if (is_home()) {
        wp_enqueue_script( 'splide-js', $theme_dir . '/assets/splide/splide.min.js', array(), '', false );
        wp_enqueue_style( 'splide-css', $theme_dir . '/assets/splide/splide.min.css' );
        wp_enqueue_style( 'splide-theme-css', $theme_dir . '/assets/splide/splide-default.min.css' );
        wp_enqueue_script( 'splide-tm', $theme_dir . '/assets/js/splide-home.js', array(), '', true );
    }*/
    
}
add_action( 'wp_enqueue_scripts', 'loadk_scripts' );

// Custom, cached checks for post existence
/*function set_post_type_globals() {
    
    global $wpdb, $blog_has_posts, $awards_exists, $people_exist;
    
    if (!function_exists('apply_filters') || !has_filter('wpml_current_language')) {
        $cache_key = 'post_type_globals_default';
    } else {
        $current_lang = apply_filters('wpml_current_language', NULL);
        $cache_key = 'post_type_globals_' . $current_lang;
    }
    
    $cached_results = wp_cache_get($cache_key, 'post_type_globals');
    
    if (false === $cached_results) {
        if (!function_exists('apply_filters') || !has_filter('wpml_current_language')) {
            $results = $wpdb->get_results("
                SELECT post_type, COUNT(*) as count
                FROM {$wpdb->posts} 
                WHERE post_type IN ('post', 'award', 'osoba')
                AND post_status = 'publish'
                GROUP BY post_type
            ");
        } else {
            $current_lang = apply_filters('wpml_current_language', NULL);
            $results = $wpdb->get_results($wpdb->prepare("
                SELECT p.post_type, COUNT(p.ID) as count
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->prefix}icl_translations t ON p.ID = t.element_id
                WHERE p.post_type IN ('post', 'award', 'osoba')
                AND p.post_status = 'publish'
                AND t.element_type LIKE CONCAT('post_', p.post_type)
                AND t.language_code = %s
                GROUP BY p.post_type
            ", $current_lang));
        }
        
        $cached_results = ['post' => 0, 'award' => 0, 'osoba' => 0];
        foreach ($results as $result) {
            if ($result->count > 0) {
                $cached_results[$result->post_type] = 1;
            }
        }
        
        wp_cache_set($cache_key, $cached_results, 'post_type_globals', 300);
    }
    
    $blog_has_posts = $cached_results['post'];
    $awards_exists = $cached_results['award'];
    $people_exist = $cached_results['osoba'];
}

function clear_post_type_globals_cache($post_id) {
    $post_type = get_post_type($post_id);
    if (in_array($post_type, ['post', 'award', 'osoba'])) {

        wp_cache_delete('post_type_globals_default', 'post_type_globals');
        
        if (function_exists('apply_filters') && has_filter('wpml_active_languages')) {
            $active_languages = apply_filters('wpml_active_languages', NULL);
            if (is_array($active_languages)) {
                foreach ($active_languages as $lang_code => $language) {
                    wp_cache_delete('post_type_globals_' . $lang_code, 'post_type_globals');
                }
            }
        }
    }
}

add_action('save_post', 'clear_post_type_globals_cache');
add_action('delete_post', 'clear_post_type_globals_cache');
add_action('wp_trash_post', 'clear_post_type_globals_cache');
add_action('untrash_post', 'clear_post_type_globals_cache');

add_action('init', 'set_post_type_globals');*/


