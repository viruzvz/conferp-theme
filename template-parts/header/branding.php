<?php
/**
 * Institutional Branding
 *
 * Identidade visual da instituição responsável pela
 * instalação atual do WordPress.
 *
 * Este componente pode representar:
 *
 * - CONFERP;
 * - CONRERP 1ª Região;
 * - CONRERP 2ª Região;
 * - CONRERP 3ª Região;
 * - etc.
 *
 * Elementos:
 *
 * - Logo institucional;
 * - Nome institucional;
 * - Redes sociais;
 * - E-mail institucional.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;


/**
 * ==========================================================
 * Institutional Data
 * ==========================================================
 *
 * O nome institucional utiliza um Theme Mod próprio.
 *
 * Enquanto não houver configuração, o CONFERP é utilizado
 * como valor padrão da instalação inicial.
 */

$institution_name = get_theme_mod(
	'conferp_institution_name',
	'CONSELHO FEDERAL DE PROFISSIONAIS DE RELAÇÕES PÚBLICAS'
);


/**
 * ==========================================================
 * Social Networks
 * ==========================================================
 *
 * Estes campos serão registrados posteriormente no
 * Customizer / configurações do tema.
 *
 * Campos vazios não serão exibidos.
 */

$facebook_url = get_theme_mod(
	'conferp_social_facebook',
	''
);

$linkedin_url = get_theme_mod(
	'conferp_social_linkedin',
	''
);

$instagram_url = get_theme_mod(
	'conferp_social_instagram',
	''
);

$youtube_url = get_theme_mod(
	'conferp_social_youtube',
	''
);

$contact_email = get_theme_mod(
	'conferp_contact_email',
	get_option( 'admin_email' )
);


/**
 * Verifica se existe ao menos um canal institucional.
 */
$has_social_links =
	! empty( $facebook_url ) ||
	! empty( $linkedin_url ) ||
	! empty( $instagram_url ) ||
	! empty( $youtube_url ) ||
	! empty( $contact_email );

?>

<div class="site-branding">


	<!-- ==================================================
		Institutional Identity
	================================================== -->

	<div class="site-branding__identity">


		<!-- ==================================================
			Logo
		================================================== -->

		<div class="site-branding__logo">

			<a
				href="<?php echo esc_url( home_url( '/' ) ); ?>"
				class="site-branding__logo-link"
				rel="home"
				aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
			>

				<?php if ( has_custom_logo() ) : ?>

					<?php
					/**
					 * Custom Logo definido pelo WordPress.
					 */
					$custom_logo_id = get_theme_mod(
						'custom_logo'
					);

					echo wp_get_attachment_image(
						$custom_logo_id,
						'full',
						false,
						array(
							'class' => 'site-branding__logo-image',
							'alt'   => get_bloginfo( 'name' ),
						)
					);
					?>

				<?php else : ?>

					<?php
					/**
					 * Fallback padrão CONFERP.
					 *
					 * Utilizado enquanto nenhuma logo tiver
					 * sido definida pelo administrador.
					 */
					?>

					<img
						class="site-branding__logo-image"
						src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-oficial-federal.svg' ); ?>"
						alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
					>

				<?php endif; ?>

			</a>

		</div>


		<!-- ==================================================
			Institution Name
		================================================== -->

		<?php if ( ! empty( $institution_name ) ) : ?>

			<div class="site-branding__institution">

				<span class="site-branding__institution-name">

					<?php
					echo esc_html(
						$institution_name
					);
					?>

				</span>

			</div>

		<?php endif; ?>


	</div>


	<!-- ==================================================
		Social Networks
	================================================== -->

	<?php if ( $has_social_links ) : ?>

		<nav
			class="site-branding__social"
			aria-label="<?php esc_attr_e( 'Redes sociais e contato', 'conferp' ); ?>"
		>


			<!-- Facebook -->

			<?php if ( ! empty( $facebook_url ) ) : ?>

				<a
					href="<?php echo esc_url( $facebook_url ); ?>"
					class="site-branding__social-link"
					target="_blank"
					rel="noopener noreferrer"
					aria-label="<?php esc_attr_e( 'Facebook', 'conferp' ); ?>"
				>

					<?php
					conferp_icon(
						'facebook',
						array(
							'class' => 'site-branding__social-icon',
						)
					);
					?>

				</a>

			<?php endif; ?>


			<!-- LinkedIn -->

			<?php if ( ! empty( $linkedin_url ) ) : ?>

				<a
					href="<?php echo esc_url( $linkedin_url ); ?>"
					class="site-branding__social-link"
					target="_blank"
					rel="noopener noreferrer"
					aria-label="<?php esc_attr_e( 'LinkedIn', 'conferp' ); ?>"
				>

					<?php
					conferp_icon(
						'linkedin',
						array(
							'class' => 'site-branding__social-icon',
						)
					);
					?>

				</a>

			<?php endif; ?>


			<!-- Instagram -->

			<?php if ( ! empty( $instagram_url ) ) : ?>

				<a
					href="<?php echo esc_url( $instagram_url ); ?>"
					class="site-branding__social-link"
					target="_blank"
					rel="noopener noreferrer"
					aria-label="<?php esc_attr_e( 'Instagram', 'conferp' ); ?>"
				>

					<?php
					conferp_icon(
						'instagram',
						array(
							'class' => 'site-branding__social-icon',
						)
					);
					?>

				</a>

			<?php endif; ?>


			<!-- YouTube -->

			<?php if ( ! empty( $youtube_url ) ) : ?>

				<a
					href="<?php echo esc_url( $youtube_url ); ?>"
					class="site-branding__social-link"
					target="_blank"
					rel="noopener noreferrer"
					aria-label="<?php esc_attr_e( 'YouTube', 'conferp' ); ?>"
				>

					<?php
					conferp_icon(
						'youtube',
						array(
							'class' => 'site-branding__social-icon',
						)
					);
					?>

				</a>

			<?php endif; ?>


			<!-- Separator -->

			<?php
			if (
				! empty( $contact_email ) &&
				(
					! empty( $facebook_url ) ||
					! empty( $linkedin_url ) ||
					! empty( $instagram_url ) ||
					! empty( $youtube_url )
				)
			) :
				?>

				<span
					class="site-branding__social-separator"
					aria-hidden="true"
				></span>

			<?php endif; ?>


			<!-- E-mail -->

			<?php if ( ! empty( $contact_email ) ) : ?>

				<a
					href="<?php echo esc_url( 'mailto:' . antispambot( $contact_email ) ); ?>"
					class="site-branding__social-link"
					aria-label="<?php esc_attr_e( 'Enviar e-mail', 'conferp' ); ?>"
				>

					<?php
					conferp_icon(
						'email',
						array(
							'class' => 'site-branding__social-icon site-branding__social-icon--email',
						)
					);
					?>

				</a>

			<?php endif; ?>


		</nav>

	<?php endif; ?>


</div>