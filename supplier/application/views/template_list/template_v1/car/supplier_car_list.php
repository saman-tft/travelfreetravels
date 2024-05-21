<?php 


?>

<!-- HTML BEGIN -->
<div class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
	
	<li role="presentation" class="active">
		<a	href="#tableList" aria-controls="profile" role="tab" data-toggle="tab">car List</a>
	</li>
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
</ul>
</div>
</div>
<!-- PANEL HEAD START -->

<div class="panel-body"><!-- PANEL BODY START -->
<div class="tab-content">
<div role="tabpanel" class="clearfix tab-pane " id="fromList">
<div class="panel-body">
	
<?php
	/************************ GENERATE CURRENT PAGE FORM ************************/
	
	/************************ GENERATE UPDATE PAGE FORM ************************/
?>
</div>
</div>
<div role="tabpanel" class="clearfix tab-pane active" id="tableList">
<!--/************************ GENERATE Filter Form ************************/-->

<div class="clearfix"></div>
<!--/************************ GENERATE Filter Form ************************/-->
<?php
/************************ GENERATE CURRENT PAGE TABLE ************************/
echo get_table($data_list);
/************************ GENERATE CURRENT PAGE TABLE ************************/
?>
</div>
</div>
</div>
<!-- PANEL BODY END --></div>
<!-- PANEL WRAP END --></div>
<!-- HTML END -->
<?php 
function get_table($promo_code_list)
{
	$table  = '';
	$table  .= $GLOBALS['CI']->pagination->create_links();
	$table .= '<table class="table table-bordered table-hover table-condensed" id="car_table">';
	$table .= '<thead><tr>';
	$table .= '<th><i class="fa fa-sort-numeric-asc"></i> Sno</th>';
	$table .= '<th>Car Name</th>';	
	$table .= '<th>Vehicle Number</th>';
	$table .='<th>Door Type</th>';
	$table .='<th>Status</th>';
	$table .= '</tr></thead><tbody>';
	if(valid_array($promo_code_list)) {
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach($promo_code_list as $k => $v) {
		
			$action = '';
			extract($v);
			$table .= '<tr>';
			$table .= '<td>'.($k+1).'</td>';
			$table .= '<td>'.ucfirst($car_name).'</td>';			
			$table .= '<td>'.$vehicle_no.'</td>';	
			$table .='<td>'.$door_type.'</td>';					
			$table .='<td>'.get_status_label($status).'</td>';
			$table .= '</tr>';
		}
	} else {
		$table .= '<tr><td colspan="6">No Data Found</td></tr>';
	}
	$table .= '</tbody></table>';
	return $table;
}
function get_status_label($status)
{
	if (intval($status) == ACTIVE) {
		return '<span class="label label-success"><i class="fa fa-circle"></i> '.get_enum_list('status', ACTIVE).'</span>';
	} else {
		return '<span class="label label-danger"><i class="fa fa-circle"></i> '.get_enum_list('status', INACTIVE).'</span>';
	}
}

/**
 * FIXME: Jaganath--Implement Share It Button
 * @param $origin
 */

?>
<script type="text/javascript">
	var car_base_url = "<?php echo base_url()?>";
	$(document).ready(function(){
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
		$('.toggle-user-status').on('change', function(e) {
			e.preventDefault();
			var _user_status = this.value;
			var _opp_url = app_base_url+'index.php/car_supplier/';
			if (parseInt(_user_status) == 1) {
				_opp_url = _opp_url+'activate_car_assets/';
			} else {
				_opp_url = _opp_url+'deactivate_car_assets/';
			}
			_opp_url = _opp_url+$(this).data('id')+'/car_class';
			toastr.info('Please Wait!!!');
			$.get(_opp_url, function() {
				toastr.info('Updated Successfully!!!');
				location.reload();
			});
		});
	});
	
</script>
 

<?php

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/car.js'), 'defer' => 'defer');
?> 

<script>
    $(document).ready(function() {
          $('#car_table').dataTable();
     });     
</script>

