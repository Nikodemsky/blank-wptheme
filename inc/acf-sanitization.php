<?php

/**
 * Helper function to get escaped single field from ACF
 *
 * @param string $field_key The ACF field key/name
 * @param mixed $post_id Post ID (optional, defaults to current post)
 * @param bool $format_value Whether to format the value
 * @param string $escape_method esc_html / esc_attr or NULL for none
 * @return array|string
 */
function get_field_escaped($field_key, $post_id = false, $format_value = true, $escape_method = 'esc_html')
{
    $field = get_field($field_key, $post_id, $format_value);
    
    /* Check for null and falsy values and always return empty string */
    if ($field === null || $field === false) {
        return '';
    }
    
    /* Handle arrays recursively */
    if (is_array($field)) {
        return escape_field_value($field, $escape_method);
    } else {
        return $escape_method === null ? $field : $escape_method($field);
    }
}

/**
 * Recursively escape array values
 *
 * @param array $field
 * @param string $escape_method
 * @return array
 */
function escape_field_value($field, $escape_method = 'esc_html')
{
    if (!is_array($field)) {
        return $escape_method === null ? $field : $escape_method($field);
    }
    
    $field_escaped = [];
    foreach ($field as $key => $value) {
        if (is_array($value)) {
            $field_escaped[$key] = escape_field_value($value, $escape_method);
        } else {
            $field_escaped[$key] = $escape_method === null ? $value : $escape_method($value);
        }
    }
    return $field_escaped;
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