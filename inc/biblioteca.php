<?php
/**
 * Biblioteca module.
 *
 * Registers the Biblioteca custom post type and its
 * independent collection taxonomy.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Register Biblioteca custom post type.
 *
 * @return void
 */
function conferp_register_biblioteca_post_type() {

	$labels = array(
		'name'                  => __( 'Biblioteca', 'conferp' ),
		'singular_name'         => __( 'Item da Biblioteca', 'conferp' ),
		'menu_name'             => __( 'Biblioteca', 'conferp' ),
		'name_admin_bar'        => __( 'Item da Biblioteca', 'conferp' ),
		'add_new'               => __( 'Adicionar novo', 'conferp' ),
		'add_new_item'          => __( 'Adicionar novo item', 'conferp' ),
		'new_item'              => __( 'Novo item', 'conferp' ),
		'edit_item'             => __( 'Editar item', 'conferp' ),
		'view_item'             => __( 'Ver item', 'conferp' ),
		'all_items'             => __( 'Todos os itens', 'conferp' ),
		'search_items'          => __( 'Pesquisar na Biblioteca', 'conferp' ),
		'not_found'             => __( 'Nenhum item encontrado.', 'conferp' ),
		'not_found_in_trash'    => __( 'Nenhum item encontrado na lixeira.', 'conferp' ),
		'archives'              => __( 'Biblioteca', 'conferp' ),
		'attributes'            => __( 'Atributos do item', 'conferp' ),
		'insert_into_item'      => __( 'Inserir no item', 'conferp' ),
		'uploaded_to_this_item' => __( 'Enviado para este item', 'conferp' ),
	);

	$args = array(
		'labels' => $labels,

		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'show_in_rest'        => true,

		'query_var'           => true,

		'rewrite' => array(
			'slug'       => 'biblioteca',
			'with_front' => false,
		),

		'capability_type' => 'post',

		'has_archive' => true,

		'hierarchical' => false,

		'menu_position' => 6,
		'menu_icon'     => 'dashicons-book-alt',

		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'author',
			'revisions',
		),
	);

	register_post_type(
		'biblioteca',
		$args
	);
}
add_action(
	'init',
	'conferp_register_biblioteca_post_type'
);


/**
 * Register Biblioteca collection taxonomy.
 *
 * This taxonomy belongs exclusively to the Biblioteca
 * module and must not be shared with News categories.
 *
 * @return void
 */
function conferp_register_biblioteca_taxonomy() {

	$labels = array(
		'name'              => __( 'Coleções', 'conferp' ),
		'singular_name'     => __( 'Coleção', 'conferp' ),
		'search_items'      => __( 'Pesquisar coleções', 'conferp' ),
		'all_items'         => __( 'Todas as coleções', 'conferp' ),
		'parent_item'       => __( 'Coleção superior', 'conferp' ),
		'parent_item_colon' => __( 'Coleção superior:', 'conferp' ),
		'edit_item'         => __( 'Editar coleção', 'conferp' ),
		'update_item'       => __( 'Atualizar coleção', 'conferp' ),
		'add_new_item'      => __( 'Adicionar nova coleção', 'conferp' ),
		'new_item_name'     => __( 'Nome da nova coleção', 'conferp' ),
		'menu_name'         => __( 'Coleções', 'conferp' ),
		'not_found'         => __( 'Nenhuma coleção encontrada.', 'conferp' ),
	);

	$args = array(
		'labels' => $labels,

		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_admin_column'  => true,
		'show_in_nav_menus'  => true,
		'show_tagcloud'      => false,
		'show_in_rest'       => true,

		'hierarchical' => true,

		'query_var' => true,

		'rewrite' => array(
			'slug'         => 'biblioteca/colecao',
			'with_front'   => false,
			'hierarchical' => true,
		),
	);

	register_taxonomy(
		'biblioteca_colecao',
		array( 'biblioteca' ),
		$args
	);
}
add_action(
	'init',
	'conferp_register_biblioteca_taxonomy'
);


/**
 * Get all Biblioteca collections.
 *
 * Used by the search select and collections sidebar.
 *
 * @param bool $hide_empty Whether collections without items should be hidden.
 *
 * @return array|WP_Error
 */
function conferp_get_biblioteca_collections( $hide_empty = true ) {

	return get_terms(
		array(
			'taxonomy'   => 'biblioteca_colecao',
			'hide_empty' => $hide_empty,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);
}


/**
 * Get the currently selected Biblioteca collection.
 *
 * @return string
 */
function conferp_get_biblioteca_selected_collection() {

	if ( empty( $_GET['colecao'] ) ) {
		return '';
	}

	return sanitize_title(
		wp_unslash( $_GET['colecao'] )
	);
}


/**
 * Get the current Biblioteca search term.
 *
 * The Biblioteca uses its own query parameter so its
 * search remains independent from the native WordPress
 * search used by News.
 *
 * @return string
 */
function conferp_get_biblioteca_search_term() {

	if ( empty( $_GET['biblioteca_s'] ) ) {
		return '';
	}

	return sanitize_text_field(
		wp_unslash( $_GET['biblioteca_s'] )
	);
}


/**
 * Build the Biblioteca query arguments.
 *
 * Supports:
 * - all Biblioteca items;
 * - text search;
 * - collection filtering;
 * - text search inside a selected collection;
 * - pagination.
 *
 * @return array
 */
function conferp_get_biblioteca_query_args() {

	$search_term        = conferp_get_biblioteca_search_term();
	$selected_collection = conferp_get_biblioteca_selected_collection();

	$paged = max(
		1,
		get_query_var( 'paged' ),
		get_query_var( 'page' )
	);

	$args = array(
		'post_type'           => 'biblioteca',
		'post_status'         => 'publish',
		'posts_per_page'      => 4,
		'paged'               => $paged,
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
	);


	/*
	 * Biblioteca text search.
	 *
	 * Because this WP_Query explicitly uses the Biblioteca
	 * post type, results cannot include News posts.
	 */
	if ( $search_term ) {
		$args['s'] = $search_term;
	}


	/*
	 * Optional collection filter.
	 */
	if ( $selected_collection ) {

		$args['tax_query'] = array(
			array(
				'taxonomy' => 'biblioteca_colecao',
				'field'    => 'slug',
				'terms'    => $selected_collection,
			),
		);
	}


	return $args;
}


/**
 * Create the Biblioteca query.
 *
 * @return WP_Query
 */
function conferp_get_biblioteca_query() {

	return new WP_Query(
		conferp_get_biblioteca_query_args()
	);
}


/**
 * Get a Biblioteca item excerpt.
 *
 * Prioritizes the manually entered excerpt. If none exists,
 * generates a plain-text excerpt from the item content.
 *
 * @param int $length  Number of words.
 * @param int $post_id Post ID.
 *
 * @return string
 */
function conferp_get_biblioteca_excerpt( $length = 28, $post_id = null ) {

	$post_id = $post_id ?: get_the_ID();

	$manual_excerpt = get_post_field(
		'post_excerpt',
		$post_id
	);

	if ( ! empty( $manual_excerpt ) ) {

		return wp_trim_words(
			wp_strip_all_tags( $manual_excerpt ),
			$length,
			'…'
		);
	}


	$content = get_post_field(
		'post_content',
		$post_id
	);

	if ( empty( $content ) ) {
		return '';
	}


	$content = strip_shortcodes( $content );
	$content = wp_strip_all_tags( $content, true );
	$content = preg_replace( '/\s+/', ' ', $content );
	$content = trim( $content );


	if ( empty( $content ) ) {
		return '';
	}


	return wp_trim_words(
		$content,
		$length,
		'…'
	);
}