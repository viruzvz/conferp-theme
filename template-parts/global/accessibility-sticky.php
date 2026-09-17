<?php
/**
 * CONFERP Global Accessibility Sticky.
 *
 * Federal accessibility component shared across CONFERP
 * and all CONRERP installations.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;
?>

<div
	class="conferp-accessibility-sticky"
	data-conferp-accessibility-sticky
>

	<div
		id="conferp-accessibility-sticky-panel"
		class="conferp-accessibility-sticky__panel"
		data-conferp-accessibility-panel
		aria-hidden="true"
	>

		<!-- VLibras -->

		<button
			type="button"
			class="conferp-accessibility-sticky__control"
			data-conferp-vlibras
			aria-label="<?php esc_attr_e( 'Ativar VLibras', 'conferp' ); ?>"
			title="<?php esc_attr_e( 'VLibras', 'conferp' ); ?>"
		>
			<?php
			conferp_icon(
				'vlibras',
				array(
					'class' => 'conferp-accessibility-sticky__control-icon',
				)
			);
			?>
		</button>


		<!-- Página de acessibilidade -->

		<a
			href="<?php echo esc_url( home_url( '/acessibilidade/' ) ); ?>"
			class="conferp-accessibility-sticky__control"
			aria-label="<?php esc_attr_e( 'Acessar página de acessibilidade', 'conferp' ); ?>"
			title="<?php esc_attr_e( 'Acessibilidade', 'conferp' ); ?>"
		>
			<?php
			conferp_icon(
				'universal-access',
				array(
					'class' => 'conferp-accessibility-sticky__control-icon',
				)
			);
			?>
		</a>


		<!-- Alto contraste -->

		<button
			type="button"
			class="conferp-accessibility-sticky__control"
			data-conferp-contrast
			aria-label="<?php esc_attr_e( 'Ativar ou desativar alto contraste', 'conferp' ); ?>"
			aria-pressed="false"
			title="<?php esc_attr_e( 'Alto contraste', 'conferp' ); ?>"
		>
			<?php
			conferp_icon(
				'contrast',
				array(
					'class' => 'conferp-accessibility-sticky__control-icon',
				)
			);
			?>
		</button>


		<!-- Aumentar fonte -->

		<button
			type="button"
			class="
				conferp-accessibility-sticky__control
				conferp-accessibility-sticky__control--text
			"
			data-conferp-font-increase
			aria-label="<?php esc_attr_e( 'Aumentar tamanho do texto', 'conferp' ); ?>"
			title="<?php esc_attr_e( 'Aumentar fonte', 'conferp' ); ?>"
		>
			A+
		</button>


		<!-- Diminuir fonte -->

		<button
			type="button"
			class="
				conferp-accessibility-sticky__control
				conferp-accessibility-sticky__control--text
			"
			data-conferp-font-decrease
			aria-label="<?php esc_attr_e( 'Diminuir tamanho do texto', 'conferp' ); ?>"
			title="<?php esc_attr_e( 'Diminuir fonte', 'conferp' ); ?>"
		>
			A-
		</button>

	</div>


	<!-- Trigger -->

	<button
		type="button"
		class="conferp-accessibility-sticky__trigger"
		data-conferp-accessibility-trigger
		aria-label="<?php esc_attr_e( 'Abrir recursos de acessibilidade', 'conferp' ); ?>"
		aria-expanded="false"
		aria-controls="conferp-accessibility-sticky-panel"
	>

		<?php
		conferp_icon(
			'accessibility',
			array(
				'class' => 'conferp-accessibility-sticky__trigger-icon',
			)
		);
		?>

	</button>

</div>