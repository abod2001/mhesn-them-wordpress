<?php
/**
 * Random Posts with Thumbnail widget.
 *
 * @package    mhesn
 * @author     Mhesn
 * @copyright  Copyright (c) 2022, Mhesn
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */
class mhesn_Random_Widget extends WP_Widget {

	/**
	 * Sets up the widgets.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		// Set up the widget options.
		$widget_options = array(
			'classname'   => 'widget-mhesn-random widget-posts-thumbnail',
			'description' => __( 'Display random posts with thumbnails.', 'mhesn' )
		);

		// Create the widget.
		parent::__construct(
			'mhesn-random',                                   // $this->id_base
			__( '&raquo; Random Posts', 'mhesn' ), // $this->name
			$widget_options                                      // $this->widget_options
		);

		// Flush the transient.
		add_action( 'save_post'   , array( $this, 'flush_widget_transient' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_transient' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_transient' ) );

	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @since 1.0.0
	 */
	function widget( $args, $instance ) {
		extract( $args );

		// Output the theme's $before_widget wrapper.
		echo wp_kses_post( $before_widget );

		// If the title not empty, display it.
		if ( $instance['title'] ) {
			echo wp_kses_post( $before_title ) . wp_kses_post( apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) . wp_kses_post( $after_title );
		}

		// Display the random posts.
		if ( false === ( $random = get_transient( 'mhesn_random_widget_' . $this->id ) ) ) {

			// Posts query arguments.
			$args = array(
				'post_type'      => 'post',
				'posts_per_page' => $instance['limit'],
				'post__not_in' => get_option( 'sticky_posts' ),
				'orderby'        => 'rand'				
			);

			// The post query
			$random = new WP_Query( $args );

			// Store the transient.
			set_transient( 'mhesn_random_widget_' . $this->id, $random );

		}

		global $post;
		if ( $random->have_posts() ) {
			echo '<ul>';

				while ( $random->have_posts() ) : $random->the_post();

					echo '<li class="clear">';
						if ( has_post_thumbnail() ) {

							echo '<a class="thumbnail-link" href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . '<div class="thumbnail-wrap">';
								the_post_thumbnail();  
							echo '</div>' . '</a>';
							
						}
						
						echo '<div class="entry-wrap"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . esc_attr( get_the_title() ) . '</a>'; 

						if ( $instance['show_date'] ) :
							echo '<div class="entry-meta">' . get_the_date() . '</div>';
						endif;
					echo '</div></li>';

				endwhile;

			echo '</ul>';
		}

		// Reset the query.
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
		$instance['title']     = strip_tags( $new_instance['title'] );
		$instance['limit']     = (int) $new_instance['limit'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;

		// Delete our transient.
		$this->flush_widget_transient();

		return $instance;
	}

	/**
	 * Flush the transient.
	 *
	 * @since  1.0.0
	 */
	function flush_widget_transient() {
		delete_transient( 'mhesn_random_widget_' . $this->id );
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 1.0.0
	 */
	function form( $instance ) {

		// Default value.
		$defaults = array(
			'title'     => esc_html__( 'Random Posts', 'mhesn' ),
			'limit'     => 5,
			'show_date' => true
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
	?>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title', 'mhesn' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'limit' ) ); ?>">
				<?php esc_html_e( 'Number of posts to show', 'mhesn' ); ?>
			</label>
			<input class="small-text" id="<?php echo esc_html( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'limit' ) ); ?>" type="number" step="1" min="0" value="<?php echo (int)( $instance['limit'] ); ?>" />
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_date'] ); ?> id="<?php echo esc_html( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'show_date' ) ); ?>" />
			<label for="<?php echo esc_html( $this->get_field_id( 'show_date' ) ); ?>">
				<?php esc_html_e( 'Display post date?', 'mhesn' ); ?>
			</label>
		</p>

	<?php

	}

}
