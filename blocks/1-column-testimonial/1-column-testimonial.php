<?php 
	/* Block Name: Review banner */
	/* Order: 50 */

	$filter = $content['filter'];
	if($filter == 'filter'):
		$categories = $content['filter-category'];
		$tags 		= $content['filter-tag'];

		// getReviews($reviews = null, $amount = -1, $categories = null, $tags = null)
		$query 		= getReviews(null, 1, $categories, $tags);
		$review 	= $query->posts;
		$review  	= $review[0]->ID;
	else:
		$review 	= $content['review'];
	endif;

	if($review):
		$quote = get_field('quote', $review);
		$author = get_field('author', $review);
?>
<div class="columns">
	<div class="column">
		<div class="content">
			
			<h3 class="testimonial-quote"><?php echo $quote; ?></h3>
			
			<?php if($author): ?>
				<div class="testimonial-author">
				<?php 
					$image = $author['image'];
					$name  = $author['name'];
					$role  = $author['role'];

					if($image):
						$content['image'] = $image;
						$imageSize = 'xs';
						include( locate_template( 'partials/elements/image.php', false, false ) );
						$imageSize = '';
					endif;
					
					if($name): ?>
						<h4 class="testimonial-author-name"><?php echo $name; ?></h4>
					<?php endif;

					if($role): ?>
						<p class="testimonial-role"><?php echo $role; ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>
<?php endif; ?> 