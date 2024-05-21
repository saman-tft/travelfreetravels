<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->

			<div class="panel-title">
				<i class="fa fa-edit"></i> Top Destinations In Flight
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="panel-body"><!-- PANEL BODY START -->
			<fieldset><legend><i class="fa fa-plane"></i> Airport List</legend>
			<span style="color:#dd4b39"><?php if(isset($message)){ echo $message; } ?></span>
				<form action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" class="form-horizontal" method="POST" autocomplete="off">

					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label">From Airport<span class="text-danger">*</span></label>
						<div class="col-sm-5">
							<select name="from_airport" class="form-control" required="">
								<option value="INVALIDIP">Please Select</option>
								<?=generate_options($flight_list)?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label">To Airport<span class="text-danger">*</span></label>
						<div class="col-sm-5">
							<select name="to_airport" class="form-control" required="">
								<option value="INVALIDIP">Please Select</option>
								<?=generate_options($flight_list)?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label">Image<span class="text-danger">*</span></label>
						<div class="col-sm-6">
							<input type="file" class="" accept="image/*" required="required" name="top_destination">
						</div>
					</div>

					<div class="well well-sm">
						<div class="clearfix col-md-offset-1">
							<button class=" btn btn-sm btn-success " type="submit">Add</button>
						</div>
					</div>
				</form>
			</fieldset>
		</div><!-- PANEL BODY END -->
		<div class="panel-body">
			<table class="table table-condensed">
				<tr>
					<th>Sno</th>
					<th>From City</th>
					<th>To City</th>
					<th>Image</th>
					<th>Action</th>
				</tr>
				<?php
				//debug($data_list);exit;
				if (valid_array($data_list) == true) {
					foreach ($data_list as $k => $v) :
				?>
					<tr>
						<td><?=($k+1)?></td>
						<td><?=$v['from_airport_name']?></td>
						<td><?=$v['to_airport_name']?></td>
						<td><img src="<?php echo $GLOBALS ['CI']->template->domain_images ($v['image']) ?>" height="100px" width="100px" class="img-thumbnail"></td>
						<td><?php echo get_status_label($v['status']).get_status_toggle_button($v['status'], $v['origin']) ?></td>
					</tr>
				<?php
					endforeach;
				} else {
					echo '<tr><td>No Data Found</td></tr>';
				}
				?>
			</table>
		</div>
	</div><!-- PANEL WRAP END -->
</div>
<?php 
function get_status_label($status)
{
	if (intval($status) == ACTIVE) {
		return '<span class="label label-success"><i class="fa fa-circle-o"></i> '.get_enum_list('status', ACTIVE).'</span>
	<a role="button" href="" class="hide">'.get_app_message('AL0021').'</a>';
	} else {
		return '<span class="bg-red-active"><i class="fa fa-circle-o"></i> '.get_enum_list('status', INACTIVE).'</span>
		<a role="button" href="" class="hide">Deactivate</a>';
	}
}

function get_status_toggle_button($status, $origin)
{
	if (intval($status) == ACTIVE) {
		return '<a role="button" href="'.base_url().'index.php/cms/deactivate_flight_top_destination/'.$origin.'" class="text-danger">Deactivate</a>';
	} else {
		return '<a role="button" href="'.base_url().'index.php/cms/activate_flight_top_destination/'.$origin.'" class="text-success">Activate</a>';
	}
}

?>
