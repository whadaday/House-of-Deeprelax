<?php 
	/* Block Name: Image */

	$image    = $content['image'];
	$width    = $options['width'];
	$height   = $options['height'];
	$scaling  = $options['scaling'];
	$crop     = getImageCrop($image);
?>
<div class="columns" data-width="<?php echo $width; ?>" data-height="<?php echo $height; ?>" data-scaling="<?php echo $scaling; ?>">
	<div class="column">
		<div class="content">
			<?php
				$isCoverImage = true;
				include( locate_template( 'partials/elements/image.php', false, false ) );
			?>
		</div>
	</div>
</div>  