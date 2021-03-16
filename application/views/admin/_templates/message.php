<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

        <section class="content-header">
            <div class="alert bg-warning alert-dismissible" role="alert">
                <?php echo(isset($message) ? $message : '');?>
                <?php echo($this->session->flashdata('message') ? $this->session->flashdata('message') : '');?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </section>