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
                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('unidadeshospitalares_box_title'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <th><?php echo lang('unidadeshospitalares_cnpj'); ?></th>
                                                <td><?php echo $unidadehospitalar->cnpj; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('unidadeshospitalares_razaosocial'); ?></th>
                                                <td><?php echo htmlspecialchars($unidadehospitalar->razaosocial, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('unidadeshospitalares_nomefantasia'); ?></th>
                                                <td><?php echo htmlspecialchars($unidadehospitalar->nomefantasia, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('unidadeshospitalares_status'); ?></th>
                                                <td><?php echo ($unidadehospitalar->active) ? '<span class="label label-success">'.lang('unidadeshospitalares_active').'</span>' : '<span class="label label-default">'.lang('unidadeshospitalares_inactive').'</span>'; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('unidadeshospitalares_setores'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
<?php foreach ($setores as $setor): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($setor->nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/setores/view/'.$setor->id, lang('actions_see'), array('class' => 'btn btn-primary btn-flat')); ?> &nbsp;
                                                    <?php echo anchor('admin/setores/edit/'.$setor->id, lang('actions_edit'), array('class' => 'btn btn-default btn-flat')); ?>
                                                </td>
                                            </tr>
<?php endforeach?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
