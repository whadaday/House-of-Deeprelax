<?php

// Register FAQ Post Type
function add_book_posttype() {

	$labels = array(
        'name'                  => _x( 'Book', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Chapter', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Book', 'text_domain' ),
        'name_admin_bar'        => __( 'Chapter', 'text_domain' ),
        'archives'              => __( 'Chapter Archives', 'text_domain' ),
        'attributes'            => __( 'Chapter Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Chapter:', 'text_domain' ),
        'all_items'             => __( 'All Book', 'text_domain' ),
        'add_new_item'          => __( 'Add New Chapter', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Chapter', 'text_domain' ),
        'edit_item'             => __( 'Edit Chapter', 'text_domain' ),
        'update_item'           => __( 'Update Chapter', 'text_domain' ),
        'view_item'             => __( 'View Chapter', 'text_domain' ),
        'view_items'            => __( 'View Chapter', 'text_domain' ),
        'search_items'          => __( 'Search Chapter', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into Chapter', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Chapter', 'text_domain' ),
        'items_list'            => __( 'Book list', 'text_domain' ),
        'items_list_navigation' => __( 'Book list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter Chapter list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Book', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'custom-fields' ),
        'taxonomies'            => array( ),
        'menu_icon'             => 'dashicons-book',
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
        'rewrite' => array(
            'slug' => 'een-ontspannen-jij',
        ),
    );
    register_post_type( 'book', $args );

}
add_action( 'init', 'add_book_posttype', 0 );