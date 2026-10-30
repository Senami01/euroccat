<?php
/**
 * Template Name: Pleine largeur (sans bandeau)
 * Description: Page libre pour composer avec l'éditeur de blocs, sans bandeau de titre.
 */
get_header();
while ( have_posts() ) :
	the_post();
	echo '<div class="entry-content entry-content--full">';
	the_content();
	echo '</div>';
endwhile;
get_footer();
