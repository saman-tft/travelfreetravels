<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-edit"></i> B2C users Reward Range 
				<span class="pull-right"></strong></span>
		
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="panel-body"><!-- PANEL BODY START -->
		<p style="color: red" class="alert-text"></p>
			<?php //debug($rewards_range); ?>
				<form action="<?=base_url()?>index.php/reward/reward_range_submit" class="form-horizontal" method="POST" autocomplete="off">
					<div class="hide">
					
						
					</div>
						<div class="more_range" >
						<?php //debug($rewards_range); ?>
						    <?php 

						    $row_count = 0;
						    foreach ($rewards_range as $key => $value) {
						    	 ?>
							<div class="row mainRow" data-range_count="<?=$row_count?>">
							<input type="hidden" value="<?=$row_count?>" class="data_range_count" name="data_range_count" >
							<?php $row_count++; ?>
							<div class="form-group">
						        <div class="col-md-5">
							    <div class="radio">
								<div class="row">
									<div class="col-md-4">
										Price Range<span class="text-danger">*</span>
										<input type="hidden" name="id[]" class="reward_point_id" value="<?=$value['id']?>" />
									</div>
									<div class="col-md-4">
										<input type="number" placeholder="From" name="reward_point_from[]"  min="0" max="10000000" class="form-control reward_point_from" value="<?=$value['reward_from']?>" required="">  
									</div>
									<div class="col-md-4">
										<input type="number" placeholder="To" name="reward_point_to[]"  min="0" max="10000000"   class="form-control reward_point_to" value="<?=$value['reward_to']?>" required="">  
									</div>
								</div>
								</div>
								</div>
								<div class="col-md-1"> = </div>
								<div class="col-md-5">
								    <div class="row">
								    	<div class="col-md-2">(%)</div>
									    <div class="col-md-7">
											<input  type="number" min="0" max="100" name="reward_percentage[]" id="reward_percentage" value="<?=$value['reward_value']?>"  class="form-control reward_percentage" required="">
										</div>
										<div class="col-md-3"><span class="btn btn-danger btn-sm remove_rage" id="<?=$value['id']?>"><span class="glyphicon glyphicon-remove"></span> remove</span></div>
										
									</div>
									<input type="hidden" name="currency" value="<?=get_application_default_currency()?>">
									<input type="hidden" name="origin" value="1">
								</div>
							</div>
						</div>
						<?php } ?>

					</div>
					<div class="row">
                            <span href="#" class="btn btn-primary btn-mini add_range"><span class="glyphicon glyphicon-plus"></span>Add More Range</span>&nbsp;&nbsp;
							<button class=" btn btn-mini btn-success" id="general-markup-submit-btn" type="submit"><span class="glyphicon glyphicon-chevron-right"></span> Apply</button>
					</div>		
					
				</form>
			</fieldset>
		</div><!-- PANEL BODY END -->
	</div><!-- PANEL WRAP END -->
</div>
<script type="text/javascript">
$(document).ready(function(){
	var count_range = 0;
    //for add the reward rage textfields	
	$(".add_range").click(function(){
	  //var range_count = $('.more_range data_range_count:last').val(); 
	  var range_count = $(".data_range_count").last().val();
	  range_count=parseInt(range_count)+1;
	  var div = $("<div clas=\"row mainRow\" />");
	  div.html(GetDynamicTextBox("",range_count));
      $(".more_range").append(div);
      //count_range = count_range++;
    });
    //remove the reward rage textfields
    $('body').on('click', '.remove_rage', function () {
       //count_range = count_range--;
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
          	// alert('ok');
          	$(this).closest(".mainRow").remove();
          }
        }
      });
      }else{

      	$(this).closest(".mainRow").remove();
      }
    });
    ////////to get the the dynamic content/////////
    function GetDynamicTextBox(value,range_count) {
      return '<input type="hidden" value="'+range_count+'" class="data_range_count" name="data_range_count" ><div class="mainRow form-group"><div class="col-md-5"><div class="row"><div class="col-md-4">Price Range<span class="text-danger">*</span></div><div class="col-md-4"><input type="number" name="reward_point_from[]" placeholder="From"  min="0" max="100000000"  class="form-control reward_point_from" value="" required=""></div><div class="col-md-4"><input type="number" name="reward_point_to[]" min="0"   max="100000000" placeholder="To"  class="form-control reward_point_to" value="" required=""></div></div></div><div class="col-md-1">=</div><div class="col-md-5"><div class="row"><div class="col-md-2">(%)</div><div class="col-md-7"><input  type="number" min="0" max="100" name="reward_percentage[]" value="" id="reward_percentage" class="form-control" required=""></div><div class="col-md-3"><span class="btn btn-danger btn-sm remove_rage"><span class="glyphicon glyphicon-remove"></span> remove</span></div></div></div></div>';
    }
});

