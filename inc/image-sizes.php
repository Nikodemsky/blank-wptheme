<?php

/* Remove unused image sizes;
medium & large can be removed in CMS admin panel by setting size values to 0 */
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

function remove_unused_image_sizes_from_wysiwyg($size_names) {
	unset($size_names['medium_large']);
	unset($size_names['1536x1536']);
	unset($size_names['2048x2048']);
	
	return $size_names;
}
add_filter('image_size_names_choose', 'remove_unused_image_sizes_from_wysiwyg');

/* Remove sizes from ACF sizes dropdowns;
those has to be removed by hand even after removing specific image size */
function remove_unused_image_sizes_from_acf($sizes) {

    //unset($sizes['medium']);
    //unset($sizes['large']);

    unset($sizes['medium_large']);
    unset($sizes['1536x1536']);
    unset($sizes['2048x2048']);
    
    return $sizes;
}
if (function_exists('get_field')) {
    add_filter('acf/get_image_sizes', 'remove_unused_image_sizes_from_acf');
}

/* Add custom image sizes: 
id, width, height, hard-crop */
//add_image_size( 'size-1', 150, 150, false );
//add_image_size( 'size-2', 300, 300, true );
