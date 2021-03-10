<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

    <div class="content-wrapper" style="width:100%; height:100%;">
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
        <br>
<section style="min-height: calc(100vh - 83px);" class="light-bg">
    <div class="container">        
            <div class="container-fluid">
                <center><img src="/sgc/assets/frameworks/cemerge/images/logo.png"></center><br>
                <center><h1><strong>Como acessar o sistema.</strong></h1></center>
                <center><h3><label>Acesse:<br><a href="http://www.cemerge.com.br/sgc" ><h3>http://www.cemerge.com.br/sgc</h3></a></label></h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/primeiro_acesso.png"></center><br>
                <center><h3><strong>1. Clique no link “Acessar o sistema” ou no botão “Acessar” no banner principal.</strong></h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/primeiro_acesso2.png"></center><br>
                <center><h3><strong>2. Entre com seu e-mail cadastrado na CEMERGE e sua senha cadastrada.</strong></h3></align>
                <center><img style="width:30%; height:30%;"  src="/sgc/assets/manuais/img/primeiro_acesso3.png"></center><br>
                <center><h3><strong>3. Na área “Coordenação de Plantão” até o menu “Escalas” e escolha a opção “Validar escala”</strong></h3></center>
                <center><img style="width:20%; height:20%;"  src="/sgc/assets/manuais/img/validar.png"></center><br>
                <center><h3><strong>4. Selecione a unidade hospitalar, setor, ano e mês da escala que deseja validar.</strong></h3></center>
                <center><img style="width:30%; height:30%;"  src="/sgc/assets/manuais/img/validar2.png"></center><br>
                <center><h3><strong>5. A escala consolidada será listada, conforme abaixo:</strong></h3></center>
                <center><img style="width:30%; height:30%;"  src="/sgc/assets/manuais/img/validar3.png"></center><br>
                <center><h3><strong>6. Role até o final da página, examinando se a escala está totalmente correta e se não falta nenhuma cessão ou troca a ser realizada.<br> Caso esteja tudo ok, clique no botão “Validar e enviar a escala” ao final da página.</strong></h3></center>
                <center><img style="width:20%; height:20%;"  src="/sgc/assets/manuais/img/validar4.png"></center><br>
                <center><h3><strong>7. Será exibida uma mensagem de confirmação caso tudo ocorra como esperado ou uma mensagem de erro caso ocorra algum imprevisto.</strong></h3></center><br>
                <center><h3><strong>8. A escala agora está pronta para ser processada pela área responsável pelos processos na Cemerge.</strong></h3></center>
            </div>
    </div>
</section>
    </div> 