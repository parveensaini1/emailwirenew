<table width="800" align="center" cellpadding="0" cellspacing="0"  style="background-color:#faf7f0;">
    <thead align="center">
        <tr>
            <td><h2>Email wire</h2></td>
        </tr>
    </thead>

    <tbody align="center">
        <tr>
            <td style="font-size: 0; line-height: 0;" height="40">&nbsp;</td>
        </tr>
        <tr>
          <td><h1 style="text-align:center; font-size:20px; color:#082833; font-weight:bold; margin:0; padding:0;">Dear <?php echo ucfirst($name); ?></h1></td>
        </tr>
        <!--<tr><td style="font-size:14px; color:#787878;">Your Order is completed Successfully and your order will be deliver to you shortly</td>
        </tr>-->
        <tr>
            <td style="font-size: 0; line-height: 0;" height="30">&nbsp;</td>
        </tr>

        <tr><td>
                <table width="100%" align="center" cellpadding="0" cellspacing="0"  style="background-color:#fff;padding: 38px;border:7px solid #d9d6d0;display:block;margin:auto; ">
                    <tbody align="center">
                      <tr>  <td style="font-size: 0; line-height: 0;" height="20">&nbsp;</td>
                        </tr> 
                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Transaction id</td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $data['Transaction']['tx_id'];?></td></tr> 

                            <?php if(isset($data["Transaction"]['newsroom_amount'])&&$data["Transaction"]['newsroom_amount']>0 ){?>
                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Newsroom Amount</td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$data["Transaction"]['newsroom_amount'];?></td></tr>
                        <?php } ?>
                        <?php 
                        if(!empty($data['TransactionPlan'])){
                            foreach ($data['TransactionPlan'] as $plan){?>
                                <!-- <td><?php echo $plan['plan_id']; ?></td> -->
                                <tr>
                                <td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;"><?php echo $plan['title']; ?></td>

                                <td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$plan['plan_amount'];?></td>
                                </tr>

                            <?php } 
                        } ?>

                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Subtotal</td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$data["Transaction"]['subtotal'];?></td></tr>

                            <?php if(!empty($data["Transaction"]['discount'])&&$data["Transaction"]['discount']!=0){?>
                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Discount </td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$data["Transaction"]['discount'];?></td>
                            </tr>
                            <?php } ?>    
                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Tax </td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$data["Transaction"]['tax'];?></td>
                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Total Amount</td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$data["Transaction"]['total'];?></td>
                            </tr>

                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Payment status</td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $data['Transaction']['status'];?></td></tr>

                            <tr><td style="font-size: 0; line-height: 0; border-bottom:1px solid #f2f2f2;" height="20"></td></tr>    
                        
                   </tbody>
                </table>
            </td></tr>   
    </tbody>
    </table>  
