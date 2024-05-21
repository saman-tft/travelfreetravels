<!-- HTML BEGIN -->
<?php
 //debug($country_list);exit;
?>
<div class="bodyContent">
	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-edit"></i> Top Destinations In Activity
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="panel-body"><!-- PANEL BODY START -->
			<fieldset><legend><i class="fa fa-hotel"></i> City List</legend>
				<form action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" class="form-horizontal" method="POST" autocomplete="off">
					<input type="text" name="origin" value="<?php  echo $data['origin']; ?>" hidden>
					<input type="text" name="dbimage" value="<?php  echo $data['image']; ?>" hidden>
<div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label">Nationality<span class="text-danger">*</span></label>
						<div class="col-sm-6">
							<select id="nationality"  name="country" class="form-control" required="">
								<option value="INVALIDIP">Please Select</option>
								<?=generate_options($country_list,array($data['nationality']))?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label">Country<span class="text-danger">*</span></label>
						<div class="col-sm-6">
							<select id="country"  class="form-control countrigger" required="">
								<option value="INVALIDIP">Please Select</option>
								<?=generate_options($country_list,array($data['country_code']))?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label">City<span class="text-danger">*</span></label>
						<div class="col-sm-6">
							<select id="city" class="form-control" name="city" required="">
								<option value="INVALIDIP">Please Select</option>
								<?=generate_options($city_data,array($get_city_data))?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label">URL<span class="text-danger">*</span></label>
						<div class="col-sm-6">
						    <input type="text" class="form-control" name="url" value="<?php  echo $data['url']; ?>" placeholder="url" />
						</div>
					</div>
					
					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label">Image<span class="text-danger">*</span></label>
						<div class="col-sm-6">
							<input type="file" class="" accept="image/*"  name="top_destination">
							<?php
if($idv!="")
{
	?>
							<img src="https://travelfreetravels.com/extras/custom/TMX6244821650276433/images/<?php echo $data['image']; ?>" width="25%" height="25%"/>
							<?php

						}

						?>
						</div>
					</div>

					<div class="well well-sm">
						<div class="clearfix col-md-offset-1">
                                   <?php
if($idv!="")
{
	?>
	<button class=" btn btn-sm btn-success " type="submit">Update</button>
	<?php
}
else
{
                                   ?>

														<button class=" btn btn-sm btn-success " type="submit">Add</button>
														<?php

}
														?>
						</div>
					</div>
				</form>
			</fieldset>
		</div><!-- PANEL BODY END -->
		<div class="panel-body">
			<table class="table table-condensed">
				<tr>
					<th>Sno</th>
					<th>Nationality</th>
					<th>City</th>
					<th>Url</th>
					<th>Image</th>
					<th>Home Page Publish Status</th>
					<th>Action</th>
				</tr>
				<?php
				//debug($data_list);exit;
				if (valid_array($data_list) == true) {
					foreach ($data_list as $k => $v) :
				?>
					<tr>
						<td><?=($k+1)?></td>
						<th><?=$v['nationality']?></th>
						
						<td><?=$v['city_name']?></td>
							<td><?=$v['url']?></td>
						<td><img src="<?php echo $GLOBALS ['CI']->template->domain_images ($v['image']) ?>" height="100px" width="100px" class="img-thumbnail"></td>
						<td><?php echo get_status_label1($v['home_status']).get_status_toggle_button1($v['home_status'], $v['origin']); ?></td>
						<td><?php echo get_status_label($v['top_destination']).get_status_toggle_button($v['top_destination'], $v['origin']) ?>
						
						
					<a role="button" href="<?php echo base_url()?>index.php/cms/activityv1_top_destinations/<?php echo $v['origin'] ?>" class="text-danger">Edit</a>
						
						</td>
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
$("#country").trigger("change");
	$('#country').on('change', function() {
		var _country = this.value;
		if (_country != 'INVALIDIP') {
			//load city for country
			$.get(app_base_url+'index.php/ajax/get_city_list/'+_country, function(resp) {
				$('#city').html(resp.data);
			});
		}
	});
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

function get_status_toggle_button($status, $origin)
{
	if (intval($status) == ACTIVE) {
		return '<a role="button" href="'.base_url().'index.php/cms/deactivate_top_destination/'.$origin.'" class="text-danger">Deactivate</a>';
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
		return '<a role="button" href="'.base_url().'index.php/cms/deactivate_top_destination_home/'.$origin.'" class="text-danger">Deactivate</a>';
	} else {
		return '<a role="button" href="'.base_url().'index.php/cms/activate_top_destination_home/'.$origin.'" class="text-success">Activate</a>';
	}
}

?>
