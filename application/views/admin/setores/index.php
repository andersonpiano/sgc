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
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($setores as $setor):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($setor->nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($setor->unidade_hospitalar, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/setores/edit/'.$setor->id, lang('actions_edit')); ?> &nbsp;
                                                    <?php echo anchor('admin/setores/view/'.$setor->id, lang('actions_see')); ?>
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
