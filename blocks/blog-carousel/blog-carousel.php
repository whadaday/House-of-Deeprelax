<?php 
  /* Block Name: Article feed */
  /* Order: 100 */

  // echo '<pre>'; 
  // print_r($content); 
  // print_r($options); 
  // echo '</pre>';
  
  $title  = $content['title'];
  $link   = $content['link'];
  $filter = $content['filter']['blog-filter']; // 'latest' or 'custom'

  if($filter == 'custom'):
    $articles = $content['filter']['blog-filter-articles'];
  else:
    $categories = $content['filter']['blog-filter-category'];
    $queryArticles  = getArticles($categories, null, null, 4);
    $articles       = $queryArticles->posts;
  endif;

  if($articles):
?>
  <?php if($title): ?>
    <div class="columns columns-header">
      <div class="column column-text">
        <h2><?php echo $title; ?></h2>
      </div>
      <div class="column column-action">
        <?php if(!empty($link)):
          $link_url    = $link['url'];
            $link_title  = $link['title'];
            $link_target = $link['target'] ? $link['target'] : '_self';
        ?>
          <a 
            class="link"
            href="<?php echo esc_url( $link_url ); ?>"
            target="<?php echo esc_attr( $link_target ); ?>">
            <?php echo esc_html( $link_title ); ?>
          </a>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

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

<?php endif; ?>