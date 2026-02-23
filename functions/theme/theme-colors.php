<?php

    function getThemeColors() {
        $themeColors         = get_field('colors', 'theme');
        $colors              = array();
        // Change array format 
        if(!empty($themeColors)):
            foreach ($themeColors as $color):
                $colorName = strtolower(str_replace(' ', '-', $color['name']));
                $colorHex = $color['color'];
                $colors[$colorName] = $colorHex;
            endforeach;
        endif;
        return $colors;
    }

    function acf_input_admin_footer() { ?>
        <script type="text/javascript"> 
          <?php
            $jsArray = "var colors = [";
              $colors = getThemeColors();
              foreach($colors as $color):
              $jsArray .= '"'.$color.'",';
            endforeach;
            $jsArray .="]";
            echo $jsArray;
          ?>
        </script>
        <script type="text/javascript">
            (function($) {

            acf.add_filter('color_picker_args', function( args, $field ){

            // add the hexadecimal codes here for the colors you want to appear as swatches
            args.palettes = colors;

            // return colors
            return args;

            });

            })(jQuery);
        </script>

    <?php }

    add_action('acf/input/admin_footer', 'acf_input_admin_footer');

    // Add colors to WYSIWYG editor
    function my_mce4_options($init) {

        $themeColors = get_field('colors', 'theme');
        $iColor = 0;
        $wysiwyg_colors = '';

        if(is_array($themeColors)):
          foreach($themeColors as $color):
              if($iColor != 0): $wysiwyg_colors .= ', '; endif;
              $wysiwyg_colors .= '"'.strtoupper(str_replace('#', '', $color['color'])).'", "'.ucfirst($color['name']).'"';
              $iColor++;
          endforeach;
        endif;

        // build colour grid default+custom colors
        $init['textcolor_map'] = '['.$wysiwyg_colors.']';

        // change the number of rows in the grid if the number of colors changes
        // 8 swatches per row
        $init['textcolor_rows'] = 2;

        return $init;
    }
    add_filter('tiny_mce_before_init', 'my_mce4_options');
    

    function add_color_variables() {
        $colors = getThemeColors();

        ?>
            <style>
                :root {
                    <?php
                    /* Set colors */
                    foreach($colors as $name => $hex):
                        $rgb = hexToRgb($hex);
                        $hsl = rgbToHsl($rgb);

                        $rgbDarker = hslToRgb(darkenHsl($hsl, 1.1));
                        $hexDarker = rgbToHex($rgbDarker);

                        $rgbDark = hslToRgb(darkenHsl($hsl, 1.8));
                        $hexDark = rgbToHex($rgbDark);

                        $rgbLight = hslToRgb(lightenHsl($hsl, 1.1));
                        $hexLight = rgbToHex($rgbLight);

                        $rgbBright = hslToRgb(saturateHsl($hsl, 1.8));
                        $hexBright = rgbToHex($rgbBright);

                    ?>
                        --color-<?php echo $name; ?>: <?php echo $hex; ?>;
                        --color-<?php echo $name; ?>-rgb: <?php echo $rgb['r'].','.$rgb['g'].','.$rgb['b']; ?>;
                        --color-<?php echo $name; ?>-darker: <?php echo $hexDarker; ?>;
                        --color-<?php echo $name; ?>-darker-rgb: <?php echo $rgbDarker['r'].','.$rgbDarker['g'].','.$rgbDarker['b']; ?>;
                        --color-<?php echo $name; ?>-dark: <?php echo $hexDark; ?>;
                        --color-<?php echo $name; ?>-dark-rgb: <?php echo $rgbDark['r'].','.$rgbDark['g'].','.$rgbDark['b']; ?>;
                        --color-<?php echo $name; ?>-light: <?php echo $hexLight; ?>;
                        --color-<?php echo $name; ?>-light-rgb: <?php echo $rgbLight['r'].','.$rgbLight['g'].','.$rgbLight['b']; ?>;
                        --color-<?php echo $name; ?>-bright: <?php echo $hexBright; ?>;
                        --color-<?php echo $name; ?>-bright-rgb: <?php echo $rgbBright['r'].','.$rgbBright['g'].','.$rgbBright['b']; ?>;

                    <?php endforeach; ?>
                }

                <?php
                $themeStyles = get_field('styles', 'theme');
                $colors      = getThemeColors();
                $colorCta    = $themeStyles['color-cta'];

                /* Make rules */
                foreach($colors as $name => $hex): 

                    // Get text light and dark color                    
                    $colorTextLight = $themeStyles['color-text-light'];
                    $colorTextLightRgb = hexToRgb($colors[$colorTextLight]);

                    $colorTextDark  = $themeStyles['color-text-dark'];
                    $colorTextDarkRgb  = hexToRgb($colors[$colorTextDark]);

                    $colorRgb = hexToRgb($hex);

                    // Change btn text color based on contrast
                    $distanceLight = colorDistance($colorRgb, $colorTextLightRgb);
                    $distanceDark = colorDistance($colorRgb, $colorTextDarkRgb);

                    if($distanceLight >= $distanceDark):
                        $btnTextColor = '--color-text-light';
                    else:
                        $btnTextColor = '--color-text-dark';
                    endif;
                        

                ?>
                    [data-color="<?php echo $name; ?>"] {
                        --color-background: var(--color-<?php echo $name; ?>);
                        --color-background-rgb: var(--color-<?php echo $name; ?>-rgb);
                        --text-color-background-darker: var(--color-<?php echo $name; ?>-dark);
                    }
                    [data-mobile-color="<?php echo $name; ?>"] {
                        --color-background: var(--color-<?php echo $name; ?>);
                        --color-background-rgb: var(--color-<?php echo $name; ?>-rgb);
                    }
                    [data-bg-inner="<?php echo $name; ?>"] {
                        --color-background-inner: var(--color-<?php echo $name; ?>);
                        --color-background-inner-rgb: var(--color-<?php echo $name; ?>);
                    }
                    [data-gradient-color="<?php echo $name; ?>"] {
                        --color-gradient: var(--color-<?php echo $name; ?>);
                        --color-gradient-rgb: var(--color-<?php echo $name; ?>-rgb);
                    }
                    [data-btn-color="<?php echo $name; ?>"] {
                        --btn-background-color: var(--color-<?php echo $name; ?>);
                        --btn-background-color-hover: var(--color-<?php echo $name; ?>-darker);
                        --btn-text-color: var(<?php echo $btnTextColor; ?>);
                    }
                <?php endforeach; ?>

                <?php
                    // zit het te dicht tegen zwart aan? Maak em dan iets lichter
                ?>
                .btn[data-btn-color="dark"],
                [data-btn-color="dark"],
                .btn-dark {
                    --btn-background-color: var(--color-<?php echo $colorTextDark; ?>);
                    --btn-background-color-hover: var(--color-<?php echo $colorTextDark; ?>-darker);
                    --btn-text-color: var(--color-text-light);
                }
                
                .btn[data-btn-color="light"],
                [data-btn-color="light"],
                .btn-light {
                    --btn-background-color: var(--color-<?php echo $colorTextLight; ?>);
                    --btn-background-color-hover: var(--color-<?php echo $colorTextLight; ?>-darker);
                    --btn-text-color: var(--color-text-dark);
                }

                .btn[data-btn-color="color"],
                [data-btn-color="color"],
                .btn-color {
                    --btn-background-color: var(--color-<?php echo $colorCta; ?>);
                    --btn-background-color-hover: var(--color-<?php echo $colorCta; ?>-darker);
                    --btn-text-color: var(--color-text-light);
                }
                
            </style>
        <?php
    }
    add_action('wp_head', 'add_color_variables', 5);

