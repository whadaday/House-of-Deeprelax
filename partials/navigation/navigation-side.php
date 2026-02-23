<?php
	$navigation  = get_field('navigation', $lang);
	$navOptions  = $navigation['nav-options'];
	$navDropdown = isset($navOptions['show-nav-side']) ? $navOptions['show-nav-side'] : null;

	if($navSide):
		$navContent = $navigation['nav-side'];
		$navContent = $navContent['content-nav'];
?>
		<div id="nav-side">
			<div class="nav-side-header">
				<div class="columns">
					<div class="column column-title">
						<span class="nav-overlay-title">Menu</span>
					</div>
					<div class="column column-hamburger">
						<a href="#" class="toggle-menu" aria-label="Open menu">
							<span class="hamburger-holder hamburger-close"><span class="hamburger"></span></span>
						</a>
					</div>
				</div>
			</div>
			<div class="nav-side-content">
				<?php include( locate_template( 'partials/navigation/navigation-builder.php', false, false ) ); ?>
			</div>
		</div>
<?php
	endif;
 