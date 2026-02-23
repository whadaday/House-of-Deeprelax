<?php
$theme = wp_get_theme();
define('THEME_VERSION', $theme->Version);

function theme_styles()
{
    wp_enqueue_style('main', get_template_directory_uri().'/style.css', array(), THEME_VERSION, 'all', 99);
    wp_enqueue_script('main', get_template_directory_uri().'/main.js', array(), THEME_VERSION, 'all');

    if( ! class_exists('ACF') ) : return; endif;

    $devMode = get_field('dev-mode', 'theme');
    if($devMode):
        $jsFile = locate_template( 'assets/javascript/scripts/dev-mode.js', false, false );
        if (file_exists($jsFile)):
            wp_enqueue_script('dev-mode', get_template_directory_uri() .'/assets/javascript/scripts/dev-mode.js', array(), THEME_VERSION, 'all');
        endif;
    endif;
}
add_action('wp_enqueue_scripts', 'theme_styles');

function admin_styles() {
     wp_enqueue_style( 'admin_css', get_template_directory_uri() . '/assets/stylesheets/style-admin.css', array(), THEME_VERSION, 'all');
     wp_enqueue_style( 'acf_css', get_template_directory_uri() . '/assets/stylesheets/style-acf.css', array(), THEME_VERSION, 'all');
}
add_action( 'admin_enqueue_scripts', 'admin_styles' );

function admin_scripts($hook) {

    // if ('post-new.php' !== $hook):
    //     return;
    // endif;

    wp_enqueue_script('main', get_template_directory_uri().'/assets/javascript/admin.js', array(), THEME_VERSION, 'all');

    $post_id = get_the_ID(); // we'll be using this later to grab the gallery field on the page
    
    wp_register_script('load_block_templates_ajax', false); // register the script with no dependencies

    wp_enqueue_script('load_block_templates_ajax');
    
    wp_add_inline_script( 'load_block_templates_ajax', 'const template_params = ' . json_encode( array( // note: "template_params" is script object
        'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', //this is the WordPress Ajax URL
        'post_id' => $post_id, // reference the Post ID declared above
        'security' => wp_create_nonce('block-templates-nonce'), // create an nonce for security hardening
        ) ), 'before' );
}

add_action('admin_enqueue_scripts', 'admin_scripts');