<?php
	$card_title = $card['title'];
	$link 		= $card['url'];	
	$background = $card['background-type'];
?>
	<div class="deeprelax-card">
		<a class="card-link" href="<?php echo $link; ?>" aria-label="Lees meer"></a>
		<div class="card-image-holder">
			<span class="card-title"><?php echo $card_title; ?></span>
			<span class="card-btn card-btn-dummy"><span>Lees meer</span></span>
        	<?php
				if($background == 'video'):
					$video = $card['video'];
					include( locate_template( 'partials/elements/video.php', false, false ) );
				elseif($background == 'image'):
					$image = $card['image'];
					$imageSize = 's';
					include( locate_template( 'partials/elements/image.php', false, false ) );
					$imageSize = '';
				endif;

			?>
	    </div>
	</div>