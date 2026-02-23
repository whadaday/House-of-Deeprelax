<?php

// Register Reviews Post Type
function add_reviews_posttype() {

	$reviewsActive = get_field('reviews-active', 'theme');
	if($reviewsActive):
    
	    $labels = array(
	        'name'                  => _x( 'Ervaringen', 'Post Type General Name', 'text_domain' ),
	        'singular_name'         => _x( 'Ervaringen', 'Post Type Singular Name', 'text_domain' ),
	        'menu_name'             => __( 'Ervaringen', 'text_domain' ),
	        'name_admin_bar'        => __( 'Ervaringen', 'text_domain' ),
	        'archives'              => __( 'Ervaring Archives', 'text_domain' ),
	        'attributes'            => __( 'Ervaring Attributes', 'text_domain' ),
	        'parent_item_colon'     => __( 'Parent Ervaring:', 'text_domain' ),
	        'all_items'             => __( 'All Ervaringen', 'text_domain' ),
	        'add_new_item'          => __( 'Add New Ervaring', 'text_domain' ),
	        'add_new'               => __( 'Add New', 'text_domain' ),
	        'new_item'              => __( 'New Ervaring', 'text_domain' ),
	        'edit_item'             => __( 'Edit Ervaring', 'text_domain' ),
	        'update_item'           => __( 'Update Ervaring', 'text_domain' ),
	        'view_item'             => __( 'View Ervaring', 'text_domain' ),
	        'view_items'            => __( 'View Ervaring', 'text_domain' ),
	        'search_items'          => __( 'Search Ervaring', 'text_domain' ),
	        'not_found'             => __( 'Not found', 'text_domain' ),
	        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
	        'featured_image'        => __( 'Featured Image', 'text_domain' ),
	        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
	        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
	        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
	        'insert_into_item'      => __( 'Insert into Ervaring', 'text_domain' ),
	        'uploaded_to_this_item' => __( 'Uploaded to this Ervaring', 'text_domain' ),
	        'items_list'            => __( 'Ervaring list', 'text_domain' ),
	        'items_list_navigation' => __( 'Ervaring list navigation', 'text_domain' ),
	        'filter_items_list'     => __( 'Filter Ervaring list', 'text_domain' ),
	    );
	    $args = array(
	        'label'                 => __( 'Ervaring', 'text_domain' ),
	        'labels'                => $labels,
	        'menu_icon'             => 'dashicons-star-filled',
	        'description'           => '',
	        'public'                => false,
	        'publicly_queryable'    => true,
	        'show_ui'               => true,
	        'show_in_rest'          => false,
	        'rest_base'             => '',
	        'show_in_menu'          => true,
	        'exclude_from_search'   => false,
	        'capability_type'       => 'post',
	        'map_meta_cap'          => true,
	        'hierarchical'          => false,
	        'has_archive'           => false,
	        'query_var'             => true,
	        'supports'              => array( 'title', 'custom-fields' ),
	        'taxonomies' => array( 'category', 'post_tag' ),
	    );
	    register_post_type( 'review', $args );

	endif;

}
add_action( 'init', 'add_reviews_posttype', 0 );