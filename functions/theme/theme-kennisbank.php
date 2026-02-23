<?php

// Register Custom Post Type
function add_kennisbank_posttype() {
    
    $args = array(
        'label' => 'Kennisbank',
        'labels' => array(
            'name' => 'Kennisbank',
            'singular_name' => 'Kennisbank',
            'menu_name' => 'Kennisbank',
            'all_items' => 'All Kennisbank',
            'add_new' => 'Add Kennisbank',
        ),
        'menu_icon' => 'dashicons-book-alt',
        'description' => '',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_rest' => false,
        'rest_base' => '',
        'show_in_menu' => true,
        'exclude_from_search' => false,
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'hierarchical' => false,
        // 'rewrite' => array( 'slug'=>'kennisbank' ),
        'has_archive' => 'kennisbank',
        'query_var' => true,
        'supports' => array( 'title', 'custom-fields', 'excerpt', 'thumbnail', 'author' ),
        'taxonomies' => array( 'category', 'post_tag' ),
    );

    register_post_type( 'kennisbank', $args );

}
add_action( 'init', 'add_kennisbank_posttype', 0 );

// function getKennisbankArticles($amount, $category, $currentPost = null) {
//     $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
//     if($category != null):
//         $args = array(
//             'posts_per_page'    => $amount,
//             'post_type'     => 'kennisbank',
//             'post_status' => 'publish',
//             'orderby' => 'title',
//             'order'   => 'ASC',
//             'post__not_in' => array($currentPost),
//             'paged'=> $paged,
//             'tax_query' => array(
//                 array (
//                     'taxonomy' => 'kennisbank_category',
//                     'field' => 'slug',
//                     'terms' => $category,
//                 )
//             )

//         );
//     else:
//         $args = array(
//             'posts_per_page'    => $amount,
//             'post_type'     => 'kennisbank',
//             'post_status' => 'publish',
//             'orderby' => 'title',
//             'order'   => 'ASC',
//             'paged'=> $paged,
//         );
//     endif;

//     return new WP_Query( $args );
// }

function getRelatedKennisbankArticles($category, $amount, $currentPost) {
    $args = array(
        'posts_per_page' => $amount,
        'post_type' => 'kennisbank',
        'orderby' => 'title',
        'order'   => 'ASC',
        'post__not_in' => array($currentPost),
        'tax_query' => array(
            array(
                'taxonomy' => 'kennisbank_category',
                'field' => 'slug',
                'terms' => $category
            ),
        )
    );



    $query = new WP_Query($args);
    $num = $query->post_count;

    if($num < 3):

        $args = array(
            'posts_per_page' => $amount,
            'post_type' => 'kennisbank',
            'orderby' => 'DESC',
            'post__not_in' => array($currentPost),
        );
        $query = new WP_Query($args);

    endif;

    return $query;
}


function getKennisbankArticles($categories = null, $articles = null, $author = null, $amount = -1, $featured = null) {

    
    /* Basic WP query wit pagination */
    $paged  = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args   = array(
            'posts_per_page' => $amount,
            'post_type'      => 'kennisbank',
            'post_status'    => 'publish',
            'ignore_sticky_posts' => true,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'paged'          => $paged,
        );

    /* Do we need to filter on categories? */
    if($categories):

        // echo '<pre>';
        // print_r($categories);
        // echo '</pre>';

        if(!is_array($categories)):
            $categories = array($categories);
        endif;

        $args['category__in'] = $categories;

        // $args['tax_query'] = array(
        //     'relation' => 'OR'
        // );

        // foreach($categories as $category):

        //     $slug = $category->slug;
        //     $filter = array(
        //         'taxonomy'  => 'category',
        //         'field'     => 'slug',
        //         'terms'     => $slug,
        //     );
        //     array_push($args['tax_query'], $filter);

        // endforeach;

    endif;

    if(is_single()):
        $post_id = get_the_ID();
        $args['post__not_in'] = array($post_id);
    endif;


    /* Do we need to filter on articles? */
    // if($articles):

    //     $args['post__in']       = $articles;
    //     $args['posts_per_page'] = -1;

    // endif;


    /* Do we need to filter on author? */
    if($author):

        $args['author']         = $author;
        $args['posts_per_page'] = -1;

    endif;

    if($featured):
        $args['post__not_in'] = array($featured);

    endif;

    // echo '<pre>';
    // print_r($args);
    // echo '</pre>';
    // die();

    $query = new WP_Query($args);

    $posts = $query->posts;
    $listPosts = array();

    foreach($posts as $post) {
        array_push($listPosts, $post->ID);
    }

    return $listPosts;
}