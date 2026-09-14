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
 * Estes links pertencem à estrutura global do tema.
 */
$conferp_regionals = array(
	'' => 'Acesse seu CONRERP',

	// Atualizar posteriormente com os endereços oficiais.
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
					class="conferp-utility-bar__federal-logo-image"
					src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/global/conferp-symbol-white.svg' ); ?>"
					alt=""
					width="58"
					height="42"
					aria-hidden="true"
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
			Links institucionais CONFERP
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

					<?php conferp_icon( 'chat' ); ?>

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

					<?php conferp_icon( 'transparency' ); ?>

				</span>

				<span>
					<?php esc_html_e( 'Transparência CONFERP', 'conferp' ); ?>
				</span>

			</a>

		</nav>


		<!-- ==================================================
			RIGHT
			Acessibilidade + Área do Registrado
		================================================== -->

		<div class="conferp-utility-bar__actions">


			<div
				class="conferp-utility-bar__accessibility"
				role="group"
				aria-label="<?php esc_attr_e( 'Recursos de acessibilidade', 'conferp' ); ?>"
			>


				<!-- ==================================================
					VLibras
				================================================== -->

				<button
					type="button"
					class="
						conferp-accessibility-control
						conferp-accessibility-control--icon
					"
					data-conferp-vlibras
					aria-label="<?php esc_attr_e( 'Ativar VLibras', 'conferp' ); ?>"
				>

					<?php
					conferp_icon(
						'vlibras',
						array(
							'class' => 'conferp-accessibility-control__icon conferp-accessibility-control__icon--vlibras',
						)
					);
					?>

				</button>


				<span
					class="conferp-utility-bar__separator"
					aria-hidden="true"
				></span>


				<!-- ==================================================
					Página de acessibilidade
				================================================== -->

				<a
					href="<?php echo esc_url( home_url( '/acessibilidade/' ) ); ?>"
					class="
						conferp-accessibility-control
						conferp-accessibility-control--icon
					"
					aria-label="<?php esc_attr_e( 'Acessar página de acessibilidade', 'conferp' ); ?>"
				>

					<?php
					conferp_icon(
						'universal-access',
						array(
							'class' => 'conferp-accessibility-control__icon conferp-accessibility-control__icon--universal-access',
						)
					);
					?>

				</a>


				<span
					class="conferp-utility-bar__separator"
					aria-hidden="true"
				></span>


				<!-- ==================================================
					Alto contraste
				================================================== -->

				<button
					type="button"
					class="
						conferp-accessibility-control
						conferp-accessibility-control--icon
					"
					data-conferp-contrast
					aria-label="<?php esc_attr_e( 'Ativar ou desativar alto contraste', 'conferp' ); ?>"
					aria-pressed="false"
				>

					<?php
					conferp_icon(
						'contrast',
						array(
							'class' => 'conferp-accessibility-control__icon conferp-accessibility-control__icon--contrast',
						)
					);
					?>

				</button>


				<span
					class="conferp-utility-bar__separator"
					aria-hidden="true"
				></span>


				<!-- ==================================================
					Aumentar fonte
				================================================== -->

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


				<!-- ==================================================
					Diminuir fonte
				================================================== -->

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
				Área do Registrado
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