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
.available_dates_booking{ float: left; width:52%; }
.dates_div_booking {float: left;width: 100%;margin-top: 7px;}
.sub_activities{ float: left; width:52%; }
.div_sub_activity {float: left;width: 100%;margin-top: 7px;}
.close_bar .btn-primary{background-color:transparent!important;border-color: transparent!important;color: #f00;font-size:24px;    font-size: 24px;
    padding: 0 6px;
    margin-top: 22px;}
.close_bar_avl_date .btn-primary{background-color:transparent!important;border-color: transparent!important;color: #f00;font-size:24px;    font-size: 24px;
    padding: 0 6px;
    margin-top: 22px;}
 .close_bar_activity .btn-primary{background-color:transparent!important;border-color: transparent!important;color: #f00;font-size:24px;    font-size: 24px;
    padding: 0 6px;}
 #age_grp{padding-left: 100px;}




.select-choose {
  position: relative;
  margin-bottom: 20px;
}

/* arrow on select */
.select-choose::before {
  position: absolute;
  top: 50%;
  right: 0px;
  width: 6px;
  height: 4px;
  background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAYAAAAEBAMAAABfKlNwAAAAFVBMVEXnPkHnPkHnPkHnPkEAAADnPkHnPkF51gTgAAAAB3RSTlP21ywMAKCf3aGGrQAAABhJREFUCNdjCHYxZWBUEmBQZBBicE41AQAQTQIPZ28SKQAAAABJRU5ErkJggg==);
  opacity: 0.5;
  content: "";
  transform: translateY(-50%);
  transition: all 0.2s;
}

.select-choose:hover::before {
  opacity: 1;
}

/* input text styling for select */

.select-choose__link {
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  height: 32px;
}

/* hidden dropdown */
.select-choose__list {
  display: none;
  position: absolute;
  top: 34px;
  left: 0;
  z-index: 110;
  width: 100%;
  background-color: #fff;
  border: 1px solid #e1e1e1;
  border-radius: 3px;
  padding: 0 8px;
}

.select-choose__item {
  color: #757575;
  font-size: 1.4rem;
  padding: 4px 0;
  margin: 8px 0;
}
input[type="checkbox"] {
  display: inline-block;
  vertical-align: middle;
  margin: -2px 8px 0 0;
}

.navigation {
  padding: 0;
  margin: 0;
  border: 0;
  line-height: 1;
}

.navigation ul,
.navigation ul li,
.navigation ul ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.navigation ul {
  position: relative;
  z-index: 500;
  float: left;
}

.navigation ul li {
  float: left;
  min-height: 0.05em;
  line-height: 1.4em;
  vertical-align: middle;
  position: relative;
}

.navigation ul li.hover,
.navigation ul li:hover {
  position: relative;
  z-index: 510;
  cursor: default;
  background-color: #015291;
}

.navigation ul ul {
  visibility: hidden;
  position: absolute;
  top: 100%;
  left: 0px;
  z-index: 520;
  width: 100%;
}

.navigation ul ul li {
  float: none;
}

.navigation ul ul ul {
  top: 0;
  right: 0;
}

.navigation ul li:hover > ul {
  visibility: visible;
}

.navigation ul ul {
  top: 0;
  left: 100%;
}

.navigation ul li {
  float: none;
  width: 100%;
}

.navigation ul ul {
  margin-top: 0.05em;
}

.navigation {
  width: 13em;
  width: 100%;
  zoom: 1;
  box-shadow: rgba(0, 0, 0, 0.2) 0px 8px 16px 0px;
}

.navigation:before {
  content: "";
  display: block;
}

.navigation:after {
  content: "";
  display: table;
  clear: both;
}

.navigation a {
  display: block;
  padding: 1em 1.3em;
  color: #757575;
  text-decoration: none;
  text-transform: uppercase;
}

.navigation > ul {
  width: 100%;
}

.navigation ul ul {
  width: 13em;
}

.navigation > ul > li > a {
  border-right: 0.3em solid #015291;
  color: #757575;
}

.navigation > ul > li > a:hover {
  color: #ffffff;
}

.navigation > ul > li a:hover,
.navigation > ul > li:hover a {
  background: lightseagreen;
  color: white;
}

.navigation li {
  position: relative;
}

.navigation ul li.has-sub > a:after {
  content: "»";
  position: absolute;
  right: 1em;
}

.navigation ul ul li.first {
  -webkit-border-radius: 0 3px 0 0;
  -moz-border-radius: 0 3px 0 0;
  border-radius: 0 3px 0 0;
}

.navigation ul ul li.last {
  -webkit-border-radius: 0 0 3px 0;
  -moz-border-radius: 0 0 3px 0;
  border-radius: 0 0 3px 0;
  border-bottom: 0;
}

.navigation ul ul {
  -webkit-border-radius: 0 3px 3px 0;
  -moz-border-radius: 0 3px 3px 0;
  border-radius: 0 3px 3px 0;
  width: fit-content;
}

.navigation ul ul {
  border: 1px solid #34a65f;
}

.navigation ul ul a {
  color: #ffffff;
  font-size: 14px;
  text-transform: capitalize;
}

.navigation ul ul a:hover {
  color: #ffffff;
}

.navigation ul ul li:hover > a {
  background: #4eb1ff;
  color: #ffffff;
}

.navigation.align-right > ul > li > a {
  border-left: 0.3em solid #4d2411;
  border-right: none;
}

.navigation.align-right {
  float: right;
}

.navigation.align-right li {
  text-align: right;
}

.navigation.align-right ul li.has-sub > a:before {
  content: "+";
  position: absolute;
  top: 50%;
  left: 15px;
  margin-top: -6px;
}

.navigation.align-right ul li.has-sub > a:after {
  content: none;
}

.navigation.align-right ul ul {
  visibility: hidden;
  position: absolute;
  top: 0;
  left: -100%;
  z-index: 598;
  width: 100%;
}

.navigation.align-right ul ul li.first {
  -webkit-border-radius: 3px 0 0 0;
  -moz-border-radius: 3px 0 0 0;
  border-radius: 3px 0 0 0;
}

.navigation.align-right ul ul li.last {
  -webkit-border-radius: 0 0 0 3px;
  -moz-border-radius: 0 0 0 3px;
  border-radius: 0 0 0 3px;
}

.navigation.align-right ul ul {
  -webkit-border-radius: 3px 0 0 3px;
  -moz-border-radius: 3px 0 0 3px;
  border-radius: 3px 0 0 3px;
}

.levelBody{
  background-color:white;
  width:200px;
  padding-left:10px;
  border-style: solid; 
  border-width:1px;
  border-color:slategrey;
  box-shadow: rgba(0, 0, 0, 0.2) 0px 8px 16px 0px;
  text-align:left;
}

.checkboxLabel {
  color:slategrey;
  font-size:13px;
  display:inline-block
}
.descriptionAndThoughts{ 
  text-align:center; 
  color:darkslategrey;
  width:60%;
  display:inline-block; 
}

.descriptionWrapper{
  text-align:center; 
  width:100%;
}


</style>
 <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.3/jquery.timepicker.min.css">    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.3/jquery.timepicker.min.js"></script>

 <script src="<?=RESOURCE_DIR?>/system/library/daterangepicker/moment.min.js"></script><script src="<?=RESOURCE_DIR?>/system/library/daterangepicker/daterangepicker.js"></script> 
 <link rel="stylesheet" href="<?=RESOURCE_DIR?>/system/library/daterangepicker/daterangepicker-bs3.css">



<div id="Package" class="bodyContent col-md-12">
  <div class="panel panel-default">
    <!-- PANEL WRAP START -->
    <div class="panel-heading">
      <!-- PANEL HEAD START -->
      <div class="panel-title">
        <ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
          <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
          <li role="presentation" class="active" id="add_package_li"><a
            href="#add_package" aria-controls="home" role="tab"
            data-toggle="tab">Add Excursion </a></li>
          <li role="presentation" class="" id="itenary_li"><a href="#itenary"
            aria-controls="home" role="tab" data-toggle="">Day Descritpion
          </a></li>
           <li role="presentation" class="" id="price_li"><a href="#nationality_price"
            aria-controls="home" role="tab" data-toggle="">Price Management
          </a></li> 
          <!-- <li role="presentation" class="" id="gallery_li"><a href="#gallery"
            aria-controls="home" role="tab" data-toggle="">Photo Gallery </a></li> -->
          <li role="presentation" class="" id="rate_card_li"><a
            href="#rate_card" aria-controls="home" role="tab" data-toggle="">Terms & Conditions</a></li>
          <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
        </ul>
      </div>
    </div>
    <!-- PANEL HEAD START -->
    <div class="panel-body">
      <!-- PANEL BODY START -->
      <form
        action="<?php echo base_url(); ?>index.php/activity/add_package_new"
        method="post" enctype="multipart/form-data"
        class='form form-horizontal validate-form'>
        <div class="tab-content">
          <!-- Add Activity Starts -->
          <div role="tabpanel" class="tab-pane active" id="add_package">
            <div class="col-md-12">

              <input type="hidden" name="a_wo_p" value="a_w"> <input
                type="hidden" name="deal" value="0">
              <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_current'>Excursion Name <span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <div class="controls">
                    <input type="text" name="name" id="name"
                      data-rule-minlength='2' data-rule-required='true'
                      placeholder="Enter Excursion Name"
                      class='form-control add_pckg_elements' required>
                  </div>
                </div>
              </div>
              <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_name'>Excursion type<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <select class='select2 form-control add_pckg_elements'
                    data-rule-required='true' name='disn' id="disn" required>
                    <option value=''>Select Excursion Type</option>
                                    <?php
                                                for($l = 0; $l < count ( $package_type_data ); $l ++) {
                                                  ?>
                                <option
                                value='<?php echo $package_type_data[$l]->activity_types_id; ?>'> <?php echo $package_type_data[$l]->activity_types_name; ?>  </option>
                                <?php
                                                   }
                                                    ?>
                                                    ?>
                                </select> <span id="distination"
                    style="color: #F00; display: none;">validate</span>
                </div>
              </div>
              <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_name'>Description <span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <textarea name="Description" 
                    class="form-control add_pckg_elements" cols="70" rows="3"
                    placeholder="Description" required></textarea>
                  <!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->
                </div>
              </div>
              <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_name'>Supplier Name <span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <input type="text" name="supplier_name" 
                    class="form-control add_pckg_elements"
                    placeholder="Supplier Name" required>
                  <!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->
                </div>
              </div>
              <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_country'>Choose Theme<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <!-- <select class='select2 form-control add_pckg_elements'
                    data-rule-required='true' name='choose_theme' id="choose_theme" required>
                    <option value="">Select Theme</option>
                            <?php foreach ($themes as $activity_theme) {?>
                            <option value='<?php echo $activity_theme['id']; ?>'><?php echo $activity_theme['activity_subtheme']; ?></option>
                            <?php }?>
                          </select> -->
              

