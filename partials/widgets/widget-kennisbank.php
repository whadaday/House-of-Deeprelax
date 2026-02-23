<?php
    $title  = get_field('title', $widget_id);
    $filter = get_field('kennisbank-filter', $widget_id); // 'latest' or 'custom'

	if($filter == 'custom'):
		$articles 		= get_field('kennisbank-filter-articles', $widget_id);
	else:
		$articles  = getKennisbankArticles(null, null, null, 5);
	endif;

	if($articles):
?>

<div class="widget widget-kennisbank">
    <div class="widget-content">
		<h4 class="widget-title"><?php echo $title; ?></h4>
		<ul class="list-featured">
			<?php foreach($articles as $article): 
				$post_id    = $article;
				$link       = get_the_permalink($post_id);
  				$title      = get_the_title($post_id);
			?>
				<li><a href="<?php echo $link; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></li>
			<?php endforeach; ?>
		</ul>
    </div>
</div>

<?php endif; ?>