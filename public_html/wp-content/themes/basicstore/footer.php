<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package basicstore
 */

?>

			</div><!-- .row -->

		</div><!-- .container -->

	</section><!-- #site-content -->


	<footer id="site-footer" class="">

		<div class="container">

			<?php if (is_active_sidebar('sidebar-footer')) : ?>

			 <div id="footer-widgets">

				 <div class="row">

					 <?php dynamic_sidebar('Footer'); ?>

				 </div><!-- .row -->

			 </div><!-- #footer-widgets -->

			<hr>

			<?php endif; ?>

			<div id="site-copyright" class="text-center">

				<p><?php echo esc_html ('&#169;' ); ?> <?php echo esc_html (date('Y') ); ?> <?php echo get_bloginfo( 'name' ); ?>. <?php _e("All Rights Reserved","basicstore"); ?></p>

				<p><?php printf( esc_html__( 'Proudly powered by %1$s', 'basicstore' ), '<a href="https://wordpress.org" target="_blank">WordPress</a>' ); ?>

				<span class="sep"> | </span>

				<?php printf( esc_html__( 'Theme: %1$s by %2$s', 'basicstore' ), 'BasicStore', '<a href="https://wpcodefactory.com/author/theme-al/" target="_blank">Theme.al</a>' ); ?></p>

			</div><!-- .site-info -->

		</div><!-- .container -->

	</footer><!-- #site-footer -->


<?php wp_footer(); ?>


</body>
</html>
