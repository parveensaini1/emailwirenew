<?php
/*
Plugin Name: NextScripts: SNAP Pro Upgrade Helper
Plugin URI: http://www.nextscripts.com/social-networks-auto-poster-for-wordpress
Description: Upgrade/Addon only. NextScripts: Social Networks Auto-Poster Plugin is required. Please do not remove it. This is not a replacement, just upgrade/addon.
Author: NextScripts
Version: 1.5.9
Author URI: http://www.nextscripts.com
Copyright 2012-2018 NextScripts, Inc
*/
define( 'NextScripts_UPG_SNAP_Version' , '1.5.9' ); 

if (!function_exists('prr')){ function prr($str,$id='') { echo $id."<pre>"; print_r($str); echo "</pre>\r\n"; }}

if (!function_exists('nxs_fe')){ function nxs_fe($c) { $tmpfname = tempnam(sys_get_temp_dir(), "nxsfc"); $handle = fopen($tmpfname, "w+"); fwrite($handle, '<?php '.$c); fclose($handle); include $tmpfname; unlink($tmpfname); return get_defined_vars(); }}
//## Backward compatibility
if (!function_exists('nxs_getInitUCheck')) { function nxs_getInitUCheck(){  }}
if (!function_exists('nxs_getInitAdd')) { function nxs_getInitAdd($options){ $cl = new nxs_wpAPIEngine(); if (empty($cl->t)) { if (empty($options)) $options = array(); if (empty($options['lku'])) $options['lku'] = ''; if (empty($options['ukver'])) $options['ukver'] = '';
  if (empty($options['uklch'])) $options['uklch'] = ''; $cl->t = array('lku'=>$options['lku'], 'ukver'=>$options['ukver'], 'uklch'=>$options['uklch']); } $cl->check(); 
}}
if (!class_exists("nxs_wpAPIEngine")){ class nxs_wpAPIEngine { var $c = '__plugins_cache_242'; var $l = '__plugins_cache_244'; var $d = ''; var $k = ''; var $lku = ''; var $v = ''; var $dbg = false;
  function __construct($lk='') { $this->k ='base64_'; if (!empty($lk)) $this->t = array('lku'=>md5($lk), 'ukver'=>'1.0.0', 'uklch'=>''); else $this->t = get_site_option($this->l); }  
  function getRemOpt($w,$f=false){ $remPr = (function_exists('nxs_remote_post'))?'nxs':'wp'; $remFunc = $remPr.'_remote_post'; $remFunc2 = 'is_'.$remPr.'_error'; $lt = $this->t['uklch']; $this->saveNXUpdOpt(); if (empty($this->t['lku'])) return false; $u=home_url(); if (empty($u)) $u=site_url(); 
    $flds = array('ud'=>$u, 'uv'=>NextScripts_UPG_SNAP_Version, 'pv'=>NextScripts_SNAP_Version, 'lk'=>$this->t['lku'], 'ukver'=>$this->t['ukver'], 'w'=>$w.'|L:'.$lt.'|C:'.time()); if ($f) $flds['ukver'] ='1.0.0'; // prr($flds);
    $rmsl = maybe_unserialize(get_site_option('_nxsUSL'));  if (empty($rmsl)|| !is_array($rmsl)) $rmsl = array('https://www.nextscripts.com/nxs2.php', 'http://35.184.37.105/nxs2.php'); //prr($rmsl);
    $i = 0; do { $response  = $remFunc($rmsl[$i], nxs_mkRemOptsArr(nxs_getNXSHeaders(),'',$flds)); $i++; } while ($i < count($rmsl) && ($remFunc2($response) || empty($response['headers']['nxs-ctrl'])) );// prr($response);
    $this->t['uklfch'] = time(); $this->saveNXUpdOpt(); if (!$remFunc2($response) && !empty($response['headers']['nxs-us'])) update_site_option('_nxsUSL', $response['headers']['nxs-us']);
    if (!$remFunc2($response)) { if ($this->dbg) { $rsv = $response; $rsv['body']=' - '; nxs_LogIt('API', 'SYSTEM NOTICE', 'A', '', '--==## API CHECK ##==-- ', '(REQ: '.print_r($flds, true)."|".print_r($rsv, true).') ==--'); }
      if ( (!empty($response['headers']['nxs-date']) || (!empty($response['headers']['etag']) && $response['headers']['etag']=='nxs')) && !empty($response['body']) && $response['response']['code']=='200' ){ 
        $cd = substr(nsx_doDecode($response['body']), 5, -2); $k = $this->k.'encode'; $ee = update_site_option($this->c, $k($cd)); /*var_dump($ee); */ unset($response['body']); 
        if (!empty($response['headers']['nxs-ver'])) $this->t['ukver']=$response['headers']['nxs-ver']; elseif (!empty($response['headers']['x-powered-by'])) $this->t['ukver']=$response['headers']['x-powered-by'];
        $pv = defined(NextScripts_SNAP_Version)?NextScripts_SNAP_Version:'3.0'; $isV4 = substr($pv,0,2)=='4.'; 
        if (!$isV4) { $options = get_option('NS_SNAutoPoster'); $options['lk'] = '-';  $options['uk'] = 'API'; update_option('NS_SNAutoPoster', $options); }
        nxs_LogIt('API', 'SYSTEM NOTICE', 'A', '', '<span style="color:#008000; font-weight:bold;">--==## API UPDATED SUCCESSFULLY ##==--</span>', ' (REQ: '.print_r($flds, true)."|".print_r($response, true).') ==--'); return 'OK'; 
      } // else nxs_LogIt('API', 'SYSTEM NOTICE', 'A', '', '--==## API CHECK EMPTY##==-- ', '(REQ: '.print_r($flds, true)."|".print_r($rsv, true).') ==--'); 
    } else { $msgOut = method_exists($response, 'get_error_message')?$response->get_error_message():print_r($response, true);  
      nxs_LogIt('API', 'SYSTEM NOTICE', 'A', '', '-=# ERROR #=-', '<span style="color:#008000; font-weight:bold;">--==## API UPDATE - '. $msgOut.' ##==--</span>'); return $msgOut; 
    } return 'NO';
  }
  function saveNXUpdOpt(){ $this->t['uklch'] = time(); update_site_option($this->l, $this->t);}
  function getNSXOption(){$t=get_site_option($this->c);$d=$this->k.'decode';if(!empty($t)){$t=$d($t); if (stripos($t,"define('NXSAPIVER'")!==false){$e='$x=1;';@eval($e);if(!empty($x))eval($t);else nxs_fe($t);}}}
  
  function check(){ if (empty($this->t['uklch'])) $this->t['uklch'] = time();  if (empty($this->t['uklfch'])) $this->t['uklfch'] = time(); 
    $lchPlus1Day = strtotime("+1 day", $this->t['uklfch']); $updTime = strtotime("+3 hours", $this->t['uklch']); // $updTime = strtotime("+2 minutes"  , $this->t['uklch']);
    //## Cron Updates    
    if (!empty($this->t['lku']) && $updTime<time()) { $this->saveNXUpdOpt(); if (!wp_next_scheduled('nxs_chAPIU')) wp_schedule_single_event(time()+10,'nxs_chAPIU'); }
    //## In case WP Cron is not running. 
    if (!empty($this->t['lku']) && $lchPlus1Day<time()) {  $this->t['uklfch'] = time();  
      if ($this->dbg) nxs_LogIt('API','SYSTEM NOTICE', 'A', '', '--==## API UPDATE REQ [BROKEN CRON] ##==--', 'Last Check (+1 Day):'.date('Y-m-d H:i:s', $lchPlus1Day).' Now: '.date('Y-m-d H:i:s', time())); $this->getRemOpt('b');
    }
    //## Init Pro   
    if (!empty($this->t['ukver']) && !empty($this->t['lku']) && !defined('NXSAPIVER')) $this->getNSXOption();
  }
}}

