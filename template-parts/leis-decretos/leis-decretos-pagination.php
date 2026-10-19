<?php
/** Module pagination. @package conferp */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$documents = $args['documents']; $filters = $args['filters'];
if ( $documents->max_num_pages < 2 ) { return; }
$params = array_filter( array(
    'ld_busca' => $filters['q'], 'ld_tipo' => $filters['tipo'], 'ld_ano' => $filters['ano'], 'ld_ordem' => $filters['ordem'],
) );
$links = paginate_links( array(
    'total' => (int) $documents->max_num_pages,
    'current' => max( 1, absint( get_query_var( 'paged' ) ) ),
    'add_args' => $params,
    'prev_text' => __( '← Anterior', 'conferp' ),
    'next_text' => __( 'Próxima →', 'conferp' ),
    'type' => 'list',
) );
if ( $links ) : ?>
<nav class="leis-decretos-pagination" aria-label="<?php esc_attr_e( 'Paginação de leis e decretos', 'conferp' ); ?>"><?php echo wp_kses_post( $links ); ?></nav>
<?php endif; ?>
