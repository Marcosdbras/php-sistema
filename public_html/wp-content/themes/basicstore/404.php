<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package basicstore
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<main id="main" class="site-main" >

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'basicstore' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p class="text-warning"><?php esc_html_e( 'It looks like nothing was found at this location. You may start from the homepage ', 'basicstore' ); ?></p>
					<p><a class="btn btn-warning" role="button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to homepage', 'basicstore' ); ?></a></p>
				</div><!-- .page-content -->

			</section><!-- .error-404 -->

		</main><!-- #main -->

	</div><!-- #primary -->

<?php

get_footer();
