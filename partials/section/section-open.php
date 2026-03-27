<?php
	/* Options */
	// $options
	global $blockIndex;
	$blockName = isset($options['block-title']) && $options['block-title'] != '' ? slugify($options['block-title']) : 'section-'.$blockIndex;

	// echo '<pre>'; print_r($options); echo '</pre>';

	if(isset($options['whitespace'])):
		$whitespace   = $options['whitespace'];
		$whitespaceT  = ($whitespace['top'] != 'none') ? $whitespace['top'] : 0;
		$whitespaceB  = ($whitespace['bottom'] != 'none') ? $whitespace['bottom'] : 0;
	endif;

	if(isset($options['rounded-corners'])):
		$roundedCorners   = $options['rounded-corners'];
		$roundedCornersT  = (in_array('top', $roundedCorners )) ? 1 : 0;
		$roundedCornersB  = (in_array('bottom', $roundedCorners )) ? 1 : 0;
	endif;

	$bg = (isset($options['background'])) ? $options['background'] : false;

	$bgColor 	  = ($bg == 'color' && isset($options['bg-color']['background-color']) && $options['bg-color']['background-color'] != 'transparent') ? $options['bg-color']['background-color'] : 0;

	$innerColor  = (isset($options['show-inner-color']) && $options['show-inner-color'] ) ? $options['bg-inner-color']['background-color'] : 0;

	$textColor 	  = (isset($options['text-color'])) ? $options['text-color'] : 0;

	$container    = (isset($options['container']) && $options['container']) ? 1 : 0;

	$showImageBackground = (isset($options['show-image-background']) && $options['show-image-background']) ? 1 : 0;

	$move = (isset($options['block-transition']) && ($options['block-transition'] == 'move-top' || $options['block-transition'] == 'move-bottom')) ? $options['block-transition'] : false;

	$ctaOptions  = isset($options['cta-options']) ? $options['cta-options'] : null;
	$buttonColor = isset($ctaOptions['color-btn']) ? $ctaOptions['color-btn'] : null;
	$formBtnColor = isset($options['contact-form-btn-class']) ? $options['contact-form-btn-class'] : null;
?>
<section
	id="<?php echo $blockName; ?>"
	class="section section-<?php echo $layout; ?>"
	
	<?php if($bgColor): ?> data-color="<?php echo $bgColor; ?>" <?php endif; ?>

	<?php if($innerColor): ?> 
		data-bg-inner="<?php echo $innerColor; ?>"
		data-bg-inner-position="<?php echo $options['bg-inner-position']; ?>"
	<?php endif; ?>

	<?php if($move): ?>data-move="<?php echo $move; ?>"<?php endif; ?>
	
	<?php if(isset($options['whitespace']) && $whitespaceT): ?>data-whitespace-top="<?php echo $whitespaceT; ?>"<?php endif; ?>
	<?php if(isset($options['whitespace']) && $whitespaceB): ?>data-whitespace-bottom="<?php echo $whitespaceB; ?>"<?php endif; ?>

	<?php if(isset($options['rounded-corners'])): ?>
		data-rounded-top="<?php echo $roundedCornersT; ?>"
		data-rounded-bottom="<?php echo $roundedCornersB; ?>"
	<?php endif; ?>
	
	<?php if($textColor): ?> data-color-text="<?php echo $textColor; ?>" <?php endif; ?>

	<?php if($buttonColor): ?> data-btn-color="<?php echo $buttonColor; ?>" <?php endif; ?>

	<?php if($formBtnColor): ?> data-form-btn="<?php echo esc_attr($formBtnColor); ?>" <?php endif; ?>

	<?php if(isset($container)): ?> data-container="<?php echo $container; ?>" <?php endif; ?>

	<?php if(isset($showImageBackground)): ?> data-image-background="<?php echo $showImageBackground; ?>" <?php endif; ?>

>
	<?php
		if($bg == 'image'):
			$image = $options['bg-image'];
			?><div class="section-bg"><?php include( locate_template( 'partials/elements/image.php', false, false ) ); ?></div><?php

		elseif($bg == 'video'):
			$video = $options['bg-video'];
			?><div class="section-bg"><?php include( locate_template( 'partials/elements/video.php', false, false ) ); ?></div><?php

		endif;
	?>
	<div class="container content-animate">