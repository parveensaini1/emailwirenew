<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * This is email configuration file.
 *
 * Use it to configure email transports of CakePHP.
 *
 * Email configuration class.
 * You can specify multiple configurations for production, development and testing.
 *
 * transport => The name of a supported transport; valid options are as follows:
 *  Mail - Send using PHP mail function
 *  Smtp - Send using SMTP
 *  Debug - Do not send the email, just return the result
 *
 * You can add custom transports (or override existing transports) by adding the
 * appropriate file to app/Network/Email. Transports should be named 'YourTransport.php',
 * where 'Your' is the name of the transport.
 *
 * from =>
 * The origin email. See CakeEmail::from() about the valid values
 * BOOkFvFLSpgYeAnl3Q89shFuYn6A+jysi2EoQ/F9X7la
 */
class EmailConfig {
 

	
//   public $default = array(
// 	'host' => 'ssl://smtp.gmail.com',
// 	'port' => 465,
// 	'username' => 'hiteshvermadoit@gmail.com',
// 	'password' => 'hntverma@123!',
// 	'transport' => 'Smtp', 
// );

// public $default = array(
// 	'transport' => 'Mail',
// 	'from' => 'smtp@emailwire.com',
// 	'charset' => 'utf-8',
// 	'headerCharset' => 'utf-8',
// );

	public $smtp = array(
	'transport' => 'Smtp',
		'from' => array('devsite@emailwire.com' => 'admin'),
		'headers'=>array('X-Mailer'=>'EmailWire'),
		'host' => 'localhost',
			'auth' => 'plain',
		'port' => 25,
		'timeout' => 30,
		'username' => 'smtp@devsite.emailwire.com',
        'password' => 'WPiO[Z@n,M23',
		'client' => null,
		'log' => false,
		"smtp_sasl_auth_enable " =>false,
		/*********************** changes starts here ************/
		'SMTPSecure' => 'tls',
		'tls' => false,
		'context'=>array('ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)), 
		/*********************** changes ends here ************/   
		'charset' => 'utf-8',
		'headerCharset' => 'utf-8',
	);


	public $default = array(
		'transport' => 'Smtp',
		'from' => array('devsite@emailwire.com' => 'admin'),
		'headers'=>array('X-Mailer'=>'EmailWire'),
		'host' => 'localhost',
			'auth' => 'plain',
		'port' => 25,
		'timeout' => 30,
		'username' => 'smtp@devsite.emailwire.com',
        'password' => 'WPiO[Z@n,M23',
		'client' => null,
		'log' => false,
		"smtp_sasl_auth_enable " =>false,
		/*********************** changes starts here ************/
		'SMTPSecure' => 'tls',
		'tls' => false,
		'context'=>array('ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)), 
		/*********************** changes ends here ************/   
		'charset' => 'utf-8',
		'headerCharset' => 'utf-8',
	);
		

	/*
	public $default = array(
		'host' => 'ssl://smtp.hostinger.com',
        'port' => 465,
        'username' => 'hitesh@thrdev.com',
        'password' => 'Hiteshdev@123!',
		'transport' => 'Smtp', 
		'timeout' => 120,
		'log' => true,  
		// 'tls' => true
	);*/

	// public $default = array(
	// 	'transport' => 'Mail',
	// 	'from' => 'you@localhost',
	// 	//'charset' => 'utf-8',
	// 	//'headerCharset' => 'utf-8',
	// );

	public $fast = array(
		'from' => 'you@localhost',
		'sender' => null,
		'to' => null,
		'cc' => null,
		'bcc' => null,
		'replyTo' => null,
		'readReceipt' => null,
		'returnPath' => null,
		'messageId' => true,
		'subject' => null,
		'message' => null,
		'headers' => null,
		'viewRender' => null,
		'template' => false,
		'layout' => false,
		'viewVars' => null,
		'attachments' => null,
		'emailFormat' => null,
		'transport' => 'Smtp',
		'host' => 'localhost',
		'port' => 25,
		'timeout' => 30,
		'username' => 'user',
		'password' => 'secret',
		'client' => null,
		'log' => true,
		//'charset' => 'utf-8',
		//'headerCharset' => 'utf-8',
	);

}
