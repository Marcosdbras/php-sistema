<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="icon-align-justify"></i>
                </span>
                <h5>Editar Produto</h5>
            </div>
            <div class="widget-content nopadding">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formProduto" method="post" class="form-horizontal" >
                    <div class="control-group">
                        <?php echo form_hidden('idProdutos', $result->idProdutos) ?>
                        <label for="descricao" class="control-label">Descrição<span class="required">*</span></label>
                        <div class="controls">
                            <input id="descricao" type="text" name="descricao" value="<?php echo $result->descricao; ?>"  />
                        </div>
                    </div>


                    <div class="control-group">
                        <label for="unidade" class="control-label">Unidade<span class="required">*</span></label>    
                        <div class="controls">
                            <select class="form-control" id="unidade"  name="unidade"  >

                                <?php
                                $this->db->select('idusumestre, iddetalhe, descricao, desc_reduzida');
                                $this->db->from('unidades');
                                $this->db->where('idusumestre', $this->session->userdata('idusumestre'));
                                $unidades = $this->db->get();
                                
                                if (!$unidades->result() || $result->idunidade == 0) {

                                   echo '<option selected="selected"></option>';
                               
                                }else{
                                    
                                   echo '<option></option>';
                                    
                                } 

                                foreach ($unidades->result() as $und) {
                                    if ($result->unidade == $und->desc_reduzida) {
                                        echo '<option selected="selected">' . $und->desc_reduzida . '</option>';
                                    } else {
                                        echo '<option>' . $und->desc_reduzida . '</option>';
                                    }
                                }
                                
                                ?>

                            </select>
                        </div>    
                    </div>                   

                    <!--
                    <div class="control-group">
                        <label for="unidade" class="control-label">Unidade<span class="required">*</span></label>
                        <div class="controls">
                            <input id="unidade" type="text" name="unidade" value="<?php //echo $result->unidade;   ?>"  />
                        </div>
                    </div>
                    -->

                    <div class="control-group">
                        <label for="marca" class="control-label">Marca</label>    
                        <div class="controls">
                            <select class="form-control" id="marca"  name="marca"  >

                                <?php
                                $this->db->select('idusumestre, iddetalhe, descricao, id');
                                $this->db->from('marcas');
                                $this->db->where('idusumestre', $this->session->userdata('idusumestre'));
                                $marcas = $this->db->get();

                                if (!$marcas->result() || $result->idmarca == 0) {

                                   echo '<option value="0" selected="selected"></option>';
                               
                                }else{
                                    
                                   echo '<option value="0" ></option>';
                                    
                                }                           
                                

                                foreach ($marcas->result() as $mar) {


                                        if ($result->idmarca == $mar->id) {

                                            echo '<option value="' . $mar->id . '"  selected="selected">' . $mar->descricao . '</option>';
                                        } else {

                                            echo '<option value="' . $mar->id . '">' . $mar->descricao . '</option>';
                                        }
                                  
                                }
  
                                ?>

                            </select>
                        </div>    
                    </div>


                    <div class="control-group">
                        <label for="grupo" class="control-label">Grupo</label>    
                        <div class="controls">
                            <select class="form-control" id="grupo"  name="grupo"  >

                                <?php
                                $this->db->select('idusumestre, iddetalhe, descricao, id');
                                $this->db->from('grupos');
                                $this->db->where('idusumestre', $this->session->userdata('idusumestre'));
                                $grupos = $this->db->get();

                                if (!$grupos->result() || $result->idgrupo == 0) {

                                   echo '<option value="0" selected="selected"></option>';
                               
                                }else{
                                    
                                   echo '<option value="0" ></option>';
                                    
                                }                           
                                

                                foreach ($grupos->result() as $gru) {


                                        if ($result->idgrupo == $gru->id) {

                                            echo '<option value="' . $gru->id . '"  selected="selected">' . $gru->descricao . '</option>';
                                        } else {

                                            echo '<option value="' . $gru->id . '">' . $gru->descricao . '</option>';
                                        }
                                  
                                }
  
                                ?>

                            </select>
                        </div>    
                    </div>  

                    <div class="control-group">
                        <label for="precoCompra" class="control-label">Preço de Compra<span class="required">*</span></label>
                        <div class="controls">
                            <input id="precoCompra" class="money" type="text" name="precoCompra" value="<?php echo number_format($result->precoCompra, 2, ',', ''); ?>"  />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="precoVenda" class="control-label">Preço de Venda<span class="required">*</span></label>
                        <div class="controls">
                            <input id="precoVenda" class="money" type="text" name="precoVenda" value="<?php echo number_format($result->precoVenda, 2, ',', ''); ?>"  />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="estoque" class="control-label">Estoque<span class="required">*</span></label>
                        <div class="controls">
                            <input id="estoque" type="text" name="estoque" value="<?php echo round($result->estoque, 0); ?>"  />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="estoqueMinimo" class="control-label">Estoque Mínimo</label>
                        <div class="controls">
                            <input id="estoqueMinimo" type="text" name="estoqueMinimo" value="<?php echo round($result->estoqueMinimo, 0); ?>"  />
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3">
                                <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> Alterar</button>
                                <a href="<?php echo base_url() ?>index.php/produtos" id="" class="btn"><i class="icon-arrow-left"></i> Voltar</a>
                            </div>
                        </div>
                    </div>


                </form>
            </div>

        </div>
    </div>
</div>


<script src="<?php echo base_url() ?>js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>js/maskmoney.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $(".money").maskMoney({decimal: ",", thousands: ""});

        $('#formProduto').validate({
            rules: {
                descricao: {required: true},
                unidade: {required: true},
                precoCompra: {required: true},
                precoVenda: {required: true},
                estoque: {required: true}
            },
            messages: {
                descricao: {required: 'Campo Requerido.'},
                unidade: {required: 'Campo Requerido.'},
                precoCompra: {required: 'Campo Requerido.'},
                precoVenda: {required: 'Campo Requerido.'},
                estoque: {required: 'Campo Requerido.'}
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function (element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
        });
    });
</script>






