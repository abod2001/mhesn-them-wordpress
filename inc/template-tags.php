<?php
/**
 * Custom template tags for this theme.
 *
 *
 * @package mhesn
 */

/**
 * Adds a meta box to the post editing screen
 */
function mhesn_featured_meta() {
    add_meta_box( 'mhesn_meta', __( 'Featured Post', 'mhesn' ), 'mhesn_meta_callback', 'post', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mhesn_featured_meta' );
 
/**
 * Outputs the content of the meta box
 */
 
function mhesn_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'mhesn_nonce' );
    $mhesn_stored_meta = get_post_meta( $post->ID );
    ?>
 
 <p>
    <span class="mhesn-row-title"><?php esc_html_e( 'Display post on homepage featured area?', 'mhesn' )?></span>
    <div class="mhesn-row-content">
        <label for="mhesn-featured">
            <input type="checkbox" name="mhesn-featured" id="mhesn-featured" value="yes" <?php if ( isset ( $mhesn_stored_meta['mhesn-featured'] ) ) checked( $mhesn_stored_meta['mhesn-featured'][0], 'yes' ); ?> />
            <?php esc_html_e( 'Featured Post', 'mhesn' )?>
        </label>
 
    </div>
</p>   
 
    <?php
}
 
/**
 * Saves the custom meta input
 */
