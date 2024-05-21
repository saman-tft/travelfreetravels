<?php
// $sub_category_description = json_decode($activity_itinerary[0]['sub_category_desc']);
// debug($activity_itinerary);exit;
?><style type="text/css">
.bxrad{border-radius:4px;!important;}
.bxrad-right{border-radius:4px;!important;}
.ui-timepicker-standard{border:none!important;}
.form-control{height:36px!important;}
.bxwde{left:14px!important;}
.hedpad{margin:6px 2px!important;}
  .hdaddbtn{height:35px!important;}
  .hdaddbtn i{padding:5px 6px!important;}
  #weekdays_div label::before{margin:0px 0px 0px -2px!important;}
  .weekdbtm{border:1px solid #ccc!important;margin:3px 0px!important;padding: 0px 2px 5px 2px;}
  #start_time_0{width:95%;}
  #end_time_0{width:95%;}
  .sttime{width:95%;}
  .edtime{width:95%;}
  .dsudact{margin-top:4px!important;}
  .fgthy{margin-left:6px!important;}
  .txtar{height:70px!important;}
  .lrspc{margin-left:5px!important;}
  .ctrwid{margin:0px auto!important;}
  .nation_price_1{margin-bottom:10px!important;}
  .nation_price_2{margin-bottom:10px!important;}
  .input-group .form-control{z-index:0!important;}
  .health_details_restctn .add_tab{margin: 10px 0px!important;}

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
    top: 5px;
    left: 0px;
    font-size: 1em;
    line-height: 1em;
    color: #fff;
    transition: all .2s;
    font-family: 'Lucida Sans Unicode', 'Arial Unicode MS', Arial;
    border-radius: 3px!important;
    width: 14px;
    height: 14px;
    border: 1px solid #ccc;
}
/* checked mark aspect */
[type="checkbox"]:not(:checked) + label:after,
[type="checkbox"]:checked + label:after {
  content: '\2713\0020';
    position: absolute;
    top: 5px;
    left: -1px;
    width: 14px;
    height: 14px;
    font-size: 13px;
    line-height: 16px;
    padding: 0px 2px;
    border-radius: 3px!important;
    color: #ffffff;
    background: #0094ce;
    border:1px solid #0094ce!important;
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
.available_dates_booking{ float: left; }
.dates_div_booking {float: left;width: 100%;margin-top: 7px;}
.sub_activities{ float: left; width:65%; }
.div_sub_activity {float: left;width: 100%;}
/*.close_bar .btn-primary{background-color:transparent!important;border-color: transparent!important;color: #f00;font-size:24px;    font-size: 24px;
    padding: 0 6px;
    margin-top: 22px;}
.close_bar_avl_date .btn-primary{background-color:transparent!important;border-color: transparent!important;color: #f00;font-size:24px;    font-size: 24px;
    padding: 0 6px;
    margin-top: 22px;}
 .close_bar_activity .btn-primary{background-color:transparent!important;border-color: transparent!important;color: #f00;font-size:24px;    font-size: 24px;
    padding: 0 6px;}*/
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
#tbl_no_repeat th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: center;
  background-color: #007dc6;
  color: white;
}
#tbl_no_repeat td {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: center;
}
.thumb {
  width:120px;
  height: 100px;
  margin: 0.2em -0.7em 0 0;
}
.remove_img_preview {
    position: relative;
    top: -32px;
    right: 12px;
    background: black;
    color: white;
    border-radius: 50px;
    font-size: 1.3em;
    padding: 0 0.3em 0;
    text-align: center;
    cursor: pointer;
}
.remove_img_preview:before {
    content: "×";
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
            aria-controls="home" role="tab" data-toggle="">Day Description
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
        action="<?php echo base_url(); ?>index.php/activity/add_package_new/<?php echo $packdata->package_id;?>"
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
                      class='form-control add_pckg_elements' value="<?php echo $packdata->package_name;?>" required>
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
                                                  if($package_type_data[$l]->activity_types_id==$packdata->package_type)
                                  { $selected = 'selected';}else{$selected = '';}
                                                  ?>
                                <option
                                value='<?php echo $package_type_data[$l]->activity_types_id; ?>' <?=$selected?>> <?php echo $package_type_data[$l]->activity_types_name; ?>  </option>
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
                    class="add_pckg_elements form-control txtar" cols="60" rows="3"
                    placeholder="Description" required><?=strip_tags($packdata->package_description);?></textarea>
                  <!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->
                </div>
              </div>
              <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_name'>Supplier Name <span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <input type="text" name="supplier_name" 
                    class="form-control add_pckg_elements"
                    placeholder="Supplier Name" value="<?=$packdata->supplier_name?>" required>
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
              
<label id="choose_main_theme_lbl">Main Theme</label>              
<input type="text" class="form-control add_pckg_elements" name='choose_main_theme' id="choose_main_theme" placeholder="No categories selected." value="<?=$packdata->main_themes?>" readonly>
<label >Sub Theme</label>
<div class="select-choose" >
  <input type="text" class="form-control select-choose__title add_pckg_elements" name='choose_theme' id="choose_theme" placeholder="No categories selected." value="<?=$packdata->theme_types?>" readonly >
  <input type="hidden" id="theme_ids" name="theme_ids" value="<?=$packdata->theme_ids?>">
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
        $i=0;
        foreach ($subtheme as $key => $value) {
        $sub_theme_id = $value['id'];
        $theme_id = $value['activity_theme_id'];
        $sub_theme_name = $value['sub_theme'];
          $selected='';
        if($theme_id==$activity_theme['id'])
        {
          $ids = explode(',', $packdata->theme_ids);
          for($k=0;$k<count($ids);$k++){
          if($sub_theme_id==$ids[$k]){
            // debug($value['sub_theme']);exit;
            $selected='checked';
            break;
          }
        }
            ?>
          
            <li>
              <div class="levelBody">
                
                <div class="select-choose__item">
                  <input type="checkbox"  name="sub_category<?=$sub_theme_id?>" id="catagoryCkbx<?=$sub_theme_id?>" class="checkbox" data-theme="<?php echo $activity_theme['activity_subtheme']; ?>" data-value="<?=$sub_theme_id?>" value='<?=$sub_theme_name?>' <?=$selected?>>
                  <label for="catagoryCkbx<?=$sub_theme_id?>" >
                    <?=$sub_theme_name?></label>
                </div>
              </div>
            </li>
      <?php } $i++; }}} ?>
          </ul>
        </li>
    <?php }?>
</ul>
    </div>    
  </div></div>
              


                </div>
              </div>
              <?php
               $seasons_date = json_decode($packdata->seasons_date);
              $i=1;
              ?>
              <div class="form-group">
                  <label for="field-1" class="col-sm-3 control-label">Contract Duration <span style = "color:red">*</span></label>  
                  <div class="available_dates">
                    <?php
                if(empty($seasons_date)) 
                {?>
                  <div class="dates_div" data-value="1">          
                  <div class="col-sm-4"> 
                     
                     <input type="text" class="form-control contract_duration add_pckg_elements" id="seasons_date_range_1" name="seasons_date_range[1]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="" />
                    <input type="hidden" name="all_dates" id="all_dates">
                  </div>
                  <div class='col-sm-1 controls add_tab padfive add_avail_dates'>
                        <span class="btn btn-primary hdaddbtn"><i class="fa fa-plus"></i></span>
                      </div>
                </div>
                <?php 
             }