<div class="select-choose" >
  <input type="text" class="form-control select-choose__title" placeholder="No categories selected." readonly >
  <a href="" class="select-choose__link"></a>
  <div class="select-choose__list" style="padding: 0px;">
    <!-- Drop Down extension -->
    <div class="navigation">
      <ul>
        <?php foreach ($themes as $activity_theme) {?>
        <li class="has-sub"><a href="#" data-custom-value="<?php echo $activity_theme['id']; ?>"><?php echo $activity_theme['activity_subtheme']; ?></a>
          <?php
          if(!empty($sub_themes)){?>
            <ul style="border-style: none;">
          <?php
      foreach ($sub_themes as $key => $subtheme) {
        foreach ($subtheme as $key => $value) {
        $sub_theme_id = $value['id'];
        $theme_id = $value['activity_theme_id'];
        $sub_theme_name = $value['sub_theme'];
        if($theme_id==$activity_theme['id'])
        {
            ?>
          
            <li>
              <div class="levelBody">
                
                <div class="select-choose__item">
                  <input type="checkbox"  name="sub_category<?=$sub_theme_id?>" id="catagoryCkbx<?=$sub_theme_id?>" class="checkbox" value='<?=$sub_theme_name?>'>
                  <label for="catagoryCkbx<?=$sub_theme_id?>" >
                    <?=$sub_theme_name?></label>
                </div>
              </div>
            </li>
      <?php }}}} ?>
          </ul>
        </li>
    <?php }?>
