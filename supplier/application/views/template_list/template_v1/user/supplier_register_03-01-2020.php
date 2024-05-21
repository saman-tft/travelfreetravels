 
<?php 

  $privillages=$supplier_crs_privilage;

?>



<link href="<?php echo APP_ROOT_DIR; ?>/extras/system/template_list/template_v1/css/_all-skins.min.css" rel="stylesheet" type="text/css" />  
  <script src="<?php echo APP_ROOT_DIR; ?>/extras/system/library/javascript/jquery-2.1.1.min.js"></script>

  <link  href="<?php echo APP_ROOT_DIR; ?>/extras/system/library/bootstrap/css/font-awesome.min.css" media="screen" rel="stylesheet" type="text/css" hreflang="en"></link>
  <link  href="<?php echo APP_ROOT_DIR; ?>/extras/system/library/bootstrap/css/bootstrap.min.css" media="screen" rel="stylesheet" type="text/css" hreflang="en"></link>
  <link  href="<?php echo APP_ROOT_DIR; ?>/extras/system/template_list/template_v3/css/theme_style.css" media="screen" rel="stylesheet" type="text/css" hreflang="en"></link>
  <link  href="<?php echo APP_ROOT_DIR; ?>/ extras/system/template_list/template_v1/css/shared.css" rel="stylesheet" type="text/css" hreflang="en"></link>
  <link  href="<?php echo APP_ROOT_DIR; ?>/extras/system/template_list/template_v3/css/agent.css" rel="stylesheet" type="text/css" hreflang="en"></link>
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet"><!-- 
  <link  href="/troogles/extras/system/template_list/template_v3/css/media.css" media="screen" rel="stylesheet" type="text/css" hreflang="en"></link>
  <link  href="/troogles/extras/system/library/javascript/jquery-ui-1.11.2.custom/jquery-ui.theme.min.css" rel="stylesheet" type="text/css" hreflang="en"></link>

  <link  href="/troogles/extras/system/library/javascript/jquery-ui-1.11.2.custom/jquery-ui.structure.min.css" rel="stylesheet" type="text/css" hreflang="en"></link>  -->

<script src="/troogles/extras/system/library/javascript/jquery-2.1.1.min.js"></script>
<script src="/troogles/extras/system/template_list/template_v1/javascript/javascript.js" async defer></script>
<?php

                              
// print_r($country_code);die;
$default_state=34;// Delhi
if(empty(validation_errors()) == false) {
  $view_tab = '';
  $edit_tab = ' active ';
} else {
  $view_tab = ' active ';
  $edit_tab = '';
}
if(empty(validation_errors()) == false){
  $message = 'hide';
}
//$message = 'hide';//Remove it in Soorya Travel


?>
<style type="text/css">
  .pop-info{
    font-family: monospace;
    font-size: 15px;
    margin-left: 19%;
    opacity: 0.9;
    position: fixed;
    z-index: 1000;
  }

  #register_user_form .acceptrms span.name_error div.formerror {
    color: #f00 !important;
}

</style>


<div class="newaddtab"></div>
<div class="background_login">    
    <div class="loadcity"></div>    
    <div class="clodnsun"></div>        
        <div class="reltivefligtgo">        
          <div class="flitfly"></div>       
         </div>  
        <div class="clearfix"></div>   
       <div class="busrunning">       
            <div class="runbus"></div>           
             <div class="runbus2"></div>        
                 <div class="roadd"></div>       
         </div>   
  </div>
