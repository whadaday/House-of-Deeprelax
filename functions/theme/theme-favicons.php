<?php

function add_icons() {
	
	$appicon = hod_option('site-appicon');
	$favicon = hod_option('site-favicon');

	if($favicon): ?> <link rel="shortcut icon" type="image/png" href="<?php echo $favicon; ?>"><?php endif;
  	if($appicon): ?><link rel="apple-touch-icon" href="<?php echo $appicon; ?>" /><?php endif;
  	
}

add_action('wp_head', 'add_icons');
add_action('admin_head', 'add_icons' );