<?php

foreach ($navContent as $content):

// echo '<pre>'; print_r($content);

$layout = $content['acf_fc_layout'];

if($layout == 'nav'):
	$menu  = $content['nav-menu'];
	$title = $content['nav-menu-title'];
	?>

	<div class="column column-list">
		<div class="content">
			<?php if($title): ?><span class="nav-title"><?php echo $title; ?></span><?php endif; ?>
			<ul class="list-nav-overlay">
				<?php
		          	wp_nav_menu(array(
						'menu' => $menu,
						'container'=> false,
						'items_wrap' => '%3$s',
						'walker' => new main_menu_Walker()
		          	));
	          	?>
			</ul>
		</div>
	</div>

	<?php
elseif($layout == 'title'):
	$name 	  = $content['nav-title'];
	$subtitle = $content['nav-subtitle'];
	?>

	<div class="column column-title">
		<div class="content">
			<?php if($subtitle): ?><span class="nav-title"><?php echo $subtitle; ?></span><?php endif; ?>
			<?php if($name != ''): ?><h3 class="nav-name"><?php echo $name; ?></h3><?php endif; ?>
		</div>
	</div>

	<?php
elseif($layout == 'text'):
	$name = $content['nav-title'];
	$text = $content['text'];
	?>

	<div class="column column-text">
		<div class="content">
			<?php if($name): ?><span class="nav-title"><?php echo $name; ?></span><?php endif; ?>
			<?php if($text != ''): echo $name; endif; ?>
		</div>
	</div>

	<?php

elseif($layout == 'cta'):
	// echo '<pre>'; print_r($content);

	$title = $content['nav-title'];

	$card = array(
		'title' => $content['title'],
		'url' 	=> $content['url'],
		'background-type' => $content['background-type'],
		'image' => $content['image'],
		'video' => $content['video'],
	);
?>
	<div class="column column-cta">
		<div class="content">
			<?php if($title): ?><span class="nav-title"><?php echo $title; ?></span><?php endif; ?>
			<?php include( locate_template( 'partials/elements/card.php', false, false ) ); ?>
		</div>
	</div>
<?php
elseif($layout == 'cards'):
	// echo '<pre>'; print_r($content);

	$title = $content['nav-title'];
	$cards = $content['cards'];
?>
	<div class="column column-grid">
		<div class="content">
			<?php if($title): ?><span class="nav-title"><?php echo $title; ?></span><?php endif; ?>
			<?php include( locate_template( 'partials/elements/cards.php', false, false ) ); ?>
		</div>
	</div>
<?php
elseif($layout == 'appstore'):
	// echo '<pre>'; print_r($content);

	$title = $content['nav-title'];
?>
	<div class="column">
		<div class="content">
			<?php if($title): ?><span class="nav-title"><?php echo $title; ?></span><?php endif; ?>
			<?php include( locate_template( 'partials/elements/appstore.php', false, false ) ); ?>
		</div>
	</div>
<?php
endif;
endforeach;