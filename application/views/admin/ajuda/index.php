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
                        <td style="color: white; background-color: green; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-user fa-5x" aria-hidden="true"></i><br>PrimeiroAcesso</center></td>
                        <td style="color: white; background-color: Orange; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-binoculars fa-5x" aria-hidden="true"></i><br>BuscarFrequencias</center></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="color: white; background-color: chocolate; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-exchange fa-5x" aria-hidden="true"></i><br>TrocarOuConfirmarPlantao</center></td>
                        <td style="color: white; background-color: firebrick; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-random fa-5x" aria-hidden="true"></i><br>TrocarPlantaoDeMedico</center></td>
                        <td style="color: white; background-color: royalblue; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-search fa-5x" aria-hidden="true"></i><br>BuscarEscala</center></td>
                        <td style="color: white; background-color: gray; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-pencil fa-5x" aria-hidden="true"></i><br>CriarEValidarEscala</center></td>
                    </tr>
                    <tr>
                        <td style="color: white; background-color: olive; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-object-ungroup fa-5x" aria-hidden="true"></i><br>CriarEscalaPorCopia</center></td>
                        <td style="color: white; background-color: darkBlue; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-calendar-o fa-5x" aria-hidden="true"></i><br>CriarPrimeiraEscala</center></td>
                        <td style="color: white; background-color: teal; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-refresh fa-5x" aria-hidden="true"></i><br>CederPlantaoDeMedico</center></td>
                        <td style="color: white; background-color: purple; width: 200px; height: 120px; border: 6px solid #ecf0f5;"><center><i class="fa fa-clone fa-5x" aria-hidden="true"></i><br>BuscarFrequenciaSemEscala</center></td>
                    </tr>
                </table></center>
            </div>
        </div>
</section></div>