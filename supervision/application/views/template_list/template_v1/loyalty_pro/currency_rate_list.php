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
			<label>Currency</label>
			<input type="text" placeholder="Currency" value="<?=@$country?>" name="country" class="search_filter form-control">
		</div>
		
		
		
		
	</div>
	<div class="col-sm-12 well well-sm">
		<button class="btn btn-primary" type="submit">Search</button> 
		<button class="btn btn-warning" type="reset">Reset</button>&nbsp
		<button class="btn btn-danger" id="clear_search_filters">Clear Search Filter<i class="fa fa-close"></i></button>
	</div>
</form>
<div class="clearfix"></div>
<form method="POST" autocomplete="off" id="range_point_form" action="<?php echo base_url();?>index.php/loyalty_program/add_save_currency_code">
  
 <div class="clearfix form-group">
  <div class="col-xs-4">
      <label>Country name</label>
     	<select id="country_code" name="country_code" class="search_filter form-control" required="">
		  <option value="">Select country name</option>
		  <?php 

		  	if(!empty($country_list)){

		  		foreach ($country_list as $key => $value) {
		  			
		  		
		  			?>
		 			 <option value="<?php echo $value['currency_code'];?>"><?php echo $value['country_name'];?></option>
		 			<?php
		 		}
		 	}

		 			?>
		  
		</select>
  </div>
   <div class="clearfix form-group">
  <div class="col-xs-4">
      <label>points wise currency conversion</label>
      <input type="text" placeholder="points wise currency conversion" value="" name="rate" id="rate" class="search_filter form-control" required="">
  </div>
</div>
   
  <span class="colorred"></span>
    <!-- <div class="col-xs-4">
      <label>Reward point</label>
      <input type="text" placeholder="Reward point"  name="reward_point" class="search_filter form-control" value="<?=@$hreward_point?>">
    </div> -->
    
    
    
  </div>
  <div class="col-sm-12 well well-sm">
    <button class="btn btn-primary" type="submit" id="range_button">Submit</button> 

    
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
	$pagination = '<div>'. $GLOBALS['CI']->pagination->create_links().'<span class="">Total '.$total_rows.' Currency</span></div>';
	$table .= $pagination;
	
	
	$table .= '
   <div class="clearfix">
   <div class="col-md-12 table-responsive tbpd " >
   <table class="table table-condensed table-bordered">';
	$table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i>Sno</th>
   <th>Action</th>
   <th>Country</th>
   <th>Name</th>
   <th>Points wise currency conversion</th>
   
   
  
   
   
   
   </tr></thead><tbody>';
   // debug($table_data);exit;
	if (valid_array($table_data) == true) {
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		
	

		$i=1;
		foreach ($table_data as $k => $v) {

			/*$last_login = 'Last Login : '.last_login($v['last_login']);
			$login_status = login_status($v['logout_date_time']);*/
			
			$action="";
			$action .="<a type='button' class='sidedis sideicbb1' onclick='updaterate(".$v['id'].")'>Update</a>";



			//Booking
			$table .= '<tr>
			
			<td>'.(++$current_record).'</td>
			<td>
			<div class="dropdown2" role="group">
				   <div class="dropdown slct_tbl pull-left sideicbb">
					   <i class="fa fa-ellipsis-v"></i>  
					    <ul class="dropdown-menu sidedis" style="display: none;">
						   <li>
						 '.$action.'
						   </li>
						</ul>
				    </div>
				</div>
			
			</td>
			<td>'.$v['country_name'].'</td>
			<td>'.$v['country'].'</td>
			
			<td><input type=text" id="rate_id_'.$v['id'].'" name="rate_id_'.$v['id'].'" value="'.$v['rate'].'"></td>
			
			
			
			
			
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
function updaterate(id){
	var rate=$("#rate_id_"+id).val();
	if(rate >0)
	{
		$status=$.post(app_base_url + "index.php/loyalty_program/update_currency_rate", {id: id,rate:rate}, function(result){

        
	    });
	    if($status!='')
	    {
	     toastr.info("Updated Successfully!!!");
	     // window.location.reload();
	    }
	    else
	    {
	     toastr.info("Not Update!!!");
	     // window.location.reload();
	    }
	}
	else
	{
		toastr.info("Please enter points");
	}
	
}

</script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.js"></script>



