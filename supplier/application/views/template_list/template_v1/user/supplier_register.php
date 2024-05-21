 
<?php 
// echo "string";
  $privillages=$supplier_crs_privilage;
  //debug($privillages);exit;
  $default_view = $GLOBALS ['CI']->uri->segment ( 3 );
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
 
  .error_priv{
    display: none;
  }

.invalid-ip
{
{
    border: 1px solid #e3e3e3 !!important;
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

  <?php if(!empty($this->session->flashdata('error_message'))) {?>
  <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> <strong><?php echo $this->session->flashdata('error_message'); ?></strong> </div>
  <?php } ?>
  <div class="tab-content sidewise_tab">
    <div data-role="tabpanel" class="tab-pane active clearfix" id="profile">
      <div class="agent_regtr">

        <img class="ful_logo" style=" display: table; margin: 0px auto 20px auto;width: 250px;" src="https://travelfreetravels.com/extras/custom/TMX6244821650276433/images/TMX3644721637051232logo-loginpg.png" alt="" />
        
        <div class="agentreg_heading"> SUPPLIER REGISTER
        
        <a href="<?=base_url()?>" class="gobacklink">Back</a> 
        
        </div>

        <div class="clearfix"></div>
        <!-- Edit User Profile starts-->
        <div class="tab-content">
          <div data-role="tabpanel filldiv" class="tab-pane active" id="show_user_profile">
            <form action="<?=base_url().'index.php/user/supplierRegister'; ?>" method="post" name="edit_user_form" id="register_user_form1" enctype="multipart/form-data" autocomplete="off">

              <div class="each_sections" style="display:none">
                <div class="sec_heading"><strong>1</strong>Services</div>
                <div class="inside_regwrp">

              <div class="col-sm-12 nopad">
                      <div class="wrap_space" style="width: 100%;" >
                        <div class="label_form">Select Service<span class="text-danger">*</span> </div>
                        <div class="div_wrap" >

                          <div class="col-md-3 nopad">                  
                            <div class="">                      
                              <input dt="" class="service_sel checkboxIp" type="checkbox" name="service_type[]" id="b2as_useruser_type1" value="tour_crs" <?php echo ($default_view == 'tour_crs') ?  "checked" : "" ;  ?>>                
                               <label for="b2as_useruser_type1"></label>                    
                             </div>                                        
                             <label for="b2as_useruser_type1" class="lbllbl" style="color: #666; font-size: 14px;">Tour CRS</label>                
                           </div>
                        <div class="col-md-3 nopad">                  
                            <div class="">                      
                              <input dt="" class="service_sel checkboxIp" type="checkbox" name="service_type[]" id="b2as_useruser_type5" value="villas_apts" <?php echo ($default_view == 'villas_apts') ?  "checked" : "" ;  ?>>                
                               <label for="b2as_useruser_type5"></label>                    
                             </div>                                        
                             <label for="b2as_useruser_type5" class="lbllbl" style="color: #666; font-size: 14px;">Villas & Apts/Hotels</label>                
                           </div>
                           <div class="col-md-3 nopad">                  
                            <div class="">                      
                              <input dt="" class=" service_sel checkboxIp" type="checkbox" name="service_type[]" id="b2as_useruser_type6" value="private_transfer" <?php echo ($default_view == 'private_transfer') ?  "checked" : "" ;  ?>>                
                               <label for="b2as_useruser_type6"></label>                    
                             </div>                                        
                             <label for="b2as_useruser_type6" class="lbllbl" style="color: #666; font-size: 14px;">Private  Transfer</label>                
                           </div>
                           <div class="col-md-3 nopad">                  
                            <div class="">                      
                              <input dt="" class=" service_sel checkboxIp" type="checkbox" name="service_type[]" id="b2as_useruser_type7" value="private_car" <?php echo ($default_view == 'private_car') ?  "checked" : "" ;  ?>>                
                               <label for="b2as_useruser_type7"></label>                    
                             </div>                                        
                             <label for="b2as_useruser_type7" class="lbllbl" style="color: #666; font-size: 14px;">Private Car</label>                
                           </div>
                            <div class="clearfix"></div>
                           <!--<div class="col-md-3 nopad">                  
                            <div class="">                      
                              <input dt="" class=" service_sel checkboxIp" type="checkbox" name="service_type[]" id="b2as_useruser_type8" value="private_jet" <?php echo ($default_view == 'private_jet') ?  "checked" : "" ;  ?>>                
                               <label for="b2as_useruser_type8"></label>                    
                             </div>                                        
                             <label for="b2as_useruser_type8" class="lbllbl" style="color: #666; font-size: 14px;">Private Jet</label>                
                           </div>-->

                          <span class="text-danger error_priv">Select the privilege</span>
                        </div>
                       
                      </div>
                     <!-- <?php if(!empty(form_error('user_type[]'))) { ?>
                          <div class="agent_error"><?php //echo form_error('user_type[]');?></div>
                       <?php } ?>-->
                    </div>

                  </div>

                </div>
                <div class="clearfix"></div>

                <!-----Tourcrs--->
              <div class="tour_crs box selectt" style="<?php echo ($default_view == 'tour_crs') ?  "display: block" : "display: none" ;  ?>">
                <div class="each_sections">
                  <div class="sec_heading">Tour Supplier Info</div>
                    <div class="inside_regwrp">
                      <div class="col-sm-12 nopad">
                        <div class="wrap_space" style="width: 100%;">
                          <div class="div_wrap">
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Company Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                      <input type="text" name="tour_company_name"  placeholder="Company Type" value="<?php echo set_value('company_type'); ?>" id="company_type" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                           <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Authorised Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="tour_authorised_person"  placeholder="Authorised person" value="<?php echo set_value('company_name'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Contact Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="tour_contact_person"  placeholder="Contact person" value="<?php echo set_value('contact_person'); ?>" id="contact_person" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Supplier Site<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="tour_supplier_site"  placeholder="Supplier site" value="<?php echo set_value('company_name'); ?>" id="Supplier_site" class="input_form " maxlength="" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Country <span class="text-danger"></span></div>
                        <div class="select_wrap">
                        <?php 
                          if(empty(set_value('tour_country')) == false) {
                            $default_country = set_value('tour_country');
                          } else {
                            //$default_country = $active_data['api_country_list_fk'];
                            $default_country = 212;
                          }
                          if(empty(set_value('city')) == false) {
                            $default_city = set_value('city');
                          } else {
                            // $default_city = $active_data['api_city_list_fk'];
                            $default_city = 212;
                          }
                        ?>
                            <select name="tour_country" id="tour_country_id" class="select_form ">
                              <option value="">Select Country</option>
                              <?=generate_options($country_list, $default_country);?>
                            </select>
                          </div>
                        <!-- <?php if(!empty(form_error('country'))) { ?>
                        <div class="agent_error"><?php //echo form_error('country');?></div>
                        <?php } ?>-->
                      </div>
                    </div>
                    <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Business Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="tour_business_type"  placeholder="Business Type" value="<?php echo set_value('jet_business_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                            <div class="wrap_space">
                              <div class="label_form">Tour Operators<span class="text-danger"></span></div>
                                <div class="select_wrap">
                                <select name="tour_operator" class="select_form noborderit">
                                 <option value="">Select</option>
                                 <option value="Inbound">Inbound Tour Operator</option>
                                 <option value="Outbound">Outbound Tour Operator</option>
                                 <option value="Domestic">Domestic Tour Operator</option>
                                 <option value="Ground">Ground Tour Operator</option>
                                 
                            </select>
                            
                          </div>
                             </div>
                        </div>
                            <!-- <div class="col-sm-12 nopad">
                              <div class="wrap_space" style="width: 100%;">
                                <div class="label_form">Business Type<span class="text-danger">*</span> </div>
                                  <div class="div_wrap">
                                    <div class="col-md-3 nopad">                  
                                      <div class="">                      
                                        <input dt="" class=" user_type checkboxIp businesstype_check" type="radio" name="tour_business_type" id="b2as_useruser_type1" value="tour_crs">                
                                        <label for="b2as_useruser_type1"></label>                    
                                     </div>                                        
                                      <label for="b2as_useruser_type1" class="lbllbl" style="color: #666; font-size: 14px;">Corporation</label>                
                                   </div>
                                <div class="col-md-3 nopad">                  
                                  <div class="">                      
                                    <input dt="" class=" user_type checkboxIp businesstype_check" type="radio" name="tour_business_type" id="b2as_useruser_type5" value="villas_apts">                
                                       <label for="b2as_useruser_type5"></label>                    
                                  </div>                                        
                                    <label for="b2as_useruser_type5" class="lbllbl" style="color: #666; font-size: 14px;">Limited Partnership</label>                
                                </div>
                                <div class="col-md-3 nopad">                  
                                  <div class="">                      
                                    <input dt="" class=" user_type checkboxIp businesstype_check" type="radio" name="tour_business_type" id="b2as_useruser_type6" value="private_transfer">                
                                      <label for="b2as_useruser_type6"></label>                    
                                  </div>                                        
                                     <label for="b2as_useruser_type6" class="lbllbl" style="color: #666; font-size: 14px;">Others</label>                
                                </div>
                                </div>
                              </div>
                            </div> -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
               </div>
             <div class="clearfix"></div> 
    <!-----Tourcrs--->   
     <!----villas_apts&hotel------->

      <div class="villas_apts box selectt" style="<?php echo ($default_view == 'villas_apts') ?  "display: block" : "display: none" ;  ?>">
                <div class="each_sections">
                  <div class="sec_heading">Villas & Apts/Hotels Supplier Info</div>
                    <div class="inside_regwrp">
                      <div class="col-sm-12 nopad">
                        <div class="wrap_space" style="width: 100%;">
                          <div class="div_wrap">
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Company Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                      <input type="text" name="hotel_company_name"  placeholder="Company Type" value="<?php echo set_value('hotel_company_name'); ?>" id="company_type" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                           <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Authorised Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="hotel_authorised_person"  placeholder="Authorised person" value="<?php echo set_value('hotel_authorised_person'); ?>" id="company_name" class="input_form alpha_space " maxlength="45"  />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Contact Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="hotel_contact_person"  placeholder="Contact person" value="<?php echo set_value('hotel_contact_person'); ?>" id="contact_person" class="input_form alpha_space " maxlength="45"  />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Star Rating<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="hotel_star_rating"  placeholder="Star Rating" value="<?php echo set_value('hotel_star_rating'); ?>" id="star_rating" class="input_form alpha_space " maxlength="45"  />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Number of rooms<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="hotel_num_room"  placeholder="Number of rooms" value="<?php echo set_value('hotel_num_room'); ?>" id="star_rating" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Supplier Site<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="hotel_supplier_site"  placeholder="Supplier site" value="<?php echo set_value('hotel_supplier_site'); ?>" id="Supplier_site" class="input_form " maxlength=""  />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Country <span class="text-danger"></span></div>
                        <div class="select_wrap">
                        <?php 
                          if(empty(set_value('hotel_country')) == false) {
                            $default_country = set_value('hotel_country');
                          } else {
                            //$default_country = $active_data['api_country_list_fk'];
                            $default_country = 212;
                          }
                          if(empty(set_value('city')) == false) {
                            $default_city = set_value('city');
                          } else {
                            // $default_city = $active_data['api_city_list_fk'];
                            $default_city = 212;
                          }
                        ?>
                            <select name="hotel_country" id="hotel_country_id" class="select_form " >
                              <option value="">Select Country</option>
                              <?=generate_options($country_list, $default_country);?>
                            </select>
                          </div>
                        <!-- <?php if(!empty(form_error('country'))) { ?>
                        <div class="agent_error"><?php //echo form_error('country');?></div>
                        <?php } ?>-->
                      </div>
                    </div>
                    <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Business Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="hotel_business_type"  placeholder="Business Type" value="<?php echo set_value('hotel_business_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            
                            <!-- <div class="col-sm-12 nopad">
                              <div class="wrap_space" style="width: 100%;">
                                <div class="label_form">Business Type<span class="text-danger">*</span> </div>
                                  <div class="div_wrap">
                                    <div class="col-md-3 nopad">                  
                                      <div class="">                      
                                        <input dt="" class=" user_type  businesstype_check" type="radio" name="hotel_business_type" id="b2as_useruser_type1" value="tour_crs" required="">                
                                        <label for="b2as_useruser_type1"></label>                    
                                     </div>                                        
                                      <label for="b2as_useruser_type1" class="lbllbl" style="color: #666; font-size: 14px;">Corporation</label>                
                                   </div>
                                <div class="col-md-3 nopad">                  
                                  <div class="">                      
                                    <input dt="" class=" user_type businesstype_check" type="radio" name="hotel_business_type" id="b2as_useruser_type5" value="villas_apts">                
                                       <label for="b2as_useruser_type5"></label>                    
                                  </div>                                        
                                    <label for="b2as_useruser_type5" class="lbllbl" style="color: #666; font-size: 14px;">Limited Partnership</label>                
                                </div>
                                <div class="col-md-3 nopad">                  
                                  <div class="">                      
                                    <input dt="" class=" user_type businesstype_check" type="radio" name="hotel_business_type" id="b2as_useruser_type6" value="private_transfer">                
                                      <label for="b2as_useruser_type6"></label>                    
                                  </div>                                        
                                     <label for="b2as_useruser_type6" class="lbllbl" style="color: #666; font-size: 14px;">Others</label>                
                                </div>
                                </div>
                              </div>
                            </div> -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
               </div>
             <div class="clearfix"></div>


             <!----villas_apts&hotel------>
              <!----privatetransfer------>

              <div class="private_transfer box selectt" style="<?php echo ($default_view == 'private_transfer') ?  "display: block" : "display: none" ;  ?>">
                <div class="each_sections">
                  <div class="sec_heading">Private Transfer Supplier Info</div>
                    <div class="inside_regwrp">
                      <div class="col-sm-12 nopad">
                        <div class="wrap_space" style="width: 100%;">
                          <div class="div_wrap">
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Company Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                      <input type="text" name="transfer_company_name"  placeholder="Company Type" value="<?php echo set_value('transfer_company_name'); ?>" id="company_type" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                           <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Authorised Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="transfer_authorised_person"  placeholder="Authorised person" value="<?php echo set_value('transfer_authorised_person'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Contact Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="transfer_contact_person"  placeholder="Contact person" value="<?php echo set_value('transfer_contact_person'); ?>" id="contact_person" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Supplier Site<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="transfer_supplier_site"  placeholder="Supplier site" value="<?php echo set_value('transfer_supplier_site'); ?>" id="Supplier_site" class="input_form " maxlength="" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Country <span class="text-danger">*</span></div>
                        <div class="select_wrap">
                        <?php 
                          if(empty(set_value('transfer_country')) == false) {
                            $default_country = set_value('transfer_country');
                          } else {
                            //$default_country = $active_data['api_country_list_fk'];
                            $default_country = 212;
                          }
                          if(empty(set_value('city')) == false) {
                            $default_city = set_value('city');
                          } else {
                            // $default_city = $active_data['api_city_list_fk'];
                            $default_city = 212;
                          }
                        ?>
                            <select name="transfer_country" id="transfer_country_id" class="select_form " >
                              <option value="">Select Country</option>
                              <?=generate_options($country_list, $default_country);?>
                            </select>
                          </div>
                        <!-- <?php if(!empty(form_error('country'))) { ?>
                        <div class="agent_error"><?php //echo form_error('country');?></div>
                        <?php } ?>-->
                      </div>
                    </div>
                    <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Business Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="transfer_business_type"  placeholder="Business Type" value="<?php echo set_value('transfer_business_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                            <div class="wrap_space">
                              <div class="label_form">Transfer Type<span class="text-danger"></span></div>
                                <div class="div_wrap">
                                  <input type="text" name="transfer_type"  placeholder="Transfer Type" value="<?php echo set_value('transfer_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                </div>
                               <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                <?php } ?>-->
                             </div>
                        </div>
                        <div class="col-sm-6 nopad">
                            <div class="wrap_space">
                              <div class="label_form">Quantity Of Transfer<span class="text-danger"></span></div>
                                <div class="div_wrap">
                                  <input type="text" name="transfer_quantity"  placeholder="Quantity Of Transfer" value="<?php echo set_value('transfer_quantity'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                </div>
                               <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                <?php } ?>-->
                             </div>
                        </div>
                            <!-- <div class="col-sm-12 nopad">
                              <div class="wrap_space" style="width: 100%;">
                                <div class="label_form">Business Type<span class="text-danger">*</span> </div>
                                  <div class="div_wrap">
                                    <div class="col-md-3 nopad">                  
                                      <div class="">                      
                                        <input dt="" class=" user_type businesstype_check" type="radio" name="transfer_business_type" id="b2as_useruser_type1" value="tour_crs">                
                                        <label for="b2as_useruser_type1"></label>                    
                                     </div>                                        
                                      <label for="b2as_useruser_type1" class="lbllbl" style="color: #666; font-size: 14px;">Corporation</label>                
                                   </div>
                                <div class="col-md-3 nopad">                  
                                  <div class="">                      
                                    <input dt="" class=" user_type businesstype_check" type="radio" name="transfer_business_type" id="b2as_useruser_type5" value="villas_apts">                
                                       <label for="b2as_useruser_type5"></label>                    
                                  </div>                                        
                                    <label for="b2as_useruser_type5" class="lbllbl" style="color: #666; font-size: 14px;">Limited Partnership</label>                
                                </div>
                                <div class="col-md-3 nopad">                  
                                  <div class="">                      
                                    <input dt="" class=" user_type businesstype_check" type="radio" name="transfer_business_type" id="b2as_useruser_type6" value="private_transfer">                
                                      <label for="b2as_useruser_type6"></label>                    
                                  </div>                                        
                                     <label for="b2as_useruser_type6" class="lbllbl" style="color: #666; font-size: 14px;">Others</label>                
                                </div>
                                </div>
                              </div>
                            </div> -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
               </div>
             <div class="clearfix"></div>

               <!----privatetransfer------>

                <!----privatecar------>

              <div class="private_car box selectt" style="<?php echo ($default_view == 'private_car') ?  "display: block" : "display: none" ;  ?>">
                <div class="each_sections">
                  <div class="sec_heading">Private Car Supplier Info</div>
                    <div class="inside_regwrp">
                      <div class="col-sm-12 nopad">
                        <div class="wrap_space" style="width: 100%;">
                          <div class="div_wrap">
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Company Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                      <input type="text" name="car_company_name"  placeholder="Company Type" value="<?php echo set_value('car_company_name'); ?>" id="company_type" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                           <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Authorised Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="car_authorised_person"  placeholder="Authorised person" value="<?php echo set_value('car_authorised_person'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Contact Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="car_contact_person"  placeholder="Contact person" value="<?php echo set_value('car_contact_person'); ?>" id="contact_person" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Supplier Site<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="car_supplier_site"  placeholder="Supplier site" value="<?php echo set_value('car_supplier_site'); ?>" id="Supplier_site" class="input_form " maxlength="" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Country <span class="text-danger"></span></div>
                        <div class="select_wrap">
                        <?php 
                          if(empty(set_value('car_country')) == false) {
                            $default_country = set_value('car_country');
                          } else {
                            //$default_country = $active_data['api_country_list_fk'];
                            $default_country = 212;
                          }
                          if(empty(set_value('city')) == false) {
                            $default_city = set_value('city');
                          } else {
                            // $default_city = $active_data['api_city_list_fk'];
                            $default_city = 212;
                          }
                        ?>
                            <select name="car_country" id="car_country_id" class="select_form ">
                              <option value="">Select Country</option>
                              <?=generate_options($country_list, $default_country);?>
                            </select>
                          </div>
                        <!-- <?php if(!empty(form_error('country'))) { ?>
                        <div class="agent_error"><?php //echo form_error('country');?></div>
                        <?php } ?>-->
                      </div>
                    </div>
                    <div class="col-sm-6 nopad">
                        <div class="wrap_space">
                          <div class="label_form">Business Type<span class="text-danger"></span></div>
                            <div class="div_wrap">
                              <input type="text" name="car_business_type"  placeholder="Business Type" value="<?php echo set_value('car_business_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                            </div>
                           <!--  <?php if(!empty(form_error('company_name'))) { ?>
                            <div class="agent_error"><?php //echo form_error('company_name');?></div>
                            <?php } ?>-->
                         </div>
                    </div>
                    <div class="col-sm-6 nopad">
                        <div class="wrap_space">
                          <div class="label_form">Car Type<span class="text-danger"></span></div>
                            <div class="div_wrap">
                              <input type="text" name="car_type"  placeholder="Car Type" value="<?php echo set_value('car_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                            </div>
                           <!--  <?php if(!empty(form_error('company_name'))) { ?>
                            <div class="agent_error"><?php //echo form_error('company_name');?></div>
                            <?php } ?>-->
                         </div>
                    </div>
                    <div class="col-sm-6 nopad">
                        <div class="wrap_space">
                          <div class="label_form">Quantity Of Cars<span class="text-danger"></span></div>
                            <div class="div_wrap">
                              <input type="text" name="car_quantity"  placeholder="Quantity Of Cars" value="<?php echo set_value('car_quantity'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                            </div>
                           <!--  <?php if(!empty(form_error('company_name'))) { ?>
                            <div class="agent_error"><?php //echo form_error('company_name');?></div>
                            <?php } ?>-->
                         </div>
                    </div>
                            <!-- <div class="col-sm-12 nopad">
                              <div class="wrap_space" style="width: 100%;">
                                <div class="label_form">Business Type<span class="text-danger">*</span> </div>
                                  <div class="div_wrap">
                                    <div class="col-md-3 nopad">                  
                                      <div class="">                      
                                        <input dt="" class=" user_type businesstype_check" type="radio" name="car_business_type" id="b2as_useruser_type1" value="tour_crs">                
                                        <label for="b2as_useruser_type1"></label>                    
                                     </div>                                        
                                      <label for="b2as_useruser_type1" class="lbllbl" style="color: #666; font-size: 14px;">Corporation</label>                
                                   </div>
                                <div class="col-md-3 nopad">                  
                                  <div class="">                      
                                    <input dt="" class=" user_type businesstype_check" type="radio" name="car_business_type" id="b2as_useruser_type5" value="villas_apts">                
                                       <label for="b2as_useruser_type5"></label>                    
                                  </div>                                        
                                    <label for="b2as_useruser_type5" class="lbllbl" style="color: #666; font-size: 14px;">Limited Partnership</label>                
                                </div>
                                <div class="col-md-3 nopad">                  
                                  <div class="">                      
                                    <input dt="" class=" user_type businesstype_check" type="radio" name="car_business_type" id="b2as_useruser_type6" value="private_transfer">                
                                      <label for="b2as_useruser_type6"></label>                    
                                  </div>                                        
                                     <label for="b2as_useruser_type6" class="lbllbl" style="color: #666; font-size: 14px;">Others</label>                
                                </div>
                                </div>
                              </div>
                            </div> -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
               </div>
             <div class="clearfix"></div>

               <!----privatecar------>

               <!----privatejet------>

               <div class="private_jet box selectt" style="<?php echo ($default_view == 'private_jet') ?  "display: block" : "display: none" ;  ?>">
                <div class="each_sections">
                  <div class="sec_heading">Private Jet Supplier Info</div>
                    <div class="inside_regwrp">
                      <div class="col-sm-12 nopad">
                        <div class="wrap_space" style="width: 100%;">
                          <div class="div_wrap">
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Company Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                      <input type="text" name="jet_company_name"  placeholder="Company Type" value="<?php echo set_value('jet_company_name'); ?>" id="company_type" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                           <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Authorised Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="jet_authorised_person"  placeholder="Authorised person" value="<?php echo set_value('jet_authorised_person'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Contact Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="jet_contact_person"  placeholder="Contact person" value="<?php echo set_value('jet_contact_person'); ?>" id="contact_person" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Supplier Site<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="jet_supplier_site"  placeholder="Supplier site" value="<?php echo set_value('jet_supplier_site'); ?>" id="Supplier_site" class="input_form " maxlength="" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Country <span class="text-danger"></span></div>
                        <div class="select_wrap">
                        <?php 
                          if(empty(set_value('jet_country')) == false) {
                            $default_country = set_value('jet_country');
                          } else {
                            //$default_country = $active_data['api_country_list_fk'];
                            $default_country = 212;
                          }
                          if(empty(set_value('city')) == false) {
                            $default_city = set_value('city');
                          } else {
                            // $default_city = $active_data['api_city_list_fk'];
                            $default_city = 212;
                          }
                        ?>
                            <select name="jet_country" id="jet_country_id" class="select_form ">
                              <option value="">Select Country</option>
                              <?=generate_options($country_list, $default_country);?>
                            </select>
                          </div>
                        <!-- <?php if(!empty(form_error('country'))) { ?>
                        <div class="agent_error"><?php //echo form_error('country');?></div>
                        <?php } ?>-->
                      </div>
                    </div>
                    <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Business Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="jet_business_type"  placeholder="Business Type" value="<?php echo set_value('jet_business_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                        <div class="wrap_space">
                          <div class="label_form">Jet Type<span class="text-danger"></span></div>
                            <div class="div_wrap">
                              <input type="text" name="jet_type"  placeholder="Jet Type" value="<?php echo set_value('jet_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                            </div>
                           <!--  <?php if(!empty(form_error('company_name'))) { ?>
                            <div class="agent_error"><?php //echo form_error('company_name');?></div>
                            <?php } ?>-->
                         </div>
                    </div>
                    <div class="col-sm-6 nopad">
                        <div class="wrap_space">
                          <div class="label_form">Quantity Of Jet<span class="text-danger"></span></div>
                            <div class="div_wrap">
                              <input type="text" name="jet_quantity"  placeholder="Quantity Of Jet" value="<?php echo set_value('jet_quantity'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                            </div>
                           <!--  <?php if(!empty(form_error('company_name'))) { ?>
                            <div class="agent_error"><?php //echo form_error('company_name');?></div>
                            <?php } ?>-->
                         </div>
                    </div>
                            <!-- <div class="col-sm-12 nopad">
                              <div class="wrap_space" style="width: 100%;">
                                <div class="label_form">Business Type<span class="text-danger">*</span> </div>
                                  <div class="div_wrap">
                                    <div class="col-md-3 nopad">                  
                                      <div class="">                      
                                        <input dt="" class=" user_type businesstype_check" type="radio" name="jet_business_type" id="b2as_useruser_type1" value="tour_crs">                
                                        <label for="b2as_useruser_type1"></label>                    
                                     </div>                                        
                                      <label for="b2as_useruser_type1" class="lbllbl" style="color: #666; font-size: 14px;">Corporation</label>                
                                   </div>
                                <div class="col-md-3 nopad">                  
                                  <div class="">                      
                                    <input dt="" class=" user_type businesstype_check" type="radio" name="jet_business_type" id="b2as_useruser_type5" value="villas_apts">                
                                       <label for="b2as_useruser_type5"></label>                    
                                  </div>                                        
                                    <label for="b2as_useruser_type5" class="lbllbl" style="color: #666; font-size: 14px;">Limited Partnership</label>                
                                </div>
                                <div class="col-md-3 nopad">                  
                                  <div class="">                      
                                    <input dt="" class=" user_type businesstype_check" type="radio" name="jet_business_type" id="b2as_useruser_type6" value="private_transfer">                
                                      <label for="b2as_useruser_type6"></label>                    
                                  </div>                                        
                                     <label for="b2as_useruser_type6" class="lbllbl" style="color: #666; font-size: 14px;">Others</label>                
                                </div>
                                </div>
                              </div>
                            </div> -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
               </div>
             <div class="clearfix"></div>

               <!----privatejet------>

              <input class="user_type" type="hidden" name="user_type" id="" value="1">

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
                            <input type="text" name="first_name" id="fname"  placeholder="First name" value="<?php echo set_value('first_name'); ?>" class="input_form alpha_space _guest_validate_field" required />
                          </div>
                        </div>
                         <!--<?php if(!empty(form_error('first_name'))) { ?>
                        <div class="agent_error"><?php// echo form_error('first_name');?></div>
                        <?php } ?>-->
                      </div>
                    </div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Last Name <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="text" name="last_name" placeholder="Last name" value="<?php echo set_value('last_name'); ?>" class="input_form alpha_space _guest_validate_field" id="lname" required="required"/>
                          
                        </div>
                       <!--  <?php if(!empty(form_error('last_name'))) { ?>
                        <div class="agent_error"><?php //echo form_error('last_name');?></div>
                        <?php }?>-->
                      </div>
                    </div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Mobile Number <span class="text-danger">*</span></div>
                        <div class="col-xs-6 nopad">
                          <div class="select_wrap">
                            <select name="country_code" class="select_form noborderit smaltext" required>
                              <option value="+977">Nepal +977</option>
                              <?=
                              generate_options($phone_code_array, (array)@$country_code)?>
                              <option value="+60">Malaysia +60</option>
                         
                            </select>
                          </div>
                        </div>
                        <div class="col-xs-6 nopad">
                          <div class="div_wrap">
                            <input type="text" maxlength="15" minlength="7" name="phone" placeholder="Mobile number" value="<?php echo set_value('phone'); ?>" class="input_form numeric _guest_validate_field"  required="required" id="mob" maxlength="15" minlength="2">
                            
                          </div>
                        </div>
                       <!--   <?php if(!empty(form_error('phone'))) { ?>
                        <div class="agent_error"><?php //echo form_error('phone');?></div>
                        <?php }?>-->
                      </div>
                    </div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Email <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="email" id="user_email" name="email" maxlength="80" placeholder="Email" onblur="" value="<?php echo set_value('email'); ?>" class="input_form email _guest_validate_field" required="required"/>
                          
                        </div>
                        <span id='uniqmail' style='display:none'></span>
                        <!--  <?php if(!empty(form_error('email'))) { ?>
                          <div class="agent_error"><?php //echo form_error('email');?></div>
                       <?php } ?>-->
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
                <div class="sec_heading"><strong>2</strong>Company Details
                <div class="custom01" style="display:none">
  <input type="radio" id="radio02-01" class="ra1" name="demo02"  value="Company" checked /><label for="radio02-01">Company</label>
  <input type="radio" id="radio02-02" class="ra1" name="demo02" value="Private" /><label for="radio02-02">Private</label>
