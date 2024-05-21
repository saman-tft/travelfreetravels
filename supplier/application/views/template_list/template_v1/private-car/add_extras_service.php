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
								Extra Services</h1></a></li>
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
											action="<?php echo base_url(); ?>index.php/privatecar/save_extra_service"
											method="post" name="frm1" enctype="multipart/form-data">
											<div class='form-group'>
												<label class='control-label col-sm-2' for='validation_name'>Service Name</label>
												<div class='col-sm-3 controls'>
													<input type="hidden" name="Equipid"
														value="<?=@$pack_data[0]->Equipid;?>">
															<select class='form-control' data-rule-required='true' name='PolicyName'
                                        id="PolicyName" required>

                                        <option value="">Select PolicyName</option>
                                        <option value='Full Protection'>Full Protection</option>
                                         <option value='Add Driver'>Add Driver</option>
                                        <option value='Booster Seat (4-12 years)'>Booster Seat (4-12 years)</option>
                                        <option value='Child Seat (1-3 years)'>Child Seat (1-3 years)</option>
                                        <option value='Infant'>Infant Seat (0-1 year) </option>
                                        <option value='GPS (Global Positioning System)'> GPS (Global Positioning System)</option>

                                    </select>
													
														
													
												</div>
											</div>
										
										
										
											<div class='form-group'>
												<label class='control-label col-sm-2' for='validation_name'>Amount</label>
												<div class='col-sm-3 controls'>
											
																<input
														class='form-control' data-rule-minlength='2'
														data-rule-required='true' id='pname' name='Amount'
														value="<?=@$pack_data[0]->Amount;?>"
														placeholder='Amount' type='text' required>
														
														
													
												</div>
											</div>
											<div class='form-group'>
												<label class='control-label col-sm-2' for='validation_name'>DetailedInformation</label>
												<div class='col-sm-3 controls'>
											
																<input
														class='form-control' data-rule-minlength='2'
														data-rule-required='true' id='pname' name='DetailedInformation'
														value="<?=@$pack_data[0]->DetailedInformation;?>"
														placeholder='DetailedInformation' type='text' required>
														
														
													
												</div>
											</div>
                                            	<div class='form-group'>
												<label class='control-label col-sm-2' for='validation_name'>Underwriter</label>
												<div class='col-sm-3 controls'>
											
																<input
														class='form-control' data-rule-minlength='2'
														data-rule-required='true' id='pname' name='Underwriter'
														value="<?=@$pack_data[0]->Underwriter;?>"
														placeholder='Underwriter' type='text' required>
														
														
													
												</div>
											</div>
											<div class='form-group'>
												<label class='control-label col-sm-2' for='validation_name'>PolicyUrl</label>
												<div class='col-sm-3 controls'>
											
																<input
														class='form-control' data-rule-minlength='2'
														data-rule-required='true' id='pname' name='PolicyUrl'
														value="<?=@$pack_data[0]->PolicyUrl;?>"
														placeholder='PolicyUrl' type='text' required>
														
														
													
												</div>
											</div>
												<div class='form-group'>
												<label class='control-label col-sm-2' for='validation_name'>Disclaimer</label>
												<div class='col-sm-3 controls'>
											
																<input
														class='form-control' data-rule-minlength='2'
														data-rule-required='true' id='pname' name='Disclaimer'
														value="<?=@$pack_data[0]->Disclaimer;?>"
														placeholder='Disclaimer' type='text' required>
														
														
													
												</div>
											</div>
											<div class='form-group'>
												<label class='control-label col-sm-2' for='validation_name'>InsuranceSupplier</label>
												<div class='col-sm-3 controls'>
											
																<input
														class='form-control' data-rule-minlength='2'
														data-rule-required='true' id='pname' name='InsuranceSupplier'
														value="<?=@$pack_data[0]->InsuranceSupplier;?>"
														placeholder='InsuranceSupplier' type='text' required>
														
														
													
												</div>
											</div>
											<div class='form-actions' style='margin-bottom: 0'>
												<div class='row'>
													<div class='col-sm-9 col-sm-offset-2'>
														<a
															href="<?php echo base_url(); ?>index.php/privatecar/extra_services">
															<button class='btn btn-primary' type='button'>
																<i class='icon-reply'></i> Back
															</button>
														</a>&nbsp;
														<button class='btn btn-primary' type='submit'>
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
        $("#typeselect").on("change",function(){
           if( $("#typeselect").val()=="DropDown")
           {
               $(".droplist").show();
           }else
           {
                 $(".droplist").hide();
           }
        });
    });
</script>