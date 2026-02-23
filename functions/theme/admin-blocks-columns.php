<?php

function custom_columns( $columns ) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Title',
        'blocks' => 'Blocks',
        'date' => 'Date'
     );
    return $columns;
}
add_filter('manage_block_posts_columns' , 'custom_columns');

function custom_columns_data( $column, $post_id ) {
    switch ( $column ) {
    case 'blocks':
        $contentBlocksObject = get_field_object('content', $post_id);
        $contentBlocks = $contentBlocksObject['value'];
        $contentBlocksInfo = $contentBlocksObject['layouts'];
        $i = 0;
        if($contentBlocks):
            foreach($contentBlocks as $block):
                $currentLayout = $block['acf_fc_layout'];

                foreach($contentBlocksInfo as $layout):
                    // echo '<pre>'; print_r($layout); echo '</pre>';
                    if($currentLayout == $layout['name']):
                        if($i > 0): echo ', '; endif;
                        echo $layout['label'];
                        break;
                    endif;

                endforeach;

                $i++;

            endforeach;

        endif;

        break;
    }
}
add_action( 'manage_block_posts_custom_column' , 'custom_columns_data', 10, 2 ); 

?>