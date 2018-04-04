<?php
/**
 * Customizer Options.
 *
 * @package basicstore
 */

	/*
		Add Theme Panel
	*/
	$wp_customize->add_panel( 'theme_panel',
		array(
			'title'      => esc_html__( 'Theme Option', 'basicstore' ),
			'priority'   => 90,
		)
	);

	/*
		 Front Page Options
	*/
	$wp_customize->add_section( 'front_page_option',
		array(
			'title'      => esc_html__( 'Front Page Options', 'basicstore' ),
			'priority'   => 100,
			'panel'      => 'theme_panel',
		)
	);

	/*
		Latest Product Option
	*/
	$wp_customize->add_setting('latest_product_disable',array(
			'sanitize_callback' => 'basicstore_sanitize_checkbox',
			'default' => '1',
		)
	);

	$wp_customize->add_control(new WP_Customize_Control($wp_customize,'latest_product_disable',array(
			'label' => __('Show or Hide Latest Products','basicstore'),
			'section' => 'front_page_option',
			'settings' => 'latest_product_disable',
			'type'=> 'checkbox',
		)
	));

	/*
		Featured Product Option
	*/
	$wp_customize->add_setting('featured_product_disable',array(
			'sanitize_callback' => 'basicstore_sanitize_checkbox',
			'default' => '1',
		)
	);

	$wp_customize->add_control(new WP_Customize_Control($wp_customize,'featured_product_disable',array(
			'label' => __('Show or Hide Featured Products','basicstore'),
			'section' => 'front_page_option',
			'settings' => 'featured_product_disable',
			'type'=> 'checkbox',
		)
	));

	/*
		Top Rated Product Option
	*/
	$wp_customize->add_setting('top_rated_product_disable',array(
			'sanitize_callback' => 'basicstore_sanitize_checkbox',
			'default' => '1',
		)
	);

	$wp_customize->add_control(new WP_Customize_Control($wp_customize,'top_rated_product_disable',array(
			'label' => __('Show or Hide Top Rated Products','basicstore'),
			'section' => 'front_page_option',
			'settings' => 'top_rated_product_disable',
			'type'=> 'checkbox',
		)
	));

	/*
		Product Category Option
	*/
	$wp_customize->add_setting('product_category_disable',array(
			'sanitize_callback' => 'basicstore_sanitize_checkbox',
			'default' => '1',
		)
	);

	$wp_customize->add_control(new WP_Customize_Control($wp_customize,'product_category_disable',array(
			'label' => __('Show or Hide Product Categories','basicstore'),
			'section' => 'front_page_option',
			'settings' => 'product_category_disable',
			'type'=> 'checkbox',
		)
	));
?>