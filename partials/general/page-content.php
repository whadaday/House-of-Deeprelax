<?php
  global $contentBlockIndex;

  $contentBlocks = get_field('content');

  if($contentBlocks):

    foreach ($contentBlocks as $contentBlock):

      // Get block name
      $layout = $contentBlock['acf_fc_layout'];

      // Check if block is global block and get global block data 
      if($layout == 'block'):

        $globalBlockId = $contentBlock[$layout];
        showGlobalBlock($globalBlockId);

      else:

        if(isset($contentBlock[$layout])):
          $block = $contentBlock[$layout];
          showContentBlock($block, $layout);
        endif;

      endif;

      $contentBlockIndex++;

    endforeach;

  endif;
?>