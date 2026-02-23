<div class="nav-dropdown-content" data-dropdown-item="<?php echo $page_id; ?>">
	<div class="container">
		<div class="columns">
			<?php 
				$navContent = $dropdown;
				include( locate_template( 'partials/navigation/navigation-builder.php', false, false ) );
			?>
		</div>
	</div>
</div>