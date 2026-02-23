<?php 
	/* Block Name: Stats */
	/* Order: 220 */
?>

<div class="columns columns-text">
	<div class="column">
		<div class="content">
			<?php
				include( locate_template( 'partials/elements/text.php', false, false ) );
				include( locate_template( 'partials/elements/cta.php', false, false ) ); 
			?>
		</div>
	</div>
</div>

<div class="columns columns-3">
	
	<?php
		$columns = $content['columns'];

		foreach($columns as $column):

			$eyebrow = $column['eyebrow'];
			$title 	 = $column['title'];
			$text  	 = $column['text'];
	?>
			
			<div class="column">
				<div class="content">
					<div class="content-overlay">

						<h6 class="content-eyebrow"><?php echo $eyebrow; ?></h6>
						<h2 class="content-title">
							<?php
								$shortcode = trim($title, "[]");
								if (shortcode_exists($shortcode)):
								    echo do_shortcode($title);
								else:
								    echo $title;
								endif;
							?>
				
						</h2>

						<?php if($text): ?><div class="content-text"><p><?php echo $text; ?></p></div><?php endif; ?>
					</div>

				</div>
			</div>
	<?php
		endforeach;
	?>

</div>