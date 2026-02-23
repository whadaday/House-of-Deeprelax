<?php

function after_dev_mode_update( $field ) {
    // echo '<pre>'; print_r($field); echo '</pre>'; die();
    if($field == 1):
        update_option('blog_public', '0');
    else:
        update_option('blog_public', '1');
    endif;

    return $field;
}

add_filter('acf/update_value/name=dev-mode', 'after_dev_mode_update', 10, 1);

function show_grid_view() {
    $devMode = get_field('dev-mode', 'theme');
    if($devMode):
        include( locate_template( 'partials/dev/grid-view.php', false, false ) );
    endif;
}
add_action( 'wp_footer', 'show_grid_view', 100 );