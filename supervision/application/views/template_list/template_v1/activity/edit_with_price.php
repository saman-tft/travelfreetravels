<?php
error_reporting(E_All);
?>
<style type="text/css">
/* Base for label styling */
[type="checkbox"]:not(:checked),
[type="checkbox"]:checked {
  position: absolute;
  left: -9999px;
}
[type="checkbox"]:not(:checked) + label,
[type="checkbox"]:checked + label {
  position: relative;
  padding-left: 18px;
  cursor: pointer;
}

/* checkbox aspect */
[type="checkbox"]:not(:checked) + label:before,
[type="checkbox"]:checked + label:before {
  content: '';
  position: absolute;
  left: 0; top: 0;
  width: 18px; height:18px;
  border:0px solid #ccc;
  background: #c4c3c2;
  border-radius: 4px;
  box-shadow: inset 0 1px 3px rgba(0,0,0,.1);
}
/* checked mark aspect */
[type="checkbox"]:not(:checked) + label:after,
[type="checkbox"]:checked + label:after {
  content: '\2713\0020';
  position: absolute;
  top: .15em; left: .22em;
  font-size: 1.3em;
  line-height: 0.8;
  color: #d4630d;
  transition: all .2s;
  font-family: 'Lucida Sans Unicode', 'Arial Unicode MS', Arial;
}
/* checked mark aspect changes */
[type="checkbox"]:not(:checked) + label:after {
  opacity: 0;
  transform: scale(0);
}
[type="checkbox"]:checked + label:after {
  opacity: 1;
  transform: scale(1);
}
/* disabled checkbox */
[type="checkbox"]:disabled:not(:checked) + label:before,
[type="checkbox"]:disabled:checked + label:before {
  box-shadow: none;
  border-color: #bbb;
  background-color: #ddd;
}
[type="checkbox"]:disabled:checked + label:after {
  color: #999;
}
[type="checkbox"]:disabled + label {
  color: #aaa;
}
.available_dates{ float: left; width:52%; }
.dates_div {float: left;width: 100%;margin-top: 7px;}
.close_bar .btn-primary{background-color:transparent!important;border-color: transparent!important;color: #f00;font-size:24px;    font-size: 24px;
    padding: 0 6px;
    margin-top: 22px;}
</style>
<div id="package_types" class="bodyContent col-md-12">
  <div class="panel panel-default">
    <!-- PANEL WRAP START -->
    <div class="panel-heading">
      <!-- PANEL HEAD START -->
      <div class="panel-title">
        <ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
          <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
          <li role="presentation" class="active">
            <a href="#fromList"
              aria-controls="home" role="tab" data-toggle="tab">
              <h1>Edit Excursion
              </h1>
            </a>
          </li>
          <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
        </ul>
      </div>
    </div>
    <!-- PANEL HEAD START -->
    <div class="panel-body">
      <!-- PANEL BODY START -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="fromList">
          <div class="col-md-12">
            <div class='row'>
              <div class='row'>
                <div class='col-sm-12'>
                  <div class='' style='margin-bottom:0;'>
                    <div class='box-content'>
  <form class='form form-horizontal validate-form' style='margin-bottom: 0;' action="<?php echo base_url(); ?>activity/update_package/<?php echo $packdata->package_id;?>" method="post" enctype="multipart/form-data">
                       <input type="hidden" name="w_wo_d" value="w">
                      

                      <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_name'>Excursion type</label>
                <div class='col-sm-4 controls'>
                  <select class='select2 form-control add_pckg_elements'
                    data-rule-required='true' name='disn' id="disn" required>
                    <option value=''>Select Excursion Type</option>
                        <?php
                                                for($l = 0; $l < count ( $package_type_data ); $l ++) {

                                  if($package_type_data[$l]->activity_types_id==$packdata->package_type)
                                  {
                                    ?>
                                   <option
                      value='<?php echo $package_type_data[$l]->activity_types_id; ?>' selected> <?php echo $package_type_data[$l]->activity_types_name; ?>  </option> 
                                    <?php
                                  }
                                  else
                                  {


                                                  ?>
                        <option
                      value='<?php echo $package_type_data[$l]->activity_types_id; ?>'> <?php echo $package_type_data[$l]->activity_types_name; ?>  </option>
                        <?php
                                            }
                                               }
                                                ?>
                      </select> <span id="distination"
                    style="color: #F00; display: none;">validate</span>
                </div>
              </div>

                        <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_current'>Excursion Name
 </label>
                             <div class='col-sm-4 controls'>
                        
                            <div class="controls">
                          <input type="text" name="name" id="name" value ="<?php echo $packdata->package_name;?>" data-rule-required='true' class='form-control'>
                          </div>
                     
                          </div>
                          </div>
                        
                          <div class='form-group'>
                           <!-- <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 fiveh">
                      <span class="form-group">Duration</span> -->
                      <label class='control-label col-sm-3'  for='validation_current'>Duration
  </label>
      <div class="col-sm-4 controls"> 
      <input type="text" name="duration" class="form-control" id="duration" value ="<?php echo $packdata->duration;?>" onchange="show_duration_info(this.value)" size="40" disabled>                          
      
       
      </div>                   
                  </div>
