<?php

/**
 * Helper function to get escaped single field from ACF
 *
 * @param string        $field_key     ACF field key/name
 * @param mixed         $post_id       Post ID (optional)
 * @param bool          $format_value  Whether to format the value
 * @param string|null   $escape_method esc_html / esc_attr / esc_url or null for none
 * @return string|array
 */
function get_field_escaped($field_key, $post_id = false, $format_value = true, $escape_method = 'esc_html')
{
    $field = get_field($field_key, $post_id, $format_value);

    if (empty($field)) {
        return is_array($field) ? [] : '';
    }

    if (is_array($field)) {
        array_walk_recursive($field, function (&$value) use ($escape_method) {
            if ($escape_method && is_string($value)) {
                $value = $escape_method($value);
            }
        });
        return $field;
    }

    return $escape_method ? $escape_method($field) : $field;
}

/*** USAGE EXAMPLES

// Get a single text field
$title = get_field_escaped('custom_title', $post_id);

// Get a field from current post
$subtitle = get_field_escaped('subtitle');

// Get a field with different escape method
$url = get_field_escaped('custom_url', $post_id, true, 'esc_url');

// Get a field without escaping
$raw_content = get_field_escaped('raw_field', $post_id, true, null);

// Get a repeater/group field (returns escaped array)
$hello_bar = get_field_escaped('eware_thho_hello_bar', $post_id);
$visibility = $hello_bar['visibility'];
$text = $hello_bar['text'];

// Get with esc_attr for attributes
$css_class = get_field_escaped('custom_class', $post_id, true, 'esc_attr');

*/
