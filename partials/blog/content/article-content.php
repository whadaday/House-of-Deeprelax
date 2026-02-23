<?php
  $blockIndex = 1;
  $contentBlocks = get_field('content');

  if($contentBlocks):

    foreach ($contentBlocks as $contentBlock):



      // Get block name
      $layout = $contentBlock['acf_fc_layout'];
      $block = $contentBlock;

      showBlogContentBlock($block, $layout, $blockIndex);

      $blockIndex++;

    endforeach;

  endif;
?>