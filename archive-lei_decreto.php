<?php
/** Archive — Leis e Decretos. @package conferp */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$filters     = conferp_leis_decretos_filters();
$documents   = conferp_leis_decretos_query( $filters );
$types       = get_terms( array( 'taxonomy' => 'legislacao_tipo', 'hide_empty' => true, 'orderby' => 'name' ) );
$years       = conferp_leis_decretos_years();
$archive_url = get_post_type_archive_link( 'lei_decreto' );
?>
<main id="primary" class="site-main leis-decretos-page">
    <?php get_template_part( 'template-parts/global/page-heading', null, array(
        'title' => __( 'Leis', 'conferp' ),
        'highlight' => __( 'e Decretos', 'conferp' ),
        'description' => __( 'Consulte leis e decretos relacionados ao exercício profissional de Relações Públicas.', 'conferp' ),
    ) ); ?>
    <section class="leis-decretos-search" aria-label="<?php esc_attr_e( 'Pesquisar leis e decretos', 'conferp' ); ?>">
        <div class="container">
            <?php get_template_part( 'template-parts/leis-decretos/leis-decretos-search-form', null, compact( 'filters', 'types', 'years', 'archive_url' ) ); ?>
        </div>
    </section>
    <div class="leis-decretos-results container">
        <p class="leis-decretos-page__count" aria-live="polite"><?php
            printf( esc_html( _n( '%d documento encontrado', '%d documentos encontrados', $documents->found_posts, 'conferp' ) ), absint( $documents->found_posts ) );
        ?></p>
        <div class="leis-decretos-layout">
            <div class="leis-decretos-layout__main">
                <?php get_template_part( 'template-parts/leis-decretos/leis-decretos-loop', null, array( 'documents' => $documents ) ); ?>
                <?php if ( $documents->max_num_pages > 1 ) : ?>
                    <?php get_template_part( 'template-parts/global/load-more', null, array(
                        'list'       => '.leis-decretos-lista',
                        'card'       => '.leis-decretos-card',
                        'pagination' => '.leis-decretos-pagination',
                        'total'      => $documents->found_posts,
                    ) ); ?>
                <?php endif; ?>
                <?php get_template_part( 'template-parts/leis-decretos/leis-decretos-pagination', null, compact( 'documents', 'filters' ) ); ?>
            </div>
            <aside class="leis-decretos-layout__sidebar" aria-label="<?php esc_attr_e( 'Filtros de leis e decretos', 'conferp' ); ?>">
                <?php get_template_part( 'template-parts/leis-decretos/leis-decretos-sidebar', null, compact( 'types', 'years', 'filters', 'archive_url' ) ); ?>
            </aside>
        </div>
    </div>
</main>
<?php wp_reset_postdata(); get_footer(); ?>
