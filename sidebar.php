<?php
/**
 * The sidebar containing the main widget area.
 *
 *
 * @package mhesn
 */


?>

<aside id="secondary" class="widget-area sidebar">

<?php if ( is_active_sidebar( 'sidebar-1' ) ) { ?>

	<?php dynamic_sidebar( 'sidebar-1' ); ?>

<?php } else { ?>

<?php } ?>

</aside><!-- #secondary -->

