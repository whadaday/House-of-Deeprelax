<?php 
	/* Block Name: Cards */
	/* Order: 110 */

	$cards = $content['cards'];
	$text  = $content['text'];

	$cardBtnColor  = $options['color-btn'];
	$cardTextColor = $options['cards-text-color'];

	if($text):
?>
<div class="columns columns-text">
	<div class="column column-text">
		<div class="content content-text">
			<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
		</div>
	</div>
</div>
<?php endif; ?>
<div class="columns columns-cards">
	
	<?php foreach($cards as $card):

		$text  = $card['text'];
		$cta   = $card['cta'];
		$image = $card['image'];
		$content['image'] = $image;
	?>

	<div class="column column-card">
		<div class="content">
			<div class="card">

				<?php
					if($cta == 'button'):
						$link  		 = $card['button'];
						$link_url 	 = $link['url'];
					    $link_title  = $link['title'];
					    $link_target = $link['target'] ? $link['target'] : '_self';
				?>
					<a 
						class="card-link"
						href="<?php echo esc_url( $link_url ); ?>"
						target="<?php echo esc_attr( $link_target ); ?>"></a>
				<?php endif; ?>
    
			    <div class="card-image-holder">
			        <?php 
			        	$imageSize = 's';
						include( locate_template( 'partials/elements/image.php', false, false ) );
						$imageSize = '';
			        ?>
			    </div>

				<div class="card-content" data-color-text="<?php echo $cardTextColor; ?>">
					<?php 
						echo $text;

						if($cta == 'button'): ?>
							<span class="btn" data-btn-color="<?php echo $cardBtnColor; ?>"><?php echo esc_html( $link_title ); ?></span>
						<?php	
						elseif($cta == 'appstore'):
							include( locate_template( 'partials/elements/appstore.php', false, false ) );
							
						endif;
					?>
				</div>

			</div>
		</div>
	</div>

	<?php endforeach; ?>

</div>

<?php
	$showCardMessage = $content['show-card-message'];
	if($showCardMessage):
		$cardMessage 	  = $content['card-message'];
		$cardMessageTitle = $cardMessage['title'];
		$cardMessageText  = $cardMessage['text'];
?>
		<div class="columns columns-cards columns-cards-message">
			<div class="column column-card">
				<div class="content">
					<div class="card">
						<span class="card-title"><?php echo $cardMessageTitle; ?></span>
						<div class="card-content">
							<p><?php echo $cardMessageText; ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php endif; ?>