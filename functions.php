<?php
/**
 * Euroccat : fonctions du thème.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EUROCCAT_VERSION', '1.0.0' );
define( 'EUROCCAT_DIR', get_template_directory() );
define( 'EUROCCAT_URI', get_template_directory_uri() );

require EUROCCAT_DIR . '/inc/customizer.php';
require EUROCCAT_DIR . '/inc/template-tags.php';
require EUROCCAT_DIR . '/inc/contact-form.php';

/**
 * Réglages de base du thème.
 */
function euroccat_setup() {
	load_theme_textdomain( 'euroccat', EUROCCAT_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 160,
			'width'       => 230,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_post_type_support( 'page', 'excerpt' );

	add_image_size( 'euroccat-card', 900, 600, true );
	add_image_size( 'euroccat-hero', 1920, 1080, true );

	register_nav_menus(
		array(
			'primary' => __( 'Menu principal', 'euroccat' ),
			'footer'  => __( 'Menu du pied de page', 'euroccat' ),
			'legal'   => __( 'Liens légaux', 'euroccat' ),
		)
	);

	add_editor_style( 'assets/css/editor.css' );

	// Palette éditeur alignée sur la charte.
	add_theme_support(
		'editor-color-palette',
		array(
			array( 'name' => 'Encre', 'slug' => 'ink', 'color' => '#121833' ),
			array( 'name' => 'Rouge occitan', 'slug' => 'red', 'color' => '#C8102E' ),
			array( 'name' => 'Or catalan', 'slug' => 'gold', 'color' => '#F4B400' ),
			array( 'name' => 'Bleu Europe', 'slug' => 'blue', 'color' => '#1E3A8A' ),
			array( 'name' => 'Papier', 'slug' => 'paper', 'color' => '#F8F3EA' ),
			array( 'name' => 'Blanc', 'slug' => 'white', 'color' => '#FFFFFF' ),
		)
	);
}
add_action( 'after_setup_theme', 'euroccat_setup' );

/**
 * Zones de widgets.
 */
function euroccat_widgets_init() {
	$common = array(
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget__title">',
		'after_title'   => '</h3>',
	);
	register_sidebar( array_merge( $common, array( 'name' => __( 'Barre latérale du blog', 'euroccat' ), 'id' => 'sidebar-blog' ) ) );
	register_sidebar( array_merge( $common, array( 'name' => __( 'Pied de page', 'euroccat' ), 'id' => 'sidebar-footer' ) ) );
}
add_action( 'widgets_init', 'euroccat_widgets_init' );

/**
 * Les fichiers Gilroy sont-ils présents dans assets/fonts ?
 */
function euroccat_has_gilroy() {
	return file_exists( EUROCCAT_DIR . '/assets/fonts/Gilroy-Regular.woff2' ) || file_exists( EUROCCAT_DIR . '/assets/fonts/Gilroy-Regular.woff' ) || file_exists( EUROCCAT_DIR . '/assets/fonts/Gilroy-Regular.ttf' ) || file_exists( EUROCCAT_DIR . '/assets/fonts/Gilroy-Regular.otf' );
}

/**
 * Déclarations @font-face Gilroy, générées selon les fichiers réellement présents.
 */
function euroccat_gilroy_css() {
	$weights = array(
		'Thin'       => 100,
		'UltraLight' => 200,
		'Light'      => 300,
		'Regular'    => 400,
		'Medium'     => 500,
		'SemiBold'   => 600,
		'Bold'       => 700,
		'ExtraBold'  => 800,
		'Heavy'      => 900,
		'Black'      => 900,
	);
	$formats = array( 'woff2' => 'woff2', 'woff' => 'woff', 'ttf' => 'truetype', 'otf' => 'opentype' );
	$css     = '';
	foreach ( $weights as $name => $weight ) {
		foreach ( array( '' => 'normal', 'Italic' => 'italic' ) as $suffix => $style ) {
			$file = 'Gilroy-' . $name . $suffix;
			if ( 'Regular' === $name && 'Italic' === $suffix ) {
				$file = 'Gilroy-RegularItalic';
			}
			$src = array();
			foreach ( $formats as $ext => $fmt ) {
				if ( file_exists( EUROCCAT_DIR . '/assets/fonts/' . $file . '.' . $ext ) ) {
					$src[] = 'url("' . esc_url( EUROCCAT_URI . '/assets/fonts/' . $file . '.' . $ext ) . '") format("' . $fmt . '")';
				}
			}
			if ( $src ) {
				$css .= '@font-face{font-family:"Gilroy";src:' . implode( ',', $src ) . ';font-weight:' . $weight . ';font-style:' . $style . ';font-display:swap;}';
			}
		}
	}
	return $css;
}

/**
 * Feuilles de style et scripts.
 */
function euroccat_assets() {
	// Police de secours (géométrique, proche de Gilroy) uniquement si Gilroy n'est pas encore installée.
	if ( ! euroccat_has_gilroy() ) {
		wp_enqueue_style( 'euroccat-fallback-font', 'https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,300..900;1,300..900&display=swap', array(), null );
	}

	$css_ver = EUROCCAT_VERSION . '.' . filemtime( EUROCCAT_DIR . '/assets/css/main.css' );
	$js_ver  = EUROCCAT_VERSION . '.' . filemtime( EUROCCAT_DIR . '/assets/js/main.js' );

	wp_enqueue_style( 'euroccat-main', EUROCCAT_URI . '/assets/css/main.css', array(), $css_ver );
	wp_add_inline_style( 'euroccat-main', euroccat_gilroy_css() . euroccat_customizer_css() );

	wp_enqueue_script( 'euroccat-main', EUROCCAT_URI . '/assets/js/main.js', array(), $js_ver, array( 'strategy' => 'defer', 'in_footer' => true ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'euroccat_assets' );

/**
 * Gilroy aussi dans l'éditeur de blocs.
 */
function euroccat_editor_assets() {
	if ( ! euroccat_has_gilroy() ) {
		wp_enqueue_style( 'euroccat-fallback-font', 'https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,300..900;1,300..900&display=swap', array(), null );
	}
	wp_add_inline_style( 'wp-block-library', euroccat_gilroy_css() );
}
add_action( 'enqueue_block_editor_assets', 'euroccat_editor_assets' );

/**
 * Préconnexion à Google Fonts quand la police de secours est utilisée.
 */
function euroccat_resource_hints( $urls, $relation ) {
	if ( 'preconnect' === $relation && ! euroccat_has_gilroy() ) {
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'euroccat_resource_hints', 10, 2 );

/**
 * Évite le flash de thème clair/sombre : applique le choix avant le rendu.
 */
function euroccat_theme_bootstrap() {
	echo "<script>(function(){try{var t=localStorage.getItem('euroccat-theme');if(t){document.documentElement.setAttribute('data-theme',t);}}catch(e){}document.documentElement.classList.add('js');})();</script>\n";
}
add_action( 'wp_head', 'euroccat_theme_bootstrap', 1 );

/**
 * Classes body utiles.
 */
function euroccat_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'is-home';
	}
	if ( get_theme_mod( 'euroccat_header_transparent', true ) && ! is_404() && ! is_page_template( 'page-templates/template-full-width.php' ) ) {
		$classes[] = 'has-overlay-header';
	}
	return $classes;
}
add_filter( 'body_class', 'euroccat_body_classes' );

/**
 * Extraits plus courts et élégants.
 */
add_filter( 'excerpt_length', function () {
	return 26;
} );
add_filter( 'excerpt_more', function () {
	return '…';
} );

/**
 * Ajoute une flèche aux éléments de menu ayant des enfants.
 */
function euroccat_nav_menu_item_title( $title, $item, $args, $depth ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location && in_array( 'menu-item-has-children', (array) $item->classes, true ) && 0 === $depth ) {
		$title .= '<svg class="menu-caret" width="10" height="10" viewBox="0 0 10 10" aria-hidden="true"><path d="M1 3.5 5 7l4-3.5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>';
	}
	return $title;
}
add_filter( 'nav_menu_item_title', 'euroccat_nav_menu_item_title', 10, 4 );

/**
 * Menu de secours quand aucun menu n'est assigné.
 */
function euroccat_menu_fallback() {
	echo '<ul class="menu">';
	wp_list_pages( array( 'title_li' => '', 'depth' => 2 ) );
	echo '</ul>';
}

/**
 * Temps de lecture estimé.
 */
function euroccat_reading_time( $post_id = null ) {
	$content = get_post_field( 'post_content', $post_id ? $post_id : get_the_ID() );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	$minutes = max( 1, (int) ceil( $words / 220 ) );
	/* translators: %d: minutes */
	return sprintf( _n( '%d min de lecture', '%d min de lecture', $minutes, 'euroccat' ), $minutes );
}
