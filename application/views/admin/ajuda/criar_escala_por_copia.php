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
                <center><h3><strong>3. No menu “Escalas” e escolha a opção “Nova escala por cópia”<br> (Essa opção copia a escala do mês anterior com todos os profissionais alocados para o período selecionado.<br> É muito útil quando há poucas mudanças na escala de um mês para outro).</strong></h3></center>
                <center><img style="width:20%; height:20%;"  src="/sgc/assets/manuais/img/copia.png"></center><br>
                <center><h3><strong>4. Nessa Janela devemos selecionar a “Unidade hospitalar”, “Setor”, “Data Inicial” e “Data Final”<br> da escala que queremos copiar para o intervalo das datas selecionadas.</strong></h3></center>
                <center><img style="width:30%; height:30%;"  src="/sgc/assets/manuais/img/copia2.png"></center><br>
                <center><h3><strong>5. Clique no botão “Salvar”, em seguida aparecerá uma janela (conforme imagem abaixo)<br> solicitando a confirmação e informando a possível exclusão, caso haja,<br> de alguma escala existente e preenchida nesse mesmo período escolhido.<br> Esta será gerada sobre a existente puxando os médicos de acordo com a escala do mês anterior.</strong></h3></center>
                <center><img style="width:30%; height:30%;"  src="/sgc/assets/manuais/img/copia3.png"></center><br>
                <center><h3><strong>6. Após confirmar, clicando no “OK” será exibida a seguinte confirmação.</strong></h3></center>
                <center><img style="width:30%; height:30%;"  src="/sgc/assets/manuais/img/copia4.png"></center><br>
                <center><h3><strong>7. Caso seja necessário mudar algum profissional, vá até a opção “Atribuir a Médico(a)”, que explicaremos a seguir.</strong></h3></center><br>
                <center><h2><strong>Como alterar uma escala copiada?</strong></h2></center>
                <center><h3><strong>8. Para fazer isso, você deve acessar a área “Coordenação de Plantão”, ir até o menu “Escalas”<br> Escolha a opção “Atribuir a Médico(a)” (Essa opção só deve ser utilizada para mudanças mais duradouras.<br> Mudanças temporárias devem ser feitas mediante trocas e cessões entre os profissionais).</strong></h3></center>
                <center><img style="width:20%; height:20%;"  src="/sgc/assets/manuais/img/copia5.png"></center><br>
                <center><h3><strong>9. Selecione a Unidade Hospitalar, Setor, Dias da Semana ou Turno. Clique no botão “Filtrar”.</strong></h3></center>
                <center><img style="width:30%; height:30%;"  src="/sgc/assets/manuais/img/copia6.png"></center><br>
                <center><h3><strong>10. Serão listados todos os plantões do setor no período, de acordo com os filtros selecionados.</strong></h3></center>
                <center><img style="width:30%; height:30%;"  src="/sgc/assets/manuais/img/copia7.png"></center><br>
                <center><h3><strong>11. Selecione o clique no nome do(a) Médico(a) e serão listados todos os(as) médicos(as) vinculados ao setor.</strong></h3></center>
                <center><img style="width:30%; height:30%;"  src="/sgc/assets/manuais/img/copia8.png"></center><br>
                <center><h3><strong>12. Basta selecionar o médico que irá assumir o plantão. Não precisa salvar, o sistema já efetua a mudança automaticamente.</strong></h3></center>
                <center><img style="width:30%; height:30%;"  src="/sgc/assets/manuais/img/copia9.png"></center><br>
            </div>
    </div>
</section>
    </div> 