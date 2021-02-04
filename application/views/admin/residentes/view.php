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
                                    <h3 class="box-title"><?php echo lang('residentes_box_title'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <th><?php echo lang('residentes_registro'); ?></th>
                                                <td><?php echo $residente->registro; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('residentes_nome'); ?></th>
                                                <td><?php echo htmlspecialchars($residente->nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('residentes_email'); ?></th>
                                                <td><?php echo htmlspecialchars($residente->email, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('residentes_status'); ?></th>
                                                <td><?php echo ($residente->active) ? '<span class="label label-success">'.lang('residentes_active').'</span>' : '<span class="label label-default">'.lang('residentes_inactive').'</span>'; ?></td>
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
                                    <h3 class="box-title">Unidades em que atua</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
<?php foreach ($residente->unidadeshospitalares as $setor) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($setor->nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php if ($setor->id == $unidadeshospitalarescoordenador->id) : ?>
                                                        <?php echo anchor('admin/unidadeshospitalares/view/'.$setor->id, lang('actions_see'), array('class' => 'btn btn-primary btn-flat')); ?> &nbsp;
                                                        <?php echo anchor('admin/unidadeshospitalares/edit/'.$setor->id, lang('actions_edit'), array('class' => 'btn btn-default btn-flat')); ?> &nbsp;
                                                        <?php echo anchor('admin/residentes/unlinkfromsector/'.$residente->id.'/'.$setor->id, lang('actions_unlink'), array('class' => 'btn btn-default btn-flat')); ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
<?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
