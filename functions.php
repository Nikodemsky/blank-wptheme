<?php

/*********** CORE DIRECTIVES - DO NOT MODIFY ***********/

if(!defined('_S_VERSION')){define('_S_VERSION','1.0.0');}

require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/theme-support.php';

/*********** SECURITY - HARDENING ***********/

require get_template_directory() . '/inc/security-hardening.php';

/*********** IMAGE SIZES HANDLING ***********/

require get_template_directory() . '/inc/image-sizes.php';

/*********** HELPERS - LOGIN PAGE AND EDITOR ADDONS ***********/

// Custom login page 
function login_stylesheet() {
  wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/assets/login/login.css' );
}
add_action( 'login_enqueue_scripts', 'login_stylesheet' );

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





