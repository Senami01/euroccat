<?php
/**
 * Article de blog.
 */

get_header();

while ( have_posts() ) :
	the_post();
	$cats = get_the_category();
	$meta = '<span class="avatar">' . get_avatar( get_the_author_meta( 'ID' ), 40 ) . '</span>'
		. '<span>' . esc_html( get_the_author() ) . '</span><span class="card__dot"></span>'
		. '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';

	euroccat_page_hero(
		array(
			'kicker' => $cats ? $cats[0]->name : __( 'Article', 'euroccat' ),
			'title'  => esc_html( get_the_title() ),
			'lead'   => has_excerpt() ? esc_html( get_the_excerpt() ) : '',
			'image'  => get_post_thumbnail_id(),
			'meta'   => $meta,
		)
	);
	?>
	<article <?php post_class( 'section section--tight' ); ?>>
		<div class="container article-layout">
			<aside class="article-share" aria-label="<?php esc_attr_e( 'Partager', 'euroccat' ); ?>">
				<span><?php esc_html_e( 'Partager', 'euroccat' ); ?></span>
				<?php $u = rawurlencode( get_permalink() ); $t = rawurlencode( get_the_title() ); ?>
				<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr( $u ); ?>" target="_blank" rel="noopener" aria-label="Facebook"><?php echo euroccat_social_icon( 'facebook' ); // phpcs:ignore ?></a>
				<a href="https://x.com/intent/post?url=<?php echo esc_attr( $u ); ?>&amp;text=<?php echo esc_attr( $t ); ?>" target="_blank" rel="noopener" aria-label="X"><?php echo euroccat_social_icon( 'x' ); // phpcs:ignore ?></a>
				<a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo esc_attr( $u ); ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><?php echo euroccat_social_icon( 'linkedin' ); // phpcs:ignore ?></a>
				<button type="button" data-copy-link aria-label="<?php esc_attr_e( 'Copier le lien', 'euroccat' ); ?>"><svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path d="M10 14a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1 1M14 10a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
			</aside>

			<div class="entry-content entry-content--article">
				<?php
				the_content();
				wp_link_pages( array( 'before' => '<nav class="page-links">', 'after' => '</nav>' ) );
				?>
				<?php
				$tags = get_the_tags();
				if ( $tags ) {
					echo '<div class="tags">';
					foreach ( $tags as $tag ) {
						echo '<a class="chip" href="' . esc_url( get_tag_link( $tag ) ) . '">#' . esc_html( $tag->name ) . '</a>';
					}
					echo '</div>';
				}
				?>
			</div>
		</div>

		<div class="container container--narrow">
			<nav class="post-nav" aria-label="<?php esc_attr_e( 'Articles', 'euroccat' ); ?>">
				<?php
				$prev = get_previous_post();
				$next = get_next_post();
				if ( $prev ) {
					echo '<a class="post-nav__item" href="' . esc_url( get_permalink( $prev ) ) . '"><span>← ' . esc_html__( 'Article précédent', 'euroccat' ) . '</span><strong>' . esc_html( get_the_title( $prev ) ) . '</strong></a>';
				}
				if ( $next ) {
					echo '<a class="post-nav__item post-nav__item--next" href="' . esc_url( get_permalink( $next ) ) . '"><span>' . esc_html__( 'Article suivant', 'euroccat' ) . ' →</span><strong>' . esc_html( get_the_title( $next ) ) . '</strong></a>';
				}
				?>
			</nav>
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>
	</article>

	<?php
	$related = new WP_Query(
		array(
			'posts_per_page'      => 3,
			'post__not_in'        => array( get_the_ID() ),
			'category__in'        => wp_list_pluck( $cats, 'term_id' ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	if ( $related->have_posts() ) :
		?>
		<section class="section related">
			<div class="container">
				<div class="section-head"><h2 class="display display--sm"><?php esc_html_e( 'À lire aussi', 'euroccat' ); ?></h2></div>
				<div class="cards">
					<?php
					while ( $related->have_posts() ) {
						$related->the_post();
						euroccat_post_card();
					}
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php
	endif;
endwhile;

get_footer();
