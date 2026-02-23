<?php
    $title          = get_field('title', $widget_id);
    $text           = get_field('text', $widget_id);
    $image 		    = get_field('image', $widget_id);
    $imageUrl	    = $image['sizes']['large'];
    $crop           = getImageCrop($image['id']);
    $link 		    = get_field('link', $widget_id);
    $link_url       = $link['url'];
    $link_title     = $link['title'];
    $link_target    = $link['target'] ? $link['target'] : '_self';
?>

<div class="widget-ad">
    <a href="<?php echo $link_url; ?>" class="widget-link" target="<?php echo $link_target; ?>">
		<figure class="widget-image img-background" data-crop="<?php echo $crop; ?>" style="background-image: url('<?php echo $imageUrl; ?>');"></figure>
	</a>
    <div class="widget-content" data-color="<?php echo $color; ?>" data-text-align="<?php echo $align; ?>">
			<h4 class="widget-title"><?php echo $title; ?></h4>
            <p class="widget-text"><?php echo $text; ?></p>

            <div class="content-action" data-btn-color="color">
                <a href="<?php echo $link_url; ?>" class="btn" target="<?php echo $link_target; ?>"><?php echo $link_title; ?></a>
            </div>
		</div>
</div>