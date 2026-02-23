<?php 
	$post_id		   = get_the_ID();
	$lang 		       = getLang();
	$navigation 	   = get_field('navigation', $lang);
	$hide_announcement = get_field('hide-announcement', $post_id);

	if(!$hide_announcement):
		$show_announcement = get_field('show-announcement', $post_id);
		if(!$show_announcement):
			$show_announcement = $navigation['show-announcement'];
			$announcement  	   = $navigation['announcement'];
		endif;

		if($show_announcement): 
			
			if(!isset($announcement)):
				$announcement  = get_field('announcement', $post_id);
			endif;

			$text 		   = $announcement['text'];
			$bgColor 	   = (isset($announcement['background-color'])) ? $announcement['background-color'] : 0;
			$textColor 	   = (isset($announcement['text-color'])) ? $announcement['text-color'] : 0;
	?>
		<div id="nav-announcement"
			<?php if($bgColor): ?> data-color="<?php echo $bgColor; ?>" <?php endif; ?>
			<?php if($textColor): ?> data-color-text="<?php echo $textColor; ?>" <?php endif; ?>
		>
			<div class="container">
				<div class="content">
					<?php echo $text; ?>
				</div> 
			</div>
		</div>
	<?php 
		endif; 
	endif; 
?> 