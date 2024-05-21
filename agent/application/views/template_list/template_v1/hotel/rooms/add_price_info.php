<!-- HTML BEGIN -->
<head>
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/font-icons/entypo/css/entypo.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-core.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-theme.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-forms.css">

<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/custom.css">
	
	<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/assets/js/daterangepicker/daterangepicker-bs3.css"> 
	
	<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/selectboxit/jquery.selectBoxIt.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/daterangepicker/daterangepicker-bs3.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/select2/select2-bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/select2/select2.css">

</head>
<style> 
 .tab_error_color {
 	color: red !important;
 } 
 .tab_msg_color {
 	background: blue !important;
 }
.bodyContent > .panel-default>.panel-heading{color: #fff;
    background-color: #337ab7;
    border-color: #337ab7;
  }
  
.switchToggle input[type=checkbox]{height: 0; width: 0; visibility: hidden; position: absolute; }
.switchToggle label {cursor: pointer; text-indent: -9999px; width: 85px; max-width: 85px; height: 30px; background: #d1d1d1; display: block; border-radius: 100px; position: relative; }
.switchToggle label:after {content: ''; position: absolute; top: 2px; left: 2px; width: 26px; height: 26px; background: #fff; border-radius: 90px; transition: 0.3s; }
.switchToggle input:checked + label, .switchToggle input:checked + input + label  {background: #3e98d3; }
.switchToggle input + label:before, .switchToggle input + input + label:before {content: 'Exclude'; position: absolute; top: 5px; left: 35px; width: 26px; height: 26px; border-radius: 90px; transition: 0.3s; text-indent: 0; color: #fff; }
.switchToggle input:checked + label:before, .switchToggle input:checked + input + label:before {content: 'Include'; position: absolute; top: 5px; left: 10px; width: 26px; height: 26px; border-radius: 90px; transition: 0.3s; text-indent: 0; color: #fff; }
.switchToggle input:checked + label:after, .switchToggle input:checked + input + label:after {left: calc(100% - 2px); transform: translateX(-100%); }
.switchToggle label:active:after {width: 60px; } 
.toggle-switchArea { margin: 10px 0 10px 0; }
  
 
/*.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}


.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}*/
.btn{
      margin-right: 5px;
}

.btn-warning {
    background-color: #f39c12 !important;
    border-color: #e08e0b !important;
}
.stepsBar{
  padding: 0;
}
.stepsBar{
  display:flex;
}
.stepsBar li{
  list-style-type: none;
}
.stepsBar li a{
  display: flex;
    align-items: center;
    padding: 6px 12px;
    text-align: center;
}

.stepsBar h3 {
    font-size: 16px;
    margin: 0;
    margin: 0 20px 0 0;
}
.stepsBar li.active h3{
color:#fff;
}
.stepsBar i{
  margin-right: 4px;
}
.stepsBar li.active a{
  background: #000;
  color: #fff;
}
.stepsBar li a{
  background: #FEE400;
  color: #000;
}
</style>	

<div class="bodyContent">
	<div class="panel panel-default clearfix">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Add Price Info
			</div>
		</div>
		<!-- PANEL HEAD START -->
		
			<!-- PANEL BODY START -->
			<div class="panel-body">

				<div class="col-md-12 hide">
          <?=$GLOBALS['CI']->template->isolated_view('hotel/hotel_widgets',['hotel_id'=>'', 'active'=>['step1'], 'current'=>'step1'])?> 

          </div>

           <div class="clearfix"></div>

          <div class="col-sm-12 hide">
                      <div class="card mt20 xs-mt10 sm-mt10">
                        <div class="card-header">
                          <div>
                            <div class="col-md-12">
                              <h2>Steps to Add Hotel</h2>
                            </div>
                          </div>
                        </div>
                        <div class="card-body">  
                          <div id="document-ele-carousal">
                            <ul class="wizard-verfication-ul">
                              <li>
                                <p class="para-small">
                                  Step
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    1
                                  </span>
                                </div>
                                <p class="para">
                                  Add Hotel Basic Details
                                </p>
                              </li>
                              <li>  
                                <p class="para">
                                  Add Child/Infant Age Groups
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    2
                                  </span>
                                </div>
                                <p class="para-small">
                                  Step
                                </p>                                
                              </li>
                              <li>
                                <p class="para-small">
                                  Step
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    3
                                  </span>
                                </div>
                                <p class="para">
                                  Add Rooms Details
                                </p>
                              </li>
                              <li>
                                <p class="para">
                                  Add Seasons
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    4
                                  </span>
                                </div>
                                <p class="para-small">
                                  Step
                                </p>                                
                              </li>
                              <li>
                                <p class="para-small">
                                  Step
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    5
                                  </span>
                                </div>
                                <p class="para">
                                  Add Pricing
                                </p>
                              </li>
                            </ul>
                          </div>

                          <div id="post-identity-carousal" style="display: none;">
                            <ul class="wizard-verfication-ul wizard-verfication-ul2">
                              <li>
                                <p class="para-small">
                                  Step
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    1
                                  </span>
                                </div>
                                <p class="para">
                                  Dowload the pre - populated 
                                  consent form from the secure server
                                </p>
                              </li>
                              <li>  
                                <p class="para">
                                  Sign and date the consent form                                                                    
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    2
                                  </span>
                                </div>
                                <p class="para-small">
                                  Step
                                </p>                                
                              </li>
                              <li>
                                <p class="para-small">
                                  Step
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    3
                                  </span>
                                </div>
                                <p class="para">
                                  Obtain certified true copies of Photo
                                </p>
                              </li>
                              <li>
                                <p class="para">
                                  Send in the signed and dated form and documents to Auth N Tick
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    4
                                  </span>
                                </div>
                                <p class="para-small">
                                  Step
                                </p>                                
                              </li>                              
                            </ul>
                          </div>


                        </div>

                         <!--div class="col-md-12">
                      <div class="card comman-card-page mt0">
                        <div class="card-header">
                          <div>
                            <div class="col-md-12">
                              <h1 class="card-h1">Things required to Add Hotels</h1>
                              <p class="para-small card-para">Step 1/6</p> 
                            </div>
                          </div>
                        </div>
                        <div class="card-body">
                          <div class="clearfix">
                            <div class="col-lg-12">
                              <ul class="block-inline">
                                <li>
                                  Create Hotel Type
                                </li>
                                <li>
                                  Create Room Type
                                </li>
                                <li>
                                  Create Hotel ammenities
                                </li>
                                <li>
                                  Create Room ammenities
                                </li>
                                <li>
                                  In order to add a Hotel fill Hotel basic details like Name, Address, Description .
                                </li>
                                <li>
                                  Then add Hotel Banner Image and Hotel Images.
                                </li>
                                <li>                                  
                                 Then add Seasons details along with Room rates for that seasons based on Age groups
                                </li>
                                
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div-->
                    </div>
                    
                      </div>
                     
                      


          <div class="clearfix"></div>
				<form method="post"  id="hotel" name="hotel" action="<?php echo base_url()."index.php/hotel/save_room_price_data"; ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">
					<fieldset form="user_edit">
				<legend class="form_legend">Add Room Price Info</legend>

							<input type="hidden" name="hotel_id" id="hotel_id" value="<?php echo $hotel_id; ?>" />
							<input type="hidden" name="room_id" id="room_id" value="<?php echo $room_id; ?>" />
					
					
						<!--<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">From Date<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
									<input type="text" name="date_from" id="datepickerform" class="form-control" data-validate="required" data-message-required="Please Select the from date" readonly />	
									</div>
								</div>

					
						<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">To Date<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
									<input type="text" name="date_to" id="datepickerto" class="form-control" data-validate="required" data-message-required="Please Select the to date" readonly />	
									</div>
								</div>-->
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Season<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<select id="star_rating" name="season" class="form-control" data-validate="required" data-message-required="Please Select the Season">
                                                <?php
                                                if(!empty($seasons))
                                                {
                                                foreach($seasons as $season)
                                                {
                                                ?>
												<option value="<?=$season->seasons_details_id?>" data-iconurl=""><?=$season->seasons_name?></option>
												<?php
												}}
												?>
												
									
											
										</select>
									</div>
								</div>
								
						<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">1 Adult Price (AED)<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
									<input type="text" name="one_adult" id="one_adult" class="form-control numeric" data-validate="required" data-message-required="Please Select 1 adult price" />	
									</div>
								</div>

							
						<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">2 Adult Price (AED)<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
									<input type="text" name="two_adult" id="two_adult" class="form-control numeric" data-validate="required" data-message-required="Please Select 2 adult price" />	
									</div>
								</div>

							
						<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">3 Adult Price (AED)<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
									<input type="text" name="three_adult" id="three_adult" class="form-control numeric" data-validate="required" data-message-required="Please Select 3 adult price" />	
									</div>
								</div>

							<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Child Price (AED)<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
									<input type="text" name="child_price" id="child_price" class="form-control numeric" data-validate="required" data-message-required="Please Select  child price" />	
									</div>
								</div>
								

								
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">1 Adult Breakfast (include/exclude)</label>									
									<div class="col-sm-5">
									    
									    <div class="switchToggle">
                                            <input type="checkbox" id="switch" class="bfst_chk" data-id="one_adult_breakfast">
                                            <label for="switch">Toggle</label>
                                        </div>
                                      <!--<input type="checkbox" checked data-toggle="toggle" class="bfst_chk" data-id="one_adult_breakfast"/>-->
								<br><br>
									<input type="text" name="one_adult_breakfast" id="one_adult_breakfast" class="form-control numeric hide"  value="0"/>	
									</div>
								</div>
			
								
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">2 Adult Breakfast (include/exclude)</label>									
									<div class="col-sm-5">
									    <div class="switchToggle">
                                            <input type="checkbox" id="switch1" class="bfst_chk" data-id="two_adult_breakfast">
                                            <label for="switch1">Toggle</label>
                                        </div>
									<!--<input type="checkbox" checked data-toggle="toggle" class="bfst_chk" data-id="two_adult_breakfast"/>-->
										<br><br>	
									<input type="text" name="two_adult_breakfast" id="two_adult_breakfast" class="form-control numeric hide"  value="0"/>	
									</div>
								</div>
			
								
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">3 Adult Breakfast (include/exclude)</label>									
									<div class="col-sm-5">
									  <div class="switchToggle">
                                            <input type="checkbox" id="switch2" class="bfst_chk" data-id="three_adult_breakfast">
                                            <label for="switch2">Toggle</label>
                                        </div>  
									<!--<input type="checkbox" checked data-toggle="toggle" class="bfst_chk" data-id="three_adult_breakfast"/>-->
									<br><br>	
									<input type="text" name="three_adult_breakfast" id="three_adult_breakfast"  class="form-control numeric hide"  value="0"/>	
									</div>
								</div>
			
								<div class="form-group">
                  <label for="field-1" class="col-sm-3 control-label">Minimum Stay <span class="text-danger">*</span></label>                 
                  <div class="col-sm-5">
                    <select id="star_rating" name="min_stay" class="form-control" data-validate="required" data-message-required="Please Select the Minimum stay">

                        <option value="1" data-iconurl="" <?php echo $data->min_stay == 1 ? "selected" :""; ?>>1</option>
                        <option value="2" data-iconurl="" <?php echo $data->min_stay == 2 ? "selected" :""; ?>>2</option>
                        <option value="3" data-iconurl="" <?php echo $data->min_stay == 3 ? "selected" :""; ?>>3</option>
                        <option value="4" data-iconurl="" <?php echo $data->min_stay == 4 ? "selected" :""; ?>>4</option>
                        <option value="5" data-iconurl="" <?php echo $data->min_stay == 5 ? "selected" :""; ?>>5</option>
                        <option value="6" data-iconurl="" <?php echo $data->min_stay == 6 ? "selected" :""; ?>>6</option>
                        <option value="7" data-iconurl="" <?php echo $data->min_stay == 7 ? "selected" :""; ?>>7</option>
                  
                      
                    </select>
                  </div>
                </div>
<div class="form-group">
                  <label for="field-1" class="col-sm-3 control-label">Extra bed Size <span class="text-danger">*</span></label>                 
                  <div class="col-sm-5">
                    <select id="star_rating" name="extrabed" class="form-control" >

                        <option value="0" data-iconurl="" <?php echo $data->extrabed == 0 ? "selected" :""; ?>>0</option>
                        <option value="1" data-iconurl="" <?php echo $data->extrabed == 1 ? "selected" :""; ?>>1</option>
                        <option value="2" data-iconurl="" <?php echo $data->extrabed == 2 ? "selected" :""; ?>>2</option>
                        <option value="3" data-iconurl="" <?php echo $data->extrabed == 3 ? "selected" :""; ?>>3</option>
                        <option value="4" data-iconurl="" <?php echo $data->extrabed == 4 ? "selected" :""; ?>>4</option>
                        <option value="5" data-iconurl="" <?php echo $data->extrabed == 5 ? "selected" :""; ?>>5</option>
                        <option value="6" data-iconurl="" <?php echo $data->extrabed == 6 ? "selected" :""; ?>>6</option>
                        <option value="7" data-iconurl="" <?php echo $data->extrabed == 7 ? "selected" :""; ?>>7</option>
                  
                      
                    </select>
                  </div>
                </div>
                 <div class="form-group">
					<label for="field-1" class="col-sm-3 control-label">Extra bed price</label>									
					<div class="col-sm-5">
					<input type="text" name="extrabed_price" id=""  class="form-control numeric" />	
					</div>
				</div>
                <div class="form-group">
          <label for="field-1" class="col-sm-3 control-label">child  break fast (include/exclude) </label>                  
          <div class="col-sm-5">
              <div class="switchToggle">
                    <input type="checkbox" id="switch3" class="bfst_chk1" data-id="child_breakfast_age" <?=$data->child_breakfast>0 ? 'checked' : ''?> />
                    <label for="switch3">Toggle</label>
                </div> 
          <!--<input type="checkbox" checked data-toggle="toggle" class="bfst_chk1" data-id="child_breakfast_age"  <?=$data->child_breakfast>0 ? 'checked' : ''?> />-->

          <div id="child_breakfast_age" class="hide">
          <label>Child Age</label>
          <input type="text"  class="form-control " name="child_breakfast_age"  value="" /><br>
          <label>Price</label>
          <input type="text" name="child_breakfast" id="child_breakfast"  class="form-control numeric "  value="0" />  
          </div>
        </div><br><br>
      </div>
				<div class="form-group">
					<label for="field-1" class="col-sm-3 control-label">VAT(%)</label>									
					<div class="col-sm-5">
					    <div class="switchToggle">
                            <input type="checkbox" id="switch4" class="bfst_chk" data-id="vat">
                            <label for="switch4">Toggle</label>
                        </div>
					    
					<!--<input type="checkbox" checked data-toggle="toggle" class="bfst_chk" data-id="vat"  />--><br><br>	
					<input type="text" name="vat" id="vat"  class="form-control numeric hide"  value="0"/>	
					</div>
				</div>
				<div class="form-group">
					<label for="field-1" class="col-sm-3 control-label">Searvice Charge(%)</label>									
					<div class="col-sm-5">
					    <div class="switchToggle">
                            <input type="checkbox" id="switch5" class="bfst_chk" data-id="service_charge">
                            <label for="switch5">Toggle</label>
                        </div>
					<!--<input type="checkbox" checked data-toggle="toggle" class="bfst_chk" data-id="service_charge"  />--><br><br>	
					<input type="text" name="service_charge" id="service_charge"  class="form-control numeric hide" value="0" />	
					</div>
				</div>
				
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Status<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<select id="star_rating" name="status" class="form-control" data-validate="required" data-message-required="Please Select the Status">

												<option value="ACTIVE" data-iconurl="">ACTIVE</option>
												<option value="DEACTIVE" data-iconurl="">INACTIVE</option>
									
											
										</select>
									</div>
								</div>
						
								<div class="form-group">
									<label class="col-sm-3 control-label">&nbsp;</label>									
									<div class="col-sm-5">
										<a href="<?=base_url("index.php/hotel/room_crs_list/{$hotel_id}")?>"  class="btn btn-primary  btn btn-success  " >Back to Room List</a>
										<!--<input type="submit" name="submit"  class="btn btn-success" value="Continue">-->
										<input type="submit" name="submit"  class="btn btn btn-warning " value="Save">
										<!--<a href="<?=base_url('index.php/hotels/hotel_crs_list')?>"   class="btn btn-danger" >Exit</a>-->
									</div>
								</div>
				</form>
			</div>
		
                      </div>
                    </div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL END -->
</div>
<script>
						    $(document).ready(function(){
                                $(document).on('change', '.bfst_chk', function(e){
                                    var ck = $(this).data('id');
                                    if ($(this).is(':checked')) 
                                    {
                                       $("#"+ck).removeClass('hide'); 
                                    }
                                    else
                                    {
                                     $("#"+ck).addClass('hide');
                                     $("#"+ck).val(0);
                                    }
                                });
                            
                            });

                  $(document).on('change', '.bfst_chk1', function(e){
                                    var ck = $(this).data('id');
                                    if ($(this).is(':checked')) 
                                    {
                                       $("#child_breakfast_age").removeClass('hide'); 
                                    }
                                    else
                                    {
                                     $("#child_breakfast_age").addClass('hide'); 
                                    }
                                });
                            
                            
						</script>
<!-- Page Ends Here -->
<!--Load Js--> 
	<script src="<?php echo base_url(); ?>hotel_assets/js/gsap/main-gsap.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery-ui.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/store.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/joinable.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/resizeable.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/provab-login.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/provab-api.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery-idleTimeout.js"></script>
	
	<script src="<?php echo base_url(); ?>hotel_assets/js/provab-custom.js"></script>
	
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-switch.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>	
	<script src="<?php echo base_url(); ?>assets/js/fileinput.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery.bootstrap.wizard.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/ckeditor/ckeditor.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/ckeditor/adapters/jquery.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-timepicker.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>

	<script src="<?php echo base_url(); ?>assets/js/select2/select2.min.js"></script>
   <script src="<?php echo base_url(); ?>assets/js/jquery-ui.js"></script>

  <script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/datatables/TableTools.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/dataTables.bootstrap.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/datatables/jquery.dataTables.columnFilter.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/datatables/lodash.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/datatables/responsive/js/datatables.responsive.js"></script>   
 <!--    <script src="<?= base_url(); ?>assets/js/plugins/datatables/dataTables.overrides.js" type="text/javascript"></script>
    <script src="<?= base_url(); ?>assets/js/plugins/lightbox/lightbox.min.js" type="text/javascript"></script>
 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css" type="text/css">-->
  
  
  
  

<script type="text/javascript">

$(document).ready(function () {
			$("#datepickerform").datepicker({
        dateFormat: "dd-mm-yy",
        minDate: 0,
        onSelect: function (date) {
            var date2 = $('#datepickerform').datepicker('getDate');
            date2.setDate(date2.getDate());// + 1
            $('#datepickerto').datepicker('setDate', date2);
            //sets minDate to from date + 1
            $('#datepickerto').datepicker('option', 'minDate', date2);
        }
    });
    $('#datepickerto').datepicker({
        dateFormat: "dd-mm-yy",
        onClose: function () {
            var from = $('#datepickerform').datepicker('getDate');
            console.log(from);
            var to = $('#datepickerto').datepicker('getDate');
            if (to <= from) {
                var minDate = $('#datepickerto').datepicker('option', 'maxDate');
                $('#datepickerto').datepicker('setDate', minDate);
            }
        }
    });
		});
$("#ammenities").select2();
$("#cancellation").hide();
	function addMoreRooms1() {
			$("#cancellation_clone").css({'display':'inherit'});
			var id = $('#rows_cnt').val();
			
	    	$("#cancellation_clone").append( '<div class="form-group" style="widht:80%;" ><div class="col-sm-4">'+								
								'<input type="number" class="form-control" data-rule-number="true" name="cancellation_from[]" id="cancellation_from'+id+'" value="">'+
								'</div>'+
								'<div class="col-sm-4">'+							
								'<input type="number" class="form-control" data-rule-number="true" name="cancellation_nightcharge[]" id="cancellation_nightcharge'+id+'" value="">'+
								'</div>'+
								'<div class="col-sm-4 ">'+							
								' <input type="number" class="form-control" data-rule-number="true" value="" name="cancellation_percentage[]" id="cancellation_percentage'+id+'" value=""> </div></div>');																				
			id = parseInt(id)+1;
			$('#rows_cnt').val(id);																

		}

		function removeLastRoom1(v){
			var id = $('#rows_cnt').val();
			$('#cancellation_clone .form-group').last().remove();
			if(id <= 1) {
				$("#cancellation_clone").css({'display':'none'});
			}
			id = parseInt(id)-1;
			$('#rows_cnt').val(id);
		}

		$("#hotel_cancellation").on("change",function(){
			var hotel_cancellation = $("#hotel_cancellation").val();
			if(hotel_cancellation == 0){
				$("#cancellation").hide();
			}else{
				$("#cancellation").show();
			}
		});
</script>	
<script> 
$("#event_title").hide();
		$(function(){			  
			$('#exclude_checkin_time').timepicker({
      			pickDate: false,
      			showMeridian: false
    });
			$('#exclude_checkout_time').timepicker({
      			pickDate: false,
      			showMeridian: false
    });
    
			$('#status').change(function(){
				var current_status = $('#status').val();
				if(current_status == "ACTIVE")
					$('#status').val('INACTIVE');
				else
					$('#status').val('ACTIVE');
			});

			$('#top_deals').change(function(){
				var current_status = $('#top_deals').val();
				if(current_status == "1")
					$('#top_deals').val('0');
				else
					$('#top_deals').val('1');
			});

			$('#event').change(function(){
				var current_status = $('#event').val();
				if(current_status == "1"){
					$('#event').val('0');
					$("#event_title").hide();
					$('#remove').remove();
					$("#event_name").removeAttr('required');
				}
				else{
					$("#event_title").show();
					$("#event_name").attr("required", "true");
					
					$('#event').val('1');
					$('#distance').append('<div class="form-group" id="remove"><label for="field-1" class="col-sm-3 control-label">Distance from the Conference</label><div class="col-sm-5"><textarea class="form-control" name="distance" placeholder="Distance from the Conference" data-message-required="Please enter the Distance from the Conference " required></textarea></div></div>');
				}
			});


			$(".top_deals").click(function(){
				alert("#top_deals").val();
			})
			// $('#countries_list').change(function(){
				// var country = $('#countries_list').val();
				// $.ajax({
					// type: "POST",
					// url:'<?php echo base_url(); ?>hotel/filter_city_list/'+country,
					// dataType: "json",
					// success: function(data){
						// if (data.status == 1) {
							// $('#cities_div').html(data.city);
						// } 
					// }  
				// });
			// }); 
			
			
			var hotel_name = document.getElementById('hotel_name');
			var hotel_code = document.getElementById('hotel_code');
			var position = document.getElementById('position');
			var postal_code = document.getElementById('postal_code');
			var phone_number = document.getElementById('phone_number');
			var email = document.getElementById('email');
			var hotel_address = document.getElementById('hotel_address');
			var countries_list = document.getElementById('countries_list');			

			$('input#hotel_name').keyup(function() {
				var $th = $(this);		
				if($th.val().trim() != ""){
					 var regex = /^[a-zA-Z 0-9!@#$%^&*_() - +=:;'",. ]*$/;
					if (regex.test($th.val())) {
						$th.css('border', '1px solid #099A7D');					
					} else {
						// alert("Please use only letters");
						$th.css('border', '1px solid #f52c2c');
						return '';
					}
				}else if(hotel_name.value.length < 2 || hotel_name.value.length > 60) {
					    hotel_name.style.border = "1px solid #f52c2c";   
						hotel_name.focus(); 
						return false; 
				}
			});	
			
			$("#add_location").click(function(){
				$("#add_location").hide();
				$("#location_info").hide();
				$("#location_name").slideToggle("slow");
			});
			
			$('input#postal_code').keyup(function() {
				var $th = $(this);		
				if($th.val().trim() != ""){
					 var regex = /^[0-9]*$/;
					if (regex.test($th.val())) {
						$th.css('border', '1px solid #099A7D');					
					} else {
						// alert("Please use only letters");
						$th.css('border', '1px solid #f52c2c');
						return '';
					}
				
				}
				 if(postal_code.value.length > 7 ) {
					    postal_code.style.border = "1px solid #f52c2c";   
						postal_code.focus(); 
						return false; 
				}
			});				
			
			
			$('#hotel').submit(function(){	

				var hotel_type1 = $('#hotel_type').val();				
				if(hotel_type1 == "0"){					
					hotel_type.style.border = "1px solid #f52c2c";   
					hotel_type.focus(); 
					return false; 			
				}				

				var country_id = $('#country').val();      				
				if(country_id == "0"){										
					country.style.border = "1px solid #f52c2c";   
					country.focus(); 
					return false; 		
				}				

				var city_id = $('#city_name').val();
				if(city_id == "0"){					
					city_name.style.border = "1px solid #f52c2c";   
					city_name.focus(); 
					return false; 		
				}	


			    var filter = /^[a-zA-Z 0-9!@#$%^&*()-+=:;'",.  ]*$/;
			    var alpha_filter = /^[a-zA-Z 0-9]*$/;
			    var number_filter = /^[0-9]*$/;
			    var number_filter2 = /^[+0-9]*$/;

				if(hotel_name.value != '')
				{
					if(!(hotel_name.value.match(filter)))
					{
						hotel_name.style.border = "1px solid #f52c2c";   
						hotel_name.focus(); 
						return false; 
					}
				}
				else
				{
					hotel_name.style.border = "1px solid #f52c2c";   
					hotel_name.focus(); 
					return false; 
				}

				if(hotel_name.value.length < 2 || hotel_name.value.length > 50) {
					    hotel_name.style.border = "1px solid #f52c2c";   
						hotel_name.focus(); 
						return false; 
				}								

				if(location_info.value == "select" && location_name.value == ""){
					location_info.style.border = "1px solid #f52c2c";   
					location_name.style.border = "1px solid #f52c2c";   															
					return false;
				}
				
				if(postal_code.value != '')
				{
					if(!(postal_code.value.match(number_filter)))
					{
						postal_code.style.border = "1px solid #f52c2c";   
						postal_code.focus(); 
						return false; 
					}
				}
				else
				{
					postal_code.style.border = "1px solid #f52c2c";   
					postal_code.focus(); 
					return false; 
				}
				
				if(postal_code.value == '') {
					    postal_code.style.border = "1px solid #f52c2c";   
						postal_code.focus(); 
						return false; 
				}

				if(postal_code.value.length > 8 ) {
					    postal_code.style.border = "1px solid #f52c2c";   
						postal_code.focus(); 
						return false; 
				}			
		
				
				if(phone_number.value ==  '') {
					    phone_number.style.border = "1px solid #f52c2c";   
						phone_number.focus(); 
						return false; 
				}

				if(phone_number.value.length > 50 ) {
					    phone_number.style.border = "1px solid #f52c2c";   
						phone_number.focus(); 
						return false; 
				}
				if(email.value ==  '') {
					    email.style.border = "1px solid #f52c2c";   
						email.focus(); 
						return false; 
				}
				
				if(hotel_address.value == '') {
					 hotel_address.style.border = "1px solid #f52c2c";   
						hotel_address.focus(); 
						return false; 
				}


				
			
			});
		
			$.fn.checkFileType = function(options1) {
				var defaults = {
					allowedExtensions: [],
					success: function() {},
					error: function() {}
				};
				options1 = $.extend(defaults, options1);

				return this.each(function() {

					$(this).on('change', function() {
						var value = $(this).val(),
							file = value.toLowerCase(),
							extension = file.substring(file.lastIndexOf('.') + 1);

						if ($.inArray(extension, options1.allowedExtensions) == -1) {
							options1.error();
							$(this).focus();
						} else {
							options1.success();

						}

					});

				});
			};
			
			$('#thumb_image').checkFileType({
				allowedExtensions: ['jpg', 'jpeg','png'],
				success: function() {
					file_upload = true;
					// alert('Success');
					 $("#imageflag").val("true");
				},
				error: function() {
					file_upload = false;
					alert('Please Select Valid Image (Ex: jpg,jpeg,png) ');
					 $("#imageflag").val("false");
			   	 
				}
			});

  var _URL = window.URL || window.webkitURL;
$("#thumb_image").change(function(e) {
    var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function() {
          if((this.width > 1080) || (this.height > 20))
          {
            // alert("image size should be 1080*720");
             this.value = "";
          }
        };
        img.onerror = function() {
            alert( "not a valid file: " + file.type);
        };
        img.src = _URL.createObjectURL(file);


    }

});

     /*var uploadField = document.getElementById("thumb_image");

      uploadField.onchange = function() {
        if(this.files[0].size < 1300){
          alert("File is too small!");
          this.value = "";
        };
        if(this.files[0].size > 10000000){
          alert("File is too big!");
          this.value = "";
        };
      };*/
		

			$('#hotel_image').checkFileType({
				allowedExtensions: ['jpg', 'jpeg','png'],
				success: function() {
					file_upload = true;
					// alert('Success');
					 $("#imageflag1").val("true");
				},
				error: function() {
					file_upload = false;
					alert('Please Select Valid Image (Ex: jpg,jpeg,png) ');
					 $("#imageflag1").val("false");
			   	 
				}
			});

			return false;
			
			
			
		});
		function select_city(country_id){
		   /* console.log("here");
		  console.log(country_id);*/
		 if (country_id != '') {         	  
          var select1 = $('#city_name');          
          $.ajax({
            url: '<?php echo base_url(); ?>/hotels/get_city_name/'+country_id,
            success: function (data, textStatus, jqXHR) {                                    
              select1.html('');
              select1.html(data);
              select1.trigger("chosen:updated");  
          	}
           });         
         }		
		}
		
		function select_location(city_id){				 
		 if (city_id != '') {         	  
          var location_select = $('#location_info');          
          $.ajax({
            url: '<?php echo base_url(); ?>/hotels/get_location_name/' + city_id,
            success: function (data, textStatus, jqXHR) {                                   
              location_select.html('');
              location_select.html(data);
              location_select.trigger("chosen:updated");  
          	}
           });         
          }		
		 }
		
	</script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyAiR9CLZshY_vQpB7z5M7nIGCg16gfo2E8"></script>
   	<script>   	
		var map;
		var geocoder;
		var mapOptions = { center: new google.maps.LatLng(0.0, 0.0), zoom: 2,
        mapTypeId: google.maps.MapTypeId.ROADMAP };
		
		function initialize() {			
			var myOptions = {
                center: new google.maps.LatLng(12.851, 77.659 ),
                //center: new google.maps.LatLng(-1.9501,30.0588),
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            geocoder = new google.maps.Geocoder();
            var map = new google.maps.Map(document.getElementById("map_canvas"),
            myOptions);
            google.maps.event.addListener(map, 'click', function(event) {
            	placeMarker(event.latLng);
            });

            var marker;
            function placeMarker(location) {            	
                if(marker){ //on vérifie si le marqueur existe
                    marker.setPosition(location); //on change sa position
                }else{
                    marker = new google.maps.Marker({ //on créé le marqueur
                        position: location, 
                        map: map
                    });
                }
                 document.getElementById('lat').value=location.lat();
                 document.getElementById('lng').value=location.lng();
                getAddress(location);
            }

			function getAddress(latLng) {				
				geocoder.geocode( {'latLng': latLng},
				function(results, status) {
					if(status == google.maps.GeocoderStatus.OK) {
					  if(results[0]) {					 
						document.getElementById("hotel_address").value 	= results[0].formatted_address;
						var address = results[0].address_components;
						var zipcode = address[address.length - 1].long_name;
						//document.getElementById("city").value 		= results[0].address_components[1]['long_name'];
						document.getElementById("postal_code").value 	= zipcode;						
					  }
					  else {
						//document.getElementById("city").value = "No results";
					  }
					}
					else {
					  //document.getElementById("city").value = status;
					}
				});
			}
		}
      google.maps.event.addDomListener(window, 'load', initialize);

      function getmap(){	 		 	
	 	var edValue = document.getElementById("lat");
        lat = edValue.value;
      	var edValue = document.getElementById("lng");
        lng = edValue.value;        
        var newPosition = new google.maps.LatLng(lat,lng);
        if(lat > 0 && lng > 0){
           myOptions = {                
                center: new google.maps.LatLng(lat,lng),
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            geocoder = new google.maps.Geocoder();
            map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);                        
            marker = new google.maps.Marker({ //on créé le marqueur
                        position: newPosition, 
                        map: map
            });            
            getAddress2(newPosition);        
       }        
	 }

	function getAddress2(latLng) {				
				geocoder.geocode( {'latLng': latLng},
				function(results, status) {
					if(status == google.maps.GeocoderStatus.OK) {
					  if(results[0]) {					 
						document.getElementById("hotel_address").value 	= results[0].formatted_address;
						var address = results[0].address_components;
						var zipcode = address[address.length - 1].long_name;
						//document.getElementById("city").value 		= results[0].address_components[1]['long_name'];
						document.getElementById("postal_code").value 	= zipcode;						
					  }
					  else {
						//document.getElementById("city").value = "No results";
					  }
					}
					else {
					  //document.getElementById("city").value = status;
					}
				});
			}
      
       function addMoreRooms(c) {
			var id = $('#rows_cnt').val();
			$("#rooms").css({'display':'inherit'});
			$("#rooms").append('<div class="form-group"><label for="field-1" class="col-sm-3 control-label">Exclude Checkout Date</label><div class="col-md-5"><input type="number" class="form-control datepicker" name="exclude_checkout_date[]" id="exclude_checkout_date'+id+'" data-min-date="<?php echo date('m-d-Y');?>" data-validate="required" data-message-required="Please Select the Date Range" /></div></div>');
			$('#datepicker').datepicker({
				
				dateFormat: 'dd/mm/yy',
				minDate: 0,
				firstDay: 1,
				maxDate: "+1Y",
			}
				);
			id = id+1;
			$('#rows_cnt').val(id);
		}
		function removeLastRoom(v){
			var id = $('#rows_cnt').val();
			$('#rooms .form-group').last().remove();
			id = id-1;
			$('#rows_cnt').val(id);
		}
		function checkUniqueEmail(email){
			var sEmail = document.getElementById('email');
			if (sEmail.value != ''){
				var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
				if(!(sEmail.value.match(filter))){
					$("#email").val(email);
					return false; 
				}else{
				}
			}
			return false;
		}
		 function geocodeAddress(address) {
	 		geocoder.geocode({address:address}, function (results,status)
		      { 
		         if (status == google.maps.GeocoderStatus.OK) {
		          var p = results[0].geometry.location;
		          var lat=p.lat();
		          var lng=p.lng();
		          //createMarker(address,lat,lng);
		          ///alert(lng);
		          var myOptions = {
	                center: new google.maps.LatLng(lat, lng ),
			                //center: new google.maps.LatLng(-1.9501,30.0588),
			                zoom: 10,
			                mapTypeId: google.maps.MapTypeId.ROADMAP
			            };
              	 	var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
	              	 google.maps.event.addListener(map, 'click', function(event) {
	              	 	placeMarker(event.latLng);
		            });	

	              	 var marker;
		            function placeMarker(location) {            	
		                if(marker){ //on vérifie si le marqueur existe
		                    marker.setPosition(location); //on change sa position
		                }else{
		                    marker = new google.maps.Marker({ //on créé le marqueur
		                        position: location, 
		                        map: map
		                    });
		                }
		                 document.getElementById('lat').value=location.lat();
		                 document.getElementById('lng').value=location.lng();
		                getAddress(location);
		            }

					function getAddress(latLng) {				
						geocoder.geocode( {'latLng': latLng},
						function(results, status) {
							if(status == google.maps.GeocoderStatus.OK) {
							  if(results[0]) {					 
								document.getElementById("hotel_address").value 	= results[0].formatted_address;
								var address = results[0].address_components;
								var zipcode = address[address.length - 1].long_name;
								//document.getElementById("city").value 		= results[0].address_components[1]['long_name'];
								document.getElementById("postal_code").value 	= zipcode;						
							  }
							  else {
								//document.getElementById("city").value = "No results";
							  }
							}
							else {
							  //document.getElementById("city").value = status;
							}
						});
					}
		        }
		        
		      }
		    );
		  }

		
		 $('#city_name').on('change',function(){
		 	var search_city  = $('#city_name').val();
		 	var country = $('#country').val();
		 	if(search_city!=''){
		 		geocodeAddress(search_city+','+country);
		 	}
		 });
 $(".numeric").keypress(function (e) {
      //if the letter is not digit then display error and don't type anything
      if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      //display error message
      $("#errmsg").html("Digits Only").show().fadeOut("slow");
      return false;
      }
      });


</script>