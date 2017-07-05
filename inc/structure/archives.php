<?php
defined( 'WPINC' ) or die;

if ( ! function_exists( 'generate_archive_title' ) ) :
/**
 * Build the archive title
 *
 * @since 1.3.24
 */
add_action( 'generate_archive_title','generate_archive_title' );
function generate_archive_title() {
	if ( ! function_exists( 'the_archive_title' ) ) {
		return;
	}
	?>
	<header class="page-header<?php if ( is_author() ) echo ' clearfix';?>">
		<?php do_action( 'generate_before_archive_title' ); ?>
		<h1 class="page-title">
			<?php the_archive_title(); ?>
		</h1>
		<?php do_action( 'generate_after_archive_title' ); ?>
		<?php
			// Show an optional term description.
			$term_description = term_description();
			if ( ! empty( $term_description ) ) :
				printf( '<div class="taxonomy-description">%s</div>', $term_description );
			endif;
			
			if ( get_the_author_meta('description') && is_author() ) : // If a user has filled out their decscription show a bio on their entries
				echo '<div class="author-info">' . get_the_author_meta('description') . '</div>';
			endif;
		?>
		<?php do_action( 'generate_after_archive_description' ); ?>
	</header><!-- .page-header -->
	<?php
}
endif;

if ( ! function_exists( 'generate_filter_the_archive_title' ) ) :
/**
 * Alter the_archive_title() function to match our original archive title function
 *
 * @since 1.3.45
 */
add_filter( 'get_the_archive_title','generate_filter_the_archive_title' );
function generate_filter_the_archive_title( $title ) {
	if ( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_author() ) {
		/* Queue the first post, that way we know
		 * what author we're dealing with (if that is the case).
		 */
		the_post();
		$title = sprintf( '%1$s<span class="vcard">%2$s</span>',
			get_avatar( get_the_author_meta( 'ID' ), 75 ),
			get_the_author()
		);
		/* Since we called the_post() above, we need to
		 * rewind the loop back to the beginning that way
		 * we can run the loop properly, in full.
		 */
		rewind_posts();
	}

	return $title;

}
endif;