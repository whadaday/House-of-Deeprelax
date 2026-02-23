<?php 
	/* Block Name: Main slider */
	/* Order: 30 */

	$agenda = $content['agenda'];
	$styles = get_field('styles', 'theme');
	$colorCta = $styles['color-cta'];
	// echo '<pre>'; print_r($styles); echo '</pre>';
	$showNav = $options['show-nav'];
	$colorSlider = $options['slider-color'];
	$activeSlide = isset($options['active-slide']) ? $options['active-slide'] : 2;
?>
<div class="columns columns-text">
	<div class="column">
		<div class="content">
			<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
		</div>
	</div>
</div>

<div class="columns columns-carousel">
	<div class="column">
		<div class="content">
			<div 
				class="swiper swiper-agenda swiper-container" 
				data-amount-slides="<?php echo count($agenda); ?>" 
				data-active-slide="<?php echo $activeSlide; ?>" 
				data-slider-color="<?php echo $colorSlider; ?>">

				<div class="swiper-wrapper">
				
					<?php
						foreach($agenda as $item):
 
							$title  = $item['title'];
							$text   = $item['text'];
							$link	= $item['link'];
							$anchor = $item['anchor'];
							$image  = $item['image'];
					?>
							
							<div class="swiper-slide">
								<div class="content">
									<div class="card-agenda">

										<?php if($link): 
											$link_url 	 = $link['url'];
										    $link_title  = $link['title'];
										    $link_target = $link['target'] ? $link['target'] : '_self';
										?>

											<a
										    	class="anchor"
										    	href="<?php echo esc_url( $link_url ); ?>"
										    	target="<?php echo esc_attr( $link_target ); ?>">
										    </a>

										<?php elseif($anchor): ?><a class="anchor" href="<?php echo esc_url( $anchor ); ?>"></a><?php endif; ?>
										
										<?php if($image): 
											$content['image'] = $image;
											$imageSize = 's';
											include( locate_template( 'partials/elements/image.php', false, false ) );
											$imageSize = '';

										endif;
										?>

										<div class="card-content">
											<h3 class="content-title "><?php echo $title; ?></h3>
											<div class="content-text">
												<p><?php echo $text; ?></p>
												<div class="content-action" data-btn-color="<?php echo $colorCta; ?>">
													<?php if($link): ?>
														<span class="btn"><?php echo $link_title; ?></span>
													<?php else: ?>
														<span class="btn">Ga naar <?php echo strtolower($title); ?></span>
													<?php endif; ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
					<?php
						endforeach;
					?>
				</div>
				
				<div class="swiper-pagination"></div>

			</div>
		</div>
	</div>
	<div class="btn-slide btn-slide-next">
		<div class="btn-slide-inner">
			<svg viewBox="0 0 16.4 10.5" style="enable-background:new 0 0 16.4 10.5;" xml:space="preserve">
			  <line class="arrow-line" x1="0.6" y1="5.2" x2="18.1" y2="5.2"/>
			  <line x1="11.6" y1="0.8" x2="15.6" y2="5.2"/>
			  <line x1="11.6" y1="9.6" x2="15.6" y2="5.2"/>
			</svg>
		</div>
	</div>
		<div class="btn-slide btn-slide-prev">
			<div class="btn-slide-inner">
  			<svg viewBox="0 0 16.4 10.5" style="enable-background:new 0 0 16.4 10.5;" xml:space="preserve">
			  <line class="arrow-line" x1="15.6" y1="5.2" x2="0.6" y2="5.2"/>
			  <line x1="4.6" y1="9.6" x2="0.6" y2="5.2"/>
			  <line x1="4.6" y1="0.8" x2="0.6" y2="5.2"/>
			</svg>
		</div>
	</div>
</div>

<?php if($showNav): ?>
<div id="agenda-navigation">
	<div class="container">
		<div class="columns">
			<div class="column">
				<ul class="list-agenda">
				<?php
					foreach($agenda as $item):
						$anchor 	= $item['anchor'];
						$title  	= $item['title'];
						$anchorType = substr($anchor, 0, 10);
				?>
					<li><a class="btn link-agenda-training <?php if($anchor == '#training-1'): echo 'active'; endif; ?>" data-btn-color="light" href="<?php echo esc_url( $anchor ); ?>"><?php echo $title; ?></a></li>
						
				<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>