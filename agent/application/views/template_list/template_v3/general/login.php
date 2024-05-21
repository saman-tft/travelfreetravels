<style>
/* Slider */
.topssecc {
    background: #fff none repeat scroll 0 0;
    float: left;
    height: 100px !important;
    position: relative;
    width: 100%;
    z-index: 100000;
}

.ful_logoo {
    padding: 5px 0px !important;
    width: 100% !important;
    height: auto;
    background: #fff !important;
    border-radius: 10px;
}

.ritsudee {
    float: right;
    padding: 25px 35px;
    width: auto;
}

.slick-slide {
    margin: 0px 20px;
}

.slick-slide img {
    width: 177px !important;
}

.slick-slider {
    position: relative;
    display: block;
    box-sizing: border-box;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    -webkit-touch-callout: none;
    -khtml-user-select: none;
    -ms-touch-action: pan-y;
    touch-action: pan-y;
    -webkit-tap-highlight-color: transparent;
}

.slick-list {
    position: relative;
    display: block;
    overflow: hidden;
    margin: 0;
    padding: 0;
}

.slick-list:focus {
    outline: none;
}

.slick-list.dragging {
    cursor: pointer;
    cursor: hand;
}

.slick-slider .slick-track,
.slick-slider .slick-list {
    -webkit-transform: translate3d(0, 0, 0);
    -moz-transform: translate3d(0, 0, 0);
    -ms-transform: translate3d(0, 0, 0);
    -o-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
}

.slick-track {
    position: relative;
    top: 0;
    left: 0;
    display: block;
}

.slick-track:before,
.slick-track:after {
    display: table;
    content: '';
}

.slick-track:after {
    clear: both;
}

.slick-loading .slick-track {
    visibility: hidden;
}

.slick-slide {
    display: none;
    float: left;
    height: 100%;
    min-height: 1px;
}

[dir='rtl'] .slick-slide {
    float: right;
}

.slick-slide img {
    display: block;
    border: 3px solid #ccc;
    min-height: 100px !important;
    height: 100px !important;
}

.slick-slide.slick-loading img {
    display: none;
}

.slick-slide.dragging img {
    pointer-events: none;
}

.slick-initialized .slick-slide {
    display: block;
}

.slick-loading .slick-slide {
    visibility: hidden;
}

.slick-vertical .slick-slide {
    display: block;
    height: auto;
    border: 1px solid transparent;
}

.slick-arrow.slick-hidden {
    display: none;
}

.marquee {
    margin: 0 auto;
    white-space: nowrap;
    overflow: hidden;
    position: absolute;
}

.marquee span {
    display: inline-block;
    padding-left: 100%;
    animation: marquee 5s linear infinite;
}

.marquee2 span {
    animation-delay: 2.5s;
}

.pagehding {
    font-family: 'Myriad Pro Bold' !important;
}

@keyframes marquee {
    0% {
        transform: translate(0, 0);
    }

    100% {
        transform: translate(-100%, 0);
    }
}
</style>



<?php
if (isset($login) == false || is_object($login) == false) {
    $login = new Provab_Page_Loader('login');
}
$login_auth_loading_image  = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v3.gif').'" alt="please wait"/></div>';
?>
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('agent_index.css');?>" rel="stylesheet" defer>
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('bootstrap-toastr/toastr.min.css');?>"
    rel="stylesheet" defer>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('bootstrap-toastr/toastr.min.js'); ?>"></script>


<link href="https://fonts.googleapis.com/css?family=Lato|Source+Sans+Pro" rel="stylesheet">
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css');?>" rel="stylesheet" defer>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
<script src="https://cdn.ywxi.net/js/1.js"></script>

<div class="topform_main">
    <div class="topform">
        <!--header-->
        <header>
            <div class="clearfix"></div>
            <div class="topssec">
                <div class="container nopad">
                    <div class="headdetails">
                        <div class="headleft clearfix">
                            <div class="bars_menu fa fa-bars menu_brgr"></div>
                     
                    <!--changes added a new logo-->
                            <!--<a class="logo" href="<?=base_url()?>">-->
                                <!--<img class="tab_logo" src="/alkhaleej_tours/extras/custom/TMX1512291534825461/images/TMX1512291534825461logo-loginpg.png" alt="Logo"> -->
                              
                              
                              
                              
                            <!--    <img class="ful_logoo"-->
                            <!--        src="https://travelfreetravels.com/extras/custom/TMX6244821650276433/images/TMX3644721637051232logo-loginpg.png"-->
                            <!--        alt="Logo">-->
                            <!--</a>-->
                            <a class="logo" style="margin-top:-3em; overflow:clip;" href="<?= base_url() ?>">

