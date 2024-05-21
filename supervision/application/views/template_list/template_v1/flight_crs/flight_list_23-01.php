<?php
//debug($data);
?>
<style type="text/css">
a.act { cursor: pointer;} 
.table{margin-bottom: 0;}
.modal-footer { padding: 10px;}
  .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
  .toggle.ios .toggle-handle { border-radius: 20px; }
</style>
<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				Flight List
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
		<div class="table-responsive">
			<!-- PANEL BODY START -->
			<table class="table table-bordered table-hover table-condensed" id="tab_flight_list">
			<thead>
				<tr>
					<th><i class="fa fa-sort-numeric-asc"></i> SNo</th>
					<th>Origin</th>
					<th>Description</th>
					<th>Departure From Datetime</th>
					<th>Departure To Datetime</th> 
					<th>Flight Number</th>
					<th>Carrier Code</th>
					<th>Airline Name</th>
					<th>Class</th>
					<th>No Of Stops</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
				</thead><tbody>
				<?php 
				if(count($data)>0){
				foreach($data as $key => $flight_list_details){ 
					if($flight_list_details['active']==1)
						$chk = "checked";
					else
						$chk = "";
					?>
				<tr>
					<td> <?=$key+1?></td>
					<td><?=$flight_list_details['origin']?></td>
					<td><?=$flight_list_details['destination']?></td>
					<td><?=date('d-m-Y',strtotime($flight_list_details['dep_from_date']))?>
						<?=date('H:i',strtotime($flight_list_details['departure_time']))?>
					</td>
					<td><?=date('d-m-Y',strtotime($flight_list_details['dep_to_date']))?>
						<?=date('H:i',strtotime($flight_list_details['arrival_time']))?>
					</td>
					<td><?=$flight_list_details['flight_num']?></td>
					<td><?=$flight_list_details['carrier_code']?></td>
					<td><?=$flight_list_details['airline_name']?></td>
					<td><?=$flight_list_details['class_type']?></td>
					<td><?=$flight_list_details['no_of_stops']?></td>
					<td><button type="button" class="btn btn-sm btn-toggle stus dyna_status <?=($flight_list_details['active'] == 1)?'active' : '' ?> act-<?=$flight_list_details['fsid']?>" data-toggle="button" aria-pressed="<?=($flight_list_details['active'] == 1)?'true' : 'false' ?>" data-fsid="<?=$flight_list_details['fsid']?>" data-status="<?=$flight_list_details['active']?>" autocomplete="off">
   					<div class="handle"></div>
					</button>
					<!-- <input <?=$chk?> data-toggle="toggle" class="status_change" data-style="ios" type="checkbox" data-status=<?=$flight_list_details['active']?> /> --></td>
					<td><a class="act" data-adult_basefare="<?=$flight_list_details['adult_basefare']?>" data-adult_tax="<?=$flight_list_details['adult_tax']?>" data-child_basefare="<?=$flight_list_details['child_basefare']?>" data-child_tax="<?=$flight_list_details['child_tax']?>" data-infant_basefare="<?=$flight_list_details['infant_basefare']?>" data-infant_tax="<?=$flight_list_details['infant_tax']?>" >Fare Details</a> <br> <a class="flight_details" data-fsid="<?=$flight_list_details['fsid']?>" href="javascript::void(0)" >Flight Details</a><br>
						<a href="<?=base_url();?>index.php/flight/update_flight_details/<?=$flight_list_details['fsid']?>" >Update Flight Details</a>
					</td>
					
				</tr>
				<?php 
				} }else{ ?>
				<tr>
					<td colspan="12"> <strong>No flights available.</strong></td>
				</tr>
				
				<?php }?>
				</tbody>
			</table>
			
			<?php 
			?>
		  </div>
		</div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL WRAP END -->
</div>
<!-- HTML END -->

<div id="action" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title dyn_title" id="dynamic_text"></h4>
      </div>
      <div class="modal-body action_details">
      	<div class="table-responsive fare_data dyn_data">          
		 
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script>
/*   $(function() {
    $('#toggle').bootstrapToggle({
      on: 'Enabled',
      off: 'Disabled'
    });
  }) */
