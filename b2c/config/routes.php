<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

 |	http://codeigniter.com/user_guide/general/routing.html

 |

 | -------------------------------------------------------------------------

 | RESERVED ROUTES

 | -------------------------------------------------------------------------

 |

 | There area two reserved routes:	

 |

 |	$route['default_controller'] = 'welcome';

 |

 | This route indicates which controller class should be loaded if the

 | URI contains no data. In the above example, the "welcome" class

 | would be loaded.

 |

 |	$route['404_override'] = 'errors/page_missing';

 |

 | This route will tell the Router what URI segments to use if those provided

 | in the URL cannot be matched to a valid route.

 |

 */

//Adding Custom Routes for CMS Url Rewriting

require_once APPPATH .'libraries/custom_router.php';

$route = Custom_Router::cms_routes();

$route = Custom_Router::cms_routes_new();

/*if($_SERVER['REMOTE_ADDR'] == "106.207.84.204")

{

	

	// print_r($route);exit;

}*/

$meta_tags = Custom_Router::set_meta_tags();

$domain_key = Custom_Router::domain_details();

// echo "<pre>";print_r($domai_key);exit;



$route['default_controller'] = "general";
//added for new login and registration
//Added a new route to display promocodes
$route['promocodepage'] = "general/promocodes";
$route['loginhandle'] = "auth/loginNew";
$route['register'] = "auth/registerNew";
$route['registeruser'] = "auth/registerNewHandle";
$route["forgotload"] = "auth/forgotPasswordLoad";
$route['forgot'] = "auth/forgotPassword";
$route['forgot_password/reset/(:any)'] = 'auth/verifyLink/$1';
$route['forget/changePassword'] = 'auth/resetPassword';
$route['login'] = "general/newpage";

$route['flights'] = "general/index";
$route['referrals'] = "general/index";
$route['privatejet'] = "general/index";

$route['hotels'] = "general/index";

$route['buses'] = "general/index";

$route['transfers'] = "general/index";

$route['car'] = "general/index";

$route['activities'] = "general/index";

$route['holidays'] = "general/index";

$route['villasapartment'] = "general/index";

$route['privatecar'] = "general/index";

$route['contactus'] = 'general/contactus';

$route['blog'] = 'general/blog';

$route['home'] = 'general/index';





$route['terms-conditions'] = "general/cms/terms-and-conditions-";

// $route['promo-code'] = "general/cms/promo-code";

$route['rewardpoints'] = "general/cms/rewardpoints";

$route['customersupport'] = "general/cms/customersupport";

$route['faq'] = "general/cms/faq";

$route['specialassistance'] = "general/cms/specialassistance";



$route['checkmybooking'] = 'general/checkmybooking';
// Added new routes for customersupport
$route['customersupport'] = 'general/customersupport';
$route['general/contactFormHandle'] = "general/contactFormHandle";

$route['refundinfo'] = 'general/refundinfo';
$route['canceltime'] = 'general/canceltime';
$route['rescheduleflights'] = 'general/rescheduleflights';



$route['investors'] = 'general/investors';

$route['gallery'] = 'general/gallery';

$route['gallery-video'] = 'general/gallery_video';

$route['about-us'] = 'general/about_us';

$route['private-transfer'] = "general/index";

$route['404_override'] = 'general/ooops_404';

$route['404_nodata'] = 'general/nodata';

