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


<!--/************************ GENERATE Filter Form ************************/-->
<h4>Search Panel</h4>

<hr>
<form method="GET" autocomplete="off" id="search_filter_form">
	<input type="hidden" name="user_status" value="<?=@$user_status?>" >
	<div class="clearfix form-group">
		<div class="col-xs-4">
			<label>Agency Name</label>
			<input type="text" placeholder="Agency Name" value="<?=@$agency_name?>" name="agency_name" class="search_filter form-control">
		</div>
		<div class="col-xs-4">
			<label>Agency email id</label>
			<input type="text" placeholder="Agency email id" value="<?=@$user_name?>" name="user_name" class="search_filter form-control">
		</div>
		<div class="col-xs-4">
			<label>Agency mobile number</label>
			<input type="text" placeholder="phone number" value="<?=@$phone?>" name="phone" class="search_filter form-control">
		</div>
		
		
		
	</div>
	<div class="col-sm-12 well well-sm">
		<button class="btn btn-primary" type="submit">Search</button> 
		<button class="btn btn-warning" type="reset">Reset</button>&nbsp
		<button class="btn btn-danger" id="clear_search_filters">Clear Search Filter <i class="fa fa-close"></i></button>
	</div>
</form>
<!-- <div class="dropdown col-xs-3 btmr">
                    <button class="btn btn-info dropdown-toggle" type="button" id="excel_imp_drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-download" aria-hidden="true"></i> Excel
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="excel_imp_drop">
                        <li>
                            <a href="">Confirmed Booking</a>
                        </li>
                        <li>
                            <a href="">Cancelled Booking</a>
                        </li>
                    </ul>
                </div> -->
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
	$pagination = '<div>'. $GLOBALS['CI']->pagination->create_links().'<span class="">Total '.$total_rows.' agents</span></div>';
	$table .= $pagination;
	
	
	$table .= '
   <div class="clearfix">
   <div class="col-md-12 table-responsive tbpd " >
   <table class="table table-condensed table-bordered">';
	$table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i>Sno</th>
   
   <th>Agency Name</th>
   <th>Agency email id</th>
   <th>Phone</th>
   <th>Total reward point</th>
   <th>Hotel</th>
   <th>Transfer</th>
   <th>Holidays</th>
   <th>Excursion</th>
   <th>Visa</th>
  
   
   
   
   </tr></thead><tbody>';
   // debug($table_data);exit;
	if (valid_array($table_data) == true) {
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		$rep_url = base_url().'index.php/report/';
		$dep_url = base_url().'index.php/management/b2b_balance_manager';
	

		$i=1;
		foreach ($table_data as $k => $v) {

			/*$last_login = 'Last Login : '.last_login($v['last_login']);
			$login_status = login_status($v['logout_date_time']);*/
			
			

			//Booking
			$table .= '<tr>
			<td>'.(++$current_record).'</td>
			
			<td>'.(empty($v['agency_name']) == false ? $v['agency_name'] : 'Not Added' ).'</td>
			
			<td>'.provab_decrypt($v['email']).'</td>
			<td>'.$v['phone'].'</td>
			
			<td>'.$v['t_reward'].'</td>
			<td>'.$v['hotel'].'</td>
			<td>'.$v['transfer'].'</td>
			<td>'.$v['holidays'].'</td>
			<td>'.$v['activities'].'</td>
			<td>'.$v['visa'].'</td>
			
			
			
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
	$('#clear_search_filters').click(function(){
    $('.search_filter', "form#search_filter_form").val('');
    $("form#search_filter_form").submit();
  });

	
	 //set dropdownlist selected
	

});

</script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.js"></script>



