<?php 
	/* Block Name: Review carousel */
	/* Order: 40 */

	$text = $content['text'];
	

	$filter = $content['filter'];
	if($filter == 'filter'):
		$categories = $content['filter-category'];
		$tags 		= $content['filter-tag'];
		$amount 	= $content['filter-amount'];
		// getReviews($reviews = null, $amount = -1, $categories = null, $tags = null)
		$query 		= getReviews(null, $amount, $categories, $tags);
		$reviews 	= $query->posts;
	else:
		$reviews 	= $content['reviews'];
	endif;

	if($text):
?>

<div class="columns columns-text">
	<div class="column">
		<div class="content">
			<p><?php echo $text; ?></p>
		</div>
	</div>
</div>
<?php endif; ?>

<?php  
	if($reviews):
?>

<div class="carousel-testimonials swiper-container">
    <div class="swiper-wrapper">

    	<?php foreach($reviews as $review):
    		
    		$quote = get_field('quote', $review);
			$author = get_field('author', $review);
			
		?>
				
			<div class="swiper-slide">

				<div class="testimonial">
					<p class="testimonial-quote"><?php echo $quote; ?></p>

					<?php if($author): 
						$image = $author['image'];
						$name  = $author['name'];
						$role  = $author['role'];
					?>
						<div class="testimonial-author" <?php if($image): ?>data-image="1"<?php endif; ?>>
						<?php 
							if($image): 
								$content['image'] = $image;
								$imageSize = 'xs';
								include( locate_template( 'partials/elements/image.php', false, false ) );
								$imageSize = '';
							endif;
							
							if($name): ?>
								<span class="testimonial-author-name"><?php echo $name; ?></span>
							<?php endif;

							if($role): ?>
								<span class="testimonial-author-role"><?php echo $role; ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

			</div>
		<?php endforeach; ?>

	</div>
	<div class="swiper-pagination"></div>
</div>
<div class="columns columns-guide-nav">
	<div class="column">
		<div class="content">
			<div class="guide-nav-container">
				<div class="guide-nav guide-nav-prev btn-slide-testimonial-prev"></div>
				<div class="guide-nav guide-nav-next btn-slide-testimonial-next"></div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>