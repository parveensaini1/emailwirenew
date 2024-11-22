<!-- Modal -->
  <div class="modal fade" id="modelBannerTypePop" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body row">
            <div id="modelAjaxLoading"><img src="/img/ajax-loader.gif">Loading... Please wait...</div>
            <div class="responseModel"></div>
        </div>
      </div>
    </div> 
  </div>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
               <?php echo $this->element('submenu');
               ?>
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $tableHeaders = $this->Html->tableHeaders(array(
                                $this->Paginator->sort(__("id")),
                                __("name"),
                                __("Status"),
                                __('Actions'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        
                         <tbody>
                            <?php

                            $rows = array();
                            if (count($data_array) > 0) {
                                foreach ($data_array AS $count => $data) {
                                   $actions = ' ' . $this->Html->link(__('Edit'), array('controller' => $controller, 'action' => 'edit', $data[$model]['id']), array('class' => 'btn btn-xs btn-success'));
                                  $actions .= ' ' . $this->Html->link(__('Delete'), array(
                                                'controller' => $controller,
                                                'action' => 'delete',
                                                $data[$model]['id'],
                                                    ), array('class' => 'btn btn-xs btn-danger', 'onclick' => 'return confirmAction(this.href);'));
                                  if($data[$model]['status'] == 1){
                                    $status = 'Active';
                                    $actions .= ' ' . $this->Html->link(__('Inactive'), array('controller' => $controller, 'action' => 'disapprove', $data[$model]['id']), array('class' => 'btn btn-xs btn-danger'));
                                  }else{
                                    $status = 'Inactive';
                                    $actions .= ' ' . $this->Html->link(__('Active'), array('controller' => $controller, 'action' => 'approve', $data[$model]['id']), array('class' => 'btn btn-xs btn-success'));
                                  }
                                    $rows[] = array(
                                        __($count+1),
                                        __($data[$model]['title']),
                                        __($status),
                                        $actions,
                                    );
                                }
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            } else {
                                ?>
                                <tr>
                                    <td align="center" colspan="6">No result found!</td>
                                </tr>
                                <?php
                            }
                            ?> 
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <?php echo $this->element('pagination'); ?>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<script type="text/javascript">
    $(document).ready(function() {
    $(".fancybox").fancybox({
        openEffect  : 'none',
        closeEffect : 'none'
    });
});
</script>
<?php echo $this->Html->script(array( '/plugins/fancybox/jquery.fancybox',));?>
<script type="text/javascript">
    function BannerTypePopup(id){
         $("#modelAjaxLoading").show();
         $('#modelBannerTypePop').modal("show");
         var path=SITEURL+'ajax/bannertypeDetails/'+id;
           $.ajax({
           url: path,
           type:'GET',
           success:function(response){
             $("#modelAjaxLoading").hide();
            if(response){
            var obj=JSON.parse(response);
            $(".modal-title").text(obj.BannerType.title);
            var html='<div class="col-sm-5"><ul><li>Remark : <span>'+obj.BannerType.remark+'</span></li><li>Dimensions : <span>'+obj.BannerType.width+' X '+obj.BannerType.height+'</span></li></ul></div><div class="col-sm-7"><img src="'+obj.BannerType.image+'"></div>';
            $(".responseModel").html(html);
             }
           }
          });
    }
</script>