<?php 

// Guard
defined( 'ABSPATH' ) || exit;

// Default body open
if (!function_exists("wp_body_open")) :
    function wp_body_open() {
        do_action("wp_body_open");
    }
endif;
