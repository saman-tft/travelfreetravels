<form name="b2as_user" autocomplete="off" action="<?=base_url()?>index.php/user/b2as_user?<?=$_SERVER['QUERY_STRING']?>" method="POST" enctype="multipart/form-data" id="b2as_user" role="form" class="form-horizontal">
   <fieldset form="b2as_user">
      <legend class="form_legend">User Profile</legend>
      <input name="user_id" type="hidden" id="user_id" class=" user_id hiddenIp" required="" value="<?=isset($user_id)?$user_id:''?>">
      <div class="form-group">
         <label class="col-sm-3 control-label" for="title" form="b2as_user">Title<span class="text-danger">*</span></label>
         <div class="col-sm-6">
            <select required="" dt="PROVAB_SOLID_SB01" name="title" class=" title form-control" id="title" data-container="body" data-toggle="popover" data-original-title="Here To Help" data-placement="bottom" data-trigger="hover focus" data-content="Title Ex:Mr, Miss.">
               <option value="INVALIDIP">Please Select</option>
               <option value="1" <?=($title==1)?'selected':''?> >Mr</option>
               <option value="2" <?=($title==2)?'selected':''?>>Ms</option>
               <option value="3" <?=($title==3)?'selected':''?>>Miss</option>
               <option value="4" <?=($title==4)?'selected':''?>>Master</option>
            </select>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="first_name" form="b2as_user">First Name<span class="text-danger">*</span></label>
         <div class="col-sm-6">
          <input value="<?=isset($first_name)?$first_name:''?>" name="first_name" required="" type="text" placeholder="First Name" class="first_name form-control" id="first_name" ></div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="last_name" form="b2as_user">Last Name<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input value="<?=isset($last_name)?$last_name:''?>" name="last_name" required="" type="text" placeholder="Last Name" class=" last_name form-control" id="last_name">
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="country_code" form="b2as_user">Country Code<span class="text-danger">*</span></label>
         <div class="col-sm-6">
            <select required="" name="country_code" class=" country_code form-control" id="country_code">
               <?=generate_options($phone_code_array, $country_code)?>
            </select>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="phone" form="b2as_user">Phone Number<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input maxlength="15" minlength="7" value="<?=isset($phone)?$phone:''?>" name="phone"  required="" type="text" placeholder="Phone Number" class=" phone form-control" id="phone" ></div>
      </div>
  
      <div class="radio">
        <label class="col-sm-3 control-label" for="status" form="b2as_user">User Status<span class="text-danger">*</span></label>
        <label class="radio-inline" for="b2as_userstatus1">  
          <input <?php if(isset($status) && $status==1){ echo 'checked'; }?> required="" dt="PROVAB_SOLID_B01" class=" status radioIp" type="radio" name="status" id="b2as_userstatus1" value="1">Active
        </label>
        <label class="radio-inline" for="b2as_userstatus0">  <input <?php if(isset($status) && $status==0){ echo 'checked'; }?> required=""  class=" status radioIp" type="radio" name="status" id="b2as_userstatus0" value="0">Inactive</label></div>

      <input name="language_preference" type="hidden" id="language_preference" class=" language_preference hiddenIp" value="">
   </fieldset>

   <fieldset form="b2as_user">  
    <legend class="form_legend">Company Details</legend>   
      <div class="form-group">
         <label class="col-sm-3 control-label" for="compant_name" form="b2as_user">Company Name<span class="text-danger">*</span></label>
         <div class="col-sm-6">
               <input value="<?=isset($agency_name)?$agency_name:''?>" name="agency_name" required="" type="company_name" placeholder="Company Name" class=" form-control" id="company_name"></div>
      </div>
        <div class="form-group">
         <label class="col-sm-3 control-label" for="address" form="b2as_user">Address<span class="text-danger">*</span></label>
         <div class="col-sm-6"><textarea required=""  name="address" id="address" rows="3" class=" address form-control" data-original-title="" title=""><?=isset($address)?$address:''?></textarea></div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="country_id" form="b2as_user">Country</label>
          
         <div class="col-sm-6">
          <?php 
          //debug($country_name);exit();
            $this->db->select('*');
            $this->db->from('api_country_list');
            $this->db->where('origin',$country_name);
            $query=$this->db->get();
            foreach ($query->result_array() as  $value) {
              # code...
            }
            $cname=$value['name'];
           // debug($cname);exit();
              $this->db->select('*');
            $this->db->from('api_state_list');
            $this->db->where('origin',$state);
            $query2=$this->db->get();
            foreach ($query2->result_array() as  $value) {
              # code...
            }
