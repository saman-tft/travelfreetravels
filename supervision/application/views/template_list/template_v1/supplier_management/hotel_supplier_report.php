<?php
if (is_array ( $search_params )) {
  extract ( $search_params );
}

$_datepicker = array (
  array (
    'created_datetime_from',
    PAST_DATE 
    ),
  array (
    'created_datetime_to',
    PAST_DATE 
    ) 
  );
$this->current_page->set_datepicker ( $_datepicker );
$this->current_page->auto_adjust_datepicker ( array (
  array (
    'created_datetime_from',
    'created_datetime_to' 
    ) 
  ) );
  ?>
  <?=$GLOBALS['CI']->template->isolated_view('report/email_popup')?>
  <div class="bodyContent col-md-12">
    <div class="panel panel-default clearfix">
      <div class="panel-heading">
      </div>
      <div class="panel-body">
        <h4>Search Panel</h4>
        <hr>
        <form action="<?=base_url().'supplier_management/report/'?>" method="GET" autocomplete="off"> 
          <div class="clearfix form-group">
            
            <div class="col-xs-4">
            
              <label> Supplier </label> 
              <select class="form-control" name="supplier_name">
              <option>ALL</option>
                <?php
                  foreach($supplier_list as $supplier)
                  {
                    if($supplier_id == $supplier['user_id'])
                    {
                      echo '<option value="'.$supplier['user_id'].'" selected>'.$supplier['first_name'].' '.$supplier['last_name'].'</option>';
                    }
                    else
                    {
                      echo '<option value="'.$supplier['user_id'].'">'.$supplier['first_name'].' '.$supplier['last_name'].'</option>';
                    }
                    
                  }
                ?>
            </select>
          </div>
          <div class="col-xs-4">
            <label> Month </label> 
            <select class="form-control" name="month">
              <option>All</option>
              <?php
                for($i = 1; $i <= 12; $i++)
                {
                  if($month == $i)
                  {
                   echo '<option value="'.$i.'" selected>'.date('F', strtotime('2020-'.$i.'-01')).'</option>';
                  }
                  else
                  {
                    echo '<option value="'.$i.'">'.date('F', strtotime('2020-'.$i.'-01')).'</option>';
                  }
                }

              ?>
            </select>

           <!--  <input type="text" readonly
            id="created_datetime_from" class="form-control"
            name="created_datetime_from" value="<?=@$created_datetime_from?>"
            placeholder="Request Date"> -->
          </div>
          <div class="col-xs-4">
            <label> Year </label> 
            <select class="form-control" name="year">
              <option>All</option>
              <?php
                for($i = 2020; $i <= date('Y'); $i++)
                {
                  if($year == $i)
                  {
                    echo '<option value="'.$i.'" selected>'.$i.'</option>';
                  }
                 else
                 {
                   echo '<option value="'.$i.'">'.$i.'</option>';
                 }
                }

              ?>
            </select>
            <!-- <input type="text" readonly
            id="created_datetime_to"
            class="form-control disable-date-auto-update"
            name="created_datetime_to" value="<?=@$created_datetime_to?>"
            placeholder="Request Date"> -->
          </div>
        </div>
        <div class="col-sm-12 well well-sm">
          <button type="submit" class="btn btn-primary">Search</button>
          <a href="<?=base_url().'supplier_management/report'?>" class="btn btn-warning">Reset</a>
                  
        </div>
      </form>
      
      </div>
      <div class="clearfix"></div>
     
      <div id="tableList"  class="clearfix table-responsive">
        <div class="pull-left"><?=$GLOBALS ['CI']->pagination->create_links () ?><span class="">Total <?=$total_records?> Bookings</span></div>
           <div class="clearfix"></div><br>
           <div class="pull-left" style="padding-left:1%">
             <h4>Amount Payable : <?php echo get_application_default_currency().' '.$supplier_amount_details['total_supplier_price'];?></h4>
          
             <?php 
                if(isset($payment_status) && valid_array($payment_status))
                {
                  if($payment_status['status'] == SUCCESS_STATUS)
                  {
                    echo '<h4>Payment Status &nbsp; : Paid at '.app_friendly_absolute_date($payment_status['data'][0]['created_datetime']).'</h4>';
                  }
                  else if($payment_status['status'] == "0")
                  {
                    echo '<h4>Payment Status &nbsp; : Not Paid</h4>';
                  }
                }
             ?>
           </div>
        	<?php echo get_table($table_data, $total_rows);?>

      </div>
    </div>
  </div>
</div>
</div>
<?php 
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('js/jquery.dataTables.js'), 'defer' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('js/dataTables.tableTools.js'), 'defer' => 'defer');
 ?>
 
