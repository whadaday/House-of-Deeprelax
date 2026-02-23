<?php
	$tags = get_the_tags();
	if($tags):
?>
<div class="content content-article-tags">
	<ul class="list-article-tags">
		<?php foreach($tags as $tag): ?>
			<li><a class="btn" href="<?php echo get_term_link($tag->term_id); ?>"><?php echo $tag->name; ?></a></li>
    	<?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>