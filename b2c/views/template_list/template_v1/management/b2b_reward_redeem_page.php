<style>
.trvlwrap {
    width: 80%;
    margin: 0 auto;
    position: relative;
    left: 45%;
    transform: translateX(-50%);
}
.staffareadash{
    box-shadow:none;
}
</style>
<?php 
//error_reporting(E_ALL);


?>
<!-- HTML BEGIN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css">
<div class="content-wrapper dashboard_section">
    <div class="container">
        <div class="staffareadash">
            <div id="general_user" class="content-wrapper dashboard_section">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/profile_navigator_tab') ?>
                <div class="panel panel-default">
                     <!-- PANEL WRAP START -->
                     <div class="panel-heading">
                        <!-- PANEL HEAD START -->
                        <div class="panel-title">

                            <h4>Reward Redeem points</h4>

                        </div>
                    </div>
                    <!-- PANEL HEAD START -->
                    <div class="panel-body">
                        <!-- PANEL BODY START -->
                        <div class="cetrel_all">

                            <?php  if(!isset($print_voucher) && ($print_voucher!='yes')){ echo $GLOBALS['CI']->template->isolated_view('share/navigation'); } ?>

                        </div>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="mybookings">

                                <div class="trvlwrap">

                                    <!--/************************ GENERATE Filter Form ************************/-->

                                    <div class="clearfix"></div>
                                    <!--/************************ GENERATE Filter Form ************************/-->
                                    <div class="clearfix">




                                        <div class="clearfix">
                                            <div class="col-md-12 table-responsive ">
                                                <table class="table table-condensed table-bordered">
                                                    <thead>
                                                        <tr>


                                                            <th>Total</th>
                                                            <th>Hotel</th>
                                                            <th>Transfer</th>
                                                            <th>Holidays</th>
                                                            <th>Excursion</th>
                                                            <th>Visa</th>





                                                        </tr>
                                                    </thead>
                                                    <tbody>











                                                        <tr>

                                                            <?php if($total_point){?>
                                                            <td><?=@$total_point['t_redeem']?></td>
                                                            <td><?=@$total_point['rhotel']?></td>

                                                            <td><?=@$total_point['rtransfer']?></td>
                                                            <td><?=@$total_point['rholiday']?></td>

                                                            <td><?=@$total_point['ractivities']?></td>
                                                            <td><?=@$total_point['rvisa']?></td>

                                                            <?php }else{?>
                                                            <td>0</td>
                                                            <td>0</td>
                                                            <td>0</td>
                                                            <td>0</td>
                                                            <td>0</td>
                                                            <td>0</td>

                                                            <?php }?>


                                                        </tr>




                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel-title">
                                <h4>Redeem Request Form</h4>
                                <div class="col-md-12">

                                    <!-- Tab panes -->


                                    <form method="POST" autocomplete="off" id="search_filter_form"
                                        action="<?php echo base_url()?>loyalty_program/redeem_request">



                                        <div id="productdetails" style="display:none;">

                                        </div>

                                        <div class="col-sm-12 well well-sm">
                                            <button class="btn btn-primary" type="button"
                                                id="submitbutton">Submit</button>

                                            <a class="btn btn-warning"
                                                href="<?php echo base_url();?>loyalty_program/product_list">Back</a>
                                        </div>
                                    </form>
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
        </div>
    </div>
</div>
<!-- HTML END -->





<script>
var total_reward = "<?=@$total_point['t_redeem']?>";

var moduletypename = "";
$(document).ready(function() {
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>index.php/loyalty_program/get_product_list',
        data: {
            'total_reward': total_reward
        },

        success: function(response) {
            $("#productdetails").html(response);
            $("#productdetails").show();
            $("").product_id
            $("#module_type").prop('required', false);
        }
    });
    $("#submitbutton").click(function() {
        // alert($('[name="product_id[]"]:checked').length);
        if ($('[name="product_id[]"]:checked').length > 0) {
            $("#search_filter_form").submit();
        } else {
            // alert();
            $(".colorred").show();
            $(".colorred").text("Please Select product");
        }
        // var seletedproduct=$()
    })
});


