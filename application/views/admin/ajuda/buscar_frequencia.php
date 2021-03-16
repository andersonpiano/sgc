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
                <center><h3><label>1. Acesse: <a href="http://www.cemerge.com.br/sgc" ><h3>http://www.cemerge.com.br/sgc</h3></a></label><h3></center>
                <center><img style="width:30%; height:30%" src="/sgc/assets/manuais/img/primeiro_acesso.png"/></center><br>
                <center><h3><strong>2. Clique no link “Acessar o sistema” ou no botão “Acessar” no banner principal.</strong></h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/primeiro_acesso2.png"></center><br>
                <center><h3><strong>3. Entre com seu e-mail cadastrado na CEMERGE e sua senha cadastrada.</strong></h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/primeiro_acesso3.png"></center><br>
                <center><h3><strong>4. Vá até o menu “Conferência” e escolha uma opção.</strong></h3></center>
                <center><img style="width:20%; height:20%;" src="/sgc/assets/manuais/img/buscar_frequencia.png"></center><br>
                <center><h3><strong>5. Na opção “Buscar frequência por setor”, Você seleciona Unidade hospitalar, Data Inicial e Data final e clica em “Buscar”.</strong></h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/buscar_frequencia2.png"></center><br>
                <center><h3><strong>6. Selecionado os campos, aparecerá esta tela: Primeiro o nome do médico, seguido de sua batida de ponto. “entrada ou saída”.</strong></h3></center>
                <center><img style="width:40%; height:40%;" src="/sgc/assets/manuais/img/buscar_frequencia3.png"></center><br>
                <center><h3><strong>7. Na opção “Buscar frequência por profissional”, você seleciona Unidade Hospitalar, Médico(a), Data Inicial, Data final e clica em “Buscar”.</strong></h3></center>
                <center><img style="width:50%; height:50%;" src="/sgc/assets/manuais/img/buscar_frequencia4.png"></center><br>
                <center><h3><strong>8. Selecionado os campos, aparecerá esta tela: Primeiro o nome do médico, seguido de Setor, data e suas frequências. “entrada ou saída”.</strong></h3></center>
                <center><img style="width:50%; height:50%;" src="/sgc/assets/manuais/img/buscar_frequencia5.png"></center><br>
            </div>
        </div>
    </div>    
</section>