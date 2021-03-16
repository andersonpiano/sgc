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

                <?php if ($user_type == 0) : ?>
                    <?php if (!empty($recebidos)) : ?>
                    <section class="content col-md-12 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="box">
                                    <h4>Cessões e trocas recebidas</h4>
                                    <?php foreach ($recebidos as $unidade => $plantoes) : ?>
                                        <?php foreach ($plantoes as $indice => $plantao) : ?>
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
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="w3-card-2 w3-white w3-margin-bottom w3-round">
                                                    <header class="w3-container w3-white">
                                                        <h6><?php echo($unidade); ?></h6>
                                                    </header>
                                                    <div class="w3-container w3-white">
                                                        <h6><strong><?php echo htmlspecialchars($plantao->setor_nome, ENT_QUOTES, 'UTF-8');?></strong></h6>
                                                    </div>
                                                    <div class="w3-container w3-white">
                                                        <h6><span class="fa fa-calendar"></span> <?php echo htmlspecialchars(date('d/m/Y', strtotime($plantao->dataplantao)), ENT_QUOTES, 'UTF-8');?></h6>
                                                    </div>
                                                    <div class="w3-container w3-white">
                                                        <h6><span class="fa fa-clock-o"></span> <?php echo htmlspecialchars(date('H:i', strtotime($plantao->horainicialplantao)) . ' - ' . date('H:i', strtotime($plantao->horafinalplantao)), ENT_QUOTES, 'UTF-8');?></h6>
                                                    </div>
                                                    <div class="w3-container w3-white">
                                                        <h6><?php echo lang('plantoes_profissional_titular') . ': ' . htmlspecialchars($plantao->profissional_passagem_nome, ENT_QUOTES, 'UTF-8');?></h6>
                                                    </div>
                                                    <?php if ($user_type == 1 or $user_type == 2) : ?>
                                                    <div class="w3-container w3-white">
                                                        <h6><?php echo lang('plantoes_profissional_substituto') . ': ' . htmlspecialchars($plantao->profissional_substituto_nome, ENT_QUOTES, 'UTF-8');?></h6>
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="w3-container w3-white">
                                                        <h6><?php echo lang('plantoes_tipopassagem') . ': ' . htmlspecialchars($tipospassagem[$plantao->passagenstrocas_tipopassagem], ENT_QUOTES, 'UTF-8');?></h6>
                                                    </div>
                                                    <div class="w3-container w3-white">
                                                        <h6><?php echo lang('plantoes_statuspassagem') . ':&nbsp;';?>
                                                        <span class="badge badge-<?php echo($status_passagem);?>">
                                                            <?php echo htmlspecialchars($statuspassagem[$plantao->passagenstrocas_statuspassagem], ENT_QUOTES, 'UTF-8'); ?>
                                                        </span>
                                                    </h6>
                                                    </div>
                                                    <div class="w3-container w3-white">
                                                        <h6>
                                                            <?php echo anchor('admin/plantoes/view/'.$plantao->id, lang('actions_see'), 'class="btn btn-primary"'); ?>
                                                            <?php if ($plantao->passagenstrocas_statuspassagem == 0 && $plantao->passagenstrocas_tipopassagem == 0) { echo anchor('admin/plantoes/confirm/'.$plantao->id, lang('actions_confirm'), 'class="btn btn-default"'); } ?> &nbsp;
                                                            <?php if ($plantao->passagenstrocas_statuspassagem == 0 && $plantao->passagenstrocas_tipopassagem == 1) { echo anchor('admin/plantoes/propose/'.$plantao->id, lang('actions_propose'), 'class="btn btn-default"'); } ?> &nbsp;
                                                            <?php if ($plantao->passagenstrocas_statuspassagem == 1) { echo anchor('admin/plantoes/tooffer/'.$plantao->id . '/cessoestrocas', lang('actions_to_offer'), 'class="btn btn-default"'); } ?> &nbsp;
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach;?>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php else : ?>
                    <section class="content col-md-12 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="box">
                                    <h4>Cessões e trocas recebidas</h4>
                                    <div class="w3-container w3-white">
                                        <h6>Não há cessões e trocas recebidas.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (!empty($passagens)) : ?>
                <section class="content col-md-12 col-xs-12">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="box">
                                <?php if ($user_type == 0) : ?>
                                    <h4>Cessões e trocas passadas</h4>
                                <?php else : ?>
                                    <h4>Cessões e trocas por setor</h4>
                                <?php endif; ?>
                                <?php foreach ($passagens as $unidade => $plantoes) : ?>
                                    <?php foreach ($plantoes as $indice => $plantao) : ?>
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
                                        <div class="col-xs-12 col-sm-12 col-md-12" id="row<?php echo($plantao->passagenstrocas_id); ?>">
                                            <div class="w3-card-2 w3-white w3-margin-bottom w3-round">
                                                <header class="w3-container w3-white">
                                                    <h6><?php echo($unidade); ?></h6>
                                                </header>
                                                <div class="w3-container w3-white">
                                                    <h6><strong><?php echo htmlspecialchars($plantao->setor_nome, ENT_QUOTES, 'UTF-8');?></strong></h6>
                                                </div>
                                                <div class="w3-container w3-white">
                                                    <h6><span class="fa fa-calendar"></span> <?php echo htmlspecialchars(date('d/m/Y', strtotime($plantao->dataplantao)), ENT_QUOTES, 'UTF-8');?></h6>
                                                </div>
                                                <div class="w3-container w3-white">
                                                    <h6><span class="fa fa-clock-o"></span> <?php echo htmlspecialchars(date('H:i', strtotime($plantao->horainicialplantao)) . ' - ' . date('H:i', strtotime($plantao->horafinalplantao)), ENT_QUOTES, 'UTF-8');?></h6>
                                                </div>
                                                <?php if ($user_type == 1 or $user_type == 2) : ?>
                                                <div class="w3-container w3-white">
                                                    <h6><?php echo lang('plantoes_profissional_titular') . ': ' . htmlspecialchars($plantao->profissional_titular_nome, ENT_QUOTES, 'UTF-8');?></h6>
                                                </div>
                                                <?php endif; ?>
                                                <div class="w3-container w3-white">
                                                    <h6><?php echo lang('plantoes_profissional_substituto') . ': ' . htmlspecialchars($plantao->profissional_substituto_nome, ENT_QUOTES, 'UTF-8');?></h6>
                                                </div>
                                                <div class="w3-container w3-white">
                                                    <h6><?php echo lang('plantoes_tipopassagem') . ': ' . htmlspecialchars($tipospassagem[$plantao->passagenstrocas_tipopassagem], ENT_QUOTES, 'UTF-8');?></h6>
                                                </div>
                                                <div class="w3-container w3-white">
                                                    <h6><?php echo lang('plantoes_statuspassagem') . ':&nbsp;';?>
                                                    <span class="badge badge-<?php echo($status_passagem);?>">
                                                        <?php echo htmlspecialchars($statuspassagem[$plantao->passagenstrocas_statuspassagem], ENT_QUOTES, 'UTF-8'); ?>
                                                    </span>
                                                </h6>
                                                </div>
                                                <div class="w3-container w3-white">
                                                    <h6>
                                                        <?php if ($plantao->passagenstrocas_statuspassagem == 0 && $plantao->passagenstrocas_tipopassagem == 0) : ?>
                                                            <a href="#" onclick="cancelarCessao(<?php echo($plantao->passagenstrocas_id);?>);" class="btn btn-primary"><?php echo(lang('actions_cancel'));?></a>
                                                        <?php endif; ?> &nbsp;
                                                        <?php echo anchor('admin/plantoes/view/'.$plantao->id, lang('actions_see'), 'class="btn btn-default"'); ?>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach;?>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                </section>
                <?php else : ?>
                <section class="content col-md-12 col-xs-12">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="box">
                                <?php if ($user_type == 0) : ?>
                                    <h4>Cessões e trocas passadas</h4>
                                <?php else : ?>
                                    <h4>Cessões e trocas por setor</h4>
                                <?php endif; ?>
                                <div class="w3-container w3-white">
                                    <h6>Não há cessões e trocas passadas.</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <?php endif; ?>
            </div>
