

<style>
	.stepsBar-wrap .card-body .stepsBar {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: stretch;
    -ms-flex-align: stretch;
    align-items: stretch;
    margin: 0;
    padding: 0;
}

.card, .comman-box-shadow {
    box-shadow: 0 2px 3px 0 
    rgba(50,50,50,.12);
}

</style>


<link href="<?=get_domain()?>extras/system/template_list/template_v1/javascript/page_resource/datepicker/datepicker.css" rel="stylesheet"> 
<script src="<?=get_domain()?>extras/system/template_list/template_v1/javascript/page_resource/datepicker/datepicker.js"> </script>  
<script src="<?=get_domain()?>extras/system/template_list/template_v1/javascript/page_resource/datepicker/jquery.blueberry.js"> </script> 

<link href="<?=get_domain()?>extras/system/template_list/template_v1/css/bootstrap-duallistbox-master/src/bootstrap-duallistbox.css" rel="stylesheet">

<script src="<?=get_domain()?>extras/system/template_list/template_v1/javascript/bootstrap-duallistbox-master/src/jquery.bootstrap-duallistbox.js"></script>



<?php //error_reporting(0); ?>
<div id="Package" class="bodyContent col-md-12">


	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active" id="add_package_li"><a
						href="#add_package" aria-controls="home" role="tab"
						data-toggle="tab">Tour Manager</a></li>
					
				</ul>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
			<div class="col-md-12">
          		<div class="card card-page stepsBar-wrap" style="display: none;">
					<div class="card-body">
						<ul class="stepsBar">
							<li id="step1" class="active current"><a href="<?=base_url()?>index.php/tours/add_tour"><i class="fal fa-file-alt"></i><i class="icon-Check-Circle"></i><h3>Add Tour </h3></a></li>
							<li id="step2"><a href="javascript:;"><i class="glyphicon glyphicon-user"></i><i class="icon-Check-Circle"></i><h3>Departure Date</h3></a></li>
							<li id="step3"><a href="javascript:;"><i class="glyphicon glyphicon-home"></i><i class="icon-Check-Circle"></i><h3>City List</h3></a></li>
							<li id="step4"><a href="javascript:;"><i class="glyphicon glyphicon-tree-conifer"></i><i class="icon-Check-Circle"></i><h3>Itinerary</h3></a></li>
							<li id="step5"><a href="javascript:;"><i class="fal fa-file-alt"></i><i class="icon-Check-Circle"></i><h3>Description</h3></a></li>
							<li id="step6"><a href="javascript:;"><i class="glyphicon glyphicon-usd"></i><i class="icon-Check-Circle"></i><h3>Price Management</h3></a></li>
						</ul>
					</div>
				</div>
				<div class="clearfix"></div> 
          	</div>
           	<div class="clearfix"></div>
          	<div class="col-sm-12 hide">
	        	<div class="card mt20 xs-mt10 sm-mt10">
		            <div class="card-header">
		              <div>
		                <div class="col-md-12">
		                  <h2>Steps to Add Tour</h2>
		                </div>
		              </div>
		            </div>
	            	<div class="card-body">  
		              	<div id="document-ele-carousal">
			                <ul class="wizard-verfication-ul tour-wizard-ul">
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
			                      Add Tour Basic Details
			                    </p>
			                  </li>
			                  <li>  
			                    <p class="para">
			                      Add Departure Date
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
			                      Add City List
			                    </p>
			                  </li>
			                  <li>
			                    <p class="para">
			                      Add Itinerary
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
			                      Description
			                    </p>
			                  </li>
			                  <li>
			                    <p class="para">
			                      Add Price Management
			                    </p>
			                    <div class="circle-icon-no">
			                      <span>
			                        6
			                      </span>
			                    </div>
			                    <p class="para-small">
			                      Step
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
	        	</div>    
            </div>



			<form
				action="<?php echo base_url(); ?>index.php/tours/add_tour_save"
				method="post" enctype="multipart/form-data" id="form form-horizontal validate-form"
				class='form form-horizontal validate-form'>
				<div class="tab-content">
					<!-- Add Package Starts -->
					<div role="tabpanel" class="tab-pane active" id="add_package">
						   <div class="col-md-12">
                           
						   <div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Tour Name <span style = "color:red">*</span>
								</label>
								<div class='col-sm-8 controls'>
									<input type="text" name="package_name" id="package_name"
										placeholder="Enter Tour Name" data-rule-required='true'
										class='form-control add_pckg_elements' required>									
								</div>
							</div>
							
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Tour Description 
								</label>
								<div class='col-sm-8 controls'>
									<input type="text" name="package_description" id="package_description"
										placeholder="Enter Tour Description" data-rule-required='true'
										class='form-control add_pckg_elements' >									
								</div>
							</div>
							<div class='form-group ' id="select_date">
					        <label class='control-label col-sm-3' for='validation_current'>Start Date <span style = "color:red">*</span>
					        </label>
					        <div class='col-sm-4 controls'>
					        <input type="text" name="tour_start_date" id="tour_start_date" data-rule-required='true'
										class='form-control add_pckg_elements' required value="" placeholder="Choose Date" data-rule-required='true'  readonly> 
					        </div>
					       </div>
							<div class='form-group ' id="select_date">
					        <label class='control-label col-sm-3' for='validation_current'>Expiry Date <span style = "color:red">*</span>
					        </label>
					        <div class='col-sm-4 controls'>
					        <input type="text" name="tour_expire_date" id="tour_expire_date" data-rule-required='true'
										class='form-control add_pckg_elements' required value="" placeholder="Choose Date" data-rule-required='true'  readonly> 
					        </div>
					       </div>

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Supplier Name 
								</label>
								<div class='col-sm-8 controls'>
									<input type="text" name="supplier_name" id="supplier_name"
										placeholder="Enter Supplier Name" 
										class='form-control add_pckg_elements' >									
								</div>
							</div>
						<!-- 	<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Choose Theme <span style = "color:red">*</span>
								</label>
								<div class='col-sm-6 col-md-6 controls'>
								<select class='select2 form-control' data-rule-required='true' name='tour_type' id="tour_type" data-rule-required='true' required>
                                <option value="">Choose Theme</option>
                                <?php
                                foreach($tour_type as $k => $v)
                                {
                                	echo '<option value="'.$v['id'].'">'.$v['tour_type_name'].' </option>';
                                }
                                ?>
								</select>				
								</div>
							</div> -->

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Choose  Category
								</label>
								<div class='col-sm-3 col-md-3 controls'>
								<select class='select2 form-control'  name='tour_type[]' id="tour_type" multiple >
                               
                                <?php
                               foreach($tour_type as $k => $v)
                                {
                                	echo '<option value="'.$v['id'].'">'.$v['tour_type_name'].' </option>';
                                }
                                ?>
								</select>	
										
								</div>
								<div class='col-sm-1 col-md-1 controls'>
								>><br>
								<<	
								</div>
								<div class='col-sm-3 col-md-3 controls'>
								<select id="second_tour_type" class="form-control" name="tour_type_new[]" multiple>
								</select> 
								</div>
							</div>






							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Choose Theme
								</label>
								<div class='col-sm-3 col-md-3 controls'>
								<select class='select2 form-control'  name='theme[]' id="theme" multiple >
                                <?php
                                foreach($tour_subtheme as $k => $v)
                                {
                                	echo '<option value="'.$v['id'].'">'.$v['tour_subtheme'].' </option>';
                                }
                                ?>
								</select>	
											
								</div>
								<div class='col-sm-1 col-md-1 controls'>
								>><br>
								<<	
								</div>
								<div class='col-sm-3 col-md-3 controls'>
								
								<select id="second_theme" class="form-control" name="theme_new[]" multiple>
								</select>
								</div>

							</div>










							<!-- <div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Choose Theme 
								</label>
								<div class='col-sm-3 col-md-3 controls'>
								<select class='select2 form-control' name='theme[]' id="theme" multiple>
                               
                                <?php
                                foreach($tour_subtheme as $k => $v)
                                {
                                	echo '<option value="'.$v['id'].'">'.$v['tour_subtheme'].' </option>';
                                }
                                ?>
								</select>				
								</div>
								<div class='col-sm-3 col-md-3 controls'>
								<select id="second_theme" class="form-control" name="second_theme[]" multiple>
								</select>
								</div>
										
							</div> -->

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Choose Region <span style = "color:red">*</span>
								</label>
								<div class='col-sm-6 col-md-6 controls'>
								<select class='select2 form-control' data-rule-required='true' name='tours_continent' id="tours_continent" data-rule-required='true' required>
                               <option value="NA">Select Region </option>
                                <?php
                                foreach($tours_continent as $tours_continent_key => $tours_continent_value)
                                {
                                	echo '<option value="'.$tours_continent_value['id'].'">'.$tours_continent_value['name'].' </option>';
                                }
                                ?>
								</select>				
								</div>
							</div>
								<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Choose Country <span style = "color:red">*</span>
								</label>
								<div class='col-sm-6 col-md-6 controls'>
								<select class='select2 form-control' data-rule-required='true' name='tours_country[]' id="tours_country" multiple data-rule-required='true' required>
                              
                               
								</select>				
								</div>
							</div>
							<!-- <div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Choose Country <span style = "color:red">*</span>
								</label>
								<div class='col-sm-6 col-md-6 controls'>
								<select class='select2 form-control' name='tours_country' id="tours_country" data-rule-required='true' required>
                                <option value="">Choose Country</option>                               
								</select>				
								</div>
							</div> -->
							<!-- <div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Choose City <span style = "color:red">*</span>
								</label>
								<div class='col-sm-6 col-md-6 controls'>
								<select class='select2 form-control' name='tours_city[]' id="tours_city" multiple data-rule-required='true' required>
                                <option value="">Choose City</option>                               
								</select>				
								</div>
							</div> -->
							 <div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Choose City <span style = "color:red">*</span>
								</label>
								<div class='col-sm-3 col-md-3 controls'>
								
								<select class='select2 form-control' data-rule-required='true' name='tours_city[]' id="tours_city" multiple data-rule-required='true' required>
                                                            
								</select>
								
								</div>
								<div class='col-sm-1 col-md-1 controls'>
								>><br>
								<<	
								</div>
								<div class='col-sm-3 col-md-3 controls'>
								
								<select id="second" class="form-control" name="tours_city_new[]" multiple>
								</select>
											
								</div>		
								
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Choose Duration <span style = "color:red">*</span>
								</label>
								<div class='col-sm-6 col-md-6 controls'>
						        <select class='select2 form-control' data-rule-required='true' name='duration' id="duration" data-rule-required='true' required>          
						          <?php
						          echo '<option value="Select">Select</option>';
						          for($dno=1;$dno<=30;$dno++)
						          {
						           if($dno==1) { 
						            $DayNight = ($dno+1).' Days | '.($dno).' Night';
						           }else 
						           {
						            $DayNight = ($dno+1).' Days | '.($dno).' Nights';
						           }
						           echo '<option value="'.$dno.'">'.$DayNight.'</option>';
						          }
						          ?>
														</select>				
								</div>
							</div>


							<!--  <div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Sim Card Quantity <span style = "color:red">*</span>
								</label>
								<div class='col-sm-6 col-md-6 controls'>
								<div class="org_row">
								<div class='col-sm-6'>
								<input class='form-control numeric'  name='sim_quantity' id="sim_quantity" required type="text"  minlength="1" maxlength="99" />
                                                            
								 
								</div>
											
								</div>		
								</div>
							</div>


							 <div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Sim Type<span style = "color:red">*</span>
								</label>
								<div class='col-sm-6 col-md-6 controls'>
								<div class="org_row">
								<div class='col-sm-12'>
								 <select name="sim_type" class="form-group" id="sim_type" onchange="return sim_price_check();">
								 	<option value="Free">Free Sim</option>
								 	<option value="Paid">Paid Sim</option>

								 </select>
                                                            
								 
								</div>
											
								</div>		
								</div>
							</div> -->
							

							<div class='form-group' style="display: none;" id="sim_price_div">
								<label class='control-label col-sm-3' for='validation_current'>Sim Price( 1 Sim) <span style = "color:red">*</span>
								</label>
								<div class='col-sm-6 col-md-6 controls'>
								<div class="org_row">
								<div class='col-sm-6'>
								<input class='form-control'  name='sim_price' id="sim_price"  type="text"   />                                                          
								 
								</div>
											
								</div>		
								</div>
							</div>



							<div class='' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>								
										<button class='btn btn-primary' type="submit">Create Tour</button> &nbsp;
										<a class='btn btn-primary' href="<?php echo base_url(); ?>index.php/tours/tour_list">Go Back to Tour List</a>
									</div>
								</div>
							</div>						    						
						    <hr>
						    
						    
						</div>							
					</div>					
				</div>
			</form>
		</div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL WRAP END -->
