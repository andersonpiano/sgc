<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <?php if (isset($message) or $this->session->flashdata('message')) : ?>
                <section class="content-header">
                    <div class="alert bg-warning alert-dismissible" role="alert">
                        <?php echo(isset($message) ? $message : '');?>
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
                                    <h3 class="box-title"><?php echo lang('valoresplantoes_create'); ?></h3>
                                </div>
                                <div class="box-body">

                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-create_valoresplantoes')); ?>
                                        <div class="form-group">
                                            <?php echo lang('valoresplantoes_unidadehospitalar', 'unidadehospitalar_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($unidadehospitalar_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('valoresplantoes_setor', 'setor_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($setor_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('valoresplantoes_semanadia', 'semanadia', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-1">
                                                <?php echo form_input($semanadia);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('valoresplantoes_semananoite', 'semananoite', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-1">
                                                <?php echo form_input($semananoite);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('valoresplantoes_sextanoite', 'sextanoite', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-1">
                                                <?php echo form_input($sextanoite);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('valoresplantoes_sabadodomingo', 'sabadodomingo', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-1">
                                                <?php echo form_input($sabadodomingo);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('valoresplantoes_feriados', 'feriados', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-1">
                                                <?php echo form_input($feriados);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('valoresplantoes_datasespeciais', 'datasespeciais', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-1">
                                                <?php echo form_input($datasespeciais);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_submit'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/valoresplantoes', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
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
