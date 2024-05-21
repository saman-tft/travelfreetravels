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
.clr_labl {
  color: #000;
}

div.gallery {
  margin: 5px;
  border: 1px solid #ccc;
  float: left;
  width: 180px;
}

div.gallery:hover {
  border: 1px solid #777;
}

div.gallery img {
  width: 100%;
  height: 20%;
}

div.desc {
  padding: 15px;
  text-align: center;
}
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
              <h1>View Excursion
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
                           <label class='control-label col-sm-3'  for='validation_current'>Excursion Name :
 </label>
                             <div class='col-sm-4 controls'>
                        
                            <div class="controls">
                              <label class="clr_labl"><?php echo $packdata->package_name;?>
                                </label>
                          </div>
                     
                          </div>
                          </div>
                      <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_name'>Excursion type :</label>
                <div class='col-sm-4 controls'>
                        <?php
                                                for($l = 0; $l < count ( $package_type_data ); $l ++) {

                                  if($package_type_data[$l]->activity_types_id==$packdata->package_type)
                                  {
                                    ?> <label class="clr_labl"><?php echo $package_type_data[$l]->activity_types_name; ?>  </label> 
                                    <?php
                                  }
                                  else
                                  {                ?>
                        <!-- <label> <?php echo $package_type_data[$l]->activity_types_name; ?>  </label> -->
                        <?php
                                            }
                                               }
                                                ?>
                </div>
              </div>
              <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_current'>Supplier Name :
 </label>
                             <div class='col-sm-4 controls'>
                        
                            <div class="controls">
                              <label class="clr_labl"><?php echo $packdata->supplier_name;?>
                                </label>
                          </div>
                     
                          </div>
                          </div>

                        
                        
                         
<?php
$seasons_date = json_decode($packdata->seasons_date);
 /*$hit=explode(" - ",$seasons_date[0]);
 debug($hit[0]); debug($hit[1]);
  $one=date("d-M-y",strtotime($hit[0]));
  $two=date("d-M-y",strtotime($hit[0]));
 debug($one); 
 debug($two);
exit;*/

$i=1;
?>
  <div class="form-group">
                  <label for="field-1" class="col-sm-3 control-label">Contract Duration :</label> 
                  <div class="available_dates">
                
         <?php 
foreach ($seasons_date as $key => $seasons_date_value) {
  ?>
  <div class="dates_div">         
                  <div class="col-sm-4"> 
                     <label class="clr_labl"><?php $date = explode('-', $seasons_date_value); echo date('d M y',strtotime(str_replace('/', '-', $date[0]))).' - '.date('d M y',strtotime(str_replace('/', '-', $date[1])));?></label>
                     <!-- <input type="text" class="form-control" id="seasons_date_range_<?php echo$i;?>" name="seasons_date_range[]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="<?php echo$seasons_date_value;?>" /> -->
                    
                  </div>
                </div>
<?php
$i++;
}
?>
      </div>
                </div>  

                <!--  <div class='form-group ' id="select_date">
                  <label class='control-label col-sm-3' for='validation_current'>Booking Available Date <span style = "color:red">*</span>
                  </label>
                  <div class='col-sm-4 controls'>
                  <input type="text" name="tour_publish_date" id="tour_publish_date" data-rule-required='true'
                    class='form-control add_pckg_elements' required value="<?php echo$packdata->publish_date;?>" placeholder="Choose Date" data-rule-required='true' class='form-control add_pckg_elements tour_publish_date' required value="" placeholder="Choose Date" data-rule-required='true' readonly > 
                  </div>
                 </div> -->

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
              
