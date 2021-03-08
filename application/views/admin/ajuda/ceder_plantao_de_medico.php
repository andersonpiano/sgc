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
                <center><h3><strong>4. Vá até o menu “Meus Plantões” e escolha a opção “Próximos plantões”<br> (Você também pode realizar uma busca mais detalhada na opção “Busca completa”)</strong></h3></center>
                <center><img style="width:20%; height:20%;" src="/sgc/assets/manuais/img/trocar_plantao.png"></center><br>
                <center><h3><strong>5. Nessa opção, já aparecem todos os seus plantões nos próximos 30 dias.<br> Todos os setores e hospitais (onde o sistema estiver implantado).</strong></h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/trocar_plantao2.png"></center><br>
                <center><h3><strong>6. Clique no botão “Oferecer” no plantão escolhido.</strong></h3></center>
                <center><img style="width:30%; height:30%;" src="/sgc/assets/manuais/img/trocar_plantao3.png"></center><br>
                <center><h3><strong>7. Escolha o tipo de passagem “Cessão” e o(a) Médico(a) substituto:<br> Na opção lista de oportunidades, você vai oferecer para todos os médicos do setor.<br> Ou com qual médico você quer trocar o plantão. Depois clique no botão “Salvar”.</strong></h3></center>
                <center><h3><strong>8. O(A) Médico(a) escolhido deverá aceitar a proposta para que este plantao passe a ser atribuído a ele(a).</strong></h3></center><br>

                <center><h2><strong>Como aceitar um plantão cedido a mim?</strong></h2></center>
                <center><h3><strong>1. Vá até o menu “Meus Plantões” e escolha a opção “Cessões e trocas”.<br> (Você também pode realizar uma busca mais detalhada na opção “Busca completa”)</strong></h3></center>
                <center><img style="width:20%; height:20%;" src="/sgc/assets/manuais/img/trocar_plantao4.png"></center><br>
                <center><h3><strong>2. Nessa opção, já aparecem todas as cessões e trocas recebidas e passadas por você.</strong></h3></center>
                <center><h3><strong>3. Nessa opção você pode confirmar uma cessão passada para você.</strong></h3></center>
                <center><img style="width:50%; height:50%;" src="/sgc/assets/manuais/img/trocar_plantao5.png"></center>
                <center><h3><strong>4. Clique no botão “Confirmar”, se deseja confirmar o recebimento deste plantão.<br> O plantão só passará a constar como atribuído à você quando a confirmação for feita.<br> Será aberta a tela a seguir, mostrando maiores detalhes da cessão.</strong></h3></center>
                <center><img style="width:25%; height:25%;" src="/sgc/assets/manuais/img/ceder_plantao.png"></center>
                <center><h3><strong>5. Clique no botão “Confirmar”.</strong></h3></center>
            </div>
        </div>
    </div>    
</section>