</script>

<script type="text/javascript">
$(document).on('click', '.dyna_status', function(){
	var thisss = $(this);
	var fsid   = $(this).data('fsid'); 
	var status = $(this).attr('data-status'); 
	if(parseInt(status) === parseInt(1)){
		status = 0;
	} else {
		status = 1;
	}    
    $.ajax({
        url: "<?=base_url();?>index.php/flight/get_flight_status/"+fsid+"/"+status, 
        async:false, 
        success: function(result){
            thisss.attr('data-status',status);
    	}
    });
});

 $(document).ready(function(){
 	
	 
    $(".act").click(function(){
    var adult_basefare = $(this).data('adult_basefare'); 
    var adult_tax = $(this).data('adult_tax');
    // var child_basefare = $(this).data('child_basefare');
    // var child_tax = $(this).data('child_tax');
    var infant_basefare = $(this).data('infant_basefare');
    var infant_tax = $(this).data('infant_tax');

    var total_adult_fare = adult_basefare + adult_tax;
    // var total_child_fare = child_basefare + child_tax;
    var total_infant_fare = infant_basefare + infant_tax;
    	
    
    var str = '<table class="table table-bordered"><thead><tr><th></th><th>Adult</th><th>Infant</th></tr></thead><tbody><tr><td><strong>Base Fare</strong></td><td>'+adult_basefare+'</td><td>'+infant_basefare+'</td></tr><tr><td><strong>Tax</strong></td><td>'+adult_tax+'</td><td>'+infant_tax+'</td></tr><tr><td><strong>Total Fare</strong></td><td>'+total_adult_fare+'</td><td>'+total_infant_fare+'</td></tr></tbody></table>';
    $('.fare_data').html(str);
    $('#dynamic_text').text('Fare Details');
	$("#action").modal('show'); 
    });
    
    $(".flight_details").click(function(){
        var fsid = $(this).data('fsid'); 
        $.ajax({url: "<?= base_url();?>index.php/flight/get_flight_details/"+fsid, success: function(result){
        	
           var res = JSON.parse(result);
         //  alert(result)
           if(res.length>0){
        	 
        	   var flight_data = '';
        	   var flight_data = '<table class="table table-bordered"><thead><tr><th>#SL</th><th>Origin</th><th>Destination</th><th>Deparature From Date</th><th>Deparature To Date</th><th>Flight Num</th><th>Carrier code</th><th>Airline Name</th><th>Class</th><th>Trip Type</th></tr></thead><tbody>';
				for(var i=0;i<res.length;i++){
					flight_data +='<tr><td>'+parseInt(i+1)+'</td><td>'+res[i]['origin']+'</td><td>'+res[i]['destination']+'</td><td>'+res[i]['departure_date_from']+' ' + res[i]['departure_time']+'</td><td>'+res[i]['departure_date_to']+' ' + res[i]['arrival_time']+'</td><td>'+res[i]['flight_num']+'</td><td>'+res[i]['carrier_code']+'</td><td>'+res[i]['airline_name']+'</td><td>'+res[i]['class_type']+'</td><td>'+((res[i]['trip_type']==0)?"Onward":"Return")+'</td></tr>';
				}	
				flight_data +='</tbody></table>';
           }
           $('.fare_data').html(flight_data);
           $('#dynamic_text').text('Flight Details'); 
           $("#action").modal('show'); 
        
        }});
       // var str='<table class="table table-bordered"><thead><tr><th></th><th>Origin</th><th>Destination</th><th>Flight Num</th><th>Carrier code</th><th>Airline Name</th></tr></thead><tbody>";
        // </tbody></table>';
       // $('.dyn_data').html(str);
    	//$("#action").modal('show'); 
    	//$(".dyn_title").text("Flight Details");
        });
 
    $('#tab_flight_list').DataTable({
		  "searching": true,
		  "paging" : false
	});
}); 
</script>
