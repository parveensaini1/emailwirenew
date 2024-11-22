<table class="table table-bordered table-striped">
    <thead class="">
        <tr> 
            <th>Id</th>    
            <th>Function Name</th>
            <th>Label</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($controller_detail['StaffAction2'] as $function) { ?>
            <tr id="tr_<?php echo $function['id']; ?>">
                <td width="5%"><?php echo $function['id']; ?></td>
                <td width="15%"><?php echo $function['name']; ?></td>
                <td width="65%" class="label-td">
                    <div class="form-group group-text"> 
                        <input data-id="<?php echo $function['id']; ?>" class="change-label form-control" value="<?php echo $function['label']; ?>">
                    </div> 
                    <span id=loader_<?php echo $function['id']; ?>></span>
                </td>
                <td>
                    <a data-id="<?php echo $function['id']; ?>" class="update-status btn btn-xs <?php echo ($function['status']) ? 'btn-danger' : 'btn-success'; ?>" href="javascript:void(0);">
                        <span class="span_label"><?php echo ($function['status']) ? 'Disable' : 'Enable'; ?></span>
                        <span class="span_ldr" style="padding-left: 5px;display: none;"><?php echo $this->Html->image('spinners/loader2.gif'); ?></span>
                    </a>
                    <span id=loader2_<?php echo $function['id']; ?>></span>
                </td>
            </tr>
        <?php } ?>  
    </tbody>
</table>