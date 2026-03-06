<?php 
	/* Block Name: Benefits */
	/* Order: 80 */
?>
<div class="columns columns-text">
	<div class="column">
		<div class="content">
			<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
		</div>
	</div>
</div>

<?php
	$columns 	   = $content['columns'];
	$imageRatio    = $options['images-ratio'];
	$columnsPerRow = isset($options['columns-amount']) ? $options['columns-amount'] : 2;
?>

<div class="columns columns-benefits" data-columns="<?php echo $columnsPerRow; ?>">
	
	<?php
		foreach($columns as $column):

			$benefit = $column['benefit'];
			$title   = $column['title'];
			$text    = $column['text'];
			$link    = $column['link'];
			$image   = $column['image'];
	?>

			<div class="column">
				<div class="content">

					<?php if($image):
						$content['image'] = $image;
					?>
						<div class="image-benefits" data-ratio="<?php echo $imageRatio; ?>">
							<?php
								$imageSize = 's';
								include( locate_template( 'partials/elements/image.php', false, false ) );
								$imageSize = '';
							?>
						</div>
					<?php endif; ?>

					<?php if($benefit): ?>
						<div class="content-benefit"><?php echo $benefit; ?></div>
					<?php else: ?>
						<h3 class="content-title"><?php echo $title; ?></h3>
						<div class="content-text"><p><?php echo $text; ?></p></div>
					<?php endif; ?>
					
					<?php if($link): ?>
						<div class="content-action">
					    	<?php
								$link_url 	 = $link['url'];
							    $link_title  = $link['title'];
							    $link_target = $link['target'] ? $link['target'] : '_self';
							?>
							<a 
								class="link"
								href="<?php echo esc_url( $link_url ); ?>"
								target="<?php echo esc_attr( $link_target ); ?>">
								<?php echo esc_html( $link_title ); ?>
							</a>
					    </div>
					<?php endif; ?>
				</div>
			</div>
	<?php
		endforeach;
	?>

</div>