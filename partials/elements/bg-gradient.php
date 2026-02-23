<?php
	global $blockIndex;
	global $contentBlockIndex;

	$gradient = (isset($options['block-transition']) && $options['block-transition'] == 'gradient') ? true : false;
	if($gradient):
		$gradient 		   = $options['background-gradient'];

		$gradientPosition  = $gradient['position'];
		$gradientSize 	   = $gradient['size'];

		$contentBlocks     = get_field('content');

		// echo '<pre>'; print_r($contentBlocks); echo '</pre><br /><br />';

		$currentBlockIndex = $contentBlockIndex;
		if(isset($headerGradient)): $currentBlockIndex = -1; endif;

		if($gradientPosition == 'top'):
			$currentBlockIndex--;
		else:
			$currentBlockIndex++;
		endif;

		if($currentBlockIndex >= 0):

			$layout = $contentBlocks[$currentBlockIndex]['acf_fc_layout'];

			/* TO FIX -- global blocks */
			if($layout == 'block'):

				$globalContentBlocks = get_field('content', $contentBlocks[$currentBlockIndex]['block']);
		        foreach ($globalContentBlocks as $contentBlock):
		                
		            // Get block name of global block
		            $layout = $contentBlock['acf_fc_layout'];
		            if($layout != 'block'):
		            	$gradientColor     = $contentBlock[$layout]['options']['bg-color']['background-color'];
		            endif;
		          
		       endforeach;
		    else:
		    	$gradientColor = $contentBlocks[$currentBlockIndex][$layout]['options']['bg-color']['background-color'];
			endif;

			
    ?>
	    <div
	    	class="bg-gradient"
	    	data-color="<?php echo $gradientColor; ?>"
	    	data-position="<?php echo $gradientPosition; ?>"
	    	data-size="<?php echo $gradientSize; ?>"
	    >
	    </div>
	<?php endif; ?>
<?php endif; ?>