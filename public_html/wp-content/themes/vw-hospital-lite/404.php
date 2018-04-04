<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package VW Hospital Lite
 */

get_header(); ?>
	<div id="content-vw">
		<div class="container">
            <div class="page-content">		
				<div class="col-md-12">
					<h1><?php the_title();?></h1>
					<h3><?php esc_html_e( '<strong>404</strong> Not Found', 'vw-hospital-lite' ); ?></h3>
					<p class="text-404"><?php esc_html_e( 'Looks like you have taken a wrong turn&hellip', 'vw-hospital-lite' ); ?></p>
					<p class="text-404"><?php esc_html_e( 'Don\'t worry&hellip it happens to the best of us.', 'vw-hospital-lite' ); ?></p>
					<div class="read-moresec">
                		<div><a href="<?php echo esc_url( home_url() ); ?>" class="button hvr-sweep-to-right"><?php esc_html_e( 'Back to Home Page', 'vw-hospital-lite' ); ?></a></div>
 					</div>
				</div>
				<div class="clearfix"></div>
            </div>
        <div class="clearfix"></div>
		</div>
	</div>
<?php get_footer(); ?>