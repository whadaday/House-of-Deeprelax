<?php

  /**
   * Current language slug, resolved once per request.
   *
   * getLang() wordt op ~30 sites aangeroepen (o.a. per menu-item in de
   * nav-walker) en deed elke keer een get_field('site-lang','theme')-read —
   * óók wanneer Polylang actief was en de waarde niet eens gebruikt werd. De
   * static memo maakt er één berekening van, en de fallback-read leunt op de
   * options-cache (hod_option). Zie docs/OPTIMALISATIE.md HOD-08.
   */
  function getLang() {
    static $lang = null;
    if ($lang !== null) {
      return $lang;
    }

    if (function_exists('pll_the_languages')) {
      $lang = pll_current_language();
    } else {
      $lang = function_exists('hod_option') ? hod_option('site-lang') : get_field('site-lang', 'theme');
    }

    return $lang;
  }

?>
