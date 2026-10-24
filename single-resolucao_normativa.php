<?php
/** Documento individual de Resolução Normativa. @package conferp */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<main id="primary" class="site-main legislation-single resolucao-single">
<?php while ( have_posts() ) : the_post(); ?>
    <?php get_template_part( 'template-parts/global/page-heading', null, array( 'title' => get_the_title() ) ); ?>
    <div class="container legislation-single__container">
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'institutional-document' ); ?>>
            <?php get_template_part( 'template-parts/document/document-header' ); ?>
            <div class="institutional-document__content entry-content">
                <?php the_content(); ?>
                <?php wp_link_pages( array( 'before' => '<nav class="institutional-document__page-links" aria-label="' . esc_attr__( 'Páginas do documento', 'conferp' ) . '">', 'after' => '</nav>' ) ); ?>
            </div>
            <?php get_template_part( 'template-parts/document/document-footer' ); ?>
        </article>
        <?php get_template_part( 'template-parts/document/document-actions' ); ?>
    </div>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
