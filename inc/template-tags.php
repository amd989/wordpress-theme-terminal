<?php
/**
 * Template tags: date, meta, tags, pagination rendering.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function terminal_post_date_format( $post_id = null ) {
	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}
	$per_post = get_post_meta( $post_id, '_terminal_date_format', true );
	if ( $per_post ) {
		return $per_post;
	}
	$site = terminal_get_mod( 'date_format', '' );
	if ( $site ) {
		return $site;
	}
	return 'Y-m-d';
}

function terminal_the_post_date( $post_id = null ) {
	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}
	$format = terminal_post_date_format( $post_id );
	echo esc_html( get_the_date( $format, $post_id ) );
}

function terminal_the_post_lastmod( $post_id = null ) {
	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}
	$format = terminal_post_date_format( $post_id );
	$prefix = terminal_get_mod( 'updated_date_prefix', __( 'Updated: ', 'terminal' ) );
	echo '[' . esc_html( $prefix ) . esc_html( get_the_modified_date( $format, $post_id ) ) . ']';
}

function terminal_the_post_author() {
	$author = get_the_author();
	if ( $author ) {
		echo '<span class="post-author">' . esc_html( $author ) . '</span>';
	}
}

function terminal_the_reading_time() {
	if ( ! terminal_should_show_reading_time() ) {
		return;
	}
	$minutes = terminal_reading_time();
	$words   = terminal_word_count();
	$m_label = terminal_get_mod( 'reading_time_label', __( 'min read', 'terminal' ) );
	$w_label = terminal_get_mod( 'words_label', __( 'words', 'terminal' ) );

	printf(
		'<span class="post-reading-time">%d %s (%d %s)</span>',
		(int) $minutes,
		esc_html( $m_label ),
		(int) $words,
		esc_html( $w_label )
	);
}

function terminal_the_post_tags() {
	$tags = get_the_tags();
	if ( empty( $tags ) ) {
		return;
	}
	echo '<span class="post-tags">';
	foreach ( $tags as $tag ) {
		printf(
			'#<a href="%s">%s</a>&nbsp;',
			esc_url( get_tag_link( $tag->term_id ) ),
			esc_html( $tag->name )
		);
	}
	echo '</span>';
}

function terminal_the_post_meta() {
	?>
	<div class="post-meta">
		<time class="post-date">
			<?php terminal_the_post_date(); ?>
			<?php
			if ( terminal_get_mod( 'show_last_updated', false ) && get_the_modified_date( 'U' ) !== get_the_date( 'U' ) ) {
				echo '&nbsp;';
				terminal_the_post_lastmod();
			}
			?>
		</time>
		<?php terminal_the_post_author(); ?>
		<?php terminal_the_reading_time(); ?>
	</div>
	<?php
}

/**
 * Archive / list pagination. Mirrors layouts/partials/pagination.html.
 */
function terminal_pagination() {
	global $wp_query;

	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	$current = max( 1, get_query_var( 'paged' ) );
	$max     = (int) $wp_query->max_num_pages;
	$newer   = terminal_get_mod( 'newer_posts', __( 'Newer posts', 'terminal' ) );
	$older   = terminal_get_mod( 'older_posts', __( 'Older posts', 'terminal' ) );

	echo '<div class="pagination"><div class="pagination__buttons">';

	if ( $current > 1 ) {
		printf(
			'<a href="%s" class="button inline prev">&lt; [<span class="button__text">%s</span>]</a>',
			esc_url( get_pagenum_link( $current - 1 ) ),
			esc_html( $newer )
		);
	}

	if ( $current > 1 && $current < $max ) {
		echo ' :: ';
	}

	if ( $current < $max ) {
		printf(
			'<a href="%s" class="button inline next">[<span class="button__text">%s</span>] &gt;</a>',
			esc_url( get_pagenum_link( $current + 1 ) ),
			esc_html( $older )
		);
	}

	echo '</div></div>';
}

/**
 * Prev/next single-post navigation. Mirrors posts_pagination.html.
 */
function terminal_post_navigation() {
	$prev = get_previous_post();
	$next = get_next_post();

	if ( ! $prev && ! $next ) {
		return;
	}

	$heading = terminal_get_mod( 'read_other_posts', __( 'Read other posts', 'terminal' ) );

	echo '<div class="pagination"><div class="pagination__title">';
	echo '<span class="pagination__title-h">' . esc_html( $heading ) . '</span><hr /></div>';
	echo '<div class="pagination__buttons">';

	if ( $next ) {
		printf(
			'<a href="%s" class="button inline prev">&lt; [<span class="button__text">%s</span>]</a>',
			esc_url( get_permalink( $next ) ),
			esc_html( get_the_title( $next ) )
		);
	}

	if ( $next && $prev ) {
		echo ' :: ';
	}

	if ( $prev ) {
		printf(
			'<a href="%s" class="button inline next">[<span class="button__text">%s</span>] &gt;</a>',
			esc_url( get_permalink( $prev ) ),
			esc_html( get_the_title( $prev ) )
		);
	}

	echo '</div></div>';
}
