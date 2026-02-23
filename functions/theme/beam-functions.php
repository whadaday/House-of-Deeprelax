<?php

function slugify($string) {
	return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
}

function addSvgAlign($svg) {
	$svg = str_replace("<svg ", "<svg preserveAspectRatio=\"xMidYMid meet\" ", $svg);
	return $svg;
}

function showLogo($logo_url, $class = null) {
	$logoType = substr($logo_url, -3);
 
	$logo =	'';

	if($class): $logo .= '<figure class="'.$class.'" data-size="normal">'; endif;

	if($logoType == 'svg'):
		$svg = file_get_contents($logo_url);
		$svg = addSvgAlign($svg);
		$logo .= $svg;
	else:
		$logo_id = attachment_url_to_postid($logo_url);
		$logo_obj = acf_get_attachment($logo_id);
		$logo .= '<img alt="'.$logo_obj['alt'].'" src="'.$logo_obj['sizes']['medium'].'" />';
	endif;

	if($class): $logo .= '</figure>'; endif;

	return $logo;
}

function getPostReadTime($id) {

	$totalreadingtime = 1;
	$totalWords = 0;

	$type = get_post_type($id);
	if($type != 'post' && $type != 'kennisbank'): return false; endif;

	// get content blocks
	$contentBlocks = get_field('content', $id);
	$layoutsToRead = array('text', 'quote');

	$word_count_title = str_word_count( strip_tags( get_the_title($id) ) );
	$word_count_excerpt = str_word_count( strip_tags( get_the_excerpt($id) ) );

	$totalWords += $word_count_title;
	$totalWords += $word_count_excerpt;

	if($contentBlocks):
		foreach ($contentBlocks as $contentBlock):
			
			$layout = $contentBlock['acf_fc_layout'];
			
			if (in_array($layout, $layoutsToRead)):

				$block = $contentBlock['content'];

				if($layout == 'text'):
					$word_count = str_word_count( strip_tags( $block['text'] ) );
				elseif($layout == 'quote'):
					$word_count = str_word_count( strip_tags( $block['quote'] ) );
				endif;
				
				$totalWords += $word_count;

			endif;

		endforeach;
	endif;
	
	$readingtime = ceil($totalWords / 250);
	$totalreadingtime = $readingtime.' min lezen';

	return $totalreadingtime;
}

function getLogoUrl() {
    $url = '#wrapper';

    if(!is_front_page()):
        // global $lang;
        // $defaultLang = get_field('site-lang', 'beam');
        // if($lang != $defaultLang):
        //     $url = '/'.$lang;
        // else:
        //     $url = '/';
        // endif;
        $url = '/';

    endif;

    return $url;
}

function getImageCrop($image) {

	if(!$image): return; endif;

	if( is_array($image) ):
		$image = $image['id'];
	endif;

	$crop = get_field('crop', $image);
	
	return $crop;
}

function getImageDimension($image) {
	$imageWidth    = intval($image['width']);
	$imageHeight   = intval($image['height']);

	if($imageWidth > $imageHeight):
		$dimension = 'landscape';
	elseif($imageWidth == $imageHeight):
		$dimension = 'square';
	else:
		$dimension = 'portrait';
	endif;

	return $dimension;
}

function getImageDimensionPercentage($image) {
	$imageWidth    = intval($image['width']);
	$imageHeight   = intval($image['height']);
	$imageRatio    = $imageHeight / $imageWidth * 100; 
	$imageRatio    = round($imageRatio, 2);

	return $imageRatio;
}

