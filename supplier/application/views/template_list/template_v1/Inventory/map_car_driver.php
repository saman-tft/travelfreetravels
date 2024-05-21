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
//debug($driver_list);

?>
<!-- HTML BEGIN -->
<div id="general_user" class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
	<li role="presentation" class="active"><a
		href="#tableList" aria-controls="profile" role="tab" data-toggle="tab">
	<i class="fa fa-users"></i>Map Car&Driver</a>
	</li>
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
</ul>
</div>
</div>
<!-- PANEL HEAD START -->
<div class="panel-body"><!-- PANEL BODY START -->
<div class="tab-content">

<div role="tabpanel" class="clearfix tab-pane active" id="tableList">
<!--/************************ GENERATE Filter Form ************************/-->

<div class="clearfix"></div>
<!--/************************ GENERATE Filter Form ************************/-->
<div class="panel-body"><?php
/************************ GENERATE CURRENT PAGE TABLE ************************/

echo get_table(@$table_data,$driver_list);
/************************ GENERATE CURRENT PAGE TABLE ************************/


?></div>
</div>
</div>
</div>
<!-- PANEL BODY END --></div>
<!-- PANEL WRAP END --></div>
<!-- HTML END -->
<?php
function get_table($table_data='',$driver_list='')
{
	$table = '';
	$pagination = $GLOBALS['CI']->pagination->create_links();
	$table .= $pagination;
	$table .= '
   <div class="table-responsive">
   <table class="table table-hover table-striped table-bordered table-condensed" id="map_table">';
	$table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i> '.get_app_message('AL006').'</th>
   <th>Car Name</th>   
   <th>Vehicle Number</th>
   <th>Driver List</th>   
   <th>Activated Driver</th>
   <th>Driver Phone Number</th>  
   <th>Availability Status</th>
   <th>Current Status</th>   
   <th>Currenct Location</th>
   <th>Full Location</th>
   </tr></thead><tbody>';

	if (valid_array($table_data) == true) {

		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach ($table_data as $k => $v) {		

			$current_location = $v['current_location'];
			// if($current_location==''){
			// 	$current_location = $v['current_location'];
			// }
			$table .= '<tr>
			<td>'.(++$current_record).'</td>
			<td class="hand-cursor">'.$v['car_name'].'</td>		
			<td>'.$v['vehicle_no'].'</td>	
			<td>'.get_driver_list($driver_list,$v['driver_id'],$v['map_id'],$v['car_id']).'</td>
			<td><span id="act-driver-id-'.$v['car_id'].'">'.$v['driver_name'].'</span></td>
			<td>'.$v['driver_phone_number'].'</td>
			<td>'.get_status_toggle_button($v['a_status'], $v['car_id'],$v['driver_id']).'</td>
			<td>'.get_availability_status($v['status']).'</td>
			<td>'.$current_location.'</td>
			<td>'.$v['full_address'].'</td>
			
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

function get_status_toggle_button($status,$car_id,$driver_id)
{
	$status_options = get_enum_list('status');
	return '<select class="toggle-user-status" data-driver-id="'.$driver_id.'" data-car-id="'.$car_id.'">'.generate_options($status_options, array($status)).'</select>';
	/*if (intval($status) == INACTIVE) {
		return '<a role="button" href="'.base_url().'user/activate_account/'.$user_id.'/'.$uuid.'" class="text-success">Activate</a>';
	} else {
		return '<a role="button" href="'.base_url().'user/deactivate_account/'.$user_id.'/'.$uuid.'" class="text-danger">Deactivate</a>';
	}*/
}

function get_driver_list($driver_list,$driver_id,$map_id,$car_id)
{	
	$option_list = '<option value=0>Please Select</option>';
	
	//echo "driver_id".$driver_id;
	foreach ($driver_list as $key => $value) {
		//debug($value);
		$select = '';
		if($driver_id==$value['driver_id']){
			$select = "selected=selected";
		}
		$option_list .='<option value='.$value['driver_id'].' '.$select.'>'.$value['full_name'].'</option>';
	}
	
	$str = '<select class="toggle-driver-status form-control" data-car-id="'.$car_id.'" data-map-id="'.$map_id.'">'.$option_list.'</select>';

	return $str;
	
}
function show_activated_driver($id=0){

}
function get_availability_status($status=0){
	if($status==0){
		return 'Not available';
	}else{
		return 'Available';
	}
}

?>
<script>
// $('.date1').datepicker({dateFormat: 'dd-mm-yy'});
$(document).ready(function() {
	$("#map_table").dataTable();
	var car_base_url = "<?php echo  base_url()?>";
	$('.toggle-user-status').on('change', function(e) {
		e.preventDefault();

		var _user_status = this.value;
		var _opp_url = car_base_url+'index.php/car_supplier/';
		if (parseInt(_user_status) == 1) {
			_opp_url = _opp_url+'activate_today/';
		} else {
			_opp_url = _opp_url+'deactivate_today/';
		}
		_opp_url = _opp_url+$(this).data('car-id')+"/"+$(this).data('driver-id');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function(res) {
			if(res==1){
				toastr.info('Updated Successfully!!!');
				location.reload();
			}else if(res==2){
				toastr.warning('This Driver Already Assoicated with another car please deactive that car and proceed');
			}else{

				toastr.warning('Please Select Driver');
			}
			
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
						toastr.warning('Not Deleted!!!');
					}
				},
				error:function(res){
					alert("Technical Issues");
				}
			})
	});
	$(".toggle-driver-status").on("change",function(){
		var car_id = $(this).data('car-id');
		var driver_id = $(this).val();
		var map_id = $(this).data('map-id');
		var selected_driver = $(this).find("option:selected").text();
		if(driver_id!=0){
			toastr.info('Please Wait!!!');
			$.ajax({
				url:car_base_url+"index.php/car_supplier/update_map_car_driver/"+car_id+"/"+driver_id+"/"+map_id,
				success:function(res){
					if(res==1){
						toastr.info('Updated Successfully!!!');
						$("#act-driver-id-"+car_id).text(selected_driver);
						location.reload();
					}else if(res==2){
						toastr.warning('Driver Already Assoicated With Another Car,Inactive that car and try again!!!!');
					}
					else{
						toastr.info('Driver Not Updated!!!');
					}
				},
				error:function(res){
					alert('Technical Issues');
				}
			});
		}else{
			alert("Please select the driver");
		}
		
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
