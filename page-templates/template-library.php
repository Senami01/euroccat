<?php
/**
 * Template Name: Bibliothèque de documents
 * Description: Liste automatiquement tous les PDF de la médiathèque, avec recherche instantanée et filtres par année.
 */

get_header();

while ( have_posts() ) :
	the_post();
	euroccat_page_hero(
		array(
			'kicker' => __( 'Archives & publications', 'euroccat' ),
			'title'  => esc_html( get_the_title() ),
			'lead'   => has_excerpt() ? esc_html( get_the_excerpt() ) : '',
			'image'  => get_post_thumbnail_id(),
		)
	);

	$docs = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'application/pdf',
			'posts_per_page' => -1,
			'post_status'    => 'inherit',
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);

	$years = array();
	foreach ( $docs as $doc ) {
		$y = '';
		if ( preg_match( '/(19|20)\d{2}/', $doc->post_title . ' ' . $doc->post_excerpt . ' ' . basename( get_attached_file( $doc->ID ) ), $m ) ) {
			$y = $m[0];
		}
		$doc->euroccat_year = $y;
		if ( $y ) {
			$years[ $y ] = true;
		}
	}
	krsort( $years );
	?>
	<div class="section section--tight">
		<div class="container">
			<?php if ( trim( get_the_content() ) ) : ?>
				<div class="entry-content container--content library-intro"><?php the_content(); ?></div>
			<?php endif; ?>

			<div class="library-tools">
				<label class="library-search">
					<svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><path d="m20 20-3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
					<span class="screen-reader-text"><?php esc_html_e( 'Filtrer les documents', 'euroccat' ); ?></span>
					<input type="search" placeholder="<?php esc_attr_e( 'Rechercher un document…', 'euroccat' ); ?>" data-lib-search />
				</label>
				<div class="filters" data-lib-filters>
					<button type="button" class="filter is-active" data-year=""><?php esc_html_e( 'Toutes les années', 'euroccat' ); ?></button>
					<?php foreach ( array_keys( $years ) as $y ) : ?>
						<button type="button" class="filter" data-year="<?php echo esc_attr( $y ); ?>"><?php echo esc_html( $y ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( $docs ) : ?>
				<ul class="library-grid" data-lib-list>
					<?php foreach ( $docs as $doc ) : ?>
						<?php $title = get_the_title( $doc ); ?>
						<li class="doc reveal" data-year="<?php echo esc_attr( $doc->euroccat_year ); ?>" data-title="<?php echo esc_attr( strtolower( remove_accents( $doc->post_title . ' ' . $title . ' ' . $doc->post_excerpt ) ) ); ?>">
							<div class="doc__cover" aria-hidden="true">
								<span class="doc__fold"></span>
								<span class="doc__ext">PDF</span>
								<?php echo euroccat_cross_svg( 'doc__cross' ); // phpcs:ignore ?>
								<?php if ( $doc->euroccat_year ) : ?>
									<span class="doc__year"><?php echo esc_html( $doc->euroccat_year ); ?></span>
								<?php endif; ?>
							</div>
							<div class="doc__body">
								<h3 class="doc__title"><?php echo esc_html( $title ); ?></h3>
								<?php if ( $doc->post_excerpt ) : ?>
									<p class="doc__desc"><?php echo esc_html( $doc->post_excerpt ); ?></p>
								<?php endif; ?>
								<p class="doc__meta"><?php echo esc_html( euroccat_file_size( $doc->ID ) ); ?></p>
								<div class="doc__actions">
									<a class="btn btn--small btn--primary" href="<?php echo esc_url( wp_get_attachment_url( $doc->ID ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Lire', 'euroccat' ); ?></a>
									<a class="btn btn--small btn--outline" href="<?php echo esc_url( wp_get_attachment_url( $doc->ID ) ); ?>" download><?php echo euroccat_download_icon(); // phpcs:ignore ?> <?php esc_html_e( 'Télécharger', 'euroccat' ); ?></a>
								</div>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
				<p class="library-empty" data-lib-empty hidden><?php esc_html_e( 'Aucun document ne correspond à votre recherche.', 'euroccat' ); ?></p>
			<?php else : ?>
				<p class="library-empty"><?php esc_html_e( 'Ajoutez des fichiers PDF dans la médiathèque : ils apparaîtront ici automatiquement.', 'euroccat' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<?php
endwhile;

get_footer();
