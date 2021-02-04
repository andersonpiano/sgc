<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('escalas_box_title'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <th><?php echo lang('plantoes_dataplantao'); ?></th>
                                                <td><?php echo(date('d/m/Y', strtotime($plantao->dataplantao))); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('plantoes_horainicialplantao'); ?></th>
                                                <td><?php echo htmlspecialchars(date('H:i', strtotime($plantao->horainicialplantao)), ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('plantoes_horafinalplantao'); ?></th>
                                                <td><?php echo htmlspecialchars($plantao->horafinalplantao, ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">xxxx</h3>
                                </div>
                                <div class="box-body">


                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
