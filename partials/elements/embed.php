<div class="embed-container">
	<?php
		// Klant-WYSIWYG-embed: kses met iframe-allowlist (stored-XSS-hardening)
		// en loading="lazy" op iframes zonder eager-load (HOD-17).
		$embed = !empty($content['embed']) ? $content['embed'] : '';
		if ($embed) {
			$embed = preg_replace('/<iframe(?![^>]*\\bloading=)/i', '<iframe loading="lazy"', $embed);
			$allowed = array(
				'iframe' => array(
					'src' => true, 'width' => true, 'height' => true, 'title' => true,
					'frameborder' => true, 'allow' => true, 'allowfullscreen' => true,
					'loading' => true, 'referrerpolicy' => true, 'style' => true, 'class' => true,
				),
				'div' => array('class' => true, 'style' => true),
				'p' => array('class' => true),
				'br' => array(),
			);
			echo wp_kses($embed, $allowed);
		}
	?>
</div>