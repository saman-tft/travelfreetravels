<div id="package_types" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-toggle="tab"><h1>Add / Edit
								Vehicle Category</h1></a></li>
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
				</ul>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="fromList">
					<div class="col-md-12">
						<div class='row'>
							<div class='col-sm-12'>
								<div class=''>
									<div class='box-header '>
										<div class='title' id="tab1">  <?php if(isset($status)){echo $status;}?></div>
										<div class='actions'></div>
									</div>
									<div class=''>
										<form class='form form-horizontal validate-form'
											style='margin-bottom: 0;'
											action="<?php echo base_url(); ?>index.php/privatecar/save_category_type"
											method="post" name="frm1" enctype="multipart/form-data">
											<div class='form-group'>
												<label class='control-label col-sm-2' for='validation_name'>Vehicle Category <span class="text-danger">*</span></label>
												<div class='col-sm-3 controls'>
													<input type="hidden" name="category_id"
														value="<?=@$pack_data[0]->category_id;?>"> <input
														class='form-control' data-rule-minlength='2'
														data-rule-required='true' id='name' name='name'
														value="<?=@$pack_data[0]->category_name;?>"
														placeholder='Vehicle Category Name' type='text' required> <span id="error-name"
														style="color: #F00; display: none;">Please Enter Vehicle Category</span>
												</div>
											</div>
												<div class='form-group'>
								<label class='control-label col-sm-2' for='adult'>Maxmium Age <span class="text-danger">*</span></label>
								<div class='col-sm-4 controls'>
									<input type="number" name="maximium_age" id="maximium_age"
											value="<?=@$pack_data[0]->maximium_age;?>"
										placeholder="Maxmium Age"
										class='form-control numeric' maxlength='10'
										minlength='3' required>
										<span id="error-maximium_age"
														style="color: #F00; display: none;">Please Enter valid Maxmium Age</span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-2' for='adult'>Minimium Age <span class="text-danger">*</span></label>
								<div class='col-sm-4 controls'>
									<input type="number" name="minimium_age" id="minimium_age"
											value="<?=@$pack_data[0]->minimium_age;?>"
										placeholder="Minimium Age"
										class='form-control numeric' maxlength='10'
										minlength='3' required>
										<span id="error-minimium_age"
														style="color: #F00; display: none;">Please Enter valid Minimium Age</span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-2' for='adult'>Young Driver Charge</label>
								<div class='col-sm-4 controls'>
									<input type="number" name="young_driver" id="infant_price"
											value="<?=@$pack_data[0]->Young_driver_charge;?>"
										placeholder="Young Driver Charge"
										class='form-control numeric' >
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-2' for='adult'>Senior Driver Charge</label>
								<div class='col-sm-4 controls'>
									<input type="number" name="senior_driver" id="infant_price"
											value="<?=@$pack_data[0]->Senior_driver_charge;?>"
										placeholder="Senior Driver Charge"
										class='form-control numeric' >
								</div>
							</div>
								
											<div class='form-actions' style='margin-bottom: 0'>
												<div class='row'>
													<div class='col-sm-9 col-sm-offset-2'>
														<a
															href="<?php echo base_url(); ?>index.php/privatecar/view_category_types">
															<button class='btn btn-primary' type='button'>
																<i class='icon-reply'></i> Back
															</button>
														</a>&nbsp;
														<button id="submitbtn" class='btn btn-primary' type='submit'>
															<i class='icon-save'></i> Save
														</button>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL WRAP END -->
</div>

<script>

$(document).ready(function(){
    $('#submitbtn').click(function () {
        let name = $('#name').val();
        let maximiumAge = $('#maximium_age').val();
        let minimiumAge = $('#minimium_age').val();
            
        if (name =="") {
            $('#name').next().show();
            return false;
        }else if(maximiumAge == "" || maximiumAge <20 || maximiumAge >80){
            $('#minimium_age').val("");
            $('#minimium_age').next().show();
            return false;
        }else if(minimiumAge == "" || minimiumAge <20 || minimiumAge >80){
            $('#minimium_age').val("");
            $('#minimium_age').next().show();
            return false;
        }else {
            return true;
        }
    });
});
    $(document).on('change blur paste', '#name,#maximium_age,#minimium_age', function() {
        checKrequire(this);
    });
    $(document).on('change blur paste', '#maximium_age,#minimium_age', function() {
        checKAge(this);
    });
    function checKrequire(item){
        if($(item).val() ==""){
            $(item).next().show();
        }else{
            $(item).next().hide();
        }
    }
    function checKAge(item){
        if($(item).val() <20 || $(item).val() >80 ){
            $(item).val("");
            $(item).next().show();
        }else{
            $(item).next().hide();
        }
    }
</script>