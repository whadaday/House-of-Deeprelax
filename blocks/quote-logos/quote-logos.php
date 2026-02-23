<?php 
	/* Block Name: Featured + Quote */
	/* Order: 130 */

	$quote = $content['quote'];
	$image = $quote['image'];
?>
<div class="columns columns-quote animate-quote">
	<div class="column">
		<div class="content">
			<?php
				$content['image'] = $image;
				include( locate_template( 'partials/elements/image.php', false, false ) );
			?>
			<div class="content-overlay">
				<div class="content-inner">
					<h3><?php echo $quote['text']; ?></h3>
					<span class="quote-author"><?php echo $quote['author']; ?></span>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	$logos = $content['logos'];
	if($logos):
?>

<div class="columns columns-logos-holder animate-logos">
	<div class="column column-logos-holder">
		<div class="content content-logos-holder">
			<div class="columns columns-logos">
				<?php foreach($logos as $logo): ?>
					<div class="column column-logo">
						<div class="content content-logo">
							<img class="logo" src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>">
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div> 
<?php endif; ?>