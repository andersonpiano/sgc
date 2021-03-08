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
                <center><h1><label>1. Acesse: <a href="http://www.cemerge.com.br/sgc" >http://www.cemerge.com.br/sgc</a></label><h1></center>
                <center><img style="width:40%; height:40%;" src="/sgc/assets/manuais/img/primeiro_acesso.png"></center><br>
                <center><h2><strong>2. Clique no link “Acessar o sistema” ou no botão “Acessar” no banner principal.</strong></h2></center>
                <center><img src="/sgc/assets/manuais/img/primeiro_acesso2.png"></center><br>
                <center><h2><strong>3. Entre com seu e-mail cadastrado na CEMERGE e sua senha cadastrada.</strong></h2></center>
                <center><img src="/sgc/assets/manuais/img/primeiro_acesso3.png"></center><br>
                <center><h2><strong>4. Vá até o menu “Meus Plantões” e escolha a opção “Próximos plantões” (Você também pode realizar uma busca mais detalhada na opção “Busca completa”)</strong></h2></center>
                <center><img src="/sgc/assets/manuais/img/trocar_plantao.png"></center><br>
                <center><h2><strong>5. Nessa opção, já aparecem todos os seus plantões nos próximos 30 dias, em todos os setores e hospitais (onde o sistema esƟver implantado).</strong></h2></center>
                <center><img src="/sgc/assets/manuais/img/trocar_plantao2.png"></center><br>
                <center><h2><strong>6. Clique no botão “Oferecer” no plantão escolhido.</strong></h2></center>
                <center><img src="/sgc/assets/manuais/img/trocar_plantao3.png"></center><br>
                <center><h2><strong>7. Escolha o tipo de passagem “Cessão” e o(a) Médico(a) substituto:<br> Na opção lista de oportunidades, você vai oferecer para todos os médicos do setor.<br> Ou com qual médico você quer trocar o plantão. Depois clique no botão “Salvar”.</strong></h2></center>
                <center><h2><strong>8. O(A) Médico(a) escolhido deverá aceitar a proposta de troca para ser validado.</strong></h2></center>

                <center><h1><strong>Cessões e trocas.</strong></h1></center>
                <center><h2><strong>1. Vá até o menu “Meus Plantões” e escolha a opção “Cessões e trocas” (Você também pode realizar uma busca mais detalhada na opção “Busca completa”)</strong></h2></center>
                <center><img src="/sgc/assets/manuais/img/trocar_plantao4.png"></center><br>
                <center><h2><strong>2. Nessa opção, já aparecem todas as cessões e trocas recebidas e passadas por você.</strong></h2></center>
                <center><h2><strong>3. Também nessa opção você pode cancelar uma cessão passada para você.</strong></h2></center>
                <center><img src="/sgc/assets/manuais/img/trocar_plantao5.png"></center><br>

                <center><h1><strong>Como aceitar um plantão proposto em troca?</strong></h1></center>
                <center><h2><strong>1. Vá até o menu “Meus Plantões” e escolha a opção “Cessões e trocas” (Você também pode realizar uma busca mais detalhada na opção “Busca completa”)</strong></h2></center>
                <center><img src="/sgc/assets/manuais/img/trocar_plantao4.png"></center><br>
                <center><h2><strong>2. Nessa opção, já aparecem todas as cessões e trocas recebidas e passadas por você.</strong></h2></center>
                <center><img src="/sgc/assets/manuais/img/trocar_plantao6.png"></center><br>
                <center><img src="/sgc/assets/manuais/img/trocar_plantao7.png"></center><br>
                <center><h2><strong>3. Clique em “Confirmar”. O processo de troca está concluído e ambos os plantões agora constam na escala dos devidos profissionais.</strong></h2></center>
            </div>
        </div>
    </div>    
</section>