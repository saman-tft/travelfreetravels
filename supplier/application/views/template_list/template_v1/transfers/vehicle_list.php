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
<div id="package_types" class="bodyContent col-md-12">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->

	<li role="presentation" class="<?php echo $tab1; ?>"><a
		id="fromListHead" href="#fromList" aria-controls="home" role="tab"
		data-toggle="tab"> <i class="fa fa-edit"></i>Add / Update Vehicle Details
	</a></li>
	
	<li role="presentation" class="<?php echo $tab2; ?>"><a
		href="#tableList" aria-controls="profile" role="tab" data-toggle="tab">
	<i class="fa fa-car"></i> Vehicle List </a>
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

			<form action="<?php echo base_url(); ?>index.php/car_supplier/vehical_list/<?=$ID?>/<?=$CAR_ID?>"
							method="post" enctype="multipart/form-data"
							class='form form-horizontal validate-form'>

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Vehical List </label>
								<?php
										$disable = '';
										if(isset($car_id)){
											$disable = 'disabled="true"';
											echo "<input type='hidden' value=".$car_id." name='car_id'>";
										}
									?>
								<div class='col-sm-4 controls'>
									<select class='select2 form-control add_pckg_elements'
										data-rule-required='true' name='car_id' id="car_id" required <?=$disable?>>	
										
				                        <?php foreach ($car_list as $car) {?>
				                       	<?php
				                       	    $select = '';
				                       		if($car_id==$car['id']){
				                       			$select = "selected=selected";
				                       		}
				                       	?>
				                        <option value='<?php echo $car['id']; ?>' <?=$select?>><?php echo $car['name']; ?></option>
				                        <?php }?>
				                      </select>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Door Type </label>
								<div class='col-sm-4 controls'>
									<select class='select2 form-control add_pckg_elements'
										data-rule-required='true' name='door_type' id="door_type" required>	
										<?php
									    if(isset($door_type))
									    	echo '<option>'.$door_type.'</option>';
									   	else 
									   		echo '<option value="">Select</option>'; 
									    ?>

										 <option>Butterfly Doors</option>
									    <option>Suicide Doors</option>
									    <option>Gullwing Doors</option>
									    <option>Scissor Doors</option>
									    <option>Sliding Doors</option>
									    <option>Canopy Doors</option>
				                      </select>
				                       <span class="text-danger"><?php echo form_error('door_type')?></span></td>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								No Of Doors </label>
								<div class='col-sm-4 controls'>
									
									<select class='select2 form-control add_pckg_elements'
										data-rule-required='true' name='no_of_doors' id="no_of_doors" required>	
										
									    <?php for($j=1;$j<=5;$j++):?>
									    <?php 
									    	$d_selected = '';
									    	if(isset($no_of_doors)){
									    		if($j==$no_of_doors){
									    			$d_selected="selected=selected";
									    		}
									    	}
									    ?>
										 <option value="<?=$j?>" <?=$d_selected?>><?=$j?></option>
									    <?php endfor;?>
				                      </select>
				                       <span class="text-danger"><?php echo form_error('no_of_doors')?></span></td>
								</div>
							</div>

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Luggage </label>
								<div class='col-sm-4 controls'>
									<select class='select2 form-control add_pckg_elements'
										data-rule-required='true' name='luggage' id="luggage" required>	
										
									    <?php for($i=1;$i<=6;$i++):?>
									    	<?php
									    		$l_selected= '';
									    		if(isset($luggage)){
									    			if($i==$luggage){
									    				$l_selected="selected=selected";
									    			}
									    		}
									    	?>
										 <option value="<?=$i?>" <?=$l_selected?>><?=$i?></option>
									    <?php endfor;?>
				                      </select>
				                       <span class="text-danger"><?php echo form_error('luggage')?></span></td>
								</div>
							</div>
