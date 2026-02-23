<?php


function getReviews($reviews = null, $amount = -1, $categories = null, $tags = null) {
    
    /* Basic WP query wit pagination */
    $paged  = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args   = array(
            'posts_per_page' => $amount,
            'post_type'      => 'review',
            'post_status'    => 'publish',
            'ignore_sticky_posts' => true,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'paged'          => $paged,
        );

    /* Do we need to filter on reviews? */
    if($reviews):
        $args['post__in']       = $reviews;
        $args['posts_per_page'] = -1;
    endif;

    if($categories || $tags):

        $args['tax_query'] = array(
            'relation' => 'OR',
        );

        if($categories):

            if(!is_array($categories)):
                $categories = array($categories);
            endif;

            $args['tax_query'][] = array(
                'taxonomy' => 'category',
                'field' => 'term_id',
                'terms' => $categories,
                'operator' => 'IN',
            );

        endif;

        if($tags):

            if(!is_array($tags)):
                $tags = array($tags);
            endif;

            $args['tax_query'][] = array(
                'taxonomy' => 'post_tag',
                'field' => 'term_id',
                'terms' => $tags,
                'operator' => 'IN',
            );

        endif;
    
    endif;

    // echo '<pre>';
    // print_r($args);
    // echo '</pre>';

    // die();


    return new WP_Query( $args );
}