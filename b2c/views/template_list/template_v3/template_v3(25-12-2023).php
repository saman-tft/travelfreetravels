<?php
$___favicon_ico = $GLOBALS ['CI']->template->domain_images('favicon/favicon.ico');
$active_domain_modules = $GLOBALS ['CI']->active_domain_modules;
$master_module_list = $GLOBALS ['CI']->config->item('master_module_list');
if (empty($default_view)) {
    $default_view = $GLOBALS ['CI']->uri->segment(1);
     // if($_SERVER['REMOTE_ADDR'] == '106.207.86.199'){
     //                                             debug($default_view);exit;
     //                                         }
        if($default_view=='tours'){
            $default_view='holidays';
        } 
        if($default_view=='hotel'){
            $default_view='hotels';
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
        if($default_view=='home'){
            $default_view='home';
        } 
        if($default_view=='villasapartment'){
            $default_view='Villas & Apts';
        } 
        if($default_view=='flight_crs'){
            $default_view='flight_crs';
        } 
        //echo $default_view;die;
        if($default_view=='user')
        {
            $default_view = $GLOBALS ['CI']->uri->segment(2);
           
            
        }
}

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$url = "https://";
else
$url = "http://";
// Append the host(domain name, ip) to the URL.
$url.= $_SERVER['HTTP_HOST'];

// Append the requested resource location to the U
$url.= $_SERVER['REQUEST_URI'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo HEADER_TITLE_SUFFIX; ?></title>
    <meta charset="utf-8" />
    <!-- <meta name ='Compatible' http-equiv="X-UA-Compatible" content="IE=edge" /> -->
    <meta name='Compatible' content="IE=edge" />
    <meta name='google-site-verification' content="QquX5o8OZsxEevPOCbWCHXKityUzEbFLtHh7DfD3YGo" />
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
    <meta name="keywords" content="<?= META_KEYWORDS ?>" />
    <meta name="description" content="<?= META_DESCRIPTION ?>" />

    <link rel="shortcut icon" href="<?= $___favicon_ico ?>" type="image/x-icon" />

    <link rel="canonical" href="<?=$url?>" />
    <link rel="icon" href="<?= $___favicon_ico ?>" type="image/x-icon" />

    <link href="https://fonts.googleapis.com/css?family=Righteous" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" />
    <!--         <link href="https://fonts.googleapis.com/css?family=Lato|Source+Sans+Pro" rel="stylesheet" />
 -->
    <link href="https://cloud.typenetwork.com/projects/3365/fontface.css/" rel="stylesheet" type="text/css" />
    <?php
        // Loading Common CSS and JS
        $GLOBALS ['CI']->current_page->header_css_resource();
        Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('front_end.css'), 'media' => 'screen');
        $GLOBALS ['CI']->current_page->header_js_resource();
        echo $GLOBALS ['CI']->current_page->css();
        ?>
    <!-- Custom CSS -->
    <link href="<?php echo $GLOBALS['CI']->template->template_css_dir('media.css'); ?>" rel="stylesheet" />
    <link href="<?php echo $GLOBALS['CI']->template->template_css_dir('google_font.css'); ?>" rel="stylesheet" />
    <script>
    var app_base_url = "<?= base_url() ?>";
    var tmpl_img_url = '<?= $GLOBALS['CI']->template->template_images(); ?>';
    <?php if (!empty($slideImageJson)) { ?>
    var slideImageJson = '<?php echo base64_encode(json_encode($slideImageJson)); ?>';
    var tmpl_imgs = JSON.parse(atob(slideImageJson));
    <?php } ?>
    var _lazy_content;
    </script>
    <script>
    (function(c, l, a, r, i, t, y) {
        c[a] = c[a] || function() {
            (c[a].q = c[a].q || []).push(arguments)
        };
        t = l.createElement(r);
        t.async = 1;
        t.src = "https://www.clarity.ms/tag/" + i;
        y = l.getElementsByTagName(r)[0];
        y.parentNode.insertBefore(t, y);
    })(window, document, "clarity", "script", "7iyq9otobx");
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
    <!--<script src="https://cdn.ywxi.net/js/1.js"></script>-->
    <!-- added google tag manager -->
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K4PP7X5J');</script>
<!-- End Google Tag Manager -->



<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2006552456483075"
     crossorigin="anonymous"></script>

     <!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '705234253982415');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=705234253982415&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->

</head>

