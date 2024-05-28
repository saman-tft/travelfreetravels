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
define('META_TRANSFER_COURSE','TMVIATID1527240212');
define('ADMIN_BASE_CURRENCY_STATIC', 'NPR');
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

define('LOGIN_POINTER', 'ALID');
define('AUTH_USER_POINTER', 'AUID');
define('PROJECT_PREFIX', 'TM');
define('PROJECT_AGENT_PREFIX', 'TFT');
define('DEFAULT_TEMPLATE', 'template_v1');
 define('CURRENT_DOMAIN_KEY', 'TMX6244821650276433');
 //define('CURRENT_DOMAIN_KEY', 'TMX3644721637051232');
 //define('CURRENT_DOMAIN_KEY', 'TMX3372451534825527');

//echo CURRENT_DOMAIN_KEY;die;
define('DOMAIN_AUTH_ID', 'domain_auth_id');
define('DOMAIN_KEY', 'domain_key');
define('PHONE', 'phone');
define('EMAIL', 'email');
define('GENERAL_SOCIAL', 1);
define('PROJECT_NAME','Travelomatix');
define('STATIC_ADDRESS', "Sumangal Residence, Opp. Prime Minister's Quarter, Gate No. 1
Baluwatar, Kathmandu-03.");
define('STATIC_COUNTRY', 'NEPAL');
define('DOMAIN_LINK_URL','https://travelfreetravels.com');
/*
 |--------------------------------------------------------------------------
 | Folder Names
 |--------------------------------------------------------------------------
 |--------------------------------------------------------------------------
 | Application Folder Names
 |--------------------------------------------------------------------------
 */

$project_parent_folder = 'travelfreetravels';

if (empty($project_parent_folder) == false) {
	//define('APP_ROOT_DIR', '/'.$project_parent_folder); //main folder which wraps complete application
	define('APP_ROOT_DIR', '/'.$project_parent_folder);
	define('PROJECT_COOKIE_PATH', '/'.$project_parent_folder.'/agent/');
} else {
	define('APP_ROOT_DIR', ''); //main folder which wraps complete application
	define('PROJECT_COOKIE_PATH', '/agent/');
}


define('MODULE_ROOT_DIR', '/agent'); //main folder which holds current module
define('PROJECT_URI', APP_ROOT_DIR.MODULE_ROOT_DIR);
define('PROJECT_COOKIE_NAME', 'agent');
define('APP_ROOT_DIR_TEST', 'https://www.traveLfreetravels.com/agent/');



/*
 |--------------------------------------------------------------------------
 | PLAZMA KEYS
 |--------------------------------------------------------------------------
 */

//LIVE
// define('PLAZMAURL','http://usbooking.org/us/UnitedSolutions?wsdl=');
// define('PLAZMAUSERID','TRVFRE');
// define('PLAZMAPASSWORD','TR@F169@2EE');
// define('PLAZMAAGENCY','PLZ169');

//TEST
define('PLAZMAURL','http://dev.usbooking.org/us/UnitedSolutions?wsdl=');
define('PLAZMAUSERID','TRAFUS');
define('PLAZMAPASSWORD','PASSWORD');
define('PLAZMAAGENCY','PLZ131');


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
	define('SYSTEM_TEMPLATE_LIST_RELATIVE_PATH', realpath('../..'.SYSTEM_TEMPLATE_LIST));
} else {
	define('SYSTEM_TEMPLATE_LIST_RELATIVE_PATH', realpath('../extras').'/system/template_list');
}*/
define('DOMAIN_HOTEL_UPLOAD_DIR', realpath('../extras').'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/');
define('DOMAIN_HOTEL_IMAGE_DIR', CUSTOM_RESOURCE_DIR.'/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/');

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
define('DOMAIN_PCKG_UPLOAD_DIR', realpath('../extras').'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/packages/');
define('DOMAIN_PCKG_UPLOAD_DIR2', CUSTOM_RESOURCE_DIR.'/'.CURRENT_DOMAIN_KEY.'/uploads/packages/');

define('DOMAIN_TMP_DIR', CUSTOM_RESOURCE_DIR.'/'.CURRENT_DOMAIN_KEY.'/tmp/');
define('DOMAIN_TMP_UPLOAD_DIR', realpath('../extras').'/custom/'.CURRENT_DOMAIN_KEY.'/tmp/');
define('DOMAIN_LAG_IMAGE_DIR', CUSTOM_RESOURCE_DIR.'/'.CURRENT_DOMAIN_KEY.'/images/flags');
/*
 |--------------------------------------------------------------------------
 PDF
 |--------------------------------------------------------------------------
 */
