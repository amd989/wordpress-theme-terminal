<?php
/**
 * Terminal theme bootstrap.
 *
 * Ports the Hugo theme of the same name to a classic WordPress theme:
 * registers theme features, enqueues per-file CSS + JS matching Hugo's
 * ordering, loads customizer settings, shortcodes, helpers, and meta boxes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TERMINAL_VERSION', '1.0.0' );
define( 'TERMINAL_DIR', trailingslashit( get_theme_file_path() ) );
define( 'TERMINAL_URI', trailingslashit( get_theme_file_uri() ) );

require TERMINAL_DIR . 'inc/helpers.php';
require TERMINAL_DIR . 'inc/template-tags.php';
require TERMINAL_DIR . 'inc/customizer.php';
require TERMINAL_DIR . 'inc/shortcodes.php';
require TERMINAL_DIR . 'inc/post-meta-box.php';
require TERMINAL_DIR . 'inc/prism-languages.php';

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'terminal', TERMINAL_DIR . 'languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'custom-logo', array( 'height' => 100, 'width' => 400, 'flex-height' => true, 'flex-width' => true ) );
		add_theme_support(
			'editor-color-palette',
			array(
				array( 'name' => __( 'Accent', 'terminal' ), 'slug' => 'accent', 'color' => '#eec35e' ),
				array( 'name' => __( 'Background', 'terminal' ), 'slug' => 'background', 'color' => '#1a170f' ),
				array( 'name' => __( 'Foreground', 'terminal' ), 'slug' => 'foreground', 'color' => '#eceae5' ),
			)
		);

		register_nav_menus(
			array(
				'main' => __( 'Main Navigation', 'terminal' ),
			)
		);
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		$css_files = array(
			'fonts',
			'main',
			'header',
			'menu',
			'footer',
			'post',
			'code',
			'buttons',
			'pagination',
			'terms',
			'syntax',
			'gist',
			'terminal',
		);

		foreach ( $css_files as $name ) {
			$path = TERMINAL_DIR . 'assets/css/' . $name . '.css';
			if ( ! file_exists( $path ) ) {
				continue;
			}
			wp_enqueue_style(
				'terminal-' . $name,
				TERMINAL_URI . 'assets/css/' . $name . '.css',
				array(),
				filemtime( $path )
			);
		}

		wp_enqueue_style( 'terminal-style', get_stylesheet_uri(), array(), TERMINAL_VERSION );

		$js_files = array( 'menu', 'code' );
		foreach ( $js_files as $name ) {
			$path = TERMINAL_DIR . 'assets/js/' . $name . '.js';
			wp_enqueue_script(
				'terminal-' . $name,
				TERMINAL_URI . 'assets/js/' . $name . '.js',
				array(),
				filemtime( $path ),
				true
			);
		}

		/*
		 * Prism.js via jsDelivr CDN with the autoloader component, which lazy-
		 * loads language grammars on demand. Self-hosting a custom Prism bundle
		 * remains possible — drop a prism.js into assets/js/ and override this
		 * enqueue in a child theme.
		 */
		wp_enqueue_script(
			'prism-core',
			'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/prism.min.js',
			array(),
			'1.29.0',
			true
		);
		wp_enqueue_script(
			'prism-autoloader',
			'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/autoloader/prism-autoloader.min.js',
			array( 'prism-core' ),
			'1.29.0',
			true
		);

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
);

/**
 * Run heading anchor injection before TOC generation so the TOC picks up
 * the injected IDs. Priority 9 runs before wpautop (10) — safe for both
 * classic and block-editor rendered content.
 */
add_filter( 'the_content', 'terminal_inject_heading_anchors', 9 );

/**
 * Append <body> class hints: container layout + one-heading-size flag.
 */
add_filter(
	'body_class',
	function ( $classes ) {
		$layout = terminal_get_mod( 'layout', 'default' );
		if ( 'full' === $layout ) {
			$classes[] = 'layout-full';
		} elseif ( 'center' === $layout ) {
			$classes[] = 'layout-center';
		}
		if ( terminal_get_mod( 'one_heading_size', false ) ) {
			$classes[] = 'headings--one-size';
		}
		return $classes;
	}
);

/**
 * Hide comments section when the post opts out via meta box.
 */
add_filter(
	'comments_open',
	function ( $open, $post_id ) {
		if ( '1' === get_post_meta( $post_id, '_terminal_hide_comments', true ) ) {
			return false;
		}
		return $open;
	},
	10,
	2
);