<div class="image__container" style=" max-height:100px;overflow:hidden;">


    <img style="mix-blend-mode:multiply;" src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>" alt="Book a tour to India" class="text-center foot_logo" />
</div>
                            </a>
                        </div>
                        <div class="headright clearfix">
                            <div class="menuandall">
                                <div class="sepmenus">
                                    <!--<ul class="exploreall">
                    <?php
                      $burl=base_url();
                      $burl1=str_replace('agent/','',$burl);

                    ?>
                    <li class="active">
                      <a href="<?=$burl1?>about-us" traget="_blank">                        <span class="sprte cmnexplor "></span>                        <strong>ABOUT US</strong>                        </a>                      
                    </li>
                    <li class="">
                      <a href="<?=$burl1?>faq" traget="_blank">                        <span class="sprte cmnexplor "></span>                        <strong>FAQ</strong>                        </a>                      
                    </li>
                   
                  </ul>-->
                                </div>
                                <div class="ritsude">
                                    <!---account start-->
                                    <div class="sidebtn">
                                        <!--  <a class="topa logindown hide" data-toggle="modal" data-target="#show_log">
                      <div class="reglog hide">
                        <div class="userorlogin">My Account 
                        </div>
                        <div class="userimage">                                                        <img src="/ocio/extras/system/template_list/template_v3/images/user.png" alt="">                          </div>
                      </div>
                    </a> -->
                                        <div class="agentlogin">
                                            <a class="phonenum"><i class=" fa fa-phone fa-rotate-90"></i>+977-9860000111</a>
                                            <a class="btn btn-secondary" type="button"
                                                href="<?=base_url()?>index.php/user/agentRegister">
                                                Register
                                                <div class="userimage hide"> <img
                                                        src="<?=$GLOBALS['CI']->template->template_images('user.png')?>"
                                                        alt=""> </div>
                                            </a>
                                            <!-- <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Partner Login</a>
                        <a class="dropdown-item" href="#">Customer Login</a>
                      </div>-->
                                        </div>
                                    </div>
                                    <!---account end-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!--header-->


        <div class="headagent">
            <div class="container">
                <div class="leftul">
                    <?php 
            if(!empty($page_content['data'])) { 
                foreach ($page_content['data'] as $k => $v) {
                    if(strtolower(str_replace(' ', '', $v['page_title'])) == 'aboutus'){
            ?>
                    <a class="myangr"
                        href="<?php echo base_url () . 'index.php/general/cms/' .$v['page_seo_keyword'] ; ?>"><?=@$v['page_title']?></a>
                    <?php 
                break;
                        } else {
                            continue;
                        }
                    }
                } 
            ?>

                </div>
                <div class="rightsin">
                    <a class="myangr" href="<?=base_url().'index.php/user/agentRegister' ?>">Haven't Registered Yet?</a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="container">
            <div class="headinglog">
                <div class="hmembr fr_mobl"> Online Reservation System </div>
                <div class="lorentt fr_mobl">Award winning B2B platform for travel agents and start-up travel companies.
                </div>
            </div>

            <div class="loginbox">
                <!--<div class="col-sm-7 col-xs-7 nopad">
        <div class="">
         <div class="hmembr fr_mobl"> Online  <br/> Reservation <br/>System </div>
          <div class="lorentt fr_mobl">Award winning B2B platform for travel agents and start-up travel companies.</div>
        </div>
      </div>-->
                <div class="col-sm-12 col-xs-12 nopad">
                    <?php 
      $class ='';
      $otp_class = 'hide';
      $OTP_status = $this->session->userdata('OTP_status');
      if(isset($OTP_status) && $OTP_status == 'not verified'){
        $class= 'hide';
        $otp_class = '';
      }
      //echo $this->session->userdata('OTP_status');exit;?>
                    <div class="innersecing <?php echo $class; ?>">
                        <div class="signhes"><i class="far fa-power-off"></i> Sign in to Continue </div>
                        <?php $name = 'login' ?>
                        <form name="<?=$name?>" autocomplete="off"
                            action="<?php echo base_url(); ?>index.php/general/index" method="POST"
                            enctype="multipart/form-data" id="login" role="form" class="form-horizontal">
                            <?php $FID = $GLOBALS['CI']->encrypt->encode($name); ?>
                            <input type="hidden" name="FID" value="<?=$FID?>">
                            <div class="inputsing"> <span class="sprite userimg"></span>
                                <!-- <input type="text" class="mylogbox" placeholder="Username" /> -->
                                <input value="" name="email" dt="PROVAB_SOLID_V80" required="" type="email"
                                    placeholder="Username" class="mylogbox login-ip email _guest_validate_field"
                                    id="email" data-container="body" data-toggle="popover" data-original-title=""
                                    data-placement="bottom" data-trigger="hover focus"
                                    data-content="Username Ex: john@bookingsdaily.com">
                            </div>
                            <div class="inputsing"> <span class="sprite lockimg"></span>
                                <!-- <input type="text" class="mylogbox" placeholder="Password" /> -->
                                <input value="" name="password" dt="PROVAB_SOLID_V45" required="" type="password"
                                    placeholder="Password" class="login-ip password mylogbox _guest_validate_field"
                                    id="password" data-container="body" data-toggle="popover" data-original-title=""
                                    data-placement="bottom" data-trigger="hover focus"
                                    data-content="Password Ex: A3#FD*3377^*">
                            </div>

                            <!-- <button class="logbtn">Login</button> -->
                            <button id="login_submit" class="logbtn">Login</button>
                            <div id="login_auth_loading_image" style="display: none">
                                <?=$login_auth_loading_image?>
                            </div>
                            <div id="login-status-wrapper" class="alert alert-danger" style="display: none"></div>
                            <div class="signhes fortlog">
                                <?php echo $GLOBALS['CI']->template->isolated_view('general/forgot-password');?></div>
                        </form>
                        <!--<div class="signhes"> Don’t have an account ? <a href="<?=base_url().'index.php/user/agentRegister' ?>">Sign up</a></div>-->


                    </div>
                    <div class="innersecing <?php echo $otp_class; ?>" id="otp_div">
                  <!-- changes added new style for back button to actually show -->
                  <a href="#" class="gobacklink" style="z-index:1;">Back</a>
                        <?php $name = 'otp' ?>
                        <form name="<?=$name?>" autocomplete="off" action="" method="POST" enctype="multipart/form-data"
                            id="login" role="form" class="form-horizontal">
                            <div class="inputsing">
                                <!-- <input type="text" class="mylogbox" placeholder="Password" /> -->
                                <input value="" name="opt" required="" type="text" placeholder="Enter OTP"
                                    class="login-ip mylogbox _guest_validate_field" id="otp">
                            </div>
                            <button id="opt_submit" class="logbtn">Login</button>
                            <div id="login-otp-wrapper" class="alert alert-danger" style="display: none"></div>
                        </form>

                    </div>
                </div>
            </div>



        </div>



        <section class="target">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="content-target">
                            <h3>Take your targets to the sky</h3>
                            <p>Travel Free Travels features special discounts, extra baggage, free ticket changes and
                                more. Get ready to discover the benefits of becoming a member of the Travel Free Travels
                                Corporate Club that brings thousands of corporate companies focusing on development
                                together and start enjoying them without any commitments.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="company-grey">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="content-target text-left">
                            <h3>Move your company forward</h3>
                            <p>Add prestige to your travels with Travel Free Travels Corporate Club, which combines Travel Free Travels privileges with your corporate company.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                         <img src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v3/images/company-one.jpg" width="100%" alt="">
                    </div>

                </div>
            </div>
        </section>

        <section class="company">
            <div class="container">
                <div class="row">
                <div class="col-md-6">
                         <img src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v3/images/special-offer.jpg" width="100%" alt="">
                    </div>

                    <div class="col-md-6">
                        <div class="content-target text-left">
                            <h3>Special discounts</h3>
                            <p>As a Travel Free Travels Corporate Club member, you can purchase domestic and international tickets with special discounts.</p>
                        </div>
                    </div>
                  
                </div>
            </div>
        </section>

        <section class="company-grey">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="content-target text-left">
                            <h3>Corporate Help Desk</h3>
                            <p>As a Travel Free Travels Corporate Club member, you can forward requests and questions to our after-sales support service at the Corporate Help Desk.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                         <img src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v3/images/company-two.jpg" width="100%" alt="">
                    </div>

                </div>
            </div>
        </section>


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

                                            <li class="frteli-fa"><i class="fab fa-facebook"></i> </li>
                                            <li class="frteli-fa"><i class="fab fa-linkedin"></i> </li>
                                            <li class="frteli-fa"><i class="fab fa-youtube"></i> </li>
                                            <li class="frteli-fa"><i class="fab fa-twitter"></i> </li>
                                        </ul>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="frtbest">
                                        <ul id="accordionfot2" class="accordionftr">
                                            <h4 class="ftrhd arimo ">Connect With Us</h4>
                                            <ul class="submenuftr1">
                                                <li class="frteli"><a href="#"> Address: Sumangal Residence, Opp. Prime Minister's Quarter, Gate No. 1
