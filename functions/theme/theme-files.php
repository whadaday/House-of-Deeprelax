<?php

function custom_mime_types( $mimes ) {

    // New allowed mime types.
    $mimes['svg']   = 'image/svg+xml';
    $mimes['svgz']  = 'image/svg+xml';
    $mimes['woff2'] = 'font/woff2';
 
    // Optional. Remove a mime type.
    // unset( $mimes['exe'] );

    // echo '<pre>'; print_r($mimes); echo '</pre>'; die();

    return $mimes;
}
 
add_filter( 'upload_mimes', 'custom_mime_types' );

function divi_engine_font_correct_filetypes( $data, $file, $filename, $mimes, $real_mime ) {

    if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
        return $data;
    }

    $wp_file_type = wp_check_filetype( $filename, $mimes );

    // Check for the file type you want to enable, e.g. 'svg'.
    if ( 'woff2' === $wp_file_type['ext'] ) {
        $data['ext'] = 'woff2';
        $data['type'] = 'font/woff2';
    }

    if ( 'svg' === $wp_file_type['ext'] ) {
        $data['ext'] = 'svg';
        $data['type'] = 'image/svg+xml';
    }

    return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'divi_engine_font_correct_filetypes', 10, 5 );