</ul>
    </div>    
  </div></div>
              


                </div>
              </div>
              <div class="form-group">
                  <label for="field-1" class="col-sm-3 control-label">Contract Duration </label>  
                  <div class="available_dates">
                  <div class="dates_div" data-value="1">          
                  <div class="col-sm-4"> 
                     
                     <input type="text" class="form-control contract_duration" id="seasons_date_range_1" name="seasons_date_range[1]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="" />
                    <input type="hidden" name="all_dates" id="all_dates">
                  </div>
                  <div class='col-sm-1 controls add_tab padfive add_avail_dates'>
                        <span class="btn btn-primary"><i class="fa fa-plus"></i></span>
                      </div>
                </div>
                </div>
              </div>
              <?php 

              
                    $monday = '1';
                  $tuesday = '2';
                  $wednesday = '3';
                  $thursday = '4';
                  $friday = '5';
                  $saturday = '6';
                  $sunday = '7';
                  
                ?>
              <div class="form-group">
                  <label for="field-1" class="col-sm-3 control-label">Booking Available</label>
                  <div class="col-sm-8 controls ">
                  <input type="radio" id="booking_availbale_date" name="booking_availbale" value="N" >&nbsp;<label for="sunday_0">Date Wise</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="booking_availbale_day" name="booking_availbale" value="D" >&nbsp;<label for="sunday_0">Week Wise</label>
                  <div class="col-sm-12 available_dates_booking" style="display: none;">
                  <div class="dates_div_booking">         
                  <!-- <div > 
                     <input type="text" class="form-control" id="booking_date_range_1" name="booking_date_range[1]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="" />
                    
                  </div>
                  <div class='col-sm-1 controls add_tab padfive add_booking_avail_dates'>
                        <span class="btn btn-primary"><i class="fa fa-plus"></i></span>
                      </div> -->
                </div></div> 
                  <div class="col-sm-12 available_weekdays" style="display: none;">
                    </div>
                </div>
            </div>
            <!--  <div class='form-group ' id="select_date">
                  <label class='control-label col-sm-3' for='validation_current'>Start Date <span style = "color:red">*</span>
                  </label>
                  <div class='col-sm-4 controls'>
                  <input type="text" name="tour_start_date" id="tour_start_date" data-rule-required='true'
                    class='form-control add_pckg_elements' required value="" placeholder="Choose Date" data-rule-required='true'  readonly> 
                  </div>
                 </div> -->
              <!-- <div class='form-group ' id="select_date">
                  <label class='control-label col-sm-3' for='validation_current'>Expiry Date <span style = "color:red">*</span>
                  </label>
                  <div class='col-sm-4 controls'>
                  <input type="text" name="tour_expire_date" id="tour_expire_date" data-rule-required='true'
                    class='form-control add_pckg_elements' required value="" placeholder="Choose Date" data-rule-required='true'  > 
                  </div>
                 </div> -->

                 <!-- <div class='form-group ' id="select_date">
                  <label class='control-label col-sm-3' for='validation_current'>Booking Available Date <span style = "color:red">*</span>
                  </label>
                  <div class='col-sm-4 controls'>
                  <input type="text" name="tour_publish_date" id="tour_publish_date" data-rule-required='true'
                    class='form-control add_pckg_elements' required value="" placeholder="Choose Date" data-rule-required='true'  readonly="readonly"> 
                  </div>
                 </div> -->
              <!-- <div class='form-group'>
                <label class='control-label col-sm-3' for='adult'>Price</label>
                <div class='col-sm-4 controls'>
                  <input type="text" name="p_price" id="p_price"
                    data-rule-number="true" data-rule-required='true'
                    placeholder="Price"
                    class='form-control add_pckg_elements numeric' maxlength='10'
                    minlength='3' required>
                </div>
              </div> -->
              <!-- <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_name'> Starting Time
                </label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <div class="col-md-4 padfive days_lablel">
              
                                      <input type="text" name="price_shift_from" id="price_shift_from" 
                        data-rule-required='true' placeholder="Price Shift From" 
                        class='form-control price_elements price_shift_from' required readonly>
                                </div>
                            </div>
                        </div>

                          <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_name'> Ending Time
                </label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <div class="col-md-4 padfive days_lablel">
              
                                       <input type="text" name="price_shift_to" id="price_shift_to" 
                      data-rule-required='true' placeholder="Price Shift To"
                      class='form-control price_elements price_shift_to' required readonly>
                                </div>
                            </div>
                        </div> -->

              <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_current'>Type Of Tour<span style = "color:red">*</span>
                </label>
                <div class="col-sm-4 controls ">
                  <input type="radio" id="num_of_days" name="type_of_tour" class="type_of_tour_radio" value="N" >&nbsp;<label for="sunday_0">Number of days</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="day_wise" name="type_of_tour" class="type_of_tour_radio" value="D" onclick="show_duration_info('')">&nbsp;<label for="sunday_0">Day Plan</label>
                  <div id="no_of_days" style="display: none;"><input type="text" name="duration" data-rule-number='true'
                    class="form-control add_pckg_elements numeric" maxlength='2'
                    minlength='1' id="duration"
                    onblur="show_duration_info(this.value)" size="40"
                    placeholder="Enter Number Between 1-99"></div> 
                  <div id="day_wise_plan" style="display: none;">
                    <span><input class='form-control checkbox' id='repeat_sight' name='half_day' value="7" type='checkbox' >
                                     <label for="repeat_sight">Half Day</label></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span><input type="checkbox" id="full_day" name="full_day" value="F"  class="day form-control checkbox" ><label for="full_day">Full Day</label></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <!-- <span><input type="checkbox" name="night" id="night" value="N"   class="form-control checkbox"><label for="night">Night</label></span> -->
                            <div class="repeat_session" style="display: none;">
                              <span><input type="checkbox" name="morning" id="morning" value="M" class="form-control checkbox" ><label for="morning">First Half</label></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <span><input type="checkbox" name="evening" id="evening" value="E" class="form-control checkbox"><label for="evening">Second Half</label></span>
                            </div></div>
                </div>
                <!-- <span>Days</span> -->
              </div>
              <div class='form-group' id="activity_duration_selection" style="display: none;">
                <label class='control-label col-sm-3' for='validation_name'>Excursion Duration Selection<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <label>Hours/ Min </label>
                  <input type="text" name="activity_duration" 
                    class="form-control add_pckg_elements"
                    placeholder="Activity Duration Selection">
                  <!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->
                </div>
                <div class='col-sm-4 controls'>
                  <label>Add times</label>
                  <div class="input-group bootstrap-timepicker timepicker col-sm-5 controls new_time" >
                            <input id="site_time_add" name="add_time[]" type="text" class="form-control input-small site_time_add">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                          </div>
                            <div class="col-sm-8 controls">
                              <a href="javascript:void(0);" class="new_pick" id="buttonAdd"><i class="fa fa-plus"></i> Add another pickup time</a>
                              <div class="new_time_clone clearfix" id="TextBoxContainer">                     
                            </div>
                          </div>
                </div>
              </div>
              <div class='form-group' id="sub_activity_selection" style="display: none;">
                <label class='control-label col-sm-3' for='validation_current'>Sub Excursion Selection  
                </label>
                <div class="sub_activities">
                  <div class="div_sub_activity">          
                  <div class="col-sm-4"> 
                    <select class='select2 form-control add_pckg_elements'
                    data-rule-required='true' name='filter_list[1]' id="filter_list_1" >
                    <option value=''>Select Sub Category</option>
                  </select>
                    
                  </div>
                  <div class="col-sm-4"> <input type="text" class='form-control'  name='sub_duration[1]' id="time_duration_1" placeholder="Time(Hours/ Min)">
                  </div>
                  <div class='col-sm-1 controls add_tab padfive add_sub_activities'>
                        <span class="btn btn-primary"><i class="fa fa-plus"></i></span>
                      </div>
                </div>
                </div>
              </div>
              <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_country'>Country<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <select class='select2 form-control add_pckg_elements'
                    data-rule-required='true' name='country' id="country" required>
                    <!--  <input type="text" name="country" id="country" data-rule-required='true' class='form-control'>  -->
                    <option value="">Select Location</option>
                        <?php foreach ($country as $coun) {?>
                        <option value='<?php echo $coun->country_id; ?>'><?php echo $coun->name; ?></option>
                        <?php }?>
                      </select>
                </div>
              </div>
              <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_current'>City
                </label>
                <div class='col-sm-4 controls'>
                  <select class='form-control add_pckg_elements'
                    name='cityname_old' id="cityname"  required>
                    <option value=''>Select city</option>
                  </select>
                </div>
              </div>
              <!-- <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_current'>City List
                </label>
                <div class='col-sm-4 controls'>
                  <input type="text" class="normal AlphabetsOnly" id="textbox" name="cityname" style="width:450px;" required>
                </div>
              </div> -->
              <!-- <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_current'>Location <span style = "color:red">*</span>
                </label>
                <div class='col-sm-4 controls'>
                  <input type="text" name="location" id="location"
                    placeholder="Enter Location" 
                    class='form-control add_pckg_elements AlphabetsOnly' required>
                </div>
              </div> -->
              <div class="form-group">
                  <label for="field-1" class="col-sm-3 control-label">Pick Up Location<span class="text-danger">*</span></label>                 
                  <div class="col-sm-5">
                                      <div id="map_canvas" style="height:300px;width:700px;margin: 0.6em;">                                       
                                      </div>
                                    </div>
                                </div> 
                                <div class="form-group">                                    
                                    <label for="field-1" class="col-sm-3 control-label">Address<span class="text-danger">*</span></label>                 
                  <div class="col-sm-5">
                    <textarea class="form-control" id="hotel_address" name="hotel_address" placeholder="Hotel Address" data-validate="required" data-message-required="Please enter the Address" id="hotel_address" rows="7" readonly="true"></textarea>
                  </div>                
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-3 control-label">Latitude<span class="text-danger">*</span></label>                  
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="latitude" onblur="getmap()" placeholder="Latitude" data-validate="required" data-message-required="Please enter the latitude of the pick up location" id="lat" readonly="true">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-3 control-label">Longitude<span class="text-danger">*</span></label>                 
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="longitude" onblur="getmap()" placeholder="Longitude" data-validate="required" data-message-required="Please enter the Longitude of the pick up location" id="lng" readonly="true">
                  </div>
                </div>
              <!-- <div class='form-group'>
                  <label class='control-label col-sm-3' for='validation_current'>Choose Duration <span style = "color:red">*</span>
                  </label>
                  <div class='col-sm-3 col-md-3 controls'>
                    <select class='select2 form-control' data-rule-required='true' name='choose_duration' id="choose_duration" data-rule-required='true' required>   
                      <option value="1">Hours/Minutes Format</option>
                      <option value="2">Days/Hours Format</option>
                      <option value="3">Nights/Days Format</option>
                            

                    </select>       
                  </div>
                  <div id="changebase">
                    <div class='col-sm-1 col-md-1 controls'>
                      <input type="number" name="due1" 
                      data-rule-required='true' min="0"
                      class='form-control add_pckg_elements' required>

                    </div>
                    <div class='col-sm-1 col-md-1 controls'>
                      <span id="duratiob_categry_1">Hours</span>
                    </div>
                    <div class='col-sm-1 col-md-1 controls'>
                      <input type="number" name="due2" min="0"
                      data-rule-required='true'
                      class='form-control add_pckg_elements' required>

                    </div>
                    <div class='col-sm-1 col-md-1 controls'>
                      <span id="duratiob_categry_2">Minutes</span>
                    </div>
                  </div>
                </div> -->
                <div class="form-group clearfix">
                  <label class="control-label col-sm-3">Pick Up Time</label>
                  <div class="input-group bootstrap-timepicker timepicker col-sm-5 controls new_time" >
                    <input id="site_time" name="pickup_time[]" type="text" class="form-control input-small site_time">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                  </div>
                  <label class="control-label col-sm-3">&nbsp;</label>
                  <!-- <div class="col-sm-6 controls">
                    <a href="javascript:void(0);" class="new_pick" id="buttonAdd"><i class="fa fa-plus"></i> Add another pickup time</a>
                    <div class="new_time_clone clearfix" id="TextBoxContainer">                     
                    </div>
                  </div> -->
                </div>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Book Before No.of Days</label>
                  <div class='col-sm-4 controls'>
                    <input type="number" name="bok_no_day" min="0" placeholder="Enter Book Before No.of Days" class='form-control add_pckg_elements' value="4" >                  
                  </div>
                </div>
        <div class='form-group'>
          <label class='control-label col-sm-3' for='validation_country'>Amenities<span style = "color:red">*</span></label>
          <div class='col-sm-4 controls'>
            <select class='select2 form-control add_pckg_elements'
              data-rule-required='true' name='amenities' id="amenities" required>
              <!--  <input type="text" name="country" id="country" data-rule-required='true' class='form-control'>  -->
              <option value="">Select Location</option>
                  <?php foreach ($amenities as $val) {?>
                  <option value='<?=$val['id']?>'><?=$val['activity_amenties']?></option>
                  <?php }?> 
                </select>
          </div>
        </div>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Pax Occupancy<span style = "color:red">*</span></label>
                  <div class='col-sm-2 controls'>
                    <label>Minimum Pax</label>
                    <input type="number" placeholder="Enter Minimum Pax" name="min_pax" min="1" class='form-control add_pckg_elements' required value="1">
                  </div>
                  <!-- <label class='control-label col-sm-3'>Maximum Pax <span style = "color:red">*</span></label> -->
                  <div class='col-sm-2 controls'>
                    <label>Maximum Pax</label>
                    <input type="number" placeholder="Enter Maximum Pax" name="max_pax" min="0" class='form-control add_pckg_elements' required value="24">
                  </div>
                </div>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Do you pick up Traveler<span style = "color:red">*</span></label>
                  <div class='col-sm-4 controls'>
                    <label>Country</label>
          <select class='select2 form-control add_pckg_elements'
                    data-rule-required='true' name='traveller_country' id="traveller_country" required>
                    <!--  <input type="text" name="country" id="country" data-rule-required='true' class='form-control'>  -->
                    <option value="">Select Location</option>
                        <?php foreach ($country as $coun) {?>
                        <option value='<?php echo $coun->country_id; ?>'><?php echo $coun->name; ?></option>
                        <?php }?>
                    </select>
                    <label>City</label>
                    <select class='form-control add_pckg_elements'
                    name='traveller_cityname' id="traveller_cityname"  required>
                    <option value=''>Select city</option>
                  </select>
          <label>Travelers Pick Up</label>
                    <select class='form-control add_pckg_elements'
                    name='traveller_pickup' id="traveller_pickup"  required>
                    <option value='Y'>Yes, we pick up all travelers</option>
                    <option value='N'>No, we meet meet all travellers at a meeting point</option>
                  </select>
          <label>Meeting Instructions</label>
          <textarea name="meeting_instruction"
                    class="form-control"
                    data-rule-required="true" cols="70" rows="3"
                    placeholder="Meeting Instructions" required></textarea>
          <label>Information travelers need from you</label>
          <br><b>Health Restrictions</b><br>
          <?php
          foreach ($health_instructions as $key => $restrictions) { ?>
            <input type="checkbox" class='form-control checkbox' name="health_instructions" id="health_instructions" value="<?=$restrictions['id']?>"><label for="health_instructions">&nbsp;&nbsp;</label><?=$restrictions['health_instructions']?><br>

          <?php }
           ?>
           <div class="health_details_restctn"></div>
          <div class='col-sm-1 controls add_tab padfive health_details'>
            <span class="btn btn-primary"><i class="fa fa-plus"></i></span>
          </div>
          <br><br><b>Select the physical difficulty level</b><br>
                   <input type="checkbox" class='form-control checkbox' name="difficulty_easy" id="difficulty_easy" value="E"><label for="difficulty_easy">&nbsp;&nbsp;</label>Easy<br>
                   <input type="checkbox" class='form-control checkbox' name="difficulty_moderate" id="difficulty_moderate" value=""><label for="difficulty_moderate">&nbsp;&nbsp;</label>Moderate<br>
                   <input type="checkbox" class='form-control checkbox' name="difficulty_challenging" id="difficulty_challenging" value=""><label for="difficulty_challenging">&nbsp;&nbsp;</label>Challenging<br>
                   <input type="checkbox" class='form-control checkbox' name="difficulty_other" id="difficulty_other" value="" onclick="uncheckfun(this);"><label for="difficulty_other">&nbsp;&nbsp;</label>Other
                   <input type="text" name="other_difficulty" id="other_difficulty" style="display: none;"><br>
                </div>
                  <!-- <label class='control-label col-sm-3'>Maximum Pax <span style = "color:red">*</span></label> -->
                  

                </div>
        <div class='form-group'>
          <label class='control-label col-sm-3' for='validation_company'>Excursion
            Display Image <span style = "color:red">*</span></label>
          <div class='col-sm-4 controls'>
            <input type="file" title='Image to add'
              class='add_pckg_elements' data-rule-required='true' id='photo'
              name='photo' required> <span id="pacmimg"
              style="color: #F00; display: none">Please Upload Excursion Image</span>
          </div>
        </div>
        <div class='form-group'>
          <label class='control-label col-sm-3' for='validation_company'>Excursion
            Gallery Images <span style = "color:red">*</span></label>
          <div class='col-sm-4 controls'>
            <input type="file" title='Image to add'
              class='add_pckg_elements' data-rule-required='true' id='gallery_photo'
              name='gallery_photo[]' multiple="multiple" required> <span id="pacmimg"
              style="color: #F00; display: none">Please Upload Excursion Image</span>
          </div>
        </div>
        <div class='form-group'>
          <label class='control-label col-sm-3' for='validation_rating'>Rating
          </label>
          <div class="col-sm-4 controls">
            <select class='form-control add_pckg_elements'
              data-rule-required='true' name='rating' id="rating" required>
              <option value="0">0</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
            </select>
          </div>
        </div>
        <!-- <div class='form-group'>
          <label class='control-label col-sm-3' for='validation_current'>Pax Group <span style = "color:red">*</span>
          </label>
          <div class='col-sm-2 controls'>
            <label for='validation_current'>Child First Group<span style = "color:red">*</span></label>
            <input type="text" name="child_first_grp" id="child_first_grp"
              placeholder="0-8" 
              class='form-control add_pckg_elements' required>
          </div>
          <div class='col-sm-2 controls'>
            <label for='validation_current'>Child Second Group<span style = "color:red">*</span></label>
            <input type="text" name="child_second_grp" id="child_second_grp"
              placeholder="9-18" 
              class='form-control add_pckg_elements' required>
          </div>
          <div class='col-sm-2 controls'>
            <label for='validation_current'>Adult Age Limit<span style = "color:red">*</span></label>
            <input type="text" name="adult_grp" id="adult_grp"
              placeholder="19-60"  
              class='form-control add_pckg_elements' required>
          </div>
        </div> -->
        <div class='form-group'>
          <label class='control-label col-sm-3' for='validation_current'>Pax Group <span style = "color:red">*</span>
          </label>
          <div class='col-sm-6 controls'>
            <label for='validation_current'>Define the age groups that can participate<span style = "color:red">*</span></label><br>
            <p id="age_grp">Min Age &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Max Age</p><br>
            <input type="checkbox" class='form-control checkbox' name="adult_age" id="adult_age" value="E"><label for="adult_age">&nbsp;&nbsp;</label>Adult&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="adult_age_min" style="width: 40px;">&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<input type="text" name="adult_age_max" style="width: 40px;"><br>
            <input type="checkbox" class='form-control checkbox' name="infant_age" id="infant_age" value="E"><label for="infant_age">&nbsp;&nbsp;</label>Infant&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="infant_age_min" style="width: 40px;">&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<input type="text" name="infant_age_max" style="width: 40px;"><br>
            <input type="checkbox" class='form-control checkbox' name="child_age" id="child_age" value="E"><label for="child_age">&nbsp;&nbsp;</label>Child&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="child_age_min" style="width: 40px;">&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<input type="text" name="child_age_max" style="width: 40px;"><br>
            <input type="checkbox" class='form-control checkbox' name="senior_age" id="senior_age" value="E"><label for="senior_age">&nbsp;&nbsp;</label>Senior&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="senior_age_min" style="width: 40px;">&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<input type="text" name="senior_age_max" style="width: 40px;"><br>

          </div>
        </div>
        <div class='form-group'>
          <div id="addCityButton" class="col-lg-2" style="display: none;">
            <input type="button" class="srchbutn comncolor"
              id="addCityInput" value="Add Peroid"
              style="padding: 3px 10px;">
          </div>
          <div id="removeCityButton" class="col-lg-2"
            style="display: none;">
            <input type="button" class="srchbutn comncolor"
              id="removeCityInput" value="Remove Peroid"
              style="padding: 3px 10px;">
          </div>
        </div>
