<?php
/**
 * Template principal.
 *
 * @package CONFERP
 */

get_header();

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
else :
    echo '<p>' . esc_html__( 'Nenhum conteúdo encontrado.', 'conferp' ) . '</p>';
endif;

get_footer();