<body class="<?php echo (isset($body_class) == false ? 'index_page' : $body_class) ?>">
    <!-- Google Tag Manager (noscript) -->


    <!-- Google tag (gtag.js) --> 
    <!-- added google tag manager -->
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K4PP7X5J"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->



    <div id="show_log" class="modal fade" role="dialog">
        <div class="modal-dialog lgfrmb">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <?php
                    $d=array();
                    $d['refercode']=$_GET["refercode"];
                    ?>
                    <?= $GLOBALS['CI']->template->isolated_view('general/login',$d) ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Timer -->

    
    <!-- Timer -->
    <div class="allpagewrp">


        <!-- Header Start -->
        <header>


            <div class="clearfix"></div>    

            <div class="topssec">
                
            <!-- <div class="search-toptxt alert alert-info fade in alert-dismissible"><marquee width="100%" direction="left"><ul><li><p>ALERT:** <em><strong> This website is Under Maintenance...</strong></em>&nbsp;**</p></li></ul></marquee> </div>   --> 
                <!-- Added id here for nav container styling in the css 2023/12/15  -->
                <div class="container" id="full__container">
                    <div class="bars_menu fa fa-bars menu_brgr"></div>
                       <!-- Added styles here to prevent white background of the logo 2023/12/15  -->
                    <a class="logo" style="mix-blend-mode:multiply;" href="<?php echo base_url()?>">
                        <img width="300" class="tab_logo"
                            src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>"
                            alt="Logo" />
                  <!-- Added styles here for large screen logos 2023/12/15 -->                        <img width="250"  class="ful_logo"
                  style="margin-top:-2em;" 
                            src="<?php echo $GLOBALS['CI'] ->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>"
                            alt="Book a tour to India" />
                    </a>
                    <!-- <div>
                        
                    </div> -->
                    <div class="menuandall">
                        <div class="launch">
                           <!--  <span>Best Deals Available Now.!</span> -->
                             <!--<span>Offer ends in</span><p id="launchtimer"></p>-->
                        </div>
                        <div class="ritsude">
                            <!-- flag currency start-->

                            <div class="sidebtn">
                                <?php if (is_logged_in_user() == false) { ?>
                                    <!-- added new login and signup buttons -->
                                   <a href="<?php echo base_url('/newpage')?>"> <button style="background:transparent;outline:none; border:none;" class="topa logindown" >
                                        <div class="reglog">
                                            <!-- Removed the user icon after signup 2023/12/15 -->
                                            <div class="userimage"style="display:none;">
                                                <?php
                                                if (is_logged_in_user() == true && empty($GLOBALS['CI']->entity_image) == false) {
                                                    $profile_image = $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->entity_image);
                                                } else {
                                                    $profile_image = $GLOBALS['CI']->template->template_images('user.png');
                                                }
                                                ?>
                                                <img src="<?php echo $profile_image; ?>" alt="Book a tour to India" />
                                            </div>
                                            <!-- Added id to the div for the text 2023/12/15 -->
                                        <a style=" background:text-decoration:none;color:white;text-align:center;font-size:14px;" href="<?php echo base_url('/login')?>"><div id="signup-text">SignUp / Login  </a>
                                            </div>
                                        </div>
                                            </button>
                                    </a>
                                <?php } else { ?>
                                <a class="topa logindown dropdown-toggle" data-toggle="dropdown">
                                    <div class="reglog">
                                        <div class="userimage">
                                            <?php
                                                    if (is_logged_in_user() == true && empty($GLOBALS['CI']->entity_image) == false) {
                                                        $profile_image = $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->entity_image);
                                                    } else {
                                                        $profile_image = $GLOBALS['CI']->template->template_images('user.png');
                                                    }
                                                    ?>
                                            <img src="<?php echo $profile_image; ?>" alt="Book a tour to India" />
                                        </div>
                                        <?php if (is_logged_in_user() == false) { ?>
                                        <div class="userorlogin">My Account</div>
                                        <?php } else { ?>
                                        <div class="userorlogin"><?php echo $GLOBALS['CI']->entity_name ?><b
                                                class="caret cartdown"></b>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </a>
                                <div class="dropdown-menu mysign exploreul logdowndiv">
                                    <div class="signdiv">
                                        <div class="clearfix">
                                            <ul>
                                                <li><a
                                                        href="<?= base_url() ?>user/profile/<?= @$GLOBALS['CI']->name ?>">My
                                                        Account</a>
                                                </li>
                                                <li class="divider"></li>
                                                <?php if(true){?>
                                                <li><a href="<?= base_url() . 'auth/change_password' ?>">Change
                                                        Password</a>
                                                </li>
                                                <?php }?>
                                                <li class="divider"></li>
                                                <li><a class="user_logout_button">Logout</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <!-- Updated the styles to adjust the entire nav bar -->
<style>
/*Removed for the default logo*/
    /*.ful_logo{*/
    /*    margin-left:-5em; margin-top:-6em;mix-blend-mode: multiply; max-height:300px;*/
    /*}*/
                @media only screen and (min-width: 1100px) and (max-width: 1200px){
            #default__container{
                max-width: 600px !important;


            }


            
        }
                @media only screen and (min-width: 992px) and (max-width: 1100px){
            #default__container{
                max-width: 600px !important;


            }

