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
                                    <h3 class="box-title"><?php echo lang('users_find'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-find_user')); ?>
                                        <div class="form-group">
                                            <?php echo lang('users_firstname', 'first_name', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_input($first_name);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('users_email', 'email', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_input($email);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_find'))); ?> &nbsp;
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?> &nbsp;
                                                    <?php echo anchor('admin/users', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
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
                                    <h3 class="box-title"><?php echo anchor('admin/users/create', '<i class="fa fa-plus"></i> '. lang('users_create_user'), array('class' => 'btn btn-block btn-primary btn-flat')); ?></h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('users_firstname');?></th>
                                                <th><?php echo lang('users_lastname');?></th>
                                                <th><?php echo lang('users_email');?></th>
                                                <th><?php echo lang('users_groups');?></th>
                                                <th><?php echo lang('users_status');?></th>
                                                <th><?php echo lang('users_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($users as $user):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
<?php

foreach ($user->groups as $group)
{

    // Disabled temporary !!!
    // echo anchor('admin/groups/edit/'.$group->id, '<span class="label" style="background:'.$group->bgcolor.';">'.htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8').'</span>');
    echo anchor('admin/groups/edit/'.$group->id, '<span class="label label-default">'.htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8').'</span>');
}

?>
                                                </td>
                                                <td><?php echo ($user->active) ? anchor('admin/users/deactivate/'.$user->id, '<span class="label label-success">'.lang('users_active').'</span>') : anchor('admin/users/activate/'. $user->id, '<span class="label label-default">'.lang('users_inactive').'</span>'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/users/edit/'.$user->id, lang('actions_edit'), array('class' => 'btn btn-primary btn-flat')); ?> &nbsp;
                                                    <?php echo anchor('admin/users/profile/'.$user->id, lang('actions_see'), array('class' => 'btn btn-default btn-flat')); ?>
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
