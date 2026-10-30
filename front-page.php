<?php
/**
 * Page d'accueil : entièrement pilotée par Apparence → Personnaliser → Thème Euroccat.
 */

get_header();

$show = function ( $key ) {
	return (bool) get_theme_mod( 'euroccat_show_' . $key, true );
};
$hero_img = (int) get_theme_mod( 'euroccat_hero_image', 0 );
?>

<!-- ============ BANNIÈRE ============ -->
<section class="hero<?php echo $hero_img ? ' hero--image' : ''; ?>" data-hero>
	<div class="hero__bg" aria-hidden="true">
		<?php if ( $hero_img ) : ?>
			<?php echo wp_get_attachment_image( $hero_img, 'euroccat-hero', false, array( 'class' => 'hero__photo', 'loading' => 'eager', 'fetchpriority' => 'high', 'alt' => '' ) ); ?>
		<?php endif; ?>
				<svg class="hero__thread" viewBox="0 0 1440 600" preserveAspectRatio="none">
			<path d="M-20 420 C 120 300, 200 520, 340 400 S 520 220, 640 330 S 820 520, 960 360 S 1180 140, 1300 260 S 1420 380, 1470 300" />
		</svg>
	</div>

	<div class="container hero__inner">
		<div class="hero__content">
			<p class="kicker kicker--light hero__kicker"><?php echo esc_html( euroccat_mod( 'hero_kicker' ) ); ?></p>
			<h1 class="hero__title">
				<span class="hero__line"><span><?php echo esc_html( euroccat_mod( 'hero_title_1' ) ); ?></span></span>
				<span class="hero__line hero__line--amp"><span>&amp;</span></span>
				<span class="hero__line hero__line--outline"><span><?php echo esc_html( euroccat_mod( 'hero_title_2' ) ); ?></span></span>
			</h1>
			<p class="hero__text"><?php echo esc_html( euroccat_mod( 'hero_text' ) ); ?></p>
			<div class="hero__actions">
				<?php if ( euroccat_mod( 'hero_btn1_label' ) ) : ?>
					<a class="btn btn--primary" href="<?php echo esc_url( euroccat_link( euroccat_mod( 'hero_btn1_url' ) ) ); ?>"><?php echo esc_html( euroccat_mod( 'hero_btn1_label' ) ); ?> <?php echo euroccat_arrow(); // phpcs:ignore ?></a>
				<?php endif; ?>
				<?php if ( euroccat_mod( 'hero_btn2_label' ) ) : ?>
					<a class="btn btn--ghost" href="<?php echo esc_url( euroccat_link( euroccat_mod( 'hero_btn2_url' ) ) ); ?>"><?php echo esc_html( euroccat_mod( 'hero_btn2_label' ) ); ?></a>
				<?php endif; ?>
			</div>
		</div>

		<div class="hero__emblem" aria-hidden="true" data-tilt>
			<div class="emblem">
				<div class="emblem__disc">
					<span class="emblem__senyera"></span>
					<span class="emblem__night"></span>
				</div>
				<?php echo euroccat_cross_svg( 'emblem__cross' ); // phpcs:ignore ?>
				<div class="emblem__ring">
					<?php
					for ( $i = 0; $i < 12; $i++ ) {
						echo '<i style="--i:' . (int) $i . '">' . euroccat_star_svg() . '</i>'; // phpcs:ignore
					}
					?>
				</div>
				<svg class="emblem__text" viewBox="0 0 400 400">
					<defs><path id="emblem-circle" d="M200,200 m-176,0 a176,176 0 1,1 352,0 a176,176 0 1,1 -352,0"/></defs>
					<text><textPath href="#emblem-circle">PAÏSES OCCITANS · PAÏSOS CATALANS · EUROPA · PAÏSES OCCITANS · PAÏSOS CATALANS · EUROPA ·</textPath></text>
				</svg>
				<?php if ( get_theme_mod( 'euroccat_hero_show_logo', true ) ) : ?>
					<figure class="emblem__stamp">
						<img src="<?php echo esc_url( EUROCCAT_URI . '/assets/img/euroccat-logo.jpg' ); ?>" alt="" width="227" height="158" />
						<figcaption>Eurocongrès 2000</figcaption>
					</figure>
				<?php endif; ?>
			</div>
		</div>
	</div>

</section>

<div id="after-hero"></div>

