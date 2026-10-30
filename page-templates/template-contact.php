<?php
/**
 * Template Name: Contact
 * Description: Coordonnées (réglées dans Personnaliser → Coordonnées & réseaux) et formulaire de contact intégré.
 */

get_header();

while ( have_posts() ) :
	the_post();
	euroccat_page_hero(
		array(
			'kicker' => __( 'Contact', 'euroccat' ),
			'title'  => esc_html( get_the_title() ),
			'lead'   => has_excerpt() ? esc_html( get_the_excerpt() ) : esc_html__( 'Pour toute question sur l’association ou ses colloques.', 'euroccat' ),
			'image'  => get_post_thumbnail_id(),
		)
	);
	$email = euroccat_mod( 'contact_email' );
	$phone = euroccat_mod( 'contact_phone' );
	$addr  = euroccat_mod( 'contact_address' );
	?>
	<div class="section section--tight">
		<div class="container contact-grid">
			<aside class="contact-card">
				<?php echo euroccat_cross_svg( 'contact-card__cross' ); // phpcs:ignore ?>
				<h2><?php esc_html_e( 'Coordonnées', 'euroccat' ); ?></h2>
				<ul class="contact-list">
					<?php if ( $addr ) : ?>
						<li><span class="contact-list__label"><?php esc_html_e( 'Adresse', 'euroccat' ); ?></span><?php echo nl2br( esc_html( $addr ) ); ?></li>
					<?php endif; ?>
					<?php if ( $email ) : ?>
						<li><span class="contact-list__label"><?php esc_html_e( 'E-mail', 'euroccat' ); ?></span><a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a></li>
					<?php endif; ?>
					<?php if ( $phone ) : ?>
						<li><span class="contact-list__label"><?php esc_html_e( 'Téléphone', 'euroccat' ); ?></span><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
					<?php endif; ?>
				</ul>
				<?php euroccat_social_links( 'socials socials--light' ); ?>
			</aside>
			<div class="contact-main" id="contact-form">
				<?php if ( trim( get_the_content() ) ) : ?>
					<div class="entry-content"><?php the_content(); ?></div>
				<?php endif; ?>
				<?php echo euroccat_contact_form_html(); // phpcs:ignore ?>
			</div>
		</div>
	</div>
	<?php
endwhile;

get_footer();
