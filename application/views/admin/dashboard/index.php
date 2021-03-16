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
                    <?php echo $dashboard_alert_file_install; ?>
                    <?php if ($this->ion_auth->in_group('profissionais')):?>
                    <div class="scrollmenu">
                        <div class="row">
                            <div class="col-md-3 col-xs-10">
                                <div class="w3-card-2 w3-white w3-margin-bottom w3-round-large">
                                    <header class="w3-container w3-white">
                                        <h5><span class="fa fa-lg fa-refresh"></span> Cessões e Trocas</h5>
                                    </header>
                                    <header class="w3-container w3-white">
                                        <h6><span class="fa fa-share"></span> Cessões</h6>
                                    </header>
                                    <div class="w3-container">
                                        <p><?php echo(anchor(site_url('admin/profissional/plantoes/cessoestrocas'), $cessoes)); ?></p>
                                    </div>
                                    <header class="w3-container w3-white">
                                        <h6><span class="fa fa-exchange"></span> Trocas</h6>
                                    </header>
                                    <div class="w3-container">
                                        <p><?php echo(anchor(site_url('admin/profissional/plantoes/cessoestrocas'), $trocas)); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-10">
                                <div class="w3-card-2 w3-white w3-margin-bottom w3-round-large">
                                    <header class="w3-container w3-white">
                                        <h5><span class="fa fa-lg fa-medkit"></span> Meus Plantões</h5>
                                    </header>
                                    <header class="w3-container w3-white">
                                        <h6><span class="fa fa-calendar"></span> Próximos plantões</h6>
                                    </header>
                                    <div class="w3-container">
                                        <p><?php echo(anchor(site_url('admin/profissional/plantoes/proximosplantoes'), $proximosplantoes)); ?></p>
                                    </div>
                                    <!--
                                    <header class="w3-container w3-white">
                                        <h6><span class="fa fa-plus-square"></span> Escala por Setor</h6>
                                    </header>
                                    <div class="w3-container">
                                        <p>Você tem 3 trocas propostas</p>
                                    </div>
                                    -->
                                </div>
                            </div>
                            <!--
                            <div class="col-md-3 col-xs-10">
                                <div class="w3-card-2 w3-white w3-margin-bottom w3-round-large">
                                    <header class="w3-container w3-white">
                                        <h5><span class="fa fa-lg fa-medkit"></span> Oportunidades</h5>
                                    </header>
                                    <header class="w3-container w3-white">
                                        <h6><span class="fa fa-hourglass-3"></span> Urgentes</h6>
                                    </header>
                                    <div class="w3-container">
                                        <p>Você tem 1 oportunidade urgente</p>
                                    </div>
                                    <header class="w3-container w3-white">
                                        <h6><span class="fa fa-user-md"></span> Para você</h6>
                                    </header>
                                    <div class="w3-container">
                                        <p>Você tem 3 oportunidades que combinam com seu perfil</p>
                                    </div>
                                </div>
                            </div>
                            -->
                        </div>
                    </div>
                    <?php endif;?>
                    <?php if ($this->ion_auth->in_group('admin')):?>
                    <div class="scrollmenu">
                        <div class="row">
                            <div class="col-md-3 col-xs-10">
                                <div class="w3-card-2 w3-white w3-margin-bottom w3-round-large">
                                    <header class="w3-container w3-white">
                                        <h5><span class="fa fa-lg fa-area-chart"></span> Estatísticas</h5>
                                    </header>
                                    <header class="w3-container w3-white">
                                        <h6><span class="fa fa-medkit"></span> Profissionais</h6>
                                    </header>
                                    <div class="w3-container">
                                        <p><?php echo($count_professionals . ' profissionais cadastrados.'); ?></p>
                                    </div>
                                    <header class="w3-container w3-white">
                                        <h6><span class="fa fa-users"></span> Usuários</h6>
                                    </header>
                                    <div class="w3-container">
                                        <p><?php echo($count_users . ' usuários cadastrados.'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-10">
                                <div class="w3-card-2 w3-white w3-margin-bottom w3-round-large">
                                    <header class="w3-container w3-white">
                                        <h5><span class="fa fa-lg fa-area-chart"></span> Estatísticas</h5>
                                    </header>
                                    <header class="w3-container w3-white">
                                        <h6><span class="fa fa-building"></span> Hospitais</h6>
                                    </header>
                                    <div class="w3-container">
                                        <p><?php echo($count_hospitals . ' hospitais cadastrados.'); ?></p>
                                    </div>
                                    <header class="w3-container w3-white">
                                        <h6><span class="fa fa-cubes"></span> Setores</h6>
                                    </header>
                                    <div class="w3-container">
                                        <p><?php echo($count_sectors . ' setores cadastrados.'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>

                    <div class="row">
                        <div class="col-md-12">
<?php
/*
if ($url_exist) {
    echo 'OK';
} else {
    echo 'KO';
}
*/
?>
                        </div>

                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Notificações</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
<?php if ($this->ion_auth->in_group('profissionais')):?>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-left text-uppercase"><strong>Plantões recebidos a confirmar</strong></p>
    <?php if(sizeof($plantoes_recebidos_confirmar) > 0) :?>
        <?php foreach($plantoes_recebidos_confirmar as $plantao_recebido) :?>
                                            <span class="badge badge-success">Importante</span>&nbsp;
                                            <p class="text-justify"><a href="<?php echo(site_url('admin/plantoes/confirm/'.$plantao_recebido->id));?>">
            <?php
                $text = $plantao_recebido->profissional_passagem_nome . ' está oferecendo um plantão no ';
                $text .= $plantao_recebido->unidadehospitalar_razaosocial . ' - ' . $plantao_recebido->setor_nome;
                $text .= ' no dia ' . date('d/m/Y', strtotime($plantao_recebido->dataplantao));
                $text .= ' de ' . date('H:i', strtotime($plantao_recebido->horainicialplantao));
                $text .= ' &agrave;s ' . date('H:i', strtotime($plantao_recebido->horafinalplantao)) . ' para você.';
                $text .= ' Clique aqui e confirme.';
                echo($text);
            ?>
                                            </a></p>
        <?php endforeach;?>
    <?php else:?>
                                                <p>Sem novidades por enquanto</p>
    <?php endif;?>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-left text-uppercase"><strong>Plantões passados sem confirmação</strong></p>
    <?php if(sizeof($plantoes_passados_confirmar) > 0) :?>
        <?php foreach($plantoes_passados_confirmar as $plantao_passado) :?>
                                            <span class="badge badge-success">Importante</span>&nbsp;
                                            <p class="text-justify">
            <?php
                $text = 'O plantão no ' . $plantao_passado->unidadehospitalar_razaosocial . ' - ' . $plantao_passado->setor_nome;
                $text .= ' do dia ' . date('d/m/Y', strtotime($plantao_passado->dataplantao));
                $text .= ' de ' . date('H:i', strtotime($plantao_passado->horainicialplantao));
                $text .= ' &agrave;s ' . date('H:i', strtotime($plantao_passado->horafinalplantao));
                if (isset($plantao_passado->profissional_substituto_nome)) {
                    $text .= ' que você ofertou a ' . $plantao_passado->profissional_substituto_nome;
                    $text .= ' ainda não foi confirmado. Entre em contato com ele(a) para que efetue a confirmação.';
                } else {
                    $text .= ' que você ofertou ainda não foi confirmado por ninguém do setor.';
                }                
                echo($text);
            ?>
                                            </p>
        <?php endforeach;?>
    <?php else:?>
                                                <p>Sem novidades por enquanto</p>
    <?php endif;?>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-left text-uppercase"><strong>Trocas propostas não confirmadas</strong></p>
    <?php if(sizeof($trocas_propostas_confirmar) > 0) :?>
        <?php foreach($trocas_propostas_confirmar as $troca_proposta) :?>
                                            <span class="badge badge-success">Importante</span>&nbsp;
                                            <p class="text-justify">
            <?php
                $text = 'O plantão no ' . $troca_proposta->unidadehospitalar_razaosocial . ' - ' . $troca_proposta->setor_nome;
                $text .= ' do dia ' . date('d/m/Y', strtotime($troca_proposta->dataplantao));
                $text .= ' de ' . date('H:i', strtotime($troca_proposta->horainicialplantao));
                $text .= ' &agrave;s ' . date('H:i', strtotime($troca_proposta->horafinalplantao));
                if (isset($troca_proposta->profissional_substituto_nome)) {
                    $text .= ' que você ofertou a ' . $troca_proposta->profissional_substituto_nome;
                    $text .= ' ainda não foi confirmado. Entre em contato com ele(a) para que efetue a confirmação.';
                } else {
                    $text .= ' que você ofertou ainda não foi confirmado por ninguém do setor.';
                }                
                echo($text);
            ?>
                                            </p>
        <?php endforeach;?>
    <?php else:?>
                                                <p>Sem novidades por enquanto</p>
    <?php endif;?>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-left text-uppercase"><strong>Trocas recebidas não confirmadas</strong></p>
    <?php if(sizeof($trocas_recebidas_confirmar) > 0) :?>
        <?php foreach($trocas_recebidas_confirmar as $troca_recebida) :?>
                                            <span class="badge badge-success">Importante</span>&nbsp;
                                            <p class="text-justify">
            <?php
                $text = 'A troca do plantão no ' . $troca_recebida->unidadehospitalar_razaosocial . ' - ' . $troca_recebida->setor_nome;
                $text .= ' do dia ' . date('d/m/Y', strtotime($troca_recebida->dataplantao));
                $text .= ' de ' . date('H:i', strtotime($troca_recebida->horainicialplantao));
                $text .= ' &agrave;s ' . date('H:i', strtotime($troca_recebida->horafinalplantao));
                $text .= ' que você recebeu de ' . $troca_recebida->profissional_passagem_nome;
                $text .= ' ainda não foi confirmado.';
                echo($text);
            ?>
                                            </p>
        <?php endforeach;?>
    <?php else:?>
                                                <p>Sem novidades por enquanto</p>
    <?php endif;?>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-left text-uppercase"><strong>Plantões aguardando justificativas</strong></p>
    <?php if(sizeof($justificativas) > 0) :?>
        <?php foreach($justificativas as $justificativa) :?>
                                            <span class="badge badge-success">Importante</span>&nbsp;
                                            <p class="text-justify">
            <?php
                $text = 'O plantão do dia ' . date('d/m/Y', strtotime($justificativa->dataplantao));
                $text .= ' Inicio: ' . date('H:i', strtotime($justificativa->horainicialplantao));
                $text .= ' Fim ' . date('H:i', strtotime($justificativa->horafinalplantao));
                $text .= ' Esta aguardando justificativa';
                
                echo($text);
            ?>
                                            </p>
        <?php endforeach;?>
    <?php else:?>
                                                <p>Sem novidades por enquanto</p>
    <?php endif;?>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-left text-uppercase"><strong>Oportunidades</strong></p>
    <?php if(sizeof($oportunidades) > 0) :?>
        <?php foreach($oportunidades as $oportunidade) :?>
                                                <span class="badge badge-success">Importante</span>&nbsp;
                                                <p class="text-justify"><a href="<?php echo(site_url('admin/escalas/confirmaroportunidade/'.$oportunidade->id));?>" onClick="alert(\'Deseja aceitar esse plantão?\')">
            <?php
                $text = $oportunidade->profissional_passagem_nome . ' está oferecendo um plantão no ';
                $text .= $oportunidade->unidadehospitalar_razaosocial . ' - ' . $oportunidade->setor_nome;
                $text .= ' no dia ' . date('d/m/Y', strtotime($oportunidade->dataplantao));
                $text .= ' de ' . date('H:i', strtotime($oportunidade->horainicialplantao));
                $text .= ' &agrave;s ' . date('H:i', strtotime($oportunidade->horafinalplantao)) . '.';
                $text .= ' Clique aqui para aceitar, caso seja do seu interesse.';
                echo($text);
            ?>
                                                </a></p>
        <?php endforeach;?>
    <?php else:?>
                                                <p>Sem novidades por enquanto</p>
    <?php endif;?>
                                        </div>
                                    </div>
                                </div>
<?php endif;?>
                            </div>
                        </div>


                    </div>
                </section>
            </div>
