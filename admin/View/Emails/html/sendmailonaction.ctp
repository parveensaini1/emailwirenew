<table width="800" align="center" cellpadding="0" cellspacing="0"  style="background-color:#faf7f0;">

    <tbody align="center">
        <tr>
            <td style="font-size: 0; line-height: 0;" height="40">&nbsp;</td>
        </tr>
        <tr>
            <td><img src="https://netleontech.com/email_wire/website/img/emailwire-logo.jpg"></td>
        </tr>
        <tr>
            <td style="font-size: 0; line-height: 0;" height="30">&nbsp;</td>
        </tr>

        <tr><td>
                <table width="630" align="center" cellpadding="0" cellspacing="0"  style="background-color:#fff;padding: 38px;border:7px solid #d9d6d0;display:block;margin:auto; ">
                    <tbody align="center">
                        <tr>
                            <td style="text-align:center; font-size:20px; color:#082833; font-weight:bold; display:block;">Dear <?php echo $name;?></td>
                        </tr>
                        <tr>
                            <td style="text-align:center; font-size: 0; line-height: 0;" height="30">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="text-align:center; font-size:14px; color:#787878;">
                                <p><?php echo $premessage;?></p>
                                <?php echo $message;?></td>
                        </tr>
                        
                        </tr>
                        <tr align="center">
                            <td style="font-size: 0; line-height: 0;" height="18">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="text-align:center; color:#082833; font-size:16px; font-weight:bold;">Team <?php echo $site_name; ?></td>
                        </tr>
                    </tbody>
                </table>


            </td></tr>   
    </tbody></table>  

<table width="800" align="center" cellpadding="0" cellspacing="0"  style="background-color:#faf7f0;">
    <tbody>
     
        <tr>
            <td style="font-size: 0; line-height: 0;" height="18">&nbsp;</td>
        </tr>
        <tr align="center">
            <td align="center" style="font-size:13px; color:#000000;"><?php echo str_replace("##YEAR##",date('Y'),Configure::read('Site.Copyright'))?></td>
        </tr>
        <tr>
            <td style="font-size: 0; line-height: 0;" height="22">&nbsp;</td>
        </tr>
    </tbody>
</table>