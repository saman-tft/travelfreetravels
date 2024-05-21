<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="panel-heading">
			
				<i class="fa fa-edit"></i> <strong>B2C Users Reward Rate
				<span class="pull-right"></strong></span>
		</div><!-- PANEL HEAD START -->
		<div class="panel-body"><!-- PANEL BODY START -->
			<fieldset>
				<form action="<?=base_url()?>index.php/reward/reward_conversion" class="form-horizontal" method="POST" autocomplete="off">
					<div class="hide">
					
						
					</div>
					<div class="row">
						<div class="col-md-4">
						
							<label for="value_type" class="col-sm-4 control-label">Reward Point<span class="text-danger">*</span></label>
							<label for="value_type_plus" class="radio-inline">
							<input type="number" name="reward_point" max="1000" min="1"  id="value_type_plus"  class="form-control value_type_plus radioIp" value="<?php echo $data['0']['reward_point']?>" required="">  
							</label>
						
						</div>
						<div class="col-md-1"  style="margin: 15px 0;"> = </div>
						<div class="col-md-4">
						    <label for="value_type" class="col-sm-4 control-label">Price(NPR)<span class="text-danger">*</span></label>
							<label for="value_type_percent" class="radio-inline">
							<input  type="number"  name="currency_value" max="100000000" min="1" value="<?php echo $data['0']['currency_value']?>" id="value_type_percent"  class="form-control value_type_percent radioIp" required=""> 
							<input type="hidden" name="currency" value="">
							<input type="hidden" name="origin" value="1">
							</label>
						</div>
						<div class="col-md-3">
						</div>
					</div>
						<div class="row">
							<div class="col-md-4">
								<label for="value_type" class="col-sm-4 control-label">Min Limit<span class="text-danger">*</span></label>
								<label for="value_type_plus" class="radio-inline">
								<input type="number" name="reward_min" max="100000000" min="1"  id="value_type_plus"  class="form-control value_type_plus radioIp" value="<?php echo $data['0']['reward_min']?>" required="">  
							</label>
							</div>
							<div class="col-md-1" style="margin: 15px 0;"> = </div>
							<div class="col-md-4">
								<label for="value_type" class="col-sm-4 control-label">Max Limit<span class="text-danger">*</span></label>
								<label for="value_type_percent" class="radio-inline">
								<input  type="number"  name="reward_max" max="100000000" min="1" value="<?php echo $data['0']['reward_max']?>" id=""  class="form-control value_type_percent radioIp" required=""> 
								
							</label>
							</div>
							<div class="col-md-3">
								<button class=" btn btn-sm btn-success " id="general-markup-submit-btn" type="submit">Save</button>&nbsp;
								
							</div>
							</div>
						

					</div>
					
				</form>
			</fieldset>
		</div><!-- PANEL BODY END -->
		
				
				
	</div><!-- PANEL WRAP END -->

					<!-- <div class="panel panel-primary">
						<div class="panel-heading">
							<i class="fa fa-edit"></i> 
								<strong>B2C Users Limit <span class="pull-right"></span></strong>
						</div>
						<div class="panel-body">
							<div class="row">
							<div class="col-md-4">
								<label for="value_type" class="col-sm-4 control-label">Min Limit<span class="text-danger">*</span></label>
								<label for="value_type_plus" class="radio-inline">
								<input type="number" name="reward_min" max="100000000" min="0"  id="value_type_plus"  class="form-control value_type_plus radioIp" value="<?php echo $data['0']['reward_max']?>" required="">  
							</label>

							</div>
							<div class="col-md-1"> = </div>
							<div class="col-md-4">
								<label for="value_type" class="col-sm-4 control-label">Max Limit<span class="text-danger">*</span></label>
								<label for="value_type_percent" class="radio-inline">
								<input  type="number"  name="reward_max" max="100000000" min="0" value="<?php echo $data['0']['reward_max']?>" id=""  class="form-control value_type_percent radioIp" required=""> 
								
							</label>
							</div>
							<div class="col-md-3">
								<button class=" btn btn-sm btn-success " id="general-markup-submit-btn" type="submit">Save</button>&nbsp;
								<button class=" btn btn-sm btn-warning " id="general-markup-reset-btn" type="reset">Reset</button>
							</div>
							</div>

						</div>
					</div> -->
</div>
