<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 |--------------------------------------------------------------------------
 | File and Directory Modes
 |--------------------------------------------------------------------------
 |
 | These prefs are used when checking and setting modes when working
 | with the file system.  The defaults are fine on servers with proper
 | security, but you may wish (or even need) to change the values in
 | certain environments (Apache running a separate process for each
 | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
 | always be used to set the mode correctly.
 |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);
/*
 |--------------------------------------------------------------------------
 | File Stream Modes
 |--------------------------------------------------------------------------
 |
 | These modes are used when working with fopen()/popen()
 |
 */
define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');
/*
 |--------------------------------------------------------------------------
 | Constant Used In Application
 |--------------------------------------------------------------------------
 |--------------------------------------------------------------------------
 | Active Module Name
 |--------------------------------------------------------------------------
 |
 | This is the current module which is active
 |
 */
define('PROJECT_PREFIX', 'TM');
define('CURRENT_DOMAIN_KEY', 'TMX1512291534825461'); //FIXME :: Auto Set
define('PROJECT_COOKIE_NAME', 'supervision');

define('LOGIN_POINTER', 'LID');
define('AUTH_USER_POINTER', 'AID');
define('DEFAULT_TEMPLATE', 'template_v1');
define('DOMAIN_AUTH_ID', 'domain_auth_id');
define('DOMAIN_KEY', 'domain_key');

/*
 |--------------------------------------------------------------------------
 | Folder Names
 |--------------------------------------------------------------------------
 |--------------------------------------------------------------------------
 | Application Folder Names
 |--------------------------------------------------------------------------
 */
$project_parent_folder = 'vivance_travels';

if (empty($project_parent_folder) == false) {
	define('APP_ROOT_DIR', '/'.$project_parent_folder); //main folder which wraps complete application
	define('PROJECT_COOKIE_PATH', '/'.$project_parent_folder.'/supervision/');
} else {
	define('APP_ROOT_DIR', ''); //main folder which wraps complete application
	define('PROJECT_COOKIE_PATH', '/supervision/');
}
define('PROJECT_COOKIE_TIME', '86400');
define('MODULE_ROOT_DIR', '/supervision'); //main folder which holds current module
define('PROJECT_URI', APP_ROOT_DIR.MODULE_ROOT_DIR);
define('PROJECT_NAME','vivance_travels');

/*
 |--------------------------------------------------------------------------
 | RESOURCE FOLDERS
 |--------------------------------------------------------------------------
 */
define('RESOURCE_DIR', APP_ROOT_DIR.'/extras'); //main folder which holds all the resource
define('SYSTEM_RESOURCE_DIR', RESOURCE_DIR.'/system');
define('CUSTOM_RESOURCE_DIR', RESOURCE_DIR.'/custom');
define('SYSTEM_RESOURCE_LIBRARY', SYSTEM_RESOURCE_DIR.'/library'); //complete application library storage
define('SYSTEM_TEMPLATE_LIST', SYSTEM_RESOURCE_DIR.'/template_list');//complete application template storage
define('SYSTEM_TEMPLATE_LIST_RELATIVE_PATH', realpath('../extras').'/system/template_list');
/*if (empty($project_parent_folder) == false) {
	define('SYSTEM_TEMPLATE_LIST_RELATIVE_PATH', '../..'.SYSTEM_TEMPLATE_LIST);
} else {
	define('SYSTEM_TEMPLATE_LIST_RELATIVE_PATH', realpath('../extras').'/system/template_list');
}*/

/*
 |--------------------------------------------------------------------------
 | EXTRAS LIBRARY FOLDERS
 |--------------------------------------------------------------------------
 */
define('BOOTSTRAP_JS_DIR', SYSTEM_RESOURCE_LIBRARY.'/bootstrap/js/');
define('BOOTSTRAP_CSS_DIR', SYSTEM_RESOURCE_LIBRARY.'/bootstrap/css/');
define('SYSTEM_IMAGE_DIR', SYSTEM_RESOURCE_LIBRARY.'/images/');
define('GRAPH_SCRIPT', SYSTEM_RESOURCE_LIBRARY.'/Highcharts/js/');
define('JAVASCRIPT_LIBRARY_DIR', SYSTEM_RESOURCE_LIBRARY.'/javascript/');
define('JQUERY_UI_LIBRARY_DIR', JAVASCRIPT_LIBRARY_DIR.'jquery-ui-1.11.2.custom/');
define('DATEPICKER_LIBRARY_DIR', SYSTEM_RESOURCE_LIBRARY.'/datetimepicker/');

/*
 |--------------------------------------------------------------------------
 | THEME TEMPLATE LIBRARY FOLDERS
 |--------------------------------------------------------------------------
 */
