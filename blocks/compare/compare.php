<?php 
	/* Block Name: Compare */
	/* Order: 150 */

	$title 	 		= $content['title'];
	$eyebrow 		= $content['title-eyebrow'];
	$contradictions = $content['contradictions'];
?>

<?php if($title != '' || $eyebrow != ''): ?>
<div class="columns columns-header">
	<div class="column column-text">
		<div class="content">
			<?php if($eyebrow): echo '<h4>'.$eyebrow.'</h4>'; endif; ?>
			<?php if($title): echo '<h2>'.$title.'</h2>'; endif; ?>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="columns columns-contradictions">
	<div class="column column-text">
		<div class="content content-text">
			<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
			<?php include( locate_template( 'partials/elements/cta.php', false, false ) ); ?>
		</div>
	</div>
	<div class="column column-contradictions">
		<div class="content">
			<ul class="list-contradictions">
				<?php foreach($contradictions as $contradiction): ?>
					<li>
						<span class="contradiction-title"><?php echo $contradiction['title']; ?></span>
						<span class="contradiction-subtitle"><?php echo $contradiction['subtitle']; ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>