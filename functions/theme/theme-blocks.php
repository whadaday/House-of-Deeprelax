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

/**
 * Enumerate the blocks/* directory into the sorted layout list used by the
 * pagebuilder flexible-content field: per block its ACF field-group key (from
 * the block's *.json) plus its display name + order (parsed from the PHP header
 * comment). This is pure disk I/O — one glob over blocks/* plus two file reads
 * per block — whose result only changes on a code deploy or a block-field-group
 * save. It ran ongecachet on acf/init (i.e. EVERY request, frontend included),
 * ~140 filesystem ops per page. Cache it in a transient keyed on THEME_VERSION
 * so a deploy refreshes it automatically, mirroring the acf_load_json paths
 * cache (HOD-21). (HOD perf-audit — hook-gebruik)
 *
 * @return array<int,array{order:int,name:string,slug:string,key:string}>
 */
function hod_pagebuilder_blocks() {
    $cache_key = 'hod_pagebuilder_blocks_' . (defined('THEME_VERSION') ? THEME_VERSION : '1');
    $cached    = get_transient($cache_key);
    if (is_array($cached)) {
        return $cached;
    }

    $base_dir = trailingslashit(get_template_directory());
    $dir      = 'blocks';

    $contentblocks = array();

    foreach ((array) glob($base_dir . $dir . '/*') as $folder) {
        $block = basename($folder);

        $jsonFile = glob($base_dir . $dir . '/' . $block . '/*.json');
        if (empty($jsonFile) || !file_exists($jsonFile[0])) { continue; }

        $json = (array) json_decode(file_get_contents($jsonFile[0]));
        $key  = isset($json['key']) ? $json['key'] : '';

        $blockFile = glob($base_dir . $dir . '/' . $block . '/*.php');
        if (empty($blockFile)) { continue; }

        $source     = file_get_contents($blockFile[0]);
        $blockName  = get_string_between($source, '/* Block Name: ', ' */');
        if ($blockName === '') { $blockName = $block; }
        $blockOrder = intval(get_string_between($source, '/* Order: ', ' */'));
        if (!$blockOrder) { $blockOrder = 1000; }

        $contentblocks[] = array(
            'order' => $blockOrder,
            'name'  => $blockName,
            'slug'  => $block,
            'key'   => $key,
        );
    }

    usort($contentblocks, fn($a, $b) => $a['order'] <=> $b['order']);

    set_transient($cache_key, $contentblocks, 12 * HOUR_IN_SECONDS);

    return $contentblocks;
}

// Vernieuw de block-cache wanneer een veldgroep verandert (nieuw block gaat
// gepaard met een field-group-save) of bij theme-switch — zelfde triggers als
// hod_flush_acf_json_paths, zodat een nieuw block direct in de pagebuilder
// verschijnt i.p.v. tot 12u onzichtbaar te blijven.
function hod_flush_pagebuilder_blocks() {
    delete_transient('hod_pagebuilder_blocks_' . (defined('THEME_VERSION') ? THEME_VERSION : '1'));
}
add_action('acf/update_field_group', 'hod_flush_pagebuilder_blocks');
add_action('acf/trash_field_group', 'hod_flush_pagebuilder_blocks');
add_action('after_switch_theme', 'hod_flush_pagebuilder_blocks');

add_action('acf/init', 'add_pagebuilder_fields');
function add_pagebuilder_fields() {

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

    // Block-lijst uit de cache — de glob + file reads draaien alléén bij een
    // cache-miss (na deploy / field-group-save), niet meer op elke acf/init.
    $blockNames = hod_pagebuilder_blocks();

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

    // add array
    $blockTemplates = array();

    foreach($blocks as $block):
        $field['choices'][ $block ] = get_the_title($block);
    endforeach;

    // return the field
    return $field;
    
}
add_filter('acf/prepare_field/name=block-templates', 'acf_load_block_templates');


function load_templates_ajax_handler() {

    // Alleen bewerkers: dit endpoint leest ACF-block-content uit; geen anonieme
    // toegang (fix audit r2 — was nopriv geregistreerd → IDOR/info-disclosure).
    if ( ! current_user_can('edit_posts') ) {
        wp_send_json_error('forbidden', 403);
    }

    if (!check_ajax_referer('block-templates-nonce', 'security', false)): // set up our security nonce
        wp_send_json_error('Invalid security token sent.');
        wp_die(); // if no match, exit
    endif;
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