<!--							<div class='form-group'>-->
<!--								<label class='control-label col-sm-3' for='validation_current'>								Pick Up Information </label>-->
<!--								<div class='col-sm-4 controls'>-->
<!--									<div class="controls">-->
<!--										<textarea  class="form-control" name="pick_up_information" id="pick_up_information" rows="5" cols="45">--><?php //echo isset($pick_up_information) ? $pick_up_information : '';?><!-- </textarea>-->
<!--										<span class="text-danger">--><?php //echo form_error('pick_up_information')?><!--</span>-->
<!--									</div>-->
<!--								</div>-->
<!--							</div>-->
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Description </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<textarea name="description" id="description" class="form-control" rows="5" cols="45"><?php echo isset($description) ? $description : '';?> </textarea>
										<span class="text-danger"><?php echo form_error('description')?></span>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>							
										Car Registration Number </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="car_reg_no" id="car_reg_no" value="<?php echo isset($car_reg_no) ? $car_reg_no : '';?>" 
											data-rule-required='true' placeholder="Car Registration Number"
											class='form-control add_pckg_elements' maxlength="25"  onkeypress="return alphanumeric(event)" required>
										<span class="text-danger"><?php echo form_error('car_reg_no')?></span>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>							
										Engine Number </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="engine_no" id="engine_no" value="<?php echo isset($engine_no) ? $engine_no : '';?>" 
											 placeholder="Engine Number"
											class='form-control add_pckg_elements' maxlength="25" onkeypress="return alphanumeric(event)" >
										
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>							
										Color </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="color" id="color" value="<?php echo isset($color) ? $color : '';?>" 
											placeholder="Color"
											class='form-control add_pckg_elements alpha' maxlength="30">

									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>							
										Mileage </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="mileage" id="mileage" value="<?php echo isset($mileage) && $mileage!="" ? $mileage : 0;?>" 
											placeholder="Mileage"
											class='form-control add_pckg_elements numeric' maxlength="2">
									</div>
								</div>
							</div>
						<!-- 	<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>							
										Emission </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="emission" id="emission" value="<?php echo isset($emission) ? date("m/d/Y",strtotime($emission)) : '';?>" 
											placeholder="Emission"
											class='form-control add_pckg_elements'>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>							
										Authorization </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" value="<?php echo isset($authorization) ? date("m/d/Y",strtotime($authorization)) : '';?>" name="authorization" id="authorization"
											placeholder="Authorization"
											class='form-control add_pckg_elements'>
									</div>
								</div>
							</div> -->
							<hr/>
							<h5>Insurance Details</h5>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>							
										Insurance Company </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="insurance_company" id="insurance_company" value="<?php echo isset($insurance_company) ? $insurance_company : '';?>" 
											placeholder="Insurance Company"
											class='form-control add_pckg_elements' maxlength="150" onkeypress="return alphanumeric(event)" >
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>							
									Insurance Begin Date </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" value="<?php echo isset($insurance_begin) ? date("m/d/Y",strtotime($insurance_begin)) : '';?>" name="insurance_begin" id="insurance_begin"
											placeholder="Insurance Begin Date"
											class='form-control add_pckg_elements date-class' readonly>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>							
									Insurance End Date </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text"  value="<?php echo isset($insurance_end) ? date("m/d/Y",strtotime($insurance_end)) : '';?>" name=" insurance_end" id="insurance_end"
											 placeholder="Insurance End Date"
											class='form-control add_pckg_elements date-class' readonly >
									</div>
								</div>
							</div>
							<hr/>
							<h5>Purchase Details</h5>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>							
									Purchase From</label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="purchase_from" value="<?php echo isset($purchase_from) ? $purchase_from : '';?>" id="purchase_from"
											placeholder="Purchase From"
											class='form-control add_pckg_elements' onkeypress="return alphanumeric(event)">
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>							
									Purchase Amount</label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" value="<?php echo isset($purchase_amount) && $purchase_amount!="" ? $purchase_amount : 0;?>" name="purchase_amount" id="purchase_amount"
											placeholder="Purchase Amount"
											class='form-control add_pckg_elements numeric' maxlength="9" >
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>							
										Purchase Date</label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" value="<?php echo isset($purchase_date) ? date("m/d/Y",strtotime($purchase_date)) : '';?>" name="purchase_date" id="purchase_date"
											placeholder="Purchase Date"
											class='form-control add_pckg_elements date-class' readonly>
									</div>
								</div>
							</div>

							<div class='' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										
										<input class="btn btn-primary" type="submit" id=sup_submit value="Submit">&nbsp;&nbsp;
											<a class='btn btn-primary' href="<?php echo base_url(); ?>car_supplier/vehical_list"> Cancel</a>
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
   <table class="table table-hover table-striped table-bordered table-condensed" id="inventory_table">';
	$table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i> '.get_app_message('AL006').'</th>
   <th>Vehical Number</th>
  
   <th>Car Name</th>
   <th>Door Type</th>
   <th>Color</th>   
   <th>Action</th>
   </tr></thead><tbody>';

	if (valid_array($table_data) == true) {
		
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach ($table_data as $k => $v) {	
			//debug($v);	exit;	
			//echo $GLOBALS['CI']->template->domain_uploads_car($v['icon']);
			$action =get_edit_button($v['id'],$v['car_id']);
			$action .=get_delete_button($v['id']);
			$table .= '<tr>
			<td>'.(++$current_record).'</td>
			<td>'.$v['vehicle_no'].'</td>					
			<td>'.$v['car_name'].'</td>
			<td>'.$v['door_type'].'</td>
			<td>'.$v['color'].'</td>			
			<td>'.$action.'</td>
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

function get_edit_button($id,$car_id)
{
	return '<a role="button" href="'.base_url().'index.php/car_supplier/vehical_list/'.$id.'/'.$car_id.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
		'.get_app_message('AL0022').'</a>
		';
	/*<a role="button" href="'.base_url().'general/account?uid='.$id.'" class="btn btn-sm">
		<span class="glyphicon glyphicon-zoom-in"></span>'.get_app_message('AL0023').'</a>*/
}
function get_delete_button($origin){

	return '<a role="button" href="#" class="btn btn-sm btn-danger del-car-asset-btn" data-id="'.$origin.'" data-table="car_supplier_vehicle_management"><i class="fa fa-trash"></i>
		'." ".get_app_message('AL00342').'</a>';
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
    $(".date-class").datepicker();
	$("#inventory_table").dataTable();

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

			if (confirm('Are You Sure? you want to delete?')) {

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
		}
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