<?php
 $seasons_date = json_decode($packdata->seasons_date);
$i=1;
?>
  <div class="form-group">
                  <label for="field-1" class="col-sm-3 control-label">Contract Duration</label> 
                  <div class="available_dates">
                
            
                <?php
                if(empty($seasons_date)) 
                {?>
                  <div class="dates_div">         
                  <div class="col-sm-4"> 
                     
                     <input type="text" class="form-control" id="seasons_date_range_1" name="seasons_date_range[]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="" />
                     <div class='col-sm-1 controls add_tab padfive add_avail_dates'>
                        <span class="btn btn-primary"><i class="fa fa-plus"></i></span>
                      </div>
                  </div>
           </div>
         <?php 
             }
foreach ($seasons_date as $key => $seasons_date_value) {
  ?>
  <div class="dates_div">         
                  <div class="col-sm-4"> 
                     
                     <input type="text" class="form-control" id="seasons_date_range_<?php echo$i;?>" name="seasons_date_range[]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="<?php echo$seasons_date_value;?>" />
                    
                  </div>
                  <?php
                  if($i==1)
                  {
                    ?>
                     <div class='col-sm-1 controls add_tab padfive add_avail_dates'>
                        <span class="btn btn-primary"><i class="fa fa-plus"></i></span>
                      </div>

                    <?php
                  }
                  else
                  {
                    ?>
                      <div class='col-sm-2 close_bar controls padfive'>
                    <span class="btn btn-primary"><i class="far fa-times-circle"></i></span>
                  </div>

                    <?php

                  }
                  ?>
                  
                 
                </div>
<?php
$i++;
}
?>
      </div>
                </div>  

               <!--  <div class='form-group ' id="select_date">
                  <label class='control-label col-sm-3' for='validation_current'>Expiry Date <span style = "color:red">*</span>
                  </label>
                  <div class='col-sm-4 controls'>
                  <input type="text" name="tour_expire_date" id="tour_expire_date" data-rule-required='true'
                    class='form-control add_pckg_elements' required value="<?php  echo $packdata->end_date;?>" placeholder="Choose Date" data-rule-required='true' 
                    class='form-control add_pckg_elements tour_expire_date' required value="" placeholder="Choose Date" data-rule-required='true' readonly > 
                  </div>
                 </div> -->

                 <div class='form-group ' id="select_date">
                  <label class='control-label col-sm-3' for='validation_current'>Booking Available Date <span style = "color:red">*</span>
                  </label>
                  <div class='col-sm-4 controls'>
                  <input type="text" name="tour_publish_date" id="tour_publish_date" data-rule-required='true'
                    class='form-control add_pckg_elements' required value="<?php echo$packdata->publish_date;?>" placeholder="Choose Date" data-rule-required='true' class='form-control add_pckg_elements tour_publish_date' required value="" placeholder="Choose Date" data-rule-required='true' readonly > 
                  </div>
                 </div>

              <?php          
                 //debug($seasons_date);exit;
                  $weekdays = json_decode($packdata->weekdays,true);

                    $monday = '';
                  $tuesday = '';
                  $wednesday = '';
                  $thursday = '';
                  $friday = '';
                  $saturday = '';
                  $sunday = '';
                  foreach ($weekdays as $shift_key => $value) {
                  

                    if($value == 1){
                      $monday = 'checked';
                    }
                    if($value == 2){
                      $tuesday = 'checked';
                    }
                    if($value == 3){
                      $wednesday = 'checked';
                    }
                    if($value == 4){
                      $thursday = 'checked';
                    }
                    if($value == 5){
                      $friday = 'checked';
                    }
                    if($value == 6){
                      $saturday = 'checked';
                    }
                    if($value == 7){
                      $sunday = 'checked';
                    }
                  } 
                ?>
              

              <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_name'> Available Weekdays
                </label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='monday_0' name='weekdays[]' value="1" type='checkbox' <?php echo $monday;?>>
                                     <label for="monday_0">Mon</label>
                                   </div>
                                    <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='tuesday_0' name='weekdays[]' value="2" type='checkbox' <?php echo $tuesday;?>>
                                     <label for="tuesday_0">Tue</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='wednesday_0' name='weekdays[]' value="3" type='checkbox' <?php echo $wednesday;?>>
                                     <label for="wednesday_0">Wed</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='thursday_0' name='weekdays[]' value="4" type='checkbox' <?php echo $thursday;?>>
                                     <label for="thursday_0">Thu</label>
                                    </div>
                                    <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='friday_0' name='weekdays[]' value="5" type='checkbox' <?php echo $friday;?>>
                                     <label for="friday_0">Fri</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='saturday_0' name='weekdays[]' value="6" type='checkbox' <?php echo $saturday;?>>
                                     <label for="saturday_0">Sat</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='sunday_0' name='weekdays[]' value="7" type='checkbox' <?php echo $sunday;?>>
                                     <label for="sunday_0">Sun</label>
                                     </div>
                                </div>
              </div> 


                 
                        <div class='form-group'>
                          <label class='control-label col-sm-3' for='validation_country'>Country</label>
                          <div class='col-sm-4 controls'>
                            <select class='select2 form-control' data-rule-required='true' name='country' id="validation_country" value ="<?php echo $packdata->package_country;?>">
                            <!--  <input type="text" name="country" id="country" data-rule-required='true' class='form-control'>  -->
                              <?php foreach ($countries as $country) {?><option  value='<?php echo $country->country_id;?>'<?php if($country->country_id == $packdata->package_country) { echo "selected=selected"; } ?>><?php echo $country->name;?></option>
                                 <?php }?>
                           </select>
                          </div>
                        </div>
                       

                        <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_current'>City
 </label>
                             <div class='col-sm-4 controls'>

                            <div class="controls">
                          <input type="text" name="city" id="country" value ="<?php echo $packdata->package_city;?>" data-rule-required='true' class='form-control' disabled>
                          </div>
                     
                          </div>
                          </div>
                          <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_current'>Location
  </label>
                             <div class='col-sm-4 controls'>
                   
                      <input type="text" name="location" id="location" data-rule-required='true' class='form-control' value ="<?php echo $packdata->package_location;?>">
                        
                          </div>
                          </div>
                          
                                 <div class='form-group'>
                          <label class='control-label col-sm-3' for='validation_company'>Excursion Main Image</label>
                          <div class='col-sm-3 controls'>
                         <!--  <input type="hidden" value="<?php echo $packdata->image; ?>" name="photo"> -->
                                 <input type="file" title='Image to add to add' class=''   id='photo' name='photo'>                                 
                                  <input type="hidden" name='hidephoto' value="<?php echo $packdata->image; ?>">
                                 <img src="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images($packdata->image); ?>" width="100" name="photo">
                                 </div>
                          
                           
                        </div>

                                 <div class='form-group'>
                            <label class='control-label col-sm-3' for='validation_name'>Description</label>
                          <div class='col-sm-8 controls'>
                            <textarea name="Description" data-rule-required='true' class="form-control ckeditor" id="editor" cols="1000" rows="10" placeholder="Description" required><?php echo $packdata->package_description;?></textarea>                             <!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->
                           </div> 
                           </div>
                      
                  <div class='form-group'>
                         
                      <label class='control-label col-sm-3'  for='validation_rating' >Rating  </label>
      <div class="col-sm-4 controls" >
      <select class='form-control' data-rule-required='true' name='rating' id="rating" value ="" >
     
                <option><?php echo $packdata->rating;?></option>
                                <option value="0">0</option>
                                <option value="1">1</option> 
                                <option value="2">2</option>
                                <option value="3">3</option>  
                                <option value="4">4</option>
                                <option value="5">5</option>
                                 
                                 </select>
                                    </div>  
                                     </div>
                 
                     <!-- <div class='box-header blue-background'>
                      <div class=''><h4>Price Info</h4></div>

                      
                    </div> -->