<?php if ( $show( 'marquee' ) ) : ?>
<!-- ============ BANDEAU ============ -->
<section class="marquee" aria-label="<?php esc_attr_e( 'Thèmes', 'euroccat' ); ?>">
	<div class="marquee__track">
		<?php
		$words = array_filter( array_map( 'trim', preg_split( '/[·|\n]/u', euroccat_mod( 'marquee' ) ) ) );
		for ( $r = 0; $r < 2; $r++ ) {
			echo '<div class="marquee__group"' . ( $r ? ' aria-hidden="true"' : '' ) . '>';
			foreach ( $words as $w ) {
				echo '<span>' . esc_html( $w ) . '</span>' . euroccat_cross_svg( 'marquee__sep' ); // phpcs:ignore
			}
			echo '</div>';
		}
		?>
	</div>
</section>
<?php endif; ?>

<?php if ( $show( 'about' ) || $show( 'stats' ) ) : ?>
<!-- ============ À PROPOS + CHIFFRES ============ -->
<section class="section about">
	<div class="container">
		<?php if ( $show( 'about' ) ) : ?>
			<div class="about__grid">
				<div class="about__head reveal">
					<?php if ( euroccat_mod( 'about_kicker' ) ) : ?><p class="kicker"><?php echo esc_html( euroccat_mod( 'about_kicker' ) ); ?></p><?php endif; ?>
					<h2 class="display"><?php echo esc_html( euroccat_mod( 'about_title' ) ); ?></h2>
				</div>
				<div class="about__body reveal">
					<p class="lead"><?php echo esc_html( euroccat_mod( 'about_text' ) ); ?></p>
					<?php if ( euroccat_mod( 'about_link_label' ) ) : ?>
						<a class="link-arrow link-arrow--big" href="<?php echo esc_url( euroccat_link( euroccat_mod( 'about_link_url' ) ) ); ?>"><?php echo esc_html( euroccat_mod( 'about_link_label' ) ); ?> <?php echo euroccat_arrow(); // phpcs:ignore ?></a>
					<?php endif; ?>
				</div>
			</div>

		<?php endif; ?>

		<?php if ( $show( 'stats' ) ) : ?>
			<div class="stats">
				<?php
				for ( $i = 1; $i <= 4; $i++ ) :
					$num = euroccat_mod( "stat_{$i}_num" );
					if ( '' === $num ) {
						continue;
					}
					?>
					<div class="stat reveal" style="--d:<?php echo (int) $i; ?>">
						<span class="stat__num" data-count="<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $num ) ); ?>"><?php echo esc_html( $num ); ?></span>
						<span class="stat__label"><?php echo esc_html( euroccat_mod( "stat_{$i}_label" ) ); ?></span>
					</div>
				<?php endfor; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php if ( $show( 'timeline' ) ) : ?>
<!-- ============ FRISE ============ -->
<section class="section timeline-section">
	<div class="container">
		<div class="section-head reveal">
			<div>
				<?php if ( euroccat_mod( 'timeline_kicker' ) ) : ?><p class="kicker kicker--light"><?php echo esc_html( euroccat_mod( 'timeline_kicker' ) ); ?></p><?php endif; ?>
				<h2 class="display"><?php echo esc_html( euroccat_mod( 'timeline_title' ) ); ?></h2>
			</div>
			<div class="timeline__nav">
				<button type="button" class="round-btn" data-tl-prev aria-label="<?php esc_attr_e( 'Précédent', 'euroccat' ); ?>">←</button>
				<button type="button" class="round-btn" data-tl-next aria-label="<?php esc_attr_e( 'Suivant', 'euroccat' ); ?>">→</button>
			</div>
		</div>
	</div>
	<div class="timeline" data-timeline>
		<div class="timeline__rail" aria-hidden="true"></div>
		<ol class="timeline__list">
			<?php
			for ( $i = 1; $i <= 6; $i++ ) :
				$title = euroccat_mod( "tl_{$i}_title" );
				if ( ! $title ) {
					continue;
				}
				$url = euroccat_link( euroccat_mod( "tl_{$i}_url" ) );
				?>
				<li class="tl-item">
					<span class="tl-item__dot" aria-hidden="true"></span>
					<span class="tl-item__year"><?php echo esc_html( euroccat_mod( "tl_{$i}_year" ) ); ?></span>
					<h3 class="tl-item__title"><?php echo esc_html( $title ); ?></h3>
					<p class="tl-item__text"><?php echo esc_html( euroccat_mod( "tl_{$i}_text" ) ); ?></p>
					<?php if ( $url ) : ?>
						<a class="tl-item__link stretched" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Découvrir', 'euroccat' ); ?> <?php echo euroccat_arrow(); // phpcs:ignore ?></a>
					<?php endif; ?>
				</li>
			<?php endfor; ?>
		</ol>
	</div>
