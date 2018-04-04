<?php 
$shopress_calltoaction_enable = get_theme_mod('shopress_calltoaction_enable',true);
if($shopress_calltoaction_enable){
$shopress_calltoaction_background = get_theme_mod('shopress_calltoaction_background','');
$shopress_calltoaction_color = get_theme_mod('shopress_calltoaction_color');
$shopress_calltoaction_button_one_label = get_theme_mod('shopress_calltoaction_button_one_label','Shop Now');
$shopress_calltoaction_button_one_link = get_theme_mod('shopress_calltoaction_button_one_link','#');
$shopress_calltoaction_button_one_target = get_theme_mod('shopress_calltoaction_button_one_target','true');
?>
<!--==================== CALL TO ACTION SECTION ====================-->
<?php if($shopress_calltoaction_background != '') { ?>

<section class="ta-calltoaction" style="background-image:url('<?php echo $shopress_calltoaction_background;?>');">
<?php } else { ?>
<section class="ta-calltoaction">
  <?php } ?>
  <div class="overlay" style="background-color:<?php echo $shopress_calltoaction_color;?>">
    <div class="container">
      <div class="row">
        <div class="col-md-9 col-sm-9">
          <div class="ta-calltoaction-box-info">
            <?php $shopress_calltoaction_title = get_theme_mod('shopress_calltoaction_title',__('Best and Affordable','shopress'));
          
            if( !empty($shopress_calltoaction_title) ):

              echo '<h5>'.$shopress_calltoaction_title.'</h5>';

            endif; ?>
          </div>
        </div>
        <div class="col-md-2 col-sm-2"> 
          <a href="<?php echo $shopress_calltoaction_button_one_link; ?>" <?php if( $shopress_calltoaction_button_one_target == true) { echo "target='_blank'"; } ?>  class="btn btn-theme">
          <?php echo $shopress_calltoaction_button_one_label; ?>
          </a> 
        </div>
      </div>
    </div>
  </div>
</section>
<?php } ?>