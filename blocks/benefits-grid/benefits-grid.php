<?php 
	/* Block Name: Benefits grid */
	/* Order: 80 */
?>
<?php
	$text = $content['text'];
	$benefits = $content['benefits'];
	$chevDown	 = get_template_directory().'/assets/images/beam/icons/chevron-right.svg';

	if($text):
?>
<div class="columns columns-text">
	<div class="column">
		<div class="content">
			<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
		</div>
	</div>
</div>
<?php endif; ?>
<div class="columns columns-benefits">
	<?php foreach ($benefits as $benefit): 
		$link = $benefit['link'];
		$link_url 	 = $link['url'];
	    $link_title  = $link['title'];
	    $link_target = $link['target'] ? $link['target'] : '_self';
		$image = $benefit['icon'];
	?>
	<div class="column">
		<div class="content content-benefit">
			<a 
				class="link-benefit"
				href="<?php echo esc_url( $link_url ); ?>"
				target="<?php echo esc_attr( $link_target ); ?>">
				<span class="benefit-title"><?php echo esc_html( $link_title ); ?></span>
				<span class="chev-right"><?php echo file_get_contents($chevDown); ?></span>
				<?php 
					if($image): 
						if($image['subtype'] == 'svg+xml'):
							echo '<span class="icon-benefit">'.file_get_contents($image['url']).'</span>';
						else:
							$content['image'] = $image;
							$imageSize = 'xs';
							include( locate_template( 'partials/elements/image.php', false, false ) );
							$imageSize = '';
						endif;
					endif; 
				?>
			</a>
		</div>
	</div>
	<?php endforeach; ?>
</div>