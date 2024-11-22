<div class="row">
    <div class="col-lg-12">
        <div class="card card-default">
            <!-- /.card-heading -->
            <div class="card-body">
               <?php echo $this->element('submenu'); ?>
                <div class="dataTable_wrapper">
                <?php 
                echo $this->Form->create($model, array('novalidate' => 'novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
                //'onchange'=>"redirect_coupon_type(this.value)",
                echo $this->Form->input('id');

                echo $this->Form->input('type', array('options'=>array('flat'=>'Flat','percentage'=>'Percentage'),'empty' => '-Select Type-','class'=>'custom_select form-control','default'=>'flat'));?>
                    <div class="row">                        
                        <div class="col-sm-4">
                            <?php echo $this->Form->input('code', array('type' => 'text','maxLength'=>'50')); ?>
                        </div>
                        <div class="col-sm-4">
                            <?php echo $this->Form->input('value', array('type' => 'text','maxLength'=>'8')); ?>
                        </div>
                        <div class="col-sm-4">
                            <?php 
                                echo $this->Form->input('coupon_limit', array('type' => 'number','min'=>'0'));
                                echo "<p class='text-gray'>0 mean no limit for this coupon.</p>";
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                           <?php echo $this->Form->input('release_date',array('type' =>'text','class'=>'cdatepicker form-control','label'=>'Coupon Release date'));?>
                        </div>
                        <div class="col-sm-4">
                           <?php echo $this->Form->input('end_date',array('type' =>'text','class'=>'cdatepicker form-control','label'=>'Coupon End date'));?>
                        </div>
                    </div>
                    <?php  
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                      echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

function forceInputUppercase(e)
{
var start = e.target.selectionStart;
var end = e.target.selectionEnd;
e.target.value = e.target.value.toUpperCase();
e.target.setSelectionRange(start, end);
}

document.getElementById("CouponCode").addEventListener("keyup", forceInputUppercase, false);

 $(function () {
        $(".cdatepicker").datepicker({
            dateFormat: "yy-mm-dd",
             minDate : 0,
            changeMonth: true,
            changeYear: true,
        });
        //   $('.timepicker').timepicker();
    });
function redirect_coupon_type(selected) {
window.location.replace(SITEURL+"plans/add/"+selected);
} 
</script>