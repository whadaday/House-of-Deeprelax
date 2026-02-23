<?php
  $lang = getLang();
  $socials = get_field('socials', $lang);

  if($socials):
?>

<ul class="list-socials">
  <?php foreach($socials as $name => $url): 
    $icon = get_template_directory().'/assets/images/social/icon-'.$name.'.svg';
    if($url):
      if($name == 'email'): $url = 'mailto:'.$url; endif;
  ?>

    <li>
      <a href="<?php echo $url; ?>" target="_blank">
        <?php echo file_get_contents($icon); ?>
      </a>
    </li>

  <?php endif; endforeach; ?>
</ul>

<?php endif; ?> 