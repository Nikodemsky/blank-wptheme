<?php

/*********** CORE DIRECTIVES - DO NOT MODIFY ***********/

if(!defined('_S_VERSION')){define('_S_VERSION','1.0.0');}

require get_template_directory() . '/inc/template-tags.php'; // Helpers, wp_body_open
require get_template_directory() . '/inc/customizer.php'; // Customizes support
require get_template_directory() . '/inc/theme-support.php'; // add_theme_support
require get_template_directory() . '/inc/security-hardening.php'; // Security - hardening
require get_template_directory() . '/inc/image-sizes.php'; // Image sizes handling
// require get_template_directory() . '/inc/exists-checks.php'; // Custom, cached checks for post existence

/*********** HELPERS - LOGIN PAGE AND EDITOR ADDONS ***********/

// Custom login page 
function login_stylesheet() {
  wp_enqueue_style( 'custom-login', get_template_directory() . '/assets/login/login.css' );
}
add_action( 'login_enqueue_scripts', 'login_stylesheet' );

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

// Register custom nav menus
/*function add_nav_menus() {
    register_nav_menus( array(
        'header-helpers'=> __( 'Helper menu - header', 'wg-blank' ),
        'footer-menu'=> __( 'Footer menu', 'wg-blank' ),
    ));
}
add_action('init', 'add_nav_menus');*/

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


