<?php
	$featuredArticles = get_field('articles-featured');

	$query    = getArticles(null, $featuredArticles, null, 4);
  $articles = array_merge($query->posts,$query->posts);

  if($articles):
 ?>

<section
  id="section-1"
  class="section section-blog-carousel-featured"
  data-color="white"
  data-whitespace-top="normal" data-whitespace-bottom="normal">

	<div class="container">
		<div class="swiper-container blog-carousel-featured">
			
			<div class="slide-content-holder">
				<?php
					foreach($articles as $article):

						$post_id  	= $article;
						$title      = get_the_title($post_id);
						$categoryID = getArticleCategoryID($post_id); 
						if($categoryID): $category = get_term_by('id', $categoryID, 'category'); endif; 
						$link       = get_the_permalink($post_id);
				?>
						<div class="slide-content">

							<?php if($categoryID): ?><span class="slide-subtitle"><?php echo $category->name; ?></span><?php endif; ?>
							<a class="slide-content-link" href="<?php echo $link; ?>">
								<span class="slide-title"><?php echo $title; ?></span>
							</a>
							<div class="content-action" data-align="center">
								<a class="btn" data-btn-color="light" href="<?php echo $link; ?>">Lees verder</a>
					        </div>
						</div>
				<?php endforeach; ?>
			</div>


			<div class="swiper-wrapper">
			    <?php
			    	foreach($articles as $article):
					    $post_id  			  = $article;
							$link       		  = get_the_permalink($post_id);
							$title      		  = get_the_title($post_id);
							$date       		  = get_the_date('d F Y', $post_id);
							$image_id         = get_post_thumbnail_id($post_id);
		  				$content['image'] = acf_get_attachment($image_id);

							$categoryID = getArticleCategoryID($post_id); 
							if($categoryID): $category = get_term_by('id', $categoryID, 'category'); endif; 
				?>
			      <div class="column swiper-slide">
			        <div class="content">
			          <div class="card">
			            <?php
			            	include( locate_template( 'partials/elements/image.php', false, false ) );
			            ?>
			        	</div>
			        </div>
			      </div>
			    <?php
					endforeach;
				?>
			</div>
		  <div class="arrow-next"><span class="arrow"></span></div>
		  <div class="arrow-prev"><span class="arrow"></span></div>
		</div>
	</div>
  
</div>

<?php
	endif;
?>