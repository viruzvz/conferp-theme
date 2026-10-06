<?php
/**
 * Biblioteca functionality.
 *
 * Registers the Biblioteca custom post type, taxonomies,
 * query helpers and document management.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* ==========================================================
   REGISTER BIBLIOTECA POST TYPE
   ========================================================== */

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
		'parent_item_colon'     => __( 'Item pai:', 'conferp' ),
		'not_found'             => __( 'Nenhum item encontrado.', 'conferp' ),
		'not_found_in_trash'    => __( 'Nenhum item encontrado na lixeira.', 'conferp' ),
		'archives'              => __( 'Biblioteca', 'conferp' ),
		'attributes'            => __( 'Atributos do item', 'conferp' ),
		'insert_into_item'      => __( 'Inserir no item', 'conferp' ),
		'uploaded_to_this_item' => __( 'Enviado para este item', 'conferp' ),
	);

	$args = array(
		'labels' => $labels,

		'public'             => true,
		'publicly_queryable' => true,

		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_admin_bar'  => true,
		'show_in_nav_menus'  => true,

		'query_var' => true,

		'rewrite' => array(
			'slug'       => 'biblioteca',
			'with_front' => false,
		),

		'capability_type' => 'post',

		'has_archive' => true,
		'hierarchical' => false,

		'menu_position' => 20,
		'menu_icon'     => 'dashicons-book-alt',

		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'author',
			'revisions',
		),

		'show_in_rest' => true,
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


/* ==========================================================
   REGISTER COLLECTION TAXONOMY
   ========================================================== */

/**
 * Register Biblioteca collection taxonomy.
 *
 * @return void
 */
