<?php if ( function_exists('yoast_breadcrumb')  ): ?>
	
	<div class="columns columns-breadcrumbs">
		<div class="column">
			<div class="content">
				<p id="breadcrumbs">
					<?php yoast_breadcrumb(); ?>
				</p>
			</div>
		</div>
	</div>
	
<?php elseif( function_exists('rank_math_the_breadcrumbs') ): ?>

	<div class="columns columns-breadcrumbs">
		<div class="column">
			<div class="content breadcrumbs">
				<?php rank_math_the_breadcrumbs(); ?>
			</div>
		</div>
	</div>

<?php endif; ?>