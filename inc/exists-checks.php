<?php
// Guard
defined( 'ABSPATH' ) || exit;

/**
 * Cached exist checks with support for WPML/Polylang
 *
 * Usage
 *   if ( theme_post_type_has_posts( 'award' ) ) { ... } // current language
 *   if ( theme_post_type_has_posts( 'osoba', 'en' ) ) { ... } // specific language
 *   $blog_has_posts // as single variable
 */

// Config
function theme_tracked_post_types() {
	return apply_filters( 'theme_tracked_post_types', array(
		'specialization'  => '',
		'product' => '',
		'person' => '',
		// 'realizacja' => 'projects_exist',
	) );
}

const THEME_PT_FLAGS_KEY = 'theme_pt_flags'; // transient name
const THEME_PT_FLAGS_TTL = 43200; // expiration in seconds (43200 = 12h)

// Multi-language plugins support
function theme_ml_plugin() {
	if ( function_exists( 'pll_current_language' ) ) {
		return 'polylang';
	}
	if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		return 'wpml';
	}
	return '';
}

// Current language slug check
function theme_ml_current_lang() {
	switch ( theme_ml_plugin() ) {
		case 'polylang':
			$lang = pll_current_language( 'slug' );
			break;
		case 'wpml':
			$lang = apply_filters( 'wpml_current_language', null );
			break;
		default:
			$lang = '';
	}

	return ( is_string( $lang ) && '' !== $lang && 'all' !== $lang ) ? $lang : '';
}

// Check if current CPT is translatable
function theme_ml_is_translated( $post_type ) {
	switch ( theme_ml_plugin() ) {
		case 'polylang':
			return function_exists( 'pll_is_translated_post_type' ) && pll_is_translated_post_type( $post_type );
		case 'wpml':
			return (bool) apply_filters( 'wpml_is_translated_post_type', false, $post_type );
	}
	return false;
}

// Array map
function theme_build_post_type_flags( array $types ) {
	global $wpdb;

	$empty = array_fill_keys( $types, false );
	$data  = array( '_all' => $empty );

	if ( ! $types ) {
		return $data;
	}

	$in = implode( ',', array_fill( 0, count( $types ), '%s' ) );

	$found = $wpdb->get_col( $wpdb->prepare(
		"SELECT DISTINCT post_type
		 FROM {$wpdb->posts}
		 WHERE post_status = 'publish'
		   AND post_type IN ($in)",
		$types
	) );

	foreach ( $found as $type ) {
		$data['_all'][ $type ] = true;
	}

	if ( ! $found ) {
		return $data;
	}

	$rows = array();

	switch ( theme_ml_plugin() ) {
		case 'wpml':
			$element_types = array();
			foreach ( $types as $type ) {
				$element_types[] = 'post_' . $type;
			}

			$rows = $wpdb->get_results( $wpdb->prepare(
				"SELECT DISTINCT p.post_type, t.language_code AS lang
				 FROM {$wpdb->posts} p
				 INNER JOIN {$wpdb->prefix}icl_translations t
				         ON t.element_id = p.ID
				        AND t.element_type IN ($in)
				 WHERE p.post_status = 'publish'
				   AND p.post_type IN ($in)",
				array_merge( $element_types, $types )
			) );
			break;

		case 'polylang':
			$rows = $wpdb->get_results( $wpdb->prepare(
				"SELECT DISTINCT p.post_type, t.slug AS lang
				 FROM {$wpdb->posts} p
				 INNER JOIN {$wpdb->term_relationships} tr ON tr.object_id = p.ID
				 INNER JOIN {$wpdb->term_taxonomy} tt
				         ON tt.term_taxonomy_id = tr.term_taxonomy_id
				        AND tt.taxonomy = 'language'
				 INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id
				 WHERE p.post_status = 'publish'
				   AND p.post_type IN ($in)",
				$types
			) );
			break;
	}

	foreach ( $rows as $row ) {
		if ( ! isset( $data[ $row->lang ] ) ) {
			$data[ $row->lang ] = $empty;
		}
		$data[ $row->lang ][ $row->post_type ] = true;
	}

	return $data;
}

// Cache + transient; @param bool $flush true for clearing
function theme_post_type_flags( $flush = false ) {
	static $flags = null;

	if ( $flush ) {
		$flags = null;
		delete_transient( THEME_PT_FLAGS_KEY );
		return array();
	}

	if ( null !== $flags ) {
		return $flags;
	}

	$types = array_keys( theme_tracked_post_types() );
	$sig    = md5( implode( ',', $types ) . '|' . theme_ml_plugin() );
	$cached = get_transient( THEME_PT_FLAGS_KEY );

	if (
		is_array( $cached )
		&& isset( $cached['sig'], $cached['time'], $cached['data'] )
		&& $cached['sig'] === $sig
		&& ( time() - (int) $cached['time'] ) < THEME_PT_FLAGS_TTL
	) {
		$flags = $cached['data'];
		return $flags;
	}

	$flags = theme_build_post_type_flags( $types );

	set_transient( THEME_PT_FLAGS_KEY, array(
		'sig'  => $sig,
		'time' => time(),
		'data' => $flags,
	) );

	return $flags;
}

// Public API
function theme_post_type_has_posts( $post_type, $lang = null ) {
	$flags = theme_post_type_flags();

	if ( null === $lang ) {
		$lang = theme_ml_current_lang();
	}

	if ( '' !== $lang && theme_ml_is_translated( $post_type ) ) {
		return ! empty( $flags[ $lang ][ $post_type ] );
	}

	return ! empty( $flags['_all'][ $post_type ] );
}

// Single value checks
function set_post_type_globals() {
	foreach ( theme_tracked_post_types() as $post_type => $global ) {
		if ( $global ) {
			$GLOBALS[ $global ] = (int) theme_post_type_has_posts( $post_type );
		}
	}
}
add_action( 'wp', 'set_post_type_globals' );

// Cache invalidation
function theme_is_tracked_post_type( $post_type ) {
	return $post_type && array_key_exists( $post_type, theme_tracked_post_types() );
}

// Manual clearing
function clear_post_type_globals_cache() {
	theme_post_type_flags( true );
}

// Checks for status change
function theme_pt_flags_on_transition( $new_status, $old_status, $post ) {
	if (
		( 'publish' === $new_status || 'publish' === $old_status )
		&& theme_is_tracked_post_type( $post->post_type )
	) {
		clear_post_type_globals_cache();
	}
}
add_action( 'transition_post_status', 'theme_pt_flags_on_transition', 10, 3 );

// Removal bypassing the trash (eg. WP-CLI)
function theme_pt_flags_on_delete( $post_id, $post = null ) {
	if ( ! $post instanceof WP_Post ) {
		clear_post_type_globals_cache();
		return;
	}
	if ( 'publish' === $post->post_status && theme_is_tracked_post_type( $post->post_type ) ) {
		clear_post_type_globals_cache();
	}
}
add_action( 'deleted_post', 'theme_pt_flags_on_delete', 10, 2 );

// Check for polylang - changing language without status change
function theme_pt_flags_on_language_change( $object_id, $terms, $tt_ids, $taxonomy ) {
	if (
		'language' === $taxonomy
		&& 'publish' === get_post_status( $object_id )
		&& theme_is_tracked_post_type( get_post_type( $object_id ) )
	) {
		clear_post_type_globals_cache();
	}
}
add_action( 'set_object_terms', 'theme_pt_flags_on_language_change', 10, 4 );
