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
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <caption><?php echo lang('plantoes_plantoes');?></caption>
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('plantoes_unidadehospitalar');?></th>
                                                <th><?php echo lang('plantoes_setor');?></th>
                                                <th><?php echo lang('plantoes_dataplantao');?></th>
                                                <th><?php echo lang('plantoes_horario');?></th>
                                                <th><?php echo lang('plantoes_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($plantoes as $plantao):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($plantao->razaosocial, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($plantao->nomesetor, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($plantao->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($plantao->horainicialplantao)) . ' - ' . date('H:i', strtotime($plantao->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
    <?php if ($plantao->profissionalsubstituto_id == 0): ?>
                                                    <?php echo anchor('admin/plantoes/tooffer/'.$plantao->id, lang('actions_to_offer')); ?> &nbsp;
    <?php endif;?>
                                                    <?php echo anchor('admin/plantoes/view/'.$plantao->id, lang('actions_see')); ?>
                                                </td>
                                            </tr>
<?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <caption><?php echo lang('plantoes_recebidos');?></caption>
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('plantoes_unidadehospitalar');?></th>
                                                <th><?php echo lang('plantoes_setor');?></th>
                                                <th><?php echo lang('plantoes_dataplantao');?></th>
                                                <th><?php echo lang('plantoes_horario');?></th>
                                                <th><?php echo lang('plantoes_profissional_titular');?></th>
                                                <th><?php echo lang('plantoes_statuspassagem');?></th>
                                                <th><?php echo lang('plantoes_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($recebidos as $recebido) :?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($recebido->razaosocial, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($recebido->nomesetor, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($recebido->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($recebido->horainicialplantao)) . ' - ' . date('H:i', strtotime($recebido->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($recebido->nomeprofissional, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <span class="badge badge-<?php echo($recebido->statuspassagem==1 ? 'success' : 'danger');?>"><?php echo htmlspecialchars($statuspassagem[$recebido->statuspassagem], ENT_QUOTES, 'UTF-8'); ?></span>
                                                </td>
                                                <td>
    <?php if ($recebido->statuspassagem == 0) :?>
                                                    <?php echo anchor('admin/plantoes/confirm/'.$recebido->id, lang('actions_confirm')); ?> &nbsp;
    <?php endif;?>
                                                    <?php echo anchor('admin/plantoes/view/'.$recebido->id, lang('actions_see')); ?>
                                                </td>
                                            </tr>
<?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <caption><?php echo lang('plantoes_passagens');?></caption>
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('plantoes_unidadehospitalar');?></th>
                                                <th><?php echo lang('plantoes_setor');?></th>
                                                <th><?php echo lang('plantoes_dataplantao');?></th>
                                                <th><?php echo lang('plantoes_horario');?></th>
                                                <th><?php echo lang('plantoes_profissional_titular');?></th>
                                                <th><?php echo lang('plantoes_profissional_substituto');?></th>
                                                <th><?php echo lang('plantoes_statuspassagem');?></th>
                                                <th><?php echo lang('plantoes_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($passagens as $passagem):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($passagem->razaosocial, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($passagem->nomesetor, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($passagem->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($passagem->horainicialplantao)) . ' - ' . date('H:i', strtotime($passagem->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($passagem->nomeprofissional, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($passagem->nomeprofissionalsubstituto, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <span class="badge badge-<?php echo($passagem->statuspassagem==1 ? 'success' : 'danger');?>"><?php echo htmlspecialchars($statuspassagem[$passagem->statuspassagem], ENT_QUOTES, 'UTF-8'); ?></span>
                                                </td>
                                                <td>
                                                    <?php echo anchor('admin/plantoes/edit/'.$passagem->id, lang('actions_edit')); ?> &nbsp;
                                                    <?php echo anchor('admin/plantoes/view/'.$passagem->id, lang('actions_see')); ?>
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