/* Background colors ------------------------------------------------------------------------------------------ */
function acf_load_text_color_field_choices( $field ) {
    
    // reset choices
    $field['choices'] = array();

    // load colors
    $colors = getThemeColors();
    $themeStyles  = get_field('styles', 'theme');
    // echo '<pre>'; print_r($colors); echo '</pre>';
    // die();

    $colorDark  = $themeStyles['color-text-dark'];
    $colorLight = $themeStyles['color-text-light'];

    $extraClass = (isColorTooCloseToWhite($colors[$colorDark])) ? ' border' : '';
    $field['choices'][ 'dark' ] = '<span class="color-indicator'.$extraClass.'" style="color:'.$colors[$colorDark].'; background:'.$colors[$colorDark].';"></span>';
    
    $extraClass = (isColorTooCloseToWhite($colors[$colorLight])) ? ' border' : '';
    $field['choices'][ 'light' ] = '<span class="color-indicator'.$extraClass.'" style="color:'.$colors[$colorLight].'; background:'.$colors[$colorLight].';"></span>';


    $field['default_value'] = 'dark';

    // return the field
    return $field;
    
}
add_filter('acf/prepare_field/name=text-color',        'acf_load_text_color_field_choices');
add_filter('acf/prepare_field/name=slider-color',      'acf_load_text_color_field_choices');
add_filter('acf/prepare_field/name=guide-text-color',  'acf_load_text_color_field_choices');
add_filter('acf/prepare_field/name=mobile-color-text', 'acf_load_text_color_field_choices');
add_filter('acf/prepare_field/name=color-text',        'acf_load_text_color_field_choices');
add_filter('acf/prepare_field/name=navigation-color',  'acf_load_text_color_field_choices');


