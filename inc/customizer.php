<?php
/**
 * Theme Customizer.
 *
 * Configurações institucionais da instalação atual.
 *
 * Estas opções são específicas da instituição que utiliza
 * o tema e, portanto, podem variar entre CONFERP e CONRERPs.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;


/**
 * Register Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 *
 * @return void
 */
function conferp_customize_register( $wp_customize ) {


	/**
	 * ======================================================
	 * Institutional Identity
	 * ======================================================
	 *
	 * A logo continua utilizando o recurso nativo
	 * "Identidade do site" do WordPress.
	 *
	 * Aqui adicionamos apenas os dados complementares
	 * da instituição.
	 */

	$wp_customize->add_section(
		'conferp_institution',
		array(
			'title'       => __( 'Identidade Institucional', 'conferp' ),
			'description' => __(
				'Configure os dados da instituição responsável por esta instalação do tema.',
				'conferp'
			),
			'priority'    => 30,
		)
	);


	/**
	 * Institution Name.
	 */

	$wp_customize->add_setting(
		'conferp_institution_name',
		array(
			'default'           => 'CONSELHO FEDERAL DE PROFISSIONAIS DE RELAÇÕES PÚBLICAS',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);


	$wp_customize->add_control(
		'conferp_institution_name',
		array(
			'type'        => 'textarea',
			'section'     => 'conferp_institution',
			'label'       => __( 'Nome da instituição', 'conferp' ),
			'description' => __(
				'Nome exibido junto à identidade institucional do site.',
				'conferp'
			),
		)
	);


	/**
	 * ======================================================
	 * Contact and Social Networks
	 * ======================================================
	 */

	$wp_customize->add_section(
		'conferp_social',
		array(
			'title'       => __( 'Contato e Redes Sociais', 'conferp' ),
			'description' => __(
				'Configure os dados de contato e os canais institucionais utilizados no cabeçalho e no rodapé do site. Campos vazios não serão exibidos.',
				'conferp'
			),
			'priority'    => 31,
		)
	);


	/**
	 * ======================================================
	 * Institutional Contact
	 * ======================================================
	 */


	/**
	 * Footer Contact Title.
	 */

	$wp_customize->add_setting(
		'conferp_footer_contact_title',
		array(
			'default'           => 'Contatos',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);


	$wp_customize->add_control(
		'conferp_footer_contact_title',
		array(
			'type'        => 'text',
			'section'     => 'conferp_social',
			'label'       => __( 'Título de contato no rodapé', 'conferp' ),
			'description' => __(
				'Título exibido acima dos dados institucionais de contato no rodapé.',
				'conferp'
			),
			'input_attrs' => array(
				'placeholder' => __( 'Contatos', 'conferp' ),
			),
		)
	);


	/**
	 * Institutional E-mail.
	 */

	$wp_customize->add_setting(
		'conferp_contact_email',
		array(
			'default'           => get_option( 'admin_email' ),
			'sanitize_callback' => 'sanitize_email',
			'transport'         => 'refresh',
		)
	);


	$wp_customize->add_control(
		'conferp_contact_email',
		array(
			'type'        => 'email',
			'section'     => 'conferp_social',
			'label'       => __( 'E-mail institucional', 'conferp' ),
			'description' => __(
				'E-mail principal de contato da instituição.',
				'conferp'
			),
			'input_attrs' => array(
				'placeholder' => 'contato@instituicao.org.br',
			),
		)
	);


	/**
	 * Institutional Phone.
	 */

	$wp_customize->add_setting(
		'conferp_contact_phone',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);


	$wp_customize->add_control(
		'conferp_contact_phone',
		array(
			'type'        => 'text',
			'section'     => 'conferp_social',
			'label'       => __( 'Telefone institucional', 'conferp' ),
			'description' => __(
				'Telefone principal de contato da instituição.',
				'conferp'
			),
			'input_attrs' => array(
				'placeholder' => '+55 61 99999-9999',
			),
		)
	);


	/**
	 * Institutional Address.
	 */

	$wp_customize->add_setting(
		'conferp_contact_address',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);


	$wp_customize->add_control(
		'conferp_contact_address',
		array(
			'type'        => 'textarea',
			'section'     => 'conferp_social',
			'label'       => __( 'Endereço institucional', 'conferp' ),
			'description' => __(
				'Endereço físico exibido no rodapé institucional. As quebras de linha serão preservadas.',
				'conferp'
			),
			'input_attrs' => array(
				'placeholder' => "SCS Quadra 00 - Edifício\nBrasília - DF\nCEP 00000-000",
				'rows'        => 4,
			),
		)
	);


	/**
	 * ======================================================
	 * Social Networks
	 * ======================================================
	 */


	/**
	 * Footer Social Title.
	 */

	$wp_customize->add_setting(
		'conferp_footer_social_title',
		array(
			'default'           => 'Veja também',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);


	$wp_customize->add_control(
		'conferp_footer_social_title',
		array(
			'type'        => 'text',
			'section'     => 'conferp_social',
			'label'       => __( 'Título das redes sociais no rodapé', 'conferp' ),
			'description' => __(
				'Título exibido acima dos ícones das redes sociais no rodapé.',
				'conferp'
			),
			'input_attrs' => array(
				'placeholder' => __( 'Veja também', 'conferp' ),
			),
		)
	);


	/**
	 * Facebook.
	 */

	$wp_customize->add_setting(
		'conferp_social_facebook',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);


	$wp_customize->add_control(
		'conferp_social_facebook',
		array(
			'type'        => 'url',
			'section'     => 'conferp_social',
			'label'       => __( 'Facebook', 'conferp' ),
			'description' => __(
				'URL completa do perfil institucional no Facebook.',
				'conferp'
			),
			'input_attrs' => array(
				'placeholder' => 'https://www.facebook.com/...',
			),
		)
	);


	/**
	 * LinkedIn.
	 */

	$wp_customize->add_setting(
		'conferp_social_linkedin',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);


	$wp_customize->add_control(
		'conferp_social_linkedin',
		array(
			'type'        => 'url',
			'section'     => 'conferp_social',
			'label'       => __( 'LinkedIn', 'conferp' ),
			'description' => __(
				'URL completa do perfil institucional no LinkedIn.',
				'conferp'
			),
			'input_attrs' => array(
				'placeholder' => 'https://www.linkedin.com/...',
			),
		)
	);


	/**
	 * Instagram.
	 */

	$wp_customize->add_setting(
		'conferp_social_instagram',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);


	$wp_customize->add_control(
		'conferp_social_instagram',
		array(
			'type'        => 'url',
			'section'     => 'conferp_social',
			'label'       => __( 'Instagram', 'conferp' ),
			'description' => __(
				'URL completa do perfil institucional no Instagram.',
				'conferp'
			),
			'input_attrs' => array(
				'placeholder' => 'https://www.instagram.com/...',
			),
		)
	);


	/**
	 * YouTube.
	 */

	$wp_customize->add_setting(
		'conferp_social_youtube',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);


	$wp_customize->add_control(
		'conferp_social_youtube',
		array(
			'type'        => 'url',
			'section'     => 'conferp_social',
			'label'       => __( 'YouTube', 'conferp' ),
			'description' => __(
				'URL completa do canal institucional no YouTube.',
				'conferp'
			),
			'input_attrs' => array(
				'placeholder' => 'https://www.youtube.com/...',
			),
		)
	);

}

add_action( 'customize_register', 'conferp_customize_register' );