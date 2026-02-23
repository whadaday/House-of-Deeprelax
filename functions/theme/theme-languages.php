<?php
  
  function getLang() {
    $defaultLang = get_field('site-lang', 'theme');
    if(function_exists( 'pll_the_languages' )):
      $lang = pll_current_language();
    else:
      $lang = $defaultLang;
    endif;

    return $lang;
  }

?>