//define('DOMAIN_PDF_DIR', CUSTOM_RESOURCE_DIR.'/'.CURRENT_DOMAIN_KEY.'/temp_booking_data_pdf/');
define ('DOMAIN_PDF_DIR', '../extras/custom/'.CURRENT_DOMAIN_KEY.'/temp_booking_data_pdf/',true);
/*
 |--------------------------------------------------------------------------
 | PAGE CONFIGURATION
 |--------------------------------------------------------------------------
 */
define('CUSTOM_FOLDER_PREFIX', '');

define('CORE_PAGE_CONFIGURATIONS', '../'.MODULE_ROOT_DIR.'/application/views/page_configuration/');
define('COMMON_JS', '../'.MODULE_ROOT_DIR.'/application/views/page_configuration/resources/common.php');
define('DATEPICKER_JS', '../'.MODULE_ROOT_DIR.'/application/views/page_configuration/resources/datepicker.php');
define('COMMON_UI_JS', '../'.MODULE_ROOT_DIR.'/application/views/page_configuration/resources/common_ui_js.php');
define('COMMON_SHARED_CSS_RESOURCE', '../'.MODULE_ROOT_DIR.'/application/views/page_configuration/resources/header_css_resource.php');
define('COMMON_SHARED_JS_RESOURCE', '../'.MODULE_ROOT_DIR.'/application/views/page_configuration/resources/header_js_resource.php');
define('COMMON_SHARED_FOOTER_JS_RESOURCE', '../'.MODULE_ROOT_DIR.'/application/views/page_configuration/resources/footer_js_resource.php');
define('ENUM_DATA_DIR', '../'.MODULE_ROOT_DIR.'/application/custom/enumeration/');
define('DATATYPE_DIR', '../'.MODULE_ROOT_DIR.'/application/custom/data_type/');
define('COMMON_SHARED_JS', '../'.MODULE_ROOT_DIR.'/application/views/page_configuration/shared_js/');
define('DOMAIN_CONFIG', '../'.MODULE_ROOT_DIR.'/application/custom/domain_config/');
define('DOMAIN_ACTY_UPLOAD_DIR', CUSTOM_RESOURCE_DIR.'/'.CURRENT_DOMAIN_KEY.'/uploads/activity/');
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
define('FAILURE_MESSAGE', 4);
define('BOOKING_CONFIRMED', 1);//Booking completed
define('BOOKING_HOLD', 2);//Booking on hold
define('BOOKING_CANCELLED', 3);//Booked and cancelled
define('BOOKING_ERROR', 4);//unable to continue booking
define('BOOKING_INCOMPLETE', 5);//left in between
define('BOOKING_VOUCHERED', 6);//left in between
define('BOOKING_PENDING', 7);//left in between
define('BOOKING_FAILED', 8);//left in between
define('BOOKING_INPROGRESS', 9);//Booking is processing

/*
 |--------------------------------------------------------------------------
 | Status codes used in topup
 |--------------------------------------------------------------------------
 */
define('INITIATED', 4);
define('REFUNDED', 5);
define('EXPIRED', 6);

define('FULL_REFUND', 7);
define('PARTIAL_REFUND', 8);
define('AMBIGUOUS', 9);
define('NOT_FOUND', 10);

define('TOPUP_INPROGRESS', 11);
define('TOPUP_FAILED', 12);
define('TOPUP_SUCCESSFUL', 13);
define('TOPUP_INITIATED', 14);
define("ERROR",15);
define('FAILED',16);


define('PAY_NOW', 'PNHB1'); // FonePay
define('PAY_AT_BANK', 'PABHB2'); //CIPS
define('PAY_WITH_ESEWA', 'PWEHB3'); //ESEWA
define('PAY_WITH_KHALTI', 'PWKHB4'); //KHALTI
define('PAY_WITH_NICA', 'PWNICAHB4'); //NICASIA
/*
 |--------------------------------------------------------------------------
 | Connectips credentials
 |--------------------------------------------------------------------------
 */

//Merchant details CIPS
define('IPSCONNCT_MID', 199);
define('IPSCONNCT_APPID', 'MER-199-APP-5');
define('IPSCONNCT_VALIDATION_URL', 'https://login.connectips.com/connectipswebws/api/creditor/validatetxn');

/*
 |--------------------------------------------------------------------------
 | Type Of Markup Supported in application
 |--------------------------------------------------------------------------
 */