</section>
<?php endif; ?>

<?php if ( $show( 'quote' ) && euroccat_mod( 'quote_text' ) ) : ?>
<!-- ============ CITATION ============ -->
<section class="quote-section">
	<?php echo euroccat_cross_svg( 'quote-section__cross' ); // phpcs:ignore ?>
	<div class="container container--narrow">
		<figure class="big-quote reveal">
			<blockquote><p class="split-words">« <?php echo esc_html( euroccat_mod( 'quote_text' ) ); ?> »</p></blockquote>
			<figcaption>
				<strong><?php echo esc_html( euroccat_mod( 'quote_author' ) ); ?></strong>
				<span><?php echo esc_html( euroccat_mod( 'quote_role' ) ); ?></span>
			</figcaption>
		</figure>
	</div>
</section>
<?php endif; ?>

<?php
if ( $show( 'blog' ) ) :
	$latest = new WP_Query(
		array(
			'posts_per_page'      => max( 1, (int) euroccat_mod( 'blog_count' ) ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	if ( $latest->have_posts() ) :
		$blog_page = (int) get_option( 'page_for_posts' );
		?>
<!-- ============ BLOG ============ -->
<section class="section latest">
	<div class="container">
		<div class="section-head reveal">
			<div>
				<?php if ( euroccat_mod( 'blog_kicker' ) ) : ?><p class="kicker"><?php echo esc_html( euroccat_mod( 'blog_kicker' ) ); ?></p><?php endif; ?>
				<h2 class="display"><?php echo esc_html( euroccat_mod( 'blog_title' ) ); ?></h2>
			</div>
			<?php if ( $blog_page ) : ?>
				<a class="btn btn--outline" href="<?php echo esc_url( get_permalink( $blog_page ) ); ?>"><?php esc_html_e( 'Tous les articles', 'euroccat' ); ?> <?php echo euroccat_arrow(); // phpcs:ignore ?></a>
			<?php endif; ?>
		</div>
		<div class="cards cards--home">
			<?php
			$n = 0;
			while ( $latest->have_posts() ) {
				$latest->the_post();
				euroccat_post_card( array( 'featured' => 0 === $n && ( 1 === $latest->post_count || $latest->post_count > 2 ) ) );
				$n++;
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
		<?php
	endif;
endif;
?>

<?php if ( $show( 'library' ) ) : ?>
<!-- ============ BIBLIOTHÈQUE ============ -->
<section class="section library-cta">
	<div class="container library-cta__grid">
		<div class="reveal">
			<h2 class="display"><?php echo esc_html( euroccat_mod( 'library_title' ) ); ?></h2>
			<p class="lead"><?php echo esc_html( euroccat_mod( 'library_text' ) ); ?></p>
			<a class="btn btn--accent" href="<?php echo esc_url( euroccat_link( euroccat_mod( 'library_url' ) ) ); ?>"><?php echo esc_html( euroccat_mod( 'library_btn' ) ); ?> <?php echo euroccat_arrow(); // phpcs:ignore ?></a>
		</div>
		<ul class="doc-stack reveal">
			<?php
			$docs = get_posts(
				array(
					'post_type'      => 'attachment',
					'post_mime_type' => 'application/pdf',
					'posts_per_page' => 4,
					'post_status'    => 'inherit',
				)
			);
			foreach ( $docs as $k => $doc ) :
				?>
				<li style="--k:<?php echo (int) $k; ?>">
					<a href="<?php echo esc_url( wp_get_attachment_url( $doc->ID ) ); ?>" target="_blank" rel="noopener">
						<span class="doc-stack__type">PDF</span>
						<span class="doc-stack__title"><?php echo esc_html( get_the_title( $doc ) ); ?></span>
						<span class="doc-stack__icon"><?php echo euroccat_download_icon(); // phpcs:ignore ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
<?php endif; ?>

<?php
// Contenu libre de la page d'accueil (éditeur de blocs), affiché s'il existe.
while ( have_posts() ) :
	the_post();
	if ( trim( get_the_content() ) ) :
		?>
		<section class="section">
			<div class="container container--narrow entry-content"><?php the_content(); ?></div>
		</section>
		<?php
	endif;
endwhile;

get_footer();
