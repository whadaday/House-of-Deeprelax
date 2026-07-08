<?php
  $lang = getLang();
  $socials = hod_option('socials', $lang);

  if($socials):
?>

<ul class="list-socials">
  <?php foreach($socials as $name => $url): 
    $icon = get_template_directory().'/assets/images/social/icon-'.$name.'.svg';
    if($url):
      if($name == 'email'): $url = 'mailto:'.$url; endif;
  ?>

    <li>
      <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr(ucfirst($name)); ?>">
        <?php echo hod_inline_svg($icon); ?>
      </a>
    </li>

  <?php endif; endforeach; ?>
</ul>

<?php endif; ?> 