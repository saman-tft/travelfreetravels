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
		data-toggle="tab"> <i class="fa fa-edit"></i>Add/Update Car Performance
	</a></li>
	
	<li role="presentation" class="<?php echo $tab2; ?>"><a
		href="#tableList" aria-controls="profile" role="tab" data-toggle="tab">
	<i class="fa fa-users"></i>Car Performance List</a>
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

			<form action="<?php echo base_url(); ?>index.php/car_supplier/car_performance/<?=$ID?>"
							method="post" enctype="multipart/form-data"
							class='form form-horizontal validate-form'>
							
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Vehicle Number </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<select name="vehicle_id" id="vehicle_id" class="form-control">
										<?php foreach($vehical_list as $id=>$value):?>
											<?php 
												$select = "";
												if($vehicle_id==$value['id']){
													$select = "select=selected";
												}
											?>
											<option value="<?=$value['id']?>" <?=$select?>><?=$value['vehicle_no']?></option>
										<?php endforeach;?>
										</select>
										<span class="text-danger"><?php echo form_error('vehicle_id')?></span>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Performance Type</label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="type" id="type" value="<?php echo isset($type) ? $type : '';?>" 
											data-rule-required='true' placeholder="Performance Type"
											class='form-control add_pckg_elements' required>
										<span class="text-danger"><?php echo form_error('type')?></span>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Cost</label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="number" name="cost" id="cost" value="<?php echo isset($cost) ? $cost : '';?>" 
											data-rule-required='true' placeholder="Cost"
											class='form-control add_pckg_elements' required>
										<span class="text-danger"><?php echo form_error('cost')?></span>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Item</label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="number" name="item" id="cost" value="<?php echo isset($item) ? $item : '';?>" 
											data-rule-required='true' placeholder="Item"
											class='form-control add_pckg_elements' required>
										<span class="text-danger"><?php echo form_error('item')?></span>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Amount</label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="number" name="amount" id="cost" value="<?php echo isset($amount) ? $amount : '';?>" 
											data-rule-required='true' placeholder="Amount"
											class='form-control add_pckg_elements' required>
										<span class="text-danger"><?php echo form_error('amount')?></span>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Date</label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="date" id="date" value="<?php echo isset($date) ? $date : '';?>" 
											data-rule-required='true' placeholder="Date"
											class='form-control add_pckg_elements date-class' required>
										<span class="text-danger"><?php echo form_error('date')?></span>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Remarks</label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<textarea rows="5" cols="40" name="description"><?php echo isset($description) ? $description : '';?></textarea>
									</div>
								</div>
							</div>
							<div class='' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										
										<input class="btn btn-primary" type="submit" id=sup_submit value="Submit">&nbsp;&nbsp;
											<a class='btn btn-primary' href="<?php echo base_url(); ?>car_supplier/car_performance"> Cancel</a>
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
   <table class="table table-hover table-striped table-bordered table-condensed">';
	$table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i> '.get_app_message('AL006').'</th>
   <th>Vehicle Number</th>   
   <th>Performance Type</th>
   <th>Cost</th>  
   <th>Item</th>
   <th>Amount</th>
   <th>Date</th>
   <th>Remarks</th>
   </tr></thead><tbody>';

	if (valid_array($table_data) == true) {
		
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach ($table_data as $k => $v) {	
				
			$table .= '<tr>
			<td>'.(++$current_record).'</td>
			<td class="hand-cursor">'.$v['vehicle_no'].'</td>			
			<td>'.$v['type'].'</td>
			<td>'.$v['cost'].'</td>			
			<td>'.$v['item'].'</td>
			<td>'.$v['amount'].'</td>
			<td>'.$v['date'].'</td>
			<td>'.$v['description'].'</td>
			<td>'.get_edit_button($v['id']).'</td>
		
			</tr>';
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
	return '<a role="button" href="'.base_url().'index.php/car_supplier/car_performance/'.$id.'" 
	class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
		'.get_app_message('AL0022').'</a>
		';
	
}
function get_delete_button($origin){
	return '<a role="button" href="#" class="btn btn-sm btn-danger del-car-asset-btn" data-id="'.$origin.'" data-table="driver_list"><i class="fa fa-trash"></i>
		'." ".get_app_message('AL00342').'</a>';
}
?>
<script>

$(document).ready(function() {
	
	$('#date').datepicker({
		minDate:new Date(),
		changeMonth:true,
		changeYear:true
	});
	// $(".date-class").datepicker({
	// 	setDate:new Date()
	// });
	var car_base_url = "<?php echo  base_url()?>";
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
	$(".del-car-asset-btn").on("click",function(){
			var id = $(this).data('id');
			var table = $(this).data('table');
			toastr.info('Please Wait!!!');
			$.ajax({
				url:car_base_url+"index.php/car_supplier/delete_record/"+table+"/"+id+"/driver_id",
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
$(document).on('change', '#country_id', function() {
		var country = $(this).val();
		if (country != 0) {
			$.get('<?php echo base_url()."index.php/ajax/get_custom_city_list/"?>'+country, function(response) {
				if (response) {
					$('#city_id').empty().html(response.data);
				}
 			});
		}
});

</script>
