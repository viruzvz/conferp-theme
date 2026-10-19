<?php
/** Sidebar independente de Leis e Decretos. @package conferp */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$types = $args['types'];
$years = $args['years'];
$filters = $args['filters'];
$url = $args['archive_url'];
?>
<div class="leis-decretos-sidebar">
    <section class="leis-decretos-sidebar__panel">
        <h2 class="leis-decretos-sidebar__title"><?php esc_html_e( 'Acesso rápido', 'conferp' ); ?></h2>
        <div class="leis-decretos-sidebar__content">
            <ul class="leis-decretos-sidebar__list">
                <?php if ( ! is_wp_error( $types ) ) : foreach ( $types as $term ) :
                    $link = add_query_arg( array_filter( array( 'ld_tipo' => $term->slug, 'ld_ano' => $filters['ano'], 'ld_busca' => $filters['q'], 'ld_ordem' => $filters['ordem'] ) ), $url ); ?>
                    <li><a href="<?php echo esc_url( $link ); ?>" <?php if ( $filters['tipo'] === $term->slug ) : ?>aria-current="page"<?php endif; ?>><span><?php echo esc_html( $term->name ); ?></span><span class="leis-decretos-sidebar__count"><?php echo esc_html( (string) $term->count ); ?></span></a></li>
                <?php endforeach; endif; ?>
            </ul>
        </div>
    </section>
    <section class="leis-decretos-sidebar__panel">
        <h2 class="leis-decretos-sidebar__title"><?php esc_html_e( 'Ano', 'conferp' ); ?></h2>
        <div class="leis-decretos-sidebar__content">
            <ul class="leis-decretos-sidebar__list">
                <?php foreach ( $years as $year ) :
                    $link = add_query_arg( array_filter( array( 'ld_ano' => $year, 'ld_tipo' => $filters['tipo'], 'ld_busca' => $filters['q'], 'ld_ordem' => $filters['ordem'] ) ), $url ); ?>
                    <li><a href="<?php echo esc_url( $link ); ?>" <?php if ( (string) $filters['ano'] === (string) $year ) : ?>aria-current="page"<?php endif; ?>><span><?php echo esc_html( $year ); ?></span></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
</div>
