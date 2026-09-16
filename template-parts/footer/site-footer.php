<?php
/**
 * Site Footer
 *
 * Rodapé institucional da instalação atual.
 *
 * Este componente pode representar:
 *
 * - CONFERP;
 * - CONRERP 1ª Região;
 * - CONRERP 2ª Região;
 * - CONRERP 3ª Região;
 * - etc.
 *
 * A identidade e os dados deste componente pertencem
 * à instituição responsável pela instalação atual.
 *
 * O Subfooter Federal NÃO pertence a este componente.
 * Ele é carregado separadamente pelo footer.php através
 * de template-parts/global/subfooter.php.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;


/**
 * ==========================================================
 * Institutional Data
 * ==========================================================
 */

$institution_name = get_theme_mod(
	'conferp_institution_name',
	'CONSELHO FEDERAL DE PROFISSIONAIS DE RELAÇÕES PÚBLICAS'
);


/**
 * ==========================================================
 * Contact Data
 * ==========================================================
 */

$contact_email = get_theme_mod(
	'conferp_contact_email',
	get_option( 'admin_email' )
);

$contact_phone = get_theme_mod(
	'conferp_contact_phone',
	''
);

$contact_address = get_theme_mod(
	'conferp_contact_address',
	''
);


/**
 * ==========================================================
 * Social Networks
 * ==========================================================
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

$has_social_links =
	! empty( $facebook_url ) ||
	! empty( $linkedin_url ) ||
	! empty( $instagram_url ) ||
	! empty( $youtube_url );

?>

<footer
	id="colophon"
	class="site-footer"
>

	<div class="container-fluid">

		<div class="site-footer__inner">


			<!-- ==================================================
				Institutional Branding
			================================================== -->

			<div class="site-footer__branding">

				<a
					href="<?php echo esc_url( home_url( '/' ) ); ?>"
					class="site-footer__branding-link"
					rel="home"
				>

					<span class="site-footer__logo">

						<?php if ( has_custom_logo() ) : ?>

							<?php
							$custom_logo_id = get_theme_mod(
								'custom_logo'
							);

							echo wp_get_attachment_image(
								$custom_logo_id,
								'full',
								false,
								array(
									'class' => 'site-footer__logo-image',
									'alt'   => get_bloginfo( 'name' ),
								)
							);
							?>

						<?php else : ?>

							<img
								class="site-footer__logo-image"
								src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/global/conferp-symbol-white.svg' ); ?>"
								alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
							>

						<?php endif; ?>

					</span>


					<?php if ( ! empty( $institution_name ) ) : ?>

						<span class="site-footer__institution-name">

							<?php echo esc_html( $institution_name ); ?>

						</span>

					<?php endif; ?>

				</a>

			</div>


			<!-- ==================================================
				Acesse
			================================================== -->

			<nav
				class="site-footer__column"
				aria-labelledby="site-footer-access-title"
			>

				<h2
					id="site-footer-access-title"
					class="site-footer__title"
				>
					<?php esc_html_e( 'Acesse', 'conferp' ); ?>
				</h2>

				<ul class="site-footer__links">

					<li>
						<a href="<?php echo esc_url( home_url( '/mapa-do-site/' ) ); ?>">
							<?php esc_html_e( 'Site map', 'conferp' ); ?>
						</a>
					</li>

					<li>
						<a href="<?php echo esc_url( home_url( '/regionais/' ) ); ?>">
							<?php esc_html_e( 'Regionais', 'conferp' ); ?>
						</a>
					</li>

					<li>
						<a href="<?php echo esc_url( home_url( '/transparencia/' ) ); ?>">
							<?php esc_html_e( 'Transparência', 'conferp' ); ?>
						</a>
					</li>

					<li>
						<a href="<?php echo esc_url( home_url( '/area-do-registrado/' ) ); ?>">
							<?php esc_html_e( 'Área do Registrado', 'conferp' ); ?>
						</a>
					</li>

				</ul>

			</nav>


			<!-- ==================================================
				Quick Links
			================================================== -->

			<nav
				class="site-footer__column"
				aria-labelledby="site-footer-quick-links-title"
			>

				<h2
					id="site-footer-quick-links-title"
					class="site-footer__title"
				>
					<?php esc_html_e( 'Links rápidos', 'conferp' ); ?>
				</h2>

				<ul class="site-footer__links">

					<li>
						<a href="<?php echo esc_url( home_url( '/quem-somos/' ) ); ?>">
							<?php esc_html_e( 'Quem somos', 'conferp' ); ?>
						</a>
					</li>

					<li>
						<a href="<?php echo esc_url( home_url( '/legislacao/' ) ); ?>">
							<?php esc_html_e( 'Legislação', 'conferp' ); ?>
						</a>
					</li>

					<li>
						<a href="<?php echo esc_url( home_url( '/noticias/' ) ); ?>">
							<?php esc_html_e( 'Notícias', 'conferp' ); ?>
						</a>
					</li>

				</ul>

			</nav>


			<!-- ==================================================
				Policies
			================================================== -->

			<nav
				class="site-footer__column"
				aria-labelledby="site-footer-policies-title"
			>

				<h2
					id="site-footer-policies-title"
					class="site-footer__title"
				>
					<?php esc_html_e( 'Políticas', 'conferp' ); ?>
				</h2>

				<ul class="site-footer__links">

					<li>
						<a href="<?php echo esc_url( home_url( '/politica-de-privacidade/' ) ); ?>">
							<?php esc_html_e( 'Política de Privacidade', 'conferp' ); ?>
						</a>
					</li>

					<li>
						<a href="<?php echo esc_url( home_url( '/politica-de-cookies/' ) ); ?>">
							<?php esc_html_e( 'Política de Cookies', 'conferp' ); ?>
						</a>
					</li>

					<li>
						<a href="<?php echo esc_url( home_url( '/lgpd/' ) ); ?>">
							<?php esc_html_e( 'Lei de proteção de dados (LGPD)', 'conferp' ); ?>
						</a>
					</li>

					<li>
						<a href="<?php echo esc_url( home_url( '/acessibilidade/' ) ); ?>">
							<?php esc_html_e( 'Acessibilidade', 'conferp' ); ?>
						</a>
					</li>

				</ul>

			</nav>


			<!-- ==================================================
				Contact
			================================================== -->

			<div class="site-footer__column site-footer__contact">

				<h2 class="site-footer__title">
					<?php esc_html_e( 'Contatos', 'conferp' ); ?>
				</h2>


				<?php if ( ! empty( $contact_email ) ) : ?>

					<a
						class="site-footer__contact-link"
						href="<?php echo esc_url( 'mailto:' . antispambot( $contact_email ) ); ?>"
					>
						<?php echo esc_html( antispambot( $contact_email ) ); ?>
					</a>

				<?php endif; ?>


				<?php if ( ! empty( $contact_phone ) ) : ?>

					<p class="site-footer__contact-item">
						<?php echo esc_html( $contact_phone ); ?>
					</p>

				<?php endif; ?>


				<?php if ( ! empty( $contact_address ) ) : ?>

					<address class="site-footer__address">
						<?php echo nl2br( esc_html( $contact_address ) ); ?>
					</address>

				<?php endif; ?>

			</div>


			<!-- ==================================================
				Social Networks
			================================================== -->

			<?php if ( $has_social_links ) : ?>

				<div class="site-footer__column site-footer__social">

					<h2 class="site-footer__title">
						<?php esc_html_e( 'Veja também', 'conferp' ); ?>
					</h2>


					<nav
						class="site-footer__social-links"
						aria-label="<?php esc_attr_e( 'Redes sociais', 'conferp' ); ?>"
					>


						<?php if ( ! empty( $facebook_url ) ) : ?>

							<a
								href="<?php echo esc_url( $facebook_url ); ?>"
								class="site-footer__social-link"
								target="_blank"
								rel="noopener noreferrer"
								aria-label="<?php esc_attr_e( 'Facebook', 'conferp' ); ?>"
							>
								<?php
								conferp_icon(
									'facebook',
									array(
										'class' => 'site-footer__social-icon',
									)
								);
								?>
							</a>

						<?php endif; ?>


						<?php if ( ! empty( $linkedin_url ) ) : ?>

							<a
								href="<?php echo esc_url( $linkedin_url ); ?>"
								class="site-footer__social-link"
								target="_blank"
								rel="noopener noreferrer"
								aria-label="<?php esc_attr_e( 'LinkedIn', 'conferp' ); ?>"
							>
								<?php
								conferp_icon(
									'linkedin',
									array(
										'class' => 'site-footer__social-icon',
									)
								);
								?>
							</a>

						<?php endif; ?>


						<?php if ( ! empty( $instagram_url ) ) : ?>

							<a
								href="<?php echo esc_url( $instagram_url ); ?>"
								class="site-footer__social-link"
								target="_blank"
								rel="noopener noreferrer"
								aria-label="<?php esc_attr_e( 'Instagram', 'conferp' ); ?>"
							>
								<?php
								conferp_icon(
									'instagram',
									array(
										'class' => 'site-footer__social-icon',
									)
								);
								?>
							</a>

						<?php endif; ?>


						<?php if ( ! empty( $youtube_url ) ) : ?>

							<a
								href="<?php echo esc_url( $youtube_url ); ?>"
								class="site-footer__social-link"
								target="_blank"
								rel="noopener noreferrer"
								aria-label="<?php esc_attr_e( 'YouTube', 'conferp' ); ?>"
							>
								<?php
								conferp_icon(
									'youtube',
									array(
										'class' => 'site-footer__social-icon',
									)
								);
								?>
							</a>

						<?php endif; ?>


					</nav>

				</div>

			<?php endif; ?>


		</div>

	</div>

</footer>