</div>
                </div>
                <div class="inside_regwrp">
                 
                    <div class="col-sm-6 nopad">
                        
                      <div class="wrap_space">
                          
                        <div class="label_form">Company Name <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          
                          <input type="text" name="company_name"  placeholder="Company name" value="<?php echo set_value('company_name'); ?>" id="company_name" class="input_form alpha_space _guest_validate_field" maxlength="45" required="required" />
                          
                        </div>
                       <!--  <?php if(!empty(form_error('company_name'))) { ?>
                        <div class="agent_error"><?php //echo form_error('company_name');?></div>
                        <?php } ?>-->
                      </div>
                    </div>

                    <?php //if($active_data['api_country_list_fk'] == 92) { ?>
                  
                    <?php //} ?>
                    
                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Address <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <textarea class="input_textarea _guest_validate_field" name="address" id="address" placeholder="Address" required><?php echo set_value('address'); ?></textarea>
                          
                        </div>
                     <!--    <?php if(!empty(form_error('address'))) { ?>
                        <div class="agent_error"><?php //echo form_error('address');?></div>
                        <?php } ?>-->
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
                            //$default_country = $active_data['api_country_list_fk'];
                            $default_country = 212;
                          }
                          if(empty(set_value('city')) == false) {
                            $default_city = set_value('city');
                          } else {
                            // $default_city = $active_data['api_city_list_fk'];
                            $default_city = 212;
                          }
                        ?>
                            <select name="country" id="country_id" class="select_form _guest_validate_field" required>
                              <option value="">Select Country</option>
                              <?=generate_options($country_list, $default_country);?>
                            </select>
                          </div>
                        <!-- <?php if(!empty(form_error('country'))) { ?>
                        <div class="agent_error"><?php //echo form_error('country');?></div>
                        <?php } ?>-->
                      </div>
                    </div>

                    <!-- <div class="col-sm-6 nopad state_data">
                      <div class="wrap_space">
                        <div class="label_form">State <span class="text-danger">*</span></div>
                        <div class="select_wrap">
                             <select name="state" id="state_id" class="select_form" required>
                              <option value="">Select State</option>
                              <?=generate_options($state_list, array($default_state));?>
                            </select>
                            
                          </div>
                          <?php if(!empty(form_error('state'))) { ?>
                        <div class="agent_error"><?php //echo form_error('state');?></div>
                        <?php } ?>
                      </div>
                    </div> -->

                    <div class="col-sm-6 nopad pan_datax">
                      <div class="wrap_space state_drop hide">
                        <div class="label_form">State / Province <span class="text-danger">*</span></div>
                        <div class="select_wrap ">
                             <select name="state" id="state_id" class="select_form">
                              <option value="">Select State / Province</option>
                             <?=generate_options($state_list, $default_state);?>
                             <!--  <?=generate_options($state_list);?> -->
                            </select>
                            
                          </div>
                         <!-- <?php if(!empty(form_error('state'))) { ?>
                        <div class="agent_error"><?php //echo form_error('state');?></div>
                        <?php } ?>-->
                      </div>

                       <div class="wrap_space state_txt ">
                        <div class="label_form">State / Province<span class="text-danger">*</span></div>
                        <div class="div_wrap">
                             <input type="text" id="statetxt" name="state_txt" placeholder="State"  value="<?php echo set_value('state_txt'); ?>" class="input_form alpha _guest_validate_field" maxlength="50"/>                            
                          </div>
                     <!--     <?php if(!empty(form_error('state_txt'))) { ?>
                        <div class="agent_error"><?php //echo form_error('state_txt');?></div>
                        <?php } ?>-->
                      </div>

                    </div>

                    <div class="col-sm-12 nopad">
                      <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">City <span class="text-danger">*</span></div>
                        <div class="select_wrap">
                            <select name="city"  id="city_id" class="select_form _guest_validate_field" required>
                              <option value = '' selected="">Select City</option>
                            </select>
                            
                          </div>
                      <!--    <?php if(!empty(form_error('city'))) { ?>
                        <div class="agent_error"><?php //echo form_error('city');?></div>
                        <?php } ?>-->
                      </div>
                      </div>

                      <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Postal Code (ZIP)<span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="text" maxlength="6" name="pin_code" placeholder="Postal Code (ZIP)"  value="<?php echo set_value('pin_code'); ?>" class="input_form _guest_validate_field numeric" maxlength="15" id="pin" required />
                          
                        </div>
                      <!-- <?php if(!empty(form_error('pin_code'))) { ?>
                        <div class="agent_error"><?php //echo form_error('pin_code');?></div>
                        <?php } ?>-->
                      </div>
                    </div>

                    </div>
                      <div class="pan_datax" style="width: 100%;float: left;" >
                    <!-- <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Company Reg No <span class="text-danger non-mandatory">*</span></div>
                        <div class="div_wrap">
                          <input type="text" name="pan_number" placeholder="Company Reg No" value="<?php echo set_value('pan_number'); ?>" id="pan_number" class="input_form  _guest_validate_field" maxlength="50"/>
                          
                        </div>
                        <?php if(!empty(form_error('pan_number'))) { ?>
                        <div class="agent_error"><?php //echo form_error('pan_number');?></div>
                        <?php } ?>
                      </div>
                    </div>-->
                    
                    
                   <!-- <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Travel Licence Number</div>
                        <div class="div_wrap">
                          <input type="text" name="pan_holdername" placeholder="Travel Licence Number"  value="<?php echo set_value('pan_holdername'); ?>"  class="input_form _guest_validate_field" maxlength="50" />
                          
                        </div>
                       <?php if(!empty(form_error('pan_holdername'))) { ?>
                        <div class="agent_error"><?php //echo form_error('pan_holdername');?></div>
                        <?php } ?>
                      </div>
                    </div>-->
                    
                    <div class="clearfix"></div>

                    <!--<div class="col-sm-6 nopad">
                     <div class="wrap_space">
                      <div class="label_form">Company Registration ID</div>
                       <div class="div_wrap">
                        <input type="file"  name="panimage" accept="image/*,.doc, .docx,.txt,.pdf,.jpg" />
                        </div>
                      </div>
                                  
                    </div>

                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                      <div class="label_form">Travel Certifications</div>
                       <div class="div_wrap">
                        <input type="file"  name="gstimage" accept="image/*,.doc, .docx,.txt,.pdf,.jpg" />
                     </div> 
                     </div>                 
                  </div>
                  </div>
                  
                  <div class="col-sm-6 nopad">
                     <div class="wrap_space">
                      <div class="label_form">Upload Driving License / Passport</div>
                       <div class="div_wrap">
                        <input type="file"  name="UploadDriver" accept="image/*,.doc, .docx,.txt,.pdf,.jpg" />
                        </div>
                      </div>
                                  
                    </div>-->

                  <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Office Phone <span class="text-danger non-mandatory">*</span></div>
                        <div class="col-xs-6 nopad">
                          <div class="select_wrap">
                            <select name="ofc_country_code" class="select_form noborderit smaltext" required>
                              <option value="+977">Nepal +977</option>
                              <?=
                              generate_options($phone_code_array, (array)@$country_code)?>
                         
                            </select>
                          </div>
                        </div>
                        <div class="col-xs-6 nopad">
                          <div class="div_wrap">
                            <input type="text"  name="office_phone" placeholder="Office Number" value="<?php echo set_value('office_phone'); ?>" class="input_form numeric _guest_validate_field"   id="ofc_no" maxlength="15" minlength="7">
                            
                          </div>
                        </div>
                        <!--  <?php if(!empty(form_error('office_phone'))) { ?>
                        <div class="agent_error"><?php //echo form_error('office_phone');?></div>
                        <?php }?>-->
                      </div>
                    </div>
                  
                  
                    
                  <!--   <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Office Phone <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="text" name="office_phone" placeholder="Office Number" value="<?php echo set_value('office_phone'); ?>" class="input_form numeric _guest_validate_field"  required="required" maxlength="15">
                          
                        </div>
                        <?php if(!empty(form_error('office_phone'))) { ?>
                        <div class="agent_error"><?php //echo form_error('office_phone');?></div>
                        <?php } ?>
                      </div>
                    </div> -->


                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Company website link<span class="text-danger non-mandatory"></span></div>
                        <div class="div_wrap">
                          <input type="text" id="web_url" name="comp_website_link" placeholder="Company website link" value="<?php echo set_value('comp_website_link'); ?>" class="input_form _guest_validate_field"   >
                          <span style="display:none;" id="email_error" class="name_error"><div class="formerror" style="color:red">Enter a valid website link</div></span>
                        </div>
                       <!-- <?php if(!empty(form_error('comp_website_link'))) { ?>
                        <div class="agent_error"><?php //echo form_error('comp_website_link');?></div>
                        <?php } ?>-->
                      </div>
                    </div>

                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Company Email ID<span class="text-danger non-mandatory"></span></div>
                        <div class="div_wrap">
                           

                           <input type="email" id="comp_email" name="comp_email" maxlength="80" placeholder="Company Email ID" onblur="" value="<?php echo set_value('comp_email'); ?>" class="input_form email _guest_validate_field"/>

                          
                        </div>
                       <!-- <?php if(!empty(form_error('comp_email'))) { ?>
                        <div class="agent_error"><?php //echo form_error('comp_email');?></div>
                        <?php } ?>-->
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
                          <input type="email" id="user_name" readonly name="user_name" placeholder="Username" maxlength="80" value="<?php echo set_value('email'); ?>" class="input_form email _guest_validate_field" required="required"/>
                        </div>
                       <!-- <?php if(!empty(form_error('user_name'))) { ?>
                        <div class="agent_error"><?php //echo form_error('user_name');?></div>
                        <?php } ?>-->
                      </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Password <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="password" name="password" placeholder="Password" value="<?php echo set_value('password'); ?>" class="input_form pass _guest_validate_field" id="pass1" required="required"/>
                        </div>
                        <!--<?php if(!empty(form_error('password'))) { ?>
                        <div class="agent_error"><?php //echo form_error('password');?></div>
                        <?php } ?>-->
                      </div>
                    </div>
                    
                    <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Confirm Password <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="password" name="password_c" placeholder="Retype password" value="<?php echo set_value('password_c'); ?>" class="input_form pass _guest_validate_field"  id="pass2" required="required"/>
                        </div>
                          <!--<?php if(!empty(form_error('password_c'))) { ?>
                        <div class="agent_error"><?php //echo form_error('password_c');?></div>
                        <?php } ?>-->
                      </div>
                    </div>
                    
                </div>
              </div>
              
              
              
              <div class="clearfix"></div>
              
              
              <div class="submitsection suplrgstr">
              
                <div class="acceptrms">
                  <div class="squaredThree">
                      <input type="checkbox" value="<?php echo set_value('term_condition'); ?>" required="" name="term_condition" class="airlinecheckbox validate_user_register _guest_validate_field" id="term_condition" >
                      <label for="term_condition"></label>
                    </div>
                    <?php
