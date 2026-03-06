<?php

function privacy_shortcode($atts) {

  $atts = shortcode_atts(array(
    'text' => 'privacybeleid',
  ), $atts ); 

  $href = '#';
  $policy_page_id = get_option( 'wp_page_for_privacy_policy' );
  if ( $policy_page_id != ''):
    $href = get_the_permalink($policy_page_id);
  endif;

  $output ='<a href="'.$href.'" target="_blank">'.$atts['text'].'</a>';

  return $output;
}

add_shortcode( 'privacy', 'privacy_shortcode' );

function menu_404_shortcode() {

  $lang = getLang();

  $menu = get_field('nav-404', $lang);



  if($menu):

    ob_start();
    
    echo '<ul class="list-nav-overlay list-404">';
    
    $args = array(
      'menu' => $menu,
      'container'=> false,
      'items_wrap' => '%3$s',
      'depth' => 1,
    );
    wp_nav_menu($args);
    
    echo '</ul>';

    return ob_get_clean();

  else:

    return;

  endif;
}

add_shortcode( 'menu_404', 'menu_404_shortcode' );

function logo_shortcode($atts) {

  $atts = shortcode_atts(array(
    'type' => null,
  ), $atts ); 

  $type = $atts['type'];

  $logoPrimary   = get_field('site-logo', 'theme');
  $logoSecondary = get_field('logo-secondary', 'theme');
  $tagline       = get_field('logo-tagline', 'theme');
  $icon          = get_field('site-icon', 'theme');

  if(!$logoPrimary && !$logoSecondary && !$icon):
    return;
  endif;

  ob_start();
  
  if($type == 'secondary' && $logoSecondary):
    $logo  = $logoSecondary;
    $class = 'logo-secondary';

  elseif($type == 'tagline' && $tagline):
    $logo  = $tagline;
    $class = 'logo-word';

  elseif($type == 'icon' && $icon):
    $logo  = $icon;
    $class = 'logo-icon';

  elseif($logoPrimary):
    $logo  = $logoPrimary;
    $class = 'logo-primary';

  endif;

  echo '<figure class="'.$class.'">'.file_get_contents($logo).'</figure>';

  return ob_get_clean();
}

add_shortcode( 'logo', 'logo_shortcode' );

function audio_shortcode($atts) {

  $atts = shortcode_atts(array(
    'url' => null,
    'title' => null,
  ), $atts ); 

  $audio['url'] = $atts['url'];
  $audio['title'] = $atts['title'];
 
  if($audio['url'] == ''): return; endif;
  
  ob_start();

  include( locate_template( 'partials/elements/audio.php', false, false ) );
  
  return ob_get_clean();
}

add_shortcode( 'audio-player', 'audio_shortcode' );


function socials_shortcode() {

  $lang    = getLang();
  $socials = get_field('socials', $lang);
  if(!empty($socials)):
      $socials = array_filter($socials);

      ob_start();

      if(!empty($socials)):

      echo '<ul class="list-socials">';

      foreach($socials as $name => $value):
        $icon = get_template_directory().'/assets/images/social/icon-'.$name.'.svg';
        if($icon && $value):
          echo '<li class="social-'.$name.'"><a href="'.$value.'" target="_blank">'.file_get_contents($icon).'</a></li>';
        endif;
      endforeach;

      echo '</ul>';

    endif;

    return ob_get_clean();
  else:
    return;
  endif;
}

add_shortcode( 'socials', 'socials_shortcode' );

function sitename_shortcode() {

  $site_title = get_bloginfo( 'name' );
  
  ob_start();

  echo $site_title;

  return ob_get_clean();
}

add_shortcode( 'sitename', 'sitename_shortcode' );

function signature_shortcode() {

  $image_src = get_template_directory().'/assets/images/signature.png';
  
  ob_start();

  echo '<img src="'.$image_src.'" class="img-signature" />';

  return ob_get_clean();
}

add_shortcode( 'signature', 'signature_shortcode' );

function contact_info_shortcode() {

  $lang        = getLang();
  $contactInfo = get_field('contact-info', $lang);
  
  ob_start();

  echo '<ul class="list-contact-info">';

  foreach ($contactInfo as $label => $info):

    if($label == 'address'):
      $label = ($lang == 'nl') ? 'Adres' : 'Adress';
    elseif($label == 'e-mail'):
      $label = ($lang == 'nl') ? 'E-mail' : 'Email';
    elseif($label == 'phone'):
      $label = ($lang == 'nl' ) ? 'Telefoon' : 'Phone';
    elseif($label == 'times'):
      $label = ($lang == 'nl') ? 'Openings<br />tijden' : 'Opening</br />hours';
    endif;

    echo '<li><h6>'.$label.'</h6>'.$info.'</li>';

  endforeach;

  echo '</ul>';

  return ob_get_clean();
}

