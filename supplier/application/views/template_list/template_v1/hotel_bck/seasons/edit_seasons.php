<!-- HTML BEGIN -->
<head>
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/font-icons/entypo/css/entypo.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-core.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-theme.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-forms.css">

<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/custom.css">
	
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery-1.11.0.min.js"></script>
	<link rel="stylesheet" href="http://192.168.0.83/OneClickk_V1/hotel_assets/assets/js/daterangepicker/daterangepicker-bs3.css"> 
	
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
</style>	

<div class="bodyContent">
	<div class="panel panel-default clearfix">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i>Season Management
			</div>
		</div>
		<!-- PANEL HEAD START -->
			<?php
			  $season_id = "";
			  if(isset($season_detail_id)){
			  	$season_id = $season_detail_id;
			  }
			  elseif(isset($seasons_list[0]->seasons_details_id)){
			  	$season_id = $seasons_list[0]->seasons_details_id;
			  }

						?>
			<!-- PANEL BODY START -->
			<div class="panel-body">
				<form method="post" id="tax" name="tax" action="<?php echo site_url()."/seasons/update_seasons/".base64_encode(json_encode($season_id))."/".$hotel_id1; ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">
					<fieldset form="user_edit">
				<legend class="form_legend">Edit Season</legend>
			
							<div class="form-group">
									<div class="col-sm-2"><label for="field-1" class="col-sm-12 control-label">Hotel</label>	</div>								
									<div class="col-sm-5">
									    <?php //debug($hotel_details_id);exit; ?>
									    
									 <select id="hotel_details_id" name="hotel_details_id" onChange="select_room(this.value);" class="form-control" readonly>
											 
											<?php foreach ($hotels_list as $hotel){ 
											 if($hotel->hotel_details_id == $seasons_list[0]->hotel_details_id )
											 {
											 ?>
											
												<option value="<?php echo $hotel->hotel_details_id; ?>" 
    												<?php 
    											/*	if(isset($hotel_details_id)) 
    												{
        												if($hotel_details_id == $hotel->hotel_details_id)
        												{ 
        												   // echo "if";exit;
        												    echo "selected"; 
        												    
        												}
    												} */
    											//	elseif( isset($seasons_list[0]->hotel_details_id))
    											if( isset($seasons_list[0]->hotel_details_id))
    												{ 
    												   // echo "elseif";exit;
    												    if($hotel->hotel_details_id == $seasons_list[0]->hotel_details_id )
    												{
    												echo "selected"; 
    												}
    												}?> 
    												data-iconurl=""><?php echo $hotel->hotel_name; ?>
												</option>
											<?php }  } ?>
										</select>
										<!--<input type="hidden" id="hotel_details_id" name="hotel_details_id" value="<?=$hotel_id?>">
										 <select  class="form-control" disabled="disabled" onChange="select_room(this.value);" id="hotel_details_id"  name="hotel_details_id">
											<?php foreach ($hotels_list as $hotel){ ?>
												<option value="<?php echo $hotel->hotel_details_id; ?>"
												 <?php  if(isset($hotel_details_id)){ if($hotel_details_id == $hotel->hotel_details_id) { echo "selected"; } } elseif($hotel_id_rec[0]->hotel_details_id == $hotel->hotel_details_id ){ echo "selected"; } ?> data-iconurl=""><?php echo $hotel->hotel_name; ?></option>
											<?php } ?>
										</select>-->
										<?php echo form_error('hotel_details_id',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
									</div>
								</div>	
								<div class="form-group">
								 <div class="col-sm-2">
									<label for="field-1"  class="col-sm-12 control-label">Room Type</label>									
								 </div>	
									<div class="col-sm-5">                                      
										<select name="room_type" onchange="get_from_location(this.value)" class="form-control" id="room_type">											
										<option value="0">Select</option>
										</select>
                                        <?php echo form_error('room_type',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
									</div>
								</div>	
								<div style="display:none;" class="form-group">
									<div class="col-sm-2">
										<label for="field-1" class="col-sm-12 control-label">Season Name</label>									
									</div>
									<div class="col-sm-5"> 
										 <input type="text" class="form-control" value="<?php if(isset($seasons_name)){ echo $seasons_name; } elseif($seasons_list[0]->seasons_name){ echo $seasons_list[0]->seasons_name; } ?>" name="seasons_name" id="seasons_name" data-validate="required" data-message-required="Please Enter the Seasons Name" />
										 <!---?php echo form_error('seasons_name',  '<span for="field-1" class="validate-has-error">', '</span>'); ?-->
									</div>
								</div>	
								<div class="form-group">
								  <div class="col-sm-2">
									<label for="field-1" class="col-sm-12 control-label">Season Date</label>									
								  </div>	
									<div class="col-sm-5"> 
										 <input type="text" class="form-control" id="seasons_date_range" readonly name="seasons_date_range" value="<?php 
										 if(isset($seasons_date_range)){ echo $seasons_date_range; }
										 elseif(isset($seasons_list)){
										  if($seasons_list[0]->seasons_from_date != "0000-00-00"){ 										   
										    echo date('d/m/Y',strtotime($seasons_list[0]->seasons_from_date)) ." - ".date('d/m/Y',strtotime($seasons_list[0]->seasons_to_date));
										  }
									    }
									    ?>" 
										 data-validate="required" data-message-required="Please Select the Date Range" />
									</div>
									<?php echo form_error('seasons_date_range',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
								</div>							

								<div class="form-group">
								   <div class="col-sm-2">
									<label for="field-1" class="col-sm-12 control-label">Minimum Stays (Days)</label>									
								   </div>	
									<div class="col-sm-5"> 
										 <input type="text" class="form-control" maxlength="2" name="minimum_stays" value="<?php if(isset($minimum_stays)){ echo $minimum_stays; } elseif(isset($seasons_list[0]->minimum_stays)){ echo $seasons_list[0]->minimum_stays; } ?>" id="minimum_stays" data-rule-number="true" data-validate="required" data-message-required="Please Enter the Minimum Stays" />
										 <?php echo form_error('minimum_stays',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
									</div>
								</div>			

								<!--
								<div class="form-group">
									<div class="col-sm-2">
										<label class="col-sm-12 control-label">Status</label>		
									</div>
									<div class="col-sm-5"> 	
										<div id="label-switch" class="make-switch" data-on-label="Active" data-off-label="InActive" style="min-width: 200px;">
											<input type="checkbox" name="status" value="ACTIVE" id="status" checked>
										</div>
									</div>
								</div>	
								-->
									<!-- <label for="field-1" class="col-sm-2 control-label">Cancellation Policy</label>		 -->
								<!-- <div class="form-group">									
									<br>
									        <label class="col-md-2 control-label">From(Days)</label>
											<label class="col-md-3 control-label">To(Days)</label>
											<label class="col-md-2 control-label ">No of Night (Per Room Rate)</label>											
											<label class="col-md-4 control-label">Charges (%) (Per Room Rate)</label>																				

									<div class="col-sm-2">
										<input type="text" class="form-control" name="cancellation_from[]" id="cancellation_from" value="<?php if(isset($policy[0]['cancellation_from'])) { echo $policy[0]['cancellation_from']; } elseif(isset($cancellation_policy[0]->cancellation_from)){ echo $cancellation_policy[0]->cancellation_from; } ?>">
										<?php echo form_error('cancellation_from',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>										
									</div>
															
									<div class="col-sm-3">
										<input type="text" class="form-control" name="cancellation_to[]" id="cancellation_to" value="<?php if( isset($policy[0]['cancellation_to'])){ echo $policy[0]['cancellation_to']; } elseif(isset($cancellation_policy[0]->cancellation_to)){ echo $cancellation_policy[0]->cancellation_to; } ?>">
										<?php echo form_error('cancellation_to',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>										
									</div>
									
												
									<div class="col-sm-3">
										<input type="text" class="form-control" name="cancellation_nightcharge[]" id="cancellation_nightcharge" value="<?php if(isset($policy[0]['cancellation_night_charge'])){ echo $policy[0]['cancellation_night_charge']; } elseif(isset($cancellation_policy[0]->cancellation_night_charge)){ if($cancellation_policy[$cp]->cancellation_night_charge == '0' ) { echo ''; } else{ echo $cancellation_policy[0]->cancellation_night_charge; } } ?>">
										<?php echo form_error('cancellation_nightcharge',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>										
									</div>

									<div class="col-sm-3">
										<input type="text" class="form-control" name="cancellation_percentage[]" id="cancellation_percentage" value="<?php if( isset($policy[0]['cancellation_percentage_charge'])){ echo $policy[0]['cancellation_percentage_charge']; } elseif(isset($cancellation_policy[0]->cancellation_percentage_charge)){ if($cancellation_policy[$cp]->cancellation_percentage_charge == 0) { echo ''; } else{ echo $cancellation_policy[0]->cancellation_percentage_charge; } } ?>">
										<?php echo form_error('cancellation_percentage',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>										
									</div>	
									<div class="col-sm-1">
										<input type="hidden" class="form-control" readonly name="cancellation_policy_id[]" id="cancellation_policy_id" value="<?php if( isset($policy[0]['cancellation_policy_id'])){ echo $policy[0]['cancellation_policy_id']; } elseif(isset($cancellation_policy[0]->hotel_cancellation_id)){ echo $cancellation_policy[0]->hotel_cancellation_id; } ?>">										
									</div>	
								</div>	 -->

								<!-- <div id="cancellation_clone"></div>
		    
							  	 <div class="form-group" id="btn">							  	 
								     <div class="col-md-12"> 								     		
											<div class="col-md-1"><input type="hidden" id="rows_cnt" value="1"/><button type="button" class="btn btn-success" onclick="addMoreRooms1();">Add</button></div>
											<div class="col-md-2"><button type="button" class="btn btn-success" onclick="removeLastRoom1(this);">Remove Last</button></div>
									</div>		
							  	 </div> -->					  
							<div class="form-group">
									<div class="col-md-2">
										<label class="col-sm-12 control-label"></label>
									</div>
									<div class="col-sm-5"> 	
										<button type="submit" class="btn btn-success">Update Season</button>
									</div>
								</div>	
				</form>
			</div>
		
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL END -->
</div>

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
	
	<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap-switch.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery.validate.min.js"></script>	
	<script src="<?php echo base_url(); ?>hotel_assets/js/fileinput.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery.bootstrap.wizard.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/ckeditor/ckeditor.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/ckeditor/adapters/jquery.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap-timepicker.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap-datepicker.js"></script>

	<script src="<?php echo base_url(); ?>hotel_assets/js/select2/select2.min.js"></script>
   <script src="<?php echo base_url(); ?>hotel_assets/js/jquery-ui.js"></script>

  <script src="<?php echo base_url(); ?>hotel_assets/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/datatables/TableTools.min.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/dataTables.bootstrap.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/datatables/jquery.dataTables.columnFilter.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/datatables/lodash.min.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/datatables/responsive/js/datatables.responsive.js"></script>   
 <!--    <script src="<?= base_url(); ?>assets/js/plugins/datatables/dataTables.overrides.js" type="text/javascript"></script>
    <script src="<?= base_url(); ?>assets/js/plugins/lightbox/lightbox.min.js" type="text/javascript"></script>
 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css" type="text/css">-->
  
	<script src="<?php echo base_url(); ?>hotel_assets/js/daterangepicker/moment.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/daterangepicker/daterangepicker.js"></script>

 <script>
	$(document).ready(function () { 
			$('#seasons_date_range').daterangepicker({
				format: 'DD/MM/YYYY'
        
    });
		});
	function addMoreRooms1() {		    
			$("#cancellation_clone").css({'display':'inherit'});
			var id = $('#rows_cnt').val();
			//alert(id);
	    	$("#cancellation_clone").append( '<div class="form-group" style="widht:80%;" ><div class="col-sm-2">'+								
								'<input type="text" class="form-control" name="cancellation_from[]" id="cancellation_from'+id+'" value="">'+
								'</div>'+
								'<div class="col-sm-3">'+
								'<input type="text" class="form-control" name="cancellation_to[]" id="cancellation_to'+id+'" value="">'+
								'</div>'+
								'<div class="col-sm-3">'+							
								'<input type="text" class="form-control" name="cancellation_nightcharge[]" id="cancellation_nightcharge'+id+'" value="">'+
								'</div>'+
								'<div class="col-sm-3 ">'+							
								'<input type="text" class="form-control" name="cancellation_percentage[]" id="cancellation_percentage'+id+'" value="">'+
								'</div>'+
								'<div class="col-sm-1 ">'+							
								'<input type="hidden" readonly class="form-control" name="cancellation_policy_id[]" id="cancellation_policy_id'+id+'" value="">'+
								'</div></div>');																				
			id = parseInt(id)+1;
			$('#rows_cnt').val(id);																

		}

		function removeLastRoom1(v){
			var id = $('#rows_cnt').val();
			var id1= parseInt(id)-1;
			var cancellation_policy_id = $('#cancellation_policy_id'+id1).val();						
			if(cancellation_policy_id > 1){
				var conval = confirm("Are you want to delete " +$('#cancellation_from'+id1).val()+" to "+ $('#cancellation_to'+id1).val());
				if(conval == true){
					$.ajax({
            			url: '<?php echo base_url(); ?>seasons/delete_cancellation_policy/' + cancellation_policy_id,
            			success: function (data, textStatus, jqXHR) {                     	            	            	              			
            				alert("Cancellation policy has deleted");
            				$('#cancellation_clone .form-group').last().remove();
							if(id <= 1) {
								$("#cancellation_clone").css({'display':'none'});
							}
							id = parseInt(id)-1;
							$('#rows_cnt').val(id);            				
						}	
           			});   	
           		}	
			}
			else{
				$('#cancellation_clone .form-group').last().remove();
				if(id <= 1) {
					$("#cancellation_clone").css({'display':'none'});
				}
				id = parseInt(id)-1;
				$('#rows_cnt').val(id);
			}	
		}


	 function select_cutoff(type){
	  	if(type == 2){
	  		$('#cutoffdiv').show();	
	  	}
	  	else{
	  		$('#cutoffdiv').hide();	
	  	}
	  }
	   function select_room(hotel){	  		  	
	  	if(hotel != ""){
	  		var select1 = $('#room_type');
	  		$.ajax({
            url: '<?php echo base_url(); ?>index.php/seasons/get_room_type/' + hotel,
            success: function (data, textStatus, jqXHR) {                     	            	
              select1.html('');
              select1.html(data);
              select1.trigger("chosen:updated");              
            }
           });         
         }	  	
	  }
		$(function(){					  
			var hotel = "<?php if(isset($hotel_details_id)){ echo $hotel_details_id; } elseif(isset($seasons_list[0]->hotel_details_id)){ echo $seasons_list[0]->hotel_details_id; }?>";
			var room_type = "<?php if(isset($room_type)) { echo $room_type; } elseif(isset($seasons_list[0]->hotel_room_type_id)){ echo $seasons_list[0]->hotel_room_type_id; } ?>"			
			//alert(room_type);
			if(hotel != ""){

	  		var select1 = $('#room_type');
	  		$.ajax({
            url: '<?php echo base_url(); ?>seasons/get_room_type/' + hotel+"/"+room_type,
            success: function (data, textStatus, jqXHR) {                     	            	            	
              select1.html('');
              select1.html(data);
              select1.trigger("chosen:updated");              
            }
           });   
	  	  }//if	 	    	
	  	 
	  	<?php 	  	
	  	 if(isset($cancellation_policy)) {
	  	 	$count = sizeof($cancellation_policy); 
	  	 	if($count >= 1){
		     for($cp =1; $cp < $count; $cp++) {  	?>		     
		      addMoreRooms1(null);			    		      
		      $('#cancellation_from'+<?php echo $cp; ?>).val("<?php echo $cancellation_policy[$cp]->cancellation_from; ?>");
      		  $('#cancellation_to'+<?php echo $cp; ?>).val("<?php echo $cancellation_policy[$cp]->cancellation_to; ?>");
			  $('#cancellation_nightcharge'+<?php echo $cp; ?>).val("<?php if($cancellation_policy[$cp]->cancellation_night_charge == 0){ echo ''; } else { echo $cancellation_policy[$cp]->cancellation_night_charge; } ?>");			  
			  $('#cancellation_percentage'+<?php echo $cp; ?>).val("<?php if($cancellation_policy[$cp]->cancellation_percentage_charge == 0) { echo '';} else { echo $cancellation_policy[$cp]->cancellation_percentage_charge; } ?>");
			  $('#cancellation_policy_id'+<?php echo $cp; ?>).val("<?php echo $cancellation_policy[$cp]->hotel_cancellation_id; ?>");
			  //alert("<?php echo $cancellation_policy[$cp]->cancellation_percentage_charge; ?>");
		<?php 
	         }//for
	        }//if
	       }//if  
	       elseif(isset($policy)){
           $count = sizeof($policy); 
	  	 	if($count >= 1){
		     for($cp =1; $cp < $count; $cp++) {  	?>		     
		      addMoreRooms1(null);			    		      		      
		      $('#cancellation_from'+<?php echo $cp; ?>).val("<?php echo $policy[$cp]['cancellation_from']; ?>");
      		  $('#cancellation_to'+<?php echo $cp; ?>).val("<?php echo $policy[$cp]['cancellation_to']; ?>");
			  $('#cancellation_nightcharge'+<?php echo $cp; ?>).val("<?php echo $policy[$cp]['cancellation_night_charge']; ?>");			  
			  $('#cancellation_percentage'+<?php echo $cp; ?>).val("<?php echo $policy[$cp]['cancellation_percentage_charge']; ?>");
			  $('#cancellation_policy_id'+<?php echo $cp; ?>).val("<?php echo $policy[$cp]['cancellation_policy_id']; ?>");			  
			  //alert("<?php echo $policy[$cp]['cancellation_percentage_charge']; ?>");
		<?php 
	         }//for
	        }//if
	       }//if  
	     ?>  
        <?php if(isset($season_type)){
         	 if($season_type == 2){ ?>
         	 	$('#cutoffdiv').show();	
         <?php	 } else{ ?>
         		$('#cutoffdiv').hide();	
         <?php } } elseif(isset($seasons_list[0]->season_type)) {
         	if($seasons_list[0]->season_type == 2) { ?>
         	 $('#cutoffdiv').show();	         	 
         <?php	 } else { ?>
         		$('#cutoffdiv').hide();	 	 
         <?php } }?>
			
			$('#status').change(function(){
				var status = $('#status').val();
				if(status == "ACTIVE")
					$('#status').val('INACTIVE');
				else
					$('#status').val('ACTIVE');
			});			

			var min_stay = document.getElementById('minimum_stays');
			$('input#minimum_stays').keyup(function() {				
				var $th = $(this);		
				if($th.val().trim() != ""){
					 var regex = /^[0-9]*$/;
					if (regex.test($th.val())) {
						$th.css('border', '1px solid #099A7D');					
					} else {						
						$th.css('border', '1px solid #f52c2c');
						return '';
					}				
				}
				 if(minimum_stays.value.length > 2 ) {
					    minimum_stays.style.border = "1px solid #f52c2c";   
						minimum_stays.focus(); 
						return false; 
				}
			});	

			var cutof = document.getElementById('cutoff');
			$('input#cutoff').keyup(function() {				
				var $th = $(this);		
				if($th.val().trim() != ""){
					 var regex = /^[0-9]*$/;
					if (regex.test($th.val())) {
						$th.css('border', '1px solid #099A7D');					
					} else {						
						$th.css('border', '1px solid #f52c2c');
						return '';
					}				
				}
				 if(cutoff.value.length > 2 ) {
					    cutoff.style.border = "1px solid #f52c2c";   
						cutoff.focus(); 
						return false; 
				}
			});

			$('#tax').submit(function(){

				var hotel_type1 = $('#hotel_details_id').val();				
				if(hotel_type1 == "0"){					
					hotel_details_id.style.border = "1px solid #f52c2c";   
					hotel_details_id.focus(); 
					return false; 			
				}			

				var number_filter = /^[0-9]*$/;
				var number_filter = /^[0-9 -/]*$/;
				var filter = /^[a-zA-Z 0-9!@#$%^&*()-+=:;'",.  ]*$/;

				if(seasons_name.value != '')
				{
					seasons_name.value=seasons_date_range.value;
					/*if(!(seasons_name.value.match(filter)))
					{
						seasons_name.style.border = "1px solid #f52c2c";   
						seasons_name.focus(); 
						return false; 
					}*/
				}
				else
				{
					seasons_name.value=seasons_date_range.value;
					//seasons_name.style.border = "1px solid #f52c2c";   
					//seasons_name.focus(); 
					//return false; 
				}

				if(seasons_name.value.length < 2 || seasons_name.value.length > 50) {
					    seasons_name.style.border = "1px solid #f52c2c";   
						seasons_name.focus(); 
						return false; 
				}

				if(seasons_date_range.value != '')
				{
					if(!(seasons_date_range.value.match(number_filter)))
					{
						seasons_date_range.style.border = "1px solid #f52c2c";   
						seasons_date_range.focus(); 
						return false; 
					}
				}
				else
				{
					seasons_date_range.style.border = "1px solid #f52c2c";   
					seasons_date_range.focus(); 
					return false; 
				}
				
				if(seasons_date_range.value == '' ) {
					    seasons_date_range.style.border = "1px solid #f52c2c";   
						seasons_date_range.focus(); 
						return false; 
				}

				if(seasons_date_range.value.length != 23 ) {
					    seasons_date_range.style.border = "1px solid #f52c2c";   
						seasons_date_range.focus(); 
						return false; 
				}

				if(minimum_stays.value != '')
				{
					if(!(minimum_stays.value.match(number_filter)))
					{
						minimum_stays.style.border = "1px solid #f52c2c";   
						minimum_stays.focus(); 
						return false; 
					}
				}
				else
				{
					minimum_stays.style.border = "1px solid #f52c2c";   
					minimum_stays.focus(); 
					return false; 
				}
				
				if(minimum_stays.value == '' ) {
					    minimum_stays.style.border = "1px solid #f52c2c";   
						minimum_stays.focus(); 
						return false; 
				}

				if(minimum_stays.value.length > 2 ) {
					    minimum_stays.style.border = "1px solid #f52c2c";   
						minimum_stays.focus(); 
						return false; 
				}

				if(cutoff.value != '')
				{
					if(!(cutoff.value.match(number_filter)))
					{
						cutoff.style.border = "1px solid #f52c2c";   
						cutoff.focus(); 
						return false; 
					}
				}
				else
				{
					cutoff.style.border = "1px solid #f52c2c";   
					cutoff.focus(); 
					return false; 
				}
				
				if(cutoff.value == '' ) {
					    cutoff.style.border = "1px solid #f52c2c";   
						cutoff.focus(); 
						return false; 
				}

				if(cutoff.value.length > 2 ) {
					    cutoff.style.border = "1px solid #f52c2c";   
						cutoff.focus(); 
						return false; 
				}


				  
		    });	
			
		});
	</script>
  