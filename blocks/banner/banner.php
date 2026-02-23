<?php 
	/* Block Name: Banner */
	/* Order: 60 */

	// echo '<pre>'; print_r($content); echo '</pre>';

	$align 	      	= $options['align'];
	$textColor 	  	= $options['text-color'];
	$bannerGradient = $options['banner-gradient'];
	$bgHeroColor  	= $options['background-color-banner'];
	$bannerType   	= $options['banner-type'];

	$cta 		  	= isset($content['cta']) ? $content['cta'] : null;
	$button 	  	= isset($cta['button']) ? $cta['button'] : null;
	$button2   	  	= isset($cta['button2']) ? $cta['button2'] : null;
?>

<div class="columns banner-promo" data-align="<?php echo $align; ?>" data-color-text="<?php echo $textColor; ?>">
	
	<?php
		if( !empty($button) && empty($button2) ):

			$link_url 	 = $button['url'];
		    $link_title  = $button['title']; 
		    $link_target = $button['target'] ? $button['target'] : '_self';
	?>

	    <a
	    	class="banner-link"
	    	href="<?php echo esc_url( $link_url ); ?>"
	    	target="<?php echo esc_attr( $link_target ); ?>">
	    </a>

	<?php endif; ?>

	<div 
		class="banner-overlay-container <?php if($bannerGradient): ?>overlay-has-color<?php endif; ?>"
		<?php if($bannerGradient): ?>data-color="<?php echo $bgHeroColor; ?>"<?php endif; ?>
		>
		
		<?php
			if($bannerType == 'image'):
				$image = $content['image'];
				$imageSize = 'm';
				include( locate_template( 'partials/elements/image.php', false, false ) );
				$imageSize = '';

			elseif($bannerType == 'video'):
				$video = $content['video'];
				include( locate_template( 'partials/elements/video.php', false, false ) );

			endif;
		?>

		<div class="banner-overlay"></div>
	</div>

	<div class="column column-text">
		<div class="content content-text">
			<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
			<?php if( !empty($button) && empty($button2) ): ?>
				<span class="card-btn card-btn-dummy"><span><?php echo $link_title; ?></span></span>
			<?php else: ?>
				<?php include( locate_template( 'partials/elements/cta.php', false, false ) ); ?>
			<?php endif; ?>
		</div>
	</div>

</div>

