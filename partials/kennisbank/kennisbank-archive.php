<?php

  /** is this a category archive page? */
  // if(is_category()):
  //   $category = $wp_query->get_queried_object();
  //   $title = $category->name;
  //   $query = getArticles($category->term_id);
  
  // /* Or the parent blog archive */
  // else:
  //   $query = getArticles();
  //   $title = 'Blog';
  // endif;

  $title = 'Kennisbank';

  $articles = getKennisbankArticles();

  $alphabet = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');

  $listOfCharacters = array();
  foreach($articles as $article):
  	$firstCharacter = strtolower(substr(get_the_title($article), 0, 1));
  	array_push($listOfCharacters, $firstCharacter);
  endforeach;
  $listOfCharacters = array_unique($listOfCharacters);

  if($articles):
?>

<section
  id="section-1"
  class="section section-kennisbank-az"
  data-color="white"
  data-whitespace-top="normal" data-whitespace-bottom="normal">

  <div class="container content-animate">

    <?php include_once( locate_template( 'partials/navigation/breadcrumbs.php', false, false ) ); ?>

    <div class="columns columns-header">
      <div class="column column-text">
        <h1><?php echo $title; ?></h1>
      </div>
    </div>

    <div class="columns columns-kennisbank-az">
    	<div class="column">
    		<div class="content">

    			<ul class="list-alphabet">
    				<?php foreach($alphabet as $character): ?>
    					<li>
	    					<?php if (in_array($character, $listOfCharacters)): ?>
	    						<a href="#kennisbank-<?php echo $character; ?>"><?php echo $character; ?></a>
	    					<?php else: ?>
	    						<span><?php echo $character; ?></span>
	    					<?php endif; ?>
    					</li>
    				<?php endforeach; ?>
    			</ul>

        		<?php
        		$currentCharacter = '';
        		foreach($articles as $article):

        			$post_id  		= $article;
        			$link     		= get_the_permalink($post_id);
  					$title    		= get_the_title($post_id);
        			$excerpt  		= get_the_excerpt($post_id);
        			$firstCharacter = strtolower(substr($title, 0, 1));

        			if($firstCharacter != $currentCharacter): ?>


        				<?php // don't do this for first 'kennisbank-character'
        				if($currentCharacter != ''): ?></div><?php endif; ?>
        				
        				<div id="kennisbank-<?php echo $firstCharacter; ?>" class="kennisbank-character">
        					<h3><?php echo strtoupper($firstCharacter); ?></h3>

        		<?php
        			$currentCharacter = $firstCharacter; 
        			endif;
        		?>

        			<div class="kennisbank-article">
        				<p><a href="<?php echo $link; ?>"><strong><?php echo $title; ?></strong></a><br />
        				<?php if($excerpt): echo $excerpt; endif; ?></p>
        			</div>

          		<?php endforeach; ?>

          	</div>
        </div>
    </div>

  </div>
</section>

<?php endif; ?>