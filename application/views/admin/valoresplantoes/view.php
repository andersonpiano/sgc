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
                                    <h3 class="box-title"><?php echo lang('escalas_box_title'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <th><?php echo lang('escalas_dataplantao'); ?></th>
                                                <td><?php echo(date('d/m/Y', strtotime($escala->dataplantao))); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('escalas_horainicialplantao'); ?></th>
                                                <td><?php echo htmlspecialchars($escala->horainicialplantao, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('escalas_horafinalplantao'); ?></th>
                                                <td><?php echo htmlspecialchars($escala->horafinalplantao, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('escalas_status'); ?></th>
                                                <td><?php echo ($escala->active) ? '<span class="label label-success">'.lang('escalas_active').'</span>' : '<span class="label label-default">'.lang('escalas_inactive').'</span>'; ?></td>
                                            </tr>
                                            <!--
                                            <tr>
                                                <th><?php //echo lang('users_groups'); ?></th>
                                                <td>
                                            -->
<?php //foreach ($user->groups as $group):?>
                                                    <?php // Disabled temporary !!! ?>
                                                    <?php //echo '<span class="label" style="background:'.$group->bgcolor.'">'.htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8').'</span>'; ?>
                                                    <?php //echo '<span class="label label-default">'.htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8').'</span>'; ?>
<?php //endforeach?>
                                                <!--</td>
                                            </tr>-->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">xxxx</h3>
                                </div>
                                <div class="box-body">


                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
