<?php
  $lazy    = isset($lazy) ? $lazy : true;
  $overlay = isset($video['overlay']) ? $video['overlay'] : null;
  $url     = $video['url'];
  $image   = $video['image'];
?>

<div class="content-image">
  <div class="video-holder">
    <?php if($lazy): ?><div class="video-lazy"><?php endif; ?>
      <video
        class="video-player<?php if($lazy): ?> lazy<?php endif; ?>"
        loop muted playsinline disablepictureinpicture autoplay
        type="video/mp4">
        <?php if($lazy): ?>
          <source data-src="<?php echo $url; ?>" type="video/mp4">
        <?php else: ?>
          <source src="<?php echo $url; ?>" type="video/mp4">
        <?php endif; ?>
      </video>

      <?php 
        if(isset($overlay) && $overlay['active']):
          include( locate_template( 'partials/elements/image-overlay.php', false, false ) );
        endif;
      ?>

    <?php if($lazy): ?>
      </div>
      <?php if($image): ?><div class="lazy-placeholder"><img src="<?php echo $image['sizes']['placeholder']; ?>" alt="<?php echo $image['alt']; ?>" /></div><?php endif; ?>
    <?php endif; ?>
  </div>
</div>