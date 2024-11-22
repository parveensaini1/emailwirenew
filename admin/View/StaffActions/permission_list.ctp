<table class="table table-bordered table-striped">
    <thead class="">
        <tr> 
            <th>#</th>
            <th>Actions</th>
            <?php foreach ($role_list as $role_key => $role_value) { ?>
                <th><?php echo $role_value; ?></th>  
            <?php } ?> 
        </tr>
    </thead>
    <tbody>
        <?php foreach ($controller_detail['StaffAction2'] as $function) { ?>
            <tr id="tr_<?php echo $function['id']; ?>">
                <td width="5%"><?php echo $function['id']; ?></td>
                <td width="15%"><?php echo $function['label']; ?></td>
                <?php foreach ($role_list as $role_key => $role_value) { ?>
                    <td width="15%" class="label-td"> 
                        <?php if ($role_key == 1) { ?>
                            <i class="fa fa-unlock"></i>
                        <?php }else{ ?>
                        <a class="change-permission" href="javascript:void(0);" controller-id="<?php echo $controller_detail['StaffAction']['id']; ?>" data-id="<?php echo  $function['id']; ?>" role-id="<?php echo $role_key; ?>">
                            <span id="status_<?php echo $controller_detail['StaffAction']['id'] . '_' . $role_key . '_' . $function['id']; ?>">
                                <?php if ($this->Acl->getPermission($role_key,  $function['id'])) { ?>
                                    <i class="fa fa-unlock"></i>
                                <?php } else { ?>
                                    <i class="fa fa-lock"></i>
                                <?php } ?>
                            </span> 
                        </a>
                        <?php } ?>
                        <span id=loader_<?php echo $function['id']; ?>></span>
                    </td> 
                <?php } ?> 
            </tr>
        <?php } ?>  
    </tbody>
</table>