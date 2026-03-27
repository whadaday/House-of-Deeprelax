<?php
	/* Block Name: Review cards */
	/* Order: 91 */
?>

<div class="columns columns-text">
	<div class="column">
		<div class="content">
			<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
		</div>
	</div>
</div>

<?php
	$reviews = $content['reviews'];
	$amount  = $options['amount'];
	$amountMobile = $options['amount-mobile'];
	$starColor = !empty($options['star-color']) ? $options['star-color'] : null;
	$starColorVar = $starColor ? 'var(--color-' . $starColor . ')' : null;
?>

<div
	class="columns columns-reviews content-animate"
	data-amount-per-row="<?php echo $amount; ?>"
	data-amount-per-row-mobile="<?php echo $amountMobile; ?>"
	<?php if($starColorVar): ?>style="--color-stars: <?php echo esc_attr($starColorVar); ?>"<?php endif; ?>
>

	<?php foreach($reviews as $review):
		$title = $review['title'];
		$quote = $review['quote'];
		$name  = $review['name'];
		$role  = $review['role'];
	?>
		<div class="column">
			<div class="content content-review">

				<div class="review-stars">
					<?php for($i = 0; $i < 5; $i++): ?>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
					<?php endfor; ?>
				</div>

				<?php if($title): ?>
					<h4 class="review-title"><?php echo $title; ?></h4>
				<?php endif; ?>

				<p class="review-quote"><?php echo $quote; ?></p>

				<?php if($name): ?>
					<div class="review-author">
						<span class="review-name"><?php echo $name; ?></span>
						<?php if($role): ?>
							<span class="review-role"><?php echo $role; ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	<?php endforeach; ?>

</div>
