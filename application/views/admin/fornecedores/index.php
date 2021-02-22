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

<section style="min-height: calc(100vh - 83px);" class="light-bg">
    <div class="container">

        <div class="tab-content">
            <div id="tab_estoque" class="tab-pane active">
                <div class="container-fluid">
                    <h2 class="text-center"><strong>Gerenciar Estoque</strong></h2>
                    <a id="btn_add_estoque" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Adicionar estoque</i></a>
                    <table id="dt_estoque" class="table table-striped table-bordered">
                        <thead>
                            <tr class="tableheader">
                                <th class="dt-center">Código</th>
                                <th class="dt-center">Nome</th>
                                <th class="dt-center no-sort">Ações</th>
                            </tr> 
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            
        <div id="tab_especializacao" class="tab-pane">
            <div class="container-fluid">
                <h2 class="text-center"><strong>Gerenciar Especializações</strong></h2>
                <a id="btn_add_especializacao" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Adicionar Especialização</i></a>
                <table id="especializacao" class="table table-striped table-bordered">
                    <thead>
                        <tr class="tableheader">
                            <th class="dt-center" >Código</th>
                            <th class="dt-center">Nome</th>
                            <th class="dt-center no-sort">Ações</th>
                        </tr> 
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<div id="modal_estoque" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">X</button>
			<h4 class="modal-title">Estoque</h4>
		</div>

		<div class="modal-body">
			<form id="form_estoque">

				<input id="estoque_nome" name="estoque_nome" hidden>

				<div class="form-group">
					<label class="col-lg-2 control-label">Nome</label>
					<div class="col-lg-10">
						<input id="estoque_nome" name="estoque_nome" class="form-control" maxlength="100">
						<span class="help-block"></span>
					</div>
				</div>

				<div class="form-group text-center">
            		<button type="submit" id="btn_save_estoque" class="btn btn-primary">
              		<i class="fa fa-save"></i>&nbsp;&nbsp;Salvar</button>
            		<span class="help-block"></span>
          		</div>

			</form>
		</div>
	</div>
</div>
</div>

<div id="modal_especializacao" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">X</button>
			<h4 class="modal-title">Nova Especialização</h4>
		</div>
		<div class="modal-body">
			<form id="form_especializacao">
				<input id="especializacao_nome" name="especializacao_nome" hidden>
				<div class="form-group">
					<label class="col-lg-2 control-label">Nome</label>
					<div class="col-lg-10">
						<input id="especializacao_nome" name="especializacao_nome" class="form-control" maxlength="100">
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                    <label class="col-lg-2 control-label">estoque</label>
                    <div class="col-lg-10">
                        <select name="estoque_select" class="form-control">
                            <option value="#" selected>Sem especialização</option>
                            <option value="#">Residência Médica</option>
                            <option value="#">Mestrado</option>
                            <option value="#">Doutorado</option>
                            <option value="#">Supervisor de PRM ESP-CE</option>
                        </select>
                        <span class="help-block"></span>
                    </div>
                    </div>
				</div>
				<div class="form-group text-center">
            		<button type="submit" id="btn_salvar_especializacao" class="btn btn-primary">
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