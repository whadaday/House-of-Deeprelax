<?php
	$lang = getLang();
	$globalBlockId = get_field('404', $lang);
	if(!$globalBlockId): 
		wp_redirect('/');
		exit;
	endif;

  	get_header();
      
	showGlobalBlock($globalBlockId);
	
  	get_footer();
?>