<!-- 
                          <div><h2></h2></div> -->
                  
                          
                     
                         <!--  </div> -->
               <!--            <div class='form-group'>
                          <label class='control-label col-sm-3'  for='validation_number'>Price
 </label>
                             <div class='col-sm-3 controls'>
                        
                         
                          <input type="text"  name="p_price" id="p_price" data-rule-number='true' data-rule-required='true' class='form-control' value ="<?php echo $packdata->price;?>">
                         
                     
                          </div>
                          </div> -->
                           <div class='form-group'>
                                 <div class='form-group' id="addMultiCity"> </div> 
                              <div id="addCityButton" class="col-lg-2" style="display:none">
                                <input type="button" class="srchbutn comncolor" id="addCityInput" value="Add Month" style="padding:3px 10px;">
                                <input type="hidden" value="1" id="multiCityNo" name="no_of_days">
                           </div>
                           <div id="removeCityButton" class="col-lg-2" style="display:none;">
                                <input type="button" class="srchbutn comncolor" id="removeCityInput" value="Remove One Month" style="padding:3px 10px;">
                           </div>
                       </div>
                   
                         
                          
                         
                          
                          <div class='box-header blue-background'>
                      <div class=''><h4>Pricing Policy</h4></div>
                      
                      
                    </div>
                      <div><h2></h2></div>
                     
                          <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_includes'>Price Includes
  </label>
                             <div class='col-sm-8 controls'>
                   
                      <!-- <input type="text" name="includes" id="includes" data-rule-required='true' class='form-control'> -->
                      <textarea name="includes" data-rule-required='true' class="form-control ckeditor" cols="80" rows="10" placeholder="Price Includes" required><?php echo $packdata->price_includes;?></textarea>                             
                      <!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->
                      </div>
                          </div>
                          <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_excludes'>Price Excludes
  </label>
                             <div class='col-sm-8 controls'>
                   
                      <!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
                      <textarea name="excludes" data-rule-required='true' class="form-control ckeditor" cols="80" rows="10" placeholder="Price Excludes" required><?php echo $packdata->price_excludes;?></textarea>                             
