<?php
/**
 * Search Form
 *
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 *
 * @package basicstore
 */
?>

<form role="search" method="get" class="search-form form-inline" action="<?php echo esc_url(home_url( '/' )); ?>">

	 <div class="form-group">

	  <label class="screen-reader-text sr-only"><span><?php echo _x( 'Search for:', 'label', 'basicstore' ) ?></span></label>

	  <input type="search"  class="search-field form-control" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder','basicstore' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for', 'label','basicstore'  ); ?>" />

		<button class="btn btn-link" type="submit" aria-label="<?php echo esc_attr_x( 'Search', 'submit button', 'basicstore' ); ?>" title="<?php echo esc_attr_x( 'Search', 'submit button', 'basicstore' ); ?>">
	      <i class="glyphicon glyphicon-search"></i>
	  </button>

	</div>

</form>
