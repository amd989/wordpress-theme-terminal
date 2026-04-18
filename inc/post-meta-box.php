<?php
/**
 * Per-post overrides: cover image URL/credit, hide comments, noindex, TOC,
 * reading time, date format.
 *
 * Mirrors the Hugo theme's per-post frontmatter fields.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const TERMINAL_META_FIELDS = array(
	'_terminal_cover',
	'_terminal_cover_credit',
	'_terminal_show_full_content',
	'_terminal_hide_comments',
	'_terminal_noindex',
	'_terminal_reading_time',
	'_terminal_toc',
	'_terminal_date_format',
);

add_action(
	'add_meta_boxes',
	function () {
		foreach ( array( 'post', 'page' ) as $type ) {
			add_meta_box(
				'terminal_post_options',
				__( 'Terminal Options', 'terminal' ),
				'terminal_render_meta_box',
				$type,
				'side',
				'default'
			);
		}
	}
);

function terminal_render_meta_box( $post ) {
	wp_nonce_field( 'terminal_meta_box', 'terminal_meta_nonce' );

	$cover        = get_post_meta( $post->ID, '_terminal_cover', true );
	$cover_credit = get_post_meta( $post->ID, '_terminal_cover_credit', true );
	$full         = get_post_meta( $post->ID, '_terminal_show_full_content', true );
	$hide_c       = get_post_meta( $post->ID, '_terminal_hide_comments', true );
	$noindex      = get_post_meta( $post->ID, '_terminal_noindex', true );
	$reading      = get_post_meta( $post->ID, '_terminal_reading_time', true );
	$toc          = get_post_meta( $post->ID, '_terminal_toc', true );
	$date_fmt     = get_post_meta( $post->ID, '_terminal_date_format', true );
	?>
	<p>
		<label for="terminal_cover"><strong><?php esc_html_e( 'Cover image URL', 'terminal' ); ?></strong></label><br />
		<input type="text" id="terminal_cover" name="_terminal_cover" value="<?php echo esc_attr( $cover ); ?>" class="widefat" />
		<em><?php esc_html_e( 'Overrides the featured image. Leave empty to use featured image.', 'terminal' ); ?></em>
	</p>
	<p>
		<label for="terminal_cover_credit"><strong><?php esc_html_e( 'Cover credit', 'terminal' ); ?></strong></label><br />
		<input type="text" id="terminal_cover_credit" name="_terminal_cover_credit" value="<?php echo esc_attr( $cover_credit ); ?>" class="widefat" />
	</p>
	<p>
		<label><input type="checkbox" name="_terminal_show_full_content" value="1" <?php checked( $full, '1' ); ?> />
			<?php esc_html_e( 'Show full content on archive/list views', 'terminal' ); ?></label>
	</p>
	<p>
		<label><input type="checkbox" name="_terminal_hide_comments" value="1" <?php checked( $hide_c, '1' ); ?> />
			<?php esc_html_e( 'Hide comments on this post', 'terminal' ); ?></label>
	</p>
	<p>
		<label><input type="checkbox" name="_terminal_noindex" value="1" <?php checked( $noindex, '1' ); ?> />
			<?php esc_html_e( 'Noindex this post (robots meta)', 'terminal' ); ?></label>
	</p>
	<p>
		<label><input type="checkbox" name="_terminal_reading_time" value="1" <?php checked( $reading, '1' ); ?> />
			<?php esc_html_e( 'Force reading time display on this post', 'terminal' ); ?></label>
	</p>
	<p>
		<label for="terminal_toc"><strong><?php esc_html_e( 'Table of contents', 'terminal' ); ?></strong></label><br />
		<select id="terminal_toc" name="_terminal_toc" class="widefat">
			<option value="" <?php selected( $toc, '' ); ?>><?php esc_html_e( 'Use site default', 'terminal' ); ?></option>
			<option value="1" <?php selected( $toc, '1' ); ?>><?php esc_html_e( 'Show TOC', 'terminal' ); ?></option>
			<option value="0" <?php selected( $toc, '0' ); ?>><?php esc_html_e( 'Hide TOC', 'terminal' ); ?></option>
		</select>
	</p>
	<p>
		<label for="terminal_date_format"><strong><?php esc_html_e( 'Date format override', 'terminal' ); ?></strong></label><br />
		<input type="text" id="terminal_date_format" name="_terminal_date_format" value="<?php echo esc_attr( $date_fmt ); ?>" class="widefat" placeholder="Y-m-d" />
	</p>
	<?php
}

add_action(
	'save_post',
	function ( $post_id ) {
		if ( ! isset( $_POST['terminal_meta_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['terminal_meta_nonce'] ) ), 'terminal_meta_box' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$text_fields     = array( '_terminal_cover', '_terminal_cover_credit', '_terminal_date_format' );
		$checkbox_fields = array( '_terminal_show_full_content', '_terminal_hide_comments', '_terminal_noindex', '_terminal_reading_time' );
		$select_fields   = array( '_terminal_toc' );

		foreach ( $text_fields as $field ) {
			$value = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
			if ( '' === $value ) {
				delete_post_meta( $post_id, $field );
			} else {
				update_post_meta( $post_id, $field, $value );
			}
		}

		foreach ( $checkbox_fields as $field ) {
			$value = isset( $_POST[ $field ] ) ? '1' : '';
			if ( '' === $value ) {
				delete_post_meta( $post_id, $field );
			} else {
				update_post_meta( $post_id, $field, '1' );
			}
		}

		foreach ( $select_fields as $field ) {
			$value = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
			if ( ! in_array( $value, array( '0', '1' ), true ) ) {
				delete_post_meta( $post_id, $field );
			} else {
				update_post_meta( $post_id, $field, $value );
			}
		}
	}
);
