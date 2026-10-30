<?php
/**
 * Formulaire de contact intégré (sans extension).
 * Utilisable via le modèle de page « Contact » ou le code court [euroccat_contact].
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function euroccat_contact_form_html() {
	$status = isset( $_GET['contact'] ) ? sanitize_key( wp_unslash( $_GET['contact'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	ob_start();
	?>
	<?php if ( 'ok' === $status ) : ?>
		<div class="notice notice--success" role="status"><?php esc_html_e( 'Merci ! Votre message a bien été envoyé. Nous vous répondrons rapidement.', 'euroccat' ); ?></div>
	<?php elseif ( 'error' === $status ) : ?>
		<div class="notice notice--error" role="alert"><?php esc_html_e( 'Le message n’a pas pu être envoyé. Vérifiez les champs obligatoires et réessayez.', 'euroccat' ); ?></div>
	<?php endif; ?>
	<form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="euroccat_contact" />
		<input type="hidden" name="redirect" value="<?php echo esc_url( get_permalink() ); ?>" />
		<?php wp_nonce_field( 'euroccat_contact', 'euroccat_contact_nonce' ); ?>
		<div class="hp-field" aria-hidden="true"><label>Site web <input type="text" name="website" tabindex="-1" autocomplete="off" /></label></div>
		<div class="form-row form-row--2">
			<p class="field">
				<input id="cf-name" type="text" name="name" required placeholder=" " autocomplete="name" />
				<label for="cf-name"><?php esc_html_e( 'Nom complet', 'euroccat' ); ?> *</label>
			</p>
			<p class="field">
				<input id="cf-email" type="email" name="email" required placeholder=" " autocomplete="email" />
				<label for="cf-email"><?php esc_html_e( 'Adresse e-mail', 'euroccat' ); ?> *</label>
			</p>
		</div>
		<p class="field">
			<input id="cf-subject" type="text" name="subject" placeholder=" " />
			<label for="cf-subject"><?php esc_html_e( 'Sujet', 'euroccat' ); ?></label>
		</p>
		<p class="field">
			<textarea id="cf-message" name="message" rows="6" required placeholder=" "></textarea>
			<label for="cf-message"><?php esc_html_e( 'Votre message', 'euroccat' ); ?> *</label>
		</p>
		<p class="form-consent">
			<label><input type="checkbox" name="consent" value="1" required /> <?php esc_html_e( 'J’accepte que mes données soient utilisées pour répondre à ma demande.', 'euroccat' ); ?></label>
		</p>
		<button class="btn btn--primary" type="submit"><?php esc_html_e( 'Envoyer le message', 'euroccat' ); ?> <?php echo euroccat_arrow(); // phpcs:ignore ?></button>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'euroccat_contact', 'euroccat_contact_form_html' );

function euroccat_handle_contact() {
	$redirect = isset( $_POST['redirect'] ) ? esc_url_raw( wp_unslash( $_POST['redirect'] ) ) : home_url( '/' );
	$redirect = wp_validate_redirect( $redirect, home_url( '/' ) );

	$valid = isset( $_POST['euroccat_contact_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['euroccat_contact_nonce'] ) ), 'euroccat_contact' );

	// Pot de miel anti-robots : on fait semblant que tout va bien.
	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'ok', $redirect ) . '#contact-form' );
		exit;
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( ! $valid || ! $name || ! is_email( $email ) || ! $message ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $redirect ) . '#contact-form' );
		exit;
	}

	$to = euroccat_mod( 'contact_email' );
	if ( ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}

	$sent = wp_mail(
		$to,
		sprintf( '[%s] %s', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), $subject ? $subject : __( 'Nouveau message', 'euroccat' ) ),
		sprintf( "%s : %s\n%s : %s\n\n%s", __( 'Nom', 'euroccat' ), $name, __( 'E-mail', 'euroccat' ), $email, $message ),
		array( 'Reply-To: ' . $name . ' <' . $email . '>' )
	);

	wp_safe_redirect( add_query_arg( 'contact', $sent ? 'ok' : 'error', $redirect ) . '#contact-form' );
	exit;
}
add_action( 'admin_post_nopriv_euroccat_contact', 'euroccat_handle_contact' );
add_action( 'admin_post_euroccat_contact', 'euroccat_handle_contact' );