define('GENERIC', 0);
define('SPECIFIC', 1);
define('MARKUP_VALUE_PERCENTAGE', 0);
define('MARKUP_VALUE_MONEY', 1);
define('B2C_FLIGHT', 1);
define('B2C_HOTEL', 2);
define('B2C_CAR', 3);
define('MARKUP_CURRENCY', 'NPR');

/*
 |--------------------------------------------------------------------------
 | Currency
 |--------------------------------------------------------------------------
 */
define('UNIVERSAL_DEFAULT_CURRENCY', 'NPR'); // INR
//define('COURSE_LIST_DEFAULT_CURRENCY', 61); // INR
define('COURSE_LIST_DEFAULT_CURRENCY', 40); // USD
// define('COURSE_LIST_DEFAULT_CURRENCY_VALUE', 'INR');
define('COURSE_LIST_DEFAULT_CURRENCY_VALUE', 'NPR');
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
define ( 'HOLIDAY_BOOKING', 'TA' );
define('HOLIDAY_BOOKING_SOURCE','PTBSID0000000022');


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
define('CARADULT_DATE_PICKER', '10'); 

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
define('C_MRS_TITLE', 5);
define('A_MASTER',6);

/*
 |--------------------------------------------------------------------------
 Country AND City Code
 |--------------------------------------------------------------------------
 */
define('INDIA_CODE', 92);
define('INDIA_COUNTRY_CODE', +91);
define('ISO_INDIA', 'IN');

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
define('META_CAR_COURSE', 'TMCAR1433491849');
define('META_ACCOMODATION_CRS', 'VHCID1420613749');
define('META_RESVOYAGE_COURCE', 'VHCID1420613749420');
define('META_PRIVATECAR_COURSE','VHCID14206137979');
define('META_SIGHTSEEING_COURSE','TMCID1524458882');
define('META_TRANSFERV1_COURSE','TMVIATID1527240212');
/*
 |--------------------------------------------------------------------------
 Booking source of Course Type
 |--------------------------------------------------------------------------
 */
define('PROVAB_HOTEL_BOOKING_SOURCE', 'PTBSID0000000001');
define('PROVAB_RESVOYAGE_BOOKING_SOURCE',  'PTBSID0000000004201');
define('PROVAB_FLIGHT_BOOKING_SOURCE', 'PTBSID0000000002');
define('AMADEUS_FLIGHT_BOOKING_SOURCE', 'PTBSID0000000009');
define('PROVAB_BUS_BOOKING_SOURCE', 'PTBSID0000000003');
define('PROVAB_SIGHTSEEN_BOOKING_SOURCE', 'PTBSID0000000006');
define('PROVAB_CAR_BOOKING_SOURCE', 'PTBSID0000000007');
define('PROVAB_CAR_CRS_BOOKING_SOURCE', 'PTBCSID00000000017');
define('PROVAB_TRANSFERV1_BOOKING_SOURCE','PTBSID0000000008');
define('PROVAB_TRANSFERV1_SOURCE_CRS', 'PTBSID00000000013');
define('CRS_HOTEL_BOOKING_SOURCE','PTBSID0000000011');
define('PROVAB_SIGHTSEEN_SOURCE_CRS', 'PTBSID00000000012');
define('PROVAB_AEROCRS_BOOKING_SOURCE', 'PTBSID0000000020');
define('PLAZMA_BOOKING_SOURCE', 'PTBSID0000000021');

/*
 |--------------------------------------------------------------------------
 TBO SPECIFIC CONSTANTS
 |--------------------------------------------------------------------------
 */
define('LCC_BOOKING', 'process_fare_quote');
define('NON_LCC_BOOKING', 'process_fare_quote');//In V10, FareQuote is Mandatoy For Both LCC and NON-LCC FLIGHTS

/*
 |--------------------------------------------------------------------------
 | Different Types Of Payment Methods supported in application
 |--------------------------------------------------------------------------
 */
define('PAY_NOW', 'PNHB1');
define('PAY_AT_BANK', 'PABHB2');

/*
 |--------------------------------------------------------------------------
 | Custom Provab Application Seperator used in the application
 |--------------------------------------------------------------------------
 */
define('DB_SAFE_SEPARATOR', '*_*');

define('HOTEL_BOOKING', 'HB');
define('FLIGHT_BOOKING', 'FB');
define('TRANSFER_BOOKING','TB');
define('BUS_BOOKING', 'BB');
define('CAR_BOOKING', 'CB');
define('MAX_BUS_SEAT_BOOKING', 6);
define('SIGHTSEEING_BOOKING','SB');


/*
 |--------------------------------------------------------------------------
 | Email ID Constants
 |--------------------------------------------------------------------------
 */
