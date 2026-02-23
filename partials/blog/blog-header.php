<?php
		
		$headerHeight = 'content';
		$headerType 	= 'image';
		$bgColorName 	= 'white';
		$colorText 		= 'light';

		$post_id 		  = get_the_ID();
		$image_id 		  = get_post_thumbnail_id($post_id);
		$align			= 'bottom';

		$title = get_the_title();

		$cat_exist = taxonomy_exists( 'category' );
		if($cat_exist):
			$categoryID = getArticleCategoryID($post_id); 
			if($categoryID):
				$category = get_term_by('id', $categoryID, 'category');
				$catLink  = get_term_link($category); 
			endif; 
		endif;

		$date       = get_the_date('d/m/Y', $post_id);
?>

<header
	id="header"
	class="header-news"
	data-height="<?php echo $headerHeight; ?>"
	data-type="<?php echo $headerType; ?>"
	data-color="<?php echo $bgColorName; ?>"
	data-color-text="<?php echo $colorText; ?>"
>
	<?php
		if($image_id):
			$content['image'] = acf_get_attachment($image_id);
			$isCoverImage 	  = true;
	?>
			<div class="header-image">
				<?php include( locate_template( 'partials/elements/image.php', false, false ) ); ?>
			</div>
	<?php endif; ?>
 
	<?php /* Content */ ?>
	<div class="header-content">
		<div class="container">
			<div class="columns">
				<div class="column column-text">
					<div class="content content-text">
						<span class="card-article-info">
							<?php
								if(is_singular('kennisbank')):
									?><a class="card-category" href="<?php echo get_post_type_archive_link('kennisbank'); ?>">Kennisbank</a><?php
								else:
							?>
					          		<?php if(isset($categoryID)): ?>
					          			<a class="card-category" href="<?php echo $catLink; ?>"><?php echo $category->name; ?></a>
					          		<?php endif; ?>
				          	<?php endif; ?>
			          		<?php /*<span class="card-date"><?php echo $date; ?></span>*/ ?>
		          		</span>
						<h1><?php echo $title; ?></h1>
					</div>
				</div>
			</div>
		</div>
	</div>	

</header>