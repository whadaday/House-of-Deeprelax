<?php

// Register Blocks Post Type
function add_blocks_posttype() {

    $labels = array(
        'name'                  => _x( 'Blocks', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Blocks', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Blocks', 'text_domain' ),
        'name_admin_bar'        => __( 'Blocks', 'text_domain' ),
        'archives'              => __( 'Block Archives', 'text_domain' ),
        'attributes'            => __( 'Block Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Block:', 'text_domain' ),
        'all_items'             => __( 'All Blocks', 'text_domain' ),
        'add_new_item'          => __( 'Add New Block', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Block', 'text_domain' ),
        'edit_item'             => __( 'Edit Block', 'text_domain' ),
        'update_item'           => __( 'Update Block', 'text_domain' ),
        'view_item'             => __( 'View Block', 'text_domain' ),
        'view_items'            => __( 'View Block', 'text_domain' ),
        'search_items'          => __( 'Search Block', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into Block', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Block', 'text_domain' ),
        'items_list'            => __( 'Block list', 'text_domain' ),
        'items_list_navigation' => __( 'Block list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter Block list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Block', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'custom-fields'),
        'taxonomies'            => array('event-type', 'event-tag'),
        'menu_icon'             => 'dashicons-layout',
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'public'                => true,
        'publicly_queryable'    => false,
        'show_ui'               => true,
        'show_in_rest'          => false,
        'rest_base'             => '',
        'show_in_menu'          => true,
        'exclude_from_search'   => true,
        'capability_type'       => 'page',
        'map_meta_cap'          => true,
        'hierarchical'          => false,
        'has_archive'           => false,
        'query_var'             => true,        
    );
    register_post_type( 'block', $args );

}
add_action( 'init', 'add_blocks_posttype', 0 );

function blockIndex() {
    global $blockIndex;
    $blockIndex = 1;
}
add_action( 'after_setup_theme', 'blockIndex' );

function contentBlockIndex() {
    global $contentBlockIndex;
    $contentBlockIndex = 0;
}
add_action( 'after_setup_theme', 'contentBlockIndex' );

function showContentBlock($block, $layout) {

	$contentFile  = locate_template( 'blocks/'.$layout.'/'.$layout.'.php', false, false );
	$jsFile       = locate_template( 'blocks/'.$layout.'/'.$layout.'.js', false, false );

	// Does block file exist?
	if (file_exists($contentFile)): 

		// Include JS file if exists
		if (file_exists($jsFile)):
			wp_enqueue_script($layout, get_template_directory_uri() .'/blocks/'.$layout.'/'.$layout.'.js', array(), THEME_VERSION, 'all');
		endif;

		// Put block content in more readable variables
		$content = isset($block['content']) ? $block['content'] : null;
        $options = isset($block['options']) ? $block['options'] : null;

		// Remove empty keys, casues by tabs
		unset($block['']);

		// Check if block is disabled
		$hideBlock = (isset($options['hide-block'])) ? $options['hide-block'] : 0;

		if(!$hideBlock):

			// Open section and include options
			include( locate_template( 'partials/section/section-open.php', false, false ) );

			// Include section content
			include($contentFile);

			// Close section
			include( locate_template( 'partials/section/section-close.php', false, false ) );

		endif;

	endif;
}

function showGlobalBlock($blockID) {

    if(!is_array($blockID)): $blockID = array($blockID); endif;

    foreach($blockID as $id):

        $globalContentBlocks = get_field('content', $id);
        foreach ($globalContentBlocks as $contentBlock):
                
            // Get block name of global block
            $layout = $contentBlock['acf_fc_layout'];
            $block  = $contentBlock[$layout];

          showContentBlock($block, $layout);
          
       endforeach;

    endforeach;
}

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

add_action('acf/init', 'add_pagebuilder_fields');
function add_pagebuilder_fields() {

    $base_dir = trailingslashit(get_template_directory());
    $dir      = 'blocks';
    $folders  = glob($base_dir.$dir.'/*');

    // Get names of blocks
    $blockNames = array();

    foreach ($folders as $folder):
        $folder = explode('/', $folder);
        $folder = end($folder);
        array_push($blockNames, $folder);
    endforeach;

    // Create layouts
    $layouts = array();
    $layouts['beam_blocks_content_globalblock'] = array(
        'key' => 'beam_blocks_content_globalblock',
        'name' => 'block',
        'label' => '1. Global block',
        'display' => 'block',
        'sub_fields' => array(
            array(
                'key' => 'beam_blocks_content_globalblock_post',
                'label' => 'Globale bouwsteen',
                'name' => 'block',
                'aria-label' => '',
                'type' => 'post_object',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'post_type' => array(
                    0 => 'block',
                ),
                'post_status' => '',
                'taxonomy' => '',
                'return_format' => 'id',
                'multiple' => 0,
                'allow_null' => 0,
                'ui' => 1,
                'bidirectional_target' => array(
                ),
            ),
        ),
        'min' => '',
        'max' => '',
    );

    $contentBlocks = array();

    foreach ($blockNames as $block):
        $jsonFile = glob($base_dir.$dir.'/'.$block.'/*.json');
        if(!empty($jsonFile)):
            $jsonFile = $jsonFile[0];
            if (file_exists($jsonFile)):
                $json = file_get_contents($jsonFile);
                $json = (array) json_decode($json);
                $key = $json['key'];
                $blockSlug = $block;
                $blockFile = glob($base_dir.$dir.'/'.$block.'/*.php');
                if(empty($blockFile)): continue; endif;
                $blockFile = $blockFile[0];
                $source = file_get_contents($blockFile);
                $blockName = get_string_between($source, '/* Block Name: ', ' */');
                if($blockName == ''): $blockName = $blockSlug; endif;
                $blockOrder = intval(get_string_between($source, '/* Order: ', ' */'));
                if(!$blockOrder): $blockOrder = 1000; endif;

                $contentblocks[] = array(
                    'order' => $blockOrder,
                    'name'  => $blockName,
                    'slug'  => $block,
                    'key'   => $key
                );
            endif;
        endif;
    endforeach;

    // Sort layouts based on label
    usort($contentblocks, fn($a, $b) => $a['order'] <=> $b['order']);
    $blockNames = $contentblocks;

    $i = 2;
    foreach ($blockNames as $block):

        $blockSlug = $block['slug'];
        $blockName = $block['name'];
        $blockKey  = $block['key'];
            
        $layouts['beam_blocks_content_'.$blockSlug] = array(
            'key' => 'beam_blocks_content_'.$blockSlug,
            'name' => $blockSlug,
            'label' => $i.'. '.$blockName,
            'display' => 'block',
            'sub_fields' => array(
                array(
                    'key' => 'beam_blocks_content_'.$blockSlug.'_clone',
                    'label' => '',
                    'name' => $blockSlug,
                    'aria-label' => '',
                    'type' => 'clone',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'clone' => array(
                        0 => $blockKey,
                    ),
                    'display' => 'group',
                    'layout' => 'block',
                    'prefix_label' => 0,
                    'prefix_name' => 0,
                ),
            ),
            'min' => '',
            'max' => '',
        );

        $i++;

    endforeach;


    acf_add_local_field_group( array(
        'key' => 'beam_blocks',
        'title' => 'Bouwstenen',
        'fields' => array(
            array(
                'key' => 'beam_blocks_content',
                'label' => 'Content',
                'name' => 'content',
                'aria-label' => '',
                'type' => 'flexible_content',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => 'page-builder',
                    'id' => '',
                ),
                'layouts' => $layouts,
                'min' => '',
                'max' => '',
                'button_label' => 'Nieuwe bouwsteen',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'block',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'guide',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'book',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'landingpage',
                ),
            ),
        ),
        'menu_order' => 1,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(
            0 => 'the_content',
        ),
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
    ) );

}



