<?php
	$overlayColor 	= $overlay['color'];
	$overlayOpacity = $overlay['opacity'] / 100;
?>
	<div class="image-overlay" data-color="<?php echo esc_attr($overlayColor); ?>" style="--overlay-opacity: <?php echo $overlayOpacity; ?>"></div>