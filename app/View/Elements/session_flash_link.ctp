<section class="" style="min-height:auto; margin:10px 0px 0px 0px;">
<div class="row">
        <div class="col-sm-12"> 
            <div class="alert alert-warning alert-dismissable" style="margin:0px;">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <i class="icon fa fa-warning"></i>
               <?php
					echo $message;
					echo $this->Html->link($link_text, $link_url, array("escape" => false));
?></div>  
        </div><!-- /.col -->
    </div>
        </section>
