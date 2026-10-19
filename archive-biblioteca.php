<?php
/**
 * Biblioteca archive template.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$biblioteca_query = conferp_get_biblioteca_query();
?>

<main
	id="primary"
	class="site-main biblioteca-page"
>

	<?php
	get_template_part(
		'template-parts/global/page-heading',
		null,
		array(
			'title'       => __( 'Biblioteca', 'conferp' ),
			'description' => __( 'Aqui estão todas as publicações disponibilizadas pelo CONFERP, todas ao seu alcance.', 'conferp' ),
		)
	);
	?>

	<section
		class="biblioteca-search"
		aria-label="<?php esc_attr_e( 'Pesquisar na Biblioteca', 'conferp' ); ?>"
	>

		<div class="container">

			<?php
			get_template_part(
				'template-parts/biblioteca/biblioteca-search-form'
			);
			?>

		</div>

	</section>


	<div class="biblioteca-layout container">

		<div class="biblioteca-layout__main">

			<?php
			get_template_part(
				'template-parts/biblioteca/biblioteca-results-summary',
				null,
				array(
					'query' => $biblioteca_query,
				)
			);
			?>


			<?php
			get_template_part(
				'template-parts/biblioteca/biblioteca-loop',
				null,
				array(
					'query' => $biblioteca_query,
				)
			);
			?>

		</div>


		<aside
			class="biblioteca-layout__sidebar"
			aria-label="<?php esc_attr_e( 'Coleções da Biblioteca', 'conferp' ); ?>"
		>

			<?php
			get_template_part(
				'template-parts/biblioteca/biblioteca-sidebar'
			);
			?>

		</aside>

	</div>

</main>

<?php
wp_reset_postdata();

get_footer();