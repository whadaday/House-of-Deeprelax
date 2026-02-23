<?php 
	$post_id		   = get_the_ID();
	$lang 		       = getLang();
	$navigation 	   = get_field('navigation', $lang);
	$hide_bottombanner = get_field('hide-bottombanner', $post_id);

	if(!$hide_bottombanner):
		$show_bottombanner = get_field('show-bottombanner', $post_id);
		if(!$show_bottombanner):
			$show_bottombanner = $navigation['show-bottombanner'];
			$bottombanner  	   = $navigation['bottombanner'];
		endif;

		if($show_bottombanner): 
			
			if(!isset($bottombanner)):
				$bottombanner  = get_field('bottombanner', $post_id);
			endif;

			$text 		   = $bottombanner['text'];
			$button        = $bottombanner['button'];
			$buttonColor   = $bottombanner['color-btn'];
			$bgColor 	   = (isset($bottombanner['background-color'])) ? $bottombanner['background-color'] : 0;
			$textColor 	   = (isset($bottombanner['text-color'])) ? $bottombanner['text-color'] : 0;
			$ShowAfterHeader = (isset($bottombanner['show-after-header'])) ? $bottombanner['show-after-header'] : 0;
	?>
		<div id="nav-bottombanner"
			<?php if($bgColor): ?> data-color="<?php echo $bgColor; ?>" <?php endif; ?>
			<?php if($textColor): ?> data-color-text="<?php echo $textColor; ?>" <?php endif; ?>
			<?php if($ShowAfterHeader): ?> data-show-after-header="<?php echo $ShowAfterHeader; ?>" <?php endif; ?>
		>
			<div class="container">
				<div class="content">
					<?php echo $text; ?>
					<?php if(!empty($button)): ?>
						<div class="content-action">
							<?php
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
						    	class="btn"
						    	data-btn-color="<?php echo $buttonColor; ?>"
						    	href="<?php echo esc_url( $link_url ); ?>"
						    	target="<?php echo esc_attr( $link_target ); ?>">
						    	<?php echo esc_html( $link_title ); ?>
						    </a>
					    </div>
					<?php endif; ?>
				</div> 
			</div>
		</div>
	<?php 
		endif; 
	endif; 
?> 