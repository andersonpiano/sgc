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

                <section class="content findform">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('escalas_find'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-create_escala')); ?>
                                        <div class="form-group">
                                            <?php echo lang('escalas_unidadehospitalar', 'unidadehospitalar_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($unidadehospitalar_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_setor', 'setor_id', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-4">
                                                <?php echo form_dropdown($setor_id);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_datainicialplantao', 'datainicial', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($datainicial);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_datafinalplantao', 'datafinal', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($datafinal);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_tipoescala', 'tipoescala', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_dropdown($tipoescala);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('escalas_tipovisualizacao', 'tipovisualizacao', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-3">
                                                <?php echo form_dropdown($tipovisualizacao);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_find'))); ?>&nbsp;
                                                    <a href="<?php echo(current_url()); ?>" onclick="window.print(); return false;" class="btn btn-default btn-flat">Imprimir</a>&nbsp;
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>&nbsp;
                                                    <?php echo anchor('admin/escalas', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>&nbsp;
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
    <div class="print-header row">
        <div class="col-lg-2 col-xs-2"><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/></div>
        <div class="col-lg-10 col-xs-10 pull-right"><h3>Escalas Processadas</h3></div>
    </div>
        <?php
        if (isset($calendario)) {
            echo($calendario);
        }
        ?>
    </section>
<?php endif;?>
<?php if ($tipovisualizacao['value'] == 2) : ?>
    <section class="content">
    <div class="print-header row">
        <div class="col-lg-2 col-xs-2"><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/></div>
        <div class="col-lg-10 col-xs-10 pull-right"><h3>Escalas Processadas</h3></div>
    </div>
        <div id="container" class="container border grade-frequencia">
            <div class="row border-bottom">
                <div class="col-sm-2 text-center">PROFISSIONAIS</div>
                <div class="col-sm-10">
                    <div class="row border-left border-bottom">
                        <?php for ($i = 1; $i <= $ultimo_dia_mes; $i++) : ?>
                            <div class="dia pull-left <?php echo('fdsAAA');?>"><?php echo($i);?></div>
                        <?php endfor; ?>
                    </div>
                    <div class="row border-left border-top">
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left fds">S</div>
                        <div class="dia pull-left fds">D</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left">T</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left fds">S</div>
                        <div class="dia pull-left fds">D</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left">T</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left fds">S</div>
                        <div class="dia pull-left fds">D</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left">T</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left fds">S</div>
                        <div class="dia pull-left fds">D</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left">T</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left fds">S</div>
                    </div>
                </div>
            </div>
            <?php foreach ($escalas as $profissional => $plantoes) : ?>
                <div class="row border-bottom">
                    <div class="col-sm-2 text-center dados-profissional">
                        <div class="row text-center"><?php echo($profissional);?></div>
                    </div>
                    <div class="col-sm-10 border-left">
                        <div class="row">
                            <?php 
                            $dias = array_fill(1, 31, ''); // Trocar pelos dias do mês selecionado
                            $turnos = array();
                            foreach ($plantoes as $plantao) {
                                $turno = '&nbsp;';
                                $dia_plantao = (int)date('d', strtotime($plantao->dataplantao));
                                if (date('w', strtotime($plantao->dataplantao)) == 0 or date('w', strtotime($plantao->dataplantao)) == 6) {
                                    //
                                }
                                if ($plantao->horainicialplantao == '06:00:00') {
                                    $dias[$dia_plantao] .= 'M';
                                } elseif ($plantao->horainicialplantao == '07:00:00') {
                                    $dias[$dia_plantao] .= 'M';
                                } elseif ($plantao->horainicialplantao == '13:00:00') {
                                    $dias[$dia_plantao] .= 'T';
                                } elseif ($plantao->horainicialplantao == '19:00:00') {
                                    $dias[$dia_plantao] .= 'N';
                                }
                            }
                            ?>
                            <?php for ($i = 1; $i <= $ultimo_dia_mes; $i++) : ?>
                                <div class="dia pull-left <?php echo('');?>"><?php echo($dias[$i] ? $dias[$i] : '&nbsp;');?></div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="row border-bottom border-top">
                <div class="col-sm-2 text-center">
                    PROFISSIONAIS
                </div>
                <div class="col-sm-10">
                    <div class="row border-left border-bottom">
                        <?php for ($i = 1; $i <= $ultimo_dia_mes; $i++) : ?>
                            <div class="dia pull-left <?php echo('fdsAAA');?>"><?php echo($i);?></div>
                        <?php endfor; ?>
                    </div>
                    <div class="row border-left border-top">
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left fds">S</div>
                        <div class="dia pull-left fds">D</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left">T</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left fds">S</div>
                        <div class="dia pull-left fds">D</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left">T</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left fds">S</div>
                        <div class="dia pull-left fds">D</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left">T</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left fds">S</div>
                        <div class="dia pull-left fds">D</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left">T</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">Q</div>
                        <div class="dia pull-left">S</div>
                        <div class="dia pull-left fds">S</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif;?>
<?php if ($tipovisualizacao['value'] == 0) : ?>
                <section class="content">
                <div class="print-header row">
                    <div class="col-lg-2 col-xs-2"><img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/></div>
                    <div class="col-lg-10 col-xs-10 pull-right"><h3>Escalas Processadas</h3></div>
                </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header with-border dontprint">
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
                                                <th class="dontprint"><?php echo lang('escalas_unidadehospitalar');?></th>
                                                <th><?php echo lang('escalas_setor');?></th>
                                                <th><?php echo lang('escalas_profissional');?></th>
                                                <th><?php echo lang('escalas_dataplantao');?></th>
                                                <th><?php echo lang('escalas_diadasemana');?></th>
                                                <th><?php echo lang('escalas_horario');?></th>
                                                <th class="dontprint"><?php echo lang('escalas_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
    <?php foreach ($escalas as $escala) : ?>
                                            <tr>
                                                <td class="dontprint">
                                                    <?php echo htmlspecialchars($escala->unidadehospitalar_razaosocial, ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($escala->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($escala->profissional_nome ? $escala->profissional_nome : '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($escala->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($diasdasemana[date('w', strtotime($escala->dataplantao))], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($escala->horainicialplantao)) . " a " . date('H:i', strtotime($escala->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="dontprint">
                                                    <?php echo anchor('admin/escalas/edit/'.$escala->id, lang('actions_edit'), array('class' => 'btn btn-primary btn-flat')); ?> &nbsp;
                                                    <?php echo anchor('admin/escalas/view/'.$escala->id, lang('actions_see'), array('class' => 'btn btn-default btn-flat')); ?> &nbsp;
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
                                                <th class="dontprint"><?php echo lang('escalas_unidadehospitalar');?></th>
                                                <th><?php echo lang('escalas_setor');?></th>
                                                <th><?php echo lang('escalas_profissional');?></th>
                                                <th><?php echo lang('escalas_dataplantao');?></th>
                                                <th><?php echo lang('escalas_horario');?></th>
                                                <th class="dontprint"><?php echo lang('escalas_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
    <?php foreach ($escalas as $escala) : ?>
                                            <tr>
                                                <td class="dontprint">
                                                    <?php echo htmlspecialchars($escala->unidadehospitalar_razaosocial, ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($escala->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php if ($escala->passagenstrocas_id == null) :?>
                                                <td><?php echo htmlspecialchars($escala->profissional_nome ? $escala->profissional_nome : '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php else :?>
                                                <td><?php echo htmlspecialchars($escala->profissional_substituto_nome ? $escala->profissional_substituto_nome : '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php endif;?>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($escala->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($escala->horainicialplantao)) . " a " . date('H:i', strtotime($escala->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="dontprint">
                                                    <?php echo anchor('admin/escalas/view/'.$escala->id, lang('actions_see'), array('class' => 'btn btn-primary btn-flat')); ?>
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
                                                <th class="dontprint"><?php echo lang('escalas_unidadehospitalar');?></th>
                                                <th><?php echo lang('escalas_setor');?></th>
                                                <th><?php echo lang('escalas_profissional');?></th>
                                                <th><?php echo lang('escalas_profissional_substituto');?></th>
                                                <th><?php echo lang('escalas_dataplantao');?></th>
                                                <th><?php echo lang('escalas_horario');?></th>
                                                <th class="dontprint"><?php echo lang('escalas_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
    <?php foreach ($escalas as $escala) : ?>
                                            <tr>
                                                <td class="dontprint">
                                                    <?php echo htmlspecialchars($escala->unidadehospitalar_razaosocial, ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($escala->setor_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($escala->profissional_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($escala->profissional_substituto_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($escala->dataplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($escala->horainicialplantao)) . " a " . date('H:i', strtotime($escala->horafinalplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php echo anchor('admin/escalas/view/'.$escala->id, lang('actions_see'), array('class' => 'btn btn-primary btn-flat')); ?>
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
<?php endif; ?>
            </div>