/*Removed for the default logo*/
    /*.ful_logo{*/
    /*    margin-left:-5em; margin-top:-4em;mix-blend-mode: multiply; max-height:300px;min-width: 150px;*/
    /*}*/

            
        }
    @media(max-width:480px) {

#side-buttons{
    display:none !important;
}
    }
       @media(max-width:280px) {

#side-buttons{
    display:none !important;
}
    }
</style>
                            <div id="side-buttons" class="sidebtn flgsidebtn flagss">
                                <a class="topa dropdown-toggle" data-toggle="dropdown">
                                    <div class="reglognorml">
                                        <div class="flag_images">
                                            <?php
                                                $curr = get_application_currency_preference();

                                                echo '<span id="disply_curr" class="disply_curr curncy_img sprte ' . strtolower($curr) . '"></span>'
                                                ?>
                                        </div>
                                        <div class="flags">
                                            <?php
                                                echo $curr;
                                                ?>
                                        </div>
                                        <i class="fal fa-angle-down cartt"></i>
                                    </div>
                                </a>
                                <ul class="dropdown-menu exploreul explorecntry logdowndiv">
                                    <?= $this->template->isolated_view('utilities/multi_currency') ?>
                                </ul>
                            </div>

                            <!---account end-->
                        </div>
                        <!-- Added id for the navbar styling 2023/12/15 -->
                        <div class="sepmenus" id="default__container">
                            <ul class="exploreall">
                                <?php $geturl=$this->uri->segment(1);
                                    if($geturl==''){ ?>
                                <li class="active">
                                    <?php } else { ?>
                                <li class="">
                                    <?php } ?>
                                    <a href="<?php echo base_url()?>" id="h">
                                        <span class="sprte cmnexplor "></span><strong>Home</strong>
                                    </a>
                                </li>
                                <?php    
                                    if($_SERVER['REMOTE_ADDR'] == '42.109.147.165'){
                                               //debug($active_domain_modules); exit();

                                            }
                                                                    
                                   foreach ($master_module_list as $k => $v) {
                                        

                                        if (in_array($k, $active_domain_modules)) {
                                            
                                            if($v!="Villas & Apts")
                                            {
                                            
                                            ?>

                                <li
                                    class="<?php echo 'at_'.$v ?> <?= ((@$default_view == $k || $default_view == $v) ? 'active' : '') ?>">

                                    <a href="<?php echo base_url()?><?php  if($v=='transfer'){
												echo "private-transfer";
												} else
											{
												echo $v; 
											 }?>" id="<?php echo ($v) ?>">
                                        <span class="sprte cmnexplor <?= module_spirit_img(strtolower($v)) ?>"></span>
                                        <strong><?php echo ucfirst($v); ?></strong>
                                    </a>
                                </li>
                               

                                <?php
                                            }
                                        }
                                    }
                               
                                   ?>
								 <!--<li class="<?=($default_view =="private-transfer")? 'active' : ''?>">
                                    <a href="<?php echo base_url() ?>private-transfer" id="private transfer">
                                        <span class="sprte cmnexplor "></span><strong>Transfer</strong>
                                    </a>
                                </li>-->
                                <li class="">
                                    <a href="https://www.travelfreetravels.com/agent/index.php/general/index" id="c"> 
                                        <span class="sprte cmnexplor "></span>
                                        <strong> Agent </strong>
                                    </a>
                                    
                                </li>
                                <li class="">
                                    <a href="https://www.travelfreetravels.com/supplier/" id="c"> 
                                        <span class="sprte cmnexplor "></span>
                                        <strong>Suppliers </strong>
                                    </a>
                                    
                                </li>



                            </ul>
                        </div>


                    </div>
                </div>
            </div>
        </header>
        <!-- Header End -->
        <div class="clearfix"></div>
        <!-- UTILITY NAV For Application MESSAGES START -->
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
                ?>
            <!-- /ROW -->
        </div>
        <!-- UTILITY NAV For Application MESSAGES END -->
        <!-- Body Printed Here -->
        <div class="fromtopmargin">
            <?= $body ?>
        </div>
        <div class="clearfix"></div>
        <div class="subscribe">
            <div class="container">
                <div class="col-md-12 col-xs-12 fulnine  nopad">
                    <div class="subscb">
                        <div class="col-md-12 col-xs-12 nopad">
                            <div class="subsc_sec">
                                <h6 class="hdttle">Subscribe to our Newsletter</h6>
                                <p>Keep yourself updated by connecting with us !</p>
                                <div class="signfomup">

                                    <div class="formbtmns">
                                        <input type="text" name="email" id="exampleInputEmail1"
                                            class="form-control ft_subscribe" value="" required="required"
                                            placeholder="Enter Your Email">
                                    </div>
                                    <span class="msgNewsLetterSubsc12"><b>Please Provide Valid Email ID</b></span> <span
                                        class="succNewsLetterSubsc"><b>Thank you for your subscription!We will keep you
                                            informed of our activities.</b></span> <span
                                        class="msgNewsLetterSubsc"><b>You are already subscribed to Newsletter
                                            feed.</b></span> <span class="msgNewsLetterSubsc1"><b>Activated to
                                            Newsletter feed.Thank you</b></span>
                                </div>
                                <div class="form_new"><button type="button" class="btn btn_sub subsbtm"
                                        onclick="check_newsletter()"><i class="fa fa-paper-plane"></i></button></div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="clearfix"></div>
            </div>
        </div>




        <!-- Footer Start -->
        <footer>
            <div class="fstfooter">
                <div class="">
                    <div class="reftr">
                        <div class="container">
                            <div class="clearfix"></div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 col-sm-3 nopad fulnine">
                                    <div class="col-md-12 nopad">
                                        <a href="<?= base_url() ?>">
                                            <img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>"
                                                alt="Book a tour to India" class="text-center foot_logo" />
                                        </a>
                                        <?php $query = "SELECT * FROM contact_us_details";
                                            $get_contact_data= $this->db->query($query)->result();?>
                                        <div class="foot_address">


                                        </div>
                                        <ul class="frtbest">
 
