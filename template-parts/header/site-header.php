<?php
/**
 * Site Header
 *
 * Cabeçalho institucional dinâmico do tema.
 *
 * Este componente representa a instituição responsável
 * pela instalação atual do WordPress, podendo ser:
 *
 * - CONFERP;
 * - CONRERP 1ª Região;
 * - CONRERP 2ª Região;
 * - CONRERP 3ª Região;
 * - etc.
 *
 * A Utility Bar NÃO pertence a este componente.
 * Ela é carregada separadamente pelo header.php como
 * elemento global do Sistema CONFERP/CONRERPs.
 *
 * Estrutura:
 *
 * site-header
 * ├── site-header__main
 * │   └── branding.php
 * │
 * ├── navigation.php
 * │
 * └── mobile-navigation.php
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;
?>

<header
	id="masthead"
	class="site-header"
>


	<!-- ==================================================
		Institutional Header
		Logo + Institution Name + Social Networks
	================================================== -->

	<div class="site-header__main">

		<div class="container-fluid">

			<div class="site-header__inner">

				<?php
				/**
				 * Institutional Branding
				 *
				 * Responsável por:
				 *
				 * - Logo institucional;
				 * - Nome da instituição;
				 * - Redes sociais;
				 * - E-mail institucional.
				 */
				get_template_part(
					'template-parts/header/branding'
				);
				?>

			</div>

		</div>

	</div>


	<!-- ==================================================
		Desktop Navigation
	================================================== -->

	<div class="site-header__navigation">

		<?php
			/**
			 * Desktop primary navigation.
			 */
			get_template_part(
				'template-parts/header/navigation'
			);
		?>

	</div>


	<!-- ==================================================
		Mobile Navigation
	================================================== -->

	<div class="site-header__mobile-navigation">

		<?php
			/**
			 * Mobile primary navigation.
			 */
			get_template_part(
				'template-parts/header/mobile-navigation'
			);
		?>

	</div>


</header>