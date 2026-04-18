<?php
/**
 * Normalize Gutenberg code-block language hints to Prism's class naming.
 *
 * Gutenberg's core/code block stores the language in a `language` attribute
 * but does not emit a class on the rendered `<code>`. This filter reads that
 * attribute and injects a Prism-compatible `language-XXX` class so the
 * Prism.js bundle can highlight the block without manual authoring.
 *
 * We also alias a few commonly-used names to Prism's canonical identifiers
 * (e.g. "shell" → "bash", "node" → "javascript").
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function terminal_prism_alias( $lang ) {
	$lang    = strtolower( trim( $lang ) );
	$aliases = array(
		'shell'  => 'bash',
		'sh'     => 'bash',
		'zsh'    => 'bash',
		'node'   => 'javascript',
		'js'     => 'javascript',
		'ts'     => 'typescript',
		'yml'    => 'yaml',
		'py'     => 'python',
		'rb'     => 'ruby',
		'golang' => 'go',
		'md'     => 'markdown',
		'htm'    => 'html',
	);
	return $aliases[ $lang ] ?? $lang;
}

add_filter(
	'render_block_core/code',
	function ( $block_content, $block ) {
		if ( empty( $block['attrs']['language'] ) ) {
			return $block_content;
		}
		$lang = terminal_prism_alias( $block['attrs']['language'] );
		if ( '' === $lang ) {
			return $block_content;
		}

		return preg_replace_callback(
			'#<code\b([^>]*)>#i',
			function ( $m ) use ( $lang ) {
				$attrs = $m[1];
				if ( preg_match( '/\bclass=(["\'])([^"\']*)\1/i', $attrs ) ) {
					$attrs = preg_replace(
						'/\bclass=(["\'])([^"\']*)\1/i',
						'class=$1$2 language-' . esc_attr( $lang ) . '$1',
						$attrs,
						1
					);
				} else {
					$attrs .= ' class="language-' . esc_attr( $lang ) . '"';
				}
				return '<code' . $attrs . '>';
			},
			$block_content,
			1
		);
	},
	10,
	2
);