define('TEMPLATE_CSS_DIR', '/css/');
define('TEMPLATE_AUDIO_DIR', '/audio/');
define('TEMPLATE_JS_DIR', '/javascript/');
define('TEMPLATE_IMAGE_DIR', '/images/');

/*
 |--------------------------------------------------------------------------
 | DOMAIN SPECIFIC CONSTANTS
 |--------------------------------------------------------------------------
 */
define('DOMAIN_IMAGE_DIR', CUSTOM_RESOURCE_DIR.'/'.CURRENT_DOMAIN_KEY.'/images/');
define('DOMAIN_IMAGE_UPLOAD_DIR', realpath('../extras').'/custom/'.CURRENT_DOMAIN_KEY.'/images/');
define('DOMAIN_UPLOAD_DIR', CUSTOM_RESOURCE_DIR.'/'.CURRENT_DOMAIN_KEY.'/uploads/');
define('DOMAIN_PCKG_UPLOAD_DIR', CUSTOM_RESOURCE_DIR.'/'.CURRENT_DOMAIN_KEY.'/uploads/packages/');
//define('DOMAIN_PDF_DIR', CUSTOM_RESOURCE_DIR.'/'.CURRENT_DOMAIN_KEY.'/temp_booking_data_pdf/');
define ('DOMAIN_PDF_DIR', '../extras/custom/'.CURRENT_DOMAIN_KEY.'/temp_booking_data_pdf/',true);
define('DOMAIN_BAN_UPLOAD_DIR', realpath('../extras').'/system/template_list/template_v1/images/');
define('DOMAIN_BAN_IMAGE_DIR', SYSTEM_RESOURCE_DIR.'/template_list/template_v1/images/');
define('DOMAIN_PROMO_UPLOAD_DIR', realpath('../extras').'/system/template_list/template_v1/images/promocode');
define('DOMAIN_TOP_AIRLINE_UPLOAD_DIR', realpath('../extras').'/system/template_list/template_v3/images/top_airlines');
define('DOMAIN_TOP_AIRLINE_IMAGE_DIR', SYSTEM_RESOURCE_DIR.'/template_list/template_v3/images/top_airlines/');
define('DOMAIN_TOUR_STYLE_UPLOAD_DIR', realpath('../extras').'/system/template_list/template_v3/images/tourstyles');
define('DOMAIN_TOUR_STYLE_IMAGE_DIR', SYSTEM_RESOURCE_DIR.'/template_list/template_v3/images/tourstyles/');
define('DOMAIN_PROMO_IMAGE_DIR', SYSTEM_RESOURCE_DIR.'/template_list/template_v1/images/promocode/');
define('DOMAIN_BLOG_UPLOAD_DIR', realpath('../extras').'/custom/'.CURRENT_DOMAIN_KEY.'/images/blog');
//define('DOMAIN_BLOG_IMAGE_DIR', realpath('../extras').'/custom/'.CURRENT_DOMAIN_KEY.'/images/blog/');
//define('DOMAIN_BLOG_IMAGE_DIR', SYSTEM_RESOURCE_DIR.'/custom/'.CURRENT_DOMAIN_KEY.'/images/blog/');
define('DOMAIN_BLOG_IMAGE_DIR', CUSTOM_RESOURCE_DIR.'/'.CURRENT_DOMAIN_KEY.'/images/blog/');
//define('DOMAIN_BLOG_IMAGE_DIR', '../extras/custom/'.CURRENT_DOMAIN_KEY.'/images/blog/');

/*
 |--------------------------------------------------------------------------
 | PAGE CONFIGURATION
 |--------------------------------------------------------------------------
 */
define('CORE_PAGE_CONFIGURATIONS', '../'.MODULE_ROOT_DIR.'/application/views/page_configuration/');
define('COMMON_JS', '../'.MODULE_ROOT_DIR.'/application/views/page_configuration/js/common.php');
define('DATEPICKER_JS', '../'.MODULE_ROOT_DIR.'/application/views/page_configuration/js/datepicker.php');
define('COMMON_UI_JS', '../'.MODULE_ROOT_DIR.'/application/views/page_configuration/js/common_ui_js.php');
define('COMMON_SHARED_CSS_RESOURCE', '../'.MODULE_ROOT_DIR.'/application/views/page_configuration/js/header_css_resource.php');
define('COMMON_SHARED_JS_RESOURCE', '../'.MODULE_ROOT_DIR.'/application/views/page_configuration/js/header_js_resource.php');
define('COMMON_SHARED_FOOTER_JS_RESOURCE', 'b2c/views/page_configuration/resources/footer_js_resource.php');
define('ENUM_DATA_DIR', '../'.MODULE_ROOT_DIR.'/application/custom/enumeration/');
define('DATATYPE_DIR', '../'.MODULE_ROOT_DIR.'/application/custom/data_type/');
define('COMMON_SHARED_JS', '../'.MODULE_ROOT_DIR.'/application/views/page_configuration/shared_js/');
define('DOMAIN_CONFIG', '../'.MODULE_ROOT_DIR.'/application/custom/domain_config/');

