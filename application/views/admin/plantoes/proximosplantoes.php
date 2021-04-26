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
                                                <button type="button" class="btn btn-primary btn-oferecer fa fa-share" data-dismiss="modal" plantao_id="<?php echo $plantao->id; ?>"><?php echo ' '.lang('actions_to_offer');?></button>&nbsp;
                                                    <?php /*echo anchor('admin/plantoes/tooffer/'.$plantao->id . '/proximosplantoes', lang('actions_to_offer'), 'class="btn btn-primary"'); */?> &nbsp;
                                                    <?php echo anchor('admin/plantoes/view/'.$plantao->id, ' '.lang('actions_see'), 'class="btn btn-primary fa fa-eye"'); ?> &nbsp;
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
            </div></div>

<div id="modal_ofertar" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">Selecione o profissional</h4>
                </div>

                <div class="modal-body">
                    <form id="form_oferecer">

                        <input id="folha_id" name="folha_id" hidden>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tipo</label>
                            <div class="col-lg-10">
                                    <?php echo form_dropdown($tipo_oferta);?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Médico Substituto</label>
                            <div class="col-lg-10">
                                    <?php echo form_dropdown($tipo_oferta);?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Plantão</label>
                            <div class="col-lg-10">
                                    <?php echo form_dropdown($tipo_oferta);?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group text-center">
                        <button type="submit" id="btn_save_folha" class="btn btn-primary text-center">
                            <i class="fa fa-money"></i>&nbsp;&nbsp;Confirmar</button>
                            <span class="help-block"></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
