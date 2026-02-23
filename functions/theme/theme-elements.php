<?php
function add_elements_variables() {
    if( class_exists('ACF') ) :
        $themeElements = get_field('elements', 'theme');
        if($themeElements):
            $themeElements['p'] = $themeElements['body'];

            // Remove empty keys, casues by messages
            unset($themeElements['']);

            if($themeElements):

                // echo '<pre>'; print_r($themeElements); echo '</pre>';

            ?>
            <style>
                :root {

            <?php

                    // Put settings in css variables
                    foreach($themeElements as $key => $element):

                        // Put each element in css variables
                        foreach ($element as $name => $value):

                            // Modify 'Font family'
                            if($name == 'font-family'):
                                $value = 'var(--font-'.$value.')';

                            // Modify 'Font size'
                            elseif($name == 'font-size'):
                                $value = round(intval($value)/16, 3).'rem'; 

                            // Modify 'Letter spacing'
                            elseif($name == 'letter-spacing'):
                                if($value != 0):
                                    $value = $value.'rem'; 
                                endif;

                            // Modify 'Margin'
                            elseif($name == 'margin'):

                                // Skip the body margin style
                                if($key == 'body' && $name == 'margin'):
                                    continue;
                                endif;

                                $margins = '';

                                foreach ($value as $margin):
                                    if($margin != 0):
                                        $margin = $margin.'rem'; 
                                    endif;

                                    $margins .= $margin.' ';
                                endforeach;

                                if($margins == '0 0 0 0 '):
                                    $value = 0; 
                                else:
                                    $value = rtrim($margins);
                                endif;
                            
                            // Check if desktop is filled
                            elseif($name == 'responsive'):

                                foreach ($value as $breakpoint => $breakpointValue):

                                    if($breakpoint == 'sm-min' && $breakpointValue['font-size'] > 0):
                                        $breakpointValue = round(intval($breakpointValue['font-size'])/16, 3).'rem'; 
                                        echo '} @media only screen and (min-width: 768px) { :root { --'.$key.'-font-size: '.$breakpointValue.';}} :root {';
                                    endif;

                                    if($breakpoint == 'md-min' && $breakpointValue['font-size'] > 0):
                                        $breakpointValue = round(intval($breakpointValue['font-size'])/16, 3).'rem'; 
                                        echo '} @media only screen and (min-width: 991px) { :root { --'.$key.'-font-size: '.$breakpointValue.';}} :root {';
                                    endif;

                                    if($breakpoint == 'lg-min' && $breakpointValue['font-size'] > 0):
                                        $breakpointValue = round(intval($breakpointValue['font-size'])/16, 3).'rem'; 
                                        echo '} @media only screen and (min-width: 1280px) { :root { --'.$key.'-font-size: '.$breakpointValue.';}} :root {';
                                    endif;

                                endforeach;

                                continue;

                            endif;

                            echo '--'.$key.'-'.$name.': '.$value.';';

                        endforeach;
                       
    //                     echo '<pre>'; print_r($element); echo '</pre>';
                    endforeach;
            ?>
                }
            </style>
            <?php
            endif;
        endif;
        // echo '<pre>'; print_r($themeStylesFields); echo '</pre>';
        
    endif;
}

add_action('wp_head', 'add_elements_variables');


// Get fonts in font selector
function get_fonts_dynamically( $field ) {
    $fonts = get_field('fonts', 'theme');

    if($fonts):

        $field['choices'] = array();

        $primary    = isset($fonts[0]) ? $fonts[0]['font'] : null;
        $secondary  = isset($fonts[1]) ? $fonts[1]['font'] : null;
        $third      = isset($fonts[2]) ? $fonts[2]['font'] : null;

        if($primary):   $field['choices']['primary'] = $primary; endif;
        if($secondary): $field['choices']['secondary'] = $secondary; endif;
        if($third):     $field['choices']['third'] = $third; endif;

    endif;

    return $field;
}
add_filter('acf/prepare_field/name=font-family', 'get_fonts_dynamically');