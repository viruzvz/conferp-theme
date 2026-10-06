<?php
/**
 * Breadcrumb functionality.
 *
 * Builds the institutional breadcrumb hierarchy used
 * throughout the CONFERP theme.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* ==========================================================
   BREADCRUMB ITEMS
   ========================================================== */

/**
 * Build breadcrumb items for the current request.
 *
 * Each item contains:
 *
 * - label
 * - url
 * - current
 *
 * @return array
 */
function conferp_get_breadcrumb_items() {

	$items = array();


	/*
	 * Do not display breadcrumb on the front page.
	 */
	if ( is_front_page() ) {
		return $items;
	}


	/*
	 * Home.
	 */
	$items[] = array(
		'label'   => __( 'Início', 'conferp' ),
		'url'     => home_url( '/' ),
		'current' => false,
	);


	/* ======================================================
	   NEWS
	   ====================================================== */

	/*
	 * News archive / Posts Page.
	 */
	if ( is_home() ) {

		$items[] = array(
			'label'   => __( 'Notícias', 'conferp' ),
			'url'     => '',
			'current' => true,
		);

		return $items;
	}


	/*
	 * Single native post.
	 */
	if ( is_singular( 'post' ) ) {

		$news_url = conferp_get_news_url();

		$items[] = array(
			'label'   => __( 'Notícias', 'conferp' ),
			'url'     => $news_url,
			'current' => false,
		);


		/*
		 * Primary category.
		 *
		 * For now we use the first category assigned
		 * to the post.
		 */
		$categories = get_the_category();

		if ( ! empty( $categories ) ) {

			$category = $categories[0];

			$items[] = array(
				'label'   => $category->name,
				'url'     => get_category_link(
					$category->term_id
				),
				'current' => false,
			);
		}


		$items[] = array(
			'label'   => get_the_title(),
			'url'     => '',
			'current' => true,
		);

		return $items;
	}


	/*
	 * News category.
	 */
	if ( is_category() ) {

		$category = get_queried_object();

		$items[] = array(
			'label'   => __( 'Notícias', 'conferp' ),
			'url'     => conferp_get_news_url(),
			'current' => false,
		);


		if (
			$category
			&& ! is_wp_error( $category )
		) {

			/*
			 * Add category ancestors when hierarchical
			 * categories are being used.
			 */
			$ancestors = array_reverse(
				get_ancestors(
					$category->term_id,
					'category',
					'taxonomy'
				)
			);

			foreach ( $ancestors as $ancestor_id ) {

				$ancestor = get_term(
					$ancestor_id,
					'category'
				);

				if (
					! $ancestor
					|| is_wp_error( $ancestor )
				) {
					continue;
				}

				$items[] = array(
					'label'   => $ancestor->name,
					'url'     => get_category_link(
						$ancestor->term_id
					),
					'current' => false,
				);
			}


			$items[] = array(
				'label'   => $category->name,
				'url'     => '',
				'current' => true,
			);
		}

		return $items;
	}


	/* ======================================================
	   BIBLIOTECA
	   ====================================================== */

	/*
	* Biblioteca archive.
	*
	* Also handles the custom collection and search
	* filters used by the Biblioteca module.
	*/
	if ( is_post_type_archive( 'biblioteca' ) ) {

		$selected_collection = function_exists(
			'conferp_get_biblioteca_selected_collection'
		)
			? conferp_get_biblioteca_selected_collection()
			: '';

		$search_term = function_exists(
			'conferp_get_biblioteca_search_term'
		)
			? conferp_get_biblioteca_search_term()
			: '';


		/*
		* Plain Biblioteca archive.
		*
		* Início / Biblioteca
		*/
		if (
			! $selected_collection
			&& ! $search_term
		) {

			$items[] = array(
				'label'   => __( 'Biblioteca', 'conferp' ),
				'url'     => '',
				'current' => true,
			);

			return $items;
		}


		/*
		* When filters are active, Biblioteca becomes
		* an intermediate clickable breadcrumb item.
		*/
		$items[] = array(
			'label'   => __( 'Biblioteca', 'conferp' ),
			'url'     => get_post_type_archive_link(
				'biblioteca'
			),
			'current' => false,
		);


		/*
		* Selected collection.
		*
		* Início / Biblioteca / Ebooks
		*
		* or:
		*
		* Início / Biblioteca / Ebooks / Resultados da busca
		*/
		if ( $selected_collection ) {

			$collection = get_term_by(
				'slug',
				$selected_collection,
				'biblioteca_colecao'
			);


			if (
				$collection
				&& ! is_wp_error( $collection )
			) {

				/*
				* If there is also a search term, the
				* collection is an intermediate link.
				*
				* Otherwise it is the current item.
				*/
				$items[] = array(
					'label'   => $collection->name,
					'url'     => $search_term
						? add_query_arg(
							'colecao',
							$collection->slug,
							get_post_type_archive_link(
								'biblioteca'
							)
						)
						: '',
					'current' => ! $search_term,
				);
			}
		}


		/*
		* Biblioteca search.
		*
		* Início / Biblioteca / Resultados da busca
		*
		* or:
		*
		* Início / Biblioteca / Ebooks / Resultados da busca
		*/
		if ( $search_term ) {

			$items[] = array(
				'label'   => __( 'Resultados da busca', 'conferp' ),
				'url'     => '',
				'current' => true,
			);
		}


		return $items;
	}


	/*
	 * Biblioteca single.
	 */
	if ( is_singular( 'biblioteca' ) ) {

		$items[] = array(
			'label'   => __( 'Biblioteca', 'conferp' ),
			'url'     => get_post_type_archive_link(
				'biblioteca'
			),
			'current' => false,
		);


		/*
		 * Collection.
		 *
		 * If the item belongs to a collection,
		 * include the first collection in the trail.
		 */
		$collections = get_the_terms(
			get_the_ID(),
			'biblioteca_colecao'
		);

		if (
			! empty( $collections )
			&& ! is_wp_error( $collections )
		) {

			$collection = $collections[0];

			$collection_url = add_query_arg(
				'colecao',
				$collection->slug,
				get_post_type_archive_link(
					'biblioteca'
				)
			);

			$items[] = array(
				'label'   => $collection->name,
				'url'     => $collection_url,
				'current' => false,
			);
		}


		$items[] = array(
			'label'   => get_the_title(),
			'url'     => '',
			'current' => true,
		);

		return $items;
	}


	/*
	 * Biblioteca collection taxonomy.
	 */
	if ( is_tax( 'biblioteca_colecao' ) ) {

		$term = get_queried_object();

		$items[] = array(
			'label'   => __( 'Biblioteca', 'conferp' ),
			'url'     => get_post_type_archive_link(
				'biblioteca'
			),
			'current' => false,
		);


		if (
			$term
			&& ! is_wp_error( $term )
		) {

			/*
			 * Collection ancestors.
			 */
			$ancestors = array_reverse(
				get_ancestors(
					$term->term_id,
					'biblioteca_colecao',
					'taxonomy'
				)
			);

			foreach ( $ancestors as $ancestor_id ) {

				$ancestor = get_term(
					$ancestor_id,
					'biblioteca_colecao'
				);

				if (
					! $ancestor
					|| is_wp_error( $ancestor )
				) {
					continue;
				}

				$ancestor_url = add_query_arg(
					'colecao',
					$ancestor->slug,
					get_post_type_archive_link(
						'biblioteca'
					)
				);

				$items[] = array(
					'label'   => $ancestor->name,
					'url'     => $ancestor_url,
					'current' => false,
				);
			}


			$items[] = array(
				'label'   => $term->name,
				'url'     => '',
				'current' => true,
			);
		}

		return $items;
	}


	/*
	 * Biblioteca author taxonomy.
	 */
	if ( is_tax( 'biblioteca_autor' ) ) {

		$author = get_queried_object();

		$items[] = array(
			'label'   => __( 'Biblioteca', 'conferp' ),
			'url'     => get_post_type_archive_link(
				'biblioteca'
			),
			'current' => false,
		);


		$items[] = array(
			'label'   => __( 'Autores', 'conferp' ),
			'url'     => '',
			'current' => false,
		);


		if (
			$author
			&& ! is_wp_error( $author )
		) {

			$items[] = array(
				'label'   => $author->name,
				'url'     => '',
				'current' => true,
			);
		}

		return $items;
	}


	/* ======================================================
	SEARCH
	====================================================== */

	if ( is_search() ) {

		/*
		* Native WordPress search used by the
		* News module.
		*
		* Início / Notícias / Resultados da busca
		*/
		$post_type = get_query_var( 'post_type' );

		$is_news_search = false;


		/*
		* Explicit post type.
		*/
		if ( 'post' === $post_type ) {
			$is_news_search = true;
		}


		/*
		* post_type may also be supplied as an array.
		*/
		if (
			is_array( $post_type )
			&& in_array( 'post', $post_type, true )
		) {
			$is_news_search = true;
		}


		/*
		* The News search may use the native WordPress
		* search without explicitly sending post_type.
		*
		* In that case, treat the search as News when
		* no custom post type was requested.
		*/
		if ( empty( $post_type ) ) {
			$is_news_search = true;
		}


		if ( $is_news_search ) {

			$items[] = array(
				'label'   => __( 'Notícias', 'conferp' ),
				'url'     => conferp_get_news_url(),
				'current' => false,
			);
		}


		$items[] = array(
			'label'   => __( 'Resultados da busca', 'conferp' ),
			'url'     => '',
			'current' => true,
		);


		return $items;
	}


	/* ======================================================
	   404
	   ====================================================== */

	if ( is_404() ) {

		$items[] = array(
			'label'   => __( 'Página não encontrada', 'conferp' ),
			'url'     => '',
			'current' => true,
		);

		return $items;
	}


	/* ======================================================
	   STANDARD PAGES
	   ====================================================== */

	if ( is_page() ) {

		$page_id = get_queried_object_id();


		/*
		 * Build parent hierarchy.
		 */
		$ancestors = array_reverse(
			get_post_ancestors(
				$page_id
			)
		);


		foreach ( $ancestors as $ancestor_id ) {

			$items[] = array(
				'label'   => get_the_title(
					$ancestor_id
				),
				'url'     => get_permalink(
					$ancestor_id
				),
				'current' => false,
			);
		}


		$items[] = array(
			'label'   => get_the_title(
				$page_id
			),
			'url'     => '',
			'current' => true,
		);

		return $items;
	}


	/* ======================================================
	   GENERIC CUSTOM POST TYPE ARCHIVE
	   Future modules:
	   Atas, Leis, Resoluções, Portarias...
	   ====================================================== */

	if ( is_post_type_archive() ) {

		$post_type = get_query_var(
			'post_type'
		);

		if ( is_array( $post_type ) ) {
			$post_type = reset( $post_type );
		}


		$post_type_object = get_post_type_object(
			$post_type
		);


		if ( $post_type_object ) {

			$items[] = array(
				'label'   => $post_type_object->labels->name,
				'url'     => '',
				'current' => true,
			);
		}

		return $items;
	}


	/* ======================================================
	   GENERIC CUSTOM POST TYPE SINGLE
	   ====================================================== */

	if ( is_singular() ) {

		$post_type = get_post_type();


		/*
		 * Native posts have already been handled above.
		 */
		if (
			$post_type
			&& 'post' !== $post_type
		) {

			$post_type_object = get_post_type_object(
				$post_type
			);


			if ( $post_type_object ) {

				if (
					$post_type_object->has_archive
				) {

					$items[] = array(
						'label'   => $post_type_object->labels->name,
						'url'     => get_post_type_archive_link(
							$post_type
						),
						'current' => false,
					);
				}


				$items[] = array(
					'label'   => get_the_title(),
					'url'     => '',
					'current' => true,
				);
			}
		}

		return $items;
	}


	return $items;
}


