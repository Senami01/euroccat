<?php
/**
 * Fonctions d'affichage réutilisables.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Emblème : croix occitane stylisée (SVG).
 */
function euroccat_cross_svg( $class = '', $title = '' ) {
	$arm = '<path d="M43 43 L57 43 L67 9 Q59 15 50 4 Q41 15 33 9 Z"/><circle cx="33" cy="8" r="3.6"/><circle cx="50" cy="3.6" r="3.6"/><circle cx="67" cy="8" r="3.6"/>';
	$svg  = '<svg class="' . esc_attr( $class ) . '" viewBox="0 0 100 100" ' . ( $title ? 'role="img" aria-label="' . esc_attr( $title ) . '"' : 'aria-hidden="true"' ) . ' fill="currentColor">';
	$svg .= '<g>' . $arm . '</g>';
	foreach ( array( 90, 180, 270 ) as $deg ) {
		$svg .= '<g transform="rotate(' . $deg . ' 50 50)">' . $arm . '</g>';
	}
	$svg .= '<rect x="42" y="42" width="16" height="16"/></svg>';
	return $svg;
}

/**
 * Étoile européenne (SVG).
 */
function euroccat_star_svg( $class = '' ) {
	return '<svg class="' . esc_attr( $class ) . '" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path d="M12 1.8l2.9 6.6 7.2.7-5.4 4.8 1.6 7-6.3-3.7-6.3 3.7 1.6-7L1.9 9.1l7.2-.7z"/></svg>';
}

/**
 * Logo du site : logo personnalisé, sinon emblème + nom.
 */
