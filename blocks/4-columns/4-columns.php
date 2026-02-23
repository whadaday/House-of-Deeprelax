<?php 
	/* Block Name: 4 kolommen */
	/* Order: 90 */
?>

<div class="columns columns-text">
	<div class="column">
		<div class="content">
			<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
		</div>
	</div>
</div>

<?php
	$columns = $content['columns'];
	$amount  = $options['amount'];
	$amountMobile  = $options['amount-mobile'];
?>

<div 
	class="columns columns-4" 
	data-amount-per-row="<?php echo $amount; ?>"
	data-amount-per-row-mobile="<?php echo $amountMobile; ?>"
>
	
	<?php
		foreach($columns as $column):

			$title = $column['title']; 
			$text  = $column['text'];
			$link  = $column['link'];
			$image = $column['image'];
	?>
			
			<div class="column">
				<div class="content content-card">
					
					<?php
					if($image):
						$content['image'] = $image;
						$imageSize = 'xs';
						include( locate_template( 'partials/elements/image.php', false, false ) );
						$imageSize = '';
					endif;
					?>

					<h3 class="content-title"><?php echo $title; ?></h3>
					<div class="content-text"><p><?php echo $text; ?></p></div>
					
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