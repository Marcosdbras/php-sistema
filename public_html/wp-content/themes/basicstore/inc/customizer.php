<?php
/**
 * basic_store Theme Customizer
 *
 * @version 1.3.7
 * @since   1.0.0
 * @package basic_store
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @version 1.3.7
 * @since   1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function basic_store_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	//$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	//$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	// Sanitization.
	require_once trailingslashit( get_template_directory() ) . '/inc/sanitize.php';

	// Load options.
	require_once trailingslashit( get_template_directory() ) . '/inc/options.php';

	// Load Upgrade to Pro control.
	require_once trailingslashit( get_template_directory() ) . '/inc/upgrade-to-pro/control.php';

	// Register custom section types.
	$wp_customize->register_section_type( 'BasicStore_Customize_Section_Upsell' );

	// Register sections.
	$wp_customize->add_section(
		new BasicStore_Customize_Section_Upsell(
			$wp_customize,
			'theme_upsell',
			array(
				'title'    => esc_html__( 'BasicStore Plus', 'basicstore' ),
				'pro_text' => esc_html__( 'Upgrade to PRO', 'basicstore' ),
				'pro_url'  => 'https://wpcodefactory.com/item/basicstore-theme-for-woocommerce/',
				'priority' => 1,
			)
		)
	);
}
add_action( 'customize_register', 'basic_store_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function basic_store_customize_preview_js() {
	wp_enqueue_script( 'basic_store_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'basic_store_customize_preview_js' );

/**
 * Enqueue style for custom customize control.
 */
function basicstore_custom_customize_enqueue() {
	wp_enqueue_script( 'basicstore-customize-controls', get_template_directory_uri() . '/inc/upgrade-to-pro/customize-control.js', array( 'customize-controls' ) );

	wp_enqueue_style( 'basicstore-customize-controls', get_template_directory_uri() . '/inc/upgrade-to-pro/customize-control.css' );
}
add_action( 'customize_controls_enqueue_scripts', 'basicstore_custom_customize_enqueue' );
