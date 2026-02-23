<?php 
	/* Block Name: Cards carousel */
	/* Order: 30 */

	$cards = $content['cards'];
	$filters = $content['cards-filters'];

	$categories = [];

	if ( ! empty( $filters ) && ! empty( $cards ) ) {

	    // 1. Verzamel alle categorieën die in de kaarten gebruikt worden
	    $used_categories = [];

	    foreach ( $cards as $card ) {
	        if ( empty( $card['list-filters-category'] ) ) {
	            continue;
	        }

	        // Ga uit van een slug of maak er één (werkt ook als het al een slug is)
	        $slug = sanitize_title( $card['list-filters-category'] );

	        $used_categories[ $slug ] = true;
	    }

	    // 2. Loop over de filters in de gewenste volgorde
	    foreach ( $filters as $filter ) {
	        if ( empty( $filter['name'] ) ) {
	            continue;
	        }

	        // Zelfde normalisatie als bij de cards
	        $slug  = sanitize_title( $filter['name'] );

	        // Alleen toevoegen als deze categorie ook echt in een card voorkomt
	        if ( isset( $used_categories[ $slug ] ) ) {

	            $categories[] = $filter['name'];
	        }
	    }
	}

?>
<div class="columns columns-text">
	<div class="column">
		<div class="content">
			<?php include( locate_template( 'partials/elements/text.php', false, false ) ); ?>
		</div>
	</div>
</div>

<?php if(!empty($categories)): ?>
<div class="columns columns-filters">
	<div class="column">
		<div class="content">
			<ul class="list-filters">
				<?php $i = 0; foreach ($categories as $category): ?>
					<li><a <?php if($i == 0): echo 'class="active"'; endif; ?> href="#" data-type="<?php echo slugify($category); ?>"><?php echo $category; ?></a></li>
				<?php $i++; endforeach; ?>
			</ul>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="columns columns-carousel">
	<div class="swiper swiper-container carousel-cards">

		<div class="swiper-wrapper">
		
			<?php
				foreach($cards as $card):

					$title  = $card['title'];
					$text   = $card['text'];
					$link	= $card['url'];
					$image  = $card['image'];
					$category = $card['list-filters-category'];
			?>
					
					<div class="swiper-slide" data-type="<?php echo slugify($category); ?>">
						<div class="content content-post" data-color-text="light">

							<?php if($link): ?>
								<a class="card-link" href="<?php echo $link; ?>"></a>
							<?php endif; ?>
							<div class="card-post">
								<?php
									$content['image'] = $image;
									$imageSize = 'm';
			                        include( locate_template( 'partials/elements/image.php', false, false ) );
			                        $imageSize = '';
								?>
							</div>
							<div class="card-content">
								<div class="card-content-inner">
									<h3 class="card-title"><?php echo $title; ?></h3>
									<?php if($text): ?><span class="card-subtitle"><?php echo $text; ?></span><?php endif; ?>
								</div>
							</div>


						</div>
					</div>
			<?php
				endforeach;
			?>
		</div>

	</div>
</div>

<div class="columns columns-action">
	<div class="column">
		<div class="content">
			<div class="actions-holder">
				
				<?php
					$link = $content['link'];
					if($link):
						$link_url 	 = $link['url'];
					    $link_title  = $link['title'];
					    $link_target = $link['target'] ? $link['target'] : '_self';
					?>
					<a 
						class="link"
						href="<?php echo esc_url( $link_url ); ?>"
						target="<?php echo esc_attr( $link_target ); ?>">
						<?php echo esc_html( $link_title ); ?>
					</a>
				<?php endif; ?>
				
				<div class="btns-holder">
					<div class="btn-slide btn-slide-cards-prev">
						<div class="btn-slide-inner">
				  			<svg viewBox="0 0 16.4 10.5" style="enable-background:new 0 0 16.4 10.5;" xml:space="preserve">
							  <line class="arrow-line" x1="15.6" y1="5.2" x2="0.6" y2="5.2"/>
							  <line x1="4.6" y1="9.6" x2="0.6" y2="5.2"/>
							  <line x1="4.6" y1="0.8" x2="0.6" y2="5.2"/>
							</svg>
						</div>
					</div>
					
					<div class="btn-slide btn-slide-cards-next">
						<div class="btn-slide-inner">
							<svg viewBox="0 0 16.4 10.5" style="enable-background:new 0 0 16.4 10.5;" xml:space="preserve">
							  <line class="arrow-line" x1="0.6" y1="5.2" x2="18.1" y2="5.2"/>
							  <line x1="11.6" y1="0.8" x2="15.6" y2="5.2"/>
							  <line x1="11.6" y1="9.6" x2="15.6" y2="5.2"/>
							</svg>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>