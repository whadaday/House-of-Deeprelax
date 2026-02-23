<?php
function getTocTitles($blocks) {
    
    $tocTitles = array();
    
    foreach($blocks as $block):
      $text = isset($block['content']['text']) ? $block['content']['text'] : null;
      if($text):

        // preg_match_all('/<h3>([\S\s\n\t\r\n\f.]*?<\/h3>)/', $text, $h3);

        $h2 = preg_grep('/(?s)(?<=<h2>).*?(?=<\/h2>)/', explode("\n", $text));
        // $h3 = preg_grep('/(?s)(?<=<h3>).*?(?=<\/h3>)/', explode("\n", $text));
        // $h4 = preg_grep('/(?s)(?<=<h3>).*?(?=<\/h4>)/', explode("\n", $text));
        // $h5 = preg_grep('/(?s)(?<=<h3>).*?(?=<\/h5>)/', explode("\n", $text));
        // $h6 = preg_grep('/(?s)(?<=<h3>).*?(?=<\/h6>)/', explode("\n", $text));

        // echo '<pre>'; print_r($h2); print_r($h3); echo '</pre>';

        $titles = $h2;
        ksort($titles);

        // echo '<pre>'; print_r($titles); echo '</pre>';

        foreach($titles as $title):
          
          $heading = substr($title, 2, 1);

          preg_match('/<h'.$heading.'>(.*?)<\/h'.$heading.'>/s', $title, $match);
          array_push($tocTitles, $heading.' '.$match[1]);

        endforeach;

      endif;
    endforeach;

    return $tocTitles;
}

function addTocTitlesToText($text) {
    $headings = array(2,3,4,5,6);
    foreach($headings as $heading):
      $titles = preg_grep('/<h'.$heading.'>.*<\/h'.$heading.'>/', explode("\n", $text));
      foreach($titles as $title):
          preg_match('/<h'.$heading.'>(.*?)<\/h'.$heading.'>/s', $title, $match);
          $title = $match[1];
          $slug = slugify($title);
          $text = str_replace('<h'.$heading.'>'.$title.'</h'.$heading.'>', '<h'.$heading.' id="'.$slug.'">'.$title.'</h'.$heading.'>', $text);
      endforeach;
    endforeach;
    
    return $text;
}