<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

            <aside class="main-sidebar">
                <section class="sidebar">
<?php if ($admin_prefs['user_panel'] == true): ?>
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="<?php echo base_url($avatar_dir . '/generic.png'); ?>" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php echo $user_login['firstname'].$user_login['lastname']; ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> <?php echo lang('menu_online'); ?></a>
                        </div>
                    </div>

<?php endif; ?>
<?php if ($admin_prefs['sidebar_form'] == true): ?>
                    <!-- Search form -->
                    <form action="#" method="get" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="<?php echo lang('menu_search'); ?>...">
                            <span class="input-group-btn">
                                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>

<?php endif; ?>
                    <!-- Sidebar menu -->
                    <ul class="sidebar-menu">
                        <li>
                            <a href="<?php echo site_url('/'); ?>">
                                <i class="fa fa-home text-primary"></i> <span><?php echo lang('menu_access_website'); ?></span>
                            </a>
                        </li>

                        <li class="header text-uppercase"><?php echo lang('menu_main_navigation'); ?></li>
                        <li class="<?=active_link_controller('dashboard')?>">
                            <a href="<?php echo site_url('admin/dashboard'); ?>">
                                <i class="fa fa-dashboard"></i> <span><?php echo lang('menu_dashboard'); ?></span>
                            </a>
                        </li>

                        <li class="<?=active_link_controller('ajuda')?>">
                            <a href="<?php echo site_url('admin/ajuda'); ?>">
                                <i class="fa fa-info-circle"></i> <span><?php echo lang('menu_ajuda'); ?></span>
                            </a>
                        </li>