/*
 |--------------------------------------------------------------------------
 | IMAGE SIZE
 |--------------------------------------------------------------------------
 */
define('PANEL_WRAPPER', 'panel-primary');

/*
 |--------------------------------------------------------------------------
 | IMAGE SIZE
 |--------------------------------------------------------------------------
 */
define('THUMBNAIL', 1);
/*
 |--------------------------------------------------------------------------
 | SMS/EMAIL Cofiguration
 |--------------------------------------------------------------------------
 */
define('GENERAL_SMS', 1);
define('GENERAL_EMAIL', 1);
/*
 |--------------------------------------------------------------------------
 | Status codes used in application
 |--------------------------------------------------------------------------
 */
define('INACTIVE', 0);
define('FAILURE_STATUS', 0);
define('QUERY_FAILURE', 0);
define('ACTIVE', 1);
define('SUCCESS_STATUS', 1);
define('QUERY_SUCCESS', 1);
define('PENDING', 1);
define('ACCEPTED', 2);
define('DECLINED', 3);
define('SUCCESS_MESSAGE', 0);
define('ERROR_MESSAGE', 1);
define('WARNING_MESSAGE', 2);
define('INFO_MESSAGE', 3);

define('BOOKING_CONFIRMED', 1);//Booking completed
define('BOOKING_HOLD', 2);//Booking on hold
define('BOOKING_CANCELLED', 3);//Booked and cancelled
define('BOOKING_ERROR', 4);//unable to continue booking
define('BOOKING_INCOMPLETE', 5);//left in between
define('BOOKING_VOUCHERED', 6);//
define('BOOKING_PENDING', 7);//left in between
define('BOOKING_FAILED', 8);//left in between
define('BOOKING_INPROGRESS', 9);//Booking is processing


/*
 |--------------------------------------------------------------------------
 | Type Of Markup Supported in application
 |--------------------------------------------------------------------------
 */
define('GENERIC', 'generic');
define('SPECIFIC', 'specific');
define('MARKUP_VALUE_PERCENTAGE', 'percentage');
define('MARKUP_VALUE_MONEY', 'plus');
define('B2C_FLIGHT', 'b2c_hotel');
define('B2C_HOTEL', 'b2c_flight');
define('B2C_CAR', 'b2c_bus');
define('MARKUP_CURRENCY', 'INR');

/*
 |--------------------------------------------------------------------------
 | Currency
 |--------------------------------------------------------------------------
 */
define('UNIVERSAL_DEFAULT_CURRENCY', 'INR'); // USD
define('COURSE_LIST_DEFAULT_CURRENCY', 22); // INR
define('COURSE_LIST_DEFAULT_CURRENCY_VALUE', 'INR');
define('COURSE_LIST_DEFAULT_CURRENCY_SYMBOL', '&#8377;');

/*
 |--------------------------------------------------------------------------
 | Application USER LIST
 |--------------------------------------------------------------------------
 */
define('AUTO_SYSTEM', 0);
define('ADMIN', 1);
define('SUB_ADMIN', 2);
define('B2B_USER', 3);
define('B2C_USER', 4);
define('B2E_USER', 5);
define('CALL_CENTER_USER', 6);
define('CORPORATE_USER', 7);

/*
 |--------------------------------------------------------------------------
 | Application PAGINATION
 |--------------------------------------------------------------------------
 */
define('RECORDS_RANGE_1', 10);
define('RECORDS_RANGE_2', 20);
define('RECORDS_RANGE_3', 50);


/*
 |--------------------------------------------------------------------------
 | Application Booking Engine Data Source
 |--------------------------------------------------------------------------
 */
define('FLIGHT_CRS', 0);
define('HOTEL_CRS', 1);
define('TRANSFER_CRS', 2);
define('HOLIDAY_CRS', 0);
define('SIGHTSEEING_CRS', 0);
define('RECHARGE_CRS', 0);
define('ACTIVITY_CRS', 50);
define('DMC_CRS', 8);
define('OTHER_BOOKING_SOURCE', 6);

define('FLIGHT_API',00);
define('HOTEL_API', 'ESHB');
define('HOLIDAY_API', 00);
define('TRANSFER_API', 00);
define('SIGHTSEEING_API', 00);
define('RECHARGE_API', 00);

/*
 |--------------------------------------------------------------------------
 | DATE TYPES
 |--------------------------------------------------------------------------
 */
