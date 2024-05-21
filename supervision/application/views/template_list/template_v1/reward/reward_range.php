<style type="text/css">
	.vertical-align-equalto{
		margin-top: 10px;
	}
</style>
<!-- HTML BEGIN -->
<div class="bodyContent">
        


	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="alert alert-danger alert-dismissible fade in remove_alert" style="display: none;" >
		<span class="close" data-dismiss="alert" aria-label="close">x</span>
		Reward range removed successfully.
		</div>


		


		<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-edit"></i> B2C Users Reward Range 
				<span class="pull-right"></strong></span>

			</div>
		</div><!-- PANEL HEAD START -->
		
		<div class="panel-body"><!-- PANEL BODY START -->
		<p style="color: red" class="alert-text"></p>
			<?php 

			   $module_array =  array(
			   		'flight'=>'Flights',
			   		'hotel'=>'Hotel',
			   	//	'car'=>'Car',
			   		'activity'=>'Activity',
			   		'transfers'=>'Transfers',
			   		'holidays'=>'Holidays',
			   );

			 ?>

			<div id="exTab2" class="container">	
<ul class="nav nav-tabs">
	
			<?php 
				foreach ( $module_array as $key => $value) { 
				if($key=='flight'){
					$active_class  = 'active';
				}else{
					$active_class = "";
				}

				?>

				<li class="<?=$active_class?>"><a  href="#<?=$key?>" data-toggle="tab"><?=$value?></a></li>
			<?php } ?>								
			
			<!-- <li class="active"><a  href="#flight" data-toggle="tab">Flights</a></li>
			<li><a href="#hotel" data-toggle="tab">Hotel</a></li>
			<li><a href="#car" data-toggle="tab">Car</a></li>
			<li><a href="#activity" data-toggle="tab">Activity</a></li>
			<li><a href="#transfers" data-toggle="tab">Transfers</a></li>
			<li><a href="#holidays" data-toggle="tab">Holidays</a></li> -->
		</ul>

			<div class="tab-content ">
				<?php

				 foreach ($module_array as $key => $value) {

						if($key=='flight'){
						$active_class  = 'active';
						}else{
						$active_class = "";
						}
						?>
					
					<div class="tab-pane <?=$active_class?>" id="<?=$key?>">
						<form action="<?=base_url()?>index.php/reward/reward_range_submit/<?php echo $key?>" class="form-horizontal" method="POST" autocomplete="off">
					
						<div class="more_range_<?=$key?>" >
						<div class="row">

							<div class="col-md-5">
								<div class="form-group">
									<!-- <div class="col-md-4">Module : <span class="text-danger">*</span></div>
									<div class="col-md-4">
										<select class="form-control" required="" style="" id="module" name="module">
												<option value="All">All Modules</option>
												<option value="<?=META_AIRLINE_COURSE?>">Flights</option>
												<option value="<?=META_ACCOMODATION_COURSE?>">Hotel</option>
												<option value="<?=META_CAR_COURSE?>">Car</option>
												<option value="<?=META_SIGHTSEEING_COURSE?>">Activity</option>
												<option value="<?=META_TRANSFERV1_COURSE?>">Transfers</option>
												<option value="<?=META_PACKAGE_COURSE?>">Holidays</option>
										</select>
									</div> -->
									<input type="hidden" value="<?=$key?>" name="module">
								</div>
							</div>
						</div>
						<div class="form-group">
						    <div class="col-md-5">
							    <div class="row">
							    	<div class="col-md-4">
							    	</div>
							    	<div class="col-md-4">From Price
							    	</div>
							    	<div class="col-md-4">To Price
							    	</div>
							    </div>
							</div>
							<div class="col-md-1 vertical-align-equalto"></div>
							<div class="col-md-6">
							    <div class="row">
							    	<!-- <div class="col-md-1"></div> -->
							    	<div class="col-md-4">Redeem Points
							    	</div>
							    	<div class="col-md-4">Getting Points
							    	</div>
							    	<div class="col-md-3">Action
							    	</div>
							    </div>
							</div>
						</div>
						<?php 

						    $row_count = 0;

						    if($rewards_range){

						    foreach ($rewards_range[$key] as $key_range => $value) {

						    	 ?>
							<div class="row mainRow" id="complete_<?=$row_count?>" data-range_count="<?=$row_count?>">
							<input type="hidden" value="<?=$row_count?>" class="data_range_count_<?php echo $key;?>" name="data_range_count" >
							<?php $row_count++; ?>
							<div class="form-group">
						        <div class="col-md-5">
							    <div class="row">
									<div class="col-md-4">
										Price Range<span class="text-danger">*</span>
										<input type="hidden" name="id[]" class="reward_point_id" value="<?=$value['id']?>" />
										<input type="hidden" name="module_old[]" class="reward_point_id"  value="<?=$value['module']?>" />
									</div>
									<div class="col-md-4">
										<input type="number" placeholder="From Price" name="reward_point_from[]"  min="1" max="10000000" class="form-control reward_point_from_<?php echo $key;?>" value="<?=$value['reward_from']?>" required="">  
									</div>
									<div class="col-md-4">
										<input type="number" placeholder="To Price" name="reward_point_to[]"  min="1" max="10000000" class="form-control reward_point_to_<?php echo $key;?>" value="<?=$value['reward_to']?>" required="">  
									</div>
								</div>
								</div>
								<div class="col-md-1 vertical-align-equalto"> = </div>
								<div class="col-md-6">
								    <div class="row">
								    	<!-- <div class="col-md-1"></div> -->
									    <div class="col-md-4">
											<input  type="number" min="1" max="100" name="reward_percentage[]" id="reward_percentage" value="<?=$value['reward_value']?>"  class="form-control reward_percentage" required="">
										</div>
										<div class="col-md-4">
											<input  type="number" min="1" max="100" name="reward_getting[]" id="reward_getting" value="<?=$value['reward_getting']?>"  class="form-control reward_percentage" required="">
										</div>
										<div class="col-md-3"><span class="btn btn-danger btn-sm remove_rage" id="<?=$value['id']?>"><span class="fa fa-times"></span> Remove</span></div>
										
									</div>
									<input type="hidden" name="currency" value="<?=get_application_default_currency()?>">
									<input type="hidden" name="origin" value="1">
								</div>
							</div>
						</div>
						<?php }}else{ ?>
						<div class="alert alert-danger alert-dismissable">
							<button type="button" class="close" aria-hidden="true">&times;</button>
							<p>Please add reward ranges.</p>
						</div>
							<?php } ?>

					</div>
					<div class="row">
                            <span href="#" id="<?php echo $key?>" class="btn btn-primary btn-mini add_range"><span class="glyphicon glyphicon-plus"></span>Add Price Range</span>&nbsp;&nbsp;
							<button class=" btn btn-mini btn-success general-markup-submit-btn" id="<?=$key?>" type="submit"><span class="fa fa-angle-right"></span> Save Changes</button>
					</div>		
					
				</form>
					</div>
              <?php } ?>


					<div class="tab-pane" id="hotel">
					
					</div>
					<div class="tab-pane" id="car">
					
					</div>
			</div>
  </div>


				
			</fieldset>
		</div><!-- PANEL BODY END -->
	</div><!-- PANEL WRAP END -->
