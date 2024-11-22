<h2><?php echo __d('cake_dev', 'Database Error'); ?></h2>
<p class="alert alert-error">
        <button class="close" data-dismiss="alert">×</button>
        <strong><?php echo __d('cake_dev', 'Error'); ?>: </strong>
        <?php echo h($error->getMessage()); ?>
</p>
<?php if (!empty($error->queryString)) : ?>
        <p class="alert alert-info">
        <button class="close" data-dismiss="alert">×</button>
            <strong><?php echo __d('cake_dev', 'SQL Query'); ?>: </strong>
            <?php echo $error->queryString; ?>
        </p>
<?php endif; ?>
<?php if (!empty($error->params)) : ?>
                <strong><?php echo __d('cake_dev', 'SQL Query Params'); ?>: </strong>
                <?php echo Debugger::dump($error->params); ?>
<?php endif; ?>
<p class="alert alert-info">
        <button class="close" data-dismiss="alert">×</button>
        <strong><?php echo __d('cake_dev', 'Notice'); ?>: </strong>
        <?php echo __d('cake_dev', 'If you want to customize this error message, create %s', APP_DIR . DS . 'View' . DS . 'Errors' . DS . 'pdo_error.ctp'); ?>
</p>
<?php echo $this->element('exception_stack_trace'); ?>