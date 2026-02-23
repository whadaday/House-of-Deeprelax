<?php
	if(is_front_page()):
		$lang = getLang();
		$instagramFeed  = get_field('instagram-feed', $lang);

		if($instagramFeed):
	?>
	<section class="section section-instagram">
		<div class="container">
			<div class="columns">
				<div class="column column-feed">
					<div class="content">
						<?php echo do_shortcode($instagramFeed); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php 
		endif;
	endif;
?>