<table class="table table-striped table-bordered table-hover" id="dataTables-example">
	<thead>
		<?php  

		if($model=='Link'){
		$tableHeaders = $this->Html->tableHeaders(array($this->Paginator->sort("S/N"),__("Link"),__("Unique"),__("Total"),__("Action")), array(), array('class' => 'sorting'));
		}else{
			$tableHeaders = $this->Html->tableHeaders(array($this->Paginator->sort("S/N"),__("Email"),), array(), array('class' => 'sorting'));
		}
		echo $tableHeaders;
		?>
	</thead>
	 <tbody>
	 	<?php 
		$rows = array();
		if (count($data_array) > 0) {
		    foreach ($data_array as $index => $data) { 
		    	if($model=='Link'){
	    			$link = stripslashes($data[$model]['link']);
				  	$link_trunc = strlen($link) > 100 ? substr($link, 0, 100).'...' : $link;
		    		$link="<a href='".$data[$model]['link']."' target='_blank'>".$link_trunc."</a>";

					$unique_clicks = '0';
					$total_clicks = '0';
		    		if(!empty($data[$model]['clicks'])){
		    			$clicks=$data[$model]['clicks'];
			  			$total_clicks_array = explode(',', $clicks);
			  			$total_clicks = count($total_clicks_array);
			  			$unique_clicks = count(array_unique($total_clicks_array));
			  		}

			  		$query=$this->request->query; 
	                $query="?";
	                if(!empty($this->request->query) ){ $query .=http_build_query($this->request->query);} 
			  		$action='<a href="'.$this->here.$query.'&msts=link-subscriber&link='.$data[$model]['id'].'" class="btn btn-xs btn-primary">View list</a>';
		    		$rows[] = array(
                    __($index+1),
	                  $link,
	                  $unique_clicks,
					  $total_clicks,
					  $action
	                );
		    	}else{
		    		$email= $this->Post->getEmailforClippingReport($data[$model]['email']);
		    		$rows[] = array(
                    __($index+1),
	                    $email,
	                );

		    	}
		    }
		   echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
		}
        ?>

	 </tbody>
</table>	
<div class="row">
    <?php echo $this->element('pagination'); ?>
</div>  