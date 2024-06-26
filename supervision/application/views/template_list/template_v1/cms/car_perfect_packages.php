<!-- HTML BEGIN -->
<?php
 //debug($country_list);exit;
?>
<div class="bodyContent">
	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-edit"></i> Perfect Car Packages
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="panel-body"><!-- PANEL BODY START -->
			<fieldset><legend><i class="fa fa-hotel"></i> City List</legend>
				<form action="<?=base_url()?>index.php/cms/car_perfect_packages" enctype="multipart/form-data" class="form-horizontal" method="POST" autocomplete="off">

					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label">Destination Name<span class="text-danger">*</span></label>
						<div class="col-sm-6">
							<select id="country" name="country" class="form-control" required="">
								<option value="INVALIDIP">Please Destination</option>
								<!-- <?=generate_options($country_list)?> -->
								<?php

								foreach ($country_list as $key => $value) {
									//echo "<option value=".$value['origin'].">".$value['Country_Name_EN']."</option>";
									echo "<option value=".$value['origin'].">".$value['Airport_Name_EN']."</option>";
								}
								?>
							</select>
						</div>
					</div>
					<!-- <div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label">City<span class="text-danger">*</span></label>
						<div class="col-sm-6">
							<select id="city" class="form-control" name="city" required="">
								<option value="INVALIDIP">Please Select</option>
							</select>
						</div>
					</div> -->
					
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
					<th>Destination</th>
					<th>Image</th>
					<th>Perfect Packages Publish Status</th>
					<th>Action</th>
				</tr>
				<?php
				//debug($data_list);exit;
				if (valid_array($data_list) == true) {
					foreach ($data_list as $k => $v) :
				?>
					<tr>
						<td><?=($k+1)?></td>
						<td><?=$v['Country_Name_EN']?></td>
						<td><img src="<?php echo $GLOBALS ['CI']->template->domain_images ($v['image2']) ?>" height="100px" width="100px" class="img-thumbnail"></td>
						<td><?php echo get_status_label1($v['car_perfect_packages_status']).get_status_toggle_button1($v['car_perfect_packages_status'], $v['origin']); ?></td>
						<?php /* <td><?php echo get_status_label($v['car_inner_top_destination']).get_status_toggle_button($v['car_inner_top_destination'], $v['origin']) ?></td> */ ?>
						<td><a role="button" href="<?php echo base_url()?>index.php/cms/delete_car_perfect_packages/<?php echo $v['origin'] ?>" class="text-danger">Delete</a></td>
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
<script>
	/*$('#country').on('change', function() {
		var _country = this.value;
		if (_country != 'INVALIDIP') {
			//load city for country
			$.get(app_base_url+'index.php/ajax/get_city_list/'+_country, function(resp) {
				$('#city').html(resp.data);
			});
		}
	});*/
</script>
<?php 
function get_status_label($status)
{
	if (intval($status) == ACTIVE) {
		return '<span class="label label-success"><i class="fa fa-circle-o"></i> '.get_enum_list('status', ACTIVE).'</span>
	<a role="button" href="" class="hide">'.get_app_message('AL0021').'</a>';
	} else {
		return '';
	}
}
function get_status_label1($home_status)
{
	if (intval($home_status) == ACTIVE) {
		return '<span class="label label-success"><i class="fa fa-circle-o">Active</i> '.get_enum_list('home_status', ACTIVE).'</span>
	<a role="button" href="" class="hide">'.get_app_message('AL0021').'</a>';
	} else {
		return '<span class="bg-red-active"><i class="fa fa-circle-o">Inactive</i> '.get_enum_list('home_status', INACTIVE).'</span>
		<a role="button" href="" class="hide">Deactivate</a>';
	}
}
function get_status_toggle_button1($home_status, $origin)
{
	if (intval($home_status) == ACTIVE) {
		return '<a role="button" href="'.base_url().'index.php/cms/deactivate_car_perfect_packages/'.$origin.'" class="text-danger">Deactivate</a>';
	} else {
		return '<a role="button" href="'.base_url().'index.php/cms/activate_car_perfect_packages/'.$origin.'" class="text-success">Activate</a>';
	}
}
function get_status_toggle_button($status, $origin)
{
	if (intval($status) == ACTIVE) {
		return '<a role="button" href="'.base_url().'index.php/cms/deactivate_top_inner_destination_car/'.$origin.'" class="text-danger">Deactivate</a>';
	} else {
		return '';		
	}
}

?>
