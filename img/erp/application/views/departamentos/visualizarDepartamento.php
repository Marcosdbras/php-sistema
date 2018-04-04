<div class="accordion" id="collapse-group">
    <div class="accordion-group widget-box">
        <div class="accordion-heading">
            <div class="widget-title">
                <a data-parent="#collapse-group" href="#collapseGOne" data-toggle="collapse">
                    <span class="icon"><i class="icon-list"></i></span><h5>Dados do Departamento</h5>
                </a>
            </div>
        </div>
        <div class="collapse in accordion-body">
            <div class="widget-content">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td style="text-align: right; width: 30%"><strong>Descrição</strong></td>
                            <td><?php echo $result->descricao ?></td>
                        </tr>
                  
                        <tr>
                            <td style="text-align: right; width: 30%"><strong>Ponto de atendimento</strong></td>
                            <td><?php echo $result->desc_ponto_atendimento ?></td>
                        </tr>

                        <tr>
                            <td style="text-align: right; width: 30%"><strong>Posição Inicial</strong></td>
                            <td><?php echo $result->ponto_inicial ?></td>
                        </tr>

                        <tr>
                            <td style="text-align: right; width: 30%"><strong>Posição Final</strong></td>
                            <td><?php echo $result->ponto_final ?></td>
                        </tr>
                        
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

