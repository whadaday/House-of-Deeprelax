<?php
	$lang 		 	  = getLang();

	// Logo
	$siteName 	 	  = get_bloginfo('name');
	$logo 	  	 	  = get_field('site-logo', 'theme');
	$logoUrl  	 	  = getLogoUrl();

	// Nav
	$navigation  	  = get_field('navigation', $lang);
	$navColor 	 	  = get_field('navigation-color', $post_id) ? get_field('navigation-color', $post_id) : 'dark';

	// Nav side
	$navOptions		  = $navigation['nav-options'];
	$navSide 	  	  = isset($navOptions['show-nav-side']) ? $navOptions['show-nav-side'] : null;
	$navBehindHeader  = isset($navOptions['show-nav-behind-header']) ? $navOptions['show-nav-behind-header'] : null;
	$navHideScroll    = isset($navOptions['show-nav-hide-scroll']) ? $navOptions['show-nav-hide-scroll'] : null;

	// Nav format
	$navType 	 	  = $navigation['nav-type'] ? $navigation['nav-type'] : 1;
	$nav 		 	  = $navigation['nav-'.$navType];
	$menu1 		 	  = $nav['nav-menu-1'];
	$menu2 		 	  = $nav['nav-menu-2'];
	$navAlign 	 	  = isset($nav['column-align']) ? $nav['column-align'] : null;

	// Nav mobile
	$navMobile   	  = $navigation['nav-mobile'];
	$navDropdown 	  = isset($navOptions['show-nav-dropdown']) ? $navOptions['show-nav-dropdown'] : null;
?> 

	<nav id="nav-bar"
		<?php if($navBehindHeader): ?>data-behind-header="1"<?php endif; ?>
		<?php if($navHideScroll): ?>data-hide-scroll="1"<?php endif; ?>
	>

		<?php include( locate_template( 'partials/navigation/announcement.php', false, false ) ); ?>
		
		<div id="nav-main" data-color-text="<?php echo $navColor; ?>">
			<div class="container">
				<div
					class="columns"
					data-type="<?php echo $navType; ?>"
					<?php if($navAlign): ?>data-x-align="<?php echo $navAlign; ?>" <?php endif; ?>
				>

					<div class="column column-logo">
						<div class="content">
							<?php if($logo): ?>
								<a class="site-logo logo-primary" href="<?php echo $logoUrl; ?>" title="<?php echo $siteName; ?>">
									<?php echo file_get_contents($logo); ?>
								</a>
							<?php endif; ?>

							<?php if($navMobile && $navDropdown): ?>
								<a class="nav-back" href="#">
									<?php $icon = get_template_directory().'/assets/images/beam/icons/chevron-left.svg'; ?>
	                				<span class="chev-left"><?php echo file_get_contents($icon); ?></span>
								</a>
							<?php endif; ?>
						</div>
					</div>
					<?php if($menu1): ?>
						<div class="column column-nav">
							<ul class="list-nav">
								<?php
									$args = array(
										'menu' => $menu1,
										'container'=> false,
										'items_wrap' => '%3$s',
										'walker' => new main_menu_Walker()
						          	);
							        wp_nav_menu($args);
					          	?>
					        </ul>
					    </div>
					<?php endif; ?>

					<?php if($menu2): ?>
						<div class="column column-action">
							<ul class="list-nav list-nav-cta">
								<?php
									$args = array(
										'menu' => $menu2,
										'container'=> false,
										'items_wrap' => '%3$s',
										'walker' => new main_menu_Walker()
						          	);
						          	wp_nav_menu($args);
					          	?>
					        </ul>
					    </div>
					<?php endif; ?>

					<div class="column column-hamburger<?php if($navSide): ?> show-nav-side<?php endif; ?>">
						<a href="#" class="toggle-menu" aria-label="Open menu">
							<span class="hamburger-holder"><span class="hamburger"></span></span>
						</a>
					</div>

				</div>
			</div>
		</div>
		<?php include( locate_template( 'partials/navigation/navigation-dropdown.php', false, false ) ); ?>
	</nav>

	<?php 
		include( locate_template( 'partials/navigation/navigation-side.php', false, false ) );
		include( locate_template( 'partials/navigation/navigation-mobile.php', false, false ) );
	?>

	<div id="nav-backdrop"></div>