<div class='' style='margin-bottom: 0'>
    <div class='row'>
      <div class='col-sm-9 col-sm-offset-3'>
        <a class='btn btn-primary' id="add_package_button"> Submit &
          Continue</a>&nbsp;&nbsp; <a class='btn btn-primary'
          href="<?php echo base_url(); ?>activity/view_with_price">
          Cancel</a>
      </div>
    </div>
  </div>
</div>
</div>
          <!-- Add Activity Ends -->

          <!-- Itenary Starts -->
          <div role="tabpanel" class="tab-pane" id="itenary">
            <div class="col-md-12" id="itinery_details">
              
              
            </div>
          </div>
          <!-- Itenary Ends -->


          <!-- Nationality price Starts -->
           <div role="tabpanel" class="tab-pane" id="nationality_price">
            <div class="col-md-12">
              <div class="nationality_price_info_class clearfix" id="nationality_price_info">
              <div class="nation_price_0" data-myval="0">
                <!-- <div class='form-group col-sm-6'>
                <label class='control-label col-sm-3' for='validation_country'>Country</label>
                <div class='col-sm-4 controls'>
                  <select class='select2 form-control price_element'
                    data-rule-required='true' name='nationality[0]' id="nationality[1]" required>
                    
                    <option value="">Select Location</option>
                        <?php foreach ($country as $coun) {?>
                        <option value='<?php echo $coun->country_id; ?>'><?php echo $coun->name; ?></option>
                        <?php }?>
                      </select>
                </div>
              </div> -->

              <!-- <div class='form-group col-sm-6'>
                <label class='control-label col-sm-3' for='adult'>Price</label>
                <div class='col-sm-4 controls'>
                  <input type="text" name="p_price[0]" id="p_price[0]"
                    data-rule-number="true" data-rule-required='true'
                    placeholder="Price"
                    class='form-control price_element numeric' maxlength='10'
                    minlength='3' required>
                </div>
              </div> -->
              <div class='form-group col-sm-4'>
                <label class='control-label col-sm-3' for='validation_country'>Nationality</label>
                <div class='col-sm-9 controls'>

                   
                  <select class='select2 form-control price_element'
                    data-rule-required='true' name='nationality[0]' id="nationality_0" onchange="get_currency(this.value);" required>
                    
                    <option value="0">Select Currency</option>
                                <?php foreach ($currency as $coun) {
                                  ?>
                                <option value="<?php echo $coun->country_list; ?>"><?php echo $coun->country_name; ?></option>
                                <?php }?>
                              </select>
                </div>
              </div>

              <div class='form-group col-sm-4'>
                <label class='control-label col-sm-3' for='adult'>Currency</label>
                <div class='col-sm-9'>
                  <input type="text" name="currency[0]" id="currency_0"
                    data-rule-required='true'
                    
                    class='form-control' readonly="readonly"  required>
                </div>
              </div>

                


              <div class='form-group col-sm-4 adult_price_div' style="display: none;">
                <label class='control-label col-sm-3' for='adult'>Adult Price</label>
                <div class='col-sm-9'>
                  <input type="text" name="p_price[0]" id="p_price_0"
                    data-rule-number="true" data-rule-required='true'
                    placeholder="Adult Price"
                    class='form-control price_element numeric' maxlength='10'
                    minlength='' required>
                </div>
              </div>
              <div class='form-group col-sm-6 child_price_div' style="display: none;">
                <label class='control-label col-sm-6' for='adult'>Child Price</label>
                <div class='col-sm-6'>
                  <input type="text" name="child_price[0]" id="child_price_0"
                    data-rule-number="true" data-rule-required='true'
                    placeholder="Child Group 1 Price"
                    class='form-control price_element numeric' maxlength='10'
                    minlength='' required>
                </div>
              </div>
              <div class='form-group col-sm-6 infant_price_div' style="display: none;">
                <label class='control-label col-sm-6' for='adult'>Infant Price</label>
                <div class='col-sm-6'>
                  <input type="text" name="infant_price[0]" id="infant_price_0"
                    data-rule-number="true" data-rule-required='true'
                    placeholder="Infant Price"
                    class='form-control price_element numeric' maxlength='10'
                    minlength='' required>
                </div>
              </div>
              <div class='form-group col-sm-6 senior_price_div' style="display: none;">
                <label class='control-label col-sm-6' for='adult'>Senior Citizen Price</label>
                <div class='col-sm-6'>
                  <input type="text" name="senior_price[0]" id="senior_price_0"
                    data-rule-number="true" data-rule-required='true'
                    placeholder="Senior Citizen Price"
                    class='form-control price_element numeric' maxlength='10'
                    minlength='' required>
                </div>
              </div>
            </div>
              </div>
                <div class="addDiscountBox"><span class="btn btn-info pull-right addDiscountBtn">Add</span></div>
              </div>
              <div class='form-actions' style='margin-bottom: 0'>
                <div class='row'>
                  <div class='col-sm-9 col-sm-offset-3'>
                    <a class='btn btn-primary' id="nation_price_button">submit &
                      continue</a>
                  </div>
                </div>
              </div>
            </div> 
          <!-- Nationality price  Ends -->

          <!-- Photo Gallery Starts -->
          <div role="tabpanel" class="tab-pane" id="gallery">
            <div class="col-md-12">
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_company'>Add
                  Images</label>
                <div class='col-sm-3 controls'>
                  <input type="file" title='upload Photos'
                    class='gallery_elements' data-rule-required='true'
                    value="upload photo" id='traveller' name='traveller[]'
                    multiple required> <span id="travel"
                    style="color: #F00; display: none"> Upload Image</span>
                </div>
              </div>
              <div class='form-actions' style='margin-bottom: 0'>
                <div class='row'>
                  <div class='col-sm-9 col-sm-offset-3'>
                    <a class='btn btn-primary' id="gallery_button">submit &
                      continue</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Photo Gallery Ends -->

          <!-- Rate card Starts -->
          <div role="tabpanel" class="tab-pane" id="rate_card">
            <div class="col-md-12">
              <div class='form-group'>  
                <label class='control-label col-sm-3' for='validation_current'>Offer Ticket<span style = 'color:red'>*</span> </label>  
                <div class='col-sm-8 controls '>    
                  <input type='radio' id='offer_ticket_yes' name='offer_ticket' value='Y' onclick='offer_ticket_show();'>&nbsp;<label for='sunday_0'>Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type='radio' id='offer_ticket_no' name='offer_ticket' value='N' onclick='offer_ticket_hide();'>&nbsp;<label for='sunday_0'>No</label>  
                  <div id='offet_ticket_div' style='display: none;'>    
                    <div class='col-sm-6 controls'>   
                      <input type='file' title='Ticket to add' class='itenary_elements'     data-rule-required='true' id='image' name='image' > <span     id='pacmimg' style='color: #F00; display: none' >Please Upload Ticket</span> <br>
                      <textarea name="ticket_description"
                    class="form-control rate_card_elements"
                    data-rule-required="true" cols="70" rows="3"
                    placeholder="Description" ></textarea>  
                    </div></div>      
                  </div>  
                </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_advance'>Cancellation Terms<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
                  <textarea name="advance"
                    class="form-control rate_card_elements"
                    data-rule-required="true" cols="70" rows="3"
                    placeholder="Cancellation Terms" required></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_excludes'>Refundable Terms<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <input type='radio' id='refundable_yes' name='refundable' value='Y'>&nbsp;<label for='sunday_0'>Refundable</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type='radio' id='refundable_no' name='refundable' value='N'>&nbsp;<label for='sunday_0'>Non-Refundable</label>
                  <!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
                  <textarea name="penality"
                    class="form-control rate_card_elements"
                    data-rule-required="true" cols="70" rows="3"
                    placeholder="Refundable Terms" required></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_includes'>Information travelers/ Trip<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <!-- <input type="text" name="includes" id="includes" data-rule-required='true' class='form-control'> -->
                  <textarea name="infor_travellers"
                    class="form-control rate_card_elements"
                    data-rule-required="true" cols="70" rows="3"
                    placeholder="Information travelers/ Trip" required></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_includes'>Inclusion<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <!-- <input type="text" name="includes" id="includes" data-rule-required='true' class='form-control'> -->
                  <textarea name="includes"
                    class="form-control rate_card_elements"
                    data-rule-required="true" cols="70" rows="3"
                    placeholder="Inclusion" required></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_includes'>Inclusion Policy<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <!-- <input type="text" name="includes" id="includes" data-rule-required='true' class='form-control'> -->
                  <textarea name="inclusion_policy"
                    class="form-control rate_card_elements"
                    data-rule-required="true" cols="70" rows="3"
                    placeholder="Inclusion Policy" required></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_excludes'>Exclusion<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <textarea name="excludes"
                    class="form-control rate_card_elements"
                    data-rule-required="true" cols="70" rows="3"
                    placeholder="Exclusion" required></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_excludes'>Exclusion Policy<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <textarea name="exclusion_policy"
                    class="form-control rate_card_elements"
                    data-rule-required="true" cols="70" rows="3"
                    placeholder="Exclusion Policy" required></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_excludes'>Contact Address<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <textarea name="contact_address"
                    class="form-control rate_card_elements"
                    data-rule-required="true" cols="70" rows="3"
                    placeholder="Contact Address" required></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_excludes'>Contact Email Policy<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <input type="text" name="contact_email"
                    class="form-control rate_card_elements"
                    data-rule-required="true" 
                    placeholder="Contact Email" required>
                </div>
              </div>
              <div class='form-actions' style='margin-bottom: 0'>
                <div class='row'>
                  <div class='col-sm-9 col-sm-offset-3'>
                    <button class='btn btn-primary' type='submit'>Submit</button>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <!-- Rate card Ends -->

        </div>
      </form>
    </div>
    <!-- PANEL BODY END -->
  </div>
  <!-- PANEL WRAP END -->
