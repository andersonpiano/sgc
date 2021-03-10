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
                <center><h1><strong>Como acessar o sistema pela primeira vez e alterar a senha padrão?</strong></h1></center>
                <center><h3><label>1. Acesse: <a href="http://www.cemerge.com.br/sgc" ><h3>http://www.cemerge.com.br/sgc</h3></a></label><h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/primeiro_acesso.png"></center><br>
                <center><h3><strong>2. Clique no link “Acessar o sistema” ou no botão “Acessar” no banner principal.</strong></h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/primeiro_acesso2.png"></center><br>
                <center><h3><strong>3. Entre com seu e-mail cadastrado na CEMERGE e senha: cemerge2020</strong></h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/primeiro_acesso3.png"></center><br>
                <center><h3><strong>4. Por questões de segurança, em seu primeiro acesso, será necessário trocar a senha.</strong></h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/primeiro_acesso4.png"></center><br>
                <center><h3><strong>5. Informe a senha que você acabou de usar no campo “Sua senha atual”.<br> Informe a nova senha nos campos “Sua nova senha” e “Confirme sua nova senha”.<br> As senhas devem ter, no mínimo 8 caracteres e, no máximo, 20 caracteres.</strong></h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/primeiro_acesso5.png"></center><br>
                <center><h3><strong>6. Clique no botão “Alterar minha senha”.<br> Sua senha será alterada e você será redirecionado(a) para a tela principal.</strong></h3></center>
            </div>
        </div>
    </div>    
</section>