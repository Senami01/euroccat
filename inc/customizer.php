<?php
/**
 * Outil de personnalisation (Apparence → Personnaliser).
 * Tout le contenu de la page d'accueil, les couleurs et les coordonnées se règlent ici.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Valeurs par défaut centralisées.
 */
function euroccat_defaults() {
	return array(
		// Couleurs.
		'color_primary'   => '#C8102E',
		'color_accent'    => '#F4B400',
		'color_secondary' => '#1E3A8A',
		'color_ink'       => '#302A68',
		'color_paper'     => '#F8F3EA',

		// En-tête.
		'header_cta_label' => 'Nous contacter',
		'header_cta_url'   => '/contact/',

		// Héros.
		'hero_kicker'  => 'Païses occitans · Països catalans',
		'hero_title_1' => 'Occitània',
		'hero_title_2' => 'Catalunya',
		'hero_text'    => 'Association créée en 1999 pour organiser, côté Occitanie, l’Eurocongrès 2000 des pays occitans et catalans. Elle organise aujourd’hui les colloques Agaches à Narbonne et publie leurs actes.',
		'hero_btn1_label' => 'Découvrir l’Eurocongrès',
		'hero_btn1_url'   => '/rencontres/eurocongres-2000/',
		'hero_btn2_label' => 'Lire le texte fondateur',
		'hero_btn2_url'   => '/texte-fondateur/',

		// Bandeau défilant.
		'marquee' => 'Tolosa · Barcelona · Narbona · Montpelhièr · Perpinyà · Lleida · Marselha · València · Pau · Girona',

		// Chiffres clés.
		'stat_1_num' => '1999', 'stat_1_label' => 'Création de l’association',
		'stat_2_num' => '9',    'stat_2_label' => 'Domaines de réflexion',
		'stat_3_num' => '7',    'stat_3_label' => 'Campagnes menées',
		'stat_4_num' => '4',    'stat_4_label' => 'Colloques depuis 2000',

		// À propos.
		'about_kicker' => '',
		'about_title'  => 'L’association',
		'about_text'   => 'Euroccat est une association loi 1901 constituée en 1999 pour l’organisation côté Occitanie de l’Eurocongrès 2000 des pays occitans et catalans. Le congrès s’est tenu entre 2000 et 2002, de Toulouse à Barcelone.',
		'about_link_label' => 'En savoir plus',
		'about_link_url'   => '/association/',

		// Frise.
		'timeline_kicker' => '',
		'timeline_title'  => 'Les rencontres',
		'tl_1_year' => '2000', 'tl_1_title' => 'Eurocongrès 2000', 'tl_1_text' => 'Le congrès des pays occitans et catalans, organisé en neuf domaines et sept campagnes.', 'tl_1_url' => '/rencontres/eurocongres-2000/',
		'tl_2_year' => '2002', 'tl_2_title' => 'Colloque 2002', 'tl_2_text' => 'Colloque de suivi de l’Eurocongrès. La plaquette est consultable en ligne.', 'tl_2_url' => '/rencontres/colloque-2002/',
		'tl_3_year' => '2022', 'tl_3_title' => 'Agaches 2022', 'tl_3_text' => 'Colloque de Narbonne du 9 décembre 2022. Les actes sont disponibles en PDF.', 'tl_3_url' => '/rencontres/agaches-2022/',
		'tl_4_year' => '2024', 'tl_4_title' => 'Agaches 2024', 'tl_4_text' => 'Narbonne, 28 et 29 novembre 2024. Thème : « Surmonter les frontières, coupures/coutures ».', 'tl_4_url' => '/rencontres/agaches-2024/',

		// Citation.
		'quote_text'   => 'Catalans e Occitans, inventem ensems l’intrada dins un nòu millenari.',
		'quote_author' => 'Robert Lafont',
		'quote_role'   => 'Texte fondateur, 2000',

		// Blog.
		'blog_kicker' => '',
		'blog_title'  => 'Derniers articles',
		'blog_count'  => 3,

		// Bibliothèque.
		'library_title' => 'Bibliothèque',
		'library_text'  => 'Actes des colloques, programmes en occitan, en catalan et en français, plaquettes.',
		'library_btn'   => 'Voir tous les documents',
		'library_url'   => '/bibliotheque/',

		// Coordonnées.
		'contact_email'   => 'euroccat.tls@free.fr',
		'contact_phone'   => '',
		'contact_address' => 'Toulouse',
		'social_facebook' => '',
		'social_instagram' => '',
		'social_x'        => '',
		'social_youtube'  => '',
		'social_linkedin' => '',

		// Pied de page.
		'footer_text' => 'Association loi 1901, Toulouse.',
		'footer_copy' => '© {year} Euroccat',
	);
}

