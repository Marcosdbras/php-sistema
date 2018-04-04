<?php
/**
 * The template part for displaying slider
 *
 * @package VW Hospital Lite
 * @subpackage vw-hospital-lite
 * @since VW Hospital Lite 1.0
 */
?>
<div class="col-md-4 col-sm-4">
	<div id="post-<?php the_ID(); ?>" <?php post_class('inner-service'); ?>>	
		<div class="services-box">
	    	<div class="page-box">
		      	<h4><a href="<?php echo esc_url(get_permalink()); ?>" title="<?php the_title_attribute(); ?>"><?php the_title();?></a></h4> 
		      	<div class="date-box"><?php the_time( get_option( 'date_format' ) ); ?></div>            
		      	<div class="box-image">
			        <?php 
			          	if(has_post_thumbnail()) { 
			            	the_post_thumbnail(); 
			          	}
			        ?>      
			     </div>    
		      	<div class="box-content">
		        	<?php the_excerpt();?>
		      	</div>            
		      	<div class="cat-box">
		        	<?php foreach((get_the_category()) as $category) { echo esc_html($category->cat_name) . ' '; } ?>
		      	</div>
		    </div>
	    </div>
    </div>
</div>