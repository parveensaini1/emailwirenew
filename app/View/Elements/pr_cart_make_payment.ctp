<?php   
$promo_code=''; 

$total_amount=(isset($cartdata['totals']['total'])&&$cartdata['totals']['total']>0)?$cartdata['totals']['total']:"0.00"; 
$subtotal=(isset($cartdata['totals']['subtotal'])&&$cartdata['totals']['subtotal']>0)?$cartdata['totals']['subtotal']:"0.00";
$discount=(isset($cartdata['totals']['discount'])&&$cartdata['totals']['discount']>0)?$cartdata['totals']['discount']:"0.00";
$tax=(isset($cartdata['totals']['tax'])&&$cartdata['totals']['tax']>0)?$cartdata['totals']['tax']:"0.00";
$submitBtnlabel=($subtotal>0)?"Submit PR without payment for approval":"Submit Press Release for approval"; 
?>
<!-- <div style="display: none;"id="buy-plan-error" class="text-danger col-lg-12 buy-plan-error-msg">Your cart is empty.</div> -->

<div id="cartsection" class="orange-border"  <?php if($subtotal<=0){?> style="display: none;" <?php } ?>  >
    <div class="ew-title-price full">
        <h2>Now in your cart</h2>
 
        <div class="full cart-items">
            <ul id="cartplanlist">
                <?php 
                    if(!empty($cartdata['prlist'])){

                        foreach ($cartdata['prlist'] as $key => $list) {

                         echo "<li id='plan-".$list['plan_id']."'><span class='float-left'>".$list['title']."</span><span class='float-right'>".$list['amount']."</span></li>";
                        }
                    }

                ?>

            </ul>  
            <ul id="cartfeaturelist">
                <?php 
                    if(!empty($cartdata['feature'])){
                        foreach ($cartdata['feature'] as $index => $feature) {
                         echo "<li id='plan-$index' class='charges' ><span class='".$feature['class']."'>".$feature['name']."</span><span class='float-right'>".$feature['price']."</span></li>";
                        }
                    }

                ?>

            </ul>
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
            <span id="cartsubtotal" class="float-right text-right"><?php echo "$".$subtotal;?></span>
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
            <span id="carttotalamout" class="float-right text-right"><?php echo '$'.$total_amount;?></span>
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
    
 
    <div class="buy_now_section">
        <div class="row">
            <div class="col-sm-12">
             <?php
              $checkouturl=($subtotal>0)? SITEURL."/stripe/checkout?selectedplan=$selectedplan&prId=$id":"javascript:void(0)";
             ?>
            <a href="javascript:void(0)" data-link="<?=$checkouturl?>" onclick='check_cart_amount(<?=$subtotal?>)'; id="checkoutbtn"  class="btn orange-btn">Checkout</a>
            </div>
          </div>
    </div>
 </div>



 <script>
    $(document).ready(function(){
        $(document).on('click', "#checkoutbtn", function(event){
            event.preventDefault();
            let payment_method = $("input[name='payment_method']:checked").val();
            if(payment_method=='stripe'){
                //let payUrl = "<?=$checkouturl?>";
                //window.location.href = payUrl;
                submitform('stripe');
            }else if(payment_method=='paypal'){
                submitform('paypal');
            }else{
                 alert("Please Select Payment Method");
            }
        });
    });
 </script>