// add_filter('acf/fields/flexible_content/layout_title', 'pagebuilder_layout_custom_title', 10, 4);
function pagebuilder_layout_custom_title( $title, $field, $layout, $i ) {

    $index = $i;
    // echo '<pre>'; print_r($field); echo '</pre>';

    $blockLayout = $field['value'][$index]['acf_fc_layout'];
    // if($blockLayout != 'block'):

    //     echo 'in layout';

    //     $block = get_sub_field($blockLayout);
    //     $options = $block['options'];
    //     $blockTitle = ucfirst($options['block-title']);
        
    //     if($blockTitle != ''):
    //         // $title .= ': '.$blockTitle;
    //         $ret = sprintf($title . ': ' . '<strong>' . $blockTitle . '</strong>');
    //     endif;

    //     // Display thumbnail image.
    //     // if( $image = get_sub_field('image') ) {
    //     //     $title .= '<div class="thumbnail"><img src="' . esc_url($image['sizes']['thumbnail']) . '" height="36px" /></div>';     
    //     // }

    // endif;

    $ret = $title;
    if ($custom_title = 'je moeder') {
    $ret = sprintf($title . ': ' . '<strong>' . $custom_title . ' layout: '.$index.' - '.count($field['value'][$index] ).'</strong>');
    // $ret .= ' - test '.count( $field['value'] );
    }

    return $ret;
}


