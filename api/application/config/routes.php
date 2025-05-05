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
|	https://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = '';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* API */
$route['product'] = 'api/Product';
$route['product/(:any)'] = 'api/Product/$1';
$route['product/(:num)']['PUT'] = 'api/Product/$1';
$route['product/(:num)']['DELETE'] = 'api/Product/$1';
$route['register'] = 'api/User/register';
$route['parent_register'] = 'api/User/parent_register';
$route['login'] = 'api/User/login'; 
$route['social_login'] = 'api/User/social_login';  
$route['forgot_password'] = 'api/User/forgot_password'; 
$route['get_started'] = 'api/Get_started/get_started'; 
$route['courses'] = 'api/Course/index';
$route['course_details'] = 'api/Course/course_details';
$route['race'] = 'api/Course/race'; 
$route['change_password'] = 'api/Menu/change_password'; 
$route['contact_us'] = 'api/Menu/contact_us'; 
$route['parent_profile_update'] = 'api/Menu/parent_profile_update'; 
$route['child_profile_update'] = 'api/Menu/child_profile_update'; 
$route['dialect'] = 'api/Course/dialect'; 
$route['country'] = 'api/Course/country'; 
$route['country_search'] = 'api/Course/country_search';
$route['course_material'] = 'api/Course/course_material'; 
$route['event_display'] = 'api/Event/event_display'; 
$route['event_details'] = 'api/Event/event_details'; 
$route['religion'] = 'api/Course/religion'; 
$route['nric'] = 'api/Course/nric'; 
$route['single_ongoing_course_details'] = 'api/Course/single_ongoing_course_details'; 
$route['nationlity'] = 'api/Course/nationlity'; 
$route['city'] = 'api/Course/city'; 
$route['state'] = 'api/Course/state'; 
$route['ongoing_course'] = 'api/Course/ongoing_course'; 
$route['quiz'] = 'api/Course/quiz'; 
$route['course_apply_leave'] = 'api/Course/course_apply_leave'; 
$route['reschedule_course_display'] = 'api/Course/reschedule_course_display'; 
$route['reschedule_course'] = 'api/Course/reschedule_course'; 
$route['course_exercise'] = 'api/Course/course_exercise'; 
$route['single_exercise'] = 'api/Course/single_exercise'; 
$route['classes_display'] = 'api/Course/classes_display'; 
$route['child_homework'] = 'api/Course/child_homework'; 
$route['homework_display'] = 'api/Course/homework_display'; 
$route['quiz_result'] = 'api/Course/quiz_result'; 
$route['quiz_result_detail'] = 'api/Course/quiz_result_detail'; 
$route['child_quiz_result'] = 'api/Course/child_quiz_result'; 
$route['leaderboard_display'] = 'api/Course/leaderboard_display'; 
$route['subject'] = 'api/Course/subject'; 
$route['course_certificate'] = 'api/Course/course_certificate'; 
$route['child_quiz_answer'] = 'api/Course/child_quiz_answer'; 
$route['search_child'] = 'api/User/search_child';
$route['parent_child_relationship'] = 'api/Add_child/parent_child_relationship'; 
$route['notification'] = 'api/Add_child/notification'; 
$route['child_approval'] = 'api/Add_child/child_approval';
$route['profile_display'] = 'api/User/profile_display';
$route['child_image'] = 'api/User/child_image';
$route['slider'] = 'api/Home/slider'; 
$route['search_child_by_student_id'] = 'api/Add_child/search_child_by_student_id'; 
$route['home_screen'] = 'api/Home/home_screen_display'; 
$route['payment'] = 'api/Home/payment'; 
$route['search_course_with_keyword'] = 'api/Home/search_course_with_keyword'; 
$route['parent_checkin'] = 'api/Home/parent_checkin'; 
$route['single_payment_history'] = 'api/Home/single_payment_history'; 
$route['complain'] = 'api/Home/complain'; 
$route['remove_item'] = 'api/Home/remove_item'; 
$route['course_type'] = 'api/Home/course_type'; 
$route['search_chapter'] = 'api/Home/search_chapter'; 
$route['search_course'] = 'api/Home/search_course';
$route['bookmark'] = 'api/Home/bookmark'; 
$route['about_us'] = 'api/course/about_us'; 
$route['payment_history'] = 'api/Home/payment_history'; 
$route['add_bookmark'] = 'api/Home/add_bookmark'; 
$route['checkin_class_display'] = 'api/Home/checkin_class_display'; 
$route['child_display'] = 'api/Home/child_display'; 
$route['testTimeDifference'] = 'api/Home/testTimeDifference'; 
$route['checkin'] = 'api/Home/checkin'; 
$route['remove_bookmark'] = 'api/Home/remove_bookmark'; 
$route['my_cart_display'] = 'api/Home/my_cart_display'; 
$route['course_rating'] = 'api/Home/course_rating'; 
$route['course_rating_display'] = 'api/Home/course_rating_display';
$route['add_cart'] = 'api/Home/add_cart'; 
$route['billing_address_display'] = 'api/Home/billing_address_display'; 
$route['search_course_with_type'] = 'api/Home/search_course_with_type';
$route['billing_address'] = 'api/Home/billing_address'; 
$route['remove_cart'] = 'api/Home/remove_cart'; 
$route['news_display'] = 'api/Home/news_display'; 
$route['logout'] = 'api/User/logout';
$route['reGenToken'] = 'api/Token/reGenToken';

