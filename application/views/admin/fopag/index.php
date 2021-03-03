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
                    <h2 class="text-center"><strong>Gerenciar profissionais</strong></h2>
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
                    <h2 class="text-center"><strong>Gerenciar Notas Fiscais</strong></h2>
                    <a id="btn_add_eventos" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Adicionar Evento</i></a>
                    <table id="dt_eventos" class="table table-striped table-bordered">
                        <thead>
                            <tr class="tableheader">
                                <th class="dt-center text-center">Data</th>
                                <th class="dt-center text-center">Código</th>
                                <th class="dt-center text-center">profissional</th>
                                <th class="dt-center text-center">Valor</th>
                                <th class="dt-center text-center">Anexo</th>
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
                <h2 class="text-center"><strong>Gerenciar folhas</strong></h2>
                <a id="btn_add_categoria" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Adicionar Categoria</i></a>
                <table id="dt_categoria" class="table table-striped table-bordered">
                    <thead>
                        <tr class="tableheader">
                            <th width="10%" class="dt-center text-center" >Código</th>
                            <th class="dt-center text-center" >Nome</th>
                            <th width="20%" class="dt-center text-center">Tipo</th>
                            <th width="07%" class="dt-center no-sort text-center">Ações</th>
                        </tr> 
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab_produtos" class="tab-pane">
            <div class="container-fluid">
                <h2 class="text-center"><strong>Gerenciar Produtos</strong></h2>
                <a id="btn_add_produto" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Adicionar Produto</i></a>
                <table id="dt_produtos" class="table table-striped table-bordered">
                    <thead>
                        <tr class="tableheader">
                            <th width="10%" class="dt-center text-center" >Código</th>
                            <th width="30%" class="dt-center text-center" >Nome</th>
                            <th width="10%" class="dt-center text-center">Marca</th>
                            <th width="10%" class="dt-center text-center">Setor</th>
                            <th width="10%" class="dt-center text-center">Categoria</th>
                            <th width="10%" class="dt-center text-center" >Tombamento</th>
                            <th width="10%" class="dt-center text-center">Responsavel</th>
                            <th width="10%" class="dt-center no-sort text-center">Ações</th>
                        </tr> 
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab_estoque" class="tab-pane">
            <div class="container-fluid">
                <h2 class="text-center"><strong>Gerenciar Estoque</strong></h2>
                <table id="dt_estoque" class="table table-striped table-bordered">
                    <thead>
                        <tr class="tableheader">
                            <th width="10%" class="dt-center text-center" >Código</th>
                            <th width="60%" class="dt-center text-center" >Produto</th>
                            <th width="20%" class="dt-center text-center">Quantidade</th>
                            <th width="10%" class="dt-center no-sort text-center">Ações</th>
                        </tr> 
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab_entrada" class="tab-pane">
            <div class="container-fluid">
                <h2 class="text-center"><strong>Gerenciar Entrada de Produtos</strong></h2>
                <a id="btn_add_entrada" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Registrar Entrada</i></a>
                <table id="dt_entrada" class="table table-striped table-bordered">
                    <thead>
                        <tr class="tableheader">
                            <th class="dt-center text-center" >Código</th>
                            <th class="dt-center text-center" >Produto</th>
                            <th class="dt-center text-center">Quantidade</th>
                            <th class="dt-center text-center">Data</th>
                            <th class="dt-center text-center">Setor</th>
                            <th class="dt-center text-center">Usuário</th>
                            <th class="dt-center no-sort text-center">Ações</th>
                        </tr> 
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab_saida" class="tab-pane">
            <div class="container-fluid">
                <h2 class="text-center"><strong>Gerenciar Saida de Produtos</strong></h2>
                <a id="btn_add_saida" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Registrar Saída</i></a>
                <table id="dt_saida" class="table table-striped table-bordered">
                    <thead>
                        <tr class="tableheader">
                            <th class="dt-center text-center" >Código</th>
                            <th class="dt-center text-center" >Nome</th>
                            <th class="dt-center text-center">Tipo</th>
                            <th class="dt-center no-sort text-center">Ações</th>
                        </tr> 
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab_responsaveis" class="tab-pane">
            <div class="container-fluid">
                <h2 class="text-center"><strong>Gerenciar Responsaveis</strong></h2>
                <a id="btn_add_responsavel" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Cadastrar Responsável</i></a>
                <table id="dt_responsaveis" class="table table-striped table-bordered">
                    <thead>
                        <tr class="tableheader">
                            <th class="dt-center text-center" >Código</th>
                            <th class="dt-center text-center" >Nome</th>
                            <th class="dt-center text-center">CPF</th>
                            <th class="dt-center text-center">E-mail</th>
                            <th class="dt-center text-center">Contato</th>
                            <th class="dt-center text-center">Setor</th>
                            <th class="dt-center no-sort text-center">Ações</th>
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
                        <td style="color: white; background-color: green; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-address-card fa-5x" aria-hidden="true"></i><br>Produtos por categoria</center></td>
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

        <div id="modal_categoria" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">Nova Categoria</h4>
                </div>

                <div class="modal-body">
                    <form id="form_categoria">

                        <input id="categoria_id" name="categoria_id" hidden>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nome</label>
                            <div class="col-lg-10">
                                <input id="categoria_nome" name="categoria_nome" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                    <label class="col-lg-2 control-label">Tipo</label>
                    <?php echo lang('escalas_unidadehospitalar', 'categoria_tipo_select', array('class' => 'col-sm-2 control-label')); ?>
                    <div class="col-lg-10">
                        <?php echo form_dropdown($categoria_tipo_select);?>
                    </div>
                    </div>

                        <div class="form-group text-center">
                            <button type="submit" id="btn_save_categoria" class="btn btn-primary">
                            <center><i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button></center>
                            <span class="help-block"></span>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        </div>

        <div id="modal_eventos" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">Nova Nota Fiscal</h4>
                </div>

                <div class="modal-body">
                    <form id="form_eventos">

                        <input id="eventos_id" name="eventos_id" hidden>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Código</label>
                            <div class="col-lg-10">
                                <input id="eventos_codigo" name="eventos_codigo" class="form-control" maxlength="44" onKeyPress="if(this.value.length==44) return false;">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">profissional</label>
                            <div class="col-lg-10">
                                    <?php echo form_dropdown($profissionais_select);?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Valor</label>
                            <div class="col-lg-10">
                                <input id="eventos_valor" name="eventos_valor" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Data</label>
                            <div class="col-lg-10">
                                <input id="eventos_data" type="date" name="eventos_data" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Anexo</label>
                            <div class="col-lg-10">
                                <center><img id="eventos_img_path" name="eventos_img_path" src="" style="max-height: 300px; max-height: 400px"></center>
                                <label class="btn btn-block btn-ieventoso">
                                    <i class="fa fa-upload"></i>&nbsp;&nbsp;Importar Imagem
                                    <input type="file" id="eventos_upload_img" name="eventos_upload_img" accept="image/*" style="display: none;">
                                </label>
                                    <input id="eventos_img" name="eventos_img" hidden>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group text-center">
                        <button type="submit" id="btn_save_eventos" class="btn btn-primary text-center">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button>
                            <button type="submit" id="btn_limpar_eventos_img" class="btn btn-primary text-center">
                            <i class="fa fa-chain-broken"></i>&nbsp;&nbsp;Limpar Imagem</button>
                            <span class="help-block"></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>

        <div id="modal_produto" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">Novo Produto</h4>
                </div>

                <div class="modal-body">
                    <form id="form_produto">

                        <input id="produto_id" name="produto_id" hidden>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nome</label>
                            <div class="col-lg-10">
                                <input id="produto_nome" name="produto_nome" class="form-control" maxlength="300" onKeyPress="if(this.value.length==300) return false;">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Descrição</label>
                            <div class="col-lg-10">
                                <input id="produto_descricao" name="produto_descricao" class="form-control" maxlength="600" onKeyPress="if(this.value.length==600) return false;">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Categoria</label>
                            <div class="col-lg-10">
                                <?php echo form_dropdown($folhas_select);?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Marca</label>
                            <div class="col-lg-10">
                                <input id="produto_marca" name="produto_marca" class="form-control" maxlength="150" onKeyPress="if(this.value.length==150) return false;">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">profissional</label>
                            <div class="col-lg-10">
                                    <?php echo form_dropdown($profissionais_select);?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tombamento</label>
                            <div class="col-lg-10">
                                <input id="produto_tombamento" name="produto_tombamento" type="number" step="1" class="form-control" onKeyPress="if(this.value.length==11) return false;">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Número de Série</label>
                            <div class="col-lg-10">
                                <input id="produto_n_serie" name="produto_n_serie" class="form-control" maxlength="100" onKeyPress="if(this.value.length==100) return false;">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Responsavel</label>
                            <div class="col-lg-10">
                                    <?php echo form_dropdown($responsavel_select);?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nota Fiscal</label>
                            <div class="col-lg-10">
                                    <?php echo form_dropdown($eventos_select);?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Data de Tombamento</label>
                            <div class="col-lg-10">
                                <input id="produto_data_tombamento" name="produto_data_tombamento" type="date" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Data de Validade</label>
                            <div class="col-lg-10">
                                <input id="produto_data_validade" name="produto_data_validade" type="date" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Data de Aquisição</label>
                            <div class="col-lg-10">
                                <input id="produto_data_aquisicao" name="produto_data_aquisicao" type="date" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Valor de Compra</label>
                            <div class="col-lg-10">
                                <input id="produto_valor_compra" name="produto_valor_compra" type="number" step="0.01" class="form-control" onKeyPress="if(this.value.length==11) return false;">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Valor Atual</label>
                            <div class="col-lg-10">
                                <input id="produto_valor_atual" name="produto_valor_atual" type="number" step="0.01" class="form-control" onKeyPress="if(this.value.length==11) return false;">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Observação</label>
                            <div class="col-lg-10">
                                <input id="produto_obs" name="produto_obs" class="form-control" maxlength="600">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Foto</label>
                            <div class="col-lg-10">
                                <center><img id="produto_img_path" name="produto_img_path" src="" style="max-width: 300px; max-height: 400px"></center>
                                <label class="btn btn-block btn-ieventoso">
                                    <i class="fa fa-upload"></i>&nbsp;&nbsp;Importar Imagem
                                    <input type="file" id="produto_upload_img" name="produto_upload_img" accept="image/*" style="display: none;">
                                </label>
                                    <input id="produto_img" name="produto_img" hidden>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" id="btn_save_produto" class="btn btn-primary">
                            <center><i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button></center>
                            <span class="help-block"></span>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        </div>

        <div id="modal_profissional" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">X</button>
                        <h4 class="modal-title">Novo profissional</h4>
                    </div>
                <div class="modal-body">
                    <form id="form_profissional">
                        <input id="profissional_id" name="profissional_id" hidden>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nome</label>
                            <div class="col-lg-10">
                                <input id="profissional_nome" name="profissional_nome" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">CNPJ</label>
                                <div class="col-lg-10">
                                    <input id="profissional_cnpj" name="profissional_cnpj" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">Endereço</label>
                                <div class="col-lg-10">
                                    <input id="profissional_endereco" name="profissional_endereco" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">E-mail</label>
                                <div class="col-lg-10">
                                    <input id="profissional_email" name="profissional_email" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Contato</label>
                                <div class="col-lg-10">
                                    <input id="profissional_contato" name="profissional_contato" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" id="btn_salvar_profissional" class="btn btn-primary">
                            <center><i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button></center>
                            <span class="help-block"></span>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>

        <div id="modal_entrada" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">X</button>
                        <h4 class="modal-title">Registrar Entrada</h4>
                    </div>
                <div class="modal-body">
                    <form id="form_entrada">
                        <input id="profissional_nome" name="profissional_nome" hidden>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tipo</label>
                            <div class="col-lg-10">
                                <input id="profissional_nome" name="profissional_nome" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">Produto</label>
                                <div class="col-lg-10">
                                        <?php echo form_dropdown($produtos_select);?>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">Quantidade</label>
                                <div class="col-lg-10">
                                    <input id="profissional_endereco" name="profissional_endereco" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Data</label>
                                <div class="col-lg-10">
                                    <input id="profissional_email" name="profissional_email" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Setor</label>
                                <div class="col-lg-10">
                                    <input id="profissional_contato" name="profissional_contato" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" id="btn_salvar_profissional" class="btn btn-primary">
                            <center><i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button></center>
                            <span class="help-block"></span>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>

        <div id="modal_saida" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">X</button>
                        <h4 class="modal-title">Registrar Saída</h4>
                    </div>
                <div class="modal-body">
                    <form id="form_saida">
                        <<input id="profissional_nome" name="profissional_nome" hidden>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tipo</label>
                            <div class="col-lg-10">
                                <input id="profissional_nome" name="profissional_nome" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">Produto</label>
                                <div class="col-lg-10">
                                    <input id="profissional_cnpj" name="profissional_cnpj" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">Quantidade</label>
                                <div class="col-lg-10">
                                    <input id="profissional_endereco" name="profissional_endereco" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Data</label>
                                <div class="col-lg-10">
                                    <input id="profissional_email" name="profissional_email" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Setor</label>
                                <div class="col-lg-10">
                                    <input id="profissional_contato" name="profissional_contato" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" id="btn_salvar_profissional" class="btn btn-primary">
                            <center><i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button></center>
                            <span class="help-block"></span>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>


        <div id="modal_responsavel" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">X</button>
                        <h4 class="modal-title">Cadastrar Responsável</h4>
                    </div>
                <div class="modal-body">
                    <form id="form_responsavel">
                        <input id="responsavel_id" name="responsavel_id" hidden>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nome</label>
                            <div class="col-lg-10">
                                <input id="responsavel_nome" name="responsavel_nome" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">CPF</label>
                                <div class="col-lg-10">
                                    <input id="responsavel_cpf" name="responsavel_cpf" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">E-Mail</label>
                                <div class="col-lg-10">
                                    <input id="responsavel_email" name="responsavel_email" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Contato</label>
                                <div class="col-lg-10">
                                    <input id="responsavel_contato" name="responsavel_contato" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Setor</label>
                                <div class="col-lg-10">
                                    <input id="responsavel_setor" name="responsavel_setor" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" id="btn_save_responsavel" class="btn btn-primary">
                            <center><i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button></center>
                            <span class="help-block"></span>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
<div>
</div>