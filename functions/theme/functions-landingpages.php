<?php

// Register Landingpage Post Type
function add_landingpage_posttype() {

	$labels = array(
        'name'                  => _x( 'Landingspagina\'s', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Landingspagina', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Landingspagina\'s', 'text_domain' ),
        'name_admin_bar'        => __( 'Landingspagina', 'text_domain' ),
        'archives'              => __( 'Landingspagina Archives', 'text_domain' ),
        'attributes'            => __( 'Landingspagina Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Landingspagina:', 'text_domain' ),
        'all_items'             => __( 'All Landingspagina\'s', 'text_domain' ),
        'add_new_item'          => __( 'Add New Landingspagina', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Landingspagina', 'text_domain' ),
        'edit_item'             => __( 'Edit Landingspagina', 'text_domain' ),
        'update_item'           => __( 'Update Landingspagina', 'text_domain' ),
        'view_item'             => __( 'View Landingspagina', 'text_domain' ),
        'view_items'            => __( 'View Landingspagina', 'text_domain' ),
        'search_items'          => __( 'Search Landingspagina', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into Landingspagina', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Landingspagina', 'text_domain' ),
        'items_list'            => __( 'Landingspagina\'s list', 'text_domain' ),
        'items_list_navigation' => __( 'Landingspagina\'s list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter Landingspagina list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Landingspagina\'s', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'custom-fields', 'revisions' ),
        'taxonomies'            => array( ),
        'menu_icon'             => 'dashicons-admin-page',
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'rewrite'               => false,
        'show_in_rest'          => true,
    );
    register_post_type( 'landingpage', $args );

}
add_action( 'init', 'add_landingpage_posttype', 0 );

// Permalink aanpassen zodat 'landingpage' prefix weg is
function landingpage_custom_permalink($post_link, $post) {
    if ($post->post_type === 'landingpage' && $post->post_status === 'publish') {
        return home_url('/' . $post->post_name . '/');
    }
    return $post_link;
}
add_filter('post_type_link', 'landingpage_custom_permalink', 10, 2);

function landingpage_custom_rewrite_rules() {
    $landingpages = get_posts(array(
        'post_type' => 'landingpage',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));

    foreach ($landingpages as $page) {
        add_rewrite_rule(
            '^' . $page->post_name . '/?$',
            'index.php?landingpage=' . $page->post_name,
            'top'
        );
    }
}
add_action('init', 'landingpage_custom_rewrite_rules');

add_action( 'save_post_landingpage', 'reset_permalinks_on_landingpage_save', 10, 3 );

function reset_permalinks_on_landingpage_save( $post_id, $post, $update ) {
    // Voorkom onnodige uitvoering tijdens autosave of wanneer een revisie wordt opgeslagen
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Zorg dat het om het juiste post type gaat
    if ( $post->post_type !== 'landingpage' ) {
        return;
    }

    // Flush de rewrite regels (permalinks)
    flush_rewrite_rules();
}