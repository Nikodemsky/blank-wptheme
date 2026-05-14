<?php

/*********** CORE DIRECTIVES - DO NOT MODIFY ***********/

// Custom defines
if (!defined('THEME_DIR')){ define('THEME_DIR', get_template_directory()); }
if (!defined('THEME_DIR_URI')) { define('THEME_DIR_URI', get_template_directory_uri()); } 

// Global version
if(!defined('_S_VERSION')){define('_S_VERSION','1.0.0');}

// Modules, helpers and additional defines
require THEME_DIR . '/inc/template-tags.php'; // Helpers, wp_body_open
require THEME_DIR . '/inc/customizer.php'; // Customizer support
require THEME_DIR . '/inc/theme-support.php'; // add_theme_support
require THEME_DIR . '/inc/security-hardening.php'; // Security - hardening
require THEME_DIR . '/inc/image-sizes.php'; // Image sizes handling
// require get_template_directory() . '/inc/exists-checks.php'; // Custom, cached checks for post existence
if (class_exists( 'ACF' )) { require THEME_DIR . '/inc/acf-sanitization.php'; } // ACF sanitization helper functions

/*********** HELPERS - LOGIN PAGE AND EDITOR ADDONS ***********/

// Custom login page 
function login_stylesheet() {
  wp_enqueue_style( 'custom-login', THEME_DIR_URI . '/assets/login/login.css' );
}
add_action( 'login_enqueue_scripts', 'login_stylesheet' );

/*********** CUSTOM STYLING ***********/

// Template styles
function wg_styles() {

    // Load compiled styles
    wp_register_style( 'main-css', THEME_DIR_URI . '/assets/css/main.min.css', array(), '1.00' );
    wp_enqueue_style( 'main-css' );

    // Inits and imports
    wp_enqueue_script( 'main', THEME_DIR_URI .'/assets/js/main.min.js', array(), '1.00', array( 'strategy' => 'defer', 'in-footer' => true));

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
/* if ( class_exists( 'Radio_Buttons_For_Taxonomies' ) ) {
    add_filter( 'radio_buttons_for_taxonomies_no_term_grupa', '__return_false' );
} */

// Register custom nav menus
/*function add_nav_menus() {
    register_nav_menus( array(
        'header-helpers'=> __( 'Helper menu - header', 'wg-blank' ),
        'footer-menu'=> __( 'Footer menu', 'wg-blank' ),
    ));
}
add_action('init', 'add_nav_menus');*/

// Remove <p> and <br/> from Contact Form 7
/* if ( class_exists( 'WPCF7' ) ) {
    add_filter('wpcf7_autop_or_not', '__return_false');
} */
