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
        <br>
<section style="min-height: calc(100vh - 83px);" class="light-bg">
    <div class="container">

        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_profissionais" role="tab" data-toggle="tab">Profissionais</a></li>
            <li><a href="#tab_eventos" role="tab" data-toggle="tab">Eventos</a></li>
            <li><a href="#tab_folhas" role="tab" data-toggle="tab">Folha</a></li>
            <li><a href="#tab_relatorios" role="tab" data-toggle="tab">Relatórios</a></li>
        </ul>

        <div class="tab-content">
            
            <div id="tab_profissionais" class="tab-pane active">
                <div class="container-fluid">
                    <h2 class="text-center"><strong>Gerenciar Profissionais</strong></h2>
                    <a id="btn_add_profissional" href="http://localhost/sgc/admin/profissionais/create" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Cadastrar profissional</i></a>
                    <table id="dt_profissional" class="table table-striped table-bordered">
                        <thead>
                            <tr class="tableheader">
                                <th class="dt-center text-center" >Matricula</th>
                                <th class="dt-center text-center">Nome</th>
                                <th class="dt-center text-center" >CRM</th>
                                <th class="dt-center text-center">E-mail</th>
                                <th class="dt-center no-sort text-center">Ações</th>
                            </tr> 
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="tab_eventos" class="tab-pane">
                <div class="container-fluid">
                    <h2 class="text-center"><strong>Gerenciar Eventos da Folha</strong></h2>
                    <a id="btn_add_evento" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Adicionar Evento</i></a>
                    <table id="dt_eventos" class="table table-striped table-bordered">
                        <thead>
                            <tr class="tableheader">
                                <th class="dt-center text-center">ID</th>
                                <th class="dt-center text-center">Nome</th>
                                <th class="dt-center text-center">Tipo</th>
                                <th class="dt-center text-center">Fixo</th>
                                <th class="dt-center text-center">%</th>
                                <th class="dt-center text-center">REF</th>
                                <th class="dt-center no-sort text-center">Ações</th>
                            </tr> 
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            
        <div id="tab_folhas" class="tab-pane">
            <div class="container-fluid">
                <h2 class="text-center"><strong>Gerenciar Folha</strong></h2>
                <a id="btn_add_folha" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Nova Folha</i></a>
                <table id="dt_folha" class="table table-striped table-bordered">
                    <thead>
                        <tr class="tableheader">
                            <th width="10%" class="dt-center text-center" >Ano</th>
                            <th class="dt-center text-center" >Mês</th>
                            <th width="20%" class="dt-center text-center">Tipo</th>
                            <th width="20%" class="dt-center text-center">Total</th>
                            <th width="07%" class="dt-center no-sort text-center">Ações</th>
                        </tr> 
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>


        <div id="tab_relatorios" class="tab-pane">
            <div class="container-fluid">
                <center><table style="margin: 100px; border: 6px solid #ecf0f5;">
                    <tr>
                        <td></td>
                        <td style="color: white; background-color: green; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-user fa-5x" aria-hidden="true"></i><br>Lista de profissionais</center></td>
                        <td style="color: white; background-color: Orange; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-barcode fa-5x" aria-hidden="true"></i><br>Notas Fiscais</center></td>
                        <td style="color: white; background-color: royalblue; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-exchange fa-5x" aria-hidden="true"></i><br>Movimentação </center></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="color: white; background-color: green; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-address-card fa-5x" aria-hidden="true"></i><br>Produtos por folha</center></td>
                        <td style="color: white; background-color: firebrick; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-battery-quarter fa-5x" aria-hidden="true"></i><br>Produtos Sem Estoque</center></td>
                        <td style="color: white; background-color: royalblue; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-battery-half fa-5x" aria-hidden="true"></i><br>Produtos a vencer</center></td>
                        <td style="color: white; background-color: green; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-id-badge fa-5x" aria-hidden="true"></i><br>Equip. por profissional</center></td>
                        <td style="color: white; background-color: orange; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-building fa-5x" aria-hidden="true"></i><br>Equip. por setor</center></td>
                    </tr>
                    <tr>
                        <td style="color: white; background-color: royalblue; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i><br>Bens Móveis</center></td>
                        <td style="color: white; background-color: orange; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-tasks fa-5x" aria-hidden="true"></i><br>Bens de Consumo</center></td>
                        <td style="color: white; background-color: green; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-reply fa-5x" aria-hidden="true"></i><br>Entrada de Produtos</center></td>
                        <td style="color: white; background-color: firebrick; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-share fa-5x" aria-hidden="true"></i><br>Saída de Produtos</center></td>
                        <td style="color: white; background-color: royalblue; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-plus-square fa-5x" aria-hidden="true"></i><br>Materiais médicos</center></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="color: white; background-color: firebrick; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-laptop fa-5x" aria-hidden="true"></i><br>Materiais Ieventosormatica</center></td>
                        <td style="color: white; background-color: royalBlue; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-shopping-cart fa-5x" aria-hidden="true"></i><br>Compras recentes</center></td>
                        <td style="color: white; background-color: green; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-recycle fa-5x" aria-hidden="true"></i><br>Materiais Doação</center></td>                        
                        
                        <td></td>
                    </tr>
                </table></center>
            </div>
        </div>
    </div>
