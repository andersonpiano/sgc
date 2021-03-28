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

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('setores_box_title'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <th><?php echo lang('setores_nome'); ?></th>
                                                <td><?php echo $setor->nome; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('setores_unidadehospitalar'); ?></th>
                                                <td><?php echo htmlspecialchars($setor->unidadehospitalar->razaosocial, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('setores_status'); ?></th>
                                                <td><?php echo ($setor->active) ? '<span class="label label-success">'.lang('setores_active').'</span>' : '<span class="label label-default">'.lang('setores_inactive').'</span>'; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Profissionais do setor</h3>
                                    <button style="font-size:19px; position: relative; float: right; color:green;" class="btn btn-link btn-addtosector text-center" id="add_medico_setor" escala="'.$freq->id.'" profissional="'.$freq->id_profissional.'"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Adicionar ao Setor</i></button>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
<?php foreach ($setor->profissionais as $profissional) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($profissional->nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/profissionais/view/'.$profissional->id, ' ', array('class' => 'btn btn-primary text-center fa fa-eye')); ?> &nbsp;
                                                    <?php echo anchor('admin/profissionais/edit/'.$profissional->id, ' ', array('class' => 'btn btn-primary text-center fa fa-edit')); ?> &nbsp;
                                                    <?php echo anchor('admin/profissionais/unlinkfromsector/'.$profissional->id.'/'.$setor->id, ' ', array('class' => 'btn btn-danger btn-flat fa fa-chain-broken')); ?>
                                                </td>
                                            </tr>
<?php endforeach?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Usuários vinculados ao setor</h3>
                                    <button style="font-size:19px; position: relative; float: right; color:green;" class="btn btn-link btn-troca-coordenador text-center" id="troca_coordenador" setor="<?php echo $setor->id; ?>"><i class="fa fa-refresh" aria-hidden="true">&nbsp;Trocar Coordenador</i></i></button>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <?php foreach ($setor->usuarios as $usuario) : ?>
                                                <?php if ($usuario->coordenador == 1) : ?>
                                                    <?php $coordenador = ' (Coordenador)'; ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($usuario->first_name . ' ' . $usuario->last_name . $coordenador, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td style="width: 30%;">
                                                    <?php echo anchor('admin/users/profile/'.$usuario->id, /*lang('actions_see')*/ ' ', array('class' => 'btn btn-primary btn-flat fa fa-eye', 'style'=>'margin: 0 2px 0 0;')); ?>
                                                    <?php echo anchor('admin/users/edit/'.$usuario->id, ' ', array('class' => 'btn btn-primary btn-flat fa fa-edit', 'style'=>'margin: 0 2px 0 0;')); ?>
                                                    <?php echo anchor('admin/users/unlinkfromsector/'.$usuario->id.'/'.$setor->id, ' ', array('class' => 'btn btn-danger btn-flat fa fa-chain-broken')); ?>
                                                </td>
                                            </tr>
    <?php endif ?>
<?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div id="modal_coordenador" class="modal fade">
            <div class="modal-dialog modal-lg" style="width:50%;">
                <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <center><h4 class="modal-title">Selecione um coordenador</h4></center>
                </div>

                <div class="modal-body">
                        <div class="form-group">
                            <center><label class="control-label">Profissionais no Setor</label></center>
                            <center>
                                <?php 
                                /*$data = array(
                                    '1'  => 'CEMERGE',
                                    '2' => 'SESA',
                                );*/
                                echo form_dropdown($setor_id); 
                                ?>
                            </center>
                                <span class="help-block"></span>
                        </div>
                        <div class="form-group text-center">
                        <button id="btn_selecionar_coordenador" class="btn btn-success text-center btn-selecionar_coordenador">
                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Selecionar</button>
                            <span class="help-block"></span>
                        </div>
                </div>
            </div>
        </div>
    </div>
