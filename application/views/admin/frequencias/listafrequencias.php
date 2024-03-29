<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
            <script type="text/javascript"> 
                // unblock when ajax activity stops 
                $(document).ajaxStop($.unblockUI); 
            
                $(document).ready(function() { 
                    $('#btn_submit').click(function() { 
                        $.blockUI({
                            message: '<h4><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/loading.gif'); ?>" /> Aguarde</h4>',
                            css: { 
                                width: '40%',
                                left: '30%',
                            }
                        });
                    }); 
                });
            </script>

            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <?php if ($this->session->flashdata('message')) : ?>
                <section class="content-header">
                    <div class="alert bg-warning alert-dismissible" role="alert">
                        <?php echo($this->session->flashdata('message') ? $this->session->flashdata('message') : '');?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </section>
                <?php endif; ?>

                <section class="content findform">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('frequencias_por_setor'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-frequencias_find')); ?>
                                        <div class="form-group">
                                            <?php echo lang('frequencias_unidadehospitalar', 'unidadehospitalar_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_dropdown($unidadehospitalar_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('frequencias_profissional', 'profissional_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_dropdown($profissional_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('frequencias_setor', 'setor_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_dropdown($setor_id);?>
                                            </div>
                                        </div>   
                                        <div class="form-group">
                                            <?php echo lang('frequencias_covid', 'covid', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_dropdown($covid);?>
                                            </div>
                                        </div>                                    
                                        <div class="form-group">
                                            <?php echo lang('frequencias_datainicialplantao', 'datainicial', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_input($datainicial);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('frequencias_datafinalplantao', 'datafinal', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_input($datafinal);?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <?php echo lang('frequencias_tipoescala', 'tipo_plantao', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_dropdown($tipos);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('id' => 'btn_submit', 'name' => 'btn_submit', 'type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_find'))); ?>
                                                    <a href="<?php echo(current_url()); ?>" onclick="window.print(); return false;" class="btn btn-default btn-flat">Imprimir</a>&nbsp;
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/frequencias/listafrequenciasemescala', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
                                                    <a style="float: right; color: white; padding-top: 1px; padding-bottom: 1px; padding-left: 3px; padding-right: 3px;" class="btn btn-primary text-center gera-arquivo"><i class="fa fa-floppy-o fa-2x" aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php echo form_close();?>
                                </div>
                            </div>
                         </div>
                    </div>
                </section>

                <section class="content">
                    <div class="row">
                    
                        <div class="col-md-12">
                            <div class="box" >
                            
                                <?php foreach ($frequencias as $profissional => $freqs) : ?>
                                <div class="box-header with-border">
                                <div class="print-header row">
                                    <div class="col-lg-4 col-xs-4 pull-left"><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/></div>
                                    <div class="col-lg-8 col-xs-8 pull-right"> <h3 style="float: right; font-size:16px;"><?php echo (date('d:m:Y H:i:s')); ?></h3><br><h3><?php echo($this->input->post('covid') == 1 ? $freqs[0]->nomefantasia . " - COVID" : $freqs[0]->nomefantasia); ?></h3></div>
                                    <div class="col-lg-12 col-xs-12"><h3 class="text-center">Periodo: <?php echo date('d/m/Y', strtotime($this->input->post('datainicial'))); ?> a <?php echo date('d/m/Y', strtotime($this->input->post('datafinal'))); ?> Competência: <?php echo date('M/Y', strtotime($this->input->post('datafinal')));?> </h3>
                                    </div>
                                </div>
                                    <h3 class="box-title">
                                        <?php echo(lang('frequencias_profissional') . ": " .  $profissional); ?>
                                    </h3>
                                </div>
                                <div class="box-body"  style="page-break-after:always;">
                                    <table class="table-striped table-sm" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="w-20"><?php echo lang('frequencias_setor');?></th>
                                                <th class="text-center w-10"><?php echo lang('frequencias_dataplantao');?></th>
                                                <th class="text-center w-10"><?php echo lang('frequencias_horafrequencia');?></th>
                                                <th class="text-center w-10"><?php echo('Tipo');?></th>
                                                <th class="text-center w-20 dontprint"><?php echo lang('frequencias_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php foreach ($freqs as $freq) : ?>
                                        <?php
                                        $tipo_batida_escala = 'Sem escala';
                                        if ($freq->tipobatida == 1) {
                                            $tipo_batida_escala = 'Entrada';
                                        } else if ($freq->tipobatida == 2) {
                                            $tipo_batida_escala = 'Saída';
                                        } else if ($freq->tipobatida == 3) {
                                            $tipo_batida_escala = 'Entrada *';
                                        } else if ($freq->tipobatida == 4) {
                                            $tipo_batida_escala = 'Saída *';
                                        } else if ($freq->tipobatida == 5) {
                                            $tipo_batida_escala = 'Entrada **';
                                        } else if ($freq->tipobatida == 6) {
                                            $tipo_batida_escala = 'Saída **';
                                        }
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($freq->nome_setor, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars(date('d/m/Y', strtotime($freq->datahorabatida)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars(date('H:i:s', strtotime($freq->datahorabatida)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center <?php echo($freq->escala_id ? 'bg-success' : 'bg-danger'); ?>"><?php echo($tipo_batida_escala); ?></td>
                                                <td class="dontprint text-center">
                                                    <?php if($freq->tipobatida <= 2){
                                                        echo anchor('admin/frequencias/editarfrequencia_nova/'.$freq->frequencia_id, '<i class="fa fa-pencil" aria-hidden="true"> Editar</i>', array('class' => 'btn btn-primary btn-flat', 'target' => '_blank')); 
                                                    }
                                                    if ($freq->escala_id == null){
                                                        echo '<button style="margin-left: 10px;" class="btn btn-danger fa fa-close btn-excluir-frequencia" frequencia="'.$freq->frequencia_id.'"> Excluir</button>';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                    <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>