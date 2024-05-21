<?php if ($ID) {
	$tab1 = " active ";
	$tab2 = "";
} else {
	$tab2 = " active ";
	$tab1 = "";
}
$_datepicker = array(array('created_datetime_from', PAST_DATE), array('created_datetime_to', PAST_DATE));
$this->current_page->set_datepicker($_datepicker);
if(!empty($search_params)){
	if (is_array($search_params)) {
	extract($search_params);
	}	
}


?>
<!-- HTML BEGIN -->
<div id="general_user" class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->

	<li role="presentation" class="<?php echo $tab1; ?>"><a
		id="fromListHead" href="#fromList" aria-controls="home" role="tab"
		data-toggle="tab"> <i class="fa fa-edit"></i> Add Car
	</a></li>
	
	<li role="presentation" class="<?php echo $tab2; ?>"><a
		href="#tableList" aria-controls="profile" role="tab" data-toggle="tab">
	<i class="fa fa-car"></i> Car List </a>
	</li>
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
</ul>
</div>
</div>
<!-- PANEL HEAD START -->
<div class="panel-body"><!-- PANEL BODY START -->
<div class="tab-content">
<?php //if($domain_admin_exists == false) { ?>
<div role="tabpanel" class="clearfix tab-pane <?php echo $tab1; ?>" id="fromList">
<div class="panel-body"></div>
<div class="tab-content">

			<form action="<?php echo base_url(); ?>index.php/car_supplier/car_list/<?=$ID?>"
							method="post" enctype="multipart/form-data"
							class='form form-horizontal validate-form'>

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Car Name </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="name" id="name" value="<?php echo isset($name) ? $name : '';?>"  
											data-rule-required='true' placeholder="Enter Car Name"
											class='form-control add_pckg_elements' required>
											<span class="error"><?php echo form_error('name')?></span>
									</div>
								</div>
							</div>
								<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Vehical Number </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="vehicle_no" id="vehicle_no" value="<?php echo isset($vehicle_no) ? $vehicle_no : '';?>" <?php echo $ID==0 ? "required" : "readonly" ;?> 
											data-rule-required='true' placeholder="Enter Vehical Number"
											class='form-control add_pckg_elements'>
										 <span class="text-danger"><?php echo form_error('vehicle_no')?></span>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Car Type </label>
								<div class='col-sm-4 controls'>
									<select class='select2 form-control add_pckg_elements'
										data-rule-required='true' name='car_make_id' id="car_make_id" required>	
										<option value="">Select Type</option>
				                        <?php foreach ($car_type as $type) {?>
				                        <?php 
				                        	$select = "";
				                        	if($car_make_id==$type['id']){
				                        		$select = "selected=selected";
				                        	}
				                        ?>
				                        <option value='<?php echo $type['id']; ?>' <?=$select?>><?php echo $type['make_name']; ?></option>
				                        <?php }?>
				                      </select>
				                      <span class="error"><?php echo form_error('car_make_id')?></span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Car Transmission </label>
								<div class='col-sm-4 controls'>
									<select class='select2 form-control add_pckg_elements'
										data-rule-required='true' name='car_transmission_id' id="car_transmission_id" required>	
										<option value="">Select Type</option>
				                        <?php foreach ($car_transmission as $trans) {?>
				                        <?php
				                        	$t_select = "";
				                        	if($car_transmission_id==$trans['id']){
				                        		$t_select="selected=selected";
				                        	} 
				                        ?>
				                        <option value='<?php echo $trans['id']; ?>' <?=$t_select?> >
				                        <?php echo $trans['transmission_name']; ?></option>
				                        <?php }?>
				                      </select>
				                       <span class="error"><?php echo form_error('car_transmission_id')?></span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Car Class </label>
								<div class='col-sm-4 controls'>
									<select class='select2 form-control add_pckg_elements'
										data-rule-required='true' name='car_class_id' id="car_class_id" required>	
										<option value="">Select Type</option>
				                        <?php foreach ($car_class as $class) {?>
				                        	<?php 
				                        	   $c_select= "";
				                        	   if($car_class_id==$class['id']){
				                        	   	$c_select = "selected=selected";
				                        	   }
				                        	?>
				                        <option value='<?php echo $class['id']; ?>' <?=$c_select?>><?php echo $class['class_name']; ?></option>
				                        <?php }?>
				                      </select>
				                      <span class="error"><?php echo form_error('car_class_id')?></span></td>
								</div>
							</div>
							<!-- <div class="form-group">
								<label class='control-label col-sm-3' for='validation_current'>								No Of Cars </label>
								<div class='col-sm-4 controls'>
									<select class="form-control" name="no_of_car" id="no_of_car" required="">
										<?php for($c=1;$c<=200;$c++):?>
											<?php
											  $selected  = '';
											  if(isset($no_of_car)){
											  	if($c==$no_of_car){
											  		$selected = "selected=selected";
											  	}
											  }
											 
											?>
											<option value="<?=$c?>" <?=$selected?>><?=$c?></option>
										<?php endfor;?>
									</select>
								</div>
							</div> -->
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Car Features </label>
								<div class='col-sm-4 controls'>
									<?php

										$sel_id = isset($feature_id) ? $feature_id : array();
										echo feature_list($car_features, $sel_id);
									?>
									
								</div>
							</div>

							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Seating Capacity (+driver)</label>
								<div class='col-sm-4 controls'>
									<input type="numeric" name="no_of_seats" id="no_of_seats" value="<?php echo isset($no_of_seats) ? $no_of_seats : '';?>"

										data-rule-number="true" data-rule-required='true' placeholder="No Of Seats"
										class='form-control add_pckg_elements' required="">
										<span class="error"><?php echo form_error('no_of_seats')?></span>
								</div>
							</div>	
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Fuel Type</label>

								<div class='col-sm-4 controls'>
									<select name="fuel_type" id="fuel_type" required="" class="form-control">
										<?php
											$p_selected = '';
											$d_selected = '';
											if(isset($fuel_type)){
												if($fuel_type==0){
													$p_selected = 'selected=selected';
												}
												if($fuel_type==1){
													$d_selected = "selected=selected";
												}
											} 
										?>
										<option value="0" <?php echo $p_selected?> >Petrol</option>
										<option value="1" <?php echo $d_selected ?>>Diesel</option>
									</select>
									<span class="error"><?php echo form_error('fuel_type')?></span>
									
										
								</div>
							</div>	

							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Fuel Capacity</label>
								<div class='col-sm-4 controls'>
									<input type="numeric" name="fuel_capacity" id="fuel_capacity" value="<?php echo isset($fuel_capacity) ? $fuel_capacity : '';?>"
										data-rule-number="true" data-rule-required='true' placeholder="Fuel Capacity"
										class='form-control add_pckg_elements' required="">
										<span class="error"><?php echo form_error('fuel_capacity')?></span>
								</div>
							</div>	
							<hr/>
							<h5>Price Management</h5>
							<p>Define price per/km 1km price</p>
							<p>Define price in IDR currency</p>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Weekdays Price  </label>
								<div class='col-sm-4 controls'>
									<input type="numeric" name="weekdays_price" id="weekdays_price"
										data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($weekdays_price)?$weekdays_price:'';?>" 
										class='form-control add_pckg_elements numeric' required="">
										<span class="error"><?php echo form_error('weekdays_price')?></span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Weekend  </label>
								<div class='col-sm-4 controls'>
									<input type="numeric" name="weekend_price" id="weekend_price"
										data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($weekend_price)?$weekend_price:'';?>" 
										class='form-control add_pckg_elements numeric' >
										<span class="error"><?php echo form_error('weekend_price')?></span>
								</div>
							</div>	
							<hr/>
							<h5>Cancellation Policy Management</h5>
							<div class="form-group">
							<label class='control-label col-sm-3' for='adult'>Policy</label>
								<div class='col-sm-4 controls'>
									<label><input type="radio" name="policy_type" class="policy_type"
											value="<?php echo INACTIVE?>"
											<?php echo (isset($policy_type) == true and intval($policy_type) == INACTIVE) ? 'checked' : '';?>  />NonRefundable</label>
										<label><input type="radio" name="policy_type" class="policy_type" value="<?php echo ACTIVE?>"
										<?php echo (isset($policy_type) == true and intval($policy_type) == ACTIVE) ? 'checked' : '';?> />Refundable</label>
								</div>
							</div>

							<div class="form-group" id="ref_cancell_div">
								<label class='control-label col-sm-3' for='adult'>Cancellation days</label>
								
								<div class='col-sm-2 controls'>
									<select class="form-control" id="c_day" name="c_day" required="">
										<?php for($i=2;$i<=10;$i++): ?>
											<?php
												$o_selected= '';
												if($cancellation_day==$i){
													$o_selected = "selected=selected";
												}
											?>
											<option value="<?=$i?>" <?=$o_selected?>><?=$i?> days</option>
										<?php endfor;?>
									</select>
									<span>Define in % ex:(10)</span>
									<input type="numeric" name="c_percentage" id="c_percentage"
										data-rule-number="true" data-rule-required='true' placeholder="Price" value="<?php echo isset($cancel_percentage)?$cancel_percentage:'';?>" 
										class='form-control add_pckg_elements numeric' >
										<span class="error"><?php echo form_error('c_percentage')?></span>

								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_company'>Cab Image</label>

									<?php 
										$i_required= 'required';
										if(isset($icon)
											){
											echo '<img height="80px" width="120px" alt="cabimage" src="'.get_host().APP_ROOT_DIR.str_replace('../','/',DOMAIN_CAR_UPLOAD_DIR).$icon.'">';
											$i_required ='';
										}
									?>
								<div class='col-sm-4 controls'>
									<input type="file" title='Image to add'
										class='add_pckg_elements' data-rule-required='true' id='photo'
										name='photo' <?=$i_required?>> <span id="pacmimg" 
										style="color: #F00; display: none">Please Upload Car Image</span>
										<span class="error"><?php echo form_error('icon')?></span></td>
								</div>
							</div>
									

							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Status</label>
								<div class='col-sm-4 controls'>
									<label><input type="radio" name="status"
											value="<?php echo INACTIVE?>"
											<?php echo (isset($status) == true and intval($status) == INACTIVE) ? 'checked' : '';?> />Inactive</label>
										<label><input type="radio" name="status" value="<?php echo ACTIVE?>"
										<?php echo (isset($status) == true and intval($status) == ACTIVE) ? 'checked' : '';?> />Active</label>
								</div>
							</div>

							<div class='' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										
										<input class="btn btn-primary" type="submit" id=sup_submit value="Submit">&nbsp;&nbsp;
											<a class='btn btn-primary' href="<?php echo base_url(); ?>car_supplier/car_list"> Cancel</a>
									</div>
								</div>
							</div>
			</form>
	</div>
