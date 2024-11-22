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
        <tr>
            <td style="font-size: 0; line-height: 0;" height="30">&nbsp;</td>
        </tr>

        <tr><td>
                <table width="100%" align="center" cellpadding="0" cellspacing="0"  style="background-color:#fff;padding: 38px;border:7px solid #d9d6d0;display:block;margin:auto; ">
                    <tbody align="center">
                      <tr>
                       <td style="font-size: 0; line-height: 0;" height="20">&nbsp;</td>
                        </tr> 
                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Transaction id</td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $data['TransactionPressRelease']['tx_id'];?></td></tr> 

                        <?php if(isset($data['TransactionPressRelease']["word_amount"])&&$data['TransactionPressRelease']["word_amount"]>0 ){?>
                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Extra word charges</td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$data['TransactionPressRelease']["word_amount"];?></td></tr>
                        <?php } ?>

                         <?php if(isset($data['TransactionPressRelease']["category_amount"])&&$data['TransactionPressRelease']["category_amount"]>0 ){?>
                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Extra category charges</td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$data['TransactionPressRelease']["category_amount"];?></td></tr>
                        <?php } ?>

                        <?php if(isset($data['TransactionPressRelease']["state_amount"])&&$data['TransactionPressRelease']["state_amount"]>0 ){?>
                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Extra state charges</td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$data['TransactionPressRelease']["state_amount"];?></td></tr>
                        <?php } ?>

                        <?php if(isset($data['TransactionPressRelease']["msa_amount"])&&$data['TransactionPressRelease']["msa_amount"]>0 ){?>
                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Extra MSA charges</td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$data['TransactionPressRelease']["msa_amount"];?></td></tr>
                        <?php } ?>

                         <?php if(isset($data['TransactionPressRelease']["translate_charges"])&&$data['TransactionPressRelease']["translate_charges"]>0 ){?>
                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Extra translate charges</td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$data['TransactionPressRelease']["translate_charges"];?></td></tr>
                        <?php } ?>

                        <?php 
                        if(!empty($data['TransactionPressRelease']['distribution_ids'])){
                            $features=unserialize($data['TransactionPressRelease']['distribution_ids']);
                            if(count($features)>0){
                            foreach ($features as $feature){?>
                                <!-- <td><?php echo $plan['plan_id']; ?></td> -->
                                <tr>
                                <td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;"><?php echo $feature['name']; ?></td>

                                <td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$feature['price'];?></td>
                                </tr>

                            <?php } } 
                        } ?>

                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Subtotal</td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$data["TransactionPressRelease"]['subtotal'];?></td></tr>

                            <?php if(!empty($data["TransactionPressRelease"]['discount'])&&$data["TransactionPressRelease"]['discount']!=0){?>
                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Discount </td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$data["TransactionPressRelease"]['discount'];?></td>
                            </tr>
                            <?php } ?>    
                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Tax </td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$data["TransactionPressRelease"]['tax'];?></td>
                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Total Amount</td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $currency.' '.$data["TransactionPressRelease"]['total'];?></td>
                            </tr>

                            <tr><td style="font-size:14px; color:#082833; border-bottom:1px solid #f2f2f2; border-right:1px solid #f2f2f2; font-weight:bold; padding: 15px; width: 50%;">Payment status</td><td style="font-size:14px; color:#000000; border-bottom:1px solid #f2f2f2; font-weight:400; padding: 15px; width: 50%;"><?php echo $data['TransactionPressRelease']['status'];?></td></tr>

                            <tr><td style="font-size: 0; line-height: 0; border-bottom:1px solid #f2f2f2;" height="20"></td></tr>    
                        
                   </tbody>
                </table>
            </td></tr>   
    </tbody>
</table>  