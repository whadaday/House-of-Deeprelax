			</main>
			<?php 
				include( locate_template( 'partials/general/page-cta.php', false, false ) );
				include( locate_template( 'partials/general/page-instagram.php', false, false ) );
				include( locate_template( 'partials/general/page-footer.php', false, false ) );
			?>
			
		</div> <?php /* #wrapper */ ?>
		<?php wp_footer(); ?>
		<?php
			if(is_page(6332)):
				?>
					<script id="sleakbot" src="https://cdn.sleak.chat/fetchsleakbot.js" chatbot-id="afca85ce-8c32-4ee2-8081-ec1f85135444" ></script>
				<?php
			endif;
		?>
    </body>
</html>