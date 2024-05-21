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
						data-toggle="tab">Package Descriptions [ Package Name : <?=string_replace_encode($tour_data['package_name'])?> ]</a></li>
					
				</ul>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
			<form
				action="<?php echo base_url(); ?>index.php/tours/tour_pricing_p2_save"
				method="post" enctype="multipart/form-data" id="form form-horizontal validate-form"
				class='form form-horizontal validate-form' onsubmit="return validation();">
				<div class="tab-content">
					<!-- Add Package Starts -->
					<div role="tabpanel" class="tab-pane active" id="add_package">
						   <div class="col-md-12">
						   	
						   	<input type="hidden" name="adult_twin_sharing" id="adult_twin_sharing" value="100"
										placeholder="Adult on twin sharing" data-rule-required='true'
										class='form-control add_pckg_elements' required>
										
										
									<input type="hidden" name="adult_tripple_sharing" id="adult_tripple_sharing" value="200"
										placeholder="Adult on tripple sharing"  class='form-control'>									
								
                           
						   <!--div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Per Person/Double Occupancy
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="adult_twin_sharing" id="adult_twin_sharing" value="<?=$tour_data['adult_twin_sharing']?>"
										placeholder="Adult on twin sharing" data-rule-required='true'
										class='form-control add_pckg_elements' required>									
								</div>
							</div>
<!-- 					 		<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Adult on tripple sharing
								</label>
								<div class='col-sm-4 controls'>
									<input type="hidden" name="adult_tripple_sharing" id="adult_tripple_sharing" value="<?=$tour_data['adult_tripple_sharing']?>"
										placeholder="Adult on tripple sharing"  class='form-control'>									
								</div>
							</div>  -->
							<input type="hidden" name="adult_tripple_sharing" id="adult_tripple_sharing" value="0">
							<!--<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Child with bed
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="child_with_bed" id="child_with_bed"  value="<?=$tour_data['child_with_bed']?>"
										placeholder="Child with bed" class='form-control'>									
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Child without bed
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="child_without_bed" id="child_without_bed" value="<?=$tour_data['child_without_bed']?>"
										placeholder="Child without bed" class='form-control' >									
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Joining directly
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="joining_directly" id="joining_directly" value="<?=$tour_data['joining_directly']?>"
										placeholder="Joining directly" class='form-control' >									
								</div>
							</div>

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Single Suppliment
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="single_suppliment" id="single_suppliment" value="<?=$tour_data['single_suppliment']?>"
										placeholder="Single Suppliment" class='form-control'>									
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Service Tax
								</label>
								<div class='col-sm-8 controls'>
									<input type="text" name="service_tax" id="service_tax" value="<?=string_replace_encode($tour_data['service_tax'])?>"
										placeholder="Service Tax" class='form-control'>									
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>TCS
								</label>
								<div class='col-sm-8 controls'>
									<input type="text" name="tcs" id="tcs" value="<?=string_replace_encode($tour_data['tcs'])?>"
										placeholder="TCS" class='form-control'>									
								</div>
							</div>-->
							<div class="form-group">
								<label class="control-label col-sm-3" for="validation_current">Highlights
								</label>
								<div class="col-sm-8 controls">
								<textarea name="highlights" class="form-control" cols="70" rows="5" placeholder="Tour Highlights"><?=string_replace_encode($tour_data['inclusions'])?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3" for="validation_current">Inclusions <span class="text-danger">*</span>
								</label>
								<div class="col-sm-8 controls">
								<textarea name="inclusions" class="form-control" cols="70" rows="5" placeholder="Tour Inclusions"><?=string_replace_encode($tour_data['inclusions'])?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3" for="validation_current">Exclusions <span class="text-danger">*</span>
								</label>
								<div class="col-sm-8 controls">
								<textarea name="exclusions" class="form-control" cols="70" rows="5" placeholder="Tour Exclusions"><?=string_replace_encode($tour_data['exclusions'])?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3" for="validation_current">Terms & Conditions
								</label>
								<div class="col-sm-8 controls">
								<textarea name="terms" class="form-control" cols="70" rows="5" placeholder="Terms & Conditions"><?=string_replace_encode($terms_n_Conditions[0]['terms_n_conditions'])?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3" for="validation_current">Cancellation Policy
								</label>
								<div class="col-sm-8 controls">
								<textarea name="canc_policy" class="form-control" cols="70" rows="5" placeholder="Cancellation Policy"><?=string_replace_encode($terms_n_Conditions[0]['cancellation_policy'])?></textarea>
								</div>
							</div>	
							<div class="form-group">
								<label class="control-label col-sm-3" for="validation_current">Trip Notes
								</label>
								<div class="col-sm-8 controls">
								<textarea name="trip_notes" class="form-control" cols="70" rows="5" placeholder="Trip Notes"><?=string_replace_encode($terms_n_Conditions[0]['trip_notes'])?></textarea>
								</div>
							</div>		
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Banner Image
								</label>
								<div class='col-sm-4 controls'>
									<input type="file" name="banner_image" id="banner_image" class='form-control' data-rule-required='true' >					<?=img_size_msg(360,320)?>				
								</div>
							</div>
						    
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Upload Gallery
								</label>
								<div class='col-sm-4 controls'>
									<input type="file" name="gallery[]" id="gallery" multiple data-rule-required='true' class='form-control' >				<?=img_size_msg(610,370)?>						
								</div>
							</div>
							<div class='form-group'>
                              <label class='control-label col-sm-3' for='validation_current'>Image Descriptions
                                </label>
                               <div class='col-sm-9 controls'>
                               
                            <textarea  placeholder="Description" class="form-control" name="image_description"   id="image_description" ></textarea>
                            <strong>Note *:</strong>
                       		<span style="color:#999;">Please update each image description followed by "#"</span>
                            </div>
                            </div>		

						    <div class='' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>	
									    <input type="hidden" name="tour_id" value="<?=$tour_id?>">						
										<button class='btn btn-primary' type="submit">Save</button>
									</div>
								</div>
							</div> 
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

     	  $('#tour_addition_save').on('click', function() { //alert('tour_addition_save');
          $package_name = $('#package_name').val();
          $destination  = $('#destination').val(); 
          $duration     = $('#duration').val();
          $.post('add_tour_pre/'+$package_name+'/'+$destination+'/'+$duration,{'duration':$duration},function(data)
          {
          	  //alert(data);
              $('#tour_addition').html(data);
          });
          });

          $('#no_of_days').on('change', function() { //alert('no_of_days');
          $no_of_days = $(this).val(); 
          $.post('no_of_days/'+$no_of_days,{'no_of_days':$no_of_days},function(data)
          {
          	  //alert(data);
              $('#itinerary_contents').html(data);
          });
          });

          $('#no_of_hotels').on('change', function() { //alert('no_of_hotels');
          $no_of_hotels = $(this).val(); 
          $.post('no_of_hotels/'+$no_of_hotels,{'no_of_hotels':$no_of_hotels},function(data)
          {
          	  //alert(data);
              $('#hotel_contents').html(data);
          });
          });

          $('#no_of_weather').on('change', function() { //alert('no_of_weather');
          $no_of_weather = $(this).val(); 
          $.post('no_of_weather/'+$no_of_weather,{'no_of_weather':$no_of_weather},function(data)
          {
          	  //alert(data);
              $('#weather_contents').html(data);
          });
          });

          $('#tour_dep_date').on('change', function() { //alert('tour_dep_date');
          $tour_dep_date = $(this).val(); 
          $.post('tour_dep_date/'+$tour_dep_date,{'tour_dep_date':$tour_dep_date},function(data)
          {
          	  //alert(data);
              $('#tour_dep_date_list').html(data);
              $('#tour_dep_date').val(''); 
          });
          });   
     });
     
     function validation()
     {
          $adult_twin_sharing    = $('#adult_twin_sharing').val();
          $adult_tripple_sharing = $('#adult_tripple_sharing').val();
          /*$child_with_bed        = $('#child_with_bed').val();
          $child_without_bed     = $('#child_without_bed').val();
          $joining_directly      = $('#joining_directly').val();
          $single_suppliment     = $('#single_suppliment').val();*/

          if($adult_twin_sharing=="")
          {
             $("#adult_twin_sharing").attr("placeholder","Adult Twin Sharing Required.");
             $("#adult_twin_sharing").focus();
             return false;
          }
          else if(isNaN($adult_twin_sharing))
          {
             $("#adult_twin_sharing").val('');
             $("#adult_twin_sharing").attr("placeholder","Valid Amount Required.");
             $("#adult_twin_sharing").focus();
             return false;
          }
          if($adult_tripple_sharing!="" && isNaN($adult_tripple_sharing))
          {
             $("#adult_tripple_sharing").val('');
             $("#adult_tripple_sharing").attr("placeholder","Valid Amount Required.");
             $("#adult_tripple_sharing").focus();
             return false;
          }
          /*if($child_with_bed!="" && isNaN($child_with_bed))
          {
             $("#child_with_bed").val('');
             $("#child_with_bed").attr("placeholder","Valid Amount Required.");
             $("#child_with_bed").focus();
             return false;
          }
          if($child_without_bed!="" && isNaN($child_without_bed))
          {
             $("#child_without_bed").val('');
             $("#child_without_bed").attr("placeholder","Valid Amount Required.");
             $("#child_without_bed").focus();
             return false;
          }
          if($joining_directly!="" && isNaN($joining_directly))
          {
             $("#joining_directly").val('');
             $("#joining_directly").attr("placeholder","Valid Amount Required.");
             $("#joining_directly").focus();
             return false;
          }
          if($single_suppliment!="" && isNaN($single_suppliment))
          {
             $("#single_suppliment").val('');
             $("#single_suppliment").attr("placeholder","Valid Amount Required.");
             $("#single_suppliment").focus();
             return false;
          } */
     }
