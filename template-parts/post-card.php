<?php
/**
 * Post preview card for archive / list views. Maps to the `.on-list` post
 * rendering inside Hugo layouts/_default/list.html.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_full = '1' === get_post_meta( get_the_ID(), '_terminal_show_full_content', true );
$read_more = terminal_get_mod( 'read_more', __( 'Read more', 'terminal' ) );
?>
<article <?php post_class( 'post on-list' ); ?>>
	<h2 class="post-title">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h2>

	<div class="post-meta">
		<time class="post-date"><?php terminal_the_post_date(); ?></time>
		<?php terminal_the_post_author(); ?>
	</div>

	<?php terminal_the_post_tags(); ?>
	<?php get_template_part( 'template-parts/post-cover' ); ?>

	<div class="post-content">
		<?php
		if ( $show_full ) {
			the_content();
		} elseif ( has_excerpt() ) {
			printf( '<p>%s</p>', esc_html( get_the_excerpt() ) );
		} else {
			the_excerpt();
		}
		?>
	</div>

	<?php if ( ! $show_full ) : ?>
		<div>
			<a class="read-more button inline" href="<?php the_permalink(); ?>">[<?php echo esc_html( $read_more ); ?>]</a>
		</div>
	<?php endif; ?>
</article>
