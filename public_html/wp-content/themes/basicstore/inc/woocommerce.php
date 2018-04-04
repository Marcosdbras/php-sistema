<?php
/**
 * WooCommerce related functions
 *
 * @package basicstore
 */


/**
*
* Remove all styles from WooCommerce
* https://docs.woocommerce.com/document/disable-the-default-stylesheet/
*
*/
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );


/**
 *
 * Checkout fields make with Bootstrap classes
 *
 */
add_filter('woocommerce_checkout_fields', 'basic_store_addBootstrapToCheckoutFields' );
function basic_store_addBootstrapToCheckoutFields($fields) {
  foreach ($fields as &$fieldset) {
    foreach ($fieldset as &$field) {
      $field['class'][] = 'form-group';
      $field['input_class'][] = 'form-control';
    }
  }
  return $fields;
}

/**
 *
 * Bootstrap row count for products list
 *
 */
 function basic_store_woocommerce_bootstrap_row_count($columns) {

   switch ($columns) {
   	case '2':
   		return $bootstrap_col_size = 'col-sm-6';
   		break;
   	case '3':
   		return $bootstrap_col_size = 'col-sm-4';
   		break;
   	case '4':
   		return $bootstrap_col_size = 'col-sm-3';
   		break;
   	case '6':
   		return $bootstrap_col_size = 'col-sm-2';
   		break;
   	default:
       return $bootstrap_col_size = 'col-sm-12';
   }

}


/*
* Change Placeholder images for products
* https://docs.woocommerce.com/document/change-the-placeholder-image/
*
**/
function basic_store_custom_fix_thumbnail() {

  add_filter('woocommerce_placeholder_img_src', 'basic_store_custom_woocommerce_placeholder_img_src');

	function basic_store_custom_woocommerce_placeholder_img_src( $src ) {
  	return get_template_directory_uri() . '/assets/img/woo-placeholder.png';
	}

}
add_action( 'init', 'basic_store_custom_fix_thumbnail' );


/**
*
* Replace "Cart" menu item content
*
*/
function basic_store_nav_replace_wpse_189788($item_output, $item) {

  if ( ! class_exists( 'WooCommerce' ) ) {
    return $item_output;
  }

  // "Cart" menu item
  global $woocommerce;

  if ( $item->url == wc_get_cart_url() ) {

    ob_start();

    ?>

    <a href="<?php echo esc_url(wc_get_cart_url()); ?>">

      <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
      <span class=""><?php _e('Cart','basicstore'); ?></span>

      <span class="cart-badge-wrapper">
        <?php if ($woocommerce->cart->cart_contents_count) : ?>
          <span class="badge"><span><?php echo esc_html($woocommerce->cart->cart_contents_count); ?></span></span>
        <?php endif; ?>
      </span>

    </a>

    <?php

    return ob_get_clean();

  }

  return $item_output;

}

add_filter('walker_nav_menu_start_el','basic_store_nav_replace_wpse_189788',10,2);


/**
*
* Ensure cart contents update when products are added to the cart via AJAX
* Used in conjunction with https://gist.github.com/DanielSantoro/1d0dc206e242239624eb71b2636ab148
*
*/
function basic_store_woocommerce_header_add_to_cart_fragment( $fragments ) {

  if ( ! class_exists( 'WooCommerce' ) ) {
    return;
  }

	global $woocommerce;

	ob_start();

	?>

  <span class="cart-badge-wrapper">
    <?php if ($woocommerce->cart->cart_contents_count) : ?>
      <span class="badge"><span><?php echo esc_html($woocommerce->cart->cart_contents_count); ?></span></span>
    <?php endif; ?>
  </span>

	<?php $fragments['.cart-badge-wrapper'] = ob_get_clean();

	return $fragments;

}

add_filter('woocommerce_add_to_cart_fragments', 'basic_store_woocommerce_header_add_to_cart_fragment');


/**
*
* Update navbar toggle class if we have items on cart
*
*/
function basic_store_navbar_toggle_check_cart_items_fragment( $fragments ) {

  if ( ! class_exists( 'WooCommerce' ) ) {
    return;
  }

	global $woocommerce;

  ob_start();

	?>

  <?php if ($woocommerce->cart->cart_contents_count) : ?>
    <span class="icon-bar has-cart-items"></span>
  <?php else: ?>
    <span class="icon-bar"></span>
  <?php endif; ?>

	<?php $fragments['#site-header .navbar-toggle .icon-bar'] = ob_get_clean();

	return $fragments;

}
add_filter('woocommerce_add_to_cart_fragments', 'basic_store_navbar_toggle_check_cart_items_fragment');


// navbar toggle check if cart has items
function basic_store_navbar_toggle_check_cart_items() {

  if ( ! class_exists( 'WooCommerce' ) ) {
    return;
  }

  global $woocommerce;

  if ($woocommerce->cart->cart_contents_count) {
    return "has-cart-items";
  } else {
    return;
  }

}

// Limit number of products on cross sells display
if ( ! function_exists( 'basic_store_cartCrossSellTotal' ) ) :
  function basic_store_cartCrossSellTotal($total) {
  	$total = '2';
  	return $total;
  }
endif;
add_filter('woocommerce_cross_sells_total', 'basic_store_cartCrossSellTotal');
