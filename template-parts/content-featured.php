<?php		
	$args = array( 
	    'posts_per_page' => 5,
		'ignore_sticky_posts' => 1,
		'post__not_in' => get_option( 'sticky_posts' ),		    
		'meta_query' => array(
                array(
                'key' => 'mhesn-featured',
                'value' => 'yes'
        	)
    	)
	); 

	$args2 = array( 
	    'posts_per_page' => 5
	);  

	// The Query
	$the_query = new WP_Query( $args );

	$i = 1;

	if ( $the_query->have_posts() && (!get_query_var('paged')) ) {	
?>

	<div id="featured-content" class="clear">

	<?php
		while ( $the_query->have_posts() ) : $the_query->the_post();
	?>	

	<?php if ($i == 1) { ?>

	<div class="featured-large hentry clear">

		<div class="thumbnail-wrap">
			<a class="thumbnail-link" href="<?php the_permalink(); ?>">					
				<?php 
				if ( has_post_thumbnail() ) {
					the_post_thumbnail('mhesn_featured_large_thumb');  
				} else {
					echo '<img src="' . esc_url( get_template_directory_uri() ) . '/assets/img/featured-large-thumb.png" alt="" />';
				}
				?>
			</a>					
		</div><!-- .thumbnail-wrap -->

		<div class="entry-header">	
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>		
		</div><!-- .entry-header -->

		<div class="entry-summary">
			<?php echo esc_html( wp_trim_words(get_the_excerpt(), '16') ); ?>
		</div><!-- .entry-summary -->

	</div><!-- .featured-large -->			

	<?php } else { ?>

	<?php
		if ( ($i == 2) && ( $the_query->current_post + 1 !== $the_query->post_count ) ) {
			echo '<div class="featured-right">';
		}		
	?>

	<div class="featured-small hentry">

		<div class="thumbnail-wrap">
			<a class="thumbnail-link" href="<?php the_permalink(); ?>">					
				<?php 
				if ( has_post_thumbnail() ) {
					the_post_thumbnail('mhesn_post_thumb');  
				} else {
					echo '<img src="' . esc_url( get_template_directory_uri() ) . '/assets/img/featured-large-thumb.png" alt="" />';
				}
				?>
			</a>					
		</div><!-- .thumbnail-wrap -->

		<div class="entry-header">	
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>	
		</div><!-- .entry-header -->	

	</div><!-- .featured-small -->

	<?php
		if ( ($i > 1) && ( $the_query->current_post + 1 === $the_query->post_count ) ) {
			echo '</div><!-- .featured-right -->';
		}
	?>

	<?php

		}
		$i++;
		endwhile;
	?>

	</div><!-- #featured-content -->

<?php
	}
	wp_reset_postdata();	
?>
	