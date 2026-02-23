<?php
/* Custom code to header */
function add_custom_head() {
	$code = get_field('code-head', 'theme');
	echo $code;
}

add_action('wp_head', 'add_custom_head', 1);

/* Custom css to header */
function add_custom_css() {
	$code = get_field('code-css', 'theme');
	echo '<style>'.$code.'</style>';
}

add_action('wp_head', 'add_custom_css', 100);

/* Custom code to body */
function add_custom_body() {
	$code = get_field('code-body', 'theme');
	echo $code;
}

add_action('wp_body_open', 'add_custom_body', 1);

/* Custom code to footer */
function add_custom_footer() {
	$code = get_field('code-footer', 'theme');
	echo $code;
}

add_action('wp_footer', 'add_custom_footer', 100);

