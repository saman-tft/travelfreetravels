<?php 
$d=1;
if ($ID) {
	$tab1 = " active ";
	$tab2 = "";
} else {
	$tab2 = " active ";
	$tab1 = "";
}


?>

<!-- HTML BEGIN -->
<div class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
	<li role="presentation" class="<?=$tab1?>">
		<a id="fromListHead" href="#fromList" aria-controls="home" role="tab"	data-toggle="tab">Add Car Features</a>
	</li>
	<li role="presentation" class="<?=$tab2?>">
		<a	href="#tableList" aria-controls="profile" role="tab" data-toggle="tab">Car Features List </a>
	</li>
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
</ul>
</div>
</div>
<!-- PANEL HEAD START -->

<div class="panel-body"><!-- PANEL BODY START -->
<div class="tab-content">
<div role="tabpanel" class="clearfix tab-pane <?=$tab1?>" id="fromList">
<div class="panel-body">
	<form method="post" action="<?php echo base_url();?>index.php/car_supplier/car_features/<?php echo $ID;?>" enctype="multipart/form-data">
		<div class='form-group'>
			<label class='control-label col-sm-3' for='validation_current'>								Feature Name </label>
			<div class='col-sm-4 controls'>
				<div class="controls">
					<input type="text" name="feature_name" id="name"
						data-rule-required='true' value="<?php echo isset($feature_name) ? $feature_name : '';?>" placeholder="Enter Feature Type"
						class='form-control add_pckg_elements' onkeypress="return alphanumeric(event)"  required>
				</div>
			</div>
			<span><?php echo form_error('feature_name')?></span>
		</div>
		<div class='' style='margin-bottom: 0'>
			<div class='row'>
				<div class='col-sm-9 col-sm-offset-3'>
					
					<input class="btn btn-primary" type="submit" id=sup_submit value="Submit">&nbsp;&nbsp;
						<a class='btn btn-primary' href="<?php echo base_url(); ?>car_supplier/car_features"> Cancel</a>
				</div>
			</div>
		</div>
</form>
<?php
	/************************ GENERATE CURRENT PAGE FORM ************************/
	
	/************************ GENERATE UPDATE PAGE FORM ************************/
?>
</div>
</div>
<div role="tabpanel" class="clearfix tab-pane <?=$tab2?>" id="tableList">
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
	$table .= '<th>Type</th>';	
	$table .= '<th>Status</th>';
	
	$table .= '<th>Action</th>';
	$table .= '</tr></thead><tbody>';
	if(valid_array($promo_code_list)) {
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach($promo_code_list as $k => $v) {

			$action = '';
			extract($v);			
			$action .= get_status_toggle_button($v['status'], $v['id']);
			$action .= get_edit_button($id);
			$action .= get_delete_button($id);
			$table .= '<tr>';
			$table .= '<td>'.(++$d).'</td>';
			$table .= '<td>'.ucfirst($feature_name).'</td>';			
			$table .= '<td>'.get_status_label($status).'</td>';			
			$table .= '<td>'.$action.'</td>';
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
function get_status_toggle_button($status, $id)
{

	$status_options = get_enum_list('status');
	return '<select class="toggle-user-status" data-id="'.$id.'">'.generate_options($status_options, array($status)).'</select>';	
}
function get_edit_button($origin)
{
	return '<a role="button" href="'.base_url().'index.php/car_supplier/car_features/'.$origin.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
		'.
		" ".get_app_message('AL0022').'</a>';
}
function get_delete_button($origin){
	return '<a role="button" href="#" class="btn btn-sm btn-danger del-car-asset-btn" data-id="'.$origin.'" data-table="car_features"><i class="fa fa-trash"></i>
		'." ".get_app_message('AL00342').'</a>';
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
		$('.toggle-user-status').on('click', function(e) {
			e.preventDefault();
			var _user_status = this.value;
			var _opp_url = app_base_url+'index.php/car_supplier/';
			if (parseInt(_user_status) == 1) {
				_opp_url = _opp_url+'activate_car_assets/';
			} else {
				_opp_url = _opp_url+'deactivate_car_assets/';
			}
			_opp_url = _opp_url+$(this).data('id')+'/car_features';
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