<?php

 $temp = $this->custom_db->single_table_records('social_links','*',array('status' => 1));
if($_SERVER['REMOTE_ADDR']=="106.197.144.126")
{
  //  debug( $temp);die;
}

?>
                                            <li class="frteli-fa"><a href="<?php echo $temp['data'][0]['url_link'] ?>" target="_blank"><i class="fab fa-facebook"></i></a></li>
                                            <li class="frteli-fa"><a href="<?php echo $temp['data'][3]['url_link'] ?>" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                                            <li class="frteli-fa"><a href="<?php echo $temp['data'][2]['url_link'] ?>" target="_blank"><i class="fab fa-youtube"></i></a></li>
                                            <li class="frteli-fa"><a href="<?php echo $temp['data'][1]['url_link'] ?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                        </ul>

                                    </div>
                                </div>
                                <div class="col-md-2 nopad">
                                    <div class="frtbest">
                                        <ul id="accordionfot2" class="accordionftr">
                                            <h4 class="ftrhd arimo ">Connect With Us</h4>
                                            <ul class="submenuftr1">
                                                <li class="frteli"><a href="https://www.google.com/maps/place/Travel+Free+Travels,+Sumangal+Niwas,+Opp.+Prime+Minister+Qarter,+Gate.1,+Pandol+Marga+03,+Kathmandu+44600/@27.7274148,85.3278573,17z/data=!4m6!3m5!1s0x39eb1943abdeb8d3:0xd519207a9c9fa312!8m2!3d27.7274148!4d85.3278573!16s%2Fg%2F11gxnpn0cr"> Address: Sumangal Residence, Opp. Prime Minister's Quarter, Gate No. 1 Baluwatar, Kathmandu-03.</a></li>
                                                <li class="frteli"><a  href="mailto:info@travelfreetravels.com"> Email: <span style=" text-transform:lowercase !important;">info@travelfreetravels.com</span></a>
                                                </li>
                                                <li class="frteli"><a href="tel:+977-1-5365553"> Phone: +977-1-5365553/54</a></li>
                                                <li class="frteli"><a href="tel: +977-9860000111"> Mobile:
                                                        +977-9860000111</a>



                                            </ul>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-2 nopad">
                                            <div class="frtbest">
                                                <ul id="accordionfot1" class="accordionftr">
                                                    <h4 class="ftrhd arimo ">Quick Links</h4>
                                                    <ul class="submenuftr1">
                                                        <?php
                                                foreach ($master_module_list as $k => $v) {

                                                    if (in_array($k, $active_domain_modules)) {
                                                              
                                                        ?>
                                                        <li class="frteli">


                                                            <a href="<?php echo base_url().'/' ?><?php echo ($v) ?>">

                                                                <?php echo ucfirst($v); ?></a>
                                                        </li>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                                    </ul>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-3 nopad">
                                            <div class="frtbest">
                                                <ul id="accordionfot2" class="accordionftr">
                                                    <h4 class="ftrhd arimo ">Traveler Tools</h4>
                                                    <ul class="submenuftr1">
                                                              <?php
                                                    $cond = array(
                                                        'page_status' => ACTIVE
                                                    );
                                                    $cms_data = $this->custom_db->single_table_records('cms_pages', '', $cond);
                                                   
                                                    foreach ($cms_data ['data'] as $keys => $values) {
                                                        if ($values['page_position']=='travellertools') {
                                                            if ($values['page_url']=='') {
                                                            echo '<li class="frteli"><a href="' . base_url() . $values ['page_label'] . '">' . $values ['page_title'] . ' <br> </a></li>';
                                                        }
                                                        else
                                                        {
                                                               echo '<li class="frteli"><a href="'. $values ['page_url'].'">' . $values ['page_title'] . ' <br> </a></li>';
                                                        }
                                                    }
                                                }

                                                ?>
                                                       <!-- <li class="frteli"><a href="https://www.travelfreetravels.com/promo-code">Gift Cards</a></li>
                                                        <li class="frteli"><a href="https://www.travelfreetravels.com/rewardpoints">TFT Miles</a></li>
                                                      
                                                        <li class="frteli"><a href="https://www.travelfreetravels.com/specialassistance">Special Assistance</a></li>-->
                                            
                                                    </ul>
                                                </ul>
                                            </div>
                                        </div>
                                   

                                        <div class="col-md-2 nopad">
                                            <div class="frtbest">
                                                <ul id="accordionfot2" class="accordionftr">
                                                    <h4 class="ftrhd arimo ">TFT</h4>
                                                    <ul class="submenuftr1">
                                                          <?php
                                                    $cond = array(
                                                        'page_status' => ACTIVE
                                                    );
                                                    $cms_data = $this->custom_db->single_table_records('cms_pages', '', $cond);
                                                   
                                                    foreach ($cms_data ['data'] as $keys => $values) {
                                                        if ($values['page_position']=='tft') {
                                                            if ($values['page_url']=='') {
                                                            echo '<li class="frteli"><a href="' . base_url() . $values ['page_label'] . '">' . $values ['page_title'] . ' <br> </a></li>';
                                                        }
                                                        else
                                                        {
                                                               echo '<li class="frteli"><a href="'. $values ['page_url'].'">' . $values ['page_title'] . ' <br> </a></li>';
                                                        }
                                                    }
                                                }

                                                ?>
                                                      <!--  <li class="frteli"><a href="https://www.travelfreetravels.com/about-us">About Us</a></li>
                                                        <li class="frteli"><a href="#">Press Room</a></li>
                                                        <li class="frteli"><a href="#">Careers</a></li>
                                                        <li class="frteli"><a href="#">Social Responsibility</a></li>
                                                        <li class="frteli"><a href="#">Affliate Program</a></li>
                                                        <li class="frteli"><a href="#">Client Testimonial</a></li>
                                                        <li class="frteli"><a href="#">Agent Portal</a></li>
                                                        <li class="frteli"><a href="#">Corporate Club</a></li>
                                                        <li class="frteli"><a href="#">Newsletter</a></li>-->
                                                    </ul>
                                                </ul>
                                            </div>
                                        </div>
                                       <div class="col-md-2 nopad">
                                            <div class="frtbest">
                                                <ul id="accordionfot" class="accordionftr">
                                                    <h4 class="ftrhd arimo ">Legals</h4>
                                                    <ul class="submenuftr1">
                                                        <?php
                                                    $cond = array(
                                                        'page_status' => ACTIVE
                                                    );
                                                    $cms_data = $this->custom_db->single_table_records('cms_pages', '', $cond);
                                                   
                                                    foreach ($cms_data ['data'] as $keys => $values) {
                                                        if ($values['page_position']=='legals') {
                                                            if ($values['page_url']=='') {
                                                            echo '<li class="frteli"><a href="' . base_url() . $values ['page_label'] . '">' . $values ['page_title'] . ' <br> </a></li>';
                                                        }
                                                        else
                                                        {
                                                               echo '<li class="frteli"><a href="'. $values ['page_url'].'">' . $values ['page_title'] . ' <br> </a></li>';
                                                        }
                                                    }
                                                }

                                                ?>

                                                   
                                                    </ul>


                                                </ul>

                                            </div>
                                        </div>
                                        <div class="col-md-3 helplin"> 
                                            <div class="frtbest">
                                                <ul id="accordionfot2" class="accordionftr">
                                                    <h4 class="ftrhd arimo ">Helpline</h4>
                                                    <ul class="submenuftr1">
                                                           <?php
                                                    $cond = array(
                                                        'page_status' => ACTIVE
                                                    );
                                                    $cms_data = $this->custom_db->single_table_records('cms_pages', '', $cond);
                                                   
                                                    foreach ($cms_data ['data'] as $keys => $values) {
                                                        if ($values['page_position']=='helpline') {
                                                            if ($values['page_url']=='') {
                                                            echo '<li class="frteli"><a href="' . base_url() . $values ['page_label'] . '">' . $values ['page_title'] . ' <br> </a></li>';
                                                        }
                                                        else
                                                        {
                                                               echo '<li class="frteli"><a href="'. $values ['page_url'].'">' . $values ['page_title'] . ' <br> </a></li>';
                                                        }
                                                    }
                                                }

                                                ?>
                                                       <!-- <li class="frteli"><a href="<?php echo base_url();?>general/customersupport">Customer service </a></li>
                                                        <li class="frteli"><a href="<?php echo base_url();?>general/checkmybooking">Checkmybooking</a></li>
                                                        <li class="frteli"><a href="<?php echo base_url();?>general/refundinfo">Refund processing</a></li>
                                                        <li class="frteli"><a href="<?php echo base_url();?>general/rescheduleflights">Reschedules flight</a></li>
                                                        <li class="frteli"><a href="<?php echo base_url();?>general/canceltime">Cancel flight </a></li>
                                                        <li class="frteli"><a href="<?php echo base_url();?>general/reviews">Rewards claim/ Use TFT coupon</a></li>
                                                       
                                                         <li class="frteli"><a href="https://www.travelfreetravels.com/faq">FAQ's</a></li>-->
                                                    </ul>
                                                </ul>
                                            </div>
                                        </div>

                                    
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>

                <div class="btmfooternw">
                    <div class="container">
                        <div class="row footer-logo">
                            <div class="col-md-6">
                                <div class="order1">
                                    <img src="<?php echo base_url()?>extras/system/template_list/template_v3/images/order1.png"
                                        alt="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="order2">
                                    <img src="<?php echo base_url()?>extras/system/template_list/template_v3/images/order2.png"
                                        alt="">
                                </div>
                            </div>
                        </div>

                        <div class="copyrit">
                            Copyright &copy; <?php echo date('Y') ?><a href="index.php"> Travel Free Travels</a>
                            All rights reserved.
                        </div>
                    </div>
                </div>
                <div class="masco_animate">
                    <!-- <img src="extras/system/template_list/template_v3/images/tftanimation1.png"> -->
                </div>
            </div>
            <div class="clearfix"></div>
        </footer>

        <!-- Footer End -->
    </div>
    <?php
        // Dynamic Loading of all the files needed in the application
        Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/datepicker.js'), 'defer' => 'defer');
        Provab_Page_Loader::load_core_resource_files();
        $GLOBALS ['CI']->current_page->footer_js_resource();
        echo $GLOBALS ['CI']->current_page->js();
        ?>
    <!--<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('modernizr.custom.js'); ?>" defer></script>-->


<script>

</script>

    <script>
    $(document).ready(function() {


        // Set the date we're counting down to
var countDownDate = new Date("Nov 13, 2022  12:00:00").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id="demo"
  document.getElementById("launchtimer").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("launchtimer").innerHTML = "EXPIRED";
  }
}, 1000);
        $("#id").attr("content", "width=device-width, initial-scale=1")
    })
    var accessToken = "8484e898405d4becb83c0091285f68a2";
    var baseUrl = "https://api.api.ai/v1/";

    $(document).ready(function() {
        $("#from_city").keypress(function(event) {
            if (event.which == 13) {
                event.preventDefault();
                send();
            }
        });
        $("#rec").click(function(event) {
            switchRecognition();
            // setInput();
        });
    });
    var recognition;

    function startRecognition() {
        recognition = new webkitSpeechRecognition();
        recognition.onstart = function(event) {
            updateRec();
        };
        recognition.onresult = function(event) {
            var text = "";
            for (var i = event.resultIndex; i < event.results.length; ++i) {
                text += event.results[i][0].transcript;
            }
            setInput(text);
            stopRecognition();
        };
        recognition.onend = function() {
            stopRecognition();
        };
        recognition.lang = "en-US";
        recognition.start();
    }

    function stopRecognition() {
        if (recognition) {
            recognition.stop();
            recognition = null;
        }
        updateRec();
    }

    function switchRecognition() {
        if (recognition) {
            stopRecognition();
        } else {
            startRecognition();
        }
    }

    function setInput(text) {
        $("#input_speech").val(text);
        var from_city = text.split(" to");
        if (typeof(from_city[1]) != 'undefined' && from_city[1].indexOf(' on') >= -1) {
            var to_city = from_city[1].split(" on");
        } else {
            var to_city = [from_city[1], ''];
        }
        if (typeof(to_city[1]) != 'undefined' && to_city[1].indexOf(' for') != -1) {
            var ddate = to_city[1].split(" for");
        } else {

            if (typeof(to_city[1]) != 'undefined') {
                var removed_space_date = to_city[1].trim();

                var new_date = removed_space_date.split(" ");

                var ddate = [new_date[1] + ' ' + new_date[0]];

            } else {
                var d = new Date();
                var strDate = (d.getDate() + 1) + "-" + (d.getMonth() + 1);

                var ddate = [strDate, ''];
            }

        }

        if (typeof(ddate[1]) != 'undefined' && ddate[1].indexOf(' adult') != -1) {
            var adult_value = ddate[1].split("adult");
        } else {
            var adult_value = ["1"];
        }

        if (typeof(adult_value[1]) != 'undefined' && adult_value[1].indexOf(' child') != -1) {
            var child_value = adult_value[1].split(" child");
        } else {
            var child_value = ["0"];
        }
        if (typeof(child_value[1]) != 'undefined' && child_value[1].indexOf(' infant') != -1) {
            var infant_value = child_value[1].split(" infant");
        } else {
            var infant_value = ["0"];
        }
        if ($.trim(to_city[0]) != '' && $.trim(from_city[0]) != '') {
            var from_city_value = update_city($.trim(from_city[0]), 'from', 'from_loc_id_val');
            var to_city_value = update_city($.trim(to_city[0]), 'to', 'to_loc_id_val');

            $("#flight_datepicker1").val(ddate[0] + "-2018");
            $("#OWT_adult").val(adult_value[0]);
            $("#OWT_child").val(child_value[0]);
            $("#OWT_infant").val(infant_value[0]);


            setTimeout(function() {
                $("#flight-form-submit").click();
            }, 5000);
        } else {
            alert("Please Try agin with proper input data");
        }
    }

    function updateRec() {
        $("#rec").html(recognition ?
            "<img style='width: 14px; padding-top: 2px;' src='<?php echo $GLOBALS['CI']->template->template_images('mike_red.png'); ?>' alt='Book a tour to India'>" :
            "<img style='width: 14px; padding-top: 2px;' src='<?php echo $GLOBALS['CI']->template->template_images('mike.png'); ?>' alt='Book a tour to India'>"
        );
    }

    function update_city(input_data, id, val) {
        var search_data = input_data.replace(" ", "_");

        $.ajax({
            type: "POST",
            url: 'https://localhost/travelomatix/ajax/get_airport_code_list_for_voice_speach/' + search_data,
            success: function(data) {
                var data_arrange = data.split("|");

                $("#" + id).val($.trim(data_arrange[0]));
                $("#" + val).val($.trim(data_arrange[1]));
            },
        });
    }

    function send() {
        var text = $("#input").val();
        $.ajax({
            type: "POST",
            url: baseUrl + "query?v=20150910",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                "Authorization": "Bearer " + accessToken
            },
            data: JSON.stringify({
                query: text,
                lang: "en",
                sessionId: "somerandomthing"
            }),

            success: function(data) {
                setResponse(JSON.stringify(data, undefined, 2));
            },
            error: function() {
                setResponse("Internal Server Error");
            }
        });
        setResponse("Loading...");
    }

    function setResponse(val) {
        $("#response").text(val);
    }
    $(document).ready(function() {
        $('#keep_mail_sub').click(function(e) {
            $("#keep_mail_sub_error").text('');
            $("#sub_msg").text('');
            // alert();
            e.preventDefault();
            var input_text = $("#email_id").val();
            var mailformat = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/.test(
                input_text);
            if (!mailformat) {
                $("#keep_mail_sub_error").text('Enter Valid email address!');
                return false;
            }
            $.ajax({
                url: app_base_url + 'general/save_keep_email',
                type: 'POST',
                data: {
                    'email_id': input_text
                },
                success: function(msg) {
                    if (msg.status == true) {
                        $("#sub_msg").css("color", "green");
                        $('#sub_msg').text(msg.message);
                    } else {
                        $("#sub_msg").css("color", "red");
                        $('#sub_msg').text(msg.message);
                    }
                },
                error: function() {}
            });

        });
    })
    // $(window).scroll(function() {
    //     if ($("input").is(":focus")) {
    //         $('.hasDatepicker').blur();
    //     }
    // });
    </script>
    
    <script>
    $(document).ready(function() {
        $('body').on('dragstart', function() {
            return false;
        });
    });
    </script> 

    <script>
    $(document).ready(function() {
        $('.customer-logos').slick({
            slidesToShow: 6,
            autoplay: true,
            autoplaySpeed: 0,
            speed: 1000,
            cssEase: "linear",
            arrows: false,
            dots: false,
            pauseOnHover: false,
            responsive: [{
                breakpoint: 768,
                settings: {
                    slidesToShow: 4
                }
            }, {
                breakpoint: 520,
                settings: {
                    slidesToShow: 3
                }
            }]
        });
    });
    </script>
    <!-- Start of LiveChat (www.livechatinc.com) code -->
    <script>
    // window.__lc = window.__lc || {};
    // window.__lc.license = 12842352;
    // ;(function(n,t,c){function i(n){return e._h?e._h.apply(null,n):e._q.push(n)}var e={_q:[],_h:null,_v:"2.0",on:function(){i(["on",c.call(arguments)])},once:function(){i(["once",c.call(arguments)])},off:function(){i(["off",c.call(arguments)])},get:function(){if(!e._h)throw new Error("[LiveChatWidget] You can't use getters before load.");return i(["get",c.call(arguments)])},call:function(){i(["call",c.call(arguments)])},init:function(){var n=t.createElement("script");n.async=!0,n.type="text/javascript",n.src="https://cdn.livechatinc.com/tracking.js",t.head.appendChild(n)}};!n.__lc.asyncInit&&e.init(),n.LiveChatWidget=n.LiveChatWidget||e}(window,document,[].slice))
    </script>
    <!-- <noscript><a href="https://www.livechatinc.com/chat-with/12842352/" rel="">Chat with us</a>, powered by <a href="https://www.livechatinc.com/?welcome" rel="noopener " target="_blank">LiveChat</a></noscript> -->
    <!-- noscript><a href="https://www.livechatinc.com/chat-with/12842352/" rel="nofollow">Chat with us</a>, powered by <a href="https://www.livechatinc.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a></noscript>
 -->
    <!-- End of LiveChat code -->

