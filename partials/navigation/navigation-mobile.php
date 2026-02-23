<?php
	$navigation  = get_field('navigation', $lang);
	
	$navOptions  = $navigation['nav-options'];
	$navDropdown = isset($navOptions['show-nav-dropdown']) ? $navOptions['show-nav-dropdown'] : null;

	$navMobile   = $navigation['nav-mobile'];
	$navContent  = isset($navMobile['content-nav']) ? $navMobile['content-nav'] : null;
?>
<div id="nav-mobile">
	<div class="nav-mobile-container">
		<div class="nav-mobile-column">
			<div class="nav-mobile-inner">
				<?php include( locate_template( 'partials/navigation/navigation-builder.php', false, false ) ); ?>
			</div>
		</div>
		<?php
			if($navDropdown):
		      	wp_nav_menu(array(
					'menu' => $menu1,
					'container'=> false,
					'items_wrap' => '<div class="nav-mobile-column"><div class="nav-dropdown-items">%3$s</div></div>',
					'walker' => new dropdown_menu_Walker()
		      	));
		    endif;
	    ?>
	</div>
</div> 