<?php
/**
 * Post cover image. Maps to Hugo partials/cover.html.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cover = terminal_cover_url();
if ( ! $cover ) {
	return;
}

$title  = get_the_title();
$credit = terminal_cover_credit();
?>
<img src="<?php echo esc_url( $cover ); ?>"
	class="post-cover"
	alt="<?php echo esc_attr( $title ); ?>"
	title="<?php echo esc_attr( $credit ); ?>" />