function euroccat_site_logo() {
	echo '<a class="brand" href="' . esc_url( home_url( '/' ) ) . '" rel="home" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
	if ( has_custom_logo() ) {
		$logo_id = get_theme_mod( 'custom_logo' );
		echo wp_get_attachment_image( $logo_id, 'medium', false, array( 'class' => 'brand__img', 'alt' => get_bloginfo( 'name' ) ) );
	}
	echo '<span class="brand__mark">' . euroccat_cross_svg( 'brand__cross' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '<span class="brand__text"><span class="brand__name">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
	$desc = get_bloginfo( 'description' );
	if ( $desc ) {
		echo '<span class="brand__tagline">' . esc_html( $desc ) . '</span>';
	}
	echo '</span></a>';
}

/**
 * Icônes des réseaux sociaux.
 */
function euroccat_social_icon( $network ) {
	$icons = array(
		'facebook'  => '<path d="M14 8.5V6.8c0-.8.5-1 .9-1H17V2.1L14 2c-3.3 0-4 2.4-4 4v2.5H7.6V12H10v10h4V12h2.8l.4-3.5z"/>',
		'instagram' => '<path d="M12 7.3A4.7 4.7 0 1 0 16.7 12 4.7 4.7 0 0 0 12 7.3zm0 7.8A3.1 3.1 0 1 1 15.1 12 3.1 3.1 0 0 1 12 15.1zM17 5.9a1.1 1.1 0 1 0 1.1 1.1A1.1 1.1 0 0 0 17 5.9z"/><path d="M16.5 2h-9A5.5 5.5 0 0 0 2 7.5v9A5.5 5.5 0 0 0 7.5 22h9a5.5 5.5 0 0 0 5.5-5.5v-9A5.5 5.5 0 0 0 16.5 2zm3.9 14.5a3.9 3.9 0 0 1-3.9 3.9h-9a3.9 3.9 0 0 1-3.9-3.9v-9a3.9 3.9 0 0 1 3.9-3.9h9a3.9 3.9 0 0 1 3.9 3.9z"/>',
		'x'         => '<path d="M17.8 3h3.1l-6.8 7.7 8 10.3h-6.2l-4.9-6.3L5.4 21H2.3l7.2-8.3L1.8 3h6.4l4.4 5.8zm-1.1 16.2h1.7L7.4 4.7H5.6z"/>',
		'youtube'   => '<path d="M22.5 7.2a2.8 2.8 0 0 0-2-2C18.8 4.8 12 4.8 12 4.8s-6.8 0-8.5.4a2.8 2.8 0 0 0-2 2A29 29 0 0 0 1 12a29 29 0 0 0 .5 4.8 2.8 2.8 0 0 0 2 2c1.7.4 8.5.4 8.5.4s6.8 0 8.5-.4a2.8 2.8 0 0 0 2-2A29 29 0 0 0 23 12a29 29 0 0 0-.5-4.8zM9.8 15V9l5.7 3z"/>',
		'linkedin'  => '<path d="M4.98 3.5A2.5 2.5 0 1 1 2.5 6a2.5 2.5 0 0 1 2.48-2.5zM3 8.5h4V21H3zm7 0h3.8v1.7h.1a4.2 4.2 0 0 1 3.8-2c4 0 4.8 2.6 4.8 6.1V21h-4v-6c0-1.4 0-3.3-2-3.3s-2.3 1.6-2.3 3.2V21h-4z"/>',
	);
	return isset( $icons[ $network ] ) ? '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" fill="currentColor">' . $icons[ $network ] . '</svg>' : '';
}

function euroccat_social_links( $class = 'socials' ) {
	$out = '';
	foreach ( array( 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'x' => 'X', 'youtube' => 'YouTube', 'linkedin' => 'LinkedIn' ) as $key => $label ) {
		$url = euroccat_mod( 'social_' . $key );
		if ( $url ) {
			$out .= '<li><a href="' . esc_url( $url ) . '" target="_blank" rel="noopener" aria-label="' . esc_attr( $label ) . '">' . euroccat_social_icon( $key ) . '</a></li>';
		}
	}
	if ( $out ) {
		echo '<ul class="' . esc_attr( $class ) . '">' . $out . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
}

/**
 * Icône flèche.
 */
function euroccat_arrow( $class = 'icon-arrow' ) {
	return '<svg class="' . esc_attr( $class ) . '" width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}

function euroccat_download_icon() {
	return '<svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12m0 0-5-5m5 5 5-5M4 21h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}

/**
 * Visuel de secours pour les articles sans image : motif génératif aux couleurs de la charte.
 */
function euroccat_fallback_visual( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$seed    = abs( crc32( (string) $post_id . get_the_title( $post_id ) ) );
	$variant = $seed % 3;
	$classes = array( 'is-red', 'is-gold', 'is-blue' );
	$title   = get_the_title( $post_id );
	$initial = function_exists( 'mb_substr' ) ? mb_substr( $title, 0, 1 ) : substr( $title, 0, 1 );
	return '<div class="fallback-visual ' . $classes[ $variant ] . '" aria-hidden="true"><span class="fallback-visual__stripes"></span>' . euroccat_cross_svg( 'fallback-visual__cross' ) . '<span class="fallback-visual__letter">' . esc_html( $initial ) . '</span></div>';
}

/**
 * Carte d'article.
 */
function euroccat_post_card( $args = array() ) {
	$featured = ! empty( $args['featured'] );
	?>
	<article <?php post_class( 'card' . ( $featured ? ' card--featured' : '' ) . ' reveal' ); ?>>
		<a class="card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( $featured ? 'large' : 'euroccat-card', array( 'loading' => 'lazy' ) );
			} else {
				echo euroccat_fallback_visual(); // phpcs:ignore WordPress.Security.EscapeOutput
			}
			?>
		</a>
		<div class="card__body">
			<div class="card__meta">
				<?php
				$cats = get_the_category();
				if ( $cats ) {
					echo '<span class="chip">' . esc_html( $cats[0]->name ) . '</span>';
				}
				?>
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			</div>
			<h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<p class="card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), $featured ? 40 : 22 ) ); ?></p>
			<a class="link-arrow" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Lire l’article', 'euroccat' ); ?> <?php echo euroccat_arrow(); // phpcs:ignore ?></a>
		</div>
	</article>
	<?php
}

/**
 * Fil d'Ariane.
 */
function euroccat_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}
	$items   = array();
	$items[] = '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Accueil', 'euroccat' ) . '</a>';

	if ( is_singular( 'post' ) || is_category() || is_tag() || is_author() || is_date() ) {
		$blog_id = (int) get_option( 'page_for_posts' );
		if ( $blog_id ) {
			$items[] = '<a href="' . esc_url( get_permalink( $blog_id ) ) . '">' . esc_html( get_the_title( $blog_id ) ) . '</a>';
		}
	}
	if ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $ancestor ) {
			$items[] = '<a href="' . esc_url( get_permalink( $ancestor ) ) . '">' . esc_html( get_the_title( $ancestor ) ) . '</a>';
		}
	}
	if ( is_singular() ) {
		$items[] = '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_home() ) {
		$items[] = '<span aria-current="page">' . esc_html( get_the_title( get_option( 'page_for_posts' ) ) ) . '</span>';
	} elseif ( is_archive() ) {
		$items[] = '<span aria-current="page">' . wp_strip_all_tags( get_the_archive_title() ) . '</span>';
	} elseif ( is_search() ) {
		$items[] = '<span aria-current="page">' . esc_html__( 'Recherche', 'euroccat' ) . '</span>';
	}
	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Fil d’Ariane', 'euroccat' ) . '">' . implode( '<span class="breadcrumbs__sep" aria-hidden="true">/</span>', $items ) . '</nav>'; // phpcs:ignore
}

