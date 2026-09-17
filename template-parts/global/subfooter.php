<?php
/**
 * Global Subfooter.
 *
 * Federal CONFERP component shared across CONFERP
 * and all CONRERP installations.
 *
 * IMPORTANT:
 *
 * This component belongs to the Federal Global layer.
 * Its identity, logo and institutional information must
 * not be affected by the current CONRERP installation.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;


/**
 * ==========================================================
 * Federal Global Data
 * ==========================================================
 *
 * The regional list is shared with the Utility Bar.
 * The single source of truth is:
 *
 * conferp_get_regionals()
 */

$conferp_regionals = conferp_get_regionals();


/**
 * ==========================================================
 * Federal CONFERP URL
 * ==========================================================
 *
 * Absolute URL is intentional.
 *
 * When this theme is installed on a CONRERP website,
 * clicking the Federal CONFERP logo must still lead
 * to the official CONFERP website.
 */

$conferp_federal_url = 'https://conferp.org.br/';


/**
 * ==========================================================
 * Federal Address
 * ==========================================================
 *
 * This information belongs to CONFERP and is intentionally
 * hard-coded as part of the Federal Global component.
 */

$conferp_address = 'CONFERP - BRASIL | Brasília - Distrito Federal - SCS – Quadra 2 – Bloco C – Ed. Serra Dourada – Sala 107 – CEP: 70317-900';


/**
 * ==========================================================
 * Copyright
 * ==========================================================
 */

$conferp_copyright = sprintf(
	/* translators: %s: current year. */
	__( 'Copyright © %s - Todos os direitos reservados', 'conferp' ),
	wp_date( 'Y' )
);

?>

<div class="conferp-subfooter">

	<div class="container-fluid">

		<div class="conferp-subfooter__inner">


			<!-- ==================================================
			     Federal Identity
			     ================================================== -->

			<div class="conferp-subfooter__brand">

				<a
					class="conferp-subfooter__federal-logo"
					href="<?php echo esc_url( $conferp_federal_url ); ?>"
					aria-label="<?php esc_attr_e( 'CONFERP - Conselho Federal de Profissionais de Relações Públicas', 'conferp' ); ?>"
				>

					<img
						class="conferp-subfooter__federal-logo-image"
						src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/global/conferp-symbol-white.svg' ); ?>"
						alt=""
						width="58"
						height="42"
						aria-hidden="true"
					>

				</a>


				<!-- ==============================================
				     CONRERP Selector
				     ============================================== -->

				<div class="conferp-subfooter__regional-selector">

					<label
						class="visually-hidden"
						for="conferp-subfooter-regional-selector"
					>
						<?php esc_html_e( 'Acesse seu CONRERP', 'conferp' ); ?>
					</label>


					<select
						id="conferp-subfooter-regional-selector"
						class="form-select"
						data-conferp-regional-selector
						aria-label="<?php esc_attr_e( 'Acesse seu CONRERP', 'conferp' ); ?>"
					>

						<?php foreach ( $conferp_regionals as $url => $label ) : ?>

							<option
								value="<?php echo esc_url( $url ); ?>"
								<?php echo empty( $url ) ? 'selected' : ''; ?>
							>
								<?php echo esc_html( $label ); ?>
							</option>

						<?php endforeach; ?>

					</select>

				</div>

			</div>


			<!-- ==================================================
			     Federal Address
			     ================================================== -->

			<div class="conferp-subfooter__address">

				<p>
					<?php echo esc_html( $conferp_address ); ?>
				</p>

			</div>


			<!-- ==================================================
			     Copyright
			     ================================================== -->

			<div class="conferp-subfooter__copyright">

				<p>
					<?php echo esc_html( $conferp_copyright ); ?>
				</p>

			</div>


		</div><!-- .conferp-subfooter__inner -->

	</div><!-- .container-fluid -->

</div><!-- .conferp-subfooter -->