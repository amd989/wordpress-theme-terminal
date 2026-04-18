<?php
/**
 * Desktop navigation with "More" dropdown. Maps to Hugo partials/menu.html.
 *
 * Reads the `show_menu_items` customizer option: the first N items render
 * inline; the rest collapse under a "More" dropdown. Setting 0 shows all
 * items inline with no dropdown.
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

$top_items = array_filter(
	$items,
	function ( $item ) {
		return 0 === (int) $item->menu_item_parent;
	}
);
$top_items = array_values( $top_items );

$show_n     = (int) terminal_get_mod( 'show_menu_items', 2 );
$more_label = terminal_get_mod( 'menu_more_label', __( 'Show more', 'terminal' ) );

$render_item = function ( $item ) {
	$target = '_self';
	if ( in_array( '_blank', (array) $item->target, true ) ) {
		$target = '_blank';
	}
	$new_tab_attr = '_blank' === $target ? ' target="_blank" rel="noopener"' : '';
	printf(
		'<li><a href="%s"%s>%s</a></li>',
		esc_url( $item->url ),
		$new_tab_attr, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		esc_html( $item->title )
	);
};
?>
<nav class="navigation-menu">
	<ul class="navigation-menu__inner menu--desktop">
		<?php
		if ( $show_n > 0 ) {
			$head = array_slice( $top_items, 0, $show_n );
			$tail = array_slice( $top_items, $show_n );

			foreach ( $head as $item ) {
				$render_item( $item );
			}

			if ( ! empty( $tail ) ) :
				?>
				<li>
					<ul class="menu">
						<li class="menu__trigger"><?php echo esc_html( $more_label ); ?>&nbsp;▾</li>
						<li>
							<ul class="menu__dropdown">
								<?php foreach ( $tail as $item ) : ?>
									<?php $render_item( $item ); ?>
								<?php endforeach; ?>
							</ul>
						</li>
					</ul>
				</li>
				<?php
			endif;
		} else {
			foreach ( $top_items as $item ) {
				$render_item( $item );
			}
		}
		?>
	</ul>
</nav>
