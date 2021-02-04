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
                                    <h3 class="box-title"><?php echo lang('escalas_falta'); ?></h3>
                                    <p><cite><?php echo lang('escalas_falta_description'); ?></cite></p>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-falta_escala')); ?>
                                        <?php echo form_hidden('escala_id', $escala->id);?>
                                        <div class="form-group">
                                            <?php echo lang('escalas_profissional', 'profissional', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo($escala->nome_profissional);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_setor', 'setor', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo($escala->nomesetor);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_escala', 'escala', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo(date('d/m/Y', strtotime($escala->dataplantao)) . " - " . date('H:i', strtotime($escala->horainicialplantao)) . " &agrave;s " . date('H:i', strtotime($escala->horafinalplantao)));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_tipofalta', 'tipofalta', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo(form_dropdown($tipofalta));?>
                                            </div>
                                            <span><cite>A justificativa só precisa ser preenchida no caso de falta justificada.</cite></span>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_justificativa', 'justificativa', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-6">
                                                <?php echo(form_textarea($justificativa));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_save'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/escalas/conferencia', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
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