add_shortcode( 'contact-info', 'contact_info_shortcode' );

function rating_shortcode() {

  $star = get_bloginfo('template_url').'/assets/images/icon-star.svg';
  
  ob_start();

  ?>
  <ul class="list-rating">
    <li><?php echo file_get_contents($star); ?></li>
    <li><?php echo file_get_contents($star); ?></li>
    <li><?php echo file_get_contents($star); ?></li>
    <li><?php echo file_get_contents($star); ?></li>
    <li><?php echo file_get_contents($star); ?></li>
  </ul>
  <?php

  return ob_get_clean();
}

add_shortcode( 'rating', 'rating_shortcode' );

function countdown_shortcode($atts) {
  $atts = shortcode_atts([
    'day' => '',
    'time' => '00:00:00',
    'align' => 'left',
    'timer' => ''
  ], $atts);

  $layoutName = 'countdown';
  $jsFile = locate_template('/assets/javascript/shortcodes/'.$layoutName.'.js', false, false);

  if (file_exists($jsFile)) {
    wp_enqueue_script($layoutName, get_template_directory_uri().'/assets/javascript/shortcodes/'.$layoutName.'.js', [], THEME_VERSION, true);
  }

  if (!empty($atts['timer'])) {
    return '<div class="countdown js-countdown"
      data-timer="'.esc_attr($atts['timer']).'"
      data-align="'.esc_attr($atts['align']).'"
      data-labels="Dagen, Uren, Minuten, Seconden"></div>';
  }

  $tz  = new DateTimeZone('Europe/Amsterdam');
  $dt  = new DateTime(trim($atts['day'].' '.$atts['time']), $tz);
  $iso = $dt->format('c'); // bv 2025-12-16T20:00:00+01:00 of +02:00

  return '<div class="countdown js-countdown"
    data-align="'.esc_attr($atts['align']).'"
    data-countdown="'.esc_attr($iso).'"
    data-labels="Dagen, Uren, Minuten, Seconden"></div>';
}
add_shortcode('countdown', 'countdown_shortcode');

function badge_shortcode($atts) {

  $atts = shortcode_atts(array(
    'text'  => '',
    'color' => 'dark',
    'bg'    => 'coconut',
  ), $atts);

  $text  = esc_html($atts['text']);
  $color = sanitize_title($atts['color']);
  $bg    = sanitize_title($atts['bg']);

  $color_var = $color === 'light' ? 'var(--color-text-light)' : 'var(--color-text-dark)';

  $output = '<p class="badge" style="--badge-color: ' . $color_var . '; --badge-bg: var(--color-' . $bg . ')">' . $text . '</p>';

  return $output;
}

add_shortcode('badge', 'badge_shortcode');

function stats_shortcode($atts, $content = null) {

  $atts = shortcode_atts(array(
    'columns' => '2',
    'color'   => '',
    'bg'      => '',
  ), $atts);

  $columns = intval($atts['columns']);

  $styles = '';
  if($atts['color']) {
    $color = sanitize_title($atts['color']);
    $color_var = ($color === 'light') ? 'var(--color-text-light)' : (($color === 'dark') ? 'var(--color-text-dark)' : 'var(--color-' . $color . ')');
    $styles .= '--stat-color: ' . $color_var . ';';
  }
  if($atts['bg']) {
    $bg = sanitize_title($atts['bg']);
    $styles .= '--stat-bg: var(--color-' . $bg . ');';
  }

  $style_attr = $styles ? ' style="' . $styles . '"' : '';

  $inner = do_shortcode(shortcode_unautop($content));
  $inner = preg_replace('/<br\s*\/?>\s*/', '', $inner);

  $output = '<div class="stats-grid" data-columns="' . $columns . '"' . $style_attr . '>';
  $output .= $inner;
  $output .= '</div>';

  return $output;
}

add_shortcode('stats', 'stats_shortcode');

function stat_shortcode($atts) {

  $atts = shortcode_atts(array(
    'value' => '',
    'label' => '',
  ), $atts);

  $value = wp_kses($atts['value'], array('strong' => array(), 'b' => array()));
  $label = esc_html($atts['label']);

  $output  = '<div class="stat-card">';
  $output .= '<div class="stat-value">' . $value . '</div>';
  $output .= '<div class="stat-label">' . $label . '</div>';
  $output .= '</div>';

  return $output;
}

add_shortcode('stat', 'stat_shortcode');
