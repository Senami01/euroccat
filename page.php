<?php
/**
 * Page standard.
 */

get_header();

while ( have_posts() ) :
	the_post();
	$parent = wp_get_post_parent_id( get_the_ID() );
	euroccat_page_hero(
		array(
			'kicker' => $parent ? get_the_title( $parent ) : get_bloginfo( 'name' ),
			'title'  => esc_html( get_the_title() ),
			'lead'   => has_excerpt() ? esc_html( get_the_excerpt() ) : '',
			'image'  => get_post_thumbnail_id(),
		)
	);
	?>
	<div class="section section--tight">
		<div class="container container--content">
			<div class="entry-content">
				<?php
				the_content();
				wp_link_pages( array( 'before' => '<nav class="page-links">', 'after' => '</nav>' ) );
				?>
			</div>
		</div>
	</div>
	<?php
	get_template_part( 'template-parts/siblings' );

	if ( comments_open() || get_comments_number() ) {
		echo '<div class="container container--narrow">';
		comments_template();
		echo '</div>';
	}
endwhile;

get_footer();