</section>
        <div id="modal_evento" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">Novo Evento</h4>
                </div>

                <div class="modal-body">
                    <form id="form_evento">

                        <input id="evento_id" name="evento_id" hidden>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nome</label>
                            <div class="col-lg-10">
                                <input id="evento_nome" name="evento_nome" class="form-control" maxlength="44" onKeyPress="if(this.value.length==44) return false;">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tipo</label>
                            <div class="col-lg-10">
                                    <!--<?php //echo form_dropdown($tipo_evento_select);?>-->
                                        <input type="radio" id="C" name="evento_tipo" value="C">
                                        <label for="male">&nbsp;&nbsp;Crédito&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        <input type="radio" id="D" name="evento_tipo" value="D">
                                        <label for="female">&nbsp;&nbsp;Débito</label><br>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Fixo</label>
                            <div class="col-lg-10">
                                    <!--<?php //echo form_dropdown($tipo_evento_select);?>-->
                                        <input type="radio" id="fixo_sim" name="evento_fixo" value="1">
                                        <label for="male">&nbsp;&nbsp;Sim&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        <input type="radio" id="fixo_nao" name="evento_fixo" value="0">
                                        <label for="female">&nbsp;&nbsp;Não</label><br>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Percentual</label>
                            <div class="col-lg-10">
                                        <input type="radio" id="sim" name="evento_percentual" value="1">
                                        <label for="male">&nbsp;&nbsp;Sim&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        <input type="radio" id="nao" name="evento_percentual" value="0">
                                        <label for="female">&nbsp;&nbsp;Não</label><br>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Valor Referência</label>
                            <div class="col-lg-10">
                                <input id="evento_valor" name="evento_valor" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Valor Base</label>
                            <div class="col-lg-10">
                                    <?php echo form_dropdown($eventos_select);?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Incidência</label>
                            <div class="col-lg-10">
                            <input type = "checkbox" id="IR" name = "evento_incidencias" value = "2">
                            <label for = "IR">&nbsp;&nbsp;IR&nbsp;&nbsp;</label>
                            <input type = "checkbox" id="INSS" name = "evento_incidencias" value = "3">
                            <label for = "INSS">&nbsp;&nbsp;INSS&nbsp;&nbsp;</label>
          
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group text-center">
                        <button type="submit" id="btn_save_eventos" class="btn btn-primary text-center">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button>
                            <span class="help-block"></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="modal_folha" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">Folha de Pagamentos</h4>
                </div>

                <div class="modal-body">
                    <form id="form_folha">

                        <input id="folha_id" name="folha_id" hidden>
                        <input id="profissional_id" name="profissional_id" hidden>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Ano</label>
                            <div class="col-lg-10">
                                    <?php echo form_dropdown($anos_select);?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Mês</label>
                            <div class="col-lg-10">
                                    <?php echo form_dropdown($meses_select);?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tipo</label>
                            <div class="col-lg-10">
                                    <?php echo form_dropdown($tipos_folha_select);?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group text-center">
                        <button type="submit" id="btn_save_folhas" class="btn btn-primary text-center">
                            <i class="fa fa-money"></i>&nbsp;&nbsp;Lançador</button>
                            <button type="submit" id="btn_limpar_folhas_img" class="btn btn-primary text-center">
                            <i class="fa fa-file-o"></i>&nbsp;&nbsp;Contra Cheque</button>
                            <span class="help-block"></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="modal_lancador_profissional" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">lancador_profissional de Pagamentos</h4>
                </div>

                <div class="modal-body">
                    <form id="form_lancador_profissional">

                        <input id="folha_id" name="folha_id" hidden>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Ano</label>
                            <div class="col-lg-10">
                                    <?php echo form_dropdown($anos_select);?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Mês</label>
                            <div class="col-lg-10">
                                    <?php echo form_dropdown($meses_select);?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tipo</label>
                            <div class="col-lg-10">
                                    <?php echo form_dropdown($tipos_folha_select);?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group text-center">
                        <button type="submit" id="btn_save_lancador_profissional" class="btn btn-primary text-center">
                            <i class="fa fa-money"></i>&nbsp;&nbsp;Gerar</button>
                            <span class="help-block"></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<div></div></div>