<?php
	// Get header options
	if(is_tax('faq-category')):
		$queried_object = get_queried_object(); 
		$taxonomy = $queried_object->taxonomy;
		$term_id = $queried_object->term_id;  
		$post_id = $taxonomy . '_' . $term_id;
	endif;

	$options = get_field('options', $post_id);
	
	if($options):

		// Delete empty keys
	    unset($options['']);

		// Is header disabled?
		$disabled 	  	  = $options['disabled'];

		if(!$disabled):

			$headerType   = get_field('header-type', $post_id);
			$colorText 	  = $options['color-text'];
			$headerHeight = $options['header-height'];
			$layout 	  = $options['header-layout'];	

			if($layout == 1):

				$alignX     = $options['header-align-x'];
				$textAlign  = (isset($options['text-align']) && $options['text-align'] ) ? $options['text-align'] : 'align-left';
				if($alignX == 'align-center'): $textAlign = 'align-center'; endif;

				if($headerHeight == 'fullscreen'):
					$alignY = $options['header-align-y'];
				endif;

			endif;
	
			if($headerType == 'color'): 
				$bgColorName = get_field('background-color', $post_id);

			elseif($headerType == 'image'):
				$mobileVariant = get_field('mobile-variant', $post_id);
				$mobileColor = get_field('mobile-color', $post_id);
				if(isset($mobileColor)):
					$mobileColor = $mobileColor['background-color'];
				endif;
				$mobileColorText = get_field('mobile-color-text', $post_id);
			endif;

			// Text
			$text 			 = get_field('text', $post_id);
			$content['text'] = $text;

			// CTA
			$block['content']['cta'] = get_field('cta', $post_id) ? get_field('cta', $post_id) : null; 
			$block['options']['cta'] = $options['cta'] ? $options['cta'] : null;
			$ctaOptions = isset($block['options']['cta']) ? $block['options']['cta'] : null;
			$buttonColor  = $ctaOptions['color-btn'];

			$showScrollIndicator = (isset($options['show-scroll-indicator']) && $options['show-scroll-indicator']) ? true : false;
?>

<header
	id="header"
	data-type="<?php echo $headerType; ?>"
	<?php if(isset($bgColorName)): ?>data-color="<?php echo $bgColorName; ?>"<?php endif; ?>
	data-color-text="<?php echo $colorText; ?>"
	data-btn-color="<?php echo $buttonColor; ?>"
	data-height="<?php echo $headerHeight; ?>"
	data-type="<?php echo $headerType; ?>"
	<?php if(isset($mobileVariant) && $mobileVariant != '0'): ?>
		class="mobile-rows <?php echo $mobileVariant; ?>"
		<?php if($mobileColor): ?>data-mobile-color="<?php echo $mobileColor; ?>"<?php endif; ?>
		data-mobile-color-text="<?php echo $mobileColorText; ?>"
	<?php endif; ?>
	
>
	<?php
		// Background

		if($headerType == 'video'):
			$video = get_field('video', $post_id);
			$lazy = false;
			include( locate_template( 'partials/elements/video.php', false, false ) );
		elseif($headerType == 'image'):
			$image = get_field('image', $post_id);
			include( locate_template( 'partials/elements/image.php', false, false ) );
		endif;
	?>

	<?php 
		// Content 
		if($content['text'] || $block['content']['cta']['button'] || $block['content']['cta']['button2']):
	?>
	<div class="header-content"
		data-layout="<?php echo $layout; ?>"
		<?php if(isset($alignY)): ?>data-v-align="<?php echo $alignY; ?>"<?php endif; ?>
	>

		<div class="container">
			<div class="columns"
				<?php if(isset($alignX)): ?>data-x-align="<?php echo $alignX; ?>"<?php endif; ?>
			>

			<?php if($layout == 1): ?>

				<?php if($content['text'] || $block['content']['cta']['button'] || $block['content']['cta']['button2']): ?>
					<div class="column column-text">
						<div 
							class="content content-text" 
							<?php if(isset($textAlign)): ?>data-text-align="<?php echo $textAlign; ?>"<?php endif; ?>
						>
							<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
							<?php include( locate_template( 'partials/elements/cta.php', false, false ) ); ?>
						</div>
					</div>
				<?php endif; ?>

			<?php else: ?>

				<?php if($content['text']): ?>
					<div class="column column-text">
						<div 
							class="content content-text" 
							<?php if(isset($textAlign)): ?>data-text-align="<?php echo $textAlign; ?>"<?php endif; ?>
						>
							<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if($block['content']['cta']['button'] || $block['content']['cta']['button2']): ?>
					<div class="column column-action">
						<div class="content">
							<?php include( locate_template( 'partials/elements/cta.php', false, false ) ); ?>
						</div>
					</div>
				<?php endif; ?>

			<?php endif; ?>

			</div>
		</div>
	</div>	

	<?php endif; ?>

	<?php if($headerHeight == 'fullscreen' && $showScrollIndicator): ?>
		<a href="#section-1" class="scroll-indicator">
			<?php /*<span class="scroll-indicator-text">Scroll to play</span>*/ ?>
			<span class="scroll-indicator-mouse"></span>
		</a>
	<?php endif; ?>

	<?php
	$headerGradient = true; 
	include( locate_template( 'partials/elements/bg-gradient.php', false, false ) );
	$headerGradient = false;
	?>

</header>

<?php endif; endif; ?>