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
                        <?php echo($this->session->flashdata('message'));?>
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
                                    <h3 class="box-title"><?php echo lang('plantoes_tooffer'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(uri_string(), array('class' => 'form-horizontal', 'id' => 'form-edit_plantao')); ?>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_unidadehospitalar', 'unidadehospitalar', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4 ">
                                                <?php echo($plantao->unidadehospitalar_razaosocial);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_setor', 'setor', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo($plantao->setor_nome);?>
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
                                            <?php echo lang('plantoes_tipopassagem', 'tipopassagem', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($tipopassagem);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_profissional', 'profissionaltitular_id', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php
                                                if (isset($plantao->profissional_substituto_nome)) {
                                                    echo($plantao->profissional_substituto_nome);
                                                } else {
                                                    echo($plantao->profissional_passagem_nome);
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_profissional_substituto', 'profissionalsubstituto_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($profissionalsubstituto_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_frequencias_disponiveis', 'frequencias_disponiveis', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($frequencias_disponiveis);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <?php echo form_hidden('id', $plantao->id);?>
                                                <?php echo form_hidden($csrf); ?>
                                                <?php echo form_hidden('data_plantao', $plantao->dataplantao); ?>
                                                <?php echo form_hidden('hora_inicial_plantao', $plantao->horainicialplantao); ?>
                                                <?php echo form_hidden('unidadehospitalar_id', $plantao->unidadehospitalar_id); ?>
                                                <?php echo form_hidden('setor_id', $plantao->setor_id); ?>
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
