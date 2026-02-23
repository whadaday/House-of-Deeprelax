<?php

function change_roles() {
    global $wp_roles;

    $wp_roles->roles['administrator']['name'] = 'Beam Admin';
    $wp_roles->role_names['administrator'] = 'Beam Admin';

	remove_role( 'editor' );
	remove_role( 'staff_member' );
	remove_role( 'admin' );
	remove_role( 'author' );
	remove_role( 'contributor' );
	remove_role( 'subscriber' );
	remove_role( 'wpseo_manager' );
	remove_role( 'wpseo_editor' );

	//https://wordpress.org/documentation/article/roles-and-capabilities

	/* Create Staff Member User Role */
	$role = 'admin';
	$display_name = __('Website beheerder');
	$capabilities = array(
	    'switch_themes' 			=> false,
		'edit_themes' 				=> false,
		'activate_plugins' 			=> false,
		'edit_plugins' 				=> false,
		'edit_users' 				=> true,
		'manage_options' 			=> false,
		'moderate_comments' 		=> false,
		'manage_categories' 		=> true,
		'manage_links' 				=> false,
		'upload_files' 				=> true,
		'import' 					=> false,
		'unfiltered_html' 			=> true,
		'edit_posts' 				=> true,
		'edit_others_posts' 		=> true,
		'edit_published_posts' 		=> true,
		'publish_posts' 			=> true,
		'edit_pages' 				=> true,
		'read' 						=> true,
		'level_true0' 				=> false,
		'level_9' 					=> false,
		'level_8' 					=> false,
		'level_7' 					=> false,
		'level_6' 					=> false,
		'level_5' 					=> false,
		'level_4' 					=> false,
		'level_3' 					=> false,
		'level_2' 					=> false,
		'level_true' 				=> false,
		'level_0' 					=> false,
		'edit_others_pages' 		=> true,
		'edit_published_pages' 		=> true,
		'publish_pages' 			=> true,
		'delete_pages' 				=> true,
		'delete_others_pages' 		=> true,
		'delete_published_pages' 	=> true,
		'delete_posts' 				=> true,
		'delete_others_posts' 		=> true,
		'delete_published_posts' 	=> true,
		'delete_private_posts' 		=> true,
		'edit_private_posts' 		=> true,
		'read_private_posts' 		=> true,
		'delete_private_pages' 		=> true,
		'edit_private_pages' 		=> true,
		'read_private_pages' 		=> true,
		'delete_users' 				=> true,
		'create_users' 				=> true,
		'unfiltered_upload' 		=> true,
		'edit_dashboard' 			=> false,
		'update_plugins' 			=> false,
		'delete_plugins' 			=> false,
		'install_plugins' 			=> false,
		'update_themes' 			=> false,
		'install_themes' 			=> false,
		'update_core' 				=> false,
		'list_users' 				=> true,
		'remove_users' 				=> true,
		'promote_users' 			=> true,
		'edit_theme_options' 		=> true,
		'delete_themes' 			=> false,
		'export' 					=> false,
		'wpseo_manage_options' 		=> false,
	);

	add_role($role, $display_name, $capabilities);        
}
add_action('init', 'change_roles');

// Customize user list columns
function wph_admin_user_columns($columns) {
  unset($columns['posts']);
 
  return $columns;
}
 
add_filter('manage_users_columns', 'wph_admin_user_columns', 10, 3);


// Hide administrators from user list
function hide_all_administrators( $u_query ) {
	
	// let's do the trick only for non-administrators
	$current_user = wp_get_current_user();

	if ( isset($current_user->roles[0]) && $current_user->roles[0] !== 'administrator' ) {
		global $wpdb;
		$u_query->query_where = str_replace(
			'WHERE 1=1', 
			"WHERE 1=1 AND {$wpdb->users}.ID IN (
				SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
					WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}capabilities'
					AND {$wpdb->usermeta}.meta_value NOT LIKE '%administrator%')", 
			$u_query->query_where
		);
	}
}
add_action('pre_user_query','hide_all_administrators');

// Change capabilities for certain pages
function change_page_capabilities( $required_caps, $cap, $user_id, $args ) {
    if ( in_array( $cap, ['edit_post', 'publish_post', 'delete_post'] ) ) {
        $page_id = $args[0]; // post_id
        $page_restricted = array();

        $lang = getLang();

        $terms_page_id 		= get_field('terms-page', $lang);
        $policy_page_id 	= get_option( 'wp_page_for_privacy_policy' );
        $page_for_posts_id 	= get_option( 'page_for_posts' );
        array_push($page_restricted, $terms_page_id, $policy_page_id, $page_for_posts_id);

        // prevent edit restricted pages
        if ( in_array( $page_id, $page_restricted ) ) {
            $user = new WP_User( $user_id );

            if (! in_array( 'administrator', $user->roles ) ) {
                $required_caps = ['do_not_allow'];
            }
        }
    }

    return $required_caps;
}
add_filter( 'map_meta_cap', 'change_page_capabilities', 10, 4 );