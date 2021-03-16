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
                                    <h3 class="box-title"><?php echo lang('faltas_find'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo isset($message) ? $message : '';?>

                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-find_falta')); ?>
                                        <div class="form-group">
                                            <?php echo lang('faltas_unidadehospitalar', 'unidadehospitalar_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($unidadehospitalar_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('faltas_profissional', 'profissional_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($profissional_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('faltas_data_inicial', 'data_inicial', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($data_inicial);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('faltas_data_final', 'data_final', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($data_final);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_find'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/faltas', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
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
                                    <h3 class="box-title">
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('faltas_profissional');?></th>
                                                <th><?php echo lang('faltas_setor');?></th>
                                                <th><?php echo lang('faltas_data_plantao');?></th>
                                                <th><?php echo lang('faltas_turno_plantao');?></th>
                                                <th><?php echo lang('faltas_tipo_falta');?></th>
                                                <th><?php echo lang('faltas_justificativa');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($faltas as $falta):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($falta->profissional_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($falta->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($falta->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($falta->horainicialplantao, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($falta->tipo_falta==1 ? 'Justificada' : 'Não justificada', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($falta->justificativa ? $falta->justificativa : '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/faltas/view/'.$falta->id, lang('actions_see'), array('class' => 'btn btn-default btn-flat')); ?>
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
