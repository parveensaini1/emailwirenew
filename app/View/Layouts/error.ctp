<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $title_for_layout; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.4 --> 
        <?php
        echo $this->Html->css(
                array(
                    '/website/css/font-awesome',
                    '/website/css/bootstrap-grid.min',
                    '/website/css/bootstrap-reboot.min',
                    '/website/css/bootstrap.min',
                    '/website/css/custom',
                )
        );
        ?> 
        <?php
        echo $this->Js->writeBuffer(array('cache' => true));
        echo $this->Html->script(
                array(
                    '/website/js/jquery-3.3.1.min',
                    '/website/js/popper.min',
                    '/website/js/bootstrap.min',
                    '/website/js/custom',
                )
        );
        ?> 
    </head>
    <body>
        <?php echo $this->element('site_header'); ?>

        <?php
        if ($this->params->controller == 'home' && $this->params->action == 'index') {
            echo $this->element('site_banner');
        }
        ?>

        <div class="full ew-home-mid">
            <div class="container">
                <?php echo $this->fetch('content'); ?>
            </div>
        </div>
        <?php echo $this->element('site_footer'); ?>
    </body>
</html>
