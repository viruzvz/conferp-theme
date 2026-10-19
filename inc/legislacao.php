<?php
/**
 * Agrupador administrativo Legislação (sem CPT ou página pública própria).
 * Os CPTs independentes usam show_in_menu => conferp-legislacao.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function conferp_legislacao_admin_menu() {
    add_menu_page(
        __( 'Legislação', 'conferp' ),
        __( 'Legislação', 'conferp' ),
        'edit_posts',
        'conferp-legislacao',
        'conferp_legislacao_admin_landing',
        'dashicons-media-document',
        25
    );

    // Evita um submenu redundante com o mesmo nome do agrupador.
    remove_submenu_page( 'conferp-legislacao', 'conferp-legislacao' );
}
add_action( 'admin_menu', 'conferp_legislacao_admin_menu', 9 );

function conferp_legislacao_admin_landing() {
    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_die( esc_html__( 'Você não tem permissão para acessar esta página.', 'conferp' ) );
    }

    echo '<div class="wrap"><h1>' . esc_html__( 'Legislação', 'conferp' ) . '</h1>';
    echo '<p>' . esc_html__( 'Selecione um módulo no submenu para administrar os documentos.', 'conferp' ) . '</p></div>';
}
