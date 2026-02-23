<?php

add_theme_support('title-tag');
add_theme_support('menus');

add_theme_support('yoast-seo-breadcrumbs');

remove_theme_support('admin-bar');

// 'admin-bar'
// 'align-wide'
// 'automatic-feed-links'
// 'core-block-patterns'
// 'custom-background'
// 'custom-header'
// 'custom-line-height'
// 'custom-logo'
// 'customize-selective-refresh-widgets'
// 'custom-spacing'
// 'custom-units'
// 'dark-editor-style'
// 'disable-custom-colors'
// 'disable-custom-font-sizes'
// //'editor-color-palette'
// 'editor-gradient-presets'
// 'editor-font-sizes'
// 'editor-styles'
// 'featured-content'
// 'html5'
// 'menus'
// 'post-formats'
// 'post-thumbnails'
// 'responsive-embeds'
// 'starter-content'
// 'title-tag'
// 'wp-block-styles'
// 'widgets'
// 'widgets-block-editor'


add_action('do_meta_boxes', 'disable_page_widgets');

function disable_page_widgets() {
    remove_meta_box( 'postimagediv','page','side' );
}

function configure_admin_menu() {
  // Configure the admin menu here.
    global $menu;
    if($menu[4][4] == 'wp-menu-separator'): unset($menu[4]); endif;
    if($menu[99][4] == 'wp-menu-separator'): unset($menu[99]); endif;

    // echo '<pre>'; print_r($menu); echo '</pre>';

    
    // Remove dashboard
    remove_submenu_page( 'index.php', 'update-core.php' );
    remove_submenu_page( 'index.php','index.php' );

    // Remove plugin pages
    remove_submenu_page( 'plugins.php','plugins.php' );
    remove_submenu_page( 'plugins.php','plugin-install.php' );
    remove_submenu_page( 'plugins.php','plugin-editor.php' );

    // Remove Tools
    // remove_menu_page('tools.php');

    // Remove Yoast
    remove_action('admin_bar_menu', 'wpseo_admin_bar_menu',95);
    remove_menu_page('wpseo_dashboard');
    remove_menu_page('wpseo_workouts');
    remove_menu_page('wpseo_redirects');

    remove_menu_page('copy-delete-posts');

    // Remove setting pages
    // remove_menu_page('options-general.php');

    // Remove ACF
    // remove_menu_page('edit.php?post_type=acf-field-group');

    // Remove appaerance and add "Menu" menu item
    remove_menu_page('themes.php');
    add_menu_page('Menu','Menu','edit_theme_options','nav-menus.php','','dashicons-leftright',2);

    // Add logout
    add_menu_page(__('Log out'),__('Log out'),'edit_theme_options', wp_logout_url(),'','dashicons-exit',999);

    // Remove footer text
    add_filter( 'admin_footer_text', '__return_empty_string', 11 );
    add_filter( 'update_footer',     '__return_empty_string', 11 );

    global $submenu;

    // echo '<pre>'; print_r($submenu); echo '</pre>';

    

}
add_action('admin_menu', 'configure_admin_menu');

function disable_dashboard_widgets() {
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');   // Right Now
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // Recent Comments
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');  // Incoming Links
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');   // Plugins
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');  // Quick Press
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');  // Recent Drafts
    remove_meta_box('dashboard_primary', 'dashboard', 'side');   // WordPress blog
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');   // Other WordPress News
    remove_meta_box('wpforms_reports_widget_pro', 'dashboard', 'normal');   // WP Forms
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');   // Site Health Status
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');   // Activity
    remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal');   // Yoast
    remove_meta_box('wpseo-wincher-dashboard-overview', 'dashboard', 'normal');
    remove_meta_box('catf_dashboard_widget', 'dashboard', 'normal'); // Catfolders
    remove_meta_box('rank_math_dashboard_widget', 'dashboard', 'normal'); // Rank Math

    remove_action('welcome_panel', 'wp_welcome_panel');
}
add_action('wp_dashboard_setup', 'disable_dashboard_widgets', 40);

function wpb_remove_screen_options() { 
    return false; 
}
// add_filter('screen_options_show_screen', 'wpb_remove_screen_options');

function contextual_help_list_remove(){
    $screen = get_current_screen();
    $screen->remove_help_tabs();
}
add_filter('admin_head','contextual_help_list_remove');