function conferp_register_biblioteca_collection_taxonomy() {

	$labels = array(
		'name'              => __( 'Coleções', 'conferp' ),
		'singular_name'     => __( 'Coleção', 'conferp' ),
		'search_items'      => __( 'Pesquisar coleções', 'conferp' ),
		'all_items'         => __( 'Todas as coleções', 'conferp' ),
		'parent_item'       => __( 'Coleção pai', 'conferp' ),
		'parent_item_colon' => __( 'Coleção pai:', 'conferp' ),
		'edit_item'         => __( 'Editar coleção', 'conferp' ),
		'update_item'       => __( 'Atualizar coleção', 'conferp' ),
		'add_new_item'      => __( 'Adicionar nova coleção', 'conferp' ),
		'new_item_name'     => __( 'Nome da nova coleção', 'conferp' ),
		'menu_name'         => __( 'Coleções', 'conferp' ),
	);

	$args = array(
		'labels' => $labels,

		'public'             => true,
		'publicly_queryable' => true,

		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_in_rest'      => true,

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
	'conferp_register_biblioteca_collection_taxonomy'
);


/* ==========================================================
   REGISTER AUTHOR TAXONOMY
   ========================================================== */

/**
 * Register Biblioteca author taxonomy.
 *
 * Represents the intellectual authorship of Biblioteca items.
 * This is independent from the native WordPress post author,
 * which represents the user responsible for publishing content.
 *
 * @return void
 */
function conferp_register_biblioteca_author_taxonomy() {

	$labels = array(
		'name'                       => __( 'Autores', 'conferp' ),
		'singular_name'              => __( 'Autor', 'conferp' ),
		'search_items'               => __( 'Pesquisar autores', 'conferp' ),
		'popular_items'              => __( 'Autores mais utilizados', 'conferp' ),
		'all_items'                  => __( 'Todos os autores', 'conferp' ),
		'edit_item'                  => __( 'Editar autor', 'conferp' ),
		'update_item'                => __( 'Atualizar autor', 'conferp' ),
		'add_new_item'               => __( 'Adicionar novo autor', 'conferp' ),
		'new_item_name'              => __( 'Nome do novo autor', 'conferp' ),
		'separate_items_with_commas' => __( 'Separe os autores por vírgulas', 'conferp' ),
		'add_or_remove_items'        => __( 'Adicionar ou remover autores', 'conferp' ),
		'choose_from_most_used'      => __( 'Escolher entre os autores mais utilizados', 'conferp' ),
		'not_found'                  => __( 'Nenhum autor encontrado.', 'conferp' ),
		'menu_name'                  => __( 'Autores', 'conferp' ),
	);

	$args = array(
		'labels' => $labels,

		'public'             => true,
		'publicly_queryable' => true,

		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => false,
		'show_in_rest'      => true,

		/*
		 * Authors behave like tags.
		 *
		 * A Biblioteca item may have one or multiple
		 * intellectual authors.
		 */
		'hierarchical' => false,

		'query_var' => true,

		'rewrite' => array(
			'slug'       => 'biblioteca/autor',
			'with_front' => false,
		),
	);

	register_taxonomy(
		'biblioteca_autor',
		array( 'biblioteca' ),
		$args
	);
}
add_action(
	'init',
	'conferp_register_biblioteca_author_taxonomy'
);


/* ==========================================================
   COLLECTION HELPERS
   ========================================================== */

/**
 * Get Biblioteca collections.
 *
 * @param bool $hide_empty Whether empty collections should be hidden.
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
 * Get currently selected Biblioteca collection.
 *
 * @return string
 */
function conferp_get_biblioteca_selected_collection() {

	if ( ! isset( $_GET['colecao'] ) ) {
		return '';
	}

	return sanitize_title(
		wp_unslash(
			$_GET['colecao']
		)
	);
}


/* ==========================================================
   SEARCH HELPERS
   ========================================================== */

/**
 * Get Biblioteca search term.
 *
 * @return string
 */
function conferp_get_biblioteca_search_term() {

	if ( ! isset( $_GET['biblioteca_s'] ) ) {
		return '';
	}

	return sanitize_text_field(
		wp_unslash(
			$_GET['biblioteca_s']
		)
	);
}


/* ==========================================================
   BIBLIOTECA QUERY
   ========================================================== */

/**
 * Build Biblioteca query arguments.
 *
 * Search and collection filters are intentionally scoped
 * exclusively to the Biblioteca module.
 *
 * @return array
 */
function conferp_get_biblioteca_query_args() {

	$search_term         = conferp_get_biblioteca_search_term();
	$selected_collection = conferp_get_biblioteca_selected_collection();

	$paged = max(
		1,
		absint( get_query_var( 'paged' ) ),
		absint( get_query_var( 'page' ) )
	);

	$args = array(
		'post_type'           => 'biblioteca',
		'post_status'         => 'publish',
		'posts_per_page'      => 10,
		'paged'               => $paged,
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
	);


	/*
	 * Search only inside Biblioteca.
	 */
	if ( $search_term ) {
		$args['s'] = $search_term;
	}


	/*
	 * Filter by selected Biblioteca collection.
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
 * Get Biblioteca query.
 *
 * @return WP_Query
 */
function conferp_get_biblioteca_query() {

	return new WP_Query(
		conferp_get_biblioteca_query_args()
	);
}


/* ==========================================================
   EXCERPT
   ========================================================== */

/**
 * Get Biblioteca item excerpt.
 *
 * Uses the manual excerpt when available. Otherwise,
 * generates an excerpt from the post content.
 *
 * @param int $length Number of words.
 *
 * @return string
 */
function conferp_get_biblioteca_excerpt( $length = 28 ) {

	$post = get_post();

	if ( ! $post ) {
		return '';
	}

	if ( has_excerpt( $post ) ) {

		return wp_trim_words(
			get_the_excerpt( $post ),
			$length
		);
	}

	$content = wp_strip_all_tags(
		strip_shortcodes(
			$post->post_content
		)
	);

	return wp_trim_words(
		$content,
		$length
	);
}


/* ==========================================================
   DOCUMENT METABOX
   ========================================================== */

/**
 * Register Biblioteca document metabox.
 *
 * @return void
 */
function conferp_register_biblioteca_document_metabox() {

	add_meta_box(
		'conferp-biblioteca-document',
		__( 'Documento da Biblioteca', 'conferp' ),
		'conferp_render_biblioteca_document_metabox',
		'biblioteca',
		'normal',
		'high'
	);
}
add_action(
	'add_meta_boxes',
	'conferp_register_biblioteca_document_metabox'
);


/**
 * Render Biblioteca document metabox.
 *
 * @param WP_Post $post Current Biblioteca item.
 *
 * @return void
 */
function conferp_render_biblioteca_document_metabox( $post ) {

	$attachment_id = absint(
		get_post_meta(
			$post->ID,
			'_biblioteca_document_id',
			true
		)
	);

	$attachment_url  = '';
	$attachment_name = '';

	if ( $attachment_id ) {

		$attachment_url = wp_get_attachment_url(
			$attachment_id
		);

		$attached_file = get_attached_file(
			$attachment_id
		);

		if ( $attached_file ) {

			$attachment_name = wp_basename(
				$attached_file
			);

		} else {

			$attachment_name = get_the_title(
				$attachment_id
			);
		}
	}

	wp_nonce_field(
		'conferp_save_biblioteca_document',
		'conferp_biblioteca_document_nonce'
	);
	?>

	<div
		class="conferp-biblioteca-document"
		data-biblioteca-document
	>

		<input
			type="hidden"
			id="conferp-biblioteca-document-id"
			name="conferp_biblioteca_document_id"
			value="<?php echo esc_attr( $attachment_id ); ?>"
			data-biblioteca-document-id
		>


		<div
			class="conferp-biblioteca-document__selected"
			data-biblioteca-document-selected
			<?php echo $attachment_id ? '' : 'hidden'; ?>
		>

			<p>
				<strong>
					<?php
					esc_html_e(
						'Documento selecionado:',
						'conferp'
					);
					?>
				</strong>
			</p>

			<p>
				<span data-biblioteca-document-name>
					<?php echo esc_html( $attachment_name ); ?>
				</span>
			</p>


			<?php if ( $attachment_url ) : ?>

				<p>
					<a
						href="<?php echo esc_url( $attachment_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						data-biblioteca-document-link
					>
						<?php
						esc_html_e(
							'Visualizar documento',
							'conferp'
						);
						?>
					</a>
				</p>

			<?php else : ?>

				<a
					href="#"
					target="_blank"
					rel="noopener noreferrer"
					data-biblioteca-document-link
					hidden
				>
					<?php
					esc_html_e(
						'Visualizar documento',
						'conferp'
					);
					?>
				</a>

			<?php endif; ?>

		</div>


		<p>

			<button
				type="button"
				class="button button-secondary"
				data-biblioteca-document-select
			>
				<?php
				echo $attachment_id
					? esc_html__(
						'Substituir documento',
						'conferp'
					)
					: esc_html__(
						'Selecionar documento',
						'conferp'
					);
				?>
			</button>


			<button
				type="button"
				class="button button-link-delete"
				data-biblioteca-document-remove
				<?php echo $attachment_id ? '' : 'hidden'; ?>
			>
				<?php
				esc_html_e(
					'Remover documento',
					'conferp'
				);
				?>
			</button>

		</p>


		<p class="description">
			<?php
			esc_html_e(
				'Selecione o documento principal deste item na Biblioteca de Mídia do WordPress.',
				'conferp'
			);
			?>
		</p>

	</div>

	<?php
}


/* ==========================================================
   SAVE DOCUMENT
   ========================================================== */

/**
 * Save Biblioteca document attachment.
 *
 * Stores the WordPress attachment ID instead of the
 * attachment URL.
 *
 * @param int $post_id Biblioteca post ID.
 *
 * @return void
 */
function conferp_save_biblioteca_document( $post_id ) {

	if (
		! isset(
			$_POST['conferp_biblioteca_document_nonce']
		)
	) {
		return;
	}


	$nonce = sanitize_text_field(
		wp_unslash(
			$_POST['conferp_biblioteca_document_nonce']
		)
	);


	if (
		! wp_verify_nonce(
			$nonce,
			'conferp_save_biblioteca_document'
		)
	) {
		return;
	}


	if (
		defined( 'DOING_AUTOSAVE' )
		&& DOING_AUTOSAVE
	) {
		return;
	}


	if (
		'biblioteca' !== get_post_type( $post_id )
	) {
		return;
	}


	if (
		! current_user_can(
			'edit_post',
			$post_id
		)
	) {
		return;
	}


	$attachment_id = isset(
		$_POST['conferp_biblioteca_document_id']
	)
		? absint(
			$_POST['conferp_biblioteca_document_id']
		)
		: 0;


	/*
	 * Empty value means that the document
	 * was intentionally removed.
	 */
	if ( ! $attachment_id ) {

		delete_post_meta(
			$post_id,
			'_biblioteca_document_id'
		);

		return;
	}


	/*
	 * Validate that the supplied ID actually
	 * represents a WordPress attachment.
	 */
	if (
		'attachment' !== get_post_type(
			$attachment_id
		)
	) {
		return;
	}


	update_post_meta(
		$post_id,
		'_biblioteca_document_id',
		$attachment_id
	);
}
add_action(
	'save_post_biblioteca',
	'conferp_save_biblioteca_document'
);


/* ==========================================================
   DOCUMENT HELPERS
   ========================================================== */

/**
 * Get Biblioteca document attachment ID.
 *
 * @param int|null $post_id Biblioteca item ID.
 *
 * @return int
 */
function conferp_get_biblioteca_document_id( $post_id = null ) {

	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	return absint(
		get_post_meta(
			$post_id,
			'_biblioteca_document_id',
			true
		)
	);
}


/**
 * Get Biblioteca document URL.
 *
 * @param int|null $post_id Biblioteca item ID.
 *
 * @return string
 */
function conferp_get_biblioteca_document_url( $post_id = null ) {

	$attachment_id = conferp_get_biblioteca_document_id(
		$post_id
	);

	if ( ! $attachment_id ) {
		return '';
	}

	$url = wp_get_attachment_url(
		$attachment_id
	);

	return $url
		? $url
		: '';
}


/* ==========================================================
   ADMIN ASSETS
   ========================================================== */

/**
 * Load Biblioteca admin assets.
 *
 * Loads WordPress Media Library and the Biblioteca
 * document selector only on Biblioteca editing screens.
 *
 * @param string $hook_suffix Current admin screen hook.
 *
 * @return void
 */
function conferp_biblioteca_admin_assets( $hook_suffix ) {

	if (
		'post.php' !== $hook_suffix
		&& 'post-new.php' !== $hook_suffix
	) {
		return;
	}


	$screen = get_current_screen();

	if (
		! $screen
		|| 'biblioteca' !== $screen->post_type
	) {
		return;
	}


	/*
	 * Required for wp.media().
	 */
	wp_enqueue_media();


	$script_path = get_template_directory()
		. '/assets/js/biblioteca-admin.js';


	wp_enqueue_script(
		'conferp-biblioteca-admin',
		get_template_directory_uri()
			. '/assets/js/biblioteca-admin.js',
		array(),
		file_exists( $script_path )
			? filemtime( $script_path )
			: wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action(
	'admin_enqueue_scripts',
	'conferp_biblioteca_admin_assets'
);