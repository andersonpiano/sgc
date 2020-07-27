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
                                        <?php echo anchor('admin/plantoes/create', '<i class="fa fa-plus"></i> '. 
                                            lang('plantoes_create'), 
                                            array('class' => 'btn btn-block btn-primary btn-flat')); ?>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <caption><?php echo lang('plantoes_plantoes');?></caption>
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('plantoes_dataplantao');?></th>
                                                <th><?php echo lang('plantoes_horainicialplantao');?></th>
                                                <th><?php echo lang('plantoes_horafinalplantao');?></th>
                                                <th><?php echo lang('plantoes_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($plantoes as $plantao):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($plantao->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($plantao->horainicialplantao, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($plantao->horafinalplantao, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/plantoes/edit/'.$plantao->id, lang('actions_edit')); ?> &nbsp;
                                                    <?php echo anchor('admin/plantoes/view/'.$plantao->id, lang('actions_see')); ?>
                                                </td>
                                            </tr>
<?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <caption><?php echo lang('plantoes_passagens');?></caption>
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('plantoes_dataplantao');?></th>
                                                <th><?php echo lang('plantoes_horainicialplantao');?></th>
                                                <th><?php echo lang('plantoes_horafinalplantao');?></th>
                                                <th><?php echo lang('plantoes_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($plantoes as $plantao):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($plantao->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($plantao->horainicialplantao, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($plantao->horafinalplantao, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/plantoes/edit/'.$plantao->id, lang('actions_edit')); ?> &nbsp;
                                                    <?php echo anchor('admin/plantoes/view/'.$plantao->id, lang('actions_see')); ?>
                                                </td>
                                            </tr>
<?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <caption><?php echo lang('plantoes_recebidos');?></caption>
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('plantoes_dataplantao');?></th>
                                                <th><?php echo lang('plantoes_horainicialplantao');?></th>
                                                <th><?php echo lang('plantoes_horafinalplantao');?></th>
                                                <th><?php echo lang('plantoes_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($plantoes as $plantao):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($plantao->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($plantao->horainicialplantao, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($plantao->horafinalplantao, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/plantoes/edit/'.$plantao->id, lang('actions_edit')); ?> &nbsp;
                                                    <?php echo anchor('admin/plantoes/view/'.$plantao->id, lang('actions_see')); ?>
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