//set dropdownlist selected
/*$('#redeem_type').on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    if(valueSelected=='cash')
    {
    	$("#credit_amount").show();
    	$("#productdetails").hide();
    	$("#module_type").prop('required',true);
    	$("#reward_point").prop('required',true);



    }
    else if(valueSelected=='credit amount')
    {
    	$("#credit_amount").show();
    	$("#productdetails").hide();
    	$("#module_type").prop('required',true);
    	$("#reward_point").prop('required',true);
    }
    else if(valueSelected=='product')
    {
    	
    	$("#credit_amount").hide();
    	$("#module_type").prop('required',false);
    	$("#reward_point").prop('required',false);


    	$.ajax({
                type: "POST",
                url: '<?php echo base_url();?>index.php/loyalty_program/get_product_list',
                data:{'total_reward':total_reward},
                
                success: function(response)
                {
                   $("#productdetails").html(response);
                   $("#productdetails").show();
                   $("").product_id
                   $("#module_type").prop('required',false);
               }
           }); 

    }
});
	
	$('#module_type').on('change', function (e) {
    		var moduletype = $("option:selected", this);
    		moduletypename = this.value;
    		// alert(moduletypename);
    })
    

});*/
/*function checkamount(){
  		var reward_point=$("#reward_point").val();
  		// alert(total_hotel);
  		if(moduletypename !="")
  		{
  			if(moduletypename=='hotel')
  			{
  				if(reward_point !=""){
  					if(reward_point <=total_hotel)
  					{
  						$.ajax({
                            type: "POST",
                            url: '<?php echo base_url();?>index.php/loyalty_program/get_redeem_amout',
                            data:{'reward_point':reward_point,'module_type':moduletypename},
                            
                            success: function(response)
                            {
                                if(response)
                                {
                                    if(response >0){
                                    	$("#amount").val(reward_point*response);
                                    }
                                }
                           }
                       });  
  					}
  					else
  					{
  						alert("Enter redeem reward point less or equal to hotel reward point");
  					}
  				}
  				else
  				{
  					alert("Enter redeem reward point");
  				}
  			}
  			else if(moduletypename=='transfer')
  			{
  				if(reward_point !=""){
  					if(reward_point <=transfer)
  					{
  						$.ajax({
                            type: "POST",
                            url: '<?php echo base_url();?>index.php/loyalty_program/get_redeem_amout',
                            data:{'reward_point':reward_point,'module_type':moduletypename},
                            
                            success: function(response)
                            {
                                if(response)
                                {
                                    if(response >0){
                                    	$("#amount").val(reward_point*response);
                                    }
                                }
                           }
                       });  
  					}
  					else
  					{
  						alert("Enter redeem reward point less or equal to transfer reward point");
  					}
  				}
  				else
  				{
  					alert("Enter redeem reward point");
  				}

  			}
  			else if(moduletypename=='holiday')
  			{
  				if(reward_point !=""){
  					if(reward_point <=holidays)
  					{
  						$.ajax({
                            type: "POST",
                            url: '<?php echo base_url();?>index.php/loyalty_program/get_redeem_amout',
                            data:{'reward_point':reward_point,'module_type':moduletypename},
                            
                            success: function(response)
                            {
                                if(response)
                                {
                                    if(response >0){
                                    	$("#amount").val(reward_point*response);
                                    }
                                }
                           }
                       });  
  					}
  					else
  					{
  						alert("Enter redeem reward point less or equal to holiday reward point");
  					}
  				}
  				else
  				{
  					alert("Enter redeem reward point");
  				}
  			}
  			else if(moduletypename=='activities')
  			{
  				if(reward_point !=""){
  					if(reward_point <=activities)
  					{
  						$.ajax({
                            type: "POST",
                            url: '<?php echo base_url();?>index.php/loyalty_program/get_redeem_amout',
                            data:{'reward_point':reward_point,'module_type':moduletypename},
                            
                            success: function(response)
                            {
                                if(response)
                                {
                                    if(response >0){
                                    	$("#amount").val(reward_point*response);
                                    }
                                }
                           }
                       });  
  					}
  					else
  					{
  						alert("Enter redeem reward point less or equal to activities reward point");
  					}
  				}
  				else
  				{
  					alert("Enter redeem reward point");
  				}
  				
  			}

  		}
  		else
  		{
  			alert("Select module Type.");
  		}
	}*/
</script>

<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.js"></script>