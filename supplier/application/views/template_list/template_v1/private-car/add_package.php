<?php
$controller=$this->uri->segment(1);
$pack_data[0]['PricedEquip']=json_decode($pack_data[0]['PricedEquip'],true);
$pack_data[0]['OperationSchedules']=json_decode($pack_data[0]['OperationSchedules'],true);
$pack_data[0]['AdditionalInfo']=json_decode($pack_data[0]['AdditionalInfo'],true);
$pack_data[0]['AdditionalInfo']['OpeningHours']=json_decode($pack_data[0]['AdditionalInfo']['OpeningHours'],true);
$pack_data[0]['LocationDetails']=json_decode($pack_data[0]['LocationDetails'],true);
$pack_data[0]['CancellationPolicy']=json_decode($pack_data[0]['CancellationPolicy'],true);
//debug($pack_data);
$einstein=array();
for($i=0;$i<count($pack_data[0]['PricedEquip']);$i++)
{
    array_push($einstein,$pack_data[0]['PricedEquip'][$i]['Description']);
}
$larrypage=array();
for($i=0;$i<count($pack_data[0]['AdditionalInfo']['OpeningHours']);$i++)
{
    array_push($larrypage,$pack_data[0]['AdditionalInfo']['OpeningHours'][$i]['Day']);
}
//debug($larrypage);
// echo $controller;exit;
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/index.js'), 'defer' => 'defer');
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script
    src="https://www.alkhaleejtours.com/dev/extras/system/template_list/template_v3/javascript/page_resource/car_suggest.js?v=1629692933"
    defer="defer?v=1629692933" charset="UTF-8"></script>
