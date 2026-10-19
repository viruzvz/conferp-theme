<?php
/**
 * Módulo independente Leis e Decretos: CPT, taxonomia e metadados.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Registra Leis e Decretos como CPT independente e sua taxonomia exclusiva. */
function conferp_register_legislacao() {
	register_post_type(
		'lei_decreto',
		array(
			'labels' => array(
				'name'               => __( 'Leis e Decretos', 'conferp' ),
				'singular_name'      => __( 'Documento legal', 'conferp' ),
				'menu_name'          => __( 'Leis e Decretos', 'conferp' ),
				'add_new'            => __( 'Adicionar novo', 'conferp' ),
				'add_new_item'       => __( 'Adicionar documento legal', 'conferp' ),
				'edit_item'          => __( 'Editar documento legal', 'conferp' ),
				'view_item'          => __( 'Ver documento legal', 'conferp' ),
				'all_items'          => __( 'Leis e Decretos', 'conferp' ),
				'search_items'       => __( 'Pesquisar documentos legais', 'conferp' ),
				'not_found'          => __( 'Nenhum documento encontrado.', 'conferp' ),
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_in_rest'       => true,
			'has_archive'        => 'leis-e-decretos',
			'rewrite'            => array( 'slug' => 'leis-e-decretos', 'with_front' => false ),
			'show_in_menu'       => 'conferp-legislacao',
			'menu_icon'          => 'dashicons-media-text',
			'supports'           => array( 'title', 'editor', 'revisions', 'author' ),
			'taxonomies'         => array( 'legislacao_tipo' ),
		),
	);

	register_taxonomy(
		'legislacao_tipo',
		array( 'lei_decreto' ),
		array(
			'labels'            => array(
				'name'          => __( 'Tipos de documento', 'conferp' ),
				'singular_name' => __( 'Tipo de documento', 'conferp' ),
				'menu_name'     => __( 'Tipos de documento', 'conferp' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'tipo-legislacao', 'with_front' => false ),
		),
	);

	$fields = array(
		'_legislacao_numero' => 'string',
		'_legislacao_data'   => 'string',
		'_legislacao_ementa' => 'string',
		'_legislacao_fonte'  => 'string',
	);

	foreach ( $fields as $key => $type ) {
		register_post_meta(
			'lei_decreto',
			$key,
			array(
				'type'              => $type,
				'single'            => true,
				'show_in_rest'      => false,
				'auth_callback'     => static function () {
					return current_user_can( 'edit_posts' );
				},
			),
		);
	}
}
add_action( 'init', 'conferp_register_legislacao' );

/** Insere os quatro tipos padrão sem remover classificações existentes. */
function conferp_legislacao_default_terms() {
	$terms = array(
		'lei'             => 'Lei',
		'lei-complementar' => 'Lei Complementar',
		'decreto'         => 'Decreto',
		'decreto-lei'     => 'Decreto-Lei',
	);
	foreach ( $terms as $slug => $name ) {
		if ( ! term_exists( $slug, 'legislacao_tipo' ) ) {
			wp_insert_term( $name, 'legislacao_tipo', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'init', 'conferp_legislacao_default_terms', 20 );

/** Campos de identificação; o corpo da norma fica no Gutenberg. */
function conferp_legislacao_add_meta_box() {
	add_meta_box(
		'conferp-legislacao-identificacao',
		__( 'Identificação do documento', 'conferp' ),
		'conferp_legislacao_render_meta_box',
		'lei_decreto',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'conferp_legislacao_add_meta_box' );

function conferp_legislacao_render_meta_box( $post ) {
	wp_nonce_field( 'conferp_legislacao_save', 'conferp_legislacao_nonce' );
	$fields = array(
		'_legislacao_numero' => array( 'Número / identificação', 'text', 'Ex.: 5.377' ),
		'_legislacao_data'   => array( 'Data do documento', 'date', '' ),
		'_legislacao_ementa' => array( 'Ementa', 'textarea', '' ),
		'_legislacao_fonte'  => array( 'URL da fonte oficial', 'url', 'https://' ),
	);
	foreach ( $fields as $key => $details ) {
		$value = get_post_meta( $post->ID, $key, true );
		printf( '<p><label for="%1$s"><strong>%2$s</strong></label></p>', esc_attr( $key ), esc_html( $details[0] ) );
		if ( 'textarea' === $details[1] ) {
			printf( '<p><textarea class="widefat" rows="3" id="%1$s" name="%1$s">%2$s</textarea></p>', esc_attr( $key ), esc_textarea( $value ) );
		} else {
			printf( '<p><input class="widefat" type="%1$s" id="%2$s" name="%2$s" value="%3$s" placeholder="%4$s"></p>', esc_attr( $details[1] ), esc_attr( $key ), esc_attr( $value ), esc_attr( $details[2] ) );
		}
	}
	echo '<p>' . esc_html__( 'Selecione o Tipo de documento na barra lateral. Insira o texto integral no editor de blocos. O brasão será exibido automaticamente no template público.', 'conferp' ) . '</p>';
}

/** Salva somente campos autorizados, sem alterar o conteúdo da norma. */
function conferp_legislacao_save_meta( $post_id ) {
	if ( ! isset( $_POST['conferp_legislacao_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['conferp_legislacao_nonce'] ) ), 'conferp_legislacao_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$fields = array( '_legislacao_numero', '_legislacao_data', '_legislacao_ementa', '_legislacao_fonte' );
	foreach ( $fields as $key ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$raw = wp_unslash( $_POST[ $key ] );
		if ( ! is_string( $raw ) ) {
			continue;
		}
		if ( '_legislacao_fonte' === $key ) {
			$value = esc_url_raw( $raw );
		} elseif ( '_legislacao_ementa' === $key ) {
			$value = sanitize_textarea_field( $raw );
		} elseif ( '_legislacao_data' === $key ) {
			$value = preg_match( '/^\d{4}-\d{2}-\d{2}$/', $raw ) ? $raw : '';
		} else {
			$value = sanitize_text_field( $raw );
		}
		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post_lei_decreto', 'conferp_legislacao_save_meta' );
