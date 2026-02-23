<?php
  // $block_templates_id = 2785;
  // $globalContentBlocks = get_field('content', $block_templates_id);

  // $templateLayouts2 = array();
  // foreach ($globalContentBlocks as $contentBlock):
          
  //   // Get block name of global block
  //   $layout = $contentBlock['acf_fc_layout'];
  //   $block  = $contentBlock[$layout];

  //   // Put block content in more readable variables
  //   $content = isset($block['content']) ? $block['content'] : null;
  //   $options = isset($block['options']) ? $block['options'] : null;

  //   // Remove empty keys, casues by tabs
  //   unset($options['']);

  //   $templateLayouts2[] = array(
  //     'layout' => $layout,
  //     'content' => $content,
  //     'options' => $options
  //   );
    
  // endforeach;

  // echo '<pre>'; print_r($templateLayouts2);

  // die();

  // ABOVE IS TEST CONTENT

  get_header();
  $lang = getLang();

  $globalBlockId = get_field('page-start', $lang);
  if($globalBlockId): showGlobalBlock($globalBlockId); endif;
    
  include( locate_template( 'partials/general/page-content.php', false, false ) );

  $globalBlockId = get_field('page-end', $lang);
  if($globalBlockId): showGlobalBlock($globalBlockId); endif;

  get_footer();
?>