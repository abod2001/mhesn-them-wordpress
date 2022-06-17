<?php
/**
 * Home content widget.
 *
 * @package    mhesn
 * @author     Mhesn
 * @copyright  Copyright (c) 2022, Mhesn
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */
class mhesn_Home_Content_2_Widget extends WP_Widget {

	/**
	 * Sets up the widgets.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		// Set up the widget options.
		$widget_options = array(
			'classname'   => 'widget-mhesn-home-block-two',
			'description' => __( 'Display post content blocks. Only use for the "Home Content" widget area.', 'mhesn' )
		);

		// Create the widget.
		parent::__construct(
			'mhesn-home-block-two',          // $this->id_base
			__( '&raquo; Home Content 2', 'mhesn' ), // $this->name
			$widget_options                    // $this->widget_options
		);
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @since 1.0.0
	 */
	function widget( $args, $instance ) {

		// Default value.
		$defaults = array(
			'title' => '',
			'limit' => 2,
			'cat'   => ''
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		extract( $args );

		// Output the theme's $before_widget wrapper.
		echo wp_kses_post( $before_widget );

			// Theme prefix
			$prefix = 'mhesn-';

			// Pull the selected category.
			$cat_id = isset( $instance['cat'] ) ? absint( $instance['cat'] ) : 0;

			// Get the category.
			$category = get_category( $cat_id );

			// Get the category archive link.
			$cat_link = get_category_link( $cat_id );

			// Posts query arguments.
			$args = array(
				'post_type'      => 'post',
				'ignore_sticky_posts' => 1,
				'post__not_in' => get_option( 'sticky_posts' ),					
				'posts_per_page' => ( ! empty( $instance['limit'] ) ) ? absint( $instance['limit'] ) : 6
			);

			// Limit to category based on user selected tag.
			if ( ! $cat_id == 0 ) {
				$args['cat'] = $cat_id;
			}

			// Allow dev to filter the post arguments.
			$query = apply_filters( 'mhesn_home_one_column_args', $args );

			// The post query.
			$posts = new WP_Query( $query );

			$i = 1;

			if ( $posts->have_posts() ) : ?>

				<div class="content-block content-block-2 clear">

					<div class="section-heading">

					<?php
						if ( ( ! empty( $instance['title'] ) ) && ( $cat_id != 0 ) ) {

							echo '<h3 class="section-title"><a href="' . esc_url( $cat_link ) . '">' . wp_kses_post( apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) . '</a></h3>';

							$term_description = term_description( $cat_id );
							if ( ! empty( $term_description ) ) {
								printf( '<span class="taxonomy-description">%s</span>', wp_kses_post($term_description) );
							}

							echo '<span class="section-more-link"><a href="' . esc_url( $cat_link ) . '">'. esc_html( 'More', 'mhesn' ) . '</a></span>';

						} elseif ( $cat_id == 0 ) {

							echo '<h3 class="section-title"><span>' . wp_kses_post( apply_filters( 'widget_title',  esc_html( 'Latest Posts', 'mhesn' ), $instance, $this->id_base ) ) . '</span></h3>';

						} else {

							echo '<h3 class="section-title"><a href="' . esc_url( $cat_link ) . '">' . wp_kses_post( apply_filters( 'widget_title', esc_attr( $category->name ), $instance, $this->id_base ) ) . '</a></h3>';

							$term_description = term_description( $cat_id );
							if ( ! empty( $term_description ) ) {
								printf( '<span class="taxonomy-description">%s</span>', wp_kses_post($term_description) );
							}

							echo '<span class="section-more-link"><a href="' . esc_url( $cat_link ) . '">'. esc_html( 'More', 'mhesn' ) . '</a></span>';

						}
					?>

					</div><!-- .section-heading -->			

					<div class="posts-loop clear">

					<?php 
						while ( $posts->have_posts() ) : $posts->the_post(); 
					?>

					<div <?php post_class('ht_grid_1_2 ht_grid_m_1_2'); ?>>

						<?php if ( has_post_thumbnail() ) { ?>
							<a class="thumbnail-link" href="<?php the_permalink(); ?>">
								<div class="thumbnail-wrap">
									<?php 
										the_post_thumbnail('mhesn_featured_large_thumb');  
									?>
								</div><!-- .thumbnail-wrap -->										
							</a>
						<?php } ?>

						<div class="entry-header">
							<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>					
						</div><!-- .entry-header -->

						<div class="entry-summary">
							<?php echo esc_html( wp_trim_words(get_the_excerpt(), '16') ); ?>
						</div><!-- .entry-summary -->

					</div><!-- .hentry -->

					<?php 
						$i++;
						endwhile; 
					?>
					</div><!-- .posts-loop -->

				</div><!-- .content-block-2 -->

			<?php endif;

			// Restore original Post Data.
			wp_reset_postdata();

		// Close the theme's widget wrapper.
		echo wp_kses_post( $after_widget );

	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @since 1.0.0
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $new_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['limit'] = (int) $new_instance['limit'];
		$instance['cat']   = (int) $new_instance['cat'];

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 1.0.0
	 */
	function form( $instance ) {

		// Default value.
		$defaults = array(
			'title' => '',
			'limit' => 2,
			'cat'   => ''
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
	?>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'mhesn' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'cat' ) ); ?>"><?php esc_html_e( 'Choose category', 'mhesn' ); ?></label>
			<select class="widefat" id="<?php echo esc_html( $this->get_field_id( 'cat' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'cat' ) ); ?>" style="width:100%;">
				<?php $categories = get_terms( 'category' ); ?>
				<option value="0"><?php esc_html_e( 'All categories &hellip;', 'mhesn' ); ?></option>
				<?php foreach( $categories as $category ) { ?>
					<option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $instance['cat'], $category->term_id ); ?>><?php echo esc_html( $category->name ); ?></option>
				<?php } ?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'limit' ) ); ?>">
				<?php esc_html_e( 'Number of posts to show', 'mhesn' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'limit' ) ); ?>" type="number" step="1" min="0" value="<?php echo (int)( $instance['limit'] ); ?>" />
		</p>		

	<?php

	}

}