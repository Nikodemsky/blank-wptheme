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

/*********** OPTIMIZATIONS ***********/

// Remove gutenberg styles
function smartwp_remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-blocks-style' );
} 
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );

function dequeue_gutenberg_assets() {
    wp_dequeue_script('wp-editor');
}
add_action('wp_enqueue_scripts', 'dequeue_gutenberg_assets', 100);

// Disable emojis
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );    
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );  
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    
    // Remove from TinyMCE
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}

// Removes comments completely
/*add_action('admin_init', function () {

    global $pagenow;
    
    if ($pagenow === 'edit-comments.php' || $pagenow === 'options-discussion.php') {
        wp_redirect(admin_url());
        exit;
    }

    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

add_filter('commenwgblank_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
add_filter('commenwgblank_array', '__return_empty_array', 10, 2);

add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
    remove_submenu_page('options-general.php', 'options-discussion.php');
});

add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_commenwgblank_menu', 60);
    }
});

function remove_comments(){
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'remove_comments' );*/

// Disable oEmbed on the website but keep it enabled for external platforms
function disable_oembed_on_site() {
    if (!is_admin()) {
        remove_filter( 'the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );
        remove_action( 'rest_api_init', 'wp_oembed_register_route' );
        add_filter( 'embed_oembed_discover', '__return_false' );
        add_filter( 'embed_preview', '__return_false' );
    }
}
add_action( 'init', 'disable_oembed_on_site' );

// Disable RSS feeds completely  
add_action('do_feed', 'wp_disable_feeds', 1);
add_action('do_feed_rdf', 'wp_disable_feeds', 1);
add_action('do_feed_rss', 'wp_disable_feeds', 1);
add_action('do_feed_rss2', 'wp_disable_feeds', 1);
add_action('do_feed_atom', 'wp_disable_feeds', 1);
add_action('do_feed_rss2_comments', 'wp_disable_feeds', 1);
add_action('do_feed_atom_comments', 'wp_disable_feeds', 1);

function wp_disable_feeds() {
    wp_die( __('No feeds available!') );
}

// Remove Widgets
function remove_widget_support() {
    remove_theme_support( 'widgets-block-editor' );
    remove_theme_support( 'widgets' );
}
add_action( 'after_setup_theme', 'remove_widget_support' );

// Disable Really Simple Discovery
remove_action( 'wp_head', 'rsd_link' );

// Remove Wordpress shortlink
remove_action('wp_head', 'wp_shortlink_wp_head', 10);

// Remove jQuery Migrate
function remove_jquery_migrate( $scripts ) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
            $script = $scripts->registered['jquery'];
    if ( $script->deps ) { 
    // Check whether the script has any dependencies
    $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
}}}
add_action( 'wp_default_scripts', 'remove_jquery_migrate' );

// Remove admin dashboard widgets
function remove_all_dashboard_widgets() {

    // Remove Welcome Panel
    remove_action( 'welcome_panel', 'wp_welcome_panel' );

    // Remove Widgets
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); // Quick Draft
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' ); // Activity
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); // WordPress News
    remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' ); // Site Health
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' ); // At a Glance
}
add_action( 'wp_dashboard_setup', 'remove_all_dashboard_widgets' );

// Remove REST API links from header
function remove_json_api () {
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
}
add_action( 'after_setup_theme', 'remove_json_api' );

// Disable self-pingbacks
function no_self_ping( &$links ) {
    $home = get_option( 'home' );
    foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, $home ) )
            unset($links[$l]);
}
add_action( 'pre_ping', 'no_self_ping' );

// Disable built-in sitemap
add_filter('wp_sitemaps_enabled', '__return_false');

// Disable Help tabs in admin
add_filter( 'contextual_help', 'mytheme_remove_help_tabs', 999, 3 );
function mytheme_remove_help_tabs($old_help, $screen_id, $screen){
    $screen->remove_help_tabs();
    return $old_help;
}

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Modify heartbeat interval (in seconds)
add_filter( 'heartbeat_settings', 'modify_heartbeat_settings' );
function modify_heartbeat_settings( $settings ) {
    $settings['interval'] = 60; // 60 seconds instead of default 15
    return $settings;
}

/*********** CUSTOM STYLING ***********/

// Template styles
function wg_styles() {

    // Globals
    $theme_dir = get_stylesheet_directory_uri();

    wp_register_style( 'css-normalize-substrate-system', $theme_dir . '/assets/css/css-normalize.css', array(), '03.2025' ); 
    wp_register_style( 'utilities', $theme_dir . '/assets/css/utilities.css', array(), '1.1' );
    wp_register_style( 'wg-css', $theme_dir . '/assets/css/wg.css', array(), '1.00' ); 
    wp_register_style( 'responsive-767', $theme_dir . '/assets/css/responsive-767.css', array(), '1.00' ); 
    wp_register_style( 'responsive-1024', $theme_dir . '/assets/css/responsive-1024.css', array(), '1.00' ); 
    //wp_register_style( 'responsive-1279', $theme_dir . '/responsive-1279.css', array(), '1.00' ); 
    
    wp_enqueue_style( 'css-normalize-substrate-system' );
    wp_enqueue_style( 'utilities' );
    wp_enqueue_style( 'wg-css' );
    wp_enqueue_style( 'responsive-767' );
    wp_enqueue_style( 'responsive-1024' );
    //wp_enqueue_style( 'responsive-1279' );

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
    unset($sizes['medium_large']);
    unset($sizes['large']);
    unset($sizes['thumbnail']);
    
    return $sizes;
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

// ACF options page v2
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

    // Smoothscroll
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

// Change image process engine
/*function wpb_image_editor_default_to_gd( $editors ) {
    $gd_editor = 'WP_Image_Editor_GD';
    $editors = array_diff( $editors, array( $gd_editor ) );
    array_unshift( $editors, $gd_editor );
    return $editors;
}
add_filter( 'wp_image_editors', 'wpb_image_editor_default_to_gd' );*/

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




