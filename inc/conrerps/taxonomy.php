<?php
/**
 * UFs atendidas por cada Conselho Regional.
 *
 * @package Conferp_Theme
 */
defined( 'ABSPATH' ) || exit;

add_action( 'init', 'conferp_register_uf_taxonomy', 11 );
add_action( 'init', 'conferp_seed_uf_terms', 20 );

function conferp_register_uf_taxonomy() {
    register_taxonomy( 'conferp_uf', array( 'conferp_regional' ), array(
        'labels' => array(
            'name'          => 'Estados atendidos',
            'singular_name' => 'Estado atendido',
            'search_items'  => 'Pesquisar estados',
            'all_items'     => 'Todos os estados',
            'edit_item'     => 'Editar estado',
            'update_item'   => 'Atualizar estado',
        ),
        'public'            => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'hierarchical'      => false,
        'rewrite'           => false,
        'meta_box_cb'       => 'post_categories_meta_box',
    ) );
}

function conferp_seed_uf_terms() {
    $version = '1';
    if ( get_option( 'conferp_uf_seed_version' ) === $version ) {
        return;
    }

    $ufs = array(
        'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá',
        'AM' => 'Amazonas', 'BA' => 'Bahia', 'CE' => 'Ceará',
        'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
        'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso',
        'MS' => 'Mato Grosso do Sul', 'MG' => 'Minas Gerais',
        'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
        'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro',
        'RN' => 'Rio Grande do Norte', 'RS' => 'Rio Grande do Sul',
        'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
        'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins',
    );

    foreach ( $ufs as $sigla => $nome ) {
        $slug = strtolower( $sigla );
        if ( ! term_exists( $slug, 'conferp_uf' ) ) {
            $result = wp_insert_term( $nome, 'conferp_uf', array( 'slug' => $slug ) );
            if ( is_wp_error( $result ) ) {
                return; // Tentará novamente em uma próxima requisição.
            }
        }
    }

    update_option( 'conferp_uf_seed_version', $version, false );
}
