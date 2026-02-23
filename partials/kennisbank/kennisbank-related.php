<?php
  /* Opties */
  $cardLayout      = 'landscape';
  $colorText       = 'dark';
  $colorButton     = 'dark';
  $bgColorName     = 'white';

  /* Content */
  $post_id         = get_the_ID();
  $categories      = GetCategories(get_the_category());

  $articles        = getKennisbankArticles(null, null, null, 4);

  $title           = 'Ook interessant voor jou?';
  $linkText        = 'Bekijk alles';
  $categoryLink    = get_post_type_archive_link('kennisbank');

  $whitespaceT     = 'normal';
  $whitespaceB     = 'normal'; 

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