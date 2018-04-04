<?php
/**
 * Template Name: Front Page
 * The template used for displaying fullwidth page content in fullwidth.php
 *
 * @version 1.3.7
 * @since   1.0.0
 * @package basicstore
 */
 // check to see if Site Sidebar is published to adjust bootstrap column size
$bootstrap_col_size = is_active_sidebar( 'sidebar-frontpage' ) ? "col-md-9" : "col-md-12";

get_header(); ?>

	<div id="primary" class="content-area <?php echo esc_attr($bootstrap_col_size); ?>">

		<main id="main" class="site-main" >

			<?php
				$latest_product = get_theme_mod( 'latest_product_disable', '1' );
				if( $latest_product == '1' ) :
			?>
				<h2><?php _e('Latest Products' , 'basicstore' ); ?> </h2>
				<?php echo do_shortcode('[recent_products per_page="4" columns="4"]'); ?>
			<?php
				endif;
			?>
			<?php
				$featured_product = get_theme_mod( 'featured_product_disable', '1' );
				if( $featured_product == '1' ) :
			?>
				<h2><?php _e('Featured Products' , 'basicstore' ); ?> </h2>
				<?php echo do_shortcode('[featured_products per_page="4" columns="4"]'); ?>
			<?php
				endif;
			?>
			<?php
				$top_rated_product = get_theme_mod( 'top_rated_product_disable', '1' );
				if( $top_rated_product == '1' ) :
			?>
				<h2><?php _e('Top Rated Products' , 'basicstore' ); ?> </h2>
				<?php echo do_shortcode('[top_rated_products per_page="4" columns="4"]'); ?>
			<?php
				endif;
			?>
			<?php
				$product_category = get_theme_mod( 'product_category_disable', '1' );
				if( $product_category == '1' ) :
			?>
				<h2><?php _e('Product Categories' , 'basicstore' ); ?> </h2>
				<?php echo do_shortcode('[product_categories number="6" columns="6" parent="0"]'); ?>
			<?php
				endif;
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();