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
                                    <h3 class="box-title"><?php echo lang('plantoes_tooffer'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo $message;?>

                                    <?php echo form_open(uri_string(), array('class' => 'form-horizontal', 'id' => 'form-edit_plantao')); ?>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_unidadehospitalar', 'unidadehospitalar', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4 ">
                                                <?php echo($plantao->unidadehospitalar_razaosocial);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_setor', 'setor', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo($plantao->setor_nome);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_dataplantao', 'dataplantao', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo date('d/m/Y', strtotime($plantao->dataplantao));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_horainicialplantao', 'horainicialplantao', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo date('H:i:s', strtotime($plantao->horainicialplantao));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_horafinalplantao', 'horafinalplantao', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php echo date('H:i:s', strtotime($plantao->horafinalplantao));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_tipopassagem', 'tipopassagem', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($tipopassagem);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_profissional', 'profissionaltitular_id', array('class' => 'col-sm-2 text-right')); ?>
                                            <div class="col-sm-4">
                                                <?php
                                                if (isset($plantao->profissional_substituto_nome)) {
                                                    echo($plantao->profissional_substituto_nome);
                                                } else {
                                                    echo($plantao->profissional_passagem_nome);
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('plantoes_profissional_substituto', 'profissionalsubstituto_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($profissionalsubstituto_id);?>
                                            </div>
                                        </div>
<?php if ($this->ion_auth->is_admin()): ?>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"><?php echo lang('plantoes_active');?></label>
                                            <div class="col-sm-10">
                                                <div class="checkbox">
                                                    <label>
    <?php $checked = ($plantao->active == 1) ? 'checked' : '';?>
                                                        <input type="checkbox" name="active" value="1" <?php echo $checked; ?>>
                                                        <?php echo htmlspecialchars(lang('plantoes_active'), ENT_QUOTES, 'UTF-8'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
<?php endif ?>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <?php echo form_hidden('id', $plantao->id);?>
                                                <?php echo form_hidden($csrf); ?>
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_submit'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/plantoes', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
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