<div class="b2b_agent_profile agent_regpage agentmyn">
<div class="container">
  <?php if(!empty($this->session->flashdata('message'))) {?>
  <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> <strong><?php echo $this->session->flashdata('message'); ?></strong> </div>
  <?php } ?>
  <div class="tab-content sidewise_tab">
    <div data-role="tabpanel" class="tab-pane active clearfix" id="profile">
      <div class="agent_regtr">

        <img class="ful_logo" style=" display: table; margin: 0px auto 20px auto;" src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>" alt="" />
        
        <div class="agentreg_heading"> SUPPLIER REGISTER
        
        <a href="<?=base_url()?>" class="gobacklink">Back</a> 
        
        </div>
        
        <div class="clearfix"></div>
        <!-- Edit User Profile starts-->
        <div class="tab-content">
          <div data-role="tabpanel filldiv" class="tab-pane active" id="show_user_profile">
            <form action="<?=base_url().'index.php/user/supplierRegister'; ?>" method="post" name="edit_user_form" id="register_user_form" enctype="multipart/form-data" autocomplete="off">
              <div class="each_sections">
                <div class="sec_heading"><strong>1</strong>Personal Info</div>
                <div class="inside_regwrp">
                  <div class="col-sm-12 nopad">
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">First Name <span class="text-danger">*</span></div>
                        <div class="col-xs-3 nopad">
                          <div class="select_wrap">
                            <select name="title" class="select_form noborderit smaltext" required>
                             <option value="1">Mr</option>
                             <option value="2">Ms</option>
                             <option value="3">Miss</option>
                             <option value="4">Master</option>
                             <option value="5">Mrs</option>
                             <option value="6">Mstr</option>
                            </select>
                            
                          </div>
                        </div>
                        <div class="col-xs-9 nopad">
                          <div class="div_wrap">
                            <input type="text" name="first_name"  placeholder="First name" value="<?php echo set_value('first_name'); ?>" class="input_form alpha_space _guest_validate_field" required />
                          </div>
                        </div>
                         <?php if(!empty(form_error('first_name'))) { ?>
                        <div class="agent_error"><?php echo form_error('first_name');?></div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Last Name <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="text" name="last_name" placeholder="Last name" value="<?php echo set_value('last_name'); ?>" class="input_form alpha_space _guest_validate_field" required="required"/>
                          
                        </div>
                         <?php if(!empty(form_error('last_name'))) { ?>
                        <div class="agent_error"><?php echo form_error('last_name');?></div>
                        <?php }?>
                      </div>
                    </div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Mobile Number <span class="text-danger">*</span></div>
                        <div class="col-xs-6 nopad">
                          <div class="select_wrap">
                            <select name="country_code" class="select_form noborderit smaltext" required>
                              <option value="92">India +91</option>
                              <?=
                              generate_options($phone_code_array, (array)@$country_code)?>
                         
                            </select>
                          </div>
                        </div>
                        <div class="col-xs-6 nopad">
                          <div class="div_wrap">
                            <input type="text" name="phone" placeholder="Mobile number" value="<?php echo set_value('phone'); ?>" class="input_form numeric _guest_validate_field"  required="required" maxlength="10" minlength="8">
                            
                          </div>
                        </div>
                          <?php if(!empty(form_error('phone'))) { ?>
                        <div class="agent_error"><?php echo form_error('phone');?></div>
                        <?php }?>
                      </div>
                    </div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Email <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="email" id="user_email" name="email" maxlength="80" placeholder="Email" onblur="validateemail()" value="<?php echo set_value('email'); ?>" class="input_form email" required="required"/>
                          
                        </div>
                        <span id='uniqmail' style='display:none'></span>
                          <?php if(!empty(form_error('email'))) { ?>
                          <div class="agent_error"><?php echo form_error('email');?></div>
                       <?php } ?>
                      </div>
                    </div>

                    <div class="col-sm-12 nopad">
                      <div class="wrap_space" style="width: 100%;">
                        <div class="label_form">Privilages <span class="text-danger">*</span></div>
                        <div class="div_wrap">

                          <div class="col-md-3 nopad">                  
                            <div class="squaredThree">                      
                              <input dt="" class=" user_type checkboxIp" type="checkbox" name="user_type[]" id="b2as_useruser_type1" value="1">                
                               <label for="b2as_useruser_type1"></label>                    
                             </div>                                        
                             <label for="b2as_useruser_type1" class="lbllbl" style="color: #666; font-size: 14px;">Tour CRS</label>                
                           </div>

                           <div class="col-md-3 nopad">                  
                            <div class="squaredThree">                      
                              <input dt="" class="user_type checkboxIp invalid-ip" type="checkbox" name="user_type[]" id="b2as_useruser_type2" value="2">       
                               <label for="b2as_useruser_type2"></label>                    
                             </div>                                        
                             <label for="b2as_useruser_type2" class="lbllbl" style="color: #666; font-size: 14px;">Activity CRS</label>                
                           </div>

                           <div class="col-md-3 nopad">                  
                            <div class="squaredThree">                      
                              <input dt="" class=" user_type checkboxIp" type="checkbox" name="user_type[]" id="b2as_useruser_type4" value="4" >                
                               <label for="b2as_useruser_type4"></label>                    
                             </div>                                        
                             <label for="b2as_useruser_type4" class="lbllbl" style="color: #666; font-size: 14px;">Transfers CRS </label>                
                           </div>

                           <div class="col-md-3 nopad">                  
                            <div class="squaredThree">                      
                              <input dt="" class=" user_type checkboxIp" type="checkbox" name="user_type[]" id="b2as_useruser_type3" value="3">                
                               <label for="b2as_useruser_type3"></label>                    
                             </div>                                        
                             <label for="b2as_useruser_type3" class="lbllbl" style="color: #666; font-size: 14px;">Hotel CRS </label>                
                           </div>
                        

                        </div>
                         
                      </div>
                    </div>

                  </div>
                  
                  <!--<div class="col-sm-4 nopad">
                    <div class="tnlepasport_b2b upload_wrap wrap_space">
                      <div class="label_form">Profile Image</div>
                      <div class="uplod_image"  style="background-image:url(<?=$GLOBALS['CI']->template->template_images('agent_demo.png')?>)">
                        <input type="file" id="profile_img_id" name="image" accept="image/*" class="hideupload" />
                        
                      </div>
                      
                    </div>
                  </div>-->

                </div>
              </div>
              <div class="clearfix"></div>
              
              <div class="each_sections">
                <div class="sec_heading"><strong>2</strong>Company Details</div>
                <div class="inside_regwrp">
                  
                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Company Name <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="text" name="company_name"  placeholder="Company name" value="<?php echo set_value('company_name'); ?>" class="input_form alpha_space _guest_validate_field"s maxlength="45" required="required" />
                          
                        </div>
                         <?php if(!empty(form_error('company_name'))) { ?>
                        <div class="agent_error"><?php echo form_error('company_name');?></div>
                        <?php } ?>
                      </div>
                    </div>

                    <?php //if($active_data['api_country_list_fk'] == 92) { ?>
                  
                    <?php //} ?>
                    
                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Address <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <textarea class="input_textarea _guest_validate_field" name="address" placeholder="Address" required><?php echo set_value('address'); ?></textarea>
                          
                        </div>
                         <?php if(!empty(form_error('address'))) { ?>
                        <div class="agent_error"><?php echo form_error('address');?></div>
                        <?php } ?>
                      </div>
                    </div>
                    
                    
                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Country <span class="text-danger">*</span></div>
                        <div class="select_wrap">
                        <?php 
                          if(empty(set_value('country')) == false) {
                            $default_country = set_value('country');
                          } else {
                            $default_country = $active_data['api_country_list_fk'];
                          }
                          if(empty(set_value('city')) == false) {
                            $default_city = set_value('city');
                          } else {
                            $default_city = $active_data['api_city_list_fk'];
                          }
                        ?>
                            <select name="country" id="country_id" class="select_form" required>
                              <option value="">Select Country</option>
                              <?=generate_options($country_list, array($default_country));?>
                            </select>
                          </div>
                         <?php if(!empty(form_error('country'))) { ?>
                        <div class="agent_error"><?php echo form_error('country');?></div>
                        <?php } ?>
                      </div>
                    </div>

                    <div class="col-sm-6 nopad pan_data">
                      <div class="wrap_space">
                        <div class="label_form">State <span class="text-danger">*</span></div>
                        <div class="select_wrap">
                             <select name="state" id="state_id" class="select_form" required>
                              <option value="">Select State</option>
                              <?=generate_options($state_list, array($default_state));?>
                            </select>
                            
                          </div>
                          <?php if(!empty(form_error('state'))) { ?>
                        <div class="agent_error"><?php echo form_error('state');?></div>
                        <?php } ?>
                      </div>
                    </div>

                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">City <span class="text-danger">*</span></div>
                        <div class="select_wrap">
                            <select name="city"  id="city_id" class="select_form" required>
                              <option value = '' selected="">Select City</option>
                            </select>
                            
                          </div>
                          <?php if(!empty(form_error('city'))) { ?>
                        <div class="agent_error"><?php echo form_error('city');?></div>
                        <?php } ?>
                      </div>
                    </div>
                      <div class="pan_data" style="width: 100%;float: left;" >
                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Company Reg No <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="text" name="pan_number" placeholder="Company Reg No" value="<?php echo set_value('pan_number'); ?>" id="pan_number" class="input_form" maxlength="10"/>
                          
                        </div>
                         <?php if(!empty(form_error('pan_number'))) { ?>
                        <div class="agent_error"><?php echo form_error('pan_number');?></div>
                        <?php } ?>
                      </div>
                    </div>
                    
                    
                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Travel Licence Number</div>
                        <div class="div_wrap">
                          <input type="text" name="pan_holdername" placeholder="Travel Licence Number"  value="<?php echo set_value('pan_holdername'); ?>" class="input_form" maxlength="15" />
                          
                        </div>
                       <?php if(!empty(form_error('pan_holdername'))) { ?>
                        <div class="agent_error"><?php echo form_error('pan_holdername');?></div>
                        <?php } ?>
                      </div>
                    </div>
                    

                    <div class="col-sm-6 nopad">
                     <div class="wrap_space">
                      <div class="label_form">Company Registration ID Image</div>
                       <div class="div_wrap">
                        <input type="file"  name="panimage" accept="image/*" />
                        </div>
                      </div>
                                  
                    </div>

                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                      <div class="label_form">Travel Certifications</div>
                       <div class="div_wrap">
                        <input type="file"  name="gstimage" accept="image/*" />
                     </div> 
                     </div>                 
                  </div>
                  </div>
                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Postal Code (ZIP)<span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="text" name="pin_code" placeholder="Postal Code (ZIP)"  value="<?php echo set_value('pin_code'); ?>" class="input_form _guest_validate_field numeric" maxlength="10" required />
                          
                        </div>
                       <?php if(!empty(form_error('pin_code'))) { ?>
                        <div class="agent_error"><?php echo form_error('pin_code');?></div>
                        <?php } ?>
                      </div>
                    </div>
                    
                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Office Phone <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="text" name="office_phone" placeholder="Office Number" value="<?php echo set_value('office_phone'); ?>" class="input_form numeric _guest_validate_field"  required="required" maxlength="10">
                          
                        </div>
                        <?php if(!empty(form_error('office_phone'))) { ?>
                        <div class="agent_error"><?php echo form_error('office_phone');?></div>
                        <?php } ?>
                      </div>
                    </div>


                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Company website link<span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="text" name="comp_website_link" placeholder="Company website link" value="<?php echo set_value('comp_website_link'); ?>" class="input_form _guest_validate_field"  required="required" >
                          
                        </div>
                        <?php if(!empty(form_error('comp_website_link'))) { ?>
                        <div class="agent_error"><?php echo form_error('comp_website_link');?></div>
                        <?php } ?>
                      </div>
                    </div>

                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Company Email ID<span class="text-danger">*</span></div>
                        <div class="div_wrap">
                           

                           <input type="email" id="comp_email" name="comp_email" maxlength="80" placeholder="Company Email ID" onblur="validateemail()" value="<?php echo set_value('comp_email'); ?>" class="input_form email" required="required"/>

                          
                        </div>
                        <?php if(!empty(form_error('comp_email'))) { ?>
                        <div class="agent_error"><?php echo form_error('comp_email');?></div>
                        <?php } ?>
                      </div>
                    </div>
                    
                    
                    
                </div>
              </div>
        
              
              
              
              
              <div class="clearfix"></div>
              
              <div class="each_sections">
                <div class="sec_heading"> <strong>3</strong>Login Info</div>
                <div class="inside_regwrp">
                  <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">User Name <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="email" id="user_name" readonly name="user_name" placeholder="Email" maxlength="80" value="<?php echo set_value('email'); ?>" class="input_form email" required="required"/>
                        </div>
                        <?php if(!empty(form_error('user_name'))) { ?>
                        <div class="agent_error"><?php echo form_error('user_name');?></div>
                        <?php } ?>
                      </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Password <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="password" name="password" placeholder="Password" value="<?php echo set_value('password'); ?>" class="input_form pass _guest_validate_field" required="required"/>
                        </div>
                        <?php if(!empty(form_error('password'))) { ?>
                        <div class="agent_error"><?php echo form_error('password');?></div>
                        <?php } ?>
                      </div>
                    </div>
                    
                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Confirm Password <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="password" name="password_c" placeholder="Retype password" value="<?php echo set_value('password_c'); ?>" class="input_form pass _guest_validate_field" required="required"/>
                        </div>
                          <?php if(!empty(form_error('password_c'))) { ?>
                        <div class="agent_error"><?php echo form_error('password_c');?></div>
                        <?php } ?>
                      </div>
                    </div>
                    
                </div>
              </div>
              
              
              
              <div class="clearfix"></div>
              
              
              <div class="submitsection">
              
                <div class="acceptrms">
                  <div class="squaredThree">
                      <input type="checkbox" value="<?php echo set_value('term_condition'); ?>" required="" name="term_condition" class="airlinecheckbox validate_user_register _guest_validate_field" id="term_condition" >
                      <label for="term_condition"></label>
                    </div>
                    <?php
$base='url';
                    ?>
                    <label for="term_condition" class="lbllbl">I accept the <a target="_balnk" href="<?=$base?>terms-conditions">agency terms and conditions</a></label>
                </div>
                
                <div class="clearfix"></div>
                
                <button type="submit" class="btnreg_agent">Register</button>
                
              </div>
              
              
              

              
            </form>
          </div>
          
          
          
          <div data-role="tabpanel" class="tab-pane clearfix" id="edit_user_profile"> </div>
        </div>
        <!-- Edit User Profile Ends--> 
        
      </div>
    </div>
  </div>
</div>
<?php
$datepicker = array(array('date_of_birth', PAST_DATE));
$GLOBALS['CI']->current_page->set_datepicker($datepicker);
?>


<script type="text/javascript">

function validateemail() {



var request;

try {

request= new XMLHttpRequest();

}

catch (tryMicrosoft) {

try {

request= new ActiveXObject("Msxml2.XMLHTTP");
}

catch (otherMicrosoft) 
{
try {
request= new ActiveXObject("Microsoft.XMLHTTP");
}

catch (failed) {
request= null;
}
}
}


var urll='<?php base_url()?>';
// alert(urll);

var url= <?php base_url()?>'emailvalidate';
// alert(url);
var emailaddress= document.getElementById("user_email").value;
// alert(emailaddress);
var vars= "email="+emailaddress;
request.open("POST", url, true);

request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

request.onreadystatechange= function() {
if (request.readyState == 4 && request.status == 200) {
  var return_data=  request.responseText;
  // alert(return_data);
  if(return_data=='exists')

{
  
$('#uniqmail').show();
 document.getElementById("uniqmail").innerHTML= '<p style="color:red">This email alredy exists. please enter another email</p>';
}
else
{
  $('#uniqmail').hide();
}
}
}

request.send(vars);
}
</script>
<script type="text/javascript">
var default_city = '<?=$default_city;?>';
  $(document).ready(function(){
    
    get_city_list();
    //get the state
    $('#country_id').on('change', function(){
      country_origion = $(this).val();
      if(country_origion == '92'){
        $(".pan_data").css("display", "block");
        $("#pan_number").addClass('_guest_validate_field');
      }
      else{
        $(".pan_data").css("display", "none");
        $("#pan_number").removeClass('_guest_validate_field');
      }
      get_city_list();
    });
    function get_city_list(country_id)
    {
      var country_id = $('#country_id').val();
      // alert(country_id);
      if(country_id == ''){
          $("#city_id").empty().html('<option value = "" selected="">Select City</option>');
         return false;
         } 
      $.post(app_base_url+'index.php/ajax/get_city_lists_supplier',{country_id : country_id},function( data ) {
          // console.log(data);
         $("#city_id").empty().html(data);
         $('#city_id').val(default_city);
         $("#city_id").val("");
      });
    }

    //Auto populate the user email to the user name
    $('#user_email').on('blur', function(){
      var user_email = $(this).val().trim();
      if(user_email !='') {
        $('#user_name').val(user_email);
      }
    });

      $(".btnreg_agent").on('click',function(){
                  
              var count = 0;
              $('._guest_validate_field').each( function () {
                      if(this.value.trim() == '') {
                             count++;
                             $(this).addClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
                      }
                    

                     });
                if(count > 0 || $("uniqmail").is(":visible")){


                        return false;
                      }
      })


$('._guest_validate_field').focus( function () {
    $(this).removeClass('invalid-ip');
  });


    $("#term_condition").on('click',function(){
      if($('#term_condition').is(':checked')){
      $('#term_condition').val('1');
          }else{
      $('#term_condition').val('0');
          } 

})

    
    
    });

//image preview





$(function() {
    $("#profile_img_id").on("change", function()
    {
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
 
        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file
 
            reader.onloadend = function(){ // set image data as background of div
                $(".uplod_image").css("background-image", "url("+this.result+")");
            }
        }else{
          $("#profile_img_id").val('');
        }
    });
});

