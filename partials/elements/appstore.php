<?php
	$lang = getLang();
  $appstores = get_field('appstore', $lang);
  $qr_src = get_template_directory_uri().'/assets/images/qr-download-app.jpg';
  if($appstores):
?>

	<ul class="list-apps">
		<li class="dynamic-qr">
			<img src="<?php echo $qr_src; ?>" />
			<p>Scan de QR-code met je telefoon en krijg direct toegang tot de Deeprelax-app.</p>
		</li>
		<li>
			<a class="link-appstore" href="<?php echo $appstores['apple']; ?>" target="_blank" aria-label="Ontdek het op Google play">
				<?php 
					$logo = get_template_directory().'/assets/images/badge-appstore.svg'; 
					if($logo): echo file_get_contents($logo); endif;
				?>
			</a>
		</li>
		<li>
			<a class="link-playstore" href="<?php echo $appstores['android']; ?>" target="_blank" aria-label="Download in de App Store">
				<?php 
					$logo = get_template_directory().'/assets/images/badge-playstore.svg'; 
					if($logo): echo file_get_contents($logo); endif;
				?>
			</a>
		</li>
	</ul>

<?php endif; ?>