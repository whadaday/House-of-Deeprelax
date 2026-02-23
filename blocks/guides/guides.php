<?php 
	/* Block Name: Guide feed */
	/* Order: 210 */
 
	$guides = getGuides();
	$activeGuideType = null;
?>
<div class="columns columns-text">
	<div class="column">
		<div class="content">
			<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
		</div>
	</div>
</div>
<?php if($guides): 

	$terms = get_terms(array(
		'taxonomy'   => 'guide_type',
		'hide_empty' => true,
		// 'orderby'       => 'meta_value_num',
    	// 'meta_key'      => 'order'
	));

	// echo '<pre>'; print_r($terms); echo '</pre>';

	if(!empty($terms)):
?>
		<div class="columns columns-guides-buttons">
			<div class="column">
				<div class="content">
					<div class="btn-container">
						<div class="btn-group btn-group-guides">
							<?php 
								$iTerm = 0; 
								foreach($terms as $term): 
									if($iTerm == 0 && !$activeGuideType): $activeGuideType = $term->term_id; endif;
							?>
									<button class="btn <?php if($iTerm == 0): echo 'active'; endif; ?>" data-type="<?php echo $term->slug; ?>"><?php echo $term->name; ?></button>
							<?php 
									$iTerm++; 
								endforeach; 
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="columns columns-guides">
		<div class="column column-guide-list">
			<div class="content">
				<ul class="list-guides">
					<?php 
						$iGuide = 0;
						$activeGuide = null;

						foreach($guides as $guide):
							$title = get_the_title($guide);
							
							// Get guide category
							$categoryID = getGuideTypeID($guide);
							if($categoryID): $category = get_term_by('id', $categoryID, 'guide_type'); endif;

							// check if guide has active type
							if($activeGuide == null && $categoryID == $activeGuideType): $activeGuide = $guide; endif;
					?>
							<li 
								<?php if($categoryID): ?>data-type="<?php echo $category->slug; ?>"<?php endif; ?>
								data-guide="<?php echo $guide; ?>"
								class="<?php if($categoryID == $activeGuideType): echo 'show'; endif; ?> <?php if($guide == $activeGuide): echo 'active'; endif; ?>"
							>
								<?php echo $title; ?>
							</li>
					<?php 
							$iGuide++; 
						endforeach;
					?>
				</ul>
			</div>
		</div>
		<div class="column column-guide"> 
			<div class="content content-guides">
				<?php 
					$iGuide = 0;
					
					foreach($guides as $guide):
						$image 		= get_field('guide-illustration', $guide);
						$title 		= get_field('guide-overview-title', $guide);
						$text  		= get_the_excerpt($guide);
						$link  		= get_the_permalink($guide);
						$color 		= get_field('guide-color', $guide);
						$textColor  = get_field('text-color', $guide);
				?>
						<div 
							class="content-guide <?php if($guide == $activeGuide): echo 'show'; endif; ?>"
							data-guide="<?php echo $guide; ?>"
							data-color-text="<?php echo $textColor; ?>"
							data-guide-color="<?php echo $color; ?>"
						>
							<?php 
								if($image): 
									$content['image'] = $image;
									$imageSize = 's';
									include( locate_template( 'partials/elements/image.php', false, false ) );
									$imageSize = '';
								endif;
							?>
							<h3><?php echo $title; ?></h3>
							<?php if($text): ?><p><?php echo $text; ?></p><?php endif; ?>
							<div class="content-action" data-btn-color="dark">
								<a href="<?php echo $link; ?>" class="btn">Naar de guide</a>
							</div>
						</div>
				<?php 
						$iGuide++;
					endforeach;
				?>
			</div>
		</div>
	</div>

<?php endif; ?>