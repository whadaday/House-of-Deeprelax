<?php 
	/* Block Name: Step slider */
	/* Order: 200 */
?>
<div class="columns columns-text">
	<div class="column">
		<div class="content">
			<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
		</div>
	</div>
</div>

<div class="columns columns-steps">

	<?php /* Carousel images */ 
		$steps = $content['steps'];
		$dimension = $options['ratio'];
		
	?>
	<div class="column column-image" data-ratio="<?php echo $dimension; ?>">
		<div id="carousel-vertical-<?php echo $blockIndex; ?>" class="content swiper swiper-container">

			<div id="step-images-holder-<?php echo $blockIndex; ?>" class="steps-images-holder swiper-wrapper">

				<?php 
					$iStep = 1;
					foreach($steps as $step): 
						$image = $step['image'];
				?>
					
					<div class="step-image swiper-slide <?php if($iStep == 1): ?>active<?php endif; ?>">
						<div class="step-image-holder">
							<?php 
								$content['image'] = $image;
								$imageSize = 'm';
								include( locate_template( 'partials/elements/image.php', false, false ) );
								$imageSize = '';
							?>
						</div>
					</div>

				<?php $iStep++; endforeach; ?>

				<div class="steps-pagination-container">
					<ul class="steps-pagination">
						<?php $iStep = 1; foreach($steps as $step): ?><li <?php if($iStep == 1): ?>class="active"<?php endif; ?>></li><?php $iStep++; endforeach; ?>
					</ul>
				</div>

			</div>

		</div>
	</div>

	<?php /* Carousel text */ ?>
	<div id="gallery-content-holder-<?php echo $blockIndex; ?>" class="column column-text steps-text-holder swiper swiper-container">
		<div class="gallery-inner swiper-wrapper">

			<?php 
				$iStep = 1;

				foreach($steps as $step): 
					$btn = $step['btn'];
			?>
				<div id="section-<?php echo $blockIndex; ?>-<?php echo $iStep; ?>" class="content-step swiper-slide">
					<div class="content-inner" data-number="<?php echo $iStep; ?>">
						<h3 class="step-title"><?php echo $step['title']; ?></h3>
						<div class="step-text">
							<p><?php echo $step['text']; ?></p>

							<?php if($btn): 
								$link_url 	 = $btn['url'];
							    $link_title  = $btn['title'];
							    $link_target = $btn['target'] ? $btn['target'] : '_self';
							    $buttonColor  = $options['color-btn'];
							?>
								<div class="content-action">
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

			<?php $iStep++; endforeach; ?>

		</div>
		<div class="swiper-pagination"></div>
	</div>

</div>

