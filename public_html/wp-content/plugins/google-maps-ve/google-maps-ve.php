<?php

/**
 * @package Google Maps Veebiekspert
 * @version 1.0.2
 */
/*
Plugin Name: Google Maps Veebiekspert
Plugin URI: http://www.googlemapswordpress.com
Description: Create your google maps
Author: Priit Elbe
Version: 1.0.2
Author URI: http://www.veebiekspert.ee
*/


define('GOOGLE_MAPS_VE_TD', 'google-maps-ve-td');

error_reporting(0);
ini_set('display_errors', 0);

class Google_Maps_Ve
{
    
    protected $_app;
    protected $_actions;
    protected $_views;
    
    public function __construct()
    {
        $path = plugin_dir_path( __FILE__ );
        $url = plugin_dir_url( __FILE__ );
        define('GOOGLE_MAPS_VE_PATH', $path);
        define('GOOGLE_MAPS_VE_URL', $url);
        define('GOOGLE_MAPS_VE_DEFAULT_MARKER', $url . 'media/img/default-marker.png');
        
        require_once GOOGLE_MAPS_VE_PATH . 'includes/app.php';
        require_once GOOGLE_MAPS_VE_PATH . 'includes/actions.php';
        require_once GOOGLE_MAPS_VE_PATH . 'includes/defs.php';
        require_once GOOGLE_MAPS_VE_PATH . 'includes/view.php';
        $this->_actions = new Google_Maps_Ve_Actions($this);
        $this->_views = new Google_Maps_Ve_View($this);
        $this->_app = new Google_Maps_Ve_App($this);
        add_action('admin_enqueue_scripts', array($this, 'load_css'));
        add_action('wp_enqueue_scripts', array($this, 'load_front_scripts'));
    }
    
    public function load_languages()
    {
        
    }
    
    public function getApp()
    {
        return $this->_app;
    }
    
    public function getAction()
    {
        return $this->_actions;
    }
    
    public function getView()
    {
        return $this->_views;
    }
    
    public function load_css()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_style( 'wp-color-picker');
        wp_enqueue_script( 'wp-color-picker');
        wp_enqueue_style('ve-maps-css', GOOGLE_MAPS_VE_URL . '/media/css/maps.css');
        wp_enqueue_script('ve-maps-geolocation', GOOGLE_MAPS_VE_URL . '/media/js/geolocation.js');
        wp_enqueue_media();
    }
    
    public function load_front_scripts()
    {
        wp_enqueue_style('ve-maps-css', GOOGLE_MAPS_VE_URL . '/media/css/maps.css');
        wp_enqueue_script('ve-maps-geolocation', GOOGLE_MAPS_VE_URL . '/media/js/geolocation.js');
    }
}

load_plugin_textdomain(GOOGLE_MAPS_VE_TD, false, basename(dirname(__FILE__)). '/languages/');
global $google_maps_ve;
if (class_exists('Google_Maps_Ve_Pro')) {
    $google_maps_ve = new Google_Maps_Ve_Pro();
}
else {
    $google_maps_ve = new Google_Maps_Ve();
}