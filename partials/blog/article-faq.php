<?php
	$post_id = get_the_ID();
	$content['text'] = '<h3>'.get_field('faq-title', $post_id).'</h3>';
	$content['faqs'] = get_field('faqs', $post_id);
	$options['color-btn'] = 'dark';

	if($content['faqs']):
		wp_enqueue_script('faq', get_template_directory_uri() .'/blocks/faq/faq.js', array(), THEME_VERSION, 'all');
		?>
		<div class="content content-article-faq">
			<?php include( locate_template( 'blocks/faq/faq.php', false, false ) ); ?>
		</div>
		<?php
	endif;
?>