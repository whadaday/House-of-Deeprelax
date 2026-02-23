<?php

  /* Blog archive */ 
  
  get_header();

  // Get page content
  include( locate_template( 'partials/blog/blog-carousel-featured.php', false, false ) );
  include( locate_template( 'partials/general/page-content.php', false, false ) );

  get_footer();

?>