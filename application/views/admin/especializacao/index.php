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
                                    <h3 class="box-title"><?php echo lang('especializacoes_find'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-find_especializacao')); ?>
                                        <div class="form-group">
                                            <?php echo lang('especializacoes_data_plantao', 'data_plantao', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo(form_input($data_plantao));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_find'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/especializacoes', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
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
                                        <?php echo anchor('admin/especializacoes/create', '<i class="fa fa-plus"></i> '. 
                                            lang('especializacoes_create'), 
                                            array('class' => 'btn btn-block btn-primary btn-flat')); ?>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('especializacoes_data_plantao');?></th>
                                                <th><?php echo lang('especializacoes_hora_entrada');?></th>
                                                <th><?php echo lang('especializacoes_hora_saida');?></th>
                                                <th><?php echo lang('especializacoes_descricao');?></th>
                                                <th><?php echo lang('especializacoes_status');?></th>
                                                <th><?php echo lang('especializacoes_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($especializacoes as $especializacao):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($especializacao->data_plantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($especializacao->hora_entrada ? date('H:i', strtotime($especializacao->hora_entrada))  : "-", ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($especializacao->hora_saida ? date('H:i', strtotime($especializacao->hora_saida))  : "-", ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($especializacao->descricao, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($especializacao->status, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/especializacoes/view/'.$especializacao->id, 'Aprovar', array('class' => 'btn btn-primary btn-flat')); ?> &nbsp;
                                                    <?php echo anchor('admin/especializacoes/view/'.$especializacao->id, lang('actions_see'), array('class' => 'btn btn-primary btn-flat')); ?> &nbsp;
                                                    <?php echo anchor('admin/especializacoes/edit/'.$especializacao->id, lang('actions_edit'), array('class' => 'btn btn-default btn-flat')); ?> &nbsp;
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
