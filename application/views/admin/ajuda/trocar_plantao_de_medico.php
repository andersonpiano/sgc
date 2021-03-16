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
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/primeiro_acesso.png"></center><br>
                <center><h3><strong>2. Clique no link “Acessar o sistema” ou no botão “Acessar” no banner principal.</strong></h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/primeiro_acesso2.png"></center><br>
                <center><h3><strong>3. Entre com seu e-mail cadastrado na CEMERGE e sua senha cadastrada.</strong></h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/primeiro_acesso3.png"></center><br>
                <center><h3><strong>4. Na área “Coordenação de Plantão”, Vá até o menu “Plantões do Setor” e escolha a opção “Busca completa”.</strong></h3></center>
                <center><img style="width:25%; height:25%;" src="/sgc/assets/manuais/img/plantao_medico.png"></center><br>
                <center><h3><strong>5. Nessa opção, você deve selecionar a Unidade Hospitalar, Setor, Data inicial, Data final,<br> Tipo de Escala “Consolidada do Setor” e o Tipo de Visualização “Lista”. Clique em “Buscar”.</strong></h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/plantao_medico2.png"></center><br>
                <center><h3><strong>6. Será exibida a seguinte lista de plantões:</strong></h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/plantao_medico3.png"></center><br>
                <center><h3><strong>7. Clique no botão “Trocar” no plantão escolhido.</strong></h3></center>
                <center><img style="width:20%; height:20%;" src="/sgc/assets/manuais/img/plantao_medico4.png"></center><br>
                <center><h3><strong>8. Escolha o tipo de passagem “Troca”, selecione o(a) Médico(a) com o(a) qual quer trocar o plantão,<br> selecione o plantão que será trocado. Clique no botão “Salvar”.</strong></h3></center><br>
                <center><h3><strong>9. Quando esse procedimento é realizado pelo coordenador do setor,<br> os(as) Médicos(as) envolvidos na troca não precisam aceitar os plantões para que estes passem a ser atribuídos a eles(as).<br> A confirmação só precisa ser feita quando esse processo é realizado entre os(as) próprios(as) Médicos(a)</strong></h3></center>
            </div>
        </div>
    </div>    
</section>