function example_admin_bar_remove_logo() {
    global $wp_admin_bar;

    $wp_admin_bar->remove_menu( 'wp-logo' );
    $wp_admin_bar->remove_menu( 'about' );
    $wp_admin_bar->remove_menu( 'site-name' );
    $wp_admin_bar->remove_menu( 'new-content' );
    $wp_admin_bar->remove_menu( 'wpseo-menu' );

    $wp_admin_bar->remove_menu( 'my-account' );
    // $wp_admin_bar->remove_menu( 'menu-toggle' );
    $wp_admin_bar->remove_menu( 'logout' );
    
}
// add_action( 'wp_before_admin_bar_render', 'example_admin_bar_remove_logo', 0 );

// Replace Footer Text Admin
// function customize_admin_footer() {
//     echo 'Website by <a href="https://www.thewiebesagency.com" target="_blank">The Wiebes Agency</a>';
// }
// add_filter('admin_footer_text', 'customize_admin_footer');


//Page Slug Body Class
function add_slug_body_class( $classes ) {
  global $post;
  
  if ( isset( $post ) ):
    $classes[] = $post->post_type . '-' . $post->post_name;
  endif;

  return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );


/**
 * @snippet       Disable WooCommerce Ajax Cart Fragments Everywhere
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.6.4
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
 
add_action( 'wp_enqueue_scripts', 'bbloomer_disable_woocommerce_cart_fragments', 11 ); 
 
function bbloomer_disable_woocommerce_cart_fragments() { 
   wp_dequeue_script( 'wc-cart-fragments' ); 
}

/** Disable All WooCommerce  Styles and Scripts Except Shop Pages*/
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_styles_scripts', 99 );

function dequeue_woocommerce_styles_scripts() {
    if ( function_exists( 'is_woocommerce' ) ) {
    if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
        # Styles
        wp_dequeue_style( 'woocommerce-general' );
        wp_dequeue_style( 'woocommerce-layout' );
        wp_dequeue_style( 'woocommerce-smallscreen' );
        wp_dequeue_style( 'woocommerce_frontend_styles' );
        wp_dequeue_style( 'woocommerce_fancybox_styles' );
        wp_dequeue_style( 'woocommerce_chosen_styles' );
        wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
        # Scripts
        wp_dequeue_script( 'wc_price_slider' );
        wp_dequeue_script( 'wc-single-product' );
        wp_dequeue_script( 'wc-add-to-cart' );
        wp_dequeue_script( 'wc-cart-fragments' );
        wp_dequeue_script( 'wc-checkout' );
        wp_dequeue_script( 'wc-add-to-cart-variation' );
        wp_dequeue_script( 'wc-single-product' );
        wp_dequeue_script( 'wc-cart' );
        wp_dequeue_script( 'wc-chosen' );
        wp_dequeue_script( 'woocommerce' );
        wp_dequeue_script( 'prettyPhoto' );
        wp_dequeue_script( 'prettyPhoto-init' );
        wp_dequeue_script( 'jquery-blockui' );
        wp_dequeue_script( 'jquery-placeholder' );
        wp_dequeue_script( 'fancybox' );
        wp_dequeue_script( 'jqueryui' );
    }
}
}

// remove updates from admin bar
function remove_toolbar_items($wp_adminbar) {
    $wp_adminbar->remove_node('updates');
    $wp_adminbar->remove_node('comments');
    $wp_adminbar->remove_node('fb-edit');
}
add_action('admin_bar_menu', 'remove_toolbar_items', 999);