define('PAST_DATE', '0');
define('FUTURE_DATE', '1');
define('PAST_DATE_TIME', '2');
define('FUTURE_DATE_TIME', '3');
define('ENABLE_MONTH', '4');
define('ADULT_DATE_PICKER', '5');
define('CHILD_DATE_PICKER', '6');
define('INFANT_DATE_PICKER', '7');
define('FUTURE_DATE_DISABLED_MONTH', '8');
define('FUTURE_DATE_SINGLE_MONTH', '9');

/*
 |--------------------------------------------------------------------------
 | Location TYPES
 |--------------------------------------------------------------------------
 */
define('CONTINENT_ZLOCATION', 'continent');
define('COUNTRY_ZLOCATION', 'country');
define('CITY_ZLOCATION', 'city');
define('EVENT_TEMPLATE', 'event');
define('GENERAL_TEMPLATE', 'general');

/*
 |--------------------------------------------------------------------------
 | User Title TYPES
 |--------------------------------------------------------------------------
 */
define('MR_TITLE', 1);
define('MRS_TITLE', 2);
define('MISS_TITLE', 3);
define('MASTER_TITLE', 4);

/*
 |--------------------------------------------------------------------------
 Country AND City Code
 |--------------------------------------------------------------------------
 */
define('INDIA_CODE', 92);
define('INDIA_COUNTRY_CODE', +91);
define('INDIA','INDIA');

/*
 |--------------------------------------------------------------------------
 Meta Course Type
 |--------------------------------------------------------------------------
 */
define('META_AIRLINE_COURSE', 'VHCID1420613784');
define('META_TRANSFERS_COURSE', 'VHCID1420613763');
define('META_ACCOMODATION_COURSE', 'VHCID1420613748');
define('META_BUS_COURSE', 'VHCID1433498307');
define('META_PACKAGE_COURSE', 'VHCID1433498322');
define('META_CAR_COURSE','TMCAR1433491849');
define('META_SIGHTSEEING_COURSE','TMCID1524458882');
define('META_TRANSFERV1_COURSE','TMVIATID1527240212');
/*
 |--------------------------------------------------------------------------
 Booking source of Course Type
 |--------------------------------------------------------------------------
 */
define('PROVAB_HOTEL_BOOKING_SOURCE', 'PTBSID0000000001');
define('PROVAB_FLIGHT_BOOKING_SOURCE', 'PTBSID0000000002');
define('PROVAB_BUS_BOOKING_SOURCE', 'PTBSID0000000003');

define('PROVAB_SIGHTSEEN_BOOKING_SOURCE', 'PTBSID0000000006');
define('PROVAB_CAR_BOOKING_SOURCE', 'PTBSID0000000007');
define('PROVAB_TRANSFERV1_BOOKING_SOURCE','PTBSID0000000008');



define('DB_SAFE_SEPARATOR', '*_*');
define('GENERAL_BOOKING_TRIGGER_TOKEN', '***GENERAL_TRIGGER_TOKEN***');
define('SPECIFIC_BOOKING_TRIGGER_TOKEN', '***SPECIFIC_TRIGGER_TOKEN***');

define('REMINDER_TYPE_PREFIX', 'reminder_type');
define('SCHEDULER_ID_PREFIX', 'dailySchedulerId');
define('REFERENCE_ID_PREFIX', 'referenceId');
define('SCHEDULE_ATTRIBUTE_ID_PREFIX', 'attributeId');
define('SCHEDULER_RELOAD_TIME_LIMIT', 600000);
define('A_DAY_TIMESTAMP', 86400);
/*
 |--------------------------------------------------------------------------
 | Report Filters
 |--------------------------------------------------------------------------
 */
define('FILTER_PLANNED_BOOKING', 'FILTER_PLANNED_BOOKING');
define('FILTER_COMPLETED_BOOKING', 'FILTER_COMPLETED_BOOKING');
define('FILTER_CANCELLED_BOOKING', 'FILTER_CANCELLED_BOOKING');
/*
 |--------------------------------------------------------------------------
 | Social Login Constants for Social Login Integration
 |--------------------------------------------------------------------------
 */
define('GENERAL_SOCIAL', 1);
//Image configuration

define('MAX_DOMAIN_LOGO_SIZE','1000000');
define('MAX_DOMAIN_LOGO_WIDTH','1800');
define('MAX_DOMAIN_LOGO_HEIGHT','400');


/*AES Encryption Key*/
define('PROVAB_ENC_KEY','0x6211e4df763ac394df2bd2a84fa7fbebfa6797f939f846de4e2cd1bf2c00f587');

define('PROVAB_MD5_SECRET','14c374552fa9b2b1d64c4799698cf0f4');

define('PROVAB_SECRET_IV','fdbe2d90bb96e6c334dc1eb308985f9e');
/* End of file constants.php */
/* Location: ./application/config/constants.php */