</div>
<script type="text/javascript">
$(document).ready(function(){
    $(".alert button.close").click(function (e) {
    $(this).parent().fadeOut('slow');
    });
    $(".alert-dismissable").click(function (e) {
    $(this).fadeOut('slow');
    });
    $('#module').val('<?=$current_module?>');
	//to change the module wise range
	$("#module").change(function(){
		var module = $("#module").val();
		window.location.replace("<?=base_url()?>index.php/reward/reward_range/"+module);
	});
	var count_range = 0;
	//for add the reward rage textfields	
	$(".add_range").click(function(){
	  $(".alert-dismissable").hide();	
	  id=$(this).attr("id");
	  
	  var range_count = $(".data_range_count_"+id).last().val();
	  if(!range_count)
	  {
	  	range_count=0;
	  }
	  
	  range_count=parseInt(range_count)+1;
	  
	  var div = $("<div clas=\"row mainRow\" />");
	  div.html(GetDynamicTextBox("",range_count,id));
      $(".more_range_"+id).append(div);
    });
    //remove the reward rage textfields
    $('body').on('click', '.remove_rage', function () {
     $(this).closest(".mainRow").remove();
     var id = this.id;
     if(id){
      $.ajax({
      	type:"POST",
      	url: "<?php echo base_url() ?>reward/reward_manage",
      	data:{'id':id},
      	dataType:'json',
      	async:false,
        success: function(result){
          if(result){
          	$(".remove_alert").show();
          	$(this).closest(".mainRow").remove();
          }
        }
      });
      }else{
        $(this).closest(".mainRow").remove();
      }
    });
////////to get the the dynamic content/////////
	function GetDynamicTextBox(value,range_count,module) 
	{
		
	  return '<span id="complete_'+range_count+'"><input type="hidden" value="'+range_count+'" class="data_range_count_'+module+'" name="data_range_count" ><div class="mainRow form-group"><div class="col-md-5"><div class="row"><div class="col-md-4">Price Range<span class="text-danger">*</span></div><div class="col-md-4"><input type="number" name="reward_point_from[]" placeholder="From Price"  min="0" max="100000000"  class="form-control reward_point_from_'+module+'" value="" required=""></div><div class="col-md-4"><input type="number" name="reward_point_to[]" min="0"   max="100000000" placeholder="To Price"  class="form-control reward_point_to_'+module+'" value="" required=""></div></div></div><div class="col-md-1 vertical-align-equalto">=</div><div class="col-md-6"><div class="row"><div class="col-md-4"><input  type="number" min="0" max="100" name="reward_percentage[]" placeholder="Redeem points(%)" value="" id="reward_percentage" class="form-control" required=""></div><div class="col-md-4"><input  type="number" min="0" max="100" name="reward_getting[]" placeholder="Getting points(%)" value="" id="getting_percentage" class="form-control" required=""></div><div class="col-md-3"><span class="btn btn-danger btn-sm remove_rage"><span class="glyphicon glyphicon-remove"></span> remove</span></div></div></div></div></span>';
	}
});
$('.general-markup-submit-btn').on('click', function(e){
        
        id=$(this).attr('id');
        
        var from_range = new Array();
		var to_range   = new Array();
        $(".reward_point_from_"+id).each(function() {
         from_range.push($(this).val());
        });
        $(".reward_point_to_"+id).each(function() {
          to_range.push($(this).val());
        });
        var count_ar = from_range.length;
        /////for from range//////
        var find_dup =0;
        for(var j=0;j<count_ar;j++){
	        var count_ar = from_range.length;
	        var current_from_range = from_range[j];
		        for(var i=0;i<count_ar;i++){
		            
		            from_range[i] = parseInt(from_range[i]);
		        	current_from_range = parseInt(current_from_range);
		        	to_range[i] = parseInt(to_range[i]);
		        	
		            if(from_range[i]<current_from_range && to_range[i]>current_from_range ){
		         		find_dup++;
		         		  var val_id = "#complete_"+(i);
		                  $(val_id).find(".reward_point_from_"+id).addClass('invalid-ip');
		                  $(val_id).find(".reward_point_to_"+id).addClass('invalid-ip');
		         		
		         	}
					if(from_range[i]>to_range[i]){
						
						$(".alert-text").text("From range should be less than to range.");
						var val_id = "#complete_"+(i);
		                $(val_id).find(".reward_point_from_"+id).addClass('invalid-ip');
		                $(val_id).find(".reward_point_to_"+id).addClass('invalid-ip');
						e.preventDefault();
					}
					if(find_dup){

						$(".alert-text").text("Duplication present between the ranges.");
						e.preventDefault();
					}
				}

		}
			for(var j=0;j<count_ar;j++){
            var count_ar = to_range.length;
	        var current_to_range = to_range[j];
	        var find_dup =0;
	        for(var i=0;i<count_ar;i++){
                
	        	from_range[i] = parseInt(from_range[i]);
        	    current_from_range = parseInt(current_to_range);
        	    to_range[i] = parseInt(to_range[i]);

                if(from_range[i]<current_to_range && to_range[i]>current_to_range){
					find_dup++;
				}
				if(find_dup){
					$(".alert-text").text("Duplication present between the ranges.");
					var val_id = "#complete_"+(i);
					$(val_id).find(".reward_point_from_"+id).addClass('invalid-ip');
					$(val_id).find(".reward_point_to_"+id).addClass('invalid-ip');
					e.preventDefault();
					
				}
			}
		}
        //////for checking equal to///////////
		var count_ar = count_ar_total =from_range.length;
		var find_dup =0;
		var find_dup_to =0;
		for(var j=0;j<count_ar;j++){
            var current_from_range = from_range[j];
            var current_to_range = to_range[j];
            for(var i=0;i<count_ar;i++){
       			if(current_from_range==from_range[i]){
    				find_dup++;
    			}
    			if(current_to_range==to_range[i]){
    				find_dup_to++;
    			}
    			
    			if(find_dup>count_ar_total){
    				var val_id = "#complete_"+(j);
					$(val_id).find(".reward_point_from_"+id).val(current_from_range).addClass('invalid-ip');
					$(val_id).find(".reward_point_to_"+id).addClass('invalid-ip');
    				$(".alert-text").text("Duplication present between the ranges.");
		            e.preventDefault();
    			}
    			if(find_dup_to>count_ar_total){
    				var val_id = "#complete_"+(j);
					$(val_id).find(".reward_point_to_"+id).val(current_to_range).addClass('invalid-ip');
					$(".alert-text").text("Duplication present between the ranges.");
		            e.preventDefault();
    			}
			}
		}
});

