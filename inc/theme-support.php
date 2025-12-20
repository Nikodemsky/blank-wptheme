<?php

// Theme support directives
if (!function_exists("wgblank_setup")):
    function wgblank_setup()
    {
        load_theme_textdomain(
            "wg-blank",
            get_template_directory() . "/languages"
        );
        //add_theme_support("automatic-feed-links");
        add_theme_support("title-tag");
        add_theme_support("post-thumbnails");
        register_nav_menus(["menu-1" => esc_html__("Main menu", "wg-blank")]);
        add_theme_support("html5", [
            "search-form",
            "comment-form",
            "comment-list",
            "gallery",
            "caption",
            "style",
            "script",
        ]);
        /*add_theme_support(
            "custom-background",
            apply_filters("wgblank_custom_background_args", [
                "default-color" => "ffffff",
                "default-image" => "",
            ])
        );*/
        //add_theme_support("customize-selective-refresh-widgets");
        add_theme_support("custom-logo", [
            "height" => 250,
            "width" => 250,
            "flex-width" => true,
            "flex-height" => true,
        ]);
    }
endif;
add_action("after_setup_theme", "wgblank_setup");

// Content width
function wgblank_content_width() {
    $GLOBALS["content_width"] = apply_filters("wgblank_content_width", 1025);
}
add_action("after_setup_theme", "wgblank_content_width", 0);

// Default stylesheet
function wgblank_scripts() {
    wp_enqueue_style("wgblank-style", get_stylesheet_uri(), [], _S_VERSION);
    wp_style_add_data("wgblank-style", "rtl", "replace");
}
add_action("wp_enqueue_scripts", "wgblank_scripts");
