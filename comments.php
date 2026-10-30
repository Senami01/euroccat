<?php
/**
 * Commentaires.
 */
if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments__title">
			<?php
			/* translators: %s: number of comments */
			printf( esc_html( _n( '%s commentaire', '%s commentaires', get_comments_number(), 'euroccat' ) ), esc_html( number_format_i18n( get_comments_number() ) ) );
			?>
		</h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>
	<?php
	comment_form(
		array(
			'title_reply'        => __( 'Laisser un commentaire', 'euroccat' ),
			'class_submit'       => 'btn btn--primary',
			'title_reply_before' => '<h2 id="reply-title" class="comments__title">',
			'title_reply_after'  => '</h2>',
		)
	);
	?>
</section>
