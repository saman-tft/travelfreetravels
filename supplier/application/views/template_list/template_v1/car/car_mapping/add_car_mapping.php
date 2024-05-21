<?php
//debug($duration_list);
//exit;
//if(isset($car_mapping_data)){
//    debug($car_mapping_data);
//exit;
//}

if (isset($car_mapping_data)) {
    $tab3 = " active ";
    $tab1 = "";
    $tab2 = "";
} else {
    $tab1 = " active ";
    $tab2 = "";
    $tab3 = "";
}
?>
<!-- HTML BEGIN -->
<head>
    <link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/font-icons/entypo/css/entypo.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-core.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-theme.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-forms.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/custom.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/assets/js/daterangepicker/daterangepicker-bs3.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/selectboxit/jquery.selectBoxIt.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/daterangepicker/daterangepicker-bs3.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/select2/select2-bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/select2/select2.css">

</head>
<style>
    .tab_error_color {
        color: red !important;
    }
    .tab_msg_color {
        background: blue !important;
    }
</style>
<?php
$car_list = $car_list['data']['list'];
?>
<div class="bodyContent">
    <div class="panel panel-default clearfix">
        <!-- PANEL WRAP START -->
        <div class="panel-heading">
            <!-- PANEL HEAD START -->
            <div class="panel-title">

                <ul class="nav nav-tabs" role="tablist" id="myTab">
                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->

                    <li role="presentation" class="<?php echo $tab1; ?>">
                        <a href="#tableList" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-car"></i> Car Mapping List
                        </a>
                    </li>

                    <li role="presentation" class="<?php echo $tab2; ?>">
                        <a id="fromListHead" href="#fromList" aria-controls="home" role="tab" data-toggle="tab"> <i class="fa fa-edit"></i> Add Car Mapping
                        </a>
                    </li>

                    <?php if(isset($car_mapping_data)){ ?>
                        <li role="presentation" class="<?php echo $tab2; ?>">
                            <a id="editListHead" href="#editmapping" aria-controls="home" role="tab" data-toggle="tab"> <i class="fa fa-edit"></i> Edit Car Mapping
                            </a>
                        </li>
                    <?php } ?>

                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
                </ul>

            </div>
        </div>
        <!-- PANEL HEAD START -->

        <!-- PANEL BODY START -->
        <div class="panel-body">
            <div class="tab-content">
                <div role="tabpanel" class="clearfix tab-pane car_map_box <?php echo $tab2; ?>" id="fromList">
                    <form method="post" id="tax" name="tax" action="<?php echo site_url()."/car_mapping/add_car_mapping"; ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">
                        <fieldset form="user_edit">
                            <legend class="form_legend">Add Car Mapping</legend>

                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label">Car</label>
                                <div class="col-sm-5">
                                    <select id="car_details_id" name="car_details_id" class="form-control">
                                        <option value="0">Select Car</option>
                                        <?php foreach ($car_list as $k=>$v){ ?>
                                            <option value="<?php echo $v['car_id']; ?>" data-iconurl=""><?php echo $v['name']; ?></option>
                                        <?php } ?>
                                        <?php echo form_error('car_details_id',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label">Duration</label>
                                <div class="col-sm-5">
                                    <select multiple id="car_duration" name="car_duration[]" class="form-control">
                                        <option value="0">Select Duration</option>
                                        <?php foreach ($duration_list as $k=>$v){ ?>
                                            <option value="<?php echo ($v->duration_id.','.$v->duration_from_date.','.$v->duration_to_date); ?>" data-iconurl=""><?php echo $v->duration_from_date." to ".$v->duration_to_date; ?></option>
                                        <?php } ?>
                                        <?php echo form_error('car_details_id',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label">Applicable type</label>
                                <div class="col-sm-5">
                                    <input type="checkbox" name="local_use" id="local_use" />Drive Me<br/>
                                    <input type="checkbox" name="transfers" id="transfers" />Airport Taxi<br/>
                                     <input type="checkbox" name="out_station" id="out_station" />Out Station<br/> 
                                </div>
                            </div>
                            <div class="form-group--1">
                                <div class="">
                                    <table class="table-bordered">
                                        <tr>
                                            <td id="local_use_lable" style="display:none">Drive Me</td>
                                            <td id="transfers_lable" style="display:none">Airport Taxi</td>
                                            <td id="out_station_lable" style="display:none">Out Station</td>
                                        </tr>
                                        <tr>
                                            <td id="local_use_lable_data" style="display:none" valign="top">

<div id="charges" style='display:none;'>
                            <label for="chrg_type" class="padfive" style="float: left;">Charge Type</label>
                            <ul id="chrg_type" class="padfive" style="float: left;">
                                <li style="float: left;">
                                    <input type="radio" name="charges_type" id="perhr" value="perhr" class="active">
                                    <label for="perhr" style="vertical-align: middle;">Per Hour</label>
                                </li>
                                <li style="float: left; margin-left: 10px;">
                                    <input name="charges_type" value="perkm" type="radio" id="perkm">
                                  <!--  <label for="perkm" style="vertical-align: middle;">Per Km</label>-->
                                  <label for="perkm" style="vertical-align: middle;">Per Distance</label>
                                </li>
                            </ul>
                            </div>
                           
                           <div class="clearfix"></div>


<div style='display:none' id="kl_m_drive">
                            <label for="klm_op" class="padfive" style="float: left;">Select Km or Miles</label>
                            <ul id="klm_op" class="padfive" style="float: left;">
                                <li style="float: left;">
                                    <input type="radio" name="kmiles_driveme_op" id="km_op" value="km_op" class="active">
                                    <label for="km_op" style="vertical-align: middle;">KiloMeter</label>
                                </li>
                                <li style="float: left; margin-left: 10px;">
                                    <input name="kmiles_driveme_op" value="m_op" type="radio" id="m_op">
                                  <!--  <label for="perkm" style="vertical-align: middle;">Per Km</label>-->
                                  <label for="m_op" style="vertical-align: middle;">Miles</label>
                                </li>
                            </ul>
                            </div><br>
                            <div class="clearfix"></div>


                                                <div class="form-group1">
                                                    <div class='' style="display: center">
                                                        <div>Country: <select class='form-control'
                                                                              data-rule-required='true' name='localuse_from_country' id="localuse_from_country" required>
                                                                <option>---Select Country---</option>
                                                                <?php
                                                                foreach ($country_list as $key => $value) {
                                                                    echo "<option value='".$value['origin']."'>".$value['country_name']."</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        From City: <select class='form-control'
                                                                           data-rule-required='true' name='localuse_from_city' id="localuse_from_city" required>
                                                        </select>
                                                        Note: Please select price type to add price.<br/>

<div id='driveme_hr'>
                                                        <div class="radio radio-success radio-inline">
                                                            <input type="radio" id="inlineRadio1" value="1" name="bn">
                                                            <label style="padding-left: 0px;" for="inlineRadio1">Fixed Price(AUD)</label>
                                                        </div>
                                                        <div class="radio radio-danger radio-inline">
                                                            <input type="radio" id="inlineRadio2" value="0" name="bn">
                                                            <label style="padding-left: 0px;" for="inlineRadio2">Variable Price(AUD)</label>
                                                        </div>
                                                        
                                                        </div>
                                                        <div id='drive_pric' style='display:none;'>
                                                        <div class="radio radio-danger radio-inline" >
                                                        <div class='d_perkm'>
                                                            <input type="radio"  id="inlineRadio2" value="2"  name="bn">
                                                            <label style="padding-left: 0px;" for="inlineRadio2">Price Per Km(AUD)</label>
                                                            </div>
                                                            <div class='d_permile'>
                                                            <input  type="radio" id="inlineRadio2" value="3"  name="bn">
                                                            <label style="padding-left: 0px;" for="inlineRadio2">Price Per Mile(AUD)</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                        <div id="fixed_price" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                                                <!-- Modal content-->
                                         <div class="modal-content">
                                           <div class="modal-header">
                                               <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                     <h4 class="modal-title">Fixed Price(AUD)</h4>
                                                     </div>
                                     <div class="modal-body">
                                                <label for="per_wkday">Fixed Per 2Hr WeekDay Price(AUD)</label>
                                                      <input type="text" id="per_wkday" name="per_wkday" />
                                                                        <br>
                                                                        <label for="per_wkend">Fixed Per 2Hr WeekEnd Price(AUD)</label>
                                                                        <input type="text" id="per_wkend" name="per_wkend" />
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div id="variable_price" class="modal fade" role="dialog">
                                                            <div class="modal-dialog modal-lg">

                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        <h4 class="modal-title">Variable Price(AUD)</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                              <table class="table table-bordered table-striped table-highlight">
                                                                                <thead>
                                                                                <th>Day Type</th>
                                                                                <?php for($i=2;$i<=24;$i+=2){ ?>
                                                                                <th><?= $i ?> Hr</th>
                                                                                <?php } ?>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td>WeekDay Price(AUD)</td>
                                                                                    <?php for($i=2;$i<=24;$i+=2){ ?>
                                                                                    <td ><input type="text" name="wkday_<?= $i ?>"  class="form-control var_price" value="" /></td>
                                                                                    <?php } ?>

                                                                                </tr>
                                                                                <tr>
                                                                                    <td>WeekEnd Price(AUD)</td>
                                                                                    <?php for($i=2;$i<=24;$i+=2){ ?>
                                                                                    <td ><input type="text" name="wkend_<?= $i ?>"  class="form-control var_price" value="" /></td>
                                                                                    <?php } ?>

                                                                                </tr>
                                                                                </tbody>
                                                        </table>
                                                        </div>
                                           </div>
                                           <div class="modal-footer">
                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

<div id="m_driveprice" class="modal fade" role="dialog">
                                                            <div class="modal-dialog modal-lg">

                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Drive Me Price(Per Km)(AUD)</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered table-striped table-highlight">
                                    <thead>
                                         <th>Km</th>
                                        <?php for($i=0;$i<9;$i+=1){ 
                                            $km_hr=array('0-1000','1000-2000','2000-3000','3000-4000','4000-5000','5000-6000','6000-7000','7000-8000','8000-9000');
                                            ?>
                                                <th><select name='drive_mhr'>
<option value="<?php echo $i?>"><?php echo $km_hr[$i];?></option>


</select>
                                                 Km</th>
                                                    <?php } ?>
                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                            <td>WeekDay Price(AUD)</td>
                                            <?php for($i=0;$i<9;$i+=1){ ?>
                            <td ><input type="text" name="drive_mwkday_<?= $i ?>"  class="form-control var_price" value="" /></td>
                                <?php } ?>

                                </tr>
                            <tr>
                                      <td>WeekEnd Price(AUD)</td>
                                    <?php for($i=0;$i<9;$i+=1){ ?>
                                        <td ><input type="text" name="drive_mwkend_<?= $i ?>"  class="form-control var_price" value="" /></td>
                                                    <?php } ?>

                                            </tr>
                                        </tbody>
                                    </table>
                            </div>
                                </div>
                        <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div id="km_driveprice" class="modal fade" role="dialog">
                                                            <div class="modal-dialog modal-lg">

                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        <h4 class="modal-title">Drive Me Price(Per Mile)(AUD)</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered table-striped table-highlight">
                                    <thead>
                                         <th>Miles</th>
                                        <?php for($i=0;$i<9;$i+=1){ 
                                            $km_hr=array('0-1000','1000-2000','2000-3000','3000-4000','4000-5000','5000-6000','6000-7000','7000-8000','8000-9000');
                                            ?>
                                                <th><select name='drive_kmhr'>
<option value="<?php echo $i?>"><?php echo $km_hr[$i];?></option>


</select>
                                                 Miles</th>
                                                    <?php } ?>
                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                            <td>WeekDay Price(AUD)</td>
                                            <?php for($i=0;$i<9;$i+=1){ ?>
                            <td ><input type="text" name="drive_wkday_<?= $i ?>"  class="form-control var_price" value="" /></td>
                                <?php } ?>

                                </tr>
                            <tr>
                                      <td>WeekEnd Price(AUD)</td>
                                    <?php for($i=0;$i<9;$i+=1){ ?>
                                        <td ><input type="text" name="drive_wkend_<?= $i ?>"  class="form-control var_price" value="" /></td>
                                                    <?php } ?>

                                            </tr>
                                        </tbody>
                                    </table>
                            </div>
                                </div>
                        <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td id="transfers_lable_data" style="display:none" valign="top">
                                                <div style="padding: 0px 15px;">
                                                <div style='display:none' id="kll_m">
                            <label for="kllm_op" class="padfive" style="float: left;">Select Km or Miles</label>
                            <ul id="kllm_op" class="padfive" style="float: left;">
                                <li style="float: left;">
                                    <input type="radio" name="kmiles_op_airport" id="kllm_op" value="kllm_op" class="active">
                                    <label for="klm_op" style="vertical-align: middle;">KiloMeter</label>
                                </li>
                                <li style="float: left; margin-left: 10px;">
                                    <input name="kmiles_op_airport" value="lm_op" type="radio" id="lm_op">
                                  <!--  <label for="perkm" style="vertical-align: middle;">Per Km</label>-->
                                  <label for="lm_op" style="vertical-align: middle;">Miles</label>
                                </li>
                            </ul>
                            </div><br>
                            <div class="clearfix"></div>

                                                    <div class="form-group form-group1">
                                                        <div class=''>
                                                            Country: <select class=' form-control'
                                                                             data-rule-required='true' name='transfers_from_country' id="transfers_from_country" required>
                                                                <option>---Select Country---</option>
                                                                <?php
                                                                foreach ($country_list as $key => $value) {
                                                                    echo "<option value='".$value['origin']."'>".$value['country_name']."</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                            From City: <select class='form-control'
                                                                               data-rule-required='true' name='transfers_from_city' id="transfers_from_city" required>
                                                            </select>
                                                            Note: Please Provide price  for the trip.<br/>
                                                            
                                                            <div class='airport_km' class='hide'>
                            Weekdays Price(Per Kms) (AUD):
                            <input type="numeric" name="transfers_weekdays_price" id="transfers_weekdays_price"
                                   data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($car_mapping_data)?$car_mapping_data['data'][0]['weekday_price']:'';?>"
                                   class='form-control add_pckg_elements numeric' required="">
                            <span class="error"><?php echo form_error('transfers_weekdays_price')?></span>
                            Weekend Price(Per Kms) (AUD):
                            <input type="numeric" name="transfers_weekend_price" id="transfers_weekend_price"
                                   data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($car_mapping_data)?$car_mapping_data['data'][0]['weekend_price']:'';?>"
                                   class='form-control add_pckg_elements numeric' >
                            <span class="error"><?php echo form_error('transfers_weekend_price')?></span>

                        </div>
                        <div class='airport_miles' class='hide'>
                            Weekdays Price(Per Mile) (AUD):
                            <input type="numeric" name="transfers_weekdays_pricemile" id="transfers_weekdays_pricemile"
                                   data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($car_mapping_data)?$car_mapping_data['data'][0]['weekday_pricemile']:'';?>"
                                   class='form-control add_pckg_elements numeric' required="">
                            <span class="error"><?php echo form_error('transfers_weekdays_pricemile')?></span>
                            Weekend Price(Per Mile)(AUD):
                            <input type="numeric" name="transfers_weekend_pricemile" id="transfers_weekend_pricemile"
                                   data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($car_mapping_data)?$car_mapping_data['data'][0]['weekend_pricemile']:'';?>"
                                   class='form-control add_pckg_elements numeric' >
                            <span class="error"><?php echo form_error('transfers_weekend_pricemile')?></span>

                        </div>


                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td id="out_station_lable_data" style="display:none" valign="top">
                                                <div class="form-group1">
                                                    <div class=''>
                                                        Trip Type: <input type="radio" id="oneway_transfers" name="trip_type[]" value="oneway" />One Way
                                                        <input type="radio" name="trip_type[]" id="roundway_transfers" value="roundway" />Round Way<br/>
                                                         
                                            <div id="charg" style='display:none;'>
                            <label for="chrg_type" class="padfive" style="float: left;">Charge Type</label>
                            <ul id="chrg_type" class="padfive" style="float: left;">
                                <li style="float: left;">
                                    <input type="radio" name="charge_type" id="perhr" value="perhr" class="active">
                                    <label for="perhr" style="vertical-align: middle;">Per Hour</label>
                                </li>
                                <li style="float: left; margin-left: 10px;">
                                    <input name="charge_type" value="perkm" type="radio" id="perkm">
                                  <!--  <label for="perkm" style="vertical-align: middle;">Per Km</label>-->
                                  <label for="perkm" style="vertical-align: middle;">Per Distance</label>
                                </li>
                            </ul>
                            </div>
                           
                           <div class="clearfix"></div>
                                                        <div style='display:none' id="klm">
                            <label for="klm_op" class="padfive" style="float: left;">Select Km or Miles</label>
                            <ul id="klm_op" class="padfive" style="float: left;">
                                <li style="float: left;">
                                    <input type="radio" name="kmiles_op" id="km_op" value="km_op" class="active">
                                    <label for="km_op" style="vertical-align: middle;">KiloMeter</label>
                                </li>
                                <li style="float: left; margin-left: 10px;">
                                    <input name="kmiles_op" value="m_op" type="radio" id="m_op">
                                  <!--  <label for="perkm" style="vertical-align: middle;">Per Km</label>-->
                                  <label for="m_op" style="vertical-align: middle;">Miles</label>
                                </li>
                            </ul>
                            </div>
 <div class="clearfix"></div>
                           
                            
                                         Country: <select class='form-control'
                                                                         data-rule-required='true' name='outsource_from_country' id="outsource_from_country" required>
                                                            <option>---Select Country---</option>
                                                            <?php
                                                            foreach ($country_list as $key => $value) {
                                                                echo "<option value='".$value['origin']."'>".$value['country_name']."</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                        From City: <select class='form-control'
                                                                           data-rule-required='true' name='outsource_from_city' id="outsource_from_city" required>
                                                            <option>---Select city---</option>
                                                        </select>
                                                        To City: <select class='form-control'
                                                                         data-rule-required='true' name='outsource_to_city' id="outsource_to_city" required>
                                                        </select>
                                                        Note: Please Provide total price for the trip.<br/>

                                                        
                                                       <div class="hide" id="transfers_oneway_price">
                                                            Weekdays Price(Per KM)(Oneway) (AUD)<input type="numeric" name="outsource_weekdays_price_oneway" id="outsource_weekdays_price_oneway"                                                                                    data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($outsource_weekdays_price_oneway)?$outsource_weekdays_price_oneway:'';?>"                                                                                    class='form-control add_pckg_elements numeric' required="">
                                                            <span class="error"><?php echo form_error('outsource_weekdays_price_oneway')?></span>
                                                            Weekend Price(Per KM)(Oneway) (AUD)<input type="numeric" name="outsource_weekend_price_oneway" id="outsource_weekend_price_oneway"                                                                                        data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($outsource_weekend_price_oneway)?$outsource_weekend_price_oneway:'';?>"                                                                                        class='form-control add_pckg_elements numeric' >
                                                            <span class="error"><?php echo form_error('outsource_weekend_price_oneway')?></span><br/>
                                                        </div>


<div class="hide" id="transfers_oneway_milesprice">
                                                            Weekdays Price(Per Mile)(Oneway) (AUD)<input type="numeric" name="outsource_weekdays_price_milesoneway" id="outsource_weekdays_price_milesoneway"                                                                                    data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($outsource_weekdays_price_milesoneway)?$outsource_weekdays_milesprice_oneway:'';?>"                                                                                    class='form-control add_pckg_elements numeric' required="">
                                                            <span class="error"><?php echo form_error('outsource_weekdays_price_milesoneway')?></span>
                                                            Weekend Price(Per Mile)(Oneway) (AUD)<input type="numeric" name="outsource_weekend_price_milesoneway" id="outsource_weekend_price_milesoneway"                                                                                        data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($outsource_weekend_price_milesoneway)?$outsource_weekend_price_milesoneway:'';?>"                                                                                        class='form-control add_pckg_elements numeric' >
                                                            <span class="error"><?php echo form_error('outsource_weekend_price_milesoneway')?></span><br/>
                                                        </div>









                                                        <div class="hide" id="transfers_roundway_price">
                                                            Weekdays Price(Per Km) (AUD)<input type="numeric" name="outsource_weekdays_price_roundway_km" id="outsource_weekdays_price_roundway"                                                                                         data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($outsource_weekdays_price_roundway)?$outsource_weekdays_price_roundway:'';?>"                                                                                         class='form-control add_pckg_elements numeric' required="">
                                                            <span class="error"><?php echo form_error('outsource_weekdays_price_roundway')?></span>
                                                            Weekend Price(Per Km) (AUD)<input hidden type="numeric" name="outsource_weekend_price_roundway_km" id="outsource_weekend_price_roundway"                                                                                        data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($outsource_weekend_price_round)?$outsource_weekend_price_round:'';?>"                                                                                        class='form-control add_pckg_elements numeric' >
</div>
<div id='roundway_perhr' class='hide'>
                                                            Weekdays Price(Per hour) (AUD)<input type="numeric" name="outsource_weekdays_price_roundway_hr" id="outsource_weekdays_price_roundway_hr"                                                                                          data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($outsource_weekdays_price_roundway)?$outsource_weekdays_price_roundway:'';?>"                                                                                           class='form-control add_pckg_elements numeric' required="">
                                                            <span class="error"><?php echo form_error('outsource_weekdays_price_roundway')?></span>
                                                            Weekend Price(Per hour) (AUD)<input hidden type="numeric" name="outsource_weekend_price_roundway_hr" id="outsource_weekend_price_roundway_hr"                                                                                          data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($outsource_weekend_price_round)?$outsource_weekend_price_round:'';?>"                                                                                        class='form-control add_pckg_elements numeric' >
                                                            <span class="error"><?php echo form_error('outsource_weekend_price_round')?></span>
                                                        </div>
</div>

                                                        <div class="hide" id="transfers_roundway_milesprice">
                                                            Weekdays Price(Per Mile) (AUD)<input type="numeric" name="outsource_weekdays_price_roundway_mile" id="outsource_weekdays_price_roundwaymile"                                                                                data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($outsource_weekdays_price_roundwaymile)?$outsource_weekdays_price_roundwaymile:'';?>"                                                                                         class='form-control add_pckg_elements numeric' required="">
                                                            <span class="error"><?php echo form_error('outsource_weekdays_price_roundway')?></span>
                                                            Weekend Price(Per Mile) (AUD)<input hidden type="numeric" name="outsource_weekend_price_roundway_mile" id="outsource_weekend_price_roundwaymile"                                                                                        data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($outsource_weekend_price_roundmile)?$outsource_weekend_price_roundmile:'';?>"                                                                           class='form-control add_pckg_elements numeric' >
                                                            </div>
<div id='roundway_perhrmile' class='hide'>
                                                            Weekdays Price(Per hour) (AUD)<input type="numeric" name="outsource_weekdays_price_roundway_hrmile" id="outsource_weekdays_price_roundway_hrmile"                                                                                          data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($outsource_weekdays_price_roundwaymile)?$outsource_weekdays_price_roundwaymile:'';?>"                                                                                           class='form-control add_pckg_elements numeric' required="">
                                                            <span class="error"><?php echo form_error('outsource_weekdays_price_roundway')?></span>
                                                            Weekend Price(Per hour) (AUD)<input hidden type="numeric" name="outsource_weekend_price_roundway_hrmile" id="outsource_weekend_price_roundway_hrmile"                                                                                          data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($outsource_weekend_price_roundmile)?$outsource_weekend_price_roundmile:'';?>"                                                                                        class='form-control add_pckg_elements numeric' >
                                                            <span class="error"><?php echo form_error('outsource_weekend_price_roundmile')?></span>
                                                            </div>
                                                        </div>
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label"></label>
                                <div class="col-sm-5">
                                    <button id="add_submit" type="submit" disabled="disabled" class="btn btn-success">Add Car Mapping</button>
                                </div>
                            </div>
                    </form>
                </div>

                <div role="tabpanel" class="clearfix tab-pane <?php echo $tab1; ?>" id="tableList">

                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#transfers_table">Airport Taxi</a></li>
                        <li><a data-toggle="tab" href="#localuse_table">Drive Me</a></li>
                       <li><a data-toggle="tab" href="#outstation_table">Outstation(OneWay)</a></li>
                        <li><a data-toggle="tab" href="#roundway_table">Outstation(RoundWay)</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="transfers_table" class="tab-pane fade active in">
                            <h3>Airport Taxi</h3>
                            <?php

                            echo get_table_transfers(@$table_data_transfers);
                            ?>
                        </div>
                        <div id="localuse_table" class="tab-pane fade">
                            <h3>Drive Me</h3>
                            <?php

                            echo get_table_localuse(@$table_data_localuse);
                            ?>
                        </div>
                        <div id="outstation_table" class="tab-pane fade">
                            <h3>Outstation(OneWay)</h3>
                            <?php

                            echo get_table_outstation(@$table_data_oneway);
                            ?>
                        </div>
                        <div id="roundway_table" class="tab-pane fade">
                            <h3>Outstation(RoundWay)</h3>
                            <?php

                            echo get_table_roundway(@$table_data_roundway);
                            ?>
                        </div>
                    </div>
                </div>


                <?php if(isset($car_mapping_data)){
                if($car_mapping_type == 'transfers')
                {
                    if(!empty($car_mapping_data['user_data']))
                        $map_id=$car_mapping_data['user_data']['transfers_id'];
                    else
                    $map_id = $car_mapping_data['data'][0]['transfers_id'];
                    //$map_id = $car_mapping_data['data'][0]['transfers_id'];
                }
                if($car_mapping_type == 'localuse')
                {
                    if(!empty($car_mapping_data['user_data']))
                        $map_id=$car_mapping_data['user_data']['localuse_id'];
                    else
                    $map_id = $car_mapping_data['data'][0]['localuse_id'];
                    //$map_id = $car_mapping_data['data'][0]['localuse_id'];
                }
                if($car_mapping_type == 'outstation')

                {
                    if(!empty($car_mapping_data['user_data']))
                        $map_id=$car_mapping_data['user_data']['outstation_id'];
                    else
                    $map_id = $car_mapping_data['data'][0]['outstation_id'];
                }
                   // $map_id = $car_mapping_data['data'][0]['outstation_id'];
                if($car_mapping_type == 'roundway')
                {
                    if(!empty($car_mapping_data['user_data']))
                        $map_id=$car_mapping_data['user_data']['outstation_id'];
                    else
                    $map_id = $car_mapping_data['data'][0]['outstation_id'];
            }
                ?>
                <div role="tabpanel" class="clearfix tab-pane <?php echo $tab3; ?>" id="editmapping">
                    <?php //debug($car_mapping_data); ?>
                    <form method="post" id="tax" name="tax" action="<?php echo site_url()."/car_mapping/update_car_mapping/".$map_id."/".$car_mapping_type; ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">

                        <legend class="form_legend">Edit Car Mapping</legend>

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Car</label>
                            <div class="col-sm-5">
                                <select id="car_details_id_edit" name="car_details_id" class="form-control">
                                    <option value="0">Select Car</option>
                                    <?php foreach ($car_list as $k=>$v){  if($v['car_id'] == $car_mapping_data['data'][0]['car_id']) { ?>
                                        <option selected="selected" value="<?php echo $v['car_id']; ?>" data-iconurl=""><?php echo $v['name']; ?></option>
                                        <?php
                                    }  else{
                                        ?>
                                        <option value="<?php echo $v['car_id']; ?>" data-iconurl=""><?php echo $v['name']; ?></option>
                                    <?php }  } ?>
                                    <?php echo form_error('car_details_id',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Duration</label>
                            <div class="col-sm-5">
                                <select multiple id="car_duration" name="car_duration[]" class="form-control">
                                    <option value="0">Select Duration</option>
                                    <?php foreach ($duration_list as $k=>$v){
                                        if($v->duration_id == $car_mapping_data['data'][0]['car_duration']) { ?>
                                            <option selected="selected" value="<?php echo ($v->duration_id.','.$v->duration_from_date.','.$v->duration_to_date); ?>" data-iconurl=""><?php echo $v->duration_from_date." to ".$v->duration_to_date; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo ($v->duration_id.','.$v->duration_from_date.','.$v->duration_to_date); ?>" data-iconurl=""><?php echo $v->duration_from_date." to ".$v->duration_to_date; ?></option>
                                        <?php }  }  echo form_error('car_details_id',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
                                </select>
                            </div>
                        </div>
                        <?php
                        if($car_mapping_type == 'transfers'){

                             $air_weekdaykm=$car_mapping_data['user_data']['weekday_price_oneway'];
$air_weekendkm=$car_mapping_data['user_data']['weekend_price_oneway'];
$air_weekdaymile=$car_mapping_data['user_data']['weekday_price_onewaymile'];
$air_weekendmile=$car_mapping_data['user_data']['weekend_price_onewaymile'];
                        ?>
                        <div class="form-group1 col-sm-5">
                            Country:
                            <select class='form-control'
                                    data-rule-required='true' name='transfers_from_country' id="edit_transfers_from_country" required>
                                <option>---Select Country---</option>
                                <?php
                                foreach ($country_list as $key => $value) {
                                    if($value['origin'] == $car_mapping_data['data'][0]['from_country']){
                                        echo "<option selected value='".$value['origin']."'>".$value['country_name']."</option>";
                                    }else{
                                        echo "<option value='".$value['origin']."'>".$value['country_name']."</option>";
                                    }

                                }
                                ?>
                            </select>

                            From City:
                            <select class='form-control'
                                    data-rule-required='true' name='transfers_from_city' id="edit_transfers_from_city" required><?php echo $car_mapping_data['data'][0]['city_option']; ?>
                            </select>
                            Note: Please Provide price for the trip.<br/>
                    <div class='airport_km'>
                            Weekdays Price(Per Kms) (AUD):
                            <input type="numeric" name="transfers_weekdays_price" id="transfers_weekdays_price"
                                   data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($air_weekdaykm)?$air_weekdaykm:$car_mapping_data['data'][0]['weekday_price'];?>"
                                   class='form-control add_pckg_elements numeric' required="">
                            <span class="error"><?php echo form_error('transfers_weekdays_price')?></span>
                            Weekend Price(Per Kms) (AUD):
                            <input type="numeric" name="transfers_weekend_price" id="transfers_weekend_price"
                                   data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($air_weekendkm)?$air_weekendkm:$car_mapping_data['data'][0]['weekend_price'];?>"
                                   class='form-control add_pckg_elements numeric' >
                            <span class="error"><?php echo form_error('transfers_weekend_price')?></span>

                        </div>
                        <div class='airport_miles' >
                            Weekdays Price(Per Mile) (AUD):
                            <input type="numeric" name="transfers_weekdays_pricemile" id="transfers_weekdays_pricemile"
                                   data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($air_weekdaylime)?$air_weekdaymile:$car_mapping_data['data'][0]['weekday_pricemile'];?>"
                                   class='form-control add_pckg_elements numeric' required="">
                            <span class="error"><?php echo form_error('transfers_weekdays_pricemile')?></span>
                            Weekend Price(Per Mile)(AUD):
                            <input type="numeric" name="transfers_weekend_pricemile" id="transfers_weekend_pricemile"
                                   data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($air_weekendmile)?$air_weekendmile:$car_mapping_data['data'][0]['weekend_pricemile'];?>"
                                   class='form-control add_pckg_elements numeric' >
                            <span class="error"><?php echo form_error('transfers_weekend_pricemile')?></span>

                        </div>
                        </div>
                </div>

                <?php
                }
                if($car_mapping_type == 'localuse')
                {
$fixed_wkday_price=$car_mapping_data['user_data']['fixed_wkday_price'];
$fixed_wkend_price=$car_mapping_data['user_data']['fixed_wkend_price'];
$var_wkday_price=json_decode($car_mapping_data['user_data']['var_wkday_price'],true);
$var_wkend_price=json_decode($car_mapping_data['user_data']['var_wkend_price'],true);

$mile_wkday_price=json_decode($car_mapping_data['user_data']['mile_wkday_price'],true);
$mile_wkend_price=json_decode($car_mapping_data['user_data']['mile_wkend_price'],true);
$km_wkday_price=json_decode($car_mapping_data['user_data']['km_wkday_price'],true);

$km_wkend_price=json_decode($car_mapping_data['user_data']['km_wkend_price'],true);

                ?>
                <div class="form-group1">
                    <div class='' style="display: center">
                        <div>Country: <select class='form-control'
                                              data-rule-required='true' name='localuse_from_country' id="edit_localuse_from_country" required>
                                <option>---Select Country---</option>
                                <?php
                                foreach ($country_list as $key => $value) {
                                    if($value['origin'] == $car_mapping_data['data'][0]['from_country']){
                                        echo "<option selected value='".$value['origin']."'>".$value['country_name']."</option>";
                                    }else{
                                        echo "<option value='".$value['origin']."'>".$value['country_name']."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        From City: <select class='form-control'
                                           data-rule-required='true' name='localuse_from_city' id="edit_localuse_from_city" required><?php echo $car_mapping_data['data'][0]['city_option']; ?>
                        </select>

                            Price Type: <br>
                            <div class="clearfix"></div>
                            <ul id="pric_type" style="float: left;">
                            <?php
                            if(isset($pric_type))
                            {
for($ind=0;$ind<count($pric_type);$ind++)
{
                                ?>

                                <li >
                                    <input type="radio" name="change_type" id="pric_type" value="<?php echo $pric_type[$ind];?>" class="active">
                                    <label for="pric_type" style="vertical-align: middle;"><?php echo $pric_type[$ind];?></label>
                                </li>

                           <?php
                        }
                    }
                        ?>
                        </ul>
                            <!--<label for="change_type">Change Price type?</label>-->

                        
                        <div class="clearfix"></div>
                        Note: Weekday(Sun-Th) & Weekend(Fr-Sat) Price (AUD).
                        <div class="radio radio-success radio-inline">
                            <input type="hidden" id="price_type" name="price_type" value="<?php echo isset($car_mapping_data['user_data']['price_type'])?$car_mapping_data['user_data']['price_type']:''; ?>" />
<!--                            <button type="button" id="edit_price" value="--><?php //echo isset($car_mapping_data['data'][0]['price_type'])?$car_mapping_data['data'][0]['price_type']:''; ?><!--" name="edit_price">Edit Price</button>-->

                        </div>


                        <div id="local_fixed" style='display:none;' class="form-control1" >
                            <label for="per_wkday">Fixed WeekDay Price (AUD/2Hr)</label>
                            <input type="text" id="per_wkday" name="per_wkday" value="<?php echo isset($fixed_wkday_price)?$fixed_wkday_price:$car_mapping_data['data'][0]['fixed_wkday_price']; ?>" />
                            <br>
                            <label for="per_wkend">Fixed WeekEnd Price (AUD/2Hr)</label>
                            <input type="text" id="per_wkend" name="per_wkend" value="<?php echo isset($fixed_wkend_price)?$fixed_wkend_price:$car_mapping_data['user_data']['fixed_wkend_price']; ?>" />
                        </div>

                    <div id="local_var" class="table-responsive" style="display: none;">
                        <table class="table table-bordered table-striped table-highlight">
                            <thead>
                            <th>Day Type</th>
                            <?php for($i=2;$i<=24;$i+=2){ ?>
                                <th><?= $i ?> Hr</th>
                            <?php } ?>
                            </thead>
                            <tbody>
                            <tr>
                                <td>WeekDay Price(AUD)</td>
                                <?php for($i=2;$i<=24;$i+=2){ ?>
                                    <td><input type="text" name="wkday_<?= $i ?>"  class="form-control var_price" value="<?php echo isset($var_wkday_price)?$var_wkday_price[$i]:$car_mapping_data['data'][0]['var_wkday_price'][$i]; ?>" /></td>
                                <?php } ?>

                            </tr>
                            <tr>
                                <td>WeekEnd Price(AUD)</td>
                                <?php for($i=2;$i<=24;$i+=2){ ?>
                                    <td><input type="text" name="wkend_<?= $i ?>"  class="form-control var_price" value="<?php echo isset($var_wkend_price)?$var_wkend_price[$i]:$car_mapping_data['data'][0]['var_wkend_price'][$i]; ?>" /></td>
                                <?php } 


                                ?>

                            </tr>
                            </tbody>
                        </table>
                    </div>


                    <div id="miles_price" class="table-responsive" style="display: none;">
                        <table class="table table-bordered table-striped table-highlight">
                            <thead>
                            <th>Mile</th>
                            <?php 
$mile_wkday_price=json_decode($car_mapping_data['user_data']['mile_wkday_price'],true);
$mile_wkend_price=json_decode($car_mapping_data['user_data']['mile_wkend_price'],true);
//debug($km_wkday_price);die;
                            for($i=0;$i<9;$i+=1){ 
                                $km_hr=array('0-1000','1000-2000','2000-3000','3000-4000','4000-5000','5000-6000','6000-7000','7000-8000','8000-9000');?>
                                <th><?= $km_hr[$i]; ?> Miles</th>
                            <?php } ?>
                            </thead>
                            <tbody>
                            <tr>
                                <td>WeekDay Price(AUD)</td>
                                <?php for($i=0;$i<9;$i+=1){ ?>
                                    <td><input type="text" name="milewkday_<?= $i ?>"  class="form-control var_price" value="<?php echo isset($mile_wkday_price)?$mile_wkday_price[$i]:''; ?>" /></td>
                                <?php } ?>

                            </tr>
                            <tr>
                                <td>WeekEnd Price(AUD)</td>
                                <?php for($i=0;$i<9;$i+=1){ ?>
                                    <td><input type="text" name="milewkend_<?= $i ?>"  class="form-control var_price" value="<?php echo isset($mile_wkend_price)?$mile_wkend_price[$i]:''; ?>" /></td>
                                <?php } ?>

                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="km_drives" class="table-responsive" style="display: none;">
                        <table class="table table-bordered table-striped table-highlight">
                            <thead>
                            <th>Km</th>
                            <?php 
$km_wkday_price=json_decode($car_mapping_data['user_data']['km_wkday_price'],true);
$km_wkend_price=json_decode($car_mapping_data['user_data']['km_wkend_price'],true);

                            for($i=0;$i<9;$i+=1){ 
                              $km_hr=array('0-1000','1000-2000','2000-3000','3000-4000','4000-5000','5000-6000','6000-7000','7000-8000','8000-9000');?>
                                <th><?= $km_hr[$i]; ?> Km</th>
                            <?php } ?>
                            </thead>
                            <tbody>
                            <tr>
                                <td>WeekDay Price(AUD)</td>
                                <?php for($i=0;$i<9;$i+=1){ ?>
                                    <td><input type="text" name="kmwkday_<?= $i ?>"  class="form-control var_price" value="<?php echo isset($km_wkday_price)?$km_wkday_price[$i]:''; ?>" /></td>
                                <?php } ?>

                            </tr>
                            <tr>
                                <td>WeekEnd Price(AUD)</td>
                                <?php for($i=0;$i<9;$i+=1){ ?>
                                    <td><input type="text" name="kmwkend_<?= $i ?>"  class="form-control var_price" value="<?php echo isset($km_wkend_price)?$km_wkend_price[$i]:''; ?>" /></td>
                                <?php } ?>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php }
                if($car_mapping_type == 'outstation' ){
                    // echo "in";
                    $a11=$car_mapping_data['user_data']['weekday_price_oneway'];
$b11=$car_mapping_data['user_data']['weekend_price_oneway'];
$c11=$car_mapping_data['user_data']['weekday_price_onewaymile'];
$d11=$car_mapping_data['user_data']['weekend_price_onewaymile'];
                ?>

                <div class="form-group1">
                    <div class=''>

                        Country: <select class='form-control'
                                         data-rule-required='true' name='outsource_from_country' id="edit_outsource_from_country" required>
                            <option>---Select Country---</option>
                            <?php
                            foreach ($country_list as $key => $value) {
                                if($value['origin'] == $car_mapping_data['data'][0]['from_country']){
                                    echo "<option selected value='".$value['origin']."'>".$value['country_name']."</option>";
                                }else{
                                    echo "<option value='".$value['origin']."'>".$value['country_name']."</option>";
                                }
                            }
                            ?>
                        </select>
                        From City: <select class='form-control'
                                           data-rule-required='true' name='outsource_from_city' id="edit_outsource_from_city" required>
                            <?php echo $car_mapping_data['data'][0]['from_city_option']; ?>
                        </select>
                        To City: <select class='form-control'
                                         data-rule-required='true' name='outsource_to_city' id="edit_outsource_to_city" required><?php echo $car_mapping_data['data'][0]['to_city_option']; ?>
                        </select>
                        Note: Please Provide total price for the trip.<br/>

                        <div  id="transfers_oneway_price">
                            Weekdays Price(Per Km)(Oneway) (AUD) <input type="numeric" name="outsource_weekdays_price_oneway" id="outsource_weekdays_price_oneway"
                                                         data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php 
echo isset($a11)?$a11:$car_mapping_data['data'][0]['weekday_price_oneway'];?>"
                                                         class='form-control add_pckg_elements numeric' required="">
                            <span class="error"><?php echo form_error('outsource_weekdays_price_oneway')?></span>
                            Weekend Price(Per Km)(Oneway) (AUD)<input type="numeric" name="outsource_weekend_price_oneway" id="outsource_weekend_price_oneway"
                                                        data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($b11)?$b11:$car_mapping_data['data'][0]['weekend_price_oneway'];?>"
                                                        class='form-control add_pckg_elements numeric' >
                            <span class="error"><?php echo form_error('outsource_weekend_price_oneway')?></span><br/>
                            
                            Weekdays Price(Per Mile)(Oneway) (AUD)<input type="numeric" name="outsource_weekdays_price_milesoneway" id="outsource_weekdays_price_milesoneway"                                                                                    data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($c11)?$c11:$car_mapping_data['data'][0]['weekdays_price_onewaymile']

                            ;?>"                                                                                    class='form-control add_pckg_elements numeric' required="">
                                                            <span class="error"><?php echo form_error('outsource_weekdays_price_milesoneway')?></span>
                             Weekend Price(Per Mile)(Oneway) (AUD)<input type="numeric" name="outsource_weekend_price_milesoneway" id="outsource_weekend_price_milesoneway"                                                                                        data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($d11)?$d11:$car_mapping_data['data'][0]['weekend_price_onewaymile']
;?>"       class='form-control add_pckg_elements numeric' >
                                                            <span class="error"><?php echo form_error('outsource_weekend_price_milesoneway')?></span>
                        </div>

                    </div>
                </div>

                <?php }
                if($car_mapping_type == 'roundway')
                {
                    // echo "in";die;
                    
//$e11=$car_mapping_data['user_data']['weekend_price_onewaymile'];
//$f11=$car_mapping_data['user_data']['weekend_price_roundway'];

                ?>
                <div class="form-group1">
                    <div class=''>

                        Country: <select class='form-control'
                                         data-rule-required='true' name='outsource_from_country' id="edit_outsource_from_country" required>
                            <option>---Select Country---</option>
                            <?php
                            foreach ($country_list as $key => $value) {
                                if($value['origin'] == $car_mapping_data['data'][0]['from_country']){
                                    echo "<option selected value='".$value['origin']."'>".$value['country_name']."</option>";
                                }else{
                                    echo "<option value='".$value['origin']."'>".$value['country_name']."</option>";
                                }
                            }
                            ?>
                        </select>
                        From City: <select class='form-control'
                                           data-rule-required='true' name='outsource_from_city' id="edit_roundway_from_city" required>
                            <?php echo $car_mapping_data['data'][0]['from_city_option']; ?>
                        </select>
                        To City: <select class='form-control'
                                         data-rule-required='true' name='outsource_to_city' id="edit_roundway_to_city" required><?php echo $car_mapping_data['data'][0]['to_city_option']; ?>
                        </select>
                        Note: Please Provide total price for the trip.<br/>
<?php
$a1=$car_mapping_data['user_data']['weekday_price_perkm'];
$b1=$car_mapping_data['user_data']['weekend_price_perkm'];
$c1=$car_mapping_data['user_data']['weekday_price_permile'];
$d1=$car_mapping_data['user_data']['weekend_price_permile'];
$e1=$car_mapping_data['user_data']['weekday_price_perhr'];
$f1=$car_mapping_data['user_data']['weekend_price_perhr'];
// debug($car_mapping_data['user_data']);die;
/*if($isset($car_mapping_data['user_data']))
{
    $car_mapping_data['data'][0]['weekday_price_perkm']=$car_mapping_data['user_data']['weekday_price_perkm'];
    $car_mapping_data['data'][0]['weekend_price_perkm']=$car_mapping_data['user_data']['weekend_price_perkm'];
    $car_mapping_data['data'][0]['weekday_price_permile']=$car_mapping_data['user_data']['weekday_price_permile'];
    $car_mapping_data['data'][0]['weekend_price_permile']=$car_mapping_data['user_data']['weekend_price_permile'];
    $car_mapping_data['data'][0]['weekday_price_perhr']=$car_mapping_data['user_data']['weekday_price_perhr'];
    $car_mapping_data['data'][0]['weekend_price_perhr']=$car_mapping_data['user_data']['weekend_price_perhr'];
 


    ?>


<div>
                            Weekdays Price(AUD Per Km)<input type="numeric" name="outsource_weekdays_price_roundway_km" id="outsource_weekdays_price_roundway"
                                                         data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo $car_mapping_data['data'][0]['weekday_price_perkm'];?>"
                                                         class='form-control add_pckg_elements numeric' required="">
                            <span class="error"><?php echo form_error('outsource_weekdays_price_roundway')?></span>
                            Weekend Price(AUD Per Km)<input hidden type="numeric" name="outsource_weekend_price_roundway_km" id="outsource_weekend_price_roundway"
                                                        data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo $car_mapping_data['data'][0]['weekend_price_perkm'];?>"
                                                        class='form-control add_pckg_elements numeric' >

                            Weekdays Price(Per Mile)(AUD)<input type="numeric" name="outsource_weekdays_price_roundway_mile" id="outsource_weekdays_price_roundway_mile"                                                                                    data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo $car_mapping_data['data'][0]['weekday_price_permile'];?>"                                                                                    class='form-control add_pckg_elements numeric' required="">
                                                            <span class="error"><?php echo form_error('outsource_weekdays_price_roundway_mile')?></span>
                                                            Weekend Price(Per Mile)(AUD)<input type="numeric" name="outsource_weekend_price_roundway_mile" id="outsource_weekend_price_roundway_mile"                                                                                        data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo $car_mapping_data['data'][0]['weekend_price_permile'];?>"                                                                                        class='form-control add_pckg_elements numeric' >
                                                            <span class="error"><?php echo form_error('outsource_weekend_price_roundway_mile')?></span>


                            Weekdays Price(AUD Per hour)<input type="numeric" name="outsource_weekdays_price_roundway_hr" id="outsource_weekdays_price_roundway_hr"
                                                           data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo $car_mapping_data['data'][0]['weekday_price_perhr'];?>"
                                                           class='form-control add_pckg_elements numeric' required="">
                            <span class="error"><?php echo form_error('outsource_weekdays_price_roundway')?></span>
                            Weekend Price(AUD Per hour)<input hidden type="numeric" name="outsource_weekend_price_roundway_hr" id="outsource_weekend_price_roundway_hr"
                                                          data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo $car_mapping_data['data'][0]['weekend_price_perhr'];?>"
                                                          class='form-control add_pckg_elements numeric' >
                            <span class="error"><?php echo form_error('outsource_weekend_price_round')?></span>
                        </div>

<?php
}
else 
{*/
?>
                        <div>
                            Weekdays Price(AUD Per Km)<input type="numeric" name="outsource_weekdays_price_roundway_km" id="outsource_weekdays_price_roundway"
                                                         data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($a1)?$a1:$car_mapping_data['data'][0]['weekday_price_perkm'];?>"
                                                         class='form-control add_pckg_elements numeric' required="">
                            <span class="error"><?php echo form_error('outsource_weekdays_price_roundway')?></span>
                            Weekend Price(AUD Per Km)<input hidden type="numeric" name="outsource_weekend_price_roundway_km" id="outsource_weekend_price_roundway"
                                                        data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($b1)?$b1:$car_mapping_data['data'][0]['weekend_price_perkm'];?>"
                                                        class='form-control add_pckg_elements numeric' >
                                                        <span class="error"><?php echo form_error('outsource_weekend_price_roundway')?></span>
Weekdays Price(AUD Per Mile)<input type="numeric" name="outsource_weekdays_price_roundway_mile" id="outsource_weekdays_price_roundwaymile"
                                                         data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($c1)?$c1:$car_mapping_data['data'][0]['weekday_price_permile'];?>"
                                                         class='form-control add_pckg_elements numeric' required="">
                            <span class="error"><?php echo form_error('outsource_weekdays_price_roundwaymile')?></span>
                            Weekend Price(AUD Per Mile)<input hidden type="numeric" name="outsource_weekend_price_roundway_mile" id="outsource_weekend_price_roundwaymile"
                                                        data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($d1)?$d1:$car_mapping_data['data'][0]['weekend_price_permile'];?>"
                                                        class='form-control add_pckg_elements numeric' >
<span class="error"><?php echo form_error('outsource_weekend_price_roundwaymile')?></span>
                           
                            Weekdays Price(AUD Per hour)<input type="numeric" name="outsource_weekdays_price_roundway_hr" id="outsource_weekdays_price_roundway_hr"
                                                           data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($e1)?$e1:$car_mapping_data['data'][0]['weekday_price_perhr'] ;?>"
                                                           class='form-control add_pckg_elements numeric' required="">
                            <span class="error"><?php echo form_error('outsource_weekdays_price_roundway')?></span>
                            Weekend Price(AUD Per hour)<input hidden type="numeric" name="outsource_weekend_price_roundway_hr" id="outsource_weekend_price_roundway_hr"
                                                          data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($f1)?$f1:$car_mapping_data['data'][0]['weekend_price_perhr'];?>"
                                                          class='form-control add_pckg_elements numeric' >
                            <span class="error"><?php echo form_error('outsource_weekend_price_round')?></span>
                        </div>
                        <?php


                   // }
                    ?>

                    </div>
                </div>
                <?php
                }
                ?>

                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"></label>
                    <div class="col-sm-5">
                        <button type="submit" class="btn btn-success">update Car Mapping</button>
                    </div>
                </div>

                </form>
            </div

            <?php } ?>
        </div>
    </div>


    <!-- PANEL BODY END -->
</div>
<!-- PANEL END -->
</div>

<div id="view_price_modal" class="modal fade" role="dialog">
    <div class="modal-dialog" id="show_price">
    </div>
</div>

<?php

function get_table_transfers($table_data='')
{
    $total_row_count = 0;
    if($table_data){
        $total_row_count = count($table_data);

    }
    $table = '';
//    $this->load->library('pagination');
//    if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
//    $config['base_url'] = base_url().'index.php/car_mapping';
//    $config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
//    $config['total_rows'] = $total_row_count;
//    $config['per_page'] = RECORDS_RANGE_1;
//    $this->pagination->initialize($config);
//    $pagination = $this->pagination->create_links();
//
//
//    $table .= $pagination;
    $table .= '
   <div class="table-responsive">
   <table class="table table-hover table-striped table-bordered table-condensed" id="car_table">';
    $table .= '<thead><tr>
   <th>SNo</th>
   <th>Car Name</th>
   <th>Duration From</th>   
   <th>duration To</th>
   <th>City</th>
   <th>Weekend Price(AUD/Km)</th>
   <th>Weekday Price(AUD/Km)</th>
   <th>Weekday Price(AUD/Mile)</th>
   <th>Weekend Price(AUD/Mile)</th>
   
   <th>Status</th>
   <th>Action</th>
   </tr></thead><tbody>';

    if (valid_array($table_data) == true) {


        $current_record = 0;
        foreach ($table_data['data'] as $k => $v) {
            //debug($v);
            //echo $GLOBALS['CI']->template->domain_uploads_car($v['icon']);

            $action = '';

            $action .=get_edit_button($v['transfers_id'],'transfers');

            $table .= '<tr>
			<td>'.(++$current_record).'</td>
			<td>'.$v['car_name'].'</td>
			<td>'.$v['duration_from'].'</td>			
			<td>'.$v['duration_to'].'</td>
			<td>'.$v['from_city'].'</td>
			<td>'.$v['weekday_price'].'</td>
			<td>'.$v['weekend_price'].'</td>
            <td>'.$v['weekday_pricemile'].'</td>
            <td>'.$v['weekend_pricemile'].'</td>
			<td>'.get_status_toggle_button($v['status'], $v['transfers_id'],'transfers').'</td>
			<td>'.$action.'</td>
			</tr>';

        }
    } else {
        $table .= '<tr><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td></tr>';
    }
    $table .= '</tbody></table></div>';

    return $table;
}

function get_table_localuse($table_data='')
{
    // debug($table_data);die;
    $total_row_count = 0;
    if($table_data){
        $total_row_count = count($table_data);

    }
    $table = '';
//    $this->load->library('pagination');
//    if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
//    $config['base_url'] = base_url().'index.php/car_mapping';
//    $config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
//    $config['total_rows'] = $total_row_count;
//    $config['per_page'] = RECORDS_RANGE_1;
//    $this->pagination->initialize($config);
//    $pagination = $this->pagination->create_links();
//
//
//    $table .= $pagination;
    $table .= '
   <div class="table-responsive">
   <table class="table table-hover table-striped table-bordered table-condensed" id="car_table">';
    $table .= '<thead><tr>
   <th>SNo</th>
   <th>Car Name</th>
   <th>Duration From</th>   
   <th>duration To</th>
   <th>City</th>
   <th>Price Type</th>
   <th class="text-center">Weekend Price(AUD)</th>
   <th class="text-center">Weekday Price(AUD)</th>
   <th>Status</th>
   <th>Action</th>
   </tr></thead><tbody>';

    if (valid_array($table_data) == true) {

        $current_record = 0;
        foreach ($table_data['data'] as $k => $v) {
            //debug($v);
            //echo $GLOBALS['CI']->template->domain_uploads_car($v['icon']);

            $action = '';

            $action .=get_edit_button($v['localuse_id'],'localuse');

            $table .= '<tr>
			<td>'.(++$current_record).'</td>
			<td>'.$v['car_name'].'</td>
			<td>'.$v['duration_from'].'</td>			
			<td>'.$v['duration_to'].'</td>
			<td>'.$v['from_city'].'</td>
			<td>'.ucfirst($v['price_type']).'</td>';

            if($v["price_type"] == "fixed"){
                $table .= '<td class="text-center">'.$v['fixed_wkend_price'].' (AUD/2Hr)</td>
                           <td class="text-center">'.$v['fixed_wkday_price'].' (AUD/2Hr)</td>';
             }

            if($v["price_type"] == "variable"){
                $table .= '<td class="text-center"><button data-type="wkend" data-id="'.$v['localuse_id'].'" type="button" class="btn view-price"><span class="glyphicon glyphicon-eye-open"> View </span></button></td>
                <td class="text-center"><button data-type="wkday" data-id="'.$v['localuse_id'].'" type="button" class="btn view-price"><span class="glyphicon glyphicon-eye-open"> View </span></button></td>';
			}

if($v["price_type"] == "mileprice"){
                $table .= '<td class="text-center"><button data-type="drive_wkend" data-id="'.$v['localuse_id'].'" type="button" class="btn view-price"><span class="glyphicon glyphicon-eye-open"> View </span></button></td>
                <td class="text-center"><button data-type="drive_wkday" data-id="'.$v['localuse_id'].'" type="button" class="btn view-price"><span class="glyphicon glyphicon-eye-open"> View </span></button></td>';
            }
            if($v["price_type"] == "kmprice"){
                $table .= '<td class="text-center"><button data-type="drive_mwkend" data-id="'.$v['localuse_id'].'" type="button" class="btn view-price"><span class="glyphicon glyphicon-eye-open"> View </span></button></td>
                <td class="text-center"><button data-type="drive_mwkday" data-id="'.$v['localuse_id'].'" type="button" class="btn view-price"><span class="glyphicon glyphicon-eye-open"> View </span></button></td>';
            }

            

			$table .= '<td>'.get_status_toggle_button($v['status'], $v['localuse_id'],'localuse').'</td>
			<td>'.$action.'</td>
			</tr>';

        }
    } else {
        $table .= '<tr><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td></tr>';
    }
    $table .= '</tbody></table></div>';

    return $table;
}

function get_table_outstation($table_data='')
{
    $total_row_count = 0;
    if($table_data){
        $total_row_count = count($table_data);

    }
    $table = '';
//    $this->load->library('pagination');
//    if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
//    $config['base_url'] = base_url().'index.php/car_mapping';
//    $config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
//    $config['total_rows'] = $total_row_count;
//    $config['per_page'] = RECORDS_RANGE_1;
//    $this->pagination->initialize($config);
//    $pagination = $this->pagination->create_links();
//
//
//    $table .= $pagination;
    $table .= '
   <div class="table-responsive">
   <table class="table table-hover table-striped table-bordered table-condensed" id="car_table">';
    $table .= '<thead><tr>
   <th>SNo</th>
   <th>Car Name</th>
   <th>Duration From</th>   
   <th>duration To</th>
   <th>From City</th>
   <th>To City</th>
   <th>OneWay Weekend Price(Per Km)(AUD)</th>
   <th>OneWay Weekday Price(Per Km)(AUD)</th>
   <th>OneWay Weekend Price(Per Mile)(AUD)</th>
   <th>OneWay Weekday Price(Per Mile)(AUD)</th>
   <th>Status</th>
   <th>Action</th>
   </tr></thead><tbody>';

    if (valid_array($table_data) == true) {

        $current_record = 0;
        foreach ($table_data['data'] as $k => $v) {
            //debug($v);
            //echo $GLOBALS['CI']->template->domain_uploads_car($v['icon']);
            $action = '';

            $action .=get_edit_button($v['outstation_id'],'outstation');

            $table .= '<tr>
			<td>'.(++$current_record).'</td>
			<td>'.$v['car_name'].'</td>
			<td>'.$v['duration_from'].'</td>			
			<td>'.$v['duration_to'].'</td>
			<td>'.$v['from_city'].'</td>
			<td>'.$v['to_city'].'</td>
			<td>'.$v['weekend_price_oneway'].'</td>
			<td>'.$v['weekday_price_oneway'].'</td>
            <td>'.$v['weekday_price_onewaymile'].'</td>
            <td>'.$v['weekend_price_onewaymile'].'</td>
			<td>'.get_status_toggle_button($v['status'], $v['outstation_id'],'outstation').'</td>
			<td>'.$action.'</td>
			</tr>';

        }
    } else {
        $table .= '<tr><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td></tr>';
    }
    $table .= '</tbody></table></div>';

    return $table;
}

function get_table_roundway($table_data='')
{
    $total_row_count = 0;
    if($table_data){
        $total_row_count = count($table_data);

    }
    $table = '';
//    $this->load->library('pagination');
//    if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
//    $config['base_url'] = base_url().'index.php/car_mapping';
//    $config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
//    $config['total_rows'] = $total_row_count;
//    $config['per_page'] = RECORDS_RANGE_1;
//    $this->pagination->initialize($config);
//    $pagination = $this->pagination->create_links();
//
//
//    $table .= $pagination;
    $table .= '
   <div class="table-responsive">
   <table class="table table-hover table-striped table-bordered table-condensed" id="car_table">';
    $table .= '<thead><tr>
   <th>SNo</th>
   <th>Car Name</th>
   <th>Duration From</th>   
   <th>Duration To</th>
   <th>From City</th>
   <th>To City</th>
   <th>Weekend Price(AUD Per Km)</th>
   <th>Weekday Price(AUD Per Km)</th>
   <th>Weekend Price(AUD Per Mile)</th>
   <th>Weekday Price(AUD Per Mile)</th>
   <th>Weekend Price(AUD Per Hr)</th>
   <th>Weekday Price(AUD Per Hr)</th>
   <th>Status</th>
   <th>Action</th>
   </tr></thead><tbody>';

    if (valid_array($table_data) == true) {

        $current_record = 0;
        foreach ($table_data['data'] as $k => $v) {
            //debug($v);
            //echo $GLOBALS['CI']->template->domain_uploads_car($v['icon']);
            $action = '';

            $action .=get_edit_button($v['outstation_id'],'roundway');

            $table .= '<tr>
			<td>'.(++$current_record).'</td>
			<td>'.$v['car_name'].'</td>
			<td>'.$v['duration_from'].'</td>			
			<td>'.$v['duration_to'].'</td>
			<td>'.$v['from_city'].'</td>
			<td>'.$v['to_city'].'</td>
			<td>'.$v['weekend_price_perkm'].'</td>
			<td>'.$v['weekday_price_perkm'].'</td>
            <td>'.$v['weekend_price_permile'].'</td>
            <td>'.$v['weekday_price_permile'].'</td>
			<td>'.$v['weekend_price_perhr'].'</td>
			<td>'.$v['weekday_price_perhr'].'</td>
			<td>'.get_status_toggle_button($v['status'], $v['outstation_id'],'roundway').'</td>
			<td>'.$action.'</td>
			</tr>';

        }
    } else {
        $table .= '<tr><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td></tr>';
    }
    $table .= '</tbody></table></div>';

    return $table;
}

function get_status_label($status)
{
    if (intval($status) == ACTIVE) {
        return '<span class="label label-success"><i class="fa fa-circle-o"></i> '.get_enum_list('status', ACTIVE).'</span>
	<a role="button" href="" class="hide">'.get_app_message('AL0021').'</a>';
    } else {
        return '<span class="label label-danger"><i class="fa fa-circle-o"></i> '.get_enum_list('status', INACTIVE).'</span>
		<a role="button" href="" class="hide">'.get_app_message('AL0020').'</a>';
    }
}

function get_status_toggle_button($status, $id, $table)
{
    $status_options = get_enum_list('status');
    if($table == 'transfers')
        $table_name = 'car_crs_transfers';
    if($table == 'localuse')
        $table_name = 'car_crs_localuse';
    if($table == 'outstation')
        $table_name = 'car_crs_outstation';
    if($table == 'roundway')
        $table_name = 'car_crs_roundway';
    return '<select class="toggle-user-status" data-table="'.$table_name.'" data-origin="'.$id.'" >'.generate_options($status_options, array($status)).'</select>';
    /*if (intval($status) == INACTIVE) {
        return '<a role="button" href="'.base_url().'user/activate_account/'.$user_id.'/'.$uuid.'" class="text-success">Activate</a>';
    } else {
        return '<a role="button" href="'.base_url().'user/deactivate_account/'.$user_id.'/'.$uuid.'" class="text-danger">Deactivate</a>';
    }*/
}

function get_edit_button($id,$type)
{
    return '<a role="button" href="'.base_url().'index.php/car_mapping/update_car_mapping/'.$id.'/'.$type.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
		'.get_app_message('AL0022').'</a>
		';
    /*<a role="button" href="'.base_url().'general/account?uid='.$id.'" class="btn btn-sm">
        <span class="glyphicon glyphicon-zoom-in"></span>'.get_app_message('AL0023').'</a>*/
}

function feature_list($feature_list='', $sel_id=array())
{
    $resp = '';
    if (is_array($feature_list) == true) {
        $resp .= '<tr><td colspan=4><div class="col-md-3">';

        $int=0;
        foreach ($feature_list as $k => $v) {
            if (in_array($v['id'], $sel_id)) {
                $checked = 'checked="true"';
            } else {
                $checked = '';
            }

            if($int%5==0 && $int!=0)
                $resp .='</div><div class="col-md-3">';

            $resp .= ' <input type="checkbox" '.$checked.' name="feature_id[]" value="'.$v['id'].'" id="cb'.$v['id'].'" />';
            $resp .= ' <label for="cb'.$v['id'].'">'.$v['feature_name'].'</label><br>';

            $int++;
        }
        $resp .= '</div></td></tr>';
    } else {
        $resp .= '<tr>No Data Found</tr>';
    }
    return $resp;
}
?>

<!-- Page Ends Here -->
<!--Load Js-->
<script src="<?php echo base_url(); ?>hotel_assets/js/gsap/main-gsap.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/store.min.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/joinable.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/resizeable.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/provab-login.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/provab-api.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/jquery-idleTimeout.js"></script>

<script src="<?php echo base_url(); ?>hotel_assets/js/provab-custom.js"></script>

<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap-switch.min.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/fileinput.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/jquery.bootstrap.wizard.min.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/ckeditor/adapters/jquery.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap-datepicker.js"></script>

<script src="<?php echo base_url(); ?>hotel_assets/js/select2/select2.min.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/jquery-ui.js"></script>

<script src="<?php echo base_url(); ?>hotel_assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/datatables/TableTools.min.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/datatables/jquery.dataTables.columnFilter.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/datatables/lodash.min.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/datatables/responsive/js/datatables.responsive.js"></script>
<!--    <script src="<?= base_url(); ?>assets/js/plugins/datatables/dataTables.overrides.js" type="text/javascript"></script>
    <script src="<?= base_url(); ?>assets/js/plugins/lightbox/lightbox.min.js" type="text/javascript"></script>
 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css" type="text/css">-->

<script src="<?php echo base_url(); ?>hotel_assets/js/daterangepicker/moment.min.js"></script>
<script src="<?php echo base_url(); ?>hotel_assets/js/daterangepicker/daterangepicker.js"></script>

<script>
    $(document).ready(function(e){
var opt = [];
        var localuse_price_type = '<?= $car_mapping_data['data'][0]['price_type'] ?>';
// alert(localuse_price_type);
        if(localuse_price_type == 'fixed')
            $('#local_fixed').show();
        else if(localuse_price_type == 'variable')
            $('#local_var').show();
        else if(localuse_price_type == 'kmprice')
            $('#km_drives').show();
        else
            $('#miles_price').show();


        $('input[name="bn"]').change(function() {
            if($(this).is(':checked') && $(this).val() == '0') {
                $('#per_wkday').val('');
                $('#per_wkend').val('');
                $('#variable_price').modal('show');
            }
            if($(this).is(':checked') && $(this).val() == '1') {
                $('.var_price').val('');
                $('#fixed_price').modal('show');
            }
             if($(this).is(':checked') && $(this).val() == '3' )  {
               // $('.var_price').val('');
                $('#km_driveprice').modal('show');
            }
            if($(this).is(':checked') && $(this).val() == '2' )  {
               // $('.var_price').val('');
                $('#m_driveprice').modal('show');
            }
        });
var op=new Array();
        $('input[name="change_type"]').change(function() {

            if( $(this).val() == 'fixed') {
               // alert('fixed');
                opt1=$(this).val();
                op.push(opt1);
 // alert(op);
                if($(this).prop('checked')){
                    $('#local_fixed').show();
                    $('#price_type').val('variable');
                    $('#edit_price').val('variable');
                    $('#local_var').hide();
                    $('#km_drives').hide();
                    $('#miles_price').hide();
                }
                                

            }

            if( $(this).val() == 'variable') {
                 opt2=$(this).val();
                 op.push(opt2);
                  // alert(op);
                if($(this).prop('checked')){
                    $('#local_var').show();
                    $('#price_type').val('fixed');
                    $('#edit_price').val('fixed');
                    $('#local_fixed').hide();
                    $('#km_drives').hide();
                    $('#miles_price').hide();
                }
                
            }
            if($(this).val() == 'mileprice')
                {
                    opt3=$(this).val();
                    op.push(opt3);
                     // alert(op);

if($(this).prop('checked')){
                    $('#local_var').hide();
                     $('#local_fixed').hide();
                     $('#km_drives').hide();
                      $('#miles_price').show();
                   
                }
                                  
                     
                }
                if ($(this).val() == 'kmprice')
                {
                     opt4=$(this).val();
                   op.push(opt4);
                      // alert(op);
                     $('#local_var').hide();
                     $('#local_fixed').hide();
                     $('#km_drives').show();
                      $('#miles_price').hide();
                   
                }
              var v=$('#price_type').val(op);
               // alert(v);
            v=$('#price_type').val();
            // alert(v);
        });

        $(document).on("click", '#edit_price', function () {

            if($(this).val() == 'fixed'){
                $('#edit_fixed_price').modal('show');
            }

            if($(this).val() == 'variable'){
                $('#edit_variable_price').modal('show');
            }

        });

        $(document).on("click", ".view-price", function () {

            var price_type = $(this).attr("data-type");
            var id = $(this).attr("data-id");
            // alert(price_type);
            // alert(id);
if(price_type=='drive_wkend' || price_type=='drive_wkday')
{
    $.ajax({
                url:app_base_url+'index.php/car_mapping/get_localuse_mileprice',
                type: "post",
                dataType: 'html',
                data: {price_type: price_type, id: id},
                success:function(result){

                    $('#show_price').html(result);
                    $('#view_price_modal').modal('show');
                }
            });
}
else if(price_type=='drive_mwkend' || price_type=='drive_mwkday')
{
    $.ajax({
                url:app_base_url+'index.php/car_mapping/get_localuse_kmprice',
                type: "post",
                dataType: 'html',
                data: {price_type: price_type, id: id},
                success:function(result){

                    $('#show_price').html(result);
                    $('#view_price_modal').modal('show');
                }
            });
}

else
{
            $.ajax({
                url:app_base_url+'index.php/car_mapping/get_localuse_price',
                type: "post",
                dataType: 'html',
                data: {price_type: price_type, id: id},
                success:function(result){

                    $('#show_price').html(result);
                    $('#view_price_modal').modal('show');
                }
            });
}
        });

        $('.toggle-user-status').on('change', function(e) {
            e.preventDefault();
            var _user_status = this.value;
            var _opp_url = app_base_url+'index.php/car_mapping/';
            if (parseInt(_user_status) == 1) {
                _opp_url = _opp_url+'active_car_mapping/';
            } else {
                _opp_url = _opp_url+'deactive_car_mapping/';
            }
            _opp_url = _opp_url+$(this).data('table')+'/'+$(this).data('origin');
            toastr.info('Please Wait!!!');
            $.get(_opp_url, function() {
                toastr.info('Updated Successfully!!!');
            });
        });

        $('#car_details_id').change(function(){

            if($("#local_use").is(":checked")) {
                $("#local_use").trigger("change");
            }
            if($("#out_station").is(":checked")) {
                $("#out_station").trigger("change");
            }
            if($("#transfers").is(":checked")) {
                $("#transfers").trigger("change");
            }
        });

        $('#car_duration').change(function(){

            if($("#local_use").is(":checked")) {
                $("#local_use").trigger("change");
            }
            if($("#out_station").is(":checked")) {
                $("#out_station").trigger("change");
            }
            if($("#transfers").is(":checked")) {
                $("#transfers").trigger("change");
            }
        });

        $("#out_station").change(function(e) {

            if($(this).is(":checked")) {
                var car_id = $('#car_details_id').find(":selected").val();

                var duration_id = $('#car_duration').find(":selected").val();

                if(car_id == 0){
                    alert("Please Select a Car");
                    return false;
                }

                if(duration_id == 0 || typeof duration_id === "undefined")
                {
                    alert("Please Select a duration");
                    return false;
                }


                var check_app_type;


                    $.ajax({
                        url: app_base_url+'index.php/car_mapping/check_car_applicable_type/' + car_id +'/outsource',
                        dataType: 'json',
                        success: function(json) {

                            check_app_type = json.length;
                            if(check_app_type == 0 || typeof check_app_type === "undefined"){
                                $('#out_station').prop('checked', false);
                                alert("Car is not applicable for this type");
                                return false;
                            }else{
                                $('#out_station_lable').css('display','');
                                $('#out_station_lable_data').css('display','');
                                $('#add_submit').removeAttr('disabled');
                            }

                        }
                    });



            }else{
                $('#out_station_lable').css('display','none');
                $('#out_station_lable_data').css('display','none');
                $('#add_submit').attr('disabled','disabled');
            }
        });

        $("#local_use").change(function(e) {

            if($(this).is(":checked")) {
$('#klm').show();
                var car_id = $('#car_details_id').find(":selected").val();

                var duration_id = $('#car_duration').find(":selected").val();

                if(car_id == 0){
                    alert("Please Select a Car");
                    return false;
                }

                if(duration_id == 0 || typeof duration_id === "undefined")
                {
                    alert("Please Select a duration");
                    return false;
                }

                duration_id = duration_id.split(',')[0];

                var check_duration;
                var check_app_type;

                $.when(
                    $.ajax({
                        url: app_base_url+'index.php/car_mapping/get_car_mapping_data/' + car_id +'/'+ duration_id + '/localuse',
                        dataType: 'json',
                        success: function(json) {

                             check_duration = json.length;

                        }
                    }),

                    $.ajax({
                        url: app_base_url+'index.php/car_mapping/check_car_applicable_type/' + car_id +'/localuse',
                        dataType: 'json',
                        success: function(json) {

                             check_app_type = json.length;

                        }
                    })
                ).then(function() {

                    if(check_duration > 0 ){
                        alert("Car already mapped for this duration");
                    }
                    else if(check_app_type == 0 || typeof check_app_type === "undefined"){
                        $('#local_use').prop('checked', false);
                        alert("Car is not applicable for this type");
                    }
                    else
                    {
                        $('#local_use_lable').css('display','');
                        // alert('test');
                        $('#charges').show();
                        $('#local_use_lable_data').css('display','');
                        $('#add_submit').removeAttr('disabled');
                    }

                });



            }else{
                $('#local_use_lable').css('display','none');
                $('#local_use_lable_data').css('display','none');
                $('#add_submit').attr('disabled','disabled');
            }

        });

        $("#transfers").change(function(e) {

            if($(this).is(":checked")) {
$('#kll_m').show();
                var car_id = $('#car_details_id').find(":selected").val();

                var duration_id = $('#car_duration').find(":selected").val();

                if(car_id == 0){
                    alert("Please Select a Car");
                    return false;
                }

                if(duration_id == 0 || typeof duration_id === "undefined")
                {
                    alert("Please Select a duration");
                    return false;
                }

                duration_id = duration_id.split(',')[0];

                var check_duration;
                var check_app_type;

                $.when(
                    $.ajax({
                        url: app_base_url+'index.php/car_mapping/get_car_mapping_data/' + car_id +'/'+ duration_id + '/localuse',
                        dataType: 'json',
                        success: function(json) {

                            check_duration = json.length;

                        }
                    }),

                    $.ajax({
                        url: app_base_url+'index.php/car_mapping/check_car_applicable_type/' + car_id +'/transfers',
                        dataType: 'json',
                        success: function(json) {

                            check_app_type = json.length;

                        }
                    })
                ).then(function() {

                    if(check_duration > 0 ){
                        alert("Car already mapped for this duration");
                    }
                    else if(check_app_type == 0 || typeof check_app_type === "undefined"){
                        $('#transfers').prop('checked', false);
                        alert("Car is not applicable for this type");
                    }
                    else
                    {
                        $('#transfers_lable').css('display','');
                         
                        $('#transfers_lable_data').css('display','');
                        $('#add_submit').removeAttr('disabled');
                    }

                });


            }else{
                $('#transfers_lable').css('display','none');

                $('#transfers_lable_data').css('display','none');
                $('#add_submit').attr('disabled','disabled');
            }
        });
        $('#localuse_from_country').on('change', function() {
            // alert('1');
            $.ajax({
                url: 'car_mapping/get_active_city/' + $(this).val(),
                dataType: 'json',
                success: function(json) {
                    if(json.result=='<option value="">Select City</option>'){
                        $('#localuse_from_city').addClass('hide');
                    }
                    else{
                        $('select[name=\'localuse_from_city\']').html(json.result);
                        $('#localuse_from_city').removeClass('hide');
                    }
                }
            });
        });
        $('#transfers_from_country').on('change', function() {
            // alert('2');
            $.ajax({
                url: 'car_mapping/get_active_city/' + $(this).val(),
                dataType: 'json',
                success: function(json) {
                    if(json.result=='<option value="">Select City</option>'){
                        $('#transfers_from_city').addClass('hide');
                    }
                    else{
                        $('select[name=\'transfers_from_city\']').html(json.result);
                        $('#transfers_from_city').removeClass('hide');
                    }
                }
            });
        });
        $('#outsource_from_country').on('change', function() {
// alert('3');
            $.ajax({
                url: 'car_mapping/get_active_city/' + $(this).val(),
                dataType: 'json',
                success: function(json) {

                    if(json.result=='<option value="">Select City</option>'){
                        $('#outsource_from_city').addClass('hide');
                        $('#outsource_to_city').addClass('hide');
                    }
                    else{

                        $('select[name=\'outsource_from_city\']').html(json.result);
                        $('select[name=\'outsource_to_city\']').html(json.result);
                        $('#outsource_from_city').removeClass('hide');
                        $('#outsource_to_city').removeClass('hide');
                    }
                }
            });
        });
function findoption(r)
{

    var c=$('#oneway_transfers').prop('checked');
    var d=$('#roundway_transfers').prop('checked');

    // alert(r);
    // alert(d);
   if(c==true && d==false)
   {
    // alert('test');
    $('#charg').hide();
   if(r=='km_op')
{
    // alert(r);
    $('#charg').hide();
               $('#transfers_oneway_price').removeClass('hide');
               $('#transfers_roundway_price').addClass('hide');
               $('#transfers_oneway_milesprice').addClass('hide');
           }
else if(r=='m_op')
           {
            // alert(r);
             $('#charg').hide();
            $('#transfers_oneway_price').addClass('hide');
             $('#transfers_oneway_milesprice').removeClass('hide');
               $('#transfers_roundway_price').addClass('hide');
           }
       
}
else if(d==true)
{
     // alert(r);
if(r=='km_op')
{
   $('#charg').show();
    $('#roundway_perhr').addClass('hide');
               $('#transfers_roundway_price').removeClass('hide');
                $('#transfers_oneway_price').addClass('hide');
                $('#transfers_roundway_milesprice').addClass('hide');
                
                $('#transfers_oneway_milesprice').addClass('hide');
           }
           else if(r=='m_op')
           {
            $('#charg').show();
            // alert(r);
            $('#roundway_perhr').addClass('hide');
            // $('#roundway_perhrmile').removeClass('hide');
             $('#transfers_roundway_price').addClass('hide');
             $('#transfers_roundway_milesprice').removeClass('hide');
             $('#transfers_oneway_price').addClass('hide');
             $('#transfers_oneway_milesprice').addClass('hide');
           }
}
else
{
    // var c=$('#inlineRadio2').val();
   var c=$("input[name='kmiles_driveme_op']:checked").val();
    // alert(c);
    if(c=='km_op')
    {
   // alert(c);
    $('#drive_pric').show();
   $('.d_perkm').show();
   // $('#d_perkm').show();
    $('.d_permile').hide();
}
    else if(c=='m_op')
    {
        $('#drive_pric').show();
        $('.d_permile').show();
        $('.d_perkm').hide();
    }

}
}

function findoption_airport(r)
{
   // var c=$('#kmiles_op_airport').prop('checked');
   // alert(r);
   
   if(r=='kllm_op')
{
    // alert(r);
               $('.airport_km').removeClass('hide');
               $('.airport_miles').addClass('hide');
               
           }
else if(r=='lm_op')
           {
            // alert(r);
              $('.airport_km').addClass('hide');
               $('.airport_miles').removeClass('hide');
           }
       

}

function findoption_roundway(r)
{
   var c=$('#roundway_transfers').prop('checked');
   // alert(c);
   if(c==true)
   {
   if(r=='perhr')
{
    // alert(r);
               
               $('#roundway_perhr').removeClass('hide');
               $('#klm').hide();

               
           }
else if(r=='perkm')
           {
            // alert(r);
            $('#drive_pric').hide();
            $('#klm').show();
            $('#roundway_perhr').addClass('hide');
              
           }
       
}
else
{
    $("#charg").hide();
    
}
}

function findoption_driveme(r)
{
  
   // var r=$("input[name='charge_type']:checked").val();
    // alert(r);
    if(r=='perhr')
    {
    $("#driveme_hr").show();
    $("#kl_m_drive").hide();
    $('#drive_pric').hide();
                $('.d_permile').hide();
        $('.d_perkm').hide();
    }
    else
    {    
        $("#driveme_hr").hide();
        //$("#charges").hide();
    $("#kl_m_drive").show();

}
}


$('[name="trip_type[]"]').on('change', function() {
     var r=$("input[name='trip_type[]']:checked").val();
     // alert(r);
              findoption(r);
      // handle_active_car_type_way(this.value)
    });
 $('[name="kmiles_op"]').on('change', function() {
     var r=$("input[name='kmiles_op']:checked").val();
              findoption(r);
      // handle_active_car_type_way(this.value)
    });
 $('[name="kmiles_driveme_op"]').on('change', function() {
     var r=$("input[name='kmiles_driveme_op']:checked").val();
              findoption(r);
      // handle_active_car_type_way(this.value)
    });

 $('[name="kmiles_op_airport"]').on('change', function() {
     var r=$("input[name='kmiles_op_airport']:checked").val();
              findoption_airport(r);
      // handle_active_car_type_way(this.value)
    });

 $('[name="charge_type"]').on('change', function() {
     var r=$("input[name='charge_type']:checked").val();
              findoption_roundway(r);
          });
$('[name="charges_type"]').on('change', function() {
     var r=$("input[name='charges_type']:checked").val();
              findoption_driveme(r);
      // handle_active_car_type_way(this.value)
    });
        $('#oneway_transfers').on('change', function() {
            if($('#oneway_transfers').prop('checked') == true ){
                $('#klm').show();
             
             
          
        }else{
                $('#klm').show();
               $('#transfers_oneway_price').addClass('hide');
            }
        });

        $('#roundway_transfers').on('change', function() {
            if($('#roundway_transfers').prop('checked') == true){
                // alert('test');
                $('#charg').show();
               //$('#klm').show();
                
            }else{
               $('#klm').hide();
               
            }
        });
     });
</script>


<script type="text/javascript">
    $(document).ready(function(){
        // $('select').on('change', function() {
        var table = $('#section_table_datatable').DataTable( {
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "destroy": true,
            "order": [
            ],
            "pageLength": 50,
            "ajax": {
                "url":'<?=base_url()?>index.php/car_mapping/car_mapping_list/',
                "type":"POST"
            },
            "columns": [
                {"data": null, "className":"serialnumber" },
                {"data": "car_id" },
                {"data": "duration_from" },
                {"data": "duration_to" },
                {"data": "from_city"},
                {"data": "weekday_price" },
                {"data": "weekend_price" },
                {"data": "status"},
                {"data": null, "searchable": false, "className":"action"}
            ],
            "rowCallback": function( row, data, index ) {
                // console.log(data);
                // return false;
                var settings = table.settings();
                $('td.serialnumber', row).html(settings[0]['_iDisplayStart'] + index +1);

                var actionBtn = '';
                actionBtn += '<div class="btn-group">';
                if(typeof(data.id)!=='undefined'){
                    actionBtn += '<button type="button" class="btn btn-default button_action button_action_edit" data-redirect="'+app_controller_url+'shop/<?=$type?>/update/'+data.id+'/shop_information">Edit</button>';
                }
                actionBtn += '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">';
                actionBtn += '<span class="caret"></span>';
                actionBtn += '<span class="sr-only">Toggle Dropdown</span>';
                actionBtn += '</button>';
                actionBtn += '<ul class="dropdown-menu" role="menu">';
                if(typeof(data.id)!=='undefined'){
                    actionBtn += '<li><a href="#" class="button_action button_action_edit" data-redirect="'+app_controller_url+'shop/<?=$type?>/update/'+data.id+'/information"><i class="fa fa-edit"></i> Edit</a></li>';
                }
                actionBtn += '</ul>';
                actionBtn += '</div>';
                $('td.action', row).html(actionBtn);

                var des_btn = '';
                des_btn = '<div style="text-align:center;"><button type="button" class="btn btn-default" data-toggle="modal" data-idval="'+ data.id +'" id="view_des" data-target="#myModal">View</button></div>';
                $('td.description', row).html(des_btn);
            }
        });

        $(document).on('click delegated', '.button_action', function () {
            var redirect_path = $(this).data('redirect');
            if(typeof(redirect_path)!=='undefined' && (redirect_path !== '')){
                window.location.href = redirect_path;
            }
        });

        // });
    });
</script>

<script>
    $("#wrapper input[type=button]").click(function(){

    });
</script>