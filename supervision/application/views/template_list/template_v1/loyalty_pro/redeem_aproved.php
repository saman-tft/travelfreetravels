<?php 
error_reporting(E_ALL);


?>
<!-- HTML BEGIN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css">
<style>
	.bhu{margin:0px 3px;}</style>
<div id="general_user" class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">


</div>
</div>
<!-- PANEL HEAD START -->
<div class="panel-body"><!-- PANEL BODY START -->
<div class="tab-content">


<!--/************************ GENERATE Filter Form ************************/-->
<a class="btn btn-primary bhu" href="<?php echo base_url();?>index.php/loyalty_program/redeem_request">Pending Request</a>
<a class="btn btn-success bhu" href="<?php echo base_url();?>index.php/loyalty_program/redeem_approved">Aproved Request</a>
<div class="clearfix"></div>
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
	$pagination = '<div>'. $GLOBALS['CI']->pagination->create_links().'<span class="">Total '.$total_rows.' pending Request</span></div>';
	$table .= $pagination;
	
	
	$table .= '
   <div class="clearfix">
   <div class="col-md-12 table-responsive tbpd " >
   <table class="table table-condensed table-bordered">';
	$table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i>Sno</th>  
   <th>Agent Name</th>
   <th>Email id</th>
   <th>Product List</th>
   
  
   
   
   
   </tr></thead><tbody>';
   // debug($table_data);exit;
	if (valid_array($table_data) == true) {
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		
	

		$i=1;
		foreach ($table_data as $k => $v) {

			// $this->load->model('loyalty_program_model');
		// $get_product_list=$this->user_model->get_product_list_name();
			/*$last_login = 'Last Login : '.last_login($v['last_login']);
			$login_status = login_status($v['logout_date_time']);*/
			
			$action="";
			/*$action .="<a  class='btn btn-info btn-lg' href='".base_url()."loyalty_program/redeem_request_approve/".$v['redemelist']['id']."'>approve</a>";*/



			//Booking
			$table .= '<tr>
			<td>'.(++$current_record).'</td>
			
			
			
			
			
			<td>'.$v['redemelist']['agency_name'].'</td>
			<td>'.provab_decrypt($v['redemelist']['email']).'</td>
			
			<td>'.$v['redemelist']['proname']['name'].'</td>
			
			
			
			
			
</tr>';
		
		$i++;
		}
	} else {
		$table .= '<tr><td colspan="8">'.get_app_message('AL005').'</td></tr>';
	}
	$table .= '</tbody></table></div></div>';
	return $table;
}


?>

 


<script>
$(document).ready(function() {
	
	 //set dropdownlist selected
	

});
function updaterate(id){
	var rate=$("#rate_id_"+id).val();
	$status=$.post(app_base_url + "index.php/loyalty_program/update_currency_rate", {id: id,rate:rate}, function(result){

        
    });
    if($status!='')
    {
     toastr.info("deleted Successfully!!!");
     // window.location.reload();
    }
    else
    {
     toastr.info("Not Update!!!");
     // window.location.reload();
    }
}

</script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.js"></script>



