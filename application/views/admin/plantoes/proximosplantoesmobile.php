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

                <section class="content col-md-12 col-xs-12">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="box">
                                <h4>Próximos Plantões</h4>
                                <?php foreach ($proximosplantoes as $unidade => $plantoes) : ?>
                                    <?php foreach ($plantoes as $indice => $plantao) : ?>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="w3-card-2 w3-white w3-margin-bottom w3-round">
                                                <header class="w3-container w3-white">
                                                    <h6><?php echo($unidade); ?></h6>
                                                </header>
                                                <div class="w3-container w3-white">
                                                    <h6><strong><?php echo htmlspecialchars($plantao->setor_nome, ENT_QUOTES, 'UTF-8');?></strong></h6>
                                                </div>
                                                <?php if ($user_type == 1 or $user_type == 2) : ?>
                                                <div class="w3-container w3-white">
                                                    <h6><span class="fa fa-user-md"></span> <?php echo htmlspecialchars($plantao->profissional_nome, ENT_QUOTES, 'UTF-8');?></h6>
                                                </div>
                                                <?php endif;?>
                                                <div class="w3-container w3-white">
                                                    <h6><span class="fa fa-calendar"></span> <?php echo htmlspecialchars(date('d/m/Y', strtotime($plantao->dataplantao)), ENT_QUOTES, 'UTF-8');?></h6>
                                                </div>
                                                <div class="w3-container w3-white">
                                                    <h6><span class="fa fa-clock-o"></span> <?php echo htmlspecialchars(date('H:i', strtotime($plantao->horainicialplantao)) . ' - ' . date('H:i', strtotime($plantao->horafinalplantao)), ENT_QUOTES, 'UTF-8');?></h6>
                                                </div>
                                                <div class="w3-container w3-white">
                                                    <h6>
                                                    <button type="button" class="btn btn-primary btn-oferecer fa fa-share" data-dismiss="modal" plantao_id="<?php echo $plantao->id; ?>" setor_id="<?php echo $plantao->setor_id; ?>" ><?php echo ' '.lang('actions_to_offer');?></button>&nbsp;
                                                        <?php //echo anchor('admin/plantoes/view/'.$plantao->id, lang('actions_see'), 'class="btn btn-primary"'); ?> &nbsp;
                                                        <?php //echo anchor('admin/plantoes/tooffer/'.$plantao->id . '/proximosplantoes', lang('actions_to_offer'), 'class="btn btn-default"'); ?>
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
            </div>