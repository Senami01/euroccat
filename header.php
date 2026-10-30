<?php
/**
 * En-tête du site.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="theme-color" content="<?php echo esc_attr( euroccat_mod( 'color_ink' ) ); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Aller au contenu', 'euroccat' ); ?></a>
<div class="progress" aria-hidden="true"><span></span></div>

<header class="site-header" id="site-header">
	<div class="container site-header__inner">
		<?php euroccat_site_logo(); ?>

		<nav class="main-nav" id="main-nav" aria-label="<?php esc_attr_e( 'Navigation principale', 'euroccat' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'menu',
					'fallback_cb'    => 'euroccat_menu_fallback',
					'depth'          => 2,
				)
			);
			?>
			<div class="main-nav__mobile-extra">
				<?php euroccat_social_links( 'socials socials--nav' ); ?>
			</div>
		</nav>

		<div class="site-header__actions">
			<?php if ( get_theme_mod( 'euroccat_show_search', true ) ) : ?>
				<button class="icon-btn" type="button" data-search-open aria-label="<?php esc_attr_e( 'Rechercher', 'euroccat' ); ?>">
					<svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><path d="m20 20-3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
				</button>
			<?php endif; ?>
			<?php if ( get_theme_mod( 'euroccat_show_theme_toggle', true ) ) : ?>
				<button class="icon-btn theme-toggle" type="button" data-theme-toggle aria-label="<?php esc_attr_e( 'Basculer le mode sombre', 'euroccat' ); ?>">
					<svg class="i-sun" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4.5" fill="currentColor"/><g stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></g></svg>
					<svg class="i-moon" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 14.5A8.5 8.5 0 0 1 9.5 3.5a8.5 8.5 0 1 0 11 11z" fill="currentColor"/></svg>
				</button>
			<?php endif; ?>
			<?php
			$cta = euroccat_mod( 'header_cta_label' );
			if ( $cta ) :
				?>
				<a class="btn btn--small btn--primary site-header__cta" href="<?php echo esc_url( euroccat_link( euroccat_mod( 'header_cta_url' ) ) ); ?>"><?php echo esc_html( $cta ); ?></a>
			<?php endif; ?>
			<button class="burger" type="button" aria-controls="main-nav" aria-expanded="false" data-menu-toggle>
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'euroccat' ); ?></span>
				<span class="burger__line"></span><span class="burger__line"></span>
			</button>
		</div>
	</div>
</header>

<div class="search-overlay" id="search-overlay" hidden>
	<button class="search-overlay__close" type="button" data-search-close aria-label="<?php esc_attr_e( 'Fermer la recherche', 'euroccat' ); ?>">×</button>
	<div class="container container--narrow">
		<p class="kicker"><?php esc_html_e( 'Rechercher sur le site', 'euroccat' ); ?></p>
		<?php get_search_form(); ?>
	</div>
</div>

<main id="main" class="site-main" tabindex="-1">
