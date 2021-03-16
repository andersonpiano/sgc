<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('frequencias_edit'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo $message;?>

                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-edit_frequencias')); ?>
                                        <?php echo form_hidden('id', $frequencia->CD_CTL_FRQ);?>
                                        <div class="form-group">
                                            <?php echo lang('frequencias_profissional', 'profissional', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo($frequencia->profissional->nome);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('frequencias_frequencia', 'frequencia', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo(date('d/m/Y H:i:s', strtotime($frequencia->DT_FRQ)));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('frequencias_unidadehospitalar', 'unidadehospitalar_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_dropdown($unidadehospitalar_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('frequencias_setor', 'setor_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_dropdown($setor_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('frequencias_tipobatida', 'tipo_batida', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_dropdown($tipo_batida);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_save'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/frequencias', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
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