/* Guide colors ------------------------------------------------------------------------------------------ */
function acf_load_guide_color_field_choices( $field ) {
    
    // reset choices
    $field['choices'] = array();

    // load colors
    $colors = array(
        'yellow'     => '#ffd200',
        'gold'       => '#db9b3c',
        'red'        => '#b4483b',
        'salmon'     => '#fb9787',
        'pink'       => '#dea8aa',
        'mauve'      => '#9c7992',
        'purple'     => '#a283be',
        'light-blue' => '#c3d5d6',
        'blue'       => '#5fa6bb',
        'turquoise'  => '#248188',
        'green'      => '#a3a852',
        'white'      => '#fffcf9'
    );
    // global $colors;

    // echo '<pre>'; print_r($colors); echo '</pre>';
    // die();

    foreach($colors as $name => $hex):
        $field['choices'][ $name ] = '<span class="color-indicator" style="background:'.$hex.';"></span>';
    endforeach;

    // echo '<pre>'; print_r($field); echo '</pre>';

    // return the field
    return $field;
    
}
add_filter('acf/prepare_field/name=guide-color', 'acf_load_guide_color_field_choices');


/* Background colors ------------------------------------------------------------------------------------------ */
function acf_load_bg_color_field_choices( $field ) {
    
    // reset choices
    $field['choices'] = array();

    // load colors
    $colors = getThemeColors();

    foreach($colors as $name => $hex):

        $extraClass = (isColorTooCloseToWhite($hex)) ? ' border' : '';
        $field['choices'][ $name ] = '<span class="color-indicator'.$extraClass.'" style="color:'.$hex.'; background:'.$hex.';"></span>';
    
    endforeach;

    $themeStyles  = get_field('styles', 'theme');
    if($themeStyles):
        $default = $themeStyles['body-background-color'];

        if($default):
            $field['default_value'] = $default;
        endif;
    endif;

    // echo '<pre>'; print_r($field); echo '</pre>';

    // return the field
    return $field;
    
}
function acf_load_bg_color_field_choices_with_transparent( $field ) {
    
    // reset choices
    $field['choices'] = array();

    // load colors
    $colors = getThemeColors();

    $field['choices']['transparent'] = '<span class="color-indicator color-transparent"></span>';
    $field['default_value'] = 'transparent';

    foreach($colors as $name => $hex):
        $extraClass = (isColorTooCloseToWhite($hex)) ? ' border' : '';
        $field['choices'][ $name ] = '<span class="color-indicator'.$extraClass.'" style="color:'.$hex.'; background:'.$hex.';"></span>';
    endforeach;

    return $field;
    
}
add_filter('acf/prepare_field/name=background-color',           'acf_load_bg_color_field_choices_with_transparent');
add_filter('acf/prepare_field/name=slide-background-color',     'acf_load_bg_color_field_choices');
add_filter('acf/prepare_field/name=overlay-color',              'acf_load_bg_color_field_choices');
add_filter('acf/prepare_field/name=accent-color',               'acf_load_bg_color_field_choices');
add_filter('acf/prepare_field/name=color',                      'acf_load_bg_color_field_choices');
add_filter('acf/prepare_field/name=container-color',            'acf_load_bg_color_field_choices');
add_filter('acf/prepare_field/name=background-color-banner',    'acf_load_bg_color_field_choices');


