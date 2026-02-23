<?php

function getArticles(
    $categories = null,
    $articles = null,
    $author = null,
    $amount = 12,
    $currentArticle = null
) {

    
    /* Basic WP query wit pagination */
    $paged  = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args   = array(
        'posts_per_page' => $amount,
        'post_type'      => 'post',
        'post_status'    => 'publish',
        // 'meta_key'       => 'featured-article',
        // 'orderby'        => [
        //     'featured-article' => 'DESC',
        //     'date'             => 'DESC'
        // ],
        'paged'          => $paged,
    );

    /* Do we need to filter on categories? */
    if($categories):

        // echo '<pre>';
        // print_r($categories);
        // echo '</pre>';

        if(is_single()):
            $post_id = get_the_ID();
            $args['post__not_in'] = array($post_id);
        endif;

        if(!is_array($categories)):
            $categories = array($categories);
        endif;


        $term = get_term_by('term_id', $categories[0], 'post_tag');
        if($term):
            $args['tag__in'] = $categories;
        else:
            $term = get_term_by('term_id', $categories[0], 'category');
            $args['category__in'] = $categories;
        endif;
        

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


    /* Do we need to filter on articles? */
    if($articles):
        $args['post__in']       = $articles;
        $args['posts_per_page'] = -1;
    endif;


    /* Do we need to filter on author? */
    if($author):
        $args['author']         = $author;
        $args['posts_per_page'] = -1;
    endif;

    if($currentArticle):
        $args['post__not_in'] = array($currentArticle);
    endif;

    // echo '<pre>';
    // print_r($args);
    // echo '</pre>';
    // die();


    return new WP_Query( $args );
}

function getArticleCategoryID($post_id) {

    $categories = get_the_category($post_id);
    if(!$categories): return false; endif;
    
    $amountOfCategories = count($categories);

    if($amountOfCategories > 1):
        if( class_exists('WPSEO_Options') ):
            $id = yoast_get_primary_term_id( 'category', $post_id );
        elseif( class_exists('RankMath') ):
            $id = get_post_meta( $post_id, 'rank_math_primary_category', true );
        else:
            $id = $categories[0]->term_id;
        endif;
    else:
      $id = $categories[0]->term_id;
    endif;

    return $id;

     //#DK code, if using Rank Math SEO plugin
    // if(isset($primary_cat_id) && !empty($primary_cat_id) && intval($primary_cat_id)) {
    //     return get_term( $primary_cat_id );
    // }
}

function getCategories($categories) {

    $listOfCategories = array();
    foreach($categories as $category):
      $id = $category->term_id;
      array_push($listOfCategories, $id);
    endforeach;

    return $listOfCategories;
}

// function getFeaturedArticles($featuredPosts) {
//     $args = array(
//         'posts_per_page' => -1,
//         'post__in'       => $featuredPosts,
//         'post_type'      => 'hod_magazine',
//         'post_status'    => 'publish',
//         'orderby'        => 'post__in',
//         'order'          => 'DESC',

//     );

//     return new WP_Query( $args );
// }