/**
 * Récupère un réglage avec sa valeur par défaut.
 */
function euroccat_mod( $key ) {
	$defaults = euroccat_defaults();
	return get_theme_mod( 'euroccat_' . $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
}

/**
 * Transforme une URL relative du Customizer (/contact/) en URL complète.
 */
function euroccat_link( $url ) {
	if ( '' === $url ) {
		return '';
	}
	if ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) {
		return home_url( $url );
	}
	return $url;
}

/**
 * Enregistrement des panneaux, sections et réglages.
 */
function euroccat_customize_register( $wp_customize ) {
	$d = euroccat_defaults();

	$add = function ( $key, $section, $label, $type = 'text', $extra = array() ) use ( $wp_customize, $d ) {
		$sanitize = 'sanitize_text_field';
		if ( 'textarea' === $type ) {
			$sanitize = 'sanitize_textarea_field';
		} elseif ( 'url' === $type ) {
			$sanitize = 'esc_url_raw';
		} elseif ( 'email' === $type ) {
			$sanitize = 'sanitize_email';
		} elseif ( 'checkbox' === $type ) {
			$sanitize = 'euroccat_sanitize_checkbox';
		} elseif ( 'number' === $type ) {
			$sanitize = 'absint';
		} elseif ( 'link' === $type ) {
			$sanitize = 'euroccat_sanitize_link';
			$type     = 'text';
		}
		$wp_customize->add_setting(
			'euroccat_' . $key,
			array(
				'default'           => isset( $d[ $key ] ) ? $d[ $key ] : ( isset( $extra['default'] ) ? $extra['default'] : '' ),
				'sanitize_callback' => $sanitize,
				'transport'         => isset( $extra['transport'] ) ? $extra['transport'] : 'refresh',
			)
		);
		unset( $extra['default'], $extra['transport'] );
		$wp_customize->add_control(
			'euroccat_' . $key,
			array_merge(
				array(
					'label'   => $label,
					'section' => $section,
					'type'    => $type,
				),
				$extra
			)
		);
	};

	$wp_customize->add_panel(
		'euroccat_panel',
		array(
			'title'       => __( 'Thème Euroccat', 'euroccat' ),
			'description' => __( 'Personnalisez toutes les sections du site.', 'euroccat' ),
			'priority'    => 20,
		)
	);

	/* Couleurs */
	$wp_customize->add_section( 'euroccat_colors', array( 'title' => __( 'Couleurs de la charte', 'euroccat' ), 'panel' => 'euroccat_panel', 'priority' => 5 ) );
	$colors = array(
		'color_primary'   => __( 'Couleur principale (rouge occitan)', 'euroccat' ),
		'color_accent'    => __( 'Couleur d’accent (or catalan)', 'euroccat' ),
		'color_secondary' => __( 'Couleur secondaire (bleu Europe)', 'euroccat' ),
		'color_ink'       => __( 'Bleu nuit (textes et fonds foncés)', 'euroccat' ),
		'color_paper'     => __( 'Papier (fond clair)', 'euroccat' ),
	);
	foreach ( $colors as $key => $label ) {
		$wp_customize->add_setting( 'euroccat_' . $key, array( 'default' => $d[ $key ], 'sanitize_callback' => 'sanitize_hex_color' ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'euroccat_' . $key, array( 'label' => $label, 'section' => 'euroccat_colors' ) ) );
	}

	/* En-tête */
	$wp_customize->add_section( 'euroccat_header', array( 'title' => __( 'En-tête', 'euroccat' ), 'panel' => 'euroccat_panel', 'priority' => 10 ) );
	$add( 'header_transparent', 'euroccat_header', __( 'En-tête transparent sur les visuels', 'euroccat' ), 'checkbox', array( 'default' => true ) );
	$add( 'show_theme_toggle', 'euroccat_header', __( 'Afficher le bouton mode sombre', 'euroccat' ), 'checkbox', array( 'default' => true ) );
	$add( 'show_search', 'euroccat_header', __( 'Afficher la recherche', 'euroccat' ), 'checkbox', array( 'default' => true ) );
	$add( 'header_cta_label', 'euroccat_header', __( 'Texte du bouton', 'euroccat' ) );
	$add( 'header_cta_url', 'euroccat_header', __( 'Lien du bouton', 'euroccat' ), 'link' );

	/* Héros */
	$wp_customize->add_section( 'euroccat_hero', array( 'title' => __( 'Accueil : Bannière', 'euroccat' ), 'panel' => 'euroccat_panel', 'priority' => 20 ) );
	$add( 'hero_kicker', 'euroccat_hero', __( 'Surtitre', 'euroccat' ) );
	$add( 'hero_title_1', 'euroccat_hero', __( 'Titre : ligne 1', 'euroccat' ) );
	$add( 'hero_title_2', 'euroccat_hero', __( 'Titre : ligne 2', 'euroccat' ) );
	$add( 'hero_text', 'euroccat_hero', __( 'Texte', 'euroccat' ), 'textarea' );
	$add( 'hero_btn1_label', 'euroccat_hero', __( 'Bouton 1 : texte', 'euroccat' ) );
	$add( 'hero_btn1_url', 'euroccat_hero', __( 'Bouton 1 : lien', 'euroccat' ), 'link' );
	$add( 'hero_btn2_label', 'euroccat_hero', __( 'Bouton 2 : texte', 'euroccat' ) );
	$add( 'hero_btn2_url', 'euroccat_hero', __( 'Bouton 2 : lien', 'euroccat' ), 'link' );
	$wp_customize->add_setting( 'euroccat_hero_image', array( 'default' => '', 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'euroccat_hero_image', array( 'label' => __( 'Image de la bannière (facultative)', 'euroccat' ), 'section' => 'euroccat_hero', 'mime_type' => 'image' ) ) );
	$add( 'hero_show_logo', 'euroccat_hero', __( 'Afficher l’emblème historique de l’Eurocongrès', 'euroccat' ), 'checkbox', array( 'default' => true ) );

	/* Sections d'accueil */
	$wp_customize->add_section( 'euroccat_home', array( 'title' => __( 'Accueil : Sections', 'euroccat' ), 'panel' => 'euroccat_panel', 'priority' => 30 ) );
	foreach ( array(
		'marquee'  => __( 'Bandeau défilant', 'euroccat' ),
		'stats'    => __( 'Chiffres clés', 'euroccat' ),
		'about'    => __( 'À propos', 'euroccat' ),
		'timeline' => __( 'Frise des rencontres', 'euroccat' ),
		'quote'    => __( 'Citation', 'euroccat' ),
		'blog'     => __( 'Derniers articles', 'euroccat' ),
		'library'  => __( 'Appel bibliothèque', 'euroccat' ),
	) as $key => $label ) {
		/* translators: %s: section name */
		$add( 'show_' . $key, 'euroccat_home', sprintf( __( 'Afficher : %s', 'euroccat' ), $label ), 'checkbox', array( 'default' => true ) );
	}
	$add( 'marquee', 'euroccat_home', __( 'Mots du bandeau (séparés par ·)', 'euroccat' ), 'textarea' );
	for ( $i = 1; $i <= 4; $i++ ) {
		/* translators: %d: number */
		$add( "stat_{$i}_num", 'euroccat_home', sprintf( __( 'Chiffre %d : valeur', 'euroccat' ), $i ) );
		/* translators: %d: number */
		$add( "stat_{$i}_label", 'euroccat_home', sprintf( __( 'Chiffre %d : légende', 'euroccat' ), $i ) );
	}
	$add( 'about_kicker', 'euroccat_home', __( 'À propos : surtitre', 'euroccat' ) );
	$add( 'about_title', 'euroccat_home', __( 'À propos : titre', 'euroccat' ) );
	$add( 'about_text', 'euroccat_home', __( 'À propos : texte', 'euroccat' ), 'textarea' );
	$add( 'about_link_label', 'euroccat_home', __( 'À propos : texte du lien', 'euroccat' ) );
	$add( 'about_link_url', 'euroccat_home', __( 'À propos : lien', 'euroccat' ), 'link' );

	/* Frise */
	$wp_customize->add_section( 'euroccat_timeline', array( 'title' => __( 'Accueil : Frise des rencontres', 'euroccat' ), 'panel' => 'euroccat_panel', 'priority' => 35 ) );
	$add( 'timeline_kicker', 'euroccat_timeline', __( 'Surtitre', 'euroccat' ) );
	$add( 'timeline_title', 'euroccat_timeline', __( 'Titre', 'euroccat' ) );
	for ( $i = 1; $i <= 6; $i++ ) {
		/* translators: %d: number */
		$add( "tl_{$i}_year", 'euroccat_timeline', sprintf( __( 'Étape %d : année', 'euroccat' ), $i ) );
		/* translators: %d: number */
		$add( "tl_{$i}_title", 'euroccat_timeline', sprintf( __( 'Étape %d : titre', 'euroccat' ), $i ) );
		/* translators: %d: number */
		$add( "tl_{$i}_text", 'euroccat_timeline', sprintf( __( 'Étape %d : texte', 'euroccat' ), $i ), 'textarea' );
		/* translators: %d: number */
		$add( "tl_{$i}_url", 'euroccat_timeline', sprintf( __( 'Étape %d : lien', 'euroccat' ), $i ), 'link' );
	}

	/* Citation, blog, bibliothèque */
	$wp_customize->add_section( 'euroccat_more', array( 'title' => __( 'Accueil : Citation, blog, bibliothèque', 'euroccat' ), 'panel' => 'euroccat_panel', 'priority' => 40 ) );
	$add( 'quote_text', 'euroccat_more', __( 'Citation', 'euroccat' ), 'textarea' );
	$add( 'quote_author', 'euroccat_more', __( 'Auteur', 'euroccat' ) );
	$add( 'quote_role', 'euroccat_more', __( 'Source', 'euroccat' ) );
	$add( 'blog_kicker', 'euroccat_more', __( 'Blog : surtitre', 'euroccat' ) );
	$add( 'blog_title', 'euroccat_more', __( 'Blog : titre', 'euroccat' ) );
	$add( 'blog_count', 'euroccat_more', __( 'Blog : nombre d’articles', 'euroccat' ), 'number', array( 'input_attrs' => array( 'min' => 1, 'max' => 9 ) ) );
	$add( 'library_title', 'euroccat_more', __( 'Bibliothèque : titre', 'euroccat' ) );
	$add( 'library_text', 'euroccat_more', __( 'Bibliothèque : texte', 'euroccat' ), 'textarea' );
	$add( 'library_btn', 'euroccat_more', __( 'Bibliothèque : bouton', 'euroccat' ) );
	$add( 'library_url', 'euroccat_more', __( 'Bibliothèque : lien', 'euroccat' ), 'link' );

	/* Coordonnées */
	$wp_customize->add_section( 'euroccat_contact', array( 'title' => __( 'Coordonnées & réseaux', 'euroccat' ), 'panel' => 'euroccat_panel', 'priority' => 50 ) );
	$add( 'contact_email', 'euroccat_contact', __( 'E-mail (reçoit aussi le formulaire)', 'euroccat' ), 'email' );
	$add( 'contact_phone', 'euroccat_contact', __( 'Téléphone', 'euroccat' ) );
	$add( 'contact_address', 'euroccat_contact', __( 'Adresse', 'euroccat' ), 'textarea' );
	foreach ( array( 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'x' => 'X (Twitter)', 'youtube' => 'YouTube', 'linkedin' => 'LinkedIn' ) as $key => $label ) {
		$add( 'social_' . $key, 'euroccat_contact', $label, 'url' );
	}

	/* Pied de page */
	$wp_customize->add_section( 'euroccat_footer', array( 'title' => __( 'Pied de page', 'euroccat' ), 'panel' => 'euroccat_panel', 'priority' => 60 ) );
	$add( 'footer_text', 'euroccat_footer', __( 'Présentation', 'euroccat' ), 'textarea' );
	$add( 'footer_copy', 'euroccat_footer', __( 'Mention de copyright ({year} = année)', 'euroccat' ) );
}
add_action( 'customize_register', 'euroccat_customize_register' );

function euroccat_sanitize_checkbox( $value ) {
	return (bool) $value;
}

function euroccat_sanitize_link( $value ) {
	$value = trim( (string) $value );
	if ( 0 === strpos( $value, '/' ) || 0 === strpos( $value, '#' ) ) {
		return sanitize_text_field( $value );
	}
	return esc_url_raw( $value );
}

/**
 * Variables CSS générées à partir des couleurs choisies.
 */
function euroccat_customizer_css() {
	$map = array(
		'--c-primary'   => euroccat_mod( 'color_primary' ),
		'--c-accent'    => euroccat_mod( 'color_accent' ),
		'--c-secondary' => euroccat_mod( 'color_secondary' ),
		'--c-ink'       => euroccat_mod( 'color_ink' ),
		'--c-paper'     => euroccat_mod( 'color_paper' ),
	);
	$css = ':root{';
	foreach ( $map as $var => $value ) {
		$value = sanitize_hex_color( $value );
		if ( $value ) {
			$css .= $var . ':' . $value . ';';
		}
	}
	$css .= '}';
	return $css;
}
