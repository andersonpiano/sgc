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
            <li class="active"><a href="#tab_profissionais_cadastro" role="tab" data-toggle="tab">Profissionais</a></li>
            <li><a href="#tab_categoria" role="tab" data-toggle="tab">Níveis de Formação</a></li>
            <li><a href="#tab_especializacao" role="tab" data-toggle="tab">Especialidades</a></li>
            <li><a href="#tab_profissionais" role="tab" data-toggle="tab">Associar a Profissionais</a></li>
        </ul>

        <div class="tab-content">
            <div id="tab_categoria" class="tab-pane">
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

        <div id="tab_profissionais_cadastro" class="tab-pane active">
                <div class="container-fluid">
                    <h2 class="text-center"><strong>Gerenciar Profissionais</strong></h2>
                    <a id="btn_add_profissional_cadastro" href="/sgc/admin/profissionais/create" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Cadastrar profissional</i></a>
                    <table id="dt_profissionais_cadastro" class="table table-striped table-bordered">
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

<div id="modal_edit_profissinal" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">X</button>
			<h4 class="modal-title">Editar</h4>
		</div>

        <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('profissionais_edit'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(uri_string(), array('class' => 'form-horizontal', 'id' => 'form-edit_profissionais')); ?>
                                        <div class="form-group">
                                            <?php echo lang('profissionais_registro', 'registro', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($registro);?>
                                            </div>
                                        </div>
                                        <input type="hidden" id="profissional_id" value=<?php echo $profissional->id; ?> ></input>
                                        <div class="form-group">
                                            <?php echo lang('profissionais_matricula', 'matricula', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($matricula);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('profissionais_nome', 'nome', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-10">
                                                <?php echo form_input($nome);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('profissionais_nomecurto', 'nomecurto', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-10">
                                                <?php echo form_input($nomecurto);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('profissionais_vinculo', 'vinculo', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($vinculo);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('profissionais_email', 'email', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-10">
                                                <?php echo form_input($email);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('profissionais_cpf', 'cpf', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_input($cpf);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('profissionais_rg', 'rg', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_input($rg);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('profissionais_orgao_expeditor_rg', 'orgao_expeditor_rg', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_input($orgao_expeditor_rg);?>
                                            </div>
                                        </div>

<?php if ($this->ion_auth->is_admin()): ?>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"><?php echo lang('profissionais_active');?></label>
                                            <div class="col-sm-10">
                                                <div class="checkbox">
                                                    <label>
    <?php $checked = ($profissional->active == 1) ? 'checked' : '';?>
                                                        <input type="checkbox" name="active" value="1" <?php echo $checked; ?>>
                                                        <?php echo htmlspecialchars(lang('profissionais_active'), ENT_QUOTES, 'UTF-8'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        

                                        
<?php endif ?>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <?php echo form_hidden('id', $profissional->id);?>
                                                <?php echo form_hidden($csrf); ?>
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_save'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/profissionais', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
                                                    <?php echo anchor('admin/profissionais/createuser/' . $profissional->id, lang('actions_create_user'), array('class' => 'btn btn-default btn-flat')); ?>
                                                   <!-- <?php //echo anchor('admin/profissionais/linktosector/' . $profissional->id, lang('actions_link_user_sector'), array('class' => 'btn btn-default btn-flat')); ?>-->
                                                </div>
                                            </div>
                                        </div>
                                    <?php echo form_close();?>
                                    <table id="dt_setores_profissional" class="table table-striped table-bordered">
                                            <thead>
                                                <tr class="tableheader">
                                                    <th width="80%" class="dt-center text-center">Setores</th>
                                                    <th width="20%" class="dt-center text-center">Ações</th>
                                                </tr> 
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    <button style="font-size:19px; position: relative; float: right; color:green;" class="btn btn-link btn-addtosector text-center" id="add_medico_setor"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Adicionar Setor</i></button>
                                    
                                </div>
                                
                            </div>
                            
                         </div>
                         
                    </div>
                    
                </section>
            </div>
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