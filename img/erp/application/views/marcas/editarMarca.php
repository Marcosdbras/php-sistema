<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="icon-align-justify"></i>
                </span>
                <h5>Editar Marca</h5>
            </div>
            <div class="widget-content nopadding">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formMarca" method="post" class="form-horizontal" >
                     <div class="control-group">
                        <?php echo form_hidden('id',$result->id) ?>
                        <label for="descricao" class="control-label">Descrição<span class="required">*</span></label>
                        <div class="controls">
                            <input id="descricao" type="text" name="descricao" value="<?php echo $result->descricao; ?>"  />
                        </div>
                    </div>


                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3">
                                <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> Alterar</button>
                                <a href="<?php echo base_url() ?>index.php/marcas" id="" class="btn"><i class="icon-arrow-left"></i> Voltar</a>
                            </div>
                        </div>
                    </div>


                </form>
            </div>

         </div>
     </div>
</div>


<script src="<?php echo base_url()?>js/jquery.validate.js"></script>
<script src="<?php echo base_url();?>js/maskmoney.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        
        $(".money").maskMoney({ decimal: ",", thousands: "" });

        $('#formMarca').validate({
            rules :{
                  descricao: { required: true}
            },
            messages:{
                  descricao: { required: 'Campo Requerido.'}
            },

            errorClass: "help-inline",
            errorElement: "span",
            highlight:function(element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
           });
    });
</script>






