<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
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
                                    <h3 class="box-title"><?php echo lang('escalas_buscaroportunidades'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-buscarcessoesetrocas')); ?>
                                        <div class="form-group">
                                            <?php echo lang('escalas_datainicialplantao', 'data_inicial', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_input($data_inicial);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_datafinalplantao', 'data_final', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_input($data_final);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_status_oportunidade', 'status', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_dropdown($status);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_find'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/escalas/listarcessoesetrocas', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
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
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Resultados</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('escalas_setor');?></th>
                                                <th><?php echo lang('escalas_profissional');?></th>
                                                
                                                <th class="text-center"><?php echo lang('escalas_dataplantao');?></th>
                                                <th class="text-center"><?php echo lang('escalas_turno');?></th>
                                                <th class="text-center"><?php echo lang('escalas_status_passagem');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
    <?php foreach ($oportunidade as $op) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($op->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($op->profissional_passagem_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                
                                                <td class="text-center"><?php echo htmlspecialchars(date('d/m/Y', strtotime($op->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($op->turno, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($op->status_oportunidade, ENT_QUOTES, 'UTF-8'); ?></td>
                                                
                                                <td>
                                                    <?php echo ($op->status_oportunidade != 'Confirmada') ? anchor('admin/escalas/confirmaroportunidade/'.$op->id, lang('actions_accept'), array('class' => 'btn btn-primary btn-flat', 'target' => '_blank', 'onclick' => 'return confirm(\'Tem certeza que deseja aceitar essa oportunidade?\');')) . '&nbsp;': ''; ?>
                                                    <?php echo ($op->status_oportunidade == 'Confirmada') ? anchor('admin/escalas/cancelarcessaotroca/'.$op->id, lang('actions_cancel'), array('class' => 'btn btn-primary btn-flat', 'target' => '_blank', 'onclick' => 'return confirm(\'Tem certeza que deseja cancelar essa oportunidade?\');')) . '&nbsp;': ''; ?>
                                                    <!--<?php //echo anchor('admin/escalas/cancelarcessaotroca/'.$op->id, lang('actions_delete'), array('class' => 'btn btn-danger btn-flat', 'target' => '_blank', 'onclick' => 'return confirm(\'Tem certeza que deseja apagar a cessão ou troca?\');')) . '&nbsp;'; ?>-->
                                                </td>
                                            </tr>
    <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>