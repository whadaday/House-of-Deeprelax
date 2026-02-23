<?php
    global $post;   
    $author_id = $post->post_author;

    $authorName1  = get_the_author_meta('first_name', $author_id);
    $authorName2  = get_the_author_meta('last_name', $author_id);
    if($authorName2 != ''):
        $authorName = $authorName1.' '.$authorName2;
    else: 
        $authorName = $authorName1;
    endif;

    $author_bio  = get_the_author_meta('author-description', $author_id);
    $authorLink  = get_author_posts_url($author_id);

    $authorImage = get_field('author-image', 'user_'.$author_id);
    $authorRole  = get_field('author-role', 'user_'.$author_id);

    $socials     = get_field('author_socials', 'user_'.$author_id);

    if($author_bio):

?>
<div class="content content-author-outro">
    <div class="author-summary">
        <?php if($authorImage): 
            $content['image'] = $authorImage;
        ?>
            <a class="author-image" href="<?php echo $authorLink; ?>">
                <?php 
                    $imageSize = 'xs';
                        include( locate_template( 'partials/elements/image.php', false, false ) );
                    $imageSize = '';
                ?>
            </a>
        <?php endif; ?>
        <div class="author-content">
            <p class="author-name"><a href="<?php echo $authorLink; ?>"><?php echo $authorName; ?></a><?php if($authorRole): ?>, <span class="author-role"><?php echo lcfirst($authorRole); ?></span><?php endif; ?></p>
            <?php if($author_bio): ?><p class="author-description"><?php echo $author_bio; ?> <?php /*<a href="<?php echo $authorLink; ?>">Lees verder</a>*/ ?></p><?php endif; ?>

            <?php if($socials): ?>
                <ul class="list-author-socials">
                    <?php foreach($socials as $social => $url):
                        if($url):
                            if($social == 'email'): $url = 'mailto:'.$url; endif;
                            $icon = get_template_directory().'/assets/images/social/icon-'.$social.'.svg';
                    ?>
                        <li>
                            <a class="link-social link-<?php echo $social; ?>" href="<?php echo $url; ?>" target="_blank">
                                <?php echo file_get_contents($icon); ?>
                            </a>
                        </li>
                    <?php endif; endforeach; ?>
                </ul>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php endif; ?>