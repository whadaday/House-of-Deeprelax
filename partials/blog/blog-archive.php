<?php

  /** is this a category archive page? */
  if(is_category()):
    $category = $wp_query->get_queried_object();
    $title = $category->name;
    $query = getArticles($category->term_id);

  /** is this a category archive page? */
  elseif(is_tag()):
    $tag = $wp_query->get_queried_object();
    $title = $tag->name;
    $query = getArticles($tag->term_id);

  /* Or the parent blog archive */
  else:
    $query = getArticles();
    $title = 'Blog';
  endif;

  $articles = $query->posts;
  if($articles):
?>

<section
  id="section-1"
  class="section section-blog-grid"
  data-color="white"
  data-whitespace-top="normal" data-whitespace-bottom="normal">

  <div class="container content-animate">

    <?php include_once( locate_template( 'partials/navigation/breadcrumbs.php', false, false ) ); ?>

    <div class="columns columns-header">
      <div class="column column-text">
        <h1><?php echo $title; ?></h1>
      </div>
    </div>

    <div class="columns columns-blog" data-layout="portrait">

        <?php foreach($articles as $article): ?>

          <div class="column">
              
              <div class="content content-blog">
                <?php include( locate_template( 'partials/blog/card-blog.php', false, false ) ); ?>
              </div>

            </div>

          <?php endforeach; ?>

    </div>

    <div class="columns columns-footer">
      <div class="column">
        <div class="content">
            <?php
              $icon = get_template_directory().'/assets/images/icon-arrow.svg';
              $paginateArgs = array(
                  'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                  'total'        => $query->max_num_pages,
                  'current'      => max( 1, get_query_var( 'paged' ) ),
                  'format'       => '?page=%#%',
                  'show_all'     => false,
                  'type'         => 'list',
                  'end_size'     => 1,
                  'mid_size'     => 1,
                  'prev_next'    => true,
                  'prev_text'    => '<i class="pagination-arrow-prev">'.file_get_contents($icon).'</i>',
                  'next_text'    => '<i class="pagination-arrow-next">'.file_get_contents($icon).'</i>',
                  'add_args'     => false,
                  'add_fragment' => '',
              );
              echo paginate_links($paginateArgs);
          ?>
        </div>
      </div>
    </div>

  </div>
</section>

<?php endif; ?>