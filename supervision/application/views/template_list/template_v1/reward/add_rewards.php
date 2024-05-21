<!-- HTML BEGIN -->
<?php 
if (is_array($search_params)) {
	extract($search_params);
 }  //debug($data); die;?>

<div class="bodyContent">
	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-edit"></i> B2C Active Users Reward Points
			
		
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="panel-body"><!-- PANEL BODY START -->
			<fieldset><legend>B2C - General Rewards </legend>
				<form action="<?=$_SERVER['PHP_SELF']?>" class="form-horizontal" method="POST" >
					
					
						<div class="clearfix form-group">
							<div class="col-xs-4">
							
								<label> General Rewards for active B2C users</label>
								<input type="number" id="generic_value" max="1000000000" min="0" autocomplete ="off" name="general_reward" class="form-control" placeholder="Reward Point" required="" value="" />
								Note : Application Default Currency 
								
							</div>
							
						</div>
						
					
					
						<div class="col-sm-12 well well-sm">
						
							<button class=" btn btn-primary" id="general-markup-submit-btn" type="submit">Add</button>
							
						&nbsp;&nbsp;
							<button class=" btn btn-warning" id="general-markup-reset-btn" type="reset">Reset</button>
					
						</div>
				</form>
			</fieldset>
		</div><!-- PANEL BODY END -->
		
		


<div class="">
	<div class="panel panel-default clearfix"><!-- PANEL WRAP START -->
			<div class="panel-heading"><!-- PANEL HEAD START -->
				
		</div><!-- PANEL HEAD START -->
		<div class="panel-body">
			<h4>Search Panel</h4>
			<hr>
			<form method="GET" action="<?php echo base_url() ?>reward/add_rewards" autocomplete="off">
				<!-- <input type="hidden" name="created_by_id" value="<?=@$created_by_id?>" > -->
				<div class="clearfix form-group">
					<div class="col-xs-4">
						<label>
						Email
						</label>
						<input type="text" class="form-control" maxlength="50" name="email" value="<?=@$email?>" placeholder="Email">
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
	
	$pagination = '<div class="row"><div class="pull-left">'.$GLOBALS['CI']->pagination->create_links().' <span class="">Total '.$total_rows.' users</span></div>';
	$report_data = '';
	$report_data .= '<div id="tableList" class="clearfix table-responsive">';
	$report_data .= $pagination;
	
	$report_data .= '<table class="table table-condensed table-bordered" id="b2b_report_hotel_table">
		<thead>
			<tr>
				<th>S.No</th>
				<th>Email</th>
				<th>Name </th>
				<th>General Rewards </th>
				<th>Specific Rewards </th>
				<th>Pending Rewards</th>
				<th>Used Rewards</th>
			
				<th>Action</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>S.No</th>
				<th>Email</th>
				<th>Name </th>
				<th>General Rewards </th>
				<th>Specific Rewards </th>
				<th>Pending Rewards</th>
				<th>Used Rewards</th>
			
				<th>Action</th>
			</tr>
		</tfoot><tbody>';
		
		
			
			$segment_3 = $GLOBALS['CI']->uri->segment(3);
			$current_record = (empty($segment_3) ? 1 : $segment_3);
			
	 foreach ($table_data as $row)
			{ 
				// debug($row); exit();
		     
				
		 $report_data .= '<form  class="form-horizontal" method="POST" autocomplete="off"><tr>
					<td>'.($current_record++).'</td>
					<td>'.provab_decrypt($row['email']).'</td>
					<td>'.$row['first_name']. $row['last_name'].'</td>
				<td>'.$row['general_reward']. '
					</td>
					<td><input type="number" class="form-control"  max="1000"  id="value-type-plus-" name="spefic_reward" checked="checked" value="'.$row['spefic_reward'].'" > </td>
					<td>'.$row['pending_reward']. '
					</td>
					<td>'.@$row['used_reward'].'</td>
					<td><button class=" btn btn-xs btn-info " type="submit">Save</button>&nbsp;&nbsp;
                   
				 <input type="hidden" name="user_id" value="'.$row['user_id'].'" />
				 <input type="hidden" name="email" value="'.$row['email'].'" />
					
				</tr></form>'; 
		      
			} 
	
	$report_data .= '</tbody></table> </div></div>';
	return $report_data;
} ?>
		</div>
	</div>
</div>
		
		
		<!-- <div class="panel-body">PANEL BODY START 
			<fieldset><legend><i class="fa fa-plane"></i> B2C users - Specific Reward</legend>
				
					<div class="row">
							
							<div class="col-md-2">
								Email
							</div>
							<div class="col-md-2">
								Name
							</div>
							
							<div class="col-md-2">
								<div class="radio">
									Specific Rewards
									
								</div>
							</div>
							<div class="col-md-2">
								Pending  Rewards
							</div>
							<div class="col-md-2">
								Used  Rewards
							</div>
							<div class="well well-sm">
					<div class="clearfix col-md-offset-1">
						Action
					</div>
				</div>
							
						</div>
				
						<?php foreach ($data as $row)
						{ ?>
						<form action="<?=$_SERVER['PHP_SELF']?>" class="form-horizontal" method="POST" autocomplete="off">
						<input type="hidden" name="user_id" value="<?=$row['user_id']?>" />
						<div class="row">
							
							<div class="col-md-2">
								<?=$row['user_name']?>
							</div>
							<div class="col-md-2">
								<?=$row['first_name'] ?> <?=$row['last_name'] ?>
							</div>
							
							<div class="col-md-2">
								<div class="radio">
									<label class="hide col-sm-4 control-label">Reward</label>
									<label for="value-type-plus-" class="radio-inline">
										<input type="text"  id="value-type-plus-" name="spefic_reward" checked="checked" value="<?=$row['spefic_reward']?>" > 
									</label>
									
								</div>
							</div>
							<div class="col-md-2">
								<?=$row['pending_reward'] ?>
							</div>
							<div class="col-md-2">
								<?=$row['used_reward'] ?>
							</div>
							<div class="well well-sm">
					<div class="clearfix col-md-offset-1">
						<button class=" btn btn-sm btn-success " type="submit">Save</button>
						<button class=" btn btn-sm btn-warning " type="reset">Reset</button>
					</div>
				</div>
							
						</div>
						</form>
						<?php } ?>
						<hr>
				

				
			</fieldset>
		</div> PANEL BODY END -->
		
	</div><!-- PANEL WRAP END -->
</div>
