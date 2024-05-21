<!-- HTML BEGIN -->
<?php 
if (is_array($search_params)) {
	extract($search_params);
}//debug($data); die;?>


<div class="bodyContent">
	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-edit"></i> B2C Active Users Reward Reports
			
		
			</div>
		</div>
		
		


<div class="">
	<div class="panel panel-default clearfix"><!-- PANEL WRAP START -->
			<div class="panel-heading"><!-- PANEL HEAD START -->
				
		</div><!-- PANEL HEAD START -->
		<div class="panel-body">
			<h4>Search Panel</h4>
			<hr>
			<form method="GET" autocomplete="off">
				<!-- <input type="hidden" name="created_by_id" value="<?=@$created_by_id?>" > -->
				<div class="clearfix form-group">
					<div class="col-xs-4">
						<label>
						Email
						</label>
						<input type="text" class="form-control" name="email" value="<?=@$email?>" placeholder="Email">
					</div>	
					</div>
					<div class="col-xs-4">
						
					</div>
					<div class="col-xs-4">
					
					<div class="col-xs-4">
						
					</div>
					<div class="col-xs-4">
						
					</div>
					<div class="col-xs-4">
						
					</div>
				</div>
				<div class="col-sm-12 well well-sm">
				<button type="submit" class="btn btn-primary">Search</button> 
				<button type="reset" class="btn btn-warning">Reset</button>
				</div>
			</form>
		</div>
		<div class="clearfix"><!-- PANEL BODY START -->
		
			<?php
			
			echo get_table($table_data, $total_rows);?>
							<?php
function get_table($table_data, $total_rows)
{
	
	$pagination = '<div class="pull-left">'.$GLOBALS['CI']->pagination->create_links().' <span class="">Total '.$total_rows.' users</span></div>';
	$report_data = '';
	$report_data .= '<div id="tableList" class="clearfix table-responsive">';
	$report_data .= $pagination;
	
	$report_data .= '<table class="table table-condensed table-bordered" id="b2b_report_hotel_table">
		<thead>
			<tr>
				<th>S.No</th>
				<th>Email</th>
				<th>Name </th>
				
				<th>Specific Rewards </th>
				<th>Pending Rewards</th>
				<th>Used Rewards</th>
				<th>Total Rewards </th>
				<th>Action</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>S.No</th>
				<th>Email</th>
				<th>Name </th>
				
				<th>Specific Rewards </th>
				<th>Pending Rewards</th>
				<th>Used Rewards</th>
				<th>Total Rewards </th>
				<th>Action</th>
			</tr>
		</tfoot><tbody>';
		
		
			
			$segment_3 = $GLOBALS['CI']->uri->segment(3);
			$current_record = (empty($segment_3) ? 1 : $segment_3);
			
	 foreach ($table_data as $row)
			{ 
				//debug($row); 
		     
				
		 $report_data .= '<form action="'.$_SERVER['PHP_SELF'].'" class="form-horizontal" method="POST" autocomplete="off"><tr>
					<td>'.($current_record++).'</td>
					<td>'.$row['user_name'].'</td>
					<td>'.$row['first_name']. $row['last_name'].'</td>
				<td>'.$row['general_reward']. '	</td>
					<td>'.$row['spefic_reward']. '
					</td>
					<td>'.$row['pending_reward']. '
					</td>
					<td>'.$row['used_reward'].'</td>
						
				 <input type="hidden" name="user_id" value="'.$row['user_id'].'" />
					
				</tr></form>'; 
		      
			} 
	
	$report_data .= '</tbody></table> </div>';
	return $report_data;
} ?>
		</div>
	</div>
</div>
		
		
	
		
	</div><!-- PANEL WRAP END -->
</div>