</div>
<?php //} ?>
<div role="tabpanel" class="clearfix tab-pane <?php echo $tab2; ?>" id="tableList">
<!--/************************ GENERATE Filter Form ************************/-->

<div class="clearfix"></div>
<!--/************************ GENERATE Filter Form ************************/-->
<div class="panel-body"><?php
/************************ GENERATE CURRENT PAGE TABLE ************************/
echo get_table(@$table_data);
/************************ GENERATE CURRENT PAGE TABLE ************************/
?></div>
</div>
</div>
</div>
<!-- PANEL BODY END --></div>
<!-- PANEL WRAP END --></div>
<!-- HTML END -->
<?php
function get_table($table_data='')
{
	$table = '';
	$pagination = $GLOBALS['CI']->pagination->create_links();
	$table .= $pagination;
	$table .= '
   <div class="table-responsive">
   <table class="table table-hover table-striped table-bordered table-condensed" id="car_table">';
	$table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i> '.get_app_message('AL006').'</th>
   <th>Car Name</th>
   <th>Car Type</th>   
   <th>Class Name</th>
   <th>Transmission</th>
   <th>Seats</th>
   <th>Image</th>
   <th>Status</th>
   <th>Action</th>
   </tr></thead><tbody>';

	if (valid_array($table_data) == true) {
		
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach ($table_data as $k => $v) {	
		//debug($v);		
			//echo $GLOBALS['CI']->template->domain_uploads_car($v['icon']);

			$action = '';
			
			$action .=get_edit_button($v['id']);
			$action .=get_delete_button($v['id']);
			$table .= '<tr>
			<td>'.(++$current_record).'</td>
			<td>'.$v['name'].'</td>
			<td>'.$v['make_name'].'</td>			
			<td>'.$v['class_name'].'</td>
			<td>'.$v['transmission_name'].'</td>
			<td>'.$v['no_of_seats'].'</td>
			<td><img width="50" height="50" alt="'.$v['name'].'" src="'.get_host().APP_ROOT_DIR.str_replace('../','/',DOMAIN_CAR_UPLOAD_DIR).$v['icon'].'"></td>
			<td>'.get_status_toggle_button($v['status'], $v['id']).'</td>
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

function get_status_toggle_button($status, $id)
{
	$status_options = get_enum_list('status');
	return '<select class="toggle-user-status" data-table="car_details" data-origin="'.$id.'" >'.generate_options($status_options, array($status)).'</select>';
	/*if (intval($status) == INACTIVE) {
		return '<a role="button" href="'.base_url().'user/activate_account/'.$user_id.'/'.$uuid.'" class="text-success">Activate</a>';
	} else {
		return '<a role="button" href="'.base_url().'user/deactivate_account/'.$user_id.'/'.$uuid.'" class="text-danger">Deactivate</a>';
	}*/
}

function get_edit_button($id)
{
	return '<a role="button" href="'.base_url().'index.php/car_supplier/car_list/'.$id.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
		'.get_app_message('AL0022').'</a>
		';
	/*<a role="button" href="'.base_url().'general/account?uid='.$id.'" class="btn btn-sm">
		<span class="glyphicon glyphicon-zoom-in"></span>'.get_app_message('AL0023').'</a>*/
}
function get_delete_button($origin){
	return '<a role="button" href="#" class="btn btn-sm btn-danger del-car-asset-btn" data-id="'.$origin.'" data-table="car_details"><i class="fa fa-trash"></i>Delete</a>';
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
<script>
var car_base_url ="<?php echo  base_url()?>";
$(document).ready(function() {
	$("#car_table").dataTable();
	$('.toggle-user-status').on('change', function(e) {
		e.preventDefault();
		var _user_status = this.value;
		var _opp_url = app_base_url+'index.php/car_supplier/';
		if (parseInt(_user_status) == 1) {
			_opp_url = _opp_url+'active_car_driver/';
		} else {
			_opp_url = _opp_url+'deactive_car_driver/';
		}
		_opp_url = _opp_url+$(this).data('table')+'/'+$(this).data('origin');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function() {
			toastr.info('Updated Successfully!!!');
		});
	});
	$(".del-car-asset-btn").on("click",function(){
			var id = $(this).data('id');
			var table = $(this).data('table');
			toastr.info('Please Wait!!!');
			$.ajax({
				url:car_base_url+"index.php/car_supplier/delete_record/"+table+"/"+id,
				success:function(res){
					if(res==1){
						toastr.info('Updated Successfully!!!');
						location.reload();
					}else{
						toastr.info('Not Deleted!!!');
					}
				},
				error:function(res){
					alert("Technical Issue");
				}
			})
		});
});
$(document).ready(function(){

		var selected_policy_type= $(".policy_type").val();
		//console.log("selected_policy_type"+selected_policy_type);
		if(selected_policy_type==1){
				$("#ref_cancell_div").show();
		}else if(selected_policy_type==0){
			$("#ref_cancell_div").hide();
		}
		$(".policy_type").click(function(){
			var policy_type  = $(this).val();
			if(policy_type==1){
				$("#ref_cancell_div").show();
			}else if(policy_type==0){
				$("#ref_cancell_div").hide();
			}
		});
         $('#country').on('change', function() {
           $.ajax({
           url: 'get_crs_city/' + $(this).val(),
           dataType: 'json',
           success: function(json) {
           	if(json.result=='<option value="">Select City</option>'){
           		$('#city_name_div').addClass('hide');
           	}
           	else{
           		$('select[name=\'cityname\']').html(json.result);
           		$('#city_name_div').removeClass('hide');
           	}
           }
       	});
         });
        $("#cityname").on('click',function(){
        	var dropdownVal=$(this).val();

        	$("#textbox").val(dropdownVal); 
		
    	});

     });

</script>
