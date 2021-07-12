<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <section class="content dontprint">
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
                                                    <a href="<?php echo(current_url()); ?>" onclick="window.print(); return false;" class="btn btn-default btn-flat">Imprimir</a>&nbsp;
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
                    <div class="print-header row">
                        <div class="col-lg-2 col-xs-2"><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/></div>
                        <div class="col-lg-10 col-xs-10 pull-right"><?php echo htmlspecialchars(!empty($escalas[0]->unidadehospitalar_razaosocial) ? $escalas[0]->unidadehospitalar_razaosocial : '', ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="col-lg-8 col-xs-10 pull-right"><h3>Lista Setores</h3></div>
                    </div>
                    <div class="row ">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header with-border dontprint">
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
                                                <th><center><?php echo lang('setores_fopag');?></center></th>
                                                <th><?php echo lang('setores_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($setores as $setor):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($setor->nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($setor->unidadehospitalar->razaosocial, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><center><button class="btn-light btn btn-flat btn-vagas" setor="<?php echo $setor->id;?>" nome="<?php echo $setor->nome; ?>"><?php echo htmlspecialchars($setor->vagas, ENT_QUOTES, 'UTF-8'); ?></button></center></td>
                                                <td class="dontprint text-center">
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

            <div id="modal_vagas_setor" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <center><h4 class="modal-title" id="titulo"></h4></center>
                </div>

                <div class="modal-body">
                        <table id="dt_vagas" class="table table-striped table-bordered">
                        <thead>
                            <tr class="tableheader">
                                <th style="width:20%" class="dt-center text-center" >Data</th>
                                <th style="width:60%" class="dt-center text-center">Turno</th>
                                <th style="width:20%" class="dt-center no-sort text-center">Tipo de Escala</th>
                                <th style="width:60%" class="dt-center text-center">Condição</th>
                            </tr> 
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