/**
 * Bandeau d'en-tête des pages intérieures.
 */
function euroccat_page_hero( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'kicker' => '',
			'title'  => get_the_title(),
			'lead'   => '',
			'image'  => 0,
			'meta'   => '',
		)
	);
	$bg = $args['image'] ? wp_get_attachment_image_url( $args['image'], 'euroccat-hero' ) : '';
	?>
	<header class="page-hero<?php echo $bg ? ' page-hero--image' : ''; ?>">
		<div class="page-hero__bg" aria-hidden="true">
			<?php if ( $bg ) : ?>
				<img src="<?php echo esc_url( $bg ); ?>" alt="" />
			<?php endif; ?>
			<span class="page-hero__senyera"></span>
			<?php echo euroccat_cross_svg( 'page-hero__cross' ); // phpcs:ignore ?>
			<span class="page-hero__stars">
				<?php
				for ( $i = 0; $i < 12; $i++ ) {
					echo '<i style="--i:' . (int) $i . '">' . euroccat_star_svg() . '</i>'; // phpcs:ignore
				}
				?>
			</span>
		</div>
		<div class="container page-hero__inner">
			<?php euroccat_breadcrumbs(); ?>
			<h1 class="page-hero__title split-words"><?php echo wp_kses_post( $args['title'] ); ?></h1>
			<?php if ( $args['lead'] ) : ?>
				<p class="page-hero__lead"><?php echo wp_kses_post( $args['lead'] ); ?></p>
			<?php endif; ?>
			<?php if ( $args['meta'] ) : ?>
				<div class="page-hero__meta"><?php echo $args['meta']; // phpcs:ignore -- construit et échappé par l'appelant. ?></div>
			<?php endif; ?>
		</div>
	</header>
	<?php
}

/**
 * Pagination numérotée.
 */
function euroccat_pagination() {
	$links = paginate_links(
		array(
			'type'      => 'list',
			'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Précédent', 'euroccat' ) . '</span>←',
			'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Suivant', 'euroccat' ) . '</span>→',
		)
	);
	if ( $links ) {
		echo '<nav class="pagination" aria-label="' . esc_attr__( 'Pagination', 'euroccat' ) . '">' . $links . '</nav>'; // phpcs:ignore
	}
}

/**
 * Taille de fichier lisible.
 */
function euroccat_file_size( $attachment_id ) {
	$path = get_attached_file( $attachment_id );
	if ( $path && file_exists( $path ) ) {
		return size_format( filesize( $path ), 1 );
	}
	return '';
}
