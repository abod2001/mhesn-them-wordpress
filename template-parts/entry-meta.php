<div class="entry-meta">
	
	<?php if (is_single()) { ?>
		<span class="entry-category"><?php mhesn_first_category(); ?></span>
		<span class="entry-author"><?php esc_url( the_author_posts_link() ); ?> &#8212; </span> 		
	<?php } ?>

	<?php if ( (!is_category()) && (!is_single()) ) { ?>
		<span class="entry-category"><?php mhesn_first_category(); ?></span>
	<?php } ?>

	<span class="entry-date"><?php echo get_the_date(); ?></span>
	<span class="sep">&middot;</span>
	<span class='entry-comment'><?php comments_popup_link( __('0 Comment','mhesn'), __('1 Comment','mhesn'), __('% Comments','mhesn'), 'comments-link', __('Comments off','mhesn')); ?></span>
	
</div><!-- .entry-meta -->