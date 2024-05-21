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
</style>	

<div class="bodyContent">
	<div class="panel panel-default clearfix">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Season Management
			</div>
		</div>
		<!-- PANEL HEAD START -->
		
			<!-- PANEL BODY START -->
			<div class="panel-body">
				<form method="post" id="post_form" name="tax" action="<?php echo site_url()."index.php/hotel/add_seasons"?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">				
					<fieldset form="user_edit">
				<legend class="form_legend">Add Season</legend>
			
						
							<input type="hidden" name="hotel_details_id" id="hotel_details_id" value="<?=$hotel_id?>">

								<div  class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Season Name</label>									
									<div class="col-sm-4"> 
										 <input type="text" class="form-control" name="seasons_name" id="seasons_name" data-validate="required" data-message-required="Please Enter the Seasons Name" value="<?php if(isset($seasons_name)){ echo $seasons_name; }?>"/>
										 <?php echo form_error('seasons_name',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
									</div>
								</div>	
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Season Date Start</label>									
									<div class="col-sm-4"> 
										 <input type="text" class="form-control" id="seasons_start" name="seasons_from_date" readonly  data-validate="required" data-message-required="Please Select the Date Range"  />
										 <?php echo form_error('seasons_from_date',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
									</div>
								</div>						
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Season Date End</label>									
									<div class="col-sm-4"> 
										 <input type="text" class="form-control" id="seasons_end" name="seasons_to_date" readonly  data-validate="required" data-message-required="Please Select the Date Range"  />
										 <?php echo form_error('seasons_to_date',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
									</div>
								</div>						
							
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label"></label>
									<div class="col-sm-5"> 	
									<p id="msg" style="color:red;"></p>
									<a href="<?=base_url("index.php/hotel/season_list/{$hotel_id}")?>" class="btn btn-danger">Back</a>&nbsp;&nbsp;
										<a id="check" class="btn btn-success">Add Season</a>
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
			$('#seasons_start').datepicker({
				dateFormat: 'dd/mm/yy',
				minDate: 0,
				//firstDay: 1,
				//maxDate: "+1Y",
			});
				$('#seasons_end').datepicker({
				dateFormat: 'dd/mm/yy',
				minDate: 0,
				//firstDay: 1,
				//maxDate: "+1Y",
			});
			
			 $("#check").click(function(){
			     var start = $("#seasons_start").val();
			     var end = $("#seasons_end").val();
			     var hotel_id = $("#hotel_details_id").val();
			     var id = "";
			     if(start == "" || end=="")
			     {
			         return;
			     }
			     $("#check").html("Please wait..");
			 $.ajax({
                url: "<?=base_url("hotels/check_availability")?>",
                type: "post",
                dataType: "html" ,
                data: {start:start,end:end,hotel_id:hotel_id,id:id} ,
                success: function (response) {
        
                if(response > 0)
                {
                  $("#msg").html("This date range season already exist.");  
                }
                else
                {
                   $("#post_form").submit(); 
                }
                $("#check").html("Add season");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                   console.log(textStatus, errorThrown);
                }
            });
            });
			
			
		});
	function addMoreRooms1() {
			$("#cancellation_clone").css({'display':'inherit'});
			var id = $('#rows_cnt').val();
			
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
								' <input type="text" class="form-control" value="" name="cancellation_percentage[]" id="cancellation_percentage'+id+'" value=""> </div></div>');																				
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
	  function select_room(hotel){	  		  	
	  	if(hotel != ""){
	  		var select1 = $('#room_type');
	  		$.ajax({
            url: '<?php echo site_url(); ?>/index.php/seasons/get_room_type/' + hotel,
            success: function (data, textStatus, jqXHR) {                     	            	
              select1.html('');
              select1.html(data);
              select1.trigger("chosen:updated");              
            }
           });         
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

	  $('#status').change(function(){
				var status = $('#status').val();
				if(status == "ACTIVE")
					$('#status').val('INACTIVE');
				else
					$('#status').val('ACTIVE');
	  });	

		$(function(){
			var hotel = "<?php if(isset($hotel_details_id)){ echo $hotel_details_id; } elseif(isset($hotel_id)) { echo $hotel_id; } ?>";
			var room_type_id = "<?php if(isset($room_type)){ echo $room_type; } else { echo ""; } ?>";						
			if(hotel != ""){
	  		var select1 = $('#room_type');
	  		$.ajax({
            url: '<?php echo base_url(); ?>index.php/seasons/get_room_type/' + hotel + '/' + room_type_id,
            success: function (data, textStatus, jqXHR) {                     	            	
              select1.html('');
              select1.html(data);
              select1.trigger("chosen:updated");              
            }
           });         
         }

         <?php 	  	
	  	 if(isset($cancellation_from)) {
	  	 	$count = sizeof($cancellation_from); 	  	 	
	  	 	if($count >= 1){
		     for($cp =1; $cp < $count; $cp++) {  	?>		     		      
		      addMoreRooms1(null);			    		      
		      $('#cancellation_from'+<?php echo $cp; ?>).val("<?php echo $cancellation_from[$cp]; ?>");
      		  $('#cancellation_to'+<?php echo $cp; ?>).val("<?php echo $cancellation_to[$cp]; ?>");	
      		  $('#cancellation_nightcharge'+<?php echo $cp; ?>).val("<?php echo $cancellation_nightcharge[$cp]; ?>");	
      		  $('#cancellation_percentage'+<?php echo $cp; ?>).val("<?php echo $cancellation_percentage[$cp]; ?>");	      		  		  
			  //alert("<?php echo $cancellation_from[$cp]; ?>");
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
         <?php } } else{  ?>
         	 $('#cutoffdiv').hide();	         	 
         <?php }?>

         <?php if(isset($status)){
         	if($status == "ACTIVE"){ ?>
         		$('#status').val('ACTIVE');
         <?php	} else{ ?>
         		$('#status').val('INACTIVE');
         <?php } } else{   ?>
         			$('#status').val('INACTIVE');
         <?php }     ?>

					

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
				var number_filter1 = /^[0-9 -/]*$/;
				var filter = /^[a-zA-Z 0-9!@#$%^&*()-+=:;'",.  ]*$/;

				if(seasons_name.value != '')
				{
					if(!(seasons_name.value.match(filter)))
					{
						seasons_name.style.border = "1px solid #f52c2c";   
						seasons_name.focus(); 
						return false; 
					}
				}
				else
				{
					seasons_name.value=seasons_date_range.value;
					//seasons_name.value.border = "1px solid #f52c2c";   
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
					if(!(seasons_date_range.value.match(number_filter1)))
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
/*

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
				*/
				  
		    });		

		});
	</script>