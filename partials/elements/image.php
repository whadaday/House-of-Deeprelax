<?php
	$overlay 		 = isset($image['overlay']) ? $image['overlay'] : null;
	$imageMobile = isset($image['image-mobile']) ? $image['image-mobile'] : false;
	$image 	 		 = isset($image['image']) ? $image['image'] : $content['image'];

	if($image != ''):

	$parralax 	= isset($options['parralax']) && $options['parralax'] ? 1 : 0;	

	if($image['subtype'] != 'svg+xml'):
		$ratio 		= getImageDimensionPercentage($image);
		$crop  		= getImageCrop($image);
	else:
		// echo '<pre>'; print_r($image); echo '</pre>';
		unset($ratio);
		$crop = false;
	endif;

	if($parralax):
		$imageClass = 'image-holder'; //parralax-holder';
	else:
		$imageClass = 'image-holder';
	endif;

	global $blockIndex;
	$priority = ($blockIndex == 1) ? 'high' : 'auto';
?>

<div class="content-image" <?php if(isset($imageRatio) && $imageRatio == 'standard'): ?>style="padding-bottom: <?php echo $ratio; ?>%;"<?php endif; ?>>

	<?php if($imageMobile):
		$imageMobileCrop = getImageCrop($imageMobile);
	?>
			
		<div class="<?php echo $imageClass; ?> image-mobile">
			<picture class="picture-lazy" <?php if($imageMobileCrop): ?>data-crop="<?php echo $imageMobileCrop; ?>"<?php endif; ?>>
			    <source media="(max-width: 786px)" srcset="<?php echo $imageMobile['sizes']['medium_large']; ?> 1x, <?php echo $imageMobile['sizes']['1536x1536']; ?> 2x">
			    <img
			    	data-src="<?php echo $imageMobile['sizes']['1536x1536']; ?>"
			    	class="lazy"
			    	alt="<?php echo $imageMobile['alt']; ?>"
			    	width="<?php echo $imageMobile['sizes']['1536x1536-width']; ?>"
			    	height="<?php echo $imageMobile['sizes']['1536x1536-height']; ?>"
			    	fetchpriority="<?php echo $priority; ?>"
			    	loading="lazy"
			    />
			</picture>
			<?php if($imageMobile['subtype'] != 'svg+xml'): ?>
				<div class="lazy-placeholder">
					<img
						src="<?php echo $imageMobile['sizes']['placeholder']; ?>"
						fetchpriority="<?php echo $priority; ?>"
						loading="lazy"
						alt="<?php echo $imageMobile['alt']; ?>"
					/>
				</div>
			<?php endif; ?>
		</div>

	<?php endif; ?>
	
	<div class="<?php echo $imageClass; ?>">
		<picture class="picture-lazy" <?php if($crop): ?>data-crop="<?php echo $crop; ?>"<?php endif; ?>>

				<?php if(isset($imageSize) && $imageSize == 'xs'): ?>

					<source media="(min-width: 768px)" srcset="<?php echo $image['sizes']['medium']; ?> 1x, <?php echo $image['sizes']['medium_large']; ?> 2x">

				<?php elseif(isset($imageSize) && $imageSize == 's'): ?>

					<source media="(min-width: 768px)" srcset="<?php echo $image['sizes']['medium_large']; ?> 1x, <?php echo $image['sizes']['large']; ?> 2x">

				<?php elseif(isset($imageSize) && $imageSize == 'm'): ?>

					<source media="(min-width: 1920px)" srcset="<?php echo $image['sizes']['1536x1536']; ?> 1x, <?php echo $image['sizes']['cover']; ?> 2x">
					<source media="(min-width: 1200px)" srcset="<?php echo $image['sizes']['large']; ?> 1x, <?php echo $image['sizes']['1536x1536']; ?> 2x">
					<source media="(min-width: 768px)" srcset="<?php echo $image['sizes']['medium_large']; ?> 1x, <?php echo $image['sizes']['large']; ?> 2x">
					

				<?php else: ?>

					<source media="(min-width: 2560px)" srcset="<?php echo $image['sizes']['cover']; ?> 1x, <?php echo $image['sizes']['cover']; ?> 2x">
					<source media="(min-width: 1920px)" srcset="<?php echo $image['sizes']['2048x2048']; ?> 1x, <?php echo $image['sizes']['cover']; ?> 2x">
					<source media="(min-width: 1200px)" srcset="<?php echo $image['sizes']['1536x1536']; ?> 1x, <?php echo $image['sizes']['cover']; ?> 2x">
					<source media="(min-width: 768px)" srcset="<?php echo $image['sizes']['large']; ?> 1x, <?php echo $image['sizes']['2048x2048']; ?> 2x">
					

				<?php endif; ?>

				<source media="(max-width: 320px)" srcset="<?php echo $image['sizes']['medium']; ?> 1x, <?php echo $image['sizes']['medium_large']; ?> 2x">
				<source media="(max-width: 480px)" srcset="<?php echo $image['sizes']['medium_large']; ?> 1x, <?php echo $image['sizes']['medium_large']; ?> 2x">
				<source media="(max-width: 787px)" srcset="<?php echo $image['sizes']['medium_large']; ?> 1x, <?php echo $image['sizes']['1536x1536']; ?> 2x">

		    <img
		    	data-src="<?php echo $image['sizes']['cover']; ?>"
		    	class="lazy"
		    	alt="<?php echo $image['alt']; ?>"
		    	width="<?php echo $image['sizes']['cover-width']; ?>"
		    	height="<?php echo $image['sizes']['cover-height']; ?>"
		    	fetchpriority="<?php echo $priority; ?>"
		    	loading="lazy"
		    />
		</picture>
		<?php if($image['subtype'] != 'svg+xml'): ?>
			<div class="lazy-placeholder">
				<img
					src="<?php echo $image['sizes']['placeholder']; ?>"
					fetchpriority="<?php echo $priority; ?>"
					alt="<?php echo $image['alt']; ?>"
				/>
			</div>
		<?php endif; ?>
	</div>

	<?php 
    if(isset($overlay) && $overlay['active']):
      include( locate_template( 'partials/elements/image-overlay.php', false, false ) );
    endif;
  ?>

</div> 

<?php endif; ?>