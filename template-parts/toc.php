<?php
/**
 * Table of contents. Maps to the TOC block in Hugo layouts/_default/single.html.
 *
 * Expects the pre-filtered post content (already run through
 * terminal_inject_heading_anchors) to be passed as $args['content'], so that
 * heading IDs are guaranteed to exist before we scan for them.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$content = isset( $args['content'] ) ? $args['content'] : '';
$toc     = terminal_toc_from_content( $content );
if ( ! $toc ) {
	return;
}
$title = terminal_get_mod( 'toc_title', __( 'Table of Contents', 'terminal' ) );
?>
<div class="table-of-contents">
	<h2><?php echo esc_html( $title ); ?></h2>
	<?php echo $toc; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