</div>

<script type="text/javascript">
 
     $(document).ready(function(){
      $('.site_time').timepicker();
      $('.site_time_add').timepicker();
         $('#country').on('change', function() {
           $.ajax({
           url: 'get_crs_city/' + $(this).val(),
           dataType: 'json',
           success: function(json) {
               $('select[name=\'cityname_old\']').html(json.result);
           }
       });
         });
         $('#disn').on('change', function() {
           $.ajax({
           url: 'get_sub_category/' + $(this).val(),
           dataType: 'json',
           success: function(json) {
               $('select[name=\'filter_list[1]\']').html(json.result);
           }
       });
         });
         $('#traveller_country').on('change', function() {
           $.ajax({
           url: 'get_crs_city/' + $(this).val(),
           dataType: 'json',
           success: function(json) {
               $('select[name=\'traveller_cityname\']').html(json.result);
           }
       });
         });
         $("#cityname").on('click',function(){
          var dropdownVal=$(this).val();

          $("#textbox").val(dropdownVal); 
    
      });


      $("#adult_age").on('click',function(){
          if($('#adult_age').is(':checked')) {
          $('.adult_price_div').show();
          }
          if(!$('#adult_age').is(':checked')) {
            $('.adult_price_div').hide();
          } 
    
      });
      $("#child_age").on('click',function(){
          if($('#child_age').is(':checked')) {
          $('.child_price_div').show();
          }
          if(!$('#child_age').is(':checked')) {
            $('.child_price_div').hide();
          }
    
      });
      $("#infant_age").on('click',function(){
          if($('#infant_age').is(':checked')) {
          $('.infant_price_div').show();
          }
          if(!$('#infant_age').is(':checked')) {
            $('.infant_price_div').hide();
          }
      });
      $("#senior_age").on('click',function(){
          if($('#senior_age').is(':checked')) {
          $('.senior_price_div').show();
          }
          if(!$('#senior_age').is(':checked')) {
            $('.senior_price_div').hide();
          }
    
      });


         $('.AlphabetsOnly').keypress(function (e) {
        var regex = new RegExp(/^[a-zA-Z\s]+$/);
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        else {
            e.preventDefault();
            return false;
        }
    });


     });
  
     function show_duration_info(duration)
       {
         if(duration=='')
         {
          duration=0;
         }
         if (window.XMLHttpRequest)
         {// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp=new XMLHttpRequest();
         }
         else
         {// code for IE6, IE5
           xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
         }
           xmlhttp.onreadystatechange=function()
         {
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
         {
           document.getElementById("duration_info").innerHTML=xmlhttp.responseText;
         }
         }
         
          // $("#itenary_li").addClass("active");
          // $("#itenary").addClass("active");
          $("#itinery_details").html('');
          var itinery_details = '';
          if(duration==0){
            var j = duration+1;
            itinery_details = "<div class='form-group clearfix'>  <label class='control-label col-sm-3' for='validation_name'>Day </label>  <div class='col-sm-4 controls'>   <input type='text' name='days[]' id='days"+j+"' data-rule-required='true'     readonly value='"+j+"'      class='form-control itenary_elements' required> </div></div><div class='form-group clearfix'> <label class='control-label col-sm-3' for='validation_name'>Description </label>  <div class='col-sm-4 controls'>         <textarea name='desc[]' class='form-control itenary_elements'           data-rule-required='true' cols='70' rows='3'            placeholder='Description' required></textarea>        </div>  </div>";
            $('#itinery_details').append(itinery_details);

          }else{


          for (var i = 0; i < duration; i++) {
            var j = i+1;
            itinery_details = "<div class='form-group' id='sub_activity_selection_duration '>     <label class='control-label col-sm-3' for='validation_current'>Sub Activity Selection       </label>      <div class='sub_activities_duration'>       <div class='div_sub_activity_duration clearfix'>                  <div class='col-sm-2'>           <select class='form-control'  name='filter_list_duration[1]' id='filter_list_duration_1'>            <option value='NA'>Select Filter</option>                        </select>                  </div>        <div class='col-sm-2'> <input type='text' class='form-control'  name='sub_duration_itinery[1]' id='time_duration_itinery_1' placeholder='Time(Hours/ Min)'>       </div>        <div class='col-sm-2 controls'>         <textarea name='desc[1]' class='form-control itenary_elements'            data-rule-required='true' cols='70' rows='3'            placeholder='Description' required></textarea>        </div>        <div class='col-sm-1 controls add_tab padfive add_sub_activities_duration' onclick='add_sub_div();'>              <span class='btn btn-primary'><i class='fa fa-plus'></i></span>           </div>      </div>      </div>    </div>  <div class='form-group clearfix'> <label class='control-label col-sm-3' for='validation_name'>Day </label>  <div class='col-sm-4 controls'>   <input type='text' name='days[]' id='"+j+"' data-rule-required='true'     readonly value='"+j+"'      class='form-control itenary_elements' required> </div></div>";
            $('#itinery_details').append(itinery_details);
            var type = $('#disn').val();
            $.ajax({
                   url: 'get_sub_category/' + type,
                   dataType: 'json',
                   success: function(json) {
                       $('select[name=\'filter_list_duration[1]\']').html(json.result);
                   }
               });
          }
        }
        var day_btn = "<div class='form-actions' style='margin-bottom: 0'>                <div class='row'>                 <div class='col-sm-9 col-sm-offset-3'>                    <a class='btn btn-primary' id='itenary_button'>Submit &                     Continue</a>                  </div>                </div>              </div>";

        $('#itinery_details').append(day_btn);
           // xmlhttp.open("GET","itinerary_loop/"+duration,true);
           // xmlhttp.send();
       }
     $("#addanother").click(function(){
     var addin = '<input type="text" name="ancountry" value="" placeholder="country" class="ma_pro_txt" style="margin:2px;"/><input type="text" name="anstate" placeholder="state" value="" class="ma_pro_txt" style="margin:2px;"/><input type="text" name="ancity" placeholder="city" value="" class="ma_pro_txt" style="margin:2px;"/><div onclick="removeinput()" style="font-weight:bold;cursor:pointer;">Remove</div><br/>';
     $("#addmorefields").html(addin);
  });
  $("#num_of_days").click(function(){
          $('#no_of_days').show();
          $('#day_wise_plan').hide();
          $('#activity_duration_selection').hide();
          $('#sub_activity_selection').hide();
  });
  $("#day_wise").click(function(){
          $('#no_of_days').hide();
          $('#day_wise_plan').show();
          $('#activity_duration_selection').show();
          $('#sub_activity_selection').show();
  });
  $("#booking_availbale_date").click(function(){
        $('.dates_div_booking').html('');
          var count = $('.dates_div').length;
          for (var i = 1; i <= count; i++) {
            var valll = $('#seasons_date_range_'+i).val();
            if(valll==''){
              alert('Please Select Contract Duration!!');
            }else{
            var vall= valll.split(' - ');
            var dates_div_booking =`

        <div class="dates_div_booking"> 
        <div class="dates_div_available_booking_`+i+`"> 
                <div class="dates_div_avlble_booking_`+i+`" data-value="`+i+`">       
                  <div class="col-sm-6"> 
                     <input type="text" class="form-control booking_date_range_`+i+`_1_class" id="booking_date_range_`+i+`_1" name="booking_date_range[`+i+`]_1" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="" />
                     <div id="date_list"></div>
                  </div><div class='col-sm-1 controls add_tab padfive add_avail_booking_dates'>
                        <span class="btn btn-primary" onclick="add_more_avlble_booking(`+i+`,`+vall[0]+`,`+vall[1]+`);"><i class="fa fa-plus"></i></span>
                      </div>
                </div>  
                </div>`;
    $('.available_dates_booking').append(dates_div_booking);
    // $('#booking_date_range_'+i+'_1').daterangepicker({
    //     minDate:  vall[0],
    //     maxDate:  vall[1],
    //     "startDate":  vall[0],
    //     "endDate":  vall[1],
    //     // select: 'multiple',
    //       format: 'DD/MM/YYYY',
    //       // format: 'mm/dd/yyyy',
    //       multidate: true,
    //       daysOfWeekHighlighted: [0,6],
    //       clearBtn: true,
            
    //     });
    $('.booking_date_range_'+i+'_1_class').datepicker({
     // multidate: true,
     // dateFormat: 'dd/mm/yy',
     //      format: 'mm/dd/yyyy',
     //      multidate: true,
     //      daysOfWeekHighlighted: [0,6],
     //      clearBtn: true,
      multidate: true,
      format: 'dd-mm-yyyy',
         minDate:  vall[0],
     maxDate:  vall[1],
     "startDate":  vall[0],
     "endDate":  vall[1],
      // onSelect: function(selectedDate, dateText, inst) { 
      //    $("#date_list").append($("<li>").html($.datepicker.formatDate("dd/MM/yy",this)));
      //  }
        });
    // $('#booking_date_range_'+i).val(valll);
          $('.available_dates_booking').show();
          }
      }
          $('.available_weekdays').hide();
  });
  function add_more_avlble_booking(div_val, min_Date, max_Date){
      var count = $('.dates_div_avlble_booking').length;
      var cc = count +1;
      var dates_div_avlble_booking = '';
      if(count<5){
      var dates_div_avlble_booking =`

        <div class="dates_div_avlble_booking_`+div_val+`" data-value="`+cc+` ">         
                  <div class="col-sm-4"> 
                     
                     <input type="text" class="form-control contract_duration" id="booking_date_range_`+div_val+`_`+cc+`" name="booking_date_range_[`+div_val+`]_`+cc+`" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="" />
                    
                  </div>
                          <div class='col-sm-2 close_bar_avlble_booking controls padfive'>
                    <span class="btn btn-primary"><i class="far fa-times-circle"></i></span>
                  </div>
                </div>`;
    $('.dates_div_available_booking_'+div_val).append(dates_div_avlble_booking);
    $('#booking_date_range_'+div_val+'_'+cc).daterangepicker({
        minDate:  min_Date,
        maxDate:  max_Date,
        "startDate":  min_Date,
        "endDate":  max_Date,
        select: 'multiple',
          format: 'DD/MM/YYYY'
            
        });
  }
  else{
    alert('Maximum Reached');
  }
    }
      $(document).on('click','.close_bar_avlble_booking',function(){
      $(this).closest('.dates_div_avlble_booking').remove();
    });
  $("#booking_availbale_day").click(function(){
      $('.available_weekdays').html('');
          var count = $('.dates_div').length;
          for (var i = 1; i <= count; i++) {
            var valll = $('#seasons_date_range_'+i).val();
            if(valll==''){
              alert('Please Select Contract Duration!!');
            }else{
            var vall= valll.split(' - ');
            var dates_div_booking_days =`<div id="weekdays_div"><div class="col-sm-12">
                  <div class="col-md-2 padfive days_lablel">
                                     <label for="range_`+i+`">Range `+i+`</label>
                                   </div>
                                   <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='monday_`+i+`' name='driver_shift_days[`+i+`]' value="1" type='checkbox'>
                                     <label for="monday_`+i+`">Mon</label>
                                   </div>
                                    <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='tuesday_`+i+`' name='driver_shift_days[`+i+`]' value="2" type='checkbox' >
                                     <label for="tuesday_`+i+`">Tue</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='wednesday_`+i+`' name='driver_shift_days[`+i+`]' value="3" type='checkbox' >
                                     <label for="wednesday_`+i+`">Wed</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='thursday_`+i+`' name='driver_shift_days[`+i+`]' value="4" type='checkbox' >
                                     <label for="thursday_`+i+`">Thu</label>
                                    </div>
                                    <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='friday_`+i+`' name='driver_shift_days[`+i+`]' value="5" type='checkbox' >
                                     <label for="friday_`+i+`">Fri</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='saturday_`+i+`' name='driver_shift_days[`+i+`]' value="6" type='checkbox' >
                                     <label for="saturday_`+i+`">Sat</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='sunday_`+i+`' name='driver_shift_days[`+i+`]' value="7" type='checkbox' >
                                     <label for="sunday_`+i+`">Sun</label>
                                     </div>
                                </div></div>`;
    $('.available_weekdays').append(dates_div_booking_days);
    // $('#booking_date_range_'+i).val(valll);
          $('.available_weekdays').show();
          }
      }
          $('.available_dates_booking').hide();
  });
  function removeinput(){
   $("#addmorefields").html('');
  }
  
       function activate(that) { window.location.href = that; }
  var a;
  $(document).ready(function(){ 
  
  $('#addCityInput').click(function(){
   var cityNo = parseInt($('#multiCityNo').val());
    //alert(cityNo);
    var duration = $('#duration').val();
   var cityNo = cityNo+1;
    var cit = cityNo-1;
   var allCity = '';
   var i = cityNo;
   var s = i-1;
    
   allCity += "<div id='bothCityInputs"+i+"'><div class='form-group'><label class='control-label col-sm-2' for='validation_company'>From Date</label><div class='input-group col-sm-3' ><input class='fromd datepicker2 b2b-txtbox form-control' placeholder='MM/DD/YYYY' id='deptDate"+i+"'  myid='"+i+"' name='sd[]'' type='text'><span class='input-group-addon'><i class='icon-calendar'></i></span></div><label class='control-label col-sm-2' for='validation_name'>To Date</label><div class='input-group col-sm-3' ><input class='form-control b2b-txtbox' placeholder='MM/DD/YYYY' id='too"+i+"' name='ed[]'' type='text' readonly><span class='input-group-addon'><i class='icon-calendar'></i></span><span id='dorigin_error7' style='color:#F00;'></span><span id='dorigin_error' style='color:#F00;'></span><br></div><br></div>";
  
   allCity += "<div class='form-group clearfix'><label class='control-label col-sm-2' for='adult'>Adult Price</label><div class='input-group col-sm-3' ><input type='text' name='adult[]' id='adult"+i+"'  myid='"+i+"' data-rule-number='true' data-rule-required='true' class='form-control'><span class='input-group-addon'><i class='icon-usd'></i></span></div><label class='control-label col-sm-2' for='child'>Child Price</label><div class='input-group col-sm-3' ><input type='text' name='child[]' id='child"+i+"'  myid='"+i+"' data-rule-number='true' data-rule-required='true' class='form-control'><span class='input-group-addon'><i class='icon-usd'></i></span></div></div><hr>";
  allCity += '<script>var d1 = $("#deptDate'+cit+'").datepicker("getDate");'+
                 //'var dd = d1.getDate() + 1;var mm = d1.getMonth() + 1;var yy = d1.getFullYear();'+
                 'd1.setDate(d1.getDate() + parseInt(1));'+
                  'var dd = d1.getDate();var mm = d1.getMonth() + 1;var yy = d1.getFullYear();'+
                 'var to_date = (mm) + "/" + dd + "/" + yy;'+
                 //'var to_date = (mm) + "/" + dd + "/" + yy;'+
                 'alert(to_date);'+
                  'var duration = $("#duration").val();'+
                  '$("#deptDate'+i+'").datepicker({'+
                  'dateFormat: "mm/dd/yy",'+
                  'minDate: to_date,'+
                   'onSelect: function(dateStr) {'+
                    'var d1 = $(this).datepicker("getDate");'+  
                    
  
                    'd1.setDate(d1.getDate() + parseInt(duration));'+
                   
                     'var dd = d1.getDate();var mm = d1.getMonth() + 1;var yy = d1.getFullYear();'+
                     'var to_date = (mm) + "/" + dd + "/" + yy;'+
                     '$("#too'+i+'").val(to_date);'+
                       '}'+
                    '});'+
                       '<\/script>'+
                       '</div>';
   //$("#addMultiCity").append("<label class='control-label col-sm-2' for='validation_company'>From</label><div class='col-sm-3 controls'><input name='sd' id='' type='text' class='datepicker2 b2b-txtbox form-control'     />   <span id='dorigin_error6' style='color:#F00;'></span><br></div><label class='control-label col-sm-3' for='validation_name'>To</label><div class='col-sm-3 controls'><input name='ed' id='' type='text' class='datepicker3 b2b-txtbox form-control'   />  <span id='dorigin_error7' style='color:#F00;'></span><span id='dorigin_error' style='color:#F00;'></span></div>");
                         
   $("#addMultiCity").append(allCity);
   if(cityNo>1){
     $("#removeCityButton").show();
   }
   $('#multiCityNo').val(cityNo);
     });
  $('#removeCityInput').click(function(){
   var cityNo = parseInt($('#multiCityNo').val());
   
   var allCity = '';
   if(cityNo >1){
     $("#bothCityInputs"+cityNo).remove();
     var cityNo = cityNo-1;
     if(cityNo>1){
       $("#removeCityButton").show();
   }
   }
   else
      {
     $("#removeCityButton").hide();
      }
   $('#multiCityNo').val(cityNo);
  });  

$('#add_package_button').click(function(){
  var error_free = true;
    $( ".add_pckg_elements" ).each(function() {
        if($( this ).val() == ''){
          error_free = false;
          $( this ).closest( ".form-group" ).addClass( "has-error" );
        }        
      });
      if(error_free)
      {
          $("#add_package_li").removeClass("active");
          $("#add_package").removeClass("active");
          $("#itenary_li").addClass("active");
          $("#itenary").addClass("active");
          $("html, body").animate({ scrollTop: 0 }, "slow");
          return false;
      }
    });
$('#itenary_button').click(function(){
  var error_free = true;
    $( ".itenary_elements" ).each(function() {
        if($( this ).val() == ''){
          error_free = false;
          $( this ).closest( ".form-group" ).addClass( "has-error" );
        }        
      });
      if(error_free)
      {
    $("#itenary_li").removeClass("active");
    $("#itenary").removeClass("active");
    // $("#gallery_li").addClass("active");
    // $("#gallery").addClass("active");
    $("#add_package_li").removeClass("active");
      $("#add_package").removeClass("active");
    $("#rate_card_li").addClass("active");
    $("#rate_card").addClass("active");
    $("#price_li").removeClass("active");
    $("#nationality_price").removeClass("active");
    $("html, body").animate({ scrollTop: 0 }, "slow");
          return false;
      }
    });
$('#itenary_li').click(function(){
  var num_days = $(".type_of_tour_radio").val();
  if(num_days!='')
  {
    $("#itenary_li").addClass("active");
    $("#itenary").addClass("active");
    // $("#gallery_li").addClass("active");
    // $("#gallery").addClass("active");
    $("#add_package_li").removeClass("active");
      $("#add_package").removeClass("active");
    $("#rate_card_li").removeClass("active");
    $("#rate_card").removeClass("active");
    $("#price_li").removeClass("active");
    $("#nationality_price").removeClass("active");
    $("html, body").animate({ scrollTop: 0 }, "slow");
          return false;
          }else{
            alert('Please select duration');
          }
    });
$('#rate_card_li').click(function(){
    $("#itenary_li").removeClass("active");
    $("#itenary").removeClass("active");
    // $("#gallery_li").addClass("active");
    // $("#gallery").addClass("active");
    $("#add_package_li").removeClass("active");
      $("#add_package").removeClass("active");
    $("#rate_card_li").addClass("active");
    $("#rate_card").addClass("active");
    $("#price_li").removeClass("active");
    $("#nationality_price").removeClass("active");
    $("html, body").animate({ scrollTop: 0 }, "slow");
          return false;
    });
$('#price_li').click(function(){
    $("#itenary_li").removeClass("active");
    $("#itenary").removeClass("active");
    // $("#gallery_li").addClass("active");
    // $("#gallery").addClass("active");
    $("#add_package_li").removeClass("active");
      $("#add_package").removeClass("active");
    $("#rate_card_li").removeClass("active");
    $("#rate_card").removeClass("active");
    $("#price_li").addClass("active");
    $("#nationality_price").addClass("active");
    $("html, body").animate({ scrollTop: 0 }, "slow");
          return false;
    });
/*$('#nation_price_button').click(function(){
  var error_free = true;
    $( ".price_element" ).each(function() {
        if($( this ).val() == ''){
          error_free = false;
          $( this ).closest( ".form-group" ).addClass( "has-error" );
        }        
      });
      if(error_free)
      {
    $("#price_li").removeClass("active");
    $("#nationality_price").removeClass("active");
    $("#gallery_li").addClass("active");
    $("#gallery").addClass("active");
    $("html, body").animate({ scrollTop: 0 }, "slow");
    return false;
      }
    });*/

$('#gallery_button').click(function(){
  var error_free = true;
    $( ".gallery_elements" ).each(function() {
        if($( this ).val() == ''){
          error_free = false;
          $( this ).closest( ".form-group" ).addClass( "has-error" );
        }        
      });
      if(error_free)
      {
    $("#gallery_li").removeClass("active");
    $("#gallery").removeClass("active");
    $("#rate_card_li").addClass("active");
    $("#rate_card").addClass("active");
    $("html, body").animate({ scrollTop: 0 }, "slow");
    return false;
      }
    });
  });
    $(document).ready(function(){ 
  
  $(document).on("change",".fromd",function(){ 
     current_date = $(this).val();
     
   current_id = $(this).attr('id');
   // alert(current_id);
  $(".fromd").each(function(){ 
     previous_dates = $(this).val();
      //alert(previous_dates);
     currenr_id=$(this).attr('id');
  
      
     if(current_date == previous_dates && current_id != currenr_id){
   myid=$("input[type='text']#"+current_id).attr('myid');
     alert("Already Same Date Selected");
     $("#"+current_id).val(" ");
    // alert(myid);
      $("#to"+myid).val(" ");
        $("#too"+myid).val(" ");
  }
   });
  });
  });
  
    $('#validation_country').on('change', function(){
        var country=$(this).val();
        $.ajax({
            type:"POST",
            url: "<?php echo base_url(); ?>activity/get_cities/"+country,
            data:{country:country},
            success:function(wcity)
            {
              $('#city').html(wcity);
            }
          });
      });
    $(document).ready(function () {

      $('.add_avail_dates').on('click',function(){
      var count = $('.dates_div').length;
      var cc = count +1;
      var dates_div = '';
      if(count<5){
      var dates_div =`

        <div class="dates_div" data-value="`+cc+`">         
                  <div class="col-sm-4"> 
                     
                     <input type="text" class="form-control contract_duration" id="seasons_date_range_`+cc+`" name="seasons_date_range[`+cc+`]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="" />
                    
                  </div>
                          <div class='col-sm-2 close_bar controls padfive'>
                    <span class="btn btn-primary"><i class="far fa-times-circle"></i></span>
                  </div>
                </div>`;
    $('.available_dates').append(dates_div);

    // $('#seasons_date_range_'+cc).daterangepicker({
    //    "startDate":  new Date(),
  //          format: 'DD/MM/YYYY'
            
    //     });
    $(function() {
  $('#seasons_date_range_'+cc).daterangepicker({
    "endDate":  new Date(),
    format: 'DD/MM/YYYY',
    opens: 'right'
  }, function(start, end, label) {
     var a = $('#seasons_date_range_'+cc).val();
     $('#all_dates').append(a);
     
  $('.dates_div').each(function(){
    var b = $('.dates_div').data('value');
            var start_date = $(".contract_duration").val();
            // alert(start_date)
          });
});
});
    
  }
  else{
    alert('Maximum Reached');
  }
    });
      $(document).on('click','.close_bar',function(){
      $(this).closest('.dates_div').remove();
    });
$('.health_details').on('click',function(){
      var count = $('.health_div').length;
      var cc = count +1;
      var health_div = '';
      if(count<5){
      var health_div =`

        <div class="health_div">          
                  <div class="col-sm-8"> 
                     
                     <input type="text" class="form-control" id="hlth_restrctn_detls_`+cc+`" name="hlth_restrctn_detls[`+cc+`]" placeholder="Health Restrictions" value="" />
                    
                  </div>
                          <div class='col-sm-2 health_close_bar controls padfive'>
                    <span class="btn btn-primary"><i class="far fa-times-circle"></i></span>
                  </div>
                </div>`;
    $('.health_details_restctn').append(health_div);
    
  }
  else{
    alert('Maximum Reached');
  }
    });
      $(document).on('click','.health_close_bar',function(){
      $(this).closest('.health_div').remove();
    });
      $('.add_sub_activities').on('click',function(){
      var count = $('.div_sub_activity').length;
      var cc = count +1;
      var sub_activity = '';
      var sub_activity =`

        <div class="div_sub_activity">          
                  <div class="col-sm-4"> 
                     
                     <select class='form-control'  name='filter_list[`+cc+`]' id="filter_list_`+cc+`">
                      <option value="NA">Select Filter</option>
                     </select>
                    
                  </div>
                  <div class="col-sm-4"> <input type="text" class='form-control'  name='sub_duration[`+cc+`]' id="time_duration_`+cc+`" placeholder="Time(Hours/ Min)">
                  </div>
                          <div class='col-sm-2 close_bar_activity controls padfive'>
                    <span class="btn btn-primary"><i class="far fa-times-circle"></i></span>
                  </div>
                </div>`;
    $('.sub_activities').append(sub_activity);
    var type = $('#disn').val();
    $.ajax({
           url: 'get_sub_category/' + type,
           dataType: 'json',
           success: function(json) {
               $('select[name=\'filter_list['+cc+']\']').html(json.result);
           }
       });
    });
$(document).on('click','.close_bar_activity',function(){
      $(this).closest('.div_sub_activity').remove();
    });
          

    $('.add_booking_avail_dates').on('click',function(){
      var count = $('.dates_div_booking').length;
      var cc = count +1;
      var dates_div_booking = '';
      if(count<5){
      var dates_div_booking =`

        <div class="dates_div_booking">         
                  <div class="col-sm-4"> 
                     
                     <input type="text" class="form-control" id="booking_date_range_`+cc+`" name="booking_date_range[`+cc+`]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="" />
                    
                  </div>
                          <div class='col-sm-2 close_bar_avl_date controls padfive'>
                    <span class="btn btn-primary"><i class="far fa-times-circle"></i></span>
                  </div>
                </div>`;
    $('.available_dates_booking').append(dates_div_booking);

    $('#booking_date_range_'+cc).daterangepicker({
        "startDate":  new Date(),
          format: 'DD/MM/YYYY'
            
        });
    
  }
  else{
    alert('Maximum Reached');
  }
    });
          $(document).on('click','.close_bar_avl_date',function(){
      $(this).closest('.dates_div_booking').remove();
    });




/*
$('#seasons_date_range_1').daterangepicker({
            format: 'DD/MM/YYYY'
            
        });*/
$('#seasons_date_range_1').daterangepicker({
    "endDate":  new Date(),
    format: 'DD/MM/YYYY',

    //"endDate": "03/21/2020"
});
// $(function() {
//   $('#seasons_date_range_1').daterangepicker({
//     "endDate":  new Date(),
//     format: 'DD/MM/YYYY',
//     opens: 'right'
//   },
// });
// $('#booking_date_range_1').daterangepicker({
//     "endDate":  new Date(),
//     format: 'DD/MM/YYYY'
//     //"endDate": "03/21/2020"
// });
//function(start, end, label) {
  //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
//});

      // $('#price_shift_from').timepicker({
      //     timeFormat: 'h:mm p',
     //        // startTime: '8:00 am',
     //        dynamic: true,
     //        dropdown: true,
     //        scrollbar: true
      // });
      $('#price_shift_from').timepicker({startTime: '6:00 am'});
      $('#price_shift_to').timepicker({startTime: '6:00 am'});
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

    var i=1;
      $('body').delegate(".addDiscountBtn", "click", function(){
      if($('#adult_age').is(':checked')) {
          var shw_hid_adult = '';
          }
          if(!$('#adult_age').is(':checked')) {
            var shw_hid_adult = "style='display: none;'";
          } 
    
          if(!$('#child_age').is(':checked')) {
          var shw_hid_child = "style='display: none;'";
          }
          if($('#child_age').is(':checked')) {
            var shw_hid_child = '';
          }
    
          if(!$('#infant_age').is(':checked')) {
          var shw_hid_infant = "style='display: none;'";
          }
          if($('#infant_age').is(':checked')) {
            var shw_hid_infant = '';
          }

          if(!$('#senior_age').is(':checked')) {
          var shw_hid_senior = "style='display: none;'";
          }
          if($('#senior_age').is(':checked')) {
            var shw_hid_senior = '';
          }
        //console.log(iterateMe);
        var urlbox = '';
          urlbox="<div class='nation_price'><div class='form-group col-sm-4 adult_price_div' "+shw_hid_adult+">                <label class='control-label col-sm-3' for='adult'>Adult Price</label>                <div class='col-sm-9'>                  <input type='text' name='p_price["+i+"]' id='p_price_"+i+"'                    data-rule-number='true' data-rule-required='true'                    placeholder='Adult Price'                    class='form-control price_element numeric' maxlength='10'                    minlength='' required>                </div>              </div>              <div class='form-group col-sm-6 child_price_div' "+shw_hid_child+">                <label class='control-label col-sm-6' for='adult'>Child Price</label>                <div class='col-sm-6'>                  <input type='text' name='child_price["+i+"]' id='child_price_"+i+"'                    data-rule-number='true' data-rule-required='true'                    placeholder='Child Group 1 Price'                    class='form-control price_element numeric' maxlength='10'                    minlength='' required>                </div>              </div>              <div class='form-group col-sm-6 infant_price_div' "+shw_hid_infant+">                <label class='control-label col-sm-6' for='adult'>Infant Price</label>                <div class='col-sm-6'>                  <input type='text' name='infant_price["+i+"]' id='infant_price_"+i+"'                    data-rule-number='true' data-rule-required='true'                    placeholder='Infant Price'                    class='form-control price_element numeric' maxlength='10'                    minlength='' required>                </div>              </div>              <div class='form-group col-sm-6 senior_price_div' "+shw_hid_senior+">                <label class='control-label col-sm-6' for='adult'>Senior Citizen Price</label>                <div class='col-sm-6'>                  <input type='text' name='senior_price["+i+"]' id='senior_price_"+i+"'                    data-rule-number='true' data-rule-required='true'                    placeholder='Senior Citizen Price'                    class='form-control price_element numeric' maxlength='10'                    minlength='' required>                </div>              </div><div class='col-sm-2 close_bar_activity_price controls padfive'>                    <span class='btn btn-primary'><i class='far fa-times-circle'></i></span>                  </div>";
// <div class="addDiscountBox"><span class="btn btn-info pull-right delete_url_Btn">Delete</span></div>
//console.log(urlbox);
    $('#nationality_price_info').append(urlbox);
    i++;
  
  });
$(document).on('click','.close_bar_activity_price',function(){
      $(this).closest('.nation_price').remove();
    });
  // function delete_price_div(count_val){
    
  //  $(".nation_price_"+count_val).remove();
  //  //alert(count_val); 
  // }

    });
