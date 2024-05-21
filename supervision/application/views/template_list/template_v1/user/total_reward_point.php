<?php 
error_reporting(E_ALL);


?>
<!-- HTML BEGIN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css">

<div id="general_user" class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<h4>Total Reward point spent</h4>

</div>
</div>
<!-- PANEL HEAD START -->
<div class="panel-body"><!-- PANEL BODY START -->
<div class="tab-content">


<!--/************************ GENERATE Filter Form ************************/-->

<div class="clearfix"></div>
<!--/************************ GENERATE Filter Form ************************/-->
<div class="clearfix">

	
	
	
   <div class="clearfix">
   <div class="col-md-12 table-responsive tbpd " >
   <table class="table table-condensed table-bordered">
	<thead><tr>
   
   
   <th>Hotel</th>
   <th>Transfer</th>
   <th>Holidays</th>
   <th>Excursion</th>
   <th>Visa</th>
   
  
   
   
   
   </tr></thead><tbody>
  
		
	

		
		

			
			
			

			<tr>
			
			
			<td><?=$get_all_list['thotel']?></td>
			
			<td><?=$get_all_list['ttransfer']?></td>
			<td><?=$get_all_list['holidays']?></td>
			
			<td><?=$get_all_list['tactivities']?></td>
			<td><?=$get_all_list['tvisa']?></td>
			
			
			
			
			
</tr>
		
		
		
	
	</tbody></table></div></div>
</div>

</div>
</div>
<!-- PANEL BODY END --></div>
<!-- PANEL WRAP END --></div>
<!-- HTML END -->


 


<script>
$(document).ready(function() {
	
	 //set dropdownlist selected
	

});

</script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.js"></script>



