<?php get_header(); ?>

	<?php 
		get_template_part('template-parts/content','featured');
	?>

	<div class="home-container">

		<div id="primary" class="content-area <?php if ( is_active_sidebar( 'home' ) ) { echo 'full-width'; } ?> clear">

			<main id="main" class="site-main clear">

				<?php if ( is_active_sidebar( 'home' ) ) { ?>

				<div id="recent-content">

					<?php dynamic_sidebar( 'home' ); ?>

				</div><!-- #recent-content -->	

				<?php } else { ?>

				<div id="recent-content" class="content-list clear">

					<?php

					if ( have_posts() ) :	
					
					$i = 1;		
						
					/* Start the Loop */
					while ( have_posts() ) : the_post();

						get_template_part('template-parts/content', 'list');

						$i++;

					endwhile;

					else :

						get_template_part( 'template-parts/content', 'none' );

					endif; 

					?>

				</div><!-- #recent-content -->
				
				<?php get_template_part( 'template-parts/pagination', '' ); ?>

				<?php } ?>							

			</main><!-- .site-main -->

		</div><!-- #primary -->

		<?php 
			if ( !is_active_sidebar( 'home' ) ) { 
				get_sidebar();
			}
		?>

	</div><!-- .home-container -->

<?php get_footer(); ?>
