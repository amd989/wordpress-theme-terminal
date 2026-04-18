<?php
/**
 * Mobile navigation dropdown. Maps to Hugo partials/mobile-menu.html.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locations = get_nav_menu_locations();
if ( empty( $locations['main'] ) ) {
	return;
}
$menu = wp_get_nav_menu_object( $locations['main'] );
if ( ! $menu ) {
	return;
}
$items = wp_get_nav_menu_items( $menu->term_id );
if ( empty( $items ) ) {
	return;
}
$top_items = array_values(
	array_filter(
		$items,
		function ( $item ) {
			return 0 === (int) $item->menu_item_parent;
		}
	)
);
?>
<ul class="menu menu--mobile">
	<li class="menu__trigger"><?php esc_html_e( 'Menu', 'terminal' ); ?>&nbsp;▾</li>
	<li>
		<ul class="menu__dropdown">
			<?php foreach ( $top_items as $item ) : ?>
				<li><a href="<?php echo esc_url( $item->url ); ?>"><?php echo esc_html( $item->title ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</li>
</ul>