define('GENERAL_EMAIL', 1);
/*
 |--------------------------------------------------------------------------
 |Report constants
 |--------------------------------------------------------------------------
 */
define('SCHEDULER_RELOAD_TIME_LIMIT', 600000);
/*
 |--------------------------------------------------------------------------
 | SMS Constants
 |--------------------------------------------------------------------------
 */
define('GENERAL_SMS', 1);
define('LAZY_IMG_ENCODED_STR', '');
/*
 |--------------------------------------------------------------------------
 | Report Filters
 |--------------------------------------------------------------------------
 */
define('FILTER_PLANNED_BOOKING', 'FILTER_PLANNED_BOOKING');
define('FILTER_COMPLETED_BOOKING', 'FILTER_COMPLETED_BOOKING');
define('FILTER_CANCELLED_BOOKING', 'FILTER_CANCELLED_BOOKING');

/*Image Configuration*/

define('MAX_DOMAIN_LOGO_SIZE','1000000');
define('MAX_DOMAIN_LOGO_WIDTH','10000');
define('MAX_DOMAIN_LOGO_HEIGHT','10000');
/*AES Encryption Key*/
define('PROVAB_ENC_KEY','0x6211e4df763ac394df2bd2a84fa7fbebfa6797f939f846de4e2cd1bf2c00f587');

define('PROVAB_MD5_SECRET','14c374552fa9b2b1d64c4799698cf0f4');

define('PROVAB_SECRET_IV','fdbe2d90bb96e6c334dc1eb308985f9e');

//changes added new keys for CIPS payment
//Merchant details CIPS
// define('IPSCONNCT_MID', 199);
// define('IPSCONNCT_APPID', 'MER-199-APP-5');
// define('IPSCONNCT_VALIDATION_URL', 'https://login.connectips.com/connectipswebws/api/creditor/validatetxn');
//changes added new keys for nicasia payment
// TEST NIC ASIA CREDS PS: also comment and uncomment live and test creds in verify_nica function in payment_gateway.php file
// define('NICA_SECRET_KEY', '9c0d3ded679b4eb6a5ea4a4bc8b26d67ac6d36f66b47455a8b1d88dca028a5867c206dcc2e554d74bb00b6e9ebde0267df4b4a6b7036470d9d443e7437924e35f9c5da6f12a04bf9a8468136e3bec1d9bf59395b60d5481db6dc5395f455a736645505a1d2c541e98b3578d59a00327cb92e920c8fb44bbaa64e3ad08907208e');
// define('NICA_ACCESS_KEY', 'b07e89f9538a33a29cd40f90c5396a0b');
// define('NICA_PROFILE_ID', '712D7C24-02A0-4E8D-BFAF-76D66CE50152');
// define('NICA_URL', 'https://testsecureacceptance.cybersource.com/pay');

// LIVE Merchant details NICASIA
define('NICA_SECRET_KEY', '8412c08da61747d9a6f33a7112bde8eeee310f01fbfa4728a7d3ad25d517d3252a1c835789c64a0194a24faca63ed4d6b97b3c975807484eafaebf7bc8cd48de219f7e55b82848088bf828c6088d41d7c8d904a8b04040a4ac07816dbc51bba291a8d59f7b254b75ade198889919386d6060e258ef8942038acb6719c1fc873b');
define('NICA_ACCESS_KEY', '067585b22f5933b89066dc2ee9c4a7d0');
define('NICA_PROFILE_ID', 'AE78244D-8690-4250-9F7A-3F6858B5F36E');
define('NICA_URL', 'https://secureacceptance.cybersource.com/pay');


// LIVE CREDENTIALS FOR PROTECT INSURANCE API
define('PROTECT_LIVE_USERNAME', 'UAT_DEMO');
define('PROTECT_LIVE_PASSWORD', 'ypHALsJ3EG3p');
define('PROTECT_LIVE_CHANNEL_CODE', 'IBE_B2BAE');
define('PROTECT_LIVE_REQUEST_URL', 'https://uat-tpe.tune2protect.com/ZeusAPI/Zeus.asmx');


//TEST CREDENTIALS FOR PROTECT INSURANCE API
define('PROTECT_TEST_USERNAME', 'UAT_DEMO');
define('PROTECT_TEST_PASSWORD', 'ypHALsJ3EG3p');
define('PROTECT_TEST_CHANNEL_CODE', 'IBE_B2BAE');
define('PROTECT_TEST_REQUEST_URL', 'https://uat-tpe.tune2protect.com/ZeusAPI/Zeus.asmx');

/* End of file constants.php */
/* Location: ./application/config/constants.php */
