<?php 
	/* Block Name: Banner media */
	/* Order: 61 */
	
	$bgHeroColor = $options['background-color-banner'];
?>

<div class="columns" data-align="center">

	<div class="overlay" <?php if($bgHeroColor): ?> style="--color-banner: var(--color-<?php echo $bgHeroColor; ?>);"<?php endif; ?>></div>

	<div class="column column-text">
		<div class="content content-text">
			
			<?php
				$title 		  = $content['title'];
				$testimonials = $content['testimonial'];
			?>

			<?php if($title): ?><h4><?php echo $title; ?></h4><?php endif; ?>

			<div class="swiper swiper-media-quotes">
				<div class="swiper-wrapper">
			<?php
				foreach($testimonials as $item):
					$text = $item['testimonial'];
			?>
					
					<div class="swiper-slide">
						<div class="content">
							<p><?php echo $text; ?></p>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			</div>

			<div class="swiper swiper-media-logos">
				<div class="swiper-wrapper">
				<?php
					foreach($testimonials as $item):
						$logo = $item['logo'];
				?>
						
						<div class="swiper-slide">
							<div class="content">
								<img src="<?php echo $logo['sizes']['large']; ?>" alt="<?php echo $logo['alt']; ?>" />
							</div>
						</div>
					<?php endforeach; ?>
					</div>
				</div>
			</div>
			

		</div>
	</div>

</div>