Baluwatar, Kathmandu-03.2</a></li>
                                                <li class="frteli"><a href="#"> Email: info@travelfreetravels.com</a>
                                                </li>
                                                <li class="frteli"><a href="#"> Phone: +977-1-5365553/54</a></li>
                                                <li class="frteli"><a href="tel: +977-9860000111"> Phone:
                                                        +977-9860000111</a> <i class="fab fa-whatsapp"></i> <i
                                                        class="fab fa-viber"></i> </li>



                                            </ul>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-3">
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
                                        <div class="col-md-3">
                                            <div class="frtbest">
                                                <ul id="accordionfot2" class="accordionftr">
                                                    <h4 class="ftrhd arimo ">Traveler Tools</h4>
                                                    <ul class="submenuftr1">
                                                        <li class="frteli"><a href="#">Gift Cards</a></li>
                                                        <li class="frteli"><a href="#">TFT Miles</a></li>
                                                        <li class="frteli"><a href="#">Check My Booking</a></li>
                                                        <li class="frteli"><a href="#">Customer Support</a></li>
                                                        <li class="frteli"><a href="#">Online Check-In</a></li>
                                                        <li class="frteli"><a href="#">Airline Baggage Fees</a></li>
                                                        <li class="frteli"><a href="#">Travel Guides</a></li>
                                                        <li class="frteli"><a href="#">Check Flight Status</a></li>
                                                        <li class="frteli"><a href="#">FAQ's</a></li>
                                                        <li class="frteli"><a href="#">Special Assistance</a></li>
                                                        <li class="frteli"><a href="#">Travel Blog</a></li>
                                                        <li class="frteli"><a href="#">Local Guides</a></li>
                                                    </ul>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="frtbest">
                                                <ul id="accordionfot2" class="accordionftr">
                                                    <h4 class="ftrhd arimo ">TFT</h4>
                                                    <ul class="submenuftr1">
                                                        <li class="frteli"><a href="#">About Us</a></li>
                                                        <li class="frteli"><a href="#">Press Room</a></li>
                                                        <li class="frteli"><a href="#">Careers</a></li>
                                                        <li class="frteli"><a href="#">Social Responsibility</a></li>
                                                        <li class="frteli"><a href="#">Affliate Program</a></li>
                                                        <li class="frteli"><a href="#">Client Testimonial</a></li>
                                                        <li class="frteli"><a href="#">Agent Portal</a></li>
                                                        <li class="frteli"><a href="#">Corporate Club</a></li>
                                                        <li class="frteli"><a href="#">Newsletter</a></li>
                                                    </ul>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="frtbest">
                                                <ul id="accordionfot" class="accordionftr">
                                                    <h4 class="ftrhd arimo ">Legals</h4>
                                                    <ul class="submenuftr">
                                                        <?php
                                                    $cond = array(
                                                        'page_status' => ACTIVE
                                                    );
                                                    $cms_data = $this->custom_db->single_table_records('cms_pages', '', $cond);
                                                   
                                                    foreach ($cms_data ['data'] as $keys => $values) {
                                                    if ($values['page_position']=='Right') {
                                                       if( $values ['page_label']!="terms-and-conditions-")
                                                       {
                                                        echo '<li class="frteli"><a href="' . base_url() . $values ['page_label'] . '">' . $values ['page_title'] . ' <br> </a></li>';
                                                       }
                                                       else
                                                       {
                                                            echo '<li class="frteli"><a href="https://www.travelfreetravels.com/'.$values ['page_label'] . '">' . $values ['page_title'] . ' <br> </a></li>';
                                                       }
                                                    }
                                                }

                                                ?>
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
                                    <img src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v3/images/order1.png"
                                        alt="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="order2">
                                    <img src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v3/images/order2.png"
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
            </div>
            <div class="clearfix"></div>
        </footer>

        <!-- Footer End -->




    </div>
