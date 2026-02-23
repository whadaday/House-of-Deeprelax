<?php 
	/* Block Name: FAQ */
	/* Order: 180 */

	$text 	 	 = $content['text'];
	$faqs 	 	 = $content['faqs'];
	$columns 	 = (isset($options['columns'])) ? $options['columns'] : 1;
	$buttonColor = $options['color-btn'];

	foreach ($faqs as $index => $faq):

			$type = $faq['question-type'];
			if($type == 'global'):
				$post_id  				  = $faq['question-global'];
				$faqs[$index]['question'] = get_the_title($post_id);
				$faqs[$index]['answer']   = get_field('answer', $post_id);
				$faqs[$index]['link'] 	  = get_field('link', $post_id);
				$faqs[$index]['loom'] 	  = get_field('loom', $post_id);
			endif;

	endforeach;

	if($columns == 2):
?>

	<?php if($text != ''): ?>
	<div class="columns columns-text">
		<div class="column column-text">
			<div class="content">
				<?php echo $text; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<div class="columns columns-accordion" data-columns="<?php echo $columns; ?>">

		<?php
			$count = count($faqs);
			
			$faqColumn2 = intdiv($count, 2);
			$faqColumn1 = $count-$faqColumn2;

			$faqs1 = array_slice($faqs, 0, $faqColumn1);
			$faqs2 = array_slice($faqs, $faqColumn1, $faqColumn2);
		?>

		<div class="column column-accordion">
			<div class="content">
				<div class="accordion">
					<?php showFAQ($faqs1, $buttonColor); ?>
				</div>
			</div>
		</div>
		<div class="column column-accordion">
			<div class="content">
				<div class="accordion">
					<?php showFAQ($faqs2, $buttonColor); ?>
				</div>
			</div>
		</div>

	</div>

<?php
	else:
?>
	
		<div class="columns columns-accordion" data-columns="<?php echo $columns; ?>">
			
			<?php if($text != ''): ?>
				<div class="column column-text">
					<div class="content">
						<?php echo $text; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="column column-accordion">
				<div class="content">
					<div class="accordion">
						<?php showFAQ($faqs, $buttonColor); ?>
					</div>
				</div>
			</div>

		</div>

	<?php endif; ?>

	<script type='application/ld+json'>
	{
		"@context": "http://schema.org",
		"@type": "FAQPage",
		"mainEntity": [
		<?php $iFaq = 0; foreach($faqs as $faq): 

		?>
			<?php if($iFaq != 0): echo ','; endif; ?>{
			"@type": "Question",
			"name": "<?php echo $faq['question']; ?>",
			"acceptedAnswer": {
				"@type": "Answer",
				"text": "<?php echo strip_tags($faq['answer']); ?>"
			}
			}
		<?php $iFaq++; endforeach; ?>
		]
	}
	</script>