</div>
                          </div>
                          <div class='box-header blue-background'>
                      <div class=''><h4>Cancellation & Refund Policy</h4></div>
                     
                    </div>
                      <div><h1></h1></div>
                      <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_advance'>Cancellation In Advance
  </label>
                             <div class='col-sm-8 controls'>
                   
                      <!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
                       <textarea name="advance" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3" placeholder="Cancellation In Advance"><?php echo $packdata->cancellation_advance;?></textarea>
                        
                          </div>
                          </div>
                          <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_excludes'>Cancellation Penalty
  </label>
                             <div class='col-sm-8 controls'>
                   
                      <!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
                       <textarea name="penality" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3" placeholder="Cancellation Penalty"><?php echo $packdata->cancellation_penality;?></textarea>
                        
                          </div>
                          </div>
                        
                          </div>

                      
                    </div>
                           
                     </div>
                     

                     <div class='form-actions' style='margin-bottom:0'>
                          <div class='row'>
                            <div class='col-sm-9 col-sm-offset-3'>
                              <?php 
                                if($type=='admin')
                                      {
                                        $view_name = 'view_with_price';
                                      }
                                      if($type=='staff')
                                      {
                                        $view_name = 'view_with_price_staff';
                                      }
                                      if($type=='supplier')
                                      {
                                        $view_name = 'view_with_price_supplier';
                                      }
                                ?>
                              <a href="<?php echo base_url(); ?>activity/<?=$view_name?>">
                              <button class='btn btn-primary' type='button'>
                                <i class='icon-reply'></i>
                                Go Back
                              </button></a>
                              <button class='btn btn-primary' type='submit'>
                                <i class='icon-save'></i>
                                Update
                              </button>
                            </div>
                          </div>
                        </div>
                        
                  </div>
                </div>
                </form>
              
        </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- PANEL BODY END -->
