<?php
/**
 * Search results.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<h1>
	<?php
	printf(
		/* translators: %s: search query */
		esc_html__( 'Search results for: %s', 'terminal' ),
		'<em>' . esc_html( get_search_query() ) . '</em>'
	);
	?>
</h1>

<div class="posts">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template-parts/post-card' ); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No posts matched your search.', 'terminal' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>

	<?php terminal_pagination(); ?>
</div>

<?php
get_footer();
