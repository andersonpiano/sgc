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

                <?php if ($user_type == 0) : ?>
                    <?php if (!empty($recebidos)) : ?>
                    <section class="content col-md-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="box">
                                <?php foreach ($recebidos as $unidade => $plantoes) : ?>
                                    <div class="box-header with-border">
                                        <h3 class="">Cessões e trocas recebidas</h3>
                                    </div>
                                    <div class="box-header with-border">
                                        <h3 class="box-title">
                                            <?php echo(lang('escalas_unidadehospitalar') . ": " . $unidade); ?>
                                        </h3>
                                    </div>
                                    <div class="box-body">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th><?php echo lang('plantoes_setor');?></th>
                                                    <th><?php echo lang('plantoes_dataplantao');?></th>
                                                    <th><?php echo lang('plantoes_horario');?></th>
                                                    <th><?php echo lang('plantoes_profissional_titular');?></th>
                                                    <?php if ($user_type == 1 or $user_type == 2) : ?>
                                                    <th><?php echo lang('plantoes_profissional_substituto');?></th>
                                                    <?php endif; ?>
                                                    <th><?php echo lang('plantoes_tipopassagem');?></th>
                                                    <th><?php echo lang('plantoes_statuspassagem');?></th>
                                                    <th class="dontprint text-center"><?php echo lang('plantoes_action');?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($plantoes as $indice => $plantao) :?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($plantao->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($plantao->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?php echo htmlspecialchars(date('H:i', strtotime($plantao->horainicialplantao)) . ' - ' . date('H:i', strtotime($plantao->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?php echo htmlspecialchars($plantao->profissional_passagem_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <?php if ($user_type == 1 or $user_type == 2) : ?>
                                                    <td><?php echo htmlspecialchars($plantao->profissional_substituto_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <?php endif; ?>
                                                    <td><?php echo htmlspecialchars($tipospassagem[$plantao->passagenstrocas_tipopassagem], ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td>    
                                                <?php
                                                $status_passagem = '';
                                                if ($plantao->passagenstrocas_statuspassagem == 0) {
                                                    $status_passagem = 'danger';
                                                } elseif ($plantao->passagenstrocas_statuspassagem == 1) {
                                                    $status_passagem = 'success';
                                                } elseif ($plantao->passagenstrocas_statuspassagem == 2) {
                                                    $status_passagem = 'warning';
                                                }
                                                ?>
                                                        <span class="badge badge-<?php echo($status_passagem);?>">
                                                            <?php echo htmlspecialchars($statuspassagem[$plantao->passagenstrocas_statuspassagem], ENT_QUOTES, 'UTF-8'); ?>
                                                        </span>
                                                    </td>
                                                    <td class="dontprint text-center">
                                                        <?php if ($plantao->passagenstrocas_statuspassagem == 0 && $plantao->passagenstrocas_tipopassagem == 0) { echo anchor('admin/plantoes/confirm/'.$plantao->id, lang('actions_confirm'), 'class="btn btn-primary"'); } ?> &nbsp;
                                                        <?php if ($plantao->passagenstrocas_statuspassagem == 0 && $plantao->passagenstrocas_tipopassagem == 1) { echo anchor('admin/plantoes/propose/'.$plantao->id, lang('actions_propose'), 'class="btn btn-primary"'); } ?> &nbsp;
                                                        <?php if ($plantao->passagenstrocas_statuspassagem == 1) { echo anchor('admin/plantoes/tooffer/'.$plantao->id . '/cessoestrocas', lang('actions_to_offer'), 'class="btn btn-primary"'); } ?> &nbsp;
                                                        <?php echo anchor('admin/plantoes/view/'.$plantao->id, lang('actions_see'), 'class="btn btn-default"'); ?>
                                                    </td>
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
                    <?php else: ?>
                    <section class="content col-md-12 col-xs-12">
                        <div class="print-header row">
                            <div class="col-lg-2 col-xs-2"><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/></div>
                            <div class="col-lg-10 col-xs-10 pull-right"><?php echo htmlspecialchars(!empty($escalas[0]->unidadehospitalar_razaosocial) ? $escalas[0]->unidadehospitalar_razaosocial : '', ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="box">
                                    <div class="box-header with-border">
                                        <h3 class="">Cessões e trocas recebidas</h3>
                                    </div>
                                    <div class="box-body">
                                        <h6 class="">Não há cessões e trocas recebidas.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (!empty($passagens)) : ?>
                <section class="content col-md-12 col-xs-12">
                    <div class="print-header row">
                        <div class="col-lg-2 col-xs-2"><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/></div>
                        <div class="col-lg-10 col-xs-10 pull-right"><?php echo htmlspecialchars(!empty($escalas[0]->unidadehospitalar_razaosocial) ? $escalas[0]->unidadehospitalar_razaosocial : '', ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="box">
                            <?php foreach ($passagens as $unidade => $plantoes) : ?>
                                <div class="box-header with-border">
                                    <?php if ($user_type == 0) : ?>
                                    <h3 class="">Cessões e trocas passadas</h3>
                                    <?php else : ?>
                                    <h3 class="">Cessões e trocas por setor</h3>
                                    <?php endif; ?>
                                </div>
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <?php echo(lang('escalas_unidadehospitalar') . ": " . $unidade); ?>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('plantoes_setor');?></th>
                                                <th><?php echo lang('plantoes_dataplantao');?></th>
                                                <th><?php echo lang('plantoes_horario');?></th>
                                                <?php if ($user_type == 1 or $user_type == 2) : ?>
                                                <th><?php echo lang('plantoes_profissional_titular');?></th>
                                                <?php endif; ?>
                                                <th><?php echo lang('plantoes_profissional_substituto');?></th>
                                                <th><?php echo lang('plantoes_tipopassagem');?></th>
                                                <th><?php echo lang('plantoes_statuspassagem');?></th>
                                                <th class="dontprint text-center"><?php echo lang('plantoes_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($plantoes as $indice => $plantao) :?>
                                            <tr id="row<?php echo($plantao->passagenstrocas_id); ?>">
                                                <td><?php echo htmlspecialchars($plantao->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($plantao->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($plantao->horainicialplantao)) . ' - ' . date('H:i', strtotime($plantao->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php if ($user_type == 1 or $user_type == 2) : ?>
                                                <td><?php echo htmlspecialchars($plantao->profissional_titular_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php endif; ?>
                                                <td><?php echo htmlspecialchars($plantao->profissional_substituto_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($tipospassagem[$plantao->passagenstrocas_tipopassagem], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                            <?php
                                            $status_passagem = '';
                                            if ($plantao->passagenstrocas_statuspassagem == 0) {
                                                $status_passagem = 'danger';
                                            } elseif ($plantao->passagenstrocas_statuspassagem == 1) {
                                                $status_passagem = 'success';
                                            } elseif ($plantao->passagenstrocas_statuspassagem == 2) {
                                                $status_passagem = 'warning';
                                            }
                                            ?>
                                                    <span class="badge badge-<?php echo($status_passagem);?>">
                                                        <?php echo htmlspecialchars($statuspassagem[$plantao->passagenstrocas_statuspassagem], ENT_QUOTES, 'UTF-8'); ?>
                                                    </span>
                                                </td>
                                                <td class="dontprint text-center">
                                                    <?php if ($plantao->passagenstrocas_statuspassagem == 0 && $plantao->passagenstrocas_tipopassagem == 0) : ?>
                                                        <a href="#" onclick="cancelarCessao(<?php echo($plantao->passagenstrocas_id);?>);" class="btn btn-primary"><?php echo(lang('actions_cancel'));?></a>
                                                    <?php endif; ?> &nbsp;
                                                    <?php echo anchor('admin/plantoes/view/'.$plantao->id, lang('actions_see'), 'class="btn btn-default"'); ?>
                                                </td>
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
                <?php else: ?>
                <section class="content col-md-12 col-xs-12">
                    <div class="print-header row">
                        <div class="col-lg-2 col-xs-2"><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/></div>
                        <div class="col-lg-10 col-xs-10 pull-right"><?php echo htmlspecialchars(!empty($escalas[0]->unidadehospitalar_razaosocial) ? $escalas[0]->unidadehospitalar_razaosocial : '', ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <?php if ($user_type == 0) : ?>
                                    <h3 class="">Cessões e trocas passadas</h3>
                                    <?php else : ?>
                                    <h3 class="">Cessões e trocas por setor</h3>
                                    <?php endif; ?>
                                </div>
                                <div class="box-body">
                                    <h6 class="">Não há cessões e trocas.</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <?php endif; ?>
            </div>
