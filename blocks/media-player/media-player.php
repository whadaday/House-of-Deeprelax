<?php 
	/* Block Name: Media player */
	/* Order: 170 */

	$bgHeroColor = $options['background-color-banner'];

	$jsFile = locate_template( '/assets/javascript/elements/audio.js', false, false );
	if (file_exists($jsFile)):
		wp_enqueue_script('audio', get_template_directory_uri() .'/assets/javascript/elements/audio.js', array(), THEME_VERSION, 'all');
	endif;
?>

<div class="columns" data-align="center">

	<div class="overlay" <?php if($bgHeroColor): ?> style="--color-banner: var(--color-<?php echo $bgHeroColor; ?>);"<?php endif; ?>></div>

	<div class="column column-text">
		<div class="content">
			<div class="content-text">
				<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
			</div>

			<?php $moments = $content['moments']; ?>
 
			<ul class="list-moments">
			<?php $i = 0; foreach($moments as $moment): ?>
				<li
					<?php if($i == 0): ?>class="active"<?php endif; ?>
					id="<?php echo $moment['name']; ?>"
                    data-track="<?php echo $moment['file']; ?>"
                ><a href="#" class="btn" data-btn-color="light" aria-label="<?php echo $moment['name']; ?>"><?php echo $moment['name']; ?></a></li>
			<?php $i++; endforeach; ?>
			</ul>

			<ul class="list-moments-description">
				<?php $i = 0; foreach($moments as $moment): ?>
					<li 
						<?php if($i == 0): ?> 
							class="active"
						<?php endif; ?>
					>
						<?php echo $moment['text']; ?>
					</li>
				<?php $i++; endforeach; ?>
			</ul>
			
			<?php
				$audio['url'] = $moments[0]['file'];
				include( locate_template( 'partials/elements/audio.php', false, false ) );
			?>
			
		</div>
	</div>

	<?php
		$image = $content['mockup'];
		if($image):
			$content['image'] = $image;
			$imageSize = 'xs';
			include( locate_template( 'partials/elements/image.php', false, false ) );
			$imageSize = '';
	?>
	<?php endif; ?>

</div>