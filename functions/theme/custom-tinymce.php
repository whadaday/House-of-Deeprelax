<?php

add_filter('acf/format_value/type=wysiwyg', 'format_value_wysiwyg', 10, 3);
function format_value_wysiwyg( $value, $post_id, $field ) { 

	$regexp = '<a href=\"([^\"]*)\" rel="1">(.*)<\/a>';

	  if(preg_match_all("/$regexp/siU", $value, $matches, PREG_SET_ORDER)) {
	    foreach($matches as $match) {
	    	$element = $match[0];
			$href = $match[1]; 
			$text = $match[2]; 
			if($post_id):
				$post_id = url_to_postid($href);
				$excerpt = get_the_excerpt($post_id);
				if($excerpt):
					$link = '<a href="'.$href.'" class="tooltip" data-title="'.$excerpt.'">'.$text.'</a>';
					$value = str_replace($element, $link, $value);
				endif;
			endif;
	    }
	  }

	return $value;
}

/** 
 * Add a 'Add rel="nofollow" to link' checkbox to the WordPress link editor
 *
 * @see https://danielbachhuber.com/tip/rel-nofollow-link-modal/
 */
add_action( 'after_wp_tiny_mce', function(){
	?>
	<script>
		var originalWpLink;
		// Ensure both TinyMCE, underscores and wpLink are initialized
		if ( typeof tinymce !== 'undefined' && typeof _ !== 'undefined' && typeof wpLink !== 'undefined' ) {
			// Ensure the #link-options div is present, because it's where we're appending our checkbox.
			if ( tinymce.$('#link-options').length ) {
				// Append our checkbox HTML to the #link-options div, which is already present in the DOM.
				tinymce.$('#link-options').append(<?php echo json_encode( '<div class="link-tooltip"><label><span></span><input type="checkbox" id="wp-link-tooltip" /> Add Tooltip to link</label></div>' ); ?>);
				// Clone the original wpLink object so we retain access to some functions.
				originalWpLink = _.clone( wpLink );
				wpLink.addRelNofollow = tinymce.$('#wp-link-tooltip');
				console.log(originalWpLink);
				// Override the original wpLink object to include our custom functions.
				wpLink = _.extend( wpLink, {
					/**
					 * Fetch attributes for the generated link based on
					 * the link editor form properties.
					 *
					 * In this case, we're calling the original getAttrs()
					 * function, and then including our own behavior.
					 */
					getAttrs: function() {
						var attrs = originalWpLink.getAttrs();

						attrs.rel = wpLink.addRelNofollow.prop( 'checked' ) ? '1' : false;
						return attrs;
					},
					/**
					 * Build the link's HTML based on attrs when inserting
					 * into the text editor.
					 *
					 * In this case, we're completely overriding the existing
					 * function.
					 */
					buildHtml: function( attrs ) {
						var html = '<a href="' + attrs.href + '"';

						if ( attrs.target ) {
							html += ' target="' + attrs.target + '"';
						}
						if ( attrs.rel ) {
							html += 'rel="' + attrs.rel + '"';
						}
						return html + '>';
					},
					/**
					 * Set the value of our checkbox based on the presence
					 * of the rel='nofollow' link attribute.
					 *
					 * In this case, we're calling the original mceRefresh()
					 * function, then including our own behavior
					 */
					mceRefresh: function( searchStr, text ) {
						originalWpLink.mceRefresh( searchStr, text );
						var editor = window.tinymce.get( window.wpActiveEditor )
						if ( typeof editor !== 'undefined' && ! editor.isHidden() ) {
							var linkNode = editor.dom.getParent( editor.selection.getNode(), 'a[href]' );
							if ( linkNode ) {
								wpLink.addRelNofollow.prop( 'checked', '1' === editor.dom.getAttrib( linkNode, 'rel' ) );
							}
						}
					}
				});
			}
		}
	</script>
	<style>
	#wp-link .link-tooltip {
		padding: 3px 0 0;
	}
	#wp-link .link-tooltip label {
		max-width: 70%;
	}
	#wp-link .link-tooltip input {
		margin-left: 3px;
	} {
		max-width: 70%;
	}
	</style>
	<?php
});