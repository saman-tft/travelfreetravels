<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 |--------------------------------------------------------------------------
 | File and Directory Modes
 |--------------------------------------------------------------------------
 |
 | These prefs are used when checking and setting modes when working
 | with the file system.  The defaults are fine on servers with proper
 | security, but you may wish (or even need) to change the vaselctmarklues in
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
define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');
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


define('PROJECT_PREFIX', 'TM'); //Prefix Used In Project - User ID, Voucher ID
define('PROJECT_NAME', 'Travelomatix');
define('CURRENT_DOMAIN_KEY', 'TMX6244821650276433');
//define('CURRENT_DOMAIN_KEY','TMX3372451534825527');
if (ENVIRONMENT == 'testing') {
	define('CURRENT_DOMAIN_KEY', 'TMX6244821650276433');
	//	define('CURRENT_DOMAIN_KEY', 'TMX3644721637051232'); // FIXME//Vc8Z7XLsyys9TEQdFPkV
} else {

	define('CURRENT_DOMAIN_KEY', 'TMX6244821650276433');

	//	define('CURRENT_DOMAIN_KEY', 'TMX3644721637051232'); // FIXME//Vc8Z7XLsyys9TEQdFPkV
}
define('PROJECT_COOKIE_NAME', 'travels'); //FIXME

