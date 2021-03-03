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
                        <td style="color: white; background-color: green; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-user fa-5x" aria-hidden="true"></i><br>Lista de profissionais</center></td>
                        <td style="color: white; background-color: Orange; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-barcode fa-5x" aria-hidden="true"></i><br>Notas Fiscais</center></td>
                        <td style="color: white; background-color: royalblue; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-exchange fa-5x" aria-hidden="true"></i><br>Movimentação </center></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="color: white; background-color: green; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-address-card fa-5x" aria-hidden="true"></i><br>Produtos por categoria</center></td>
                        <td style="color: white; background-color: firebrick; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-battery-quarter fa-5x" aria-hidden="true"></i><br>Produtos Sem Estoque</center></td>
                        <td style="color: white; background-color: royalblue; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-battery-half fa-5x" aria-hidden="true"></i><br>Produtos a vencer</center></td>
                        <td style="color: white; background-color: green; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-id-badge fa-5x" aria-hidden="true"></i><br>Equip. por profissional</center></td>
                        <td style="color: white; background-color: orange; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-building fa-5x" aria-hidden="true"></i><br>Equip. por setor</center></td>
                    </tr>
                    <tr>
                        <td style="color: white; background-color: royalblue; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-truck fa-5x" aria-hidden="true"></i><br>Bens Móveis</center></td>
                        <td style="color: white; background-color: orange; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-tasks fa-5x" aria-hidden="true"></i><br>Bens de Consumo</center></td>
                        <td style="color: white; background-color: green; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-reply fa-5x" aria-hidden="true"></i><br>Entrada de Produtos</center></td>
                        <td style="color: white; background-color: firebrick; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-share fa-5x" aria-hidden="true"></i><br>Saída de Produtos</center></td>
                        <td style="color: white; background-color: royalblue; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-plus-square fa-5x" aria-hidden="true"></i><br>Materiais médicos</center></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="color: white; background-color: firebrick; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-laptop fa-5x" aria-hidden="true"></i><br>Materiais Ieventosormatica</center></td>
                        <td style="color: white; background-color: royalBlue; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-shopping-cart fa-5x" aria-hidden="true"></i><br>Compras recentes</center></td>
                        <td style="color: white; background-color: green; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-recycle fa-5x" aria-hidden="true"></i><br>Materiais Doação</center></td>                        
                        
                        <td></td>
                    </tr>
                </table></center>
            </div>
        </div>
</section></div>