</div>


<style type="text/css">
.invalid-ip {
    border: 1px solid #bf7070 !important;
}

.alert-danger {
    background-color: #dd4b39 !important;
}

.htldeals {
    background: none repeat scroll 0 0 #fff;
    float: left;
    padding: 30px 0px 30px 0px;
    width: 100%;
    margin: 30px 0px 0px 0px;
    border-radius: 8px;
}

.pagehdwrap {
    display: block;
    overflow: hidden;
    margin: 0 0 15px;
    position: relative;
}

.tophtls {
    display: block;
    margin: 0 0px;
    overflow: hidden;
}

.pagehding {
    color: #0e1938;
    padding: 10px 0px 10px 0px;
    display: block;
    font-size: 30px;
    font-weight: bold;
    margin: 0 0 0px;
    overflow: hidden;
    text-align: center;
    font-family: 'Proxima Nova Bold';
}

.tophtls .grid {
    margin: 0 0px;
}

.figure.effect-marley .figcaption {
    text-align: right;
    transition: all 400ms ease-in-out;
}

.grid .figure .figcaption,
.grid .figure .figcaption a {
    top: 0;
    left: 0;
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.grid .figure .figcaption a {
    z-index: 1000;
    display: block;
    text-align: center;
    padding: 15px 0px;
    /* background: transparent linear-gradient(
90deg
, #F29652 0%, #C33673 100%) 0% 0% no-repeat padding-box; */
    font-family: "Proxima Nova Bd";
    font-weight: bold;
    font-size: 14px;
    line-height: 24px;
    color: #484848;
    background: #efefef;
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
}

.grid .figure .figcaption a {
    top: 0;
    left: 0;
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
<script>
$(document).ready(function() {
    $('#opt_submit').on('click', function(e) {

        e.preventDefault();
        var _otp = $('#otp').val();
        if (_otp == '') {
            $('#login-otp-wrapper').text('Please Enter Valid Details To Continue!!!').show();
        } else {

            $.post(app_base_url + "index.php/auth/check_otp/", {
                otp: _otp
            }, function(response) {

                if (response.status) {
                    window.location.reload();
                } else {
                    $('#login-otp-wrapper').text(response.data).show();
                }

            });
        }
    });
    $('.gobacklink').on('click', function(e) {//changes removed this as it was useless
        // $.post(app_base_url + "index.php/auth/back_button/", function(response) {

        //     if (response.status) {
        //         window.location.reload();
        //     } else {
        //         $('#login-otp-wrapper').text(response.data).show();
        //     }

        // });
           //changes added this here for goback to actually work
            <?php echo $this->session->unset_userdata('OTP_status'); ?>
            window.location.reload();

    });

    $('#password').on('keypress', function(e) {
        if (e.which == 32) {
            return false;
        }
    });

});
</script>
<script>
$(document).ready(function() {
    var owlindex2 = $("#owl-demo2");

    owlindex2.owlCarousel({
        itemsCustom: [
            [0, 1],
            [450, 2],
            [551, 3],
            [700, 4],
            [1000, 5],
            [1200, 5],
            [1400, 5],
            [1600, 5]
        ],
        navigation: true,
        pagination: false

    });
});
</script>
<script type="text/javascript">
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
<script type="text/javascript">
$(document).ready(function() {
    $('#keep_mail_sub').click(function(e) {
        $("#keep_mail_sub_error").text('');
        $("#sub_msg").text('');
        // alert();
        e.preventDefault();

        var input_text = $("#email_id").val();
        // alert(input_text);
        var mailformat = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/.test(input_text);
        // var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

        if (!mailformat) {
            $("#keep_mail_sub_error").text('Enter Valid email address!');
            // alert("Enter Valid email address!");
            return false;
        }


        $.ajax({
            url: 'https://www.alkhaleejtours.com/index.php/general/save_keep_email',
            type: 'POST',
            data: {
                'email_id': input_text
            },
            success: function(msg) {

                if (msg.status == true) {
                    $("#sub_msg").css("color", "green");
                    $('#sub_msg').text(msg.message);
                    // $('#generaltourenquiry')[0].reset(); 
                } else {
                    $("#sub_msg").css("color", "red");
                    $('#sub_msg').text(msg.message);
                }
            },
            error: function() {}
        });
    });
});
</script>
<!-- <script type="text/javascript">
  $(document).ready(function(){
    $('.customer-logos').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 0,
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
</script> -->