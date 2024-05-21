<?php


	$social1 = is_active_social_login ( 'facebook' );
	$social2 = is_active_social_login ( 'twitter' );
	$social3 = is_active_social_login ( 'googleplus' );

	$addr = $this->custom_db->single_table_records('api_country_list', '*');
	$addr = $addr['data'];
	
	$login_auth_loading_image	 = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v3.gif').'" alt="please wait" loading="lazy" /></div>';
	
	if ($social1 == true) {
		$GLOBALS['CI']->load->library('social_network/facebook');
	}

	if ($social2 == true) {
		//Not Yet Active
	}

	if ($social3 == true) {
		$GLOBALS['CI']->load->library('social_network/google');
	}

	if (isset ( $login ) == false || is_object ( $login ) == false) {
		$login = new Provab_Page_Loader ( 'login' );
	}
	if (is_logged_in_user () == true) {
		if($social1 == true){
			echo '<div class="hide">'.$GLOBALS['CI']->facebook->login_button ().'</div>';
		}
	?>

<?php } else { ?>

	<style>/*#container .haAclf.nsm7Bb-HzV7m-LgbsSe-BPrWId{font-size: 16px!important;
    	text-align: left!important;}
          .signdiv.nsm7Bb-HzV7m-LgbsSe.MFS4be-v3pZbf-Ia7Qfc{width: 308px!important;text-align: left!important;}
*/
</style>
<div class="my_account_dropdown mysign exploreul">
    <button type="button" class="close log_close close_with_reset" data-dismiss="modal">&times;</button>
    <div>
    	<img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>" alt="Book a tour to India" loading="lazy"><br><!--<p>Please sign up again this is for security purposes</p>--></div>
	<div class="signdiv">
		<div class="insigndiv for_sign_in">
			<div class="leftpul">
				<?php
					if ($social1) {
						
						echo $GLOBALS['CI']->facebook->login_button ();
					}
					
					if ($social2) {
						?>
				<a class="logspecify tweetcolor">
					<span class="fa fa-twitter"></span> 
					<div class="mensionsoc">Login with Twitter</div>
				</a>
				<?php
					}
					
					if ($social3) {
						?>
				<?php
					echo $GLOBALS['CI']->google->login_button ();?>
				<?php } ?>
			</div>
			<?php $no_social=no_social(); if($no_social != 0) {?>
			<div class="centerpul">
				<div class="orbar"> <strong>Or</strong> </div>
			</div>
			<?php }?>
			<div class="ritpul">
				<form id="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="off"
					name="login">
					<div class="rowput"> <span class="fa fa-user"></span>
						<input type="email"
							data-content="Username"
							data-trigger="hover focus" data-placement="bottom"
							data-original-title="Here To Help" data-toggle="popover"
							data-container="body" id="email"
							class="email form-control logpadding" placeholder="Username"
							required="required" name="email">
					</div>
					<div class="rowput"> <span class="fa fa-lock"></span>
						<input type="password"
							id="password" class="password form-control logpadding"
							placeholder="Password" required="required" name="password"
							value="" >
					</div>
					<div class="clearfix"></div>
					<div id="login-status-wrapper" class="alert alert-danger"
						style="display: none">
						<p> <i class="fa fa-warning"></i> </p>
					</div>
					<div class="clearfix"></div>
					<div id="login_auth_loading_image" style="display: none">
						<?=$login_auth_loading_image?>
					</div>
					<div class="clearfix"></div>
					
					<div class="misclog"> <a class="hand-cursor forgtpsw forgot_pasword" id="forgot-password">Forgot Password ? </a> </div>
					<div class="clearfix"></div>
					<button class="submitlogin" id="login_submit">Login</button>
					<div class="clear"></div>
					<div class="dntacnt"> New User? <a class="hand-cursor open_register">Sign Up</a> </div>
				</form>
			</div>
		</div>
		<div class="newacount_div for_sign_up">
			<div class="slpophd_new">Register with Travel Free Travels</div>
			<div class="othesend_regstr">
				<div class="ritpul">
					<form autocomplete="off" method="post" id="register_user_form">
						<div class="rowput has-feedback hide">
							<span class="fa fa-user"></span>
							<input type="text" class="validate_user_register form-control logpadding" value="Customer" placeholder="Name" name="first_name" required="" autocomplete="off" />
						</div>
						<div class="rowput has-feedback email">
							<span class="fa fa-envelope"></span>
							<input type="email" id="email_vaidation" class="validate_user_register form-control logpadding" placeholder=" Enter Email ID " value="" name="email" required="" autocomplete="off" />
							<span class="err_msg" > Email Field is mandatory</span>
							<span class="err_validation hide">Please Enter Valid Email Address</span>
						</div>
						<div class="rowput has-feedback country">
							<span class="fa fa-mobile"></span>							
							<div>

                            <div id="country_code_register" class="chosen-wrapper chosen-wrapper--style2 mych-wr" data-js="custom-scroll">
						  <select name="country_code" id="country_code_register_element"  class="validate_user_register form-control logpadding chosen-select" data-placeholder="Lorem ipsum dolor sit amet">
						                             <option value = '' >select country code</option>
						                              <?php
						                              foreach ($addr as $key => $value) {
						                              	echo "<option value = '".$value['country_code']."'>".$value['name'].' '.$value['country_code']."</option>";
						                              } 
						                              ?>
						  </select>
						  
						</div>
						<span class="err_msg" id="countrycode_error"> </span>
					     </div>


						</div> 

						<div class="rowput has-feedback phone">
							<span class="fa fa-phone"></span>
							<input type="tel" class="validate_user_register numeric form-control logpadding" maxlength="10" placeholder="Mobile Number" value="" name="phone" required="" autocomplete="off"/>
							<span class="err_msg"> Phone Field is mandatory</span>
						</div>
						<div class="rowput has-feedback password">
							<span class="fa fa-lock"></span>
							<input type="password" id="password_1" class="validate_user_register form-control logpadding" placeholder="New Password" value="" name="password" required="" autocomplete="off">
							<span class="err_msg"> Password Field is mandatory</span>
						</div>
						<div class="rowput has-feedback cpassword">
							<span class="fa fa-lock"></span>
							<input type="password" id="password_2" class="validate_user_register form-control logpadding" placeholder="Retype Password" value="" name="confirm_password" required="" autocomplete="off" />
							<span class="err_msg">Confirm password Field is mandatory</span>
						</div>
						<div class="rowput has-feedback">
						   
							<span class="fas fa-file"></span>
							<input type="text" class="form-control logpadding"  placeholder="Referral code"  name="refercodes" value="<?php echo $_GET['refercode'] ?>"  />
							
						</div>
						<div class="clearfix"></div>
						<div class="row_submit">
							<div class="col-xs-12 nopad">
								<div class="agree_terms">
									<div class="squaredThree">
										<input type="checkbox" id="register_tc" class="airlinecheckbox validate_user_register" name="tc" required="">
										<label for="register_tc"	></label>
									</div>
									<label class="lbllbl" for="tc">By signing up you accept our <a target="_blank" href="https://www.travelfreetravels.com/terms">Terms & Conditions</a></label>
									<p class="text-danger hide" id="invalid_cond_msg">Please select terms and conditions.</p>
								</div>
							</div>
							<div class="col-xs-12 nopad">
								<button type="submit" id="register_user_button" class="submitlogin">Register</button>
							</div>
						</div>
						<div class="loading hide" id="loading"><img src="<?php echo $GLOBALS['CI']->template->template_images('loader_v3.gif')?>" alt="Book a tour to India" loading="lazy"></div>
						<div class="rowput alert alert-success hide" id="register-status-wrapper" ></div>
						<div class="rowput alert alert-danger hide" id="register-error-msg"></div>
						<div class="clearfix"></div>
						
					</form>
					<a class="open_sign_in">I already have an Account</a> 
				</div>
			</div>
		</div>
		<div class="actual_forgot for_forgot">
			<div class="slpophd_new">Forgot Password?</div>
			<div class="othesend_regstr">
				<div class="rowput">
					<span class="fa fa-envelope"></span>
					<input type="text" name="forgot_pwd_email" id="recover_email" class="logpadding form-control" placeholder="Enter Email-Id" required="" />
					<span class="err_validation" id="forgot_pasword_email_id"></span>
				</div>
				<!--<div class="rowput">
					<span class="fa fa-mobile"></span>
					<input type="tel" name="forgot_pwd_phone" id="recover_phone"	class="logpadding form-control" placeholder="Registered Mobile Number" required="" />
					<span class="err_validation" id="forgot_pasword_mobile_id"></span>
				</div>-->
				<div class="clearfix"></div>
				<div id="recover-title-wrapper" class="alert alert-success"
					style="display: none">
					<p> <i class="fa fa-warning"></i> <span id="recover-title"></span> </p>
				</div>
				<div class="clearfix"></div>
				<div id="send_forgotpsw_loading_image" style="display:none;">
					<div class="text-center loader-image" style="display: none;"><img src="<?=$GLOBALS['CI']->template->template_images('loader_v3.gif')?>" alt="please wait" loading="lazy"></div></div>
				</div>
				<button class="submitlogin" id="reset-password-trigger">Send EMail</button>
				<div class="clearfix"></div>
				<a class="open_sign_in">I am an Existing User</a>
			</div>
		</div>
	</div>
</div>
<!-- New Forgot Password Modal -->
<div id="forgotpaswrdpop" class="altpopup">
	<div class="comn_close_pop fa fa-times closepopup"></div>
	<div class="insideforgot">
		<div class="slpophd">Forgot Password?</div>
		<div class="othesend">
			<div class="rowput">
				<span class="fa fa-envelope"></span>
				<input type="text" name="forgot_pwd_email" id="recover_email_book" class="logpadding form-control" placeholder="Enter Email" required="required" />
				<span class="err_validation" id="forgot_pasword_email_id_booking"></span>
			</div>
			<div class="rowput">
				<span class="fa fa-mobile"></span>
				<input type="text" name="forgot_pwd_phone" id="recover_phone_book"	class="logpadding form-control" placeholder="Enter Mobile Number" required="required" />
				<span class="err_validation" id="forgot_pasword_mobile_id_booking"></span>
			</div>
			<div class="clearfix"></div>
			<div id="recover-title-wrapper-book" class="alert alert-success"
				style="display: none">
				<p> <i class="fa fa-warning"></i> <span id="recover-title-book"></span> </p>
			</div>
			<div class="centerdprcd">
				<button class="bookcont" id="reset-password-trigger-book">Send Mail</button>
			</div>
		</div>
	</div>
</div>
<?php }
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/login.js'), 'defer' => 'defer');
?>

<script>
          $(document).ready(function(){

          	$(".close_with_reset").click(function()
          	{
              //alert("WELCOME");
              $("#register_user_form").trigger("reset");
              $(".alert-success").hide();
              $("#login").trigger("reset");
          	});
            $(".userorlogin").click(function()
          	{
              //alert("WELCOME");
              $("#login-status-wrapper").hide();
              $(".alert-success").hide();
              $("#register_user_form").trigger("reset");
              $("#login").trigger("reset");
          	});
         	$('#password_1, #password_2, #password').on('keypress', function(e) {
            if (e.which == 32){
               return false;
            }
        });
         	$('#email_vaidation, #recover_email').on('keypress', function() {
          var re = /([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{2,4}/.test(this.value);

			    if(!re) {
			    	$('.err_validation').removeClass("hide");
			    } else {
			    	 $('.err_validation').addClass("hide");
			    }
			});

});

        
</script>
<script	src="<?php echo $GLOBALS['CI']->template->template_js_dir('chosen.js'); ?>" defer></script>
				
