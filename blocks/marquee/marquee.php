<?php 
	/* Block Name: Marquee */
 
	$text = $content['text'];
	$size = $options['size'];

	if($size == 'large'):
		$amount = 8;
	else:
		$amount = 20;
	endif;

	$count = count($text);
	$copy = $text;
	while($count < $amount):
		$text = array_merge($text, $copy);
		$count = count($text);
	endwhile;
?>
<div class="marquee" data-size="<?php echo $size; ?>">
	<div class="marquee__inner" aria-hidden="true">
		<?php foreach($text as $item): ?>
			<h4><?php echo $item['item']; ?></h4>
		<?php endforeach; ?>
	</div>
</div>
