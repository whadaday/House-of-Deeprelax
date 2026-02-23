	<?php
		/* Block Name: FAQ grid */
		/* Order: 181 */

		//get categories
		$terms = get_terms( array(
			'taxonomy'   => 'faq-category',
			'hide_empty' => true,
		) );

		if($terms):
	?>
	<div class="columns">
		<?php foreach ($terms as $term):
			$name = $term->name;
			$url = get_term_link($term);
		?>
			<div class="column">
				<div class="content">
					<h3 class="faq-title"><?php echo $name; ?></h3>
					<ul class="faq-list">
						<?php
							$i = 0;
							$query = getFaqArticles($term->term_id, 3);
							$articles = $query->posts; 
							foreach($articles as $article):
								$post_id = $article->ID;
								$title = get_the_title($post_id);
						?>

							<li><a href="<?php echo $url; ?>#<?php echo $post_id; ?>"><?php echo $title; ?></a></li>
						<?php $i++; endforeach; ?>
					</ul>
					<div class="content-action">
						<a class="btn" href="<?php echo $url; ?>">Bekijk alle vragen</a>
					</div>
				</div>
			</div>

		<?php endforeach; ?>
	</div>
<?php
	endif;
?>