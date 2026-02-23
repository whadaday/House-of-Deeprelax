<?php 
	/* Block Name: Instagram */

	$title = $content['title'];
	$feed  = $content['instagram-feed'];
	$link  = $content['link'];
?>

<?php if($title): ?>
	<div class="columns">
		<div class="column">
			<div class="content">
				<h4><?php echo $title; ?></h4>
			</div>
		</div>
	</div>
<?php endif; ?>

<div class="columns">
	<div class="column">
		<div class="content">
			<?php echo do_shortcode('[instagram-feed feed='.$feed.']'); ?>
		</div>
	</div>
</div>

<?php if($link): ?>

<div class="columns">
	<div class="column">
		<div class="content">
			<div
				class="content-action"
				data-link-color="<?php echo $textColor; ?>"
				data-align="left"
			>
				<?php
					$link_url 	 = $link['url'];
				    $link_title  = $link['title'];
				    $link_target = $link['target'] ? $link['target'] : '_self';
				?>
				<a 
					class="link link-arrow"
					href="<?php echo esc_url( $link_url ); ?>"
					target="<?php echo esc_attr( $link_target ); ?>">
					<?php echo esc_html( $link_title ); ?>
				</a>

			</div>
		</div>
	</div>
</div>

<?php endif; ?>