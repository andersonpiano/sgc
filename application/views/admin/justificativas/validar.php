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
                                    <h3 class="box-title"><?php echo lang('justificativas_create'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-create_justificativa')); ?>
                                        <?php echo(form_hidden('profissional_id', $profissional_id)); ?>
                                        <?php echo(form_hidden('setor_id', $setor_id)); ?>
                                        <?php echo(form_hidden('data_plantao', $data_plantao)); ?>
                                        <?php echo(form_hidden('escala_id', $escala_id)); ?>
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
                                            <?php echo lang('justificativas_setor', 'setor_nome', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo($setor_nome);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_data_plantao', 'data_plantao', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-3">
                                                <?php echo(date('d/m/Y', strtotime($data_plantao)));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_hora_entrada', 'hora_entrada', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-2">
                                                <?php echo(form_input($hora_entrada));?>
                                            </div>
                                            <span><cite><?php echo('Preencha caso esteja justificando o horário de entrada.'); ?></cite></span>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_hora_saida', 'hora_saida', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-2">
                                                <?php echo(form_input($hora_saida));?>
                                            </div>
                                            <span><cite><?php echo('Preencha caso esteja justificando o horário de saída.'); ?></cite></span>
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
