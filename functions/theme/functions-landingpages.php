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

// Ververs de rewrite-rules ALLÉÉN als een landingpage-slug of -status wijzigt —
// dán verandert de permalink-set. Cruciaal: géén flush_rewrite_rules() hier.
// Die regenereert de rules binnen DEZE request, met de landingpages zoals ze op
// init geregistreerd stonden — dus van VÓÓR de save. De nieuwe slug ontbreekt
// dan in de opgeslagen rules en de pagina geeft 404 tot een volgende flush.
// delete_option() laat WP de rules lui regenereren op de eerstvolgende request,
// waar init de actuele slugs wél kent.
function reset_permalinks_on_landingpage_save( $post_id, $post_after, $post_before ) {
    if ( $post_after->post_type !== 'landingpage' ) {
        return;
    }
    if ( $post_before->post_name !== $post_after->post_name
        || $post_before->post_status !== $post_after->post_status ) {
        delete_option( 'rewrite_rules' );
    }
}
add_action( 'post_updated', 'reset_permalinks_on_landingpage_save', 10, 3 );

// Geplande publicatie (wp_publish_post via cron) vuurt géén post_updated —
// vang statuswissels van/naar publish daarom ook via transition_post_status.
function reset_permalinks_on_landingpage_status( $new_status, $old_status, $post ) {
    if ( $post->post_type !== 'landingpage' || $new_status === $old_status ) {
        return;
    }
    if ( $new_status === 'publish' || $old_status === 'publish' ) {
        delete_option( 'rewrite_rules' );
    }
}
add_action( 'transition_post_status', 'reset_permalinks_on_landingpage_status', 10, 3 );