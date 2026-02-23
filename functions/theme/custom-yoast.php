<?php

add_filter( 'wpseo_breadcrumb_links', 'custom_breadcrumbs' );

function custom_breadcrumbs( $links ) {
    global $post;

    if ( is_author() ):

	    $links[1]['url']  = get_permalink( get_option( 'page_for_posts' ));
	    $links[1]['text'] = 'Blog';

	    $authorName 	  = get_the_author_meta('first_name');
	    $authorId		  = get_the_author_meta('ID');
	    $authorLink 	  = get_author_posts_url($authorId);

	    $links[2]['url']  = $authorLink;
	    $links[2]['text'] = 'Artikelen van '.$authorName;

	endif;

    return $links;
}