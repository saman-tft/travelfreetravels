<?php 
error_reporting(E_ALL);


?>
<!-- HTML BEGIN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css">

<div id="general_user" class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">


</div>
</div>
<!-- PANEL HEAD START -->
<div class="panel-body"><!-- PANEL BODY START -->
<div class="tab-content">

<a class="btn btn-primary" href="<?php echo base_url();?>loyalty_program/add_product">Add product</a>
<!--/************************ GENERATE Filter Form ************************/-->

<!--/************************ GENERATE Filter Form ************************/-->
<div class="clearfix">
<?php
/************************ GENERATE CURRENT PAGE TABLE ************************/
echo get_table(@$get_all_list, $total_rows);
/************************ GENERATE CURRENT PAGE TABLE ************************/
?>
</div>

</div>
</div>
<!-- PANEL BODY END --></div>
<!-- PANEL WRAP END --></div>
<!-- HTML END -->
<?php
function get_table($table_data='', $total_rows=0)
{
	$table = '';
	$pagination = '<div>'. $GLOBALS['CI']->pagination->create_links().'<span class="">Total '.$total_rows.' Product</span></div>';
	$table .= $pagination;
	
	
	$table .= '
   <div class="clearfix">
   <div class="col-md-12 table-responsive tbpd " >
   <table class="table table-condensed table-bordered">';
	$table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i>Sno</th>
   
   <th>Action</th>
   <th>Type</th>
   <th>Name</th>
   <th>image</th>
   <th>description</th>
   <th>point</th>
   
  
   
   
   
   </tr></thead><tbody>';
   // debug($table_data);exit;
	if (valid_array($table_data) == true) {
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		
		

		$i=1;
		foreach ($table_data as $k => $v) {

			/*$last_login = 'Last Login : '.last_login($v['last_login']);
			$login_status = login_status($v['logout_date_time']);*/
			$type="";
			if($v['type']==1){
				$type="voyages product";
			}
			if($v['type']==2)
			{
				$type="other product";
			}
			$action_tab = '';
			$action_tab .= get_edit_button($v['id']);
			
			$action_tab .= '<br />'.delete_button($v['id']);
			
			$img='<img src="'.$GLOBALS['CI']->template->domain_uploads().'loyalty_product/'.$v['image'].'" alt="Smiley face" height="42" width="42">';
			//Booking
			$table .= '<tr>
			<td>'.(++$current_record).'</td>
			
			<td>
			    <div class="dropdown2" role="group">
				   <div class="dropdown slct_tbl pull-left sideicbb">
					   <i class="fa fa-ellipsis-v"></i>  
					    <ul class="dropdown-menu sidedis" style="display: none;">
						   <li>
						 '.$action_tab.'
						   </li>
						</ul>
				    </div>
				</div>
			</td>
			<td>'.$type.'</td>
			<td>'.$v['name'].'</td>
			<td>'.$img.'</td>
			
			<td>'.$v['description'].'</td>
			<td>'.$v['point'].'</td>
			
			
			
			
			
</tr>';
		
		$i++;
		}
	} else {
		$table .= '<tr><td colspan="8">'.get_app_message('AL005').'</td></tr>';
	}
	$table .= '</tbody></table></div></div>';
	return $table;
}



function get_edit_button($id)
{
	return '<a role="button" href="'.base_url().'index.php/loyalty_program/edit_product?eid='.$id.'" class="sideicbb1 sidedis">
				<i class="fa fa-edit"></i> Edit
			</a>
		';
}
function delete_button($id)
{
	return '<a role="button" href="'.base_url().'index.php/loyalty_program/delete_product?eid='.$id.'" class="sideicbb2 sidedis">
				<i class="fa fa-trash"></i> Delete
			</a>
		';
}

?>

 


<script>
$(document).ready(function() {
	
	 //set dropdownlist selected
	

});

</script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.js"></script>



