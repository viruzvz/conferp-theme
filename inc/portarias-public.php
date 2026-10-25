<?php
/** Consulta pública independente de Portarias. @package conferp */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function conferp_pt_filters() {
    $read = static function ( $key ) {
        return isset( $_GET[ $key ] ) && is_string( $_GET[ $key ] )
            ? sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) : '';
    };
    $order = $read( 'pt_ordem' );
    $year = $read( 'pt_ano' );
    return array(
        'q' => $read( 'pt_busca' ),
        'assunto' => sanitize_title( $read( 'pt_assunto' ) ),
        'situacao' => sanitize_title( $read( 'pt_situacao' ) ),
        'numero' => preg_replace( '/[^0-9]/', '', $read( 'pt_numero' ) ),
        'ano' => preg_match( '/^\d{4}$/', $year ) ? $year : '',
        'ordem' => in_array( $order, array( 'recentes', 'antigas' ), true ) ? $order : 'recentes',
    );
}

function conferp_pt_years() {
    global $wpdb;
    $years = $wpdb->get_col( $wpdb->prepare(
        "SELECT DISTINCT LEFT(pm.meta_value, 4) FROM {$wpdb->postmeta} pm
         INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         WHERE p.post_type = %s AND p.post_status = 'publish'
         AND pm.meta_key = %s AND pm.meta_value REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
         ORDER BY 1 DESC", 'portaria', '_portaria_data'
    ) );
    return array_map( 'strval', $years );
}

/** Applies only to queries explicitly marked as the RN archive. */
function conferp_pt_search_sql( $search, $query ) {
    if ( ! $query->get( 'conferp_pt_archive' ) ) { return $search; }
    $term = $query->get( 'conferp_pt_term' );
    if ( ! is_string( $term ) || '' === trim( $term ) ) { return ''; }
    global $wpdb;
    $like = '%' . $wpdb->esc_like( trim( $term ) ) . '%';
    return $wpdb->prepare(
        " AND ({$wpdb->posts}.post_title LIKE %s OR {$wpdb->posts}.post_excerpt LIKE %s
        OR {$wpdb->posts}.post_content LIKE %s OR EXISTS (
            SELECT 1 FROM {$wpdb->postmeta} pt_meta
            WHERE pt_meta.post_id = {$wpdb->posts}.ID
            AND pt_meta.meta_key IN ('_portaria_numero', '_portaria_ementa')
            AND pt_meta.meta_value LIKE %s
        ))", $like, $like, $like, $like
    );
}
add_filter( 'posts_search', 'conferp_pt_search_sql', 10, 2 );

function conferp_pt_order_sql( $orderby, $query ) {
    if ( ! $query->get( 'conferp_pt_archive' ) ) { return $orderby; }
    global $wpdb;
    $direction = 'ASC' === $query->get( 'conferp_pt_direction' ) ? 'ASC' : 'DESC';
    return "COALESCE((SELECT NULLIF(pm.meta_value, '') FROM {$wpdb->postmeta} pm
            WHERE pm.post_id = {$wpdb->posts}.ID AND pm.meta_key = '_portaria_data'
            LIMIT 1), DATE({$wpdb->posts}.post_date)) {$direction}, {$wpdb->posts}.ID {$direction}";
}
add_filter( 'posts_orderby', 'conferp_pt_order_sql', 10, 2 );

function conferp_pt_query( $filters ) {
    $args = array(
        'post_type' => 'portaria',
        'post_status' => 'publish',
        'posts_per_page' => 6,
        'paged' => max( 1, absint( get_query_var( 'paged' ) ) ),
        'ignore_sticky_posts' => true,
        'conferp_pt_archive' => true,
        'conferp_pt_term' => $filters['q'],
        'conferp_pt_direction' => 'antigas' === $filters['ordem'] ? 'ASC' : 'DESC',
    );
    if ( $filters['assunto'] ) {
        if ( term_exists( $filters['assunto'], 'portaria_assunto' ) ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'portaria_assunto', 'field' => 'slug', 'terms' => $filters['assunto'],
            );
        } else {
            $args['post__in'] = array( 0 );
        }
    }
    if ( $filters['ano'] ) {
        $args['meta_query'][] = array( 'key' => '_portaria_data', 'value' => $filters['ano'] . '-', 'compare' => 'LIKE' );
    }
    if ( $filters['numero'] ) {
        $args['meta_query'][] = array( 'key' => '_portaria_numero', 'value' => $filters['numero'], 'compare' => '=' );
    }
    if ( $filters['situacao'] ) {
        $args['tax_query'][] = array( 'taxonomy' => 'portaria_situacao', 'field' => 'slug', 'terms' => $filters['situacao'] );
    }
    return new WP_Query( $args );
}

function conferp_pt_date( $post_id ) {
    $date = get_post_meta( $post_id, '_portaria_data', true );
    if ( ! is_string( $date ) || ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) { return ''; }
    $timestamp = strtotime( $date . ' 12:00:00 UTC' );
    return $timestamp ? wp_date( get_option( 'date_format' ), $timestamp, new DateTimeZone( 'UTC' ) ) : '';
}

/**
 * Alinha a consulta principal do WordPress à paginação do arquivo.
 * Sem isso, /page/2/ pode retornar 404 antes do template executar
 * sua consulta secundária, especialmente quando o padrão do site é 10 posts.
 */
function conferp_pt_main_archive_query( $query ) {
    if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'portaria' ) ) {
        return;
    }

    $filters = conferp_pt_filters();
    $query->set( 'posts_per_page', 6 );
    $query->set( 'conferp_pt_archive', true );
    $query->set( 'conferp_pt_term', $filters['q'] );
    $query->set( 'conferp_pt_direction', 'antigas' === $filters['ordem'] ? 'ASC' : 'DESC' );

    if ( $filters['assunto'] ) {
        if ( term_exists( $filters['assunto'], 'portaria_assunto' ) ) {
            $query->set( 'tax_query', array( array(
                'taxonomy' => 'portaria_assunto',
                'field'    => 'slug',
                'terms'    => $filters['assunto'],
            ) ) );
        } else {
            $query->set( 'post__in', array( 0 ) );
        }
    }

    $meta_query = array();
    if ( $filters['ano'] ) { $meta_query[] = array( 'key' => '_portaria_data', 'value' => $filters['ano'] . '-', 'compare' => 'LIKE' ); }
    if ( $filters['numero'] ) { $meta_query[] = array( 'key' => '_portaria_numero', 'value' => $filters['numero'], 'compare' => '=' ); }
    if ( $meta_query ) { $query->set( 'meta_query', $meta_query ); }
    if ( $filters['situacao'] ) {
        $tax_query = $query->get( 'tax_query' );
        if ( ! is_array( $tax_query ) ) { $tax_query = array(); }
        $tax_query[] = array( 'taxonomy' => 'portaria_situacao', 'field' => 'slug', 'terms' => $filters['situacao'] );
        $query->set( 'tax_query', $tax_query );
    }
}
add_action( 'pre_get_posts', 'conferp_pt_main_archive_query' );
