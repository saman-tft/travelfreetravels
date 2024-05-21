
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
//Dinesh 7-Feb-2018
  function flight_passenger_email($origin)
{

  return '<a class="btn btn-sm btn-primary send_email_voucher fa fa-envelope-o" data-origin="'.$origin.'">Email Passenger Details</a>';
}
 /*function flight_passenger_email($origin,$i)
{

  return '<a class="btn btn-sm btn-primary send_email_voucher fa fa-envelope-o" data-origin="'.$origin.'" data-list_id="'.$i.'">Email Passenger Details</a>';
}*/
//end
?>
<style type="text/css">
.flt_det .table-bordered>thead>tr>th, .flt_det .table-bordered>thead>tr>td {
    border-bottom-width: 2px;
    vertical-align: middle;
    text-align: center;
}
.list label {line-height: 28px;}
.max_wdt { max-width: 120px;padding: 6px; }
.well-sm.sav_btn {
    padding: 9px;
    border-radius: 3px;
    text-align: center;
    background: none;
    border: none;
    box-shadow: none;
}
.err_msg{
    color: #f00;
    font-size: 14px; text-align: center;  
}
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
                  <div class="col-xs-12 updte"><label onclick="$('#details').toggle();" class="btn btn-default mb10">Update Details <i class="fa fa-angle-down" aria-hidden="true"></i></label></div>
                  <div id="details" style="display: none">
                     <div class="org_row">
                     <div class="col-xs-3 list">
                     <label class="col-xs-12 nopad fnt16">&nbsp;</label>
                     <label class="col-xs-6">Available Seat: </label>
                    <div class="col-xs-6">
                     <input type="text" class="form-control" value="<?php echo $update_details['seats'];?>" id="update_seat" placeholder="Update Seat"></div>
                    </div>
                  <!--     <div class="col-xs-4 list"><label class="col-xs-4">PNR: </label><input type="text" value="<?php echo $update_details['pnr'];?>" class="form-control" id="update_pnr" placeholder="Update PNR"></div> -->                  
                     <div class="col-xs-8">
                     <div class="org_row">
                      <div class="col-xs-6 list"><label class="col-xs-12 nopad fnt16">Adult:</label>
                      <div class="list_row">
                      <div class="col-xs-7 list">
                      <label class="col-xs-7 nopad">Purchase Price: </label>
                      <div class="col-xs-5 nopad">
                      <input type="text" class="form-control" value="<?php echo $update_details['adult_basefare'];?>" id="update_adult_basefare" placeholder="Update Adult Basefare"></div>
                      </div>
                      <div class="col-xs-5 list"><label class="col-xs-6 nopad">Margin: </label>
                      <div class="col-xs-6 nopad">
                      <input type="text" class="form-control" value="<?php echo $update_details['adult_tax'];?>" id="update_adult_tax" placeholder="Update Adult Tax"></div>
                      </div>
                      </div>
                      </div>
                      <div class="col-xs-6 list"><label class="col-xs-12 nopad fnt16">Infant:</label>
                      <div class="list_row">
                      <div class="col-xs-7 list">
                      <label class="col-xs-7 nopad">Purchase Price:</label>
                      <div class="col-xs-5 nopad">
                      <input type="text" class="form-control" value="<?php echo $update_details['infant_basefare'];?>" id="update_infant_basefare" placeholder="Update Infant Basefare"></div>
                      </div>
                      <div class="col-xs-5 list"><label class="col-xs-6 nopad">Margin:</label>
                      <div class="col-xs-6 nopad"><input class="form-control" type="text" value="<?php echo $update_details['infant_tax'];?>" id="update_infant_tax" placeholder="Update Infant Tax"></div> 
                      </div>
                      </div>
                      </div>
                      </div>
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
                        <th rowspan="2">Total</br>Seats</th>
                        <th rowspan="2">Booked</br>Seats</th>
                        <th rowspan="2">Available</br>Seats</th>
                        <th colspan="2">Flight Time</th>
                        <th colspan="3">Adult</th>
                        <th colspan="3">Infant</th>
                        <th colspan="2">Status</th>
                        <th rowspan="2"> Action</th>
                      </tr>
                     <tr>
                        <th>Dep. Time</th>
                        <th>Arr. Time</th>
                        <th>Purchase</br>Price</th>
                        <th>Margin</th>
                        <th>Total</th>
                        <th>Purchase</br>Price</th>
                        <th>Margin</th>
                        <th>Total</th>
                        <th>B2C</th>
                        <th>B2B</th>
                      </tr>
                  </thead>
                  <tbody>
                  	<?php
                   // debug($update_flight_details); exit;
				for($i=0; $i<$count; $i++)
				{
          $total_adult_price = 0;
          $total_child_price = 0;
					?>
                     <tr>
                        <td rowspan="2">
                        <?php echo $i+1; ?>
                        <input type="hidden" class="form-control" name="fsid[]" value="<?php echo $update_flight_details[$i]['fsid']; ?>">
                        </td>

                        <td><?php 
                        $udate = date_create($update_flight_details[$i]['avail_date']);
                        echo date_format($udate,"d-M-Y"); ?>
                        <input type="hidden" class="form-control" name="date[]" value="<?php echo $update_flight_details[$i]['avail_date']; ?>">
                        </td>

                        <td>
                        <div class=" form-group">
      					        <input type="text" class="form-control wdt80 text-uppercase" name="pnr[]" id="pnr_<?=$i?>" value="<?php 
                        if(isset($update_flight_details[$i]['pnr'])){
                          echo $update_flight_details[$i]['pnr'];
                        }
                       
                        ?>" maxlength = "10">
      					        </div>
					              </td>

                        <td>
                        <div class=" form-group">	
				                <input type="text" class="wdt60 form-control numeric" name="seat[]" id="seat_<?=$i?>" value="<?php 
                        if(isset($update_flight_details[$i]['avail_seat'])){
                           echo $update_flight_details[$i]['avail_seat'];
                           $tot_set = $update_flight_details[$i]['avail_seat'];
                        }
                        else{
                          echo $update_details['seats'];
                           $tot_set = $update_details['seats'];

                        }
                        ?>" maxlength = "5">
				                </div>
				                </td>
                
                         <td>
                        <div class=" form-group" style="color:#3c763d;text-align:center;"> 
                        <?=$update_flight_details[$i]['booked_seat'];?>
                      
                        </div>
                        </td>

                          <td>
                        <div class=" form-group" style="color:#3c763d;text-align:center;"> 
                        <?=($tot_set-$update_flight_details[$i]['booked_seat']);?>
                      
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
				                <input type="text" class="form-control max_wdt numeric adult_base" name="adult_base[]"  id="adult_base_<?=$i?>" value="<?php 
                        if(isset($update_flight_details[$i]['adult_base'])){
                          echo $update_flight_details[$i]['adult_base'];
                          $total_adult_price +=$update_flight_details[$i]['adult_base'];
                        }
                        else{
                          echo $update_details['adult_basefare'];   
                          $total_adult_price +=$update_details['adult_basefare'];

                        }
                        ?>" maxlength = "6">
				                </div>
				                </td>

                        <td>
                        <div class=" form-group">
				                <input type="text" class="exct_wdt form-control max_wdt numeric adult_tax" name="adult_tax[]"  id="adult_tax_<?=$i?>" value="<?php 
                        if(isset($update_flight_details[$i]['adult_tax'])){
                          echo $update_flight_details[$i]['adult_tax'];
                          $total_adult_price +=$update_flight_details[$i]['adult_tax'];

                        }
                        else{
                          echo $update_details['adult_tax']; 
                          $total_adult_price +=$update_details['adult_tax'];

                        }
                        ?>" maxlength = "6"> 
				                </div>
				                </td>

                       <td>
                       <div class=" form-group">
                       
                       <span id="adult_total_<?=$i?>" class="exct_wdt adult_total" ><?=$total_adult_price?></span>
                                              
                       </div>
                       </td>



                        <td>
                        <div class=" form-group">
				                <input type="text" class="form-control max_wdt numeric infant_base" name="child_base[]"  id="child_base_<?=$i?>" value="<?php 
                        if(isset($update_flight_details[$i]['child_base'])){
                          echo $update_flight_details[$i]['child_base'];
                          $total_child_price +=$update_flight_details[$i]['child_base'];

                        }
                        else{
                          echo $update_details['infant_basefare'];   
                          $total_child_price +=$update_details['infant_basefare'];

                        }
                        ?>" maxlength = "6">
				                </div>
				                </td>

                        <td>
                        <div class=" form-group">
				                <input type="text" class="form-control exct_wdt max_wdt numeric infant_tax" name="child_tax[]" id="child_tax_<?=$i?>"  value="<?php 
                        if(isset($update_flight_details[$i]['child_tax'])){
                          echo $update_flight_details[$i]['child_tax'];
                          $total_child_price +=$update_flight_details[$i]['child_tax'];

                        }
                        else{
                          echo $update_details['infant_tax']; 
                          $total_child_price +=$update_details['infant_tax'];

                        }
                        ?>" maxlength = "6">
				                </div>
				                </td>

                           <td>
                       <div class=" form-group">
                       
                       <span id="infant_total_<?=$i?>" class="exct_wdt infant_total"><?=$total_child_price?></span>
                                              
                       </div>
                       </td>



                        <td>
                        <button type="button" class="btn btn-sm btn-toggle stus dyna_status <?=($update_flight_details[$i]['b2c_status'] == 1)?'active' : '' ?> act-<?=$update_flight_details[$i]['origin']?>" data-toggle="button" aria-pressed="<?=($update_flight_details[$i]['b2c_status'] == 1)?'true' : 'false' ?>" data-origin="<?=$update_flight_details[$i]['origin']?>" data-status_type="b2c" data-status="<?=$update_flight_details[$i]['b2c_status']?>" autocomplete="off">
                        <div class="handle"></div>
                        </button>
                        </td>
                         <td>
                        <button type="button" class="btn btn-sm btn-toggle stus dyna_status <?=($update_flight_details[$i]['b2b_status'] == 1)?'active' : '' ?> act-<?=$update_flight_details[$i]['origin']?>" data-toggle="button" aria-pressed="<?=($update_flight_details[$i]['b2b_status'] == 1)?'true' : 'false' ?>" data-origin="<?=$update_flight_details[$i]['origin']?>"  data-status_type="b2b" data-status="<?=$update_flight_details[$i]['b2b_status']?>" autocomplete="off">
                        <div class="handle"></div>
                        </button>
                        </td>

					    <td>

               <button type="button" class="btn btn-primary ad_icn" id="seat_btn_<?=$i?>" onclick="seat_details(<?php echo $update_flight_details[$i]['origin']; ?>,<?=$i?>)">Seat Details</button>


              <button type="button" class="btn btn-primary" onclick="update_flight_data_per_date(<?php echo $update_flight_details[$i]['origin']; ?>,<?=$i?>,<?=$update_flight_details[$i]['booked_seat']?>)">Update</button>
              
              <?php 
                  if($update_flight_details[$i]['booked_seat']==0){
                    ?>
                   <button type="button" class="btn btn-primary" onclick="func(<?php echo $update_flight_details[$i]['origin']; ?>)">Delete</button> 
              <?php
                  }
              ?>
              <!-- Dinesh  Export button Starts -->
                  <a href = "<?php echo base_url(); ?>index.php/flight/export_passenger_details/excel?fl_details_id=<?= $update_flight_details[$i]['origin'];?>" class="btn btn-primary">Export to Excel</a>
                <!-- #####  Export button End -->
                <?php $email_btn = flight_passenger_email($update_flight_details[$i]['origin'],$i);
                  echo $email_btn;
                ?>
              </td>                  
                  </tr>

                   <tr>
                      <td colspan="15"  class="st_detail" id="seat_det_<?=$i?>">
                        <table class="table table-hover table-striped table-bordered table-condensed mb0">
                        <thead>
                            <tr>                        
                              <th>Seat No.</th>
                              <th>Title</th>
                              <th>First Name</th>
                              <th>Last Name</th>
                              <th>Pax Type</th>
                              <th>Application Reference</th>
                              <th>Price</th>
                              <th>Travel Agent</th>
                              <th>DOB</th>
                              <th>Status</th>
                              <th>&nbsp;&nbsp;&nbsp;Mailing Status &nbsp;&nbsp;&nbsp;<br>
                                <div class="checkbox mail_st col-xs-6 nopad">
                                  <label class="padl10"><input id="checkAll" type="checkbox" value="" class="parentCheckBox">All</label>
                                </div>
                              <div class="col-xs-6 nopad"><button type="button" class="btn btn-primary st_book ml_updt">Update</button>
                              </div>
                              </th>
                              <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>                        
                              <td></td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td><a class="btn btn-primary st_book" href="">Book</a></td>
                            </tr>
                        </tbody>
                        </table>
                      </td>
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
  <!--     <div class="col-sm-12 well well-sm sav_btn"><button type="submit" name="submit" value="submit" class="btn btn-primary">Save</button> </div> -->
  </form>
</section>
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

<!-- Mail - Voucher  starts-->
  <div class="modal fade" id="mail_voucher_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-envelope-o"></i>
      Email To Airline
    </h4>
      </div>
      <div class="modal-body">
          <div id="email_voucher_parameters">
                  <!--<input type="hidden" id="mail_voucher_app_reference" class="hiddenIP">
          <input type="hidden" id="mail_voucher_booking_source" class="hiddenIP">
          <input type="hidden" id="mail_voucher_booking_status" class="hiddenIP">-->
          <div class="row">
          <form action="<?php echo base_url();?>index.php/flight/flight_passenger_details" method="post" enctype="multipart/form-data" id="fileinfo">
          <div class="form-group">
            <label for="email">Email address:</label>
            <input type="email" name="email" id="voucher_recipient_email" class="form-control " value="" required="required" placeholder="Enter Email">
            <!-- <input type="email" id="voucher_recipient_email" class="form-control " value="" required="required" placeholder="Enter Email"> -->
          </div>

          <div class="form-group">
            <label for="email">CC:</label>
            <input type="email" name="cc_email" id="cc_email" class="form-control " value="" placeholder="Enter Email">
            <!-- <input type="email" id="voucher_recipient_email" class="form-control " value="" required="required" placeholder="Enter Email"> -->
          </div>


          <div class="form-group">
            <label for="subject">Subject:</label>
            <textarea class="form-control" name="email_subject" id="email_subject" required="required"></textarea>
          </div>
          <div class="form-group">
            <label for="file">Attach:</label>
            <input type="file" name="file" class="form-control" id="file">
          </div>
      <!--     <p>Copy of E-Ticket will be sent to the above Email Id</p> -->
            <div class="col-md-4">
              <!-- <input type="submit" value="SEND >" class="btn btn-success" id="send_mail_btn"> -->
              <input type="button" value="SEND >" class="btn btn-success" id="send_mail_btn">
            </div>
            <div class="col-md-8">
              <strong id="mail_voucher_error_message" class="text-danger"></strong>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Mail - Voucher  ends-->


<script type="text/javascript">

jQuery(document).ready(function($) {

  $(document).on('change, keyup', '.adult_base, .adult_tax', function(){
    var thisss = $(this);
    var thisssRow = $(this).closest('tr');
    
    var basefare = thisssRow.find('.adult_base').val();
    var tax = thisssRow.find('.adult_tax').val();
    var amount = calculateTotalFare(basefare, tax);

    thisssRow.find('.adult_total').html(parseInt(amount));
  });

  $(document).on('change, keyup', '.infant_base, .infant_tax', function(){
    var thisss = $(this);
    var thisssRow = $(this).closest('tr');
    
    var basefare = thisssRow.find('.infant_base').val();
    var tax = thisssRow.find('.infant_tax').val();
    var amount = calculateTotalFare(basefare, tax);

    thisssRow.find('.infant_total').html(parseInt(amount));
  });

  function calculateTotalFare(basefare, tax){
    return parseInt(basefare)+parseInt(tax);
  }


});



$(document).on('click', '.dyna_status', function(){
  var thisss = $(this);
  var origin   = $(this).data('origin'); 
  var status = $(this).attr('data-status'); 
  var status_type = $(this).attr('data-status_type'); 
 
  if(parseInt(status) == parseInt(1)){
    status = 0;
  } else {
    status = 1;
  }    
  
    $.ajax({
        url: "<?=base_url();?>index.php/flight/update_per_date_flight_status/"+origin+"/"+status+"/"+status_type, 
        async:false, 
        success: function(result){
          var res = JSON.parse(result);
          if(res.active_state==1){
            thisss.attr('data-status',status);
          }else{
            if(thisss.hasClass("active")){
              thisss.removeClass("active");
            }
            alert(res.msg);
            return false;
          }            
            
      }
    });
});

  function seat_details(origin,id)
  {
     $("#seat_det_"+id).toggle();
     $('#seat_btn_'+id).toggleClass('menutoggle');
     //$(this).toggleClass('menutoggle');

    var date_id = origin;
    $.ajax(
      {
        url: "<?php echo base_url();?>index.php/flight/seat_details",
        type: 'POST',
        data: {id:date_id},
        success: function(result) {
          /*  alert(result);
return false;*/
        var check_cnt = $("#seat_det_"+id).find('.childCheckBox').length;
       /* console.log(check_cnt);
        //alert(check_cnt);*/

          if (check_cnt > 0) {
            $("#seat_det_"+id).find('.parentCheckBox').show();
          } else {
            $("#seat_det_"+id).find('.parentCheckBox').hide();
          }

            $("#seat_det_"+id+" tbody").html(result);
         //   return false;
          if(result)
          {
           // window.location.reload();
          }
      }
    });
  }


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
  function update_flight_data_per_date(origin,id,booked_seat)
  {
   // alert(origin+"===="+id);
   var origin = origin;
   var pnr = $('#pnr_'+id).val();
   var avail_seat = $('#seat_'+id).val();
   var dep_time = $('#dep_time_'+id).val();
   var arr_time = $('#arr_time_'+id).val();
   var adult_base = $('#adult_base_'+id).val();
   var adult_tax = $('#adult_tax_'+id).val();
   var child_base = $('#child_base_'+id).val();
   var child_tax = $('#child_tax_'+id).val();

   if(avail_seat<booked_seat){
    alert('Total seat should be greater than or equal to booked seat.');
    return false;

   }
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


//Dinesh
$(document).on('click', '.pass_update_btn', function(){
  var first_name = $('.first_name').val(); 
  var title = $('.user_title').val(); 
  var last_name = $('.last_name').val(); 
  var user_mailing_status = $('.user_mailing_status').val(); 

  if(first_name==''){
    alert("Please enter first name");
    return false;
  }
  if(last_name==''){
    alert("Please enter last name");
    return false;
  }
  var pax_id = $('.pax_id').val(); 
  $.post("<?= base_url();?>index.php/flight/update_passenger_details/"+pax_id, {first_name: first_name, title:title,last_name:last_name,pax_id:pax_id,mailing_status:user_mailing_status}, function(result){
           // alert(result);
          toastr.info('Please Wait!!!');
          if(result)
          {
           toastr.info('Updated Successfully!!!');
            $("#action").modal('hide');
            window.location.reload();
          }
        });
  
});

$(document).on('click', '.update_passenger_details', function(){
  var title = $(this).data('title'); 
  var first_name = $(this).data('first_name'); 
  var last_name = $(this).data('last_name'); 
  var pax_id = $(this).data('pax_id'); 
  var passenger_type = $(this).data('passenger_type'); 
  var mailing_status = $(this).data('mailing_status'); 

  var selectone = '';
  var selecttwo = '';
  var selectthree = '';
  var selectfore = '';
  if(title=='Mr'){
    selectone = 'selected'; 
  }else if(title=='Ms'){
    selecttwo = 'selected'; 
  }else if(title=='Miss'){
    selectthree = 'selected'; 
  }else if(title=='Master'){
    selectfore = 'selected'; 
  }

  var selectoption = '';
  var selectbpx = '<option value="Mr" '+ selectone +'>Mr</option><option value="Ms" '+ selecttwo +'>Ms</option>';
  var selectbox_infant  ='<option value="Miss" '+ selectthree +'>Miss</option><option value="Master" '+ selectfore +'>Master</option>'; 
  if(passenger_type=="Adult"){
    selectoption = selectbpx+selectbox_infant;
  }else{
    selectoption = selectbox_infant;
  }

  var action = '';
  var mail_stus = '';
  var select_mail_status_pending = '';
  var select_mail_status_update = '';
  if(mailing_status=='PENDING'){
    action = '<button type="submit" class="btn btn-info btn-sm pass_update_btn">Update</button>';
    select_mail_status_pending = 'selected';
  }else{
    select_mail_status_update = 'selected';
  }

  var mail_stus_opt = '<option value="PENDING" '+ select_mail_status_pending +'>PENDING</option><option value="UPDATED" '+ select_mail_status_update +'>UPDATED</option>';

  var str = '<table class="table table-bordered"><tbody><tr><td><strong><input type="hidden" name="" class="pax_id" value="'+pax_id+'">Title</strong></td><td><select class="form-control user_title" name="">'+selectoption+'</select></td></tr><tr><td><strong>First Name</strong></td><td><input type="text" name="first_name" value="'+first_name+'" class="form-control first_name text-uppercase" required></td></tr><tr><td><strong>Last Name</strong></td><td><input type="text" name="last_name" value="'+last_name+'" class="form-control last_name text-uppercase" required></td></tr><tr><td><strong><input type="hidden" name="" class="pax_id" value="'+pax_id+'">Mailing Status</strong></td><td><select class="form-control user_mailing_status" name="user_mailing_status">'+mail_stus_opt+'</select></td></tr><tr><td colspan="2">'+action+'</td></tr></tbody></table>';
    $('.fare_data').html(str);
    $('#dynamic_text').text('Passenger Details');
  $("#action").modal('show');
});
//******

// Dinesh 7-feb-2018
  //send the email voucher
  $('.send_email_voucher').on('click', function(e) {

    // var list_id = $(this).data('list_id');
    // var seat_details_html = $("#seat_det_"+list_id).children('table').html();

    $("#mail_voucher_modal").modal('show');
    $('#mail_voucher_error_message').empty();
    var email = $(this).data('recipient_email');

    $("#voucher_recipient_email").val(email);
        var origin = $(this).data('origin');

    $("#send_mail_btn").off('click').on('click',function(e){
      var email_subject = $('#email_subject').val();

      email = $("#voucher_recipient_email").val();
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      if(email != ''){
        if(!emailReg.test(email)){
          $('#mail_voucher_error_message').empty().text('Please Enter Correct Email Id');
                     return false;    
              }
          
          var _opp_url = app_base_url+'index.php/flight/flight_passenger_details/';
          _opp_url = _opp_url+origin+'?email='+email;
         // console.log(_opp_url);
          toastr.info('Please Wait!!!');
          var formData = new FormData();
          formData.append('file', $('#file')[0].files[0]);
          formData.append('email', email);
          formData.append('fuid', origin);
          formData.append('email_subject', email_subject);
          //formData.append('email_html', seat_details_html);
          $.ajax({
           url: app_base_url+'index.php/flight/flight_passenger_details/',
           type: 'POST',
           data : formData,
           processData: false,  // tell jQuery not to process the data
           contentType: false,  // tell jQuery not to set contentType
           success : function(data) {
                $("#mail_voucher_modal").modal('show');
                jsondata = JSON.parse(data);
                var msg = jsondata.msg;
                if(jsondata.status==1){
                  $("#voucher_recipient_email").val('');
                  $("#file").val('');
                  $('#email_subject').val('');
                  $("#mail_voucher_modal").modal('hide');
                  $('#mail_voucher_error_message').empty();
                  toastr.info(msg);
                }else{
                  $('#mail_voucher_error_message').html(msg);
                  toastr.info(msg);
                }
           }
       });

      /*    $.ajax({
              method: "POST",
              url: app_base_url+'index.php/flight/flight_passenger_details/',
              data: inputs
            })
              .done(function( msg ) {
                alert( "Data Saved: " + msg );
              });*/
          /*$.get(_opp_url, function() {
            
            
            $("#mail_voucher_modal").modal('hide');
          });*/


      }else{
        $('#mail_voucher_error_message').empty().text('Please Enter Email ID');
      }
    });

});



  //old 
  //send the email voucher
  /*$('.send_email_voucher').on('click', function(e) {
    $("#mail_voucher_modal").modal('show');
    $('#mail_voucher_error_message').empty();
        email = $(this).data('recipient_email');
    $("#voucher_recipient_email").val(email);
        var origin = $(this).data('origin');
    $("#send_mail_btn").off('click').on('click',function(e){
      email = $("#voucher_recipient_email").val();
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      if(email != ''){
        if(!emailReg.test(email)){
          $('#mail_voucher_error_message').empty().text('Please Enter Correct Email Id');
                     return false;    
              }
          
          var _opp_url = app_base_url+'index.php/flight/flight_passenger_details/';
          _opp_url = _opp_url+origin+'?email='+email;
         // console.log(_opp_url);
          toastr.info('Please Wait!!!');
          $.get(_opp_url, function() {
            
            toastr.info('Email sent  Successfully!!!');
            $("#mail_voucher_modal").modal('hide');
          });
      }else{
        $('#mail_voucher_error_message').empty().text('Please Enter Email ID');
      }
    });

});*/
//end


/*$(document).on('click', '.menutoggle', function () { 
   $(this).children('.mb0').find('.parentCheckBox').attr('value','hello');
   console.log();
});*/

$(".parentCheckBox").change(function() {

              if (this.checked) {
                  $(this).parents('.mb0').find('.childCheckBox').each(function() {
                      this.checked=true;
                  });
              } else {
                $(this).parents('.mb0').find('.childCheckBox').each(function() {
                      this.checked=false;
                  });
              }
            }
        );


$(document).on('click', '.childCheckBox', function () { 
       if (this.checked) {
            var flag = true;
                $(this).parents('.mb0').find('.childCheckBox').each(
                  function() {
                      if (this.checked == false)
                          flag = false;
                  }
            );
            $(this).parents('.mb0').find('.parentCheckBox').each(function() {
                this.checked=flag;
            });
        } else {
          
          $(this).parents('.mb0').find('.parentCheckBox').each(function() {
                this.checked=false;
            });
        }       
    });


$('.ml_updt').on('click', function(e) {
          var book_id = $(this).val();
          var _opp_url = app_base_url+'index.php/flight/update_mailing_status/';
          _opp_url = _opp_url+book_id;
          // console.log(_opp_url);
          var formData = new FormData();
          formData.append('mailing_status', 'UPDATED');
          var book_id_arr = new Array();

          
          $(this).parents('.mb0').find('.childCheckBox').each(function() {
            if (this.checked == true){
              book_id_arr.push($(this).val());
            }      
          });
          
          formData.append('book_id', book_id_arr);
          //console.log(formData);return false;
          toastr.info('Please Wait!!!');
          $.ajax({
          url: _opp_url,
          type: 'POST',
          data : formData,
          processData: false,  // tell jQuery not to process the data
          contentType: false,  // tell jQuery not to set contentType
          success : function(data) {
              jsondata = JSON.parse(data);
            
              var msg = jsondata.msg;
              if(jsondata.status==1){
               
                toastr.info(msg);
              }else{
                $('#mail_voucher_error_message').html(msg);
                toastr.info(msg);
              }
          }
          });
});
</script>