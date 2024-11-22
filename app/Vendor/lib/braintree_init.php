<?php 
require_once("braintree_autoload.php");
if(file_exists(__DIR__ . "/../.env")) {
    $dotenv = new Dotenv\Dotenv(__DIR__ . "/../");
    $dotenv->load();
}

// $gateway = new Braintree\Gateway([
//     'environment' =>'sandbox',
//     'merchantId' =>'p7bh24d6wkf2ym4p',
//     'publicKey' =>'6cchk42wg2f9ddwn',
//     'privateKey' =>'aad8fa5d34c954657f27f1bdb8ace6e4'
// ]);

$gateway = new Braintree\Gateway([
    'environment' =>'sandbox',
    'merchantId' =>'289z72hzb2px4py5',
    'publicKey' =>'wmx8g72y79wzdgv4',
    'privateKey' =>'decfcd4ed373ff5eabfa9deaaba9606d'
]);

$baseUrl = stripslashes(dirname($_SERVER['SCRIPT_NAME']));
$baseUrl = $baseUrl == '/' ? $baseUrl : $baseUrl . '/';