/* ==========================================================
   NEWS URL
   ========================================================== */

/**
 * Get the URL used as the News archive.
 *
 * WordPress native posts do not have a post type archive
 * like custom post types. The theme therefore uses the
 * configured Posts Page when available.
 *
 * @return string
 */
function conferp_get_news_url() {

	$posts_page_id = absint(
		get_option( 'page_for_posts' )
	);


	if ( $posts_page_id ) {

		$url = get_permalink(
			$posts_page_id
		);

		if ( $url ) {
			return $url;
		}
	}


	/*
	 * Safe fallback.
	 */
	return home_url( '/noticias/' );
}


/* ==========================================================
   BREADCRUMB RENDERER
   ========================================================== */

/**
 * Render the global CONFERP breadcrumb.
 *
 * Templates should use:
 *
 * conferp_breadcrumb();
 *
 * instead of loading the template part directly.
 *
 * @return void
 */
function conferp_breadcrumb() {

	$items = conferp_get_breadcrumb_items();


	if ( empty( $items ) ) {
		return;
	}


	get_template_part(
		'template-parts/global/breadcrumb',
		null,
		array(
			'items' => $items,
		)
	);
}

/* ==========================================================
   BREADCRUMB SHORTCODE
   ========================================================== */

/**
 * Render breadcrumb through shortcode.
 *
 * Usage:
 *
 * [conferp_breadcrumb]
 *
 * Useful for Elementor and other WordPress editors.
 *
 * @return string
 */
function conferp_breadcrumb_shortcode() {

	ob_start();

	conferp_breadcrumb();

	return ob_get_clean();
}
add_shortcode(
	'conferp_breadcrumb',
	'conferp_breadcrumb_shortcode'
);