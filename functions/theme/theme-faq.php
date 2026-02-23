<?php

function getFaqArticles($category = null, $amount = -1) {
    
    /* Basic WP query wit pagination */
    $paged  = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args   = array(
            'posts_per_page' => $amount,
            'post_type'      => 'faq',
            'post_status'    => 'publish',
            'ignore_sticky_posts' => true,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'paged'          => $paged,
        );

    /* Do we need to filter on categories? */
    if($category):

        $args['tax_query'] = array(
            array (
                'taxonomy' => 'faq-category',
                'field' => 'term_id',
                'terms' => $category,
            )
        );

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

    // echo '<pre>';
    // print_r($args);
    // echo '</pre>';
    // die();

    $query = new WP_Query($args);

    return $query;
}

function showFAQ($faqs, $buttonColor) {
		foreach($faqs as $faq):
			$question = $faq['question'];
			$answer   = $faq['answer'];
			$link 	  = $faq['link'];
            $loom     = isset($faq['loom']) ? $faq['loom'] : '';
            $faq_id   = isset($faq['id']) ? $faq['id'] : null;
	?>
		<div class="accordion__row" <?php if($faq_id): ?>data-id="<?php echo $faq_id; ?>"<?php endif; ?>>
			<a href="#" class="btn-accordion"><span class="question"><?php echo $question; ?></span> <span class="icon-chevron"></span></a>
			<div class="accordion__panel" style="--panel-height: 0;">
				<div class="accordion__panel__inner">
			  		<?php echo $answer; ?>

                    <?php if($loom): 
                        $icon = get_template_directory_uri().'/assets/images/beam/icons/play-circle.svg';
                    ?>
                        <div class="content-action" data-align="left">
                            <a 
                                data-fancybox="video-gallery"
                                data-type="iframe"
                                class="btn"
                                data-btn-color="<?php echo $buttonColor; ?>"
                                href="<?php echo convertLoomShareToEmbed($loom); ?>">
                                <span class="icon-play"><?php echo file_get_contents($icon); ?></span>Bekijk de uitlegvideo
                            </a>
                        </div>
			  		<?php elseif($link): 
			  			$link_url 	 = $link['url'];
					    $link_title  = $link['title'];
					    $link_target = $link['target'] ? $link['target'] : '_self';
			  		?>
    			  		<div class="content-action" data-align="left">
    			  			<a 
    							class="btn"
    							data-btn-color="<?php echo $buttonColor; ?>"
    							href="<?php echo esc_url( $link_url ); ?>"
    							target="<?php echo esc_attr( $link_target ); ?>">
    							<?php echo esc_html( $link_title ); ?>
    						</a>
    			  		</div>
			  		<?php endif; ?>

			  	</div>
			</div>
		</div>
	<?php 
		endforeach;
}

function convertLoomShareToEmbed($url) {
    return str_replace('/share/', '/embed/', $url);
}

// Register FAQ Post Type
function add_faq_posttype() {

	$labels = array(
        'name'                  => _x( 'FAQ', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Question', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'FAQ', 'text_domain' ),
        'name_admin_bar'        => __( 'Question', 'text_domain' ),
        'archives'              => __( 'Question Archives', 'text_domain' ),
        'attributes'            => __( 'Question Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Question:', 'text_domain' ),
        'all_items'             => __( 'All FAQ', 'text_domain' ),
        'add_new_item'          => __( 'Add New Question', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Question', 'text_domain' ),
        'edit_item'             => __( 'Edit Question', 'text_domain' ),
        'update_item'           => __( 'Update Question', 'text_domain' ),
        'view_item'             => __( 'View Question', 'text_domain' ),
        'view_items'            => __( 'View Question', 'text_domain' ),
        'search_items'          => __( 'Search Question', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into Question', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Question', 'text_domain' ),
        'items_list'            => __( 'FAQ list', 'text_domain' ),
        'items_list_navigation' => __( 'FAQ list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter Question list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Question', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'custom-fields' ),
        'taxonomies'            => array( 'faq-category' ),
        'menu_icon'             => 'dashicons-format-status',
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );
    register_post_type( 'faq', $args );

    $labels = array(
        'name'                       => _x( 'FAQ Categories', 'Taxonomy General Name', 'text_domain' ),
        'singular_name'              => _x( 'FAQ Category', 'Taxonomy Singular Name', 'text_domain' ),
        'menu_name'                  => __( 'Categories', 'text_domain' ),
        'all_items'                  => __( 'All categories', 'text_domain' ),
        'parent_item'                => __( 'Parent category', 'text_domain' ),
        'parent_item_colon'          => __( 'Parent category:', 'text_domain' ),
        'new_item_name'              => __( 'New Category Name', 'text_domain' ),
        'add_new_item'               => __( 'Add New Category', 'text_domain' ),
        'edit_item'                  => __( 'Edit Category', 'text_domain' ),
        'update_item'                => __( 'Update Category', 'text_domain' ),
        'view_item'                  => __( 'View Category', 'text_domain' ),
        'separate_items_with_commas' => __( 'Separate Categories with commas', 'text_domain' ),
        'add_or_remove_items'        => __( 'Add or remove Categories', 'text_domain' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
        'popular_items'              => __( 'Popular Categories', 'text_domain' ),
        'search_items'               => __( 'Search Categories', 'text_domain' ),
        'not_found'                  => __( 'Not Found', 'text_domain' ),
        'no_terms'                   => __( 'No items', 'text_domain' ),
        'items_list'                 => __( 'Category list', 'text_domain' ),
        'items_list_navigation'      => __( 'Category list navigation', 'text_domain' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'has_archive'                => false,
        'rewrite'  => [
            'slug' => 'klantenservice',
        ],
    );
    register_taxonomy( 'faq-category', array( 'faq' ), $args );

}
add_action( 'init', 'add_faq_posttype', 0 );

add_filter( 'register_taxonomy_args', 'my_register_faq_category_args', 10, 2 );
function my_register_faq_category_args( $args, $taxonomy ) {
    if ( 'faq-category' === $taxonomy ) {
        $args['rewrite'] = (array) $args['rewrite'];
        $args['rewrite']['slug'] = 'klantenservice';
    }

    return $args;
}