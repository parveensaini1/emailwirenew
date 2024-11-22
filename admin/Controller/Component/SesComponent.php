<?php
//require_once('ses.php');
App::uses('AuthComponent', 'Controller/Component');
class SesComponent extends Component {

	function ses_sendMail(){ 
		require ROOT . DS.'vendors'.DS."aws".DS.'ses.php';
		$ses = new SimpleEmailService('AKIAWRDFK5AV3HRWDJHZ', 'TSJBiVC34flXRiqEN1b9WkeZx5w3QvdiNW92AUx+MuI=','email.us-east-1.amazonaws.com');
		$m = new SimpleEmailServiceMessage();
		$m->addTo('hitesh.verma0@gmail.com');
		$m->setFrom('John Doe <hitesh.verma0@gmail.com>');
		$m->setSubject('Amazon php SES  test with cakephp');
		$m->setSubjectCharset('UTF-8');
		$m->setMessageCharset('','UTF-8');


		$message = "
		<html>
		<head>
		<title>HTML email</title>
		</head>
		<body>
		<p>This email contains HTML Tags!</p>
		<table>
		<tr>
		<th>Firstname</th>
		<th>Lastname</th>
		<th>Testing</th>
		</tr>
		<tr>
		<td>John</td>
		<td>Doe</td>
		<td>Amazon</td>
		</tr>
		</table>
		</body>
		</html>
		";
		$m->setMessageFromString('',$message);
		pr($ses->sendEmail($m));
		die;
	}
	
}
?>