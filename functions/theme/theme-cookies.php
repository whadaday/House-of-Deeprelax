<?php
function custom_cookie_text( $cookie_text ) {

    // echo '<pre>'; print_r($cookie_text); echo '</pre>'; die();

    $cookie_text['position'] = '';
    $cookie_text['css_class'] = '';
    $cookie_text['button_class'] = 'btn';
    $cookie_text['colors']['button'] = '';

    $lang = getLang();
    $label = 'Read more';
    if($lang == 'nl'): $label = 'Lees verder'; endif;
    $more_info = do_shortcode('[privacy text="'.$label.'"]');

    $text = '<p>'.$cookie_text['message_text'].'</p>';
    $cookie_text['message_text'] = $text;
    $cookie_text['message_text'] .= '<p>'.$more_info.'</p>';

    return $cookie_text;

}
add_filter( 'cn_cookie_notice_args', 'custom_cookie_text' );