<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('profissionais_create'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo $message;?>

                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-create_profissional')); ?>
                                        <div class="form-group">
                                            <?php echo lang('profissionais_registro', 'registro', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($registro);?>
                                            </div>
                                        </div>
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
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"><?php echo lang('profissionais_active');?></label>
                                            <div class="col-sm-10">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="active" value="1" checked>
                                                        <?php echo htmlspecialchars(lang('profissionais_active'), ENT_QUOTES, 'UTF-8'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_save'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/profissionais', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php echo form_close();?>
                                </div>
                            </div>
                         </div>
                    </div>
                </section>
            </div>