define('DEFAULT_TEMPLATE', 'template_v1');
define('LOGIN_POINTER', 'LID');
define('AUTH_USER_POINTER', 'AID');
define('DOMAIN_AUTH_ID', 'domain_auth_id');
define('DOMAIN_KEY', 'domain_key');
define('PHONE', 'phone');
define('EMAIL', 'email');
define('STATIC_ADDRESS', "Sumangal Residence, Opp. Prime Minister's Quarter, Gate No. 1
Baluwatar, Kathmandu-03.");
define('STATIC_COUNTRY', 'Nepal');


/*
 |--------------------------------------------------------------------------
 | Folder Names
 |--------------------------------------------------------------------------
 |--------------------------------------------------------------------------
 | Application Folder Names
 |--------------------------------------------------------------------------
 */
if (ENVIRONMENT == 'development') {
	$project_parent_folder = 'travelfreetravels';
	$extras_folder = 'extras';
	$extras_folder_realpath = '../' . $extras_folder;
} elseif (ENVIRONMENT == 'production') {
	$project_parent_folder = '';
	$extras_folder = 'extras';
	$extras_folder_realpath = '../' . $extras_folder;
} else {
	$project_parent_folder = '';
	$extras_folder = 'extras';
	$extras_folder_realpath = '../' . $extras_folder;
}



if (empty($project_parent_folder) == false) {
	define('APP_ROOT_DIR', '/' . $project_parent_folder); //main folder which wraps complete application
	define('PROJECT_COOKIE_PATH', '/' . $project_parent_folder . '/');
} else {
	define('APP_ROOT_DIR', ''); //main folder which wraps complete application
	define('PROJECT_COOKIE_PATH', '/');
}

define('MODULE_ROOT_DIR', ''); //main folder which holds current module
define('PROJECT_URI', APP_ROOT_DIR . MODULE_ROOT_DIR);

/*
 |--------------------------------------------------------------------------
 | RESOURCE FOLDERS
 |--------------------------------------------------------------------------
 */
define('RESOURCE_DIR', APP_ROOT_DIR . '/extras'); //main folder which holds all the resource
define('SYSTEM_RESOURCE_DIR', RESOURCE_DIR . '/system');
define('CUSTOM_RESOURCE_DIR', RESOURCE_DIR . '/custom');
define('SYSTEM_RESOURCE_LIBRARY', SYSTEM_RESOURCE_DIR . '/library'); //complete application library storage
define('SYSTEM_TEMPLATE_LIST', SYSTEM_RESOURCE_DIR . '/template_list'); //complete application template storage
define('SYSTEM_TEMPLATE_LIST_RELATIVE_PATH', realpath('extras') . '/system/template_list');
define('DOMAIN_PROMO_IMAGE_DIR', SYSTEM_RESOURCE_DIR . '/template_list/template_v1/images/promocode/');
define('DOMAIN_HOTEL_IMAGE_DIR', CUSTOM_RESOURCE_DIR . '/' . CURRENT_DOMAIN_KEY . '/uploads/hotel/');
/*
 |--------------------------------------------------------------------------
 | EXTRAS LIBRARY FOLDERS
 |--------------------------------------------------------------------------
 */
define('BOOTSTRAP_JS_DIR', SYSTEM_RESOURCE_LIBRARY . '/bootstrap/js/');
define('BOOTSTRAP_CSS_DIR', SYSTEM_RESOURCE_LIBRARY . '/bootstrap/css/');
define('SYSTEM_IMAGE_DIR', SYSTEM_RESOURCE_LIBRARY . '/images/');
define('GRAPH_SCRIPT', SYSTEM_RESOURCE_LIBRARY . '/Highcharts/js/');
define('JAVASCRIPT_LIBRARY_DIR', SYSTEM_RESOURCE_LIBRARY . '/javascript/');
define('JQUERY_UI_LIBRARY_DIR', JAVASCRIPT_LIBRARY_DIR . 'jquery-ui-1.11.2.custom/');
define('DATEPICKER_LIBRARY_DIR', SYSTEM_RESOURCE_LIBRARY . '/datetimepicker/');

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

define('DOMAIN_IMAGE_DIR', CUSTOM_RESOURCE_DIR . '/' . CURRENT_DOMAIN_KEY . '/images/');
define('DOMAIN_IMAGE_UPLOAD_DIR', realpath('extras') . '/custom/' . CURRENT_DOMAIN_KEY . '/images/');
define('DOMAIN_UPLOAD_DIR', CUSTOM_RESOURCE_DIR . '/' . CURRENT_DOMAIN_KEY . '/uploads/');
define('DOMAIN_PCKG_UPLOAD_DIR', realpath('../extras') . '/custom/' . CURRENT_DOMAIN_KEY . '/uploads/packages/');
define('DOMAIN_PCKG_UPLOAD_DIR2', CUSTOM_RESOURCE_DIR . '/' . CURRENT_DOMAIN_KEY . '/uploads/packages/');


define('DOMAIN_TMP_DIR', CUSTOM_RESOURCE_DIR . '/' . CURRENT_DOMAIN_KEY . '/tmp/');
define('DOMAIN_TMP_UPLOAD_DIR', realpath('extras') . '/custom/' . CURRENT_DOMAIN_KEY . '/tmp/');
define('DOMAIN_BAN_UPLOAD_DIR', realpath('system') . '/template_list/template_v1/images/');
define('DOMAIN_TOP_AIRLINE_IMAGE_DIR', SYSTEM_RESOURCE_DIR . '/template_list/template_v3/images/top_airlines/');
define('DOMAIN_TOUR_STYLE_IMAGE_DIR', SYSTEM_RESOURCE_DIR . '/template_list/template_v3/images/tourstyles/');
define('IMG_UPLOAD_DIR', 'extras/system/template_list/template_v1/images/');
define('DOMAIN_ACTY_UPLOAD_DIR', CUSTOM_RESOURCE_DIR . '/' . CURRENT_DOMAIN_KEY . '/uploads/activity/');

// echo DOMAIN_BAN_UPLOAD_DIR;exit;
/*
 |--------------------------------------------------------------------------
 PDF
 |--------------------------------------------------------------------------
 */
define('DOMAIN_PDF_DIR', CUSTOM_RESOURCE_DIR . '/' . CURRENT_DOMAIN_KEY . '/temp_booking_data_pdf/');
//define ('DOMAIN_PDF_DIR', '../extras/custom/'.CURRENT_DOMAIN_KEY.'/temp_booking_data_pdf/',true);
/*
 |--------------------------------------------------------------------------
 | PAGE CONFIGURATION
 |--------------------------------------------------------------------------
 */
define('CUSTOM_FOLDER_PREFIX', '');

define('CORE_PAGE_CONFIGURATIONS', CUSTOM_FOLDER_PREFIX . 'b2c/views/page_configuration/');
define('COMMON_JS', CUSTOM_FOLDER_PREFIX . 'b2c/views/page_configuration/resources/common.php');
define('DATEPICKER_JS', CUSTOM_FOLDER_PREFIX . 'b2c/views/page_configuration/resources/datepicker.php');
define('COMMON_UI_JS', CUSTOM_FOLDER_PREFIX . 'b2c/views/page_configuration/resources/common_ui_js.php');
define('COMMON_SHARED_CSS_RESOURCE', CUSTOM_FOLDER_PREFIX . 'b2c/views/page_configuration/resources/header_css_resource.php');
define('COMMON_SHARED_JS_RESOURCE', CUSTOM_FOLDER_PREFIX . 'b2c/views/page_configuration/resources/header_js_resource.php');

define('COMMON_SHARED_FOOTER_JS_RESOURCE', CUSTOM_FOLDER_PREFIX . 'b2c/views/page_configuration/resources/footer_js_resource.php');
define('ENUM_DATA_DIR', CUSTOM_FOLDER_PREFIX . 'b2c/custom/enumeration/');
define('DATATYPE_DIR', CUSTOM_FOLDER_PREFIX . 'b2c/custom/data_type/');
define('COMMON_SHARED_JS', CUSTOM_FOLDER_PREFIX . 'b2c/views/page_configuration/shared_js/');
define('DOMAIN_CONFIG', CUSTOM_FOLDER_PREFIX . 'b2c/custom/domain_config/');

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
 | PLAZMA KEYS
 |--------------------------------------------------------------------------
 */

//LIVE


// define('PLAZMAURL', 'http://usbooking.org/us/UnitedSolutions?wsdl=');
// define('PLAZMAUSERID', 'TRVFRE');
// define('PLAZMAPASSWORD', 'TR@F169@2EE');
// define('PLAZMAAGENCY', 'PLZ169');

//TEST
 define('PLAZMAURL','http://dev.usbooking.org/us/UnitedSolutions?wsdl=');
define('PLAZMAUSERID','TRAFUS');
define('PLAZMAPASSWORD','PASSWORD');
define('PLAZMAAGENCY','PLZ131');

/*

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
//status for khalti
define('INITIATED', 4);
define('REFUNDED', 5);
define('EXPIRED', 6);
//status for esewa
define('FULL_REFUND', 7);
define('PARTIAL_REFUND', 8);
define('AMBIGUOUS', 9);
define('NOT_FOUND', 10);


define('SUCCESS_MESSAGE', 0);
define('ERROR_MESSAGE', 1);
define('WARNING_MESSAGE', 2);
define('INFO_MESSAGE', 3);
define('BOOKING_CONFIRMED', 1); //Booking completed
define('BOOKING_HOLD', 2); //Booking on hold
define('BOOKING_CANCELLED', 3); //Booked and cancelled
define('BOOKING_ERROR', 4); //unable to continue booking
define('BOOKING_INCOMPLETE', 5); //left in between
define('BOOKING_VOUCHERED', 6); //left in between
define('BOOKING_PENDING', 7); //left in between
define('BOOKING_FAILED', 8); //left in between
define('BOOKING_INPROGRESS', 9); //Booking is processing
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
define('HOLIDAY_BOOKING', 'TA');

/*
 |--------------------------------------------------------------------------
 | Currency
 |--------------------------------------------------------------------------
 */
define('UNIVERSAL_DEFAULT_CURRENCY', 'NPR'); // INR
define('COURSE_LIST_DEFAULT_CURRENCY', 61); // INR
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

define('FLIGHT_API', 00);
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
define('FUTURE_DATE', '12');
define('PAST_DATE_TIME', '2');
define('FUTURE_DATE_TIME', '3');
define('ENABLE_MONTH', '4');
define('ADULT_DATE_PICKER', '5');
define('CHILD_DATE_PICKER', '6');

define('INFANT_DATE_PICKER', '7');

define('YOUTH_DATE_PICKER', '5'); //Sightseeing
define('SENIOR_DATE_PICKER', '4'); //Sightseeing

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
define('A_MASTER', 6);

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
//define('META_TRANSFERS_COURSE', 'VHCID1420613763');
define('META_ACCOMODATION_COURSE', 'VHCID1420613748');
define('META_ACCOMODATION_CRS', 'VHCID1420613749');
define('META_RESVOYAGE_CRS', 'VHCID1420613749420');
define('META_BUS_COURSE', 'VHCID1433498307');
define('META_PACKAGE_COURSE', 'VHCID1433498322');
define('META_TRANSFER_COURSE', 'VHCID1433496655');
define('META_SIGHTSEEING_COURSE', 'TMCID1524458882');
define('META_CAR_COURSE', 'TMCAR1433491849');
define('META_TRANSFERV1_COURSE', 'TMVIATID1527240212');
define('META_PRIVATECAR_COURSE', 'VHCID14206137979');
define('META_PRIVATETRANSFER_COURSE', 'VHCID14206137978');
define('META_RESVOYAGE_COURCE', 'VHCID1420613749420');
define('META_INSURANCE_COURSE', 'TTAGINS15741283692');
define('META_RETIREMENT_COURSE', 'VHCID1000000001');
define('META_PRIVATEJET_COURSE', 'VHCID1420613785'); //Flight CRS
/*
 |--------------------------------------------------------------------------
 Booking source of Course Type
 |--------------------------------------------------------------------------
 */
define('REZLIVE_HOTEL', 'PTBSID0000000034');
define('PROVAB_HOTEL_BOOKING_SOURCE', 'PTBSID0000000001');
define('PROVAB_RESVOYAGE_BOOKING_SOURCE', 'PTBSID0000000004201');
define('PROVAB_FLIGHT_BOOKING_SOURCE', 'PTBSID0000000002');
define('AMADEUS_FLIGHT_BOOKING_SOURCE', 'PTBSID0000000009');
define('PROVAB_BUS_BOOKING_SOURCE', 'PTBSID0000000003');
define('ETRAVEL_BUS_BOOKING_SOURCE', 'PTBSID0000000014');
define('HOTELBED_TRANSFER_BOOKING_SOURCE', 'PTBSID0000000005');

define('PROVAB_SIGHTSEEN_BOOKING_SOURCE', 'PTBSID0000000006');

define('PROVAB_CAR_BOOKING_SOURCE', 'PTBSID0000000007');
define('PROVAB_CAR_CRS_BOOKING_SOURCE', 'PTBCSID00000000017');
define('PROVAB_TRANSFERV1_BOOKING_SOURCE', 'PTBSID0000000008');
define('PROVAB_INSURANCE_BOOKING_SOURCE', 'PTBSID0000000015');
define('HOLIDAY_BOOKING_SOURCE', 'PTBSID0000000022');
define('PLANRETIREMENT_BOOKING_SOURCE', 'PTBSID0000000016');
define('CRS_HOTEL_BOOKING_SOURCE', 'PTBSID0000000011');
define('PROVAB_TRANSFERV1_SOURCE_CRS', 'PTBSID00000000013');
define('PROVAB_AEROCRS_BOOKING_SOURCE', 'PTBSID0000000020');
define('PLAZMA_BOOKING_SOURCE', 'PTBSID0000000021');
define('PROVAB_SIGHTSEEN_SOURCE_CRS', 'PTBSID00000000012');
define('PROVAB_TRIPXML_CAR_BOOKING_SOURCE', 'PTBSID0000000028');
define('PROVAB_FLIGHT_CRS_BOOKING_SOURCE', 'PTBSID0000000025');

/*
 |--------------------------------------------------------------------------
 TBO SPECIFIC CONSTANTS
 |--------------------------------------------------------------------------
 */
define('LCC_BOOKING', 'process_fare_quote');
define('NON_LCC_BOOKING', 'process_fare_quote'); //In V10, FareQuote is Mandatoy For Both LCC and NON-LCC FLIGHTS

/*
 |--------------------------------------------------------------------------
 | Different Types Of Payment Methods supported in application
 |--------------------------------------------------------------------------
 */
define('PAY_NOW', 'PNHB1'); // FonePay
define('PAY_AT_BANK', 'PABHB2'); //CIPS
define('PAY_WITH_ESEWA', 'PWEHB3'); //ESEWA
define('PAY_WITH_KHALTI', 'PWKHB4'); //KHALTI
define('PAY_WITH_NICA', 'PWNICAHB4'); //NICASIA
/*
 |--------------------------------------------------------------------------
 | Custom Provab Application Seperator used in the application
 |--------------------------------------------------------------------------
 */
define('DB_SAFE_SEPARATOR', '*_*');

define('HOTEL_BOOKING', 'HB');
define('FLIGHT_BOOKING', 'FB');
define('BUS_BOOKING', 'BB');
define('SIGHTSEEING_BOOKING', 'SB');
define('TRANSFER_BOOKING', 'TB');
define('CAR_BOOKING', 'CB');
define('PLAN_RETIREMENT', 'PR');
define('MAX_BUS_SEAT_BOOKING', 6);

define('ADMIN_BASE_CURRENCY_STATIC', 'NPR');
define('APP_CURRENCY', 'NPR');

/*
 |--------------------------------------------------------------------------
 | Email ID Constants
 |--------------------------------------------------------------------------
 */
define('GENERAL_EMAIL', 1);
/*
 |--------------------------------------------------------------------------
 | SMS Constants for SMS Integration
 |--------------------------------------------------------------------------
 */
define('GENERAL_SMS', 1);
/*
 |--------------------------------------------------------------------------
 | Social Login Constants for Social Login Integration
 |--------------------------------------------------------------------------
 */
define('GENERAL_SOCIAL', 1);

define('LAZY_IMG_ENCODED_STR', '');
/*
 |--------------------------------------------------------------------------
 | Report Filters
 |--------------------------------------------------------------------------
 */
define('FILTER_PLANNED_BOOKING', 'FILTER_PLANNED_BOOKING');
define('FILTER_COMPLETED_BOOKING', 'FILTER_COMPLETED_BOOKING');
define('FILTER_CANCELLED_BOOKING', 'FILTER_CANCELLED_BOOKING');

/*AES Encryption Key*/
define('PROVAB_ENC_KEY', '0x6211e4df763ac394df2bd2a84fa7fbebfa6797f939f846de4e2cd1bf2c00f587');
define('PROVAB_MD5_SECRET', '14c374552fa9b2b1d64c4799698cf0f4');
define('PROVAB_SECRET_IV', 'fdbe2d90bb96e6c334dc1eb308985f9e');


//Merchant details CIPS
define('IPSCONNCT_MID', 199);
define('IPSCONNCT_APPID', 'MER-199-APP-4');
define('IPSCONNCT_VALIDATION_URL', 'https://login.connectips.com/connectipswebws/api/creditor/validatetxn');

/*
//TEST Merchant details CIPS
define('IPSCONNCT_MID',  582);
define('IPSCONNCT_APPID',  'MER-582-APP-1');
define('IPSCONNCT_VALIDATION_URL',  'https://uat.connectips.com:7443/connectipswebws/api/creditor/validatetxn');
*/

//changes added new keys for nicasia payment
// TEST NIC ASIA CREDS PS: also comment and uncomment live and test creds in verify_nica function in payment_gateway.php file
// define('NICA_SECRET_KEY', 'c72d8494971b476f9edcb8b1461ef6f372b4b44d91dd451d80311473bb13ad7a817290d6cb784101a089b158309923d76fa132fde0cb42418ebfa37c1608bad45c2b69e8effc45aeac48561eb3bcb499e274cd7d1c784f10958471318416b1043aab1b8bdd9b4b06839cba30704a82444611b6390cf14947a181ae85d44c4e16');
// define('NICA_ACCESS_KEY', 'deaa595a47ec35c9ae46dcb978ad0813');
// define('NICA_PROFILE_ID', '712D7C24-02A0-4E8D-BFAF-76D66CE50152');
// define('NICA_URL', 'https://testsecureacceptance.cybersource.com/pay');

// LIVE Merchant details NICASIA
define('NICA_SECRET_KEY', 'b5576049c3584076920cf0ed1ffcec6b9be71d165a504f99bd6a2f32cdac9e6616f41f0f08cf43ce9ecb3b30a6514db41bb7e7239acd44df8c901b63210d1f587bdf9a9b24884f9e8117a2f2c554dbdee86ca60522d04aeb80f66fe2fd1805f9a1411e8b7ce14c06817f9016fc76a0d911f4ef2e55924315a03d1ddbe005997f');
define('NICA_ACCESS_KEY', 'a711df3a7009395983fd326a498a4efc');
define('NICA_PROFILE_ID', 'AE78244D-8690-4250-9F7A-3F6858B5F36E');
define('NICA_URL', 'https://secureacceptance.cybersource.com/pay');	

/*
 |--------------------------------------------------------------------------
 | CONSTANTS FOR SESSION EXPIRY POPUP
 |--------------------------------------------------------------------------
 */

 // define('TFT_SUPPORT_CONTACT', '+977-9860000111');

/* End of file constants.php */
/* Location: ./application/config/constants.php */
