<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
            <script type="text/javascript"> 
                // unblock when ajax activity stops 
                $(document).ajaxStop($.unblockUI); 
            
                $(document).ready(function() { 
                    $('#btn_submit').click(function() { 
                        $.blockUI({
                            message: '<h4><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/loading.gif'); ?>" /> Aguarde</h4>',
                            css: { 
                                width: '40%',
                                left: '30%',
                            }
                        });
                    }); 
                });
            </script>

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

                <section class="content dontprint">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('escalas_conference'); ?></h3>
                                    <p><cite><?php echo lang('escalas_conference_description'); ?></cite></p>
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
                                            <label class="col-sm-2 control-label"><?php echo lang('escalas_turno');?></label>
                                            <div class="col-sm-10">
                                                <div class="checkbox-inline">
                                                    <label class="notboldlabel">
                                                        <input type="checkbox" name="manha" value="1"<?php echo($manha == 1 ? 'checked' : ''); ?>>
                                                        <?php echo htmlspecialchars('Manhã'); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label class="notboldlabel">
                                                        <input type="checkbox" name="tarde" value="1"<?php echo($tarde == 1 ? 'checked' : ''); ?>>
                                                        <?php echo htmlspecialchars('Tarde'); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label class="notboldlabel">
                                                        <input type="checkbox" name="noite" value="1"<?php echo($noite == 1 ? 'checked' : ''); ?>>
                                                        <?php echo htmlspecialchars('Noite'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('id' => 'btn_submit', 'name' => 'btn_submit', 'type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_filter'))); ?>
                                                    <?php echo form_button(array('id' => 'btn_print', 'name' => 'btn_print', 'onclick' => 'window.print(); return false;', 'type' => 'button', 'class' => 'btn btn-default btn-flat', 'content' => lang('actions_print'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/escalas/conferencia', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
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
                        <div class="col-lg-10 col-xs-10 pull-right"><h3>Conferência de plantões</h3></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <?php foreach ($frequencias as $setor => $freqs) : ?>
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <?php echo(lang('escalas_unidadehospitalar') . ": " . $freqs[0]->nomeunidade . "&nbsp;-&nbsp;"); ?>
                                        <?php echo(lang('escalas_setor') . ": " . $setor); ?>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="w-15"><?php echo lang('escalas_profissional');?></th>
                                                <th scope="col" class="text-center w-5"><?php echo lang('escalas_dataplantao');?></th>
                                                <th scope="col" class="text-center w-5"><?php echo lang('escalas_turno');?></th>
                                                <th scope="col" class="text-center w-5"><?php echo lang('escalas_horaentrada');?></th>
                                                <th scope="col" class="text-center w-5"><?php echo lang('escalas_horasaida');?></th>
                                                <th scope="col" class="text-center w-5"><?php echo('Status');?></th>
                                                <th scope="col" class="text-center w-15 dontprint"><?php echo lang('escalas_frequencias_sem_escala');?></th>
                                                <th scope="col" class="text-center w-20 dontprint"><?php echo lang('escalas_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php foreach ($freqs as $freq) : ?>
                                        <?php
                                        $turno = '';
                                        $hora_inicial_plantao = date('H:i:s', strtotime($freq->horainicialplantao));
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
                                        $tipo_batida_escala = 'Incompleto';
                                        // Frequências inseridas MT
                                        $frequencias_inseridas_mt = null;
                                        if (!empty($freq->frequencias_inseridas_mt)) {
                                            $frequencias_inseridas_mt = $freq->frequencias_inseridas_mt[0];
                                        }
                                        if (($freq->escala_id_entrada && $freq->escala_id_saida)
                                            or ($freq->escala_id_entrada && (!is_null($frequencias_inseridas_mt) && $frequencias_inseridas_mt->tipobatida == 4))
                                            or ($freq->escala_id_saida && (!is_null($frequencias_inseridas_mt) && $frequencias_inseridas_mt->tipobatida == 3)
                                            or ($freq->vinculo_id_profissional == 2))
                                        ) {
                                            $tipo_batida_escala = 'OK';
                                        }
                                        // Frequências inseridas justificativas
                                        $frequencias_justificadas = null;
                                        $frequencia_justificada_entrada = null;
                                        $frequencia_justificada_saida = null;
                                        if (!empty($freq->frequencias_justificadas)) {
                                            $frequencias_justificadas = $freq->frequencias_justificadas[0];
                                            foreach ($freq->frequencias_justificadas as $fj) {
                                                if ($fj->tipobatida == 5) {
                                                    $frequencia_justificada_entrada = $fj;
                                                } else if ($fj->tipobatida == 6) {
                                                    $frequencia_justificada_saida = $fj;
                                                }
                                            }
                                        }
                                        if (($freq->escala_id_entrada && $freq->escala_id_saida)
                                            or (!is_null($frequencia_justificada_entrada) && $frequencia_justificada_entrada->tipobatida == 5)
                                            or (!is_null($frequencia_justificada_saida) && $frequencia_justificada_saida->tipobatida == 6)
                                            or ($freq->vinculo_id_profissional == 2)
                                        ) {
                                            $tipo_batida_escala = 'OK';
                                        }
                                        if ($freq->status == 2) {
                                            $tipo_batida_escala = '<a href=' . site_url('admin/justificativas/validar/' . $freq->id) . ' target="_blank">Aguardando<br>Justificativa</a>';
                                        }
                                        if ($freq->status == 3) {
                                            $tipo_batida_escala = 'Falta';
                                            $tipo_falta = null;
                                            if ($freq->falta->tipo_falta == 1) {
                                                $tipo_falta = 'Justificada';
                                            } else if ($freq->falta->tipo_falta == 2) {
                                                $tipo_falta = 'Não Justificada';
                                            }
                                        }
                                        if (!$freq->nome_profissional) {
                                            $tipo_batida_escala = 'Vago';
                                        }
                                        // Trocas e passagens para title
                                        $trocas_passagens = array();
                                        if (!empty($freq->trocas_passagens)) {
                                            array_push($trocas_passagens, "Trocas e passagens:");
                                            foreach ($freq->trocas_passagens as $tp) {
                                                array_push($trocas_passagens, $tp->profissional_titular_nomecurto . "&nbsp;para&nbsp;" . $tp->profissional_substituto_nomecurto);
                                            }
                                        }
                                        ?>
                                            <tr>
                                                <td><span class="<?php echo(!empty($trocas_passagens) ? 'title_help' : '');?>" title="<?php echo(implode('&#10;', $trocas_passagens)); ?>"><?php echo(htmlspecialchars($freq->nome_profissional ? $freq->nome_profissional : '-', ENT_QUOTES, 'UTF-8')); ?></span></td>
                                                <td class="text-center"><?php echo htmlspecialchars(date('d/m/Y', strtotime($freq->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($turno, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center" <?php echo($freq->escala_id_entrada ? 'bg-success' : 'bg-danger'); ?>>
                                                    <?php
                                                    if (!is_null($freq->dt_frq_entrada)) {
                                                        echo(htmlspecialchars(date('H:i:s', strtotime($freq->dt_frq_entrada))));
                                                    } else if ($freq->vinculo_id_profissional == 2) {
                                                        echo(htmlspecialchars('Sesa', ENT_QUOTES, 'UTF-8'));
                                                    } else if (!empty($freq->frequencias_inseridas_mt)) {
                                                        if ($freq->frequencias_inseridas_mt[0]->tipobatida == 3) {
                                                            echo("<span class='title_help' title='* Batidas inseridas automaticamente nos plantões MT do mesmo profissional no mesmo setor.'>" . htmlspecialchars(date('H:i:s', strtotime($freq->frequencias_inseridas_mt[0]->datahorabatida)) . "*") . "</span>");
                                                        }
                                                    } else if (!empty($freq->frequencias_justificadas)) {
                                                        if ($frequencia_justificada_entrada->tipobatida == 5) {
                                                            echo("<span class='title_help' title='** Batidas inseridas mediante justificativa.'>" . htmlspecialchars(date('H:i:s', strtotime($frequencia_justificada_entrada->datahorabatida)) . "**") . "</span>");
                                                        }
                                                    } else {
                                                        echo(htmlspecialchars(' - ', ENT_QUOTES, 'UTF-8'));
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center" <?php echo($freq->escala_id_saida ? 'bg-success' : 'bg-danger'); ?>>
                                                    <?php
                                                    if (!is_null($freq->dt_frq_saida)) {
                                                        echo(htmlspecialchars(date('H:i:s', strtotime($freq->dt_frq_saida))));
                                                    } else if ($freq->vinculo_id_profissional == 2) {
                                                        echo(htmlspecialchars('Sesa', ENT_QUOTES, 'UTF-8'));
                                                    } else if (!empty($freq->frequencias_inseridas_mt)) {
                                                        if ($freq->frequencias_inseridas_mt[0]->tipobatida == 4) {
                                                            echo("<span class='title_help' title='* Batidas inseridas automaticamente nos plantões MT do mesmo profissional no mesmo setor.'>" . htmlspecialchars(date('H:i:s', strtotime($freq->frequencias_inseridas_mt[0]->datahorabatida)) . "*") . "</span>");
                                                        }
                                                    } else if (!empty($freq->frequencias_justificadas)) {
                                                        if ($frequencia_justificada_saida->tipobatida == 6) {
                                                            echo("<span class='title_help' title='** Batidas inseridas mediante justificativa.'>" . htmlspecialchars(date('H:i:s', strtotime($frequencia_justificada_saida->datahorabatida)) . "**") . "</span>");
                                                        }
                                                    } else {
                                                        echo(htmlspecialchars(' - ', ENT_QUOTES, 'UTF-8'));
                                                    }
                                                    ?>
                                                </td>
                                                <td title="<?php echo($freq->falta ? $tipo_falta : ''); ?>" class="text-center <?php echo($tipo_batida_escala == "OK" ? 'bg-success' : 'bg-danger'); ?><?php echo($freq->falta ? ' title_help ' : ''); ?>">
                                                    <?php echo($tipo_batida_escala); ?>
                                                </td>
                                                <td class="dontprint">
                                                    <?php
                                                    if ($tipo_batida_escala != "OK") {
                                                        foreach ($freq->frequencias_sem_escala as $f) {
                                                            echo('<div class="frequenciassemescala">');
                                                            echo(date('d/m/Y H:i:s', strtotime($f->dt_frq)) . " - " . $f->nome_curto_profissional);
                                                            if ($f->crm == $freq->crm_profissional) {
                                                                $url_corrigir = "corrigirfrequenciaescala/" . $freq->id . "/" . $f->cd_ctl_frq;
                                                                echo("&nbsp;<a onclick='return confirm(\"Deseja realmente aceitar e processar esta batida?\");' href='" . $url_corrigir . "' class='label label-success' target='_blank'>Aceitar</a><br>");
                                                            } else {
                                                                $url_extra = "criarplantaoextra/" . $freq->id . "/" . $f->cd_ctl_frq . "/" . $f->id_profissional;
                                                                echo("&nbsp;<a onclick='return confirm(\"Deseja realmente criar um plantão extra neste setor a partir desta batida?\");' href='" . $url_extra . "' class='label label-info' target='_blank'>Extra</a>");
                                                                $url_ignorar = "ignorarbatida/" . $f->cd_ctl_frq;
                                                                echo("&nbsp;<a onclick='return confirm(\"Deseja realmente ignorar esta batida?\");' href='" . $url_ignorar . "' class='label label-warning' target='_blank'>Ignorar</a><br>");
                                                            }
                                                            echo('</div>');
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td class="dontprint text-center">
                                                    <?php echo(($freq->vinculo_id_profissional == 2 && $tipo_batida_escala != "Vago") ? anchor('admin/plantoes/cederplantaoeprocessar/'.$freq->id, lang('actions_to_give_in'), array('class' => 'btn btn-primary btn-flat', 'target' => '_blank', 'title' => 'Ceder o plantão para outro profissional')) . '&nbsp;' : ''); ?>
                                                    <?php echo(($tipo_batida_escala != "OK" && $tipo_batida_escala != "Vago" && $freq->status != 1) ? anchor('admin/plantoes/cederplantaoeprocessar/'.$freq->id, lang('actions_to_give_in'), array('class' => 'btn btn-primary btn-flat', 'target' => '_blank', 'title' => 'Ceder o plantão para outro profissional')) . '&nbsp;' : ''); ?>
                                                    <?php echo(($tipo_batida_escala != "OK" && $tipo_batida_escala != "Vago" && $freq->status != 1 && strtotime(date($freq->dataplantao)) < strtotime(date('Y-m-d')) ) ? anchor('admin/escalas/aguardarjustificativa/'.$freq->id, lang('actions_justificativa'), array('class' => 'btn btn-primary btn-flat', 'target' => '_blank', 'title' => 'Aguardar justificativa')) . '&nbsp;' : ''); ?>
                                                    <?php echo(($tipo_batida_escala != "OK" && $tipo_batida_escala != "Falta" && $tipo_batida_escala != "Vago" && $freq->status != 1 && strtotime(date($freq->dataplantao)) < strtotime(date('Y-m-d'))) ? anchor('admin/escalas/registrarfalta/'.$freq->id, lang('actions_falta'), array('class' => 'btn btn-primary btn-flat', 'target' => '_blank', 'title' => 'Registrar falta')) . '&nbsp;' : ''); ?>
                                                    <?php echo(($tipo_batida_escala == "Falta" && $freq->status == 3) ? anchor('admin/escalas/retirarfalta/'.$freq->id, lang('actions_removerfalta'), array('class' => 'btn btn-primary btn-flat', 'target' => '_blank', 'title' => 'Remover falta', 'onclick' => 'return confirm(\'Tem certeza que deseja remover a falta do usuário '.$freq->nome_profissional.'?\');')) . '&nbsp;' : ''); ?>
                                                    <?php echo(($tipo_batida_escala != "Vago" && strtotime(date($freq->dataplantao)) <= strtotime(date('Y-m-d'))) ? anchor('admin/escalas/desvincularescalaefrequencias/'.$freq->id, lang('actions_desvincular'), array('class' => 'btn btn-primary btn-flat', 'target' => '_blank', 'title' => 'Desvincular a escala das frequências e vice-versa', 'onclick' => 'return confirm(\'Tem certeza que deseja desvincular esta escala de suas frequências e vice-versa?\');')).'&nbsp;': ''); ?>
                                                </td>
                                            </tr>
                                    <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endforeach;?>
                            </div>
                            <?php echo(!empty($frequencias) ? '* Batidas inseridas automaticamente nos plantões MT do mesmo profissional no mesmo setor.<br>' : ''); ?>
                            <?php echo(!empty($frequencias) ? '** Batidas inseridas mediante justificativa.' : ''); ?>
                        </div>
                    </div>
                </section>
            </div>