add_filter('acf/prepare_field/name=color-text-dark',            'acf_load_bg_color_field_choices');
add_filter('acf/prepare_field/name=color-text-dark-secondary',  'acf_load_bg_color_field_choices');
add_filter('acf/prepare_field/name=color-text-light',           'acf_load_bg_color_field_choices');
add_filter('acf/prepare_field/name=color-text-light-secondary', 'acf_load_bg_color_field_choices');
add_filter('acf/prepare_field/name=color-cta',                  'acf_load_bg_color_field_choices');
add_filter('acf/prepare_field/name=color-cta-secondary',        'acf_load_bg_color_field_choices');

add_filter('acf/prepare_field/name=body-background-color',      'acf_load_bg_color_field_choices');
add_filter('acf/prepare_field/name=footer-background-color',    'acf_load_bg_color_field_choices');

/* Set std text color ------------------------------------------------------------------------------------- */
function acf_set_text_color( $field ) {

    // echo '<pre>'; print_r($field); echo '</pre>';
    // die();

    $themeStyles  = get_field('styles', 'theme');
    if($themeStyles):
        $default = $themeStyles['body-text-color'];

        // echo 'dit is '.$default; die();

        if($default):
            $field['default_value'] = $default;
            if(!$field['value']):
                $field['value'] = $default;
            endif;
        endif;
    endif;

    // echo '<pre>'; print_r($field); echo '</pre>';

    // return the field
    return $field;
    
}
add_filter('acf/prepare_field/name=color-text', 'acf_set_text_color');



/* Button colors ------------------------------------------------------------------------------------------ */
function acf_load_btn_color_field_choices( $field ) {
    
    $themeStyles        = get_field('styles', 'theme');
    $colorDark          = $themeStyles['color-text-dark'];
    $colorLight         = $themeStyles['color-text-light'];
    $colorCta           = $themeStyles['color-cta'];
    $colorCtaSecondary  = $themeStyles['color-cta-secondary'];

    $btnColors = array($colorLight, $colorDark, $colorCta, $colorCtaSecondary);
    $field['choices'] = array();

    $field['choices']['transparent'] = '<span class="color-indicator color-transparent"></span>';

    // load colors
    $colors = getThemeColors();
    
    foreach($colors as $name => $hex):
        if (in_array($name, $btnColors)):
            if($name == $colorDark): $name = 'dark'; endif;
            if($name == $colorLight): $name = 'light'; endif;

            $extraClass = (isColorTooCloseToWhite($hex)) ? ' border' : '';

            $field['choices'][ $name ] = '<span class="color-indicator'.$extraClass.'" style="color:'.$hex.'; background:'.$hex.';"></span>';
        endif;
    endforeach;

    

    $themeStyles  = get_field('styles', 'theme');
    $default = $themeStyles['color-cta'];
    if($default):
        $field['default_value'] = $default;
    endif;

    // echo '<pre>'; print_r($field); echo '</pre>';

    // return the field
    return $field;
    
}

add_filter('acf/prepare_field/name=color-btn', 'acf_load_btn_color_field_choices');
add_filter('acf/prepare_field/name=color-btn2', 'acf_load_btn_color_field_choices');