$('#repeat_sight').click(function() {
          if ($(this).is(':checked')) {
              // return confirm("Are you sure?");
              $(".repeat_session").css('display','block');
          }
          else {
              $(".repeat_session").css('display','none');
            
          }
      });
</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript">  
$(function () {  
    $("#buttonAdd").bind("click", function () {  
        var div = $("<div />");  
        div.html(GenerateTextbox(""));  
        $("#TextBoxContainer").append(div);  
         $('.site_time_add').timepicker();
         // $('.site_time').val('9:30 AM');
    });  
    $("#buttonGet").bind("click", function () {  
        var values = "";  
        $("input[name=CreateTextbox]").each(function () {  
            values += $(this).val() + "\n";  
        });  
        alert(values);  
    });  
    $("body").on("click", ".remove", function () {  
        $(this).closest("div").remove();  
    });  
});  
function GenerateTextbox(value) {  
    return '<div class="input-group bootstrap-timepicker timepicker col-sm-6 controls" style="padding: 0 15px;"><input id="site_time_add" name="add_time[]" type="text" class="form-control input-small site_time_add"><span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span></div><input type="button" value="Remove" class="remove" />'  
}  

$("#choose_duration").change(function(){
      var duration=$(this).val();
    if(duration==1)
    {
      $('#duratiob_categry_1').text('Hours');
      $('#duratiob_categry_2').text('Minutes');
    }else if(duration==2){
      $('#duratiob_categry_1').text('Days');
      $('#duratiob_categry_2').text('Hours');
    }else if(duration==3){
      $('#duratiob_categry_1').text('Nights');
      $('#duratiob_categry_2').text('Days');
    }
    
    });
