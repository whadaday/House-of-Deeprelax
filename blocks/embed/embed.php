<?php 
	/* Block Name: Embed */
	/* Order: 190 */
 
	$text = $content['text'];
	$size = $options['size'];
?>

<?php if($text): ?>
<div class="columns">
	<div class="column">
		<div class="content">
			<?php echo $text; ?>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="columns columns-embed" data-size="<?php echo $size; ?>">
	<div class="column">
		<div class="content">
			<?php include( locate_template( 'partials/elements/embed.php', false, false ) ); ?>
		</div>
	</div>
</div>