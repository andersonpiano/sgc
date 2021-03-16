<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
.notboldlabel {
    font-weight: normal;
}

.navbar-nav {
    float: none;
    margin: 0 auto;
    display: table;
    table-layout: fixed;
}


/* ScrollMenu */
.scrollmenu > .row {
    display: flex;
    overflow-x: auto;
    overflow-y: hidden;
}
.scrollmenu > .row > .col-xs-10, .scrollmenu > .row > .col-md-3 {
    flex: 0 0 auto;
}

/* PACE */
.pace {
  -webkit-pointer-events: none;
  pointer-events: none;
  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
}

.pace-inactive {
  display: none;
}

.pace .pace-progress {
  background:black;
  position: fixed;
  z-index: 2000;
  top: 0;
  right: 100%;
  width: 100%;
  height: 2px;
}

.pace .pace-progress-inner {
  display: block;
  position: absolute;
  right: 0px;
  width: 100px;
  height: 100%;
  box-shadow: 0 0 10px black, 0 0 5px black;
  opacity: 1.0;
  -webkit-transform: rotate(3deg) translate(0px, -4px);
  -moz-transform: rotate(3deg) translate(0px, -4px);
  -ms-transform: rotate(3deg) translate(0px, -4px);
  -o-transform: rotate(3deg) translate(0px, -4px);
  transform: rotate(3deg) translate(0px, -4px);
}

.pace .pace-activity {
  display: block;
  position: fixed;
  z-index: 2000;
  top: 15px;
  right: 15px;
  width: 14px;
  height: 14px;
  border: solid 2px transparent;
  border-top-color: black;
  border-left-color: black;
  border-radius: 10px;
  -webkit-animation: pace-spinner 400ms linear infinite;
  -moz-animation: pace-spinner 400ms linear infinite;
  -ms-animation: pace-spinner 400ms linear infinite;
  -o-animation: pace-spinner 400ms linear infinite;
  animation: pace-spinner 400ms linear infinite;
}

@-webkit-keyframes pace-spinner {
  0% { -webkit-transform: rotate(0deg); transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); transform: rotate(360deg); }
}
@-moz-keyframes pace-spinner {
  0% { -moz-transform: rotate(0deg); transform: rotate(0deg); }
  100% { -moz-transform: rotate(360deg); transform: rotate(360deg); }
}
@-o-keyframes pace-spinner {
  0% { -o-transform: rotate(0deg); transform: rotate(0deg); }
  100% { -o-transform: rotate(360deg); transform: rotate(360deg); }
}
@-ms-keyframes pace-spinner {
  0% { -ms-transform: rotate(0deg); transform: rotate(0deg); }
  100% { -ms-transform: rotate(360deg); transform: rotate(360deg); }
}
@keyframes pace-spinner {
  0% { transform: rotate(0deg); transform: rotate(0deg); }
  100% { transform: rotate(360deg); transform: rotate(360deg); }
}

@media screen {
    .print-header {
        display: none;
    }
  }

/** Print */
@media print {
    header nav, footer {
        display: none;
    }
    img {
        max-width: 500px;
    }
    @page {
        margin: 0.5cm;
    }
    @page :left {
        margin: 0.5cm;
    }
    
    @page :right {
        margin: 0.8cm;
    }
    .print-header {
        display: block;
    }
}
@media print {
    #calendar {
        max-width: 100% !important;
        overflow: visible !important;
        /*transform: scale(.6);*/
        margin: 0 auto;
    }
    .findform {
        display: none;
    }
    .dontprint {
      display: none;
    }
}

/* Grade de Frequência */
.grade-frequencia {
  background-color: white;
}
.dia {
  width: 3.22%;
  height: 30px;
  text-align: center;
  border-width: 1px;
  border-style: solid solid solid solid; /* TRBL */
  border-color: black;
}
.fds {
  background-color: lightgray;
}
.dados-profissional {
  height: 30px;
  max-height: 30px;
  font-size: 90%;
  overflow: hidden;
}
.border {
  border-width: 1px;
  border-style: solid solid solid solid;
  border-color: black;
}
.border-bottom {
  border-width: 1px;
  border-style: none none solid none; /* TRBL */
  border-color: black;
}
.border-top {
  border-width: 1px;
  border-style: solid none none none; /* TRBL */
  border-color: black;
}
.border-right {
  border-width: 1px;
  border-style: none solid none none; /* TRBL */
  border-color: black;
}
.border-left {
  border-width: 1px;
  border-style: none none none solid; /* TRBL */
  border-color: black;
}


/* Bootstrap 4 width e height */
.w-5 {
  width: 5% !important;
}

.w-10 {
  width: 10% !important;
}

.w-15 {
  width: 15% !important;
}

.w-20 {
  width: 20% !important;
}

.w-25 {
  width: 25% !important;
}

.w-30 {
  width: 30% !important;
}

.w-50 {
  width: 50% !important;
}

.w-75 {
  width: 75% !important;
}

.w-100 {
  width: 100% !important;
}

.w-auto {
  width: auto !important;
}

.h-25 {
  height: 25% !important;
}

.h-50 {
  height: 50% !important;
}

.h-75 {
  height: 75% !important;
}

.h-100 {
  height: 100% !important;
}

.h-auto {
  height: auto !important;
}





/* declare a 7 column grid on the table */
#calendar {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}

