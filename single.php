<?php
/**
 * The template for displaying all single posts.
 *
 *
 * @package mhesn
 */

get_header(); 
?>

	<div id="primary" class="content-area">

		<main id="main" class="site-main">

		<div class="breadcrumbs">
			<span class="breadcrumbs-nav">
				<a href="<?php echo esc_url(home_url()); ?>"><?php esc_html_e('Home', 'mhesn'); ?></a>
				<span class="post-category"><?php mhesn_first_category(); ?></span>
				<span class="post-title"><?php the_title(); ?></span>
			</span>
		</div>
		
		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'single' );

			// the_post_navigation();

			// If comments are open or we have at least one comment, load up the comment template.
			if ( ( comments_open() || get_comments_number() ) && is_singular('post') ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
