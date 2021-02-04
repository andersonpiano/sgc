<?php
defined('BASEPATH') OR exit('No direct script access allowed');

setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
date_default_timezone_set( 'America/Sao_Paulo' );
$numero_dia = date('w')*1;
$dia_mes = date('d');
$numero_mes = date('m')*1;
$ano = date('Y');
$dia = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
$mes = array('', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

?>

            <div class="content-wrapper">
                <section class="content-header dontprint">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>
                <?php if ($this->session->flashdata('message')) : ?>
                <section class="content-header dontprint">
                    <div class="alert bg-warning alert-dismissible" role="alert">
                        <?php echo($this->session->flashdata('message') ? $this->session->flashdata('message') : '');?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </section>
                <?php endif; ?>
                <div class="print-header">
                        <img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/>
                    </div>
                <section class="content dontprint">

                    <div class="row dontprint">
                        <div class="col-md-12 dontprint">
                             <div class="box dontprint">
                                <div class="box-header with-border">
                                    <h3 class="box-title dontprint"><?php echo lang('justificativas_find'); ?></h3>
                                </div>
                                <div class="box-body dontprint">
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-find_justificativa')); ?>
                                        <div class="form-group dontprint">
                                            <?php echo lang('justificativas_data_inicio', 'data_plantao_inicio', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo(form_input($data_plantao_inicio));?>
                                            </div>
                                        </div>
                                        <div class="form-group dontprint">
                                            <?php echo lang('justificativas_data_fim', 'data_plantao_fim', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo(form_input($data_plantao_fim));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_status', 'status', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_dropdown($status);?>
                                            </div>
                                        </div>
                                        <div class="form-group dontprint">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat dontprint', 'content' => lang('actions_find'))); ?>
                                                    <td colspan="2" class="text-center"><a href="#" onclick="window.print();" class="btn btn-primary btn-flat dontprint">Imprimir</a></td>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat dontprint', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/justificativas', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat dontprint')); ?>
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
                                    <h3 class="box-title dontprint">
                                        <?php echo anchor('admin/justificativas/create', '<i class="fa fa-plus"></i> '. 
                                            lang('justificativas_create'), 
                                            array('class' => 'btn btn-block btn-primary btn-flat dontprint')); ?>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('justificativas_data_plantao');?></th>
                                                <th><?php echo lang('justificativas_turno');?></th>
                                                <th><?php echo lang('justificativas_setor');?></th>
                                                <th><?php echo lang('justificativas_profissional');?></th>
                                                <th><?php echo lang('justificativas_status');?></th>
                                                <th class="dontprint"><?php echo lang('justificativas_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($justificativas as $justificativa):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($justificativa->data_inicial_plantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($justificativa->turno, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($justificativa->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($justificativa->nome_profissional, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($justificativa->status, ENT_QUOTES, 'UTF-8');?></td>
                                                <td class="dontprint">
                                                    <?php echo anchor('admin/justificativas/view/'.$justificativa->id, lang('actions_see'), array('class' => 'btn btn-primary btn-flat')); ?> &nbsp;
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
            <div class="print-footer text-center">
                        <img class="text-center" src="<?php echo base_url($frameworks_dir . '/cemerge/images/assinatura.png'); ?>"/><br>
                        <span class="text-center"><?php echo $dia[$numero_dia].', '.$dia_mes.' de '.$mes[$numero_mes].' de '.$ano ?></span>
                </div>
