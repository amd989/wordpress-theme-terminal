<?php
/**
 * Helper functions (accent/layout resolution, reading time, TOC, cover image).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function terminal_get_mod( $name, $default = '' ) {
	return get_theme_mod( 'terminal_' . $name, $default );
}

function terminal_container_class() {
	$layout = terminal_get_mod( 'layout', 'default' );

	$classes = array( 'container' );
	if ( 'full' === $layout ) {
		$classes[] = 'full';
	} elseif ( 'center' === $layout ) {
		$classes[] = 'center';
	}

	if ( terminal_get_mod( 'one_heading_size', false ) ) {
		$classes[] = 'headings--one-size';
	}

	return implode( ' ', $classes );
}

function terminal_word_count( $content = null ) {
	if ( null === $content ) {
		$content = get_the_content();
	}
	$stripped = wp_strip_all_tags( $content );
	return str_word_count( $stripped );
}

function terminal_reading_time( $content = null ) {
	$words   = terminal_word_count( $content );
	$minutes = max( 1, (int) ceil( $words / 220 ) );
	return $minutes;
}

function terminal_should_show_reading_time() {
	if ( is_singular() ) {
		$per_post = get_post_meta( get_the_ID(), '_terminal_reading_time', true );
		if ( '1' === $per_post ) {
			return true;
		}
	}
	return (bool) terminal_get_mod( 'show_reading_time', false );
}

function terminal_cover_url( $post_id = null ) {
	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}
	if ( ! $post_id ) {
		return '';
	}

	$custom = get_post_meta( $post_id, '_terminal_cover', true );
	if ( $custom ) {
		return esc_url( $custom );
	}

	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail_url( $post_id, 'large' );
	}

	if ( terminal_get_mod( 'auto_cover', false ) ) {
		$attachments = get_children(
			array(
				'post_parent'    => $post_id,
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'numberposts'    => 1,
			)
		);
		if ( ! empty( $attachments ) ) {
			$first = reset( $attachments );
			$url   = wp_get_attachment_image_url( $first->ID, 'large' );
			if ( $url ) {
				return $url;
			}
		}
	}

	return '';
}

function terminal_cover_credit( $post_id = null ) {
	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}
	$credit = get_post_meta( $post_id, '_terminal_cover_credit', true );
	return $credit ? $credit : __( 'Cover Image', 'terminal' );
}

/**
 * Inject hanchor links into heading elements (matches Hugo theme's post behavior).
 *
 * Runs on `the_content`. Only affects singular post/page views so archives stay
 * clean.
 */
function terminal_inject_heading_anchors( $content ) {
	if ( ! is_singular() || is_admin() || is_feed() ) {
		return $content;
	}

	return preg_replace_callback(
		'#<(h[1-6])([^>]*)>(.*?)</\1>#is',
		function ( $m ) {
			$tag   = $m[1];
			$attrs = $m[2];
			$inner = $m[3];

			if ( ! preg_match( '/\sid=(["\'])([^"\']+)\1/i', $attrs, $id_match ) ) {
				$id     = sanitize_title( wp_strip_all_tags( $inner ) );
				if ( '' === $id ) {
					return $m[0];
				}
				$attrs .= ' id="' . esc_attr( $id ) . '"';
			} else {
				$id = $id_match[2];
			}

			$anchor = '<a href="#' . esc_attr( $id ) . '" class="hanchor" aria-label="Anchor">#</a>';
			return '<' . $tag . $attrs . '>' . $inner . ' ' . $anchor . '</' . $tag . '>';
		},
		$content
	);
}

/**
 * Generate a table of contents from rendered post content.
 *
 * Returns a <nav> with nested <ul> of h2/h3 links. Assumes heading IDs have
 * already been injected by terminal_inject_heading_anchors() or by Gutenberg.
 */
function terminal_toc_from_content( $content ) {
	if ( ! preg_match_all( '#<(h[23])[^>]*\sid=(["\'])([^"\']+)\2[^>]*>(.*?)</\1>#is', $content, $matches, PREG_SET_ORDER ) ) {
		return '';
	}

	$out   = '<nav id="TableOfContents"><ul>';
	$depth = 0;

	foreach ( $matches as $m ) {
		$level = (int) substr( $m[1], 1 );
		$id    = $m[3];
		$text  = wp_strip_all_tags( $m[4] );

		if ( 2 === $level ) {
			if ( 3 === $depth ) {
				$out  .= '</ul></li>';
				$depth = 2;
			}
			if ( 2 === $depth ) {
				$out .= '</li>';
			}
			$out  .= '<li><a href="#' . esc_attr( $id ) . '">' . esc_html( $text ) . '</a>';
			$depth = 2;
		} elseif ( 3 === $level ) {
			if ( 2 === $depth ) {
				$out  .= '<ul>';
				$depth = 3;
			} elseif ( 0 === $depth ) {
				$out  .= '<li><ul>';
				$depth = 3;
			}
			$out .= '<li><a href="#' . esc_attr( $id ) . '">' . esc_html( $text ) . '</a></li>';
		}
	}

	if ( 3 === $depth ) {
		$out .= '</ul></li>';
	} elseif ( 2 === $depth ) {
		$out .= '</li>';
	}
	$out .= '</ul></nav>';

	return $out;
}

function terminal_should_show_toc( $post_id = null ) {
	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}
	$per_post = get_post_meta( $post_id, '_terminal_toc', true );
	if ( '1' === $per_post ) {
		return true;
	}
	if ( '0' === $per_post ) {
		return false;
	}
	return (bool) terminal_get_mod( 'enable_toc', false );
}
