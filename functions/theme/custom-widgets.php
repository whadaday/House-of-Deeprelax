<?php
// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );


/**
 * Register our sidebars and widgetized areas.
 *
 */
function register_custom_sidebars() {

  register_sidebar( array(
    'name'          => 'Blog',
    'id'            => 'blog',
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ) );

  register_sidebar( array(
    'name'          => 'Kennisbank',
    'id'            => 'kennisbank',
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ) );

}
// add_action( 'widgets_init', 'register_custom_sidebars' );

function unregister_widgets() {
  unregister_widget('WP_Widget_Calendar');
  unregister_widget('WP_Widget_Pages');
  unregister_widget('PLL_Widget_Languages');
  unregister_widget('WP_Widget_Archives');
  unregister_widget('WP_Widget_Links');
  unregister_widget('WP_Widget_Media_Audio');
  unregister_widget('WP_Widget_Media_Image');
  unregister_widget('WP_Widget_Media_Video');
  unregister_widget('WP_Widget_Media_Gallery');
  unregister_widget('WP_Widget_Meta');
  unregister_widget('WP_Widget_Search');
  unregister_widget('WP_Widget_Text');
  unregister_widget('WP_Widget_Categories');
  unregister_widget('WP_Widget_Recent_Posts');
  unregister_widget('WP_Widget_Recent_Posts');
  unregister_widget('WP_Widget_Recent_Comments');
  unregister_widget('WP_Widget_RSS');
  unregister_widget('WP_Widget_Tag_Cloud');
  unregister_widget('WP_Nav_Menu_Widget');
  unregister_widget('WP_Widget_Custom_HTML');
  unregister_widget('MC4WP_Form_Widget');
  unregister_widget( 'Fusion_Widget_Ad_125_125' );
  unregister_widget( 'Fusion_Widget_Author' );
  unregister_widget( 'Fusion_Widget_Contact_Info' );
  unregister_widget( 'Fusion_Widget_Tabs' );
  unregister_widget( 'Fusion_Widget_Recent_Works' );
  unregister_widget( 'Fusion_Widget_Tweets' );
  unregister_widget( 'Fusion_Widget_Flickr' );
  unregister_widget( 'Fusion_Widget_Social_Links' );
  unregister_widget( 'Fusion_Widget_Facebook_Page' );
  unregister_widget( 'Fusion_Widget_Menu' );
  unregister_widget( 'Fusion_Widget_Vertical_Menu' );

  unregister_widget( 'WC_Widget_Layered_Nav_Filters' );
  unregister_widget( 'WP_Widget_Block' );
  unregister_widget( 'WC_Widget_Layered_Nav' );
  unregister_widget( 'WC_Widget_Price_Filter' );
  unregister_widget( 'WC_Widget_Rating_Filter' );
  unregister_widget( 'SbiWidget' );
  unregister_widget( 'WC_Widget_Top_Rated_Products' );
  unregister_widget( 'WC_Widget_Recently_Viewed' );
  unregister_widget( 'WC_Widget_Product_Categories' );
  unregister_widget( 'WC_Widget_Products' );
  unregister_widget( 'WC_Widget_Recent_Reviews' );
  unregister_widget( 'WPForms_Widget' );
  unregister_widget( 'WC_Widget_Cart' );
  unregister_widget( 'WC_Widget_Product_Search' );
  unregister_widget( 'Sassy_Social_Share_Floating_Widget' );
  unregister_widget( 'Sassy_Social_Share_Follow_Widget' );
  unregister_widget( 'Sassy_Social_Share_Standard_Widget' );
  

  
}

add_action( 'widgets_init', 'unregister_widgets' );





class Widget_Blog extends WP_Widget {

    public function __construct() {
      $args = array(
        'classname' => 'widget-blog',
        'description' => 'Toon artikelen',
      );
      parent::__construct(
        'widget_blog', // Base ID
        'Artikelen', // Name
        $args // Args
      ); 
    }

    public function widget( $args, $instance ) {
        $widget_id = 'widget_' . $args['widget_id'];
        include( locate_template( 'partials/widgets/widget-blog.php', false, false ) );
    }

    public function form( $instance ) {
      return $instance;
    }

    public function update( $new_instance, $old_instance ) {
      return $new_instance;
    }
}
class Widget_Kennisbank extends WP_Widget {

    public function __construct() {
      $args = array(
        'classname' => 'widget-kennisbank',
        'description' => 'Toon kennisbank artikelen',
      );
      parent::__construct(
        'widget_kennisbank', // Base ID
        'Kennisbank artikelen', // Name
        $args // Args
      ); 
    }

    public function widget( $args, $instance ) {
        $widget_id = 'widget_' . $args['widget_id'];
        include( locate_template( 'partials/widgets/widget-kennisbank.php', false, false ) );
    }

    public function form( $instance ) {
      return $instance;
    }

    public function update( $new_instance, $old_instance ) {
      return $new_instance;
    }
}

function register_widgets() {
  // register_widget( 'Widget_Blog' );
  // register_widget( 'Widget_Kennisbank' );
}

add_action( 'widgets_init', 'register_widgets' );
