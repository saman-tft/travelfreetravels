<?php if (form_visible_operation()) {
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
		data-toggle="tab"> <i class="fa fa-edit"></i> Add Supplier
	</a></li>
	
	<li role="presentation" class="<?php echo $tab2; ?>"><a
		href="#tableList" aria-controls="profile" role="tab" data-toggle="tab">
	<i class="fa fa-users"></i> Supplier List </a>
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

			<form action="<?php echo base_url(); ?>index.php/supplier/add_supplier"
							method="post" enctype="multipart/form-data"
							class='form form-horizontal validate-form'>

				<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Supplier Name </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="supplier_name" id="name"
											data-rule-required='true' placeholder="Enter Supplier Name"
											class='form-control add_pckg_elements' required>
									</div>
								</div>
							</div>
						<!-- 	<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Company Reg No</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="reg_no" id="p_price"
										data-rule-number="true" data-rule-required='true' placeholder="Company Reg No"
										class='form-control add_pckg_elements' maxlength='30' minlength='3'required>
								</div>
							</div> -->	
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Email</label>
								<div class='col-sm-4 controls'>
									<input type="email" name="email" id="p_price"
										data-rule-number="true" data-rule-required='true' placeholder="Email"
										class='form-control add_pckg_elements' required="">
								</div>
							</div>	
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Password</label>
								<div class='col-sm-4 controls'>
									<input type="password" name="password" id="password"
										data-rule-number="true" data-rule-required='true' placeholder="Password"
										class='form-control add_pckg_elements' required="">
								</div>
							</div>	
							


							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Phone No</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="phone_no" id="p_price"
										data-rule-number="true" data-rule-required='true' placeholder="Phone No"
										class='form-control add_pckg_elements numeric' maxlength='11' minlength='3' required="">
								</div>
							</div>	

							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>PAN No</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="pan_no" id="p_price"
										data-rule-number="true" data-rule-required='true' placeholder="Pan card number"
										class='form-control add_pckg_elements' maxlength='30' minlength='3' required="">
								</div>
							</div>			
						<!-- 	<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Aadhaar Number</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="adhar_no" id="p_price"
										data-rule-number="true" data-rule-required='true' placeholder="Aadhaar Number"
										class='form-control add_pckg_elements numeric' maxlength='30' minlength='3' required="">
								</div>
							</div>	 -->
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_country'>Country</label>
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
							<div class='form-group' id="city_name_div">
								<label class='control-label col-sm-3' for='validation_current'>City
								</label>
								<div class='col-sm-4 controls'>
									<select class='select2 form-control add_pckg_elements'
										name='cityname' id="cityname" multiple="multiple" required>
										<option value=''>Select city</option>
									</select>
								</div>
							</div>
<!-- 							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>City List
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" class="normal" id="textbox" name="cityname" style="width:450px;" required></td>
								</div>
							</div> -->
							
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Location
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="location" id="location" placeholder="Enter Location"
										data-rule-required='true'
										class='form-control add_pckg_elements' required>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_company'>Supplier 
									Id Proof</label>
								<div class='col-sm-4 controls'>
									<input type="file" title='Image to add'
										class='add_pckg_elements' data-rule-required='true' id='photo'
										name='photo' required> <span id="pacmimg"
										style="color: #F00; display: none">Please Upload Package Image</span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Address</label>
								<div class='col-sm-4 controls'>
									<textarea rows="4" cols="15" name="address" required=""></textarea>
								</div>
							</div>							
							<div class='' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										
										<input class="btn btn-primary" type="submit" id=sup_submit value="Submit">&nbsp;&nbsp;
											<a class='btn btn-primary' href="<?php echo base_url(); ?>supplier/view_with_price"> Cancel</a>
									</div>
								</div>
							</div>
			</form>
	</div>
</div>
<?php //} ?>
<div role="tabpanel" class="clearfix tab-pane <?php echo $tab2; ?>" id="tableList">
<!--/************************ GENERATE Filter Form ************************/-->
<h4>Search Panel</h4>

<hr>
<form method="GET" autocomplete="off" id="search_filter_form">
	<input type="hidden" name="user_status" value="<?=@$user_status?>" >
	<div class="clearfix form-group">
		<div class="col-xs-4">
			<label>User ID</label>
			<input type="text" placeholder="User ID" value="<?=@$uuid?>" name="uuid" class="search_filter form-control">
		</div>
		<div class="col-xs-4">
			<label>Email</label>
			<input type="text" placeholder="Email" value="<?=@$email?>" name="email" class="search_filter form-control">
		</div>
		<div class="col-xs-4">
			<label>Phone</label>
			<input type="text" placeholder="Phone Number" value="<?=@$phone?>" name="phone" class="search_filter numeric form-control">
		</div>
		<div class="col-xs-4">
			<label>Member Since</label>
			<input type="text" placeholder="Registration Date" readonly value="<?=@$created_datetime_from?>" id="created_datetime_from" name="created_datetime_from" class="search_filter form-control">
		</div>
	</div>
	<div class="col-sm-12 well well-sm">
		<button class="btn btn-primary" type="submit">Search</button> 
		<button class="btn btn-warning" type="reset">Reset</button>
		<a href="<?php echo base_url(); ?>index.php/user/b2c_user/?filter=user_type&q=4&user_status=<?php echo @$user_status;?>" id="clear-filter" class="btn btn-primary">ClearFilter</a>
	</div>
</form>
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
   <table class="table table-hover table-striped table-bordered table-condensed">';
	$table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i> '.get_app_message('AL006').'</th>
   <th>Supplier Name</th>   
   <th>Email</th>
   <th>Phone Number</th>
   <th>Country</th>
   <th>City</th>
   <th>Status</th>
   <th>Action</th>
   </tr></thead><tbody>';

	if (valid_array($table_data) == true) {
		
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach ($table_data as $k => $v) {
			
			$table .= '<tr>
			<td>'.(++$current_record).'</td>

			<td class="hand-cursor">'.$v['agency_name'].'</td>
			
			<td>'.$v['email'].'</td>
			<td>'.$v['phone'].'</td>
			<td>'.$v['country_name'].'</td>
			<td>'.$v['city_name'].'</td>
			<td>'.get_status_toggle_button($v['status'], $v['user_id'],$v['uuid']).'</td>
			<td>'.get_edit_button($v['user_id']).'</td></tr>';
		}
	} else {
		$table .= '<tr><td colspan="8">'.get_app_message('AL005').'</td></tr>';
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

function get_status_toggle_button($status, $user_id, $uuid='')
{
	$status_options = get_enum_list('status');
	return '<select class="toggle-user-status" data-user-id="'.$user_id.'" data-uuid="'.$uuid.'">'.generate_options($status_options, array($status)).'</select>';
	/*if (intval($status) == INACTIVE) {
		return '<a role="button" href="'.base_url().'user/activate_account/'.$user_id.'/'.$uuid.'" class="text-success">Activate</a>';
	} else {
		return '<a role="button" href="'.base_url().'user/deactivate_account/'.$user_id.'/'.$uuid.'" class="text-danger">Deactivate</a>';
	}*/
}

function get_edit_button($id)
{
	return '<a role="button" href="'.base_url().'index.php/user/user_management?'.$_SERVER['QUERY_STRING'].'&	eid='.$id.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
		'.get_app_message('AL0022').'</a>
		';
	/*<a role="button" href="'.base_url().'general/account?uid='.$id.'" class="btn btn-sm">
		<span class="glyphicon glyphicon-zoom-in"></span>'.get_app_message('AL0023').'</a>*/
}
?>
<script>
$(document).ready(function() {
	$('.toggle-user-status').on('change', function(e) {
		e.preventDefault();
		var _user_status = this.value;
		var _opp_url = app_base_url+'index.php/user/';
		if (parseInt(_user_status) == 1) {
			_opp_url = _opp_url+'activate_account/';
		} else {
			_opp_url = _opp_url+'deactivate_account/';
		}
		_opp_url = _opp_url+$(this).data('user-id')+'/'+$(this).data('uuid');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function() {
			toastr.info('Updated Successfully!!!');
		});
	});

});
$(document).ready(function(){
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
