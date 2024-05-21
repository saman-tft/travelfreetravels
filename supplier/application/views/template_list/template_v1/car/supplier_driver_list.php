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
		<a	href="#tableList" aria-controls="profile" role="tab" data-toggle="tab"><?=ucfirst($name)?> List</a>
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
	$table .= '<th>Driver Name</th>';	
	$table .= '<th>Driver Phone Number</th>';
	$table .='<th>Driver Email</th>';
	$table .='<th>Image</th>';
	$table .='<th>License Number</th>';
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
			//$table .= '<td>'.ucfirst($agency_name).'</td>';	
			$table .= '<td>'.ucfirst($full_name).'</td>';					
			$table .= '<td>'.$phone.'</td>';	
			$table .='<td>'.$email.'</td>';			
			$table .= '<td><img width="100" height="100" src="'.$GLOBALS['CI']->template->domain_images($driver_photo).'"></td>';
			$table .='<td>'.$license_no.'</td>';		
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
	
	
</script><?php

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/car.js'), 'defer' => 'defer');
?> 

<script>
    $(document).ready(function() {
          $('#car_table').dataTable();
     });     
</script>

