<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
            <div class="login-logo">
                <a href="#"><b>SG</b><?php echo $title_lg; ?></a>
            </div>

            <div class="login-box-body">
                <p class="login-box-msg"><?php echo lang('auth_sign_session'); ?></p>
                <?php echo $message;?>

                <?php echo form_open('auth/login');?>
                    <?php echo(form_hidden('destino', $destino)); ?>
                    <div class="form-group has-feedback">
                        <?php echo form_input($identity);?>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <?php echo form_input($password);?>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="checkbox icheck">
                                <label>
                                    <?php echo form_checkbox('remember', '1', false, 'id="remember"'); ?><?php echo lang('auth_remember_me'); ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <?php echo form_submit('submit', lang('auth_login'), array('class' => 'btn btn-primary btn-block btn-flat'));?>
                        </div>
                    </div>
                <?php echo form_close();?>

<?php if ($auth_social_network == true) : ?>
                <div class="social-auth-links text-center">
                    <p>- <?php echo lang('auth_or'); ?> -</p>
                    <?php echo anchor('#', '<i class="fa fa-facebook"></i>' . lang('auth_sign_facebook'), array('class' => 'btn btn-block btn-social btn-facebook btn-flat')); ?>
                    <?php echo anchor('#', '<i class="fa fa-google-plus"></i>' . lang('auth_sign_google'), array('class' => 'btn btn-block btn-social btn-google btn-flat')); ?>
                </div>
<?php endif; ?>
<?php if ($forgot_password == true) : ?>
                <?php echo anchor('auth/forgotpassword', lang('auth_forgot_password')); ?><br />
<?php endif; ?>
<?php if ($new_membership == true) : ?>
                <?php echo anchor('#', lang('auth_new_member')); ?><br />
<?php endif; ?>
            </div>

            <section class="content-header">
                <div class="alert bg-warning alert-dismissible" role="alert">
                Utilizamos cookies e tecnologias semelhantes de acordo com a nossa Política de Privacidade e, ao efetuar logon no sistema, você concorda com estas condições.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </section>
