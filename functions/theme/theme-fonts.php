<?php

function add_fonts() {

	$fonts  		 = hod_option('fonts');
	$fontsImport 	 = hod_option('fonts-import');
	$fontsLocal  	 = hod_option('fonts-local');
	$fontsVariables  = hod_option('variables-fonts');

	// ── Resource hints + remote font-CSS als echte <link> ─────────────────
	// Remote fonts stonden als CSS-@import in een inline <style>: dat
	// serialiseert de downloads én blokkeert de render. Een <link rel=stylesheet>
	// parallelliseert, en preconnect warmt de font-origin voor. (HOD-12)
	if ($fontsImport) {
		$origins = array();
		foreach ($fontsImport as $font) {
			$url = isset($font['url']) ? $font['url'] : '';
			if (!$url) { continue; }
			$origin = wp_parse_url($url, PHP_URL_SCHEME) . '://' . wp_parse_url($url, PHP_URL_HOST);
			if ($origin && !in_array($origin, $origins, true)) {
				$origins[] = $origin;
				printf('<link rel="preconnect" href="%s" crossorigin>' . "\n", esc_url($origin));
			}
		}
		foreach ($fontsImport as $font) {
			if (!empty($font['url'])) {
				printf('<link rel="stylesheet" href="%s" media="all">' . "\n", esc_url($font['url']));
			}
		}
	}

	// ── Preload van de primaire lokale woff2 (sneller LCP-tekst) ───────────
	if ($fontsLocal && !empty($fontsLocal[0]['file-woff2'])) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url($fontsLocal[0]['file-woff2'])
		);
	}

	?>
	<style>
	<?php
		if($fontsLocal):
			foreach($fontsLocal as $font):
				$name 	= isset($font['name']) ? $font['name'] : '';
				$weight = isset($font['weight']) ? $font['weight'] : '400';
				$style 	= isset($font['style']) ? $font['style'] : 'normal';
				$woff2 	= isset($font['file-woff2']) ? $font['file-woff2'] : '';
				?>
				@font-face {
				    font-family:"<?php echo esc_attr($name); ?>";
				    src: url("<?php echo esc_url_raw($woff2); ?>") format("woff2");
				    font-style: <?php echo esc_attr($style); ?>;
				    font-weight: <?php echo esc_attr($weight); ?>;
				    font-display: swap;
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
			    <?php if($primary): ?>--font-primary: <?php echo esc_html($primary); ?>, sans-serif; <?php endif; ?>
			    <?php if($secondary): ?>--font-secondary: <?php echo esc_html($secondary); ?>, sans-serif; <?php endif; ?>
			    <?php if($third): ?>--font-third: <?php echo esc_html($third); ?>, sans-serif; <?php endif; ?>
			}

			<?php
		endif;
		?>
	</style>
	<?php
}

add_action('wp_head', 'add_fonts');
