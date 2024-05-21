<?php
	// echo "<pre>";
	// print_r($update_flight_details);
 //  echo "</pre>";
 //  echo "<pre>";
 //  print_r($flight_details);  
 //  echo "</pre>";
 //  exit;
	$count = count($update_flight_details);
 //  echo $count;
	// exit;
?>
<?php
   $update_details = $flight_details[0];
   $dep_from_date = $update_details['dep_from_date'];
   $dep_to_date = $update_details['dep_to_date'];
	$begin = new DateTime($dep_from_date);
	$end = new DateTime($dep_to_date);
	$end = $end->modify( '+1 day' );

	$interval = new DateInterval('P1D');
	$daterange = new DatePeriod($begin, $interval ,$end);
?>
<style type="text/css">
.flt_det .table-bordered>thead>tr>th, .flt_det .table-bordered>thead>tr>td {
    border-bottom-width: 2px;
    vertical-align: middle;
    text-align: center;
}
.list label {line-height: 28px;}
.max_wdt { max-width: 120px; }
.well-sm.sav_btn {
    padding: 9px;
    border-radius: 3px;
    text-align: center;
    background: none;
    border: none;
    box-shadow: none;}
</style>
<section class="content" style="opacity: 1;">
   <!-- UTILITY NAV -->    
   <div class="container-fluid utility-nav clearfix">
      <!-- ROW -->            <!-- /ROW -->    
   </div>
   <!-- Info boxes -->          
   <div class="">
      <!-- HTML BEGIN -->
      <div id="general_user" class="bodyContent clearfix">
         <div class="panel panel-default clearfix">
            <!-- PANEL WRAP START -->
            <div class="panel-heading">
               <!-- PANEL HEAD START -->Flight Details
            </div>
            <!-- PANEL HEAD START -->
            <div class="panel-body">
            <!--    <h4>Search Panel</h4> -->
            <!--    <hr> -->
               <form method="POST" autocomplete="off" action="<?php echo base_url();?>index.php/flight/save_update_flight_details">
                  <div class="clearfix form-group">
                     <div class="col-xs-3 list"><label>Origin:<?php echo $update_details['origin'];?></label></div>
                     <div class="col-xs-3 list"><label>Destination:<?php echo $update_details['destination'];?></label></div>
                     <div class="col-xs-3 list"><label>Airline_name:<?php echo $update_details['airline_name'];?></label></div>
                     <div class="col-xs-3 list"><label>Flight_num:<?php echo $update_details['flight_num'];?></label></div>
                     <div class="col-xs-3 list"><label>Carrier_code:<?php echo $update_details['carrier_code'];?></label></div>
                     <div class="col-xs-3 list"><label>Class_type:<?php echo $update_details['class_type'];?></label></div>
                     <div class="col-xs-3 list"><label>No_of_stops:<?php echo $update_details['no_of_stops'];?></label></div>
                  </div>
                  <div class="col-xs-12 updte" onclick="$('#details').toggle();"><label class="btn btn-primary mb10">Update Details <i class="fa fa-angle-down" aria-hidden="true"></i></label></div>
                  <div id="details" style="display: none" >
                     <div class="col-xs-4 list"><label class="col-xs-4">Available Seat: </label><input type="text" class="form-control" value="<?php echo $update_details['seats'];?>" id="update_seat" placeholder="Update Seat"></div>
                      <div class="col-xs-4 list"><label class="col-xs-4">PNR: </label><input type="text" value="<?php echo $update_details['pnr'];?>" class="form-control" id="update_pnr" placeholder="Update PNR"></div>
                      <div class="col-xs-6 list"><label class="col-xs-12 nopad fnt16">Adult:</label>
                      <div class="list_row">
                      <div class="col-xs-6 list">
                      <label class="col-xs-4">Basefare: </label><input type="text" class="form-control" value="<?php echo $update_details['adult_basefare'];?>" id="update_adult_basefare" placeholder="Update Adult Basefare"></div>
                      <div class="col-xs-6 list"><label class="col-xs-4">Adult Tax: </label>
                      <input type="text" class="form-control" value="<?php echo $update_details['adult_tax'];?>" id="update_adult_tax" placeholder="Update Adult Tax"></div>
                      </div>
                      </div>
                      <div class="col-xs-6 list"><label class="col-xs-12 nopad fnt16">Infant:</label>
                      <div class="list_row">
                      <div class="col-xs-6 list">
                      <label class="col-xs-4">Basefare:</label><input type="text" class="form-control" value="<?php echo $update_details['infant_basefare'];?>" id="update_infant_basefare" placeholder="Update Infant Basefare"></div>
                      <div class="col-xs-6 list"><label class="col-xs-4">Infant Tax:</label><input class="form-control" type="text" value="<?php echo $update_details['infant_tax'];?>" id="update_infant_tax" placeholder="Update Infant Tax"></div> 
                      </div>
                      </div>
                      <div class="col-xs-12"><input class="btn btn-primary mb10" type="button" value="Update Details" onclick="func1(<?php echo $update_details['fsid']; ?>)"></div>
                  </div>
            <input type="hidden" class="form-control" name="fsid1" value="<?php echo $fsid; ?>">
            <div class="table-responsive col-md-12 flt_det">
                <table class="table table-hover table-striped table-bordered table-condensed">
                  <thead>
                     
                     <tr>
                        <th rowspan="2">SL#</th>
                        <th rowspan="2">Date</th>
                        <th rowspan="2">PNR</th>
                        <th rowspan="2">Available Seats</th>
                        <th colspan="2">Flight Time</th>
                        <th colspan="2">Adult</th>
                        <th colspan="2">Infant</th>
                        <th rowspan="2">Status</th>
                        <th rowspan="2"> Action</th>
                      </tr>
                     <tr>
                        <th>Dep. Time</th>
                        <th>Arr. Time</th>
                        <th>Base fare</th>
                        <th>Tax</th>
                        <th>Base fare</th>
                        <th>Tax</th>
                      </tr>
                  </thead>
                  <tbody>
                  	<?php
				for($i=0; $i<$count; $i++)
				{
					?>
                     <tr>
                        <td>
                        <?php echo $i+1; ?>
                        <input type="hidden" class="form-control" name="fsid[]" value="<?php echo $update_flight_details[$i]['fsid']; ?>">
                        </td>

                        <td><?php echo $update_flight_details[$i]['avail_date']; ?>
                        <input type="hidden" class="form-control" name="date[]" value="<?php echo $update_flight_details[$i]['avail_date']; ?>">
                        </td>

                        <td>
                        <div class=" form-group">
      					        <input type="text" class="form-control" name="pnr[]" id="pnr_<?=$i?>" value="<?php 
                        if(isset($update_flight_details[$i]['pnr'])){
                          echo $update_flight_details[$i]['pnr'];
                        }
                        else{
                          echo $update_details['pnr'];   
                        }
                        ?>" maxlength = "10">
      					        </div>
					              </td>

                        <td>
                        <div class=" form-group">	
				                <input type="text" class="form-control numeric" name="seat[]" id="seat_<?=$i?>" value="<?php 
                        if(isset($update_flight_details[$i]['avail_seat'])){
                           echo $update_flight_details[$i]['avail_seat'];
                        }
                        else{
                          echo $update_details['seats'];
                        }
                        ?>" maxlength = "5">
				                </div>
				                </td>
                

                        <td>
                        <div class=" form-group">
                        <input type="text" class="form-control max_wdt " name="dep_time[]" id="dep_time_<?=$i?>" value="<?php 
                        if(isset($update_flight_details[$i]['dep_time'])){
                        //  echo $update_flight_details[$i]['dep_time'];
                          echo date('H:i',strtotime($update_flight_details[$i]['dep_time']));   

                        }
                        else{
                          echo date('H:i',strtotime($update_details['departure_time']));   
                        }
                        ?>" maxlength = "6">
                        </div>
                        </td>

                        <td>
                        <div class=" form-group">
                        <input type="text" class="form-control max_wdt " name="arr_time[]" id="arr_time_<?=$i?>"  value="<?php 
                        if(isset($update_flight_details[$i]['arr_time'])){
                        //  echo $update_flight_details[$i]['arr_time'];
                          echo date('H:i',strtotime($update_flight_details[$i]['arr_time']));   

                        }
                        else{
                          //echo $update_details['arrival_time']; 
                          echo date('H:i',strtotime($update_details['arrival_time']));   

                        }
                        ?>" maxlength = "6"> 
                        </div>
                        </td>


                        <td>
                        <div class=" form-group">
				                <input type="text" class="form-control max_wdt numeric" name="adult_base[]"  id="adult_base_<?=$i?>" value="<?php 
                        if(isset($update_flight_details[$i]['adult_base'])){
                          echo $update_flight_details[$i]['adult_base'];
                        }
                        else{
                          echo $update_details['adult_basefare'];   
                        }
                        ?>" maxlength = "6">
				                </div>
				                </td>

                        <td>
                        <div class=" form-group">
				                <input type="text" class="form-control max_wdt numeric" name="adult_tax[]"  id="adult_tax_<?=$i?>" value="<?php 
                        if(isset($update_flight_details[$i]['adult_tax'])){
                          echo $update_flight_details[$i]['adult_tax'];
                        }
                        else{
                          echo $update_details['adult_tax']; 
                        }
                        ?>" maxlength = "6"> 
				                </div>
				                </td>

                        <td>
                        <div class=" form-group">
				                <input type="text" class="form-control max_wdt numeric" name="child_base[]"  id="child_base_<?=$i?>" value="<?php 
                        if(isset($update_flight_details[$i]['child_base'])){
                          echo $update_flight_details[$i]['child_base'];
                        }
                        else{
                          echo $update_details['infant_basefare'];   
                        }
                        ?>" maxlength = "6">
				                </div>
				                </td>

                        <td>
                        <div class=" form-group">
				                <input type="text" class="form-control max_wdt numeric" name="child_tax[]" id="child_tax_<?=$i?>"  value="<?php 
                        if(isset($update_flight_details[$i]['child_tax'])){
                          echo $update_flight_details[$i]['child_tax'];
                        }
                        else{
                          echo $update_details['infant_tax']; 
                        }
                        ?>" maxlength = "6">
				                </div>
				                </td>
                        <td>
                        <button type="button" class="btn btn-sm btn-toggle stus dyna_status <?=($update_flight_details[$i]['status'] == 1)?'active' : '' ?> act-<?=$update_flight_details[$i]['origin']?>" data-toggle="button" aria-pressed="<?=($update_flight_details[$i]['status'] == 1)?'true' : 'false' ?>" data-origin="<?=$update_flight_details[$i]['origin']?>" data-status="<?=$update_flight_details[$i]['status']?>" autocomplete="off">
                        <div class="handle"></div>
                        </button>
                        </td>

					    <td>

               <button type="button" class="btn btn-primary" onclick="seat_details(<?php echo $update_flight_details[$i]['origin']; ?>,<?=$i?>)">Seat Details</button>


              <button type="button" class="btn btn-primary" onclick="update_flight_data_per_date(<?php echo $update_flight_details[$i]['origin']; ?>,<?=$i?>)">Update</button>

              <button type="button" class="btn btn-primary" onclick="func(<?php echo $update_flight_details[$i]['origin']; ?>)">Delete</button></td>                  
                  </tr>
                  <?php
              	}
                  ?>
                  </tbody>
               </table>
            </div>
         </div>
         <!-- PANEL WRAP END -->
      </div>
      <div class="col-sm-12 well well-sm sav_btn"><button type="submit" name="submit" value="submit" class="btn btn-primary">Save</button> </div>
  </form>
