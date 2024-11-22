 <div class="row">
    <div class="col-lg-12">
        <div class="row table-header-row">
            <div class="panel-heading datatable-heading">
                <?php  
                $query=$this->request->query; 
                if(!empty($this->request->query)&&$this->request->query['type']=="mail_feed"){
                    unset($this->request->query['msts']);  
                    $query="?";
                    $query .=http_build_query($this->request->query);
                } 

                $activeClass=($msts=='opened')?"active":"";
                
                $defClass=(!isset($query['msts']))?"active":"";
                echo '<a href="'.$this->here.$query.'&msts=opened" class="btn btn-primary '.$defClass .$activeClass.'"> Opened</a> '; 


                $activeClass=($msts=='admin')?"active":"";
                if($createdfrom=='admin'){
                    echo '<a href="'.$this->here.$query.'&msts=admin" class="btn btn-primary '.$activeClass.'">Send by Admin</a> ';
                }
               ?>

                <?php
                $activeClass=($msts=='clicked'||$msts=='link-subscriber')?"active":"";
                echo '<a href="'.$this->here.$query.'&msts=clicked" class="btn btn-primary '.$activeClass.'"> Link activity</a> ';?>

                <?php  
                if($createdfrom=='fronted'){
                     $activeClass=($msts=='graph')?"active":"";
                    echo '<a href="'.$this->here.$query.'&msts=graph" class="btn btn-primary '.$activeClass.'">Graph</a> ';
                }
                ?>
            </div>    
        </div>    
    </div>
</div>
