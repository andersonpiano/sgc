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

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('justificativas_edit'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(uri_string(), array('class' => 'form-horizontal', 'id' => 'form-edit_justificativas')); ?>
                                        <?php echo(form_hidden('profissional_id', $profissional_id)); ?>
                                        <?php echo form_hidden('id', $justificativa->id);?>
                                        <?php echo form_hidden($csrf); ?>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_profissional', 'profissional_nome', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo($profissional_nome);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_cooperativa', 'cooperativa', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo('CEMERGE');?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_setor', 'setor_id', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo(form_dropdown($setor_id));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_data_plantao', 'data_plantao', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-3">
                                                <?php echo(form_input($data_plantao));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_hora_entrada', 'hora_entrada', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-3">
                                                <?php echo(form_input($hora_entrada));?>
                                            </div>
                                            <span><cite><?php echo('Preencha caso esteja justificando o horário de entrada.'); ?></cite></span>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_hora_saida', 'hora_saida', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-3">
                                                <?php echo(form_input($hora_saida));?>
                                            </div>
                                            <span><cite><?php echo('Preencha caso esteja justificando o horário de saída.'); ?></cite></span>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_descricao', 'descricao', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-6">
                                                <?php echo(form_textarea($descricao));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_recusa', 'motivo_recusa', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-6">
                                                <?php echo(form_textarea($motivo_recusa));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_save'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/justificativas', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php echo form_close();?>
                                </div>
                            </div>
                         </div>
                    </div>
                </section>
            </div>
