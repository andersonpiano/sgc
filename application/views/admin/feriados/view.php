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
                                    <h3 class="box-title"><?php echo lang('feriados_box_title'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <th><?php echo lang('feriados_data'); ?></th>
                                                <td><?php echo(date('d/m/Y', strtotime($feriado->data))); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('feriados_descricao'); ?></th>
                                                <td><?php echo htmlspecialchars($feriado->descricao, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('feriados_especial'); ?></th>
                                                <td><?php echo ($feriado->especial) ? '<span class="label label-success">Sim</span>' : '<span class="label label-default">Não</span>'; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Hospitais Vinculados</h3>
                                </div>
                                <div class="box-body">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
