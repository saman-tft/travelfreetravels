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
								Vendor</h1></a></li>
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
											action="<?php echo base_url(); ?>index.php/privatecar/save_car_type"
											method="post" name="frm1" enctype="multipart/form-data">
											<div class='form-group'>
												<label class='control-label col-sm-2' for='validation_name'>Name</label>
												<div class='col-sm-3 controls'>
													<input type="hidden" name="vendorid"
														value="<?=@$pack_data[0]->vendorid;?>"> <input
														class='form-control alphaOnly' data-rule-minlength='2'
														data-rule-required='true' id='pname' name='name'
														value="<?=@$pack_data[0]->name;?>"
														placeholder='Name' type='text' required>
													
														<span id="error-email"
                                        style="color: #F00; display: none">Please Enter Name </span>
													
												</div>
											</div>
											<div class='form-group'>
												<label class='control-label col-sm-2' for='validation_name'>Email</label>
												<div class='col-sm-3 controls'>
											
														<input
														class='form-control' data-rule-minlength='2'
														data-rule-required='true' id='pemail' name='email'
														value="<?=@$pack_data[0]->email;?>"
														placeholder='Email' type='text' required>
													    <span id="error-email"
                                        style="color: #F00; display: none">Please Enter Right Email </span>
														
													
												</div>
											</div>
											<div class='form-group'>
												<label class='control-label col-sm-2' for='validation_name'>Phone</label>
												<div class='col-sm-3 controls'>
											
																<input
														class='form-control allowOnlynumeric' data-rule-minlength='2'
														data-rule-required='true' id='pphone' name='phone'
														value="<?=@$pack_data[0]->phone;?>"
														placeholder='Phone' type='text' required>
														<span id="error-phone"
                                        style="color: #F00; display: none">Please Enter Right Phone </span>
														
													
												</div>
											</div>
											<div class='form-group'>
												<label class='control-label col-sm-2' for='validation_name'>Terms and conditions (PDF)</label>
												<div class='col-sm-3 controls'>
											
																<input
														class='form-control' data-rule-minlength='2'
														data-rule-required='true' id='pvendor' name='Vendor'
														value="<?=@$pack_data[0]->termsconditions;?>"
														placeholder='Phone' type='file' required>
														
														<span id="error-Vendor"
                                        style="color: #F00; display: none">Please Upload Terms and conditions in (PDF)</span>
													
												</div>
											</div>
											<div class='form-group'>
												<label class='control-label col-sm-2' for='validation_name'>	pre-payment rule</label>
												<div class='col-sm-3 controls'>
											
																<textarea
														class='form-control' id='pprepaymentRule' name='prepaymentRule'
														value="<?=@$pack_data[0]->pre-paymentRule;?>"
														placeholder='pre-paymentRule' required></textarea>
														
														<span id="error-prepaymentRule"
                                        style="color: #F00; display: none">Please Enter Pre Payment Rule</span>
													
												</div>
											</div>
												<div class='form-group'>
												<label class='control-label col-sm-2' for='validation_name'>Logo</label>
												<div class='col-sm-3 controls'>
											
																<input
														class='form-control' 
														data-rule-required='true'  name='logo'
														value="<?=@$pack_data[0]->logo;?>"
														placeholder='Logo' id="logo" type='file' required>
														
														<span id="error-logo"
                                        style="color: #F00; display: none">Please Upload Logo Image in (.png)</span>
													
												</div>
											</div>
											<div class='form-actions' style='margin-bottom: 0'>
												<div class='row'>
													<div class='col-sm-9 col-sm-offset-2'>
														<a
															href="<?php echo base_url(); ?>index.php/privatecar/view_car_types">
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
    
$(document).ready(function() {
    $("button[type='submit']").click(function(){
       check_require_field();
    });
});
$(document).on('change blur paste','#pname,#pemail,#pphone,#pvendor,#pprepaymentRule,#logo', function() {

    check_require_field();
});
function check_require_field(){
    
    $("#pname,#pemail,#pphone,#pvendor,#pprepaymentRule,#logo").each(function(){
        
        if($(this).attr('id')=='pvendor'){
            var ext = $('#pvendor').val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['pdf']) == -1) {
                $(this).val("");
                $(this).next().show();
                $(this).closest(".form-group").addClass("has-error");
            }else{
                $(this).next().hide();
                $(this).closest(".form-group").removeClass("has-error");
            }
        }else if($(this).attr('id')=='logo'){
            var ext2 = $('#logo').val().split('.').pop().toLowerCase();
            if($.inArray(ext2, ['png']) == -1) {
                $(this).val("");
                $(this).next().show();
                $(this).closest(".form-group").addClass("has-error");
            }else{
                $(this).next().hide();
                $(this).closest(".form-group").removeClass("has-error");
            }
        }else if($(this).attr('id')=='pemail'){
            var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
            let valueToTest = $(this).val();
            if(!testEmail.test(valueToTest)){
                $(this).val("");
                $(this).next().show();
                $(this).closest(".form-group").addClass("has-error");
            }else{
                $(this).next().hide();
                $(this).closest(".form-group").removeClass("has-error");
            }
        }else if($(this).attr('id')=='pphone'){
           
            if($(this).val().length > 10 || $(this).val()==""){
                $(this).val("");
                $(this).next().show();
                $(this).closest(".form-group").addClass("has-error");
            }else{
                $(this).next().hide();
                $(this).closest(".form-group").removeClass("has-error");
            }
        }else{
            
            if($(this).val()==""){
                $(this).next().show();
                $(this).closest(".form-group").addClass("has-error");
            }else{
                $(this).next().hide();
                $(this).closest(".form-group").removeClass("has-error");
            }
        }
    });
}

$(document).on('change keypress keyup blur paste', '.alphaOnly', function(event) {
    var element = $(this);
    var inputValue = event.which;
    // allow letters and whitespaces only.
    if(!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)) { 
        event.preventDefault(); 
        $(element).addClass('error').removeClass('valid');
        $(element).attr('aria-invalid','true');
        $(element).parent().next('.error').show();
        $(element).parent().next('.error').text('Only alphabetical characters');
    }else if((/^[a-zA-Z ]*$/.test(element.val()))){
        $(element).addClass('valid').removeClass('error');
        $(element).parent().next('.error').hide();
        $(element).parent().next('.error').text('');
    }
});

$(document).on("keypress keyup blur paste",'.allowOnlynumeric', function(event) {
    var that = this;
    //paste event 
    if (event.type === "paste") {
        setTimeout(function() {
            $(that).val($(that).val().replace(/[^\d].+/, ""));
        }, 100);
    } else {

        if (event.which < 48 || event.which > 57) {
            event.preventDefault();
        } else {
            $(this).val($(this).val().replace(/[^\d].+/, ""));
        }
    }
});
</script>