#calendar tr, #calendar tbody {
    grid-column: 1 / -1;
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    width: 100%;
}

#calendar caption {
    text-align: center;
    grid-column: 1 / -1;
    font-size: 130%;
    font-weight: bold;
    padding: 10px 0;
}

#calendar a {
    color: #00a65a;
    text-decoration: none;
}

#calendar td, #calendar th {
    padding: 5px;
    box-sizing:border-box;
    border: 1px solid #ccc;
}

#calendar .weekdays {
    background: #00a65a;
}

#calendar .weekdays th {
    text-align: center;
    text-transform: uppercase;
    line-height: 20px;
    border: none !important;
    padding: 10px 6px;
    color: #fff;
    font-size: 13px;
}

#calendar td {
    min-height: 180px;
    display: flex;
    flex-direction: column;
}

#calendar .days li:hover {
    background: #d3d3d3;
}

#calendar .date {
    text-align: center;
    margin-bottom: 5px;
    padding: 4px;
    background: #333;
    color: #fff;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    flex: 0 0 auto;
    align-self: center;
}

#calendar .event {
    flex: 0 0 auto;
    font-size: 12px;
    border-radius: 4px;
    padding: 3px;
    margin-bottom: 5px;
    line-height: 12px;
    background: #e4f2f2;
    border: 1px solid #b5dbdc;
    color: #009aaf;
    text-decoration: none;
}

#calendar .event-desc {
    color: #666;
    margin: 3px 0 7px 0;
    text-decoration: none;
}

#calendar .other-month {
    background: #f5f5f5;
    color: #666;
}

/* ============================
     Mobile Responsiveness
   ============================*/
@media(max-width: 768px) {
    #calendar .weekdays, #calendar .other-month {
        display: none;
    }

    #calendar li {
        height: auto !important;
        border: 1px solid #ededed;
        width: 100%;
        padding: 10px;
        margin-bottom: -1px;
    }
  
    #calendar, #calendar tr, #calendar tbody {
        grid-template-columns: 1fr;
    }
  
    #calendar  tr {
        grid-column: 1 / 2;
    }

    #calendar .date {
        align-self: flex-start;
    }
}



div#calendar{
    margin: 0px auto;
    padding: 2px;
    /*width: 602px;*/
    width: 100%;
    height: 100%;
    max-width: 1000px;
    margin: 40px auto;
    /*font-family:Helvetica, "Times New Roman", Times, serif;*/
}

div#calendar div.box {
    position:relative;
    top:0px;
    left:0px;
    width:98%;
    height:40px;
    background-color: #787878;
}

div#calendar div.header {
    line-height:40px;  
    vertical-align:middle;
    position:absolute;
    left:11px;
    top:0px;
    width:98%;
    height:40px;
    text-align:center;
}

div#calendar div.header a.prev,div#calendar div.header a.next { 
    position:absolute;
    top:0px;   
    height: 17px;
    display:block;
    cursor:pointer;
    text-decoration:none;
    color:#FFF;
}

div#calendar div.header span.title {
    color:#FFF;
    font-size:18px;
}

div#calendar div.header a.prev {
    left:0px;
}

div#calendar div.header a.next {
    right:0px;
}

div#calendar div.box-content {
    border:1px solid #787878 ;
    border-top:none;
}

div#calendar ul.label {
    float:left;
    margin: 0px;
    padding: 0px;
    margin-top:5px;
    margin-left: 5px;
}

div#calendar ul.label li{
    margin:0px;
    padding:0px;
    margin-right:5px;  
    float:left;
    list-style-type:none;
    width:14%; /*80px*/
    height:7%;/*40px*/
    line-height:7%;/*40px*/
    vertical-align:middle;
    text-align:center;
    color:#000;
    font-size: 15px;
    background-color: transparent;
}

div#calendar ul.dates{
    float:left;
    margin: 0px;
    padding: 0px;
    margin-left: 5px;
    margin-bottom: 5px;
}

/** overall width = width+padding-right**/
div#calendar ul.dates li{
    margin:0px;
    padding:0px;
    margin-right:5px;
    margin-top: 5px;
    line-height:80px;
    vertical-align:middle;
    float:left;
    list-style-type:none;
    width:14%;/*40px*/
    height:14%;/*40px*/
    font-size:25px;
    background-color: #DDD;
    color:#000;
    text-align:center;
}

:focus{
    outline:none;
}

div.clear{
    clear:both;
}
</style>
            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                </section>

<?php if ($tipovisualizacao['value'] == 1) : ?>
    <section class="content">
        <?php echo($calendario);?>
    </section>
<?php endif;?>
<?php if ($tipovisualizacao['value'] == 2) : ?>
    <section class="content">
        <div class="print-header">
            <img src="<?php echo base_url($frameworks_dir . '/cemerge/images/logo.png'); ?>"/>
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
                                                <th><?php echo lang('escalas_diadasemana');?></th>
                                                <th><?php echo lang('escalas_horario');?></th>
                                                <th class="dontprint"><?php echo lang('escalas_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
    <?php foreach ($escalas as $escala) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($escala->unidadehospitalar_razaosocial, ENT_QUOTES, 'UTF-8'); ?></td>
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
                                                <th><?php echo lang('escalas_unidadehospitalar');?></th>
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
                                                <td><?php echo htmlspecialchars($escala->unidadehospitalar_razaosocial, ENT_QUOTES, 'UTF-8'); ?></td>
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