<?php
/**
 * Pied de page.
 */
?>
</main>

<footer class="site-footer">
	<div class="site-footer__flag" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
	<div class="container">
		<div class="site-footer__big" aria-hidden="true">
			<span>Òc</span><?php echo euroccat_cross_svg( 'site-footer__cross' ); // phpcs:ignore ?><span>Sí</span>
		</div>

		<div class="site-footer__grid">
			<div class="site-footer__about">
				<?php euroccat_site_logo(); ?>
				<p><?php echo esc_html( euroccat_mod( 'footer_text' ) ); ?></p>
				<?php euroccat_social_links(); ?>
			</div>

			<div>
				<h3 class="site-footer__title"><?php esc_html_e( 'Explorer', 'euroccat' ); ?></h3>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => has_nav_menu( 'footer' ) ? 'footer' : 'primary',
						'container'      => false,
						'menu_class'     => 'footer-menu',
						'depth'          => 1,
						'fallback_cb'    => 'euroccat_menu_fallback',
					)
				);
				?>
			</div>

			<div>
				<h3 class="site-footer__title"><?php esc_html_e( 'Contact', 'euroccat' ); ?></h3>
				<ul class="footer-contact">
					<?php
					$addr  = euroccat_mod( 'contact_address' );
					$email = euroccat_mod( 'contact_email' );
					$phone = euroccat_mod( 'contact_phone' );
					if ( $addr ) {
						echo '<li>' . nl2br( esc_html( $addr ) ) . '</li>';
					}
					if ( $email ) {
						echo '<li><a href="mailto:' . esc_attr( antispambot( $email ) ) . '">' . esc_html( antispambot( $email ) ) . '</a></li>';
					}
					if ( $phone ) {
						echo '<li><a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a></li>';
					}
					?>
					<li><a class="link-arrow" href="<?php echo esc_url( euroccat_link( euroccat_mod( 'header_cta_url' ) ) ); ?>"><?php esc_html_e( 'Écrire à l’association', 'euroccat' ); ?> <?php echo euroccat_arrow(); // phpcs:ignore ?></a></li>
				</ul>
			</div>

			<?php if ( is_active_sidebar( 'sidebar-footer' ) ) : ?>
				<div class="site-footer__widgets"><?php dynamic_sidebar( 'sidebar-footer' ); ?></div>
			<?php endif; ?>
		</div>

		<div class="site-footer__bottom">
			<p><?php echo esc_html( str_replace( '{year}', gmdate( 'Y' ), euroccat_mod( 'footer_copy' ) ) ); ?></p>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'legal',
					'container'      => false,
					'menu_class'     => 'legal-menu',
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
			<a class="back-to-top" href="#main" data-back-to-top><?php esc_html_e( 'Haut de page', 'euroccat' ); ?> ↑</a>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
