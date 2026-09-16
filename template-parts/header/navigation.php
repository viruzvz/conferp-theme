<?php
/**
 * Primary Navigation.
 *
 * Navegação principal institucional.
 *
 * O conteúdo do menu é administrado pelo WordPress
 * através da localização "primary".
 *
 * Este componente representa a navegação desktop.
 * A navegação responsiva é tratada separadamente em
 * mobile-navigation.php.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;
?>

<nav
	id="site-navigation"
	class="primary-navigation"
	aria-label="<?php esc_attr_e( 'Navegação principal', 'conferp' ); ?>"
>

	<div class="container">

		<?php
		if ( has_nav_menu( 'primary' ) ) {

			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'primary-navigation__menu',
					'menu_id'        => 'primary-menu',
					'fallback_cb'    => false,
					'depth'          => 2,
				)
			);

		} elseif ( current_user_can( 'edit_theme_options' ) ) {
			?>

			<div class="primary-navigation__empty">

				<?php
				printf(
					/* translators: %s: WordPress menu configuration URL. */
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
					esc_url( admin_url( 'nav-menus.php' ) )
				);
				?>

			</div>

			<?php
		}
		?>

	</div>

</nav>