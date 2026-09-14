<?php
/**
 * CONFERP Global Utility Bar.
 *
 * Componente institucional global compartilhado entre
 * o CONFERP e todos os CONRERPs.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * CONRERPs.
 *
 * Estes links fazem parte da estrutura global do tema.
 * Posteriormente esta configuração poderá ser movida
 * para um arquivo específico em /inc/.
 */
$conferp_regionals = array(
	'' => 'Acesse seu CONRERP',

	// Atualizar posteriormente com os endereços oficiais definitivos.
	'https://www.conrerp1.org.br/' => 'CONRERP 1ª Região',
	'https://www.conrerp2.org.br/' => 'CONRERP 2ª Região',
	'https://www.conrerp3.org.br/' => 'CONRERP 3ª Região',
	'https://www.conrerp4.org.br/' => 'CONRERP 4ª Região',
	'https://www.conrerp5.org.br/' => 'CONRERP 5ª Região',
	'https://www.conrerp6.org.br/' => 'CONRERP 6ª Região',
);
?>

<div class="conferp-utility-bar">

	<div class="container-fluid conferp-utility-bar__container">


		<!-- ==================================================
			LEFT
			CONFERP + Regional selector
		================================================== -->

		<div class="conferp-utility-bar__brand">

			<a
				class="conferp-utility-bar__federal-logo"
				href="https://www.conferp.org.br/"
				aria-label="<?php esc_attr_e( 'Acessar o site do CONFERP', 'conferp' ); ?>"
			>

				<?php
				conferp_icon(
					'utility-symbol',
					array(
						'class' => 'conferp-utility-bar__federal-logo-icon',
					)
				);
				?>

			</a>


			<div class="conferp-utility-bar__regional-selector">

				<label
					for="conferp-regional-selector"
					class="screen-reader-text"
				>
					<?php esc_html_e( 'Acesse seu CONRERP', 'conferp' ); ?>
				</label>


				<select
					id="conferp-regional-selector"
					class="form-select"
					data-conferp-regional-selector
				>

					<?php foreach ( $conferp_regionals as $url => $label ) : ?>

						<option
							value="<?php echo esc_url( $url ); ?>"
							<?php
							if ( empty( $url ) ) {
								echo 'selected disabled';
							}
							?>
						>
							<?php echo esc_html( $label ); ?>
						</option>

					<?php endforeach; ?>

				</select>

			</div>

		</div>


		<!-- ==================================================
			CENTER
			CONFERP Institutional Links
		================================================== -->

		<nav
			class="conferp-utility-bar__federal-links"
			aria-label="<?php esc_attr_e( 'Links institucionais do CONFERP', 'conferp' ); ?>"
		>


			<!-- Ouvidoria -->

			<a
				href="https://www.conferp.org.br/ouvidoria/"
				class="conferp-utility-bar__link"
			>

				<span class="conferp-utility-bar__link-icon">

					<?php
					conferp_icon( 'chat' );
					?>

				</span>

				<span>
					<?php esc_html_e( 'Ouvidoria', 'conferp' ); ?>
				</span>

			</a>


			<!-- Transparência -->

			<a
				href="https://www.conferp.org.br/transparencia/"
				class="conferp-utility-bar__link"
			>

				<span
					class="
						conferp-utility-bar__link-icon
						conferp-utility-bar__link-icon--transparency
					"
				>

					<?php
					conferp_icon( 'transparency' );
					?>

				</span>

				<span>
					<?php esc_html_e( 'Transparência CONFERP', 'conferp' ); ?>
				</span>

			</a>

		</nav>


		<!-- ==================================================
			RIGHT
			Accessibility + Registered Area
		================================================== -->

		<div class="conferp-utility-bar__actions">


			<div
				class="conferp-utility-bar__accessibility"
				role="group"
				aria-label="<?php esc_attr_e( 'Recursos de acessibilidade', 'conferp' ); ?>"
			>


				<!-- VLibras -->

				<button
					type="button"
					class="conferp-accessibility-control"
					data-conferp-vlibras
					aria-label="<?php esc_attr_e( 'Ativar VLibras', 'conferp' ); ?>"
				>

					<?php
					conferp_icon(
						'vlibras',
						array(
							'class' => 'conferp-accessibility-control__icon',
						)
					);
					?>

				</button>


				<span
					class="conferp-utility-bar__separator"
					aria-hidden="true"
				></span>


				<!-- Accessibility information -->

				<a
					href="<?php echo esc_url( home_url( '/acessibilidade/' ) ); ?>"
					class="conferp-accessibility-control"
					aria-label="<?php esc_attr_e( 'Informações de acessibilidade', 'conferp' ); ?>"
				>

					<?php
					conferp_icon(
						'accessibility',
						array(
							'class' => 'conferp-accessibility-control__icon',
						)
					);
					?>

				</a>


				<span
					class="conferp-utility-bar__separator"
					aria-hidden="true"
				></span>


				<!-- High contrast -->

				<button
					type="button"
					class="conferp-accessibility-control"
					data-conferp-contrast
					aria-label="<?php esc_attr_e( 'Ativar ou desativar alto contraste', 'conferp' ); ?>"
					aria-pressed="false"
				>

					<?php
					conferp_icon(
						'universal-access',
						array(
							'class' => 'conferp-accessibility-control__icon',
						)
					);
					?>

				</button>


				<span
					class="conferp-utility-bar__separator"
					aria-hidden="true"
				></span>


				<!-- Increase font -->

				<button
					type="button"
					class="
						conferp-accessibility-control
						conferp-accessibility-control--text
					"
					data-conferp-font-increase
					aria-label="<?php esc_attr_e( 'Aumentar tamanho do texto', 'conferp' ); ?>"
				>
					A+
				</button>


				<!-- Decrease font -->

				<button
					type="button"
					class="
						conferp-accessibility-control
						conferp-accessibility-control--text
					"
					data-conferp-font-decrease
					aria-label="<?php esc_attr_e( 'Diminuir tamanho do texto', 'conferp' ); ?>"
				>
					A-
				</button>

			</div>


			<!-- ==================================================
				Registered Area
			================================================== -->

			<div class="conferp-utility-bar__registered">

				<a
					href="#"
					class="btn conferp-utility-bar__registered-button"
				>
					<?php esc_html_e( 'Área do Registrado', 'conferp' ); ?>
				</a>

			</div>

		</div>

	</div>

</div>