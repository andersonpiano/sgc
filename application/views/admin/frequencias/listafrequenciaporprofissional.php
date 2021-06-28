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
                                    <h3 class="box-title"><?php echo lang('frequencias_find'); ?></h3>
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
                                            <?php echo lang('frequencias_datainicialplantao', 'datainicial', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($datainicial);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('frequencias_datafinalplantao', 'datafinal', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($datafinal);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('id' => 'btn_submit', 'name' => 'btn_submit', 'type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_find'))); ?>
                                                    <a href="<?php echo(current_url()); ?>" onclick="window.print(); return false;" class="btn btn-default btn-flat">Imprimir</a>&nbsp;
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/frequencias/buscarfrequenciaporprofissional', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
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
                <div class="print-header row">
                        <div class="col-lg-2 col-xs-2"><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/></div>
                        <div class="col-lg-10 col-xs-10 pull-right"><h3>Lista frequência por Profissional</h3></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <?php foreach ($frequencias as $profissional => $freqs) : ?>
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <?php echo(lang('frequencias_profissional') . ": " . $profissional); ?>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="w-20"><?php echo lang('frequencias_setor');?></th>
                                                <th class="text-center w-10"><?php echo lang('frequencias_dataplantao');?></th>
                                                <th class="text-center w-10"><?php echo lang('frequencias_horafrequencia');?></th>
                                                <th class="text-center w-10"><?php echo('Status / Tipo');?></th>
                                                <th class="text-center w-20 dontprint"><?php echo lang('frequencias_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php foreach ($freqs as $freq) : ?>
                                        <?php
                                        $tipo_batida_escala = 'Sem escala';
                                        if($this->input->post('datainicial') <= '2021-06-20'){
                                            if ($freq->tipo_batida == 1) {
                                                $tipo_batida_escala = 'Entrada';
                                            } else if ($freq->tipo_batida == 2) {
                                                $tipo_batida_escala = 'Saída';
                                            }
                                        } else {
                                            if ($freq->tipobatida == 1) {
                                                $tipo_batida_escala = 'Entrada';
                                            } else if ($freq->tipobatida == 2) {
                                                $tipo_batida_escala = 'Saída';
                                            }
                                        }
                                        if($this->input->post('datainicial') <= '2021-06-20'){
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($freq->nm_set, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars(date('d/m/Y', strtotime($freq->dt_frq)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars(date('H:i:s', strtotime($freq->dt_frq)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center <?php echo($freq->escala_id ? 'bg-success' : 'bg-danger'); ?>"><?php echo($tipo_batida_escala); ?></td>
                                                <td class="dontprint text-center">
                                                    <?php echo anchor('admin/frequencias/editarfrequencia/'.$freq->cd_ctl_frq, lang('actions_edit'), array('class' => 'btn btn-primary btn-flat', 'target' => '_blank')); ?>
                                                </td>
                                            </tr>
                                        <?php } else { ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($freq->setor_nome_temp, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars(date('d/m/Y', strtotime($freq->datahorabatida)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars(date('H:i:s', strtotime($freq->datahorabatida)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center <?php echo($freq->escala_id ? 'bg-success' : 'bg-danger'); ?>"><?php echo($tipo_batida_escala); ?></td>
                                                <td class="dontprint text-center">
                                                    <?php echo anchor('admin/frequencias/editarfrequencia_nova/'.$freq->frequencia_id, lang('actions_edit'), array('class' => 'btn btn-primary btn-flat', 'target' => '_blank')); ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
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