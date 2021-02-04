<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <?php if (isset($message)) : ?>
                <section class="content-header">
                    <div class="alert bg-warning alert-dismissible" role="alert">
                        <?php echo(isset($message) ? $message : '');?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </section>
                <?php endif; ?>

                <!-- if (sizeof($proximosplantoes) > 0) { echo('A pesquisa não retornou resultados.');-->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                            <?php foreach ($frequencias as $setor => $frequencia) : ?>
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <?php echo(lang('escalas_setor') . ": " . $setor); ?>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('plantoes_dataplantao');?></th>
                                                <th><?php echo lang('plantoes_horario');?></th>
                                                <th><?php echo lang('plantoes_tipo_batida');?></th>
                                                <!--<th><?php //echo lang('plantoes_action');?></th>-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($frequencia as $indice => $freq) :?>
                                            <tr>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($freq->data_frequencia)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($freq->data_frequencia)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($freq->tipo_frequencia==1 ? 'Entrada' : 'Saída', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <!--<td>
                                                    <?php //echo anchor('admin/plantoes/tooffer/'.$freq->id, lang('actions_to_offer'), 'class="btn btn-primary"'); ?> &nbsp;
                                                    <?php //echo anchor('admin/plantoes/view/'.$freq->id, lang('actions_see'), 'class="btn btn-default"'); ?> &nbsp;
                                                </td>-->
                                            </tr>
                                        <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
