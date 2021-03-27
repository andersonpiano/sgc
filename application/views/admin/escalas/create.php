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
                <div class="print-header row">
                    <div class="col-lg-2 col-xs-2"><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/></div>
                    <div class="col-lg-10 col-xs-10 pull-right"><h3>Create Escala</h3></div>
                </div>
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('escalas_create'); ?></h3>
                                    <p><cite><?php echo lang('escalas_create_description'); ?></cite></p>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-create_escala', 'onsubmit' => 'return confirm(\'A criação da escala apagará toda escala existente nesse mesmo período. Deseja continuar?\');')); ?>
                                        <div class="form-group">
                                            <?php echo lang('escalas_unidadehospitalar', 'unidadehospitalar_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($unidadehospitalar_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_setor', 'setor_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($setor_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_datainicialplantao', 'datainicialplantao', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($datainicialplantao);?>
                                            </div>
                                        </div>
                                        <!--
                                        <div class="form-group">
                                            <?php //echo lang('escalas_datafinalplantao', 'datafinalplantao', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php //echo form_input($datafinalplantao);?>
                                            </div>
                                        </div>
                                        -->

                                        <div class="form-group">
                                            <?php echo lang('escalas_tipoescala', 'tipo', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_dropdown($this->data['tipos']);?>
                                            </div>
                                        </div>

                                        <div id="diaria_div" class="form-group diaria">
                                            <?php echo lang('escalas_diaria', 'diaria', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input(['type' => 'number', 'id' => 'diaria', 'class' => 'form-control']);?>
                                            </div>
                                        </div>

                                        <div id="manha" class="form-group manha">
                                            <?php echo lang('escalas_manha', 'manha', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input(['type' => 'number', 'id' => 'manha', 'class' => 'form-control']);?>
                                            </div>
                                        </div>
                                        <div id="tarde" class="form-group tarde">
                                            <?php echo lang('escalas_tarde', 'tarde', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input(['type' => 'number', 'id' => 'tarde', 'class' => 'form-control']);?>
                                            </div>
                                        </div>
                                        <div id="noite" class="form-group noite">
                                            <?php echo lang('escalas_noite', 'noite', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input(['type' => 'number', 'id' => 'noite', 'class' => 'form-control']);?>
                                            </div>
                                        </div>

                                        <!--
                                        <div class="form-group">
                                            <?php //echo lang('escalas_horainicialplantao', 'horainicialplantao', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php //echo form_input($horainicialplantao);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php //echo lang('escalas_horafinalplantao', 'horafinalplantao', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php //echo form_input($horafinalplantao);?>
                                            </div>
                                        </div> -->
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_save'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/escalas', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
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
