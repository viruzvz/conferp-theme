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
 * Posteriormente podemos mover esta configuração para
 * um arquivo específico em /inc/.
 */
$conferp_regionals = array(
	'' => 'Acesse seu CONRERP',

	// Atualizar com os endereços oficiais definitivos.
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

				<img
					src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/global/conferp-symbol-white.svg' ); ?>"
					alt="<?php esc_attr_e( 'CONFERP', 'conferp' ); ?>"
					width="48"
					height="48"
				>

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
			CONFERP institutional links
		================================================== -->

		<nav
			class="conferp-utility-bar__federal-links"
			aria-label="<?php esc_attr_e( 'Links institucionais do CONFERP', 'conferp' ); ?>"
		>

			<a
				href="https://www.conferp.org.br/ouvidoria/"
				class="conferp-utility-bar__link"
			>

				<span
					class="conferp-utility-bar__link-icon"
					aria-hidden="true"
				>

					<svg
						viewBox="0 0 24 24"
						focusable="false"
					>
						<path d="M4 4h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H9l-5 4v-4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Zm3 6h10v2H7Zm0-4h10v2H7Zm0 8h6v2H7Z"/>
					</svg>

				</span>

				<span>
					<?php esc_html_e( 'Ouvidoria', 'conferp' ); ?>
				</span>

			</a>


			<a
				href="https://www.conferp.org.br/transparencia/"
				class="conferp-utility-bar__link"
			>

				<span
					class="conferp-utility-bar__link-icon conferp-utility-bar__link-icon--highlight"
					aria-hidden="true"
				>

					<svg
						viewBox="0 0 24 24"
						focusable="false"
					>
						<path d="M11 17h2v-6h-2Zm1-16a10 10 0 1 0 10 10A10.01 10.01 0 0 0 12 1Zm0 18a8 8 0 1 1 8-8 8.009 8.009 0 0 1-8 8Zm-1-10h2V7h-2Z"/>
					</svg>

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


				<!-- Libras -->

				<a
					href="#"
					class="conferp-accessibility-control conferp-accessibility-control--libras"
					aria-label="<?php esc_attr_e( 'Acessibilidade em Libras', 'conferp' ); ?>"
				>

					<span aria-hidden="true">
						🖐
					</span>

				</a>


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

					<svg
						viewBox="0 0 24 24"
						focusable="false"
						aria-hidden="true"
					>
						<circle cx="12" cy="4" r="2"/>
						<path d="M4 7h16v2h-6v13h-4V9H4Z"/>
					</svg>

				</a>


				<span
					class="conferp-utility-bar__separator"
					aria-hidden="true"
				></span>


				<!-- Contrast -->

				<button
					type="button"
					class="conferp-accessibility-control"
					data-conferp-contrast
					aria-label="<?php esc_attr_e( 'Ativar ou desativar alto contraste', 'conferp' ); ?>"
					aria-pressed="false"
				>

					<svg
						viewBox="0 0 24 24"
						focusable="false"
						aria-hidden="true"
					>
						<path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm0 18V4a8 8 0 0 1 0 16Z"/>
					</svg>

				</button>


				<span
					class="conferp-utility-bar__separator"
					aria-hidden="true"
				></span>


				<!-- Increase font -->

				<button
					type="button"
					class="conferp-accessibility-control conferp-accessibility-control--text"
					data-conferp-font-increase
					aria-label="<?php esc_attr_e( 'Aumentar tamanho do texto', 'conferp' ); ?>"
				>
					A+
				</button>


				<!-- Decrease font -->

				<button
					type="button"
					class="conferp-accessibility-control conferp-accessibility-control--text"
					data-conferp-font-decrease
					aria-label="<?php esc_attr_e( 'Diminuir tamanho do texto', 'conferp' ); ?>"
				>
					A-
				</button>

			</div>


			<!-- Registered area -->

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