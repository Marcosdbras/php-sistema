<?php
/**
 * Plugin Name: WooCommerce MercadoPago
 * Plugin URI: https://github.com/mercadopago/cart-woocommerce
 * Description: This is the <strong>oficial</strong> module of Mercado Pago for WooCommerce plugin. This module enables WooCommerce to use Mercado Pago as a payment Gateway for purchases made in your e-commerce store.
 * Version: 3.0.6
 * Author: Mercado Pago
 * Author URI: https://www.mercadopago.com.br/developers/
 * Text Domain: woocommerce-mercadopago
 * Domain Path: /i18n/languages/
 * WC requires at least: 3.0.0
 * WC tested up to: 3.2.0
 *
 * @package MercadoPago
 * @category Core
 * @author Mercado Pago
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load plugin text domain.
 *
 * Need to require here before test for PHP version.
 *
 * @since 3.0.1
 */
function wc_mercado_pago_load_plugin_textdomain() {
	load_plugin_textdomain(	'woocommerce-mercadopago', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages/' );
}

add_action( 'init', 'wc_mercado_pago_load_plugin_textdomain' );

/**
 * Notice about unsupported PHP version.
 *
 * @since 3.0.1
 */
function wc_mercado_pago_unsupported_php_version_notice() {
	echo '<div class="error"><p>' . esc_html__( 'WooCommerce Mercado Pago requires PHP version 5.6 or later. Please update your PHP version.', 'woocommerce-mercadopago' ) . '</p></div>';
}

// Check for PHP version and throw notice.
if ( version_compare( PHP_VERSION, '5.6', '<=' ) ) {
	add_action( 'admin_notices', 'wc_mercado_pago_unsupported_php_version_notice' );
	return;
}

// Load Mercado Pago SDK
require_once dirname( __FILE__ ) . '/includes/sdk/lib/mercadopago.php';

// Load module class if it wasn't loaded yet.
if ( ! class_exists( 'WC_Woo_Mercado_Pago_Module' ) ) :

	/**
	 * Summary: WooCommerce MercadoPago Module main class.
	 * Description: Used as a kind of manager to enable/disable each Mercado Pago gateway.
	 * Available Public Static Functions:
	 * - validate_credentials_v0()
	 * - validate_credentials_v1()
	 * - woocommerce_instance()
	 * - get_conversion_rate( $used_currency )
	 * - get_common_settings()
	 * - get_categories()
	 * - get_site_data( $is_v1 = false )
	 * - workaround_ampersand_bug( $link )
	 * - get_templates_path()
	 * - get_module_version()
	 * - get_client_id( $at )
	 * - is_subscription( $items )
	 * - is_supported_currency( $site_id )
	 * - build_currency_conversion_err_msg( $currency )
	 * - build_currency_not_converted_msg( $currency, $country_name )
	 * - build_currency_converted_msg( $currency )
	 * - get_country_name( $site_id )
	 * - build_log_path_string( $gateway_id, $gateway_name )
	 * - get_wc_status_for_mp_status( $mp_status )
	 * - get_map( $selector_id )
	 * - generate_refund_cancel_subscription( $domain, $success_msg, $fail_msg, $options, $str1, $str2, $str3, $str4 )
	 * - is_product_dimensions_valid( $all_product_data )
	 * @since 3.0.0
	 */
	class WC_Woo_Mercado_Pago_Module {

		// ============================================================

		// General constants.
		const VERSION = '3.0.6';
		const MIN_PHP = 5.6;

		// Arrays to hold configurations for LatAm environment.
		// As this array contains runtime data, we can't set it as a class constant.
		public static $categories = array();
		public static $country_configs = array();

		// ============================================================

		// A singleton design pattern to access this class in global scope.
		protected static $instance = null;
		public static function init_mercado_pago_class() {
			if ( self::$instance === null ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		// Class constructor.
		private function __construct() {

			WC_Woo_Mercado_Pago_Module::$categories = WC_Woo_Mercado_Pago_Module::get_categories();
			WC_Woo_Mercado_Pago_Module::$country_configs = array(
				'MCO' => array(
					'site_id'                => 'MCO',
					'sponsor_id'             => 208687643,
					'checkout_banner'        => plugins_url( 'assets/images/MCO/standard_mco.jpg', __FILE__ ),
					'checkout_banner_custom' => plugins_url( 'assets/images/MCO/credit_card.png', __FILE__ ),
					'currency'               => 'COP'
				),
				'MLA' => array(
					'site_id'                => 'MLA',
					'sponsor_id'             => 208682286,
					'checkout_banner'        => plugins_url( 'assets/images/MLA/standard_mla.jpg', __FILE__ ),
					'checkout_banner_custom' => plugins_url( 'assets/images/MLA/credit_card.png', __FILE__ ),
					'currency'               => 'ARS'
				),
				'MLB' => array(
					'site_id'                => 'MLB',
					'sponsor_id'             => 208686191,
					'checkout_banner'        => plugins_url( 'assets/images/MLB/standard_mlb.jpg', __FILE__ ),
					'checkout_banner_custom' => plugins_url( 'assets/images/MLB/credit_card.png', __FILE__ ),
					'currency'               => 'BRL'
				),
				'MLC' => array(
					'site_id'                => 'MLC',
					'sponsor_id'             => 208690789,
					'checkout_banner'        => plugins_url( 'assets/images/MLC/standard_mlc.gif', __FILE__ ),
					'checkout_banner_custom' => plugins_url( 'assets/images/MLC/credit_card.png', __FILE__ ),
					'currency'               => 'CLP'
				),
				'MLM' => array(
					'site_id'                => 'MLM',
					'sponsor_id'             => 208692380,
					'checkout_banner'        => plugins_url( 'assets/images/MLM/standard_mlm.jpg', __FILE__ ),
					'checkout_banner_custom' => plugins_url( 'assets/images/MLM/credit_card.png', __FILE__ ),
					'currency'               => 'MXN'
				),
				'MLU' => array(
					'site_id'                => 'MLU',
					'sponsor_id'             => 243692679,
					'checkout_banner'        => plugins_url( 'assets/images/MLU/standard_mlu.png', __FILE__ ),
					'checkout_banner_custom' => plugins_url( 'assets/images/MLU/credit_card.png', __FILE__ ),
					'currency'               => 'UYU'
				),
				'MLV' => array(
					'site_id'                => 'MLV',
					'sponsor_id'             => 208692735,
					'checkout_banner'        => plugins_url( 'assets/images/MLV/standard_mlv.jpg', __FILE__ ),
					'checkout_banner_custom' => plugins_url( 'assets/images/MLV/credit_card.png', __FILE__ ),
					'currency'               => 'VEF'
				),
				'MPE' => array(
					'site_id'                => 'MPE',
					'sponsor_id'             => 216998692,
					'checkout_banner'        => plugins_url( 'assets/images/MPE/standard_mpe.png', __FILE__ ),
					'checkout_banner_custom' => plugins_url( 'assets/images/MPE/credit_card.png', __FILE__ ),
					'currency'               => 'PEN'
				)
			);

			// First of all, verify if WooCommerce is already installed.
			if ( class_exists( 'WC_Payment_Gateway' ) ) {

				// Adds each Mercado Pago gateway as available payment method.
				include_once dirname( __FILE__ ) . '/includes/WC_WooMercadoPago_BasicGateway.php';
				include_once dirname( __FILE__ ) . '/includes/WC_WooMercadoPago_CustomGateway.php';
				include_once dirname( __FILE__ ) . '/includes/WC_WooMercadoPago_TicketGateway.php';
				include_once dirname( __FILE__ ) . '/includes/WC_WooMercadoPago_SubscriptionGateway.php';
				include_once dirname( __FILE__ ) . '/includes/class-wc-product-mp_recurrent.php';
				add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateway' ) );

				// Shipping.
				include_once dirname( __FILE__ ) . '/includes/shipment/abstract-wc-mercadoenvios-shipping.php';
				include_once dirname( __FILE__ ) . '/includes/shipment/class-wc-mercadoenvios-shipping-normal.php';
				include_once dirname( __FILE__ ) . '/includes/shipment/class-wc-mercadoenvios-shipping-express.php';
				include_once dirname( __FILE__ ) . '/includes/shipment/class-wc-mercadoenvios-package.php';
				add_filter( 'woocommerce_shipping_methods', array( $this, 'add_shipping' ) );
				add_filter( 'woocommerce_available_payment_gateways', array( $this, 'filter_payment_method_by_shipping' ) );

				// This adds custom links in the plugin page.
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'woomercadopago_settings_link' ) );

			} else {
				add_action( 'admin_notices', array( $this, 'notify_woocommerce_miss' ) );
			}

			if ( is_admin() ) {
				$this->admin_includes();
			}

		}

		/**
		 * Admin includes.
		 */
		private function admin_includes() {
			include_once dirname( __FILE__ ) . '/includes/admin/class-wc-mercadoenvios-admin-orders.php';
		}

		/**
		 * Summary: As well as defining your class, you need to also tell WooCommerce (WC) that
		 * it exists. Do this by filtering woocommerce_payment_gateways.
		 * Description: As well as defining your class, you need to also tell WooCommerce (WC) that
		 * it exists. Do this by filtering woocommerce_payment_gateways.
		 * @return an array containing the payment methods.
		 */
		public function add_gateway( $methods ) {
			$methods[] = 'WC_WooMercadoPago_BasicGateway';
			$methods[] = 'WC_WooMercadoPago_CustomGateway';
			$methods[] = 'WC_WooMercadoPago_TicketGateway';
			$methods[] = 'WC_WooMercadoPago_SubscriptionGateway';
			return $methods;
		}

		// Woocommerce_shipping_methods.
		public function add_shipping( $methods ) {
			$methods['woo-mercado-pago-me-normal'] = 'WC_MercadoEnvios_Shipping_Normal';
			$methods['woo-mercado-pago-me-express'] = 'WC_MercadoEnvios_Shipping_Express';
			return $methods;
		}

		// When selecting Mercado Envios as shipping method, customer can only do the payment with Mercado Pago Basic Checkout
		public function filter_payment_method_by_shipping( $methods ) {
			$session = WC()->session;
			if ( ! isset( $session ) ) {
				return $methods;
			}
			$chosen_methods = $session->get( 'chosen_shipping_methods' );
			$chosen_shipping = $chosen_methods[0];
			// Check shipping methods is a Mercado Envios.
			if ( strpos( $chosen_shipping, 'woo-mercado-pago-me-normal' ) !== false || strpos( $chosen_shipping, 'woo-mercado-pago-me-express' ) !== false ) {
				$new_array = array();
				foreach ( $methods as $payment_method => $payment_method_object ) {
					if ( $payment_method == 'woo-mercado-pago-basic' ) {
						$new_array['woo-mercado-pago-basic'] = $payment_method_object;
					}
				}
				// Return new array shipping methods (only with Mercado Pago Basic Checkout).
				return $new_array;
			}
			// Return all shipping methods.
			return $methods;
		}

		/**
		 * Summary: Places a warning error to notify user that WooCommerce is missing.
		 * Description: Places a warning error to notify user that WooCommerce is missing.
		 */
		public function notify_woocommerce_miss() {
			echo '<div class="error"><p>' .
				sprintf(
					__( 'Woo Mercado Pago Module depends on the last version of %s to execute!', 'woocommerce-mercadopago' ),
					'<a href="https://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a>'
				) .
				'</p></div>';
		}

		// Add settings link on plugin page.
		public function woomercadopago_settings_link( $links ) {
			$plugin_links = array();
			$plugin_links[] = '<a href="' . esc_url( admin_url(
				'admin.php?page=mercado-pago-settings' ) ) .
				'">' . __( 'Mercado Pago Settings', 'woocommerce-mercadopago' ) . '</a>';
			$plugin_links[] = '<a target="_blank" href="' .
				'https://wordpress.org/support/plugin/woo-mercado-pago-module/reviews/?rate=5#new-post' .
				'">' . sprintf(
					__( 'Rate Us', 'woocommerce-mercadopago' ) . ' %s',
					'&#9733;&#9733;&#9733;&#9733;&#9733;'
				) . '</a>';
			$plugin_links[] = '<br><a target="_blank" href="' .
				'https://github.com/mercadopago/cart-woocommerce#installation' .
				'">' . __( 'Tutorial', 'woocommerce-mercadopago' ) . '</a>';
			$plugin_links[] = '<a target="_blank" href="' .
				'https://wordpress.org/support/plugin/woo-mercado-pago-module#postform' .
				'">' . __( 'Report Issue', 'woocommerce-mercadopago' ) . '</a>';
			return array_merge( $plugin_links, $links );
		}

		// ============================================================

		/**
		 * Summary: Check if we have valid credentials for v0.
		 * Description: Check if we have valid credentials.
		 * @return boolean true/false depending on the validation result.
		 */
		public static function validate_credentials_v0() {
			$client_id = get_option( '_mp_client_id', '' );
			$client_secret = get_option( '_mp_client_secret', '' );
			// Pre-validate.
			$is_valid_credentials = true;
			if ( empty( $client_id ) || empty( $client_secret ) ) {
				$is_valid_credentials = false;
			}
			if ( ! is_numeric( $client_id ) ) {
				$is_valid_credentials = false;
			}
			if ( $is_valid_credentials ) {
				try {
					$mp_v0 = new MP( WC_Woo_Mercado_Pago_Module::VERSION, $client_id, $client_secret );
					$email = ( wp_get_current_user()->ID != 0 ) ? wp_get_current_user()->user_email : null;
					$mp_v0->set_email( $email );
					$locale = get_locale();
					$locale = ( strpos( $locale, '_' ) !== false && strlen( $locale ) == 5 ) ? explode( '_', $locale ) : array('','');
					$mp_v0->set_locale( $locale[1] );
					$access_token = $mp_v0->get_access_token();
					$get_request = $mp_v0->get( '/users/me?access_token=' . $access_token );
					if ( isset( $get_request['response']['site_id'] ) && ! empty( $access_token ) ) {
						update_option( '_test_user_v0', in_array( 'test_user', $get_request['response']['tags'], true ) );
						update_option( '_site_id_v0', $get_request['response']['site_id'], true );
						update_option( '_collector_id_v0', $get_request['response']['id'], true );
						// Get available payment methods.
						$payment_methods = $mp_v0->get( '/v1/payment_methods/?access_token=' . $access_token );
						$arr = array();
						$arr[] = 'n/d';
						foreach ( $payment_methods['response'] as $payment ) {
							$arr[] = $payment['id'];
						}
						update_option( '_all_payment_methods_v0', implode( ',', $arr ), true );
						// Check for auto converstion of currency.
						$currency_ratio = WC_Woo_Mercado_Pago_Module::get_conversion_rate(
							WC_Woo_Mercado_Pago_Module::$country_configs[$get_request['response']['site_id']]['currency']
						);
						if ( $currency_ratio > 0 ) {
							update_option( '_can_do_currency_conversion_v0', true, true );
						} else {
							update_option( '_can_do_currency_conversion_v0', false, true );
						}
						return true;
					}
				} catch ( MercadoPagoException $e ) {
					// TODO: should we handle an exception here?
				}
			}
			update_option( '_test_user_v0', '', true );
			update_option( '_site_id_v0', '', true );
			update_option( '_collector_id_v0', '', true );
			update_option( '_all_payment_methods_v0', array(), true );
			update_option( '_can_do_currency_conversion_v0', false, true );
			return false;
		}

		/**
		 * Summary: Check if we have valid credentials for v1.
		 * Description: Check if we have valid credentials.
		 * @return boolean true/false depending on the validation result.
		 */
		public static function validate_credentials_v1() {
			$public_key = get_option( '_mp_public_key', '' );
			$access_token = get_option( '_mp_access_token', '' );
			// Pre-validate.
			$is_valid_credentials = true;
			if ( empty( $public_key ) || empty( $access_token ) ) {
				$is_valid_credentials = false;
			}
			if ( strpos( $public_key, 'APP_USR' ) === false && strpos( $public_key, 'TEST' ) === false ) {
				$is_valid_credentials = false;
			}
			if ( strpos( $access_token, 'APP_USR' ) === false && strpos( $access_token, 'TEST' ) === false ) {
				$is_valid_credentials = false;
			}
			if ( $is_valid_credentials ) {
				try {
					$mp_v1 = new MP( WC_Woo_Mercado_Pago_Module::VERSION, $access_token );
					$email = ( wp_get_current_user()->ID != 0 ) ? wp_get_current_user()->user_email : null;
					$mp_v1->set_email( $email );
					$locale = get_locale();
					$locale = ( strpos( $locale, '_' ) !== false && strlen( $locale ) == 5 ) ? explode( '_', $locale ) : array('','');
					$mp_v1->set_locale( $locale[1] );
					$access_token = $mp_v1->get_access_token();
					$get_request = $mp_v1->get( '/users/me?access_token=' . $access_token );
					if ( isset( $get_request['response']['site_id'] ) && ! empty( $public_key ) ) {
						update_option( '_test_user_v1', in_array( 'test_user', $get_request['response']['tags'] ), true );
						update_option( '_site_id_v1', $get_request['response']['site_id'], true );
						update_option( '_collector_id_v1', $get_request['response']['id'], true );
						// Get available payment methods.
						$payments = $mp_v1->get( '/v1/payment_methods/?access_token=' . $access_token );
						$payment_methods_ticket = array();
						foreach ( $payments['response'] as $payment ) {
							if ( isset( $payment['payment_type_id'] ) ) {
								if ( $payment['payment_type_id'] != 'account_money' &&
									$payment['payment_type_id'] != 'credit_card' &&
									$payment['payment_type_id'] != 'debit_card' &&
									$payment['payment_type_id'] != 'prepaid_card' ) {
									$obj = new stdClass();
									$obj->id = $payment['id'];
									$obj->name = $payment['name'];
									$obj->secure_thumbnail = $payment['secure_thumbnail'];
									array_push( $payment_methods_ticket, $obj );
								}
							}
						}
						update_option( '_all_payment_methods_ticket', json_encode( $payment_methods_ticket ), true );
						// Check for auto converstion of currency.
						$currency_ratio = WC_Woo_Mercado_Pago_Module::get_conversion_rate(
							WC_Woo_Mercado_Pago_Module::$country_configs[$get_request['response']['site_id']]['currency']
						);
						if ( $currency_ratio > 0 ) {
							update_option( '_can_do_currency_conversion_v1', true, true );
						} else {
							update_option( '_can_do_currency_conversion_v1', false, true );
						}
						return true;
					}
				} catch ( MercadoPagoException $e ) {
					// TODO: should we handle an exception here?
				}
			}
			update_option( '_test_user_v1', '', true );
			update_option( '_site_id_v1', '', true );
			update_option( '_collector_id_v1', '', true );
			update_option( '_all_payment_methods_ticket', '[]', true );
			update_option( '_can_do_currency_conversion_v1', false, true );
			return false;
		}

		// Get WooCommerce instance
		public static function woocommerce_instance() {
			if ( function_exists( 'WC' ) ) {
				return WC();
			} else {
				global $woocommerce;
				return $woocommerce;
			}
		}

		/**
		 * Summary: Get the rate of conversion between two currencies.
		 * Description: The currencies are the one used in WooCommerce and the one used in $site_id.
		 * @return a float that is the rate of conversion.
		 */
		public static function get_conversion_rate( $used_currency ) {
			$wc_currency = get_woocommerce_currency();
			$email = ( wp_get_current_user()->ID != 0 ) ? wp_get_current_user()->user_email : null;
			MPRestClient::set_email( $email );
			if ( strlen( $wc_currency ) == 3 && strlen( $used_currency ) == 3 ) {
				$currency_obj = MPRestClient::get(
					array( 'uri' => '/currency_conversions/search?' .
						'from=' . get_woocommerce_currency() .
						'&to=' . $used_currency
					),
					WC_Woo_Mercado_Pago_Module::get_module_version()
				);
				if ( isset( $currency_obj['response'] ) ) {
					$currency_obj = $currency_obj['response'];
					if ( isset( $currency_obj['ratio'] ) ) {
						return ( (float) $currency_obj['ratio'] );
					}
				}
			}
			return -1;
		}

		/**
		 * Summary: Builds up the array for the mp_install table, with info related with checkout.
		 * Description: Builds up the array for the mp_install table, with info related with checkout.
		 * @return an array with the module informations.
		 */
		public static function get_common_settings() {
			$w = WC_Woo_Mercado_Pago_Module::woocommerce_instance();
			$infra_data = array(
				'module_version' => WC_Woo_Mercado_Pago_Module::VERSION,
				'platform' => 'WooCommerce',
				'platform_version' => $w->version,
				'code_version' => phpversion(),
				'so_server' => PHP_OS
			);
			return $infra_data;
		}

		/**
		 * Summary: Get store categories from Mercado Pago.
		 * Description: Trigger API to get available categories and proper description.
		 * @return an array with found categories and a description for its selector title.
		 */
		public static function get_categories() {
			$store_categories_id = array();
			$store_categories_description = array();
			// Get Mercado Pago store categories.
			$email = ( wp_get_current_user()->ID != 0 ) ? wp_get_current_user()->user_email : null;
			MPRestClient::set_email( $email );
			$categories = MPRestClient::get(
				array( 'uri' => '/item_categories' ),
				WC_Woo_Mercado_Pago_Module::get_module_version()
			);
			foreach ( $categories['response'] as $category ) {
				array_push(
					$store_categories_id, str_replace( '_', ' ', $category['id'] )
				);
				array_push(
					$store_categories_description, str_replace( '_', ' ', $category['description'] )
				);
			}
			return array(
				'store_categories_id' => $store_categories_id,
				'store_categories_description' => $store_categories_description
			);
		}

		/**
		 * Summary: Get information about the used Mercado Pago account based in its site.
		 * Description: Get information about the used Mercado Pago account based in its site.
		 * @return an array with the information.
		 */
		public static function get_site_data( $is_v1 = false ) {
			if ( ! $is_v1 ) {
				$site_id = get_option( '_site_id_v0', '' );
			} else {
				$site_id = get_option( '_site_id_v1', '' );
			}
			if ( isset( $site_id ) && ! empty( $site_id ) ) {
				return WC_Woo_Mercado_Pago_Module::$country_configs[$site_id];
			} else {
				return null;
			}
		}

		// Fix to URL Problem : #038; replaces & and breaks the navigation.
		public static function workaround_ampersand_bug( $link ) {
			return str_replace( '\/', '/', str_replace( '&#038;', '&', $link) );
		}

		/**
		 * Summary: Find template's folder.
		 * Description: Find template's folder.
		 * @return a string that identifies the path.
		 */
		public static function get_templates_path() {
			return plugin_dir_path( __FILE__ ) . 'templates/';
		}

		/**
		 * Summary: Get module's version.
		 * Description: Get module's version.
		 * @return a string with the given version.
		 */
		public static function get_module_version() {
			return WC_Woo_Mercado_Pago_Module::VERSION;
		}

		/**
		 * Summary: Get client id from access token.
		 * Description: Get client id from access token.
		 * @return the client id.
		 */
		public static function get_client_id( $at ) {
			$t = explode ( '-', $at );
			if ( count( $t ) > 0 ) {
				return $t[1];
			}
			return '';
		}

		// Check if an order is recurrent.
		public static function is_subscription( $items ) {
			$is_subscription = false;
			if ( sizeof( $items ) == 1 ) {
				foreach ( $items as $cart_item_key => $cart_item ) {
					$is_recurrent = ( method_exists( $cart_item, 'get_meta' ) ) ?
						$cart_item->get_meta( '_used_gateway' ) :
						get_post_meta( $cart_item['product_id'], '_mp_recurring_is_recurrent', true );
					if ( $is_recurrent == 'yes' ) {
						$is_subscription = true;
					}
				}
			}
			return $is_subscription;
		}

		// Return boolean indicating if currency is supported.
		public static function is_supported_currency( $site_id ) {
			return get_woocommerce_currency() == WC_Woo_Mercado_Pago_Module::$country_configs[$site_id]['currency'];
		}

		public static function build_currency_conversion_err_msg( $currency ) {
			return '<img width="14" height="14" src="' .
				plugins_url( 'assets/images/error.png', __FILE__ ) . '"> ' .
				__( 'ERROR: It was not possible to convert the unsupported currency', 'woocommerce-mercadopago' ) .
				' ' . get_woocommerce_currency() . ' '	.
				__( 'to', 'woocommerce-mercadopago' ) . ' ' . $currency . '. ' .
				__( 'Currency conversions should be made outside this module.', 'woocommerce-mercadopago' );
		}

		public static function build_currency_not_converted_msg( $currency, $country_name ) {
			return '<img width="14" height="14" src="' .
				plugins_url( 'assets/images/warning.png', __FILE__ ) . '"> ' .
				__( 'ATTENTION: The currency', 'woocommerce-mercadopago' ) .
				' ' . get_woocommerce_currency() . ' ' .
				__( 'defined in WooCommerce is different from the one used in your credentials country.<br>The currency for transactions in this payment method will be', 'woocommerce-mercadopago' ) .
				' ' . $currency . ' (' . $country_name . '). ' .
				__( 'Currency conversions should be made outside this module.', 'woocommerce-mercadopago' );
		}

		public static function build_currency_converted_msg( $currency ) {
			return '<img width="14" height="14" src="' .
				plugins_url( 'assets/images/check.png', __FILE__ ) . '"> ' .
				__( 'CURRENCY CONVERTED: Your store is converting currency from', 'woocommerce-mercadopago' )  .
				' ' . get_woocommerce_currency() . ' ' .
				__( 'to', 'woocommerce-mercadopago' ) . ' ' . $currency;
		}

		public static function get_country_name( $site_id ) {
			switch ( $site_id ) {
				case 'MCO':
					return __( 'Colombia', 'woocommerce-mercadopago' );
				case 'MLA':
					return __( 'Argentine', 'woocommerce-mercadopago' );
				case 'MLB':
					return __( 'Brazil', 'woocommerce-mercadopago' );
				case 'MLC':
					return __( 'Chile', 'woocommerce-mercadopago' );
				case 'MLM':
					return __( 'Mexico', 'woocommerce-mercadopago' );
				case 'MLU':
					return __( 'Uruguay', 'woocommerce-mercadopago' );
				case 'MLV':
					return __( 'Venezuela', 'woocommerce-mercadopago' );
				case 'MPE':
					return __( 'Peru', 'woocommerce-mercadopago' );
			}
			return '';
		}

		// Build the string representing the path to the log file.
		public static function build_log_path_string( $gateway_id, $gateway_name ) {
			return '<a href="' . esc_url( admin_url( 'admin.php?page=wc-status&tab=logs&log_file=' .
				esc_attr( $gateway_id ) . '-' . sanitize_file_name( wp_hash( $gateway_id ) ) . '.log' ) ) . '">' .
				$gateway_name . '</a>';
		}

		public static function get_wc_status_for_mp_status( $mp_status ) {
			$defaults = array(
				'pending' => 'pending',
				'approved' => 'processing',
				'inprocess' => 'on_hold',
				'inmediation' => 'on_hold',
				'rejected' => 'failed',
				'cancelled' => 'cancelled',
				'refunded' => 'refunded',
				'chargedback' => 'refunded'
			);
			$status = get_option( '_mp_order_status_' . $mp_status . '_map', $defaults[$mp_status] );
			return str_replace( '_', '-', $status );
		}

		public static function get_map( $selector_id ) {
			$arr = explode( '_', $selector_id );
			$defaults = array(
				'pending' => 'pending',
				'approved' => 'processing',
				'inprocess' => 'on_hold',
				'inmediation' => 'on_hold',
				'rejected' => 'failed',
				'cancelled' => 'cancelled',
				'refunded' => 'refunded',
				'chargedback' => 'refunded'
			);
			$selection = get_option( '_mp_' . $selector_id, $defaults[$arr[2]] );
			return
				'<option value="pending"' . ( $selection == 'pending' ? 'selected="selected"' : '' ) . '>' .
					__( "Update WooCommerce order to ", "woocommerce-mercadopago" ) . 'PENDING
				</option>
				<option value="processing"' . ( $selection == 'processing' ? 'selected="selected"' : '' ) . '>' .
					__( "Update WooCommerce order to ", "woocommerce-mercadopago" ) . 'PROCESSING
				</option>
				<option value="on_hold"' . ( $selection == 'on_hold' ? 'selected="selected"' : '' ) . '>' .
					__( "Update WooCommerce order to ", "woocommerce-mercadopago" ) . 'ON-HOLD
				</option>
				<option value="completed"' . ( $selection == 'completed' ? 'selected="selected"' : '' ) . '>' .
					__( "Update WooCommerce order to ", "woocommerce-mercadopago" ) . 'COMPLETED
				</option>
				<option value="cancelled"' . ( $selection == 'cancelled' ? 'selected="selected"' : '' ) . '>' .
					__( "Update WooCommerce order to ", "woocommerce-mercadopago" ) . 'CANCELLED
				</option>
				<option value="refunded"' . ( $selection == 'refunded' ? 'selected="selected"' : '' ) . '>' .
					__( "Update WooCommerce order to ", "woocommerce-mercadopago" ) . 'REFUNDED
				</option>
				<option value="failed"' . ( $selection == 'failed' ? 'selected="selected"' : '' ) . '>' .
					__( "Update WooCommerce order to ", "woocommerce-mercadopago" ) . 'FAILED
				</option>';
		}

		public static function generate_refund_cancel_subscription( $domain, $success_msg, $fail_msg, $options, $str1, $str2, $str3, $str4 ) {
			$subscription_js = '<script type="text/javascript">
				( function() {
					var MPSubscription = {}
					MPSubscription.callSubscriptionCancel = function () {
						var url = "' . $domain . '";
						url += "&action_mp_payment_id=" + document.getElementById("payment_id").value;
						url += "&action_mp_payment_amount=" + document.getElementById("payment_amount").value;
						url += "&action_mp_payment_action=cancel";
						document.getElementById("sub_pay_cancel_btn").disabled = true;
						MPSubscription.AJAX({
							url: url,
							method : "GET",
							timeout : 5000,
							error: function() {
								document.getElementById("sub_pay_cancel_btn").disabled = false;
								alert("' . $fail_msg . '");
							},
							success : function ( status, data ) {
								document.getElementById("sub_pay_cancel_btn").disabled = false;
								var mp_status = data.status;
								var mp_message = data.message;
								if (data.status == 200) {
									alert("' . $success_msg . '");
								} else {
									alert(mp_message);
								}
							}
						});
					}
					MPSubscription.callSubscriptionRefund = function () {
						var url = "' . $domain . '";
						url += "&action_mp_payment_id=" + document.getElementById("payment_id").value;
						url += "&action_mp_payment_amount=" + document.getElementById("payment_amount").value;
						url += "&action_mp_payment_action=refund";
						document.getElementById("sub_pay_refund_btn").disabled = true;
						MPSubscription.AJAX({
							url: url,
							method : "GET",
							timeout : 5000,
							error: function() {
								document.getElementById("sub_pay_refund_btn").disabled = false;
								alert("' . $fail_msg . '");
							},
							success : function ( status, data ) {
								document.getElementById("sub_pay_refund_btn").disabled = false;
								var mp_status = data.status;
								var mp_message = data.message;
								if (data.status == 200) {
									alert("' . $success_msg . '");
								} else {
									alert(mp_message);
								}
							}
						});
					}
					MPSubscription.AJAX = function( options ) {
						var useXDomain = !!window.XDomainRequest;
						var req = useXDomain ? new XDomainRequest() : new XMLHttpRequest()
						var data;
						options.url += ( options.url.indexOf( "?" ) >= 0 ? "&" : "?" );
						options.requestedMethod = options.method;
						if ( useXDomain && options.method == "PUT" ) {
							options.method = "POST";
							options.url += "&_method=PUT";
						}
						req.open( options.method, options.url, true );
						req.timeout = options.timeout || 1000;
						if ( window.XDomainRequest ) {
							req.onload = function() {
								data = JSON.parse( req.responseText );
								if ( typeof options.success === "function" ) {
									options.success( options.requestedMethod === "POST" ? 201 : 200, data );
								}
							};
							req.onerror = req.ontimeout = function() {
								if ( typeof options.error === "function" ) {
									options.error( 400, {
										user_agent:window.navigator.userAgent, error : "bad_request", cause:[]
									});
								}
							};
							req.onprogress = function() {};
						} else {
							req.setRequestHeader( "Accept", "application/json" );
							if ( options.contentType ) {
								req.setRequestHeader( "Content-Type", options.contentType );
							} else {
								req.setRequestHeader( "Content-Type", "application/json" );
							}
							req.onreadystatechange = function() {
								if ( this.readyState === 4 ) {
									if ( this.status >= 200 && this.status < 400 ) {
										// Success!
										data = JSON.parse( this.responseText );
										if ( typeof options.success === "function" ) {
											options.success( this.status, data );
										}
									} else if ( this.status >= 400 ) {
										data = JSON.parse( this.responseText );
										if ( typeof options.error === "function" ) {
											options.error( this.status, data );
										}
									} else if ( typeof options.error === "function" ) {
										options.error( 503, {} );
									}
								}
							};
						}
						if ( options.method === "GET" || options.data == null || options.data == undefined ) {
							req.send();
						} else {
							req.send( JSON.stringify( options.data ) );
						}
					}
					this.MPSubscription = MPSubscription;
				} ).call();
			</script>';
			$subscription_meta_box = '<table>' .
				'<tr class="total">' .
					'<td><label for="payment_id" style="margin-right:1px;">' .
						$str1 .
					'</label></td>' .
					'<td><select id="payment_id" name="refund_payment_id" style="margin-left:1px;">' .
						$options .
					'</select></td>' .
				'</tr>' .
				'<tr class="total">' .
					'<td><label for="payment_amount" style="margin-right:1px;">' .
						$str2 .
					'</label></td>' .
					'<td><input type="number" class="text amount_input" id="payment_amount" value="0" name="payment_amount"' .
						' placeholder="Decimal" min="0" step="0.01" value="0.00" style="width:112px; margin-left:1px;"' .
						' ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/"/>' .
					'</td>' .
				'</tr>' .
				'<tr class="total">' .
					'<td><input onclick="MPSubscription.callSubscriptionRefund();" type="button"' .
						' id="sub_pay_refund_btn" class="button button" style="margin-left:1px; margin-top:2px;"' .
						' name="refund" value="' . $str3 .
						'" style="margin-right:1px;"></td>' .
					'<td><input onclick="MPSubscription.callSubscriptionCancel();" type="button"' .
						' id="sub_pay_cancel_btn" class="button button" style="margin-right:1px; margin-top:2px;"' .
						' name="cancel" value="' . $str4 .
						'" style="margin-left:1px;"></td>' .
				'</tr>' .
			'</table>';
			return $subscription_js . $subscription_meta_box;
		}
		
		/**
		 * Check if product dimensions are well defined
		 */
		public static function is_product_dimensions_valid( $all_product_data ) {
			if ( empty( $all_product_data ) ) {
				return true;
			}
			foreach ( $all_product_data as $p ) {
				$product = wc_get_product( $p->ID );
				if ( ! $product->is_virtual() ) {
					$dimensions = $product->get_dimensions( false );
					if ( ! is_numeric( $dimensions['height'] ) ) {
						return false;
					}
					if ( ! is_numeric( $dimensions['width'] ) ) {
						return false;
					}
					if ( ! is_numeric( $dimensions['length'] ) ) {
						return false;
					}
					if ( empty( $product->get_weight() ) || ! is_numeric( $product->get_weight() ) ) {
						return false;
					}
				}
			}
			return true;
		}

	}

	//=====

	// Create Mercado Pago option menu.
	add_action( 'admin_menu', function() {
		add_options_page(
			'Mercado Pago Options', 'Mercado Pago', 'manage_options', 'mercado-pago-settings',
			function() {

				// Verify permissions.
				if ( ! current_user_can( 'manage_options' ) )  {
					wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
				}

				// Check for submits.
				if ( isset( $_POST['submit'] ) ) {
					if ( isset( $_POST['client_id'] ) ) {
						update_option( '_mp_client_id', $_POST['client_id'], true );
					} else {
						update_option( '_mp_client_id', '', true );
					}
					if ( isset( $_POST['client_secret'] ) ) {
						update_option( '_mp_client_secret', $_POST['client_secret'], true );
					} else {
						update_option( '_mp_client_secret', '', true );
					}
					if ( isset( $_POST['public_key'] ) ) {
						update_option( '_mp_public_key', $_POST['public_key'], true );
					} else {
						update_option( '_mp_public_key', '', true );
					}
					if ( isset( $_POST['access_token'] ) ) {
						update_option( '_mp_access_token', $_POST['access_token'], true );
					} else {
						update_option( '_mp_access_token', '', true );
					}
					if ( isset( $_POST['success_url'] ) ) {
						update_option( '_mp_success_url', $_POST['success_url'], true );
					} else {
						update_option( '_mp_success_url', '', true );
					}
					if ( isset( $_POST['fail_url'] ) ) {
						update_option( '_mp_fail_url', $_POST['fail_url'], true );
					} else {
						update_option( '_mp_fail_url', '', true );
					}
					if ( isset( $_POST['pending_url'] ) ) {
						update_option( '_mp_pending_url', $_POST['pending_url'], true );
					} else {
						update_option( '_mp_pending_url', '', true );
					}
					if ( isset( $_POST['order_status_pending_map'] ) ) {
						update_option( '_mp_order_status_pending_map', $_POST['order_status_pending_map'], true );
					} else {
						update_option( '_mp_order_status_pending_map', '', true );
					}
					if ( isset( $_POST['order_status_approved_map'] ) ) {
						update_option( '_mp_order_status_approved_map', $_POST['order_status_approved_map'], true );
					} else {
						update_option( '_mp_order_status_approved_map', '', true );
					}
					if ( isset( $_POST['order_status_inprocess_map'] ) ) {
						update_option( '_mp_order_status_inprocess_map', $_POST['order_status_inprocess_map'], true );
					} else {
						update_option( '_mp_order_status_inprocess_map', '', true );
					}
					if ( isset( $_POST['order_status_inmediation_map'] ) ) {
						update_option( '_mp_order_status_inmediation_map', $_POST['order_status_inmediation_map'], true );
					} else {
						update_option( '_mp_order_status_inmediation_map', '', true );
					}
					if ( isset( $_POST['order_status_rejected_map'] ) ) {
						update_option( '_mp_order_status_rejected_map', $_POST['order_status_rejected_map'], true );
					} else {
						update_option( '_mp_order_status_rejected_map', '', true );
					}
					if ( isset( $_POST['order_status_cancelled_map'] ) ) {
						update_option( '_mp_order_status_cancelled_map', $_POST['order_status_cancelled_map'], true );
					} else {
						update_option( '_mp_order_status_cancelled_map', '', true );
					}
					if ( isset( $_POST['order_status_refunded_map'] ) ) {
						update_option( '_mp_order_status_refunded_map', $_POST['order_status_refunded_map'], true );
					} else {
						update_option( '_mp_order_status_refunded_map', '', true );
					}
					if ( isset( $_POST['order_status_chargedback_map'] ) ) {
						update_option( '_mp_order_status_chargedback_map', $_POST['order_status_chargedback_map'], true );
					} else {
						update_option( '_mp_order_status_chargedback_map', '', true );
					}
					if ( isset( $_POST['statement_descriptor'] ) ) {
						update_option( '_mp_statement_descriptor', $_POST['statement_descriptor'], true );
					} else {
						update_option( '_mp_statement_descriptor', '', true );
					}
					if ( isset( $_POST['category_id'] ) ) {
						update_option( '_mp_category_id', $_POST['category_id'], true );
						$categories_data = WC_Woo_Mercado_Pago_Module::$categories;
						update_option( '_mp_category_name', $categories_data['store_categories_id'][$_POST['category_id']], true );
					} else {
						update_option( '_mp_category_id', '', true );
						update_option( '_mp_category_name', 'others', true );
					}
					if ( isset( $_POST['store_identificator'] ) ) {
						update_option( '_mp_store_identificator', $_POST['store_identificator'], true );
					} else {
						update_option( '_mp_store_identificator', '', true );
					}
					/*if ( isset( $_POST['custom_domain'] ) ) {
						update_option( '_mp_custom_domain', $_POST['custom_domain'], true );
					} else {
						update_option( '_mp_custom_domain', '', true );
					}*/
					if ( isset( $_POST['currency_conversion_v0'] ) ) {
						update_option( '_mp_currency_conversion_v0', $_POST['currency_conversion_v0'], true );
					} else {
						update_option( '_mp_currency_conversion_v0', '', true );
					}
					if ( isset( $_POST['currency_conversion_v1'] ) ) {
						update_option( '_mp_currency_conversion_v1', $_POST['currency_conversion_v1'], true );
					} else {
						update_option( '_mp_currency_conversion_v1', '', true );
					}
					if ( isset( $_POST['debug_mode'] ) ) {
						update_option( '_mp_debug_mode', $_POST['debug_mode'], true );
					} else {
						update_option( '_mp_debug_mode', '', true );
					}
					if ( isset( $_POST['sandbox_mode'] ) ) {
						update_option( '_mp_sandbox_mode', $_POST['sandbox_mode'], true );
					} else {
						update_option( '_mp_sandbox_mode', '', true );
					}
				}

				// Mercado Pago logo.
				$mp_logo = '<img width="185" height="48" src="' . plugins_url( 'assets/images/mplogo.png', __FILE__ ) . '">';
				// Check WooCommerce.
				$has_woocommerce_message = class_exists( 'WC_Payment_Gateway' ) ?
					'<img width="14" height="14" src="' . plugins_url( 'assets/images/check.png', __FILE__ ) . '"> ' .
					__( 'WooCommerce is installed and enabled.', 'woocommerce-mercadopago' ) :
					'<img width="14" height="14" src="' . plugins_url( 'assets/images/error.png', __FILE__ ) . '"> ' .
					__( 'You don\'t have WooCommerce installed and enabled.', 'woocommerce-mercadopago' );
				// Creating PHP version message.
				$min_php_message = phpversion() >= WC_Woo_Mercado_Pago_Module::MIN_PHP ?
					'<img width="14" height="14" src="' . plugins_url( 'assets/images/check.png', __FILE__ ) . '"> ' .
					__( 'Your PHP version is OK.', 'woocommerce-mercadopago' ) :
					'<img width="14" height="14" src="' . plugins_url( 'assets/images/warning.png', __FILE__ ) . '"> ' .
					sprintf(
						__( 'Your PHP version do not support this module. You have %s, minimal required is %s.', 'woocommerce-mercadopago' ),
						phpversion(), WC_Woo_Mercado_Pago_Module::MIN_PHP
					);
				// Check cURL.
				$curl_message = in_array( 'curl', get_loaded_extensions() ) ?
					'<img width="14" height="14" src="' . plugins_url( 'assets/images/check.png', __FILE__ ) . '"> ' .
					__( 'cURL is installed.', 'woocommerce-mercadopago' ) :
					'<img width="14" height="14" src="' . plugins_url( 'assets/images/error.png', __FILE__ ) . '"> ' .
					__( 'cURL is not installed.', 'woocommerce-mercadopago' );
				// Check SSL.
				$is_ssl_message = empty( $_SERVER['HTTPS'] ) || $_SERVER['HTTPS'] == 'off' ?
					'<img width="14" height="14" src="' . plugins_url( 'assets/images/warning.png', __FILE__ ) . '"> ' .
					__( 'SSL is missing in your site.', 'woocommerce-mercadopago' ) :
					'<img width="14" height="14" src="' . plugins_url( 'assets/images/check.png', __FILE__ ) . '"> ' .
					__( 'Your site has SSL enabled.', 'woocommerce-mercadopago' );
				// Check porduct dimensions.
				global $wpdb;
				$all_product_data = $wpdb->get_results(
					'SELECT ID FROM `' . $wpdb->prefix . 'posts` where post_type="product" and post_status = "publish"'
				);
				$is_all_products_with_valid_dimensions = WC_Woo_Mercado_Pago_Module::is_product_dimensions_valid( $all_product_data ) ?
					'<img width="14" height="14" src="' . plugins_url( 'assets/images/check.png', __FILE__ ) . '"> ' .
					__( 'Your products have theirs dimensions well defined.', 'woocommerce-mercadopago' ) :
					'<img width="14" height="14" src="' . plugins_url( 'assets/images/warning.png', __FILE__ ) . '"> ' .
					__( 'You have product(s) with invalid dimensions.', 'woocommerce-mercadopago' );
				// Create links for internal redirections to each payment solution.
				$gateway_buttons = '<strong>' .
					'<a class="button button-primary" href="' . esc_url( admin_url(
						'admin.php?page=wc-settings&tab=checkout&section=woo-mercado-pago-basic' ) ) .
						'">' . __( 'Basic Checkout', 'woocommerce-mercadopago' ) . '</a>' . ' ' .
					'<a class="button button-primary" href="' . esc_url( admin_url(
						'admin.php?page=wc-settings&tab=checkout&section=woo-mercado-pago-custom' ) ) .
						'">' . __( 'Custom Checkout', 'woocommerce-mercadopago' ) . '</a>' . ' ' .
					'<a class="button button-primary" href="' . esc_url( admin_url(
						'admin.php?page=wc-settings&tab=checkout&section=woo-mercado-pago-ticket' ) ) .
						'">' . __( 'Ticket', 'woocommerce-mercadopago' ) . '</a>' . ' ' .
					'<a class="button button-primary" href="' . esc_url( admin_url(
						'admin.php?page=wc-settings&tab=checkout&section=woo-mercado-pago-subscription' ) ) .
						'">' . __( 'Subscription', 'woocommerce-mercadopago' ) . '</a>' .
				'</strong>';
				// Statement descriptor.
				$statement_descriptor = get_option( '_mp_statement_descriptor', 'Mercado Pago' );
				// Get categories.
				$store_categories_id = WC_Woo_Mercado_Pago_Module::$categories['store_categories_id'];
				$category_id = get_option( '_mp_category_id', 0 );
				if ( count( $store_categories_id ) == 0 ) {
					$store_category_message = '<img width="14" height="14" src="' . plugins_url( 'assets/images/warning.png', __FILE__ ) . '">' . ' ' .
						__( 'Configure your Client_id and Client_secret to have access to more options.', 'woocommerce-mercadopago' );
				} else {
					$store_category_message = __( 'Define which type of products your store sells.', 'woocommerce-mercadopago' );
				}
				// Store identification.
				$store_identificator = get_option( '_mp_store_identificator', 'WC-' );
				// Custom domain for IPN.
				/*$custom_domain = get_option( '_mp_custom_domain', '' );*/
				// Debug mode.
				$_mp_debug_mode = get_option( '_mp_debug_mode', '' );
				if ( empty( $_mp_debug_mode ) ) {
					$is_debug_mode = '';
				} else {
					$is_debug_mode = 'checked="checked"';
				}
				// Sandbox mode.
				$_mp_sandbox_mode = get_option( '_mp_sandbox_mode', '' );
				if ( empty( $_mp_sandbox_mode ) ) {
					$is_sandbox_mode = '';
				} else {
					$is_sandbox_mode = 'checked="checked"';
				}

				// ===== v0 verifications =====
				// Trigger v0 API to validate credentials.
				$site_id_v0 = '';
				if ( WC_Woo_Mercado_Pago_Module::validate_credentials_v0() ) {
					$site_id_v0 = get_option( '_site_id_v0', '' );
					$v0_credentials_message = '<img width="14" height="14" src="' . plugins_url( 'assets/images/check.png', __FILE__ ) . '"> ' .
						__( 'Your <strong>client_id</strong> and <strong>client_secret</strong> are <strong>valid</strong> for', 'woocommerce-mercadopago' ) . ': ' .
						'<img style="margin-top:2px;" width="18.6" height="12" src="' .
						plugins_url( 'assets/images/' . $site_id_v0 . '/' . $site_id_v0 . '.png', __FILE__ ) . '"> ' .
						WC_Woo_Mercado_Pago_Module::get_country_name( $site_id_v0 );
				} else {
					$v0_credentials_message = '<img width="14" height="14" src="' . plugins_url( 'assets/images/error.png', __FILE__ ) . '"> ' .
						__( 'Your <strong>client_id</strong> and <strong>client_secret</strong> are <strong>not valid</strong>!', 'woocommerce-mercadopago' );
				}
				$v0_credential_locales = sprintf(
					'%s <a href="https://www.mercadopago.com/mla/account/credentials?type=basic" target="_blank">%s</a>, ' .
					'<a href="https://www.mercadopago.com/mlb/account/credentials?type=basic" target="_blank">%s</a>, ' .
					'<a href="https://www.mercadopago.com/mlc/account/credentials?type=basic" target="_blank">%s</a>, ' .
					'<a href="https://www.mercadopago.com/mco/account/credentials?type=basic" target="_blank">%s</a>, ' .
					'<a href="https://www.mercadopago.com/mlm/account/credentials?type=basic" target="_blank">%s</a>, ' .
					'<a href="https://www.mercadopago.com/mpe/account/credentials?type=basic" target="_blank">%s</a>, ' .
					'<a href="https://www.mercadopago.com/mlu/account/credentials?type=basic" target="_blank">%s</a> %s ' .
					'<a href="https://www.mercadopago.com/mlv/account/credentials?type=basic" target="_blank">%s</a>',
					__( 'These credentials are used in <strong>Basic Checkout</strong> and <strong>Subscriptions</strong>. Access it for your country:<br>', 'woocommerce-mercadopago' ),
					__( 'Argentine', 'woocommerce-mercadopago' ),
					__( 'Brazil', 'woocommerce-mercadopago' ),
					__( 'Chile', 'woocommerce-mercadopago' ),
					__( 'Colombia', 'woocommerce-mercadopago' ),
					__( 'Mexico', 'woocommerce-mercadopago' ),
					__( 'Peru', 'woocommerce-mercadopago' ),
					__( 'Uruguay', 'woocommerce-mercadopago' ),
					__( 'or', 'woocommerce-mercadopago' ),
					__( 'Venezuela', 'woocommerce-mercadopago' )
				);
				// Currency conversion.
				$_mp_currency_conversion_v0 = get_option( '_mp_currency_conversion_v0', '' );
				if ( empty( $_mp_currency_conversion_v0 ) ) {
					$is_currency_conversion_v0 = '';
				} else {
					$is_currency_conversion_v0 = 'checked="checked"';
				}
				$_can_do_currency_conversion_v0 = get_option( '_can_do_currency_conversion_v0', false );
				if ( ! empty ( $site_id_v0 ) ) {
					if ( ! WC_Woo_Mercado_Pago_Module::is_supported_currency( $site_id_v0 ) ) {
						if ( empty( $_mp_currency_conversion_v0 ) ) {
							$currency_conversion_v0_message = WC_Woo_Mercado_Pago_Module::build_currency_not_converted_msg(
								WC_Woo_Mercado_Pago_Module::$country_configs[$site_id_v0]['currency'],
								WC_Woo_Mercado_Pago_Module::get_country_name( $site_id_v0 )
							);
						} elseif ( ! empty( $_mp_currency_conversion_v0 ) && $_can_do_currency_conversion_v0 ) {
							$currency_conversion_v0_message = WC_Woo_Mercado_Pago_Module::build_currency_converted_msg(
								WC_Woo_Mercado_Pago_Module::$country_configs[$site_id_v0]['currency']
							);
						} else {
							$currency_conversion_v0_message = WC_Woo_Mercado_Pago_Module::build_currency_conversion_err_msg(
								WC_Woo_Mercado_Pago_Module::$country_configs[$site_id_v0]['currency']
							);
						}
					} else {
						$currency_conversion_v0_message = '';
					}
				} else {
					$currency_conversion_v0_message = '';
				}

				// ===== v1 verifications =====
				// Trigger v1 API to validate credentials.
				$site_id_v1 = '';
				if ( WC_Woo_Mercado_Pago_Module::validate_credentials_v1() ) {
					$site_id_v1 = get_option( '_site_id_v1', '' );
					$v1_credentials_message = WC_Woo_Mercado_Pago_Module::validate_credentials_v1() ?
						'<img width="14" height="14" src="' . plugins_url( 'assets/images/check.png', __FILE__ ) . '"> ' .
						__( 'Your <strong>public_key</strong> and <strong>access_token</strong> are <strong>valid</strong> for', 'woocommerce-mercadopago' ) . ': ' .
						'<img style="margin-top:2px;" width="18.6" height="12" src="' .
						plugins_url( 'assets/images/' . $site_id_v1 . '/' . $site_id_v1 . '.png', __FILE__ ) . '"> ' .
						WC_Woo_Mercado_Pago_Module::get_country_name( $site_id_v1 ) :
						'<img width="14" height="14" src="' . plugins_url( 'assets/images/error.png', __FILE__ ) . '"> ' .
						__( 'Your <strong>public_key</strong> and <strong>access_token</strong> are <strong>not valid</strong>!', 'woocommerce-mercadopago' );
				} else {
					$v1_credentials_message = '<img width="14" height="14" src="' . plugins_url( 'assets/images/error.png', __FILE__ ) . '"> ' .
						__( 'Your <strong>public_key</strong> and <strong>access_token</strong> are <strong>not valid</strong>!', 'woocommerce-mercadopago' );
				}
				$v1_credential_locales = sprintf(
					'%s <a href="https://www.mercadopago.com/mla/account/credentials?type=custom" target="_blank">%s</a>, ' .
					'<a href="https://www.mercadopago.com/mlb/account/credentials?type=custom" target="_blank">%s</a>, ' .
					'<a href="https://www.mercadopago.com/mlc/account/credentials?type=custom" target="_blank">%s</a>, ' .
					'<a href="https://www.mercadopago.com/mco/account/credentials?type=custom" target="_blank">%s</a>, ' .
					'<a href="https://www.mercadopago.com/mlm/account/credentials?type=custom" target="_blank">%s</a>, ' .
					'<a href="https://www.mercadopago.com/mpe/account/credentials?type=custom" target="_blank">%s</a> %s ' .
					'<a href="https://www.mercadopago.com/mlv/account/credentials?type=custom" target="_blank">%s</a>',
					__( 'These credentials are used in <strong>Custom Checkout</strong> and <strong>Tickets</strong>. Access it for your country:<br>', 'woocommerce-mercadopago' ),
					__( 'Argentine', 'woocommerce-mercadopago' ),
					__( 'Brazil', 'woocommerce-mercadopago' ),
					__( 'Chile', 'woocommerce-mercadopago' ),
					__( 'Colombia', 'woocommerce-mercadopago' ),
					__( 'Mexico', 'woocommerce-mercadopago' ),
					__( 'Peru', 'woocommerce-mercadopago' ),
					__( 'or', 'woocommerce-mercadopago' ),
					__( 'Venezuela', 'woocommerce-mercadopago' )
				);
				// Currency conversion.
				$_mp_currency_conversion_v1 = get_option( '_mp_currency_conversion_v1', '' );
				if ( empty( $_mp_currency_conversion_v1 ) ) {
					$is_currency_conversion_v1 = '';
				} else {
					$is_currency_conversion_v1 = 'checked="checked"';
				}
				$_can_do_currency_conversion_v1 = get_option( '_can_do_currency_conversion_v1', false );
				if ( ! empty( $site_id_v1 ) ) {
					if ( ! WC_Woo_Mercado_Pago_Module::is_supported_currency( $site_id_v1 ) ) {
						if ( empty( $_mp_currency_conversion_v1 ) ) {
							$currency_conversion_v1_message = WC_Woo_Mercado_Pago_Module::build_currency_not_converted_msg(
								WC_Woo_Mercado_Pago_Module::$country_configs[$site_id_v1]['currency'],
								WC_Woo_Mercado_Pago_Module::get_country_name( $site_id_v1 )
							);
						} elseif ( ! empty( $_mp_currency_conversion_v1 ) && $_can_do_currency_conversion_v1 ) {
							$currency_conversion_v1_message = WC_Woo_Mercado_Pago_Module::build_currency_converted_msg(
								WC_Woo_Mercado_Pago_Module::$country_configs[$site_id_v1]['currency']
							);
						} else {
							$currency_conversion_v1_message = WC_Woo_Mercado_Pago_Module::build_currency_conversion_err_msg(
								WC_Woo_Mercado_Pago_Module::$country_configs[$site_id_v1]['currency']
							);
						}
					} else {
						$currency_conversion_v1_message = '';
					}
				} else {
					$currency_conversion_v1_message = '';
				}

				require_once( 'templates/mp_main_settings.php' );

			}
		);
	});

	// ==========================================================================================

	// add our own item to the order actions meta box
	add_action( 'woocommerce_order_actions', 'add_mp_order_meta_box_actions' );
	// define the item in the meta box by adding an item to the $actions array
	function add_mp_order_meta_box_actions( $actions ) {
		$actions['cancel_order'] = __( 'Cancel Order', 'woocommerce-mercadopago' );
		return $actions;
	}

	// Payment gateways should be created as additional plugins that hook into WooCommerce.
	// Inside the plugin, you need to create a class after plugins are loaded.
	add_action(
		'plugins_loaded',
		array( 'WC_Woo_Mercado_Pago_Module', 'init_mercado_pago_class' )
	);

endif;
