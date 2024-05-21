<label for="field-1" class="col-sm-3 control-label">City</label>									
<div class="col-sm-5">
	<select id="city_list" name="city_list" class="selectboxit">
		<?php if($cities_list!=''){ foreach ($cities_list as $city_list){ ?>
			<option value="<?php echo $city_list->city_details_id; ?>" data-iconurl=""><?php echo $city_list->city_name; ?></option>
		<?php } } ?>
	</select>
</div>