function hexToRgb( $color ) {
    if ( $color[0] == '#' ) {
            $color = substr( $color, 1 );
    }
    if ( strlen( $color ) == 6 ) {
            list( $r, $g, $b ) = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif ( strlen( $color ) == 3 ) {
            list( $r, $g, $b ) = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
            return false;
    }
    $r = hexdec( $r );
    $g = hexdec( $g );
    $b = hexdec( $b );
    return array( 'r' => $r, 'g' => $g, 'b' => $b );
}

function rgbToHsl($color) {
	$oldR = $color['r'];
	$oldG = $color['g'];
	$oldB = $color['b'];

	$r = $color['r'];
	$g = $color['g'];
	$b = $color['b'];

	$r /= 255;
	$g /= 255;
	$b /= 255;

    $max = max( $r, $g, $b );
	$min = min( $r, $g, $b );

	$h;
	$s;
	$l = ( $max + $min ) / 2;
	$d = $max - $min;

    	if( $d == 0 ){
        	$h = $s = 0; // achromatic
    	} else {
        	$s = $d / ( 1 - abs( 2 * $l - 1 ) );

		switch( $max ){
	            case $r:
	            	$h = 60 * fmod( ( ( $g - $b ) / $d ), 6 ); 
                        if ($b > $g) {
	                    $h += 360;
	                }
	                break;

	            case $g: 
	            	$h = 60 * ( ( $b - $r ) / $d + 2 ); 
	            	break;

	            case $b: 
	            	$h = 60 * ( ( $r - $g ) / $d + 4 ); 
	            	break;
	        }			        	        
	}

	return array( 'h' => round( $h, 3 ), 's' => round( $s, 3 ), 'l' => round( $l, 3 ) );
}

function hslToRgb($color) {

	$h = $color['h'];
	$s = $color['s'];
	$l = $color['l'];

    $r; 
    $g; 
    $b;

	$c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
	$x = $c * ( 1 - abs( fmod( ( $h / 60 ), 2 ) - 1 ) );
	$m = $l - ( $c / 2 );

	if ( $h < 60 ) {
		$r = $c;
		$g = $x;
		$b = 0;
	} else if ( $h < 120 ) {
		$r = $x;
		$g = $c;
		$b = 0;			
	} else if ( $h < 180 ) {
		$r = 0;
		$g = $c;
		$b = $x;					
	} else if ( $h < 240 ) {
		$r = 0;
		$g = $x;
		$b = $c;
	} else if ( $h < 300 ) {
		$r = $x;
		$g = 0;
		$b = $c;
	} else {
		$r = $c;
		$g = 0;
		$b = $x;
	}

	$r = ( $r + $m ) * 255;
	$g = ( $g + $m ) * 255;
	$b = ( $b + $m  ) * 255;

	if($r > 255): $r = 255; endif;
	if($g > 255): $g = 255; endif;
	if($b > 255): $b = 255; endif;

	return array( 'r' => round($r), 'g' => round($g), 'b' => round($b) );
}

function darkenHsl($color, $amount = 1.1) {

	$h = $color['h'];
	$s = $color['s'];
	$l = $color['l'];

	$l = $l / $amount;

	return array( 'h' => round( $h, 3 ), 's' => round( $s, 3 ), 'l' => round( $l, 3 ) );
}

function lightenHsl($color, $amount = 1.1) {

	$h = $color['h'];
	$s = $color['s'];
	$l = $color['l'];

	$l = $l * $amount;

	if($l > 1): $l = 1; endif;

	return array( 'h' => round( $h, 3 ), 's' => round( $s, 3 ), 'l' => round( $l, 3 ) );
}

function saturateHsl($color, $amount = 1.1) {

	$h = $color['h'];
	$s = $color['s'];
	$l = $color['l'];

	$s = $s * $amount;

	if($s > 1): $s = 1; endif;

	return array( 'h' => round( $h, 3 ), 's' => round( $s, 3 ), 'l' => round( $l, 3 ) );
}

function rgbToHex($color) {

	$r = $color['r'];
	$g = $color['g'];
	$b = $color['b'];

    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

/**
 * Calculate the Euclidean distance between two colors in RGB space.
 *
 * @param array $color1 Array with keys 'r', 'g', 'b'.
 * @param array $color2 Array with keys 'r', 'g', 'b'.
 * @return float Distance between the two colors.
 */
function colorDistance($color1, $color2) {
    return sqrt(
        pow($color1['r'] - $color2['r'], 2) +
        pow($color1['g'] - $color2['g'], 2) +
        pow($color1['b'] - $color2['b'], 2)
    );
}

/**
 * Check if a color is too close to white.
 *
 * @param string $hexColor Hex color string.
 * @param float $threshold Distance threshold.
 * @return bool True if the color is too close to white, false otherwise.
 */
function isColorTooCloseToWhite($hexColor, $threshold = 30.0) {
    $whiteRgb = ['r' => 255, 'g' => 255, 'b' => 255];
    $colorRgb = hexToRgb($hexColor);

    $distance = colorDistance($colorRgb, $whiteRgb);

    return $distance < $threshold;
}

function get_next_post_id() {
    global $post;

    $next_post = get_next_post();
	if (!empty( $next_post )): 
		$post_id = $next_post->ID;
	else:
		$post_type = get_post_type();
		$query = get_posts( 'post_type="'.$post_type.'"&numberposts=1&order=ASC' );
    	$post_id = $query[0]->ID; 
	endif;

    return $post_id;
}

function get_previous_post_id() {
    global $post;

    $previous_post = get_previous_post();
	if (!empty( $previous_post )): 
		$post_id = $previous_post->ID;
	else:
		$post_type = get_post_type();
		$query = get_posts( 'post_type="'.$post_type.'"&numberposts=1&order=DESC' );
    	$post_id = $query[0]->ID; 
	endif;

    return $post_id;
}

function getMenuIdByLocation($menu_location) {

	$locations = get_nav_menu_locations();

	if (isset($locations[$menu_location])):
	    return $locations[$menu_location];
	else:
		return false;
	endif;
}