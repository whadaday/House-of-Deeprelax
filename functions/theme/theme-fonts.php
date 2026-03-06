<?php

function add_fonts() {
	
	$fonts  		 = get_field('fonts', 'theme');
	$fontsImport 	 = get_field('fonts-import', 	'theme');
	$fontsLocal  	 = get_field('fonts-local', 	'theme');
	$fontsVariables  = get_field('variables-fonts', 'theme');

	?>
	<style>
	<?php
		if($fontsImport):
		
			foreach($fontsImport as $font):
			?>
				@import url(<?php echo $font['url']; ?>);
			<?php
			endforeach; 
		endif;

		if($fontsLocal):

			foreach($fontsLocal as $font):
				$name 	= $font['name'];
				$weight = $font['weight'];
				$style 	= $font['style'];
				$woff2 	= $font['file-woff2'];
				?>
				@font-face {
				    font-family:"<?php echo $name; ?>";
				    src: url("<?php echo $woff2; ?>") format("woff2");
				    font-style: <?php echo $style; ?>;
				    font-weight: <?php echo $weight; ?>;
				    font-display: block;
				}
			<?php
			endforeach;
		endif;

		if($fonts):
			$primary 	= isset($fonts[0]) ? $fonts[0]['font'] : null;
			$secondary  = isset($fonts[1]) ? $fonts[1]['font'] : null;
			$third 		= isset($fonts[2]) ? $fonts[2]['font'] : null;
			?>   

			:root {
			    
			    <?php if($primary): ?>--font-primary: <?php echo $primary; ?>, sans-serif; <?php endif; ?>
			    <?php if($secondary): ?>--font-secondary: <?php echo $secondary; ?>, sans-serif; <?php endif; ?>
			    <?php if($third): ?>--font-third: <?php echo $third; ?>, sans-serif; <?php endif; ?>
			}

			<?php
		endif;
		?>
	</style>
	<?php
}

add_action('wp_head', 'add_fonts');