	<?php
		/* Block Name: FAQ Uitgelicht */
		/* Order: 181 */

		$faqs = $content['faq-featured'];
		

		if($faqs):
	?>
	<div class="columns">
		<?php foreach ($faqs as $faq):
			$title = get_the_title($faq);
			$link = get_the_permalink($faq);
            $loom = get_field('loom', $faq);

            $terms = get_the_terms( $faq, 'faq-category' );
            $url = get_term_link($terms[0]);
		?>
			<div class="column">
				<div class="content">
					<?php if($loom): 
                        $icon = get_template_directory_uri().'/assets/images/beam/icons/play-circle.svg';
                    ?>
                    	<a 
                            data-fancybox
                            data-type="iframe"
                            class="icon-play"
                            href="<?php echo convertLoomShareToEmbed($loom); ?>">
                            <?php echo file_get_contents($icon); ?>
                        </a>
                    	<a 
                            data-fancybox
                            data-type="iframe"
                            class="faq-title faq-title-link"
                            href="<?php echo convertLoomShareToEmbed($loom); ?>">
                            <?php echo $title; ?>
                        </a>

                    <?php else: ?>
                    	<a class="faq-title" href="<?php echo $link; ?>"><?php echo $title; ?></a>
                    <?php endif; ?>
					<?php /*<div class="content-action">
						<a class="link" href="<?php echo $url; ?>#<?php echo $faq; ?>">Lees verder</a>
					</div>*/ ?>
				</div>
			</div>

		<?php endforeach; ?>
	</div>
<?php
	endif;
?>