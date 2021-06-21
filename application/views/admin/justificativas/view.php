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
                <div class="print-header row">
                    <div class="col-lg-2 col-xs-2"><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/></div>
                    <div class="col-lg-10 col-xs-10 pull-right"><h3><?php echo ("Justificativas ".$justificativa->status); ?></h3></div>
                </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header with-border dontprint">
                                    <h3 class="box-title dontprint"><?php echo lang('justificativas_box_title'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <th colspan="2" class="text-center"><h3>JUSTIFICATIVA DE AUSÊNCIA DE BIOMETRIA</h3></th>
                                            </tr>
                                            <tr>
                                                <th><?php echo(lang('justificativas_profissional')); ?></th>
                                                <td><?php echo($profissional->nome); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo(lang('justificativas_setor')); ?></th>
                                                <td><?php echo($setor->nome); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo(lang('justificativas_data_plantao')); ?></th>
                                                <td><?php echo(date('d/m/Y', strtotime($justificativa->data_plantao))); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo(lang('justificativas_turno')); ?></th>
                                                <td><?php echo $justificativa->turno; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo(lang('justificativas_hora_entrada').' Registrada'); ?></th>
                                                <td><?php echo((isset($batida_entrada)) != '00:00:00' ? date('H:i', strtotime($batida_entrada)) : "Sem Registro"); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo(lang('justificativas_hora_saida').' Registrada'); ?></th>
                                                <td><?php echo((isset($batida_saida)) != '00:00:00' ? date('H:i', strtotime($batida_saida)) : "Sem Registro"); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo(lang('justificativas_hora_entrada'). " Justificada"); ?></th>
                                                <td><?php echo($justificativa->entrada_justificada != "00:00:00"  ? date('H:i', strtotime($justificativa->entrada_justificada)) : " - "); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo(lang('justificativas_hora_saida'). " Justificada"); ?></th>
                                                <td><?php echo($justificativa->saida_justificada && $justificativa->saida_justificada != '00:00:00' ? date('H:i', strtotime($justificativa->saida_justificada)) : " - "); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo(lang('justificativas_descricao')); ?></th>
                                                <td><?php echo(htmlspecialchars(nl2br($justificativa->descricao), ENT_QUOTES, 'UTF-8')); ?></td> 
                                            </tr>
                                            <?php if($justificativa->status == '2') {?> 
                                            <tr>
                                                <th><?php echo(lang('justificativas_recusa')); ?></th>
                                                <td><?php echo(htmlspecialchars(nl2br($justificativa->motivo_recusa), ENT_QUOTES, 'UTF-8')); ?></td>
                                            </tr><?php }; ?>
                                            <tr>
                                                <th><?php echo(lang('justificativas_status')); ?></th>
                                                <td><?php echo(($justificativa->status == 0) ? htmlspecialchars(('Aguardando Aprovação'), ENT_QUOTES, 'UTF-8') : ''); ?>
                                                    <?php echo(($justificativa->status == 1) ? htmlspecialchars(('Deferidas'), ENT_QUOTES, 'UTF-8') : ''); ?>
                                                    <?php echo(($justificativa->status == 2) ? htmlspecialchars(('Indeferidas'), ENT_QUOTES, 'UTF-8') : ''); ?>
                                                </td>
                                            </tr>
                                            <tr  class="dontprint">
                                                <td colspan='2' class="text-center"><a href="#" onclick="window.print();" class="btn btn-primary btn-flat dontprint">Imprimir</a>&nbsp;
                                                <?php echo anchor('admin/justificativas/edit/'.$justificativa->id, lang('actions_edit'), array('class' => 'btn btn-primary btn-flat dontprint')); ?>&nbsp;
                                                <?php echo ($justificativa->status == 0) ? anchor('admin/justificativas/aprovar/'.$justificativa->id, 'Deferir', array('class' => 'btn btn-success btn-flat dontprint')) : '';?>&nbsp;
                                                <?php echo ($justificativa->status != 2) ? anchor('admin/justificativas/edit_recusa/'.$justificativa->id, 'Indeferir', array('class' => 'btn btn-danger btn-flat dontprint')) : anchor('admin/justificativas/aprovar/'.$justificativa->id, 'Deferir', array('class' => 'btn btn-success btn-flat dontprint'));?>&nbsp;
                                                <?php echo "<a href='" . $_SERVER['HTTP_REFERER'] . "' class='btn btn-primary btn-flat dontprint'>Voltar</a>";?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="print-footer text-center">
                        <img src="<?php echo base_url($frameworks_dir . '/cemerge/images/assinatura.png'); ?>"/><br>
                        <span class="text-center"><?php echo $dia[$numero_dia].', '.$dia_mes.' de '.$mes[$numero_mes].' de '.$ano ?></span>
                </div>
            </div>