</script> 
<script type="text/javascript">
  
    function offer_ticket_show(){
      $('#offet_ticket_div').show();
    }
    function offer_ticket_hide(){
      $('#offet_ticket_div').hide();
    }
  

function uncheckfun(data)
{
  // alert(data.id);
  if($('#'+data.id).is(':checked')) {
    $('#other_difficulty').show();
  }
  if(!$('#'+data.id).is(':checked')) {
    $('#other_difficulty').hide();
  }

}
function get_currency(country_id)
    {
      var pckge_id = $('#pckge_id').val();
     $.ajax({
            url: app_base_url+'activity/get_currency_detls',
            type: 'POST',
            data: {country_id:country_id,pckge_id:pckge_id},
            success:function(result){
              if(result==0){
                alert('Price Already Map for this Country');
                $('#nationality').val(0);
                $('#currency').val('');
                return false;
              }else{
                $('#currency').val(result);
              }
            }
          });
    }


    $(".select-choose__link").click(function() {
  $(this)
    .next(".select-choose__list")
    .slideToggle("fast");
  return false;
});
$(".select-choose__item input[type=checkbox]").each(function() {
  var thisVal = $(this).attr("value") + ", ";
  $(this).change(function() {
    var currentText = $(".select-choose")
      .find(".select-choose__title")
      .val();
    if ($(this).is(":checked")) {
      currentText = currentText + thisVal;
    } else {
      currentText = currentText.replace(thisVal, "");
    }
    $(this)
      .closest(".select-choose")
      .find(".select-choose__title")
      .val(currentText);
  });
});

