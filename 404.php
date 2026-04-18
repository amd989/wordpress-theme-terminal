<?php
/**
 * 404 Not Found. Maps to Hugo layouts/404.html.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$message = terminal_get_mod( 'missing_content', __( 'Page not found...', 'terminal' ) );
$back    = terminal_get_mod( 'missing_back_button', __( 'Back to home page', 'terminal' ) );
?>

<div class="post">
	<h1 class="post-title"><?php echo esc_html__( '404 — ', 'terminal' ) . esc_html( $message ); ?></h1>
	<div class="post-content">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $back ); ?>&nbsp;→</a>
	</div>
</div>

<?php
get_footer();
