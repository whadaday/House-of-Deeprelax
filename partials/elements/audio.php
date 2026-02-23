<?php
	$title = isset($audio['title']) ? $audio['title'] : '';

	if($audio['url']):

		$icon_play = get_bloginfo('template_directory').'/assets/images/player/icon-player-play.svg';
		$icon_pause = get_bloginfo('template_directory').'/assets/images/player/icon-player-pause.svg';

		$jsFile = locate_template( '/assets/javascript/elements/audio.js', false, false );
		if (file_exists($jsFile)):
			wp_enqueue_script('audio', get_template_directory_uri() .'/assets/javascript/elements/audio.js', array(), THEME_VERSION, 'all');
		endif;
	?>
		<div class="player-holder">
			<?php if($title): ?><span class="player-title"><?php echo $title; ?></span><?php endif; ?>
			<audio class="audio-player" src="<?php echo $audio['url']; ?>"></audio>
			<div class="controls">
				<div class="timeline-holder">
					<input type="range" class="timeline" max="100" value="0" aria-label="Afspeelvoortgang">
					<span class="time-current"></span>
					<span class="time-remain"></span>
				</div>
				<button class="btn-play" aria-label="Speel audio af">
					<span><?php echo file_get_contents($icon_play); ?></span>
					<span><?php echo file_get_contents($icon_pause); ?></span>
				</button>
			</div>
		</div>
<?php 
	endif;