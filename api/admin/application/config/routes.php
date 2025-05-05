<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'login';
$route['mainmaster'] = 'login';
$route['logout'] = 'login/logout';
$route['reset-password'] = 'login/reset_password';  









$route['sub-category'] = 'subcategory/index';
$route['customer-partner-type/(:any)'] = 'partners/index/$1';  

//$route['category'] = 'category/index';
$route['login'] = 'mainmaster/index';
//$route['forget-password'] = 'mainmaster/forgetpassword';
$route['religion'] = 'mainmaster/index';
$route['payment'] = 'mainmaster/index';
$route['nric'] = 'mainmaster/index';
$route['age_group'] = 'mainmaster/index';
$route['country'] = 'mainmaster/index';
$route['class_location'] = 'mainmaster/index';
$route['nationlity'] = 'mainmaster/index';
$route['courses'] = 'mainmaster/index';
$route['city'] = 'mainmaster/index';
$route['state'] = 'mainmaster/index';
$route['race'] = 'mainmaster/index';
$route['dialect'] = 'mainmaster/index';
$route['subject'] = 'mainmaster/index';
$route['tutorials'] = 'mainmaster/index';
$route['slider'] = 'mainmaster/index';
$route['course_type'] = 'mainmaster/index';
$route['get_started'] = 'mainmaster/index';
$route['badges'] = 'mainmaster/index';
$route['billing_address'] = 'mainmaster/index';
$route['bookmark'] = 'mainmaster/index';
$route['card'] = 'mainmaster/index';
$route['chapter'] = 'mainmaster/index';
$route['check_in'] = 'mainmaster/index';
$route['child_homework'] = 'mainmaster/index';
$route['child_parent_relationship'] = 'mainmaster/index';
$route['child_quiz_result'] = 'mainmaster/index';
$route['ch_homework_doc'] = 'mainmaster/index';
$route['complain'] = 'mainmaster/index';
$route['contact_us'] = 'mainmaster/index';
$route['course_exercise'] = 'mainmaster/index';
$route['course_material'] = 'mainmaster/index';
$route['course_rating'] = 'mainmaster/index';
$route['course_subscription'] = 'mainmaster/index';
$route['events'] = 'mainmaster/index';
$route['get_started'] = 'mainmaster/index';
$route['homework'] = 'mainmaster/index';
$route['news'] = 'mainmaster/index';
$route['main_quiz'] = 'mainmaster/index';
$route['mycart'] = 'mainmaster/index';
$route['notification'] = 'mainmaster/index';
$route['ongoing_course'] = 'mainmaster/index';
$route['quiz'] = 'mainmaster/index';
$route['quiz_options'] = 'mainmaster/index';
$route['quiz_type'] = 'mainmaster/index';
$route['reschedule_classes'] = 'mainmaster/index';
$route['tr_homework_doc'] = 'mainmaster/index';
$route['upcoming_classes'] = 'mainmaster/index';
$route['child_quiz_answer'] = 'mainmaster/index';
$route['users'] = 'mainmaster/index';
$route['student_user'] = 'mainmaster/index';
$route['add_account'] = 'mainmaster/index';
$route['parent_user'] = 'mainmaster/index';
$route['add_course'] = 'mainmaster/index';
$route['upcoming_classes'] = 'mainmaster/index';
$route['event_transaction'] = 'mainmaster/index';
$route['user_tutorial_subscription'] = 'mainmaster/index';
$route['download_qr/(:num)'] = 'dashboard/download_qr_code/$1';




//$route['sub-category'] = 'subcategory/index';
$route['sub-category'] = 'mainmaster/index';
$route['form-build'] = 'mainmaster/index'; 

//$route['customer-partner/(:any)'] = 'mainmaster/index/$1';  
$route['customer'] = 'mainmaster/index';  
$route['partners'] = 'mainmaster/index';    


//$route['metals'] = 'metals/index';
$route['metals'] = 'mainmaster/index';  
$route['purity'] = 'mainmaster/index';
$route['mfinishes'] = 'mainmaster/index';
//$route['metal-finishes'] = 'metalfinishes/index';

$route['diam-master'] = 'mainmaster/index';
$route['diamonds-cut'] = 'mainmaster/index';  
$route['diamonds-shape'] = 'mainmaster/index';
$route['diamonds-color'] = 'mainmaster/index';
$route['diamonds-clarity'] = 'mainmaster/index';
$route['diamonds-pointers'] = 'mainmaster/index';
$route['diamonds-sieve-size'] = 'mainmaster/index';
$route['diamonds-unit'] = 'mainmaster/index';
$route['diamonds-mm'] = 'mainmaster/index';



$route['gemstone-type'] = 'mainmaster/index';
$route['gemstone-cut'] = 'mainmaster/index'; 
$route['gemstone-shape'] = 'mainmaster/index'; 
$route['gemstone-quality'] = 'mainmaster/index'; 
$route['gemstone-size'] = 'mainmaster/index'; 
$route['gemstone-origin'] = 'mainmaster/index'; 
$route['gemstone-unit'] = 'mainmaster/index';  
$route['diam-gemstone'] = 'mainmaster/index'; 


$route['diam-pearl'] = 'mainmaster/index';
$route['pearl-type'] = 'mainmaster/index';
$route['pearl-shape'] = 'mainmaster/index'; 
$route['pearl-color'] = 'mainmaster/index'; 
$route['pearl-size'] = 'mainmaster/index'; 
$route['pearl-unit'] = 'mainmaster/index'; 
$route['qc1'] = 'mainmaster/index'; 
$route['qc2'] = 'mainmaster/index'; 
$route['order-status'] = 'mainmaster/index'; 


$route['diam-dimensions'] = 'mainmaster/index';
$route['dimensions-unit'] = 'mainmaster/index';
$route['dimensions-country'] = 'mainmaster/index'; 


$route['currency'] = 'mainmaster/index';

$route['prices'] = 'mainmaster/index';
$route['collections'] = 'mainmaster/index';

$route['raw-material-issue-receive'] = 'mainmaster/index';

$route['add-new-style'] = 'style/index';
$route['orders'] = 'style/index';
$route['track-your-orders'] = 'track/index';  
$route['archive-orders'] = 'style/index';
$route['style_estimator'] = 'mainmaster/index';
$route['inventory'] = 'style/index';
$route['sold-inventory'] = 'style/index';
$route['in-repair'] = 'style/index';
$route['retired-inventory'] = 'style/index';
$route['user_management'] = 'mainmaster/index';

$route['tutorial_subscription_plan'] = 'mainmaster/index';

$route['course_gallery_folders'] = 'mainmaster/index';
$route['course_gallery'] = 'mainmaster/index';
$route['announcement'] = 'mainmaster/index';
$route['course_certificate'] = 'mainmaster/index';
$route['tutorial_credit_transactions'] = 'mainmaster/index';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
