<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <section class="content">
                    <?php echo $dashboard_alert_file_install; ?>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <a href="<?php echo(site_url('admin/plantoes'));?>">
                                <div class="info-box">
                                    <span class="info-box-icon bg-maroon"><i class="fa fa-calendar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Meus Plantões</span>
                                        <span class="info-box-number">Acessar</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!--
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">AdminLTE version</span>
                                    <span class="info-box-number">2.3.1</span>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix visible-sm-block"></div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Users</span>
                                    <span class="info-box-number"><?php //echo $count_users; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-shield"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Security groups</span>
                                    <span class="info-box-number"><?php //echo $count_groups; ?></span>
                                </div>
                            </div>
                        </div>
                        -->
                    </div>

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
                                            <p class="text-left text-uppercase"><strong>Trocas não confirmadas</strong></p>
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
                                            <p class="text-left text-uppercase"><strong>Oportunidades</strong></p>
    <?php if(sizeof($oportunidades) > 0) :?>
        <?php foreach($oportunidades as $oportunidade) :?>
                                                <span class="badge badge-success">Importante</span>&nbsp;
                                                <p class="text-justify"><a href="<?php echo(site_url('###'));?>">
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