$base='url';
                    ?>
                    <label for="term_condition" class="lbllbl">I accept the <a target="_balnk" href="<?=base_url()?>../index.php/general/supplier_term">supplier terms and conditions </a></label>
                </div>
                
                <div class="clearfix"></div>
                <div class="col-md-12 text-center">
                  <button type="submit" value="submit" class="btnreg_agent" id="suppliersubmit">Register</button>
                </div>
                 
                
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
  $('#suppliersubmit').on('click',function () {
    var form = $('#register_user_form');
    var action = $(this).data('action');
    form.attr('action', action);
    form.submit();
});
</script>

<script type="text/javascript">
$("#user_email").bind("keyup change", function(e) {

    var uemail = $(this).val();

   

    if(isEmail(uemail) && uemail != '' ){
    console.log(uemail);
    var urls = '<?php echo base_url()?>index.php/user/emailvalidate';
    console.log(urls);
    //$('#uniqmail').show();
    $.ajax({
    type: "POST",
    url: urls,
    async: true,
    dataType: "html",
    data: ({'email': uemail}),
    beforeSend: function() {
        // setting a timeout
        $("#uniqmail").show();
        $("#uniqmail").html('<p style="color:red">Verifying email...</p>');
    },
   
    success: function(result){
       // alert(result.d);
       if(result == 'unique'){

        $("#uniqmail").hide();
       }else{
        $("#uniqmail").show();
        $("#uniqmail").html('<p style="color:red">Email id already in use.</p>');
       }
    }
  });
  

    }else{

      //console.log(uemail);
      console.log('Invalid email');
      $('#uniqmail').show();
 document.getElementById("uniqmail").innerHTML= '<p style="color:red">Please Enter Valid Email.</p>';
    }
    
  });
