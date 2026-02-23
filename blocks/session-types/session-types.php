<?php 
	/* Block Name: Sessies types feed */
	/* Order: 210 */
 
	$activePageType = null;

	$page_id = get_the_ID();
	$pages = $content['pages'];

 	if($pages): 
 ?>

	<div class="columns columns-pages">
		<div class="column column-page-list">
			<div class="content">
				<ul class="list-pages">
					<?php
						$iPage = 0;
						foreach($pages as $page):
							$activePage = null;
							$post_id = $page['page'];
							if($post_id == $page_id): $activePage = true; endif;
							if(!$activePage):
								$title = get_the_title($post_id);
					?>
								<li 
									data-page="<?php echo $post_id; ?>"
									class="<?php if($iPage == 0): ?>active<?php endif; ?>"
								>
									<?php echo $title; ?>
								</li>
					<?php 
								$iPage++;
							endif;
						endforeach;
					?>
				</ul>
			</div>
		</div>
		<div class="column column-page"> 
			<div class="content content-pages">
				<?php 
					$iPage = 0;
					foreach($pages as $page):
						$activePage = null;

						$post_id = $page['page'];
						if($post_id == $page_id): $activePage = true; endif;

						if(!$activePage):
							$image 		= $page['icon'];
							$title 		= ($page['title']) ? $page['title'] : get_the_title($post_id);
							$text  		= $page['text'];
							$link  		= get_the_permalink($post_id);
							$btnText    = $page['btn-text'];
				?>
							<div 
								class="content-page <?php if($iPage == 0): echo 'show'; endif; ?>"
								data-page="<?php echo $post_id; ?>"
							>
								<?php 
									if($image): 
										$content['image'] = $image;
										$imageSize = 's';
										include( locate_template( 'partials/elements/image.php', false, false ) );
										$imageSize = '';
									endif;
								?>
								<h3><?php echo $title; ?></h3>
								<?php if($text): ?><p><?php echo $text; ?></p><?php endif; ?>
								<div class="content-action" data-btn-color="color">
									<a href="<?php echo $link; ?>" class="btn"><?php echo $btnText; ?></a>
								</div>
							</div>
				<?php 
							$iPage++;
						endif;
					endforeach;
				?>
			</div>
		</div>
	</div>

<?php endif; ?>