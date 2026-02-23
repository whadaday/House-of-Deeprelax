<?php
	/* Block Name: 2 kolommen (Media + Tekst) */
	/* Order: 10 */

	$type      	 = (isset($content['media-type']) && $content['media-type'] ) ? $content['media-type'] : 'image';
	$reverse   	 = (isset($options['reverse']) && $options['reverse'] ) ? 1 : 0;
	$hideMobile  = (isset($options['image-hide-mobile']) && $options['image-hide-mobile'] ) ? 1 : 0;
	$firstMobile = (isset($options['text-first-mobile']) && $options['text-first-mobile'] ) ? 1 : 0;
	$imageRatio  = (isset($options['image-height']) && $options['image-height'] ) ? $options['image-height'] : 'standard';
	$textAlign 	 = (isset($options['text-align']) && $options['text-align'] ) ? $options['text-align'] : 'align-left';
	$vAlign 	 = (isset($options['align-vertical']) && $options['align-vertical'] ) ? $options['align-vertical'] : 'top';

	$columnImage = $options['column-image'];
	$columnText  = $options['column-text'];

	$iColumns    = intval($columnImage['column-width'])+intval($columnImage['column-offset'])+intval($columnText['column-width'])+intval($columnText['column-offset']);

	$imageOverlay = (isset($content['show-image-overlay']) && $content['show-image-overlay']) ? $content['image-overlay'] : null;

	if($type == 'video' && $imageRatio == 'standard'): $imageRatio = 'landscape'; endif;
?>
<div
	data-columns="<?php echo $iColumns; ?>"
	class="columns columns-image-text"
	data-reverse="<?php echo $reverse; ?>"
	data-image-width="<?php echo $columnImage['column-width']; ?>"
	data-image-offset="<?php echo $columnImage['column-offset']; ?>"
	<?php if($columnImage['off-container'] && $columnImage['column-offset'] == 0): ?>
		data-image-offgrid="<?php echo $columnImage['off-container']; ?>"
	<?php endif; ?>
	<?php if($firstMobile): ?>data-mobile-text="1"<?php endif; ?>


	data-text-width="<?php echo $columnText['column-width']; ?>"
	data-text-offset="<?php echo $columnText['column-offset']; ?>"
	data-v-align="<?php echo $vAlign; ?>"
>
	
	<div class="column column-media<?php if($hideMobile): ?> hide-mobile<?php endif; ?>" data-height="<?php echo $imageRatio; ?>">
		<?php if($type == 'text'): ?>
			<div class="content content-text" data-text-align="<?php echo $textAlign; ?>">
				<?php echo $content['text-2']; ?>
		<?php else: ?>
			<div class="content">
		<?php endif; ?>

			<?php 
			// Embed
			if($type == 'embed'):	
				$embed = $content['embed'];
				if($embed): include( locate_template( 'partials/elements/embed.php', false, false ) ); endif;

			// Image
			elseif($type == 'image'):
				$image = $content['media-image'];
				if($image): 
					$imageSize = 'm';
					include( locate_template( 'partials/elements/image.php', false, false ) );
					$imageSize = '';
				endif;

			// Video
			elseif($type == 'video'):
				$video = $content['media-video'];
				if($video): include( locate_template( 'partials/elements/video.php', false, false ) ); endif;
			endif;
			
			?>

			<?php if($imageOverlay): 
				$crop = getImageCrop($imageOverlay);
			?>
				<div class="image-holder image-second">
					<picture class="picture-lazy" <?php if($crop): ?>data-crop="<?php echo $crop; ?>"<?php endif; ?>>
					    <?php /*<source media="(min-width: 768px)" srcset="<?php echo $image['sizes']['large']; ?>" /> */ ?>
					    <img
					    	data-src="<?php echo $imageOverlay['sizes']['large']; ?>"
					    	class="lazy"
					    	alt="<?php echo $imageOverlay['alt']; ?>"
					    	width="<?php echo $imageOverlay['sizes']['large-width']; ?>"
					    	height="<?php echo $imageOverlay['sizes']['large-height']; ?>"
					    />
					</picture>
					<?php if($imageOverlay['subtype'] != 'svg+xml'): ?>
						<div class="lazy-placeholder"><img src="<?php echo $imageOverlay['sizes']['placeholder']; ?>" alt="<?php echo $imageOverlay['alt']; ?>" /></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="column column-text">
		<div class="content content-text" data-text-align="<?php echo $textAlign; ?>">
			<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
			<?php include( locate_template( 'partials/elements/cta.php', false, false ) ); ?>
		</div>
	</div>

	<?php
		if($columnImage['off-container'] && $columnImage['column-offset'] == 0):
			$content['image'] = $options['image-extra'];
			if($content['image']): 
				?>
				<div class="image-extra<?php if($hideMobile): ?> hide-mobile<?php endif; ?>">
					<?php
						$imageSize = 'xs';
							include( locate_template( 'partials/elements/image.php', false, false ) );
						$imageSize = '';
					?>
				</div>
			<?php endif;
		endif;
	?>
</div>