/*function validateemail() {



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
}*/

/*
  function validateemail() {

    var uemail = $(this).val();
    console.log(uemail);
  }*/

  function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
    }

  

</script>
<script type="text/javascript">
var default_city = '<?=$default_city;?>';
  $(document).ready(function(){



    $('.alpha_space').keypress(function (e) {
        return true;
    var regex = new RegExp("^[a-zA-Z.@& ]+$");
    var strigChar = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(strigChar)) {
        return true;
    }
    return false
  });
    
    get_city_list();
    //get the state
    /*$('#country_id').on('change', function(){
      country_origion = $(this).val();
      if(country_origion == '92'){
        $(".state_data").css("display", "block");
        //$(".pan_data").css("display", "block");
        $("#pan_number").addClass('_guest_validate_field');
      }
      else{
       $(".state_data").css("display", "none");
       // $(".pan_data").css("display", "none");
        $("#pan_number").removeClass('_guest_validate_field');
      }
      get_city_list();
    });*/

    $('#country_id').on('change', function(){
      country_origion = $(this).val();
      if(country_origion == '92'){
        /*$(".state_drop").css("display", "block");
        $(".state_txt").css("display", "none");*/
        $(".state_drop").removeClass('hide');
        $(".state_txt").addClass('hide');
        $("#pan_number").addClass('_guest_validate_field');
        $("#statetxt").attr("required", false);
        $("#state_id").attr("required", true);
      }
      else{
      
        $(".state_drop").addClass('hide');
        $(".state_txt").removeClass('hide');
        $("#statetxt").attr("required", true);
        $("#state_id").attr("required", false);
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
                             var dee=$(this).attr('name');
                             var dt=$('input[name=demo02]:checked').val();
                           
                            
                                 $(this).addClass('invalid-ip');
                                 $(this).parent().find(".name_error").remove();
                                 $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
                       if(dee=="pan_number" && dt=="Private")
                             {
                                $(this).removeClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                             }
                             if(dee=="pan_holdername" && dt=="Private")
                             {
                                $(this).removeClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                             }
                              if(dee=="office_phone" && dt=="Private")
                             {
                                $(this).removeClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                             }
                              if(dee=="comp_website_link" && dt=="Private")
                             {
                                $(this).removeClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                             }
                             if(dee=="comp_email" && dt=="Private")
                             {
                                $(this).removeClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                             }
                             }
                      else{
                             $(this).removeClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                      }
                    

                     });
                var crs_length=$('[name="user_type[]"]:checked').length;

                // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                    count++;
                    $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                    window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                    $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                // });
                if(count > 0 || $("uniqmail").is(":visible")){


                        //return false;
                        return true;
                      }
      });
      $("#fname").on('keyup',function(){
                  
           
              $('#fname').each( function () {
                      if(this.value.trim() == '') {
                            
                             $(this).addClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
                      }
                      else{
                             $(this).removeClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                      }
                    

                     });
                var crs_length=$('[name="user_type[]"]:checked').length;

                // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                    count++;
                    $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                    window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                    $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                // });
                if(count > 0 || $("uniqmail").is(":visible")){


                        //return false;
                        return false;
                      }
      });
      $("#lname").on('keyup',function(){
                  
           
              $("#lname").each( function () {
                      if(this.value.trim() == '') {
                          
                             $(this).addClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
                      }
                      else{
                             $(this).removeClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                      }
                    

                     });
                var crs_length=$('[name="user_type[]"]:checked').length;

                // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                    count++;
                    $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                    window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                    $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                // });
                if(count > 0 || $("uniqmail").is(":visible")){


                        //return false;
                        return false;
                      }
      });
            $("#mob").on('keyup',function(){
                  
           
              $('#mob').each( function () {
                      if(this.value.trim() == '') {
                            
                             $(this).addClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
                      }
                      else{
                             $(this).removeClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                      }
                    

                     });
                var crs_length=$('[name="user_type[]"]:checked').length;

                // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                    count++;
                    $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                    window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                    $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                // });
                if(count > 0 || $("uniqmail").is(":visible")){


                        //return false;
                        return false;
                      }
      });

                  $("#company_name").on('keyup',function(){
                  
           
              $('#company_name').each( function () {
                      if(this.value.trim() == '') {
                            
                             $(this).addClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
                      }
                      else{
                             $(this).removeClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                      }
                    

                     });
                var crs_length=$('[name="user_type[]"]:checked').length;

                // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                    count++;
                    $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                    window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                    $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                // });
                if(count > 0 || $("uniqmail").is(":visible")){


                        //return false;
                        return false;
                      }
      });
              $("#address").on('keyup',function(){
                  
           
              $('#address').each( function () {
                      if(this.value.trim() == '') {
                            
                             $(this).addClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
                      }
                      else{
                             $(this).removeClass('invalid-ip');
                             $(this).parent().find(".name_error").remove();
                             $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                      }
                    

                     });
                var crs_length=$('[name="user_type[]"]:checked').length;

                // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                    count++;
                    $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                    window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                    $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                // });
                if(count > 0 || $("uniqmail").is(":visible")){


                        //return false;
                        return false;
                      }
      });
                  $("#statetxt").on('keyup',function(){


                  $('#statetxt').each( function () {
                  if(this.value.trim() == '') {

                  $(this).addClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
                  }
                  else{
                  $(this).removeClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                  }


                  });
                  var crs_length=$('[name="user_type[]"]:checked').length;

                  // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                  count++;
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                  window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                  // });
                  if(count > 0 || $("uniqmail").is(":visible")){


                  //return false;
                  return false;
                  }
                  });
                     $("#city_id").on('change',function(){


                  $('#city_id').each( function () {
                  if(this.value.trim() == '') {

                  $(this).addClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
                  }
                  else{
                  $(this).removeClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                  }


                  });
                  var crs_length=$('[name="user_type[]"]:checked').length;

                  // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                  count++;
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                  window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                  // });
                  if(count > 0 || $("uniqmail").is(":visible")){


                  return false;
                  }
                  });

                              $("#pin").on('keyup',function(){


                  $('#pin').each( function () {
                  if(this.value.trim() == '') {

                  $(this).addClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
                  }
                  else{
                  $(this).removeClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                  }


                  });
                  var crs_length=$('[name="user_type[]"]:checked').length;

                  // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                  count++;
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                  window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                  // });
                  if(count > 0 || $("uniqmail").is(":visible")){


                  return false;
                  }
                  });
                   $("#pan_number").on('keyup',function(){


                  $('#pan_number').each( function () {
                  if(this.value.trim() == '') {
                   $(this).addClass('_guest_validate_field');
                  $(this).addClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
                  }
                  else{
                  $(this).removeClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                  }


                  });
                  var crs_length=$('[name="user_type[]"]:checked').length;

                  // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                  count++;
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                  window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                  // });
                  if(count > 0 || $("uniqmail").is(":visible")){


                  return false;
                  }
                  });    
                  
                      $("#lno").on('keyup',function(){


                  $('#lno').each( function () {
                   
                  if(this.value.trim() == '') {
         if($('input[name=demo02]:checked').val()=="Company")
         {
                  $(this).addClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
         }
                  }
                  else{
                  $(this).removeClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                  }


                  });
                  var crs_length=$('[name="user_type[]"]:checked').length;

                  // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                  count++;
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                  window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                  // });
                  if(count > 0 || $("uniqmail").is(":visible")){


                  return false;
                  }
                  });    

                               $("#ofc_no").on('keyup',function(){


                  $('#ofc_no').each( function () {
                  if(this.value.trim() == '') {
 if($('input[name=demo02]:checked').val()=="Company")
         {
                  $(this).addClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
         }
                 }
                  else{
                  $(this).removeClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                  }


                  });
                  var crs_length=$('[name="user_type[]"]:checked').length;

                  // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                  count++;
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                  window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                  // });
                  if(count > 0 || $("uniqmail").is(":visible")){


                  return false;
                  }
                  });   
                 $("#web_url").on('keyup',function(){
 $(this).removeClass('invalid-ip');

                  $('#web_url').each( function () {
                  if(this.value.trim() == '') {
                   // return true;
                    if($('input[name=demo02]:checked').val()=="Company")
         {
                  $(this).addClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
         }
                  }
                  else{
                  $(this).removeClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                  }


                  });
                  var crs_length=$('[name="user_type[]"]:checked').length;

                  // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                  count++;
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                  window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                  // });
                  if(count > 0 || $("uniqmail").is(":visible")){


                  return false;
                  }
                  });  
                       $("#comp_email").on('keyup',function(){

                //    return true;
                  $('#comp_email').each( function () {
                   
                         $(this).removeClass('invalid-ip');
                  if(this.value.trim() == '') {
        if($('input[name=demo02]:checked').val()=="Company")
         {
                  $(this).addClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
                 
                 }
                 }
                  else{
                  $(this).removeClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                  }


                  });
                  var crs_length=$('[name="user_type[]"]:checked').length;

                  // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                  count++;
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                  window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                  // });
                  if(count > 0 || $("uniqmail").is(":visible")){


                  return false;
                  }
                  });  

                                   $("#user_name").on('keyup',function(){
return true;

                  $('#user_name').each( function () {
                  if(this.value.trim() == '') {

                  $(this).addClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
                  }
                  else{
                  $(this).removeClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                  }


                  });
                  var crs_length=$('[name="user_type[]"]:checked').length;

                  // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                  count++;
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                  window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                  // });
                  if(count > 0 || $("uniqmail").is(":visible")){


                  return false;
                  }
                  });  
   

                             $("#pass1").on('keyup',function(){

return true;
                  $('#pass1').each( function () {
                  if(this.value.trim() == '') {

                  $(this).addClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
                  }
                  else{
                  $(this).removeClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                  }


                  });
                  var crs_length=$('[name="user_type[]"]:checked').length;

                  // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                  count++;
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                  window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                  // });
                  if(count > 0 || $("uniqmail").is(":visible")){


                  return false;
                  }
                  });  
      $("#pass2").on('keyup',function(){

return true;
                  $('#pass2').each( function () {
                  if(this.value.trim() == '') {

                  $(this).addClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
                  }
                  else{
                  $(this).removeClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
                  }


                  });
                  var crs_length=$('[name="user_type[]"]:checked').length;

                  // $('[name="user_type[]"]').each(function(){
                  if(crs_length==0){
                  count++;
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                  window.scrollTo(0, $('[name="user_type[]"]').offset().top);
                  }else{
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                  }
                  // });
                  if(count > 0 || $("uniqmail").is(":visible")){


                  return false;
                  }
                  });  




