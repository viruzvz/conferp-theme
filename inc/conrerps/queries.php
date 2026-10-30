<?php
/**
 * Consultas e normalização de dados dos Conselhos Regionais.
 * Reutilizável na Home e na página de Conrerps.
 * @package Conferp_Theme
 */
defined( 'ABSPATH' ) || exit;

/** Retorna regionais publicados e ativos, ordenados pela ordem administrativa. */
function conferp_conrerps_get_active( $args = array() ) {
    $defaults = array(
        'post_type'              => 'conferp_regional',
        'post_status'            => 'publish',
        'posts_per_page'         => -1,
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'orderby'                => array( 'title' => 'ASC' ),
        'suppress_filters'       => false,
    );
    // Sem meta_query: registros antigos sem chave de situação são considerados ativos.
    $posts = get_posts( wp_parse_args( $args, $defaults ) );
    $posts = array_values( array_filter( $posts, static function ( $post ) {
        $ativo = get_post_meta( $post->ID, '_conferp_regional_ativo', true );
        return '' === $ativo || '1' === (string) $ativo;
    } ) );
    usort( $posts, static function ( $a, $b ) {
        $a_ordem = (int) get_post_meta( $a->ID, '_conferp_regional_ordem', true );
        $b_ordem = (int) get_post_meta( $b->ID, '_conferp_regional_ordem', true );
        return ( $a_ordem <=> $b_ordem ) ?: strnatcasecmp( $a->post_title, $b->post_title );
    } );
    return $posts;
}

/** Retorna UFs cadastradas, ordenadas por nome; sem assumir que o slug seja o nome. */
function conferp_conrerps_get_ufs() {
    $terms = get_terms( array( 'taxonomy' => 'conferp_uf', 'hide_empty' => false, 'orderby' => 'name', 'order' => 'ASC' ) );
    return is_wp_error( $terms ) ? array() : $terms;
}

/**
 * Busca por sigla (DF) ou slug (df). Aceita associações múltiplas.
 * Retorna [] para entrada inválida ou UF inexistente.
 */
function conferp_conrerps_get_by_uf( $uf ) {
    if ( ! is_string( $uf ) ) {
        return array();
    }
    $slug = strtolower( trim( $uf ) );
    if ( ! preg_match( '/^[a-z]{2}$/', $slug ) ) {
        return array();
    }
    $term = get_term_by( 'slug', $slug, 'conferp_uf' );
    if ( ! $term || is_wp_error( $term ) ) {
        return array();
    }
    $ids = get_objects_in_term( (int) $term->term_id, 'conferp_uf' );
    if ( is_wp_error( $ids ) || empty( $ids ) ) {
        return array();
    }
    $id_map = array_fill_keys( array_map( 'intval', $ids ), true );
    return array_values( array_filter( conferp_conrerps_get_active(), static function ( $post ) use ( $id_map ) {
        return isset( $id_map[ $post->ID ] );
    } ) );
}

/** Quebra metadados de várias linhas em valores únicos não vazios. */
function conferp_conrerps_split_lines( $value ) {
    if ( ! is_string( $value ) || '' === trim( $value ) ) {
        return array();
    }
    return array_values( array_unique( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $value ) ), 'strlen' ) ) );
}

/** Completa protocolo de links antigos cadastrados sem http(s). */
function conferp_conrerps_normalize_url( $url ) {
    $url = trim( (string) $url );
    if ( '' === $url ) {
        return '';
    }
    if ( ! preg_match( '~^https?://~i', $url ) ) {
        $url = 'https://' . ltrim( $url, '/' );
    }
    return esc_url_raw( $url, array( 'http', 'https' ) );
}

/** Retorna dados estruturados sem gerar HTML (escapar somente ao renderizar). */
function conferp_conrerps_get_data( $regional ) {
    $post = get_post( $regional );
    if ( ! $post || 'conferp_regional' !== $post->post_type ) {
        return null;
    }
    $id = (int) $post->ID;
    $meta = static function ( $key ) use ( $id ) {
        return (string) get_post_meta( $id, '_conferp_regional_' . $key, true );
    };
    $terms = get_the_terms( $id, 'conferp_uf' );
    $ufs = array();
    if ( is_array( $terms ) ) {
        foreach ( $terms as $term ) {
            $ufs[] = array( 'sigla' => strtoupper( $term->slug ), 'nome' => $term->name );
        }
        usort( $ufs, static function ( $a, $b ) { return strcasecmp( $a['nome'], $b['nome'] ); } );
    }
    $telefones = array();
    foreach ( conferp_conrerps_split_lines( $meta( 'telefones' ) ) as $telefone ) {
        $numero = preg_replace( '/[^0-9+]/', '', $telefone );
        $telefones[] = array( 'texto' => $telefone, 'href' => $numero ? 'tel:' . $numero : '' );
    }
    $emails = array();
    foreach ( conferp_conrerps_split_lines( $meta( 'emails' ) ) as $email ) {
        if ( is_email( $email ) ) {
            $emails[] = $email;
        }
    }
    $whatsapp = preg_replace( '/\D+/', '', $meta( 'whatsapp' ) );
    $logo_id = absint( get_post_meta( $id, '_conferp_regional_logo_id', true ) );
    return array(
        'id'           => $id,
        'nome'         => get_the_title( $id ),
        'numero'       => $meta( 'numero' ),
        'sede'         => $meta( 'sede' ),
        'ordem'        => (int) $meta( 'ordem' ),
        'ufs'          => $ufs,
        'telefones'    => $telefones,
        'emails'       => $emails,
        'site'         => conferp_conrerps_normalize_url( $meta( 'site' ) ),
        'whatsapp'     => $whatsapp ? 'https://wa.me/' . $whatsapp : '',
        'endereco'     => $meta( 'endereco' ),
        'cidade'       => $meta( 'cidade' ),
        'uf_endereco'  => $meta( 'uf_endereco' ),
        'cep'          => $meta( 'cep' ),
        'localizacao'  => conferp_conrerps_normalize_url( $meta( 'localizacao' ) ),
        'horario'      => $meta( 'horario' ),
        'descricao'    => $meta( 'descricao' ),
        'logo_id'      => $logo_id,
        'logo_url'     => $logo_id ? (string) wp_get_attachment_url( $logo_id ) : '',
    );
}

/** Retorna dados prontos para as duas interfaces, sem exposição de registros inativos. */
function conferp_conrerps_get_public_data( $uf = '' ) {
    $posts = '' === $uf ? conferp_conrerps_get_active() : conferp_conrerps_get_by_uf( $uf );
    return array_values( array_filter( array_map( 'conferp_conrerps_get_data', $posts ) ) );
}
