<?php
/**
 * Document header + site header + navigation.
 *
 * Maps to Hugo layouts/_default/baseof.html (open tags) + partials/head.html
 * + partials/header.html.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php
	$description = '';
	if ( is_home() || is_front_page() ) {
		$description = terminal_get_mod( 'subtitle', get_bloginfo( 'description' ) );
	} elseif ( is_singular() ) {
		$excerpt = get_the_excerpt();
		$description = $excerpt ? wp_strip_all_tags( $excerpt ) : wp_strip_all_tags( get_bloginfo( 'description' ) );
	} else {
		$description = wp_strip_all_tags( get_bloginfo( 'description' ) );
	}
	?>
	<meta name="description" content="<?php echo esc_attr( $description ); ?>" />

	<?php
	$keywords = '';
	if ( is_singular() ) {
		$tags = get_the_tags();
		if ( ! empty( $tags ) ) {
			$keywords = implode( ', ', wp_list_pluck( $tags, 'name' ) );
		}
	}
	if ( ! $keywords ) {
		$keywords = terminal_get_mod( 'keywords', '' );
	}
	if ( $keywords ) {
		echo '<meta name="keywords" content="' . esc_attr( $keywords ) . '" />' . "\n";
	}

	if ( is_singular() && '1' === get_post_meta( get_the_ID(), '_terminal_noindex', true ) ) {
		echo '<meta name="robots" content="noindex" />' . "\n";
	} else {
		echo '<meta name="robots" content="noodp" />' . "\n";
	}

	$canonical = is_singular() ? get_permalink() : home_url( add_query_arg( null, null ) );
	?>
	<link rel="canonical" href="<?php echo esc_url( $canonical ); ?>" />

	<link rel="shortcut icon" href="<?php echo esc_url( TERMINAL_URI . 'assets/images/favicon.png' ); ?>">
	<link rel="apple-touch-icon" href="<?php echo esc_url( TERMINAL_URI . 'assets/images/apple-touch-icon.png' ); ?>">

	<meta name="twitter:card" content="summary" />
	<?php
	$tw_site = terminal_get_mod( 'twitter_site', '' );
	if ( $tw_site ) {
		echo '<meta name="twitter:site" content="' . esc_attr( $tw_site ) . '" />' . "\n";
	}
	$tw_creator = terminal_get_mod( 'twitter_creator', '' );
	if ( is_singular() ) {
		$author_id = get_the_author_meta( 'ID' );
		$author_tw = $author_id ? get_the_author_meta( 'twitter', $author_id ) : '';
		if ( $author_tw ) {
			$tw_creator = $author_tw;
		}
	}
	if ( $tw_creator ) {
		echo '<meta name="twitter:creator" content="' . esc_attr( $tw_creator ) . '" />' . "\n";
	}

	$og_type        = is_singular( 'post' ) ? 'article' : 'website';
	$og_title       = is_home() || is_front_page() ? get_bloginfo( 'name' ) : wp_get_document_title();
	$og_description = $description;
	$cover_url      = is_singular() ? terminal_cover_url() : '';
	$og_image       = $cover_url ? $cover_url : TERMINAL_URI . 'assets/images/og-image.png';
	?>
	<meta property="og:locale" content="<?php echo esc_attr( get_locale() ); ?>" />
	<meta property="og:type" content="<?php echo esc_attr( $og_type ); ?>" />
	<meta property="og:title" content="<?php echo esc_attr( $og_title ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( $og_description ); ?>" />
	<meta property="og:url" content="<?php echo esc_url( $canonical ); ?>" />
	<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
	<meta property="og:image" content="<?php echo esc_url( $og_image ); ?>">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="627">

	<?php
	if ( is_singular( 'post' ) ) {
		$categories = get_the_category();
		foreach ( $categories as $cat ) {
			echo '<meta property="article:section" content="' . esc_attr( $cat->name ) . '" />' . "\n";
		}
		echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( 'c' ) ) . '" />' . "\n";
	}

	$ga = terminal_get_mod( 'ga_id', '' );
	if ( $ga ) :
		?>
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga ); ?>"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', '<?php echo esc_js( $ga ); ?>');
		</script>
		<?php
	endif;

	wp_head();
	?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="<?php echo esc_attr( terminal_container_class() ); ?>">

	<header class="header">
		<div class="header__inner">
			<div class="header__logo">
				<?php get_template_part( 'template-parts/logo' ); ?>
			</div>
			<?php if ( has_nav_menu( 'main' ) ) : ?>
				<?php get_template_part( 'template-parts/mobile-menu' ); ?>
			<?php endif; ?>
		</div>
		<?php if ( has_nav_menu( 'main' ) ) : ?>
			<?php get_template_part( 'template-parts/menu' ); ?>
		<?php endif; ?>
	</header>

	<div class="content">
