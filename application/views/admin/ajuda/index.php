<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

    <div class="content-wrapper">
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
                <center><table style="margin: 100px; border: 6px solid #ecf0f5;">
                    <tr>
                        <td></td>
                        <td style="color: white; background-color: green; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><a href="ajuda/primeiro_acesso"><i class="fa fa-user fa-5x" aria-hidden="true"></i></a><br>Primeiro Acesso</center></td>                        
                        <td style="color: white; background-color: Orange; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><a href="ajuda/buscar_frequencia"><i class="fa fa-binoculars fa-5x" aria-hidden="true"></i></a><br>Buscar Frequencias</center></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="color: white; background-color: chocolate; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><a href="/sgc/assets/manuais/como_trocar_ou_confirmar_plantao.pdf"><i class="fa fa-exchange fa-5x" aria-hidden="true"></i></a><br>Trocar ou confirmar Plantao</center></td>
                        <td style="color: white; background-color: firebrick; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><a href="/sgc/assets/manuais/como_trocar_plantao_de_medico.pdf"><i class="fa fa-random fa-5x" aria-hidden="true"></i></a><br>Trocar Plantao de Medico</center></td>
                        <td style="color: white; background-color: royalblue; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><a href="ajuda/buscar_escala"><i class="fa fa-search fa-5x" aria-hidden="true"></i></a><br>Buscar Escala</center></td>
                        <td style="color: white; background-color: gray; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><a href="/sgc/assets/manuais/como_criar_e_validar_escala.pdf"><i class="fa fa-pencil fa-5x" aria-hidden="true"></i></a><br>Criar e Validar Escala</center></td>
                    </tr>
                    <tr>
                        <td style="color: white; background-color: olive; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><a href="/sgc/assets/manuais/como_criar_escala_por_copia.pdf"><i class="fa fa-object-ungroup fa-5x" aria-hidden="true"></i></a><br>Criar Escala por Copia</center></td>
                        <td style="color: white; background-color: darkBlue; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><a href="/sgc/assets/manuais/como_criar_primeira_escala.pdf"><i class="fa fa-calendar-o fa-5x" aria-hidden="true"></i></a><br>Criar Primeira Escala</center></td>
                        <td style="color: white; background-color: teal; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><a href="/sgc/assets/manuais/como_ceder_ou_confirmar_um_plantao.pdf"><i class="fa fa-refresh fa-5x" aria-hidden="true"></i></a><br>Ceder Plantao de Medico</center></td>
                        <td style="color: white; background-color: purple; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><a href="/sgc/assets/manuais/como_buscar_frequencia_sem_escala.pdf"><i class="fa fa-clone fa-5x" aria-hidden="true"></i></a><br>Buscar Frequencia sem Escala</center></td>
                    </tr>
                </table></center>
            </div>
        </div>
    </div>    
</section>