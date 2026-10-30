<?php
/**
 * Blog, archives, catégories et résultats de recherche.
 */

get_header();

$kicker = __( 'Le blog', 'euroccat' );
$title  = __( 'Blog', 'euroccat' );
$lead   = '';

if ( is_home() && get_option( 'page_for_posts' ) ) {
	$blog_id = (int) get_option( 'page_for_posts' );
	$title   = get_the_title( $blog_id );
	$lead    = has_excerpt( $blog_id ) ? get_the_excerpt( $blog_id ) : euroccat_mod( 'blog_title' );
} elseif ( is_search() ) {
	$kicker = __( 'Recherche', 'euroccat' );
	/* translators: %s: search query */
	$title = sprintf( __( 'Résultats pour « %s »', 'euroccat' ), esc_html( get_search_query() ) );
	/* translators: %d: number of results */
	$lead = sprintf( _n( '%d résultat trouvé', '%d résultats trouvés', (int) $wp_query->found_posts, 'euroccat' ), (int) $wp_query->found_posts );
} elseif ( is_archive() ) {
	$kicker = is_category() ? __( 'Catégorie', 'euroccat' ) : ( is_tag() ? __( 'Étiquette', 'euroccat' ) : __( 'Archives', 'euroccat' ) );
	$title  = get_the_archive_title();
	$title  = preg_replace( '/^[^:]+:\s*/u', '', wp_strip_all_tags( $title ) );
	$lead   = wp_strip_all_tags( get_the_archive_description() );
}

euroccat_page_hero(
	array(
		'kicker' => $kicker,
		'title'  => esc_html( $title ),
		'lead'   => esc_html( $lead ),
	)
);
?>

<div class="section section--tight">
	<div class="container">
		<?php
		$cats = get_categories( array( 'hide_empty' => true ) );
		if ( ! is_search() && count( $cats ) > 0 ) :
			$blog_url = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' );
			?>
			<nav class="filters" aria-label="<?php esc_attr_e( 'Catégories', 'euroccat' ); ?>">
				<a class="filter<?php echo is_home() ? ' is-active' : ''; ?>" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'Tout', 'euroccat' ); ?></a>
				<?php foreach ( $cats as $cat ) : ?>
					<a class="filter<?php echo is_category( $cat->term_id ) ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?> <sup><?php echo (int) $cat->count; ?></sup></a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="cards cards--blog">
				<?php
				$i = 0;
				while ( have_posts() ) {
					the_post();
					if ( 'post' !== get_post_type() ) {
						?>
						<article class="card card--simple reveal">
							<div class="card__body">
								<div class="card__meta"><span class="chip"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></span></div>
								<h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p class="card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
								<a class="link-arrow" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Voir la page', 'euroccat' ); ?> <?php echo euroccat_arrow(); // phpcs:ignore ?></a>
							</div>
						</article>
						<?php
					} else {
						euroccat_post_card( array( 'featured' => 0 === $i && ! is_paged() && is_home() ) );
					}
					$i++;
				}
				?>
			</div>
			<?php euroccat_pagination(); ?>
		<?php else : ?>
			<div class="empty-state">
				<?php echo euroccat_cross_svg( 'empty-state__cross' ); // phpcs:ignore ?>
				<h2><?php esc_html_e( 'Rien à afficher pour le moment', 'euroccat' ); ?></h2>
				<p><?php esc_html_e( 'Essayez une autre recherche ou revenez bientôt.', 'euroccat' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
