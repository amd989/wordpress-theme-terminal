<?php
/**
 * Static page. Reuses single.php layout but omits post meta and navigation.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$raw_content      = get_the_content();
	$filtered_content = apply_filters( 'the_content', $raw_content );
	?>
	<article <?php post_class( 'post' ); ?>>
		<h1 class="post-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h1>

		<?php get_template_part( 'template-parts/post-cover' ); ?>

		<?php
		if ( terminal_should_show_toc() ) {
			get_template_part( 'template-parts/toc', null, array( 'content' => $filtered_content ) );
		}
		?>

		<div class="post-content">
			<div><?php echo $filtered_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>

		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	</article>
	<?php
endwhile;

get_footer();
