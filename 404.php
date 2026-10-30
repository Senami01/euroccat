<?php
/**
 * Page introuvable.
 */
get_header();
?>
<section class="error-404">
	<div class="container">
		<div class="error-404__num" aria-hidden="true"><span>4</span><?php echo euroccat_cross_svg( 'error-404__cross' ); // phpcs:ignore ?><span>4</span></div>
		<h1 class="display"><?php esc_html_e( 'Aquesta pàgina s’es perduda…', 'euroccat' ); ?></h1>
		<p class="lead"><?php esc_html_e( 'La page que vous cherchez n’existe pas ou a été déplacée. Reprenez le chemin depuis l’accueil ou lancez une recherche.', 'euroccat' ); ?></p>
		<div class="error-404__search"><?php get_search_form(); ?></div>
		<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Retour à l’accueil', 'euroccat' ); ?> <?php echo euroccat_arrow(); // phpcs:ignore ?></a>
	</div>
</section>
<?php
get_footer();