<?php if($this->ion_auth->in_group('profissionais')) :?>
                        <li class="treeview <?=active_link_controller('plantoes')?>">
                            <a href="#">
                                <i class="fa fa-calendar"></i>
                                <span><?php echo lang('menu_plantoes'); ?></span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('index')?>"><a href="<?php echo site_url('admin/profissional/plantoes'); ?>"><?php echo lang('menu_plantoes_search'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('proximosplantoes')?>"><a href="<?php echo site_url('admin/profissional/plantoes/proximosplantoes'); ?>"><?php echo lang('menu_plantoes_next'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('cessoestrocas')?>"><a href="<?php echo site_url('admin/profissional/plantoes/cessoestrocas'); ?>"><?php echo lang('menu_plantoes_exchange'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('frequencia')?>"><a href="<?php echo site_url('admin/plantoes/frequencia'); ?>"><?php echo lang('menu_plantoes_frequencia'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('oportunidade')?>"><a href="<?php echo site_url('admin/escalas/listaroportunidades'); ?>"><?php echo lang('menu_oportunidades'); ?></a></li>
                            </ul>
                        </li>
<?php endif; ?>

<?php if($this->ion_auth->in_group('residentes')) :?>
                        <li class="treeview <?=active_link_controller('residentes')?>">
                            <a href="#">
                                <i class="fa fa-user-md"></i>
                                <span><?php echo lang('menu_residentes'); ?></span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('residentes')?>"><a href="<?php echo site_url('admin/residentes/register'); ?>"><?php echo lang('menu_residentes_register'); ?></a></li>
                            </ul>
                        </li>
<?php endif; ?>

<?php if($this->ion_auth->in_group('coordenadorplantao')) :?>
                        <li class="header text-uppercase"><?php echo lang('menu_coordenadorplantao'); ?></li>
                        <li class="treeview <?=active_link_controller('plantoes')?>">
                            <a href="#">
                                <i class="fa fa-calendar"></i>
                                <span><?php echo lang('menu_coordenadorplantao_plantoes'); ?></span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('index')?>"><a href="<?php echo site_url('admin/coordenador/plantoes'); ?>"><?php echo lang('menu_plantoes_search'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('proximosplantoes')?>"><a href="<?php echo site_url('admin/coordenador/plantoes/proximosplantoes'); ?>"><?php echo lang('menu_plantoes_next'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('cessoestrocas')?>"><a href="<?php echo site_url('admin/coordenador/plantoes/cessoestrocas'); ?>"><?php echo lang('menu_plantoes_exchange'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('justificativa')?>"><a href="<?php echo site_url('admin/justificativas'); ?>"><?php echo lang('menu_justificativas'); ?></a></li>
                            </ul>
                        </li>
                        <li class="treeview <?=active_link_controller('escalas')?>">
                            <a href="#">
                                <i class="fa fa-calendar"></i>
                                <span><?php echo lang('menu_escalas'); ?></span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('create')?>"><a href="<?php echo site_url('admin/escalas/create'); ?>"><?php echo lang('menu_escalas_create'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('createfixed')?>"><a href="<?php echo site_url('admin/escalas/createfixed'); ?>"><?php echo lang('menu_escalas_create_fixed'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('createextra')?>"><a href="<?php echo site_url('admin/escalas/createextra'); ?>"><?php echo lang('menu_escalas_create_extra'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('index')?>"><a href="<?php echo site_url('admin/escalas'); ?>"><?php echo lang('menu_escalas_find'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('atribuir')?>"><a href="<?php echo site_url('admin/escalas/atribuir'); ?>"><?php echo lang('menu_escalas_atribuir'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('validar')?>"><a href="<?php echo site_url('admin/escalas/validar'); ?>"><?php echo lang('menu_escalas_validar'); ?></a></li>
                            </ul>
                        </li>
                        <li class="<?=active_link_controller('profissionais')?>">
                            <a href="<?php echo site_url('admin/profissionais'); ?>">
                                <i class="fa fa-user-md"></i> <span><?php echo lang('menu_profissionais'); ?></span>
                            </a>
                        </li>
<?php endif; ?>

<?php if($this->ion_auth->in_group('admin')) :?>
                        <li class="header text-uppercase"><?php echo lang('menu_administration'); ?></li>
                        <li class="<?=active_link_controller('unidadeshospitalares')?>">
                            <a href="<?php echo site_url('admin/unidadeshospitalares'); ?>">
                                <i class="fa fa-hospital-o"></i> <span><?php echo lang('menu_unidadeshospitalares'); ?></span>
                            </a>
                        </li>
                        <li class="<?=active_link_controller('setores')?>">
                            <a href="<?php echo site_url('admin/setores'); ?>">
                                <i class="fa fa-plus-square"></i> <span><?php echo lang('menu_setores'); ?></span>
                            </a>
                        </li>
                        <li class="<?=active_link_controller('estoque')?>">
                            <a href="<?php echo site_url('admin/estoque'); ?>">
                                <i class="fa fa-truck"></i> <span><?php echo lang('menu_estoque'); ?></span>
                            </a>
                        </li>
                        <li class="<?=active_link_controller('profissionais')?>">
                        <a href="#">
                                <i class="fa fa-user-md"></i>
                                <span><?php echo lang('menu_profissionais'); ?></span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li class="fa fa-user-md"><a href="<?php echo site_url('admin/profissionais'); ?>"><?php echo lang('menu_profissionais_create'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('especializacao')?>"><a href="<?php echo site_url('admin/especializacoes/'); ?>"><?php echo lang('menu_especializacoes'); ?></a></li>
                            </ul>
                        </li>
                        <li class="treeview <?=active_link_controller('residentes')?>">
                            <a href="#">
                                <i class="fa fa-user-md"></i>
                                <span><?php echo lang('menu_residentes'); ?></span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('create')?>"><a href="<?php echo site_url('admin/residentes/create'); ?>"><?php echo lang('menu_residentes_create'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('index')?>"><a href="<?php echo site_url('admin/residentes/'); ?>"><?php echo lang('menu_residentes_find'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('register')?>"><a href="<?php echo site_url('admin/residentes/register'); ?>"><?php echo lang('menu_residentes_register'); ?></a></li>
                            </ul>
                        </li>
                        <li class="treeview <?=(active_link_controller('escalas') && (active_link_function('index') or active_link_function('create') or active_link_function('createfixed') or active_link_function('createextra') or active_link_function('atribuir'))) ? 'active' : ''?>">
                            <a href="#">
                                <i class="fa fa-calendar"></i>
                                <span><?php echo lang('menu_escalas'); ?></span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('index')?>"><a href="<?php echo site_url('admin/escalas'); ?>"><?php echo lang('menu_escalas_find'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('create')?>"><a href="<?php echo site_url('admin/escalas/create'); ?>"><?php echo lang('menu_escalas_create'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('createfixed')?>"><a href="<?php echo site_url('admin/escalas/createfixed'); ?>"><?php echo lang('menu_escalas_create_fixed'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('createextra')?>"><a href="<?php echo site_url('admin/escalas/createextra'); ?>"><?php echo lang('menu_escalas_create_extra'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('atribuir')?>"><a href="<?php echo site_url('admin/escalas/atribuir'); ?>"><?php echo lang('menu_escalas_atribuir'); ?></a></li>
                            </ul>
                        </li>
                        <li class="treeview <?=(
                                active_link_function('conferencia') or
                                active_link_function('buscarescalaprocessada') or
                                active_link_function('buscarfrequencias') or
                                active_link_function('listarcessoesetrocas') or
                                active_link_function('buscarescalaporprofissional') or
                                (active_link_controller('faltas') && active_link_function('index')) or
                                (active_link_controller('frequencias') && active_link_function('buscarfrequenciaporprofissional'))
                            ) ? 'active' : ''?>">
                            <a href="#">
                                <i class="fa fa-calendar-check-o"></i>
                                <span><?php echo lang('menu_conferencias'); ?></span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('conferencia')?>"><a href="<?php echo site_url('admin/escalas/conferencia'); ?>"><?php echo lang('menu_conferencias_conferencia'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('buscarescalaprocessada')?>"><a href="<?php echo site_url('admin/escalas/buscarescalaprocessada'); ?>"><?php echo lang('menu_conferencias_buscar_processadas'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('buscarfrequencias')?>"><a href="<?php echo site_url('admin/frequencias/buscarfrequencias'); ?>"><?php echo lang('menu_conferencias_buscar_frequencias'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('buscarfrequenciaporprofissional')?>"><a href="<?php echo site_url('admin/frequencias/buscarfrequenciaporprofissional'); ?>"><?php echo lang('menu_conferencias_buscar_frequencias_por_profissional'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('buscarescalaporprofissional')?>"><a href="<?php echo site_url('admin/escalas/buscarescalaporprofissional'); ?>"><?php echo lang('menu_conferencias_buscar_escalas_por_profissional'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('index')?>"><a href="<?php echo site_url('admin/faltas'); ?>"><?php echo lang('menu_faltas_find'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('listarcessoesetrocas')?>"><a href="<?php echo site_url('admin/escalas/listarcessoesetrocas'); ?>"><?php echo lang('menu_listar_cessoesetrocas'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('justificativa')?>"><a href="<?php echo site_url('admin/justificativas'); ?>"><?php echo lang('menu_justificativas'); ?></a></li>
                            </ul>
                        </li>
                        <li class="treeview <?=active_link_controller('processamento') ? 'active' : ''?>">
                            <a href="#">
                                <i class="fa fa-gears"></i>
                                <span><?php echo lang('menu_processamento'); ?></span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('index')?>"><a href="<?php echo site_url('admin/processamento'); ?>"><?php echo lang('menu_processamento'); ?></a></li>
                            </ul>
                        </li>
                        <li class="<?=active_link_controller('feriados')?>">
                            <a href="<?php echo site_url('admin/feriados'); ?>">
                                <i class="fa fa-calendar-times-o"></i> <span><?php echo lang('menu_feriados'); ?></span>
                            </a>
                        </li>
                        <li class="<?=active_link_controller('vinculos')?>">
                            <a href="<?php echo site_url('admin/vinculos'); ?>">
                                <i class="fa fa-group"></i> <span><?php echo lang('menu_vinculos'); ?></span>
                            </a>
                        </li>

                        <li class="header text-uppercase"><?php echo lang('menu_security'); ?></li>
                        <li class="<?=active_link_controller('users')?>">
                            <a href="<?php echo site_url('admin/users'); ?>">
                                <i class="fa fa-user"></i> <span><?php echo lang('menu_users'); ?></span>
                            </a>
                        </li>
                        <li class="<?=active_link_controller('groups')?>">
                            <a href="<?php echo site_url('admin/groups'); ?>">
                                <i class="fa fa-shield"></i> <span><?php echo lang('menu_security_groups'); ?></span>
                            </a>
                        </li>
                        <li class="treeview <?=active_link_controller('prefs')?>">
                            <a href="#">
                                <i class="fa fa-cogs"></i>
                                <span><?php echo lang('menu_preferences'); ?></span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('interfaces')?>"><a href="<?php echo site_url('admin/prefs/interfaces/admin'); ?>"><?php echo lang('menu_interfaces'); ?></a></li>
                            </ul>
                        </li>
<?php endif; ?>

<?php if($this->ion_auth->in_group('sac')) :?>
                        <li class="header text-uppercase"><?php echo lang('menu_administration'); ?></li>
                        <li class="<?=active_link_controller('unidadeshospitalares')?>">
                            <a href="<?php echo site_url('admin/unidadeshospitalares'); ?>">
                                <i class="fa fa-hospital-o"></i> <span><?php echo lang('menu_unidadeshospitalares'); ?></span>
                            </a>
                        </li>
                        <li class="<?=active_link_controller('setores')?>">
                            <a href="<?php echo site_url('admin/setores'); ?>">
                                <i class="fa fa-plus-square"></i> <span><?php echo lang('menu_setores'); ?></span>
                            </a>
                        </li>
                        <li class="<?=active_link_controller('profissionais')?>">
                            <a href="<?php echo site_url('admin/profissionais'); ?>">
                                <i class="fa fa-user-md"></i> <span><?php echo lang('menu_profissionais'); ?></span>
                            </a>
                        </li>
                        <li class="treeview <?=active_link_controller('escalas')?>">
                            <a href="#">
                                <i class="fa fa-calendar"></i>
                                <span><?php echo lang('menu_escalas'); ?></span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('create')?>"><a href="<?php echo site_url('admin/escalas/create'); ?>"><?php echo lang('menu_escalas_create'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('createfixed')?>"><a href="<?php echo site_url('admin/escalas/createfixed'); ?>"><?php echo lang('menu_escalas_create_fixed'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('createextra')?>"><a href="<?php echo site_url('admin/escalas/createextra'); ?>"><?php echo lang('menu_escalas_create_extra'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('index')?>"><a href="<?php echo site_url('admin/escalas'); ?>"><?php echo lang('menu_escalas_find'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('conferencia')?>"><a href="<?php echo site_url('admin/escalas/conferencia'); ?>"><?php echo lang('menu_escalas_conferencia'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('buscarescalaprocessada')?>"><a href="<?php echo site_url('admin/escalas/buscarescalaprocessada'); ?>"><?php echo lang('menu_escalas_buscar_processadas'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('buscarfrequencias')?>"><a href="<?php echo site_url('admin/escalas/buscarfrequencias'); ?>"><?php echo lang('menu_escalas_buscar_frequencias'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('buscarfrequenciasemescala')?>"><a href="<?php echo site_url('admin/escalas/buscarfrequenciasemescala'); ?>"><?php echo lang('menu_escalas_buscar_frequencias_sem_escala'); ?></a></li>
                            </ul>
                            <ul class="treeview-menu">
                                <li class="<?=active_link_function('atribuir')?>"><a href="<?php echo site_url('admin/escalas/atribuir'); ?>"><?php echo lang('menu_escalas_atribuir'); ?></a></li>
                            </ul>
                        </li>
                        <li class="<?=active_link_controller('feriados')?>">
                            <a href="<?php echo site_url('admin/feriados'); ?>">
                                <i class="fa fa-calendar-times-o"></i> <span><?php echo lang('menu_feriados'); ?></span>
                            </a>
                        </li>
                        <li class="header text-uppercase"><?php echo lang('menu_security'); ?></li>
                        <li class="<?=active_link_controller('users')?>">
                            <a href="<?php echo site_url('admin/users'); ?>">
                                <i class="fa fa-user"></i> <span><?php echo lang('menu_users'); ?></span>
                            </a>
                        </li>
<?php endif; ?>
                    </ul>
                </section>
            </aside>