// Subscription.php
$route['user_display'] = 'api/User/user_display';
$route['parent_child_display'] = 'api/User/parent_child_display';


$route['profile_display'] = 'api/User/profile_display';

// Add_child.php
$route['callback'] = 'api/Add_child/callback';  
$route['course_suggest'] = 'api/Add_child/course_suggest'; 
$route['pending_child_request'] = 'api/Add_child/pending_child_request';
//$route['search_child_by_student_id'] = 'api/Add_child/search_child_by_student_id';
$route['search_nationality'] = 'api/User/search_nationality_display';
$route['search_news'] = 'api/Home/search_news'; 
$route['search_state'] = 'api/Course/search_state';
$route['search_city'] = 'api/Course/search_city';
$route['badges_display'] = 'api/Home/badges_display';
$route['reply_count'] = 'api/Tutorial/reply_count';
$route['tutorial'] = 'api/Tutorial/tutorial';
$route['tutorial_display'] = 'api/Tutorial/tutorial_display';
$route['check_tutorial_chat_subscription'] = 'api/Tutorial/check_tutorial_chat_subscription';
$route['tutorial_subscription_plan_display'] = 'api/Tutorial/tutorial_subscription_plan_display';
$route['manage_tutorial_subscription_display'] = 'api/Tutorial/manage_tutorial_subscription_display';
$route['tutorial_subscription_purchase'] = 'api/Tutorial/tutorial_subscription_purchase';
$route['auto_subscription'] = 'api/Tutorial/auto_subscription';
$route['auto_subscription_cron'] = 'api/Cron/auto_subscription';
$route['recurring_classes'] = 'api/Cron/recurring_classes';

$route['course_progress'] = 'api/Course/course_progress';
$route['attendance_summary'] = 'api/User/attendance_summary';
$route['report'] = 'api/User/report';
$route['progress_report'] = 'api/User/progress_report';
$route['parent_child'] = 'api/User/parent_child';

$route['tutorial_search'] = 'api/Tutorial/tutorial_search';
$route['event_purchase'] = 'api/Add_child/event_purchase';
$route['event_purchase_request'] = 'api/Add_child/event_purchase_request';
$route['payment_history_search'] = 'api/Home/payment_history_search';
$route['course_type_list'] = 'api/Course/course_type_list';
$route['course_gallery_display'] = 'api/Course/course_gallery_display';
$route['course_gallery_detail'] = 'api/Course/course_gallery_detail';
$route['getstudentId'] = 'api/User/getStudentId';
$route['is_already_purchased_event'] = 'api/Add_child/is_already_purchased_event';
$route['is_class_running'] = 'api/Course/is_class_running';

$route['isUserNameExist'] = 'api/User/isUserNameExist';
$route['isContactEmailAndNumberExist'] = 'api/User/isContactEmailAndNumberExist';
$route['student_slot'] = 'api/User/student_slot';
$route['reschedule_classes'] = 'api/Course/reschedule_classes';
$route['delete_user'] = 'api/User/delete_user';
$route['logout_user'] = 'api/User/logout_user';
$route['submit_otp'] = 'api/User/submit_otp';
$route['create_new_password'] = 'api/User/create_new_password';



// $route['create_new_password'] = 'api/User/create_new_password';