<?php
	
	$banner = $content['banner-promo'];
	$content = get_field('content', $banner);
	$options = get_field('options', $banner);
	$block['content'] = $content;
	$block['options'] = $options;

	include( locate_template( 'blocks/banner/banner.php', false, false ) );

?>