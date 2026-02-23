<?php 
	/* Block Name: Facts */
	/* Order: 140 */

	$facts = $content['facts'];

	$slideColor = $options['slide-background-color'];

	$post_id = get_the_ID();

	$category = '';
?>

<div class="columns columns-facts">
	<div class="column column-facts">
		<div class="content">

			<div class="carousel-facts swiper-container" <?php if($slideColor): ?>data-color="<?php echo $slideColor; ?>"<?php endif; ?>>
		        <div class="swiper-wrapper">

		        	<?php foreach ($facts as $fact):
						$image 	  = $fact['image'];
						if($image):
							$imageUrl = $image['sizes']['cover'];
							$crop  	  = getImageCrop($image);
						endif;
						$title	  = $fact['title'];
						$info	  = $fact['info'];
						$lead	  = $fact['lead'];
						$text	  = $fact['text'];
		          	?>
		            	<div class="swiper-slide">
			                <div class="slide-content">
			                  <span class="slide-label"><?php echo $title; ?></span>
			                  <span class="slide-info"><?php echo $info; ?></span>
			                  <span class="slide-lead"><?php echo $lead; ?></span>
			                  <?php if($text): ?><div class="slide-body"><?php echo $text; ?></div><?php endif; ?>
			                </div>
			            </div>
			        <?php endforeach; ?>     
		          
		        </div>

		        <div class="swiper-pagination"></div>

		    </div>

		</div>
	</div>
	<div class="column column-facts-images">
		<div class="content">
			<div class="carousel-facts-images swiper-container">
		        <div class="swiper-wrapper">
		          	
		          	<?php foreach ($facts as $fact):
						$image 	  = $fact['image'];
						if($image):
							$content['image'] = $image;
						endif;
		          	?>
			            <div class="swiper-slide">
			            	<?php
			            		$imageSize = 's';
								include( locate_template( 'partials/elements/image.php', false, false ) );
								$imageSize = '';
			            	?>
			            </div>
			        <?php endforeach; ?>
		          
		        </div>

		        <?php if($category): ?>
                	<div class="content-action" data-btn-color="light">
						<span class="btn btn-dummy"><?php echo $category->name; ?></span>
					</div>
				<?php endif; ?>

		    </div>

		</div>
	</div>
</div>

<div class="guide-nav guide-nav-prev btn-slide-fact-prev"></div>
<div class="guide-nav guide-nav-next btn-slide-fact-next"></div>