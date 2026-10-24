<?php
/** Consulta pública independente de Resoluções Normativas. @package conferp */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function conferp_rn_filters() {
    $read = static function ( $key ) {
        return isset( $_GET[ $key ] ) && is_string( $_GET[ $key ] )
            ? sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) : '';
    };
    $order = $read( 'rn_ordem' );
    $year = $read( 'rn_ano' );
    return array(
        'q' => $read( 'rn_busca' ),
        'assunto' => sanitize_title( $read( 'rn_assunto' ) ),
        'ano' => preg_match( '/^\d{4}$/', $year ) ? $year : '',
        'ordem' => in_array( $order, array( 'recentes', 'antigas' ), true ) ? $order : 'recentes',
    );
}

function conferp_rn_years() {
    global $wpdb;
    $years = $wpdb->get_col( $wpdb->prepare(
        "SELECT DISTINCT LEFT(pm.meta_value, 4) FROM {$wpdb->postmeta} pm
         INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         WHERE p.post_type = %s AND p.post_status = 'publish'
         AND pm.meta_key = %s AND pm.meta_value REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
         ORDER BY 1 DESC", 'resolucao_normativa', '_resolucao_data'
    ) );
    return array_map( 'strval', $years );
}

/** Applies only to queries explicitly marked as the RN archive. */
function conferp_rn_search_sql( $search, $query ) {
    if ( ! $query->get( 'conferp_rn_archive' ) ) { return $search; }
    $term = $query->get( 'conferp_rn_term' );
    if ( ! is_string( $term ) || '' === trim( $term ) ) { return ''; }
    global $wpdb;
    $like = '%' . $wpdb->esc_like( trim( $term ) ) . '%';
    return $wpdb->prepare(
        " AND ({$wpdb->posts}.post_title LIKE %s OR {$wpdb->posts}.post_excerpt LIKE %s
        OR {$wpdb->posts}.post_content LIKE %s OR EXISTS (
            SELECT 1 FROM {$wpdb->postmeta} rn_meta
            WHERE rn_meta.post_id = {$wpdb->posts}.ID
            AND rn_meta.meta_key IN ('_resolucao_numero', '_resolucao_ementa')
            AND rn_meta.meta_value LIKE %s
        ))", $like, $like, $like, $like
    );
}
add_filter( 'posts_search', 'conferp_rn_search_sql', 10, 2 );

function conferp_rn_order_sql( $orderby, $query ) {
    if ( ! $query->get( 'conferp_rn_archive' ) ) { return $orderby; }
    global $wpdb;
    $direction = 'ASC' === $query->get( 'conferp_rn_direction' ) ? 'ASC' : 'DESC';
    return "COALESCE((SELECT NULLIF(pm.meta_value, '') FROM {$wpdb->postmeta} pm
            WHERE pm.post_id = {$wpdb->posts}.ID AND pm.meta_key = '_resolucao_data'
            LIMIT 1), DATE({$wpdb->posts}.post_date)) {$direction}, {$wpdb->posts}.ID {$direction}";
}
add_filter( 'posts_orderby', 'conferp_rn_order_sql', 10, 2 );

function conferp_rn_query( $filters ) {
    $args = array(
        'post_type' => 'resolucao_normativa',
        'post_status' => 'publish',
        'posts_per_page' => 6,
        'paged' => max( 1, absint( get_query_var( 'paged' ) ) ),
        'ignore_sticky_posts' => true,
        'conferp_rn_archive' => true,
        'conferp_rn_term' => $filters['q'],
        'conferp_rn_direction' => 'antigas' === $filters['ordem'] ? 'ASC' : 'DESC',
    );
    if ( $filters['assunto'] ) {
        if ( term_exists( $filters['assunto'], 'resolucao_assunto' ) ) {
            $args['tax_query'] = array( array(
                'taxonomy' => 'resolucao_assunto', 'field' => 'slug', 'terms' => $filters['assunto'],
            ) );
        } else {
            $args['post__in'] = array( 0 );
        }
    }
    if ( $filters['ano'] ) {
        $args['meta_query'] = array( array(
            'key' => '_resolucao_data', 'value' => $filters['ano'] . '-', 'compare' => 'LIKE',
        ) );
    }
    return new WP_Query( $args );
}

function conferp_rn_date( $post_id ) {
    $date = get_post_meta( $post_id, '_resolucao_data', true );
    if ( ! is_string( $date ) || ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) { return ''; }
    $timestamp = strtotime( $date . ' 12:00:00 UTC' );
    return $timestamp ? wp_date( get_option( 'date_format' ), $timestamp, new DateTimeZone( 'UTC' ) ) : '';
}

/**
 * Alinha a consulta principal do WordPress à paginação do arquivo.
 * Sem isso, /page/2/ pode retornar 404 antes do template executar
 * sua consulta secundária, especialmente quando o padrão do site é 10 posts.
 */
function conferp_rn_main_archive_query( $query ) {
    if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'resolucao_normativa' ) ) {
        return;
    }

    $filters = conferp_rn_filters();
    $query->set( 'posts_per_page', 6 );
    $query->set( 'conferp_rn_archive', true );
    $query->set( 'conferp_rn_term', $filters['q'] );
    $query->set( 'conferp_rn_direction', 'antigas' === $filters['ordem'] ? 'ASC' : 'DESC' );

    if ( $filters['assunto'] ) {
        if ( term_exists( $filters['assunto'], 'resolucao_assunto' ) ) {
            $query->set( 'tax_query', array( array(
                'taxonomy' => 'resolucao_assunto',
                'field'    => 'slug',
                'terms'    => $filters['assunto'],
            ) ) );
        } else {
            $query->set( 'post__in', array( 0 ) );
        }
    }

    if ( $filters['ano'] ) {
        $query->set( 'meta_query', array( array(
            'key'     => '_resolucao_data',
            'value'   => $filters['ano'] . '-',
            'compare' => 'LIKE',
        ) ) );
    }
}
add_action( 'pre_get_posts', 'conferp_rn_main_archive_query' );
