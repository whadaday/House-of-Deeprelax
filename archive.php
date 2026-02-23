<?php

  /* Blog category archive */ 
  
  get_header();

  /* Get page content */
  if ( is_post_type_archive( 'kennisbank' ) ):
    include_once( locate_template( 'partials/kennisbank/kennisbank-archive.php', false, false ) );
  else:
    include_once( locate_template( 'partials/blog/blog-archive.php', false, false ) );
  endif;

  get_footer();

?>