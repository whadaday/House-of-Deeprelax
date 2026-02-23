<?php

/* WYSIWYG editor ------------------------------------------------------------------------------------------ */
function customize_wysiwyg_field( $field ) {
    
    // Disable media upload
    $field['media_upload'] = 0;

    return $field;
    
}
add_filter('acf/prepare_field/type=wysiwyg', 'customize_wysiwyg_field');

/* Block options ------------------------------------------------------------------------------------------ */
function acf_load_custom_field_choices( $field ) {

    
    $choices = $field['choices'];

    foreach($choices as $value => $choice):
         $field['choices'][$value] = '
            <span class="choice-indicator" data-type="'.$value.'"></span>
            <span class="choice-indicator-label">'.$choice.'</span>
        ';
    endforeach;

    return $field;
    
}
add_filter('acf/prepare_field/name=text-align',     'acf_load_custom_field_choices');
add_filter('acf/prepare_field/name=column-align',   'acf_load_custom_field_choices');
add_filter('acf/prepare_field/name=media-type',     'acf_load_custom_field_choices');
add_filter('acf/prepare_field/name=header-type',    'acf_load_custom_field_choices');
add_filter('acf/prepare_field/name=content-type',   'acf_load_custom_field_choices');
add_filter('acf/prepare_field/name=background',     'acf_load_custom_field_choices');
add_filter('acf/prepare_field/name=columns-width',  'acf_load_custom_field_choices');
add_filter('acf/prepare_field/name=image-height',   'acf_load_custom_field_choices');
add_filter('acf/prepare_field/name=align-vertical', 'acf_load_custom_field_choices');
add_filter('acf/prepare_field/name=btn-align',      'acf_load_custom_field_choices');


function acf_save_json( $paths, $post ) {

    $modules = explode(",", $post['description']);

    if($post['description'] == 'Bouwsteen'):

        $name = $post['title'];
        $name = strtolower($name);
        $name = str_replace('\'', '', $name);
        $name = str_replace('+', '', $name);
        $name = str_replace('&', '', $name);
        // to fix 
        $name = str_replace('  ', '-', $name);
        $name = str_replace(' ', '-', $name);
        $paths = array( get_stylesheet_directory() . '/blocks/'.$name );

        // echo $name; die();

    elseif(is_array($modules) && in_array("Module", $modules) ):

        if(in_array("Bouwsteen", $modules)):
            $name = $post['title'];
            $name = strtolower($name);
            $name = str_replace('\'', '', $name);
            $name = str_replace('+', '', $name);
            $name = str_replace('&', '', $name);
            // to fix 
            $name = str_replace('  ', '-', $name);
            $name = str_replace(' ', '-', $name);

            $module = slugify($modules[2]);
            $paths = array( get_stylesheet_directory() . '/modules/'.$module.'/blocks/'.$name );
        else:
            $module = slugify($modules[1]);
            $paths = array( get_stylesheet_directory() . '/modules/'.$module.'/acf' );
            $name = '';
        endif;

        // echo '<pre>'; print_r($paths); die();

        //check if directory exists
        //if not, create directory

    else:
        $paths = get_stylesheet_directory() . '/functions/acf';
    endif;

    //check if directory exists and if not, create directory
    if (!file_exists($paths[0]) && substr($paths[0], -7) != '-(copy)' && substr($paths[0], -8) != '-(kopie)'): 
        mkdir($paths[0], 0755);
    endif;

    return $paths;
}
add_filter( 'acf/json/save_paths', 'acf_save_json', 10, 2 );




add_filter('acf/settings/load_json', 'acf_load_json');

function acf_load_json( $paths ) {
    
    unset($paths[0]);

    // Load ACF fields
    $paths[] = get_stylesheet_directory() . '/functions/acf';

    // Load ACF block fields
    $base_dir = trailingslashit(get_template_directory());
    $dir      = 'blocks/*';
    $folders  = glob($base_dir.$dir);

    foreach ($folders as $folder):
        $paths[] = $folder;
    endforeach;

    // Load ACF modules acf fields
    $dir      = 'modules/*/acf';
    $folders  = glob($base_dir.$dir);

    foreach ($folders as $folder):
        $paths[] = $folder;
    endforeach;

    // Load ACF modules block fields
    $dir      = 'modules/*/blocks/*';
    $folders  = glob($base_dir.$dir);

    foreach ($folders as $folder):
        $paths[] = $folder;
    endforeach;

    // echo '<pre>'; print_r($paths); echo '</pre>'; die();

    return $paths;
    
}


/*************************************************************/
/*   Friendly Block Titles                                  */
/***********************************************************/

function custom_layout_title($title, $field, $layout, $i) {
    // $layoutName = $layout['label'];
    // $sectionTitle = $field['value'][0]['field_627df7da950e7']['field_62a04730d0aca']['field_627e259307d4a_field_62a05a695ea2a'];

    // if($sectionTitle != ''):
    //     $title = $sectionTitle.' ('.$layoutName.')';
    // else:
    //     $title = $layoutName;
    // endif;

    return $title;
}
add_filter('acf/fields/flexible_content/layout_title', 'custom_layout_title', 10, 4);

//original code

// function my_layout_title($title, $field, $layout, $i) {
//     if($value = get_sub_field('layout_title')) {
//         return $value;
//     } else {
//         foreach($layout['sub_fields'] as $sub) {
//             if($sub['name'] == 'layout_title') {
//                 $key = $sub['key'];
//                 if(array_key_exists($i, $field['value']) && $value = $field['value'][$i][$key])
//                     return $value;
//             }
//         }
//     }
//     return $title;
// }
// add_filter('acf/fields/flexible_content/layout_title', 'my_layout_title', 10, 4);

// add_filter('acf/update_value', 'modify_headings_for_seo', 10, 3);

function modify_headings_for_seo($value, $post_id, $field) {
    // Check if the field is of type 'textarea' or 'wysiwyg'
    if (in_array($field['type'], ['text', 'textarea', 'wysiwyg'])) {
        
        // Define the tags to check
        $tags_to_check = ['span', 'em', 'a', 'b', 'strong', 'i'];

        $value = nl2br($value);

        // Decode all HTML entities to handle &nbsp; and similar
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Replace non-breaking spaces (ASCII 160 or UTF-8) explicitly with a regular space
        $value = str_replace("\xc2\xa0", ' ', $value); // UTF-8 non-breaking space
        $value = str_replace("&nbsp;", ' ', $value);  // HTML entity non-breaking space

        // Replace all extra spaces (including tabs, multiple spaces, etc.) with a single space
        $value = preg_replace('/\s+/', ' ', $value);

        // Loop through each tag and modify the content
        foreach ($tags_to_check as $tag) {
            // Add space before and after the closing tag
            $value = preg_replace("/<\/$tag>/i", " </$tag> ", $value);
        }

        // Remove extra spaces (if necessary)
        $value = preg_replace('/\s+/', ' ', $value);
        $value = trim($value);

    }

    // Return the modified value
    return $value;
}