<?php

// Register Custom Post Type
function add_banners_posttype() {
    
    $labels = array(
        'name'                  => _x( 'Banners', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Banner', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Banners', 'text_domain' ),
        'name_admin_bar'        => __( 'Banner', 'text_domain' ),
        'archives'              => __( 'Banner Archives', 'text_domain' ),
        'attributes'            => __( 'Banner Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Banner:', 'text_domain' ),
        'all_items'             => __( 'All Banners', 'text_domain' ),
        'add_new_item'          => __( 'Add New Banner', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Banner', 'text_domain' ),
        'edit_item'             => __( 'Edit Banner', 'text_domain' ),
        'update_item'           => __( 'Update Banner', 'text_domain' ),
        'view_item'             => __( 'View Banner', 'text_domain' ),
        'view_items'            => __( 'View Banner', 'text_domain' ),
        'search_items'          => __( 'Search Banner', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into Banner', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Banner', 'text_domain' ),
        'items_list'            => __( 'Banners list', 'text_domain' ),
        'items_list_navigation' => __( 'Banners list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter Banner list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Banner', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'custom-fields' ),
        'taxonomies'            => array(),
        'menu_icon'             => 'dashicons-tickets-alt',
        'public'                => false,  // Niet publiek zichtbaar
        'show_ui'               => true,   // Nog steeds bewerkbaar in admin
        'show_in_nav_menus'     => false,
        'exclude_from_search'   => true,
        'has_archive'           => false,
        'rewrite'               => false,  // Zorgt ervoor dat er GEEN slug wordt geregistreerd
        'query_var'             => false,  
        'hierarchical'          => false,
        'public'                => false,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'can_export'            => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );
    register_post_type( 'banner', $args );

}
add_action( 'init', 'add_banners_posttype', 0 );