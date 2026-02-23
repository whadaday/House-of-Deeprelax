<?php

add_image_size( 'placeholder', 20, 9999 );
add_image_size( 'cover', 2500, 9999 );
update_option( 'uploads_use_yearmonth_folders', 1 );

// completely disable image size threshold
add_filter( 'big_image_size_threshold', '__return_false' );

