<?php
	if(is_archive() || is_category() ):
		$showCta = true;
	else:
		$showCta = get_field('show-contact');

	endif;

	if($showCta):

	global $blockIndex; 
	$lang = getLang();

	$text     = get_field('contact-text', $lang);
	$form     = get_field('contact-form', $lang);
	$btnClass = get_field('contact-form-btn-class', $lang);

	if($text || $form):
?>
<section
	id="section-<?php echo $blockIndex; ?>"
	class="section section-contact"
	<?php if($btnClass): ?>data-form-btn="<?php echo esc_attr($btnClass); ?>"<?php endif; ?>
>
	<div class="container content-animate">
		<div class="columns">
			<div class="column">
				<div class="content">
					<?php 
						echo $text;
						if($form):
							echo do_shortcode('[wpforms id="'.$form.'"]'); 
						endif; 
					?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php endif; $blockIndex++; endif; ?>