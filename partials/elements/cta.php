<?php

	$cta 		  = isset($block['content']['cta']) ? $block['content']['cta'] : null;
	$ctaOptions   = isset($block['options']['cta']) ? $block['options']['cta'] : null;
	if(!$ctaOptions):
		$ctaOptions   = isset($block['options']['cta-options']) ? $block['options']['cta-options'] : null;
	endif;

	$showAppstore = isset($ctaOptions['show-appstore']) ? $ctaOptions['show-appstore'] : null;

	if($showAppstore):
		$align		  = $ctaOptions['btn-align'] ? $ctaOptions['btn-align'] : 'left';
	?>
		<div class="content-action" data-align="<?php echo $align; ?>">
			<?php include( locate_template( 'partials/elements/appstore.php', false, false ) ); ?>
		</div>

	<?php
	elseif($cta):
		$button 	  = $cta['button'] ? $cta['button'] : null;
		$buttonColor  = $ctaOptions['color-btn'];

		$button2   	  = $cta['button2'] ? $cta['button2'] : null;
		$buttonColor2 = $ctaOptions['color-btn2'];

		$align		  = $ctaOptions['btn-align'] ? $ctaOptions['btn-align'] : 'align-left';

		$video 		  = $ctaOptions['btn-video'];

		if( !empty($button) || !empty($button2) ):
?>

			<div class="content-action" data-align="<?php echo $align; ?>">
				
				<?php if(!empty($button)):
					$link_url 	 = $button['url'];
				    $link_title  = $button['title'];
				    $link_target = $button['target'] ? $button['target'] : '_self';

				    $hideNav = get_field('hide-nav-footer');
					if($hideNav):
						$ctaOverrule = get_field('cta-overrule');
						if($ctaOverrule && !$video):
							$link_url = $ctaOverrule;
							$link_target = '_blank';
						endif;
					endif;
				?>
				    <a
				    	<?php if($video): ?>data-fancybox="video-gallery"<?php endif; ?>
				    	class="btn"
				    	data-btn-color="<?php echo $buttonColor; ?>"
				    	href="<?php echo esc_url( $link_url ); ?>"
				    	target="<?php echo esc_attr( $link_target ); ?>">
				    	<?php echo esc_html( $link_title ); ?>
				    </a>
				<?php endif; ?>

				<?php if(!empty($button2)):
					$link_url 	 = $button2['url'];
				    $link_title  = $button2['title'];
				    $link_target = $button2['target'] ? $button2['target'] : '_self';
				?>
					<a 
						class="btn"
						data-btn-color="<?php echo $buttonColor2; ?>"
						href="<?php echo esc_url( $link_url ); ?>"
						target="<?php echo esc_attr( $link_target ); ?>">
						<?php echo esc_html( $link_title ); ?>
					</a>
				<?php endif; ?>

			</div>

<?php
		endif; 

	endif;
?>