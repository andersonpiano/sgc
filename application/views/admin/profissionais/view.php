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
                                    <h3 class="box-title"><?php echo lang('profissionais_box_title'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <th><?php echo lang('profissionais_registro'); ?></th>
                                                <td><?php echo $profissional->registro; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('profissionais_nome'); ?></th>
                                                <td><?php echo htmlspecialchars($profissional->nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('profissionais_email'); ?></th>
                                                <td><?php echo htmlspecialchars($profissional->email, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('profissionais_status'); ?></th>
                                                <td><?php echo ($profissional->active) ? '<span class="label label-success">'.lang('profissionais_active').'</span>' : '<span class="label label-default">'.lang('profissionais_inactive').'</span>'; ?></td>
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
