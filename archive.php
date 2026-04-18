<?php
/**
 * Archive: category, tag, date, author. Maps to Hugo layouts/_default/term.html.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php if ( is_tag() ) : ?>
	<h1><?php printf( esc_html__( 'Posts for: #%s', 'terminal' ), esc_html( single_tag_title( '', false ) ) ); ?></h1>
<?php elseif ( is_category() ) : ?>
	<h1><?php printf( esc_html__( 'Posts in: %s', 'terminal' ), esc_html( single_cat_title( '', false ) ) ); ?></h1>
<?php elseif ( is_author() ) : ?>
	<h1><?php printf( esc_html__( 'Posts by %s', 'terminal' ), esc_html( get_the_author() ) ); ?></h1>
<?php else : ?>
	<h1><?php the_archive_title(); ?></h1>
<?php endif; ?>

<?php
$description = get_the_archive_description();
if ( $description ) :
	?>
	<div class="index-content"><?php echo wp_kses_post( $description ); ?></div>
<?php endif; ?>

<div class="posts">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template-parts/post-card' ); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No posts found.', 'terminal' ); ?></p>
	<?php endif; ?>

	<?php terminal_pagination(); ?>
</div>

<?php
get_footer();
