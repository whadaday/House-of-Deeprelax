<?php 

  get_header();

  // Active term
  $queried_object = get_queried_object(); 
  $taxonomy = $queried_object->taxonomy;
  $term_id = $queried_object->term_id;  

  $query = getFaqArticles($term_id, -1);
  $articles = $query->posts; 

  $categories = get_terms( array(
    'taxonomy'   => 'faq-category',
    'hide_empty' => true,
  ) );

  $faqs = array();
  $iArticle = 0;
  foreach ($articles as $article):
    $faqs[$iArticle]['question'] = get_the_title($article->ID);
    $faqs[$iArticle]['answer']   = get_field('answer', $article->ID);
    $faqs[$iArticle]['link']     = get_field('link', $article->ID);
    $faqs[$iArticle]['loom']     = get_field('loom', $article->ID);
    $faqs[$iArticle]['id']       = $article->ID;
    $iArticle++;
  endforeach;

  wp_enqueue_script('faq', get_template_directory_uri() .'/blocks/faq/faq.js', array(), THEME_VERSION, 'all');

?>



<section class="section section-faq-detail" data-whitespace-top="small" data-whitespace-bottom="normal">
  <div class="container">
    <div class="columns columns-accordion" data-columns="1">
      <div class="column column-categories">
        <div class="content">
          <ul class="list-categories">
            <?php foreach($categories as $category): 
              $btnClass = 'link-cat';

              if($category->term_id == $term_id):
                $btnClass = 'link-cat cat-active';
              endif;


            ?>
              <li>
                <a href="<?php echo get_term_link($category); ?>" class="<?php echo $btnClass; ?>">
                  <?php echo $category->name; ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <div class="column column-accordion">
        <div class="content">
          <div class="accordion">
            <?php showFAQ($faqs); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php 
  
  $lang = getLang();
  $globalBlockId = get_field('faq-end', $lang);
  if($globalBlockId): showGlobalBlock($globalBlockId); endif;

  get_footer(); 

?>