$state_name=$value['en_name'];
           ?>
          <select name="country" id="country_id" class="form-control" required>
            <option value="<?php echo $country_name; ?>" selected><?php echo $cname; ?></option>
              
               <?=generate_options($country_list, $country_code);?> 
          </select>
       </div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="state_name" form="b2as_user">State/Province<span class="text-danger">*</span></label>
         <div class="col-sm-6">
         <input value="<?=isset($state_name)?$state_name:''?>" name="state_name" required="" type="text" placeholder="" class=" state_name alpha form-control" id="state_name"></div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="city_name" form="b2as_user">City<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input value="<?=isset($city_name)?$city_name:''?>" name="city_name" required="" type="text" placeholder="City" class="alpha city_name form-control" id="city_name"></div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="pin_code" form="b2as_user">Postal Code<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input minlength="3" maxlength="6" value="<?=isset($pin_code)?$pin_code:''?>" name="pin_code" required="" type="text" placeholder="" class=" pin_code form-control" id="pin_code" data-original-title="" title=""></div>
      </div>
       <div class="form-group">
         <label class="col-sm-3 control-label" for="pan_number" form="b2as_user">Company Reg.No<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input  value="<?=isset($pan_number)?$pan_number:''?>" name="pan_number" required="" type="text" placeholder="" class=" pan_number form-control" id="pan_number" data-original-title="" title=""></div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="pan_holdername" form="b2as_user">Travel Licence Number<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input maxlength="30" minlength="3" value="<?=isset($pan_holdername)?$pan_holdername:''?>" name="pan_holdername" required="" type="text" placeholder="" class=" pan_holdername form-control" id="pan_holdername" data-original-title="" title=""></div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="pan_holdername" form="b2as_user">Company Reg ID<span class="text-danger">*</span></label>
         <div class="col-sm-6">
          <input type="file" name="panimage" accept="image/*,.doc, .docx,.txt,.pdf" <?php if(!isset($panimage) && $panimage==""){ ?> required <?php }  ?>>
           <?php 
          if(isset($panimage) && $panimage!=""){?>
               <a target="_blank"  href="<?=$this->template->domain_images($panimage)?>" >View file</a>
          <?}
          ?>
          </div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="pan_holdername" form="b2as_user">Travel Certifications<span class="text-danger">*</span></label>
         <div class="col-sm-6">
          <input type="file" name="gstimage" accept="image/*,.doc, .docx,.txt,.pdf">
          <?php 
          if(isset($gstimage) && $gstimage!=""){?>
               <a target="_blank" href="<?=$this->template->domain_images($gstimage)?>" >View file</a>
          <?}
          ?>
          </div>

      </div>
        <div class="form-group">
         <label class="col-sm-3 control-label" for="ofc_country_code" form="b2as_user">Office Phone Code<span class="text-danger">*</span></label>
         <div class="col-sm-6">
            <select required="" name="ofc_country_code" class=" ofc_country_code form-control" id="ofc_country_code" >
               <?=generate_options($phone_code_array, $ofc_country_code)?>
            </select>
         </div>
      </div>
       <div class="form-group">
         <label class="col-sm-3 control-label" for="office_phone" form="b2as_user">Office Phone<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input minlength="7" maxlength="15" value="<?=isset($office_phone)?$office_phone:''?>" name="office_phone" required="" type="text" placeholder="" class=" office_phone phone form-control" id="office_phone" data-original-title="" title=""></div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="comp_website_link" form="b2as_user">Company Website link<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input minlength="3"  value="<?=isset($comp_website_link)?$comp_website_link:''?>" name="comp_website_link" required="" type="text" placeholder="" class=" comp_website_link form-control" id="comp_website_link" data-original-title="" title=""></div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="comp_email" form="b2as_user">Company Email ID<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input  value="<?=isset($comp_email)?$comp_email:''?>" name="comp_email" required="" type="email" placeholder="" class=" comp_email form-control" id="comp_email" data-original-title="" title=""></div>
      </div>
      
   </fieldset>
   <?php 
     function get_crs_checked($module){
          $flag='false';
          foreach ($supplier_privailage as $key => $value) {
              if($module==$value){
                    $flag='true';
              }
          }          
          return $flag;
     }
   ?>
   <fieldset form="b2as_user">
      <legend class="form_legend">Login Details</legend>
           <div class="checkbox">
            <label class="col-sm-3 control-label" for="user_type" form="b2as_user">CRS Type</label>
               <label class="radio-inline" for="b2as_useruser_type1">  
                     <input class=" user_type checkboxIp" type="checkbox" name="user_type[]" id="b2as_useruser_type1" value="1" required="required" <?php if(get_crs_checked(1)=='true'){ echo 'checked'; }?>>Tour CRS 
               </label>
               <!-- <label class="col-sm-3 control-label" for="user_type" form="b2as_user">CRS Type</label><label class="radio-inline" for="b2as_useruser_type2">  
                    <input class=" user_type checkboxIp" type="checkbox" name="user_type[]" id="b2as_useruser_type2" value="2" required="required" <?php if(get_crs_checked(2)=='true'){ echo 'checked'; }?>>Activity CRS
               </label>
               <label class="radio-inline" for="b2as_useruser_type3">  
                    <input class=" user_type checkboxIp" type="checkbox" name="user_type[]" id="b2as_useruser_type3" value="3" required="required" <?php if(get_crs_checked(3)=='true'){ echo 'checked'; }?>>Hotel CRS 
               </label>
               <label class="radio-inline" for="b2as_useruser_type4">  
                    <input class=" user_type checkboxIp" type="checkbox" name="user_type[]" id="b2as_useruser_type4" value="4" required="required" <?php if(get_crs_checked(4)=='true'){ echo 'checked'; }?>>Transfers CRS 
                    </label> -->
               </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="email" form="b2as_user">Email ID<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input <?=isset($email)?'value="'.$email.'" readonly':''?> name="email"  required="" type="email" placeholder="Email ID" class=" email form-control" id="email" /></div>
      </div>
      <?php if(!$this->input->get('eid')){?> 
        <div class="form-group">
           <label class="col-sm-3 control-label" for="password" form="b2as_user">Password<span class="text-danger">*</span></label>
           <div class="col-sm-6"><input value="" name="password"  required="" type="password" placeholder="Password" class=" password form-control" id="password" ></div>
        </div>
        <div class="form-group">
           <label class="col-sm-3 control-label" for="confirm_password" form="b2as_user">Confirm Password<span class="text-danger">*</span></label>
           <div class="col-sm-6"><input value="" name="confirm_password" required="" type="password" placeholder="Confirm Password" class=" confirm_password form-control" id="confirm_password" ></div>
        </div>
      <?php }?>
      
   </fieldset>

   <div class="form-group">
      <div class="col-sm-8 col-sm-offset-4"> <button type="submit" id="b2as_user_submit" class=" btn btn-success ">Save</button> <button type="reset" id="b2as_user_reset" class=" btn btn-warning ">Reset</button></div>
   </div>
</form>