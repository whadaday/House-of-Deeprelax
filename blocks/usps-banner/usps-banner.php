<?php
	/* Block Name: Usps banner */
	/* Order: 120 */

	$usps = isset($content['usp']) ? $content['usp'] : null;

	if($usps):
?>

<ul class="cta-usps">
	<?php foreach($usps as $usp): ?>
		<li><?php echo wp_kses($usp['text'], array('strong' => array(), 'b' => array())); ?></li>
	<?php endforeach; ?>
</ul>

<?php endif; ?>