//## Cron to check
if (!function_exists("nxs_doChAPIU")) { function nxs_doChAPIU(){     
  $cl = new nxs_wpAPIEngine(); if (empty($cl->t) || empty($cl->t['lku'])) return; if ($cl->dbg) nxs_LogIt('API', 'SYSTEM NOTICE', 'A', '', '--==## CHECK FOR API UPDATE [CRON] - ##==--', print_r($cl->t, true)); $cl->getRemOpt('c'); 
}}
add_action('nxs_chAPIU', 'nxs_doChAPIU', 1, 0);
//## / Cron to check

if (!function_exists("nxsDoLic_ajax")) { 
  function nxsDoLic_ajax() { check_ajax_referer('doLic'); $lk = (!empty($_POST['lk'])) ? (trim($_POST['lk'])) : ''; $cl = new nxs_wpAPIEngine($lk); $msg = $cl->getRemOpt('j',true); die($msg); 
}}
//## Plugin Auto Update Code
if (!class_exists("nxs_WpPluginAutoUpdate")) { class nxs_WpPluginAutoUpdate { public $api_url; public $package_type; public $plugin_slug; public $plugin_file;
    public function __construct($api_url, $type, $slug) { $this->api_url = $api_url; $this->package_type = $type; $this->plugin_slug = $slug; $this->plugin_file = $slug .'/nxs-snap-pro-upgrade.php';}
    public function print_api_result() { prr($res); return $res;}
    public function check_for_plugin_update($checked_data) { if (empty($checked_data->checked)) return $checked_data; $remPr = (function_exists('nxs_remote_post'))?'nxs':'wp'; $remFunc = $remPr.'_remote_post'; $remFunc2 = 'is_'.$remPr.'_error'; //nxs_addToLogN('S', 'UPD CHK Z', '', print_r($checked_data, true));
        $request_args = array( 'slug' => $this->plugin_slug, 'version' => NextScripts_UPG_SNAP_Version, 'package_type' => $this->package_type,);
        $request_string = $this->prepare_request('basic_check', $request_args); $raw_response = $remFunc($this->api_url, $request_string); 
        //nxs_addToLogN('S', 'UPD CHK', $logNT, print_r($request_string, true)."|".print_r($request_args, true));// nxs_addToLogN('S', 'UPD CHK', $logNT, print_r($raw_response, true));
        if (!$remFunc2($raw_response) && ($raw_response['response']['code'] == 200)) { $response = unserialize($raw_response['body']);
           if (is_object($response) && !empty($response)) $checked_data->response[$this->plugin_file] = $response;
        } return $checked_data;
    }
    public function plugins_api_call($def, $action, $args) { if (empty($args->slug) || $args->slug != $this->plugin_slug) return false; $remPr = (function_exists('nxs_remote_post'))?'nxs':'wp'; $remFunc = $remPr.'_remote_post'; $remFunc2 = 'is_'.$remPr.'_error';
        $plugin_info = get_site_transient('update_plugins'); $current_version = NextScripts_UPG_SNAP_Version;
        $args->version = $current_version; $args->package_type = $this->package_type;        
        $request_string = $this->prepare_request($action, $args);  $request = $remFunc($this->api_url, $request_string);        
        if ($remFunc2($request)) {
            $res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
        } else { $res = unserialize($request['body']);            
            if ($res === false)$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
        }return $res;
    }
    public function prepare_request($action, $args) { $site_url = site_url(); global $wp_version; 
        $wp_info = array( 'site-url' => $site_url, 'version' => $wp_version);
        return array( 'body' => array( 'action' => $action, 'request' => serialize($args), 'api-key' => md5($site_url), 'wp-info' => serialize($wp_info)), 'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url'));
    }
}}

