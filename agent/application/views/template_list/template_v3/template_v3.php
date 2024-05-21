<?php
$___favicon_ico = $GLOBALS['CI']->template->domain_images('favicon.ico');
$active_domain_modules = $GLOBALS['CI']->active_domain_modules;
$master_module_list = $GLOBALS['CI']->config->item('master_module_list');
// debug($master_module_list);exit;
if (empty($default_view)) {
    $default_view = $GLOBALS['CI']->uri->segment(1);
    if($default_view=='tours'){
            $default_view='package';
        }
        if($default_view=='sightseeing'){
            $default_view='activities';
        }
        if($default_view=='transferv1'){
            $default_view='transfers';
        }
        if($default_view=='bus'){
            $default_view='buses';
        }
        if($default_view=='flight'){
            $default_view='flights';
        }
        if($default_view=='villas_apartment'){
            $default_view='Villas & Apts';
        } 
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="keywords" content="<?= META_KEYWORDS ?>">
        <meta name="description" content="<?= META_DESCRIPTION ?>">
        <link rel="shortcut icon" href="<?= $___favicon_ico ?>" type="image/x-icon">
        <link rel="icon" href="<?= $___favicon_ico ?>" type="image/x-icon">
        <title><?php echo get_app_message('AL001') . ' ' . HEADER_TITLE_SUFFIX; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.4 -->
        <!-- Theme style -->
        <link href="https://fonts.googleapis.com/css?family=Lato|Source+Sans+Pro" rel="stylesheet">
        <link href="https://cloud.typenetwork.com/projects/3365/fontface.css/" rel="stylesheet" type="text/css">        
        <link href="<?php echo $this->template->template_css_dir('AdminLTE.min.css') ?>" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. Choose a skin from the css/skins 
             folder instead of downloading all of them to reduce the load. -->
        <link href="<?php echo $this->template->template_css_dir('_all-skins.min.css') ?>" rel="stylesheet" type="text/css" />
        <?php
        //Loading Common CSS and JS
        $this->current_page->header_css_resource();
        $this->current_page->header_js_resource();
        echo $GLOBALS ['CI']->current_page->css();
        ?>
        <script>
            var app_base_url = "<?= base_url() ?>";
            var tmpl_img_url = '<?= $GLOBALS['CI']->template->template_images(); ?>';
            var _lazy_content;
        </script>
    </head>
    <body class="skin-black-light sidebar-collapse">
        <noscript><img src="<?php echo $GLOBALS['CI']->template->template_images('default_loading.gif'); ?>"
                       class="img-responsive center-block"></img></noscript>
        <div class="wrapper">

            <!-- HEADER starts -->	
            <?php
            //check if the user is loggedin and load respective data
            //START IF - PAGE After LOGIN
            if (is_logged_in_user()) {
                ?>
                <header class="main-header">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <!-- Logo -->
                    <a href="<?php echo base_url() ?>" class="logo bg-white">
                        <!-- mini logo for sidebar mini 50x50 pixels -->
                        <span class="logo-mini"><img src="<?php echo $GLOBALS['CI']->template->domain_images('mini_' . $GLOBALS['CI']->template->get_domain_logo()) ?>" alt="logo"	class="img-responsive center-block"></span>
                        <!-- logo for regular state and mobile devices -->
                        <span class="logo-lg"><img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()) ?>" alt="logo"	class="img-responsive center-block"></span>
                    </a>

                    <!-- Header Navbar: style can be found in header.less -->
                    <nav class="navbar navbar-static-top" role="navigation">
                        <!-- Navbar Right Menu -->
                        <!-- Messages: style can be found in dropdown.less-->
                        <?php
                        if (is_domain_user()) {
                            // debug(agent_current_application_balance());exit; 
                            ?>
                            <div class="balane_msgs">
								 <!--<a href="<?php echo base_url() ?>management/b2b_reward_point_manager">
                                            <strong>
                                                <span>Reward point</span> : 
                                                <span class="crncy" id="total_reward">0</span>
                                            </strong>
                                        </a>-->
                                <a href="<?php echo base_url() ?>management/b2b_balance_manager">
                                    <strong>
                                        <span>Balance</span> : 
                                        <span class="crncy"><?php $balance = agent_current_application_balance();
                                        //Commanded by Karthick , Uncommand if needed
                    //         $currency_obj = new Currency(array('module_type' => 'flight','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                    //         $get_balance = $currency_obj->getConversionRate() * $balance['value'];
                    // // echo agent_base_currency() . ' ' . number_format($balance['value'], '2');
                    //         echo agent_base_currency() . ' ' . number_format($get_balance, '2');

                                        echo agent_base_currency() . ' ' . number_format($balance['value'], '2');
                            ?></span>
                                    </strong></a>
                                    <a href="<?php echo base_url() ?>management/b2b_credit_limit">
                                    <strong>
                                        <span>Credit Limit</span> : 
                                        <span class="crncy"> <?php echo agent_base_currency() . ' ' . number_format($balance['credit_limit'], '2'); ?></span>
                                    </strong>
                                     </a>
                                    <strong>
                                        <span>Due Amount</span> : 
                                        <span class="crncy"> <?php echo agent_base_currency() . ' ' . number_format($balance['due_amount'], '2'); ?></span>
                                    </strong>
                                   
                            </div>
                            <?php
                            }
                            ?>
                            <div class="navbar-custom-menu">
                                <ul class="nav navbar-nav agent_menu">
                                    <?php

                                    foreach ($master_module_list as $k => $v) {
                                        if (in_array($k, $active_domain_modules)) {
                                            /*if($v=="flight") {*/ 
                                                if($v=='package')
                                                {
                                                   $kk='Holidays';
                                                }
                                                else
                                                {
                                                    $kk=$v;
                                                }
                                                if($k!="VHCID1420613749")
                                                {
                                            ?>
                                            <li class="normal_srchreali <?= ((@$default_view == $k || $default_view == $v) ? 'bg-blue' : '') ?>"><a href="<?php echo base_url() ?>menu/dashboard/<?php echo ($v) ?>?default_view=<?php echo $k ?>"><i class="<?= get_arrangement_icon($k) ?>"></i> <span class="none_lables"><?php echo ucfirst($kk) ?></span></a></li>
                                            <?php
                                                }
                                            /*}*/
                                        }
                                    }
                                    ?>
                      
                                  
                                   

                                   

                                    <!--<li class="<?=($default_view =="coming_soon_pt")? 'active' : ''?>">
                                        <a href="https://www.alkhaleejtours.com/agent/menu/dashboard/private-transfer?default_view=VHCID14206137094" id="private transfer" class="active">
                                            <span class="sprte cmnexplor "></span><strong>Private transfer</strong>
                                        </a>
                                    </li>-->

                               

                                  
                                    <!-- Notifications: style can be found in dropdown.less -->
                                    <!-- Active Notification Starts -->
                                    <li class="dropdown notifications-menu">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="get_event_notification">
                                            <i class="far fa-bell"></i>
                                            <span class="label label-warning" id="active_notifications_count"></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <?php
                                                $notification_loading_image = '<div class="text-center loader-image"><img src="' . $GLOBALS['CI']->template->template_images('loader_v3.gif') . '" alt="Loading........"/></div>';
                                                ?>
                                                <!-- inner menu: contains the actual data -->
                                                <ul class="menu" id="notification_dropdown"><?= $notification_loading_image ?></ul>
                                            </li>
                                            <li class="footer hide" id="view_all_notification"><a href="<?= base_url() ?>index.php/utilities/notification_list">View more</a></li>
                                        </ul>
                                    </li>
                                    <!-- Active Notification Ends -->
                                    <!-- User Account: style can be found in dropdown.less -->
                                    <li class="dropdown user user-menu">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <!-- <img src="<?= (empty($GLOBALS['CI']->entity_image) == false ? $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->entity_image) : $GLOBALS['CI']->template->template_images('face.png')) ?>" class="user-image" alt="User Image"/> -->
                                            <img src="<?= (empty($GLOBALS['CI']->entity_image) == false ? $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->entity_image) : "/extras/system/template_list/template_v3/images/face.png") ?>" class="user-image" alt="User Image"/> 
                                            <span class="hidden-xs"><?php echo $GLOBALS['CI']->agency_name ?></span>-<span class="hidden-xs"><?php echo $GLOBALS['CI']->entity_uuid ?></span>
                                        </a>
                                        <ul class="dropdown-menu"> 
                                            <!-- User image -->
                                            <li class="user-header">
                                                <img src="<?= $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()) ?>" class="img-circle" alt="User Image" />
                                                <span class="name_user_down">

        <?= $GLOBALS['CI']->entity_name ?>
                                                </span>

                                                <small class="aciv_mber">Active since <?= app_friendly_absolute_date($GLOBALS['CI']->entity_creation) ?></small>

                                            </li>
                                            <!-- Menu Body -->
                                            <li class="user-body">
                                                <div class="col-xs-12">
                                                    <small>Admin: <i class="fa fa-phone"></i> <?= @$GLOBALS['CI']->entity_domain_phone ?></small>
                                                </div>
                                                <div class="col-xs-12">
                                                    <small><i class="fa fa-envelope"></i> <?= @$GLOBALS['CI']->entity_domain_mail ?></small>
                                                </div>
                                            </li>
                                            <li class="user-body">
                                                <div class="col-xs-6 nopad text-left">
                                                    <a href="<?php echo base_url() . 'user/account?uid=' . intval($GLOBALS['CI']->entity_user_id); ?>" class="flt_btn">Profile</a>
                                                </div>
                                                <div class="col-xs-6 nopad text-right">
                                                    <a href="<?php echo base_url() . 'user/change_password?uid=' . intval($GLOBALS['CI']->entity_user_id); ?>" class="flt_btn">Change Password</a>
                                                </div>
                                            </li>

                                            <!-- Menu Footer-->
                                            <li class="user-footer">

                                                <a href="<?php echo base_url() . 'index.php/auth/initilize_logout' ?>" class="full_logout">Sign out</a>

                                            </li>
                                        </ul>
                                    </li>
                                    <!-- Control Sidebar Toggle Button -->
                                </ul>
                            </div>

                        </nav>
                    </header>
                    <!-- HEADER ends -->
                    <div class="clearfix"></div>
                    <!-- MENU starts -->
                    <!-- Left side column. contains the logo and sidebar -->
                    <aside class="main-sidebar">
                        <!-- sidebar: style can be found in sidebar.less -->
                        <section class="sidebar">
                            <!-- Sidebar user panel -->
                            <div class="user-panel">
                                <div class="pull-left image">
                                    <!-- <img src="<?= (empty($GLOBALS['CI']->entity_image) == false ? $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->entity_image) : $GLOBALS['CI']->template->template_images('face.png')) ?>" class="img-circle" alt="User Profile Image" /> -->
                                    <img src="<?= (empty($GLOBALS['CI']->entity_image) == false ? $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->entity_image) : "/extras/system/template_list/template_v3/images/face.png") ?>" class="img-circle" alt="User Profile Image" />

                                </div>
                                <div class="pull-left info">
                                    <p><?php echo $GLOBALS['CI']->entity_name; ?></p>
                                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                                </div>
                            </div>
                            
                            <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php echo $GLOBALS['CI']->template->isolated_view('menu/magical_menu.php'); ?>
                        </section>
                        <!-- /.sidebar -->
                    </aside>
                    <!-- MENU ends -->

                    <!-- BODY CONTENT starts -->
                    <!-- Content Wrapper. Contains page content -->
                    <div class="content-wrapper" style="background-color: #c6c6c6">
                        <!-- Main content -->
                        <section class="content">
                            <!-- UTILITY NAV -->
                            <div class="container-fluid utility-nav clearfix">
                                <!-- ROW --> <?php
                                if ($this->session->flashdata('message') != "") {
                                    
                                    $message = $this->session->flashdata('message');
                                    $msg_type = $this->session->flashdata('type');
                                    $show_btn = TRUE;
                                    if ($this->session->flashdata('override_app_msg') != "") {
                                        $override_app_msg = $this->session->flashdata('override_app_msg');
                                    } else {
                                        $override_app_msg = FALSE;
                                    }
                                    echo get_message($message, $msg_type, $show_btn, $override_app_msg);
                                }
                                ?> <!-- /ROW -->
                            </div>
                            <!-- Info boxes -->
                            <div class="row_container">
        <?php echo $body ?>
                            </div><!-- /.row -->
                        </section><!-- /.content -->
                    </div><!-- /.content-wrapper -->
                    <!-- BODY CONTENT ends -->

                   
                    <?php
                    //END IF - PAGE After LOGIN
                } else {
                    //Page without LOGIN
                    echo $body;
                }
                ?>
            </div><!-- ./wrapper -->
            <?php
            Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/datepicker.js'), 'defer' => 'defer');
            Provab_Page_Loader::load_core_resource_files();
            // Loading Common CSS and JS
            $GLOBALS ['CI']->current_page->footer_js_resource();
            echo $GLOBALS ['CI']->current_page->js();
            ?>
            <script src='<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/fastclick/fastclick.min.js' defer></script>
            <!-- Sparkline -->
            <script src="<?php echo SYSTEM_RESOURCE_LIBRARY ?>/sparkline/jquery.sparkline.min.js" type="text/javascript" defer></script>
            <!-- SlimScroll 1.3.0 -->
            <script src="<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/slimScroll/jquery.slimscroll.min.js" type="text/javascript" defer></script>
            <script type="text/javascript">
				 $.ajax({
                            type: "GET",
                            url: '<?php echo base_url();?>index.php/loyalty_program/ajax_total_reward',
                            
                            success: function(response)
                            {
                                if(response)
                                {
                                    if(response >0)
                                    {
                                        $("#total_reward").text(response);
                                        // $(".fa-bell").addClass("colorred");
                                    }
                                }
                           }
                       });  
                $(window).scroll(function(){
                    if ($("input").is(":focus")) {
                    $('.hasDatepicker').blur();
                    } 
                    
                });
            </script>
            
    </body>
</html>

