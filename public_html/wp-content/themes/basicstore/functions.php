<?php
/**
 * basicstore functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package basicstore
 */

if ( ! function_exists( 'basic_store_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @version 1.3.9
 * @since   1.0.0
 */
function basic_store_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on basic_store, use a find and replace
	 * to change 'basic_store' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'basicstore', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary-left' => esc_html__( 'Primary Left', 'basicstore' ),
		'primary-right' => esc_html__( 'Primary Right', 'basicstore' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-list',
		'gallery',
		'caption',
	) );

	// Add theme support for custom logo
	add_theme_support( 'custom-logo', array(
		'height'      => 50,
		'width'       => 200,
		'header-text' => array( 'site-title', 'site-description' ),
	) );

	// Add theme support for custom background
	add_theme_support( 'custom-background', array(
		'default-image' => '',
		'default-preset' => 'default',
		'default-position-x' => 'left',
		'default-position-y' => 'top',
		'default-size' => 'auto',
		'default-repeat' => 'repeat',
		'default-attachment' => 'scroll',
		'default-color' => '#ffffff',
		'wp-head-callback' => '_custom_background_cb',
		'admin-head-callback' => '',
		'admin-preview-callback' => ''
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Declare WooCommerce support
	add_theme_support( 'woocommerce' );

	// Add theme support for custom header
	add_theme_support( 'custom-header', array(
		'default-image' => '',
		'random-default' => false,
		'flex-height' => false,
		'flex-width' => false,
		'default-text-color' => '#333333',
		'header-text' => true,
		'uploads' => true,
		'wp-head-callback' => '',
		'admin-head-callback' => '',
		'admin-preview-callback' => '',
		'video' => false,
		'video-active-callback' => 'is_front_page',
	) );

	// WooCommerce gallery support
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

}
endif;
add_action( 'after_setup_theme', 'basic_store_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function basic_store_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'basic_store_content_width', 1140 );
}
add_action( 'after_setup_theme', 'basic_store_content_width', 0 );

/**
 * Registers an editor stylesheet for the theme.
 *
 * @version 1.3.9
 * @since   1.3.9
 */
function basic_store_add_editor_styles() {
	add_editor_style( 'custom-editor-style.css' );
}

add_action( 'admin_init', 'basic_store_add_editor_styles' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function basic_store_widgets_init() {

	register_sidebar( array(
		'name'          => esc_html__( 'Home Widget', 'basicstore' ),
		'id'            => 'sidebar-frontpage',
		'description'   => esc_html__( 'Add widgets for HomePage', 'basicstore' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Blog Sidebar', 'basicstore' ),
		'id'            => 'sidebar-site',
		'description'   => esc_html__( 'Add widgets here to display on site pages', 'basicstore' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Shop Sidebar', 'basicstore' ),
		'id'            => 'sidebar-shop',
		'description'   => esc_html__( 'Add widgets here to display on shop pages', 'basicstore' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer', 'basicstore' ),
		'id'            => 'sidebar-footer',
		'description'   => __( 'Add widgets here.', 'basicstore' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'basic_store_widgets_init' );


// Add Bootstrap column class to widgets
function basic_store_widgets_count($params) {

  $sidebar_id = $params[0]['id'];

	/* Footer widgets */
	if ( $sidebar_id == 'sidebar-footer' ) {
    $total_widgets = wp_get_sidebars_widgets();
    $sidebar_widgets = count($total_widgets[$sidebar_id]);
    $params[0]['before_widget'] = str_replace('class="', 'class="col-md-' . floor(12 / $sidebar_widgets) . ' ', $params[0]['before_widget']);
  }
  return $params;
}
add_filter('dynamic_sidebar_params','basic_store_widgets_count');


/**
 * Enqueue scripts and styles.
 *
 * @version 1.4.0
 * @since   1.0.0
 */
function basic_store_scripts() {
	wp_enqueue_style( 'woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css' );
	wp_enqueue_style( 'woocommerce-layout', get_template_directory_uri() . '/assets/css/woocommerce-layout.css' );
	wp_enqueue_style( 'style', get_template_directory_uri() . '/assets/css/basicstore.css' );
	wp_enqueue_style( 'basicstore-wp-style', get_stylesheet_directory_uri() . '/assets/css/wp.css' );
	wp_enqueue_style( 'basicstore-main-style', get_stylesheet_directory_uri() . '/assets/css/theme.css' );
	wp_enqueue_script( 'basicstore-bootstrap-script', get_template_directory_uri() . '/assets/js/bootstrap/bootstrap.min.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'basicstore-bootstrap-tabcollapse', get_template_directory_uri() . '/assets/js/bootstrap-tabcollapse.js', array(), '', true );
	wp_enqueue_script( 'basicstore-script', get_template_directory_uri() . '/assets/js/theme.js', array(), '', true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'basic_store_scripts' );


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Register Custom Navigation Walker
 */
require get_template_directory() . '/inc/wp-bootstrap-navwalker.php';

/**
 * Register Custom Bootstrap Pagination
 */
require get_template_directory() . '/inc/wp-bootstrap-pagination.php';

/**
 * Load WooCommerce functions
 */
require get_template_directory() . '/inc/woocommerce.php';
