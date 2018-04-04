<?php
/**
 * VW Hospital Lite Theme Customizer
 *
 * @package VW Hospital Lite
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function vw_hospital_lite_customize_register( $wp_customize ) {

	//add home page setting pannel
	$wp_customize->add_panel( 'vw_hospital_lite_panel_id', array(
	    'priority' => 10,
	    'capability' => 'edit_theme_options',
	    'theme_supports' => '',
	    'title' => __( 'VW Settings', 'vw-hospital-lite' ),
	    'description' => __( 'Description of what this panel does.', 'vw-hospital-lite' ),
	) );

	//Layouts
	$wp_customize->add_section( 'vw_hospital_lite_left_right', array(
    	'title'      => __( 'Theme Layout Settings', 'vw-hospital-lite' ),
		'priority'   => 30,
		'panel' => 'vw_hospital_lite_panel_id'
	) );

	// Add Settings and Controls for Layout
	$wp_customize->add_setting('vw_hospital_lite_theme_options',array(
	        'default' => '',
	        'sanitize_callback' => 'vw_hospital_lite_sanitize_choices'
	    )
    );

	$wp_customize->add_control('vw_hospital_lite_theme_options',
	    array(
	        'type' => 'radio',
	        'label' => 'Do you want this section',
	        'section' => 'vw_hospital_lite_left_right',
	        'choices' => array(
	            'Left Sidebar' => __('Left Sidebar','vw-hospital-lite'),
	            'Right Sidebar' => __('Right Sidebar','vw-hospital-lite'),
	            'One Column' => __('One Column','vw-hospital-lite'),
	            'Three Columns' => __('Three Columns','vw-hospital-lite'),
	            'Four Columns' => __('Four Columns','vw-hospital-lite'),
	            'Grid Layout' => __('Grid Layout','vw-hospital-lite')
	        ),
	    )
    );


	//home page slider
	$wp_customize->add_section( 'vw_hospital_lite_slidersettings' , array(
    	'title'      => __( 'Slider Settings', 'vw-hospital-lite' ),
		'priority'   => 30,
		'panel' => 'vw_hospital_lite_panel_id'
	) );

	for ( $count = 1; $count <= 4; $count++ ) {

		// Add color scheme setting and control.
		$wp_customize->add_setting( 'vw_hospital_lite_slidersettings-page-' . $count, array(
				'default'           => '',
				'sanitize_callback' => 'absint'
		) );

		$wp_customize->add_control( 'vw_hospital_lite_slidersettings-page-' . $count, array(
			'label'    => __( 'Select Slide Image Page', 'vw-hospital-lite' ),
			'section'  => 'vw_hospital_lite_slidersettings',
			'type'     => 'dropdown-pages'
		) );

	}

	//OUR services
	$wp_customize->add_section('vw_hospital_lite_our_services',array(
		'title'	=> __('Our Services','vw-hospital-lite'),
		'description'=> __('This section will appear below the slider.','vw-hospital-lite'),
		'panel' => 'vw_hospital_lite_panel_id',
	));	

	for ( $count = 0; $count <= 3; $count++ ) {

		$wp_customize->add_setting( 'vw_hospital_lite_servicesettings-page-' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'absint'
		));
		$wp_customize->add_control( 'vw_hospital_lite_servicesettings-page-' . $count, array(
			'label'    => __( 'Select Service Page', 'vw-hospital-lite' ),
			'section'  => 'vw_hospital_lite_our_services',
			'type'     => 'dropdown-pages'
		));
	}


	//footer setting pannel
	$wp_customize->add_section('vw_hospital_lite_footer-section',array(
        'title' => __('Contact Us','vw-hospital-lite'),
        'description'   => __('This section is used to display address in footer and contact page template','vw-hospital-lite'),
        'panel' => 'vw_hospital_lite_panel_id',
    ));

    $wp_customize->add_setting('vw_hospital_lite_contact_call',array(
        'default'   => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('vw_hospital_lite_contact_call',array(
            'label' => __('Contact No ','vw-hospital-lite'),
            'section' => 'vw_hospital_lite_footer-section',
            'setting'   => 'vw_hospital_lite_contact_call',
            'type'  => 'text'
        )
    );

    $wp_customize->add_setting('vw_hospital_lite_contact_email',array(
        'default'   => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('vw_hospital_lite_contact_email',array(
            'label' => __('Email Address','vw-hospital-lite'),
            'section' => 'vw_hospital_lite_footer-section',
            'setting'   => 'vw_hospital_lite_contact_email',
            'type'  => 'text'
        )
    );

    //social pannel
	$wp_customize->add_section('vw_hospital_lite_social_section',array(
        'title' => __('Social Links','vw-hospital-lite'),
        'description'   => '',
        'panel' => 'vw_hospital_lite_panel_id',
    ));
	
	$wp_customize->add_setting('vw_hospital_lite_youtube_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field',
	));
	
	$wp_customize->add_control('vw_hospital_lite_youtube_url',array(
		'label'	=> __('Youtube URL','vw-hospital-lite'),
		'section'	=> 'vw_hospital_lite_social_section',
		'type'		=> 'text'
	));
	
	$wp_customize->add_setting('vw_hospital_lite_facebook_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field',
	));
	
	$wp_customize->add_control('vw_hospital_lite_facebook_url',array(
		'label'	=> __('Facebook URL','vw-hospital-lite'),
		'section'	=> 'vw_hospital_lite_social_section',
		'type'		=> 'text'
	));
	
	$wp_customize->add_setting('vw_hospital_lite_twitter_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field',
	));
	
	$wp_customize->add_control('vw_hospital_lite_twitter_url',array(
		'label'	=> __('Twitter URL','vw-hospital-lite'),
		'section'	=> 'vw_hospital_lite_social_section',
		'type'		=> 'text'
	));
	
	$wp_customize->add_setting('vw_hospital_lite_rss_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field',
	));
	
	$wp_customize->add_control('vw_hospital_lite_rss_url',array(
		'label'	=> __('RSS URL','vw-hospital-lite'),
		'section'	=> 'vw_hospital_lite_social_section',
		'type'		=> 'text'
	));

	$wp_customize->add_section('vw_hospital_lite_footer_section',array(
		'title'	=> __('Copyright','vw-hospital-lite'),
		'description'	=> __('Add some text for footer like copyright etc.','vw-hospital-lite'),
		'priority'	=> null,
		'panel' => 'vw_hospital_lite_panel_id',
	));
	
	$wp_customize->add_setting('vw_hospital_lite_footer_copy',array(
		'default'	=> '',
		'sanitize_callback'	=> 'wp_kses_post',
	));
	
	$wp_customize->add_control('vw_hospital_lite_footer_copy',array(
		'label'	=> __('Copyright Text','vw-hospital-lite'),
		'section'	=> 'vw_hospital_lite_footer_section',
		'type'		=> 'textarea'
	));		
	/** home page setions end here**/
	
}
add_action( 'customize_register', 'vw_hospital_lite_customize_register' );

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class vw_hospital_lite_customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function sections( $manager ) {

		// Load custom sections.
		load_template( trailingslashit( get_template_directory() ) . '/inc/section-pro.php' );

		// Register custom section types.
		$manager->register_section_type( 'vw_hospital_lite_customize_Section_Pro' );

		// Register sections.
		$manager->add_section(
			new vw_hospital_lite_customize_Section_Pro(
				$manager,
				'example_1',
				array(
					'title'    => esc_html__( 'Upgrade to Pro', 'vw-hospital-lite' ),
					'pro_text' => esc_html__( 'Go Pro',         'vw-hospital-lite' ),
					'pro_url'  => 'http://www.vwthemes.net/vw-hospital-theme/'
				)
			)
		);
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'vw-hospital-lite-customize-controls', trailingslashit( get_template_directory_uri() ) . '/js/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'vw-hospital-lite-customize-controls', trailingslashit( get_template_directory_uri() ) . '/css/customize-controls.css' );
	}
}

// Doing this customizer thang!
vw_hospital_lite_customize::get_instance();