$('#general-markup-submit-btn').on('click', function(e){
        
        var from_range = new Array();
		var to_range   = new Array();
        $(".reward_point_from").each(function() {
         from_range.push($(this).val());
        });
        $(".reward_point_to").each(function() {
          to_range.push($(this).val());
        });
        var count_ar = from_range.length;
        /////for from range///
        var find_dup =0;
        for(var j=0;j<count_ar;j++){
	        var count_ar = from_range.length;
	        var current_from_range = from_range[j];
		        for(var i=0;i<count_ar;i++){
		            
		            if(from_range[i]<current_from_range && to_range[i]>current_from_range ){
		         		find_dup++;
		         		console.log(find_dup+"not equal");
					}
					if(from_range[i]>to_range[i]){
						
						$(".alert-text").text("From range should be less than to range.");
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
                
                if(from_range[i]<current_to_range && to_range[i]>current_to_range){
					find_dup++;
				}
				if(find_dup){
					$(".alert-text").text("Duplication present between the ranges.");
					e.preventDefault();
					
				}else{
	                 
				}
			}
		}
        //////for checking equal to///////////
		var count_ar = count_ar_total =from_range.length;
		var find_dup =0;
		for(var j=0;j<count_ar;j++){
            var current_from_range = from_range[j];
       			for(var i=0;i<count_ar;i++){

            			if(current_from_range==from_range[i]){
            			  find_dup++;
            			}
            			if(find_dup>count_ar_total){
            				$(".alert-text").text("Duplication present between the ranges.");
				            e.preventDefault();
            			}
			}
		}
});

$(document).on('blur', '.reward_point_from', function() {
       
        var from_range = new Array();
		var to_range = new Array();
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

         	if(from_range[i]<current_from_range && to_range[i]>current_from_range){
				var find_dup = 1;
			}
			if(find_dup){
				$(".alert-text").text("Duplication present between the ranges.");

			}else{
                 
			}
		}
});
$(document).on('blur', '.reward_point_to', function() {
       
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
        for(var i=0;i<count_ar-1;i++){

         	if(from_range[i]<current_to_range && to_range[i]>current_to_range){
				var find_dup = 1;
			}
			if(find_dup){
				$(".alert-text").text("Duplication present between the ranges.");
			}else{
                 
			}
		}
});

$(document).on('keypress', '.reward_point_from ,.reward_point_to', function() {
	      $(".alert-text").text('');
});




//back up old code 
//from range validation///
// $(document).on('blur', '.reward_point_from', function() {
//         var from_range = new Array();
// 		var to_range = new Array();

//         $(".reward_point_from").each(function() {
//          from_range.push($(this).val());
//         });

//         $(".reward_point_to").each(function() {
//           to_range.push($(this).val());
//         });
        
//         var to_length = to_range.length;
// 		var count = $(this).closest('.mainRow').find("input[name='data_range_count']").val();
// 		var count =parseInt(count)-1;
// 		var last_to_range =   $(this).parents(".more_range").find("input[name='reward_point_to[]']:eq("+count+")").val();
// 		var current_to_range =  $(this).closest('.mainRow').find("input[name='reward_point_to[]']").val();
// 		var current_from_range =  $(this).closest('.mainRow').find("input[name='reward_point_from[]']").val();
		
// 		console.log("last_to_range="+last_to_range);
// 		console.log("current_to_range="+current_to_range);
// 		console.log("current_from_range="+current_from_range);


// 		if(count>-1){
// 		if(last_to_range>current_from_range){
// 				$(this).closest('.reward_point_from').val(last_to_range);
// 				}else if(current_from_range>current_to_range){
// 					$(this).closest('.reward_point_from').val(current_to_range);
// 				}else{
// 					$(this).closest('.reward_point_from').val(current_from_range);
// 				}
// 	    }else if(current_to_range<current_from_range){
// 		        $(this).closest('.reward_point_from').val(current_to_range);
// 	    }
	    
// });
///to range validation///
// $(document).on('blur', '.reward_point_to', function() {
        
//          var current_to_range =  $(this).closest('.mainRow').find("input[name='reward_point_to[]']").val();
//          var current_from_range =  $(this).closest('.mainRow').find("input[name='reward_point_from[]']").val();
//          var count = $(this).closest('.mainRow').find("input[name='data_range_count']").val();
// 		 var count =parseInt(count)+1;
// 		 var next_from_range =   $(this).parents(".more_range").find("input[name='reward_point_from[]']:eq("+count+")").val();
         
//          if(current_from_range>=current_to_range){
//          	$(this).closest('.reward_point_to').val(current_from_range);
//          } else if(next_from_range<current_to_range){
//          	$(this).closest('.reward_point_to').val(next_from_range);
//          }
//          else{
//          	$(this).closest('.reward_point_to').val(current_to_range);
//          }
//          console.log(next_from_range);
// });

</script>