$('._guest_validate_field').focus( function () {
    $(this).removeClass('invalid-ip');
  });


    $("#term_condition").on('click',function(){
      if($('#term_condition').is(':checked')){
//return true;
      $('#term_condition').val('1');

       $(this).removeClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'></div></span>"); 
          }else{
      $('#term_condition').val('0');
        $(this).addClass('invalid-ip');
                  $(this).parent().find(".name_error").remove();
                  $(this).parent().append("<span class='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"); 
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
   // $("#pan_number").removeClass('_guest_validate_field');
  });
</script>



 <script>

        var privillages="<?php echo $privillages;?>";
         // alert( privillages.split(","));
         $(document).ready(function() {     
         //alert("23");       
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
 if(res.includes("5"))
           {
                $("#b2as_user_edituser_type5").attr("checked",true);
           }
           if(res.includes("6"))
           {
                $("#b2as_user_edituser_type6").attr("checked",true);
           }
           if(res.includes("7"))
           {
                $("#b2as_user_edituser_type7").attr("checked",true);
           }
            
         });



    $(document).ready(function() {
        
        $(".user_type").click(function()
        {
          // alert(123);
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
                 $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').hide();
                return true;
            }else
            {
                 $('.user_type').attr('required',true);
                  $('[name="user_type[]"]:first').parent().parent().parent().children('.error_priv').show();
                 return false;
            }
        }
</script>


<?php } ?>

<script>
function isUrlValid(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}
           

    $('#web_url').keyup(function (e) {
       // return true;
    val = $(this).val();
    if(isUrlValid(val))
    {
      $("#email_error").css("display","none");
    }
    else
    {
      $("#email_error").css("display","block");  
    }
    
    });;

</script>



<script>
$(document).ready(function(){
 $("#radio02-01").on( "click", function(){
    $(".non-mandatory").show();  // checked
});
$("#radio02-02").on( "click", function(){
    $(".non-mandatory").hide();  // unchecked
});   
});

</script>
<script type="text/javascript">
            $(document).ready(function() {
                $('input[type="checkbox"]').click(function() {
                    var inputValue = $(this).attr("value");
                    $("." + inputValue).toggle();
                    //$(".service_sel").removeAttribute("required");
                    $('.service_sel').attr('required', false); 


                });
                $('.businesstype_check').attr('required', false); 
            });
         
        </script>