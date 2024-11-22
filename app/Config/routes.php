<?php



/**

 * Routes configuration

 *

 * In this file, you set up routes to your controllers and their actions.

 * Routes are very important mechanism that allows you to freely connect

 * different URLs to chosen controllers and their actions (functions).

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

 * @since         CakePHP(tm) v 0.2.9

 * @license       http://www.opensource.org/licenses/mit-license.php MIT License

 */

/**

 * Here, we are connecting '/' (base path) to controller called 'Pages',

 * its action called 'display', and we pass a param to select the view file

 * to use (in this case, /app/View/Pages/home.ctp)...

 */ 

Router::connect( '/users/add-new-company', array('controller' => 'users',"action"=>'create_new_company'));

Router::connect( '/users/clipping-report', array('controller' => 'users',"action"=>'clipping_report'));

Router::connect( '/users/press-releases/invoices', array('controller' => 'users',"action"=>'purchased_plan'));

Router::connect( '/users/press-releases/*', array('controller' => 'users',"action"=>'press_release'));

Router::connect( '/users/add-press-release/*', array('controller' => 'users',"action"=>'submit_release')); 

Router::connect( '/users/become-subscriber', array('controller' => 'users',"action"=>'become_subscriber'));

Router::connect( '/users/take-over-publishing', array('controller' => 'users',"action"=>'take_over_publishing'));

Router::connect( '/users/contact-us', array('controller' => 'users',"action"=>'contact_us'));

Router::connect( '/users/add-email-list', array('controller' => 'users',"action"=>'add_email_list'));

Router::connect( '/users/email-lists', array('controller' => 'users',"action"=>'email_list'));

Router::connect( '/users/edit-list/:lid', array('controller' => 'users',"action"=>'edit_list'));

Router::connect( '/users/add-media-email/*', array('controller' => 'users',"action"=>'sendy_add_subscriber'));

Router::connect( '/users/client-media-list/*', array('controller' => 'users',"action"=>'sendy_subscribers'));

Router::connect( '/users/update-newsletter-details/*', array('controller' => 'users',"action"=>'update_newsletter_details'));

Router::connect( '/users/cancel-subscription/*', array('controller' => 'users',"action"=>'cancel_subscription'));

Router::connect( '/users/import-media-email-list/*', array('controller' => 'users',"action"=>'sendy_import_subscriber_csv'));

Router::connect( '/users/unsubscribe-newsletter/*', array('controller' => 'users',"action"=>'unsubscribe_newsletter'));



Router::connect( '/users/create-newsroom/*', array('controller' => 'users',"action"=>'create_newsroom'));

Router::connect( '/users/newsroom-preview/*', array('controller' => 'users',"action"=>'create_newsroom_preview'));

Router::connect( '/users/edit-newsroom/*', array('controller' => 'users',"action"=>'edit_newsroom'));

Router::connect( '/users/edited-newsroom-preview/*', array('controller' => 'users',"action"=>'edited_newsroom_preview'));

Router::connect( '/users/:action/*', array('controller' => 'users'));





Router::connect('/news/rss/headlines', array('controller' =>'Rss','action' =>'headlines',"ext"=>'xml'));

Router::connect( '/news/rss/*', array('controller' => 'Rss','action'=>'latest'));

Router::connect( '/latest', array('controller' => 'Rss','action'=>'latest'));

Router::connect( '/rss/:action/*', array('controller' => 'Rss')); 

Router::connect( '/latest-news/*', array('controller' => 'Releases','action'=>'index'));

Router::connect( '/search/*', array('controller' => 'Releases','action'=>'search'));

Router::connect( '/news-by-date/*', array('controller' => 'Releases','action'=>'newsbydate'));

Router::connect( '/category/*', array('controller' => 'Releases','action'=>'newsbycategory'));

Router::connect( '/company/*', array('controller' => 'Releases','action'=>'newsbycompany'));

Router::connect( '/msa/*', array('controller' => 'Releases','action'=>'newsbymsa'));

Router::connect( '/country/*', array('controller' => 'Releases','action'=>'newsbycountry'));



Router::connect( '/news-feeds-by-categories', array('controller' => 'Releases','action'=>'newsfeedbycategory'));

Router::connect( '/news-feeds-by-newsroom', array('controller' => 'Releases','action'=>'newsbycompany'));

Router::connect( '/news-feeds-by-msa', array('controller' => 'Releases','action'=>'newsbymsa'));

Router::connect( '/news-feeds-by-countries', array('controller' => 'Releases','action'=>'newsbycountry'));



Router::connect( '/release/*', array('controller' => 'Releases',"action"=>'release'));

Router::connect( '/releases/:action/*', array('controller' => 'Releases'));

// Router::connect( '/releases/*', array('controller' => 'Releases',"action"=>'index'));



Router::connect( '/users/newsroom_view/*', array('controller' => 'users',"action"=>"newsroom_view"));

Router::connect( '/users/:action/*', array('controller' => 'users'));





Router::connect( '/pressReleases/:action/*', array('controller' => 'pressReleases'));

Router::connect('/notfound', array('controller' => 'Pages', 'action' => 'notfound'));

Router::connect( '/ajax/:action/*', array('controller' => 'ajax'));

Router::connect('/newsroom/*', array('controller' => 'Pages', 'action' => 'newsroom'));



// Router::connect('/support', array('controller' => 'Users', 'action' => 'support')); 



Router::connect( '/success', array('controller' => 'Users','action'=>'ticket_success'));

Router::connect( '/crons/:action/*', array('controller' => 'crons','action'=>'get_pr'));







Router::connect( '/payments/:action/*', array('controller' =>'Paypals'));



Router::connect( '/thanks/*', array('controller' => 'Contacts','action'=>'thanks'));



Router::connect('/sitemap', array('controller' =>'Pages','action' =>'sitemap','ext' => 'xml'));

Router::connect('/latest-news-sitemap', array('controller' =>'Pages','action' =>'latest_news_sitemap','ext' => 'xml'));

Router::connect('/sitemaps/*', array('controller' =>'Pages','action' =>'sitemaps'));




// app/Config/routes.php
Router::connect('/stripe/checkout', ['controller' => 'Payments', 'action' => 'checkout']);
Router::connect('/stripe/processPayment', ['controller' => 'Payments', 'action' => 'processPayment']);
Router::connect('/stripe/success', ['controller' => 'Payments', 'action' => 'success']);
Router::connect('/stripe/cancel', ['controller' => 'Payments', 'action' => 'cancel']);
Router::connect('/stripe/failed', ['controller' => 'Payments', 'action' => 'failed']);




Router::connect('/*', array('controller' => 'Pages', 'action' => 'index'));









Router::parseExtensions('rss','xml');



/**

 * Load all plugin routes. See the CakePlugin documentation on

 * how to customize the loading of plugin routes.

 */

CakePlugin::routes();



/**

 * Load the CakePHP default routes. Only remove this if you do not want to use

 * the built-in default routes.

 */

require CAKE . 'Config' . DS . 'routes.php';

define("SITEURL", FULL_BASE_URL . router::url('/', false));

define("SITEADMIN", FULL_BASE_URL . '/admin/');

Configure::write('hostname',preg_replace("(^https?://)", "",rtrim(FULL_BASE_URL,"/")));

define("SENDYURL",FULL_BASE_URL . '/sendy/');