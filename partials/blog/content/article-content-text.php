<?php
	$text = $content['text'];
	
	$showToc = get_field('show-toc');
	if($showToc):
		$text = addTocTitlesToText($text);
	endif;

	echo $text;
?>
