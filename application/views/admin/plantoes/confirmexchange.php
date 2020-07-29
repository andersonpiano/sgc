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
                                    <h3 class="box-title"><?php echo lang('plantoes_confirm'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo $message;?>

                                    <?php echo form_open(uri_string(), array('class' => 'form-horizontal', 'id' => 'form-edit_plantao')); ?>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_unidadehospitalar', 'unidadehospitalar', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4 ">
                                                <?php echo($plantao->setor->unidadehospitalar->razaosocial);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_setor', 'setor', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo($plantao->setor->nome);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_dataplantao', 'dataplantao', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo date('d/m/Y', strtotime($plantao->dataplantao));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_horainicialplantao', 'horainicialplantao', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo date('H:i:s', strtotime($plantao->horainicialplantao));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_horafinalplantao', 'horafinalplantao', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo date('H:i:s', strtotime($plantao->horafinalplantao));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_duracao', 'duracao', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo $plantao->duracao . 'h';?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_tipopassagem', 'tipopassagem', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo $tipospassagem[$plantao->tipopassagem];?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_profissional', 'profissionaltitular_id', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo($plantao->profissional->nome);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_profissional_substituto', 'profissionalsubstituto_id', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo $plantao->profissionalsubstituto->nome;?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_escalatroca', 'escalatroca_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-10">
                                                <?php echo form_dropdown($escalatroca_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <?php echo form_hidden('id', $plantao->id);?>
                                                <?php echo form_hidden($csrf); ?>
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_confirm'))); ?>
                                                    <?php echo anchor('admin/plantoes', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
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
