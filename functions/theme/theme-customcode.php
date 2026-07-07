<?php
/* Custom code to header */
function add_custom_head() {
	$code = hod_option('code-head');
	echo $code;
}

add_action('wp_head', 'add_custom_head', 1);

/* Custom css to header */
function add_custom_css() {
	$code = hod_option('code-css');
	echo '<style>'.$code.'</style>';
}

add_action('wp_head', 'add_custom_css', 100);

/* Custom code to body */
function add_custom_body() {
	$code = hod_option('code-body');
	echo $code;
}

add_action('wp_body_open', 'add_custom_body', 1);

/* Custom code to footer */
function add_custom_footer() {
	$code = hod_option('code-footer');
	echo $code;
}

add_action('wp_footer', 'add_custom_footer', 100);