<div id="Package" class="bodyContent col-md-12">
    <div class="panel panel-default">
        <!-- PANEL WRAP START -->
        <div class="panel-heading">
            <!-- PANEL HEAD START -->
            <div class="panel-title">
                <ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
                    <li role="presentation" class="active" id="add_package_li"><a href="#add_package"
                            aria-controls="home" role="tab" data-toggle="tab">
                            <?php
						if ($controller == 'activities_crs') {
							$title_tab = 'Add Activities';
							$title_type = 'Activities Type';
							$title_name = 'Activities Name';
							$title_display = 'Activities Display Image';
							$url = 'index.php/activities_crs/add_package_new';
						}
						else{
							$title_tab = 'Add Car';
							$title_type = 'Package';
							$title_name = 'CompanyShortName';
							$title_display = 'Vehicle Display Image';
							$url = 'index.php/privatecar/add_package_new';
						}
						if($pid!="")
						{
						    	$url = 'index.php/privatecar/update_package_new';
						}
						?>
                            <?php echo $title_tab;?>
                        </a></li>
                    <li role="presentation" class="" id="itenary_li"><a href="#itenary" aria-controls="home" role="tab"
                            data-toggle="">Business Hours
                        </a></li>
                    <li role="presentation" class="" id="gallery_li"><a href="#gallery" aria-controls="home" role="tab"
                            data-toggle="">Location Details</a></li>
                    <li role="presentation" class="" id="rate_card_li"><a href="#rate_card" aria-controls="home"
                            role="tab" data-toggle="">CancellationPolicy</a></li>
                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
                </ul>
            </div>
        </div>
        <!-- PANEL HEAD START -->
        <div class="panel-body">
            <!-- PANEL BODY START -->
            <form action="<?php echo base_url().$url ?>" method="post" enctype="multipart/form-data"
                class='form form-horizontal validate-form'>
                <div class="tab-content">
                    <!-- Add Activity Starts -->
                    <div role="tabpanel" class="tab-pane active" id="add_package">
                        <div class="col-md-12">

                            <input type="hidden" name="pid" value="<?php echo $pid; ?>"> <input type="hidden" name="deal" value="0">
                        
                            <div class='form-group'>
                                <label class='control-label col-sm-3'
                                    for='validation_name'><?php echo $title_type?> <span
                                        style="color:red">*</span></label>
                                <div class='col-sm-4 controls'>
                                    <select class='select2 form-control add_pckg_elements' data-rule-required='true'
                                        name='packagetype' id="packagetype" required>
                                        <option value=''>Select <?php echo $title_type?></option>
                                        <?php
																								for($l = 0; $l < count ( $package_type_data ); $l ++) {
																								    if($package_type_data[$l]->package_name==$pack_data[0]['RateComments'])
																								    {
																								        $sel="selected";
																								    }
																								    else
																								    {
																								         $sel="";
																								    }
																									?>
                                        <option value='<?php echo $package_type_data[$l]->package_id; ?>'  <?php echo $sel; ?>>
                                            <?php echo $package_type_data[$l]->package_name; ?> </option>
                                        <?php
																								}
																								?>
                                    </select> <span id="error-packagetype" style="color: #F00; display: none;">Please Select <?php echo $title_type?></span>
                                </div>
                            </div>
                       
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='adult'>PassengerQuantity<span
                                        style="color:red">*</span></label>

                                <div class='col-sm-4 controls'>
                                    <input type="number" name="PassengerQuantity" id="p_price" data-rule-number="true"
                                        data-rule-required='true' placeholder="PassengerQuantity"
                                        class='form-control add_pckg_elements numeric' value="<?php echo  $pack_data[0]['PassengerQuantity']; ?>" maxlength='10' minlength='3'
                                        required>
                                        <span id="error-PassengerQuantity" style="color: #F00; display: none;">Please Enter Passenger Quantity min 1</span>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='adult'>BaggageQuantity <span
                                        style="color:red">*</span></label>
                                <div class='col-sm-4 controls'>
                                    <input type="number" data-rule-required='true' name="BaggageQuantity" id="BaggageQuantity"
                                        placeholder="BaggageQuantity" value="<?php echo  $pack_data[0]['BaggageQuantity']; ?>" class='form-control numeric' maxlength='10'
                                        minlength='3' required>
                                        <span id="error-BaggageQuantity" style="color: #F00; display: none;">Please Enter Baggage Quantity (1-5)</span>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='adult'>DoorCount <span
                                        style="color:red">*</span></label>
                                <div class='col-sm-4 controls'>
                                    <input type="number" data-rule-required='true' name="DoorCount" id="DoorCount" placeholder="DoorCount"
                                        class='form-control numeric' value="<?php echo  $pack_data[0]['DoorCount']; ?>" maxlength='10' minlength='3' required>
                                        <span id="error-DoorCount" style="color: #F00; display: none;">Please Enter Door Count (1-5)</span>
                                </div>
                            </div>

                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='adult'>RentalPrice/Per Day <span
                                        style="color:red">*</span></label>
                                <div class='col-sm-4 controls'>
                                    <input type="number" data-rule-required='true' name="RentalPrice" id="RentalPrice" placeholder="RentalPrice"
                                        class='form-control numeric' value="<?php echo  $pack_data[0]['rentalprice']; ?>"  maxlength='10' minlength='3' required>
                                        <span id="error-RentalPrice" style="color: #F00; display: none;">Please Enter Right Rental Price</span>
                                </div>
                            </div>

                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='validation_current'>TransmissionType
                                 <span
                                        style="color:red">*</span></label>
                                <div class="col-sm-4 controls ">
                                    <select class='form-control' data-rule-required='true' name='TransmissionType'
                                        id="TransmissionType" required>

                                        <option value="">Select TransmissionType</option>

                                        <option value='Manual' <?php if($pack_data[0]['TransmissionType']=="Manual"){  echo "selected"; } ?>>Manual</option>
                                        <option value='Automatic' <?php if($pack_data[0]['Automatic']=="Manual"){  echo "selected"; } ?>>Automatic</option>

                                    </select>
                                    <span id="error-TransmissionType" style="color: #F00; display: none;">Please Select Transmission Type</span>
                                </div>

                            </div>
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='validation_current'>Mileage Allowance:
                                 <span
                                        style="color:red">*</span></label>
                                <div class="col-sm-4 controls ">
                                    <select class='form-control' data-rule-required='true' name=''
                                        id="MileageAllowance" required>

                                        <option value="">Select Mileage Allowance:</option>

                                        <option value='false' <?php if($pack_data[0]['unlimited']==0){  echo "selected"; } ?>>limited</option>
                                        <option value='true' <?php if($pack_data[0]['unlimited']==1){  echo "selected"; } ?>>unlimited</option>

                                    </select>
                                    <span id="error-MileageAllowance" style="color: #F00; display: none;">Please Select Mileage Allowance</span>
                                </div>

                            </div>
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='validation_country'>FuelType <span
                                        style="color:red">*</span></label>
                                <div class='col-sm-4 controls'>
                                    <select class='form-control' data-rule-required='true' name='FuelType' id="FuelType"
                                        required>
                                        <option value="">Select FuelType</option>
                                        <option value='Petrol' <?php if($pack_data[0]['FuelType']=='Petrol'){  echo "selected"; } ?>>Petrol</option>
                                        <option value='Diesel' <?php if($pack_data[0]['FuelType']=='Diesel'){  echo "selected"; } ?>>Diesel</option>
                                        <option value='Electric' <?php if($pack_data[0]['FuelType']=='Electric'){  echo "selected"; } ?>>Electric</option>
                                    </select>
                                    <span id="error-FuelType" style="color: #F00; display: none;">Please Select Fuel Type</span>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='control-label col-sm-3'
                                    for='validation_country'>Vehicle Category <span
                                        style="color:red">*</span></label>
                                <div class='col-sm-4 controls'>
                                    <select class='select2 form-control add_pckg_elements' data-rule-required='true'
                                        name='VehicleCategoryName' id="VehicleCategoryName" required>
                                        <option value=''>Select Vehicle Category</option>
                                        <?php
																								for($l = 0; $l < count ( $VehicleCategoryName ); $l ++) {
																								     if($VehicleCategoryName[$l]->category_name==$pack_data[0]['VehicleCategoryName'])
																								    {
                                                                                                       $sel="selected";
																								    }
																								    else
																								    {
																								        $sel="selected";
																								    }
																									?>
                                        <option value='<?php echo $VehicleCategoryName[$l]->category_id; ?>' <?php echo $sel; ?>>
                                            <?php echo $VehicleCategoryName[$l]->category_name; ?> </option>
                                        <?php
																								}
																								?>
                                    </select>
                                    <span id="error-VehicleCategoryName" style="color: #F00; display: none;">Please Select Vehicle Category</span>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='validation_country'>Extra Services <span
                                        style="color:red">*</span></label>
                                <div class='col-sm-4 controls'>
                                    <select class=' form-control ddssselect2 ' data-rule-required='true'
                                        name="extraservices[]" multiple name='disn' id="extraservices" required>
                                        <option  value=''>Select Extra Services</option>
                                        <?php
																								for($l = 0; $l < count ( $pricequip ); $l ++) {
																									?>
                                        <option value='<?php echo $pricequip[$l]->Equipid; ?>'  <?php if(in_array($pricequip[$l]->PolicyName,$einstein)){ echo "selected"; } ?>>
                                            <?php echo $pricequip[$l]->PolicyName; ?> </option>
                                        <?php
																								}
																								?>
                                    </select>
                                    <span id="error-extraservices" style="color: #F00; display: none;">Please Select Extra Services</span>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='validation_country'>Vendor <span
                                        style="color:red">*</span></label>
                                <div class='col-sm-4 controls'>
                                    <select class='select2 form-control add_pckg_elements' data-rule-required='true'
                                        name='Vendor' id="Vendor" required>
                                        <option value=''>Select Vendor</option>
                                        <?php
																								for($l = 0; $l < count ( $Vendor ); $l ++) {
																								    if($Vendor[$l]->name==$pack_data[0]['Vendor'])
																								    {
                                                                                                       $sel="selected";
																								    }
																								    else
																								    {
																								        $sel="selected";
																								    }
																									?>
                                        <option value='<?php echo $Vendor[$l]->vendorid; ?>' <?php echo $sel; ?>>
                                            <?php echo $Vendor[$l]->name; ?> </option>
                                        <?php
																								}
																								?>
                                    </select>
                                    <span id="error-Vendor" style="color: #F00; display: none;">Please Select Vendor</span>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='validation_country'>VehClassSizeName <span
                                        style="color:red">*</span></label>
                                <div class='col-sm-4 controls'>
                                    <select class='select2 form-control add_pckg_elements' data-rule-required='true'
                                        name='VehClassSizeName' id="VehClassSizeName" required>
                                        <option value=''>Select VehClassSizeName</option>
                                        <?php
																								for($l = 0; $l < count ( $VehClassSizeName ); $l ++) {
																								    if($VehClassSizeName[$l]->car_size==$pack_data[0]['VehClassSizeName'])
																								    {
                                                                                                       $sel="selected";
																								    }
																								    else
																								    {
																								        $sel="selected";
																								    }
																									?>
                                        <option value='<?php echo $VehClassSizeName[$l]->car_size; ?>' <?php echo $sel; ?>>
                                            <?php echo $VehClassSizeName[$l]->car_size; ?> </option>
                                        <?php
																								}
																								?>
                                    </select>
                                    <span id="error-VehClassSizeName" style="color: #F00; display: none;">Please Select Vehicle Size </span>
                                </div>
                            </div>



                            <div class='form-group'>
                                <label class='control-label col-sm-3'
                                    for='validation_company'><?php echo $title_display?> <span
                                        style="color:red">*</span></label>
                                <div class='col-sm-4 controls'>
                                    <?php 
                                      if($pid=="")
                                      {
                                          $req="required";
                                          $darwin="add_pckg_elementssadrer";
                                          $charles="photo";
                                      }
                                      else
                                      {
                                          $req="";
                                          $darwin="";
                                          $charles="";
                                          
                                      }
                                    
                                    ?>
                                    <input type="file" title='Image to add' class='<?php echo $darwin; ?>'
                                        data-rule-required='true' id='<?php echo $charles; ?>' name='photo' <?php echo $req; ?> > <span id="pacmimg"
                                        style="color: #F00; display: none">Please Upload privatecar Image in (.png)</span>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='validation_name'>Vehicle Name <span
                                        style="color:red">*</span></label>
                                <div class='col-sm-4 controls'>
                                    <input type="text" name="car_name" id="name" data-rule-minlength='2'
                                        data-rule-required='true' placeholder="Enter Car Name"
                                        class='form-control add_pckg_elements' value="<?php echo  $pack_data[0]['Name']; ?>" required>
                                        <span id="error-name" style="color: #F00; display: none;">Please Enter Vehicle Name </span>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='validation_rating'>Air Conditioner
                                 <span
                                        style="color:red">*</span></label>
                                <div class="col-sm-4 controls">
                                    <select class='form-control' data-rule-required='true' name='AirConditionInd'
                                        id="AirConditionInd" required>
                                        <option value="">Select Air Conditioner</option>
                                        <option value='true' <?php if($pack_data[0]['AirConditionInd']=='true'){  echo "selected"; } ?> >Yes</option>
                                        <option value='false' <?php if($pack_data[0]['FuelAirConditionIndType']=='false'){  echo "selected"; } ?>>No</option>

                                    </select>
                                    <span id="error-AirConditionInd" style="color: #F00; display: none;">Please Select Air Conditioner </span>
                                </div>
                            </div>
                            <div class='form-group'>
                                <div id="addCityButton" class="col-lg-2" style="display: none;">
                                    <input type="button" class="srchbutn comncolor" id="addCityInput" value="Add Peroid"
                                        style="padding: 3px 10px;">
                                </div>
                                <div id="removeCityButton" class="col-lg-2" style="display: none;">
                                    <input type="button" class="srchbutn comncolor" id="removeCityInput"
                                        value="Remove Peroid" style="padding: 3px 10px;">
                                </div>
                            </div>
                            <div class='' style='margin-bottom: 0'>
                                <div class='row'>
                                    <div class='col-sm-9 col-sm-offset-3'>
                                        <a class='btn btn-primary' id="add_package_button"> submit &
                                            continue</a>&nbsp;&nbsp; <a class='btn btn-primary'
                                            href="<?php echo base_url(); ?>privatecar/view_with_price">
                                            Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Add Activity Ends -->

                    <!-- Itenary Starts -->
                    <div role="tabpanel" class="tab-pane" id="itenary">
                        <div class="col-md-12">
                            <div class="duration_info_class clearfix" id="duration_info">
                                <div class='form-group clearfix'>
                                    <label class='control-label col-sm-3' for='validation_desc'>Days</label>
                                    <div class='col-sm-4 controls'>
                                        <label class="control-label">
                                            <input type="checkbox" name="days[]" value="SUN"  <?php if(in_array("SUN",$larrypage)){ echo "checked";  } ?>/>
                                            <span>SUN</span>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="days[]" value="MON" <?php if(in_array("MON",$larrypage)){ echo "checked";  } ?>/>
                                            <span>MON</span>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="days[]" value="TUE" <?php if(in_array("TUE",$larrypage)){ echo "checked";  } ?> />
                                            <span>TUES</span>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="days[]" value="WED" <?php if(in_array("WED",$larrypage)){ echo "checked";  } ?> />
                                            <span>WEDS</span>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="days[]" value="THU" <?php if(in_array("THU",$larrypage)){ echo "checked";  } ?> />
                                            <span>THUR</span>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="days[]" value="FRI"  <?php if(in_array("FRI",$larrypage)){ echo "checked";  } ?> />
                                            <span>Fri</span>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="days[]" value="SAT" <?php if(in_array("SAT",$larrypage)){ echo "checked";  } ?> />
                                            <span>SAT</span>
                                        </label>
                                    </div>
                                </div>
                                <div class='form-group clearfix'>
                                    <label class='control-label col-sm-3' for='validation_desc'>Start Time</label>
                                    <div class='col-sm-4 controls'>
                                        <select name="start_time" id="depature_time"
                                            class="normalsel padselct dep_t arimo">
                                            <option value="00:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="00:00"){ echo "selected"; } ?>>00:00</option>
                                            <option value="00:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="00:30"){ echo "selected"; } ?>>00:30</option>
                                            <option value="01:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="01:00"){ echo "selected"; } ?>>01:00</option>
                                            <option value="01:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="01:30"){ echo "selected"; } ?>>01:30</option>
                                            <option value="02:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="02:00"){ echo "selected"; } ?>>02:00</option>
                                            <option value="02:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="02:30"){ echo "selected"; } ?>>02:30</option>
                                            <option value="03:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="03:00"){ echo "selected"; } ?>>03:00</option>
                                            <option value="03:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="03:30"){ echo "selected"; } ?>>03:30</option>
                                            <option value="04:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="04:00"){ echo "selected"; } ?>>04:00</option>
                                            <option value="04:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="04:30"){ echo "selected"; } ?>>04:30</option>
                                            <option value="05:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="05:00"){ echo "selected"; } ?>>05:00</option>
                                            <option value="05:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="05:30"){ echo "selected"; } ?>>05:30</option>
                                            <option value="06:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="06:00"){ echo "selected"; } ?>>06:00</option>
                                            <option value="06:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="06:30"){ echo "selected"; } ?>>06:30</option>
                                            <option value="07:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="07:00"){ echo "selected"; } ?>>07:00</option>
                                            <option value="07:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="07:30"){ echo "selected"; } ?>>07:30</option>
                                            <option value="08:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="08:00"){ echo "selected"; } ?>>08:00</option>
                                            <option value="08:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="08:30"){ echo "selected"; } ?>>08:30</option>
                                            <option value="09:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="09:00"){ echo "selected"; } ?>>09:00</option>
                                            <option value="09:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="09:30"){ echo "selected"; } ?>>09:30</option>
                                            <option value="10:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="10:00"){ echo "selected"; } ?>>10:00</option>
                                            <option value="10:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="10:30"){ echo "selected"; } ?>>10:30</option>
                                            <option value="11:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="11:00"){ echo "selected"; } ?>>11:00</option>
                                            <option value="11:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="11:30"){ echo "selected"; } ?>>11:30</option>
                                            <option value="12:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="12:00"){ echo "selected"; } ?>>12:00</option>
                                            <option value="12:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="12:30"){ echo "selected"; } ?>>12:30</option>
                                            <option value="13:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="13:00"){ echo "selected"; } ?>>13:00</option>
                                            <option value="13:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="13:30"){ echo "selected"; } ?>>13:30</option>
                                            <option value="14:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="14:00"){ echo "selected"; } ?>>14:00</option>
                                            <option value="14:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="14:30"){ echo "selected"; } ?>>14:30</option>
                                            <option value="15:00"<?php if($pack_data[0]['OperationSchedules']['Start']=="15:00"){ echo "selected"; } ?>>15:00</option>
                                            <option value="15:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="15:30"){ echo "selected"; } ?>>15:30</option>
                                            <option value="16:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="16:00"){ echo "selected"; } ?>>16:00</option>
                                            <option value="16:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="16:30"){ echo "selected"; } ?>>16:30</option>
                                            <option value="17:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="17:00"){ echo "selected"; } ?>>17:00</option>
                                            <option value="17:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="17:30"){ echo "selected"; } ?>>17:30</option>
                                            <option value="18:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="18:00"){ echo "selected"; } ?>>18:00</option>
                                            <option value="18:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="18:30"){ echo "selected"; } ?>>18:30</option>
                                            <option value="19:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="19:00"){ echo "selected"; } ?>>19:00</option>
                                            <option value="19:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="19:30"){ echo "selected"; } ?>>19:30</option>
                                            <option value="20:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="20:00"){ echo "selected"; } ?>>20:00</option>
                                            <option value="20:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="20:30"){ echo "selected"; } ?>>20:30</option>
                                            <option value="21:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="21:00"){ echo "selected"; } ?>>21:00</option>
                                            <option value="21:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="21:30"){ echo "selected"; } ?>>21:30</option>
                                            <option value="22:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="22:00"){ echo "selected"; } ?>>22:00</option>
                                            <option value="22:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="22:30"){ echo "selected"; } ?>>22:30</option>
                                            <option value="23:00" <?php if($pack_data[0]['OperationSchedules']['Start']=="23:00"){ echo "selected"; } ?>>23:00</option>
                                            <option value="23:30" <?php if($pack_data[0]['OperationSchedules']['Start']=="23:30"){ echo "selected"; } ?>>23:30</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='form-group clearfix'>
                                    <label class='control-label col-sm-3' for='validation_desc'>End Time</label>
                                    <div class='col-sm-4 controls'>
                                        <select name="end_time" id="depature_time"
                                            class="normalsel padselct dep_t arimo">
                                            <option value="00:00" <?php if($pack_data[0]['OperationSchedules']['End']=="00:00"){ echo "selected"; } ?>>00:00</option>
                                            <option value="00:30" <?php if($pack_data[0]['OperationSchedules']['End']=="00:30"){ echo "selected"; } ?>>00:30</option>
                                            <option value="01:00" <?php if($pack_data[0]['OperationSchedules']['End']=="01:00"){ echo "selected"; } ?>>01:00</option>
                                            <option value="01:30" <?php if($pack_data[0]['OperationSchedules']['End']=="01:30"){ echo "selected"; } ?>>01:30</option>
                                            <option value="02:00" <?php if($pack_data[0]['OperationSchedules']['End']=="02:00"){ echo "selected"; } ?>>02:00</option>
                                            <option value="02:30" <?php if($pack_data[0]['OperationSchedules']['End']=="02:30"){ echo "selected"; } ?>>02:30</option>
                                            <option value="03:00" <?php if($pack_data[0]['OperationSchedules']['End']=="03:00"){ echo "selected"; } ?>>03:00</option>
                                            <option value="03:30" <?php if($pack_data[0]['OperationSchedules']['End']=="03:30"){ echo "selected"; } ?>>03:30</option>
                                            <option value="04:00" <?php if($pack_data[0]['OperationSchedules']['End']=="04:00"){ echo "selected"; } ?>>04:00</option>
                                            <option value="04:30" <?php if($pack_data[0]['OperationSchedules']['End']=="04:30"){ echo "selected"; } ?>>04:30</option>
                                            <option value="05:00" <?php if($pack_data[0]['OperationSchedules']['End']=="05:00"){ echo "selected"; } ?>>05:00</option>
                                            <option value="05:30" <?php if($pack_data[0]['OperationSchedules']['End']=="05:30"){ echo "selected"; } ?>>05:30</option>
                                            <option value="06:00" <?php if($pack_data[0]['OperationSchedules']['End']=="06:00"){ echo "selected"; } ?>>06:00</option>
                                            <option value="06:30" <?php if($pack_data[0]['OperationSchedules']['End']=="06:30"){ echo "selected"; } ?>>06:30</option>
                                            <option value="07:00" <?php if($pack_data[0]['OperationSchedules']['End']=="07:00"){ echo "selected"; } ?>>07:00</option>
                                            <option value="07:30" <?php if($pack_data[0]['OperationSchedules']['End']=="07:30"){ echo "selected"; } ?>>07:30</option>
                                            <option value="08:00" <?php if($pack_data[0]['OperationSchedules']['End']=="08:00"){ echo "selected"; } ?>>08:00</option>
                                            <option value="08:30" <?php if($pack_data[0]['OperationSchedules']['End']=="08:30"){ echo "selected"; } ?>>08:30</option>
                                            <option value="09:00" <?php if($pack_data[0]['OperationSchedules']['End']=="09:00"){ echo "selected"; } ?>>09:00</option>
                                            <option value="09:30" <?php if($pack_data[0]['OperationSchedules']['End']=="09:30"){ echo "selected"; } ?>>09:30</option>
                                            <option value="10:00" <?php if($pack_data[0]['OperationSchedules']['End']=="10:00"){ echo "selected"; } ?>>10:00</option>
                                            <option value="10:30" <?php if($pack_data[0]['OperationSchedules']['End']=="10:30"){ echo "selected"; } ?>>10:30</option>
                                            <option value="11:00" <?php if($pack_data[0]['OperationSchedules']['End']=="11:00"){ echo "selected"; } ?>>11:00</option>
                                            <option value="11:30" <?php if($pack_data[0]['OperationSchedules']['End']=="11:30"){ echo "selected"; } ?>>11:30</option>
                                            <option value="12:00" <?php if($pack_data[0]['OperationSchedules']['End']=="12:30"){ echo "selected"; } ?>>12:00</option>
                                            <option value="12:30" <?php if($pack_data[0]['OperationSchedules']['End']=="12:3"){ echo "selected"; } ?>>12:30</option>
                                            <option value="13:00" <?php if($pack_data[0]['OperationSchedules']['End']=="13:00"){ echo "selected"; } ?>>13:00</option>
                                            <option value="13:30" <?php if($pack_data[0]['OperationSchedules']['End']=="13:30"){ echo "selected"; } ?>>13:30</option>
                                            <option value="14:00" <?php if($pack_data[0]['OperationSchedules']['End']=="14:0"){ echo "selected"; } ?>>14:00</option>
                                            <option value="14:30" <?php if($pack_data[0]['OperationSchedules']['End']=="14:30"){ echo "selected"; } ?>>14:30</option>
                                            <option value="15:00" <?php if($pack_data[0]['OperationSchedules']['End']=="15:00"){ echo "selected"; } ?>>15:00</option>
                                            <option value="15:30" <?php if($pack_data[0]['OperationSchedules']['End']=="15:30"){ echo "selected"; } ?>>15:30</option>
                                            <option value="16:00" <?php if($pack_data[0]['OperationSchedules']['End']=="16:00"){ echo "selected"; } ?>>16:00</option>
                                            <option value="16:30" <?php if($pack_data[0]['OperationSchedules']['End']=="16:30"){ echo "selected"; } ?>>16:30</option>
                                            <option value="17:00" <?php if($pack_data[0]['OperationSchedules']['End']=="17:00"){ echo "selected"; } ?>>17:00</option>
                                            <option value="17:30" <?php if($pack_data[0]['OperationSchedules']['End']=="17:30"){ echo "selected"; } ?>>17:30</option>
                                            <option value="18:00" <?php if($pack_data[0]['OperationSchedules']['End']=="18:00"){ echo "selected"; } ?>>18:00</option>
                                            <option value="18:30" <?php if($pack_data[0]['OperationSchedules']['End']=="18:30"){ echo "selected"; } ?>>18:30</option>
                                            <option value="19:00" <?php if($pack_data[0]['OperationSchedules']['End']=="19:00"){ echo "selected"; } ?>>19:00</option>
                                            <option value="19:30" <?php if($pack_data[0]['OperationSchedules']['End']=="20:00"){ echo "selected"; } ?>>20:00</option>
                                            <option value="20:00" <?php if($pack_data[0]['OperationSchedules']['End']=="20:00"){ echo "selected"; } ?>>20:00</option>
                                            <option value="20:30" <?php if($pack_data[0]['OperationSchedules']['End']=="20:30"){ echo "selected"; } ?>>20:30</option>
                                            <option value="21:00" <?php if($pack_data[0]['OperationSchedules']['End']=="21:00"){ echo "selected"; } ?>>21:00</option>
                                            <option value="21:30" <?php if($pack_data[0]['OperationSchedules']['End']=="21:30"){ echo "selected"; } ?>>21:30</option>
                                            <option value="22:00" <?php if($pack_data[0]['OperationSchedules']['End']=="22:00"){ echo "selected"; } ?>>22:00</option>
                                            <option value="22:30" <?php if($pack_data[0]['OperationSchedules']['End']=="22:30"){ echo "selected"; } ?>>22:30</option>
                                            <option value="23:00" <?php if($pack_data[0]['OperationSchedules']['End']=="23:00"){ echo "selected"; } ?>>23:00</option>
                                            <option value="23:30" <?php if($pack_data[0]['OperationSchedules']['End']=="23:30"){ echo "selected"; } ?>>23:30</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class='form-actions' style='margin-bottom: 0'>
                                <div class='row'>
                                    <div class='col-sm-9 col-sm-offset-3'>
                                        <a class='btn btn-primary' id="itenary_button">submit &
                                            continue</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Itenary Ends -->

                    <!-- Photo Gallery Starts -->
                    <div role="tabpanel" class="tab-pane" id="gallery">
                        <div class="col-md-12">
                            <div class='form-group '>
                                <div class='form-group'>
                                    <label class='control-label col-sm-3' for='validation_country'>Country</label>
                                    <div class='col-sm-4 controls'>
                                        <select class='select2 form-control gallery_elements' data-rule-required='true'
                                            name='country' id="country">
                                            <!--  <input type="text" name="country" id="country" data-rule-required='true' class='form-control'>  -->
                                            <option value="">Select Location</option>
                                            <?php foreach ($country as $coun) {?>
                                            <option value='<?php echo $coun->country_id; ?>'  <?php if($pack_data[0]['country']=="$coun->name"){ echo "selected"; } ?>><?php echo $coun->name; ?>
                                            </option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                              
                                <div class='form-group'>
                                    <?php
                                        if($pid=="")
                                        {
                                            $reft="required";
                                        }else
                                        {
                                            $reft="";
                                        }
                                    ?>
                                    <label class='control-label col-sm-3' for='validation_current'>City</label>
                                    <div class='col-sm-4 controls'>
                                        <select class='select2 form-control gallery_elements' data-rule-required='true' name='cityname_old'
                                            id="cityname" multiple="multiple" <?php echo $reft; ?>>
                                            <option value=''>Select city</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class='form-group'>
                                    <label class='control-label col-sm-3' for='validation_current'>City</label>
                                    <div class='col-sm-4 controls'>
                                        <input type="text" class="normal AlphabetsOnly gallery_elements" data-rule-required='true' id="textbox" name="cityname"
                                            style="width:450px;" value="<?php echo $pack_data[0]['city'] ?>" required></td>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group '>
                                <label class='control-label col-sm-3' for='validation_company'>Pick Up Point</label>
                                <div class='col-sm-3 controls'>
                                    <input type="text" value="<?php echo $pack_data[0]['LocationDetails']['PickUpLocation'][0]['StreetNmbr'] ?>"
                                        placeholder="To, Airport, City" id="car_to" name="car_to"
                                        class="b-r-0 departcar form-control hotelin normalinput ui-autocomplete-input"
                                        aria-required="true"  autocomplete="off">
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='validation_current'>Pick Up point PostalCode
                                </label>
                                <div class='col-sm-4 controls'>
                                    <input type="text" class="normal AlphabetsOnly" name="PickPostalCode[]"
                                        style="width:450px;" value="<?php echo $pack_data[0]['LocationDetails']['PickUpLocation'][0]['PostalCode'] ?>" required></td>
                                </div>
                            </div>
                            <div class="dropadd">
                                <?php 
                                if($pid!="")
                                {
                                 for($i=0;$i<count($pack_data[0]['LocationDetails']['DropLocation']);$i++)
                                 {
                                  ?>
    							<div class='form-group'>
                                    <label class='control-label col-sm-3' for='validation_company'>Drop Up
                                                Point
                                    </label>
                                    <div class='col-sm-4 controls'>
    								<input type="text"value="<?php echo $pack_data[0]['LocationDetails']['DropLocation'][0]['StreetNmbr'] ?>"
                                                    placeholder="To, Airport, City" id="car_to" name="drop_to[]"
                                                    class="b-r-0 fromcar form-control hotelin normalinput ui-autocomplete-input"
                                                    aria-required="true"  autocomplete="off">
                                    </div>
                                </div>
    							<div class='form-group'>
                                    <label class='control-label col-sm-3' for='validation_company'>Drop Up point
                                                PostalCode
                                    </label>
                                    <div class='col-sm-4 controls'>
    								<input type="text" class="normal AlphabetsOnly" name="DropPostalCode[]"
                                                    style="width:450px;"  value="<?php echo $pack_data[0]['LocationDetails']['DropLocation'][0]['PostalCode'] ?>" required>
                                    <?php
                                                       if($i>0)
                                                       {
                                                           ?>
                                                           <button class="btn btn-danger dremove">Remove</button>
                                                           <?php
                                                       }
                                                    ?>
                                    </div>
                                </div>
                                <?php
                                }
                                }
                                else
                                {
                                ?>
                                	<div class='form-group'>
                                    <label class='control-label col-sm-3' for='validation_company'>Drop Up
                                                Point
                                    </label>
                                    <div class='col-sm-4 controls'>
    								<input type="text" value="<?php echo @$car_search_params['car_to'] ?>"
                                                    placeholder="To, Airport, City" id="car_to" name="drop_to[]"
                                                    class="b-r-0 fromcar form-control hotelin normalinput ui-autocomplete-input"
                                                    aria-required="true" value="<?php echo $pack_data[0]['LocationDetails']['PickUpLocation'][0]['StreetNmbr'] ?>" autocomplete="off">
                                    </div>
                                </div>
    							<div class='form-group'>
                                    <label class='control-label col-sm-3' for='validation_company'>Drop Up point
                                                PostalCode
                                    </label>
                                    <div class='col-sm-4 controls'>
    								<input type="text" class="normal AlphabetsOnly" name="DropPostalCode[]"
                                                    style="width:450px;" required>
                                                    
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                            
                            </div>



                        </div>
                        <div class="col-md-12">
                            <div class='form-group'>
                               
                                <div class='col-sm-3 controls'>
                                    <button class='btn btn-primary DAdd'>Add Drop Up Point</button>
                                </div>
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
                    <!-- Photo Gallery Ends -->

                    <!-- Rate card Starts -->
                    <div role="tabpanel" class="tab-pane" id="rate_card">
                        <div class="col-md-12 policyadd">
                            <?php 
                                if($pid!="")
                                {
                                 for($i=0;$i<count($pack_data[0]['CancellationPolicy']);$i++)
                                 {
                                  ?>
                            <div class='form-group clearfix '>
                                <label class='control-label col-sm-3' for='validation_includes'>Cancellation
                                    Policy</label>
                                <div class='col-sm-4 controls'>
                                    <label class='control-label col-sm-3' for='validation_includes'>Start Date</label>
                                    <input type="text" name="cancel_start_date[]" class='form-control cancel_start_date'
                                        required value="<?php echo $pack_data[0]['CancellationPolicy'][$i]['FromDate']?>" placeholder="Choose Date" readonly>
                                    <label class='control-label col-sm-3' for='validation_includes'>End Date</label>
                                    <input type="text" name="cancel_end_date[]" data-rule-required='true'
                                        class='form-control cancel_start_date' required 
                                        placeholder="Choose Date" value="<?php echo $pack_data[0]['CancellationPolicy'][$i]['ToDate']?>" data-rule-required='true' readonly>
                                    <label class='control-label col-sm-3' for='validation_includes'>Amount</label>
                                    <input type="text" name="camount[]" data-rule-required='true' class='form-control '
                                        required  placeholder="Amount" value="<?php echo $pack_data[0]['CancellationPolicy'][$i]['Amount']?>" data-rule-required='true'>
                                </div>
                                <?php
                                                       if($i>0)
                                                       {
                                                           ?>
                                                           <div class="col-sm-3 controls"><button class="btn btn-danger remove">Remove</button></div>
                                                           <?php
                                                       }
                                                    ?>
                                <div class='col-sm-3 controls'>
                                    <button class='btn btn-primary Add'>Add Cancellation Policy</button>
                                </div>
                            </div>

                             <?php
                                }
                                }
                                else
                                {
                                ?>
                                <div class='form-group clearfix '>
                                <label class='control-label col-sm-3' for='validation_includes'>Cancellation
                                    Policy</label>
                                <div class='col-sm-4 controls'>
                                    <label class='control-label col-sm-3' for='validation_includes'>Start Date</label>
                                    <input type="text" name="cancel_start_date[]" class='form-control cancel_start_date'
                                        required value="" placeholder="Choose Date" readonly>
                                    <label class='control-label col-sm-3' for='validation_includes'>End Date</label>
                                    <input type="text" name="cancel_end_date[]" data-rule-required='true'
                                        class='form-control cancel_start_date' required value=""
                                        placeholder="Choose Date" data-rule-required='true' readonly>
                                    <label class='control-label col-sm-3' for='validation_includes'>Amount</label>
                                    <input type="text" name="camount[]" data-rule-required='true' class='form-control '
                                        required value="" placeholder="Amount" data-rule-required='true'>
                                </div>
                                <div class='col-sm-3 controls'>
                                    <button class='btn btn-primary Add'>Add Cancellation Policy</button>
                                </div>
                            </div>
                            <?php
                                }
                                ?>



                        </div>
                        <div class='form-actions' style='margin-bottom: 0'>
                            <div class='row'>
                                <div class='col-sm-9 col-sm-offset-3'>
                                    <button class='btn btn-primary' type='submit'>submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Rate card Ends -->
</div>
                </div>
            </form>
        </div>
        <!-- PANEL BODY END -->
    </div>
    <!-- PANEL WRAP END -->
</div>

<script type="text/javascript">

$(document).on('change blur paste','#photo,#packagetype,#tour_start_date,#tour_expire_date,#p_price,#BaggageQuantity,#DoorCount,#RentalPrice,#TransmissionType,#MileageAllowance,#FuelType,#VehicleCategoryName,#extraservices,#Vendor,#VehClassSizeName,#name,#AirConditionInd', function() {
    if($(this).attr('id')=='photo'){
        var ext = $('#photo').val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['png']) == -1) {
            $(this).val("");
            $(this).next().show();
        }else{
            $(this).next().hide();
            $(this).closest(".form-group").removeClass("has-error");
        }
    }else if($(this).attr('id')=='p_price' || $(this).attr('id')=='RentalPrice'){
        if($(this).val() <= 0){
            $(this).val("");
            $(this).next().show();
        }else{
            $(this).next().hide();
            $(this).closest(".form-group").removeClass("has-error");
        }
    }
    else if($(this).attr('id')=='BaggageQuantity' || $(this).attr('id')=='DoorCount'){
        if($(this).val() > 5 || $(this).val() <= 0){
            $(this).val("");
            $(this).next().show();
        }else{
            $(this).next().hide();
            $(this).closest(".form-group").removeClass("has-error");
        }
    }
    else{
        
        if($(this).val()==""){
            $(this).next().show();
        }else{
            $(this).next().hide();
            $(this).closest(".form-group").removeClass("has-error");
        }
    }
});
    
