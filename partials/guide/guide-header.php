<?php
	$post_id 	= get_the_ID();

	$gName 		= get_field('guide-name');

	$eyebrow 	= get_field('guide-overview-eyebrow');
	$title 		= get_field('guide-overview-title');
	$text 		= get_field('guide-overview-text');
	
	$intro 		= get_field('guide-intro-text');
	$audio 		= get_field('guide-intro-audio');
	$button 	= get_field('guide-intro-link');

	$textColor  = get_field('text-color');

	$category = '';
	$categoryID = getArticleCategoryID($post_id); 
	if($categoryID):
		$category = get_term_by('id', $categoryID, 'category');
	endif; 

	$previous_post = get_previous_post_id();
	$next_post = get_next_post_id();

?>
<header class="header-guide">
	<div class="header-image">
		<?php
			$image = get_field('guide-overview-image');
			$isCoverImage = true;
			include( locate_template( 'partials/elements/image.php', false, false ) );
		?>
	</div>
	<div class="guide-overview">
		<div class="container">
			<div class="columns">
				<div class="column column-image">
					<?php
						$image_id 	= get_post_thumbnail_id($post_id);
						$content['image'] = acf_get_attachment($image_id); 
						include( locate_template( 'partials/elements/image.php', false, false ) );
					?>
				</div>
				<div class="column column-text">
					<div class="content" data-color-text="<?php echo $textColor; ?>">
						<?php if($gName): ?>
		                	<div class="content-action">
								<span data-btn-color="light" class="btn btn-dummy guide-overview-cat"><?php echo $gName; ?></span>
							</div>
						<?php endif; ?>
						<?php /*if($category): ?>
		                	<div class="content-action">
								<span data-btn-color="light" class="btn btn-dummy guide-overview-cat"><?php echo $category->name; ?></span>
							</div>
						<?php endif; */ ?>
						<h4 class="guide-overview-eyebrow"><?php echo $eyebrow; ?></h4>
						<h1 class="guide-overview-title"><?php echo $title; ?></h1>
						<p class="guide-overview-text"><?php echo $text; ?></p>
					</div>
				</div>
			</div>
			<a href="<?php echo get_the_permalink($previous_post); ?>" class="guide-nav guide-nav-prev"></a>
			<a href="<?php echo get_the_permalink($next_post); ?>" class="guide-nav guide-nav-next"></a>
		</div>
	</div>

	<?php
		$textColor  = get_field('guide-text-color');
	?>
	<div class="guide-intro">
		<div class="container">
			<div class="columns">
				<div class="column">
					<div class="content" data-color="<?php echo $textColor; ?>">
						<p><?php echo $intro; ?></p>
						<?php include( locate_template( 'partials/elements/audio.php', false, false ) ); ?>
						<?php if(!empty($button)):
							$link_url 	 = $button['url'];
						    $link_title  = $button['title'];
						    $link_target = $button['target'] ? $button['target'] : '_self';
						?>
							<div class="content-action">
							    <a
							    	class="btn"
							    	data-btn-color="color"
							    	href="<?php echo esc_url( $link_url ); ?>"
							    	target="<?php echo esc_attr( $link_target ); ?>">
							    	<?php echo esc_html( $link_title ); ?>
							    </a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>