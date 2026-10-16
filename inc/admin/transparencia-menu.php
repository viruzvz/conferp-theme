<?php
/**
 * Agrupamento administrativo: Transparência.
 * Cada CPT aparece como UM submenu; ações e taxonomias ficam na navegação contextual.
 * @package conferp
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function conferp_register_transparencia_admin_menu() {
    add_menu_page(
        __( 'Transparência', 'conferp' ),
        __( 'Transparência', 'conferp' ),
        'edit_posts',
        'conferp-transparencia',
        'conferp_render_transparencia_admin_page',
        'dashicons-visibility',
        23
    );
    add_submenu_page(
        'conferp-transparencia',
        __( 'Visão geral', 'conferp' ),
        __( 'Visão geral', 'conferp' ),
        'edit_posts',
        'conferp-transparencia',
        'conferp_render_transparencia_admin_page'
    );
}
add_action( 'admin_menu', 'conferp_register_transparencia_admin_menu', 5 );

/** Renomeia apenas o submenu automático do CPT, sem adicionar entradas avulsas. */
function conferp_rename_transparencia_atas_submenu() {
    global $submenu;
    if ( empty( $submenu['conferp-transparencia'] ) ) { return; }
    foreach ( $submenu['conferp-transparencia'] as &$item ) {
        if ( isset( $item[2] ) && 'edit.php?post_type=ata_reuniao' === $item[2] ) {
            $item[0] = __( 'Atas e Reuniões', 'conferp' );
        }
    }
    unset( $item );
}
add_action( 'admin_menu', 'conferp_rename_transparencia_atas_submenu', 999 );

/** Identifica telas de listagem, edição e taxonomias deste módulo. */
function conferp_is_atas_admin_screen( $screen ) {
    if ( ! $screen ) { return false; }
    if ( 'ata_reuniao' === $screen->post_type ) { return true; }
    return in_array( $screen->taxonomy, array( 'ata_tipo', 'ata_assunto', 'ata_gestao' ), true );
}

/** Preserva o destaque do agrupador quando uma tela do módulo estiver aberta. */
function conferp_transparencia_parent_file( $parent_file ) {
    if ( conferp_is_atas_admin_screen( get_current_screen() ) ) {
        return 'conferp-transparencia';
    }
    return $parent_file;
}
add_filter( 'parent_file', 'conferp_transparencia_parent_file' );

/** Seleciona Atas e Reuniões no submenu mesmo durante edição ou taxonomias. */
function conferp_transparencia_submenu_file( $submenu_file, $parent_file ) {
    if ( 'conferp-transparencia' === $parent_file && conferp_is_atas_admin_screen( get_current_screen() ) ) {
        return 'edit.php?post_type=ata_reuniao';
    }
    return $submenu_file;
}
add_filter( 'submenu_file', 'conferp_transparencia_submenu_file', 10, 2 );

/** Navegação contextual reaproveitável para as telas nativas de Atas. */
function conferp_render_atas_admin_navigation() {
    $screen = get_current_screen();
    if ( ! conferp_is_atas_admin_screen( $screen ) ) { return; }

    $links = array(
        array( __( 'Todas as Atas', 'conferp' ), admin_url( 'edit.php?post_type=ata_reuniao' ), 'list', 'edit_posts' ),
        array( __( 'Adicionar nova ata', 'conferp' ), admin_url( 'post-new.php?post_type=ata_reuniao' ), 'new', 'edit_posts' ),
        array( __( 'Tipos de Reunião', 'conferp' ), admin_url( 'edit-tags.php?taxonomy=ata_tipo&post_type=ata_reuniao' ), 'ata_tipo', 'manage_categories' ),
        array( __( 'Assuntos', 'conferp' ), admin_url( 'edit-tags.php?taxonomy=ata_assunto&post_type=ata_reuniao' ), 'ata_assunto', 'manage_categories' ),
        array( __( 'Mandatos', 'conferp' ), admin_url( 'edit-tags.php?taxonomy=ata_gestao&post_type=ata_reuniao' ), 'ata_gestao', 'manage_categories' ),
    );

    $active = 'list';
    if ( in_array( $screen->taxonomy, array( 'ata_tipo', 'ata_assunto', 'ata_gestao' ), true ) ) {
        $active = $screen->taxonomy;
    } elseif ( 'post' === $screen->base && 'add' === $screen->action ) {
        $active = 'new';
    } elseif ( 'post' === $screen->base ) {
        $active = 'list';
    }

    echo '<div class="notice inline" style="padding:12px 16px;margin:16px 20px 12px 0;border-left-color:#205584;">';
    echo '<strong style="display:block;margin-bottom:8px;">' . esc_html__( 'Transparência / Atas e Reuniões', 'conferp' ) . '</strong>';
    echo '<nav aria-label="' . esc_attr__( 'Navegação administrativa de Atas e Reuniões', 'conferp' ) . '" style="display:flex;flex-wrap:wrap;gap:8px;">';
    foreach ( $links as $link ) {
        if ( ! current_user_can( $link[3] ) ) { continue; }
        $class = $active === $link[2] ? 'button button-primary' : 'button';
        echo '<a class="' . esc_attr( $class ) . '" href="' . esc_url( $link[1] ) . '">' . esc_html( $link[0] ) . '</a>';
    }
    echo '</nav></div>';
}
add_action( 'admin_notices', 'conferp_render_atas_admin_navigation' );

function conferp_render_transparencia_admin_page() {
    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_die( esc_html__( 'Acesso não autorizado.', 'conferp' ) );
    }
    echo '<div class="wrap"><h1>' . esc_html__( 'Transparência', 'conferp' ) . '</h1>';
    echo '<p>' . esc_html__( 'Gerencie os módulos documentais publicados no portal CONFERP.', 'conferp' ) . '</p>';
    echo '<p><a class="button button-primary" href="' . esc_url( admin_url( 'edit.php?post_type=ata_reuniao' ) ) . '">' . esc_html__( 'Atas e Reuniões', 'conferp' ) . '</a></p></div>';
}
