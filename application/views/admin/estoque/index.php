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
            <li class="active"><a href="#tab_fornecedores" role="tab" data-toggle="tab">Fornecedores</a></li>
            <li><a href="#tab_nf" role="tab" data-toggle="tab">Nota Fiscal</a></li>
            <li><a href="#tab_categorias" role="tab" data-toggle="tab">Categorias</a></li>
            <li><a href="#tab_produtos" role="tab" data-toggle="tab">Produtos</a></li>
            <li><a href="#tab_estoque" role="tab" data-toggle="tab">Estoque</a></li>
            <li><a href="#tab_entrada" role="tab" data-toggle="tab">Entrada</a></li>
            <li><a href="#tab_saida" role="tab" data-toggle="tab">Saida</a></li>
            <li><a href="#tab_responsaveis" role="tab" data-toggle="tab">Responsaveis</a></li>
            <li><a href="#tab_relatorios" role="tab" data-toggle="tab">Relatórios</a></li>
        </ul>

        <div class="tab-content">
            
            <div id="tab_fornecedores" class="tab-pane active">
                <div class="container-fluid">
                    <h2 class="text-center"><strong>Gerenciar Fornecedores</strong></h2>
                    <a id="btn_add_fornecedor" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Cadastrar Fornecedor</i></a>
                    <table id="dt_fornecedor" class="table table-striped table-bordered">
                        <thead>
                            <tr class="tableheader">
                                <th class="dt-center text-center" >Código</th>
                                <th class="dt-center text-center" >Nome</th>
                                <th class="dt-center text-center">CNPJ</th>
                                <th class="dt-center text-center">Contato</th>
                                <th class="dt-center no-sort text-center">Ações</th>
                            </tr> 
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="tab_nf" class="tab-pane">
                <div class="container-fluid">
                    <h2 class="text-center"><strong>Gerenciar Notas Fiscais</strong></h2>
                    <a id="btn_add_nf" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Adicionar NF</i></a>
                    <table id="dt_nf" class="table table-striped table-bordered">
                        <thead>
                            <tr class="tableheader">
                                <th class="dt-center text-center">Data</th>
                                <th class="dt-center text-center">Código</th>
                                <th class="dt-center text-center">Fornecedor</th>
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
            
        <div id="tab_categorias" class="tab-pane">
            <div class="container-fluid">
                <h2 class="text-center"><strong>Gerenciar Categorias</strong></h2>
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
                            <th class="dt-center text-center">Tipo</th>
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
                <center><table style="margin: 100px; border: 1px solid black;">
                    <tr>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                    </tr>
                    <tr>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                    </tr>
                    <tr>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                    </tr>
                    <tr>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                    </tr>
                    <tr>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i></center></td>
                        <td style="color: white; background-color: green; width: 150px; height: 90px; border: 1px solid black;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i><br>Lista de Fornecedores</center></td>
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
                    <?php echo lang('escalas_unidadehospitalar', 'categoria_select', array('class' => 'col-sm-2 control-label')); ?>
                    <div class="col-lg-10">
                        <?php echo form_dropdown($categoria_select);?>
                    </div>
                    </div>

                        <div class="form-group text-center">
                            <button type="submit" id="btn_save_categoria" class="btn btn-primary">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button>
                            <span class="help-block"></span>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        </div>

        <div id="modal_nf" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">Nova Nota Fiscal</h4>
                </div>

                <div class="modal-body">
                    <form id="form_nf">

                        <input id="nf_id" name="nf_id" hidden>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Código</label>
                            <div class="col-lg-10">
                                <input id="nf_codigo" name="nf_codigo" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Fornecedor</label>
                            <div class="col-lg-10">
                                <input id="nf_fornecedor" name="nf_fornecedor" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Valor</label>
                            <div class="col-lg-10">
                                <input id="nf_valor" name="nf_valor" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Data</label>
                            <div class="col-lg-10">
                                <input id="nf_data" name="nf_data" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" id="btn_save_nf" class="btn btn-primary">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button>
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

                        <input id="nf_id" name="nf_id" hidden>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nome</label>
                            <div class="col-lg-10">
                                <input id="nf_codigo" name="nf_codigo" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Descrição</label>
                            <div class="col-lg-10">
                                <input id="nf_fornecedor" name="nf_fornecedor" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Marca</label>
                            <div class="col-lg-10">
                                <input id="nf_valor" name="nf_valor" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Fornecedor</label>
                            <div class="col-lg-10">
                                <input id="nf_data" name="nf_data" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tombamento</label>
                            <div class="col-lg-10">
                                <input id="nf_data" name="nf_data" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Número de Série</label>
                            <div class="col-lg-10">
                                <input id="nf_data" name="nf_data" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Setor</label>
                            <div class="col-lg-10">
                                <input id="nf_data" name="nf_data" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Responsavel</label>
                            <div class="col-lg-10">
                                <input id="nf_data" name="nf_data" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Data de Aquisição</label>
                            <div class="col-lg-10">
                                <input id="nf_data" name="nf_data" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nota Fiscal</label>
                            <div class="col-lg-10">
                                <input id="nf_data" name="nf_data" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Data de Tombamento</label>
                            <div class="col-lg-10">
                                <input id="nf_data" name="nf_data" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Data de Validade</label>
                            <div class="col-lg-10">
                                <input id="nf_data" name="nf_data" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Data de Aquisição</label>
                            <div class="col-lg-10">
                                <input id="nf_data" name="nf_data" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Valor de Compra</label>
                            <div class="col-lg-10">
                                <input id="nf_data" name="nf_data" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Valor Atual</label>
                            <div class="col-lg-10">
                                <input id="nf_data" name="nf_data" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" id="btn_save_nf" class="btn btn-primary">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button>
                            <span class="help-block"></span>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        </div>

        <div id="modal_fornecedor" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">X</button>
                        <h4 class="modal-title">Novo Fornecedor</h4>
                    </div>
                <div class="modal-body">
                    <form id="form_fornecedor">
                        <input id="fornecedor_id" name="fornecedor_id" hidden>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nome</label>
                            <div class="col-lg-10">
                                <input id="fornecedor_nome" name="fornecedor_nome" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">CNPJ</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_cnpj" name="fornecedor_cnpj" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">Endereço</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_endereco" name="fornecedor_endereco" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">E-mail</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_email" name="fornecedor_email" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Contato</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_contato" name="fornecedor_contato" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" id="btn_salvar_fornecedor" class="btn btn-primary">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button>
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
                        <input id="fornecedor_nome" name="fornecedor_nome" hidden>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tipo</label>
                            <div class="col-lg-10">
                                <input id="fornecedor_nome" name="fornecedor_nome" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">Produto</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_cnpj" name="fornecedor_cnpj" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">Quantidade</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_endereco" name="fornecedor_endereco" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Data</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_email" name="fornecedor_email" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Setor</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_contato" name="fornecedor_contato" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" id="btn_salvar_fornecedor" class="btn btn-primary">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button>
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
                        <input id="fornecedor_nome" name="fornecedor_nome" hidden>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tipo</label>
                            <div class="col-lg-10">
                                <input id="fornecedor_nome" name="fornecedor_nome" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">Produto</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_cnpj" name="fornecedor_cnpj" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">Quantidade</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_endereco" name="fornecedor_endereco" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Data</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_email" name="fornecedor_email" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Setor</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_contato" name="fornecedor_contato" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" id="btn_salvar_fornecedor" class="btn btn-primary">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button>
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
                        <input id="fornecedor_nome" name="fornecedor_nome" hidden>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tipo</label>
                            <div class="col-lg-10">
                                <input id="fornecedor_nome" name="fornecedor_nome" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">Produto</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_cnpj" name="fornecedor_cnpj" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">Quantidade</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_endereco" name="fornecedor_endereco" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Data</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_email" name="fornecedor_email" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Setor</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_contato" name="fornecedor_contato" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" id="btn_salvar_fornecedor" class="btn btn-primary">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button>
                            <span class="help-block"></span>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>

        <div id="modal_relatorios" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">X</button>
                        <h4 class="modal-title">Cadastrar Responsável</h4>
                    </div>
                <div class="modal-body">
                    <form id="form_relatorios">
                        <input id="fornecedor_nome" name="fornecedor_nome" hidden>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tipo</label>
                            <div class="col-lg-10">
                                <input id="fornecedor_nome" name="fornecedor_nome" class="form-control" maxlength="100">
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">Produto</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_cnpj" name="fornecedor_cnpj" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                            <label class="col-lg-2 control-label">Quantidade</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_endereco" name="fornecedor_endereco" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Data</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_email" name="fornecedor_email" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-lg-2 control-label">Setor</label>
                                <div class="col-lg-10">
                                    <input id="fornecedor_contato" name="fornecedor_contato" class="form-control" maxlength="100">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" id="btn_salvar_fornecedor" class="btn btn-primary">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button>
                            <span class="help-block"></span>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>