$(document).ready(function() {
    
    $("#photo,#packagetype,#tour_start_date,#tour_expire_date,#p_price,#BaggageQuantity,#DoorCount,#RentalPrice,#TransmissionType,#MileageAllowance,#FuelType,#VehicleCategoryName,#extraservices,#Vendor,#VehClassSizeName,#name,#AirConditionInd").each(function(){
        if(!$(this).hasClass("add_pckg_elements")){
            $(this).addClass("add_pckg_elements");
        }
    });
    
    $('#country').on('change', function() {
        $.ajax({
            url: '<?php echo base_url(); ?>privatecar/get_crs_city/' + $(this).val(),
            dataType: 'json',
            success: function(json) {
                $('select[name=\'cityname_old\']').html(json.result);
                   $("#cityname").val("<?php echo $pack_data[0]['city'];  ?>");
            }
        });
    });
    $("#cityname").on('click', function() {
        var dropdownVal = $(this).val();

        $("#textbox").val(dropdownVal);

    });
});

function show_duration_info(duration) {
    if (duration == '') {
        duration = 0;
    }
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("duration_info").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", "itinerary_loop/" + duration, true);
    xmlhttp.send();
}
$("#addanother").click(function() {
    var addin =
        '<input type="text" name="ancountry" value="" placeholder="country" class="ma_pro_txt" style="margin:2px;"/><input type="text" name="anstate" placeholder="state" value="" class="ma_pro_txt" style="margin:2px;"/><input type="text" name="ancity" placeholder="city" value="" class="ma_pro_txt" style="margin:2px;"/><div onclick="removeinput()" style="font-weight:bold;cursor:pointer;">Remove</div><br/>';
    $("#addmorefields").html(addin);
});

