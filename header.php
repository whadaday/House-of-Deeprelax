<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <?php wp_head(); ?>
</head>
<?php

  /* netter maken */
  $headerClasses  = array();
  $headerDisabled = false;

  $hideNav = get_field('hide-nav-footer');

  $hideNav = false;
  // if(is_tax('faq-category')):
  //   $hideNav = true;
  // endif;


  if($hideNav): array_push($headerClasses, 'no-navigation', 'no-footer', 'page-sales'); endif;

  if(is_404() || is_search() || is_author() || is_archive() || is_category() || is_singular('kennisbank') || is_home()):
    $showContact = true;
  else:
    $showContact = get_field('show-contact');

  endif;

  if($showContact): array_push($headerClasses, 'show-contact'); endif;



  if(is_archive() && !is_archive('kennisbank')):
    $term = get_queried_object();
    // echo '<pre>'; print_r($term); echo '</pre>'; die();
    $post_id = $term->taxonomy.'_'.$term->term_id;
  else:
    $post_id = get_the_ID();
  endif;
  $headerOptions  = get_field('options', $post_id);

  $themeStyles = get_field('styles', 'theme');
  if($themeStyles):
    $colorText   = $themeStyles['body-text-color'];
  else:
    $colorText   = 'dark';
  endif;

  if(is_404() || is_search() || is_author() || is_home() || is_archive() || is_category() ):
      $headerDisabled = true;
  endif;

  if(is_tax('faq-category')):
    $headerDisabled = false;
  endif;

  if(isset($headerOptions) && $headerOptions && !is_singular('post') && !is_singular('kennisbank')):
    $headerDisabled = $headerOptions['disabled'];
  endif;

  if($headerDisabled):
    array_push($headerClasses, 'no-header');
  endif;
?>
<body
  id="body"
  <?php body_class($headerClasses); ?>
  data-color-text="<?php echo $colorText; ?>"
  <?php echo apply_filters('body_custom_attributes', ''); ?>
>
  <?php wp_body_open(); ?>

  <div id="wrapper">
  
    <?php 
      if(!$hideNav): include( locate_template( 'partials/navigation/navigation.php', false, false ) ); endif; 
      include( locate_template( 'partials/navigation/bottombanner.php', false, false ) );

      if( is_page() || is_tax('faq-category') || is_singular('book') || is_singular('landingpage') ): 
        include( locate_template( 'partials/general/page-header.php', false, false ) );
      elseif(is_singular('post') || is_singular('kennisbank')):
        include( locate_template( 'partials/blog/blog-header.php', false, false ) );    
      elseif( is_singular( 'guide' ) ):
        include( locate_template( 'partials/guide/guide-header.php', false, false ) );
      endif;
    ?>

    <main id="main">