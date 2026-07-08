<?php

function custom_redirects() {

	/* Privacy policy page */
	$lang 			= getLang();
	$policy_page_id = get_option( 'wp_page_for_privacy_policy' );
	$policy_pdf 	= hod_option('privacy-policy', $lang);

	if ( $policy_page_id != '' && is_page($policy_page_id) ):
		if($policy_pdf):
			wp_safe_redirect( esc_url_raw($policy_pdf) );
			exit();
		// else:
		// 	global $wp_query;
		// 	$wp_query->set_404();
		// 	status_header( 404 );
		// 	get_template_part( 404 );
		// 	exit();

		endif;

	endif;

	/* Terms policy page */
	$terms_page_id = hod_option('terms-page', $lang);
	$terms_pdf 	   = hod_option('terms', $lang);

	if ( $terms_page_id != '' && is_page($terms_page_id) ):
		if($terms_pdf):
			wp_safe_redirect( esc_url_raw($terms_pdf) );
			exit();
		// else:
		// 	global $wp_query;
		// 	$wp_query->set_404();
		// 	status_header( 404 );
		// 	get_template_part( 404 );
		// 	exit();

		endif;

	endif;

}
add_action( 'template_redirect', 'custom_redirects' );