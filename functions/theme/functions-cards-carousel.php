<?php
// Vul select "Categorie" (field_691c4307b72d1) met de waardes uit
// carousel-cards -> content -> cards-filters -> name

add_filter('acf/prepare_field/key=field_691c4307b72d1', function( $field ) {

    // Altijd schoon beginnen
    $field['choices'] = [];

    // Huidige post ID
    $post_id = get_the_ID();
    if ( ! $post_id ) {
        return $field;
    }

    // Naam van jouw flexible content field
    // Pas deze aan naar hoe hij bij jou heet
    $flex_field_name = 'content';

    // Alle layouts van de flex ophalen
    $layouts = get_field( $flex_field_name, $post_id );

    if ( empty( $layouts ) || ! is_array( $layouts ) ) {
        return $field;
    }

    $choices = [];

    foreach ( $layouts as $layout ) {

        // We willen alleen de layout 'carousel-cards'
        if ( empty( $layout['acf_fc_layout'] ) || $layout['acf_fc_layout'] !== 'carousel-cards' ) {
            continue;
        }

        // Jouw print_r liet zien:
        // [carousel-cards] => [content] => [cards-filters] => [0]['name']
        if (
            empty( $layout['carousel-cards']['content']['cards-filters'] ) ||
            ! is_array( $layout['carousel-cards']['content']['cards-filters'] )
        ) {
            continue;
        }

        foreach ( $layout['carousel-cards']['content']['cards-filters'] as $row ) {

            if ( empty( $row['name'] ) ) {
                continue;
            }

            $label = trim( $row['name'] );
            if ( $label === '' ) {
                continue;
            }

            // Slug als value gebruiken
            $value = sanitize_title( $label );

            // Dubbele namen voorkomen
            $choices[ $value ] = $label;
        }
    }

    if ( ! empty( $choices ) ) {
        $field['choices'] = $choices;
    }

    return $field;
});
