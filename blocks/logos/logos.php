<?php 
	/* Block Name: Featured */
	/* Order: 120 */
	/* Version: 1.0.0 */

	$text = $content['text'];
	$logos = $content['logos'];
	$amountOfLogos = count($logos);
	$type = $options['type'];
?>

<?php if($text): ?>
<div class="columns columns-text" data-type="<?php echo $type; ?>">
	<div class="column column-text">
		<div class="content content-text">
			<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if($type == 'grid'): ?>

		<div class="columns columns-logos columns-logos-grid" data-type="<?php echo $type; ?>" data-amount="<?php echo $amountOfLogos; ?>">
			<?php foreach($logos as $logo): 
				$image = $logo['logo'];
				$url = $logo['url'];
			?>
				<div class="column column-logo">
					<div class="content content-logo">
						<?php if($url): ?><a class="link-logo" href="<?php echo $url; ?>" target="_blank"><?php endif; ?>
							<img class="logo" alt="<?php echo $image['alt']; ?>" src="<?php echo $image['sizes']['large']; ?>">
						<?php if($url): ?></a><?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

<?php else: ?>

	<div class="columns columns-logos columns-logos-carousel swiper-container carousel-logos">
		<div class="swiper-wrapper">
			<?php foreach($logos as $logo): 
				$image = $logo['logo'];
				$url = $logo['url'];
			?>
				<div class="swiper-slide">
					<?php if($url): ?><a class="link-logo" href="<?php echo $url; ?>" target="_blank"><?php endif; ?>
						<img class="logo" alt="<?php echo $image['alt']; ?>" src="<?php echo $image['sizes']['large']; ?>">
					<?php if($url): ?></a><?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

<?php endif; ?>
