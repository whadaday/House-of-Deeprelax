<?php
	if(!$banner): $banner = get_field('banner-promo'); endif;
	if($banner):
		$layout = 'banner';
		$content = get_field('content', $banner);
		$options = get_field('options', $banner);
		$block['content'] = $content;
		$block['options'] = $options;

		include( locate_template( 'partials/section/section-open.php', false, false ) );
		include( locate_template( 'blocks/banner/banner.php', false, false ) );
		include( locate_template( 'partials/section/section-close.php', false, false ) );
	endif;