<?php
	/* Block Name: 1 kolom */
	/* Order: 10 */
	
	// Options
	$type        = (isset($content['content-type']) && $content['content-type'] ) ? $content['content-type'] : 'text';
	$imageRatio  = (isset($options['image-height']) && $options['image-height'] ) ? $options['image-height'] : 'standard';
	$textAlign   = $options['text-align'];
	$width 	     = $options['column-width'];
	$xAlign      = $options['column-align'];

	$xOffset = null;
	if($xAlign != 'align-center' && (($width == 'compact') || $width == 'small' || $width == 'normal') ):
		$xOffset   = $options['column-offset'];
	endif;

	$height 	 = $options['column-height'];
	if($height != 'content'):
		$valign  = $options['column-align-v'];
	endif;

	$textBlurred = $options['text-blurred'];

	if($type == 'video' && $imageRatio == 'standard'): $imageRatio = 'landscape'; endif;

	$link = (isset($content['link']) && $content['link'] != 'none') ? $content['link'] : false;
?>

<div 
	class="columns"
	data-width="<?php echo $width; ?>"
	<?php if(isset($height)): ?>data-height="<?php echo $height; ?>"<?php endif; ?>
	<?php if(isset($valign)): ?>data-v-align="<?php echo $valign; ?>"<?php endif; ?>
	<?php if(isset($xAlign)): ?>data-x-align="<?php echo $xAlign; ?>"<?php endif; ?>
	<?php if(isset($xOffset)): ?>data-x-offset="<?php echo $xOffset; ?>"<?php endif; ?>
	<?php if(isset($textBlurred)): ?>data-blurred="<?php echo $textBlurred; ?>"<?php endif; ?>
	
>
	<div class="column column-text" data-height="<?php echo $imageRatio; ?>">
		<div class="content content-text" data-text-align="<?php echo $textAlign; ?>">
			<?php
			// Text
			if($type == 'text'):	
				include( locate_template( 'partials/elements/text.php', false, false ) );
				include( locate_template( 'partials/elements/cta.php', false, false ) ); 

			// Embed
			elseif($type == 'embed'):	
				$embed = $content['embed'];
				if($embed): include( locate_template( 'partials/elements/embed.php', false, false ) ); endif;

			// Image
			elseif($type == 'image'):
				$image = $content['media-image'];
				if($image): include( locate_template( 'partials/elements/image.php', false, false ) ); endif;

			// Video
			elseif($type == 'video'):
				$video = $content['media-video'];
				if($video): include( locate_template( 'partials/elements/video.php', false, false ) ); endif;
			endif;
			

			if($link && ($type == 'video' || $type == 'image')): 
				if($link == 'link' || $link == 'video'):
					$url = $content['url'];
				elseif($link == 'page'):
					$url = $content['url-page'];
				endif;
			?>
				<a 
					<?php if($link == 'video'): ?>data-fancybox="video-gallery"<?php endif; ?>
					class="image-link-overlay image-link-overlay-play"
					target="_blank"
					href="<?php echo esc_url( $url ); ?>">

					<?php if($link == 'video'): ?>
						<span class="btn-video">
							<i class="icon-play">
								<?php
						      		$icon = get_template_directory().'/assets/images/icon-play.svg';
						      		echo file_get_contents($icon);
						      	?>
							</i>
						</span>
					<?php endif; ?>
				</a>
			<?php endif; ?>
			
		</div>
	</div>
</div>