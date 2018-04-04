<?php
/**
 * The sidebar containing the main widget area
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @version 1.4.0
 * @since   1.4.0
 * @package basicstore
 */

if (
	! is_active_sidebar( 'sidebar-site' ) && ! is_front_page() ||
	! is_active_sidebar( 'sidebar-frontpage' ) && is_front_page()
) {
	return;
}
?>

<aside id="secondary" class="widget-area col-md-3">
	<?php if ( is_front_page() ): ?>
		<?php dynamic_sidebar( 'sidebar-frontpage' ); ?>
	<?php else: ?>
		<?php dynamic_sidebar( 'sidebar-site' ); ?>
	<?php endif; ?>


</aside><!-- #secondary -->