</section>
<script type="text/javascript">


$(document).on('click', '.dyna_status', function(){
  var thisss = $(this);
  var origin   = $(this).data('origin'); 
  var status = $(this).attr('data-status'); 
  if(parseInt(status) === parseInt(1)){
    status = 0;
  } else {
    status = 1;
  }    
    $.ajax({
        url: "<?=base_url();?>index.php/flight/update_per_date_flight_status/"+origin+"/"+status, 
        async:false, 
        success: function(result){
            thisss.attr('data-status',status);
      }
    });
});



	function func(id)
	{
		var info = 'id=' + id;
		$.ajax(
			{
  			url: "<?php echo base_url();?>index.php/flight/delete_update_flight_details",
  			type: 'POST',
  			data: info,
  			success: function(result) {
  				if(result)
  				{
  					window.location.reload();
  				}
			}
		});
	}

  function update_flight_data_per_date(origin,id)
  {
   // alert(origin+"===="+id);
   var origin = origin;
   var pnr = $('#pnr_'+id).val();
   var  avail_seat = $('#seat_'+id).val();
   var dep_time = $('#dep_time_'+id).val();
   var arr_time = $('#arr_time_'+id).val();
   var adult_base = $('#adult_base_'+id).val();
   var adult_tax = $('#adult_tax_'+id).val();
   var child_base = $('#child_base_'+id).val();
   var child_tax = $('#child_tax_'+id).val();
  /*  alert(pnr);
    alert(seat);
    alert(dep_time);
    alert(arr_time);
    alert(adult_base);
    alert(adult_tax);
    alert(child_base);
    alert(child_tax);*/
   
    $.ajax(
      {
        url: "<?php echo base_url();?>index.php/flight/update_flight_data_per_date",
        type: 'POST',
        data: { origin: origin,pnr: pnr,  avail_seat:  avail_seat, dep_time: dep_time, arr_time: arr_time, adult_base: adult_base, adult_tax: adult_tax, child_base: child_base, child_tax: child_tax },
        success: function(result) {
          toastr.info('Please Wait!!!');
          if(result)
          {
           toastr.info('Updated Successfully!!!');
          //  window.location.reload();
          }
      }
    });
  }



  function func1(id)
  {
    
    var seat = $('#update_seat').val();
    var pnr = $('#update_pnr').val();
    var abasefare = $('#update_adult_basefare').val();
    var atax = $('#update_adult_tax').val();
    var ibasefare = $('#update_infant_basefare').val();
    var itax = $('#update_infant_tax').val();
    // if(seat != '')
    // {
      //alert(data);
      $.ajax(
        {
          url: "<?php echo base_url();?>index.php/flight/update_seat_details",
          type: 'POST',
          data: 'id='+id + '&seat='+seat + '&pnr='+pnr + '&abasefare='+abasefare + '&atax='+atax + '&ibasefare='+ibasefare + '&itax='+itax,
          success: function(result) {
            if(result)
            {
              window.location.reload();
            }
        }
      });

    // }
    // else
    // {
    //   alert("Enter seat to update");
    // }
  }
  // $(document).ready(function(){
  //   $("#details").hide();
  //   $("#t_details").click(function(){
  //     $("#details").show();  
  //   })
    
  // });

//   $(document).ready(function () {
//     $("#preview").toggle(function() {
//         $("#div1").hide();
//         $("#div2").show();
//     }, function() {
//         $("#div1").show(); 
//         $("#div2").hide();
//     });
// });


</script>