<?php
function get_table($table_data, $total_rows)
{
	//$pagination = '<div class="pull-left">'.$GLOBALS['CI']->pagination->create_links().' <span class="">Total '.$total_rows.' Bookings</span></div>';
$report_data = '';
//	$report_data .= '<div id="tableList" class="table-responsive clearfix">';
//	$report_data .= $pagination;
	
	$report_data .= '<table class="table table-condensed table-bordered" id="b2c_report_hotel_table">
		<thead>
		<tr>
			<th>Sno</th>
			<th>Reference No</th>
			<th>Confirmation/<br/>Reference</th>
			<th>Lead Pax details</th>
			<th>Hotel Name</th>
			<th>No. of rooms<br/>(Adult + Child)</th>
			<th>City</th>
			<th>CheckIn/<br/>CheckOut</th>
			<th>Comm.Fare</th>
			<th>TDS</th>
			<th>Admin <br/>Markup+Markup GST</th>
			<th>Convn.Fee</th>
			<th>GST</th>
			<th>Discount</th>
		<th>User Converted Fare</th>
							<th>Supplier Converted Fare</th>
							<th>Admin Converted Fare</th>
			<th>Booked On</th>
			<th>Status</th>
			<th>Payment Status</th>
      <th>Payment id</th>
			<th>Action</th>
		</tr>
		</thead><tfoot>
		<tr>
			<th>Sno</th>
			<th>Reference No</th>
			<th>Confirmation/<br/>Reference</th>
			<th>Lead Pax details</th>
			<th>Hotel Name</th>
			<th>No. of rooms<br/>(Adult + Child)</th>
			<th>City</th>
			<th>CheckIn/<br/>CheckOut</th>
			<th>Comm.Fare</th>
			<th>TDS</th>
			<th>Admin <br/>Markup+Markup GST</th>
			<th>Convn.Fee</th>
			<th>GST</th>
			<th>Discount</th>
			<th>User </th>
		<th>User Converted Fare</th>
							<th>Supplier Converted Fare</th>
							<th>Admin Converted Fare</th>
			<th>Booked On</th>
			<th>Status</th>
			<th>Payment Status</th>
      <th>Payment id</th>
			<th>Action</th>
		</tr>
		</tfoot><tbody>';
		
		if (isset($table_data) == true and valid_array($table_data['booking_details']) == true) {
			$segment_3 = $GLOBALS['CI']->uri->segment(3);
			$current_record = (empty($segment_3) ? 1 : $segment_3);
			$booking_details = $table_data['booking_details'];
		
		    foreach($booking_details as $parent_k => $parent_v) { 
		    	
		        	extract($parent_v);
              // debug($parent_v);exit;
				$action = '';
				$email='';
				$tdy_date = date ( 'Y-m-d' );
				$diff = get_date_difference($tdy_date,$hotel_check_in);
				$customer_details = customer_details($app_reference, $booking_source, $status);
				$action .= hotel_voucher($app_reference, $booking_source, $status,'b2c');
				$action.='<br/>';
				$action .= hotel_pdf($app_reference, $booking_source, $status);
				$action.='<br/>';
				$action .= hotel_voucher_email($app_reference, $booking_source,$status,$parent_v['email']);
				$action .= '<br />' . $customer_details;
                                $action .= hotel_GST_Invoice($app_reference, $booking_source, $status, 'b2c');
				$action.='<br/>';
		    	if($status == 'BOOKING_CONFIRMED' && $diff > 0) {
					$action .= cancel_hotel_booking($app_reference, $booking_source, $status);
				}
				$action .=get_booking_pending_status($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);
				$
				$email = hotel_email_voucher($app_reference, $booking_source, $status);
			if(!empty($payment_details))
            {
              $payment_id1=json_decode($payment_details[0]['response_params'],true);
              $payment_id=$payment_id1['razorpay_payment_id'];
            }
            else
            {
               $payment_id="";
            }
		$report_data .= '<tr>
					<td>'.($current_record++).'</td>
					<td>'.$app_reference.'</td>
					<td class="">'.$confirmation_reference.'/<br/>'.$booking_reference.'</span></td>
					<td>'.$lead_pax_name. '<br/>'.
						  $lead_pax_email.'<br/>'.
						  $lead_pax_phone_number.'
					</td>
					<td>'.$hotel_name.'</td>
					<td>'.$total_rooms.'<br/>('.$adult_count.'+'.$child_count.')</td>
					<td>'.$hotel_location.'</td>
					<td>'.date('d-m-Y', strtotime($hotel_check_in)).'/<br/>'.date('d-m-Y', strtotime($hotel_check_out)).'</td>
					<td>'.$currency.''.($fare).'</td>
					<td>'.$currency.''.($itinerary_details[0]['TDS']).'</td>
					<td>'.$currency.''.$admin_markup.'</td>
					<td>'.$currency.''.$convinence_amount.'</td>
					<td>'.$currency.''.$gst.'</td>
					<td>'.$currency.''.$discount.'</td>
					<td>'.$currency.''.$grand_total.'</td>
						<td>'.$itinerary_details[0]['currency'].''.$itinerary_details[0]['total_fare'].'</td>
							<td>'.$itinerary_details[0]['currency'].''.$itinerary_details[0]['total_fare'].'</td>
					<td>'.date('d-m-Y', strtotime($voucher_date)).'</td>
					<td><span class="'.booking_status_label($status).'">'.$status.'</span></td>
          <td><span class="'.booking_status_label($payment_details[0]['status']).'">'.$payment_details[0]['status'].'</span></td>
					<td>'.$payment_id.'</td>

					<td><div class="" role="group">
					<div class="dropdown">
						 <button class="dropbtn">
							 <i class="fa fa-ellipsis-v"></i>
							 </button>
							 <div class="dropdown-content">

							'.$action.'

							 </div>
						</div>
					
					</div></td>
				</tr>';
			}
		} else {
			$report_data .= '<tr><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
								 <td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
								<td>---</td><td>---</td><td>---</td><td>---</td></tr>';
		}
	$report_data .= '</tbody></table>
			
			';
	return $report_data;
}
function get_accomodation_cancellation($courseType, $refId)
{
	return '<a href="'.base_url().'index.php/booking/accomodation_cancellation?courseType='.$courseType.'&refId='.$refId.'" class="col-md-12 btn btn-sm btn-danger "><i class="far fa-exclamation-triangle"></i> Cancel</a>';
}
function hotel_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="btn send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="far fa-envelope"></i> Email Voucher</a>';
}
function get_booking_pending_status($app_reference, $booking_source, $status)
{
	if($status == 'BOOKING_HOLD'){
		return '<a class="get_hotel_hb_status col-md-12 btn flight_u" id="pending_status_'.$app_reference.'" data-booking-source="'.$booking_source.'"
			data-app-reference="'.$app_reference.'" data-status="'.$status.'"><i class="far fa-info"></i> Update Supplier Info</a>';
	}
}
function customer_details($app_reference, $booking_source = '', $status = '') {
        return '<a  target="_blank" data-app-reference="' . $app_reference . '" data-booking-status="' . $status . '" data-booking-source="' . $booking_source . '" class="btn flight_u customer_details"><i class="fa fa-file"></i>Pax profile</a>';
}
?>
<script>
$(document).ready(function() {
    // $('#b2c_report_hotel_table').DataTable({
    //     // Disable initial sort 
    //     "aaSorting": []
    // });

	    //send the email voucher
		$('.send_email_voucher').on('click', function(e) {
			$("#mail_voucher_modal").modal('show');
			$('#mail_voucher_error_message').empty();
	        email = $(this).data('recipient_email');
			$("#voucher_recipient_email").val(email);
	        app_reference = $(this).data('app-reference');
	        book_reference = $(this).data('booking-source');
	        app_status = $(this).data('app-status');
		  $("#send_mail_btn").off('click').on('click',function(e){
			 
			  email = $("#voucher_recipient_email").val();
	
			  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
				 if(email != ''){ 
					  if(!emailReg.test(email)){
						  $('#mail_voucher_error_message').empty().text('Please Enter Correct Email Id');
		                     return false;    
						      }
					var _opp_url = app_base_url+'index.php/voucher/hotel/';
					_opp_url = _opp_url+app_reference+'/'+book_reference+'/'+app_status+'/email_voucher/'+email;
					toastr.info('Please Wait!!!');
					$.get(_opp_url, function() {
						
						toastr.info('Email sent  Successfully!!!');
						$("#mail_voucher_modal").modal('hide');
					});
					 
			  }else{
				  $('#mail_voucher_error_message').empty().text('Please Enter Email ID');
				  }
		  });
	
	});
	$(".get_hotel_hb_status").on("click",function(e){
  		
		 	app_reference = $(this).data('app-reference');
        book_reference = $(this).data('booking-source');
        app_status = $(this).data('status');
        var _opp_url = app_base_url+'index.php/hotel/get_pending_booking_status/';
		_opp_url = _opp_url+app_reference+'/'+book_reference+'/'+app_status;
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function(res) {
			if(res==1){
				toastr.info('Status Updated Successfully!!!');	
				location.reload(); 
			}else{
				toastr.info('Status not updated');
			}
			
			$("#mail_voucher_modal").modal('hide');
		});
  });
});
$(document).on('click', '.customer_details', function (e) {
            
            e.preventDefault();
            //$(this).attr('disabled', 'disabled');//disable button
            var app_ref = $(this).data('app-reference');
            var booking_src = $(this).data('booking-source');
            var status = $(this).data('booking-status');
            var module = 'hotel';

            jQuery.ajax({
                type: "GET",
                url: app_base_url + 'index.php/report/get_customer_details/' + app_ref + '/' + booking_src + '/' + status + '/' + module + '/',
                dataType: 'json',
                success: function (res) {

                    $('#customer_parameters').html(res.data);
                    $('#pax_modal').modal('show');
                }
            });
        });
</script>