</script>
 <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyAiR9CLZshY_vQpB7z5M7nIGCg16gfo2E8"></script>
    <script>    
    var map;
    var geocoder;
    var mapOptions = { center: new google.maps.LatLng(0.0, 0.0), zoom: 2,
        mapTypeId: google.maps.MapTypeId.ROADMAP };
    
    function initialize() {     
      var myOptions = {
                center: new google.maps.LatLng(12.851, 77.659 ),
                //center: new google.maps.LatLng(-1.9501,30.0588),
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            geocoder = new google.maps.Geocoder();
            var map = new google.maps.Map(document.getElementById("map_canvas"),
            myOptions);
            google.maps.event.addListener(map, 'click', function(event) {
              placeMarker(event.latLng);
            });

            var marker;
            function placeMarker(location) {              
                if(marker){ //on vérifie si le marqueur existe
                    marker.setPosition(location); //on change sa position
                }else{
                    marker = new google.maps.Marker({ //on créé le marqueur
                        position: location, 
                        map: map
                    });
                }
                 document.getElementById('lat').value=location.lat();
                 document.getElementById('lng').value=location.lng();
                getAddress(location);
            }

      function getAddress(latLng) {       
        geocoder.geocode( {'latLng': latLng},
        function(results, status) {
          if(status == google.maps.GeocoderStatus.OK) {
            if(results[0]) {           
            document.getElementById("hotel_address").value  = results[0].formatted_address;
            var address = results[0].address_components;
            var zipcode = address[address.length - 1].long_name;
            //document.getElementById("city").value     = results[0].address_components[1]['long_name'];
            document.getElementById("postal_code").value  = zipcode;            
            }
            else {
            //document.getElementById("city").value = "No results";
            }
          }
          else {
            //document.getElementById("city").value = status;
          }
        });
      }
    }
      google.maps.event.addDomListener(window, 'load', initialize);

      function getmap(){        
    var edValue = document.getElementById("lat");
        lat = edValue.value;
        var edValue = document.getElementById("lng");
        lng = edValue.value;        
        var newPosition = new google.maps.LatLng(lat,lng);
        if(lat > 0 && lng > 0){
           myOptions = {                
                center: new google.maps.LatLng(lat,lng),
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            geocoder = new google.maps.Geocoder();
            map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);                        
            marker = new google.maps.Marker({ //on créé le marqueur
                        position: newPosition, 
                        map: map
            });            
            getAddress2(newPosition);        
       }        
   }

  function getAddress2(latLng) {        
        geocoder.geocode( {'latLng': latLng},
        function(results, status) {
          if(status == google.maps.GeocoderStatus.OK) {
            if(results[0]) {           
            document.getElementById("hotel_address").value  = results[0].formatted_address;
            var address = results[0].address_components;
            var zipcode = address[address.length - 1].long_name;
            //document.getElementById("city").value     = results[0].address_components[1]['long_name'];
            document.getElementById("postal_code").value  = zipcode;            
            }
            else {
            //document.getElementById("city").value = "No results";
            }
          }
          else {
            //document.getElementById("city").value = status;
          }
        });
      }
      
    // function checkUniqueEmail(email){
    //   var sEmail = document.getElementById('email');
    //   if (sEmail.value != ''){
    //     var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    //     if(!(sEmail.value.match(filter))){
    //       $("#email").val(email);
    //       return false; 
    //     }else{
    //     }
    //   }
    //   return false;
    // }
     function geocodeAddress(address) {
      geocoder.geocode({address:address}, function (results,status)
          { 
             if (status == google.maps.GeocoderStatus.OK) {
              var p = results[0].geometry.location;
              var lat=p.lat();
              var lng=p.lng();
              //createMarker(address,lat,lng);
              ///alert(lng);
              var myOptions = {
                  center: new google.maps.LatLng(lat, lng ),
                      //center: new google.maps.LatLng(-1.9501,30.0588),
                      zoom: 10,
                      mapTypeId: google.maps.MapTypeId.ROADMAP
                  };
                  var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
                   google.maps.event.addListener(map, 'click', function(event) {
                    placeMarker(event.latLng);
                }); 

                   var marker;
                function placeMarker(location) {              
                    if(marker){ //on vérifie si le marqueur existe
                        marker.setPosition(location); //on change sa position
                    }else{
                        marker = new google.maps.Marker({ //on créé le marqueur
                            position: location, 
                            map: map
                        });
                    }
                     document.getElementById('lat').value=location.lat();
                     document.getElementById('lng').value=location.lng();
                    getAddress(location);
                }

          function getAddress(latLng) {       
            geocoder.geocode( {'latLng': latLng},
            function(results, status) {
              if(status == google.maps.GeocoderStatus.OK) {
                if(results[0]) {           
                document.getElementById("hotel_address").value  = results[0].formatted_address;
                var address = results[0].address_components;
                var zipcode = address[address.length - 1].long_name;
                //document.getElementById("city").value     = results[0].address_components[1]['long_name'];
                document.getElementById("postal_code").value  = zipcode;            
                }
                else {
                //document.getElementById("city").value = "No results";
                }
              }
              else {
                //document.getElementById("city").value = status;
              }
            });
          }
            }
            
          }
        );
      }

      $('#cityname').on('change',function(){
      var search_city  = $('#cityname').val();
      var country = $('#country').val();
      if(search_city!=''){
        geocodeAddress(search_city+','+country);
      }
     });

      function add_sub_div(){
            var count = $('.div_sub_activity_duration').length;
      var cc = count +1;
      var sub_activity = '';
      var sub_activity =`

        <div class="div_sub_activity_duration clearfix">         
                  <div class="col-sm-2"> 
                     
                     <select class='form-control'  name='filter_list_duration[`+cc+`]' id="filter_list_duration_`+cc+`">
                      <option value="NA">Select Filter</option>
                     </select>
                    
                  </div>
                  <div class="col-sm-2"> <input type="text" class='form-control'  name='sub_duration_itinery[`+cc+`]' id="time_duration_itinery_`+cc+`" placeholder="Time(Hours/ Min)">
                  </div><div class='col-sm-2 controls'>         <textarea name='desc[`+cc+`]' class='form-control itenary_elements'           data-rule-required='true' cols='70' rows='3'            placeholder='Description' required></textarea>        </div>
                          <div class='col-sm-2 close_bar_activity_itinery controls padfive'>
                    <span class="btn btn-primary"><i class="far fa-times-circle"></i></span>
                  </div>
                </div>`;
    $('.sub_activities_duration').append(sub_activity);
    var type = $('#disn').val();
    $.ajax({
           url: 'get_sub_category/' + type,
           dataType: 'json',
           success: function(json) {
               $('select[name=\'filter_list_duration['+cc+']\']').html(json.result);
           }
       });
          }
    $(document).on('click','.close_bar_activity_itinery',function(){
      $(this).closest('.div_sub_activity_duration').remove();
    });
      </script>
      
<!-- 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script> -->