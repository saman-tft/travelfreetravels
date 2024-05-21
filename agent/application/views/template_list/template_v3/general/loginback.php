<style>
	/* Slider */
  .topssecc{background: #fff none repeat scroll 0 0;
    float: left;
    height: 100px!important;
    position: relative;
    width: 100%;
    z-index: 100000;}
.ful_logoo {
    padding: 5px 0px!important;
    width: 100%!important;
    height: auto;
    background: #fff!important;
    border-radius:10px;}
    .ritsudee {
    float: right;
    padding: 25px 35px;
    width: auto;
}

.slick-slide {
    margin: 0px 20px;
}

.slick-slide img {
    width: 177px!important;
}

.slick-slider
{
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

.slick-list
{
    position: relative;
    display: block;
    overflow: hidden;
    margin: 0;
    padding: 0;
}
.slick-list:focus
{
    outline: none;
}
.slick-list.dragging
{
    cursor: pointer;
    cursor: hand;
}

.slick-slider .slick-track,
.slick-slider .slick-list
{
    -webkit-transform: translate3d(0, 0, 0);
       -moz-transform: translate3d(0, 0, 0);
        -ms-transform: translate3d(0, 0, 0);
         -o-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
}

.slick-track
{
    position: relative;
    top: 0;
    left: 0;
    display: block;
}
.slick-track:before,
.slick-track:after
{
    display: table;
    content: '';
}
.slick-track:after
{
    clear: both;
}
.slick-loading .slick-track
{
    visibility: hidden;
}

.slick-slide
{
    display: none;
    float: left;
    height: 100%;
    min-height: 1px;
}
[dir='rtl'] .slick-slide
{
    float: right;
}
.slick-slide img
{
    display: block;
    border: 3px solid #ccc;
    min-height: 100px!important;
    height: 100px!important;
}
.slick-slide.slick-loading img
{
    display: none;
}
.slick-slide.dragging img
{
    pointer-events: none;
}
.slick-initialized .slick-slide
{
    display: block;
}
.slick-loading .slick-slide
{
    visibility: hidden;
}
.slick-vertical .slick-slide
{
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
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('bootstrap-toastr/toastr.min.css');?>" rel="stylesheet" defer>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('bootstrap-toastr/toastr.min.js'); ?>"></script>
 
 
 <link href="https://fonts.googleapis.com/css?family=Lato|Source+Sans+Pro" rel="stylesheet">
 <link href="<?php echo $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css');?>" rel="stylesheet" defer>
 <script src="<?php echo $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'); ?>"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
 

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
              <a class="logo" href="<?=base_url()?>">               
				  <!--<img class="tab_logo" src="/alkhaleej_tours/extras/custom/TMX1512291534825461/images/TMX1512291534825461logo-loginpg.png" alt="Logo"> -->
              <img class="ful_logoo" src="/extras/custom/TMX9604421616070986/images/TMX1512291534825461logo-loginpg.png" alt="Logo">              </a>              
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
						<a class="phonenum"><i class=" fa fa-phone fa-rotate-90"></i>+44 2035149412</a>
                      <a class="btn btn-secondary" type="button" href="<?=base_url()?>index.php/user/agentRegister">
                        Register
                        <div class="userimage hide">                                                        <img src="<?=$GLOBALS['CI']->template->template_images('user.png')?>" alt="">                          </div>
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
            <a class="myangr" href="<?php echo base_url () . 'index.php/general/cms/' .$v['page_seo_keyword'] ; ?>" ><?=@$v['page_title']?></a>
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
            <a class="myangr" href="<?=base_url().'index.php/user/agentRegister' ?>" >Haven't Registered Yet?</a>
        </div>
    </div>
</div>
 <div class="clearfix"></div>
  <div class="container">
	  <div class="headinglog">
		   <div class="hmembr fr_mobl"> Online Reservation System </div>
          <div class="lorentt fr_mobl">Award winning B2B platform for travel agents and start-up travel companies.</div>
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
          <form name="<?=$name?>" autocomplete="off" action="<?php echo base_url(); ?>index.php/general/index" method="POST" enctype="multipart/form-data" id="login" role="form" class="form-horizontal">
          <?php $FID = $GLOBALS['CI']->encrypt->encode($name); ?>
          <input type="hidden" name="FID" value="<?=$FID?>">
          <div class="inputsing"> <span class="sprite userimg"></span>
            <!-- <input type="text" class="mylogbox" placeholder="Username" /> -->
            <input value="" name="email" dt="PROVAB_SOLID_V80" required="" type="email" placeholder="Username" class="mylogbox login-ip email _guest_validate_field" id="email" data-container="body" data-toggle="popover" data-original-title="" data-placement="bottom" data-trigger="hover focus" data-content="Username Ex: john@bookingsdaily.com">
          </div>
          <div class="inputsing"> <span class="sprite lockimg"></span>
            <!-- <input type="text" class="mylogbox" placeholder="Password" /> -->
            <input value="" name="password" dt="PROVAB_SOLID_V45" required="" type="password" placeholder="Password" class="login-ip password mylogbox _guest_validate_field" id="password" data-container="body" data-toggle="popover" data-original-title="" data-placement="bottom" data-trigger="hover focus" data-content="Password Ex: A3#FD*3377^*">
          </div>
         
          <!-- <button class="logbtn">Login</button> -->
           <button id="login_submit" class="logbtn">Login</button>
            <div id="login_auth_loading_image" style="display: none">
            <?=$login_auth_loading_image?>
          </div>
           <div id="login-status-wrapper" class="alert alert-danger" style="display: none"></div>
			   <div class="signhes fortlog"><?php echo $GLOBALS['CI']->template->isolated_view('general/forgot-password');?></div>
          </form>
          <!--<div class="signhes"> Don’t have an account ? <a href="<?=base_url().'index.php/user/agentRegister' ?>">Sign up</a></div>-->
           
          
        </div>
         <div class="innersecing <?php echo $otp_class; ?>" id="otp_div">
         <a href="#" class="gobacklink">Back</a> 
            <?php $name = 'otp' ?>
          <form name="<?=$name?>" autocomplete="off" action="" method="POST" enctype="multipart/form-data" id="login" role="form" class="form-horizontal">
            <div class="inputsing">
            <!-- <input type="text" class="mylogbox" placeholder="Password" /> -->
            <input value="" name="opt" required="" type="text" placeholder="Enter OTP" class="login-ip mylogbox _guest_validate_field" id="otp">
          </div>
          <button id="opt_submit" class="logbtn">Login</button>
           <div id="login-otp-wrapper" class="alert alert-danger" style="display: none"></div>
          </form>

         </div>
      </div>
    </div>
	
	  
	  
  </div>



  <div class="container-fluid nopad">

  <!--partners section start-->
    <div class="htldeals" style="border-radius:0px!important;">
      <div class="pagehdwrap">
        <div class="container">
          <h2 class="pagehding" style="color: #0f1a39">Our Affiliates and Partners
          </h2>
        </div>
      </div>
      <div class="tophtls">
        <div class="container">

                         <div class="customer-logos slider">
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/emirates-logo.jpg"></div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/american-logo.jpg" ></div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/aut-logo.jpg" ></div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/ethiopian-logo.jpg" ></div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/flydubai-logo.jpg" ></div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/ita-logo.jpg" ></div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/kenya-logo.jpg" ></div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/klm-logo.jpg" ></div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/quatr-logo.jpg"> </div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/trip-logo.jpg"> </div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/ut-logo.jpg" ></div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/uwa-logo.jpg" ></div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/booking-logo.jpg"></div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/carnet-logo.jpg"></div> 
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/goair-logo.jpg"></div> 
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/indigo-logo.jpg"> </div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/tmx-logo.jpg" ></div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/travelbot.jpg" ></div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/travelport-logo.jpg" ></div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/airasia-logo.jpg" ></div>
                    <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/sabre-logo.jpg" ></div>
                          <div class="slide"><img class="lazy lazy_loader" src="/extras/system/template_list/template_v3/images/amedus-logo.jpg" ></div>
                   </div>
                



        </div>
      </div>
    </div>
    
    <!--partners section end-->


  </div>



</div>
 <!-- footerstart -->
  <footer class="s-footer topdest">
   
    <div class="container">
      <div class="row">
		<div class="col-xs-12 col-sm-3 nopad fulnine">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 s-col-details">
	    <div class="s-flogo">          
          <a class="s-fotter-logo" href="">          
            <img class="text-center foot_logo" src="/extras/custom/TMX9604421616070986/images/TMX1512291534825461logo-loginpg.png" alt="Logo">             </a>        
        </div>
        <div class="foot_address">
            <?php $query = "SELECT * FROM contact_us_details";
                $get_contact_data= $this->db->query($query)->result();?>
            <p><?php echo $get_contact_data[0]->address1; ?> </p>
            <p><a class="mailadrs1" href="mailto:<?php echo $get_contact_data[0]->email1; ?>"><?php echo $get_contact_data[0]->email1; ?></a></p>
            <p><a class="mailadrs1" href="mailto:<?php echo $get_contact_data[0]->email2; ?> "><?php echo $get_contact_data[0]->email2; ?></a></p>
        </div>
        <!-- <p class="s-sicon"><a href="tel:+44 2035149412"><i class="fas fa-phone" style="transform: rotate(90deg);"></i>+44 2035149412</a></p>
          <p class="s-sicon"><a href=""><i class="fas fa-envelope"></i> info@alkhaleej.tours</a></p>
          <p class="s-sicon"><a href=""><i class="fab fa-skype"></i> live:.cid.693636f61cdcf98</a></p> -->
          <!--<h3 class="text-uppercase s-color">Quick Links</h3>
          <p> <a href=""> Flights</a></p>
          <p> <a href=""> Hotels</a></p>
          <p> <a href=""> Holidays</a></p>-->
      <!-- <h3 class="text-uppercase s-color">Social Media</h3> -->
          
        </div>
      </div>

     <div class="col-md-9 col-xs-12 fulnine color_bg">
        <!-- <div class="col-12 col-xs-12 col-sm-6 col-md-3 col-lg-3 quicklink">
		     <h3 class="text-uppercase s-color">Important Links</h3>
         <?php $query = "SELECT * FROM cms_pages WHERE page_id='5' AND page_status='1'";
            $get_data= $this->db->query($query)->row_array();
         
            ?>
          <p><a href="<?php echo APP_ROOT_DIR_TEST .'general/cms/index.php/' .$get_data['page_id'].'/'.$get_data['page_label'] ?>"> Contact Us</a></p>
          <?php $query2 = "SELECT * FROM cms_pages WHERE page_id='1' AND page_status='1'";
            $get_data2= $this->db->query($query2)->row_array();
            
            ?>
          <p><a href="<?php echo APP_ROOT_DIR_TEST .'general/cms/index.php/' .$get_data2['page_id'].'/'.$get_data2['page_label'] ?>"> About Us</a></p>
         <p><a href="<?php echo base_url();?>team"> Agent</a></p>
         <p><a href="<?php echo base_url();?>contact-us"> Service Providers</a></p>
         <p><a href="<?php echo base_url();?>company"> Blog</a></p>
          
        </div> -->
         <div class="col-12 col-xs-12 col-sm-6 col-md-3 col-lg-3 quicklink">
                                    <div class="frtbest">
                                        <ul id="accordionfot2" class="accordionftr">
                                              <h3 class="text-uppercase s-color">Important Links</h3>
                                            <ul class="submenuftr1">
                                                <li class="frteli"><a href="https://www.alkhaleejtours.com/index.php/home">Home</a></li>
                                                
                                                 <?php
                                                 $cond = array(
                                                    'page_status' => ACTIVE
                                                );
                                                $cms_data = $this->custom_db->single_table_records('cms_pages', '', $cond);
                                                // debug($cms_data);exit;
                                                foreach ($cms_data ['data'] as $keys => $values) {
                                                    if ($values['page_position']=='Left') {
                                                        //echo '<li class="frteli"><a href="' . base_url () . 'index.php/general/cms/Bottom/' . $values ['page_id'] . '">' . $values ['page_title'] . ' <br> </a></li>';
                                                      // if($values ['page_label'] == "about-us"){
                                                      //       $values ['page_label'] = "about_us";
                                                      //   }
                                                        if($values ['page_label'] == "about-us"){
                                                            $values ['page_label'] = "about-us";
                                                        }
                                                        if($values ['page_label'] == "contact-us"){
                                                            $values ['page_label'] = "contactus";
                                                        }
                                                        // echo '<li class="frteli"><a href="' . base_url() . 'index.php/'. $values ['page_label'] . '">' . $values ['page_title'] . ' <br> </a></li>';
                                                        echo '<li class="frteli"><a href="https://www.alkhaleejtours.com/index.php/'. $values ['page_label'] . '">' . $values ['page_title'] . ' <br> </a></li>';
                                                    }
                                                }
                                                // echo'<li class="frteli"><a href="' . base_url() . 'index.php/general/gallery">Gallery<br> </a></li>';
                                                echo'<li class="frteli"><a href="https://www.alkhaleejtours.com/index.php/gallery">Gallery<br> </a></li>';
                                                ?>
                                                <!-- <li class="frteli"><a href="<?php echo base_url();?>team">Agent</a></li> -->
                                                <li class="frteli"><a href="<?php echo base_url();?>index.php/general/index">Agent</a></li>
                                                <li class="frteli"><a href="https://www.alkhaleejtours.com/supplier">Service Provider Login</a></li>
                                                <li class="frteli"><a href="https://alkhaleej.biz/blog" target="_blank">Blog</a></li>

                                            </ul>
                                            <!-- <ul class="submenuftr1">
                                                <?php
                                                foreach ($cms_data ['data'] as $keys => $values) {
                                                    if ($keys >= 4) {
                                                        //echo '<li class="frteli"><a href="' . base_url () . 'index.php/general/cms/Bottom/' . $values ['page_id'] . '">' . $values ['page_title'] . ' <br> </a></li>';
                                                        echo '<li class="frteli"><a href="' . base_url() . $values ['page_label'] . '">' . $values ['page_title'] . ' <br> </a></li>';
                                                    }
                                                }
                                                ?>
                                            </ul>  -->
                                        </ul>
                                    </div>
                                </div>


        <div class="col-12 col-xs-12 col-sm-6 col-md-3 col-lg-3 quicklink">
          <h3 class="text-uppercase s-color">Quick links</h3>
          <p><a href="https://www.alkhaleejtours.com/"> Flights</a></p>
          <p><a href="https://www.alkhaleejtours.com/index.php/hotels"> Hotels</a></p>
          <p><a href="https://www.alkhaleejtours.com/index.php/transfers"> Transfers</a></p>
          <p><a href="https://www.alkhaleejtours.com/index.php/car"> Car</a></p>
          <p><a href="https://www.alkhaleejtours.com/index.php/activities"> Activities</a></p>
          <p><a href="https://www.alkhaleejtours.com/index.php/holidays"> Holidays</a></p>

        </div>


        <div class="col-12 col-xs-12 col-sm-6 col-md-3 col-lg-3 quicklink">
          <h3 class="text-uppercase s-color">Terms of Use</h3>

          <?php $query3 = "SELECT * FROM cms_pages WHERE page_id='15' AND page_status='1'";
            $get_data3= $this->db->query($query3)->row_array(); ?>
          <p><a href="<?php echo APP_ROOT_DIR_TEST .'general/cms/index.php/'.$get_data3['page_id'].'/' .$get_data3['page_label'] ?>"> Declarations</a></p>
          <?php $query4 = "SELECT * FROM cms_pages WHERE page_id='16' AND page_status='1'";
            $get_data4= $this->db->query($query4)->row_array();
            
          
            ?>
          <p><a href="<?php echo APP_ROOT_DIR_TEST .'general/cms/index.php/'.$get_data4['page_id'] .'/'.$get_data4['page_label'] ?>"> Terms and Conditions</a></p>
          <?php $query5 = "SELECT * FROM cms_pages WHERE page_id='17' AND page_status='1'";
            $get_data5= $this->db->query($query5)->row_array(); 
          
            ?>
          <p><a href="<?php echo APP_ROOT_DIR_TEST .'general/cms/index.php/'.$get_data5['page_id'].'/' .$get_data5['page_label'] ?>"> Privacy Policy</a></p>



        </div>

        <div class="col-12 col-xs-12 col-sm-6 col-md-3 col-lg-3 quicklink">
          <h3 class="text-uppercase s-color">We also accept</h3>
          <ul id="accordionfot2" class="accordionftr">
                                            <!-- <h4 class="ftrhd arimo ">We also accepts</h4> -->
                                            <!-- <ul class="submenuftr1">
                                                <li class="supprt"><i class="fas fa-phone"></i> +91-8880 321 321</li>
                                                <li class="supprt"><i class="fas fa-envelope"></i> +91-8880 321 321</li>
                                            </ul> -->
                                            <!-- <img src="https://www.travelsoho.com/alkhaleej_tours/extras/system/template_list/template_v3/images/verify.png" alt="logo"
                                        class="text-center foot_logo_s" />  -->
                                        <img src="<?php echo $GLOBALS['CI']->template->template_images('verify.png')?>" class="text-center foot_logo_s" alt="" />
                                        <br>
                                        <img src="<?php echo $GLOBALS['CI']->template->template_images('payment.png')?>" class="text-center foot_logo_s" alt="" />
                                        </ul>
                                </div>
            <!-- <h3 class="text-uppercase s-color">Verified Users</h3>
                <ul id="accordionfot2" class="accordionftr">
                                           
                                            <ul class="submenuftr1">
                                                <li class="supprt"><i class="fas fa-phone"></i> +91-8880 321 321</li>
                                                <li class="supprt"><i class="fas fa-envelope"></i> +91-8880 321 321</li>
                                            </ul>
                                            <img src="https://www.travelsoho.com/alkhaleej_tours/extras/system/template_list/template_v3/images/verify.png" alt="logo"
                                        class="text-center foot_logo_s" alt="" /> 
                     </ul> -->
         
       </div>


       <!--  <div class="col-12 col-xs-12 col-sm-6 col-md-3 col-lg-3">
          <h3 class="text-uppercase s-color">Important link</h3>
         <?php $query = "SELECT * FROM cms_pages WHERE page_id='5' AND page_status='1'";
            $get_data= $this->db->query($query)->row_array();
         
            ?>
          <p><a href="<?php echo APP_ROOT_DIR_TEST .'general/cms/index.php/' .$get_data['page_id'].'/'.$get_data['page_label'] ?>"> Contact Us</a></p>
          <?php $query2 = "SELECT * FROM cms_pages WHERE page_id='1' AND page_status='1'";
            $get_data2= $this->db->query($query2)->row_array();
            
            ?>
          <p><a href="<?php echo APP_ROOT_DIR_TEST .'general/cms/index.php/' .$get_data2['page_id'].'/'.$get_data2['page_label'] ?>"> About Us</a></p>
        </div>
 -->


       <!--  <div class="col-12 col-sm-6 col-md-3 col-lg-3">
          <h3 class="text-uppercase s-color">legals</h3>
          <?php $query3 = "SELECT * FROM cms_pages WHERE page_id='15' AND page_status='1'";
            $get_data3= $this->db->query($query3)->row_array(); ?>
          <p><a href="<?php echo APP_ROOT_DIR_TEST .'general/cms/index.php/'.$get_data3['page_id'].'/' .$get_data3['page_label'] ?>"> Declarations</a></p>
          <?php $query4 = "SELECT * FROM cms_pages WHERE page_id='16' AND page_status='1'";
            $get_data4= $this->db->query($query4)->row_array();
            
          
            ?>
          <p><a href="<?php echo APP_ROOT_DIR_TEST .'general/cms/index.php/'.$get_data4['page_id'] .'/'.$get_data4['page_label'] ?>"> Terms and Conditions</a></p>
          <?php $query5 = "SELECT * FROM cms_pages WHERE page_id='17' AND page_status='1'";
            $get_data5= $this->db->query($query5)->row_array(); 
          
            ?>
          <p><a href="<?php echo APP_ROOT_DIR_TEST .'general/cms/index.php/'.$get_data5['page_id'].'/' .$get_data5['page_label'] ?>"> Privacy Policy</a></p>
        </div> -->

        <div class="clearfix"></div>

        <div class="col-md-4 nopad">
          <div class="ss-scl"> 
            <?php $query = "SELECT * FROM social_links WHERE social='skype' AND status='1'";
            $get_data= $this->db->query($query)->row_array(); ?>      
            <a href="<?php echo $get_data['url_link'] ?>" target="_balnk">
        <i class="faftrsoc5 fab fa-skype"></i>
           </a>  

          <?php $query = "SELECT * FROM social_links WHERE social='facebook' AND status='1'";
            $get_data= $this->db->query($query)->row_array(); ?>      
            <a href="<?php echo $get_data['url_link'] ?>" target="_balnk">
        <i class="faftrsoc2 fab fa-facebook-f f1"></i>
           </a>   
          <?php $twiter = "SELECT * FROM social_links WHERE social='twitter' AND status='1'";
            $get_data2= $this->db->query($twiter)->row_array(); ?>         
            <a href="<?php echo $get_data2['url_link'] ?>" target="_balnk"><i class="faftrsoc7 fab fa-twitter"></i>
            </a>  
            <?php $insta = "SELECT * FROM social_links WHERE social='instagram' AND status='1'";
            $get_data3= $this->db->query($insta)->row_array(); ?>
       <a href="<?php echo $get_data3['url_link'] ?>" target="_balnk"><i class="faftrsoc1 fab fa-instagram"></i>
        </a>          
         <?php $youtube = "SELECT * FROM social_links WHERE social='youtube' AND status='1'";
            $get_data4= $this->db->query($youtube)->row_array(); ?>                      
            <a href="<?php echo $get_data4['url_link'] ?>" target="_balnk"><i class="faftrsoc8 fab fab fa-youtube"></i>
           </a>
            <?php $linkedin = "SELECT * FROM social_links WHERE social='linkedin' AND status='1'";
            $get_data5 = $this->db->query($linkedin)->row_array(); ?>  
            <a href="<?php echo $get_data5['url_link'] ?>" target="_balnk"><i class="faftrsoc3 fab fa-linkedin"></i>

            <!-- <?php $pinterest = "SELECT * FROM social_links WHERE social='pinterest' AND status='1'";
            $get_data6 = $this->db->query($pinterest)->row_array(); ?>  
            <a href="<?php echo $get_data6['url_link'] ?>" target="_balnk"><i class="faftrsoc1 fab fa-pinterest"></i>
         </a>   -->                         
          </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 nopad">
                            
                             
                         <div class="ftr-extra">
                            
                                <!-- <h5>Download App</h5>
                                <p>Download App for a seemless & Immersive<br> Experience</p> -->
                                <div class="ftr-extra-img">
                                    <div class="ftr-et-im">
                                        <!-- <a href="https://play.google.com/store/apps/details?id=com.alkhaleej.tours"> -->
                                          <a href="#">
                                       <img src="<?php echo $GLOBALS['CI']->template->domain_images('Google_Play.png'); ?>">
                                       </a>
                                    </div>
                                    <div class="ftr-et-im">
                                        <!-- <a href="https://apps.apple.com/us/app/alkhaleej.tours/id1401815072"> -->
                                          <a href="#">
                                       <img src="<?php echo $GLOBALS['CI']->template->domain_images('AppStore.png'); ?>">
                                        </a>
                                     </div>
                                </div>
                        </div>
            </div>

            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 nopad">
                        <div class="subsc_sec">
                                <h6 class="hdttle">Subscribe to our Newsletter</h6>
                                            <!-- <p>Keep yourself updated by connecting with us !</p> -->
                                        <div class="signfomup">
                                            
                                            <div class="formbtmns">
                                                <!-- <input type="text" name="email" id="exampleInputEmail1" class="form-control ft_subscribe" value="" required="required" placeholder="Enter Your Email"> -->
                                                <input type="email" name="email_id" id="email_id" value required="required" placeholder="Enter Your Email" class="form-control ft_subscribe">
                                                <span id="keep_mail_sub_error" style="color:red;"></span>
                                            </div>
                                        <span class="msgNewsLetterSubsc12" style="font-size: 13px; color: #fff; display: none;"><b>Please Provide Valid Email ID</b></span> <span class="succNewsLetterSubsc" style="font-size: 13px; color: #fff; display: none;"><b>Thank you for your subscription!We will keep you informed of our activities.</b></span> <span class="msgNewsLetterSubsc" style="font-size: 13px; color: #fff; display: none;"><b>You are already subscribed to Newsletter feed.</b></span> <span class="msgNewsLetterSubsc1" style="font-size: 13px; color: #fff; display: none;"><b>Activated to Newsletter feed.Thank you</b></span> 
                                            
                                        <p id="sub_msg"></p>
                                        </div>
                                        <div class="form_new"><!-- <button type="button" class="btn btn_sub subsbtm" onclick="check_newsletter()"><i class="fa fa-paper-plane"></i></button> --><button type="button" class="btn btn_sub subsbtm" id="keep_mail_sub"><i class="fa fa-paper-plane"></i></button></div>
                                        </div>
                        
                    </div>
                    <div class="footer-top__back-to-top">
                  <a class="footer-top__back-to-top-link  js-back-to-top" href="#"> 
                                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                    </a>
                    <a class="footer-top" href="https://web.whatsapp.com/"> 
                                    <i class="fab fa-whatsapp"></i>
                    </a>
                </div>


       </div>

		 
      </div>
      <!-- <div>
        <div class="col-12 col-sm-6 col-md-4 hide">
          <h3>Social Media</h3>
          <div class="ss-scl">                               
            <a href="https://www.facebook.com/" target="_balnk">
            <img src="https://www.travelsoho.com/ocio/extras/system/template_list/template_v3/images/facebook.png"></a>         
            <a href="https://twitter.com/" target="_balnk">
            <img src="https://www.travelsoho.com/ocio/extras/system/template_list/template_v3/images/twitter.png"></a>       <a href="test" target="_balnk"><img src="https://www.travelsoho.com/ocio/extras/system/template_list/template_v3/images/instagram.png"></a>                                
            <a href="https://www.youtube.com/" target="_balnk">
            <img src="https://www.travelsoho.com/ocio/extras/system/template_list/template_v3/images/youtube.png"></a> 
            <a href="test" target="_balnk"><img src="https://www.travelsoho.com/ocio/extras/system/template_list/template_v3/images/linkedin.png"></a>                              
          </div>
        </div>
      </div> -->



      <div class="btmfooternw">

                    <div class="container">



                        <div class="acceptimg">

                            <img class="img-responsive"

                                 src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/payment.png"

                                 alt="" />

                        </div>

                        <div class="copyrit">

                            Copyright &copy; <?php echo date('Y') ?><a  href="index.php"> <?= HEADER_DOMAIN_NAME ?></a> All rights reserved.

                        </div>                       
                        <!-- <div class="copyrit">Powered by <strong>PROVAB</strong></div> -->

                    </div>
                        <!-- <div id="WAButton"></div> -->
                </div>
    </div>
    <div class="clearfix"></div>
    <!--<div class="container ">
      <div class="col-12 visawrap">
        <div class="row" style="justify-content: end;float:right">
          <div class="col-12 col-md-12">                <a href=""><img src="/ocio/extras/system/template_list/template_v3/images/visa.png"></a>              </div>
        </div>
      </div>
    </div>-->
    <!-- <div class="container-fluid copy-right">
      <div class="container">
        <p class="pull-left"><a href="">Copyright@2021.Alkhaleej Tours</a></p>
       
      </div>
    </div> -->

      
  </footer>
  <!-- footerend -->




</div>

<style type="text/css">
  .invalid-ip {
    border: 1px solid #bf7070!important;
}
.alert-danger{
      background-color: #dd4b39!important;
}
.htldeals {
    background: none repeat scroll 0 0 #fff;
    float: left;
    padding: 30px 0px 30px 0px;
		width: 100%;
	margin: 30px 0px 0px 0px;    border-radius: 8px;}
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
	.grid .figure .figcaption, .grid .figure .figcaption a {
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
     
      $.post(app_base_url+"index.php/auth/check_otp/", {otp: _otp}, function(response) {
      
        if (response.status) {
          window.location.reload();
        } else {
          $('#login-otp-wrapper').text(response.data).show();
        }
       
      });
    }
  });
  $('.gobacklink').on('click', function(e) {
     $.post(app_base_url+"index.php/auth/back_button/", function(response) {
      
        if (response.status) {
          window.location.reload();
        } else {
          $('#login-otp-wrapper').text(response.data).show();
        }
       
      });
    
  });

