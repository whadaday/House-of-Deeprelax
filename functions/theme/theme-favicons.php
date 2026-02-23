<?php

function add_icons() {
	
	$appicon = get_field('site-appicon', 'theme');
	$favicon = get_field('site-favicon', 'theme');

	if($favicon): ?> <link rel="shortcut icon" type="image/png" href="<?php echo $favicon; ?>"><?php endif;
  	if($appicon): ?><link rel="apple-touch-icon" href="<?php echo $appicon; ?>" /><?php endif;
  	
}

add_action('wp_head', 'add_icons');
add_action('admin_head', 'add_icons' );