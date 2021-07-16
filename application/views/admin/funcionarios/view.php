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
                    <div class="print-header row">
                        <div class="col-lg-2 col-xs-2"><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/></div>
                        <div class="col-lg-10 col-xs-10 pull-right"><?php echo htmlspecialchars(!empty($escalas[0]->unidadehospitalar_razaosocial) ? $escalas[0]->unidadehospitalar_razaosocial : '', ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('funcionarios_box_title'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <th><?php echo lang('funcionarios_registro'); ?></th>
                                                <td><?php echo $profissional->registro; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('funcionarios_matricula'); ?></th>
                                                <td><?php echo $profissional->matricula; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('funcionarios_nome'); ?></th>
                                                <td><?php echo htmlspecialchars($profissional->nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('funcionarios_email'); ?></th>
                                                <td><?php echo htmlspecialchars($profissional->email, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('funcionarios_status'); ?></th>
                                                <td><?php echo ($profissional->active) ? '<span class="label label-success">'.lang('funcionarios_active').'</span>' : '<span class="label label-default">'.lang('funcionarios_inactive').'</span>'; ?></td>
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
                                    <h3 class="box-title">Setores em que atua</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
<?php foreach ($profissional->setores as $setor) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($setor->nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php foreach ($coordenador->setorescoordena as $setorescoordenador) : ?>
                                                        <?php if ($setor->id == $setorescoordenador->id) : ?>
                                                            <?php echo anchor('admin/setores/view/'.$setor->id, lang('actions_see'), array('class' => 'btn btn-primary btn-flat')); ?> &nbsp;
                                                            <?php echo anchor('admin/setores/edit/'.$setor->id, lang('actions_edit'), array('class' => 'btn btn-default btn-flat')); ?> &nbsp;
                                                            <?php echo anchor('admin/funcionarios/unlinkfromsector/'.$profissional->id.'/'.$setor->id, lang('actions_unlink'), array('class' => 'btn btn-default btn-flat')); ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </td>
                                            </tr>
<?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Setores que coordena</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
<?php foreach ($profissional->setorescoordena as $setor) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($setor->nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="dontprint text-center">
                                                    <?php echo anchor('admin/setores/view/'.$setor->id, lang('actions_see'), array('class' => 'btn btn-primary btn-flat')); ?> &nbsp;
                                                    <?php echo anchor('admin/setores/edit/'.$setor->id, lang('actions_edit'), array('class' => 'btn btn-default btn-flat')); ?> &nbsp;
                                                    <?php echo anchor('admin/users/unlinkfromsector/'.$profissional->usuario->user_id.'/'.$setor->id, lang('actions_unlink'), array('class' => 'btn btn-default btn-flat')); ?>
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