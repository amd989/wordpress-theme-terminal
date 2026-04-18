<?php
/**
 * Theme Customizer — maps Hugo site params to WordPress customizer controls.
 *
 * All options use the `terminal_` prefix and live under one panel "Terminal Theme".
 * Access at runtime via terminal_get_mod( 'key', $default ).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'customize_register',
	function ( WP_Customize_Manager $wp_customize ) {
		$wp_customize->add_panel(
			'terminal_panel',
			array(
				'title'    => __( 'Terminal Theme', 'terminal' ),
				'priority' => 30,
			)
		);

		$sections = array(
			'colors'      => __( 'Colors', 'terminal' ),
			'layout'      => __( 'Layout & Navigation', 'terminal' ),
			'post'        => __( 'Post Display', 'terminal' ),
			'toc'         => __( 'Table of Contents', 'terminal' ),
			'labels'      => __( 'Labels', 'terminal' ),
			'social'      => __( 'Social & SEO', 'terminal' ),
			'analytics'   => __( 'Analytics', 'terminal' ),
		);
		foreach ( $sections as $id => $title ) {
			$wp_customize->add_section(
				'terminal_section_' . $id,
				array(
					'title' => $title,
					'panel' => 'terminal_panel',
				)
			);
		}

		$color_settings = array(
			'accent'     => array(
				'label'   => __( 'Accent color', 'terminal' ),
				'default' => '#eec35e',
			),
			'background' => array(
				'label'   => __( 'Background color', 'terminal' ),
				'default' => '#1a170f',
			),
			'foreground' => array(
				'label'   => __( 'Foreground color', 'terminal' ),
				'default' => '#eceae5',
			),
		);
		foreach ( $color_settings as $key => $cfg ) {
			$wp_customize->add_setting(
				'terminal_' . $key,
				array(
					'default'           => $cfg['default'],
					'sanitize_callback' => 'sanitize_hex_color',
					'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'terminal_' . $key,
					array(
						'label'   => $cfg['label'],
						'section' => 'terminal_section_colors',
					)
				)
			);
		}

		$wp_customize->add_setting(
			'terminal_layout',
			array(
				'default'           => 'default',
				'sanitize_callback' => function ( $v ) {
					return in_array( $v, array( 'default', 'full', 'center' ), true ) ? $v : 'default';
				},
			)
		);
		$wp_customize->add_control(
			'terminal_layout',
			array(
				'label'   => __( 'Container layout', 'terminal' ),
				'section' => 'terminal_section_layout',
				'type'    => 'radio',
				'choices' => array(
					'default' => __( 'Default (left-aligned, max-width 864px)', 'terminal' ),
					'full'    => __( 'Full width', 'terminal' ),
					'center'  => __( 'Centered', 'terminal' ),
				),
			)
		);

		$wp_customize->add_setting(
			'terminal_one_heading_size',
			array(
				'default'           => false,
				'sanitize_callback' => 'wp_validate_boolean',
			)
		);
		$wp_customize->add_control(
			'terminal_one_heading_size',
			array(
				'label'   => __( 'Normalize all heading sizes', 'terminal' ),
				'section' => 'terminal_section_layout',
				'type'    => 'checkbox',
			)
		);

		$wp_customize->add_setting(
			'terminal_logo_text',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'terminal_logo_text',
			array(
				'label'       => __( 'Logo text', 'terminal' ),
				'description' => __( 'Falls back to site title if empty.', 'terminal' ),
				'section'     => 'terminal_section_layout',
				'type'        => 'text',
			)
		);

		$wp_customize->add_setting(
			'terminal_show_menu_items',
			array(
				'default'           => 2,
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			'terminal_show_menu_items',
			array(
				'label'       => __( 'Menu items shown before "More"', 'terminal' ),
				'description' => __( 'Set to 0 to show all items inline (no "More" dropdown).', 'terminal' ),
				'section'     => 'terminal_section_layout',
				'type'        => 'number',
			)
		);

		$wp_customize->add_setting(
			'terminal_menu_more_label',
			array(
				'default'           => __( 'Show more', 'terminal' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'terminal_menu_more_label',
			array(
				'label'   => __( '"More" dropdown label', 'terminal' ),
				'section' => 'terminal_section_layout',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			'terminal_subtitle',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'terminal_subtitle',
			array(
				'label'       => __( 'Site subtitle', 'terminal' ),
				'description' => __( 'Used as meta description on the homepage.', 'terminal' ),
				'section'     => 'terminal_section_layout',
				'type'        => 'text',
			)
		);

		$wp_customize->add_setting(
			'terminal_keywords',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'terminal_keywords',
			array(
				'label'       => __( 'Default site keywords', 'terminal' ),
				'description' => __( 'Comma-separated meta keywords fallback.', 'terminal' ),
				'section'     => 'terminal_section_layout',
				'type'        => 'text',
			)
		);

		$post_bool = array(
			'show_reading_time' => __( 'Show reading time on posts', 'terminal' ),
			'show_last_updated' => __( 'Show "last updated" on posts', 'terminal' ),
			'auto_cover'        => __( 'Auto-detect cover image from post attachments', 'terminal' ),
		);
		foreach ( $post_bool as $key => $label ) {
			$wp_customize->add_setting(
				'terminal_' . $key,
				array(
					'default'           => false,
					'sanitize_callback' => 'wp_validate_boolean',
				)
			);
			$wp_customize->add_control(
				'terminal_' . $key,
				array(
					'label'   => $label,
					'section' => 'terminal_section_post',
					'type'    => 'checkbox',
				)
			);
		}

		$post_text = array(
			'reading_time_label'  => array( __( 'Reading time unit', 'terminal' ), 'min read' ),
			'words_label'         => array( __( 'Word count unit', 'terminal' ), 'words' ),
			'updated_date_prefix' => array( __( 'Updated date prefix', 'terminal' ), 'Updated: ' ),
			'date_format'         => array( __( 'Date format (PHP date() format)', 'terminal' ), 'Y-m-d' ),
		);
		foreach ( $post_text as $key => $cfg ) {
			$wp_customize->add_setting(
				'terminal_' . $key,
				array(
					'default'           => $cfg[1],
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				'terminal_' . $key,
				array(
					'label'   => $cfg[0],
					'section' => 'terminal_section_post',
					'type'    => 'text',
				)
			);
		}

		$wp_customize->add_setting(
			'terminal_enable_toc',
			array(
				'default'           => false,
				'sanitize_callback' => 'wp_validate_boolean',
			)
		);
		$wp_customize->add_control(
			'terminal_enable_toc',
			array(
				'label'       => __( 'Enable TOC by default on posts', 'terminal' ),
				'description' => __( 'Individual posts can override via the Terminal meta box.', 'terminal' ),
				'section'     => 'terminal_section_toc',
				'type'        => 'checkbox',
			)
		);
		$wp_customize->add_setting(
			'terminal_toc_title',
			array(
				'default'           => __( 'Table of Contents', 'terminal' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'terminal_toc_title',
			array(
				'label'   => __( 'TOC heading text', 'terminal' ),
				'section' => 'terminal_section_toc',
				'type'    => 'text',
			)
		);

		$labels = array(
			'read_more'              => array( __( '"Read more" label', 'terminal' ), 'Read more' ),
			'read_other_posts'       => array( __( '"Read other posts" heading', 'terminal' ), 'Read other posts' ),
			'newer_posts'            => array( __( '"Newer posts" button', 'terminal' ), 'Newer posts' ),
			'older_posts'            => array( __( '"Older posts" button', 'terminal' ), 'Older posts' ),
			'missing_content'        => array( __( '404 message', 'terminal' ), 'Page not found...' ),
			'missing_back_button'    => array( __( '404 back-to-home label', 'terminal' ), 'Back to home page' ),
		);
		foreach ( $labels as $key => $cfg ) {
			$wp_customize->add_setting(
				'terminal_' . $key,
				array(
					'default'           => $cfg[1],
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				'terminal_' . $key,
				array(
					'label'   => $cfg[0],
					'section' => 'terminal_section_labels',
					'type'    => 'text',
				)
			);
		}

		foreach ( array( 'twitter_creator', 'twitter_site' ) as $key ) {
			$wp_customize->add_setting(
				'terminal_' . $key,
				array(
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				'terminal_' . $key,
				array(
					'label'       => 'twitter_creator' === $key ? __( 'Twitter @creator', 'terminal' ) : __( 'Twitter @site', 'terminal' ),
					'description' => __( 'Include the leading @.', 'terminal' ),
					'section'     => 'terminal_section_social',
					'type'        => 'text',
				)
			);
		}

		$wp_customize->add_setting(
			'terminal_ga_id',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'terminal_ga_id',
			array(
				'label'       => __( 'Google Analytics Measurement ID', 'terminal' ),
				'description' => __( 'e.g. G-XXXXXXXXXX. Leave empty to disable.', 'terminal' ),
				'section'     => 'terminal_section_analytics',
				'type'        => 'text',
			)
		);
	}
);

/**
 * Emit CSS custom properties from customizer color values in the document head.
 */
function terminal_customizer_css() {
	$accent     = terminal_get_mod( 'accent', '#eec35e' );
	$background = terminal_get_mod( 'background', '#1a170f' );
	$foreground = terminal_get_mod( 'foreground', '#eceae5' );
	?>
	<style id="terminal-customizer-colors">
		:root {
			--accent: <?php echo esc_html( $accent ); ?>;
			--background: <?php echo esc_html( $background ); ?>;
			--foreground: <?php echo esc_html( $foreground ); ?>;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'terminal_customizer_css', 100 );

/**
 * Live preview script so color changes reflect instantly in the customizer.
 */
add_action(
	'customize_preview_init',
	function () {
		wp_enqueue_script(
			'terminal-customizer-preview',
			get_theme_file_uri( 'assets/js/customizer-preview.js' ),
			array( 'customize-preview', 'jquery' ),
			wp_get_theme()->get( 'Version' ),
			true
		);
	}
);
