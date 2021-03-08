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
                <center><h2><label>Acesse:<br><a href="http://www.cemerge.com.br/sgc" ><h3>http://www.cemerge.com.br/sgc</h3></a></label></h2></center>
                <center><img style="width:40%; height:40%;" src="/sgc/assets/manuais/img/primeiro_acesso.png"></center><br>
                <center><h2><strong>1. Clique no link “Acessar o sistema” ou no botão “Acessar” no banner principal.</strong></h2></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/primeiro_acesso2.png"></center><br>
                <center><h2><strong>2. Entre com seu e-mail cadastrado na CEMERGE e sua senha cadastrada.</strong></h2></align>
                <center><img style="width:30%; height:30%;"  src="/sgc/assets/manuais/img/primeiro_acesso3.png"></center><br>
                <center><h2><strong>3. Vá até o menu “Escalas” e escolha a opção “Buscar Escalas”.</strong></h2></center>
                <center><img style="width:20%; height:20%;"  src="/sgc/assets/manuais/img/buscar_escala.png"></center><br>
                <center><h2><strong>4. Nessa opção, você seleciona a Unidade hospitalar, Setor, Data Inicial, Data final, Tipo de escala e clica em “Buscar”.</strong></h2></center>
                <center><img style="width:30%; height:30%;"  src="/sgc/assets/manuais/img/buscar_escala2.png"></center><br>
                <center><td align='justify'><h2><strong>OBS:<br> Tipo de Escala: Original - Escala ainda não alterada pelo coordenador ou profissional.<br><br>
                                    Tipo de Escala: Consolidada - Escala já alterada pelo coordenador ou profissional e com plantões definitivos.<br><br>
                                    Tipo de Escala: Trocas e passagens - Aparece apenas as trocas de plantões entre os profissionais.</strong></h2><br><br></td align='justify'></center>
                <center><h2><strong>5. Na opção “Buscar" irá aparecer as escalas em ordem de data.</strong></h2></center>
                <center><img style="width:60%; height:60%;"  src="/sgc/assets/manuais/img/buscar_escala3.png"></center><br>
            </div>
    </div>
</section>
    </div> 