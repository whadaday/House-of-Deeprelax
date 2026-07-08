<?php
	$lang = getLang();
  $appstores = hod_option('appstore', $lang);
  $qr_src = get_template_directory_uri().'/assets/images/qr-download-app.jpg';
  if($appstores):
?>

	<ul class="list-apps">
		<li class="dynamic-qr">
			<img src="<?php echo esc_url($qr_src); ?>" alt="QR-code om de app te downloaden" />
			<p>Scan de QR-code met je telefoon en krijg direct toegang tot de Deeprelax-app.</p>
		</li>
		<li>
			<a class="link-appstore" href="<?php echo esc_url($appstores['apple']); ?>" target="_blank" rel="noopener noreferrer" aria-label="Download in de App Store">
				<?php 
					$logo = get_template_directory().'/assets/images/badge-appstore.svg'; 
					if($logo): echo file_get_contents($logo); endif;
				?>
			</a>
		</li>
		<li>
			<a class="link-playstore" href="<?php echo esc_url($appstores['android']); ?>" target="_blank" rel="noopener noreferrer" aria-label="Ontdek het op Google Play">
				<?php 
					$logo = get_template_directory().'/assets/images/badge-playstore.svg'; 
					if($logo): echo file_get_contents($logo); endif;
				?>
			</a>
		</li>
	</ul>

<?php endif; ?>