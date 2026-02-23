<?php 
	if ( shortcode_exists( 'Sassy_Social_Share' ) ):
?>

		<div class="content content-article-share">
			<span class="share-title">Share</span>
		    <?php echo do_shortcode('[Sassy_Social_Share]'); ?>
		</div>

<?php
	endif;
?>