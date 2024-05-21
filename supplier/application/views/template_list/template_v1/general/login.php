<?php

 

if (isset($login) == false || is_object($login) == false) {
	$login = new Provab_Page_Loader('login');
}
?>
<style>
label[for=opt_number]
{
   display:none;
}
.form-horizontal .control-label { display: none; }
.login-ip.email {
	border: medium none;
    border-bottom: 1px solid #666;
    color: #2e2d2d;
    font-size: 14px;
    height: 50px;
    width: 100%;
    padding: 10px 30px 10px 10px;
}
.loginbox {width: 40% !important;}
.loginbox .innersecing { padding:30px !important; }
.form-horizontal .col-sm-6 { width: 100%; }
.form-horizontal .col-sm-8 { width: 100%; margin-left: 0px; }
.login-ip.password { border: medium none;
    border-bottom: 1px solid #666;
    color: #2e2d2d;
    font-size: 14px;
    height: 50px;
    width: 100%;
    padding: 10px 30px 10px 10px; }
.btn-info {
    background-color: #f04c23;
    border-color: #f04c23;
    padding: 7px 20px;
    font-size: 15px;
}

.btn-info:hover {
    background-color: #f04c23;
    border-color: #f04c23;
    padding: 7px 20px;
    font-size: 15px;
}
</style>

<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('agent_index.css');?>" rel="stylesheet" defer>



 <div class="topform_main">
 <div class="topform">

<div class="container">
   
    <div class="loginbox">
      <!-- <div class="col-sm-5 col-xs-5 nopad">
        <div class="innerfirst">
          <div class="logopart"> <img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>" alt=""/></div>
          <div class="hmembr fr_mobl"> Online  <br/> Reservation <br/>System </div>
          <div class="lorentt fr_mobl">Award winning B2B platform for travel agents and start-up travel companies.</div>
        </div>
      </div> -->
      <div class="col-sm-12 col-xs-12 nopad">
      <?php 
      $class ='';
      $otp_class = 'hide';
      $OTP_status = $this->session->userdata('OTP_status');
     // debug($OTP_status);exit();
      if(isset($OTP_status) && $OTP_status == 'not verified'){
        $class= 'hide';
        $otp_class = '';
      }
      //echo $this->session->userdata('OTP_status');exit;?>
<?php
           if($error)
           {
           ?>
           <div  class="alert alert-danger" ><?=$error;?></div>
         <?php } ?>
        <div class="innersecing">
        	 <div class="logopart"> <img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>" alt=""/></div>

          <div class="signhes"><i class="far fa-power-off"></i> Sign in to Continue </div>
         <?php $sparam = $this->input->cookie ( 'login_cookie', TRUE );  
	//	debug($sparam ); exit; 
		if(!empty($sparam)){
			echo $login->generate_form('login', array('email' => '', 'password' => ''),array('opt_number'));
		}else{
			echo $login->generate_form('login', array('email' => '', 'password' => ''));
		 
		}?>

          <div class="signhes"> Don’t have an account ? <a href="<?php  echo base_url();?>index.php/user/supplierRegister" target="_blank">Sign up</a></div>
            <div class="signhes"><?php include_once 'forgot-password.php';?></div>
          
        </div>
         <div class="innersecing <?php echo $otp_class; ?>">
            <?php $name = 'otp' ?>
          <form name="<?=$name?>" autocomplete="off" action="" method="POST" enctype="multipart/form-data" id="login" role="form" class="form-horizontal">
            <div class="inputsing">
            <!-- <input type="text" class="mylogbox" placeholder="Password" /> -->
            <input value="" name="opt" required="" type="text" placeholder="Enter OTP" class="login-ip mylogbox _guest_validate_field" id="otp">
          </div>
          <button id="opt_submit" class="logbtn1 btn btn-warning btn-lg">Verify OTP</button>
          <a href="<?php echo base_url('index.php/general/backtologin'); ?>" class="btn btn-primary btn-lg pull-right">Back to Login</a>
           <div id="login-otp-wrapper" class="alert alert-danger" style="display: none"></div>
           
          </form>

         </div>
      </div>
    </div>
  </div>

</div>
</div>


<script type="text/javascript">
$(document).on('click', '.send_otp', function(){
      // $(".email, .password, label[for=email], label[for=password]").hide();


 	var email = $('#email').val(); 

        if(email==''){
    	  $( "#email" ).addClass( "invalid-ip" );
          return false;
  	}
        var password = $('#password').val(); 
        if(password==''){
    	  $( "#password" ).addClass( "invalid-ip" );
          return false;
  	}


  	toastr.info('Please Wait!!!');
	$.post(app_base_url + "index.php/general/send_otp", {email: email, password: password}, function(result){
// alert(result);
        if(result)
        {
           if(result==true)
			{
				$(".email, .password, label[for=email], label[for=password]").hide();
				$('.opt_number').removeClass('hide');$("label[for=opt_number]").show();
				$("#login_custom").prop("type", "submit");
				$("#login_custom").html("Login");
				$("#login_custom").removeClass("send_otp");
				 toastr.info("OTP sent Successfully!!!");
				  // window.location.reload();
			}
			 if(result==false)
			{
				 toastr.info("Invalid Login Details");
			}

          
        }


    });
  
});


</script>
