<?php
  function add_option_pages() {
    if(function_exists('acf_add_options_page')):
      
      
      /* Theme options */
      $args = array(
          'page_title'  => __('Theme options'),
          'menu_title'  => __('Theme options'),
          'menu_slug'   => 'theme',
          'post_id'     => 'theme',
          'capability'  => 'administrator'
      );
      $themeOptions = acf_add_options_page($args);

      /* Site options */
      $parent = acf_add_options_page(array(
          'page_title'  => __('Site options'),
          'menu_title'  => __('Site options'),
          'redirect'    => true,
      ));

      /* Languages */
      $defaultLang = get_field('site-lang', 'theme');
      if(function_exists( 'pll_the_languages' )):
        $languages = pll_languages_list();
      else:
        $languages = array($defaultLang);
      endif;

      foreach ($languages as $lang):
        $args = array(
          'page_title'  => strtoupper($lang).' options',
          'menu_title'  => strtoupper($lang).' options',
          'parent_slug' => $parent['menu_slug'],
          'menu_slug'   => "options-${lang}",
          'post_id'     => $lang
        );
        $child = acf_add_options_sub_page($args);
      endforeach;

  endif;
}

add_action('init', 'add_option_pages');