<?php
/**
 * Resoluções Normativas: cadastro, taxonomia e metadados.
 * O conteúdo integral é editado no Gutenberg; não exige PDF.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Registra o módulo de forma independente de Leis e Decretos. */
function conferp_register_resolucoes_normativas() {
	register_post_type(
		'resolucao_normativa',
		array(
			'labels' => array(
				'name'          => __( 'Resoluções Normativas', 'conferp' ),
				'singular_name' => __( 'Resolução Normativa', 'conferp' ),
				'menu_name'     => __( 'Resoluções Normativas', 'conferp' ),
				'all_items'     => __( 'Resoluções Normativas', 'conferp' ),
				'add_new_item'  => __( 'Adicionar Resolução Normativa', 'conferp' ),
				'edit_item'     => __( 'Editar Resolução Normativa', 'conferp' ),
				'view_item'     => __( 'Ver Resolução Normativa', 'conferp' ),
				'not_found'     => __( 'Nenhuma resolução encontrada.', 'conferp' ),
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_in_rest'       => true,
			'show_in_menu'       => 'conferp-legislacao',
			'has_archive'        => 'resolucoes-normativas',
			'rewrite'            => array( 'slug' => 'resolucoes-normativas', 'with_front' => false ),
			'supports'           => array( 'title', 'editor', 'revisions', 'author' ),
			'taxonomies'         => array( 'resolucao_assunto' ),
			'menu_icon'          => 'dashicons-media-text',
		),
	);

	register_taxonomy(
		'resolucao_assunto',
		array( 'resolucao_normativa' ),
		array(
			'labels' => array(
				'name'          => __( 'Assuntos', 'conferp' ),
				'singular_name' => __( 'Assunto', 'conferp' ),
				'menu_name'     => __( 'Assuntos', 'conferp' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'assunto-resolucao', 'with_front' => false ),
		),
	);

	foreach ( conferp_resolucoes_meta_fields() as $key => $field ) {
		register_post_meta(
			'resolucao_normativa',
			$key,
			array(
				'type'         => 'string',
				'single'       => true,
				'show_in_rest' => true,
				'auth_callback' => static function ( $allowed, $meta_key, $post_id ) {
					return current_user_can( 'edit_post', $post_id );
				},
			),
		);
	}
}
add_action( 'init', 'conferp_register_resolucoes_normativas' );

/** Definição centralizada dos campos, sem interferir nos metadados de outros CPTs. */
function conferp_resolucoes_meta_fields() {
	return array(
		'_resolucao_numero'      => array( 'Número da resolução', 'text', 'Ex.: 135' ),
		'_resolucao_data'        => array( 'Data da resolução', 'date', '' ),
		'_resolucao_ementa'      => array( 'Ementa oficial', 'textarea', '' ),
		'_resolucao_fonte'       => array( 'URL da fonte oficial (opcional)', 'url', 'https://' ),
		'_resolucao_dou_data'    => array( 'Data da publicação no DOU (opcional)', 'date', '' ),
		'_resolucao_dou_edicao'  => array( 'Edição do DOU (opcional)', 'text', 'Ex.: 30' ),
		'_resolucao_dou_secao'   => array( 'Seção do DOU (opcional)', 'text', 'Ex.: 1' ),
		'_resolucao_dou_pagina'  => array( 'Página do DOU (opcional)', 'text', 'Ex.: 198' ),
	);
}

/** Insere apenas os termos ainda ausentes; não altera atribuições já cadastradas. */
function conferp_resolucoes_seed_assuntos() {
	$terms = array(
		'registro-profissional'                 => 'Registro Profissional',
		'fiscalizacao-profissional'            => 'Fiscalização Profissional',
		'etica-e-disciplina'                   => 'Ética e Disciplina',
		'organizacao-e-governanca-institucional' => 'Organização e Governança Institucional',
		'eleicoes-e-mandatos'                  => 'Eleições e Mandatos',
		'anuidades-e-emolumentos'              => 'Anuidades e Emolumentos',
		'prazos-e-parcelamentos'               => 'Prazos e Parcelamentos',
		'procedimentos-e-normas-gerais'        => 'Procedimentos e Normas Gerais',
		'formacao-e-habilitacao-profissional'  => 'Formação e Habilitação Profissional',
	);
	foreach ( $terms as $slug => $name ) {
		if ( ! term_exists( $slug, 'resolucao_assunto' ) ) {
			wp_insert_term( $name, 'resolucao_assunto', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'init', 'conferp_resolucoes_seed_assuntos', 20 );

/** Campos administrativos separados do texto integral. */
function conferp_resolucoes_add_meta_box() {
	add_meta_box(
		'conferp-resolucoes-identificacao',
		__( 'Identificação e publicação oficial', 'conferp' ),
		'conferp_resolucoes_render_meta_box',
		'resolucao_normativa',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'conferp_resolucoes_add_meta_box' );

function conferp_resolucoes_render_meta_box( $post ) {
	wp_nonce_field( 'conferp_resolucoes_save', 'conferp_resolucoes_nonce' );
	foreach ( conferp_resolucoes_meta_fields() as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		printf( '<p><label for="%1$s"><strong>%2$s</strong></label></p>', esc_attr( $key ), esc_html( $field[0] ) );
		if ( 'textarea' === $field[1] ) {
			printf( '<p><textarea class="widefat" rows="3" id="%1$s" name="%1$s">%2$s</textarea></p>', esc_attr( $key ), esc_textarea( $value ) );
		} else {
			printf( '<p><input class="widefat" type="%1$s" id="%2$s" name="%2$s" value="%3$s" placeholder="%4$s"></p>', esc_attr( $field[1] ), esc_attr( $key ), esc_attr( $value ), esc_attr( $field[2] ) );
		}
	}
	echo '<p>' . esc_html__( 'Selecione os Assuntos na barra lateral. Insira o texto integral no Gutenberg. A apresentação com brasão será aplicada pelo template público.', 'conferp' ) . '</p>';
}

/** Persistência independente, inclusive no fluxo clássico de metabox do Gutenberg. */
function conferp_resolucoes_save_meta( $post_id ) {
	if ( ! isset( $_POST['conferp_resolucoes_nonce'] ) || ! is_string( $_POST['conferp_resolucoes_nonce'] ) ) {
		return;
	}
	$nonce = sanitize_text_field( wp_unslash( $_POST['conferp_resolucoes_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'conferp_resolucoes_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( conferp_resolucoes_meta_fields() as $key => $field ) {
		if ( ! isset( $_POST[ $key ] ) || ! is_string( $_POST[ $key ] ) ) {
			continue;
		}
		$raw = wp_unslash( $_POST[ $key ] );
		switch ( $field[1] ) {
			case 'url':
				$value = esc_url_raw( $raw );
				break;
			case 'textarea':
				$value = sanitize_textarea_field( $raw );
				break;
			case 'date':
				$value = '';
				if ( preg_match( '/^\d{4}-\d{2}-\d{2}$/', $raw ) ) {
					list( $year, $month, $day ) = array_map( 'intval', explode( '-', $raw ) );
					if ( checkdate( $month, $day, $year ) ) {
						$value = $raw;
					}
				}
				break;
			default:
				$value = sanitize_text_field( $raw );
		}
		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post_resolucao_normativa', 'conferp_resolucoes_save_meta' );