</body>

</html>
<?php
  if($_GET['refercode']!="")
  {
?>

<script type="text/javascript">
$(window).load(function() {
    $('#show_log').modal('show');
    $(".open_register").trigger('click');
});
</script>
<?php
}
?>
<script>
$(document).ready(function() {
    $("#credential_picker_container").hide();
    $('#keep_mail_sub').click(function(e) {
        $("#keep_mail_sub_error").text('');
        e.preventDefault();
        var input_text = $("#email_id").val();
        var mailformat = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/.test(input_text);
        if (!mailformat) {
            $("#keep_mail_sub_error").text('Enter Valid email address!');
            return false;
        }
        $.ajax({
            url: app_base_url + 'general/save_keep_email',
            type: 'POST',
            data: {
                'email_id': input_text
            },
            success: function(msg) {

                if (msg.status == true) {
                    $("#sub_msg").css("color", "green");
                    $('#sub_msg').text(msg.message);
                } else {
                    $("#sub_msg").css("color", "red");
                    $('#sub_msg').text(msg.message);
                }
            },
            error: function() {}
        });
    });
})
</script>
<!-- <script>
$(window).scroll(function() {
    if ($(window).scrollTop() >= 300) {
        $('.secndblak').addClass('fixed-second');
    } else {
        $('.secndblak').removeClass('fixed-second');
    }
});
</script> -->
<<!-- script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/61934a936bb0760a4942c8b7/1fkjlml14';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
 -->