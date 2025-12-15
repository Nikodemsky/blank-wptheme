<?php
function set_post_type_globals() {
    global $wpdb, $blog_has_posts, $awards_exists, $people_exist;
    
    // Check if WPML is active
    $wpml_active = defined('ICL_LANGUAGE_CODE');
    $current_lang = $wpml_active ? apply_filters('wpml_current_language', null) : 'default';
    $cache_key = 'post_type_globals_' . $current_lang;
    
    $cached_results = wp_cache_get($cache_key, 'post_type_globals');
    
    if (false === $cached_results) {
        $cached_results = ['post' => 0, 'award' => 0, 'osoba' => 0];
        
        foreach (['post', 'award', 'osoba'] as $post_type) {
            if ($wpml_active) {
                // Optimized WPML query - only check if exists
                $exists = $wpdb->get_var($wpdb->prepare("
                    SELECT 1
                    FROM {$wpdb->posts} p
                    INNER JOIN {$wpdb->prefix}icl_translations t 
                        ON p.ID = t.element_id 
                        AND t.element_type = %s
                    WHERE p.post_type = %s
                    AND p.post_status = 'publish'
                    AND t.language_code = %s
                    LIMIT 1
                ", 'post_' . $post_type, $post_type, $current_lang));
            } else {
                // Non-WPML query - only check if exists
                $exists = $wpdb->get_var($wpdb->prepare("
                    SELECT 1
                    FROM {$wpdb->posts}
                    WHERE post_type = %s
                    AND post_status = 'publish'
                    LIMIT 1
                ", $post_type));
            }
            
            $cached_results[$post_type] = $exists ? 1 : 0;
        }
        
        wp_cache_set($cache_key, $cached_results, 'post_type_globals', 300);
    }
    
    $blog_has_posts = $cached_results['post'];
    $awards_exists = $cached_results['award'];
    $people_exist = $cached_results['osoba'];
}

function clear_post_type_globals_cache($post_id) {
    $post_type = get_post_type($post_id);
    
    if (!in_array($post_type, ['post', 'award', 'osoba'])) {
        return;
    }
    
    // Clear default cache
    wp_cache_delete('post_type_globals_default', 'post_type_globals');
    
    // Clear WPML language caches
    if (defined('ICL_LANGUAGE_CODE')) {
        $active_languages = apply_filters('wpml_active_languages', null);
        if (is_array($active_languages)) {
            foreach ($active_languages as $lang_code => $language) {
                wp_cache_delete('post_type_globals_' . $lang_code, 'post_type_globals');
            }
        }
    }
}

add_action('save_post', 'clear_post_type_globals_cache');
add_action('delete_post', 'clear_post_type_globals_cache');
add_action('wp_trash_post', 'clear_post_type_globals_cache');
add_action('untrash_post', 'clear_post_type_globals_cache');
add_action('init', 'set_post_type_globals');
