<?php

/* Login page */
add_filter( 'login_display_language_dropdown', '__return_false' );
add_filter( 'login_site_html_link', '__return_false' );
add_filter( 'the_privacy_policy_link', '__return_false' );

add_filter( 'gettext', 'register_text' );
add_filter( 'ngettext', 'register_text' );
function register_text( $translated ) {
    $translated = str_ireplace(
        'Username or Email Address',
        'Email',
        $translated
    );
    return $translated;
}

function wpb_remove_loginshake() {
    remove_action('login_footer', 'wp_shake_js', 12);
}
add_action('login_footer', 'wpb_remove_loginshake');

function wpum_disable_remember_me( $fields ) {

    echo '<pre>'; print_r($fields); echo '</pre>';

        if ( isset( $fields['login']['remember'] ) ) {
            unset( $fields['login']['remember'] );
        }

        return $fields;

}
add_filter( 'login_form_fields', 'wpum_disable_remember_me' );

add_action( 'login_footer', function () {
    ?>
        <script>
            try {
                document.querySelector( '.forgetmenot' ).remove();
                document.querySelector( '#backtoblog' ).remove();
            } catch ( err ) {}
        </script>
    <?php
} );


// Custom Login Logo
function customize_login_logo() {
  
  $logo = get_field('site-logo', 'theme');
  
  if($logo):
    echo '<style type="text/css">
                h1 a {
                    background-image:url('.$logo.') !important;
                    background-size:contain !important;
                    height:60px !important;
                    width:300px !important;
                    margin-bottom: 1 !important;
                }
        </style>';
  endif;

  echo '<style type="text/css">
            .login #nav {
                text-align: center;
                margin:1.5rem 0 0 0;
                padding:0;
            }
            * {
                box-sizing: border-box;
            }
            body {
                display:flex;
                justify-content: center;
                align-items: center;
                box-sizing: border-box;
                background:#f9f7f1;
                margin:0;
                padding:0;
            }
            #login {
                overflow-y:auto;
                box-shadow: 0 4px 32px rgba(0,0,0,0.02);
                background:#fff;
                border-radius:1rem;
                padding: 1.5rem;
                margin:1.5rem;
                width:360px; 
            }
            #loginform {
                margin: 0;
                padding: 0;
                box-shadow: none;
                border: 0;
                background: transparent;
                overflow: visible;
            }
            .forgetmenot { display: none !important; }
            #backtoblog {margin:0;padding:0;display:none !important;}
            .submit {
                display:flex;
            }
            #wp-submit {
                border-radius: 2rem;
                display: flex;
                height: 2.5rem;
                align-items: center;
                justify-content: center;
                width:100%;
            }
        </style>';

}
add_action('login_head', 'customize_login_logo');

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter( 'login_headertext', 'my_login_logo_url_title' );