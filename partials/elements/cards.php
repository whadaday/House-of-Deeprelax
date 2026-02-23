<div class="swiper-container carousel-nav-cards">
	<div class="swiper-wrapper">
		<?php
			foreach($cards as $card):
				// echo '<pre>'; print_r($card); echo '</pre>';
			$card_title = $card['title'];
			$link 		= $card['url'];
			$background = $card['background-type'];
		?>
			<div class="swiper-slide">
				<div class="deeprelax-card">
					<a class="card-link" href="<?php echo $link; ?>" aria-label="<?php echo $card_title; ?>"></a>
					<div class="card-image-holder">
			        	<?php
							if($background == 'video'):
								$video = $card['video'];
								include( locate_template( 'partials/elements/video.php', false, false ) );
							elseif($background == 'image'):
								$image = $card['image'];
								$imageSize = 's';
								include( locate_template( 'partials/elements/image.php', false, false ) );
								$imageSize = '';
							endif;

						?>
				    </div>
					<span class="card-title"><?php echo $card_title; ?></span>
				</div>
			</div>
		<?php
			endforeach;
		?>
	</div>
</div>
<?php