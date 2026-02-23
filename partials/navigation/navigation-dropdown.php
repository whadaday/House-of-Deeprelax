<?php
	$navigation  = get_field('navigation', $lang);
	$navOptions  = $navigation['nav-options'];
	$navDropdown = isset($navOptions['show-nav-dropdown']) ? $navOptions['show-nav-dropdown'] : null;

	if($navDropdown):
      	wp_nav_menu(array(
			'menu' => $menu1,
			'container'=> false,
			'items_wrap' => '<div id="nav-dropdown"><div class="nav-dropdown-items">%3$s</div></div>',
			'walker' => new dropdown_menu_Walker()
      	));
    endif;
?> 