function removeinput() {
    $("#addmorefields").html('');
}

function activate(that) {
    window.location.href = that;
}
var a;
$(document).ready(function() {

    $('#addCityInput').click(function() {
        var cityNo = parseInt($('#multiCityNo').val());
        //alert(cityNo);
        var duration = $('#duration').val();
        var cityNo = cityNo + 1;
        var cit = cityNo - 1;
        var allCity = '';
        var i = cityNo;
        var s = i - 1;

        allCity += "<div id='bothCityInputs" + i +
            "'><div class='form-group'><label class='control-label col-sm-2' for='validation_company'>From Date</label><div class='input-group col-sm-3' ><input class='fromd datepicker2 b2b-txtbox form-control' placeholder='MM/DD/YYYY' id='deptDate" +
            i + "'  myid='" + i +
            "' name='sd[]'' type='text'><span class='input-group-addon'><i class='icon-calendar'></i></span></div><label class='control-label col-sm-2' for='validation_name'>To Date</label><div class='input-group col-sm-3' ><input class='form-control b2b-txtbox' placeholder='MM/DD/YYYY' id='too" +
            i +
            "' name='ed[]'' type='text' readonly><span class='input-group-addon'><i class='icon-calendar'></i></span><span id='dorigin_error7' style='color:#F00;'></span><span id='dorigin_error' style='color:#F00;'></span><br></div><br></div>";

        allCity +=
            "<div class='form-group clearfix'><label class='control-label col-sm-2' for='adult'>Adult Price</label><div class='input-group col-sm-3' ><input type='text' name='adult[]' id='adult" +
            i + "'  myid='" + i +
            "' data-rule-number='true' data-rule-required='true' class='form-control'><span class='input-group-addon'><i class='icon-usd'></i></span></div><label class='control-label col-sm-2' for='child'>Child Price</label><div class='input-group col-sm-3' ><input type='text' name='child[]' id='child" +
            i + "'  myid='" + i +
            "' data-rule-number='true' data-rule-required='true' class='form-control'><span class='input-group-addon'><i class='icon-usd'></i></span></div></div><hr>";
        allCity += '<script>var d1 = $("#deptDate' + cit + '").datepicker("getDate");' +
            //'var dd = d1.getDate() + 1;var mm = d1.getMonth() + 1;var yy = d1.getFullYear();'+
            'd1.setDate(d1.getDate() + parseInt(1));' +
            'var dd = d1.getDate();var mm = d1.getMonth() + 1;var yy = d1.getFullYear();' +
            'var to_date = (mm) + "/" + dd + "/" + yy;' +
            //'var to_date = (mm) + "/" + dd + "/" + yy;'+
            'alert(to_date);' +
            'var duration = $("#duration").val();' +
            '$("#deptDate' + i + '").datepicker({' +
            'dateFormat: "mm/dd/yy",' +
            'minDate: to_date,' +
            'onSelect: function(dateStr) {' +
            'var d1 = $(this).datepicker("getDate");' +


            'd1.setDate(d1.getDate() + parseInt(duration));' +

            'var dd = d1.getDate();var mm = d1.getMonth() + 1;var yy = d1.getFullYear();' +
            'var to_date = (mm) + "/" + dd + "/" + yy;' +
            '$("#too' + i + '").val(to_date);' +
            '}' +
            '});' +
            '<\/script>' +
            '</div>';
        //$("#addMultiCity").append("<label class='control-label col-sm-2' for='validation_company'>From</label><div class='col-sm-3 controls'><input name='sd' id='' type='text' class='datepicker2 b2b-txtbox form-control'     />   <span id='dorigin_error6' style='color:#F00;'></span><br></div><label class='control-label col-sm-3' for='validation_name'>To</label><div class='col-sm-3 controls'><input name='ed' id='' type='text' class='datepicker3 b2b-txtbox form-control'   />  <span id='dorigin_error7' style='color:#F00;'></span><span id='dorigin_error' style='color:#F00;'></span></div>");

        $("#addMultiCity").append(allCity);
        if (cityNo > 1) {
            $("#removeCityButton").show();
        }
        $('#multiCityNo').val(cityNo);
    });
    $('#removeCityInput').click(function() {
        var cityNo = parseInt($('#multiCityNo').val());

        var allCity = '';
        if (cityNo > 1) {
            $("#bothCityInputs" + cityNo).remove();
            var cityNo = cityNo - 1;
            if (cityNo > 1) {
                $("#removeCityButton").show();
            }
        } else {
            $("#removeCityButton").hide();
        }
        $('#multiCityNo').val(cityNo);
    });

    $('#add_package_button').click(function() {
        var error_free = true;
        console.log("sdf");
        var item=0;
        $(".add_pckg_elements").each(function() {
            if ($(this).val() == '') {
                error_free = false;
                $(this).next().show();
                $(this).closest(".form-group").addClass("has-error");
                item ++;
            }
        });
        if (item==0 && error_free) {
            $("#add_package_li").removeClass("active");
            $("#add_package").removeClass("active");
            $("#itenary_li").addClass("active");
            $("#itenary").addClass("active");
            $("html, body").animate({
                scrollTop: 0
            }, "slow");
            return false;
        }
        console.log(item);
    });
    $('#itenary_button').click(function() {
        var error_free = true;
        $(".itenary_elements").each(function() {
            if ($(this).val() == '') {
                error_free = false;
                $(this).closest(".form-group").addClass("has-error");
            }
        });
        if (error_free) {
            $("#itenary_li").removeClass("active");
            $("#itenary").removeClass("active");
            $("#gallery_li").addClass("active");
            $("#gallery").addClass("active");
            $("html, body").animate({
                scrollTop: 0
            }, "slow");
            return false;
        }
    });
    $('#gallery_button').click(function() {
        var error_free = true;
        $(".gallery_elements").each(function() {
            if ($(this).val() == '') {
                error_free = false;
                $(this).closest(".form-group").addClass("has-error");
            }
        });
        if (error_free) {
            $("#gallery_li").removeClass("active");
            $("#gallery").removeClass("active");
            $("#rate_card_li").addClass("active");
            $("#rate_card").addClass("active");
            $("html, body").animate({
                scrollTop: 0
            }, "slow");
            return false;
        }
    });
});
$(document).ready(function() {

    $(document).on("change", ".fromd", function() {
        current_date = $(this).val();

        current_id = $(this).attr('id');
        // alert(current_id);
        $(".fromd").each(function() {
            previous_dates = $(this).val();
            //alert(previous_dates);
            currenr_id = $(this).attr('id');


            if (current_date == previous_dates && current_id != currenr_id) {
                myid = $("input[type='text']#" + current_id).attr('myid');
                alert("Already Same Date Selected");
                $("#" + current_id).val(" ");
                // alert(myid);
                $("#to" + myid).val(" ");
                $("#too" + myid).val(" ");
            }
        });
    });
});

