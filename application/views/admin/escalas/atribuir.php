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

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('escalas_attribute'); ?></h3>
                                    <p><cite><?php echo lang('escalas_attribute_description'); ?></cite></p>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-create_escala')); ?>
                                        <div class="form-group">
                                            <?php echo lang('escalas_unidadehospitalar', 'unidadehospitalar_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($unidadehospitalar_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_setor', 'setor_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($setor_id);?>
                                                <?php //echo form_multiselect($setor_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('profissionais_vinculo', 'vinculo', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($vinculo);?>
                                                <?php //echo form_multiselect($setor_id);?>
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
                                            <label class="col-sm-2 control-label"><?php echo lang('escalas_diasdasemana');?></label>
                                            <div class="col-sm-10">
                                                <div class="checkbox-inline">
                                                    <label class="notboldlabel">
                                                        <input type="checkbox" name="domingo" value="1"<?php echo($domingo == 1 ? 'checked' : ''); ?>>
                                                        <?php echo htmlspecialchars('Domingo'); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label class="notboldlabel">
                                                        <input type="checkbox" name="segunda" value="2"<?php echo($segunda == 2 ? 'checked' : ''); ?>>
                                                        <?php echo htmlspecialchars('Segunda'); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label class="notboldlabel">
                                                        <input type="checkbox" name="terca" value="3"<?php echo($terca == 3 ? 'checked' : ''); ?>>
                                                        <?php echo htmlspecialchars('Terça'); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label class="notboldlabel">
                                                        <input type="checkbox" name="quarta" value="4"<?php echo($quarta == 4 ? 'checked' : ''); ?>>
                                                        <?php echo htmlspecialchars('Quarta'); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label class="notboldlabel">
                                                        <input type="checkbox" name="quinta" value="5"<?php echo($quinta == 5 ? 'checked' : ''); ?>>
                                                        <?php echo htmlspecialchars('Quinta'); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label class="notboldlabel">
                                                        <input type="checkbox" name="sexta" value="6"<?php echo($sexta == 6 ? 'checked' : ''); ?>>
                                                        <?php echo htmlspecialchars('Sexta'); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label class="notboldlabel">
                                                        <input type="checkbox" name="sabado" value="7"<?php echo($sabado == 7 ? 'checked' : ''); ?>>
                                                        <?php echo htmlspecialchars('Sábado'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_turno', 'turno_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_dropdown($turno_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_filter'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/escalas/atribuir', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
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
                                    <?php
                                    if (sizeof($escalas) > 0) {
                                        echo(lang('escalas_unidadehospitalar')) . ": " . htmlspecialchars($escalas[0]->unidadehospitalar_razaosocial, ENT_QUOTES, 'UTF-8');
                                        echo('<br>');
                                        echo(lang('escalas_setor')) . ": " . htmlspecialchars($escalas[0]->setor_nome, ENT_QUOTES, 'UTF-8');
                                        echo '<button style="font-size:20px;float: right;" 
                                        class="btn btn-primary btn-publicar-escala text-center" 
                                        id="btn-publicar-escala"
                                        data_ini="'.$this->form_validation->set_value('datainicial').'" 
                                        data_fim="'.$this->form_validation->set_value('datafinal').'"
                                        turno="'.$this->input->post('turno_id').'" 
                                        setor="'.$this->form_validation->set_value('setor_id').'" 
                                        unidade="'.$this->form_validation->set_value('unidadehospitalar_id').'" 
                                        vinculo="'.$this->form_validation->set_value('vinculo').'">
                                        <i class="fa fa-paper-plane">
                                        </i>&nbsp;&nbsp;Publicar Escala
                                        </button>';
                                    } else {
                                        echo("A pesquisa não retornou resultados.");
                                    }
                                    ?> 
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('escalas_profissional');?></th>
                                                <th><?php echo lang('escalas_tipo_plantao');?></th>
                                                <th><?php echo lang('escalas_dataplantao');?></th>
                                                <th><?php echo lang('escalas_diadasemana');?></th>
                                                <th><?php echo lang('escalas_turno');?></th>
                                                <th><?php echo lang('escalas_horario');?></th>
                                                <th><?php echo lang('escalas_status');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
        <?php foreach ($escalas as $escala) : ?>
                                            <tr id="<?php echo("row_id_" . $escala->id);?>">
                                                <td>
                                                    <?php //echo form_dropdown($profissional_id);?>
                                                    <?php echo form_input(array('type'=>'hidden','id'=>'data_plantao_'.$escala->id, 'value'=>$escala->dataplantao));?>
                                                    <?php echo form_hidden('hora_plantao_'.$escala->id, $escala->horainicialplantao);?>
                                                    <?php echo form_dropdown($profissional_id, null, $escala->profissional_id);?>
                                                    <?php echo form_hidden('escala_id_' . $escala->id, $escala->id);?>
                                                </td>
                                                <td>
                                                    <?php echo form_dropdown($tipo_plantao, null, $escala->tipo_plantao);?>
                                                    <?php echo form_hidden('tipo_plantao_escala_id_' . $escala->id, $escala->id);?>
                                                </td>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($escala->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php 
                                                $turno = '';
                                                $hora_inicial_plantao = date('H:i:s', strtotime($escala->horainicialplantao));
                                                if ($hora_inicial_plantao == '06:00:00') {
                                                    $turno = 'Manhã';
                                                }
                                                if ($hora_inicial_plantao == '07:00:00') {
                                                    $turno = 'Manhã';
                                                }
                                                if ($hora_inicial_plantao == '13:00:00') {
                                                    $turno = 'Tarde';
                                                }
                                                if ($hora_inicial_plantao == '19:00:00') {
                                                    $turno = 'Noite';
                                        }
                                                ?>
                                                <td><?php echo htmlspecialchars($diasdasemana[date('w', strtotime($escala->dataplantao))], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($turno, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($escala->horainicialplantao)) . " a " . date('H:i', strtotime($escala->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php 
                                                    $condicao = '';
                                                    if ($escala->publicada == 1){
                                                        $condição = 'Publicada';
                                                    } else {
                                                        $condição = 'Despublicada';
                                                    }
                                                ?>
                                                <td><?php echo htmlspecialchars($condição, ENT_QUOTES, 'UTF-8'); ?></td>
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
    <div id="modal_vinculo" class="modal fade">
            <div class="modal-dialog modal-lg" style="width:50%;">
                <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <center><h4 class="modal-title">Selecione o Vinculo</h4></center>
                </div>

                <div class="modal-body">
                        <div class="form-group">
                            <center><label class="control-label">Este profissional possui 2 vinculos, favor selecione o vinculo a ser utilizado</label></center>                                <center>
                                <?php 
                                /*$data = array(
                                    '1'  => 'CEMERGE',
                                    '2' => 'SESA',
                                );*/
                                echo form_dropdown($this->data['vinculos_atribuir']); 
                                ?></center>
                                <span class="help-block"></span>
                        </div>
                        <div class="form-group text-center">
                        <button id="btn_vinculo" class="btn btn-success text-center btn-vinculo">
                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Selecionar</button>
                            <span class="help-block"></span>
                        </div>
                </div>
            </div>
        </div>
    </div>
