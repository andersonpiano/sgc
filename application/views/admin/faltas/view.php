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
                                    <h3 class="box-title"><?php echo lang('faltas_box_title'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <th><?php echo lang('faltas_profissional'); ?></th>
                                                <td><?php echo $falta->profissional->nome; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('faltas_setor'); ?></th>
                                                <td><?php echo htmlspecialchars($falta->setor->nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('faltas_data_plantao'); ?></th>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($falta->escala->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('faltas_turno_plantao'); ?></th>
                                                <td><?php echo htmlspecialchars($falta->turno_plantao, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('faltas_tipo_falta'); ?></th>
                                                <td><?php echo htmlspecialchars($falta->tipo_falta, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('faltas_justificativa'); ?></th>
                                                <td><?php echo htmlspecialchars(nl2br($falta->justificativa), ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('faltas_created_on'); ?></th>
                                                <td><?php echo htmlspecialchars(date('d/m/Y H:i:s', strtotime($falta->datahora_insercao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
