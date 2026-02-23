<?php
function register_menus() {
    register_nav_menus(
        array(
            'footer-1'      => __( 'Footer kolom 1 A' ),
            'footer-1-b'    => __( 'Footer kolom 1 B' ),
            'footer-2'      => __( 'Footer kolom 2 A' ),
            'footer-2-b'    => __( 'Footer kolom 2 B' ),
            'footer-3'      => __( 'Footer kolom 3' ),
        )
    );
}
 
add_action('init', 'register_menus');

// Main menu (add dropdown to main menu is exists)
class main_menu_Walker extends Walker_Nav_Menu {

    function start_lvl( &$output, $depth = 0, $args = null ) {
        $lang        = getLang();
        $navigation  = get_field('navigation', $lang);
        $navOptions  = $navigation['nav-options'];
        $navDropdown = isset($navOptions['show-nav-dropdown']) ? $navOptions['show-nav-dropdown'] : null;

        if(!$navDropdown):
            $output .= '<ul class="sub-menu">';
        else:
            $output .= '';
        endif;
    }
    function end_lvl( &$output, $depth = 0, $args = null ) {
        $lang        = getLang();
        $navigation  = get_field('navigation', $lang);
        $navOptions  = $navigation['nav-options'];
        $navDropdown = isset($navOptions['show-nav-dropdown']) ? $navOptions['show-nav-dropdown'] : null;

        if(!$navDropdown):
            $output .= '</ul>';
        else:
            $output .= '';
        endif;
    }

    function start_el(&$output, $item, $depth=0, $args=[], $id=0) {

        $lang        = getLang();
        $navigation  = get_field('navigation', $lang);
        $navOptions  = $navigation['nav-options'];
        $navDropdown = isset($navOptions['show-nav-dropdown']) ? $navOptions['show-nav-dropdown'] : null;

        if(!$navDropdown):

            $output .= '<li class="' .  implode(" ", $item->classes) .'">';
            $output .= '<a href="' . $item->url . '">'.$item->title;

            // echo '<pre>'; print_r($item->classes); echo '</pre>';

            if (in_array('menu-item-has-children', $item->classes)):
                $icon = get_template_directory().'/assets/images/beam/icons/chevron-down.svg';
                $output .= '<span class="chev-down">'.file_get_contents($icon).'</span>';
            endif;

            $output .= '</a>';


        // only show first level items
        elseif (!$item->menu_item_parent):

            $page_id       = $item->object_id;
            $dropdown      = get_field('nav-dropdown', $page_id);
            $dropdown      = isset($dropdown['content-nav']) ? $dropdown['content-nav'] : null;

            // echo '<pre>'; print_r($dropdown);

            $removeKey = array_search('menu-item-has-children', $item->classes);
            if($removeKey): unset($item->classes[$removeKey]); endif;

            if(!empty($dropdown) && !in_array('read-more', $item->classes)):
                array_push($item->classes, 'menu-item-has-dropdown');
                $output .= '<li class="' .  implode(" ", $item->classes) .'" data-item="'.$page_id.'">';
            else:
                $output .= '<li class="' .  implode(" ", $item->classes) .'">';
            endif;
            
            $output .= '<a href="' . $item->url . '">'.$item->title;

            if (in_array('menu-item-has-dropdown', $item->classes)):
                $icon = get_template_directory_uri().'/assets/images/beam/icons/chevron-right.svg';
                $output .= '<span class="chev-right">'.file_get_contents($icon).'</span>';
            endif;

            $output .= '</a>';

        endif;
    }

}

// Dropdown builder
class dropdown_menu_Walker extends Walker_Nav_Menu {

    function start_lvl( &$output, $depth = 0, $args = null ) {

    }
    function end_lvl( &$output, $depth = 0, $args = null ) {

    }

    function start_el(&$output, $item, $depth=0, $args=[], $id=0) {
        $page_id       = $item->object_id;
        $lang          = getLang();
        $navigation    = get_field('navigation', $lang);
        $navOptions    = $navigation['nav-options'];
        $show_dropdown = isset($navOptions['show-nav-dropdown']) ? $navOptions['show-nav-dropdown'] : null;
        $dropdown      = get_field('nav-dropdown', $page_id);
        $dropdown      = isset($dropdown['content-nav']) ? $dropdown['content-nav'] : null;
        if($dropdown):
            ob_start();
            include( locate_template( 'partials/navigation/navigation-dropdown-item.php', false, false ) );
            $output .= ob_get_clean();
        endif;
    }

}

// Mobile menu
class mobile_Menu_Walker extends Walker_Nav_Menu {

    function start_el(&$output, $item, $depth=0, $args=[], $id=0) {

        $output .= '<li class="' .  implode(" ", $item->classes) .'">';
        $output .= '<a href="' . $item->url . '">'.$item->title;

        if (in_array('menu-item-has-children', $item->classes)):
            $icon = get_template_directory_uri().'/assets/images/beam/icons/chevron-down.svg';
            $output .= '<span class="chev-down">'.file_get_contents($icon).'</span>';
        endif;

        $output .= '</a>';

    }

}

/**
 * ACF Populate Select Field with Menus
 * @link https://www.advancedcustomfields.com/resources/acf-load_field/
 * @link https://www.advancedcustomfields.com/resources/dynamically-populate-a-select-fields-choices/
 *
 * Dynamically populates any ACF field with wd_nav_menus with list of navigation menus
 *
*/
add_filter( 'acf/prepare_field/name=nav-menu',   'load_nav_menus' );
add_filter( 'acf/prepare_field/name=nav-menu-1', 'load_nav_menus' );
add_filter( 'acf/prepare_field/name=nav-menu-2', 'load_nav_menus' );
add_filter( 'acf/prepare_field/name=nav-legal',  'load_nav_menus' );
add_filter( 'acf/prepare_field/name=nav-404',    'load_nav_menus' );
function load_nav_menus( $field ) {

     $menus = wp_get_nav_menus();

     if ( ! empty( $menus ) ) {

          foreach ( $menus as $menu ) {
               $field['choices'][ $menu->term_id ] = $menu->name;
          }

     }

     return $field;

}



add_filter( 'acf/prepare_field/name=dropdown-menu', 'show_dropdown_menu' );
add_filter( 'acf/prepare_field/name=nav-dropdown', 'show_dropdown_menu' );
function show_dropdown_menu( $field ) {

    $lang        = getLang();
    $navigation  = get_field('navigation', $lang);
    $navOptions  = $navigation['nav-options'];
    $navDropdown = isset($navOptions['show-nav-dropdown']) ? $navOptions['show-nav-dropdown'] : null;
    
    if($navDropdown):
        return $field;
    else:
        return;
    endif;

}
add_filter( 'acf/prepare_field/name=dropdown-message', 'show_dropdown_message' );
function show_dropdown_message( $field ) {

    $lang        = getLang();
    $navigation  = get_field('navigation', $lang);
    $navOptions  = $navigation['nav-options'];
    $navDropdown = isset($navOptions['show-nav-dropdown']) ? $navOptions['show-nav-dropdown'] : null;

    if(!$navDropdown):
        return $field;
    else:
        return;
    endif;

}