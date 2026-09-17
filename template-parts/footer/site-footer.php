<?php
/**
 * Site Footer.
 *
 * Institutional footer for the current CONFERP / CONRERP
 * installation.
 *
 * Footer navigation columns are managed through WordPress
 * menu locations. Contact information and social networks
 * are managed through the Theme Customizer.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;


/**
 * ==========================================================
 * Institutional data
 * ==========================================================
 */

$institution_name = get_theme_mod(
	'conferp_institution_name',
	'CONSELHO FEDERAL DE PROFISSIONAIS DE RELAÇÕES PÚBLICAS'
);


/**
 * ==========================================================
 * Footer navigation menus
 * ==========================================================
 */

$footer_menu_1 = conferp_get_menu_by_location( 'footer_1' );
$footer_menu_2 = conferp_get_menu_by_location( 'footer_2' );
$footer_menu_3 = conferp_get_menu_by_location( 'footer_3' );


/**
 * ==========================================================
 * Contact information
 * ==========================================================
 */

$contact_title = get_theme_mod(
	'conferp_footer_contact_title',
	'Contatos'
);

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
 * Social networks
 * ==========================================================
 */

$social_title = get_theme_mod(
	'conferp_footer_social_title',
	'Veja também'
);

$social_links = array(
	'facebook' => array(
		'url'   => get_theme_mod( 'conferp_social_facebook', '' ),
		'label' => __( 'Facebook', 'conferp' ),
	),
	'linkedin' => array(
		'url'   => get_theme_mod( 'conferp_social_linkedin', '' ),
		'label' => __( 'LinkedIn', 'conferp' ),
	),
	'instagram' => array(
		'url'   => get_theme_mod( 'conferp_social_instagram', '' ),
		'label' => __( 'Instagram', 'conferp' ),
	),
	'youtube' => array(
		'url'   => get_theme_mod( 'conferp_social_youtube', '' ),
		'label' => __( 'YouTube', 'conferp' ),
	),
);


/**
 * Check if at least one social network exists.
 */
$has_social_links = false;

foreach ( $social_links as $social ) {

	if ( ! empty( $social['url'] ) ) {
		$has_social_links = true;
		break;
	}
}


/**
 * ==========================================================
 * Footer logo
 * ==========================================================
 *
 * Use the WordPress Custom Logo when available.
 *
 * If no custom logo has been configured, use the official
 * white CONFERP symbol bundled with the theme.
 */

$custom_logo_id = get_theme_mod( 'custom_logo' );

?>

<footer
	id="colophon"
	class="site-footer"
	role="contentinfo"
