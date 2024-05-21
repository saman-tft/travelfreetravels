<!-- HTML BEGIN -->
<?php
 //debug($country_list);exit;
//debug($data_list[0]['country_code']);
?>
<div class="bodyContent">
	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-edit"></i> Top Destinations In Hotel
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="panel-body"><!-- PANEL BODY START -->
		<?php //debug($data_list[0]['origin']);die;?>
			<fieldset><legend><i class="fa fa-hotel"></i> City List</legend>
				<form action="<?=base_url().'index.php/cms/hotel_top_destinations'?>" enctype="multipart/form-data" class="form-horizontal" method="POST" autocomplete="off">
				<?php
				$data = $this->input->get();
				//debug($data['id']);
				?>
					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label">Country<span class="text-danger">*</span></label>
						<div class="col-sm-6">
							<select id="country" name='country' class="form-control" required="">
								<option value="INVALIDIP">Please Select</option>
								<?php if(!empty($data['id'])){  ?>

								<?=generate_options($country_list,$data_list[0]['country_code'])?>

								<?php }else{?>
								<?=generate_options($country_list)?>
								<?}?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label">City<span class="text-danger">*</span></label>
						<div class="col-sm-6">
						<?php if(!empty($data['id'])){ ?>
							<input type="hidden" id="cityid" value="<?php echo @$data['id']?>">
							<?php }?>
							<select id="city" class="form-control" name="city" required="">
								<option value="INVALIDIP">Please Select</option>
							</select>
						</div>
					</div>

					<!-- <div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label">Minimum Price(in Rs)<span class="text-danger">*</span></label>
						<div class="col-sm-6">
						<?php
						if(isset($id))
{
	?>
	<input type='hidden' name='htl_id' value="<?php echo $data_list[0]['origin']?>">
							<input id="mprice" class="form-control" name="mprice" value="<?php echo $data_list[0]['mprice']?>" type='number' min='1' required="">
								<?php
					}
					else
					{
						?>
<input id="mprice" class="form-control" name="mprice"  type='number' min='1' required="">
						<?php
					}
						?>
								
						</div>
					</div> -->
					
					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label">Image<span class="text-danger">*</span></label>
						<div class="col-sm-6">
						<?php
						$required = 'required="required"';
						if(isset($id))
{
	$required = '';
	?>
						<input type="hidden" id="imagename" name="imagename" value="<?=$data_list[0]['image']?>">
						<img name="top_destination" src="<?php echo $GLOBALS ['CI']->template->domain_images ($data_list[0]['image']) ?>" height="100px" width="100px" class="img-thumbnail">
						<?php
					}
						?>
							<input type="file" id="imgfile" class="" accept="image/*" <?=$required?>  name="top_destination">
						</div>
					</div>

					<div class="well well-sm">
						<div class="clearfix col-md-offset-1">
						<?php
if(isset($id))
{
	$txt='Update';
}
else
{
	$txt='Add';
}
						?>
							<button class=" btn btn-sm btn-success " type="submit"><?php echo $txt?></button>
						</div>
					</div>
				</form>
			</fieldset>
		</div><!-- PANEL BODY END -->
<?php
if(!isset($id))
{
?>
		<div class="panel-body">
			<table class="table table-condensed">
				<tr>
					<th>Sno</th>
					<th>City</th>
					<th>Country</th>
					<th>Image</th>
					<!-- <th>Minimum Price(in Rs)</th> -->
					<th>Action</th>
				</tr>
				<?php
				//debug($data_list);exit;
				if (valid_array($data_list) == true) {
					foreach ($data_list as $k => $v) :
				?>
					<tr>
						<td><?=($k+1)?></td>
						<td><?=$v['city_name']?></td>
						<td><?=$v['country_name']?></td>
						<td><img src="<?php echo $GLOBALS ['CI']->template->domain_images ($v['image']) ?>" height="100px" width="100px" class="img-thumbnail"></td>
						<!-- <td><?=$v['mprice']?></td> -->
						<td>
<a href="<?php echo base_url(); ?>index.php/cms/hotel_top_destinations?id=<?php echo $v['origin']?>" class="btn btn-primary">update</a><br>
<?php
					echo get_status_label($v['top_destination']).get_status_toggle_button($v['top_destination'], $v['origin']) ?></td>
					</tr>
				<?php
					endforeach;
				} else {
					echo '<tr><td>No Data Found</td></tr>';
				}
				?>
			</table>
		</div>
		<?php
}
		?>
	</div><!-- PANEL WRAP END -->
</div>
<script>

	var _city_id = $("#cityid").val();
	if(_city_id != undefined){

		var _country =$("#country").val();
		$.get(app_base_url+'index.php/ajax/get_city_list/'+_country, function(resp) {
				$('#city').html(resp.data);
			});
	}
	$('#imgfile').on('change',function(){

		$("#imagename").val('');
	});
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

?>
