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
                <center><img style="width:20%; height:20%;" src="/sgc/assets/manuais/img/primeiro_acesso2.png"></center><br>
                <center><h3><strong>2. Vá até o menu “Escalas” e escolha a opção “Nova escala inicial”.</strong></h3></align>
                <center><img style="width:20%; height:20%;"  src="/sgc/assets/manuais/img/nova_escala.png"></center><br>
                <center><h3><strong>3. Selecione a Unidade Hospitalar, Setor, Data inicial e final, Hora inicial e final da escala. Clique em "Enviar".</strong></h3></center>
                <center><img style="width:30%; height:30%;"  src="/sgc/assets/manuais/img/nova_escala2.png"></center><br>
                <center><h3><strong>4. Repita o item anterior para criar os horários da escala.</strong></h3></center>
                <center><h3><strong>5. A escala do setor foi criada. Agora temos que atribuir essas escalas aos(às) médicos(as).</strong></h3></center><br>
                <center><h2><strong>Como atribuir as escalas aos médicos(as)?</strong></h2></center>
                <center><h3><strong>Esse procedimento será realizado na primeira vez que a escala for criada<br> ou quando houver mudança de médico(a) em determinados dias e horários de escala.</strong></h3></center><br>
                <center><h3><strong>1. Acesse o menu “Escalas” e escolha a opção “Atribuir a Médico(a)”</strong></h3></center>
                <center><img style="width:20%; height:20%;"  src="/sgc/assets/manuais/img/nova_escala3.png"></center><br>
                <center><h3><strong>2. Selecione a Unidade Hospitalar, Setor, Data inicial e final do período da escala criada anteriormente.<br> Caso você queira filtrar por dia da semana e / ou turno, basta fazer a seleção.<br> (Esse filtro é útil quando você precisar filtrar todas as sextas-feiras no turno manhã,<br> por exemplo, e atribuir a um único médico que sempre trabalha nesses dias, nesse setor).<br> Clique no botão enviar para buscar as escalas previamente criadas conforme o filtro.</strong></h3></center>
                <center><img style="width:30%; height:30%;"  src="/sgc/assets/manuais/img/nova_escala4.png"></center><br>
                <center><h3><strong>3. Selecione o médico no campo “Médico(a)” e ele será vinculado aquela escala naquele dia e horário.</strong></h3></center>
                <center><img style="width:30%; height:30%;"  src="/sgc/assets/manuais/img/nova_escala5.png"></center><br>
                <center><h3><strong>4. No passo 3, não precisa salvar, o sistema já salva o vínculo entre o(a) médico(a) e o horário automaticamente.</strong></h3></center>
                <center><h3><strong>5. Repita o passo 3 até que nenhum dos horários fique sem médico(a) associado.</strong></h3></center><br>
                <center><h3><strong>Obs.: A partir da segunda escala criada para o setor, esse processo fica muito mais simples,<br> pois ele copiará da escala anterior os(as) médicos(as) na ordem exata em que foram vinculados.</strong></h3></center>
            </div>
    </div>
</section>
    </div> 