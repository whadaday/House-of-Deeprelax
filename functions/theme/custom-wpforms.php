<?php
/** Disable the scrolling effect on field validation errors
 *
 *  @link   https://wpforms.com/developers/how-to-disable-the-scrolling-effect-on-field-validation/
 */
 
function wpf_dev_disable_scroll_to_error( $forms ) {
 
    // If scrollToError is disabled for at least one form on the page, it will be disabled for all the forms on the page.
 
    ?>
    <script type="text/javascript">wpforms.scrollToError = function(){};</script>
    <?php
}
add_action( 'wpforms_wp_footer_end', 'wpf_dev_disable_scroll_to_error', 10, 1 );

/*
add_filter('wpforms_field_submit', function ($submit_text, $form_data){
    
    $icon        = get_template_directory().'/assets/images/icon-arrow-right.svg';
    $submit_text = file_get_contents($icon);

    return $submit_text;

}, 10, 2);
*/

/**
 * Customize name field properties.
 *
 * @link   https://wpforms.com/developers/how-to-change-sublabels-for-the-name-field/
 */
 
function wpf_dev_name_field_properties( $properties, $field, $form_data ) {

        if(isset($properties[ 'inputs' ][ 'first' ])):
            $properties[ 'inputs' ][ 'first' ][ 'sublabel' ][ 'value' ] = __( 'Voornaam', 'plugin-domain' );
        endif;

        if(isset($properties[ 'inputs' ][ 'last' ])):
            $properties[ 'inputs' ][ 'last' ][ 'sublabel' ][ 'value' ] = __( 'Achternaam', 'plugin-domain' );
        endif;
    
    return $properties;
}
 
add_filter( 'wpforms_field_properties_name' , 'wpf_dev_name_field_properties', 10, 3 );


/*
 * Hide the disable emails option on Settings >> Misc
 *
 * @link https://wpforms.com/developers/how-to-disable-the-checkbox-setting-for-the-weekly-email-summary/
 */
 
add_filter( 'wpforms_emails_summaries_is_disabled', '__return_true' );
