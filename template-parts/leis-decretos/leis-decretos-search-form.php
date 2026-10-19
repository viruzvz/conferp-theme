<?php
/** Module-specific search and filters. @package conferp */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$filters = $args['filters']; $types = $args['types']; $years = $args['years']; $archive_url = $args['archive_url'];
?>
<form class="leis-decretos-filtros" method="get" action="<?php echo esc_url( $archive_url ); ?>" role="search">
    <div class="leis-decretos-filtros__search">
        <label for="ld_busca"><?php esc_html_e( 'Pesquisar legislação', 'conferp' ); ?></label>
        <input id="ld_busca" type="search" name="ld_busca" value="<?php echo esc_attr( $filters['q'] ); ?>" placeholder="<?php esc_attr_e( 'Buscar por título, número, ementa ou texto do documento...', 'conferp' ); ?>">
    </div>
    <div class="leis-decretos-filtros__grid">
        <div class="leis-decretos-filtros__field"><label for="ld_tipo"><?php esc_html_e( 'Tipo de documento', 'conferp' ); ?></label>
            <select name="ld_tipo" id="ld_tipo"><option value=""><?php esc_html_e( 'Todos os tipos', 'conferp' ); ?></option>
                <?php if ( ! is_wp_error( $types ) ) : foreach ( $types as $term ) : ?>
                    <option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $filters['tipo'], $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="leis-decretos-filtros__field"><label for="ld_ano"><?php esc_html_e( 'Ano do documento', 'conferp' ); ?></label>
            <select name="ld_ano" id="ld_ano"><option value=""><?php esc_html_e( 'Todos os anos', 'conferp' ); ?></option>
                <?php foreach ( $years as $year ) : ?><option value="<?php echo esc_attr( $year ); ?>" <?php selected( $filters['ano'], $year ); ?>><?php echo esc_html( $year ); ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="leis-decretos-filtros__field"><label for="ld_ordem"><?php esc_html_e( 'Ordenação', 'conferp' ); ?></label>
            <select name="ld_ordem" id="ld_ordem"><option value="recentes" <?php selected( $filters['ordem'], 'recentes' ); ?>><?php esc_html_e( 'Mais recentes', 'conferp' ); ?></option><option value="antigas" <?php selected( $filters['ordem'], 'antigas' ); ?>><?php esc_html_e( 'Mais antigas', 'conferp' ); ?></option></select>
        </div>
    </div>
    <div class="leis-decretos-filtros__actions"><button type="submit"><?php esc_html_e( 'Filtrar documentos', 'conferp' ); ?></button><a href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'Limpar filtros', 'conferp' ); ?></a></div>
</form>