/////////////////////////on blur validation/////////////////////////////////////////

$(document).on('blur', '.reward_point_from', function() {
		if($(this).hasClass('invalid-ip')){
			$(this).removeClass('invalid-ip');
		}
        var from_range = new Array();
		var to_range = new Array();
		// console.log(from_range);
		// console.log(to_range);
        $(".reward_point_from").each(function() {
         from_range.push($(this).val());
        });
        $(".reward_point_to").each(function() {
          to_range.push($(this).val());
        });


        var count_ar = from_range.length;
        var current_from_range =  $(this).closest('.mainRow').find("input[name='reward_point_from[]']").val();
        var find_dup =0;
        for(var i=0;i<count_ar-1;i++){
        	
        	from_range[i] = parseInt(from_range[i]);
        	current_from_range = parseInt(current_from_range);
        	to_range[i] = parseInt(to_range[i]);

         	if(from_range[i]<=current_from_range && to_range[i]>=current_from_range){
				var find_dup = 1;
				var val_id = "#complete_"+(i);
				//alert(from_range[i]+"---"+current_from_range+"---"+to_range[i]);
				$(val_id).find(".reward_point_from").addClass('invalid-ip');
				$(val_id).find(".reward_point_to").addClass('invalid-ip');

			}
			//alert(find_dup);
			if(find_dup){
				$(".alert-text").text("Duplication present between the ranges.");
				$(this).addClass('invalid-ip');
			}else{
                 $(this).removeClass('invalid-ip');
			}
		}
});
/*$(document).on('blur', '.reward_point_to', function() {
		if($(this).hasClass('invalid-ip')){
			$(this).removeClass('invalid-ip');
		}
       
        var from_range = new Array();
		var to_range = new Array();
        $(".reward_point_from").each(function() {
         from_range.push($(this).val());
        });
        $(".reward_point_to").each(function() {
          to_range.push($(this).val());
        });
        var count_ar = to_range.length;
        var current_to_range =  $(this).closest('.mainRow').find("input[name='reward_point_to[]']").val();
        var find_dup =0;

		from_range[i] = parseInt(from_range[i]);
		current_to_range = parseInt(current_to_range);
		to_range[i] = parseInt(to_range[i]);

        for(var i=0;i<count_ar-1;i++){

         	if(from_range[i]<=current_to_range && to_range[i]>=current_to_range){
				var find_dup = 1;
				var val_id = "#complete_"+(i);
				$(val_id).find(".reward_point_from").addClass('invalid-ip');
				$(val_id).find(".reward_point_to").addClass('invalid-ip');
			}
			if(find_dup){
				$(".alert-text").text("Duplication present between the ranges.");
				$(this).addClass('invalid-ip');
			}else{
                 $(this).removeClass('invalid-ip');
			}
		}
});*/



$(document).on('keypress', '.reward_point_from ,.reward_point_to', function() {
	      $(".alert-text").text('');
	      $(".reward_point_from").removeClass('invalid-ip');
	      $(".reward_point_to").removeClass('invalid-ip');
});
</script>


