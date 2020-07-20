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
                                        <?php echo anchor('admin/unidadeshospitalares/create', '<i class="fa fa-plus"></i> '. 
                                            lang('unidadeshospitalares_create'), 
                                            array('class' => 'btn btn-block btn-primary btn-flat')); ?>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('unidadeshospitalares_cnpj');?></th>
                                                <th><?php echo lang('unidadeshospitalares_razaosocial');?></th>
                                                <th><?php echo lang('unidadeshospitalares_nomefantasia');?></th>
                                                <th><?php echo lang('unidadeshospitalares_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($unidadeshospitalares as $unid):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($unid->cnpj, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($unid->razaosocial, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($unid->nomefantasia, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/unidadeshospitalares/edit/'.$unid->id, lang('actions_edit')); ?> &nbsp;
                                                    <?php echo anchor('admin/unidadeshospitalares/view/'.$unid->id, lang('actions_see')); ?>
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
