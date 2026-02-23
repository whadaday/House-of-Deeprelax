<?php
  /* Opties */
  $cardLayout      = 'landscape';
  $colorText       = 'dark';
  $colorButton     = 'dark';
  $bgColorName     = 'white';

  /* Content */
  $post_id         = get_the_ID();
  $categories      = GetCategories(get_the_category());

  $relatedArticles = get_field('related-articles', $post_id);
  if($relatedArticles):
    $query           = getArticles(null, $relatedArticles, null, null);
  else:
    $query           = getArticles($categories, null, null, 4);
  endif;
  $articles        = $query->posts;

  $categoryID      = getArticleCategoryID($post_id);
  $category        = get_term_by('id', $categoryID, 'category');

  $title           = 'Ook interessant voor jou?';
  $linkText        = 'Alles in '.$category->name;
  $categoryLink    = get_category_link($categoryID);

  $whitespaceT     = 'normal';
  $whitespaceB     = 'normal'; 

  // echo '<pre>'; print_r($articles); echo '</pre>';

  if($articles):
?>

<section
  id="section-<?php echo $blockIndex; ?>"
  class="section section-blog-related"
  data-color="<?php echo $bgColorName; ?>"
  data-whitespace-top="<?php echo $whitespaceT; ?>" data-whitespace-bottom="<?php echo $whitespaceB; ?>">

  <div class="container content-animate">

    <div class="columns columns-header">
      <div class="column column-text">
        <?php if($title): ?><h2><?php echo $title; ?></h2><?php endif; ?>
      </div>
      <div class="column column-action">
        <div class="content-action">
          <a href="<?php echo $categoryLink; ?>" class="link"><?php echo $linkText; ?></a>
        </div>
      </div>
    </div>

    <div class="columns columns-blog">
      <div class="swiper-container carousel-blog">
        
        <div class="swiper-wrapper">

          <?php foreach($articles as $article): ?>

            <div class="swiper-slide">

              <div class="content content-blog">
                <?php include( locate_template( 'partials/blog/card-blog.php', false, false ) ); ?>
              </div>

            </div>

          <?php endforeach; ?>

        </div>
        <div class="guide-nav-container">
          <div class="guide-nav guide-nav-prev btn-slide-blog-prev"></div>
          <div class="guide-nav guide-nav-next btn-slide-blog-next"></div>
        </div>
        <div class="swiper-pagination"></div>

      </div>
    </div>

  </div>
</section>

<?php endif; ?>