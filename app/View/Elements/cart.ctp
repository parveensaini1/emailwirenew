<?php 
$hide="display:none";
$cart_session_id=$promo_code='';
$total_amount=$subtotal=$tax=$discount='0.00';
if(!empty($cart_plans)){
    $promo_code=(!empty($cart_plans['promo_code']))?$cart_plans['promo_code']:"";
    $cart_session_id=(!empty($cart_plans['cart_session_id']))?$cart_plans['cart_session_id']:"";
    $_SESSION['cart_session_id'] = $cart_session_id;
    $discount=(!empty($cart_plans['totals']['discount']))?$cart_plans['totals']['discount']:"0.00";
    $tax=(!empty($cart_plans['totals']['tax']))?$cart_plans['totals']['tax']:"0.00";
    $subtotal=(!empty($cart_plans['totals']['subtotal']))?$cart_plans['totals']['subtotal']:"0.00";
    $total_amount=(!empty($cart_plans['totals']['total']))?$cart_plans['totals']['total']:"0.00";
    if(!empty($cart_plans['plans'])){
        $hide='';
    }
} 

if($total_amount>0){
    ?>
    <style>
        .cart-floating{display: block;}
    </style>
<?php } ?>
<div <?php if($total_amount>0){?> style="display: none;" <?php } ?> id="buy-plan-error" class="text-danger col-lg-12 buy-plan-error-msg">Your cart is empty. Please add plan in your cart.</div>
<div class="extra-margin" style="
    height: 450px !important;
    display: none;
"></div>

<div id="cartsection"  <?php if($total_amount<=0){?> style="display: none;" <?php } ?>  >
<div class="close-tab"><span></span></div>
    <div class="ew-title-price full">
        <h2>Now in your cart</h2>
        <?php  
         echo "<a id='clear-cart' style='".$hide."' href='javascript:void(0)' onclick='clearcart()'>Clear cart </a>";
        if(isset($cart_plans['newsroom_amount'])&&!empty($cart_plans['newsroom_amount'])&&$cart_plans['newsroom_amount']>0){?>
        <div class="full cart-items">
            <span class="float-left">Newsroom Fees(<?=ucfirst($cart_plans["newsroom_duration"]);?>)</span>
            <span class="float-right"><?php echo Configure::read('Site.currency'). $cart_plans['newsroom_amount'];?></span> 
        </div>
    <?php } ?>
        <div class="full cart-items">
            <ul id="cartplanlist">
                <?php
                if(!empty($cart_plans)){
                    // $cart_plans=$this->session->read("ew_cartdata");
                    if(!empty($cart_plans['plans'])){
                        foreach ($cart_plans['plans'] as $index => $plan) {
                         echo "<li id='plan-".$plan['plan_id']."' class='item-".$plan['plan_id']."'><span class='remove-cart'><a href='javascript:void(0)' onclick='removecartitem(".$plan['plan_id'].")'> X </a></span><span class='float-left'>".$plan['title']."</span><span class='float-right'>".Configure::read('Site.currency')."".$plan['amount']."</span></li>";
                        }
                    }else{
                        //$subtotal="0.00";
                        // $total_amount=Configure::read('Site.newsroom.amount');
                    }
                }?>
            </ul>  
        </div>
    </div>
    <div class="ew-cart-dis-block full">
        <div id="applypromocode" <?php if(($discount==0)){?>style="display:none;"<?php } ?> class="ew-promocode full">
            <h2><span>Enter a Promo Code</span></h2>
        <?php echo $this->Form->create('Discount', array('type' => 'post',"default"=>"false","onsubmit"=>"applycoupon(event);"));
            echo $this->Form->input('cart_session_id',array('type'=>"hidden",'value'=>$cart_session_id,'id'=>"copn_crt_sessid")); 

            echo $this->Form->input('promo_code',array('placeholder'=>"Type Code Here",'label'=>false,"id"=>"promo_code",'value'=>$promo_code)); 

            $class=($discount==0)?"hide":"";
            echo $this->Html->link('Remove coupon', 'javascript:void(0)',['class'=>"removecoupon-link $class",'id' =>'removediscountbtn','onclick' =>'removecoupon(event)']); 
            echo $this->Form->submit('Apply');
            echo $this->Form->end(); ?>
        </div>
        <?php
            $show_coupon="display:none;";
            // if($total_amount>0){
            if($discount==0){
                $show_coupon="display:block;";
            } 
            echo $this->Html->link('Apply Coupon Code', 'javascript:void(0)',['class'=>"applycouponbx",'id' =>'applypromobox',"style"=>$show_coupon,'onclick' =>'showapplycouponbox(event)']);
            
            ?>
        
        <?php //if($subtotal>0){?>
        <div id="cart-subtotal-box" class="full ew-cart-row">
            <span class="float-left">Subtotal : </span>
            <span id="cartsubtotal" class="float-right text-right"><?php echo Configure::read('Site.currency').$subtotal;?></span>
        </div>
        <?php // } ?>
        <div id="disamount-box" class="full ew-cart-row" <?php if(($discount==0)){?>style="display:none;"<?php } ?>>
            <span class="float-left">Discount : </span>
            <span id="disamount" class="float-right text-right"><?php echo Configure::read('Site.currency').$discount;?></span>
        </div>
        <div id="carttax-box" class="full ew-cart-row" style="display: none;" >
            <span class="float-left">Tax : </span>
            <span id="carttax" class="float-right text-right"><?php echo Configure::read('Site.currency').$tax;?></span>     
        </div>
        <div class="full ew-cart-row">
            <span class="float-left">Total : </span>
            <span id="carttotalamout" class="float-right text-right"><?php echo Configure::read('Site.currency');
            echo $total_amount;?></span>
        </div>

       


    </div>
        
     <div class = "payment_method_div full" style = "margin-top: 10px;">
        <p>Payment Method:</p>    
        <div>
            <label for = "stripe-pm">
                <input type = "radio" name = "payment_method" value = "stripe"  id = "stripe-pm"> Credit Card
            </label>
        </div>
        <div>
            <label>
                <input type = "radio" name = "payment_method" value = "paypal" id = "paypal-pm" checked> Paypal
            </label>
        </div>
    </div>


    <?php
    $action = strtolower(trim($this->params['action'])); 
    if(!isset($user_id)&&$action=='index'&&$this->params['pass'][0]=='plans'){
        include_once 'signup_at_plan.ctp';
    }else{?>
    <div class="buy_now_section">
        <?php 
          $newsroomSlug=(isset($newsroom_slug)&&!empty($newsroom_slug))?$newsroom_slug:"";
          $checkouturl=($total_amount>0)? SITEURL.'users/payment/'.$newsroomSlug:"javascript:void(0)";?>
          <div class=""><a id="checkoutbtn" data-link = "<?php echo $checkouturl; ?>" <?php if($total_amount==0){echo "onclick='check_cart_amount($total_amount)'"; } ?> class="orange-btn" href="javascript:void(0)">Checkout</a></div>
    </div> 
     <?php } ?>  
 </div>

 <script>
    $(document).ready(function(){
        $(document).on('click', "#checkoutbtn", function(event){
            event.preventDefault();
            let payment_method = $("input[name='payment_method']:checked").val();
            if(payment_method == ""){
                alert("Please Select Payment Method");
            }else{
                let payUrl = "<?=  SITEURL.'users/payment/'.$newsroomSlug ?>" + "?payment_method=" + payment_method;
                window.location.href = payUrl;
            }
        });
    });
 </script>