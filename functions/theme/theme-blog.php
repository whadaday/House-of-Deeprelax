<?php

// Replace Posts label as Articles in Admin Panel 

function change_post_menu_label() {
	$blogActive = get_field('blog-active', 'theme');
	if($blogActive):
	    global $menu;
	    global $submenu;
	    $menu[5][0] = 'Blog';
	    $submenu['edit.php'][5][0] = 'Blog';
	    $submenu['edit.php'][10][0] = 'Add Article';
	endif;
}
add_action( 'admin_menu', 'change_post_menu_label' );

function change_post_object_label() {
	$blogActive = get_field('blog-active', 'theme');
	if($blogActive):
        global $wp_post_types;
        $labels = &$wp_post_types['post']->labels;
        $labels->name = 'Blog';
        $labels->singular_name = 'Article';
        $labels->add_new = 'Add Article';
        $labels->add_new_item = 'Add Article';
        $labels->edit_item = 'Edit Article';
        $labels->new_item = 'Article';
        $labels->view_item = 'View Article';
        $labels->search_items = 'Search Articles';
        $labels->not_found = 'No Articles found';
        $labels->not_found_in_trash = 'No Articles found in Trash';
        $labels->name_admin_bar = 'Add Article';
    endif;
}
add_action( 'admin_init', 'change_post_object_label' );


function add_blog_posts_page() {
	$blogActive = get_field('blog-active', 'theme');
	$pageForPosts = get_option('page_for_posts');
	$blogPageLabel = 'Blog';

	if($blogActive && $pageForPosts == 0):
        $page_details = array(
            'post_title'    => $blogPageLabel,
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page'
        );
        wp_insert_post( $page_details );

        $blogPage = get_page_by_title($blogPageLabel);
        if(is_array($blogPage)):
        	$blogPage = $blogPage[0];
        endif;

        update_option( 'page_for_posts', $blogPage->ID);
    endif;
}
add_action( 'admin_init', 'add_blog_posts_page' );


// Unregister taxonomy
function blog_unregister_taxonomy(){
	global $wp_taxonomies;

	// Removing this gives error
	// if ( taxonomy_exists( 'post_format')): unset( $wp_taxonomies['post_format']); endif;
	// unregister_taxonomy('post_format');

	if ( taxonomy_exists( 'link_category')): unset( $wp_taxonomies['link_category']); endif;
	unregister_taxonomy('link_category');

	if ( taxonomy_exists( 'wp_theme')): unset( $wp_taxonomies['wp_theme']); endif;
	unregister_taxonomy('wp_theme');

	// if ( taxonomy_exists( 'wp_template_part_area')): unset( $wp_taxonomies['wp_template_part_area']); endif;
	// unregister_taxonomy('wp_template_part_area');

	// if ( taxonomy_exists( 'wpforms_form_tag')): unset( $wp_taxonomies['wpforms_form_tag']); endif;
	// unregister_taxonomy('wpforms_form_tag');

	//category
	//post_tag
	//nav_menu
	//wp_theme
	//wp_template_part_area
	//wpforms_form_tag

	// echo '<pre>'; print_r($wp_taxonomies); echo '</pre>';
	// die();

	$blogActive 	  = get_field('blog-active', 'theme');
	$categoriesActive = get_field('blog-categories-active', 'theme');
	$tagsActive 	  = get_field('blog-tags-active', 'theme');

	if(!$blogActive || !$categoriesActive):
		unregister_taxonomy_for_object_type( 'category', 'post' );
		
	endif;
	if(!$blogActive || !$tagsActive):
		unregister_taxonomy_for_object_type( 'post_tag', 'post' );
	endif;

	if($blogActive):
		add_theme_support( 'post-thumbnails' );
	endif;

}
add_action('init', 'blog_unregister_taxonomy');

// Redirect any user trying to access comments page
function df_disable_comments_admin_menu_redirect() {
	global $pagenow;
	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url()); exit;
	}
}
add_action('admin_init', 'df_disable_comments_admin_menu_redirect');


// Hide sticky posts
add_action( 'admin_print_styles', 'hide_sticky_option' );
function hide_sticky_option() {
global $post_type, $pagenow;
if( 'post.php' != $pagenow && 'post-new.php' != $pagenow && 'edit.php' != $pagenow )
    return;
?>
<style type="text/css">#sticky-span { display:none!important }
.quick-edit-row .inline-edit-col-right div.inline-edit-col > :last-child > label.alignleft:last-child{ display:none!important; }</style>
<?php
}


// Unsticky all posts
update_option( 'sticky_posts', array() );

function showBlogContentBlock($block, $layout, $blockIndex) {

	$contentFile  = locate_template( 'partials/blog/content/article-content-'.$layout.'.php', false, false );

	// Does block file exist?
	if (file_exists($contentFile)): 

		// Put block content in more readable variables
		$content = $block['content'];

		// Remove empty keys, casues by tabs
		unset($block['']);

		if($layout == 'image'):
			$layout = 'blog-'.$layout;
		endif;

		// Open section and include options
		include( locate_template( 'partials/blog/content/article-content-open.php', false, false ) );

		// Include section content
		include($contentFile);

		// Close section
		include( locate_template( 'partials/blog/content/article-content-close.php', false, false ) );

	endif;
}

function change_page_id_for_postpage( $query ) {
	$post_id = get_option( 'page_for_posts' );
	if( $query->is_main_query() && ! is_admin() && $query->is_home() ) {
		$query->set( 'p', $post_id );
	}
}
add_action( 'pre_get_posts', 'change_page_id_for_postpage' );