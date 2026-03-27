<?php
function add_style_variables() {
    if( class_exists('ACF') ) :
        $themeStyles          = get_field('styles', 'theme');
        $themeVariablesStyles = array();

        $themeStylesObject = get_field_object('styles', 'theme');

        // echo '<pre>'; print_r($themeStyles); echo '</pre>';

        // echo '<pre>'; print_r($themeStylesFields); echo '</pre>';

        // die();

        /* Change array format */
        if(!empty($themeStyles)):
            $themeStylesFields = $themeStylesObject['sub_fields'];

            foreach ($themeStyles as $name => $value):
                
                if($name):

                    // Check if class is 'choose bg'
                    $key = 0;
                    foreach ($themeStylesFields as $field):
                        if($field['name'] == $name):
                            break;
                        endif;
                        $key++;
                    endforeach;

                    if($themeStylesFields[$key]['wrapper']['class'] == 'choose-bg'):

                        $checkIfVariable = substr($value,0,2);
                        if($checkIfVariable != '--'):
                            $value = '--color-'.$value;
                            $variable = '--'.strtolower(str_replace(' ', '-', $name));
                            $themeVariablesStyles[$variable] = $value;
                        endif;

                    endif;

                endif;
            endforeach;
        endif;


        ?>
            <style>
                :root {
                    <?php

                    //echo '<pre>'; print_r($themeVariablesStyles); die(); 

                    /* Set standard color variables */
                    foreach($themeVariablesStyles as $name => $variable):
                        echo $name.': var('.$variable.');';
                        if($name == '--color-cta' || $name == '--color-cta-fourth'):
                            echo $name.'-darker: var('.$variable.'-darker);';
                            echo $name.'-rgb: var('.$variable.'-rgb);';
                        endif;
                        
                        if( $name == '--color-text-dark' ||
                            $name == '--color-text-dark-secondary' ||
                            $name == '--color-text-light' ||
                            $name == '--color-text-light-secondary'):

                            echo $name.'-rgb: var('.$variable.'-rgb);';

                        endif;
                    endforeach;
                    ?>
                }
            </style>
        <?php
        
    endif;
}

add_action('wp_head', 'add_style_variables');