<?php

function sync_yoastmeta_with_excerpt($post_ID, $post_after, $post_before){
    //post_excerpt

    // echo '<b>Post Object BEFORE update:</b><br />';

    // echo '<b>Post Object AFTER update:</b><br />';

    $excerptBefore = $post_before->post_excerpt;
    $excerptAfter  = $post_after->post_excerpt;
    // echo $excerptBefore;
    // echo $excerptAfter;

    // YOAST 
    // $metaDescription = get_post_meta($post_ID, '_yoast_wpseo_metadesc');
    if($excerptAfter):
        $_POST[ "yoast_wpseo_metadesc" ] = $excerptAfter;
    endif;
    // update_post_meta($post_ID, '_yoast_wpseo_metadesc', $excerptAfter);
}

// add_action( 'post_updated', 'sync_yoastmeta_with_excerpt', 10, 3 );