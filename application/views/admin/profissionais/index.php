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
                                        <?php echo anchor('admin/profissionais/create', '<i class="fa fa-plus"></i> '. 
                                            lang('profissionais_create'), 
                                            array('class' => 'btn btn-block btn-primary btn-flat')); ?>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('profissionais_registro');?></th>
                                                <th><?php echo lang('profissionais_nome');?></th>
                                                <th><?php echo lang('profissionais_email');?></th>
                                                <th><?php echo lang('profissionais_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($profissionais as $prof):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($prof->registro, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($prof->nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($prof->email, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/profissionais/edit/'.$prof->id, lang('actions_edit')); ?> &nbsp;
                                                    <?php echo anchor('admin/profissionais/view/'.$prof->id, lang('actions_see')); ?>
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
