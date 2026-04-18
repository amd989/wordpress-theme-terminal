<?php
/**
 * Shortcodes — [code], [image], [terminal_terms].
 *
 * [code] and [image] preserve the Hugo theme's argument names for parity with
 * existing markdown content authored against the Hugo theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * [code language="go" title="example.go" open="true"]...[/code]
 *
 * Renders a collapsible code block. Prism.js picks up the language-XXX class
 * client-side for syntax highlighting.
 */
function terminal_shortcode_code( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'language' => '',
			'title'    => '',
			'open'     => 'false',
		),
		$atts,
		'code'
	);

	if ( '' === $atts['language'] ) {
		return '';
	}

	$content = trim( $content, "\n" );
	$content = html_entity_decode( $content, ENT_QUOTES | ENT_HTML5, 'UTF-8' );

	$open_attr = ( 'true' === $atts['open'] || '1' === $atts['open'] ) ? ' open' : '';

	$title_html = '';
	if ( '' !== $atts['title'] ) {
		$title_html = '<span class="collapsable-code__title">' . esc_html( $atts['title'] ) . '</span>';
	}

	return sprintf(
		'<details class="collapsable-code"%s><summary title="%s">%s</summary><pre class="language-%s"><code class="language-%s">%s</code></pre></details>',
		$open_attr,
		esc_attr__( 'Click to interact', 'terminal' ),
		$title_html,
		esc_attr( $atts['language'] ),
		esc_attr( $atts['language'] ),
		esc_html( $content )
	);
}
add_shortcode( 'code', 'terminal_shortcode_code' );

/**
 * [image src="..." alt="..." position="center" width="600" style="..."]
 *
 * Renders a positioned image matching the Hugo theme's image shortcode.
 */
function terminal_shortcode_image( $atts ) {
	$atts = shortcode_atts(
		array(
			'src'      => '',
			'alt'      => '',
			'position' => 'left',
			'style'    => '',
			'width'    => '',
			'height'   => '',
		),
		$atts,
		'image'
	);

	if ( '' === $atts['src'] ) {
		return '';
	}

	$attrs = sprintf( ' src="%s" class="%s"', esc_url( $atts['src'] ), esc_attr( $atts['position'] ) );
	if ( '' !== $atts['alt'] ) {
		$attrs .= ' alt="' . esc_attr( $atts['alt'] ) . '"';
	}
	if ( '' !== $atts['style'] ) {
		$attrs .= ' style="' . esc_attr( $atts['style'] ) . '"';
	}
	if ( '' !== $atts['width'] ) {
		$attrs .= ' width="' . esc_attr( $atts['width'] ) . '"';
	}
	if ( '' !== $atts['height'] ) {
		$attrs .= ' height="' . esc_attr( $atts['height'] ) . '"';
	}

	return '<img' . $attrs . ' />';
}
add_shortcode( 'image', 'terminal_shortcode_image' );

/**
 * [terminal_terms taxonomy="post_tag"]
 *
 * Alphabetical taxonomy term listing (replaces Hugo's terms.html template).
 * Defaults to post_tag. Use taxonomy="category" for categories.
 */
function terminal_shortcode_terms( $atts ) {
	$atts = shortcode_atts(
		array(
			'taxonomy' => 'post_tag',
		),
		$atts,
		'terminal_terms'
	);

	$terms = get_terms(
		array(
			'taxonomy'   => $atts['taxonomy'],
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => true,
		)
	);

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return '';
	}

	$out = '<div class="terms"><ul>';
	foreach ( $terms as $term ) {
		$out .= sprintf(
			'<li><a class="terms-title" href="%s">%s [%d]</a></li>',
			esc_url( get_term_link( $term ) ),
			esc_html( $term->name ),
			(int) $term->count
		);
	}
	$out .= '</ul></div>';

	return $out;
}
add_shortcode( 'terminal_terms', 'terminal_shortcode_terms' );
