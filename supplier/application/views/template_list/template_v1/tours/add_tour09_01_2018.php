<?php error_reporting(0); ?>
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
						data-toggle="tab">Package Manager </a></li>
					
				</ul>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
			<form
				action="<?php echo base_url(); ?>index.php/tours/add_tour_save"
				method="post" enctype="multipart/form-data" id="form form-horizontal validate-form"
				class='form form-horizontal validate-form'>
				<div class="tab-content">
					<!-- Add Package Starts -->
					<div role="tabpanel" class="tab-pane active" id="add_package">
						   <div class="col-md-12">
                           
						   <div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Package Name <span style = "color:red">*</span>
								</label>
								<div class='col-sm-8 controls'>
									<input type="text" name="package_name" id="package_name"
										placeholder="Enter Package Name" data-rule-required='true'
										class='form-control add_pckg_elements' required>									
								</div>
							</div>
							
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Package Description 
								</label>
								<div class='col-sm-8 controls'>
									<input type="text" name="package_description" id="package_description"
										placeholder="Enter Package Description" data-rule-required='true'
										class='form-control add_pckg_elements' >									
								</div>
							</div>
							<div class='form-group ' id="select_date">
					        <label class='control-label col-sm-3' for='validation_current'>Expiry Date <span style = "color:red">*</span>
					        </label>
					        <div class='col-sm-4 controls'>
					        <input type="text" name="tour_expire_date" id="tour_expire_date" class="form-control" value="" placeholder="Choose Date" data-rule-required='true'  readonly> 
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
								<label class='control-label col-sm-3' for='validation_current'>Choose  Activity
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
								<div class='col-sm-3 col-md-3 controls'>
								<select id="second_tour_type" class="form-control" name="tour_type_new[]" multiple>
								</select> 
								</div>
							</div>



							<div class='form-group'>
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
								<select id="second_theme" class="form-control" name="theme_new[]" multiple>
								</select>
								</div>
										
							</div>

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
								<div class='col-sm-6 col-md-6 controls'>
								<div class="org_row">
								<div class='col-sm-6'>
								<select class='select2 form-control' data-rule-required='true' name='tours_city[]' id="tours_city" multiple data-rule-required='true' required>
                                                            
								</select>
								</div>
								<div class='col-sm-6'>
								<select id="second" class="form-control" name="tours_city_new[]" multiple>
								</select>
								</div>				
								</div>		
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Choose Duration <span style = "color:red">*</span>
								</label>
								<div class='col-sm-6 col-md-6 controls'>
        <select class='select2 form-control' data-rule-required='true' name='duration' id="duration" data-rule-required='true' required>          
          <?php
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
							<div class='' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>								
										<button class='btn btn-primary' type="submit">Create Package</button> &nbsp;
										<a class='btn btn-primary' href="<?php echo base_url(); ?>index.php/tours/tour_list">Package List</a>
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
     	//var demo1 = $('select[name="tour_type[]"]').bootstrapDualListbox();
     	//var demo2 = $('select[name="theme[]"]').bootstrapDualListbox();

     	$('#theme').click(function() {
		    var options = $("#theme").find(':selected').clone();
		    
		    $('#second_theme').append(options);
		
		    $("#theme option[value='"+options.val()+"']").prop("disabled",true).addClass('cstm_colr');
		    // $("#theme option[value='"+options.val()+"']").remove();
		    getSelectMultipleTheme(options);
		});

		$('#second_theme').click(function() {
		   var options =  $("#second_theme").find(':selected').remove();
		   $("#theme option[value='"+options.val()+"']").removeAttr('disabled',false).removeClass('cstm_colr');
		   getSelectMultipleTheme(options);
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

     	var tours_country = $('select[name="tours_country[]"]').bootstrapDualListbox({
					 // nonSelectedListLabel: 'Non-selected',
					//  selectedListLabel: 'Selected',
					//  preserveSelectionOnMove: false,
					//  moveOnSelect: false
					});
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

        if(tours_continent!='NA'){
        	$.post('<?=base_url();?>tours/ajax_tours_continent',{'tours_continent':tours_continent},function(data){
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

		        $.post('<?=base_url();?>tours/ajax_tours_country',{'tours_country':item},function(data)
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
</script>
<link href="<?=get_domain()?>extras/system/template_list/template_v1/javascript/page_resource/datepicker/datepicker.css" rel="stylesheet"> 
<script src="<?=get_domain()?>extras/system/template_list/template_v1/javascript/page_resource/datepicker/datepicker.js"> </script>  
<script src="<?=get_domain()?>extras/system/template_list/template_v1/javascript/page_resource/datepicker/jquery.blueberry.js"> </script>  
<!--<script src="<?=JAVASCRIPT_LIBRARY_DIR?>common.js" type="text/javascript"></script>
<script	src="<?=SYSTEM_RESOURCE_LIBRARY?>/validate/jquery.validate.min.js" type="text/javascript"></script>
<script	src="<?=SYSTEM_RESOURCE_LIBRARY?>/validate/additional-methods.js" type="text/javascript"></script>
<script src="<?=SYSTEM_RESOURCE_LIBRARY?>/validate/custom.js"></script>-->