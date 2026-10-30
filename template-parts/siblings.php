<?php
/**
 * Navigation entre pages sœurs (ex. : les différentes rencontres) ou vers les sous-pages.
 */

$current = get_the_ID();
$parent  = wp_get_post_parent_id( $current );
$pages   = get_pages(
	array(
		'parent'      => $parent ? $parent : $current,
		'sort_column' => 'menu_order,post_title',
	)
);
$pages   = array_filter(
	$pages,
	function ( $p ) use ( $current ) {
		return (int) $p->ID !== (int) $current;
	}
);
if ( ! $pages ) {
	return;
}
?>
<section class="section siblings">
	<div class="container">
		<div class="section-head">
			<div>
				<h2 class="display display--sm"><?php echo $parent ? esc_html__( 'À découvrir aussi', 'euroccat' ) : esc_html__( 'Dans cette rubrique', 'euroccat' ); ?></h2>
			</div>
		</div>
		<div class="sibling-grid">
			<?php foreach ( $pages as $p ) : ?>
				<?php preg_match( '/(19|20)\d{2}/', $p->post_title, $year ); ?>
				<a class="sibling reveal" href="<?php echo esc_url( get_permalink( $p ) ); ?>">
					<span class="sibling__year"><?php echo esc_html( $year ? $year[0] : '' ); ?></span>
					<span class="sibling__title"><?php echo esc_html( $p->post_title ); ?></span>
					<?php if ( $p->post_excerpt ) : ?>
						<span class="sibling__text"><?php echo esc_html( wp_trim_words( $p->post_excerpt, 18 ) ); ?></span>
					<?php endif; ?>
					<span class="sibling__arrow"><?php echo euroccat_arrow(); // phpcs:ignore ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