function set_default_page() {
    
    $lang = getLang();

    $defaultPage = get_option( 'page_on_front' );

    if(!$defaultPage):

        // set construction mode on
        update_field('dev-mode', 1, 'theme');
        
        $defaultPageID = 2;
        $defaultPage = get_post($defaultPageID);

        $privacyPageID = 3;
        $privacyPage = get_post($privacyPageID);

        $termsPageId = get_field('terms', $lang);       

        if($defaultPage):

            // Set reading settings
            update_option( 'page_on_front', $defaultPageID );
            update_option( 'show_on_front', 'page');
            update_option( 'blogdescription', '');

            // Set General settings
            update_option( 'timezone_string', 'Europe/Amsterdam' );
            update_option( 'users_can_register', 0);
            update_option( 'start_of_week', 1);
            update_option( 'WPLANG', '');
            update_option( 'date_format', 'F j, Y');
            // update_option( 'time_format', 'g:i a');
            
            // Delete std blog post
            wp_delete_post(1, true);

            // Change name of std blog category
            wp_update_term( 1, 'category', array(
                'name' => 'Blog',
                'slug' => 'blog'
            ) );


            // Set permalinks
            global $wp_rewrite;
            $wp_rewrite->set_permalink_structure( '/%postname%/' );

            // Set Homepage name
            $site_title = get_bloginfo( 'name' );
            $post_update = array(
                'ID'            => $defaultPageID,
                'post_title'    => $site_title,
                'post_content'  => '',
                'post_name'     => 'home',
            );

            wp_update_post( $post_update );

        endif;

        if($privacyPage):

            if($lang == 'nl'):
                $privacyTitle = 'Privacybeleid';
            else:
                $privacyTitle = 'Privacy policy';
            endif;

            $post_update = array(
                'ID'            => $privacyPageID,
                'post_title'    => $privacyTitle,
                'post_content'  => '',
                'post_status'   => 'publish',
            );

            // set privacy page
            update_option( 'wp_page_for_privacy_policy', $privacyPageID);
            

            wp_update_post( $post_update );

        endif;

        if(!$termsPageId):

            if($lang == 'nl'):
                $termsTitle = 'Algemene voorwaarden';
            else:
                $termsTitle = 'Terms & Conditions';
            endif;

            $page_details = array(
                'post_title'    => $termsTitle,
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page'
            );
            wp_insert_post( $page_details );

            $termsPage = get_page_by_title($termsTitle);

            update_field('terms-page', $termsPage->ID, $lang);

        endif;

    endif;
    // var_dump( $defaultPage ); /* outputs false */
    // die();
    
}

add_action('admin_head', 'set_default_page' );




/**
 * Add a character counter to post excerpts in WordPress admin.
 * Action: admin_head-post.php, admin_head-post-new.php
 * Inspired by @link: https://premium.wpmudev.org/blog/character-counter-excerpt-box/
 */
function cmfy_excerpt_character_counter() {
    // If post type does not support excerpt, do nothing
    if ( ! post_type_supports( get_post_type(), 'excerpt' ) ) {
        return;
    }

    // Character limit
    $limit = 350;

    // If set to true, the textarea will prevent input beyond limit, else it'll give a notice if limit is exceeded
    $hard_limit = true;

    // Excerpt box character count markup
    $markup = sprintf(
        '<div class="hide-if-no-js" style="border: 1px solid #e5e5e5; border-top: none; background-color: #f7f7f7; padding: 2px 10px;">%s <span id="postexcerpt_char_count"></span> / %d</div>',
        _x( 'Character count', 'excerpt characters', 'cmfy' ),
        $limit
    );
    ?>
    <script>
        ( function( $ ) {
            $( document ).ready( function() {
                var limit = <?php echo $limit; ?>,
                    hard_limit = <?php echo $hard_limit ? 'true' : 'false'; ?>,
                    markup = '<?php echo $markup; ?>',
                    
                    // Reference to the excerpt textarea
                    $excerpt = $( '#excerpt' ),
                    // Reference to the character count element after adding it to the DOM
                    $excerpt_char_count = $( '#excerpt' ).after( markup ).next().find( '#postexcerpt_char_count' );

                // If using a hard limit, set the maxlength attribute on the excerpt textarea
                if ( hard_limit ) {
                    $excerpt.attr( 'maxlength', limit );
                }

                function update_count() {
                    // Current count of excerpt
                    var count = $excerpt.val().length;
                    // Update DOM to reflect count
                    $excerpt_char_count.text( count );

                    // If not using a hard limit and count goes over limit, apply error-message class
                    if ( ! hard_limit && count > limit && ! $excerpt_char_count.hasClass( 'error-message' ) ) {
                        $excerpt_char_count.addClass( 'error-message' );
                    } else if ( ! hard_limit && count <= limit && $excerpt_char_count.hasClass( 'error-message' ) ) {
                        $excerpt_char_count.removeClass( 'error-message' );
                    }
                }
                
                // Update count on keyup which should catch most methods in which data is entered
                $excerpt.on( 'keyup', update_count );
                // If pasting not using a keyboard, do it this way
                $excerpt.on( 'paste', function() {
                    setTimeout( update_count, 0 );
                } );

                // Go!
                update_count();
            } );
        } )( jQuery );
    </script>
<?php }
add_action( 'admin_head-post.php', 'cmfy_excerpt_character_counter' );
add_action( 'admin_head-post-new.php', 'cmfy_excerpt_character_counter' );