</div>
<!-- PANEL WRAP END -->
</div>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/ckeditor/ckeditor.js"></script>
<script type="text/javascript">

   $(document).ready(function () {

      $('.add_avail_dates').on('click',function(){
      var count = $('.dates_div').length;
      var cc = count +1;
      var dates_div = '';
      if(count<5){
      var dates_div =`

        <div class="dates_div">         
                  <div class="col-sm-4"> 
                     
                     <input type="text" class="form-control" id="seasons_date_range_`+cc+`" name="seasons_date_range[`+cc+`]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="" />
                    
                  </div>
                          <div class='col-sm-2 close_bar controls padfive'>
                    <span class="btn btn-primary"><i class="far fa-times-circle"></i></span>
                  </div>
                </div>`;
    $('.available_dates').append(dates_div);

    $('#seasons_date_range_'+cc).daterangepicker({
        "startDate":  new Date(),
          format: 'DD/MM/YYYY'
            
        });
    $('#seasons_date_range_1').daterangepicker({
    "endDate":  new Date(),
    format: 'DD/MM/YYYY'
    //"endDate": "03/21/2020"
});
    
  }
  else{
    alert('Maximum Reached');
  }
    });
          $(document).on('click','.close_bar',function(){
      $(this).closest('.dates_div').remove();
    });

          /* $('#price_shift_from').timepicker({startTime: '6:00 am'});
      $('#price_shift_to').timepicker({startTime: '6:00 am'});*/

        $('#tour_start_date').datepicker({
          minDate:0,
          numberOfMonths: 2,
          changeMonth: !0,
          dateFormat: "yy-mm-dd"
        });

        $('#tour_expire_date').datepicker({
          minDate:0,
          numberOfMonths: 2,
          changeMonth: !0,
          dateFormat: "yy-mm-dd"
        });

            $('#tour_publish_date').datepicker({
          minDate:0,
          numberOfMonths: 2,
          changeMonth: !0,
          dateFormat: "yy-mm-dd"
        });


});

</script>
<script src="<?=RESOURCE_DIR?>/system/library/daterangepicker/moment.min.js"></script><script src="<?=RESOURCE_DIR?>/system/library/daterangepicker/daterangepicker.js"></script> 
 <link rel="stylesheet" href="<?=RESOURCE_DIR?>/system/library/daterangepicker/daterangepicker-bs3.css">