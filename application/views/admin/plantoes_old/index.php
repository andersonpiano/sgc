<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <?php if (isset($message) or $this->session->flashdata('message')) : ?>
                <section class="content-header">
                    <div class="alert bg-warning alert-dismissible" role="alert">
                        <?php echo(isset($message) ? $message : '');?>
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
                                    <h3 class="box-title"><?php echo lang('plantoes_find'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-create_escala')); ?>
                                        <?php echo form_input($profissional_id);?>
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
                                            <?php echo lang('escalas_tipoescala', 'tipoescala', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($tipoescala);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_tipovisualizacao', 'tipovisualizacao', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($tipovisualizacao);?>
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

<?php if ($tipovisualizacao['value'] == 1) : ?>
                <section class="content">
                    <div id='calendar'></div>
                </section>
<?php endif;?>

<?php if ($tipovisualizacao['value'] == 0) : ?>
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box"><!-- Minha escala consolidada -->
    <?php if ($tipoescala['value'] == 0) : ?>
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <?php
                                            if (sizeof($meus_plantoes) > 0) {
                                                $unidadehospitalar = $meus_plantoes[0]->unidadehospitalar_razaosocial;
                                                echo(lang('escalas_unidadehospitalar') . ": " . $unidadehospitalar);
                                            } else {
                                                echo('A pesquisa não retornou resultados.');
                                            }
                                        ?>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <caption><?php echo lang('plantoes_plantoes');?></caption>
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('plantoes_setor');?></th>
                                                <th><?php echo lang('plantoes_dataplantao');?></th>
                                                <th><?php echo lang('plantoes_horario');?></th>
                                                <th><?php echo lang('plantoes_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
        <?php foreach ($meus_plantoes as $plantao):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($plantao->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($plantao->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($plantao->horainicialplantao)) . ' - ' . date('H:i', strtotime($plantao->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/plantoes/tooffer/'.$plantao->id, lang('actions_to_offer')); ?> &nbsp;
                                                    <?php echo anchor('admin/plantoes/view/'.$plantao->id, lang('actions_see')); ?>
                                                </td>
                                            </tr>
        <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
    <?php endif;?>
    <!-- Minha escala consolidada -->
    <?php if ($tipoescala['value'] == 1) : ?>
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <?php
                                            if (sizeof($recebidos) > 0) {
                                                $unidadehospitalar = $recebidos[0]->unidadehospitalar_razaosocial;
                                                echo(lang('escalas_unidadehospitalar') . ": " . $unidadehospitalar);
                                            } else {
                                                echo('A pesquisa não retornou resultados.');
                                            }
                                        ?>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <caption><?php echo lang('plantoes_recebidos');?></caption>
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('plantoes_setor');?></th>
                                                <th><?php echo lang('plantoes_dataplantao');?></th>
                                                <th><?php echo lang('plantoes_horario');?></th>
                                                <th><?php echo lang('plantoes_profissional_titular');?></th>
                                                <th><?php echo lang('plantoes_tipopassagem');?></th>
                                                <th><?php echo lang('plantoes_statuspassagem');?></th>
                                                <th><?php echo lang('plantoes_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
        <?php foreach ($recebidos as $recebido) :?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($recebido->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($recebido->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($recebido->horainicialplantao)) . ' - ' . date('H:i', strtotime($recebido->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($recebido->profissional_passagem_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($tipospassagem[$recebido->passagenstrocas_tipopassagem], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
            <?php
            $status_passagem = '';
            if ($recebido->passagenstrocas_statuspassagem == 0) {
                $status_passagem = 'danger';
            } elseif ($recebido->passagenstrocas_statuspassagem == 1) {
                $status_passagem = 'success';
            } elseif ($recebido->passagenstrocas_statuspassagem == 2) {
                $status_passagem = 'warning';
            }
            ?>
                                                    <span class="badge badge-<?php echo($status_passagem);?>">
                                                        <?php echo htmlspecialchars($statuspassagem[$recebido->passagenstrocas_statuspassagem], ENT_QUOTES, 'UTF-8'); ?>
                                                    </span>
                                                </td>
                                                <td>
            <?php if ($recebido->passagenstrocas_statuspassagem == 0) :?>
                <?php if ($recebido->passagenstrocas_tipopassagem == 0) :?>
                                                            <?php echo anchor('admin/plantoes/confirm/'.$recebido->id, lang('actions_confirm')); ?> &nbsp;
                <?php else :?>
                                                            <?php echo anchor('admin/plantoes/propose/'.$recebido->id, lang('actions_propose')); ?> &nbsp;
                <?php endif;?>
            <?php endif;?>
                                                    <?php echo anchor('admin/plantoes/view/'.$recebido->id, lang('actions_see')); ?>
                                                </td>
                                            </tr>
        <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <caption><?php echo lang('plantoes_passagens');?></caption>
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('plantoes_setor');?></th>
                                                <th><?php echo lang('plantoes_dataplantao');?></th>
                                                <th><?php echo lang('plantoes_horario');?></th>
                                                <th><?php echo lang('plantoes_profissional_substituto');?></th>
                                                <th><?php echo lang('plantoes_tipopassagem');?></th>
                                                <th><?php echo lang('plantoes_statuspassagem');?></th>
                                                <th><?php echo lang('plantoes_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
        <?php foreach ($passagens as $passagem):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($passagem->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($passagem->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($passagem->horainicialplantao)) . ' - ' . date('H:i', strtotime($passagem->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($passagem->profissional_substituto_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($tipospassagem[$passagem->passagenstrocas_tipopassagem], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
            <?php
            $status_passagem = '';
            if ($passagem->passagenstrocas_statuspassagem == 0) {
                $status_passagem = 'danger';
            } elseif ($passagem->passagenstrocas_statuspassagem == 1) {
                $status_passagem = 'success';
            } elseif ($passagem->passagenstrocas_statuspassagem == 2) {
                $status_passagem = 'warning';
            }
            ?>
                                                    <span class="badge badge-<?php echo($status_passagem);?>">
                                                        <?php echo htmlspecialchars($statuspassagem[$passagem->passagenstrocas_statuspassagem], ENT_QUOTES, 'UTF-8'); ?>
                                                    </span>
                                                </td>
                                                <td>
        <?php if ($passagem->passagenstrocas_statuspassagem == 2) :?>
                                                    <?php echo anchor('admin/plantoes/acceptproposal/'.$passagem->id, lang('actions_accept')); ?> &nbsp;
        <?php endif;?>
                                                    <?php echo anchor('admin/plantoes/view/'.$passagem->id, lang('actions_see')); ?>
                                                </td>
                                            </tr>
        <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
    <?php endif;?>
    <?php if ($tipoescala['value'] == 2) : ?>
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <?php
                                            if (sizeof($escalas) > 0) {
                                                $unidadehospitalar = $escalas[0]->unidadehospitalar_razaosocial;
                                                echo(lang('escalas_unidadehospitalar') . ": " . $unidadehospitalar);
                                            } else {
                                                echo('A pesquisa não retornou resultados.');
                                            }
                                        ?>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('escalas_setor');?></th>
                                                <th><?php echo lang('escalas_profissional');?></th>
                                                <th><?php echo lang('escalas_dataplantao');?></th>
                                                <th><?php echo lang('escalas_horario');?></th>
                                                <th><?php //echo lang('escalas_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
        <?php foreach ($escalas as $escala) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($escala->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php if ($escala->passagenstrocas_id == null) :?>
                                                <td><?php echo htmlspecialchars($escala->profissional_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php else :?>
                                                <td><?php echo htmlspecialchars($escala->profissional_substituto_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php endif;?>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($escala->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($escala->horainicialplantao)) . " a " . date('H:i', strtotime($escala->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php //echo anchor('admin/escalas/view/'.$escala->id, lang('actions_see')); ?>
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
<?php endif;?>
            </div>