>

	<div class="container-fluid">

		<div class="site-footer__inner">


			<!-- ==================================================
			     Institutional Branding
			     ================================================== -->

			<div class="site-footer__branding">

				<a
					class="site-footer__branding-link"
					href="<?php echo esc_url( home_url( '/' ) ); ?>"
					rel="home"
				>

					<span class="site-footer__logo">

						<?php if ( $custom_logo_id ) : ?>

							<?php
							echo wp_get_attachment_image(
								$custom_logo_id,
								'full',
								false,
								array(
									'class' => 'site-footer__logo-image',
									'alt'   => '',
								)
							);
							?>

						<?php else : ?>

							<img
								class="site-footer__logo-image"
								src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/global/conferp-symbol-white.svg' ); ?>"
								alt=""
								aria-hidden="true"
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
			     Footer Menu — Column 1
			     ================================================== -->

			<?php if ( $footer_menu_1 ) : ?>

				<nav
					class="site-footer__column site-footer__navigation"
					aria-labelledby="site-footer-menu-1-title"
				>

					<h2
						id="site-footer-menu-1-title"
						class="site-footer__title"
					>
						<?php echo esc_html( $footer_menu_1->name ); ?>
					</h2>


					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer_1',
							'container'      => false,
							'menu_class'     => 'site-footer__links',
							'menu_id'        => 'site-footer-menu-1',
							'fallback_cb'    => false,
							'depth'          => 1,
						)
					);
					?>

				</nav>

			<?php endif; ?>


			<!-- ==================================================
			     Footer Menu — Column 2
			     ================================================== -->

			<?php if ( $footer_menu_2 ) : ?>

				<nav
					class="site-footer__column site-footer__navigation"
					aria-labelledby="site-footer-menu-2-title"
				>

					<h2
						id="site-footer-menu-2-title"
						class="site-footer__title"
					>
						<?php echo esc_html( $footer_menu_2->name ); ?>
					</h2>


					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer_2',
							'container'      => false,
							'menu_class'     => 'site-footer__links',
							'menu_id'        => 'site-footer-menu-2',
							'fallback_cb'    => false,
							'depth'          => 1,
						)
					);
					?>

				</nav>

			<?php endif; ?>


			<!-- ==================================================
			     Footer Menu — Column 3
			     ================================================== -->

			<?php if ( $footer_menu_3 ) : ?>

				<nav
					class="site-footer__column site-footer__navigation"
					aria-labelledby="site-footer-menu-3-title"
				>

					<h2
						id="site-footer-menu-3-title"
						class="site-footer__title"
					>
						<?php echo esc_html( $footer_menu_3->name ); ?>
					</h2>


					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer_3',
							'container'      => false,
							'menu_class'     => 'site-footer__links',
							'menu_id'        => 'site-footer-menu-3',
							'fallback_cb'    => false,
							'depth'          => 1,
						)
					);
					?>

				</nav>

			<?php endif; ?>


			<!-- ==================================================
			     Institutional Contact
			     ================================================== -->

			<?php if ( $contact_email || $contact_phone || $contact_address ) : ?>

				<div class="site-footer__column site-footer__contact">

					<?php if ( ! empty( $contact_title ) ) : ?>

						<h2 class="site-footer__title">
							<?php echo esc_html( $contact_title ); ?>
						</h2>

					<?php endif; ?>


					<?php if ( ! empty( $contact_email ) ) : ?>

						<a
							class="site-footer__contact-link"
							href="mailto:<?php echo esc_attr( antispambot( $contact_email ) ); ?>"
						>
							<?php echo esc_html( antispambot( $contact_email ) ); ?>
						</a>

					<?php endif; ?>


					<?php if ( ! empty( $contact_phone ) ) : ?>

						<?php
						/**
						 * Generate a telephone-safe version while keeping
						 * the formatted version visible to the user.
						 */
						$phone_href = preg_replace(
							'/[^0-9+]/',
							'',
							$contact_phone
						);
						?>

						<p class="site-footer__contact-item">

							<a
								class="site-footer__contact-link"
								href="tel:<?php echo esc_attr( $phone_href ); ?>"
							>
								<?php echo esc_html( $contact_phone ); ?>
							</a>

						</p>

					<?php endif; ?>


					<?php if ( ! empty( $contact_address ) ) : ?>

						<address class="site-footer__address">
							<?php
							echo nl2br(
								esc_html( $contact_address )
							);
							?>
						</address>

					<?php endif; ?>

				</div>

			<?php endif; ?>


			<!-- ==================================================
			     Social Networks
			     ================================================== -->

			<?php if ( $has_social_links ) : ?>

				<div class="site-footer__column site-footer__social">

					<?php if ( ! empty( $social_title ) ) : ?>

						<h2 class="site-footer__title">
							<?php echo esc_html( $social_title ); ?>
						</h2>

					<?php endif; ?>


					<div class="site-footer__social-links">

						<?php foreach ( $social_links as $icon => $social ) : ?>

							<?php if ( empty( $social['url'] ) ) : ?>
								<?php continue; ?>
							<?php endif; ?>


							<a
								class="site-footer__social-link"
								href="<?php echo esc_url( $social['url'] ); ?>"
								target="_blank"
								rel="noopener noreferrer"
								aria-label="<?php echo esc_attr( $social['label'] ); ?>"
							>

								<?php
								conferp_icon(
									$icon,
									array(
										'class' => 'site-footer__social-icon',
									)
								);
								?>

							</a>

						<?php endforeach; ?>

					</div>

				</div>

			<?php endif; ?>


		</div><!-- .site-footer__inner -->

	</div><!-- .container-fluid -->

</footer><!-- #colophon -->