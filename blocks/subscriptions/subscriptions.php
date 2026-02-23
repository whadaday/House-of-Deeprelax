<?php 
	/* Block Name: Pricing slider */
	/* Order: 120 */
?>

<div class="columns columns-text">
	<div class="column">
		<div class="content">
			<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
		</div>
	</div>
</div>

<?php

	$products 	 = $content['products'];
	$terms 	  	 = $content['products-note'];
	$activeSlide = $options['slide-active'];

	if(count($products) > 1):
?>
<div class="columns columns-renewals">
	<div class="column">
		<div class="content">
			<div class="btn-renewals">
				<?php
					$iProducts = 0;
					foreach ($products as $product):
				?>
					<button class="btn <?php if($iProducts == $activeSlide): ?>active<?php endif; ?>" data-show="<?php echo $iProducts; ?>">
						<span class="subscription-type"><?php echo $product['carousel-btn-title']; ?></span>
						<span class="subscription-frequent"><?php echo $product['carousel-btn-subtitle']; ?></span>
						<?php 
							$featured = $product['featured'];
							if($featured):
								$featuredText = $product['featured-text'];
						?>
							<span class="best-value"><?php echo $featuredText; ?></span>
						<?php endif; ?>
					</button>
				<?php
					$iProducts++;
					endforeach;
				?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="columns swiper swiper-subscriptions" data-slide-active="<?php echo $activeSlide; ?>">
	<div class="swiper-wrapper">

		<?php foreach ($products as $product): 
			if($product['price-sale']):
				$priceOld = $product['price'];
				$product['price'] = $product['price-sale'];
			endif;
		?>
			<div class="swiper-slide">
				<div class="columns columns-subscriptions">
					
							
					<div class="column column-subscription column-subscription-info">
						<div class="content">
							<div class="subscription">
								<?php echo $product['text']; ?>

								<span class="subscription-price">
									<span class="price">
										<?php if(isset($priceOld)): ?>
											<span class="price-old"><?php echo $priceOld; ?></span>
										<?php endif; ?>
										<?php echo $product['price']; ?></span> <?php echo $product['price-frequency']; ?>
									</span>

								<div class="subscription-note"><?php echo $product['note']; ?></div>
								<?php if($terms): ?><div class="subscription-terms"><?php echo $terms; ?></div><?php endif; ?>
							</div>
						</div>
					</div>
					<div class="column column-subscription column-subscription-image">
						<div class="content">
							<?php 
								$content['image'] = $product['image'];
								$imageSize = 's';
								include( locate_template( 'partials/elements/image.php', false, false ) );
								$imageSize = '';
							?>
						</div> 
					</div>

					<div class="column column-subscription column-subscription-action">
						<div class="content">
							<div class="content-action" data-btn-color="dark">
								<?php
									$link = $product['link'];
									$link_url 	 = $link['url'];
								    $link_title  = $link['title'];
								    $link_target = $link['target'] ? $link['target'] : '_self';
								?>
								<a 
									class="btn"
									href="<?php echo esc_url( $link_url ); ?>"
									target="<?php echo esc_attr( $link_target ); ?>">
									<?php echo esc_html( $link_title ); ?>
								</a>

								<?php
									$link = $product['link-readmore'];
									if($link):
										$link_url 	 = $link['url'];
									    $link_title  = $link['title'];
									    $link_target = $link['target'] ? $link['target'] : '_self';
								?>
									<a 
										class="link"
										href="<?php echo esc_url( $link_url ); ?>"
										target="<?php echo esc_attr( $link_target ); ?>">
										<?php echo esc_html( $link_title ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>
					</div>

				</div>
			</div>
		<?php endforeach; ?>

	</div>
	
</div>