</script>

<script type="text/javascript">
	function validation()
	{
	var inclusions = tinymce.get('inclusions').getContent();
	var exclusions = tinymce.get('exclusions').getContent(); 
	//var trip_notes = tinymce.get('trip_notes').getContent();  
   
    if (inclusions ==null || inclusions == '' )
      {
      alert("Inclusions is Required Field");
      return false;
      }
     /* if (exclusions ==null || exclusions == '' )
      {
      alert("Exclusions is Required Field");
      return false;
      }*/
     
	}
</script>
<link href="<?=get_domain()?>extras/system/template_list/template_v1/javascript/page_resource/datepicker/datepicker.css" rel="stylesheet"> 
<script src="<?=get_domain()?>extras/system/template_list/template_v1/javascript/page_resource/datepicker/datepicker.js"> </script>  
<script src="<?=get_domain()?>extras/system/template_list/template_v1/javascript/page_resource/datepicker/jquery.blueberry.js"> </script>  

<script type="text/javascript" src="<?=get_domain()?>extras/system/template_list/template_v1/javascript/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?=get_domain()?>extras/system/template_list/template_v1/javascript/js/tiny_mce/tiny_mce_call.js"></script> 
<!--
<script type="text/javascript" src="/chariot/extras/system/template_list/template_v1/javascript/js/nicEdit-latest.js"></script> 
<script type="text/javascript">
bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>-->
