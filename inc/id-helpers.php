<?php

// Helper function to get translated post ID (WPML / Polylang / Bogo)
function get_translated_id(int $post_id, string $post_type = 'page'): int {

    // WPML
    if (has_filter('wpml_object_id')) {
        $translated = apply_filters('wpml_object_id', $post_id, $post_type, true);
        return $translated ? (int) $translated : $post_id;
    }

    // Polylang
    if (function_exists('pll_get_post')) {
        $translated = pll_get_post($post_id);
        return $translated ? (int) $translated : $post_id;
    }

    // Bogo
    if (function_exists('bogo_get_post_translation')) {
        return bogo_translated_id($post_id, $post_type);
    }

    return $post_id;
}

// Bogo: translations are linked by _original_post meta, language is in _locale meta
function bogo_translated_id(int $post_id, string $post_type = 'page'): int {

    // Bogo filters 'locale' on front-end only
    if (is_admin()) {
        return $post_id;
    }

    if (!bogo_is_localizable_post_type($post_type)) {
        return $post_id;
    }

    $locale = get_locale();

    if (bogo_get_post_locale($post_id) === $locale) {
        return $post_id;
    }

    $translation = bogo_get_post_translation($post_id, $locale);

    // Bogo returns translations with any status - skip drafts / private / trashed
    if ($translation && 'publish' === get_post_status($translation)) {
        return (int) $translation->ID;
    }

    return $post_id;
}

// Get ID by page template, with transients support
function get_page_id_by_template(string $template): int {
    static $cache = [];

    if (!isset($cache[$template])) {
        $transient_key = 'tpl_page_' . md5($template);
        $id = get_transient($transient_key);

        if ($id === false) {
            $ids = get_posts([
                'post_type'                  => 'page',
                'post_status'                => 'publish',
                'posts_per_page'             => 1,
                'fields'                     => 'ids',
                'meta_key'                   => '_wp_page_template',
                'meta_value'                 => $template,
                'orderby'                    => 'ID',
                'order'                      => 'ASC',
                'suppress_filters'           => true, // WPML
                'lang'                       => '',   // Polylang
                'bogo_suppress_locale_query' => true, // Bogo
                'no_found_rows'              => true,
            ]);

            $id = $ids ? (int) $ids[0] : 0;

            set_transient($transient_key, $id, WEEK_IN_SECONDS);
        }

        $cache[$template] = (int) $id;
    }

    $id = $cache[$template];

    return $id ? get_translated_id($id, 'page') : 0;
}

// Clearing transients
function clear_page_template_transients(int $post_id): void {

    if (get_post_type($post_id) !== 'page') {
        return;
    }

    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }

    global $wpdb;

    $wpdb->query(
        "DELETE FROM {$wpdb->options}
         WHERE option_name LIKE '\_transient\_tpl\_page\_%'
            OR option_name LIKE '\_transient\_timeout\_tpl\_page\_%'"
    );

    // wp_cache_flush_group('options'); // for object cache systems like Redis or Memcached
}
add_action('save_post_page', 'clear_page_template_transients');
add_action('trashed_post', 'clear_page_template_transients');
add_action('untrashed_post', 'clear_page_template_transients');
add_action('deleted_post', 'clear_page_template_transients');