$('#password').on('keypress', function(e) {
            if (e.which == 32){
               return false;
            }
        });

});
</script>
<script>  $(document).ready(function() {
        var owlindex2 = $("#owl-demo2");

        owlindex2.owlCarousel({      
            itemsCustom : [
                 [0, 1],
                [450, 2],
                [551, 3],
                [700, 4],
                [1000, 5],
                [1200, 5],
                [1400, 5],
                [1600, 5]
            ],
            navigation : true,
			pagination: false

        });
		  });</script>
<script type="text/javascript">
	$(document).ready(function(){
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
  $(document).ready(function(){
    $('#keep_mail_sub').click(function (e) {
                $("#keep_mail_sub_error").text('');
                $("#sub_msg").text('');
        // alert();
        e.preventDefault();

        var input_text=$("#email_id").val();
            // alert(input_text);
            var mailformat =/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/.test(input_text);
            // var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            
            if(!mailformat)
            {
                $("#keep_mail_sub_error").text('Enter Valid email address!');
               // alert("Enter Valid email address!");
               return false;
            }


        $.ajax({
            url:'https://www.alkhaleejtours.com/index.php/general/save_keep_email',
            type:'POST',
            data:{'email_id':input_text},
            success:function(msg){

                if(msg.status == true)
                {
                    $("#sub_msg").css("color", "green");
                    $('#sub_msg').text(msg.message);
                    // $('#generaltourenquiry')[0].reset(); 
                }
                else
                {
                    $("#sub_msg").css("color", "red");
                    $('#sub_msg').text(msg.message);
                }
            },
            error:function(){
            }
         }) ;
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