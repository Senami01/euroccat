<?php
/**
 * Formulaire de recherche.
 */
$uid = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $uid ); ?>"><?php esc_html_e( 'Rechercher :', 'euroccat' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $uid ); ?>" class="search-form__input" placeholder="<?php esc_attr_e( 'Eurocongrès, Agaches, occitan…', 'euroccat' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
	<button type="submit" class="search-form__btn" aria-label="<?php esc_attr_e( 'Lancer la recherche', 'euroccat' ); ?>"><?php echo euroccat_arrow(); // phpcs:ignore ?></button>
</form>
