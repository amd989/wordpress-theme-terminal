<?php
/**
 * Site logo. Maps to Hugo partials/logo.html.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$logo_text = terminal_get_mod( 'logo_text', '' );
if ( ! $logo_text ) {
	$logo_text = get_bloginfo( 'name' ) ?: 'Terminal';
}
?>
<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="logo"><?php echo esc_html( $logo_text ); ?></div>
</a>
