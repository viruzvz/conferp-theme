<?php
/**
 * Mobile Navigation.
 *
 * Cabeçalho e navegação responsiva do tema.
 *
 * Utilizado em resoluções inferiores ao breakpoint LG
 * do Bootstrap (< 992px).
 *
 * Estrutura:
 *
 * - Botão do menu;
 * - Logo institucional;
 * - Nome da instituição;
 * - Botão de busca;
 * - Painel de navegação mobile.
 *
 * O mesmo menu "primary" utilizado no desktop é
 * reutilizado na navegação mobile.
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
?>

<div class="mobile-navigation">


	<!-- ==================================================
		Mobile Header
	================================================== -->

	<div class="mobile-navigation__header">

		<div class="container-fluid">

			<div class="mobile-navigation__header-inner">


				<!-- ==========================================
					Menu Toggle
				========================================== -->

				<button
					type="button"
					class="mobile-navigation__toggle"
					aria-controls="mobile-primary-menu-panel"
					aria-expanded="false"
					aria-label="<?php esc_attr_e( 'Abrir menu principal', 'conferp' ); ?>"
					data-conferp-mobile-menu-toggle
				>

					<span
						class="mobile-navigation__toggle-icon"
						aria-hidden="true"
					>
						<span></span>
						<span></span>
						<span></span>
					</span>

				</button>


				<!-- ==========================================
					Institutional Identity
				========================================== -->

				<a
					href="<?php echo esc_url( home_url( '/' ) ); ?>"
					class="mobile-navigation__branding"
					rel="home"
				>

					<span class="mobile-navigation__logo">

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
									'class' => 'mobile-navigation__logo-image',
									'alt'   => get_bloginfo( 'name' ),
								)
							);
							?>

						<?php else : ?>

							<img
								class="mobile-navigation__logo-image"
								src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-oficial-federal.svg' ); ?>"
								alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
							>

						<?php endif; ?>

					</span>


					<?php if ( ! empty( $institution_name ) ) : ?>

						<span class="mobile-navigation__institution-name">

							<?php
							echo esc_html(
								$institution_name
							);
							?>

						</span>

					<?php endif; ?>

				</a>


				<!-- ==========================================
					Search Toggle
				========================================== -->

				<button
					type="button"
					class="mobile-navigation__search-toggle"
					aria-controls="mobile-search-panel"
					aria-expanded="false"
					aria-label="<?php esc_attr_e( 'Abrir busca', 'conferp' ); ?>"
					data-conferp-mobile-search-toggle
				>

                <?php
                    conferp_icon(
                        'search',
                        array(
                            'class' => 'mobile-navigation__search-icon',
                        )
                    );
                ?>

				</button>


			</div>

		</div>

	</div>


	<!-- ==================================================
		Mobile Menu Panel
	================================================== -->

	<div
		id="mobile-primary-menu-panel"
		class="mobile-navigation__panel"
		data-conferp-mobile-menu
		hidden
	>

		<div class="container-fluid">

			<?php
			if ( has_nav_menu( 'primary' ) ) {

				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'mobile-navigation__menu',
						'menu_id'        => 'mobile-primary-menu',
						'fallback_cb'    => false,
						'depth'          => 2,
					)
				);

			} elseif ( current_user_can( 'edit_theme_options' ) ) {
				?>

				<div class="mobile-navigation__empty">

					<?php
					printf(
						wp_kses(
							__(
								'Nenhum menu principal foi definido. <a href="%s">Configure o menu</a>.',
								'conferp'
							),
							array(
								'a' => array(
									'href' => array(),
								),
							)
						),
						esc_url(
							admin_url( 'nav-menus.php' )
						)
					);
					?>

				</div>

				<?php
			}
			?>

		</div>

	</div>


	<!-- ==================================================
		Mobile Search Panel
	================================================== -->

	<div
		id="mobile-search-panel"
		class="mobile-navigation__search-panel"
		data-conferp-mobile-search
		hidden
	>

		<div class="container-fluid">

			<div class="mobile-navigation__search">

				<?php get_search_form(); ?>

			</div>

		</div>

	</div>


</div>