<!-- 
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
              </div>  -->

                      <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_current'>Type of tour :
 </label>
                             <div class='col-sm-4 controls'>
                        
                            <div class="controls">
                              <?php
                              $day_plan = '';
                               if($packdata->day_plan=='H'){
                                if($packdata->half_day_type=='M'){
                                  $day_plan = 'Half Day - First Half';
                                }else if($packdata->half_day_type=='E'){
                                  $day_plan = 'Half Day - First Half';
                                }else{$day_plan = 'Half Day';}
                              }else if($packdata->day_plan=='F'){
                                  $day_plan = 'Full Day';
                              }
                              ?>
                              <label class="clr_labl"><?php echo $day_plan;?>
                                </label>
                          </div>
                     
                          </div>
                          </div>
                        <div class='form-group'>
                          <label class='control-label col-sm-3' for='validation_country'>Excursion Duration Selection :</label>
                            <div class='col-sm-4 controls'>
                             <label class="clr_labl"><?=$packdata->activity_duration_hour;?> Hours <?=$packdata->activity_duration_min;?> Min</label>
                            </div>
                        </div>
                        <div class='form-group'>
                          <label class='control-label col-sm-3' for='validation_country'>Number of Times Repeat :</label>
                            <div class='col-sm-4 controls'>
                             <label class="clr_labl"><?=$packdata->no_of_time_repeat;?></label>
                            </div>
                        </div>
                        <div class='form-group'>
                          <label class='control-label col-sm-3' for='validation_country'>Country :</label>
                          <div class='col-sm-4 controls'>
                           
                              <?php foreach ($countries as $country) {
                                if($country->country_id == $packdata->package_country) {
                                  ?><label class="clr_labl"><?php  echo $country->name;  ?></label>
                                 <?php }}?>
                          </div>
                        </div>
                       

                        <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_current'>City :
 </label>
                             <div class='col-sm-4 controls'>

                            <div class="controls">
                          <label class="clr_labl"><?php echo $packdata->package_city;?></label>
                          </div>
                     
                          </div>
                          </div>
                          <div class='form-group'>
                          <label class='control-label col-sm-3'  for='validation_current'>Pick Up Address :
  </label>
                             <div class='col-sm-4 controls'>
                   
                      <label class="clr_labl"><?php echo $packdata->address;?></label>
                        
                          </div>
                          </div>
                          
                                 <div class='form-group'>
                          <label class='control-label col-sm-3' for='validation_company'>Excursion Main Image :</label>
                          <div class='col-sm-3 controls'>
                         <!--  <input type="hidden" value="<?php echo $packdata->image; ?>" name="photo"> -->
                                 <a target="_blank" href="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images($packdata->image); ?>">
                                 <img src="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images($packdata->image); ?>" width="100%" name="photo" target="_blank">
                                 </div></a>
                          
                           
                        </div>
                        <div class='form-group'>
                          <label class='control-label col-sm-3' for='validation_company'>Excursion Gallery Image :</label>
                            <?php 
                            $gallery = json_decode($packdata->gallery_image);
                            foreach ($gallery as $key => $value) {
                              ?>
                              <div class="gallery">
                                  <a target="_blank" href="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images($value); ?>">
                                    <img  src="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images($value); ?>"  alt="Gallery Images" width="600" height="400">
                                  </a>
                                </div>
                                 
                                 <?php
                            }
                            ?>
                          
                           
                        </div>

                                 <div class='form-group'>
                            <label class='control-label col-sm-3' for='validation_name'>Description :</label>
                          <div class='col-sm-8 controls'>
                            <label class="clr_labl"><?php echo $packdata->package_description;?></label>                             <!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->
                           </div> 
                           </div>
                      
                  <div class='form-group'>
                         
                      <label class='control-label col-sm-3'  for='validation_rating' >Rating : </label>
      <div class="col-sm-4 controls" >
     
                <label class="clr_labl"><?php echo $packdata->rating;?> Star Rating</label>
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
                      <div class=''><h4>Terms and Conditions</h4></div>
                      
                      
                    </div>
                      <div><h2></h2></div>
                      <?php
                          if($packdata->offer_ticket=='Y'){
                            $ticket_status = 'Yes';
                            $ticket_description = '';
                          }else{
                            $ticket_status = 'No';
                            $ticket_description = 'style="display:none"';
                          }?>
                          <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_includes'>Offer Ticket :
                            </label>
                             <div class='col-sm-8 controls'>
                            <label class="clr_labl"><?php echo $ticket_status;?></label>
                      </div>
                          </div>
                          <div class='form-group' <?=$ticket_description?>>
                           <label class='control-label col-sm-3'  for='validation_includes'>Offer Description :
                            </label>
                             <div class='col-sm-8 controls'>
                      <label class="clr_labl"><?php echo $packdata->ticket_description;?></label>
                      </div>
                          </div>

                      <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_advance'>Cancellation Terms :
  </label>
                             <div class='col-sm-8 controls'>
                   
                      <!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
                       <label class="clr_labl"><?php echo $packdata->cancellation_advance;?></label>
                        
                          </div>
                          </div>
                          <?php
                          if($packdata->refundable=='Y'){
                            $refundable_status = 'Yes';
                            $disply_status = '';
                          }else{
                            $refundable_status = 'No';
                            $disply_status = 'style="display:none;"';
                          }?>
                          <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_excludes'>Refundable Terms :
  </label>
                             <div class='col-sm-8 controls'>
                   
                      <!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
                       <label class="clr_labl"><?php echo $refundable_status;?></label>
                        
                          </div>
                          </div>
                          <div class='form-group' <?=$disply_status?>>
                           <label class='control-label col-sm-3'  for='validation_excludes'>Refundable Terms Description :
  </label>
                             <div class='col-sm-8 controls'>
                   
                       <label class="clr_labl"><?php echo $packdata->cancellation_penality;?></label>
                        
                          </div>
                          </div>
                          <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_excludes'>Information travelers/ Trip :
  </label>
                             <div class='col-sm-8 controls'>
                   
                      <!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
                       <label class="clr_labl"><?php echo $packdata->infor_travellers;?></label>
                        
                          </div>
                          </div>
                          <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_includes'>Includes :
  </label>
                             <div class='col-sm-8 controls'>
                   
                      <!-- <input type="text" name="includes" id="includes" data-rule-required='true' class='form-control'> -->
                      <label class="clr_labl"><?php echo $packdata->price_includes;?></label>                             
                      <!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->
                      </div>
                          </div>
                          <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_includes'>Inclusion Policy :
  </label>
                             <div class='col-sm-8 controls'>
                   
                      <!-- <input type="text" name="includes" id="includes" data-rule-required='true' class='form-control'> -->
                      <label class="clr_labl"><?php echo $packdata->inclusion_policy;?></label>                             
                      <!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->
                      </div>
                          </div>
                         
                          <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_excludes'>Excludes :
  </label>
                             <div class='col-sm-8 controls'>
                   
                      <!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
                      <label class="clr_labl"><?php echo $packdata->price_excludes;?></label>                             
</div>
                          </div>
                          <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_excludes'>Exclusion Policy :
                           </label>
                             <div class='col-sm-8 controls'>
                      <label class="clr_labl"><?php echo $packdata->exclusion_policy;?></label>           
                            </div>
                          </div>
                          <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_excludes'>Contact Address :
                           </label>
                             <div class='col-sm-8 controls'>
                      <label class="clr_labl"><?php echo $packdata->contact_address;?></label>           
                            </div>
                          </div>
                          <div class='form-group'>
                           <label class='control-label col-sm-3'  for='validation_excludes'>Contact Email :
                           </label>
                             <div class='col-sm-8 controls'>
                      <label class="clr_labl"><?php echo $packdata->contact_email;?></label>           
                            </div>
                          </div>
                      <div><h1></h1></div>
                        
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