<?php 
	/* Block Name: Summary */
	/* Order: 160 */

	$title 	  = $content['title'];
	$text 	  = $content['text'];
	$subtitle = $content['subtitle'];
	$toc 	  = $content['toc'];
?>
<div class="columns">
	<div class="column column-image">
		<div class="content">
			<?php
				$imageSize = 's';
				include( locate_template( 'partials/elements/image.php', false, false ) );
				$imageSize = '';
			?>
		</div>
	</div>
	<div class="column column-text">
		<div class="content">
			<h2><?php echo $title; ?></h2>
			<p><?php echo $text; ?></p>
			<div class="content-toc">
				<h4><?php echo $subtitle; ?></h4>
				<ul class="list-toc">
					<?php foreach($toc as $chapter): ?>
						<li><?php echo $chapter['chapter']; ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</div> 