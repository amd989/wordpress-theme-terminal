<?php
/**
 * Language switcher. No-ops if Polylang is not installed — content
 * translation is deliberately out of scope for this theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'pll_the_languages' ) ) {
	return;
}

$langs = pll_the_languages(
	array(
		'raw'       => 1,
		'hide_if_no_translation' => 0,
	)
);

if ( empty( $langs ) ) {
	return;
}

$current = '';
foreach ( $langs as $l ) {
	if ( ! empty( $l['current_lang'] ) ) {
		$current = $l['name'];
		break;
	}
}
?>
<ul class="menu menu--desktop menu--language-selector">
	<li class="menu__trigger"><?php echo esc_html( $current ); ?>&nbsp;▾</li>
	<li>
		<ul class="menu__dropdown">
			<?php foreach ( $langs as $l ) : ?>
				<li><a href="<?php echo esc_url( $l['url'] ); ?>"><?php echo esc_html( $l['name'] ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</li>
</ul>
