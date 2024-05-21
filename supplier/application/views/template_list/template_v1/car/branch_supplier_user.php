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
		<a	href="#tableList" aria-controls="profile" role="tab" data-toggle="tab">Branch User & Supplier & Driver  List</a>
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
	$table .= '<th>Branch Name</th>';	
	$table .= '<th>Branch Phone</th>';
	$table .='<th>Supplier Name</th>';
	$table .='<th>Supplier City</th>';
	$table .= '<th>Supplier Phone</th>';
	$table .='<th>Driver Name</th>';
	$table .='<th>Driver Phone</th>';	
	$table .='<th>Action</th>';
	$table .= '</tr></thead><tbody>';
	if(valid_array($promo_code_list)) {
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach($promo_code_list as $k => $v) {
			extract($v);

			$action = get_car_list($b_d,$s_id);
			$table .= '<tr>';
			$table .= '<td>'.(++$current_record).'</td>';
			$table .= '<td>'.ucfirst($b_name).'</td>';			
			$table .= '<td>'.$b_phone.'</td>';	
			$table .='<td>'.ucfirst($s_name).'</td>';
			$table .='<td>'.$s_city_name.'</td>';
			$table .= '<td>'.$s_phone.'</td>';		
			$table .= '<td>'.ucfirst($d_name).'</td>';
			$table .= '<td>'.$d_phone.'</td>';
			$table .='<td>'.$action.'</td>';
			$table .= '</tr>';
		}
	} else {
		$table .= '<tr><td colspan="6">No Data Found</td></tr>';
	}
	$table .= '</tbody></table>';
	return $table;
}
function get_car_list($branch_id,$supplier_id){
	return '<a href="'.base_url().'index.php/car_supplier/get_all_supplier_driver_car_admin/'.$branch_id.'/'.$supplier_id.'" class="btn btn-primary">View Cars</a>';
}
function get_driver_list($supplier_id){	
	return '<a href="'.base_url().'index.php/car_supplier/get_all_supplier_driver_car/driver/'.$supplier_id.'" class="btn btn-primary">View Drivers</a>';
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
<script type="text/javascript" src="<?php echo SYSTEM_RESOURCE_LIBRARY.'/DataTables/datatables.min.js';?>"></script>  

<?php
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('js/jquery.dataTables.js'), 'defer' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('js/dataTables.tableTools.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/car.js'), 'defer' => 'defer');
?> 

<script>
    $(document).ready(function() {
          $('#car_table').dataTable();
     });     
</script>

