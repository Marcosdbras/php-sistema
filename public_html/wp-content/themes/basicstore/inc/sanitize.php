<?php
/**
 * Sanitize.
 *
 * @package basicstore
 */

/**
 * Sanitize Checkbox.
 * @param $input
 * @return int|string
 */
function basicstore_sanitize_checkbox( $input ) {
	if ( $input == 1 ) {
		return 1;
	} else {
		return '';
	}
}
?>