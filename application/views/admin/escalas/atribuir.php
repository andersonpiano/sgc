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
                                    <h3 class="box-title"><?php echo lang('escalas_attribute'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo isset($message) ? $message : '';?>

                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-create_escala')); ?>
                                        <div class="form-group">
                                            <?php echo lang('escalas_unidadehospitalar', 'unidadehospitalar_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-10">
                                                <?php echo form_dropdown($unidadehospitalar_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_setor', 'setor_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-10">
                                                <?php echo form_dropdown($setor_id);?>
                                                <?php //echo form_multiselect($setor_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_datainicialplantao', 'datainicial', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_input($datainicial);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_datafinalplantao', 'datafinal', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_input($datafinal);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"><?php echo lang('escalas_diasdasemana');?></label>
                                            <div class="col-sm-10">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="domingo" value="<?php echo 1;?>"<?php echo 'checked'; ?>>
                                                        <?php echo htmlspecialchars('Domingo'); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="segunda" value="<?php echo 1;?>"<?php echo 'checked'; ?>>
                                                        <?php echo htmlspecialchars('Segunda'); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="terca" value="<?php echo 1;?>"<?php echo 'checked'; ?>>
                                                        <?php echo htmlspecialchars('Terça'); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="quarta" value="<?php echo 1;?>"<?php echo 'checked'; ?>>
                                                        <?php echo htmlspecialchars('Quarta'); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="quinta" value="<?php echo 1;?>"<?php echo 'checked'; ?>>
                                                        <?php echo htmlspecialchars('Quinta'); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="sexta" value="<?php echo 1;?>"<?php echo 'checked'; ?>>
                                                        <?php echo htmlspecialchars('Sexta'); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="sabado" value="<?php echo 1;?>"<?php echo 'checked'; ?>>
                                                        <?php echo htmlspecialchars('Sábado'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_profissional', 'profissional_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($profissional_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_submit'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/escalas', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php echo form_close();?>
                                </div>
                            </div>
                         </div>
                    </div>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <?php echo anchor('admin/escalas/create', '<i class="fa fa-plus"></i> '. 
                                            lang('escalas_create'), 
                                            array('class' => 'btn btn-block btn-primary btn-flat')); ?>
                                    </h3>
                                </div>
<?php if ($tipoescala['value'] == 0) : ?>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('escalas_unidadehospitalar');?></th>
                                                <th><?php echo lang('escalas_setor');?></th>
                                                <th><?php echo lang('escalas_profissional');?></th>
                                                <th><?php echo lang('escalas_dataplantao');?></th>
                                                <th><?php echo lang('escalas_horario');?></th>
                                                <th><?php echo lang('escalas_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
    <?php foreach ($escalas as $escala) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($escala->unidadehospitalar_razaosocial, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($escala->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($escala->profissional_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($escala->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($escala->horainicialplantao)) . " a " . date('H:i', strtotime($escala->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/escalas/edit/'.$escala->id, lang('actions_edit')); ?> &nbsp;
                                                    <?php echo anchor('admin/escalas/view/'.$escala->id, lang('actions_see')); ?>
                                                </td>
                                            </tr>
    <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
<?php endif; ?>
<?php if ($tipoescala['value'] == 1) : ?>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('escalas_unidadehospitalar');?></th>
                                                <th><?php echo lang('escalas_setor');?></th>
                                                <th><?php echo lang('escalas_profissional');?></th>
                                                <th><?php echo lang('escalas_dataplantao');?></th>
                                                <th><?php echo lang('escalas_horario');?></th>
                                                <th><?php echo lang('escalas_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
    <?php foreach ($escalas as $escala) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($escala->unidadehospitalar_razaosocial, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($escala->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php if ($escala->passagenstrocas_id == null) :?>
                                                <td><?php echo htmlspecialchars($escala->profissional_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php else :?>
                                                <td><?php echo htmlspecialchars($escala->profissional_substituto_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php endif;?>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($escala->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($escala->horainicialplantao)) . " a " . date('H:i', strtotime($escala->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/escalas/view/'.$escala->id, lang('actions_see')); ?>
                                                </td>
                                            </tr>
    <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
<?php endif; ?>
<?php if ($tipoescala['value'] == 2) : ?>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('escalas_unidadehospitalar');?></th>
                                                <th><?php echo lang('escalas_setor');?></th>
                                                <th><?php echo lang('escalas_profissional');?></th>
                                                <th><?php echo lang('escalas_profissional_substituto');?></th>
                                                <th><?php echo lang('escalas_dataplantao');?></th>
                                                <th><?php echo lang('escalas_horario');?></th>
                                                <th><?php echo lang('escalas_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
    <?php foreach ($escalas as $escala) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($escala->unidadehospitalar_razaosocial, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($escala->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($escala->profissional_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($escala->profissional_substituto_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($escala->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($escala->horainicialplantao)) . " a " . date('H:i', strtotime($escala->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/escalas/view/'.$escala->id, lang('actions_see')); ?>
                                                </td>
                                            </tr>
    <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>