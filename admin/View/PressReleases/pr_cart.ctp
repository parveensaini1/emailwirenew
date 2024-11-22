<?php 
$hide="display:none";
$promo_code='';
$total_amount=$subtotal=$tax=$discount='0.00';
?>
<!-- <div style="display: none;"id="buy-plan-error" class="text-danger col-lg-12 buy-plan-error-msg">Your cart is empty.</div> -->

<div id="cartsection"  <?php if($total_amount<=0){?> style="display: none;" <?php } ?>  >
    <div class="ew-title-price full">
        <h2>Now in your cart</h2>
 
        <div class="full cart-items">
            <ul id="cartplanlist"></ul>  
            <ul id="cartfeaturelist"></ul>
        </div>
    </div>
    <div class="ew-cart-dis-block full">
        <?php /*?>
        <div id="applypromocode" class="ew-promocode full">
            <h2><span>Enter a Promo Code</span></h2>
        <?php echo $this->Form->create('Discount', array('type' => 'post',"default"=>"false","onsubmit"=>"applycoupon(event);"));
            echo $this->Form->input('promo_code',array('placeholder'=>"Type Code Here",'label'=>false,"id"=>"promo_code")); 
            $class=($discount==0)?"hide":"";
            echo $this->Html->link('Remove coupon', 'javascript:void(0)',['class'=>"removecoupon-link $class",'id' =>'removediscountbtn','onclick' =>'removecoupon(event)']); 
            echo $this->Form->submit('Apply');
            echo $this->Form->end(); ?>
        </div>
        <?php
            $show_coupon="display:none;";
            echo $this->Html->link('Apply Coupon Code', 'javascript:void(0)',['class'=>"applycouponbx",'id' =>'applypromobox',"style"=>$show_coupon,'onclick' =>'showapplycouponbox(event)']);
            */
            ?>
        
        <?php //if($subtotal>0){?>
        <div id="cart-subtotal-box" class="full ew-cart-row">
            <span class="float-left">Subtotal : </span>
            <span id="cartsubtotal" class="float-right text-right"></span>
        </div>
        <?php // } ?>
        <div id="disamount-box" class="full ew-cart-row" style="display: none;">
            <span class="float-left">Discount : </span>
            <span id="disamount" class="float-right text-right"></span>
        </div>
        <div id="carttax-box" class="full ew-cart-row" style="display: none;" >
            <span class="float-left">Tax : </span>
            <span id="carttax" class="float-right text-right"></span>     
        </div>
        <div class="full ew-cart-row">
            <span class="float-left">Total : </span>
            <span id="carttotalamout" class="float-right text-right"></span>
        </div>
    </div>
     <!-- <div class="buy_now_section">
        <a id="checkoutbtn" href="javascript:void(0)" onclick="submitform('submitwithpayment');" class="btn orange-btn">Checkout and submit PR</a>
    </div> --> 
 </div>
 <?php /*
if(!empty($company_list)){
    if(!empty($selectedplan)){ ?>
 <div class="ew-cart-btns-block full">
<div class="button_pr">
     <p>Before you submit PR check preview of this PR.</p>
 <a href="javascript:void(0)" onclick="submitform('preview');" class="btn btn-info">PR preview</a>
</div>
     <div class="button_pr">
         <p>If you do not want to submit this PR or want to edit letter then save in draft so you will not lost your filled PR content.</p>
 <a href="javascript:void(0)" onclick="submitform('indraft');" class="btn btn-primary">Save PR in draft</a>
         </div>
</div>
<?php } }*/ ?>