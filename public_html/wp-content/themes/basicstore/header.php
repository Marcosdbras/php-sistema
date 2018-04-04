<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @version 1.4.0
 * @since   1.0.0
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package basicstore
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<a class="skip-link screen-reader-text" href="#site-content"><?php esc_html_e( 'Skip to content', 'basicstore' ); ?></a>

	<header id="site-header">

		<nav class="navbar navbar-default navbar-fixed-top">

			<div class="container">

					<div class="navbar-header">

	          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	            <span class="sr-only"><?php _e( 'Toggle navigation', 'basicstore' ); ?></span>
	            <span class="icon-bar <?php echo basic_store_navbar_toggle_check_cart_items(); ?>"></span>
	            <span class="icon-bar <?php echo basic_store_navbar_toggle_check_cart_items(); ?>"></span>
	            <span class="icon-bar <?php echo basic_store_navbar_toggle_check_cart_items(); ?>"></span>
	          </button>

						<?php if (has_custom_logo()) : ?>
							<a class="navbar-brand nav-brand-img" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			          <img src='<?php echo esc_url( basic_store_display_site_logo() ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>
							</a>
						<?php elseif (get_theme_mod('header_text')) : ?>
	          	<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
						<?php else: ?>
						<?php endif; ?>

	        </div><!-- #navbar-header -->

				<div id="navbar" class="collapse navbar-collapse">

					<?php
						wp_nav_menu( array(
							'theme_location'    => 'primary-left',
							'depth'             => 2,
							'container'         => false,
							'menu_class'        => 'nav navbar-nav',
							'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
							'walker'            => new WP_Bootstrap_Navwalker())
						);
		      ?>

					<?php if (class_exists( 'WooCommerce' )) : ?>
						<div class="navbar-form navbar-right">
							<?php get_product_search_form(); ?>
						</div>
					<?php else: ?>
						<div class="navbar-form navbar-right">
							<?php get_search_form(); ?>
						</div>
					<?php endif; ?>

					<?php
						wp_nav_menu( array(
							'theme_location'    => 'primary-right',
							'depth'             => 2,
							'container'         => false,
							'menu_class'        => 'nav navbar-nav navbar-right',
							'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
							'walker'            => new WP_Bootstrap_Navwalker())
						);
		      ?>

				</div><!-- #navbar -->

			</div><!-- .container -->

		</nav><!-- .navbar -->

	</header><!-- #site-header -->

	<?php
    // Header image
	$header_image = get_header_image();
	if ( $header_image ) {
		$header_image_style = 'background-image:url(' . esc_url( $header_image ) . ');';
	} else {
		$header_image_style = '';
	}	
	?>

    <?php
    // Header text color
    if ( display_header_text() ) {
	    $style = ' style="color:#' . get_header_textcolor() . ';"';
    } else {
	    $style = ' style="display:none;"';
    }
    ?>

	<section id="site-content" class="site-content">
	<?php if ( is_home() || is_front_page() ) : ?>
		<div id="site-jumbotron" class="jumbotron text-center" style="<?php echo $header_image_style; ?>">

			<div class="container">

				<h1 <?php echo $style; ?>><?php bloginfo( 'name' ); ?></h1>
				<p <?php echo $style; ?>><?php bloginfo('description'); ?></p>

			</div><!-- .container -->

		</div><!-- #site-jumbotron -->
	<?php endif; ?>
	<div class="container">

		<div class="row">
