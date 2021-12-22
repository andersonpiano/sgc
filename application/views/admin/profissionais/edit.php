<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <?php if (isset($message)) : ?>
                <section class="content-header">
                    <div class="alert bg-warning alert-dismissible" role="alert">
                        <?php echo(isset($message) ? $message : '');?>
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
                                            <div class="col-sm-4">
                                                <?php echo form_input($nome);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('profissionais_nomecurto', 'nomecurto', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
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
                                            <div class="col-sm-4">
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
                                                    <?php //echo anchor('admin/profissionais/createuser/' . $profissional->id, lang('actions_create_user'), array('class' => 'btn btn-default btn-flat')); ?>
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

            <div id="modal_add_to_setor" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <center><h4 class="modal-title">Selecione o Setor</h4></center>
                </div>

                <div class="modal-body">
                        <table id="dt_setores" class="table table-striped table-bordered">
                        <thead>
                            <tr class="tableheader">
                                <th style="width:60%" class="dt-center text-center">Nome</th>
                                <th style="width:20%" class="dt-center no-sort text-center">Selecionar</th>
                            </tr> 
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>