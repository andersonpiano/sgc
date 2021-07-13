<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<?php if (!$this->ion_auth->is_admin()) : ?>
            <nav class="navbar navbar-default navbar-fixed-bottom">
                <div class="container-fluid">
                    <div class="" id="bottom-toolbar">
                        <ul class="navbar-nav list-inline">
                            <li title="Próximos plantões"><a href="<?php echo base_url('/admin/profissional/plantoes/proximosplantoes');?>" class="fa fa-2x fa-calendar"></a></li>
                            <li title="Cessões e Trocas"><a href="<?php echo base_url('/admin/profissional/plantoes/cessoestrocas');?>" class="fa fa-2x fa-exchange"></a></li>
                            <!--<li title="Oportunidades"><a href="<?php //echo base_url('/admin/plantoes/oportunidades');?>" class="fa fa-2x fa-medkit"></a></li>-->
                        </ul>
                    </div>
                </div>
            </nav>
<?php endif; ?>

            <footer id="footer" class="main-footer">
                <div class="pull-right hidden-xs">
                    <b><?php echo lang('footer_version'); ?></b>
                </div>
                <strong><?php echo lang('footer_copyright'); ?></strong>
            </footer>
        </div>

        <!--<script src="<?php //echo base_url($frameworks_dir . '/jquery/jquery.min.js'); ?>"></script>-->
        <script src="<?php echo base_url($frameworks_dir . '/bootstrap/js/bootstrap.min.js'); ?>"></script>
        <script src="<?php echo base_url($plugins_dir . '/slimscroll/slimscroll.min.js'); ?>"></script>
<?php if ($mobile == true): ?>
        <script src="<?php echo base_url($plugins_dir . '/fastclick/fastclick.min.js'); ?>"></script>
<?php endif; ?>
<?php if ($admin_prefs['transition_page'] == true): ?>
        <script src="<?php echo base_url($plugins_dir . '/animsition/animsition.min.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'users' && ($this->router->fetch_method() == 'create' or $this->router->fetch_method() == 'edit')): ?>
        <script src="<?php echo base_url($plugins_dir . '/pwstrength/pwstrength.min.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'groups' && ($this->router->fetch_method() == 'create' or $this->router->fetch_method() == 'edit')): ?>
        <script src="<?php echo base_url($plugins_dir . '/tinycolor/tinycolor.min.js'); ?>"></script>
        <script src="<?php echo base_url($plugins_dir . '/colorpickersliders/colorpickersliders.min.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'escalas' && $this->router->fetch_method() == 'atribuir'): ?>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/onchange_tipo_plantao.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'plantoes'): ?>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/plantoes.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'plantoes' && $this->router->fetch_method() == 'exchangebyadmin'): ?>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/plantoes_por_profissional_mes_setor.js'); ?>"></script>
<?php endif; ?>
<?php if (in_array($this->router->fetch_class(), ['valoresplantoes', 'plantoes', 'escalas', 'profissionais', 'users'])) : ?>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/setores.js'); ?>"></script>
<?php endif; ?>
        <script src="<?php echo base_url($frameworks_dir . '/adminlte/js/adminlte.min.js'); ?>"></script>
        <script src="<?php echo base_url($frameworks_dir . '/domprojects/js/dp.min.js'); ?>"></script>
    </body>
</html>