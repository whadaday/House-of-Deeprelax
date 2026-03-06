<?php

function wpb_mce_buttons_2($buttons) {
    array_unshift($buttons, 'styleselect');
    return $buttons;
}
add_filter('mce_buttons_2', 'wpb_mce_buttons_2');

/*
* Callback function to filter the MCE settings
*/
 
function my_mce_before_init_insert_formats( $init_array ) {  
 
// Define the style_formats array
 
    $style_formats = array(  
/*
* Each array child is a format with it's own settings
* Notice that each array has title, block, classes, and wrapper arguments
* Title is the label which will be visible in Formats menu
* Block defines whether it is a span, div, selector, or inline style
* Classes allows you to define CSS classes
* Wrapper whether or not to add a new block-level element around any selected elements
*/
        array(  
            'title' => 'Small',  
            'block' => 'span',  
            'classes' => 'small',
            'wrapper' => true, 
        ), 

        array(  
            'title' => 'Lead',  
            'block' => 'span',  
            'classes' => 'lead',
            'wrapper' => true, 
        ),  

        array(  
            'title' => 'Style p',  
            'block' => 'span',  
            'classes' => 'style-p',
            'wrapper' => true, 
        ), 

        array(  
            'title' => 'Style H1',  
            'block' => 'span',  
            'classes' => 'style-h1',
            'wrapper' => true, 
        ),  

        array(  
            'title' => 'Style H2',  
            'block' => 'span',  
            'classes' => 'style-h2',
            'wrapper' => true, 
        ),  

        array(  
            'title' => 'Style H3',  
            'block' => 'span',  
            'classes' => 'style-h3',
            'wrapper' => true, 
        ),  

        array(  
            'title' => 'Style H4',  
            'block' => 'span',  
            'classes' => 'style-h4',
            'wrapper' => true, 
        ),  

        array(  
            'title' => 'Style H5',  
            'block' => 'span',  
            'classes' => 'style-h5',
            'wrapper' => true, 
        ),  

        array(
            'title' => 'Style H6',
            'block' => 'span',
            'classes' => 'style-h6',
            'wrapper' => true,
        ),

    );  
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode( $style_formats );  
     
    return $init_array;  
   
} 
// Attach callback to 'tiny_mce_before_init' 
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' ); 

function custom_editor_styles() {
	add_editor_style('style-editor.css');
}

add_action('init', 'custom_editor_styles');

function custom_mce_css( $mce_css ) {
	if ( ! empty( $mce_css ) ) {
		$mce_css .= ',';
	}
	$mce_css .= get_template_directory_uri() . '/style-editor.css?v=' . THEME_VERSION;
	return $mce_css;
}
add_filter( 'mce_css', 'custom_mce_css' );


add_filter( 'wp_editor_settings', 'wp_editor_settings' );
add_filter( 'mce_buttons', 'tinymce_buttons' );
// If you want to customize the other button rows, there are filters mce_buttons_2, mce_buttons_3 and mce_buttons_4 eagerly waiting for you.


function wp_editor_settings( $settings ) {
    // echo '<pre>'; print_r($settings); echo '</pre>';

    $new_settings = [
        'textarea_rows'         => 8,
        'tinymce'               => [
            'wp_autoresize_on'  => false,
            'resize'            => false,
            'statusbar'         => false
        ],
        'wpautop'               => true,
        'media_buttons'         => false,
        'editor_height'         => '',
        'quicktags'             => false,
    ];

    $settings = array_merge($settings, $new_settings);

    return $settings;
}

function tinymce_buttons( $buttons ) {

    // echo '<pre>'; print_r($buttons); echo '</pre>';

    $buttons = [
        'formatselect',
        'bold',
        'italic',
        'bullist',
        'numlist',
        'link',
        'styleselect',
        'forecolor',
    ];

    return $buttons;
}

function mce_block_formats( $init ) {
    $block_formats = array(
        'Text=p',
        'H1=h1',
        'H2=h2',
        'H3=h3',
        'H4=h4',
        'H5=h5',
        'H6=h6',
    );
    $init['block_formats'] = implode( ';', $block_formats );
 
    return $init;
}
add_filter( 'tiny_mce_before_init', 'mce_block_formats' );

function add_tinymce_script($hook) {

    // wp_register_script( 'jQuery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js', null, null, true );
    // wp_enqueue_script('jQuery');

    wp_register_script( 'tinymce', 'https://cdn.tiny.cloud/1/7dqlc6w8js2hhamro056gkqfq5unuyvtwirkwej1vuc8jhfc/tinymce/6/tinymce.min.js', null, null, true );
    wp_enqueue_script('tinymce');

    wp_register_script( 'tinymcejquery', 'https://cdn.tiny.cloud/1/7dqlc6w8js2hhamro056gkqfq5unuyvtwirkwej1vuc8jhfc/tinymce/6/jquery.tinymce.min.js', null, null, true );
    wp_enqueue_script('tinymcejquery');

    wp_enqueue_script('custom-tinymce-jquery', get_template_directory_uri() .'/assets/javascript/scripts/tinymce.js', array(), THEME_VERSION, 'all');

}

// add_action('admin_enqueue_scripts', 'add_tinymce_script');