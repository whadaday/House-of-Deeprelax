  <?php
  if(is_array($article)):
    $post_id  = $article->ID;
  else:
    $post_id  = $article;
  endif;

  $readTime         = getPostReadTime($post_id);
  $link             = get_the_permalink($post_id);
  $title            = get_the_title($post_id);
  // $excerpt        = get_the_excerpt($post_id);
  $date             = get_the_date('d F Y', $post_id);
  $image_id         = get_post_thumbnail_id($post_id);
  $content['image'] = acf_get_attachment($image_id);

  $categoryID = getArticleCategoryID($post_id); 
  if($categoryID):
    $category = get_term_by('id', $categoryID, 'category');
  endif; 
?>
<div class="card-blog">
  
  <a class="card-link" href="<?php echo $link; ?>" aria-label="<?php echo $title; ?>"></a>
    
    <div class="card-image-holder">
      <?php 
        $imageSize = 's';
        include( locate_template( 'partials/elements/image.php', false, false ) );
        $imageSize = '';
      ?>
    </div>
  
    <div class="card-content">
      <?php //if($categoryID): echo '<span class="card-category">'.$category->name.'</span>'; endif; ?>
      <h3 class="card-title"><?php echo $title; ?></h3>
      <div class="card-text"><p><?php echo $readTime; ?></p></div>
      <?php //if($excerpt ): echo '<p class="card-text">'.$excerpt.'</p>'; endif; ?>
      <span class="card-btn card-btn-dummy"><span>Lees meer</span></span>
    </div>

</div> 