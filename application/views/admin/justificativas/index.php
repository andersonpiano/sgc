<?php
defined('BASEPATH') OR exit('No direct script access allowed');

setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
date_default_timezone_set( 'America/Sao_Paulo' );
$numero_dia = date('w')*1;
$dia_mes = date('d');
$numero_mes = date('m')*1;
$ano = date('Y');
$dia = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
$mes = array('', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

?>

            <div class="content-wrapper">
                <section class="content-header dontprint">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>
                <?php if ($this->session->flashdata('message')) : ?>
                <section class="content-header dontprint">
                    <div class="alert bg-warning alert-dismissible" role="alert">
                        <?php echo($this->session->flashdata('message') ? $this->session->flashdata('message') : '');?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </section>
                <?php endif; ?>
                <div class="print-header row">
                    <div class="text-center"><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/></div>
                    <div ><h3 class="text-center"><?php 
                        switch ($this->input->post('status')) {
                            case 0:
                                echo ("Justificativas Aguardando Aprovação");  
                                break;
                            case 1:
                                echo ("Justificativas Deferidas"); 
                                break;
                            case 2:
                                echo ("Justificativas Indeferidas"); 
                                break;
                            case 3:
                                    echo ("Justificativas"); 
                                    break;
                        }               
                    
                    ?></h3>
                    <h3 class="text-center">Periodo: <?php echo date('d/m/Y', strtotime($this->input->post('data_plantao_inicio'))); ?> a <?php echo date('d/m/Y', strtotime($this->input->post('data_plantao_fim'))); ?> </h3></div>
                </div>
                    <section class="content dontprint">
                    <div class="row dontprint">
                        <div class="col-md-12 dontprint">
                             <div class="box dontprint">
                                <div class="box-header with-border">
                                    <h3 class="box-title dontprint"><?php echo lang('justificativas_find'); ?></h3>
                                </div>
                                <div class="box-body dontprint">
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-find_justificativa')); ?>
                                        <div class="form-group dontprint">
                                            <?php echo lang('justificativas_data_inicio', 'data_plantao_inicio', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo(form_input($data_plantao_inicio));?>
                                            </div>
                                        </div>
                                        <div class="form-group dontprint">
                                            <?php echo lang('justificativas_data_fim', 'data_plantao_fim', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo(form_input($data_plantao_fim));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_status', 'status', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_dropdown($status);?>
                                            </div>
                                        </div>
                                        <div class="form-group dontprint">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat dontprint', 'content' => lang('actions_find'))); ?>
                                                    <td colspan="2" class="text-center"><a href="#" onclick="window.print();" class="btn btn-primary btn-flat dontprint">Imprimir</a></td>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat dontprint', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/justificativas', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat dontprint')); ?>
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title dontprint">
                                            <button class="btn btn-block btn-primary btn-flat dontprint btn-justificativas-pendentes" id="justificativas-pendentes">Justificativas Pendentes</button>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('justificativas_data_plantao');?></th>
                                                <th><?php echo lang('justificativas_turno');?></th>
                                                <th><?php echo lang('justificativas_setor');?></th>
                                                <th><?php echo lang('justificativas_profissional');?></th>
                                                <th><?php echo lang('justificativas_hora_entrada');?></th>
                                                <th><?php echo lang('justificativas_hora_saida');?></th>
                                                <th><?php echo lang('justificativas_status');?></th>
                                                <th class="dontprint"><?php echo lang('justificativas_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($justificativas as $justificativa):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($justificativa->data_inicial_plantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($justificativa->turno, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($justificativa->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($justificativa->nome_profissional, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php 
                                                if (date('H:i', strtotime($justificativa->entrada_sistema)) != '00:00')
                                                { 
                                                    echo (date('H:i', strtotime($justificativa->entrada_justificada)) != '00:00' ? date('H:i', strtotime($justificativa->entrada_justificada)) : date('H:i', strtotime($justificativa->entrada_sistema)) ); 
                                                } else {
                                                    echo (date('H:i', strtotime($justificativa->entrada_justificada)) != '00:00' ? date('H:i', strtotime($justificativa->entrada_justificada)) : '-' ); 
                                                }?></td> 
                                                <td><?php 
                                                if (date('H:i', strtotime($justificativa->saida_sistema)) != '00:00')
                                                { 
                                                    echo (date('H:i', strtotime($justificativa->saida_justificada)) != '00:00' ? date('H:i', strtotime($justificativa->saida_justificada)) :  date('H:i', strtotime($justificativa->saida_sistema)) ); 
                                                } else { 
                                                    echo (date('H:i', strtotime($justificativa->saida_justificada)) != '00:00' ? date('H:i', strtotime($justificativa->saida_justificada)) :  '-'); 
                                                } ?></td>
                                                <td><?php echo htmlspecialchars($justificativa->status, ENT_QUOTES, 'UTF-8');?></td>
                                                <td class="dontprint">
                                                    <button class="btn btn-block btn-primary btn-flat dontprint btn-justificativas-view" id="justificativas-view" medico="<?php echo $justificativa->profissional_id?>" justificativa="<?php echo $justificativa->id?>">Ver</button>
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
            <div class="print-footer text-center">
                <span class="text-center"><?php echo /*$dia[$numero_dia].', <br>'.*/ $dia_mes.' de '.$mes[$numero_mes].' de '.$ano ?></span><br><br>
                        <img class="text-center" style="z-index: 1; top: 10px;" src="<?php echo base_url($frameworks_dir . '/cemerge/images/assinatura.png'); ?>"/><br>
                        <span class="text-center "><bold>Breno Douglas Dantas Oliveira</bold> <br> Coordenação Médica da Emergência
                        <br>do Hospital de Messejana<br>CREMEC 15.461</span><br>
                        
                </div>

    <div id="modal_justificativas_pendentes" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <center><h4 class="modal-title">Plantões com justificativa pendente</h4></center>
                </div>

                <div class="modal-body">
                        <table id="dt_pendentes" class="table table-striped table-bordered">
                        <thead>
                            <tr class="tableheader">
                                <th style="width:20%" data-sort='YYYYMMDD' class="dt-center text-center">Data</th>
                                <th style="width:40%" class="dt-center text-center">Setor</th>
                                <th style="width:40%" class="dt-center text-center">Médico</th>
                                <th style="width:20%" class="dt-center no-sort text-center">Turno</th>
                                <th style="width:20%" class="dt-center no-sort text-center">Açoes</th>
                            </tr> 
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="modal_justificativas_view" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <center><h4 class="modal-title">JUSTIFICATIVA DE AUSÊNCIA DE BIOMETRIA</h4></center>
                </div>
                <div class="box-body">
                    <table class="table table-striped table-hover">
                        <tbody>
                            <tr>
                                <th><?php echo(lang('justificativas_profissional')); ?></th>
                                <td id="nome_profissional"></td>
                            </tr>
                            <tr>
                                <th><?php echo(lang('justificativas_setor')); ?></th>
                                <td id="nome_setor"></td>
                            </tr>
                            <tr>
                                <th><?php echo(lang('justificativas_data_plantao')); ?></th>
                                <td id="data"></td>
                            </tr>
                            <tr>
                                <th><?php echo(lang('justificativas_turno')); ?></th>
                                <td id="turno_plantao"></td>
                            </tr>
                            <tr>
                                <th><?php echo(lang('justificativas_hora_entrada').' Registrada'); ?></th>
                                <td id="hora_entrada_sistema"></td>
                            </tr>
                            <tr>
                                <th><?php echo(lang('justificativas_hora_saida').' Registrada'); ?></th>
                                <td id="hora_saida_sistema"></td>
                            </tr>
                            <tr>
                                <th><?php echo(lang('justificativas_hora_entrada'). " Justificada"); ?></th>
                                <td id="hora_entrada_justificada"></td>
                            </tr>
                            <tr>
                                <th><?php echo(lang('justificativas_hora_saida'). " Justificada"); ?></th>
                                <td id="hora_saida_justificada"></td>
                            </tr>
                            <tr>
                                <th><?php echo(lang('justificativas_descricao')); ?></th>
                                <td id="descricao"></td> 
                            </tr>

                            <tr id="sumir">
                                <th><?php echo(lang('justificativas_recusa')); ?></th>
                                <td id="motivo"></td>
                            </tr>

                            <tr>
                                <th><?php echo(lang('justificativas_status')); ?></th>
                                <td id="condicao"></td>
                            </tr>
                            <tr  class="dontprint">
                                 <td colspan='2' class="text-center"><!--<a href="#" onclick="window.print();" class="btn btn-primary btn-flat dontprint">Imprimir</a>&nbsp; -->
                                <?php //echo anchor('admin/justificativas/edit/'.$justificativa->id, lang('actions_edit'), array('class' => 'btn btn-primary btn-flat dontprint btn-justificativas-edit')); ?>&nbsp;
                                <?php echo anchor('', 'Editar', array('class' => 'btn btn-primary btn-flat dontprint', 'id' => 'editar')) ?>&nbsp;
                                <?php echo ($justificativa->status == 0) ? anchor('', 'Deferir', array('class' => 'btn btn-success btn-flat dontprint', 'id' => 'aprovar')) : '';?>&nbsp;
                                <?php echo ($justificativa->status != 2) ? anchor('', 'Indeferir', array('class' => 'btn btn-danger btn-flat dontprint', 'id'=> 'desaprovar')) : anchor('', 'Deferir', array('class' => 'btn btn-success btn-flat dontprint', 'id' => 'aprovar'));?>&nbsp;
                                <?php echo ($justificativa->status == 0) ? anchor('', 'Ignorar', array('class' => 'btn btn-light btn-flat dontprint', 'id' => 'ignorar')) : '';?>&nbsp;
                                <button type="button" class="close btn btn-primary btn-flat dontprint" data-dismiss="modal">Voltar</button>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
<div id="modal_justificativas_edit" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <center><h4 class="modal-title">JUSTIFICATIVA DE AUSÊNCIA DE BIOMETRIA</h4></center>
                </div>

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('justificativas_edit'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(uri_string(), array('class' => 'form-horizontal', 'id' => 'form-edit_justificativas')); ?>
                                        <?php //echo(form_hidden('profissional_id', $profissional_id)); ?>
                                        <?php //echo form_hidden('id', $justificativa->id);?>
                                        <?php //echo form_hidden($csrf); ?>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_profissional', 'profissional_nome', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo('Nome do Profissional');?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_cooperativa', 'cooperativa', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo('CEMERGE');?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_setor', 'setor_id', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo 'Setor';?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_data_plantao', 'data_plantao', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-3">
                                                <input type="date" class="form-control" name="date_out">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_hora_entrada', 'hora_entrada', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-3">
                                                <input type="time" class="form-control" name="time_in">
                                            </div>
                                            <span><cite><?php echo('Preencha caso esteja justificando o horário de entrada.'); ?></cite></span>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_hora_saida', 'hora_saida', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-3">
                                                <input type="time" class="form-control" name="time_out">
                                            </div>
                                            <span><cite><?php echo('Preencha caso esteja justificando o horário de saída.'); ?></cite></span>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('justificativas_descricao', 'descricao', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-6">
                                                <input type="textarea" class="form-control" name="time_in">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_save'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/justificativas', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php echo form_close();?>
                                </div>
                            </div>
                         </div>
                    </div>
                </section>

            </div>
        </div>
    </div>