function getBlocks() {
    
    /* Basic WP query with pagination */
    $paged  = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args   = array(
            'posts_per_page'      => -1,
            'post_type'           => 'block',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'orderby'             => 'title',
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


/* Block templates ------------------------------------------------------------------------------------------ */
function acf_load_block_templates( $field ) {
    
    // reset choices
    $field['choices'] = array();
    $field['choices'][ 0 ] = 'Selecteer een template';

    $blocks = getBlocks();
    // echo '<pre>'; print_r($blocks); echo '</pre>';
    // die();

    // add array
    $blockTemplates = array();

    foreach($blocks as $block):
        $field['choices'][ $block ] = get_the_title($block);
    endforeach;

    // echo '<pre>'; print_r($field); echo '</pre>';
    // die();

    // return the field
    return $field;
    
}
add_filter('acf/prepare_field/name=block-templates', 'acf_load_block_templates');


function load_templates_ajax_handler() {

    if (!check_ajax_referer('block-templates-nonce', 'security', false)): // set up our security nonce
        wp_send_json_error('Invalid security token sent.');
        wp_die(); // if no match, exit
    endif;

    $post_id            = $_POST['post_id'];
    $block_templates_id = intval($_POST['block_templates']);

    // $contentBlocksObject = get_field_object('content', $block_templates_id);

    // // Flexible content layouts within the post of post type block
    // $contentBlocks = $contentBlocksObject['value'];

    // $templateLayouts = '';

    // $i = 0;
    // foreach($contentBlocks as $block):
    //     if($i != 0): $templateLayouts .= ','; endif;
    //     // Layout name
    //     $templateLayouts .= $block['acf_fc_layout'];
    //     $i++;
    // endforeach;

    // V2
    $globalContentBlocks = get_field('content', $block_templates_id);

    $templateLayouts2 = array();
    foreach ($globalContentBlocks as $contentBlock):
          
        // Get block name of global block
        $layout = $contentBlock['acf_fc_layout'];
        $block  = $contentBlock[$layout];

        // Put block content in more readable variables
        $content = isset($block['content']) ? $block['content'] : null;
        $options = isset($block['options']) ? $block['options'] : null;

        // Remove empty keys, casues by tabs
        unset($options['']);

        $templateLayouts2[] = array(
          'layout' => $layout,
          'content' => $content,
          'options' => $options
        );

    endforeach;

    $output = json_encode($templateLayouts2);

    echo $output;

    wp_die(); // always include this after Ajax calls
}

add_action('wp_ajax_load_templates', 'load_templates_ajax_handler');
add_action('wp_ajax_nopriv_load_templates', 'load_templates_ajax_handler');