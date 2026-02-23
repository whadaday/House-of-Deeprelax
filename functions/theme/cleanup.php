<?php 

remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
remove_action( 'in_admin_header', 'wp_global_styles_render_svg_filters' );


remove_action('template_redirect','rest_output_link_header', 11 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );


add_filter('show_admin_bar', '__return_false');
add_filter( 'emoji_svg_url', '__return_false' );

function my_deregister_scripts(){
    wp_dequeue_script( 'wp-embed' );
}

add_action( 'wp_footer', 'my_deregister_scripts' );

//Remove Gutenberg Block Library CSS from loading on the frontend
function smartwp_remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
    wp_dequeue_style( 'global-styles' );
    wp_dequeue_style( 'classic-theme-styles' );
} 

add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );

// Hide the term description in the post_tag edit form
function hide_description_form( $tag, $taxonomy) {
    ?><style>.term-description-wrap{display:none;}</style><?php
}

add_action( 'post_tag_edit_form', 'hide_description_form', 10, 2 );
add_action( 'category_edit_form', 'hide_description_form', 10, 2 );

function hide_description_columns( $columns ) {
    if( isset( $columns['description'] ) )
        unset( $columns['description'] );   
    return $columns;
}
add_filter('manage_edit-post_tag_columns' , 'hide_description_columns');
add_filter('manage_edit-category_columns' , 'hide_description_columns');

add_action( 'admin_init', 'post_type_support_init' );
function post_type_support_init() {
  remove_post_type_support( 'page', 'comments' );
  remove_post_type_support( 'post', 'trackbacks' );
  remove_post_type_support( 'post', 'comments' );
  remove_post_type_support( 'post', 'post-formats' );
}

// Disable comments on all post types
function df_disable_comments_post_types_support() {
  $post_types = get_post_types();
  foreach ($post_types as $post_type) {
    if(post_type_supports($post_type, 'comments')) {
      remove_post_type_support($post_type, 'comments');
      remove_post_type_support($post_type, 'trackbacks');
    }
  }
}
add_action('admin_init', 'df_disable_comments_post_types_support');

// Close comments on the front-end
function df_disable_comments_status() {
  return false;
}
add_filter('comments_open', 'df_disable_comments_status', 20, 2);
add_filter('pings_open', 'df_disable_comments_status', 20, 2);

// Hide existing comments
function df_disable_comments_hide_existing_comments($comments) {
  $comments = array();
  return $comments;
}
add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in menu
function df_disable_comments_admin_menu() {
  remove_menu_page('edit-comments.php');
  // remove_menu_page('edit.php');
}
add_action('admin_menu', 'df_disable_comments_admin_menu');

// Change screenshot url
add_filter( 'wp_prepare_themes_for_js', function ( $themes ) {
    $themes['beamtheme']['screenshot'][0] = get_template_directory() . '/assets/images/beam/screenshot.png';
    return $themes;
} );