<?php
	if(is_archive() || is_category() ):
		$showCta = true;
	else:
		$showCta = get_field('show-cta');
	endif;

	if($showCta):

		global $blockIndex; 
		$lang = getLang();

		$globalBlockId = hod_option('cta', $lang);
	  	if($globalBlockId): showGlobalBlock($globalBlockId); endif;
	endif;