foreach ($seasons_date as $key => $seasons_date_value) {
  ?>
  <div class="dates_div" data-value="<?=$i?>">         
                  <div class="col-sm-4"> 
                     
                     <input type="text" class="form-control" id="seasons_date_range_<?php echo$i;?>" name="seasons_date_range[]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="<?php echo$seasons_date_value;?>" readonly="readonly" />
                    
                  </div>
                  <script type="text/javascript">
                     $(document).ready(function(){
                    var i = <?=$i?>;
                    $("#seasons_date_range_"+i).datepicker("destroy");
                  });
                  </script>
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
                  <div class="col-sm-9 controls ">
                  <?php 
                    $datewise='';
                    $weekwise='';
                    if($packdata->booking_availbale == 'D'){
                      $datewise='checked';}else{
                      $weekwise='checked';
                      } ?>
                  <input type="radio" id="booking_availbale_date" name="booking_availbale" value="D" <?=$datewise?> >&nbsp;<label for="sunday_0">Date Wise</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="booking_availbale_day" name="booking_availbale" value="W" <?=$weekwise?> >&nbsp;<label for="sunday_0">Week Wise</label>
                  <div class="col-sm-12 available_dates_booking bxpd" style="display: none;">
                  <div class="dates_div_booking">     
                  <!-- <div > 
                     <input type="text" class="form-control" id="booking_date_range_1" name="booking_date_range[1]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="" />
                    
                  </div>
                  <div class='col-sm-1 controls add_tab padfive add_booking_avail_dates'>
                        <span class="btn btn-primary"><i class="fa fa-plus"></i></span>
                      </div> -->
                </div></div> 
                  <div class="col-sm-12 available_weekdays bxpd" style="display: none;">
                    </div>
                </div>
            </div>

              <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_current'>Type Of Tour<span style = "color:red">*</span>
                </label>
                <div class="col-sm-4 controls ">
                  <!-- <input type="radio" id="num_of_days" name="type_of_tour" class="type_of_tour_radio" value="N" >&nbsp;<label for="sunday_0">Number of days</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
                  <!-- <input type="radio" id="day_wise" name="type_of_tour" class="type_of_tour_radio" value="D" onclick="show_duration_info('')">&nbsp; -->
                  <label for="sunday_0">Day Plan</label>
                  <!-- <div id="no_of_days" style="display: none;"><input type="text" name="duration" data-rule-number='true'
                    class="form-control add_pckg_elements numeric" maxlength='2'
                    minlength='1' id="duration"
                    onblur="show_duration_info(this.value)" size="40"
                    placeholder="Enter Number Between 1-99"></div>  -->
                  <div id="day_wise_plan" >
                    <?php 
                    $fullday='';
                    $halfday='';
                    if($packdata->day_plan == 'F'){
                      $fullday='checked';}else if($packdata->day_plan == 'H'){
                      $halfday='checked';
                      } ?>
                    <span><input type="radio" id="full_day" name="day_plan" value="F" onclick="show_duration_info(this.value);" <?=$fullday?>><label for="full_day">&nbsp;Full Day</label></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span><input type='radio' id='repeat_sight' name='day_plan' value="H" onclick="show_duration_info(this.value);" <?=$halfday?>>
                                     <label for="repeat_sight">&nbsp;Half Day</label></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php 
                    $firsthalf='';
                    $secondhalf='';
                    if($packdata->half_day_type == 'M'){
                      $firsthalf='checked';$secondhalf='disabled';}else if($packdata->half_day_type == 'E'){ $firsthalf='disabled';$secondhalf='checked';
                      } ?>  
                            <!-- <span><input type="checkbox" name="night" id="night" value="N"   class="form-control checkbox"><label for="night">Night</label></span> -->
                            <div class="repeat_session" style="margin-left: 82px;" >
                              <span><input type="radio" name="half_day_type" id="morning" value="M" <?=$firsthalf?>  disabled="disabled"><label for="morning" class="lrspc">First Half</label></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <span><input type="radio" name="half_day_type" id="evening" value="E" <?=$secondhalf?>  disabled="disabled"><label for="evening" class="lrspc">Second Half</label></span>
                            </div></div>
                </div>
                <!-- <span>Days</span> -->
              </div>
              <div class='form-group' id="activity_duration_selection">
                <label class='control-label col-sm-3' for='validation_name'>Excursion Duration Selection<span style = "color:red">*</span></label>
                <div class='col-sm-1 controls'>
                  <label>Hours </label>
                  <select name="activity_duration_hour" id="activity_duration_hour" 
                    class="form-control add_pckg_elements bxrad" ><option value="0">0</option>
                    <!-- <?php
                    for ($i=1; $i <= 24 ; $i++) { ?>
                       <option><?=$i?></option>
                      <?php } ?> --></select>
                  <span id="duration_hour_msg" style="display: none;color: red;">(Please Select Excursion Duration)</span>
                </div>
                <div class='col-sm-1 controls'>
                  <label>Min </label>
                  <select name="activity_duration_min" id="activity_duration_min" 
                    class="form-control add_pckg_elements bxrad"><option value="0">0</option>
                    <?php
                    for ($i = 1; $i <= 45; ++$i) {
                     $mod15 = $i % 15;
                     if ($packdata->activity_duration_min == $i) {
                     $str = 'selected';
                     }else{
                      $str = '';
                     }
                     if (!$mod15) { ?>
                      <option <?=$str?>><?=$i?></option>
                     <?php } } ?></select>
                  <!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->
                </div>
                <?php
                $add_time = json_decode($packdata->add_time);
                foreach ($add_time as $key => $add_time_val) {
                ?>
                <div class='col-sm-4 controls'>
                  <label>Start Time</label>
                  <div class="input-group bootstrap-timepicker timepicker col-sm-6 controls new_time" >
                            <input id="site_time_add" name="add_time[]" type="text" class="form-control input-small site_time_add bxrad" value="<?php echo $add_time_val;?>">
                            <span class="input-group-addon bxrad-right"><i class="glyphicon glyphicon-time"></i></span>
                          </div>
                           <!--  <div class="col-sm-8 controls">
                              <a href="javascript:void(0);" class="new_pick" id="buttonAdd"><i class="fa fa-plus"></i> Add another pickup time</a>
                              <div class="new_time_clone clearfix" id="TextBoxContainer">                     
                            </div>
                          </div> -->
                </div>
                <?php 
                }
                ?>
              </div>
              <div class='form-group' id="sub_activity_selection" >
                <label class='control-label col-sm-3' for='validation_current'>Sub Excursion Selection  
                </label>
                <div class="sub_activities" style="width: 65%;">
                  <?php
                  for($k=0; $k<count($sub_activity_list); $k++) {
                    $j=$k+1;
                   ?> 
                  <div class="div_sub_activity" data-value="<?=$j?>">          
                  <div class="col-sm-4"> 
                    <label>Sub Excursion Type</label>
                    <input type="hidden" name="sub_activity_id_<?=$j?>" value="<?=$sub_activity_list[$k]['id']?>">
                    <select class='select2 form-control add_pckg_elements sub_excursion_type'
                    data-rule-required='true' name='filter_list[<?=$j?>]' id="filter_list_<?=$j?>" data-value="<?=$j?>">
                    <option value='NA'>Select Sub Excursion Type</option>
                    <?php
                    for($i=0;$i<count($sub_category_details);$i++){
                      // debug($sub_category_details[$i]->activity_sub_category);exit;
                      $str = '';

                      if ($sub_category_details[$i]->id == $sub_activity_list[$k]['sub_activity_id'] ) {
                     $str = 'selected';
                     }else{
                      $str = '';
                     }
                     ?>
                       <option value="<?=$sub_category_details[$i]->id?>|<?=$sub_category_details[$i]->activity_sub_category?>" <?=$str?>><?=$sub_category_details[$i]->activity_sub_category?></option>

                      <?php   
                    }
                    ?>
                  </select>
                    <input type="hidden" name="time_limit" id="time_limit">
                  </div>
                  <div class="col-sm-2"><label>Hours</label> <select class='form-control limit_sub_category bxrad'  name='sub_duration_hours[<?=$j?>]' id="sub_duration_hours_<?=$j?>" onchange="calculate_limit(this.id);sub_drp_hid(<?=$j?>)" disabled><option value="0">0</option>
                  <?php
                    for ($i=1; $i <= 24 ; $i++) { 
                      if ($sub_activity_list[$k]['sub_duration_hours'] == $i) {
                      // debug($sub_activity_list);exit;
                     $str = 'selected';
                     }else{
                      $str = '';
                     }?>
                       <option <?=$str?>><?=$i?></option>
                      <?php } ?>
                  </select>
                  </div>
                  <div class="col-sm-2"><label>Min</label> <select class='form-control limit_sub_category bxrad'  name='sub_duration_mins[<?=$j?>]' id="time_duration_mins_<?=$j?>" onchange="calculate_limit(this.id);" disabled><option value="0">0</option>
                    <?php
                    for ($i = 1; $i <= 45; ++$i) {
                     $mod15 = $i % 15;
                     if ($sub_activity_list[$k]['sub_duration_mins'] == $i) {
                     $str = 'selected';
                     }else{
                      $str = '';
                     }
                     if (!$mod15) { ?>
                      <option <?=$str?>><?=$i?></option>
                     <?php } } ?></select>
                  </div>
                  <?php if($k==0){ ?>
                  <div class='col-sm-1 controls add_tab padfive add_sub_activities' style="margin-top:1%;">
                        <br><span class="btn btn-primary hdaddbtn"><i class="fa fa-plus"></i></span>
                      </div>
                      <div class='col-sm-3 controls add_tab padfive'>
                        <br><span id="balance_time" style="display: none;color: red;"></span><span id="balance_time_add_more" style="display: none;color: red;" onclick="show_more();"> Do you want to add more time? <input type="button" class="btn-primary" value="Click here!!"></span>
                      </div>
               <?php }else{ ?>
                 <div class='col-sm-2 close_bar_activity controls padfive' style="padding-top:11px">
                    <br><span class="btn btn-primary hdaddbtn"><i class="far fa-times-circle"></i></span>
                  </div>
               <?php
               }
                  ?>
                </div>
              <?php }
                  ?>
                </div>
              </div>
              <div class='form-group' id="no_of_time" >
                <label class='control-label col-sm-3' for='validation_current'>Number of Times Repeat  
                </label>
                <div>
                  <div >          
                  <div class="col-sm-2"> <label>No of Times </label>
                    <input type="text" class='form-control'  name='no_of_time_repeat' id="no_of_time_repeat" placeholder="Number of Time" value="<?php echo $packdata->no_of_time_repeat;?>">
                  </div>
                  <div class="col-sm-1"> <label>Hours </label>
                    <input type="text" class='form-control bxrad'  name='no_of_time_duration_hr' id="no_of_time_duration_hr" disabled="disabled" placeholder="Hours" value="<?php echo $packdata->activity_duration_hour;?>"><!-- <option value="0">0</option></select> -->
                  </div>
                  <div class="col-sm-1"> <label>Min </label>
                    <input type="text" class='form-control bxrad'  name='no_of_time_duration_min' id="no_of_time_duration_min" disabled="disabled" placeholder="Min" value="<?php echo $packdata->activity_duration_min;?>"><!-- <option value="0">0</option>
                    <?php
                    for ($i = 1; $i <= 45; ++$i) {
                     $mod15 = $i % 15;
                     $str = '';
                     if (!$mod15) { ?>
                      <option><?=$i?></option>
                     <?php } } ?></select> -->
                  </div>
                </div><div class="clearfix"></div>
                <div id="tble_repeat_div" style="display: none;">
                  <table id="tbl_no_repeat" style="width: 30%;margin-left: 26%;margin-top:7%;">
                    <tr><th width="20%" >No</th><th width="40%">Start Time</th><th width="40%">End Time</th></tr>
                    <tbody id="bdy_rpt"></tbody>
                  </table>
                </div>
                </div>
              </div>
              <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_country'>Country<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <select class='select2 form-control add_pckg_elements'
                    data-rule-required='true' name='country' id="country" value ="<?php echo $packdata->package_country;?>" disabled required>
                    <!--  <input type="text" name="country" id="country" data-rule-required='true' class='form-control'>  -->
                    <?php foreach ($countries as $country) {?><option  value='<?php echo $country->country_id;?>'<?php if($country->country_id == $packdata->package_country) { echo "selected=selected"; } ?>><?php echo $country->name;?></option>
                                 <?php }?>
                      </select>
                </div>
              </div>
              <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_current'>City<span style = "color:red">*</span>
                </label>
                <div class='col-sm-4 controls'>
                  <select class='form-control add_pckg_elements'
                    name='cityname_old' id="cityname"  required>
                    <option value ="<?php echo $packdata->package_city;?>"><?php echo $packdata->package_city;?></option>
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
                    <textarea class="form-control txtar" id="activity_address" name="activity_address" placeholder="Address" data-validate="required" data-message-required="Please enter the Address" id="activity_address" rows="7" ><?php echo $packdata->address;?></textarea>
                  </div>                
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-3 control-label">Latitude<span class="text-danger">*</span></label>                  
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="latitude" onblur="getmap()" placeholder="Latitude" data-validate="required" data-message-required="Please enter the latitude of the pick up location" id="lat" value="<?php echo $packdata->latitude;?>" readonly="true">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-3 control-label">Longitude<span class="text-danger">*</span></label>                 
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="longitude" onblur="getmap()" placeholder="Longitude" data-validate="required" data-message-required="Please enter the Longitude of the pick up location" id="lng" value="<?php echo $packdata->longitude;?>" readonly="true">
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
                <?php
                $pickup_time = json_decode($packdata->pickup_time);
                foreach ($pickup_time as $key => $pickup_time_val) {
                ?>
                <div class="form-group clearfix">
                  <label class="control-label col-sm-3">Pick Up Time<span style = "color:red">*</span></label>
                  <div class="input-group bootstrap-timepicker timepicker col-sm-2 controls new_time bxwde" >
                    <input id="site_time" name="pickup_time[]" type="text" class="form-control input-small site_time add_pckg_elements" value="<?php echo $pickup_time_val;?>">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                  </div>
                  <label class="control-label col-sm-3">&nbsp;</label>
                </div>
                <?php 
                }
                ?>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Book Before No.of Days<span style = "color:red">*</span></label>
                  <div class='col-sm-4 controls'>
                    <input type="number" name="bok_no_day" min="0" placeholder="Enter Book Before No.of Days" class='form-control add_pckg_elements' value="4" value="<?php echo $packdata->bok_no_day;?>"  >                  
                  </div>
                </div>
        <div class='form-group'>
          <label class='control-label col-sm-3' for='validation_country'>Amenities<span style = "color:red">*</span></label>
          <div class='col-sm-4 controls'>
            <select class='select2 form-control add_pckg_elements txtar'
              data-rule-required='true' name='amenities[]' id="amenities" multiple required>
              <!--  <input type="text" name="country" id="country" data-rule-required='true' class='form-control'>  -->
                  <?php foreach ($amenities as $val) {?>
                  <!-- <option value='<?=$val['id']?>'><?=$val['activity_amenties']?></option> -->
                  <?php }

                   $amenities_selct = json_decode($packdata->amenities);
                                foreach($amenities as $k => $val)
                                {
                                  if(in_array($val['id'],$amenities_selct)){$selected='selected';}
                                  else{$selected='';}

                                  
                                  echo '<option value="'.$val['id'].'" '.$selected.'>'.$val['activity_amenties'].' </option>';
                                }
                    ?>


                </select>
          </div>
        </div>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Passenger Occupancy<span style = "color:red">*</span></label>
                  <div class='col-sm-2 controls'>
                    <label>Minimum Pax</label>
                    <input type="number" placeholder="Enter Minimum Pax" name="min_pax" id="min_pax" min="1" class='form-control add_pckg_elements' required value="<?php echo $packdata->min_pax;?>">
                  </div>
                  <!-- <label class='control-label col-sm-3'>Maximum Pax <span style = "color:red">*</span></label> -->
                  <div class='col-sm-2 controls'>
                    <label>Maximum Pax</label>
                    <input type="number" placeholder="Enter Maximum Pax" name="max_pax" min="0" class='form-control add_pckg_elements' required value="<?php echo $packdata->max_pax;?>">
                  </div>
                </div>
                <div class='form-group'>
                   <?php 
                    if($packdata->adult_age == 'E'){
                      $adult_age='checked';$adult_disabled='';
                      $adult_age_min=$packdata->adult_age_min;
                      $adult_age_max=$packdata->adult_age_max;
                    }else{
                      $adult_age='';$adult_disabled='disabled';
                      $adult_age_min='';
                      $adult_age_max='';
                      }
                      if($packdata->child_age == 'E'){
                      $child_age='checked';$child_disabled='';
                      $child_age_min=$packdata->child_age_min;
                      $child_age_max=$packdata->child_age_max;}else{
                      $child_age='';$child_disabled='disabled';
                      $child_age_min='';
                      $child_age_max='';
                      } 
                      if($packdata->infant_age == 'E'){
                      $infant_age='checked';$infant_disabled='';
                      $infant_age_min=$packdata->infant_age_min;
                      $infant_age_max=$packdata->infant_age_max;}else{
                      $infant_age='';$infant_disabled='disabled';
                      $infant_age_min='';
                      $infant_age_max='';
                      }
                      if($packdata->senior_age == 'E'){
                      $senior_age='checked';$senior_disabled='';
                      $senior_age_min=$packdata->senior_age_min;
                      $senior_age_max=$packdata->senior_age_max;}else{
                      $senior_age='';$senior_disabled='disabled';
                      $senior_age_min='';
                      $senior_age_max='';
                      }   ?>
          <label class='control-label col-sm-3' for='validation_current'>Passenger Group <span style = "color:red">*</span>
          </label>
          <div class='col-sm-6 controls'>
            <label for='validation_current'>Define the age groups that can participate</label><br>
            <!-- <p id="age_grp">Min Age &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Max Age</p><br> -->
            <input type="checkbox" class='form-control checkbox' name="adult_age" id="adult_age" value="E" <?=$adult_age?> ><label for="adult_age">&nbsp;&nbsp;</label>Adult<!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="adult_age_min" id="adult_age_min" class="age_limit_adult_min" data-value="1" style="width: 40px;"  value="<?=$adult_age_min?>" <?=$adult_disabled?>>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<input type="text" name="adult_age_max" id="adult_age_max" style="width: 40px;" class="age_limit_adult_max"  value="<?=$adult_age_max?>" <?=$adult_disabled?>><br> -->
            <input type="checkbox" class='form-control checkbox' name="child_age" id="child_age" value="E" <?=$child_age?>><label for="child_age">&nbsp;&nbsp;</label>Child<!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="child_age_min" id="child_age_min" class="age_limit_child_min" style="width: 40px;" value="<?=$child_age_max?>" <?=$child_disabled?>>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<input type="text" name="child_age_max" id="child_age_max" class="age_limit_child_max" style="width: 40px;"value="<?=$child_age_max?>" <?=$child_disabled?> ><br> -->
           <!--  <input type="checkbox" class='form-control checkbox' name="infant_age" id="infant_age" value="E" <?=$infant_age?>><label for="infant_age">&nbsp;&nbsp;</label>Infant&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="infant_age_min" id="infant_age_min" class="age_limit_infant_min" style="width: 40px;" value="<?=$infant_age_min?>" <?=$infant_disabled?>>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<input type="text" name="infant_age_max" id="infant_age_max" class="age_limit_infant_max" style="width: 40px;" value="<?=$infant_age_max?>" <?=$infant_disabled?>><br>
            <input type="checkbox" class='form-control checkbox' name="senior_age" id="senior_age" value="E" <?=$senior_age?>><label for="senior_age">&nbsp;&nbsp;</label>Senior&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="senior_age_min" id="senior_age_min" class="age_limit_senior_min" style="width: 40px;" value="<?=$senior_age_min?>" <?=$senior_disabled?>>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<input type="text" name="senior_age_max" id="senior_age_max" class="age_limit_senior_max" style="width: 40px;" value="<?=$senior_age_max?>" <?=$senior_disabled?>><br> -->

          </div>
        </div>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Do you pick up Traveler<span style = "color:red">*</span></label>
                  <div class='col-sm-4 controls'>
                    <label>Country</label>
          <select class='select2 form-control add_pckg_elements'
                    data-rule-required='true' name='traveller_country' id="traveller_country" disabled="disabled" required>
                    <!--  <input type="text" name="country" id="country" data-rule-required='true' class='form-control'>  -->
                    <?php foreach ($countries as $country) {?><option  value='<?php echo $country->country_id;?>'<?php if($country->country_id == $packdata->traveller_country) { echo "selected=selected"; } ?>><?php echo $country->name;?></option>
                                 <?php }?>
                    </select>
            </div>
        </div>
        <div class='form-group'>
                    <label class='control-label col-sm-3'>City<span style = "color:red">*</span></label>
                  <div class='col-sm-4 controls'>
                    <select class='form-control add_pckg_elements'
                    name='traveller_cityname' id="traveller_cityname"  required>
                     <option value ="<?php echo $packdata->traveller_cityname;?>"><?php echo $packdata->traveller_cityname;?></option>
                  </select>
          </div>
          </div>
        <div class='form-group'>

        <?php 
                    if($packdata->traveller_pickup == 'Y'){
                      $pickup_status = 'selected';
                    }else if($packdata->traveller_pickup == 'N'){
                      $pickup_val = 'selected'; 
                    }
                    ?>
          <label class='control-label col-sm-3'>Travelers Pick Up<span style = "color:red">*</span></label>
                  <div class='col-sm-4 controls'>
                    <select class='form-control add_pckg_elements'
                    name='traveller_pickup' id="traveller_pickup"  required>
                    <option value='Y' <?=$pickup_status?> >Yes, we pick up all travelers</option>
                    <option value='N' <?=$pickup_val?> >No, we meet meet all travellers at a meeting point</option>
                  </select>
        </div>
        </div>
        <?php 
                    if($packdata->with_transport == 'W'){
                      $with_transport='checked';
                      }else{
                      $with_transport='';
                      }
                      if($packdata->sharing_transport == 'S'){
                      $sharing_transport='checked';
                      }else{
                      $sharing_transport='';
                      } 
                      if($packdata->private_challenging == 'P'){
                      $private_challenging='checked';
                      }else{
                      $private_challenging='';
                      }
                         ?>
              <div class='form-group'>
              <label class='control-label col-sm-3'>Transfer Option<span style = "color:red">*</span></label>
                  <div class='col-sm-4 controls'>
                   <input type="checkbox" class="form-control checkbox" name="with_transport" id="with_transport" value="W" <?=$with_transport?> ><label for="with_transport">&nbsp;&nbsp;</label>Without Transfers<br>
                   <input type="checkbox" class="form-control checkbox" name="sharing_transport" id="sharing_transport" value="S" <?=$sharing_transport?>><label for="sharing_transport">&nbsp;&nbsp;</label>Sharing Transfers<br>
                   <input type="checkbox" class="form-control checkbox" name="private_challenging" id="private_challenging" value="P" <?=$private_challenging?>><label for="private_challenging">&nbsp;&nbsp;</label>Private Transfers<br>
        </div>
      </div>
          <div class='form-group'>
              <label class='control-label col-sm-3'>Meeting Instructions<span style = "color:red">*</span></label>
                  <div class='col-sm-4 controls'>
          <textarea name="meeting_instruction"
                    class="form-control txtar"
                    data-rule-required="true" cols="60" rows="3"
                    placeholder="Meeting Instructions" required><?=$packdata->meeting_instruction?></textarea>
           </div>
        </div>
         <div class='form-group'>
              <label class='control-label col-sm-3'>Information travelers need from you<span style = "color:red">*</span></label>
                  <div class='col-sm-4 controls'>
          <b>Health Restrictions</b><br>
          <?php
          $i =1;
          $health_restrictn_selct = json_decode($packdata->health_restrictn);
          foreach ($health_instructions as $key => $restrictions) { 
            if(in_array($restrictions['id'],$health_restrictn_selct)){$selected='checked';}
               else{$selected='';} ?>
            <input type="checkbox" class='form-control checkbox' name="health_restrictn[<?=$i?>]" id="health_instructions_<?=$i?>" value="<?=$restrictions['id']?>" <?=$selected?>><label for="health_instructions_<?=$i?>">&nbsp;&nbsp;</label><?=$restrictions['health_instructions']?><br>

          <?php $i++; }
           ?>
           <div class="health_details_restctn">
              <div class='col-sm-1 controls add_tab padfive health_details nopad'>
                 <span class="btn btn-primary"><i class="fa fa-plus"></i></span>
              </div>
       </div></div></div>
       <div class='form-group'>
        <?php 
                    if($packdata->difficulty_easy == 'O'){
                      $difficulty_easy_o='checked';
                      $difficulty_status_o='';
                      if($packdata->other_difficulty==0){
                        $other_difficulty_data = '';
                      }else{$other_difficulty_data = $packdata->other_difficulty;}
                      }else{
                      $difficulty_easy_o='';
                      $difficulty_status_o='style="display: none;"';
                      }
                      if($packdata->difficulty_easy == 'E'){
                      $difficulty_easy_e='checked';
                      }else{
                      $difficulty_easy_e='';
                      } 
                      if($packdata->difficulty_easy == 'M'){
                      $difficulty_easy_m='checked';
                      }else{
                      $difficulty_easy_m='';
                      }
                      if($packdata->difficulty_easy == 'C'){
                      $difficulty_easy_c='checked';
                      }else{
                      $difficulty_easy_c='';
                      }
                         ?>
           <label class='control-label col-sm-3'>Select the physical difficulty level<span style = "color:red">*</span></label>
           <div class='col-sm-4 controls'>
                   <input type="radio" name="difficulty_easy" id="difficulty_easy" value="E"  <?=$difficulty_easy_e?>><label for="difficulty_easy" >&nbsp;&nbsp;</label>Easy<br>
                   <input type="radio"  name="difficulty_easy" id="difficulty_moderate" value="M" <?=$difficulty_easy_m?>><label for="difficulty_moderate" >&nbsp;&nbsp;</label>Moderate<br>
                   <input type="radio" name="difficulty_easy" id="difficulty_challenging" value="C"  <?=$difficulty_easy_c?>><label for="difficulty_challenging">&nbsp;&nbsp;</label>Challenging<br>
                   <input type="radio"  name="difficulty_easy" id="difficulty_other" value="O" onclick="uncheckfun(this);"  <?=$difficulty_easy_o?>><label for="difficulty_other">&nbsp;&nbsp;</label>Other
                   <input type="text" name="other_difficulty" id="other_difficulty" value ="<?php echo $other_difficulty_data;?>" <?=$difficulty_status_o?>><br>
                </div>
                  <!-- <label class='control-label col-sm-3'>Maximum Pax <span style = "color:red">*</span></label> -->
                  

                </div>
        <div class='form-group'>
          <label class='control-label col-sm-3' for='validation_company'>Excursion
            Display Image <span style = "color:red">*</span></label>
          <div class='col-sm-4 controls'>
            <input type="file" 
              class='' id='photo'
              name='photo' onchange="showMyImage(this,'M')" value="<?php echo $packdata->image; ?>" > <span id="pacmimg"
              style="color: #F00; display: none">Please Upload Excursion Image</span>
              <img id="thumbnil" style="width:40%; margin-top:10px;" src="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images($packdata->image); ?>" alt="image"/>
              <input type="hidden" name='hidephoto' value="<?php echo $packdata->image; ?>">
          </div>
        </div>
        <div class='form-group'>
          <label class='control-label col-sm-3' for='validation_company'>Excursion
            Gallery Images <span style = "color:red">*</span></label>
          <div class='col-sm-9 controls'><span class="btn btn-primary btn-file">
          Browse
            <input type="file" 
              class='' id='files'
              name='gallery_photo[]' multiple="multiple"> <span id="pacmimg"
              style="color: #F00; display: none">Please Upload Excursion Image</span></span><span id="num_of_img"></span> <div id="upload_prev" name="" style="display: none;"></div> 
              <br><output id="list">
                <?php
                 $gallery_image = json_decode($packdata->gallery_image);
                 foreach ($gallery_image as $key => $gallery_images) {?>
                <span><img class="thumb" src="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images($gallery_images); ?>" title="<?=$gallery_images?>"/><span class="remove_img_preview" data-value="<?=$gallery_images?>"></span></span>
                <input type="hidden" name="hidden_gallery[]" id="hidden_gallery" value="<?=$gallery_images?>">
              <?php }
                ?>
              </output>
                <input type="hidden" name="input_hidden_field[]" id="input_hidden_field" >
          </div>
        </div>
        <div class='form-group'>
          <label class='control-label col-sm-3' for='validation_rating'>Rating
          <span style = "color:red">*</span></label>
          <div class="col-sm-4 controls">
            <select class='form-control add_pckg_elements'
              data-rule-required='true' name='rating' id="rating" required>
              <option value="<?php echo $packdata->rating;?>"><?php echo $packdata->rating;?></option>
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
              
              <input type="hidden" id='itinery_description' value="<?=$activity_itinerary[0]['itinerary_description']?>">
            </div>
            <div class='form-actions' style='margin-bottom: 0'>                <div class='row'>                 <div class='col-sm-9 col-sm-offset-3'>                    <a class='btn btn-primary' id='itenary_button'>Submit &                     Continue</a>                  </div>                </div>              </div>
          </div>
          <!-- Itenary Ends -->


          <!-- Nationality price Starts -->
           <div role="tabpanel" class="tab-pane" id="nationality_price">
            <div class="col-md-12">
              <div class="nationality_price_info_class clearfix" id="nationality_price_info">
              <div class='form-group '>
                <label class='control-label col-sm-3' for='validation_country'>Nationality<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>

                   
                  <select class='select2 form-control price_element'
                    data-rule-required='true' name='nationality[0]' id="nationality_0" onchange="get_currency(this.value);" required> 
                    
                    <option value="0">Select Nationality Group</option>
                                <?php foreach ($nationality_group as $coun) {
                                  if($coun->origin==$activity_price[0]['nationality_id']){
                                    $selected = 'selected';
                                  }else{
                                    $selected = '';
                                  }
                                  ?>
                                <option value="<?php echo $coun->origin; ?>" <?=$selected?> ><?php echo $coun->name; ?></option>
                                <?php }?>
                              </select>
                </div>
              </div>

              <!-- <div class='form-group '>
                <label class='control-label col-sm-3' for='adult'>Currency</label>
                <div class='col-sm-4'>
                  <input type="text" name="currency[0]" id="currency_0"
                    data-rule-required='true'
                    
                    class='form-control' readonly="readonly"  required>
                </div>
              </div> -->
              </div>
                <!-- <div class="addDiscountBox"><span class="btn btn-info pull-right addDiscountBtn">Add</span></div> -->
              </div>
              <div class='form-actions' style='margin-bottom: 0'>
                <div class='row'>
                  <div class='col-sm-9 col-sm-offset-5'>
                    <a class='btn btn-primary' id="nation_price_button">Submit &
                      Continue</a>
                  </div>
                </div>
              </div>
            </div> 
          <!-- Nationality price  Ends -->

          <!-- Photo Gallery Starts -->
         <!--  <div role="tabpanel" class="tab-pane" id="gallery">
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
          </div> -->
          <!-- Photo Gallery Ends -->

          <!-- Rate card Starts -->
          <div role="tabpanel" class="tab-pane" id="rate_card">
            <div class="col-md-12">
              <div class='form-group'>  
                <label class='control-label col-sm-3' for='validation_current'>Offer Ticket </label>  
                <div class='col-sm-8 controls '>  
                <?php 
                    if($packdata->offer_ticket == 'Y'){
                      $ticket_status_y='checked';$ticket_status_n='';}
                      else{ 
                        $ticket_status_y='';$ticket_status_n='checked';
                      }
                      ?>

                  <input type='radio' id='offer_ticket_yes' name='offer_ticket' value='Y' onclick='offer_ticket_show();' <?=$ticket_status_y?>>&nbsp;<label for='sunday_0'>Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type='radio' id='offer_ticket_no' name='offer_ticket' value='N' onclick='offer_ticket_hide();' <?=$ticket_status_n?>>&nbsp;<label for='sunday_0'>No</label>  
                  <div id='offet_ticket_div' style='display: none;'>    
                    <div class='col-sm-6 controls bxpd'>   
                      <input type='file' title='Ticket to add' class='rate_card_elements'     data-rule-required='true' id='image' name='image' onchange="showMyImage(this,'O');">
                      <img id="thumbnil_ticket" style="width:40%; margin-top:10px;" src="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images($packdata->image_ticket); ?>" alt="image"/> <span     id='pacmimg' style='color: #F00; display: none' >Please Upload Ticket</span> <br>
                      <input type="hidden" name='offer_ticket_image' value="<?php echo $packdata->image_ticket; ?>">
                      <textarea name="ticket_description"
                    class="form-control rate_card_elements txtar" cols="60" rows="3"
                    placeholder="Description" ><?php echo $packdata->ticket_description; ?></textarea>  
                    </div></div>      
                  </div>  
                </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_advance'>Cancellation Terms<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
                  <textarea name="advance"
                    class="form-control rate_card_elements txtar"
                    data-rule-required="true" cols="60" rows="3"
                    placeholder="Cancellation Terms" required><?php echo $cancellation_advance;?></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_excludes'>Refundable Terms<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <?php 
                    if($packdata->refundable == 'Y'){
                      $refundable_y='checked';$refundable_n='';$desc_refund='';$refund_msg='style="display: none;"';}
                      else{ 
                        $refundable_y='';$refundable_n='checked';$desc_refund='style="display: none;"';$refund_msg='';
                      }
                      ?>
                  <input type='radio' id='refundable_yes' name='refundable' value='Y' <?=$refundable_y?>>&nbsp;<label for='sunday_0'>Refundable</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type='radio' id='refundable_no' name='refundable' value='N' <?=$refundable_n?>>&nbsp;<label for='sunday_0'>Non-Refundable</label><span id="refundable_msg" style="display: none;color: red;float:left;">(The current excursion will not available for excursion canellation policy) </span>
                  <!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
                  <textarea name="penality" id="penality" <?=$desc_refund?>
                    class="form-control txtar"
                    data-rule-required="true" cols="60" rows="3"
                    placeholder="Refundable Terms" ><?php echo $refundable_term;?></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_includes'>Information travelers/ Trip<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <!-- <input type="text" name="includes" id="includes" data-rule-required='true' class='form-control'> -->
                  <textarea name="infor_travellers"
                    class="form-control rate_card_elements txtar"
                    data-rule-required="true" cols="60" rows="3"
                    placeholder="Information travelers/ Trip" required><?php echo $packdata->infor_travellers;?></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_includes'>Inclusion<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <!-- <input type="text" name="includes" id="includes" data-rule-required='true' class='form-control'> -->
                  <textarea name="includes"
                    class="form-control rate_card_elements txtar"
                    data-rule-required="true" cols="60" rows="3"
                    placeholder="Inclusion" required><?php echo $packdata->price_includes;?></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_includes'>Inclusion Policy<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <!-- <input type="text" name="includes" id="includes" data-rule-required='true' class='form-control'> -->
                  <textarea name="inclusion_policy"
                    class="form-control rate_card_elements txtar"
                    data-rule-required="true" cols="60" rows="3"
                    placeholder="Inclusion Policy" required><?php echo $packdata->inclusion_policy;?></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_excludes'>Exclusion<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <textarea name="excludes"
                    class="form-control rate_card_elements txtar"
                    data-rule-required="true" cols="60" rows="3"
                    placeholder="Exclusion" required><?php echo $packdata->price_excludes;?></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_excludes'>Exclusion Policy<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <textarea name="exclusion_policy"
                    class="form-control rate_card_elements txtar"
                    data-rule-required="true" cols="60" rows="3"
                    placeholder="Exclusion Policy" required><?php echo $packdata->exclusion_policy;?></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_excludes'>Contact Address<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <textarea name="contact_address"
                    class="form-control rate_card_elements txtar"
                    data-rule-required="true" cols="60" rows="3"
                    placeholder="Contact Address" required><?php echo $packdata->contact_address;?></textarea>
                </div>
              </div>
              <div class='form-group clearfix'>
                <label class='control-label col-sm-3' for='validation_excludes'>Contact Email<span style = "color:red">*</span></label>
                <div class='col-sm-4 controls'>
                  <input type="text" name="contact_email"
                    class="form-control rate_card_elements"
                    data-rule-required="true" id="contact_email"
                    placeholder="Contact Email" value="<?php echo $packdata->contact_email;?>" required>
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
 $('form').submit(function(e) {
    $(':disabled').each(function(e) {
        $(this).removeAttr('disabled');
    })
});
     $(document).ready(function(){
      
 $('body').delegate(".valdtn_div", "keyup", function(value){
    var count_val=$(this).val();
    var regex = /^[0-9]*$/;
    if (!regex.test(count_val)) {
      var data = count_val.substr(0, count_val.length - 1);
        this.value = data;
    }
    else {
        return true;
    }
  });
  $('body').delegate("#no_of_time_repeat", "keyup", function(value){
    var count_val=$(this).val();
    var regex = /^[0-9]*$/;
    if (!regex.test(count_val)) {
      var data = count_val.substr(0, count_val.length - 1);
        this.value = data;
    }
    else {
        return true;
    }
  });
   $('#no_of_time_repeat').on('change', function() {
          var min_pax = $("#no_of_time_repeat").val();  
          if(min_pax<1){
            alert('Minimum Number of Times Repeat Should be 1');
            $("#no_of_time_repeat").val('');  

          }
      });

      $('.site_time').timepicker({ timeFormat: 'h:mm p',
            // startTime: '8:00 am',
            dynamic: true,
            dropdown: true,
            scrollbar: true});
      $('.site_time_add').timepicker({ timeFormat: 'h:mm p',
            startTime: '4:00 am',
            dynamic: true,
            dropdown: true,
            scrollbar: true});
      // function tmTotalHrsOnSite () {
      //   alert(1)
      // };
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
          disn_sub();
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
         $("#refundable_no").on('click',function(){
          $("#refundable_msg").show(); 
          $("#penality").hide();
    
      });
         $("#refundable_yes").on('click',function(){
          $("#refundable_msg").hide(); 
          $("#penality").show();
    
      });
      $('#min_pax').on('change', function() {
          var min_pax = $("#min_pax").val();  
          if(min_pax<1){
            alert('Minimum Pax Occupancy should be 1');
            $("#min_pax").val(1);  

          }
      });

      $("#adult_age").on('click',function(){
          if($('#adult_age').is(':checked')) {
          $("#adult_age_min").attr("disabled", false);
          $("#adult_age_max").attr("disabled", false);
          $('.adult_price_div').show();
          }
          if(!$('#adult_age').is(':checked')) {
          $('.adult_price_div').hide();
          $("#adult_age_min").val('');
          $("#adult_age_max").val('');
          $("#adult_age_min").attr("disabled", true);
          $("#adult_age_max").attr("disabled", true);
          } 
    
      });
      $("#child_age").on('click',function(){
          if($('#child_age').is(':checked')) {
          $("#child_age_min").attr("disabled", false);
          $("#child_age_max").attr("disabled", false);
          $('.child_price_div').show();
          }
          if(!$('#child_age').is(':checked')) {
          $("#child_age_min").val('');
          $("#child_age_max").val('');
          $("#child_age_min").attr("disabled", true);
          $("#child_age_max").attr("disabled", true);
            $('.child_price_div').hide();
          }
    
      });
      $("#infant_age").on('click',function(){
          if($('#infant_age').is(':checked')) {
          $("#infant_age_min").attr("disabled", false);
          $("#infant_age_max").attr("disabled", false);
          $('.infant_price_div').show();
          }
          if(!$('#infant_age').is(':checked')) {
          $("#infant_age_min").val('');
          $("#infant_age_max").val('');
          $("#infant_age_min").attr("disabled", true);
          $("#infant_age_max").attr("disabled", true);
            $('.infant_price_div').hide();
          }
      });
      $("#senior_age").on('click',function(){
          if($('#senior_age').is(':checked')) {
          $("#senior_age_min").attr("disabled", false);
          $("#senior_age_max").attr("disabled", false);
          $('.senior_price_div').show();
          }
          if(!$('#senior_age').is(':checked')) {
          $("#senior_age_min").val('');
          $("#senior_age_max").val('');
          $("#senior_age_min").attr("disabled", true);
          $("#senior_age_max").attr("disabled", true);
            $('.senior_price_div').hide();
          }
    
      });

$('.age_limit_adult_min').on('change', function(){
       var adult_min = $(this).val();
       var adult_max = $('.age_limit_adult_max').val();
       var child_min = $('.age_limit_child_min').val();
       var child_max = $('.age_limit_child_max').val();
       var infant_min = $('.age_limit_infant_min').val();
       var infant_max = $('.age_limit_infant_min').val();
       var senior_min = $('.age_limit_senior_min').val();
       var senior_max = $('.age_limit_senior_max').val();
       if(adult_max!=''){
        if(adult_min>adult_max){
          alert('Adult min age should be less than adult max age');
          $('.age_limit_adult_min').val('');
        }
       }
       if(child_min!='' || child_max!='' || infant_min!='' || infant_max!='' || senior_min!='' || senior_max!='')
        var count = 0;
        var type = 'Adult';
        if((child_min!='' && child_min>adult_min) || (child_max!='' && child_max>adult_min)){ 
          var age_type = 'Child';   var limit_type =  'greater';     
          count++;
        }
        if(infant_min>adult_min || infant_max>adult_min){ 
          var age_type = 'Infant';  var limit_type =  'greater';        
          count++;
        }
        if(senior_min<adult_min || senior_max<adult_min){
          var age_type = 'Senior';  var limit_type =  'less';  
          count++;
        }
        if(count>0)
        {
          $('.age_limit_adult_min').val('');
          alert(''+type+' age should be '+limit_type+' than '+age_type+' age');
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
      $('#no_of_time_repeat').on('change', function() {
           var num = $('#no_of_time_repeat').val();
           var start_date = $('#site_time_add').val();
           var duration_hr = $('#activity_duration_hour').val();
           var duration_min = $('#activity_duration_min').val();
           var day_min = (duration_hr * 60);
           var fullday_min = parseInt(day_min) + parseInt(duration_min);
           var fulldaymin = (fullday_min * num);
           if(fulldaymin>1440){
            alert('Time exceeding 1 day');
            $('#tble_repeat_div').hide();
            return false;
           }
           $.ajax({
                type:"POST",
                url: "<?php echo base_url(); ?>activity/repeated_end_time",
                data:{duration_hr:duration_hr,duration_min:duration_min,start_date:start_date},
                success:function(str)
                {
                  var tim_duration =mintohrs(str);
                  $('#bdy_rpt').html('');
                  if(num=='' || num==0)
                  {
                  $('#tble_repeat_div').hide();
                  }
                  else{
                  for(var i=0 ; i<num; i++){
                    var j=i+1;
                  var details = '<tr><td>'+j+'</td><td><input id="start_time_'+i+'" name="start_time_['+i+']" type="text" class="form-control input-small repeat_time sttime" data-value="'+i+'" readonly></td><td><input id="end_time_'+i+'" name="end_time['+i+']" type="text" disabled class="form-control input-small repeat_time edtime"></td></tr>';
                  $('#bdy_rpt').append(details);
                  }
                  $('.repeat_time').timepicker({ timeFormat: 'h:mm p',
                  startTime: tim_duration,
                  dynamic: true,
                  dropdown: true,
                  scrollbar: true,
                  change: checkTimeAvailbility});
                  $('#tble_repeat_div').show();
                  }
                  $('#start_time_0').val(start_date);
                  checkTimeAvailbility();

              }
            });  
        });
      function checkTimeAvailbility(){
        var id = $(this).data('value');
        if(id==undefined){
          id=0;
        }
        var j = id+1;
        var start_date = $('#start_time_'+id).val();
        // $.ajax({
        //     type:"POST",
        //     url: "<?php echo base_url(); ?>activity/repeated_no_time",
        //     data:{hms:hms},
        //     success:function(data)
        //     {
              // var start_date = data;
              var duration_hr = $('#no_of_time_duration_hr').val();
              var duration_min = $('#no_of_time_duration_min').val();
              $.ajax({
                type:"POST",
                url: "<?php echo base_url(); ?>activity/repeated_end_time",
                data:{duration_hr:duration_hr,duration_min:duration_min,start_date:start_date},
                success:function(str)
                {
                  var end_time =mintohrs(str);
                  // $('#end_time_1').timepicker({startTime: end_time});
                  $('#end_time_'+id).val(end_time);
                  $('#start_time_'+j).val(end_time);

                }
              });
          //   }
          // });
  }
  function mintohrs(mins){
       let hrs = Math.floor(mins / 60);
        // getting the minutes. 
        let min = mins % 60;  
        // formatting the hours. 
         var dd = "AM";
      if (hrs >= 12) {
        dd = "PM";
        hrs = hrs - 12;
      }
    hrs = hrs < 10 ? '0' + hrs : hrs;  
        min = min < 10 ? '0' + min : min;  
        
        return `${hrs}:${min} ${dd}`;

   }
    function mintohrs_seperate(mins){
       let hrs = Math.floor(mins / 60);  
        // getting the minutes. 
        let min = mins % 60;  
        // formatting the hours.
    hrs = hrs < 10 ?  hrs : hrs;  
        min = min < 10 ?  min : min;
        hrs += ' hours';
        min += ' Min';
        
        return `${hrs}  ${min}`;

   }
     // function tmTotalHrsOnSite() {
     //    var hms = $('#start_time_0').val();
     //    alert(hms);
     //    // var hms = '02:04:33';   // your input string
     //    var a = hms.split(':'); // split it at the colons
     //    var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]); 

     //    alert(seconds);
     //  }
     function show_duration_info(type)
       {
         
         if(type=='H')
         {
          var day_type = 'Half Day :';
         }else if(type=='F'){
          var day_type = 'Full Day';
         }else{
          var day_type = 'Full Day';
         }
         var duration = 1
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
        var itinerary_description = $('#itinery_description').val();
          // $("#itenary_li").addClass("active");
          // $("#itenary").addClass("active");
          var excursion_name = $("#name").val();
          $("#itinery_details").html('');
          var itinery_details = '';
          if(duration==1){
            itinery_details = "<div class='form-group clearfix'>  <label class='control-label col-sm-3' for='validation_name'>Excursion Name :</label>  <div class='col-sm-4 controls'>  <label>"+excursion_name+"</label> </div></div>            <div class='form-group clearfix'> <label class='control-label col-sm-3' for='validation_name'>Excursion Period :</label>  <div class='col-sm-8 controls'><label class='control-label' for='validation_name'>"+day_type+"<label id='day_type_name'></label> tour</label></div></div><div class='form-group clearfix'>  <label class='control-label col-sm-3' for='validation_name'>Excursion Description :<span style = 'color:red'>*</span></label>  <div class='col-sm-4 controls'><input type='hidden' name='activity_itinery_id' value='<?=$activity_itinerary[0]['iti_id']?>'> <textarea name='desc[]' class='form-control itenary_elements txtar'           data-rule-required='true' cols='60' rows='3'            placeholder='Description' required>"+itinerary_description+"</textarea>   </div></div>  ";
            $('#itinery_details').append(itinery_details);

          }else{


          for (var i = 0; i < duration; i++) {
            var j = i+1;
            itinery_details = "<div class='form-group' id='sub_activity_selection_duration '>     <label class='control-label col-sm-3' for='validation_current'>Sub Activity Selection       </label>      <div class='sub_activities_duration'>       <div class='div_sub_activity_duration clearfix'>                  <div class='col-sm-2'>           <select class='form-control'  name='filter_list_duration[1]' id='filter_list_duration_1'>            <option value='NA'>Select Filter</option>                        </select>                  </div>        <div class='col-sm-2'> <input type='text' class='form-control'  name='sub_duration_itinery[1]' id='time_duration_itinery_1' placeholder='Time(Hours/ Min)'>       </div>        <div class='col-sm-2 controls'>         <textarea name='desc[1]' class='form-control itenary_elements txtar'            data-rule-required='true' cols='60' rows='3'            placeholder='Description' required></textarea>        </div>        <div class='col-sm-1 controls add_tab padfive add_sub_activities_duration' onclick='add_sub_div();'>              <span class='btn btn-primary hdaddbtn'><i class='fa fa-plus'></i></span>           </div>      </div>      </div>    </div>  <div class='form-group clearfix'> <label class='control-label col-sm-3' for='validation_name'>Day </label>  <div class='col-sm-4 controls'>   <input type='text' name='days[]' id='"+j+"' data-rule-required='true'     readonly value='"+j+"'      class='form-control itenary_elements' required> </div></div>";
            $('#itinery_details').append(itinery_details);
            var type = $('#disn').val();
            $.ajax({
                   // url: 'get_sub_category/' + type,
                   url: "<?php echo base_url(); ?>activity/get_sub_category/"+type,
                   type: 'POST',
                   dataType: 'json',
                   success: function(json) {
                       $('select[name=\'filter_list_duration[1]\']').html(json.result);
                   }
               });
          }
        }
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
    date_booking_availbale();
  });
  function date_booking_availbale(){

        $('.dates_div_booking').html('');
          var count = $('.dates_div').length;
          for (var i = 1; i <= count; i++) {
            var valll = $('#seasons_date_range_'+i).val();
            if(valll==''){
              alert('Please Select Contract Duration!!');
            }else{
            var vall= valll.split(' - ');
             <?php if(!empty($activity_dates)){
              $dates = $activity_dates[0];
              $coun = count($activity_dates[0]);
            for($s=0; $s<$coun; $s++){
              // $cur_duration = $val['duration'];
                ?>
                var cur_durationn = '<?=$dates[$s]['duration']?>';
          if(valll==cur_durationn){
            

            var dates_div_booking =`

          
        <div class="dates_div_booking">
        <label>Duration : `+valll+`</label> 
        <div class="dates_div_available_booking_`+i+`"> 
          <input type="hidden" name="available_range_id_`+i+`" value="<?=$dates[$s]['id']?>"> `;
        var n = 1;
        <?php 
            $activity_booking_dates = json_decode($dates[$s]['activity_booking_dates']);
            foreach ($activity_booking_dates as $key => $activity_dates_value) {
            ?>
        var count_no = $('.dates_div_avlble_booking_'+i).length;
        if(n==1){
         dates_div_booking +=` <div class="dates_div_avlble_booking_`+i+`" data-value="`+i+`">       
                  <div class="col-sm-2 bxpd">
                     <input type="text" class="form-control booking_date_range_`+i+`_`+n+`_class" id="booking_date_range_`+i+`_`+n+`" name="booking_date_range_`+i+`[`+n+`]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="<?=$activity_dates_value?>" />
                     <div id="date_list"></div>
                  </div><div class='col-sm-1 controls add_tab padfive add_avail_booking_dates'>
                        <span class="btn btn-primary hdaddbtn" onclick="add_more_avlble_booking(`+i+`);"><i class="fa fa-plus"></i></span>
                      </div>  `;
                    }else{
        dates_div_booking +=`

        <div class="dates_div_avlble_booking_`+i+`" data-value="`+n+` ">         
                  <div class="col-sm-2 bxpd"> 
                     
                     <input type="text" class="form-control contract_duration" id="booking_date_range_`+i+n+`" name="booking_date_range_`+i+`[`+n+`]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="<?=$activity_dates_value?>" />
                    
                  </div>
                          <div class='col-sm-1 close_bar_avlble_booking controls padfive' data-value="`+i+`">
                    <span class="btn btn-primary hdaddbtn"><i class="far fa-times-circle"></i></span>
                  </div>
                </div>`;
                    }
                    $('#booking_date_range_'+i+n).datepicker({
                    multidate: true,
                    dateFormat: 'dd/mm/yy',
                    multidate: true,
                    daysOfWeekHighlighted: [0,6],
                    clearBtn: true,
                    multidate: true,
                    minDate:  vall[0],
                    maxDate:  vall[1],
                    "startDate":  vall[0],
                    "endDate":  vall[1],
                    });
                    n++;
                    
                <?php } ?>
          dates_div_booking +=` </div>  
                </div>`;
          $('.available_dates_booking').append(dates_div_booking);
   
              }
                <?php  }}else{ ?>
                  var dates_div_booking =`

          
        <div class="dates_div_booking">
        <label>Duration : `+valll+`</label> 
        <div class="dates_div_available_booking_`+i+`"> 
                <div class="dates_div_avlble_booking_`+i+`" data-value="`+i+`">       
                  <div class="col-sm-2 bxpd"> 
                     <input type="text" class="form-control booking_date_range_`+i+`_1_class" id="booking_date_range_`+i+`_1" name="booking_date_range_`+i+`[1]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="" />
                     <div id="date_list"></div>
                  </div><div class='col-sm-1 controls add_tab padfive add_avail_booking_dates'>
                        <span class="btn btn-primary hdaddbtn" onclick="add_more_avlble_booking(`+i+`);"><i class="fa fa-plus"></i></span>
                      </div>
                </div>  
                </div>`;
    $('.available_dates_booking').append(dates_div_booking);
              <?php  }
            ?>
    $('#booking_date_range_'+i+'_1').datepicker({
        multidate: true,
        dateFormat: 'dd/mm/yy',
        format: 'mm/dd/yyyy',
        multidate: true,
        daysOfWeekHighlighted: [0,6],
        clearBtn: true,
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
  }
  var array_dates = [];
  function add_more_avlble_booking(div_val){
      var count = $('.dates_div_avlble_booking_'+div_val).length;
    var min_Date = $( '#booking_date_range_'+div_val+'_1' ).datepicker( "option", "minDate" );
    var max_Date = $( '#booking_date_range_'+div_val+'_1' ).datepicker( "option", "maxDate" );
    if(array_dates==''){
      var prev_date = $('#booking_date_range_'+div_val+'_'+count).val();
    }else{
    var prev_date = $('#booking_date_range_'+div_val+count).val();
    }
    array_dates.push(prev_date)
      var cc = count +1;
      var dates_div_avlble_booking = '';
      if(count<5){
      var dates_div_avlble_booking =`

        <div class="dates_div_avlble_booking_`+div_val+`" data-value="`+cc+` ">         
                  <div class="col-sm-2 bxpd"> 
                     
                     <input type="text" class="form-control contract_duration" id="booking_date_range_`+div_val+cc+`" name="booking_date_range_`+div_val+`[`+cc+`]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="" />
                    
                  </div>
                          <div class='col-sm-1 close_bar_avlble_booking controls padfive' data-value="`+div_val+`">
                    <span class="btn btn-primary hdaddbtn"><i class="far fa-times-circle"></i></span>
                  </div>
                </div>`;
    $('.dates_div_available_booking_'+div_val).append(dates_div_avlble_booking);
    $('#booking_date_range_'+div_val+cc).datepicker({
        multidate: true,
        dateFormat: 'dd/mm/yy',
        multidate: true,
        daysOfWeekHighlighted: [0,6],
        clearBtn: true,
        multidate: true,
        minDate:  min_Date,
        maxDate:  max_Date,
        "startDate":  min_Date,
        "endDate":  max_Date,
        beforeShowDay: function(date){
        var string = jQuery.datepicker.formatDate('dd/mm/yy', date);
        return [ array_dates.indexOf(string) == -1 ]
        }
        });
  }
  else{
    alert('Maximum Reached');
  }
    }
    $(document).on('click','.close_bar_avlble_booking',function(){
        var id= $('.close_bar_avlble_booking').data('value');
      $(this).closest('.dates_div_avlble_booking_'+id).remove();
    });
    $(document).ready(function(){
      if($('#booking_availbale_day').is(':checked')){
        booking_avail_dates_add();
      }
      if($('#booking_availbale_date').is(':checked')){
        date_booking_availbale();
      }
      if($('#full_day').is(':checked')){
        var day = $('#full_day').val();
      }
      if($('#repeat_sight').is(':checked')){
        var day = $('#repeat_sight').val();
      }
      edit_type_oftour(); 
      if($('#offer_ticket_yes').is(':checked')){
      offer_ticket_show()
      } 
      // disn_sub();
     show_duration_info(day);
      <?php 
    if(!empty($packdata->hlth_restrctn_detls)){ ?>
     edit_health_details();
    <?php  } ?>
    });
   function edit_health_details(){
    <?php 
    if(!empty($packdata->hlth_restrctn_detls)){
    $hlth_restrctn_detls = json_decode($packdata->hlth_restrctn_detls);
    foreach ($hlth_restrctn_detls as $key => $hlth_restrctn_value) {?>
  var count = $('.health_div').length;
      var cc = count +1;
      var health_div = '';
      if(count<5){
      var health_div =`

        <div class="health_div col-sm-12 hedpad">          
                  <div class="col-sm-8"> 
                     
                     <input type="text" class="form-control" id="hlth_restrctn_detls_`+cc+`" name="hlth_restrctn_detls[`+cc+`]" placeholder="Health Restrictions" value="<?=$hlth_restrctn_value?>" />
                    
                  </div>
                          <div class='col-sm-2 health_close_bar controls padfive '>
                    <span class="btn btn-primary hdaddbtn"><i class="far fa-times-circle"></i></span>
                  </div>
                </div>`;
    $('.health_details_restctn').append(health_div);
    
  }

  <?php  }} 
  else{ ?>
    alert('Maximum Reached');
  <?php  } ?>
}   
function disn_sub(){
          var sub = $('#disn').val();
          var package_id = '<?=$packdata->package_id?>';
          var arr = [];
           $.ajax({
           // url: 'get_sub_category/' + sub ,
           url: "<?php echo base_url(); ?>activity/get_sub_category/"+sub+"/"+package_id+"/"+"/E",
           type: 'POST',
           dataType: 'json',
           success: function(json) {
               $('select[name=\'filter_list[1]\']').html(json.result);
           }
       });
         }
  $("#booking_availbale_day").click(function(){
     booking_avail_dates_add();
  });
  function booking_avail_dates_add(){
    $('.available_weekdays').html('');
          var count = $('.dates_div').length;
          for (var i = 1; i <= count; i++) {
            var valll = $('#seasons_date_range_'+i).val();
            if(valll==''){
              alert('Please Select Contract Duration!!');
            }else{
            var vall= valll.split(' - ');
              <?php if(!empty($activity_dates)){
              $dates = $activity_dates[0];
              $coun = count($activity_dates[0]);
            for($s=0; $s<$coun; $s++){
              // $cur_duration = $val['duration'];
                ?>
                var cur_durationn = '<?=$dates[$s]['duration']?>';
          if(valll==cur_durationn){
              <?php
                $driver_shift_days = json_decode($dates[$s]['shift_days_week']);

                  $monday = '';
                  $tuesday = '';
                  $wednesday = '';
                  $thursday = '';
                  $friday = '';
                  $saturday = '';
                  $sunday = '';
                  foreach ($driver_shift_days as $shift_key => $value) {
                  

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
            var dates_div_booking_days =`<div id="weekdays_div"><div class="col-sm-8 weekdbtm">
            <input type="hidden" name="available_range_id_`+i+`" value="<?=$dates[$s]['id']?>"> 
                  <div class="col-md-4 padfive days_lablel">
                                     <label for="range_`+i+`">`+valll+` :</label>
                                   </div>
                                   <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='monday_`+i+`' name='driver_shift_days_`+i+`[]' value="1" type='checkbox' <?php echo $monday;?> onclick='checkall(`+i+`);'>
                                     <label for="monday_`+i+`">Mon</label>
                                   </div>
                                    <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='tuesday_`+i+`' name='driver_shift_days_`+i+`[]' value="2" type='checkbox' <?php echo $tuesday;?> onclick='checkall(`+i+`);'>
                                     <label for="tuesday_`+i+`">Tue</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='wednesday_`+i+`' name='driver_shift_days_`+i+`[]' value="3" type='checkbox' <?php echo $wednesday;?> onclick='checkall(`+i+`);'>
                                     <label for="wednesday_`+i+`">Wed</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='thursday_`+i+`' name='driver_shift_days_`+i+`[]' value="4" type='checkbox' <?php echo $thursday;?> onclick='checkall(`+i+`);'>
                                     <label for="thursday_`+i+`">Thu</label>
                                    </div>
                                    <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='friday_`+i+`' name='driver_shift_days_`+i+`[]' value="5" type='checkbox' <?php echo $friday;?> onclick='checkall(`+i+`);'>
                                     <label for="friday_`+i+`">Fri</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='saturday_`+i+`' name='driver_shift_days_`+i+`[]' value="6" type='checkbox' <?php echo $saturday;?>>
                                     <label for="saturday_`+i+`">Sat</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='sunday_`+i+`' name='driver_shift_days_`+i+`[]' value="7" type='checkbox' <?php echo $sunday;?> onclick='checkall(`+i+`);'>
                                     <label for="sunday_`+i+`">Sun</label>
                                     </div>
                                     <div class="col-md-1 padfive days_lablel">
                                             <input class='form-control checkbox' name='alll' id='selectall`+i+`' onclick='checkall(`+i+`);' type='checkbox' >
                                             <label for="selectall`+i+`">All</label>
                                      </div>
                                </div></div>`;
                                }
         <?php }}else{ ?> 
var dates_div_booking_days =`<div id="weekdays_div"><div class="col-sm-8 weekdbtm">
                  <div class="col-md-4 padfive days_lablel">
                                     <label for="range_`+i+`">`+valll+` :</label>
                                   </div>
                                   <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='monday_`+i+`' name='driver_shift_days_`+i+`[]' value="1" type='checkbox' onclick='checkall(`+i+`);'>
                                     <label for="monday_`+i+`">Mon</label>
                                   </div>
                                    <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='tuesday_`+i+`' name='driver_shift_days_`+i+`[]' value="2" type='checkbox' onclick='checkall(`+i+`);' >
                                     <label for="tuesday_`+i+`">Tue</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='wednesday_`+i+`' name='driver_shift_days_`+i+`[]' value="3" type='checkbox' onclick='checkall(`+i+`);' >
                                     <label for="wednesday_`+i+`">Wed</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='thursday_`+i+`' name='driver_shift_days_`+i+`[]' value="4" type='checkbox' onclick='checkall(`+i+`);' >
                                     <label for="thursday_`+i+`">Thu</label>
                                    </div>
                                    <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='friday_`+i+`' name='driver_shift_days_`+i+`[]' value="5" type='checkbox' onclick='checkall(`+i+`);' >
                                     <label for="friday_`+i+`">Fri</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='saturday_`+i+`' name='driver_shift_days_`+i+`[]' value="6" type='checkbox' onclick='checkall(`+i+`);' >
                                     <label for="saturday_`+i+`">Sat</label>
                                  </div>
                                  <div class="col-md-1 padfive days_lablel">
                                     <input class='form-control checkbox' id='sunday_`+i+`' name='driver_shift_days_`+i+`[]' value="7" type='checkbox' onclick='checkall(`+i+`);' >
                                     <label for="sunday_`+i+`">Sun</label>
                                     </div>
                                     <div class="col-md-1 padfive days_lablel">
                                             <input class='form-control checkbox' name='alll' id='selectall`+i+`' onclick='checkall(`+i+`);' type='checkbox' >
                                             <label for="selectall`+i+`">All</label>
                                      </div>
                                </div></div>`;
         <?php } ?>
    $('.available_weekdays').append(dates_div_booking_days);
    // $('#booking_date_range_'+i).val(valll);
          $('.available_weekdays').show();
          }
      }
          $('.available_dates_booking').hide();
  }
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

    if((!$('#full_day').is(':checked'))&&(!$('#repeat_sight').is(':checked'))){
            alert('Please select Any Type of Tour!!');
            return false;
          }
  if($('#repeat_sight').is(':checked')) {
         if((!$('#morning').is(':checked'))&&(!$('#evening').is(':checked'))){
            alert('Please select First Half or Second Half!!');
            return false;
          }
          }
  if((!$('#adult_age').is(':checked'))&&(!$('#child_age').is(':checked'))&&(!$('#infant_age').is(':checked'))&&(!$('#senior_age').is(':checked'))){
    alert('Please select any Passenger Age Group!!');
    return false;
  }
  // if($('#adult_age').is(':checked')) {
  //         var adult_min = $("#adult_age_min").val();
  //         var adult_max = $("#adult_age_max").val();
  //         if(adult_min=='' || adult_max==''){
  //           alert('Please enter Min and Max age of Adult');
  //           return false;
  //         }
  //         }

  // if($('#child_age').is(':checked')) {
  //         var child_min = $("#child_age_min").val();
  //         var child_max = $("#child_age_max").val();
  //         if(child_min=='' || child_max==''){
  //           alert('Please enter Min and Max age of Child');
  //           return false;
  //         }
  //         }
  // if($('#infant_age').is(':checked')) {
  //         var infant_min = $("#infant_age_min").val();
  //         var infant_max = $("#infant_age_max").val();
  //         if(infant_min=='' || infant_max==''){
  //           alert('Please enter Min and Max age of Infant');
  //           return false;
  //         }
  //         }
  // if($('#senior_age').is(':checked')) {
  //         var senior_min = $("#senior_age_min").val();
  //         var senior_max = $("#senior_age_max").val();
  //         if(senior_min=='' || senior_max==''){
  //           alert('Please enter Min and Max age of Senior');
  //           return false;
  //         }
  //         }
   if((!$('#with_transport').is(':checked'))&&(!$('#sharing_transport').is(':checked'))&&(!$('#private_challenging').is(':checked'))){
    alert('Please select any Transport Option!!');
    return false;
  }
  if((!$('#booking_availbale_date').is(':checked'))&&(!$('#booking_availbale_day').is(':checked'))) {
            alert('Please select any Booking Available option');
            return false;
  }
  var amenities_check = $('#amenities').val();
  if(amenities_check==null || amenities_check==''){
    alert('Please select Amenities!!');
    return false;
  }
   $( ".div_sub_activity" ).each(function() {
        var id = $( this ).data('value');
        var sub_category = $('#filter_list_'+id).val();
        if(sub_category == 'NA'){
        alert('Please Select Sub Excursion Type')
        return false;
      }
});

      if(error_free)
      {
          $("#add_package_li").removeClass("active");
          $("#add_package").removeClass("active");
          $("#itenary_li").addClass("active");
          $("#itenary").addClass("active");
          $("html, body").animate({ scrollTop: 0 }, "slow");
          var i = 1;
          $( ".div_sub_activity" ).each(function() {
        var id = $( this ).data('value');
        var sub_category = $('#filter_list_'+id).val();
        var sub_category = sub_category.split('|');
        var sub_category_name = sub_category[1];
        var j = 1;
        <?php 
         $sub_category_description = json_decode($activity_itinerary[0]['sub_category_desc']);
        
         foreach ($sub_category_description as $key => $sub_category_desc) {
          ?> 
           if(i == j){
        var sub_category =`<div class="col-md-12 itinery_details_sub_category`+id+`" data-value="`+id+`" >
        <div class='form-group'>
                <label class='control-label col-sm-3' for='validation_current'>`+sub_category_name+` Description :<span style = "color:red">*</span></label>
        <div class="sub_category" data-value="`+id+`">         
                  <div class="col-sm-4 bxpd"> 
                     
                     <textarea name='sub_category_desc[`+id+`]' class='form-control txtar fgthy '           data-rule-required='true' cols='60' rows='3'            placeholder='Sub Excursion Type Description' required><?=$sub_category_desc?></textarea>
                    
                  </div>
                </div></div></div>`;
              }
              j++;
                <?php
                 } 
        ?>
        var count_sub = $('.itinery_details_sub_category'+id).length;
        if(count_sub>0){
         
      }else{
        $('#itinery_details').append(sub_category);
      }
     i++;
      });
          
$( ".dates_div" ).each(function() {
        var id = $( this ).data('value');
        var valll = $('#seasons_date_range_'+id).val();
        var vall= valll.split(' - ');
        var count_th = 2;
        if($('#adult_age').is(':checked')) {
          var shw_hid_adult = '';
          var price_element_adult = '';
          count_th++;
          }
          if(!$('#adult_age').is(':checked')) {
            var shw_hid_adult = "style='display: none;'";
            var price_element_adult = 'price_element';
          } 
    
          if(!$('#child_age').is(':checked')) {
          var shw_hid_child = "style='display: none;'";
          var price_element_child = 'price_element';
          }
          if($('#child_age').is(':checked')) {
            var shw_hid_child = '';
            var price_element_child = '';
            count_th++;
          }
    
          if(!$('#infant_age').is(':checked')) {
          var shw_hid_infant = "style='display: none;'";
          var price_element_infant = 'price_element';
          }
          if($('#infant_age').is(':checked')) {
            var shw_hid_infant = '';
            var price_element_infant = '';
            count_th++;
          }

          if(!$('#senior_age').is(':checked')) {
          var shw_hid_senior = "style='display: none;'";
          var price_element_senior = 'price_element';
          }
          if($('#senior_age').is(':checked')) {
            var shw_hid_senior = '';
            var price_element_senior = '';
            count_th++;
          }
          if(count_th==3){var width = '60%';}
          else if(count_th==4){var width = '70%';}
          else if(count_th==5){var width = '80%';}
          else if(count_th==6){var width = '100%';}
        var nationality_price =` <div class="nation_price_`+id+`" data-myval="`+id+`">
        <table class='table table-bordered table-striped ctrwid'
                        style='margin-bottom: 1%;width:`+width+`;' id="price_mngmnt_tbl_`+id+`">
        <thead><tr><th colspan='`+count_th+`'>Contract Duration : `+valll+`</th></tr>
        <tr>
        <th>Transfer Option</th>`;
        if(shw_hid_adult==''){
        nationality_price +=`<th>Adult Price</th>`;
        }if(shw_hid_child==''){
        nationality_price +=`<th>Child Price</th>`;
        }if(shw_hid_infant==''){
        nationality_price +=`<th>Infant Price</th>`;
        }if(shw_hid_senior==''){
        nationality_price +=`<th>Senior Price</th>`;
        }
        var k=1;
        
        
          
        nationality_price +=` <th>Add</th></tr>
        </thead>
        <tbody id="price_mngmnt_tbody">`;
        <?php 
        for($i=0;$i<count($activity_price);$i++){
          ?>
          var id_price = '<?=$activity_price[$i]['id']?>';
          var activity_id = '<?=$activity_price[$i]['activity_id']?>';
          var nationality_id = '<?=$activity_price[$i]['nationality_id']?>';
          var duration = '<?=$activity_price[$i]['duration']?>';
          var adult_price = '<?=$activity_price[$i]['adult_price']?>';
          var child_price = '<?=$activity_price[$i]['child_price']?>';
          var infant_price = '<?=$activity_price[$i]['infant_price']?>';
          var senior_price = '<?=$activity_price[$i]['senior_price']?>';
          if(duration==valll){
          nationality_price +=`<tr>
        <td><div class='form-group  transfer_div'>
                <div class='col-sm-12'>
                <input type="hidden" name="transfer_option_id_`+k+`[`+id+`]" value="`+id_price+`">
                  <select name="transfer_option_`+id+`[`+k+`]" id="transfer_option_`+k+`_`+id+`"
                    class='form-control price_element transfer_option_cls_`+id+`'><option value="0">0</option></select>
                </div></div></td>`;
        if(shw_hid_adult==''){
        nationality_price +=`<td><div class='form-group  adult_price_div' `+shw_hid_adult+`>
                <div class='col-sm-12'>
                  <input type="text" name="p_price_`+id+`[`+k+`]" id="p_price_`+k+`_`+id+`"
                    data-rule-number="true" 
                    placeholder="Adult Price"
                    class='form-control price_element numeric valdtn_div' maxlength='10'
                    minlength='' value='`+adult_price+`' >
                </div>
              </div></td>`;}
        if(shw_hid_child==''){
        nationality_price +=`<td><div class='form-group child_price_div' `+shw_hid_child+`>
                <div class='col-sm-12'>
                  <input type="text" name="child_price_`+id+`[`+k+`]" id="child_price_`+k+`_`+id+`"
                    data-rule-number="true" 
                    placeholder="Child Price"
                    class='form-control price_element numeric valdtn_div' maxlength='10'
                    minlength='' value='`+child_price+`'>
                </div>
              </div></td>`;}
        if(shw_hid_infant==''){
        nationality_price +=`<td><div class='form-group infant_price_div' `+shw_hid_infant+`>
                <div class='col-sm-12'>
                  <input type="text" name="infant_price_`+k+`[`+id+`]" id="infant_price_`+k+``+k+`_`+id+`"
                    data-rule-number="true" 
                    placeholder="Infant Price"
                    class='form-control price_element numeric valdtn_div' maxlength='10'
                    minlength='' value='`+infant_price+`'>
                </div>
              </div></td>`;}
        if(shw_hid_senior==''){
        nationality_price +=`<td><div class='form-group senior_price_div' `+shw_hid_senior+`>
                <div class='col-sm-12'>
                  <input type="text" name="senior_price_`+k+`[`+id+`]" id="senior_price_`+k+`_`+id+`"
                    data-rule-number="true" 
                    placeholder="Senior Citizen Price"
                    class='form-control price_element numeric valdtn_div' maxlength='10'
                    minlength='' value='`+senior_price+`'>
                </div>
              </div></td>`;}
         if(shw_hid_adult==''){shw_hid_adult=0;}else{shw_hid_adult=1;} if(shw_hid_child==''){ shw_hid_child=0;}else{shw_hid_child=1;} if(shw_hid_infant==''){shw_hid_infant=0;}else{shw_hid_infant=1;} if(shw_hid_senior==''){shw_hid_senior=0;}else{shw_hid_senior=1;} 
         if(k==1){
        nationality_price +=`<td><div class='col-sm-1 controls add_tab padfive '>
                        <span class="btn btn-primary" onclick="add_nationality_price(`+id+`,`+shw_hid_adult+`,`+shw_hid_child+`,`+shw_hid_infant+`,`+shw_hid_senior+`);"><i class="fa fa-plus"></i></span>
                      </div></td></tr>`;
                  }else{
         nationality_price +=`<td><div class='col-sm-2 close_price_row controls padfive'>
                    <span class="btn btn-primary"><i class="far fa-times-circle"></i></span>
                  </div></td></tr>`;           
                  }
                  k++;
                }
                  
            <?php } ?>


          nationality_price +=`</tbody>
        </table>
             
            </div>`;
            var count_sub = $('.nation_price_'+id).length;
        if(count_sub>0){
         
      }else{
        $('#nationality_price_info').append(nationality_price);
      }
      var p = 1;
      <?php for($i=0;$i<count($activity_price);$i++){
          ?>
          var trans_w = '';
          var trans_p = '';
          var trans_s = '';
          var transfer_option = '<?=$activity_price[$i]['transfer_option']?>';
          var duration = '<?=$activity_price[$i]['duration']?>';
          if(duration==valll){
          if(transfer_option=='W'){ trans_w = 'selected';}
          if(transfer_option=='S'){ trans_s = 'selected';}
          if(transfer_option=='P'){ trans_p = 'selected';}
      $("#transfer_option_"+p+"_"+id).html('');
         var options = '';
        if($('#with_transport').is(':checked')) { 
          options += '<option value="W" '+trans_w+'>Without Transfers</option>';
           }
        if($('#sharing_transport').is(':checked')) {
          options += '<option value="S" '+trans_s+'>Sharing Transfer</option>';
           }
        if($('#private_challenging').is(':checked')) {
          options += '<option value="P" '+trans_p+'>Private Transfers</option>';
           }
        $("#transfer_option_"+p+"_"+id).append(options);  
        p++;
        }
      <?php }
          ?>
      });
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
    $("#rate_card_li").removeClass("active");
    $("#rate_card").removeClass("active");
    $("#price_li").addClass("active");
    $("#nationality_price").addClass("active");
    $("html, body").animate({ scrollTop: 0 }, "slow");
          return false;
      }
    });
// $('#itenary_li').click(function(){
//   var num_days = $(".type_of_tour_radio").val();
//   if(num_days!='')
//   {
//     $("#itenary_li").addClass("active");
//     $("#itenary").addClass("active");
//     $("#add_package_li").removeClass("active");
//       $("#add_package").removeClass("active");
//     $("#rate_card_li").removeClass("active");
//     $("#rate_card").removeClass("active");
//     $("#price_li").removeClass("active");
//     $("#nationality_price").removeClass("active");
//     $("html, body").animate({ scrollTop: 0 }, "slow");
//           return false;
//           }else{
//             alert('Please select duration');
//           }
//     });
// $('#rate_card_li').click(function(){
//     $("#itenary_li").removeClass("active");
//     $("#itenary").removeClass("active");
//     $("#add_package_li").removeClass("active");
//       $("#add_package").removeClass("active");
//     $("#rate_card_li").removeClass("active");
//     $("#rate_card").removeClass("active");
//     $("#price_li").addClass("active");
//     $("#nationality_price").addClass("active");
//     $("html, body").animate({ scrollTop: 0 }, "slow");
//           return false;
//     });
// $('#price_li').click(function(){
//     $("#itenary_li").removeClass("active");
//     $("#itenary").removeClass("active");
//     $("#add_package_li").removeClass("active");
//       $("#add_package").removeClass("active");
//     $("#rate_card_li").addClass("active");
//     $("#rate_card").addClass("active");
//     $("#price_li").removeClass("active");
//     $("#nationality_price").removeClass("active");
//     $("html, body").animate({ scrollTop: 0 }, "slow");
//           return false;
//     });
$('#nation_price_button').click(function(){
  var error_free = true;
    // $( ".price_element" ).each(function() {
    //     if($( this ).val() == ''){
    //       error_free = false;
    //       $( this ).closest( ".form-group" ).addClass( "has-error" );
    //     }        
    //   });
    var group = $('#nationality_0').val();
    if(group==0){
      alert('Please select Nationality Group');
      return false;
    }
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
$('.sub_excursion_type').on('change', function() {
      $("#activity_duration_hour").attr("disabled", true);
      $("#activity_duration_min").attr("disabled", true);
      var type = $('.sub_excursion_type').val();
      var id = $('.sub_excursion_type').data('value');
      if(type!='NA'){
        $("#sub_duration_hours_"+id).attr("disabled", false);
        $("#time_duration_mins_"+id).attr("disabled", false);
      }else{
        $("#sub_duration_hours_"+id).attr("disabled", true);
        $("#time_duration_mins_"+id).attr("disabled", true);
      }    
    });

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
      var valll = $('#seasons_date_range_'+count).val();
      var vall= valll.split(' - ');
      var cc = count +1;
      var dates_div = '';
      if(count<5){
      var dates_div =`

        <div class="dates_div" data-value="`+cc+`">         
                  <div class="col-sm-4"> 
                     
                     <input type="text" class="form-control contract_duration" id="seasons_date_range_`+cc+`" name="seasons_date_range[`+cc+`]" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="" />
                    
                  </div>
                          <div class='col-sm-2 close_bar controls padfive'>
                    <span class="btn btn-primary hdaddbtn"><i class="far fa-times-circle"></i></span>
                  </div>
                </div>`;
    $('.available_dates').append(dates_div);

    // $('#seasons_date_range_'+cc).daterangepicker({
    //    "startDate":  new Date(),
  //          format: 'DD/MM/YYYY'
            
    //     });

    $(function() {
      $('#seasons_date_range_1').daterangepicker("show"); 
  $('#seasons_date_range_'+cc).daterangepicker({
    "endDate":  new Date(),
    format: 'DD/MM/YYYY',
    opens: 'right',
    startDate: vall[1],
    minDate: vall[1],
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
      var count = 0;
      var count = $('.health_div').length;
      var cc = count +1;
      var health_div = '';
      if(count<5){
      var health_div =`

        <div class="health_div col-sm-12 hedpad">          
                  <div class="col-sm-8"> 
                     
                     <input type="text" class="form-control" id="hlth_restrctn_detls_`+cc+`" name="hlth_restrctn_detls[`+cc+`]" placeholder="Health Restrictions" value="" />
                    
                  </div>
                          <div class='col-sm-2 health_close_bar controls padfive '>
                    <span class="btn btn-primary hdaddbtn"><i class="far fa-times-circle"></i></span>
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
      var arr = [];
      for (var i = 0; i < count; i++) {
        var j= i+1;
      var sub_catgry = $('#filter_list_'+j).val();
      sub_catgry = sub_catgry.split('|');
      arr[i] = sub_catgry[0];
      }
      var check = $('#filter_list_'+count).val();
      if(check == 'NA'){
        alert('Please Select Sub Excursion Type')
        return false;
      }
      var cc = count +1;
      var sub_activity = '';
      var sub_activity =`

        <div class="div_sub_activity dsudact" data-value="`+cc+`">          
                  <div class="col-sm-4"> 
                     
                     <select class='form-control'  name='filter_list[`+cc+`]' id="filter_list_`+cc+`" data-value="`+cc+`" onchange="sub_excursion_type_repeate(`+cc+`);">
                      <option value="NA">Select Sub Excursion Type</option>
                     </select>
                    
                  </div>
                  <div class="col-sm-2"> <select class='form-control limit_sub_category'  name='sub_duration_hours[`+cc+`]' id="sub_duration_hours_`+cc+`" onchange="calculate_limit(this.id);sub_drp_hid(`+cc+`);" disabled><option value='0'>0</option></select>
                  </div>
                  <div class="col-sm-2"> <select class='form-control limit_sub_category'  name='sub_duration_mins[`+cc+`]' id="time_duration_mins_`+cc+`"  onchange="calculate_limit(this.id);" disabled><option value='0'>0</option></select>
                  </div>
                          <div class='col-sm-2 close_bar_activity controls padfive'>
                    <span class="btn btn-primary hdaddbtn"><i class="far fa-times-circle"></i></span>
                  </div>
                </div>`;

    var type = $('#disn').val();
    $.ajax({
           // url: 'get_sub_category/' + type,
           url: "<?php echo base_url(); ?>activity/get_sub_category/"+type,
           type: 'POST',
           dataType: 'json',
           data: {arr:arr},
           success: function(json) {
            if(json.result==0){alert('No Sub Excursion Type to select');}else{
              $('.sub_activities').append(sub_activity);
              $('select[name=\'filter_list['+cc+']\']').html(json.result);
            }
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
          urlbox="<div class='nation_price'><div class='form-group col-sm-4 adult_price_div' "+shw_hid_adult+">                <label class='control-label col-sm-3' for='adult'>Adult Price</label>                <div class='col-sm-9'>                  <input type='text' name='p_price["+i+"]' id='p_price_"+i+"'                    data-rule-number='true'                    placeholder='Adult Price'                    class='form-control price_element numeric' maxlength='10'                    minlength='' >                </div>              </div>              <div class='form-group col-sm-6 child_price_div' "+shw_hid_child+">                <label class='control-label col-sm-6' for='adult'>Child Price</label>                <div class='col-sm-6'>                  <input type='text' name='child_price["+i+"]' id='child_price_"+i+"'                    data-rule-number='true' ,                    placeholder='Child Group 1 Price'                    class='form-control price_element numeric' maxlength='10'                    minlength='' >                </div>              </div>              <div class='form-group col-sm-6 infant_price_div' "+shw_hid_infant+">                <label class='control-label col-sm-6' for='adult'>Infant Price</label>                <div class='col-sm-6'>                  <input type='text' name='infant_price["+i+"]' id='infant_price_"+i+"'                    data-rule-number='true'                    placeholder='Infant Price'                    class='form-control price_element numeric' maxlength='10'                    minlength='' >                </div>              </div>              <div class='form-group col-sm-6 senior_price_div' "+shw_hid_senior+">                <label class='control-label col-sm-6' for='adult'>Senior Citizen Price</label>                <div class='col-sm-6'>                  <input type='text' name='senior_price["+i+"]' id='senior_price_"+i+"'                    data-rule-number='true'                     placeholder='Senior Citizen Price'                    class='form-control price_element numeric' maxlength='10'                    minlength='' >                </div>              </div><div class='col-sm-2 close_bar_activity_price controls padfive'>                    <span class='btn btn-primary'><i class='far fa-times-circle'></i></span>                  </div>";
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
// $('#repeat_sight').click(function() {
//           if ($(this).is(':checked')) {
//               // return confirm("Are you sure?");
//               $(".repeat_session").css('display','block');
//           }
//           else {
//               $(".repeat_session").css('display','none');
            
//           }
//       });
$('#repeat_sight').click(function() {
      edit_type_oftour();  
      });
$('#full_day').click(function() {
      
      if($('#full_day').is(':checked')){
       $("#morning").attr("disabled", true);
              $("#evening").attr("disabled", true);
              $("#morning"). prop("checked", false);
              $("#evening"). prop("checked", false);
              $("#activity_duration_hour").html('');
              // $("#sub_duration_hours_1").html('');
              $("#no_of_time_duration_hr").html('');
              var hourss = "<?php echo $packdata->activity_duration_hour;?>";
              var options = '';
              for (var i=0; i <= 24 ; i++) {
              if(i == hourss){
                var status = 'selected';
              }else{
                 var status = ''; 
                }  
                options += '<option value="' + i + '" '+status+'>' + i + '</option>';
                 }
              $("#activity_duration_hour").append(options); 
              // $("#sub_duration_hours_1").append(options);
              $("#no_of_time_duration_hr").append(options);
      }
      if($('#repeat_sight').is(':checked')){
        $("#morning").removeAttr("disabled");
              $("#evening").removeAttr("disabled");
              $("#activity_duration_hour").html('');
              // $("#sub_duration_hours_1").html('');
              $("#no_of_time_duration_hr").html('');
              var hourss = "<?php echo $packdata->activity_duration_hour;?>";
              var options = '';
              for (var i=0; i <= 12 ; i++) {
               if(i == hourss){
                var status = 'selected';
                }else{
                 var status = ''; 
                }  
                options += '<option value="' + i + '" '+status+'>' + i + '</option>';
                 }
              $("#activity_duration_hour").append(options); 
              // $("#sub_duration_hours_1").append(options);
              $("#no_of_time_duration_hr").append(options);
      }   
      });
function edit_type_oftour(){
      if($('#full_day').is(':checked')){
       $("#morning").attr("disabled", true);
              $("#evening").attr("disabled", true);
              $("#morning"). prop("checked", false);
              $("#evening"). prop("checked", false);
              $("#activity_duration_hour").html('');
              $("#no_of_time_duration_hr").html('');
              var hourss = "<?php echo $packdata->activity_duration_hour;?>";
              var options = '';
              for (var i=0; i <= 24 ; i++) {
              if(i == hourss){
                var status = 'selected';
              }else{
                 var status = ''; 
                }  
                options += '<option value="' + i + '" '+status+'>' + i + '</option>';
                 }
              $("#activity_duration_hour").append(options); 
              $("#no_of_time_duration_hr").append(options);
      }
      if($('#repeat_sight').is(':checked')){
        $("#morning").removeAttr("disabled");
              $("#evening").removeAttr("disabled");
              $("#activity_duration_hour").html('');
              // $("#sub_duration_hours_1").html('');
              $("#no_of_time_duration_hr").html('');
              var hourss = "<?php echo $packdata->activity_duration_hour;?>";
              var options = '';
              for (var i=0; i <= 12 ; i++) {
               if(i == hourss){
                var status = 'selected';
                }else{
                 var status = ''; 
                }  
                options += '<option value="' + i + '" '+status+'>' + i + '</option>';
                 }
              $("#activity_duration_hour").append(options); 
              // $("#sub_duration_hours_1").append(options);
              $("#no_of_time_duration_hr").append(options);
      }


}

$('#morning').click(function() {
  var desc ='';
  if($('#morning').is(':checked')) {
    if($('#evening').is(':checked')) {
    desc = 'First Half & Second Half';
    }else{
    desc = ' - First Half';
    }
  }else{
    desc = ' - Second Half';
  }
  $("#day_type_name").text(desc);
          
      });
$('#evening').click(function() {
  var desc ='';
    if($('#morning').is(':checked')) {
    if($('#evening').is(':checked')) {
     desc = 'First Half & Second Half';
    }else{
      desc = ' - First Half';
    }
    }else{
      desc = ' - Second Half';
    }
    $("#day_type_name").text(desc);
      });
</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.3/jquery.timepicker.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.3/jquery.timepicker.min.js"></script>
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
                $('#nationality_0').val(0);
                $('#currency_0').val('');
                return false;
              }else{
                $('#currency_0').val(result);
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
    $(document).click(function (e) {
    e.stopPropagation();
    var container = $(".has-sub");

    //check if the clicked area is dropDown or not
    if (container.has(e.target).length === 0) {
        $('.select-choose__list').hide();
    }
})
  var arrayy = [];
    var duplct_arrayy = [];
    var theme_main =$('#choose_main_theme').val();
    var repeate_theme ='';
$(".select-choose__item input[type=checkbox]").each(function() {
  var thisVal = $(this).attr("value") + ", ";
  var idVal = $(this).data("value") + ",";
  $(this).change(function() {
  var main_theme = $(this).data("theme");
  var maintheme = $(this).data("theme") + ",";
  var theme_val = $('#choose_main_theme').val();
    var currentText = $(".select-choose")
      .find(".select-choose__title")
      .val();
      var theme_ids = $("#theme_ids").val();
    if ($(this).is(":checked")) {
      currentText = currentText + thisVal;
      theme_ids = theme_ids + idVal;
      if (arrayy.indexOf(main_theme) === -1) { 
       theme_main = theme_main + maintheme;
        $('#choose_main_theme').val(theme_main);
        arrayy.push(main_theme)
      }else{
        duplct_arrayy.push(main_theme)
      }
    } else {
      if (duplct_arrayy.indexOf(main_theme) === -1) { 
      theme_main = theme_main.replace(maintheme, "");
        $('#choose_main_theme').val(theme_main);
        arrayy.pop(main_theme)
      }else{
        duplct_arrayy.pop(main_theme)
      }
      currentText = currentText.replace(thisVal, "");
    }
    $(this)
      .closest(".select-choose")
      .find(".select-choose__title")
      .val(currentText);
      $("#theme_ids").val(theme_ids);
  });
});
$('#activity_duration_hour').on('change', function() {
    $('#duration_hour_msg').hide();
      var hr = $('#activity_duration_hour').val();
      $('#no_of_time_duration_hr').val(hr);
      if($('#full_day').is(':checked')){ 
        if(hr==24){
        $("#activity_duration_min").attr("disabled", true);
        $("#no_of_time_duration_min").val(0);
      }else{$("#activity_duration_min").attr("disabled", false);
      $("#no_of_time_duration_min").val(0);} }
      if($('#repeat_sight').is(':checked')){ 
        if(hr==12){
        $("#activity_duration_min").attr("disabled", true);
      }else{$("#activity_duration_min").attr("disabled", false);} }
         });
$('#activity_duration_min').on('change', function() {
      var hr = $('#activity_duration_min').val();
      $('#no_of_time_duration_min').val(hr);
         });

</script>
 <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyCJfvWH36KY3rrRfopWstNfduF5-OzoywY"></script>
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
            document.getElementById("activity_address").value  = results[0].formatted_address;
            var address = results[0].address_components;
            var zipcode = address[address.length - 1].long_name;
            //document.getElementById("city").value     = results[0].address_components[1]['long_name'];
            // document.getElementById("postal_code").value  = zipcode;            
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
            document.getElementById("activity_address").value  = results[0].formatted_address;
            var address = results[0].address_components;
            var zipcode = address[address.length - 1].long_name;
            //document.getElementById("city").value     = results[0].address_components[1]['long_name'];
            // document.getElementById("postal_code").value  = zipcode;            
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
                document.getElementById("activity_address").value  = results[0].formatted_address;
                var address = results[0].address_components;
                var zipcode = address[address.length - 1].long_name;
                //document.getElementById("city").value     = results[0].address_components[1]['long_name'];
                // document.getElementById("postal_code").value  = zipcode;            
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
$('#contact_email').on('change',function(){
    // var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    // var email_val=$(this).val();
    // if (mailformat.test(email_val))
    //             return true
    //         else {
    //             alert("Please input a valid email address!");
    //             $('#contact_email').focus();
    //             return false;
    //         }
       var sEmail = document.getElementById('contact_email');
      if (sEmail.value != ''){
        var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        if(!(sEmail.value.match(filter))){
          alert("Please input a valid email address!");
          $('#contact_email').focus();
          return false; 
        }else{
          var email_val = sEmail.value;
          var length_email = email_val.length;
          if(length_email>50){
            alert("Email Address Length Exceeded!! Maximumlenth allowed is 50.");
          $('#contact_email').focus();
          return false;
          }
        }
      }
      return false;
      
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
                  </div><div class='col-sm-2 controls'>         <textarea name='desc[`+cc+`]' class='form-control txtar itenary_elements'           data-rule-required='true' cols='60' rows='3'            placeholder='Description' required></textarea>        </div>
                          <div class='col-sm-2 close_bar_activity_itinery controls padfive'>
                    <span class="btn btn-primary"><i class="far fa-times-circle"></i></span>
                  </div>
                </div>`;
    $('.sub_activities_duration').append(sub_activity);
    var type = $('#disn').val();
    $.ajax({
           // url: 'get_sub_category/' + type,
           url: "<?php echo base_url(); ?>activity/get_sub_category/"+type,
           type: 'POST',
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
     <!--   <script>
      $("#amenities").select2({
          placeholder: "Select Amenities",
          allowClear: true
      });
    </script> -->
    <script type="text/javascript">
      
function calculate_limit(fld_id){
      // var id = $(this).attr('id');
      // alert(id)      var hr = $('#activity_duration_hour').val();
      var dur_hr = $('#activity_duration_hour').val();
      var dur_min = $('#activity_duration_min').val();
      if(dur_hr==''){
        dur_hr = 0;
      }
      if(dur_min==''){
        dur_min = 0;
      }
       $.ajax({
                type:"POST",
                url: "<?php echo base_url(); ?>activity/repeated_duration",
                data:{dur_hr:dur_hr,dur_min:dur_min},
                success:function(str)
                {
                  var current_time = str;
                  var count = $('.div_sub_activity').length;
                  var time_total = 0;
                  var arr = [];
                  for (var i = 1; i <= count; i++) {
                    j=i-1;
                  var sub_duration_hours = $('#sub_duration_hours_'+i).val();
                  var time_duration_mins = $('#time_duration_mins_'+i).val();
                  if(sub_duration_hours==''){
                    sub_duration_hours = 0;
                  }
                  if(time_duration_mins==''){
                    time_duration_mins = 0;
                  }
                  var hms = sub_duration_hours+':'+time_duration_mins;
                  arr[j] = hms;
                  }
                  $.ajax({
                    type:"POST",
                    url: "<?php echo base_url(); ?>activity/repeated_duration_sub",
                    data:{arr:arr},
                    success:function(data)
                    {
                       time_total =parseInt(data);
                       var bal = current_time - time_total;
                       if(current_time<time_total){
                          alert('Sub activity time Exceeded');
                          $('#'+fld_id).val('');
                          // $(this).next().focus();
                          $(function(){
                              $('#'+fld_id).focus();
                          });
                         var current_balance = $('#balance_time').text();
                         if(current_balance==''){
                          var ball = mintohrs_seperate(current_time);
                          ball ='Balance : '+ball;
                         }else{
                          var ball =current_balance;
                         }
                        $('#balance_time').show();
                        $('#balance_time_add_more').hide();
                        }
                        else{
                       var ball = mintohrs_seperate(bal); 
                          ball ='Balance : '+ball;
                       $('#balance_time').show();
                       $('#balance_time_add_more').hide();
                        }
                        if(current_time==time_total){
                          $('#balance_time_add_more').show();
                          $('#balance_time').hide();
                        }
                       var balance = ball;
                       $('#balance_time').text(balance);

                    }
                  });
                }
              });
    }   

    function sub_excursion_type_repeate(id) {
      $("#activity_duration_hour").attr("disabled", true);
      $("#activity_duration_min").attr("disabled", true);
      var type = $('#filter_list_'+id).val();
      if(type!='NA'){
        $("#sub_duration_hours_"+id).attr("disabled", false);
        $("#time_duration_mins_"+id).attr("disabled", false);
      }else{
        $("#sub_duration_hours_"+id).attr("disabled", true);
        $("#time_duration_mins_"+id).attr("disabled", true);
      } 
      if($('#full_day').is(':checked')){var count=24;}else{var count=12;}
      $("#sub_duration_hours_"+id).html('');
      $("#time_duration_mins_"+id).html('');
              var options = '';
              for (var i=0; i <= count ; i++) { 
                options += '<option value="' + i + '">' + i + '</option>';
                 }
              $("#sub_duration_hours_"+id).append(options);
      var options_min = '';
      for (var j = 0; j <= 45; ++j) {
                     var mod15 = j % 15;
                     if (!mod15) { 
                       options_min += '<option value="' + j + '">' + j + '</option>';
                     } }         
              $("#time_duration_mins_"+id).append(options_min);  
    }    

    function sub_drp_hid(id){
      var hr = $('#activity_duration_hour').val();
      if($('#full_day').is(':checked')){ 
        if(hr==24){
        $("#time_duration_mins_"+id).attr("disabled", true);
      }else{$("#time_duration_mins_"+id).attr("disabled", false);} }
      if($('#repeat_sight').is(':checked')){ 
        if(hr==12){
        $("#time_duration_mins_"+id).attr("disabled", true);
      }else{$("#time_duration_mins_"+id).attr("disabled", false);} }
    }

    function show_more(){
      $("#activity_duration_hour").attr("disabled", false);
      $("#activity_duration_min").attr("disabled", false);
  // $("#activity_duration_hour").prop("size", 10);
  document.getElementById('activity_duration_hour').focus();
  $('#duration_hour_msg').show();
  // $('#activity_duration_hour').trigger('chosen:open')
    }

    function showMyImage(fileInput,type) {
      if(type=='M'){
        $('#thumbnil').show();
      }else{
      $('#thumbnil_ticket').show();
      }
      var files = fileInput.files;
      for (var i = 0; i < files.length; i++) { 
      var file = files[i];
      // var imageType = /image.*/; 
      // if (!file.type.match(imageType)) {
      // continue;
      // } 
      if(type=='M'){
      var img=document.getElementById("thumbnil");
      }else{
      var img=document.getElementById("thumbnil_ticket");
      } 
      img.file = file; 
      var reader = new FileReader();
      reader.onload = (function(aImg) { 
      return function(e) { 
      aImg.src = e.target.result; 
      }; 
      })(img);
      reader.readAsDataURL(file);
      } 
    }

    var count=0;
    var arr = [];
    <?php
       $gallery_image = json_decode($packdata->gallery_image);
       foreach ($gallery_image as $key => $gallery_images) {?>
        var arr11 = '<?=$gallery_images?>';
        arr.push(arr11)
      <?php }
      ?>
  function handleFileSelect(evt) {
    var $fileUpload = $("input#files[type='file']");
    count=count+parseInt($fileUpload.get(0).files.length);
     var files = $('#files')[0].files;
     var filess = evt.target.files;
    for (var i = 0 ,f; i < files.length , f = files[i]; i++) {
      if (arr.indexOf(files[i].name) === -1) { 

      if (!f.type.match('image.*')) {
        continue;
      }
      var reader = new FileReader();
      reader.onload = (function (theFile) {
        return function (e) {
          var span = document.createElement('span');
          span.innerHTML = ['<img class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '"/><span class="remove_img_preview" data-value="' , theFile.name , '"></span>'].join('');
          document.getElementById('list').insertBefore(span, null);
        };
      })(f);

      reader.readAsDataURL(f);
      arr.push(files[i].name)
      $('#input_hidden_field').val(JSON.stringify(arr)); 
      $("#upload_prev").append('<span>' + '<div class="hesh">' + files[i].name + '</div></span>');
      } else {
        alert(files[i].name + " already selected")
      }
    }
   
    var count_h = arr.length;
    var msg = ' '+count_h+' Image Selected';
    $('#num_of_img').text(msg)
  }
  
  $('#files').change(function(evt){
    handleFileSelect(evt);
    // var count = $('.thumb').length;
  });  

  $('#list').on('click', '.remove_img_preview',function () {
    var a = $(this).data('value');
    arr = arr.filter(item => item !== a)
    $(this).parent('span').remove();
    $(this).parent('span').splice( 1, 1 );
    count--;
    var count_h = arr.length;
    var msg = ' '+count_h+' Image Selected';
    $('#num_of_img').text(msg)
  });
function add_nationality_price(id,shw_hid_adult,shw_hid_child,shw_hid_infant,shw_hid_senior){
 
       var trans_ary = [];
       var i =0
       $('.transfer_option_cls_'+id).each(function(e) {
       trans_ary [i] =  $(this).val();
        i++;
    });
     var options = '';
     var trans_count = '';
      if($('#with_transport').is(':checked')) {
      trans_count++; 
     if(jQuery.inArray("W", trans_ary) != -1) {
      } else {
        options += '<option value="W">Without Transfers</option>';
      }
         }
      if($('#sharing_transport').is(':checked')) {
        trans_count++;
        if(jQuery.inArray("S", trans_ary) != -1) {
      } else {
        options += '<option value="S">Sharing Transfer</option>';
      }
         }
      if($('#private_challenging').is(':checked')) {
        trans_count++;
        if(jQuery.inArray("P", trans_ary) != -1) {
      } else {
        options += '<option value="P">Private Transfers</option>';
      }
         }
  var rowCount = $('tr', $('#price_mngmnt_tbl_'+id).find('tbody')).length;
  if(trans_count>rowCount){
  var status = "style='display: none;";
  if(shw_hid_adult==0){shw_hid_adult='';}else{shw_hid_adult=status;} if(shw_hid_child==0){ shw_hid_child='';}else{shw_hid_child=status;}if(shw_hid_infant==0){shw_hid_infant='';}else{shw_hid_infant=status;    }if(shw_hid_senior==0){shw_hid_senior='';}else{shw_hid_senior=status;}
  // var rowCount = $('#price_mngmnt_tbl_'+idd+' tr').length;
  rowCount++;
  var nationality_price = '';
  nationality_price +=`<tr>
        <td><div class='form-group  transfer_div'>
                <div class='col-sm-12'>
                  <select name="transfer_option_`+id+`[`+rowCount+`]" id="transfer_option_`+rowCount+`_`+id+`"
                    class='form-control price_element transfer_option_cls_`+id+`'><option value="0">0</option></select>
                </div></div></td>`;
        if(shw_hid_adult==''){
        nationality_price +=`<td><div class='form-group  adult_price_div' `+shw_hid_adult+`>
                <div class='col-sm-12'>
                  <input type="text" name="p_price_`+id+`[`+rowCount+`]" id="p_price_`+rowCount+`_`+id+`"
                    data-rule-number="true" 
                    placeholder="Adult Price"
                    class='form-control price_element numeric valdtn_div' maxlength='10'
                    minlength='' >
                </div>
              </div></td>`;}
        if(shw_hid_child==''){
        nationality_price +=`<td><div class='form-group child_price_div' `+shw_hid_child+`>
                <div class='col-sm-12'>
                  <input type="text" name="child_price_`+id+`[`+rowCount+`]" id="child_price_`+rowCount+`_`+id+`"
                    data-rule-number="true" 
                    placeholder="Child Price"
                    class='form-control price_element numeric valdtn_div' maxlength='10'
                    minlength='1' >
                </div>
              </div></td>`;}
        if(shw_hid_infant==''){
        nationality_price +=`<td><div class='form-group infant_price_div' `+shw_hid_infant+`>
                <div class='col-sm-12'>
                  <input type="text" name="infant_price_`+id+`[`+rowCount+`]" id="infant_price_`+rowCount+`_`+id+`"
                    data-rule-number="true" 
                    placeholder="Infant Price"
                    class='form-control price_element numeric valdtn_div' maxlength='10'
                    minlength='' >
                </div>
              </div></td>`;}
        if(shw_hid_senior==''){
        nationality_price +=`<td><div class='form-group senior_price_div' `+shw_hid_senior+`>
                <div class='col-sm-12'>
                  <input type="text" name="senior_price_`+id+`[`+rowCount+`]" id="senior_price_`+rowCount+`_`+id+`"
                    data-rule-number="true" 
                    placeholder="Senior Citizen Price"
                    class='form-control price_element numeric valdtn_div' maxlength='10'
                    minlength='' >
                </div>
              </div></td>`;}
        nationality_price +=`<td><div class='col-sm-2 close_price_row controls padfive'>
                    <span class="btn btn-primary"><i class="far fa-times-circle"></i></span>
                  </div></td></tr>`;
      // $('#nationality_price_info').append(nationality_price);
       // $(this).closest('table').find('tr:last').after(nationality_price);
       
      //  $( ".transfer_option_cls" ).each(function() {
      //       var aa [0] =  $(this).val();
      //   i++;
      // });
       // var aa = $(".transfer_option_cls").val(); 
        $("#price_mngmnt_tbl_"+id).append(nationality_price); 
       
        $("#transfer_option_"+rowCount+"_"+id).html('');
         
        $("#transfer_option_"+rowCount+"_"+id).append(options); }
        else{
          alert('No Transfer Option to select');
        }
}
$(document).on('click','.close_price_row',function(){
      $(this).closest('tr').remove();
    });
function checkall(id){
  if($('#selectall'+id).is(':checked')) {
        $("#monday_"+id). prop("checked", true);
        $("#tuesday_"+id). prop("checked", true);
        $("#wednesday_"+id). prop("checked", true);
        $("#thursday_"+id). prop("checked", true);
        $("#friday_"+id). prop("checked", true);
        $("#saturday_"+id). prop("checked", true);
        $("#sunday_"+id). prop("checked", true);
      }else{
        $("#monday_"+id). prop("checked", false);
        $("#tuesday_"+id). prop("checked", false);
        $("#wednesday_"+id). prop("checked", false);
        $("#thursday_"+id). prop("checked", false);
        $("#friday_"+id). prop("checked", false);
        $("#saturday_"+id). prop("checked", false);
        $("#sunday_"+id). prop("checked", false);
      }
    }
    function uncheck(id){
      if(!$(this).is(':checked')) {
    $("#selectall"+id). prop("checked", false);
      }
      if($('#monday_'+id).is(':checked') && $('#tuesday_'+id).is(':checked') && $('#wednesday_'+id).is(':checked') && $('#thursday_'+id).is(':checked') && $('#friday_'+id).is(':checked') && $('#saturday_'+id).is(':checked') && $('#sunday_'+id).is(':checked')){
        $("#selectall"+id). prop("checked", true);
      }
    }
    </script>  
<!-- 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script> -->
 
   