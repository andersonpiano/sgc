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

                <!-- if (sizeof($proximosplantoes) > 0) { echo('A pesquisa não retornou resultados.');-->
                <section class="content">
                    <div class="print-header row">
                        <div class="col-lg-2 col-xs-2"><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/></div>
                        <div class="col-lg-10 col-xs-10 pull-right"><?php echo htmlspecialchars(!empty($escalas[0]->unidadehospitalar_razaosocial) ? $escalas[0]->unidadehospitalar_razaosocial : '', ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                            <?php foreach ($proximosplantoes as $unidade => $plantoes) : ?>
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
                                                <?php if ($user_type == 1 or $user_type == 2) : ?>
                                                <th><?php echo lang('plantoes_profissional');?></th>
                                                <?php endif; ?>
                                                <th><?php echo lang('plantoes_dataplantao');?></th>
                                                <th><?php echo lang('plantoes_horario');?></th>
                                                <th class="dontprint text-center"><?php echo lang('plantoes_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($plantoes as $indice => $plantao) :?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($plantao->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php if ($user_type == 1 or $user_type == 2) : ?>
                                                <td><?php echo htmlspecialchars($plantao->profissional_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php endif; ?>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($plantao->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($plantao->horainicialplantao)) . ' - ' . date('H:i', strtotime($plantao->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="dontprint text-center">
                                                    <?php echo anchor('admin/plantoes/tooffer/'.$plantao->id . '/proximosplantoes', lang('actions_to_offer'), 'class="btn btn-primary"'); ?> &nbsp;
                                                    <?php echo anchor('admin/plantoes/view/'.$plantao->id, lang('actions_see'), 'class="btn btn-default"'); ?> &nbsp;
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
            </div>
