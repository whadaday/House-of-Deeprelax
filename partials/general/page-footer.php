<?php
	$hideNav 	 = get_field('hide-nav-footer');
	$lang 		 = getLang();
	$logoUrl  	 = getLogoUrl();
	$icon 	 	 = get_field('site-icon', 'theme');
	$siteName 	 = get_field('site-name', $lang) ?: get_bloginfo('name');
	$chevDown	 = get_template_directory().'/assets/images/beam/icons/chevron-down.svg';
	$navLegal 	 = get_field('nav-legal', $lang);

	$themeStyles = get_field('styles', 'theme');
	if($themeStyles):
		$colorText   = $themeStyles['footer-text-color'];
	else:
		$colorText   = 'dark';
	endif;

  	
?>

<footer 
	id="footer"
	data-color-text="<?php echo $colorText; ?>"
>
	
	<?php if(!$hideNav): 
		$menus 	     = array('footer-1', 'footer-2', 'footer-3', 'footer-4');
		$labelApp 	 = get_field('appstore-title', $lang);
		$labelSocial = get_field('social-title', $lang);
		$thankyou    = get_field('appstore-description', $lang);
	?>
		<div class="footer-top">
			<?php if($icon): ?>
				<a class="site-icon" href="<?php echo $logoUrl; ?>" title="<?php echo $siteName; ?>">
					<?php echo file_get_contents($icon); ?>
				</a>
			<?php endif; ?>
			
			<div class="container">

				<div class="columns">

					<?php
						$menu_location = 'footer-1';
						$menu_id = getMenuIdByLocation($menu_location);
						if($menu_id):
							$menu = wp_get_nav_menu_object( $menu_id );
					?>
						<div class="column column-nav">
							<div class="content content-menu">
								<h4 class="nav-title nav-dropdown">
									<?php echo $menu->name; ?>
									<span class="chev-down"><?php echo file_get_contents($chevDown); ?></span>
								</h4>
								<?php
								  	wp_nav_menu(array(
										'theme_location' => $menu_location,
										'menu_class' => 'list-nav-overlay',
										'container'=> false,
										'depth' => 1,
								  	));
								?>
							</div>

							<?php
							$menu_location = 'footer-1-b';
							$menu_id = getMenuIdByLocation($menu_location);
							if($menu_id):
								$menu = wp_get_nav_menu_object( $menu_id );
								?>
								<div class="content content-menu">
									<h4 class="nav-title nav-dropdown">
										<?php echo $menu->name; ?>
										<span class="chev-down"><?php echo file_get_contents($chevDown); ?></span>
									</h4>
									<?php
									  	wp_nav_menu(array(
											'theme_location' => $menu_location,
											'menu_class' => 'list-nav-overlay',
											'container'=> false,
											'depth' => 1,
									  	));
									?>
								</div>
							<?php endif; ?>

						</div>
					<?php endif; ?>

					<?php
						$menu_location = 'footer-2';
						$menu_id = getMenuIdByLocation($menu_location);
						if($menu_id):
							$menu = wp_get_nav_menu_object( $menu_id );
					?>
						<div class="column column-nav">
							<div class="content content-menu">
								<h4 class="nav-title nav-dropdown">
									<?php echo $menu->name; ?>
									<span class="chev-down"><?php echo file_get_contents($chevDown); ?></span>
								</h4>
								<?php
								  	wp_nav_menu(array(
										'theme_location' => $menu_location,
										'menu_class' => 'list-nav-overlay',
										'container'=> false,
										'depth' => 1,
								  	));
								?>
							</div>

							<?php
							$menu_location = 'footer-2-b';
							$menu_id = getMenuIdByLocation($menu_location);
							if($menu_id):
								$menu = wp_get_nav_menu_object( $menu_id );
								?>
								<div class="content content-menu">
									<h4 class="nav-title nav-dropdown">
										<?php echo $menu->name; ?>
										<span class="chev-down"><?php echo file_get_contents($chevDown); ?></span>
									</h4>
									<?php
									  	wp_nav_menu(array(
											'theme_location' => $menu_location,
											'menu_class' => 'list-nav-overlay',
											'container'=> false,
											'depth' => 1,
									  	));
									?>
								</div>
							<?php endif; ?>

						</div>
					<?php endif; ?>

					<?php
						$menu_location = 'footer-3';
						$menu_id = getMenuIdByLocation($menu_location);
						if($menu_id):
							$menu = wp_get_nav_menu_object( $menu_id );
					?>
						<div class="column column-nav">
							<div class="content content-menu">
								<h4 class="nav-title nav-dropdown">
									<?php echo $menu->name; ?>
									<span class="chev-down"><?php echo file_get_contents($chevDown); ?></span>
								</h4>
								<?php
								  	wp_nav_menu(array(
										'theme_location' => $menu_location,
										'menu_class' => 'list-nav-overlay',
										'container'=> false,
										'depth' => 1,
								  	));
								?>
							</div>
						</div>
					<?php endif; ?>

					<div class="column column-appstore">
						<div class="menu-container">
							<?php if($labelApp != ''): ?><h4 class="nav-title"><?php echo $labelApp; ?></h4><?php endif; ?>
							<?php include( locate_template( 'partials/elements/appstore.php', false, false ) ); ?>
							<?php if($thankyou): ?><p class="text-thankyou"><?php echo $thankyou; ?></p><?php endif; ?>
						</div>
					</div>

				</div>

			</div>
		</div>
	<?php endif; ?>

	<div class="footer-bottom">
		<div class="container">
			
			<div class="columns">
				<div class="column column-copyright">
					<div class="content">
						<p class="copy">&copy; <?php echo date('Y'); ?> <span><?php echo $siteName; ?></span></p>
					</div>
				</div>
				<?php if($navLegal): ?>
					<div class="column column-nav">
						<div class="content">
							<ul class="list-meta">
								<?php
									$args = array(
										'menu' => $navLegal,
										'container'=> false,
										'items_wrap' => '%3$s',
										'depth' => 1,
						          	);
						          	wp_nav_menu($args);
					          	?>
					        </ul>
						</div>
					</div>
				<?php endif; ?>
		
			</div>
		</div>
	</div>

</footer>