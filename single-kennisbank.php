<?php
  
  get_header();
 
  if (have_posts()):
    
    while(have_posts()):
      
        the_post();
        
        ?>
        <figure id="progress" class="bar-progress"></figure>
        <section id="article" class="section section-article" data-whitespace-top="normal" data-whitespace-bottom="normal">

          <div class="container"> 
            
            <?php include_once( locate_template( 'partials/navigation/breadcrumbs.php', false, false ) ); ?>


            <div class="columns columns-article">
              <div class="column column-article"> 
                <?php
                  include( locate_template( 'partials/blog/article-intro.php', false, false ) );
                  include( locate_template( 'partials/blog/content/article-content.php', false, false ) );
                  include( locate_template( 'partials/blog/article-faq.php', false, false ) );
                  include( locate_template( 'partials/blog/article-tags.php', false, false ) );
                  include( locate_template( 'partials/blog/article-author.php', false, false ) );
                  include( locate_template( 'partials/blog/article-share.php', false, false ) );
                  include( locate_template( 'partials/blog/article-top.php', false, false ) );
                ?>
              </div>

              <div class="column column-widgets"> 
                <aside>

                    <?php
                      $lang = getLang();
                      $mostRead = get_field('kennisbank-widget-mostread', $lang);
                      if($mostRead):
                        ?>
                        <div class="widget widget-articles">
                          <h4>Meest gelezen</h4>
                          <ol class="list-articles">
                            <?php foreach($mostRead as $article): ?>
                              <li><a href="<?php echo get_the_permalink($article); ?>"><?php echo get_the_title($article); ?></li></a>
                            <?php endforeach; ?>
                          </ol>
                        </div>
                        <?php
                      endif;
                    ?>


                      <?php
                        $banner = (get_field('banner-promo')) ? get_field('banner-promo') : get_field('kennisbank-widget-banner', $lang);
                        if($banner):
                          ?>
                          <div class="widget widget-banner">
                          <?php
                            $content = get_field('content', $banner);
                            $options = get_field('options', $banner);
                            $block['content'] = $content;
                            $block['options'] = $options;
                            include( locate_template( 'blocks/banner/banner.php', false, false ) );
                          ?>
                          </div>
                        <?php endif; ?>
                    
                </aside>
              </div>

            </div>
          </div>
        </section>

        <?php include_once( locate_template( 'partials/general/page-banner.php', false, false ) ); ?>
        <?php include_once( locate_template( 'partials/kennisbank/kennisbank-related.php', false, false ) ); ?>

        <?php
    endwhile;

  endif;

  get_footer();

?>