</script> 
<?php if($default_country!=92){ ?>
<script type="text/javascript">
  $(document).ready(function() {
    $(".pan_data").css("display", "none");
    $("#pan_number").removeClass('_guest_validate_field');
  });
</script>



 <script>

        var privillages="<?php echo $privillages;?>";
         // alert( privillages.split(","));
         $(document).ready(function() {     
         alert("23");       
        var res = privillages.split(",");
            console.log(res);

            if(res.includes("1"))
           {
                $("#b2as_user_edituser_type1").attr("checked",true);
           }

           if(res.includes("2"))
           {
                $("#b2as_user_edituser_type2").attr("checked",true);
           }


            if(res.includes("3"))
           {
                $("#b2as_user_edituser_type3").attr("checked",true);
           }

            if(res.includes("4"))
           {
                $("#b2as_user_edituser_type4").attr("checked",true);
           }

            
         });



    $(document).ready(function() {
        
        $(".user_type").click(function()
        {
          alert(123);
            check_crs_type();            
        });
        check_crs_type();  
    });

    function check_crs_type()
        {
            var crs_len=$('[name="user_type[]"]:checked').length;

            if(crs_len>0)
            {
                $('.user_type').attr('required',false);
            }else
            {
                 $('.user_type').attr('required',true);
            }
        }
</script>


<?php } ?>