$('#validation_country').on('change', function() {
    var country = $(this).val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>supplier/get_cities/" + country,
        data: {
            country: country
        },
        success: function(wcity) {
            $('#city').html(wcity);
        }
    });
});
$(document).ready(function() {
    $(".Add").on("click", function() {

        $(".policyadd").append(
            "<div class='form-group clearfix '><label class='control-label col-sm-3' for='validation_includes'>Cancellation Policy</label><div class='col-sm-4 controls'><label class='control-label col-sm-3' for='validation_includes'>Start Date</label><input type='text' name='cancel_start_date[]' class='form-control cancel_start_date'  required value='' placeholder='Choose Date'   readonly> <label class='control-label col-sm-3' for='validation_includes'>End Date</label><input type='text' name='cancel_end_date[]' class='form-control cancel_start_date'  required value='' placeholder='Choose Date'   readonly>	<label class='control-label col-sm-3' for='validation_includes'>Amount</label><input type='text' name='camount[]' class='form-control ' required  placeholder='Amount'   > </div><div class='col-sm-3 controls'><button class='btn btn-danger remove'>Remove</button></div></div>"
            );
    });
    $(".DAdd").on("click", function() {

        $(".dropadd").append("<div class='form-group'><label class='control-label col-sm-3' for='validation_company'>Drop Up Point </label><div class='col-sm-4 controls'><input type='text'  placeholder='To, Airport, City' id='car_to' name='drop_to[]' class='b-r-0 fromcar form-control hotelin normalinput ui-autocomplete-input' aria-required='true' autocomplete='off'></div></div><div class='form-group'><label class='control-label col-sm-3' for='validation_company'>Drop Up point PostalCode </label><div class='col-sm-4 controls'><input type='text' class='normal AlphabetsOnly' name='DropPostalCode[]' style='width:450px;' required><button class='btn btn-danger dremove'>Remove</button></div></div>");
    });
    $(document).on("click", ".remove", function() {
        $(this).parent().parent().remove();

    });
    
    $(document).on("mouseover", ".fromcar", function() {
      
 var cache = {};
    var car_from = $('#car_from').val();
    var car_to = $('#car_to').val();
    $(".fromcar, .departcar").catcomplete({
        // open: function (event, ui) {
        //     $('.ui-autocomplete').off('menufocus hover mouseover mouseenter');
        // },
        source: function(request, response) {
            var term = request.term;
            if (term in cache) {
                response(cache[term]);
                return
            } else {
                $.getJSON(app_base_url + "index.php/ajax/get_airport_city_list", request, function(data, status, xhr) {
                    if ($.isEmptyObject(data) == true && $.isEmptyObject(cache[""]) == false) {
                        data = cache[""]
                    } else {
                        cache[term] = data;
                        response(cache[term])
                    }
                })
            }
        },
        minLength: 0,
        autoFocus: true,
        select: function(event, ui) {
           
            var label = ui.item.label;
            var category = ui.item.category;
            if (this.id == 'car_to') {
                to_airport = ui.item.value;
            } else if (this.id == 'car_from') {
                from_airport = ui.item.value;
               
                $('#car_from_loc_id').val(ui.item.id);
                $('#car_to').val(from_airport);
                $('#car_to_loc_id').val(ui.item.id);
                $('#car_from_loc_code').val(ui.item.airport_code);
                $('#car_to_loc_code').val(ui.item.airport_code);
            }
            $(this).siblings('.loc_id_holder').val(ui.item.id);
            $(this).siblings('.loc_code_holder').val(ui.item.airport_code);
            // auto_focus_input(this.id)
            // auto_focus_input(this.car_type)
        },
        change: function(ev, ui) {
            if (!ui.item) {
                $(this).val("")
            }
        }
    }).catcomplete("instance")._renderItem = function(ul, item) {
        var auto_suggest_value = highlight_search_text(this.term.trim(), item.value, item.label);
        var top = 'Top Searches';
        return $("<li class='custom-auto-complete'>").append('<a>' + auto_suggest_value + '</a>').appendTo(ul)
    };
    });
    $(document).on("click", ".dremove", function() {
          $(this).parent().parent().prev().remove();
        $(this).parent().parent().remove();
    

    });
    var datePickerOptions = {
        minDate: 0,
        numberOfMonths: 2,
        changeMonth: !0,
        dateFormat: "yy-mm-dd"
    }
    $('#tour_start_date').datepicker({
        minDate: 0,
        numberOfMonths: 2,
        changeMonth: !0,
        dateFormat: "yy-mm-dd"
    });

    $('#tour_expire_date').datepicker({
        minDate: 0,
        numberOfMonths: 2,
        changeMonth: !0,
        dateFormat: "yy-mm-dd"
    });
    $(document).on("mouseover", ".cancel_start_date", function() {
        $(this).datepicker({
            minDate: 0,
            numberOfMonths: 2,
            changeMonth: !0,
            dateFormat: "yy-mm-dd"
        });

    });
    $('.cancel_start_date').datepicker(datePickerOptions);
});
</script>
<script>
$(function() {
    $.widget("custom.catcomplete", $.ui.autocomplete, {
        _create: function() {
            this._super();
            this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
        },
        _renderMenu: function(ul, items) {
            var that = this,
                currentCategory = "";
            $.each(items, function(index, item) {
                var li;
                if (item.category != currentCategory) {
                    ul.append("<li class='ui-autocomplete-category'>" + item.category +
                        "</li>");
                    currentCategory = item.category;
                }
                li = that._renderItemData(ul, item);
                if (item.category) {
                    li.attr("aria-label", item.category + " : " + item.label);
                }
            });
        }
    });
    var data = [{
            label: "anders",
            category: ""
        },
        {
            label: "andreas",
            category: ""
        },
        {
            label: "antal",
            category: ""
        },
        {
            label: "annhhx10",
            category: "Products"
        },
        {
            label: "annk K12",
            category: "Products"
        },
        {
            label: "annttop C13",
            category: "Products"
        },
        {
            label: "anders andersson",
            category: "People"
        },
        {
            label: "andreas andersson",
            category: "People"
        },
        {
            label: "andreas johnson",
            category: "People"
        }
    ];

    $(".departcar").catcomplete({
        delay: 0,
        source: data
    });
});
</script>
  <?php 
                                   if($pid!="")
                                   {
                                       ?>
                                       <script>
                                       $(document).ready(function () {
                                         //  alert("test");
                                           $("#country").trigger("change");
                                       });
                                       </script>
                                       <?php
                                   }
                                ?>