</div>


 
<script type="text/javascript">
     $(document).ready(function()
     {
     	 
     	//var demo2 = $('select[name="theme[]"]').bootstrapDualListbox();
     	
		$('#theme').click(function() {
		    var options = $("#theme").find(':selected').clone();
		    $("#theme").find(':selected').remove();
		    
		    $('#second_theme').append(options);
            $("#theme option[value='"+options.val()+"']").attr('selected',true).attr("disabled",true).addClass('cstm_colr');
		        getSelectMultipleTheme(options);
		});

		$('#second_theme').click(function() {
			
			$('#theme').append($("#second_theme").find(':selected').clone());
			var options =  $("#second_theme").find(':selected').remove();
		    $("#theme option[value='"+options.val()+"']").removeAttr('disabled',false).removeClass('cstm_colr');
		    getSelectMultipleTheme(options);
		    $('#theme').html($('#theme option').sort(function(a){
				a = a.text;
				return a;
			}));
		}); 


		function getSelectMultipleTheme(options){
			$("#second_theme option[value='"+options.val()+"']").prop('selected', true);
		}

		$('#tour_type').click(function() {
		    var options = $("#tour_type").find(':selected').clone();
		    $("#tour_type").find(':selected').remove();
		    
		    $('#second_tour_type').append(options);
            $("#tour_type option[value='"+options.val()+"']").attr('selected',true).attr("disabled",true).addClass('cstm_colr');
		    // $("#theme option[value='"+options.val()+"']").remove();
		    getSelectMultipleTheme(options);
		});

		$('#second_tour_type').click(function() {
			
			$('#tour_type').append($("#second_tour_type").find(':selected').clone());
			
			
			
		   var options =  $("#second_tour_type").find(':selected').remove();
		   $("#tour_type option[value='"+options.val()+"']").removeAttr('disabled',false).removeClass('cstm_colr');
		   getSelectMultipleTheme(options);
		   $('#tour_type').html($('#tour_type option').sort(function(a){
				a = a.text;
				return a;
			}));
		});

		function getSelectMultipleTheme(options){
			$("#second_tour_type option[value='"+options.val()+"']").prop('selected', true);
		}






     	//var tours_country = $('select[name="tours_country[]"]').bootstrapDualListbox();
     	

     	//var demo1 = $('select[name="tour_type[]"]').bootstrapDualListbox({
					//  nonSelectedListLabel: 'Non-selected',
					 // selectedListLabel: 'Selected',
					//  preserveSelectionOnMove: false,
					 // moveOnSelect: false
				//	});

     	/*var tours_country = $('select[name="tours_country[]"]').bootstrapDualListbox({
					 // nonSelectedListLabel: 'Non-selected',
					//  selectedListLabel: 'Selected',
					//  preserveSelectionOnMove: false,
					//  moveOnSelect: false
					});*/
     	/*var tours_city = $('select[name="tours_city[]"]').bootstrapDualListbox({
					  nonSelectedListLabel: 'Non-selected',
					  selectedListLabel: 'Selected',
					  preserveSelectionOnMove: false,
					  moveOnSelect: false
					});*/

     	$('#tours_city').click(function() {
		    var options = $("#tours_city").find(':selected').clone();
		    $('#second').append(options);
		    getSelectMultiple();
		});

		$('#second').click(function() {
		   $("#second").find(':selected').remove();
		   getSelectMultiple();
		});

		function getSelectMultiple(){
			$("#second option").prop('selected', true);
		}


        $('#tours_continent').on('click', function() { 
            
        tours_continent = $('#tours_continent').val();
 //alert(tours_continent);
        if(tours_continent!='NA'){
        	$.post('<?=base_url();?>index.php/tours/ajax_tours_continent',{'tours_continent':tours_continent},function(data){
          	  //alert(data);
              $('#tours_country').html(data);
              $('#tours_city').html('');
              tours_country.bootstrapDualListbox('refresh', true);
         	});
        }else{
        	$('#tours_country').html('');
            $('#tours_city').html('');
        }
        });  

        $('#tours_country').on('change', function() { 
           
         	var tours_countries = $('#tours_country').val();
         
         	if(tours_countries==null){
         		$('#tours_city').html('');
         	}
         	if(tours_countries.length > 0 ){
				var tours_country_list = tours_countries;
         	}else{
	         	var tours_country_list = tours_countries.split(',');
	         }

         	$.each(tours_country_list, function(index, item) {

			    // do something with `item` (or `this` is also `item` if you like)

		        $.post('<?=base_url();?>index.php/tours/ajax_tours_country',{'tours_country':item},function(data)
		        {
		        	if(index>0){
			            $('#tours_city').append(data);
			            $('#tours_city').bootstrapDualListbox('refresh', true);
			        }else{			        	
			            $('#tours_city').html(data);
			            $('#tours_city').bootstrapDualListbox('refresh', true);
			        }
		        });
	        });
		});
     }); 
     $(document).ready(function () {
        $('#tour_start_date').datepicker({
        	minDate:0,
        	numberOfMonths: 2,
        	changeMonth: !0,
        	dateFormat: "yy-mm-dd"
        }); 

        $('#tour_expire_date').datepicker({
        	minDate:0,
        	numberOfMonths: 2,
        	changeMonth: !0,
        	dateFormat: "yy-mm-dd"
        });
    });   
</script>



<script type="text/javascript">
		function sim_price_check()
		{
			var sim_type=$("#sim_type").val();
			if(sim_type =="Free")
			{
					$("#sim_price_div").css("display","none");
					$("#sim_price").val(""+"0");
					$("#sim_price").removeAttr("required");
			}
			else
			{
					$("#sim_price_div").css("display","block"); 
					$("#sim_price").val("");
					$("#sim_price").prop('required',true);

			}
		}
	

</script>


 <!-- 
<script src="<?=JAVASCRIPT_LIBRARY_DIR?>common.js" type="text/javascript"></script>
<script	src="<?=SYSTEM_RESOURCE_LIBRARY?>/validate/jquery.validate.min.js" type="text/javascript"></script>
<script	src="<?=SYSTEM_RESOURCE_LIBRARY?>/validate/additional-methods.js" type="text/javascript"></script>
<script src="<?=SYSTEM_RESOURCE_LIBRARY?>/validate/custom.js"></script> -->