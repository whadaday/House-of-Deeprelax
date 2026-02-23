<?php
	$post_id 	= get_the_ID();
	$title 		= get_the_title();
	$lead 		= get_the_excerpt();
	// $categoryID = getArticleCategoryID($post_id);
	// if($categoryID):
	// 	$category 	= get_term_by('id', $categoryID, 'category');
	// endif;
	// $date 		= get_the_date('d.m.Y');
?>
<div class="content content-article-intro">
	<?php if($lead): ?><p class="article-lead"><?php echo $lead; ?></p><?php endif; ?>
</div>