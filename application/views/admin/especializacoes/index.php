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

        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_categoria" role="tab" data-toggle="tab">Níveis de Formação</a></li>
            <li><a href="#tab_especializacao" role="tab" data-toggle="tab">Especialidades</a></li>
            <li><a href="#tab_profissionais" role="tab" data-toggle="tab">Associar a Profissionais</a></li>
        </ul>

        <div class="tab-content">
            <div id="tab_categoria" class="tab-pane active">
                <div class="container-fluid">
                    <h2 class="text-center"><strong>Gerenciar Niveis de Formação</strong></h2>
                    <a id="btn_add_categoria" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Adicionar Nível</i></a>
                    <table id="dt_categoria" class="table table-striped table-bordered">
                        <thead>
                            <tr class="tableheader">
                                <th width="10%" class="dt-center text-center">Código</th>
                                <th width="80%" class="dt-center">Nome</th>
                                <th width="10%" class="dt-center text-center no-sort">Ações</th>
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
                <table id="dt_especializacao" class="table table-striped table-bordered">
                    <thead>
                        <tr class="tableheader">
                            <th width="10%" class="dt-center text-center" >Código</th>
                            <th width="80%" class="dt-center">Nome</th>
                            <th width="10%" class="dt-center no-sort text-center">Ações</th>
                        </tr> 
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

            <div id="tab_profissionais" class="tab-pane">
                <div class="container-fluid">
                    <h2 class="text-center"><strong>Gerenciar Profissionais</strong></h2>
                    <table id="dt_profissionais" class="table table-striped table-bordered">
                        <thead>
                            <tr class="tableheader">
                                <th width="10%" class="dt-center text-center">ID</th>
                                <th width="60%" class="dt-center text-center">Profissional</th>
                                <th width="15%" class="dt-center text-center no-sort">Nível de Formação</th>
                                <th width="15%" class="dt-center text-center no-sort">Especialidade</th>
                            </tr> 
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</section>

<div id="modal_categoria" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">X</button>
			<h4 class="modal-title">Nível de Formação</h4>
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

<div id="modal_especializacao" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">X</button>
			<h4 class="modal-title">Nova Especialização</h4>
		</div>
		<div class="modal-body">
			<form id="form_especializacao">
				<input id="especializacao_id" name="especializacao_id" hidden>
				<div class="form-group">
					<label class="col-lg-2 control-label">Nome</label>
					<div class="col-lg-10">
						<input id="especializacao_nome" name="especializacao_nome" class="form-control" maxlength="100">
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                    <label class="col-lg-2 control-label">Formação</label>
                    <?php echo lang('escalas_unidadehospitalar', 'unidadehospitalar_id', array('class' => 'col-sm-2 control-label')); ?>
                    <div class="col-lg-10">
                        <?php echo form_dropdown($categoria_select);?>
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