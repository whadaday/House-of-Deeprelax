<?php
  $showToc = get_field('show-toc');
  if($showToc):
    $text   = get_field('content');
    $titles = getTocTitles($text);
    if($titles):
?>

  <div class="content article-toc">
    <h2>In dit artikel</h2>
    <ol>
      <?php 
        // Get heading of first title
        $currentHeading = intval(substr($titles[0], 0, 1));

        $iTitles = 0;
        $level = 0;
        foreach($titles as $title): 

          // heading number
          $heading = intval(substr($title, 0, 1));

          // Get next Heading
          $nextHeading = isset($titles[$iTitles+1]) ? intval(substr($titles[$iTitles+1], 0, 1)) : null;

          // Get previous Heading
          $previousHeading = isset($titles[$iTitles-1]) ? intval(substr($titles[$iTitles-1], 0, 1)) : $heading;

          // remove heading from number
          $title = substr($title, 2);

          // Start new list
          if($previousHeading < $heading): 
            echo '<ol>';
            $level++;
          endif;

          echo '<li>';
          echo '<a href="#'.slugify($title).'">'.$title.'</a>';

          // End new list
          if($nextHeading < $heading && $level > 0): 
            echo '</ol>';
            $level--;
          endif;

          // Start end list item
          if($nextHeading <= $heading): echo '</li>'; endif;

          $iTitles++;

        endforeach;
      ?>
    </ol>
  </div>

<?php endif; endif; ?>