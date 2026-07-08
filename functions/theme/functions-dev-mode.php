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
    // Alleen tonen buiten productie of voor beheerders — voorkomt dat een
    // per ongeluk aangelaten dev-mode-toggle de grid-overlay (en blog_public=0
    // de-index) aan álle bezoekers toont. (HOD-28)
    $is_prod = function_exists('wp_get_environment_type') && wp_get_environment_type() === 'production';
    if ( $is_prod && ! current_user_can('manage_options') ) {
        return;
    }
    $devMode = hod_option('dev-mode');
    if($devMode):
        include( locate_template( 'partials/dev/grid-view.php', false, false ) );
    endif;
}
add_action( 'wp_footer', 'show_grid_view', 100 );