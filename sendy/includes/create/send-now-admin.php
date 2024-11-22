<?php  include('../functions.php');?>
<?php  include('../helpers/PHPMailerAutoload.php');?>
<?php  include('../helpers/short.php');?> 
<?php
		

$campaign_id = mysqli_real_escape_string($mysqli, $_POST['campaign_id']);

$app = isset($_POST['app']) && is_numeric($_POST['app']) ? mysqli_real_escape_string($mysqli, (int)$_POST['app']) : exit;


$offset = isset($_POST['offset']) ? mysqli_real_escape_string($mysqli, $_POST['offset']) : '';
$cron = $_POST['cron'];
$email_list = mysqli_real_escape_string($mysqli, $_POST['email_list']);
$email_list_excl = mysqli_real_escape_string($mysqli, $_POST['email_list_exclude']);
$email_lists_segs = mysqli_real_escape_string($mysqli, $_POST['email_lists_segs']);
$email_lists_segs_excl = mysqli_real_escape_string($mysqli, $_POST['email_lists_segs_excl']);
$total_recipients = isset($_POST['total_recipients']) && is_numeric($_POST['total_recipients']) ? mysqli_real_escape_string($mysqli, $_POST['total_recipients']) : 0;
$mainUserId = mysqli_real_escape_string($mysqli, $_POST['mainUserId']);
$time = time();
$s3_secret=$s3_key="";
$q = 'SELECT name, username, send_rate, timezone,s3_secret,s3_key,ses_endpoint FROM login WHERE id = '.$mainUserId;
$r = mysqli_query($mysqli, $q);
if ($r){

    while($row = mysqli_fetch_array($r)){
		$my_name = $row['name'];
		$my_email = $row['username'];
		$send_rate = $row['send_rate'];
		$user_timezone = $row['timezone'];
		$s3_secret = $row['s3_secret'];
		$s3_key = $row['s3_key'];
		$ses_endpoint = $row['ses_endpoint']; 
    }  
}

//select campaign to send email
$q = 'SELECT from_name, from_email, reply_to, title, label, plain_text, html_text, query_string, to_send, recipients, opens_tracking, links_tracking,timezone FROM campaigns WHERE id = '.$campaign_id.' AND userID = '.$mainUserId;
$r = mysqli_query($mysqli, $q);
if ($r && mysqli_num_rows($r)>0){
    while($row = mysqli_fetch_array($r)){

	    $timezone = $row['timezone'];
    	$from_name = stripslashes($row['from_name']);
    	$from_email = stripslashes($row['from_email']);
    	$reply_to = stripslashes($row['reply_to']);
		$title = stripslashes($row['title']);
		$campaign_title = $row['label']=='' ? $title : stripslashes(htmlentities($row['label'],ENT_QUOTES,"UTF-8"));
		$plain_text = stripslashes($row['plain_text']);
		$html = stripslashes($row['html_text']);
		$query_string = stripslashes($row['query_string']);
		$to_send = stripslashes($row['to_send']);
		$current_recipient_count = $row['recipients'];
		$opens_tracking = $row['opens_tracking'];
		$links_tracking = $row['links_tracking'];
		$champ_timezone = $row['timezone'];
    }  
}
 
		$mail = new PHPMailer();
		if($s3_key!='' && $s3_secret!=''){
			$subscriber_id=1;
			if(file_exists('../../uploads/attachments/'.$campaign_id))
				$mail->IsAmazonSES(false, $campaign_id, $subscriber_id, $user_timezone);
			//otherwise send with curl_multi
			else
				var_dump($mail->IsAmazonSES(true, $campaign_id, $subscriber_id, $champ_timezone, $send_rate));

			var_dump($campaign_id);
			var_dump($subscriber_id);
			var_dump($champ_timezone);
			var_dump($send_rate);
			die;
			var_dump($mail->AddAmazonSESKey($s3_key, $s3_secret));
		}else if($smtp_host!='' && $smtp_port!='' && $smtp_username!='' && $smtp_password!=''){
			$mail->IsSMTP();
			$mail->SMTPDebug = 0;
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = $smtp_ssl;
			$mail->Host = $smtp_host;
			$mail->Port = $smtp_port; 
			$mail->Username = $smtp_username;  
			$mail->Password = $smtp_password;
		}
	 
		$mail->CharSet	  =	"UTF-8";
		$mail->From       = $from_email;
		$mail->FromName   = $from_name;
		$mail->Subject = $title_treated;
		$mail->AltBody = $plain_treated;
		$mail->Body = $html_treated;
		$mail->IsHTML(true);
		$mail->AddAddress($email, $name);
		$mail->AddReplyTo($reply_to, $from_name);
		$mail->AddCustomHeader('List-Unsubscribe: <'.APP_PATH.'/unsubscribe/'.short($email).'/'.short($subscriber_list).'/'.short($campaign_id).'>');
		//check if attachments are available for this campaign to attach
		if(file_exists('../../uploads/attachments/'.$campaign_id))
		{
			foreach(glob('../../uploads/attachments/'.$campaign_id.'/*') as $attachment){
				if(file_exists($attachment))
				    $mail->AddAttachment($attachment);
			}
		}
		echo "<pre>";
		print_r($mail->Send());
		die;
		

?>
