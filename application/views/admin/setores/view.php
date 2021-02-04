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
                                    <h3 class="box-title"><?php echo lang('setores_box_title'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <th><?php echo lang('setores_nome'); ?></th>
                                                <td><?php echo $setor->nome; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('setores_unidadehospitalar'); ?></th>
                                                <td><?php echo htmlspecialchars($setor->unidadehospitalar->razaosocial, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('setores_status'); ?></th>
                                                <td><?php echo ($setor->active) ? '<span class="label label-success">'.lang('setores_active').'</span>' : '<span class="label label-default">'.lang('setores_inactive').'</span>'; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Profissionais do setor</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
<?php foreach ($setor->profissionais as $profissional) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($profissional->nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/profissionais/view/'.$profissional->id, lang('actions_see'), array('class' => 'btn btn-primary btn-flat')); ?> &nbsp;
                                                    <?php echo anchor('admin/profissionais/edit/'.$profissional->id, lang('actions_edit'), array('class' => 'btn btn-default btn-flat')); ?> &nbsp;
                                                    <?php echo anchor('admin/profissionais/unlinkfromsector/'.$profissional->id.'/'.$setor->id, lang('actions_unlink'), array('class' => 'btn btn-default btn-flat')); ?>
                                                </td>
                                            </tr>
<?php endforeach?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Usuários vinculados ao setor</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
<?php foreach ($setor->usuarios as $usuario) : ?>
    <?php if ($usuario->coordenador == 1) : ?>
        <?php $coordenador = ' (Coordenador)'; ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($usuario->first_name . ' ' . $usuario->last_name . $coordenador, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/users/profile/'.$usuario->id, lang('actions_see'), array('class' => 'btn btn-primary btn-flat')); ?> &nbsp;
                                                    <?php echo anchor('admin/users/edit/'.$usuario->id, lang('actions_edit'), array('class' => 'btn btn-default btn-flat')); ?> &nbsp;
                                                    <?php echo anchor('admin/users/unlinkfromsector/'.$usuario->id.'/'.$setor->id, lang('actions_unlink'), array('class' => 'btn btn-default btn-flat')); ?>
                                                </td>
                                            </tr>
    <?php endif ?>
<?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
