<?php echo $message;?>
<table width="650" align="center" cellpadding="0" cellspacing="0"  style="background-color:#faf7f0;">
    <tbody>
        <tr><td style="font-size: 0; line-height: 0;" height="18">&nbsp;</td></tr>
        <tr align="center">
            <td align="center" style="font-size:13px; color:#000000;"><?php echo str_replace("##YEAR##",date('Y'),Configure::read('Site.Copyright'))?></td>
        </tr>
        <tr><td style="font-size: 0; line-height: 0;" height="22">&nbsp;</td></tr>
    </tbody>
</table>