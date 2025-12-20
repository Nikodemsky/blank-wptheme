<?php

function wgblank_customize_register($wp_customize) {
    $wp_customize->get_setting("blogname")->transport = "postMessage";
    $wp_customize->get_setting("blogdescription")->transport = "postMessage";
    $wp_customize->get_setting("header_textcolor")->transport = "postMessage";
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial("blogname", [
            "selector" => ".site-title a",
            "render_callback" => "wgblank_customize_partial_blogname",
        ]);
        $wp_customize->selective_refresh->add_partial("blogdescription", [
            "selector" => ".site-description",
            "render_callback" => "wgblank_customize_partial_blogdescription",
        ]);
    }

    $wp_customize->remove_section( 'colors' ); // Removal of "Colours" section
    $wp_customize->remove_section( 'header_image' ); // Removal of "Header image" section
}
add_action('customize_register','wgblank_customize_register');

function wgblank_customize_partial_blogname() {
    bloginfo("name");
}

function wgblank_customize_partial_blogdescription() {
    bloginfo("description");
}

function wgblank_customize_preview_js() {
    wp_enqueue_script(
        "ts-customizer",
        get_template_directory_uri() . "/assets//js/customizer.js",
        ["customize-preview"],
        _S_VERSION,
        true
    );
}
add_action("customize_preview_init", "wgblank_customize_preview_js");
