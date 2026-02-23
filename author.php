<?php

  /* Blog category archive */ 
  
  get_header();

  /* Get page content */
  include_once( locate_template( 'partials/blog/blog-archive-author.php', false, false ) );

  get_footer();

?>