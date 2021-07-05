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

                <section class="content findform">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('escalas_find_processada'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-create_escala')); ?>
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
                                            <?php echo lang('escalas_datainicialplantao', 'datainicial', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($datainicial);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_datafinalplantao', 'datafinal', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($datafinal);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_find'))); ?>
                                                    <a href="<?php echo(current_url()); ?>" onclick="window.print(); return false;" class="btn btn-default btn-flat">Imprimir</a>&nbsp;
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/escalas/buscarescalaprocessada', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php echo form_close();?>
                                </div>
                            </div>
                         </div>
                    </div>
                </section>

                <section class="content">
                <div class="print-header row">
                    <div class="col-lg-2 col-xs-2"><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/></div>
                    <div class="col-lg-10 col-xs-10 pull-right"><h3>Lista Escala Processada</h3></div>
                </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header with-border dontprint">
                                    <h3 class="box-title">
                                        <?php echo anchor('admin/escalas/create', '<i class="fa fa-plus"></i> '. 
                                            lang('escalas_create'), 
                                            array('class' => 'btn btn-block btn-primary btn-flat')); ?>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('escalas_setor');?></th>
                                                <th><?php echo lang('escalas_profissional');?></th>
                                                <th class="text-center"><?php echo lang('escalas_dataplantao');?></th>
                                                <th class="text-center"><?php echo lang('escalas_turno');?></th>
                                                <th class="text-center"><?php echo lang('escalas_horaentrada');?></th>
                                                <th class="text-center"><?php echo lang('escalas_horasaida');?></th>
                                                <th class="text-center dontprint"><?php echo lang('escalas_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
    <?php foreach ($escalas as $escala) : ?>
        <?php
        $turno = '';
        if (date('H:i:s', strtotime($escala->horainicialplantao)) == '06:00:00') {
            $turno = 'Manhã';
        }
        if (date('H:i:s', strtotime($escala->horainicialplantao)) == '07:00:00') {
            $turno = 'Manhã';
        }
        if (date('H:i:s', strtotime($escala->horainicialplantao)) == '13:00:00') {
            $turno = 'Tarde';
        }
        if (date('H:i:s', strtotime($escala->horainicialplantao)) == '19:00:00') {
            $turno = 'Noite';
        }
        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($escala->nomesetor, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($escala->nome_profissional, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars(date('d/m/Y', strtotime($escala->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($turno, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center <?php echo($escala->batidaentrada ? 'bg-success' : 'bg-danger');?>"><span class="<?php echo($escala->batidaentrada_inserida ? 'bg-primary' : '');?>"><?php echo htmlspecialchars($escala->batidaentrada ? date('H:i:s', strtotime($escala->batidaentrada)) : ' - ', ENT_QUOTES, 'UTF-8'); ?></span></td>
                                                <td class="text-center <?php echo($escala->batidasaida ? 'bg-success' : 'bg-danger');?>"><span class="<?php echo($escala->batidasaida_inserida ? 'bg-primary' : '');?>"><?php echo htmlspecialchars($escala->batidasaida ? date('H:i:s', strtotime($escala->batidasaida)) : ' - ', ENT_QUOTES, 'UTF-8'); ?></span></td>
                                                <td>
                                                    <?php //echo anchor('admin/escalas/view/'.$escala->id, lang('actions_see'), array('class' => 'btn btn-primary btn-flat')); ?>
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