<!-- HTML BEGIN -->

<?php



//debug($settings);

//die;

?>

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



$get_url='';

if(isset($GET) && !empty($GET))

{

	$get_url = '/'.base64_encode($GET);

}

 ?>

<div class="bodyContent">

	<div class="panel panel-default clearfix">

		<!-- PANEL WRAP START -->

		<div class="panel-heading">

			<!-- PANEL HEAD START -->

			<div class="panel-title">

				<i class="fa fa-credit-card"></i> Room Rate Management

			</div>

		</div>

		<!-- PANEL HEAD START -->

		

			<!-- PANEL BODY START -->

			<div class="panel-body">

				<form id="room_rate_details11" method="post" action="<?php echo site_url()."/roomrate/add_room_rate".$get_url; ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">

					<fieldset form="user_edit">

				<legend class="form_legend">Add Room Rate</legend>

				<?php if($supplier_rights == 1) { ?>

							<input type="hidden" name="supplier_rights" id="supplier_rights" value="<?php echo $supplier_rights; ?>" />

							<?php } else { ?>

							<input type="hidden" name="supplier_rights" id="supplier_rights" value="" />

							<?php } ?>

								<div class="form-group">

									<label for="field-1" class="col-sm-3 control-label">Hotel</label>									

									<div class="col-sm-5">

										<select <?php if(isset($GET) && !empty($GET)){ echo ' disabled="disabled" readonly="readonly"';}?> id="hotel_details_id" <?php if(isset($GET)){ echo  ' name="hotel_details_id"';}?> onChange="select_room_type(this.value);" class="form-control">

											 <option value="0">Select Hotel</option>

											<?php foreach ($hotels_list as $hotel){ 

												

												if(isset($GET) && !empty($GET))

												{

												?>

													<option <?php if($hotel->hotel_name==str_replace('"','',($GET))){ echo ' selected="selected"'; $selected_hotel_detail_id = $hotel->hotel_details_id; }?> value="<?php echo $hotel->hotel_details_id; ?>" <?php if(isset($hotel_details_id)){ if($hotel_details_id == $hotel->hotel_details_id) { echo "selected"; } } ?> data-iconurl=""><?php echo $hotel->hotel_name; ?></option>

												<?php

												} else {

												?>

													<option value="<?php echo $hotel->hotel_details_id; ?>" <?php if(isset($hotel_details_id)){ if($hotel_details_id == $hotel->hotel_details_id) { echo "selected"; } } ?> data-iconurl=""><?php echo $hotel->hotel_name; ?></option>

												<?php

												}

												?>



												

											<?php } ?>

										</select>

										<?php 

										if(isset($GET) && !empty($GET))

										{

										?>

											<input type="hidden" value="<?=$selected_hotel_detail_id?>" name="hotel_details_id" />

										<?php

										 } 

										?>

									</div>

									<?php echo form_error('hotel_details_id',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

								</div>	

							<div class="form-group">

									<label for="field-1" class="col-sm-3 control-label">Room Type</label>									

									<div class="col-sm-5">

										 <select id="room_details_id" name="room_details_id" onchange="select_season(this.value)" class="form-control">

										 <option value="0">Select Room</option>

										</select>



										<?php echo form_error('room_details_id',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>



										<input type="hidden" name="room_details_id_input" id="room_details_id_input">

										<input type="hidden" name="extra_bed_display" id="extra_bed_display">

									</div>	



								</div>	

								<div class="form-group">

									<label for="field-1" class="col-sm-3 control-label">Seasons</label>									

									<div class="col-sm-5">

										 <select id="seasons_details_id" name="seasons_details_id" onchange="" class="form-control">

										 <option value="0">Select Seasons</option>

										 </select>

										 <?php echo form_error('seasons_details_id',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

									</div>

								</div>						

								

								<div class="form-group">

									<label for="field-1" class="col-sm-3 control-label">Cancellation Policy</label>									

									<div class="col-sm-5">

										 <select id="cancellation_policy" name="cancellation_policy" class="form-control">										 

										 	<option value="1" <?php if(isset($cancellation_policy)){ if($cancellation_policy == '1')   { echo "selected"; } } ?> >Applicable</option>

										 	<option value="0" <?php if(isset($cancellation_policy)){ if($cancellation_policy == '0')   { echo "selected"; } } ?> >Not Applicable</option>										 

										 </select>

										 <?php echo form_error('cancellation_policy',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

									</div>

								</div>	









							<div class="nationality" id="nationality_div">

								<div class='form-group'>

									<label class='control-label col-sm-3' for=''> Nationality

									</label>

									<div>

									<div class='col-sm-3'>

										<select class='form-control' name='country' id="country_list">

										<option value="">--Select Country--</option>

			                                <?php foreach ($country_nationality as $ekey => $evalue) { 

                                            ?>

                                            

                                            <option value='<?=$evalue->country_list?>'><?=$evalue->country_name?></option>

                                            <?php } ?>

                                        </select> 

									</div>

									

									<div class='col-sm-3 hide'>

										 <input type="text" name="" id="currency" placeholder="Currency" class='form-control' readonly>

									</div>



									<div class='col-sm-3 '>

										  <span type="" class="btn btn-primary add_nationality_table" id="nationality_btn" disabled>Add Nationality</span>


									</div>

									</div>

								</div>
								
							</div>	

				<!--		<div class="col-md-5 col-md-offset-3">
								<input type="checkbox" name="default_price_checkbox"  id="default_price_checkbox"  class="" >&nbsp;&nbsp;<span>Default Price</span>
                                </div>
                                <div class="default_price_div">
                                </div>-->


						<div class="price_div">

							<?php 

								foreach ($price_nationality as $n_key => $nationality) {

							?>

							<div class="price_details" data-country="<?=$nationality->country?>" data-currency="<?=$nationality->currency?>" data-country_name="<?=$nationality->country_name?>" >

							  <div class='form-group'>

							

								<table class="table table-bordered table-striped table-highlight">

								    <thead>

								    	<tr>

								    		<th colspan="8"><?=$nationality->country_name?> (<?=$nationality->currency?>)<span class="glyphicon glyphicon-remove pull-right remove_price_table"></span></th>	

								    	</tr>

				                        <tr>

				                          <th>Infant Price/ Person (0-2)</th>

					                          <th>Child Price/ Person (3-18)</th>

					                          <th>Single Adult Price</th>

					                          <th>Double Adult Price</th>

					                          <th>Triple Adult Price</th>

					                         

				                        </tr>

				                    </thead>

				                    <tbody class="price_management_tbody">

				                    	<?php 

				                    		foreach ($price_data as $key => $price_info) {

				                    			if($price_info->country == $nationality->country){

				                    	?>



				                       <tr class="price_management_tr price_management_tr_<?=$nationality->country?>">

				                           <td>

				                           <input type="text" name="price[<?=$key?>][id]" value="<?=$price_info->id?>">

				                          		<input type="text" name="price[<?=$key?>][country]" value="<?=$price_info->country?>">

					                          	<input type="text" name="price[<?=$key?>][country_name]" value="<?=$price_info->country_name?>">



				                            <input type="text" name="price[${count}][child_price_a]" id="child_price_a${country}_${count}"

								data-rule-required='true' placeholder="Display Price"

								class='form-control price_elements' required>

									      </td>

				                            <td>

				                            	 <input type="text" name="price[${count}][child_price_b]" id="child_price_b${country}_${count}"

								data-rule-required='true' placeholder="Display Price"

								class='form-control price_elements' required>

				                          </td>

				                          <td>

				                              <input type="text" name="price[${count}][week_sgl_price]" id="week_sgl_price${country}_${count}"

								data-rule-required='true' placeholder="Display Price"

								class='form-control price_elements' required>

											</td>	

				                          <td>

				                               <input type="text" name="price[${count}][week_dbl_price]" id="week_dbl_price${country}_${count}"

								data-rule-required='true' placeholder="Display Price"

								class='form-control price_elements' required>

				                          </td>

				                          <td>

				                               <input type="text" name="price[${count}][week_trp_price]" id="week_trp_price${country}_${count}"

								data-rule-required='true' placeholder="Display Price"

								class='form-control price_elements' required>

				                          </td>

				                          

				                          

				                        </tr>

				                        

										<?php

											 }		

				                    		}

				                    	?>

				                    	<tr>

				                      

				                      </tr>

				                      </tbody>

				                   </table>

									

								</div>

							</div>

							<?php		

								}

							?>

							

							</div>





						

							

								

								

							</div>

							

						</div>				



								<div class="form-group" style="display:none";>

									<label for="field-1" class="col-sm-3 control-label">Week End Require</label>									

									<div class="col-sm-5">

										 <select id="week_end_select" name="week_end_select" onchange="select_week_end(this.value)" class="form-control">										 

										    <option value="0" <?php if(isset($week_end_select)){ if($week_end_select == '0')  { echo "selected"; } } ?> >No</option>										 

										 	<option value="1" <?php if(isset($week_end_select)){ if($week_end_select == '1')  { echo "selected"; } } ?> >Yes</option>										 	

										 </select>

										 <?php echo form_error('week_end_select',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

									</div>

								</div>						

								

								<div id="promotion">

								<div class="form-group">								

									<label for="field-1" class="col-sm-6 control-label tab_error_color"><p>* Please select country in either Include or Exclude</p><label>											

								</div>	

								 <div class="form-group" >

									<label for="field-1" class="col-sm-3 control-label">Promotion</label>									

									<div class="col-sm-5"> 

										 <input type="text" class="form-control" name="promotion" value="<?php if(isset($promotion)){ echo $promotion; } ?>" maxlength="200" id="promotion" data-validate="required" data-message-required="Please Enter the Promotion" />

										 <?php echo form_error('promotion',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

									</div>

								 </div>								 

								 

								 <div class="form-group">

                                        <label for="field-1" class="col-sm-3 control-label">Country (Include)</label>									

                                        <div class="col-sm-5">                                            

                                            <select name="include_country[]" id="include_country" class="select2" multiple>                                                

                                                <?php for ($c = 0; $c < count($country); $c++) { ?>

                                                    <option value="<?php echo $country[$c]->country_id; ?>" 

                                                    <?php if(isset($include_country)) { if(in_array($country[$c]->country_id, $include_country	)) { echo "selected"; } } ?> data-iconurl=""><?php echo $country[$c]->country_name . " (" . $country[$c]->iso3_code . ")"; ?></option>

                                                <?php } ?>

                                            </select>

                                            <?php echo form_error('include_country', '<span for="field-1" class="validate-has-error">', '</span>'); ?>

                                        </div>                                        

                                </div>  

                                <div class="form-group">

                                        <label for="field-1" class="col-sm-3 control-label">Country (Exclude)</label>									

                                        <div class="col-sm-5">                                            

                                            <select name="exclude_country[]" id="exclude_country" class="select2" multiple>                                                

                                                <?php for ($c = 0; $c < count($country); $c++) { ?>

                                                    <option value="<?php echo $country[$c]->country_id; ?>" 

                                                    <?php if(isset($exclude_country)) { if(in_array($country[$c]->country_id, $exclude_country)){ echo "selected"; } } ?> data-iconurl=""><?php echo $country[$c]->country_name . " (" . $country[$c]->iso3_code . ")"; ?></option>

                                                <?php } ?>

                                            </select>

                                            <?php echo form_error('exclude_country', '<span for="field-1" class="validate-has-error">', '</span>'); ?>

                                        </div>                                        

                                </div>                                 

                               </div> 

<!--                               <div class="form-group">-->

<!--									<label for="field-1" class="col-sm-3 control-label">Date Range</label>									-->

<!--									<div class="col-sm-5">-->

										<!-- <input type="text" class="form-control" name="date_rane_rate" id="date_rane_rate"   data-validate="required" data-message-required="Please Select the Date Range"  /> -->

<!--										 <input readonly type="text" class="form-control" id="date_rane_rate" name="date_rane_rate" value="--><?php //if(isset($date_rane_rate)) { echo $date_rane_rate; } ?><!--" data-validate="required" data-message-required="Please Select the Main Date Range" />-->

<!--										 <!-- <input type="text" class="form-control daterange" id="date_rane_rate" name="date_rane_rate" value="" data-min-date="--><?php //echo date('m/d/Y');?><!--" data-validate="required" data-message-required="Please Select the Main Date Range" /> -->

										 <?php //echo form_error('date_rane_rate',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

<!--									</div>-->

<!--								</div>-->

							<!--	<div class="form-group">

                                    <label for="field-1" class="col-sm-3 control-label">Currency</label>

                                    <div class="col-sm-5">

                                            <select  data-rule-required='true' name='currency' id="currency" data-rule-required='true' class="form-control" required>

                                                <option value="">Choose Currency</option>

                                                <?php



                                                foreach($currency as $currency_key => $currency_value)

                                                {

                                                    echo '<option value="'.$currency_value['country'].'">'.$currency_value['country'].' </option>';

                                                }

                                                ?>

                                            </select>

									</div>

								</div> -->

								<div class="form-group">

									<div class="col-md-3">

										&nbsp;

									</div>

								<!--	<div class="col-sm-3">

										<label class="col-sm-12 control-label" style="text-align: left;">GST</label>		

										<div id="label-switch" class="make-switch" data-on-label="Inclusive" data-off-label="Exclusive" style="">

											<input type="checkbox" name="gst" value="Inclusive" id="gst" <?php if(isset($gst)) { if($gst == "Inclusive") { echo "checked"; } } else { echo "checked"; } ?>>

										</div>

									</div>	-->								

								<!--	<div class="col-sm-3">

										<label class="col-sm-12 control-label" style="text-align: left;">Service Charges</label>		

										<div id="label-switch" class="make-switch" data-on-label="Inclusive" data-off-label="Exclusive" style="">

											<input type="checkbox" name="service_charge" value="Inclusive" id="service_charge" <?php if(isset($service_charge)) { if($service_charge == "Inclusive") { echo "checked"; } } else { echo "checked"; } ?> >

										</div>

									</div>-->



									<!--

									<div class="col-sm-2">

										<label class="col-sm-12 control-label">Status</label>		

										<div id="label-switch" class="make-switch" data-on-label="Active" data-off-label="InActive" style="min-width: 200px;">

											<input type="checkbox" name="status" value="ACTIVE" id="status" checked>

										</div>

									</div>

									-->

									<input type="hidden" class="form-control" maxlength="7" value="0" id="adult_price" name="adult_price" data-validate="required" data-message-required="Please Enter the Adult Price" />

								</div>	

								   <div id="child_group hide">								   

								   </div>



								  <div class="form-group"> 

								  <div id="child_group1">

								   <?php if($settings[0]->child_group_a != ''){ ?>

										<div class="col-sm-2">

											<label for="field-1" class="col-sm-16 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_a ; ?>)</label>									

											<input type="text" class="form-control" maxlength="7" data-rule-number="true" id="child_price_ge_a" value="<?php if(isset($child_price_ge_a)) { echo $child_price_ge_a;} ?>" name="child_price_ge_a" data-message-required="Please Enter Clild Price" />

										</div>

									<?php } ?>

									<?php if($settings[0]->child_group_b != ''){ ?>

										<div class="col-sm-2">

											<label for="field-1" class="col-sm-16 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_b; ?>)</label>									

											 <input type="text" class="form-control" maxlength="7" data-rule-number="true" id="child_price_ge_b" value="<?php if(isset($child_price_ge_b)) { echo $child_price_ge_b; } ?>" name="child_price_ge_b" data-message-required="Please Enter the Child Price" />

										</div>

									<?php } ?>

									<?php if($settings[0]->child_group_c != ''){ ?>

										<div class="col-sm-2">

											<label for="field-1" class="col-sm-16 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_c; ?>)</label>									

											 <input type="text" class="form-control" maxlength="7" data-rule-number="true" id="child_price_ge_c" name="child_price_ge_c" value="<?php if(isset($child_price_ge_c)) { echo $child_price_ge_c;} ?>" data-message-required="Please Enter the Child Price" />

										</div>

									<?php } ?>

									<?php if($settings[0]->child_group_d != ''){ ?>

										<div class="col-sm-2">

											<label for="field-1" class="col-sm-16 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_d; ?>)</label>									

											 <input type="text" class="form-control" maxlength="7" data-rule-number="true" id="child_price_ge_d" name="child_price_ge_d" value="<?php if(isset($child_price_ge_d)) { echo $child_price_ge_d;} ?>"  data-message-required="Please Enter the Child Price" />

										</div>

									<?php } ?>

									<?php if($settings[0]->child_group_e != ''){ ?>

										<div class="col-sm-2">

											<label for="field-1" class="col-sm-16 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_e; ?>)</label>									

											 <input type="text" class="form-control" maxlength="7" data-rule-number="true" id="child_price_ge_e" value="<?php if(isset($child_price_ge_e)) { echo $child_price_ge_e;} ?>" name="child_price_ge_e" data-message-required="Please Enter the Child Price" />

										</div>

									<?php } ?>										

									

								</div>			

							</div>		

								  <!--  <div class="form-group" id="extra_bed_price">

								      <div class="col-sm-2">

										<label for="field-2" class="col-sm-16 control-label">Extra Bed Price for Child </label>									

										<input type="text" class="form-control"  maxlength="7" id="child_extra_bed_price" value="<?php if(isset($child_extra_bed_price)) { echo $child_extra_bed_price;} else{ echo ""; }?>" name="child_extra_bed_price"   data-rule-number='true'/>										 

										<?php echo form_error('child_extra_bed_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

									  </div>

									  <div class="col-sm-2">			 

										<label for="field-2" class="col-sm-16 control-label">Extra Bed Price for Adult </label>									

										<input type="text" class="form-control"  maxlength="7" id="adult_extra_bed_price" name="adult_extra_bed_price" value="<?php if(isset($adult_extra_bed_price)) { echo $adult_extra_bed_price;}else{ echo ""; } ?>"  data-rule-number='true'/>

										<?php echo form_error('adult_extra_bed_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

									  </div>	

									</div>			 -->						

								

																			

																

                                   <!--  <div class="form-group" style="margin: 0">

                                     <label for="field-2" class="col-sm-12 text-center control-label"><h4 style="text-align: center;"><b>Price</b><h4></label>									

                                    </div> -->								  			

                                    <!-- <div class="col-md-3">

                                    	&nbsp;

                                    </div>   -->	

									

								<!-- 	<div class="form-group">	

										<label for="field-2" class="col-sm-3 control-label">Single Adult Price</label>	

										<div class="col-sm-5">									

											<input type="text" class="form-control" id="week_sgl_price" maxlength="7" name="week_sgl_price" value="<?php if(isset($week_sgl_price)) { echo $week_sgl_price;} ?>"  data-rule-number="true" data-validate="required" data-message-required="Please Enter the Single Room Price" />

											<?php echo form_error('week_sgl_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

										</div>

								</div> -->



								<!-- <div class="form-group">	

									

										<label for="field-2" class="col-sm-3 control-label">Adult Breakfast(Include/Exclude)</label>		

									<div class="col-sm-5">								

										<select name="week_single_adult_bf" id="week_single_adult_bf" class="form-control">

											<option value="1" <?php if(isset($week_single_adult_bf)){ if($week_single_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>

										 	<option value="0" <?php if(isset($week_single_adult_bf)){ if($week_single_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>

								</div>-->



								<!--<div class="form-group">	

									

										<label for="field-2" class="col-sm-3 control-label">Child Breakfast(Include/Exclude)</label>		

								    <div class="col-sm-5">									

										<select name="week_single_child_bf" id="week_single_child_bf" class="form-control">

											<option value="1" <?php if(isset($week_single_child_bf)){ if($week_single_child_bf == "1"){ echo "selected"; } } ?>>Include</option>

										 	<option value="0" <?php if(isset($week_single_child_bf)){ if($week_single_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>

									 </div>-->

									<!-- <div class="col-sm-2">

										<label for="field-2" class="col-sm-16 control-label">Pax Adult</label>									

										  <select name="single_room_adult"   id="adult">

													<?php for($ad =1; $ad < $room_list[0]->adult; $ad++){ ?>

														 <option value="<?php echo $ad+1; ?>"><?php echo $ad+1; ?></option>

													<?php } ?>

											 </select>

									</div>

									<div class="col-sm-1">

										<label for="field-2" class="col-sm-16 control-label">Pax Child</label>									

										  <select name="single_room_child"   id="child">

													<?php for($ad =1; $ad < $room_list[0]->adult; $ad++){ ?>

														 <option value="<?php echo $ad+1; ?>"><?php echo $ad+1; ?></option>

													<?php } ?>

											 </select>

									</div>	 -->																

								 

								  <div class="form-group" style="margin: 0">				

								 	

								<!--  <div class="form-group">		

										<label for="field-1" class="col-sm-3 control-label">Double Adult Price</label>		

								 <div class="col-sm-5">									

										<input type="text" class="form-control" id="week_dbl_price" value="<?php if(isset($week_dbl_price)) { echo $week_dbl_price; } ?>" maxlength="7" name="week_dbl_price" data-rule-number="true" data-validate="required" data-message-required="Please Enter the Double Room Price" />

										<?php echo form_error('week_dbl_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

									 </div>

							    </div> -->

							    

							    <!--<div class="form-group">			 

									

										<label for="field-2" class="col-sm-3 control-label">Adult Breakfast(include/Exclude)</label>	

									<div class="col-sm-5">									

										<select name="week_double_adult_bf" id="week_double_adult_bf" class="form-control">

											<option value="1" <?php if(isset($week_double_adult_bf)){ if($week_double_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>

										 	<option value="0" <?php if(isset($week_double_adult_bf)){ if($week_double_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>

								</div>-->

									

								<!--	<div class="form-group">	

									<label for="field-2" class="col-sm-3 control-label">Child Breakfast(include/Exclude)</label>	

									<div class="col-sm-5">									

										<select name="week_double_child_bf" id="week_double_child_bf" class="form-control">

											<option value="1" <?php if(isset($week_double_child_bf)){ if($week_double_child_bf == "1"){ echo "selected"; } } ?>>Include</option>

										 	<option value="0" <?php if(isset($week_double_child_bf)){ if($week_double_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>

								</div>-->

									<!-- <div class="col-sm-2">

										<label for="field-2" class="col-sm-16 control-label">Pax Adult</label>									

										  <select name="double_room_adult"   id="adult1">

													<?php for($ad =1; $ad < $room_list[0]->adult; $ad++){ ?>

														 <option value="<?php echo $ad+1; ?>"><?php echo $ad+1; ?></option>

													<?php } ?>

											 </select>

									</div>

									<div class="col-sm-1">

										<label for="field-2" class="col-sm-16 control-label">Pax Child</label>									

										  <select name="double_room_child"   id="child1">

													<?php for($ad =1; $ad < $room_list[0]->adult; $ad++){ ?>

														 <option value="<?php echo $ad+1; ?>"><?php echo $ad+1; ?></option>

													<?php } ?>

											 </select>

									</div>	 -->											

								   </div>	

								 <!--  <div class="form-group">	

								 	

										<label for="field-1" class="col-sm-3 control-label">Triple Adult Price</label>		

										 <div class="col-sm-5">							

										<input type="text" class="form-control" id="week_trp_price" value="<?php if(isset($week_trp_price)) { echo $week_trp_price; } ?>" maxlength="7" name="week_trp_price" data-rule-number="true" data-validate="required" data-message-required="Please Enter the Triple Room Price" />

										<?php echo form_error('week_trp_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

									 </div>

									</div> -->



								<!--	<div class="form-group">	

									

										<label for="field-2" class="col-sm-3 control-label">Adult Breakfast(include/Exclude)</label>	

										<div class="col-sm-5">								

										<select name="week_trp_adult_bf" id="week_trp_adult_bf" class="form-control">

											<option value="1" <?php if(isset($week_trp_adult_bf)){ if($week_trp_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>

										 	<option value="0" <?php if(isset($week_trp_adult_bf)){ if($week_trp_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>

								</div>-->

									

								<!--	<div class="form-group">	

										<label for="field-2" class="col-sm-3 control-label">Child Breakfast(include/Exclude)</label>	

									<div class="col-sm-5">									

										<select name="week_trp_child_bf" id="week_trp_child_bf" class="form-control">

											<option value="1" <?php if(isset($week_trp_child_bf)){ if($week_trp_child_bf == "1"){ echo "selected"; } } ?>>Include</option>

										 	<option value="0" <?php if(isset($week_trp_child_bf)){ if($week_trp_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>

								</div>-->

									<!-- <div class="col-sm-2">

										<label for="field-2" class="col-sm-16 control-label">Pax Adult</label>									

										  <select name="triple_room_adult"   id="adult2">

													<?php for($ad =1; $ad < $room_list[0]->adult; $ad++){ ?>

														 <option value="<?php echo $ad+1; ?>"><?php echo $ad+1; ?></option>

													<?php } ?>

											 </select>

									</div>

									<div class="col-sm-1">

										<label for="field-2" class="col-sm-16 control-label">Pax Child</label>									

										  <select name="triple_room_child"   id="child2">

													<?php for($ad =1; $ad < $room_list[0]->adult; $ad++){ ?>

														 <option value="<?php echo $ad+1; ?>"><?php echo $ad+1; ?></option>

													<?php } ?>

											 </select>

									</div>	 -->										

								 

								   <!-- <div class="form-group">									

								 	 <div class="col-sm-2">

										<label for="field-1" class="col-sm-14 control-label">Quad Room Price</label>									

										<input type="text" class="form-control" id="week_quad_price" value="<?php if(isset($week_quad_price)) { echo $week_quad_price; } ?>" maxlength="7" name="week_quad_price" data-rule-number="true" data-validate="required" data-message-required="Please Enter the Quad Room Price" />

										<?php echo form_error('week_quad_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

									 </div>

									<div class="col-sm-3">

										<label for="field-2" class="col-sm-12 control-label">Adult Breakfast(include/Exclude)</label>									

										<select name="week_quad_adult_bf" id="week_quad_adult_bf" class="form-control">

											<option value="1" <?php if(isset($week_quad_adult_bf)){ if($week_quad_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>

										 	<option value="0" <?php if(isset($week_quad_adult_bf)){ if($week_quad_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>

									<div class="col-sm-3">

										<label for="field-2" class="col-sm-12 control-label">Child Breakfast(include/Exclude)</label>									

										<select name="week_quad_child_bf" id="week_quad_child_bf" class="form-control">

											<option value="1" <?php if(isset($week_quad_child_bf)){ if($week_quad_child_bf == "1"){ echo "selected"; } } ?>>Include</option>

										 	<option value="0" <?php if(isset($week_quad_child_bf)){ if($week_quad_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>

																		

								   </div>

								   <div class="form-group">									

								 	 <div class="col-sm-2">

										<label for="field-1" class="col-sm-14 control-label">Penta Room Price</label>									

										<input type="text" class="form-control" id="week_hex_price" value="<?php if(isset($week_hex_price)) { echo $week_hex_price; } ?>" maxlength="7" name="week_hex_price" data-rule-number="true" data-validate="required" data-message-required="Please Enter the Penta Room Price" />

										<?php echo form_error('week_hex_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

									 </div>

									<div class="col-sm-3">

										<label for="field-2" class="col-sm-12 control-label">Adult Breakfast(include/Exclude)</label>									

										<select name="week_hex_adult_bf" id="week_hex_adult_bf" class="form-control">

											<option value="1" <?php if(isset($week_hex_adult_bf)){ if($week_hex_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>

										 	<option value="0" <?php if(isset($week_hex_adult_bf)){ if($week_hex_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>

									<div class="col-sm-3">

										<label for="field-2" class="col-sm-12 control-label">Child Breakfast(include/Exclude)</label>									

										<select name="week_hex_child_bf" id="week_hex_child_bf" class="form-control">

											<option value="1" <?php if(isset($week_hex_child_bf)){ if($week_hex_child_bf == "1"){ echo "selected"; } } ?>>Include</option>

										 	<option value="0" <?php if(isset($week_hex_child_bf)){ if($week_hex_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>

																				

								   </div> -->

									<div class="form-group" style="display:none" >

										<div id="week_bed_room_count">																													

										</div>				

									</div>														

								

								<div id="week_end_panel">									

                                    <div class="form-group">

                                     <label for="field-2" class="col-sm-2 control-label">Week End Price</label>									

                                    </div>					

                                    <div class="form-group">			  							     	

									<div class="col-sm-2">

										<label for="field-2" class="col-sm-16 control-label">Single Adult Price</label>									

										<input type="text" class="form-control" id="weekend_sgl_price" maxlength="7" name="weekend_sgl_price" value="<?php if(isset($weekend_sgl_price)) { echo $weekend_sgl_price; }?>" data-validate="required" data-message-required="Please Enter the Single Room Price" />

										<?php echo form_error('weekend_sgl_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

									</div>

									<div class="col-sm-3">

										<label for="field-2" class="col-sm-16 control-label">Adult Breakfast(include/Exclude)</label>									

										<select name="weekend_single_adult_bf" id="weekend_single_adult_bf" class="form-control">

											<option value="1" <?php if(isset($weekend_single_adult_bf)){ if($weekend_single_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>

										 	<option value="0" <?php if(isset($weekend_single_adult_bf)){ if($weekend_single_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>

									

										<label for="field-2" class="col-sm-3 control-label">Child Breakfast(include/Exclude)</label>

									<div class="col-sm-5">										

										<select name="weekend_single_child_bf" id="weekend_single_child_bf" class="form-control">

											<option value="1" <?php if(isset($weekend_single_child_bf)){ if($weekend_single_child_bf == "1"){ echo "selected"; } } ?> >Include</option>

										 	<option value="0" <?php if(isset($weekend_single_child_bf)){ if($weekend_single_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>										

								  </div>

								  <div class="form-group">	

									<div class="col-sm-2">

										<label for="field-2" class="col-sm-14 control-label">Double Adult Price</label>									

										<input type="text" class="form-control" id="weekend_dbl_price" value="<?php if(isset($weekend_dbl_price)) { echo $weekend_dbl_price; }?>" maxlength="7" name="weekend_dbl_price"  />

										<?php echo form_error('weekend_dbl_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

									</div>

									<div class="col-sm-3">

										<label for="field-2" class="col-sm-16 control-label">Adult Breakfast(include/Exclude)</label>									

										<select name="weekend_double_adult_bf" id="weekend_double_adult_bf" class="form-control">

											<option value="1" <?php if(isset($weekend_double_adult_bf)){ if($weekend_double_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>

										 	<option value="0" <?php if(isset($weekend_double_adult_bf)){ if($weekend_double_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>

									

										<label for="field-2" class="col-sm-3 control-label">Child Breakfast(include/Exclude)</label>		

										<div class="col-sm-5">							

										<select name="weekend_double_child_bf" id="weekend_double_child_bf" class="form-control">

											<option value="1" <?php if(isset($weekend_double_child_bf)){ if($weekend_double_child_bf == "1"){ echo "selected"; } } ?> >Include</option>

										 	<option value="0" <?php if(isset($weekend_double_child_bf)){ if($weekend_double_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>										

								</div>

								<div class="form-group">	

									<div class="col-sm-2">

										<label for="field-2" class="col-sm-14 control-label">Triple Adult Price</label>									

										<input type="text" class="form-control" id="weekend_tpl_price" value="<?php if(isset($weekend_tpl_price)) { echo $weekend_tpl_price; }?>" maxlength="7" name="weekend_tpl_price"  />

										<?php echo form_error('weekend_tpl_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

									</div>

									<div class="col-sm-3">

										<label for="field-2" class="col-sm-16 control-label">Adult Breakfast(include/Exclude)</label>									

										<select name="weekend_triple_adult_bf" id="weekend_triple_adult_bf" class="form-control">

											<option value="1" <?php if(isset($weekend_triple_adult_bf)){ if($weekend_triple_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>

										 	<option value="0" <?php if(isset($weekend_triple_adult_bf)){ if($weekend_triple_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>

								

										<label for="field-2" class="col-sm-3 control-label">Child Breakfast(include/Exclude)</label>			

									<div class="col-sm-5">						

										<select name="weekend_triple_child_bf" id="weekend_triple_child_bf" class="form-control">

											<option value="1" <?php if(isset($weekend_triple_child_bf)){ if($weekend_triple_child_bf == "1"){ echo "selected"; } } ?> >Include</option>

										 	<option value="0" <?php if(isset($weekend_triple_child_bf)){ if($weekend_triple_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>										

								</div>

								<!-- <div class="form-group">	

									<div class="col-sm-2">

										<label for="field-2" class="col-sm-14 control-label">Quad Room Price</label>									

										<input type="text" class="form-control" id="weekend_quad_price" value="<?php if(isset($weekend_quad_price)) { echo $weekend_quad_price; }?>" maxlength="7" name="weekend_quad_price"  />

										<?php echo form_error('weekend_quad_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

									</div>

									<div class="col-sm-3">

										<label for="field-2" class="col-sm-12 control-label">Adult Breakfast(include/Exclude)</label>									

										<select name="weekend_quad_adult_bf" id="weekend_quad_adult_bf" class="form-control">

											<option value="1" <?php if(isset($weekend_quad_adult_bf)){ if($weekend_quad_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>

										 	<option value="0" <?php if(isset($weekend_quad_adult_bf)){ if($weekend_quad_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>

									<div class="col-sm-3">

										<label for="field-2" class="col-sm-12 control-label">Child Breakfast(include/Exclude)</label>									

										<select name="weekend_quad_child_bf" id="weekend_quad_child_bf" class="form-control">

											<option value="1" <?php if(isset($weekend_quad_child_bf)){ if($weekend_quad_child_bf == "1"){ echo "selected"; } } ?> >Include</option>

										 	<option value="0" <?php if(isset($weekend_quad_child_bf)){ if($weekend_quad_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>										

								</div>

								<div class="form-group">	

									<div class="col-sm-2">

										<label for="field-2" class="col-sm-14 control-label">Hex Room Price</label>									

										<input type="text" class="form-control" id="weekend_hex_price" value="<?php if(isset($weekend_hex_price)) { echo $weekend_hex_price; }?>" maxlength="7" name="weekend_hex_price"  />

										<?php echo form_error('weekend_hex_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>

									</div>

									<div class="col-sm-3">

										<label for="field-2" class="col-sm-12 control-label">Adult Breakfast(include/Exclude)</label>									

										<select name="weekend_hex_adult_bf" id="weekend_hex_adult_bf" class="form-control">

											<option value="1" <?php if(isset($weekend_hex_adult_bf)){ if($weekend_hex_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>

										 	<option value="0" <?php if(isset($weekend_hex_adult_bf)){ if($weekend_hex_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>

									<div class="col-sm-3">

										<label for="field-2" class="col-sm-12 control-label">Child Breakfast(include/Exclude)</label>									

										<select name="weekend_hex_child_bf" id="weekend_hex_child_bf" class="form-control">

											<option value="1" <?php if(isset($weekend_hex_child_bf)){ if($weekend_hex_child_bf == "1"){ echo "selected"; } } ?> >Include</option>

										 	<option value="0" <?php if(isset($weekend_hex_child_bf)){ if($weekend_hex_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 

										</select> 	

									</div>										

								</div> -->

								<div class="form-group" style="display:none">	

									<div  id="weekend_bed_room_count">

									</div>									

								</div>	

							</div>	

							<div class="form-group" style="margin-top: 15px;margin-left: 5px;">

							<div class="col-md-2">

								<button type="submit" class="btn btn-success">Add Rate</button>

							</div>
						<!-- 	<div class="col-md-2">

								<button type="reset" id="reset_btn" class="btn btn-primary">Reset</button>

							</div> -->

							</div>

				</form>

			</div>

		

		<!-- PANEL BODY END -->

	</div>

	<!-- PANEL END -->

</div>



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

  <script src="<?php echo base_url(); ?>hotel_assets/js/daterangepicker/moment.min.js"></script>

  <script src="<?php echo base_url(); ?>hotel_assets/js/daterangepicker/daterangepicker.js"></script>

 

 <!--    <script src="<?= base_url(); ?>assets/js/plugins/datatables/dataTables.overrides.js" type="text/javascript"></script>

    <script src="<?= base_url(); ?>assets/js/plugins/lightbox/lightbox.min.js" type="text/javascript"></script>

 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css" type="text/css">-->

  

  

  

<script>

		$(document).ready(function () {  
		   // $('#default_price_checkbox').prop('checked', true);

			$('#date_rane_rate').daterangepicker({

				format: 'DD/MM/YYYY'

        

           });

		});

//$('.default_price_div').show();

      //  $("#default_price_checkbox").change(function()

/*	$("#default_price_checkbox").click(function()
			{
			   // alert("executed");
			if($("#default_price_checkbox").prop('checked') == true)
			{
			//	alert("if");
			$('.default_price_div').show();
				var default_price_table_div="";
	
		var room_details_id_input_val=$('#room_details_id_input').val();
		var extra_bed_display_val=$('#extra_bed_display').val();

		default_price_table_div +=`

		<div class="default_price_details" >

			  <div class='form-group'>

			

				<table class="table table-bordered table-striped table-highlight">

				    <thead>

				    	<tr>

				    		<th colspan="8">{Default} (*Add Price in AED) <span class="glyphicon glyphicon-remove pull-right "></span></th>	
				    	</tr>

                        <tr>
                        <th>Default Nationality</th>

                          <th>Infant Price/ Person (0-2)</th>

                          <th>Child Price/ Person (3-18)</th>

                          <th>Single Adult Price</th>

                          <th>Double Adult Price</th>

                          <th>Triple Adult Price</th>`;



                          if(extra_bed_display_val=='yes')

                          {

                          	 default_price_table_div+=`

                          	 <th>Extra bed Price for Child</th>

                          	 <th>Extra bed price for Adult</th>

                          	 `;

                          }

                          else{

                          }


                     default_price_table_div+=`   </tr>

                    </thead>

                    <tbody >

                       <tr >
                       
                       <td>
                       <select 	class='form-control' name="default_country_name" id="default_country_name">
                        <?php foreach ($country_nationality as $ekey => $evalue)
                        { 

                                            ?>
                                            <option value='<?=$evalue->country_list?>'><?=$evalue->country_name?></option>

                                            <?php } ?>
                       </select>
                       </td>

                           <td>
              
                            <input type="text" name="default_child_price_a" id="default_child_price_a"
								data-rule-required='true' placeholder="Enter Price"
								class='form-control' required onkeypress="return event.charCode >= 48 && event.charCode <= 57">
					      </td>

                            <td>

						 <input type="text" name="default_child_price_b" id="default_child_price_b"
														data-rule-required='true' placeholder="Enter Price"
														class='form-control' required onkeypress="return event.charCode >= 48 && event.charCode <= 57">

							 </td>

                          <td>
 <input type="text" name="default_week_sgl_price" id="default_week_sgl_price"
														data-rule-required='true' placeholder="Enter Price"
														class='form-control' required onkeypress="return event.charCode >= 48 && event.charCode <= 57">

							</td>	

                           <td>
<input type="text" name="default_week_dbl_price" id="default_week_dbl_price"
														data-rule-required='true' placeholder="Enter Price"
														class='form-control' required onkeypress="return event.charCode >= 48 && event.charCode <= 57">

	                          </td>

	                          <td>
<input type="text" name="default_week_trp_price" id="default_week_trp_price"
														data-rule-required='true' placeholder="Enter Price"
														class='form-control' required onkeypress="return event.charCode >= 48 && event.charCode <= 57">

	                          </td>`;



	                          if(extra_bed_display_val=="yes")

	                          {

	                          	 default_price_table_div+=` <div class="extra_bed_price" id="extra_bed_price">

	                          <td>

	    <input type="text" name="default_child_extra_bed_price" id="default_child_extra_bed_price"

									data-rule-required='true' placeholder="Enter Price"

									class='form-control price_elements' required onkeypress="return event.charCode >= 48 && event.charCode <= 57">



	                          </td>

	                          <td>

	                                     

	    <input type="text" name="default_adult_extra_bed_price" id="default_adult_extra_bed_price"

									data-rule-required='true' placeholder="Enter Price"

									class='form-control price_elements' required onkeypress="return event.charCode >= 48 && event.charCode <= 57">

	                          </td>

	                          </div>`;



	                          }

	                          else{


	                          }

                        default_price_table_div+=` </tr>

                      </tbody>

                   </table>

					

				</div>

			</div>`;


			$('.default_price_div').html(default_price_table_div);

			}
			else
			{
				//alert("else");
				$('.default_price_div').hide();
			}

			});	
*/




$('.add_nationality_table').on('click',function(){



		var country = $('#country_list').val();

		var currency = $('#currency').val();

		var country_name = $("#country_list option:selected").text();

		// console.log(country);

		// console.log(country_name);

		// var count = $('.price_management_tr').length +1;

		var price_table_div = '';

		var length = $('.price_details').length;

		var count = $('.price_management_tr').length;

		// alert(count);



		var room_details_id_input_val=$('#room_details_id_input').val();

		var extra_bed_display_val=$('#extra_bed_display').val();

		





		price_table_div +=`

		<div class="price_details" data-country="${country}" data-country_name="${country_name}" data-currency="${currency}">

			  <div class='form-group'>

			

				<table class="table table-bordered table-striped table-highlight">

				    <thead>

				    	<tr>

				    		<th colspan="8">${country_name} (*Add Price in AED) <span class="glyphicon glyphicon-remove pull-right remove_price_table"></span></th>	

				    	</tr>

                        <tr>

                          <th>Infant Price/ Person (0-2)</th>

                          <th>Child Price/ Person (3-18)</th>

                          <th>Single Adult Price</th>

                          <th>Double Adult Price</th>

                          <th>Triple Adult Price</th>`;



                          if(extra_bed_display_val=='yes')

                          {

                          	 price_table_div+=`

                          	 <th>Extra bed Price for Child</th>

                          	 <th>Extra bed price for Adult</th>



                          	 `;



                          }

                          else{



                          }





                        

                     price_table_div+=`   </tr>

                    </thead>

                    <tbody class="price_management_tbody_${length}">

                       <tr class="price_management_tr price_management_tr_${country}">

                           <td>

                                <input type="hidden" name="price[${count}][currency]" id="currency${country}_${count}"

								data-rule-required='true' placeholder="Display Price"

								class='form-control price_elements' required value="${country}">



                            <input type="text" name="price[${count}][child_price_a]" id="child_price_a${country}_${count}"

								data-rule-required='true' placeholder="Enter Price"

								class='form-control price_elements _numeric_only ' required onkeypress="return event.charCode >= 48 && event.charCode <= 57">

					      </td>

                            <td>

                          		  <input type="text" name="price[${count}][child_price_b]" id="child_price_b${country}_${count}"

								data-rule-required='true' placeholder="Enter Price"

								class='form-control price_elements' required onkeypress="return event.charCode >= 48 && event.charCode <= 57">

                          </td>

                          <td>

                               <input type="text" name="price[${count}][week_sgl_price]" id="week_sgl_price${country}_${count}"

								data-rule-required='true' placeholder="Enter Price"

								class='form-control price_elements' required onkeypress="return event.charCode >= 48 && event.charCode <= 57">

							</td>	

                           <td>

	                                <input type="text" name="price[${count}][week_dbl_price]" id="week_dbl_price${country}_${count}"

								data-rule-required='true' placeholder="Enter Price"

								class='form-control price_elements' required onkeypress="return event.charCode >= 48 && event.charCode <= 57">

	                          </td>

	                          <td>

	                            <input type="text" name="price[${count}][week_trp_price]" id="week_trp_price${country}_${count}"

								data-rule-required='true' placeholder="Enter Price"

								class='form-control price_elements' required onkeypress="return event.charCode >= 48 && event.charCode <= 57">

	                          </td>`;



	                          if(extra_bed_display_val=="yes")

	                          {

	                          	 price_table_div+=` <div class="extra_bed_price" id="extra_bed_price">

	                          <td>

	    <input type="text" name="price[${count}][child_extra_bed_price]" id="child_extra_bed_price${country}_${count}"

									data-rule-required='true' placeholder="Enter Price"

									class='form-control price_elements' required onkeypress="return event.charCode >= 48 && event.charCode <= 57">



	                          </td>

	                          <td>

	                                     

	    <input type="text" name="price[${count}][adult_extra_bed_price]" id="adult_extra_bed_price${country}_${count}"

									data-rule-required='true' placeholder="Enter Price"

									class='form-control price_elements' required onkeypress="return event.charCode >= 48 && event.charCode <= 57">

	                          </td>

	                          </div>`;



	                          }

	                          else{



	                          }



	                        

	                        

                      

                        price_table_div+=` </tr>

                                                 

                      </tbody>

                   </table>

					

				</div>

			</div>`;



		// length++;

		// alert(country);

		if(country !='undefined' && country !=''){

		   $('.price_div').append(price_table_div);

		 /* $('#price_shift_to_'+country+'_'+count).timepicker({startTime: '6:00 am',change: checkTimeAmbiquity});

		  $('#price_shift_from_'+country+'_'+count).timepicker({startTime: '6:00 am',change: checkTimeAvailbility});*/

		  $('.price_submit_button').show();

		  var country = $('#country_list').val('');

		  var currency = $('#currency').val('');

		}else{

			alert('Please Select Country');

		}

			//$('.price_management_tbody').append(price_div);

		// }

	});



		$(document).on('click','.remove_price_table',function(){

    		var count = $('.price_details').length;

    		if(count == 1){

    			alert('One price details required');

    		}else{

    		 $(this).closest('.price_details').remove();

    		}

    	});













		$('#country_list').on('change',function(){

	var country_id = $(this).val();

	$('#currency').val('');

	$('.add_nationality_table').attr('disabled','disabled');

	   $.ajax({

            type:"POST",

            url: "<?php echo base_url(); ?>roomrate/get_currency/"+country_id,

            // data:{country:country},

            success:function(data)

            {

             var data = $.parseJSON(data);

             // console.log(data.result.currency_code);

             $('#currency').val(data.result.currency_code);

             $('.add_nationality_table').removeAttr('disabled');

            }

          });



});



		$(function(){				

		   $('#promotion').hide();	

		   $('#extra_bed_price').hide();

		   $('#bed_room_count').hide();

		   $('#week_end_panel').hide();

		   $('#block_out_date_div').hide();



		   $('#child_group1').hide();

			$('input#adult_price,#child_price_a,#child_price_b,#child_price_c,#child_price_d,#child_price_e,#extra_bed_price,#sgl_price,#dbl_price,#tpl_price,#quad_price,#hex_price').keyup(function() {

				var $th = $(this);		

				if($th.val().trim() != ""){

					 var regex = /^[0-9. ]*$/;

					if (regex.test($th.val())) {

						$th.css('border', '1px solid #099A7D');					

					} else {

						// alert("Please use only letters");

						$th.css('border', '1px solid #f52c2c');

						return '';

					}

				}

			});	



			<?php

			 if(isset($week_end_select)){			 	

			 	if($week_end_select  == "1"){  ?>

			 		$('#week_end_panel').show();	

			<?php } else { ?>

				    $('#week_end_panel').hide();					

			<?php } } else { ?>

				$('#week_end_panel').hide();

			<?php  } ?>

			<?php 	  	

	  	 	 if(isset($block_out_date_rane_rate)) {

	  	 		$count = sizeof($block_out_date_rane_rate); 	  	 	

	  	 		if($count >= 1){

		     		for($cp =1; $cp < $count; $cp++) {  	?>		     		      

		      			addMoreRooms1(null);			    		      

		      			$('#block_out_date_rane_rate'+<?php echo $cp; ?>).val("<?php echo $block_out_date_rane_rate[$cp]; ?>");      		  			

			  		    //alert("<?php echo $block_out_date_rane_rate[$cp]; ?>");

			<?php 

	         		}//for

	        	}//if

	       	 }//if  

	    	?>  

			

			var hotel_id = "<?php if(isset($hotel_details_id)) { echo $hotel_details_id; } ?>";

			var room_type_id = "<?php if(isset($room_details_id)) { echo $room_details_id; } ?>";

			var seasons_id = "<?php if(isset($seasons_details_id)) { echo $seasons_details_id; } ?>";					

			if(hotel_id != ""){

				var select = $('#room_details_id');

				$.ajax({

				url:'<?php echo site_url();?>/seasons/get_room_type/'+hotel_id + "/"+ room_type_id,				

				success: function(data, textStatus, jqXHR) {					

					//alert(data);	  

					select.html('');

					select.html(data);						

					select.trigger("chosen:updated");              

				}

			  });	 	

			  var data1 = "";			 

			  var child_price = "<?php echo $child_price; ?>";			  

		  	  $.ajax({

			  url:'<?php echo site_url();?>/hotels/get_child_group/'+hotel_id + '/' +child_price,

			  success: function(data, textStatus, jqXHR) {

				data1 = data;

				if(data1.trim() != ''){

			     $('#child_group').html(data);

			     $('#child_group1').hide();

			    } else {

				 $('#child_group').html("");

			     $('#child_group1').show();

			    }			

		      }

		     });			  	 

			}



			if(room_type_id != ""){

				var select1 = $('#seasons_details_id');

				$.ajax({

				url:'<?php echo site_url();?>/seasons/get_season_room_type/'+room_type_id + "/" + seasons_id,				

				 success: function(data, textStatus, jqXHR) {					

					//alert(data);	  

					select1.html('');

					select1.html(data);						

					select1.trigger("chosen:updated");              

				 }

			    });	 	

			    var data1 = "";

		  	    $.ajax({

			  	url:'<?php echo site_url();?>/roomrate/get_extra_bed_avail/'+room_type_id,

			  	dataType: "json",

			  	 success: function(data, textStatus, jqXHR) {

				  data1 = data.extra_bed;

				  data2 = data.no_of_room;

				  //alert(data2);				  

				  if(data1 == "yes"){

				  	$('#extra_bed_price').show();

				  }

				  else{

				   $('#extra_bed_price').hide();	

				  }				  

				  if(data2 > 0){

				  	var panel = "";

				  	panel     = "<div class='col-sm-2'><label for='tpl_price' class='col-sm-16 control-label'>"+data2 +" Bed Room Price</label>";		

         		    panel    += "<input type='text' class='form-control' id='week_bedroom_price' value='<?php if(isset($week_bedroom_price)) { echo $week_bedroom_price;} ?>' maxlength='5' name='week_bedroom_price'/>";

					panel    += "<?php echo form_error('week_bedroom_price',  '<span for=\"field-1\" class=\"tab_error_color\">', '</span>'); ?></div>";         		    

         		    panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Adult Breakfast(include/Exclude)</label>";									

					panel    +=	"<select name='week_bedroom_adult_bf' id='week_bedroom_adult_bf' class='form-control'>";

					panel    += "<option value='1' <?php if(isset($week_bedroom_adult_bf)){ if($week_bedroom_adult_bf == '1'){ echo 'selected'; }} ?>>Include</option><option value='0' <?php if(isset($week_bedroom_adult_bf)){ if($week_bedroom_adult_bf == '0'){ echo 'selected'; }} ?> >Exclude</option></select></div>";

					panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Child Breakfast(include/Exclude)</label>";									

					panel    +=	"<select name='week_bedroom_child_bf' id='week_bedroom_child_bf' class='form-control'>";

					panel    += "<option value='1' <?php if(isset($week_bedroom_child_bf)){ if($week_bedroom_child_bf == '1'){ echo 'selected'; }} ?> >Include</option><option value='0' <?php if(isset($week_bedroom_child_bf)){ if($week_bedroom_child_bf == '0'){ echo 'selected'; }} ?>  >Exclude</option></select></div>";

				  	$('#week_bed_room_count').html("");

				  	$('#week_bed_room_count').html(panel)

				  	$('#week_bed_room_count').show();



				  	panel = "";

				  	panel     = "<div class='col-sm-2'><label for='tpl_price' class='col-sm-16 control-label'>"+data2 +" Bed Room Price</label>";		

         		    panel    += "<input type='text' class='form-control' id='weekend_bedroom_price' maxlength='5' value='<?php if(isset($weekend_bedroom_price)) { echo $weekend_bedroom_price; } ?>' name='weekend_bedroom_price'/>";

         		    panel    += "<?php echo form_error('weekend_bedroom_price',  '<span for=\"field-1\" class=\"tab_error_color\">', '</span>'); ?></div>";         		    

         		    panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Adult Breakfast(include/Exclude)</label>";									

					panel    +=	"<select name='weekend_bedroom_adult_bf' id='weekend_bedroom_adult_bf' class='form-control'>";

					panel    += "<option value='1' <?php if(isset($weekend_bedroom_adult_bf)){ if($weekend_bedroom_adult_bf == '1'){ echo 'selected'; }} ?> >Include</option><option value='0' <?php if(isset($weekend_bedroom_adult_bf)){ if($weekend_bedroom_adult_bf == '0'){ echo 'selected'; }} ?> >Exclude</option></select></div>";

					panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Child Breakfast(include/Exclude)</label>";									

					panel    +=	"<select name='weekend_bedroom_child_bf' id='weekend_bedroom_child_bf' class='form-control'>";

					panel    += "<option value='1' <?php if(isset($weekend_bedroom_child_bf)){ if($weekend_bedroom_child_bf == '1'){ echo 'selected'; }} ?>  >Include</option><option value='0' <?php if(isset($weekend_bedroom_child_bf)){ if($weekend_bedroom_child_bf == '0'){ echo 'selected'; }} ?> >Exclude</option></select></div>";



				  	$('#weekend_bed_room_count').html("");

				  	$('#weekend_bed_room_count').html(panel)

				  	$('#weekend_bed_room_count').show();

				  }

				  else{

				  	$('#week_bed_room_count').html("");

				  	$('#week_bed_room_count').hide();	

				  	$('#weekend_bed_room_count').html("");

				  	$('#weekend_bed_room_count').hide();	

				  }

		         }

		        });		

			 }//room_type_id



			 <?php if(isset($room_rate_type)){ ?>			     

				$('#block_out_date_div').hide();

				$('#promotion').hide();			



			<?php if($room_rate_type == 2){ ?>

				$('#promotion').show();				

			<?php }

			elseif($room_rate_type == 3){ ?>

				$('#promotion').show();					

				$('#block_out_date_div').show();

			<?php }

			elseif($room_rate_type == 5){ ?>

				$('#block_out_date_div').show();	

			<?php }

			else{ ?>

				$('#promotion').hide();	

				$('#block_out_date_div').hide();

			<?php } } ?>

			

			$('#room_rate_details11').submit(function() {				



				var hotel_type1 = $('#hotel_details_id').val();			

				if(hotel_type1 == "0"){					

					hotel_details_id.style.border = "1px solid #f52c2c";   

					hotel_details_id.focus(); 

					return false; 			

				}





				var hotel_type111 = $('#room_details_id').val();			

				if(hotel_type111 == "" || hotel_type111 == "0" ){					

					room_details_id.style.border = "1px solid #f52c2c";   

					room_details_id.focus(); 

					return false; 			

				}



				var hotel_type11 = $('#seasons_details_id').val();						

				if(hotel_type11 == "" || hotel_type11 == "0"){					

					seasons_details_id.style.border = "1px solid #f52c2c";   

					seasons_details_id.focus(); 

					return false; 			

				}



				var hotel_type11 = $('#room_rate_type').val();						

				if(hotel_type11 == "" || hotel_type11 == "0"){					

					room_rate_type.style.border = "1px solid #f52c2c";   

					room_rate_type.focus(); 

					return false; 			

				}		





				var number_filter1 = /^[0-9 -/]*$/;

				if(date_rane_rate.value != '')

				{

					if(!(date_rane_rate.value.match(number_filter1)))

					{

						date_rane_rate.style.border = "1px solid #f52c2c";   

						date_rane_rate.focus(); 

						return false; 

					}

				}

				else

				{

					date_rane_rate.style.border = "1px solid #f52c2c";   

					date_rane_rate.focus(); 

					return false; 

				}

				

				if(date_rane_rate.value == '' ) {

					    date_rane_rate.style.border = "1px solid #f52c2c";   

						date_rane_rate.focus(); 

						return false; 

				}



				if(date_rane_rate.value.length != 23 ) {

					    date_rane_rate.style.border = "1px solid #f52c2c";   

						date_rane_rate.focus(); 

						return false; 

				}





				// domain Name validation 

				var domain_name = document.getElementById('adult_price');	

				var filter = /^[0-9. ]*$/;

				if(domain_name.value != ''){

					if(!(domain_name.value.match(filter))){

						domain_name.style.border = "1px solid #f52c2c";   

						domain_name.focus(); 

						return false; 

					}

				}else{

					domain_name.style.border = "1px solid #f52c2c";   

					domain_name.focus(); 

					return false; 

				}

				

				var domain_name = document.getElementById('child_price_a');	

				var filter = /^[0-9. ]*$/;

				if(domain_name.value != ''){

					if(!(domain_name.value.match(filter))){

						domain_name.style.border = "1px solid #f52c2c";   

						domain_name.focus(); 

						return false; 

					}

				}else{

					domain_name.style.border = "1px solid #f52c2c";   

					domain_name.focus(); 

					return false; 

				}

				var domain_name = document.getElementById('child_price_b');	

				var filter = /^[0-9. ]*$/;

				if(domain_name.value != ''){

					if(!(domain_name.value.match(filter))){

						domain_name.style.border = "1px solid #f52c2c";   

						domain_name.focus(); 

						return false; 

					}

				}else{

					domain_name.style.border = "1px solid #f52c2c";   

					domain_name.focus(); 

					return false; 

				}

				var domain_name = document.getElementById('child_price_c');	

				var filter = /^[0-9. ]*$/;

				if(domain_name.value != ''){

					if(!(domain_name.value.match(filter))){

						domain_name.style.border = "1px solid #f52c2c";   

						domain_name.focus(); 

						return false; 

					}

				}else{

					domain_name.style.border = "1px solid #f52c2c";   

					domain_name.focus(); 

					return false; 

				}

				var domain_name = document.getElementById('child_price_d');	

				var filter = /^[0-9. ]*$/;

				if(domain_name.value != ''){

					if(!(domain_name.value.match(filter))){

						domain_name.style.border = "1px solid #f52c2c";   

						domain_name.focus(); 

						return false; 

					}

				}else{

					domain_name.style.border = "1px solid #f52c2c";   

					domain_name.focus(); 

					return false; 

				}				

				

				var domain_name5 = document.getElementById('extra_bed_price');	

				var filter = /^[0-9. ]*$/;

				if(domain_name5.value != ''){

					if(!(extra_bed_price.value.match(filter))){

						extra_bed_price.style.border = "1px solid #f52c2c";   

						extra_bed_price.focus(); 

						return false; 

					}

				}else{

					extra_bed_price.style.border = "1px solid #f52c2c";   

					extra_bed_price.focus(); 

					return false; 

				}

				var domain_name6 = document.getElementById('sgl_price');	

				var filter = /^[0-9. ]*$/;

				if(domain_name6.value != ''){

					if(!(sgl_price.value.match(filter))){

						sgl_price.style.border = "1px solid #f52c2c";   

						sgl_price.focus(); 

						return false; 

					}

				}else{

					sgl_price.style.border = "1px solid #f52c2c";   

					sgl_price.focus(); 

					return false; 

				}

				var domain_name7 = document.getElementById('dbl_price');	

				var filter = /^[0-9. ]*$/;

				if(domain_name7.value != ''){

					if(!(dbl_price.value.match(filter))){

						dbl_price.style.border = "1px solid #f52c2c";   

						dbl_price.focus(); 

						return false; 

					}

				}else{

					dbl_price.style.border = "1px solid #f52c2c";   

					dbl_price.focus(); 

					return false; 

				}

				var domain_name8 = document.getElementById('tpl_price');	

				var filter = /^[0-9. ]*$/;

				if(domain_name8.value != ''){

					if(!(tpl_price.value.match(filter))){

						tpl_price.style.border = "1px solid #f52c2c";   

						tpl_price.focus(); 

						return false; 

					}

				}else{

					tpl_price.style.border = "1px solid #f52c2c";   

					tpl_price.focus(); 

					return false; 

				}

				var domain_name9 = document.getElementById('hex_price');	

				var filter = /^[0-9. ]*$/;

				if(domain_name9.value != ''){

					if(!(hex_price.value.match(filter))){

						hex_price.style.border = "1px solid #f52c2c";   

						hex_price.focus(); 

						return false; 

					}

				}

			});			



			

			$('#status').change(function(){

				var current_status = $('#status').val();

				if(current_status == "ACTIVE")

					$('#status').val('INACTIVE');

				else

					$('#status').val('ACTIVE');

			});



			$('#gst_markup').change(function(){

				var gst_markup = $('#gst_markup').val();

				if(gst_markup == "Inclusive")

					$('#gst_markup').val('Exclusive');

				else

					$('#gst_markup').val('Inclusive');

			});

			

			$('#gst').change(function(){

				var gst = $('#gst').val();

				if(gst == "Inclusive")

					$('#gst').val('Inclusive');

				else

					$('#gst').val('Exclusive');

				//alert($('#gst').val());

			});

			

			$('#gst_green_tax').change(function(){

				var gst_green_tax = $('#gst_green_tax').val();

				if(gst_green_tax == "No")

					$('#gst_green_tax').val('Yes');

				else

					$('#gst_green_tax').val('No');

			});

			$('#green_tax').change(function(){

				var green_tax = $('#green_tax').val();

				if(green_tax == "Inclusive")

					$('#green_tax').val('Exclusive');

				else

					$('#green_tax').val('Inclusive');

			});

			

			$('#sc_applicable').change(function(){

				var sc_applicable = $('#sc_applicable').val();

				if(sc_applicable == "No")

					$('#sc_applicable').val('Yes');

				else

					$('#sc_applicable').val('No');

			});

			

			$('#service_charge').change(function(){

				var service_charge = $('#service_charge').val();					

				if(service_charge == "Inclusive")

					$('#service_charge').val('Inclusive');

				else

					$('#service_charge').val('Exclusive');

				//alert($('#service_charge').val());

			});

			$('#room_details_id').change(function(){

				var room_details_id = $('#room_details_id').val();

				$.ajax({

					url:'<?php echo site_url();?>/roomrate/get_extra_bed/'+room_details_id,

					success: function(data, textStatus, jqXHR) { 

						if(data == "Available"){

						$('#extra_bed_price1').show();

						$('#extra_bed_price_total1').show();

						$('#extra_bed_price_total').val(0);

							$('#extra_bed_price').val(0);

						}else{

							$('#extra_bed_price1').hide();

							$('#extra_bed_price_total1').hide();

							$('#extra_bed_price_total').val(0);

							$('#extra_bed_price').val(0);

							}

						

					}

				});

	    

	    	var hotel_id = $('#hotel_details_id').val()

	    	/*

		    $.ajax({

				url:'<?php echo base_url();?>roomrate/get_room_daterange/'+hotel_id+'/'+room_details_id,

				success: function(data, textStatus, jqXHR) {

				 $('#date_rane_rate').val(data);

				 var date_array = data.split(" - ");

				

				 var from_date_array = date_array[0].split("/");

				 var from_date = from_date_array[2]+"-"+from_date_array[1]+"-"+from_date_array[0];

			     var to_date_array = date_array[1].split("/");

				 var todate = to_date_array[2]+"-"+to_date_array[1]+"-"+to_date_array[0];

				  $('#date_rane_rate').daterangepicker({

				       format: 'MM/DD/YYYY',

	                    startDate: from_date,

                        endDate: todate,

                        minDate: from_date,

                        maxDate:  todate,

		     	});

					

				 //~ $('#date_rane_rate').attr('data-min-date',from_date);

				 //~ $('#date_rane_rate').attr('data-max-date',todate);

				 //~ $('input[name="daterangepicker_start"]').val(date_array[0]);

				  //~ $('input[name="daterangepicker_end"]').val(date_array[1]);

				//~ 

				 }

			});

		*/

			

			});

		});

		 function addMoreRooms1() {		 		    	

			$("#extra_date").css({'display':'inherit'});

			var id = $('#rows_cnt').val();

			

	    	$("#extra_date").append('<div class="form-group">'+

	    						  '<label for="field-1" class="col-sm-3 control-label">Block Out Date Range</label>'+								  

								  '<div class="col-sm-5">'+  

								  '<input type="text" class="form-control daterange" id="block_out_date_rane_rate'+id+'" name="block_out_date_rane_rate[]" value="" data-validate="required" data-message-required="Please Select the block out date range" />'+								  

								  '</div>'+

								  '</div>');	

			$('.daterange').daterangepicker();								  							  

			id = parseInt(id)+1;

			$('#rows_cnt').val(id);																



		}



		function removeLastRoom1(v){

			var id = $('#rows_cnt').val();

			$('#extra_date .form-group').last().remove();

			if(id <= 1) {

				$("#extra_date").css({'display':'none'});

			}

			id = parseInt(id)-1;

			$('#rows_cnt').val(id);

		}	



	

		function select_room_type(hotel_id){

			var select = $('#room_details_id');

			if(hotel_id != ""){

				$.ajax({

				url:'<?php echo site_url();?>/seasons/get_room_type/'+hotel_id,				

				success: function(data, textStatus, jqXHR) {					

					//alert(data);	  

					select.html('');

					select.html(data);						

					select.trigger("chosen:updated");              

				}

			  });	 	

			  var data1 = "";

		  	  $.ajax({

			  url:'<?php echo site_url();?>/hotels/get_child_group/'+hotel_id,

			  success: function(data, textStatus, jqXHR) {

				data1 = data;

				if(data1.trim() != ''){

			     $('#child_group').html(data);

			     $('#child_group1').hide();

			    } else {

				 $('#child_group').html("");

			     $('#child_group1').show();

			    }			

		      }

		     });			  	 

			}

			else{

			 	    select.html('');					

					select.trigger("chosen:updated");              

			}

		}



		function slect_rate_type(rate_type){									

			$('#block_out_date_div').hide();

			$('#promotion').hide();					

			if(rate_type == 2){

				$('#promotion').show();				

			}

			else if(rate_type == 3){

				$('#promotion').show();					

				$('#block_out_date_div').show();

			}

			else if(rate_type == 5){

				$('#block_out_date_div').show();	

			}

			else{

				$('#promotion').hide();	

				$('#block_out_date_div').hide();

			}

		}



		function select_week_end(weekend){			

			if(weekend == 0){

				$('#week_end_panel').hide();

			}

			else{

				$('#week_end_panel').show();	

			}

		}



		function select_season(room_type_id){	

			var select = $('#seasons_details_id');

			$('#room_details_id_input').val(room_type_id);

			/*var adult = $('#adult');

			var child = $('#child');

			var adult1 = $('#adult1');

			var child1 = $('#child1');

			var adult2 = $('#adult2');

			var child2 = $('#child2');

			var adult3 = $('#adult3');

			var child3 = $('#child3');

			var adult4 = $('#adult4');

			var child4 = $('#child4');*/

			

			if(room_type_id != ""){

				$.ajax({

				url:'<?php echo site_url();?>/seasons/get_season_room_type/'+room_type_id,

				dataType: "json",			

				 success: function(data, textStatus, jqXHR) {					

					//alert(data);	  

					select.html('');

					select.html(data.options);

					/*adult.html('');

					child.html('');

					adult1.html('');

					child1.html('');

					adult2.html('');

					child2.html('');

					adult3.html('');

					child3.html('');

					adult4.html('');

					child4.html('');

						

					adult.html(data.adult_count);

					child.html(data.child_count);

					adult1.html(data.adult_count);

					child1.html(data.child_count);

					adult2.html(data.adult_count);

					child2.html(data.child_count);

					adult3.html(data.adult_count);

					child3.html(data.child_count);

					adult4.html(data.adult_count);

					child4.html(data.child_count);*/

					select.trigger("chosen:updated");

					

				 }

			    });	 	

			    var data1 = "";

		  	    $.ajax({

			  	url:'<?php echo site_url();?>/roomrate/get_extra_bed_avail/'+room_type_id,

			  	dataType: "json",

			  	 success: function(data, textStatus, jqXHR) {

				  data1 = data.extra_bed;

				  data2 = data.no_of_room;

				 // alert(data1);	

				  $('#extra_bed_display').val(data1);			  

				  if(data1 == "yes"){

				  	$('#extra_bed_price').show();

				  }

				  else{

				   $('#extra_bed_price').hide();	

				  }				  

				  if(data2 > 0){

				  	var panel = "";

				  	panel     = "<div class='col-sm-2'><label for='tpl_price' class='col-sm-16 control-label'>"+data2 +" Bed Room Price</label>";		

         		    panel    += "<input type='text' class='form-control' id='week_bedroom_price' maxlength='5' <?php if(isset($week_bedroom_price)) { echo $week_bedroom_price; } ?> name='week_bedroom_price'/></div>";

         		    panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Adult Breakfast(include/Exclude)</label>";									

					panel    +=	"<select name='week_bedroom_adult_bf' id='week_bedroom_adult_bf' class='form-control'>";

					panel    += "<option value='1' <?php if(isset($week_bedroom_adult_bf)){ if($week_bedroom_adult_bf == '1'){ echo 'selected'; }} ?> >Include</option><option value='0' <?php if(isset($week_bedroom_adult_bf)){ if($week_bedroom_adult_bf == '0'){ echo 'selected'; }} ?>>Exclude</option></select></div>";

					panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Child Breakfast(include/Exclude)</label>";									

					panel    +=	"<select name='week_bedroom_child_bf' id='week_bedroom_child_bf' class='form-control'>";

					panel    += "<option value='1' <?php if(isset($week_bedroom_child_bf)){ if($week_bedroom_child_bf == '1'){ echo 'selected'; }} ?> >Include</option><option value='0' <?php if(isset($week_bedroom_child_bf)){ if($week_bedroom_child_bf == '0'){ echo 'selected'; }} ?>>Exclude</option></select></div>";

				  	$('#week_bed_room_count').html("");

				  	$('#week_bed_room_count').html(panel)

				  	$('#week_bed_room_count').show();



				  	panel = "";

				  	panel     = "<div class='col-sm-2'><label for='tpl_price' class='col-sm-16 control-label'>"+data2 +" Bed Room Price</label>";		

         		    panel    += "<input type='text' class='form-control' id='weekend_bedroom_price' maxlength='5' <?php if(isset($weekend_bedroom_price)) { echo $weekend_bedroom_price; } ?> name='weekend_bedroom_price'/></div>";

         		    panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Adult Breakfast(include/Exclude)</label>";									

					panel    +=	"<select name='weekend_bedroom_adult_bf' id='weekend_bedroom_adult_bf' class='form-control'>";

					panel    += "<option value='1' <?php if(isset($weekend_bedroom_adult_bf)){ if($weekend_bedroom_adult_bf == '1'){ echo 'selected'; }} ?>>Include</option><option value='0' <?php if(isset($weekend_bedroom_adult_bf)){ if($weekend_bedroom_adult_bf == '0'){ echo 'selected'; }} ?>>Exclude</option></select></div>";

					panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Child Breakfast(include/Exclude)</label>";									

					panel    +=	"<select name='weekend_bedroom_child_bf' id='weekend_bedroom_child_bf' class='form-control'>";

					panel    += "<option value='1' <?php if(isset($weekend_bedroom_child_bf)){ if($weekend_bedroom_child_bf == '1'){ echo 'selected'; }} ?>>Include</option><option value='0' <?php if(isset($weekend_bedroom_child_bf)){ if($weekend_bedroom_child_bf == '0'){ echo 'selected'; }} ?>>Exclude</option></select></div>";



				  	$('#weekend_bed_room_count').html("");

				  	$('#weekend_bed_room_count').html(panel)

				  	$('#weekend_bed_room_count').show();

				  }

				  else{

				  	$('#week_bed_room_count').html("");

				  	$('#week_bed_room_count').hide();	

				  	$('#weekend_bed_room_count').html("");

				  	$('#weekend_bed_room_count').hide();	

				  }

		         }

		        });		

			 }

			 else{

			 	    select.html('');					

					select.trigger("chosen:updated");              

			 }



		}

		

		function select_date(id){			

			var $select = $('#room_details_id');

			var $select1 = $('#seasons_details_id');

			$.ajax({

				url:'<?php echo site_url();?>/roomrate/get_season_date/'+id,

				dataType: "json",

				success: function(data) {

					console.log(data);

					$('#date_rane_rate').val(data);

				}

			});

		}



		function select_room(hotelId){			

			var $select = $('#room_details_id');

			var $select1 = $('#seasons_details_id');

			$.ajax({

				url:'<?php echo site_url();?>/roomrate/get_room_data1/'+hotelId,

				dataType: "json",

				success: function(data, textStatus, jqXHR) {

					$select.html('');

					$select.html('<option value="">Select Any Room</option>'+data.options);

					$select1.html('');

					$select1.html('<option value="">Select Any Seasons</option>'+data.options1);

					$('#extra_bed_price1').hide();

					$('#extra_bed_price_total1').hide();

					$('#extra_bed_price_total').val(0);

					$('#extra_bed_price').val(0);

				}

			});

		   var data1 = "";

		  $.ajax({

			  url:'<?php echo site_url();?>/hotels/get_child_group/'+hotelId,

			 success: function(data, textStatus, jqXHR) {

				data1= data;

				if(data1.trim() != ''){

			     $('#child_group').html(data);

			    $('#child_group1').hide();

			} else {

				$('#child_group').html("");

			    $('#child_group1').show();

			}

			

		    	}

		   });

		

	   }

		  

		

		

	</script>

   

<?php 

if(isset($GET) && !empty($GET))

{

?>

	<script type="text/javascript">

		$(document).ready(function(){

		//alert('<?=$selected_hotel_detail_id?>');

		select_room_type('<?=$selected_hotel_detail_id?>')

		});

	 </script>


<?php

}

?>
<script type="text/javascript">
	
		$(document).ready(function(){
		
		});
</script>