function mhesn_meta_save( $post_id ) {
 
    // Checks save status - overcome autosave, etc.
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'mhesn_nonce' ] ) && wp_verify_nonce( sanitize_text_field( wp_unslash($_POST[ 'mhesn_nonce' ], basename( __FILE__ ) ) ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
// Checks for input and saves - save checked as yes and unchecked at no
if( isset( $_POST[ 'mhesn-featured' ] ) ) {
    update_post_meta( $post_id, 'mhesn-featured', 'yes' );
} else {
    update_post_meta( $post_id, 'mhesn-featured', 'no' );
}
 
}
add_action( 'save_post', 'mhesn_meta_save' );

/**
 * Search Filter 
 */
if ( ! function_exists( 'mhesn_search_filter' ) && ( ! is_admin() ) ) :

function mhesn_search_filter($query) {
    if ($query->is_search) {
        $query->set('post_type', 'post');
    }
    return $query;
}

add_filter('pre_get_posts','mhesn_search_filter');

endif;

/**
 * Filter the except length to 40 characters.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
if ( ! function_exists( 'mhesn_custom_excerpt_length' ) ) :

function mhesn_custom_excerpt_length( $length ) {
    if ( is_admin() ) {
        return $length;
    } else {
       return '40'; 
    }
}
add_filter( 'excerpt_length', 'mhesn_custom_excerpt_length', 999 );

endif;

/**
 * Customize excerpt more.
 */
if ( ! function_exists( 'mhesn_excerpt_more' ) ) :

function mhesn_excerpt_more( $more ) {
    if ( is_admin() ) {
        return $more;
    } else {
        return '... ';
    }
}
add_filter( 'excerpt_more', 'mhesn_excerpt_more' );

endif;

/**
 * Display the first (single) category of post.
 */
if ( ! function_exists( 'mhesn_first_category' ) ) :
function mhesn_first_category() {
    $category = get_the_category();
    if ($category) {
      echo '<a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '">' . esc_html( $category[0]->name ) .'</a> ';
    }    
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
if ( ! function_exists( 'mhesn_categorized_blog' ) ) :

function mhesn_categorized_blog() {
    if ( false === ( $all_the_cool_cats = get_transient( 'mhesn_categories' ) ) ) {
        // Create an array of all the categories that are attached to posts.
        $all_the_cool_cats = get_categories( array(
            'fields'     => 'ids',
            'hide_empty' => 1,
            // We only need to know if there is more than one category.
            'number'     => 2,
        ) );

        // Count the number of categories that are attached to the posts.
        $all_the_cool_cats = count( $all_the_cool_cats );

        set_transient( 'mhesn_categories', $all_the_cool_cats );
    }

    if ( $all_the_cool_cats > 1 ) {
        // This blog has more than 1 category so mhesn_categorized_blog should return true.
        return true;
    } else {
        // This blog has only 1 category so mhesn_categorized_blog should return false.
        return false;
    }
}

endif;

/**
 * Flush out the transients used in mhesn_categorized_blog.
 */
if ( ! function_exists( 'mhesn_category_transient_flusher' ) ) :

function mhesn_category_transient_flusher() {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient( 'mhesn_categories' );
}
add_action( 'edit_category', 'mhesn_category_transient_flusher' );
add_action( 'save_post',     'mhesn_category_transient_flusher' );

endif;


/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function mhesn_skip_link_focus_fix() {
    // The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
    ?>
    <script>
    /(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
    </script>
    <?php
}
add_action( 'wp_print_footer_scripts', 'mhesn_skip_link_focus_fix' );

/**
 * Twenty Twenty SVG Icon helper functions
 *
 * @package mhesn
 * @subpackage mhesn
 * @since mhesn 1.0
 */

if ( ! function_exists( 'mhesn_the_theme_svg' ) ) {
    /**
     * Output and Get Theme SVG.
     * Output and get the SVG markup for an icon in the mhesn_SVG_Icons class.
     *
     * @param string $svg_name The name of the icon.
     * @param string $group The group the icon belongs to.
     * @param string $color Color code.
     */
    function mhesn_the_theme_svg( $svg_name, $group = 'ui', $color = '' ) {
        echo mhesn_get_theme_svg( $svg_name, $group, $color ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in mhesn_get_theme_svg().
    }
}

if ( ! function_exists( 'mhesn_get_theme_svg' ) ) {

    /**
     * Get information about the SVG icon.
     *
     * @param string $svg_name The name of the icon.
     * @param string $group The group the icon belongs to.
     * @param string $color Color code.
     */
    function mhesn_get_theme_svg( $svg_name, $group = 'ui', $color = '' ) {

        // Make sure that only our allowed tags and attributes are included.
        $svg = wp_kses(
            mhesn_SVG_Icons::get_svg( $svg_name, $group, $color ),
            array(
                'svg'     => array(
                    'class'       => true,
                    'xmlns'       => true,
                    'width'       => true,
                    'height'      => true,
                    'viewbox'     => true,
                    'aria-hidden' => true,
                    'role'        => true,
                    'focusable'   => true,
                ),
                'path'    => array(
                    'fill'      => true,
                    'fill-rule' => true,
                    'd'         => true,
                    'transform' => true,
                ),
                'polygon' => array(
                    'fill'      => true,
                    'fill-rule' => true,
                    'points'    => true,
                    'transform' => true,
                    'focusable' => true,
                ),
            )
        );

        if ( ! $svg ) {
            return false;
        }
        return $svg;
    }
}


/**
 * Miscellaneous
 */

/**
 * Toggles animation duration in milliseconds.
 *
 * @return int Duration in milliseconds
 */
function mhesn_toggle_duration() {
    /**
     * Filters the animation duration/speed used usually for submenu toggles.
     *
     * @since mhesn 1.0
     *
     * @param int $duration Duration in milliseconds.
     */
    $duration = apply_filters( 'mhesn_toggle_duration', 250 );

    return $duration;
}


/**
 * Menus
 */

/**
 * Filters classes of wp_list_pages items to match menu items.
 *
 * Filter the class applied to wp_list_pages() items with children to match the menu class, to simplify.
 * styling of sub levels in the fallback. Only applied if the match_menu_classes argument is set.
 *
 * @param string[] $css_class    An array of CSS classes to be applied to each list item.
 * @param WP_Post  $page         Page data object.
 * @param int      $depth        Depth of page, used for padding.
 * @param array    $args         An array of arguments.
 * @param int      $current_page ID of the current page.
 * @return array CSS class names.
 */
function mhesn_filter_wp_list_pages_item_classes( $css_class, $page, $depth, $args, $current_page ) {

    // Only apply to wp_list_pages() calls with match_menu_classes set to true.
    $match_menu_classes = isset( $args['match_menu_classes'] );

    if ( ! $match_menu_classes ) {
        return $css_class;
    }

    // Add current menu item class.
    if ( in_array( 'current_page_item', $css_class, true ) ) {
        $css_class[] = 'current-menu-item';
    }

    // Add menu item has children class.
    if ( in_array( 'page_item_has_children', $css_class, true ) ) {
        $css_class[] = 'menu-item-has-children';
    }

    return $css_class;

}

add_filter( 'mhesn_page_css_class', 'mhesn_filter_wp_list_pages_item_classes', 10, 5 );

/**
 * Adds a Sub Nav Toggle to the Expanded Menu and Mobile Menu.
 *
 * @param stdClass $args  An object of wp_nav_menu() arguments.
 * @param WP_Post  $item  Menu item data object.
 * @param int      $depth Depth of menu item. Used for padding.
 * @return stdClass An object of wp_nav_menu() arguments.
 */
function mhesn_add_sub_toggles_to_main_menu( $args, $item, $depth ) {

    // Add sub menu toggles to the Expanded Menu with toggles.
    if ( isset( $args->show_toggles ) && $args->show_toggles ) {

        // Wrap the menu item link contents in a div, used for positioning.
        $args->before = '<div class="ancestor-wrapper">';
        $args->after  = '';

        // Add a toggle to items with children.
        if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {

            $toggle_target_string = '.menu-modal .menu-item-' . $item->ID . ' > .sub-menu';
            $toggle_duration      = mhesn_toggle_duration();

            // Add the sub menu toggle.
            $args->after .= '<button class="toggle sub-menu-toggle fill-children-current-color" data-toggle-target="' . $toggle_target_string . '" data-toggle-type="slidetoggle" data-toggle-duration="' . absint( $toggle_duration ) . '" aria-expanded="false"><span class="screen-reader-text">' . __( 'Show sub menu', 'mhesn' ) . '</span>' . mhesn_get_theme_svg( 'chevron-down' ) . '</button>';

        }

        // Close the wrapper.
        $args->after .= '</div><!-- .ancestor-wrapper -->';

        // Add sub menu icons to the primary menu without toggles.
    } elseif ( 'primary' === $args->theme_location ) {
        if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
            $args->after = '<span class="icon"></span>';
        } else {
            $args->after = '';
        }
    }

    return $args;

}

add_filter( 'nav_menu_item_args', 'mhesn_add_sub_toggles_to_main_menu', 10, 3 );

/*
 * Customize archive title
 */
add_filter( 'get_the_archive_title', function ($title) {    
    if ( is_category() ) {    
            $title = single_cat_title( '', false );    
        } elseif ( is_tag() ) {    
            $title = single_tag_title( '', false );    
        } elseif ( is_author() ) {    
            $title = '<span class="vcard">' . get_the_author() . '</span>' ;    
        } elseif ( is_tax() ) {
            $title = single_term_title( '', false );
        } elseif (is_post_type_archive()) {
            $title = post_type_archive_title( '', false );
        }
    return $title;    
});

/*
 * Admin Notice
 */
function mhesn_notice() {
    
    global $current_user;
    
    $theme = wp_get_theme();

    $user_id = $current_user->ID;
    
   
    
}
add_action('admin_notices', 'mhesn_notice');

/*
 * Admin Notice Ignore
 */    
function mhesn_notice_ignore() {
    
    global $current_user;
    
    $user_id = $current_user->ID;
    
    if (isset($_GET['mhesn-ignore-notice'])) {
        
        add_user_meta($user_id, 'mhesn_notice_ignore', 'true', true);
        
    }
    
}
add_action('admin_init', 'mhesn_notice_ignore');

/* Add Menu Link to Admin Bar */
function mhesn_custom_toolbar_link($wp_admin_bar) {
    $args = array(
        'id' => 'mhesn-pro',
        'title' => esc_html(''), 
        'href' => esc_url( wp_get_theme()->get( 'AuthorURI' ) . '/themes/mhesn-pro' ), 
        'meta' => array(
            'target'=> '_blank',
        )
    );
    $wp_admin_bar->add_node($args);
}
add_action('admin_bar_menu', 'mhesn_custom_toolbar_link', 999);

/* Admin Style */
function mhesn_admin_init() {
    if ( is_admin() ) {
        wp_enqueue_style("mhesn-admin-css", get_template_directory_uri() . "/assets/css/admin-style.css", false, "1.0", "all");
    }
}
add_action( 'admin_enqueue_scripts', 'mhesn_admin_init' );