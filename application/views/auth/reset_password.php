<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
            <div class="login-logo">
                <a href="#"><b>SG</b><?php echo $title_lg; ?></a>
            </div>

            <div class="login-box-body">
                <p class="login-box-msg"><?php echo lang('auth_forgot_password'); ?></p>
                <?php echo $message;?>

                <?php echo form_open('auth/resetpassword/' . $code);?>
                    <div class="form-group has-feedback">
                        <?php echo form_input($new_password);?>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <?php echo form_input($new_password_confirm);?>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <?php echo form_input($user_id);?>
                            <?php echo form_hidden($csrf); ?>
                            <?php echo form_submit('submit', lang('auth_change_password'), array('class' => 'btn btn-primary btn-block btn-flat'));?>
                        </div>
                    </div>
                <?php echo form_close();?>
            </div>