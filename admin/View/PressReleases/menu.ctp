<?php 
// $url=(isset($url))?$url: $this->request->pass[0]; 
// if($url=='editors'){

?>
 <div class="row">
    <div class="col-lg-12">
        <div class="row table-header-row">
            <div class="card-heading datatable-heading">
            <?php 
            /*echo $this->Html->link('<i class="icon-list"></i> All Users', array('controller' => $controller, 'action' => 'index'), array('class' => 'btn btn-primary', 'escape' => false));*/
            ?>
            <?php 
            
            echo $this->Html->link('<i class="icon-plus-sign-alt"></i> Add New '.$menuadd, array('controller' => $controller, 'action' => 'add',$url), array('class' => 'btn btn-primary', 'escape' => false)); 
            ?>

            <?php  
            if($check_trash>0){
            	echo $this->Html->link("<i class='icon-plus-sign-alt'></i> Trash ( ".$check_trash." )", array('controller' => $controller, 'action' => 'trash',$url), array('class' => 'btn btn-danger', 'escape' => false));
            }
           /* if($action=="index"){
            echo " Filter by :";
            echo $this->Html->link("<i class='icon-plus-sign-alt'></i> Admin ", array('controller' => $controller, 'action' => 'index','1'), array('class' => '', 'escape' => false));
            echo " | ".$this->Html->link("<i class='icon-plus-sign-alt'></i> Editor", array('controller' => $controller, 'action' => 'index','2'), array('class' => '', 'escape' => false));
            }*/
           	?>
        </div>    
        </div>    
    </div>
</div>
<?php // } ?>