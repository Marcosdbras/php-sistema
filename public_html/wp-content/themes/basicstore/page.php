<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package basicstore
 */

// check to see if Site Sidebar is published to adjust bootstrap column size
$bootstrap_col_size = is_active_sidebar( 'sidebar-site' ) ? "col-md-9" : "col-md-12";

get_header(); ?>

	<div id="primary" class="content-area <?php echo esc_attr($bootstrap_col_size); ?>">

		<main id="main" class="site-main" >

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->

	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