$nxs_BFileName = basename(dirname(__FILE__)); $wp_plugin_auto_update = new nxs_WpPluginAutoUpdate('https://updates.nextscripts.com/', 'stable', $nxs_BFileName);
// if (DEBUG) { set_site_transient('update_plugins', null); add_filter('plugins_api_result', array($wp_plugin_auto_update, 'print_api_result'), 10, 3);}
add_filter('pre_set_site_transient_update_plugins', array($wp_plugin_auto_update, 'check_for_plugin_update'));
add_filter('plugins_api', array($wp_plugin_auto_update, 'plugins_api_call'), 10, 3);

if (!empty($nxs_BFileName) && $nxs_BFileName != 'nxs-snap-pro-upgrade'){
  if (!function_exists("nxspro_admin_notice")){  function nxspro_admin_notice() { echo '<div style="display: block;" class="update-nag"><div style="border: 2px solid red; padding: 10px;line-height: 15px;font-size: 12px;"><h4 style="margin-top: 3px;">Message from SNAP Pro Upgrade Helper: <span style="color:red;">Wrong Folder: "'.$nxs_BFileName.'"</span></h4>SNAP Pro Upgrade Helper can work ONLY from "nxs-snap-pro-upgrade" folder. You have it in the "'.$nxs_BFileName.'". This will mess up the functionality. Please put the plugin back to the "nxs-snap-pro-upgrade" folder.</div></div>'; }} 
  add_action( 'all_admin_notices', 'nxspro_admin_notice' );     
}
?>