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
                                    <h3 class="box-title"><?php echo lang('setores_find'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo isset($message) ? $message : '';?>

                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-find_setor')); ?>
                                        <div class="form-group">
                                            <?php echo lang('setores_unidadehospitalar', 'unidadehospitalar_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-10">
                                                <?php echo form_dropdown($unidadehospitalar_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_find'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/setores', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
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
                                        <?php echo anchor('admin/setores/create', '<i class="fa fa-plus"></i> '. 
                                            lang('setores_create'), 
                                            array('class' => 'btn btn-block btn-primary btn-flat')); ?>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('setores_nome');?></th>
                                                <th><?php echo lang('setores_unidadehospitalar');?></th>
                                                <th><center><?php echo lang('setores_vagas');?></center></th>
                                                <th><?php //echo lang('setores_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($setores as $setor):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($setor->nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($setor->unidadehospitalar->razaosocial, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><center><?php echo htmlspecialchars($setor->vagas, ENT_QUOTES, 'UTF-8'); ?></center></td>
                                                <td>
                                                    <?php echo anchor('admin/setores/edit/'.$setor->id, ' '/*lang('actions_edit')*/, array('class' => 'btn btn-primary btn-flat fa fa-edit')); ?> &nbsp;
                                                    <?php echo anchor('admin/setores/view/'.$setor->id, ' '/*lang('actions_see')*/, array('class' => 'btn btn-primary btn-flat fa fa-eye')); ?>
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
