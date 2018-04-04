<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content-vw">
 *
 * @package VW Hospital Lite
 */

?><!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width">
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="toggle"><a class="toggleMenu" href="#"><?php esc_html_e('Menu','vw-hospital-lite'); ?></a></div>
  <div class="top-bar">
      <div class="container">
        <div class="col-md-3 col-xs-12 col-sm-4">
          <div class="top-left">
            <?php if(get_theme_mod( 'vw_hospital_lite_youtube_url','' ) != '') { ?>
              <a href="<?php echo esc_url( get_theme_mod( 'vw_hospital_lite_youtube_url','' ) ); ?>"><span class="dashicons dashicons-video-alt3" title="<?php echo esc_attr(get_theme_mod('vw_hospital_lite_youtube_url','')); ?>"></span></a>
            <?php } ?>
            <?php if(get_theme_mod( 'vw_hospital_lite_facebook_url','' ) != '') { ?>
              <a href="<?php echo esc_url( get_theme_mod( 'vw_hospital_lite_facebook_url','' ) ); ?>"><span class="dashicons dashicons-facebook" title="<?php echo esc_attr(get_theme_mod('vw_hospital_lite_facebook_url','')); ?>"></span></a>
            <?php } ?>
            <?php if(get_theme_mod( 'vw_hospital_lite_twitter_url','' ) != '') { ?>
              <a href="<?php echo esc_url( get_theme_mod( 'vw_hospital_lite_twitter_url','' ) ); ?>"><span class="dashicons dashicons-twitter" title="<?php echo esc_attr(get_theme_mod('vw_hospital_lite_twitter_url','')); ?>"></span></a>
            <?php } ?>
            <?php if(get_theme_mod( 'vw_hospital_lite_rss_url','' ) != '') { ?>
              <a href="<?php echo esc_url( get_theme_mod( 'vw_hospital_lite_rss_url','' ) ); ?>"><span class="dashicons dashicons-rss" title="<?php echo esc_attr(get_theme_mod('vw_hospital_lite_rss_url','')); ?>"></span></a>
            <?php } ?>
          </div>
        </div>
         <div class="clear"></div>
      </div>
  </div><?php /* top-bar */ ?>
  <div class="header">
    <div class="container">
      <div class="row">
        <div class="logo col-md-6">
          <?php vw_hospital_lite_the_custom_logo(); ?>
          <?php if ( is_front_page() && is_home() ) : ?>
            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
          <?php else : ?>
            <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
          <?php endif;

          $description = get_bloginfo( 'description', 'display' );
          if ( $description || is_customize_preview() ) : ?>
            <p class="site-description"><?php echo esc_html( $description ); ?></p>
          <?php endif; ?>
        </div>
        <div class="col-md-6">
          <div class="contact-call-Email">
            <?php if(esc_html( get_theme_mod('vw_hospital_lite_contact_call','') ) != '') { ?>
              <div class="col-md-6 col-sm-4">
                <p class="calling"><span class="dashicons dashicons-phone"></span><?php echo esc_html(get_theme_mod('vw_hospital_lite_contact_call','')); ?>
              </div>
            <?php } if(esc_html( get_theme_mod('vw_hospital_lite_contact_email','') ) != '') { ?>
              <div class="col-md-6 col-sm-4">
                <p class="email"><span class="dashicons dashicons-email-alt"></span><?php echo esc_html(antispambot(get_theme_mod('vw_hospital_lite_contact_email',''))); ?></p>
              </div>
            <?php } ?>
            </div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div><?php /* header */ ?>

<div class="nav menubar">
  <div class="container">        
        <?php wp_nav_menu( array('theme_location'  => 'primary') ); ?>
		<?php /* nav */ ?>
  </div>
</div>