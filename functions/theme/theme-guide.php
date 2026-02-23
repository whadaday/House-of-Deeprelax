<?php

// Register Custom Post Type
function add_guide_posttype() {
    
    $labels = array(
        'name'                  => _x( 'Guides', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Guides', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Guides', 'text_domain' ),
        'name_admin_bar'        => __( 'Guides', 'text_domain' ),
        'archives'              => __( 'Guide Archives', 'text_domain' ),
        'attributes'            => __( 'Guide Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Guide:', 'text_domain' ),
        'all_items'             => __( 'All Guides', 'text_domain' ),
        'add_new_item'          => __( 'Add New Guide', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Guide', 'text_domain' ),
        'edit_item'             => __( 'Edit Guide', 'text_domain' ),
        'update_item'           => __( 'Update Guide', 'text_domain' ),
        'view_item'             => __( 'View Guide', 'text_domain' ),
        'view_items'            => __( 'View Guide', 'text_domain' ),
        'search_items'          => __( 'Search Guide', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into Guide', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Guide', 'text_domain' ),
        'items_list'            => __( 'Guide list', 'text_domain' ),
        'items_list_navigation' => __( 'Guide list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter Guide list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Guide', 'text_domain' ),
        'labels'                => $labels,
        'menu_icon'             => 'dashicons-heart',
        'description'           => '',
        'public'                => true,
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
        'supports'              => array( 'title', 'custom-fields', 'thumbnail', 'excerpt' ),
        'taxonomies' => array( 'category', 'guide_type' ),
    );
    register_post_type( 'guide', $args );

    $args = array(
        'label'         => 'Type',
        'labels'        => array(
            'name'          => 'Type',
            'singular_name' => 'Type',
            'search_items'  => 'Find Type',
        ),
        'description'       => 'Taxonomy for guides',
        'public'            => true,
        'show_in_nav_menus' => false,
        'show_ui'           => true,
        'show_tagcloud'     => false,
        'hierarchical'      => true,
        'show_admin_column' => true,
    );
    register_taxonomy( 'guide_type', array( 'guide' ), $args );

}
add_action( 'init', 'add_guide_posttype', 0 );

function getGuides() {
    
    /* Basic WP query wit pagination */
    $paged  = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args   = array(
            'posts_per_page'      => -1,
            'post_type'           => 'guide',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'meta_key'            => 'order',
            'orderby'             => 'meta_value',
            'order'               => 'ASC',
            'paged'               => $paged,
        );

    $query = new WP_Query($args);

    $posts = $query->posts;
    $listPosts = array();

    foreach($posts as $post) {
        array_push($listPosts, $post->ID);
    }

    return $listPosts;
}

function getGuideTypeID($post_id) {

    $categories = get_the_terms($post_id, 'guide_type');
    if(!$categories): return false; endif;
    
    $amountOfCategories = count($categories);

    if($amountOfCategories > 1):
        if( class_exists('WPSEO_Options') ):
            $id = yoast_get_primary_term_id( 'guide_type', $post_id );
        elseif( class_exists('RankMath') ):
            $id = get_post_meta( $post_id, 'rank_math_primary_category', true );
        else:
            $id = $categories[0]->term_id;
        endif;
    else:
      $id = $categories[0]->term_id;
    endif;

    return $id;
}

add_filter( 'body_custom_attributes', 'add_guide_color_to_page' );

function add_guide_color_to_page() {
    if(is_singular('guide')):
        $guideColor = get_field('guide-color');
        if($guideColor):
            return 'data-guide-color="'.$guideColor.'"';
        endif;
    endif;
}

