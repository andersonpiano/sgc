<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <section class="print-header">
                    <!--Cabeçalho para imprimir-->
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

                <section class="content findform">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('escalas_validar'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-validar_escala')); ?>
                                        <div class="form-group">
                                            <?php echo lang('escalas_unidadehospitalar', 'unidadehospitalar_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_dropdown($unidadehospitalar_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_setor', 'setor_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_dropdown($setor_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_ano', 'ano', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_dropdown($ano);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_mes', 'mes', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_dropdown($mes);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_find'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/escalas', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php echo form_close();?>
                                </div>
                            </div>
                         </div>
                    </div>
                </section>

    <?php if (sizeof($escalas) > 0) : ?>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th colspan="5"><?php echo lang('escalas_unidadehospitalar') . ': ' . htmlspecialchars($escalas[0]->unidadehospitalar_razaosocial, ENT_QUOTES, 'UTF-8');?></th>
                                            </tr>
                                            <tr>
                                                <th colspan="5"><?php echo lang('escalas_setor') . ': ' . htmlspecialchars($escalas[0]->setor_nome, ENT_QUOTES, 'UTF-8');?></th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('escalas_profissional');?></th>
                                                <th><?php echo lang('escalas_diadasemana');?></th>
                                                <th><?php echo lang('escalas_dataplantao');?></th>
                                                <th><?php echo lang('escalas_horario');?></th>
                                                <th><?php echo lang('escalas_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
        <?php foreach ($escalas as $escala) : ?>
                                            <tr>
                                                <?php if ($escala->passagenstrocas_id == null) :?>
                                                <td><?php echo htmlspecialchars($escala->profissional_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php else :?>
                                                <td><?php echo htmlspecialchars($escala->profissional_substituto_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php endif;?>
                                                <td><?php echo htmlspecialchars($diasdasemana[date('w', strtotime($escala->dataplantao))], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($escala->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($escala->horainicialplantao)) . " a " . date('H:i', strtotime($escala->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php //echo anchor('admin/escalas/view/'.$escala->id, lang('actions_see'), array('class' => 'btn btn-primary btn-flat')); ?>
                                                </td>
                                            </tr>
        <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php echo form_open('admin/escalas/validarenviar', array('class' => 'form-horizontal', 'id' => 'form-validarenviar_escala')); ?>
                                <?php echo form_hidden('p_unidadehospitalar_id', $p_unidadehospitalar_id); ?>
                                <?php echo form_hidden('p_setor_id', $p_setor_id); ?>
                                <?php echo form_hidden('p_ano', $p_ano); ?>
                                <?php echo form_hidden('p_mes', $p_mes); ?>
                                <div class="box-body text-center">
                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_validate_send'))); ?>
                                    <?php echo anchor('admin/escalas/validar#print', lang('actions_print'), array('class' => 'btn btn-default btn-flat', 'onclick' => 'window.print();')); ?>
                                </div>
                                <?php echo form_close();?>
    <?php else: ?>
                            <div class="box-body text-center">Sua pesquisa não retornou resultados ou as escalas desse período já foram validadas.</div>
    <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>