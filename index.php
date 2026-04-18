<?php
/**
 * Fallback archive. Used as the safety net when no more-specific template
 * exists. Maps to Hugo layouts/_default/list.html.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="posts">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template-parts/post-card' ); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No posts yet.', 'terminal' ); ?></p>
	<?php endif; ?>

	<?php terminal_pagination(); ?>
</div>

<?php
get_footer();
