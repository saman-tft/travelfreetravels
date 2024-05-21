<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('bootstrap-toastr/toastr.min.css');?>" rel="stylesheet" defer>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('bootstrap-toastr/toastr.min.js'); ?>"></script>
<div class="bodyContent">
	<div class="table_outer_wrper"><!-- PANEL WRAP START -->
		<div class="panel_custom_heading"><!-- PANEL HEAD START -->
			<div class="panel_title">
				<?php echo $GLOBALS['CI']->template->isolated_view('share/report_navigator_tab') ?>
                <div class="clearfix"></div>
                <div class="search_fltr_section">
                <form method="GET" role="search" class="navbar-form" id="auto_suggest_booking_id_form">
				<div class="form-group">
				<input type="hidden" id="module" value="<?=PROVAB_CAR_BOOKING_SOURCE?>">
				<input type="text" autocomplete="off" data-search_category="search_query" placeholder="AppReference/ConfNumber" class="form-control auto_suggest_booking_id ui-autocomplete-input" id="auto_suggest_booking_id" name="filter_report_data" value="<?=@$_GET['filter_report_data']?>">
				</div>
				<button title="Search" class="btn btn-default" type="submit"><i class="far fa-search"></i></button>
				<a title="Clear Search" class="btn btn-default" href="<?=base_url().'index.php/report/car'?>"><i class="far fa-history"></i></a>
			</form>
           	 </div>
           	 <?php if($total_records > 0){ ?>
            <div class="clearfix"></div>
                <div class="dropdown col-xs-1">
                    <button class="btn btn-info dropdown-toggle" type="button" id="excel_imp_drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-download" aria-hidden="true"></i> Excel
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="excel_imp_drop">
                        <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_car_report/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed Booking</a>
                        </li>
                        
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_car_report/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Cancelled Booking</a>
                        </li>
                        
                    </ul>
                </div>
                <div class="dropdown col-xs-1">
                    <button class="btn btn-info dropdown-toggle" type="button" id="pdf_imp_drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-download" aria-hidden="true"></i> Pdf
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="pdf_imp_drop">
                        <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_car_report/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed Booking download</a>
                        </li>
                        
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_car_report/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" >Cancelled Booking download</a>
                        </li>
                        
                    </ul>
                </div>
                <div class="dropdown col-xs-1">
                    <button class="btn btn-info dropdown-toggle" type="button" id="csv_imp_drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-download" aria-hidden="true"></i> CSV
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="csv_imp_drop">
                        <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_car_report/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed Booking download</a>
                        </li>
                        
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_car_report/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" >Cancelled Booking download</a>
                        </li>
                       
                    </ul>



                </div>
            
            <?php } ?>
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="panel_bdy"><!-- PANEL BODY START -->
			
			<div class="tab-content">
			<?php echo get_table($table_data, $total_rows);?>
			</div>
		</div>
	</div>
</div>
<?=$GLOBALS['CI']->template->isolated_view('report/email_popup')?>
<?php
function get_table($table_data, $total_rows)
{
	$pagination = '<div class="pull-right">'.$GLOBALS['CI']->pagination->create_links().'<span class="">Total '.$total_rows.' Bookings</span></div>';
	$report_data = '';
	$report_data .= '<div id="tableList" class="">';
	$report_data .= $pagination;
	
	$report_data .= '<table class="table table-condensed table-bordered">
		<tr>
			<th>Sno</th>
			<th>Application<br/>Reference</th>
			<th>Confirmation<br/>Number</th>
			<th>Customer<br/>Name</th>
            <th>PickupLocation</th>
            <th>DropLocation</th>
			<th>Fare</th>
			<th>Markup</th>
			<th>TotalFare</th>
			<th>Pickup DateTime/<br/>Drop DateTime</th>
			<th>BookedOn</th>
            <th>Status</th>
			<th>Action</th>
		</tr>';
		
		if (isset($table_data) == true and valid_array($table_data['booking_details']) == true) {
			$segment_3 = $GLOBALS['CI']->uri->segment(3);
			$current_record = (empty($segment_3) ? 1 : $segment_3);
			$booking_details = $table_data['booking_details'];
		    foreach($booking_details as $parent_k => $parent_v) { 
		    	// debug($booking_details);exit;
		    	extract($parent_v);
		    	$pickup_datetime = $car_from_date.' '.$pickup_time;
		    	$drop_datetime = $car_to_date.' '.$drop_time;
		    	// echo $pickup_datetime;exit;
				$action = '';
				$email='';
				$tdy_date = date ( 'Y-m-d' );
				$diff = get_date_difference($tdy_date,$car_from_date);
				$action .= car_voucher($app_reference, $booking_source, $status);
				$action .='<br/>';
				$action .= car_pdf($app_reference, $booking_source, $status);
				$action .='<br/>';
				$action .= car_voucher_email($app_reference, $booking_source,$status,$parent_v['email']);
				$action.='<br/>';
				if(($status == 'BOOKING_CONFIRMED') && ($diff > 0)) {
					$action .= cancel_car_booking($app_reference, $booking_source, $status);
				}
				$action.= get_cancellation_details_button($app_reference, $booking_source, $status);
				$action .=get_booking_pending_status($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);

				$email = car_email_voucher($app_reference, $booking_source, $status);
			
		$report_data .= '<tr>
					<td>'.($current_record++).'</td>
					<td>'.$app_reference.'</td>
					<td class="">'.$booking_reference.'</span></td>
					<td>'.$customer_details[0]['title'].' '.$customer_details[0]['first_name'].' '.$customer_details[0]['last_name'].'</td>
					<td>'.$car_pickup_lcation.'</td>
					<td>'.$car_drop_location.'</td>
					<td>'.($fare+$admin_markup).'</td>
					<td>'.$agent_markup.'</td>
					<td>'.$grand_total.'</td>
					<td>'.month_date_year_time($pickup_datetime).'/<br/>'.month_date_year_time($drop_datetime).'</td>
					<td>'.$voucher_date.'</td>
					<td><span class="'.booking_status_label($status).'">'.$status.'</span></td>
					<td><div class="" role="group">'.$action.'</div></td>
				</tr>';
			}
		} else {
			$report_data .= '<tr><td>No Data Found</td></tr>';
		}
	$report_data .= '</table>
			</div>';
	return $report_data;
}
function get_accomodation_cancellation($courseType, $refId)
{
	return '<a href="'.base_url().'index.php/booking/accomodation_cancellation?courseType='.$courseType.'&refId='.$refId.'" class="col-md-12 btn btn-sm btn-danger "><i class="far fa-exclamation-triangle"></i> Cancel</a>';
}
function car_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="btn btn-sm btn-primary send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="far fa-envelope"></i> Email Voucher</a>';
}
function get_cancellation_details_button($app_reference, $booking_source, $status)
{
	if($status == 'BOOKING_CANCELLED'){
		return '<a target="_blank" href="'.base_url().'car/cancellation_refund_details?app_reference='.$app_reference.'&booking_source='.$booking_source.'&status='.$status.'" class="col-md-12 btn btn-sm btn-info "><i class="far fa-info"></i> Cancellation Details</a>';
	}
}
function get_booking_pending_status($app_reference, $booking_source, $status)
{
	if($status == 'BOOKING_HOLD'){
		return '<a class="get_car_hb_status col-md-12 btn btn-sm btn-info flight_u" id="pending_status_'.$app_reference.'" data-booking-source="'.$booking_source.'"
			data-app-reference="'.$app_reference.'" data-status="'.$status.'"><i class="far fa-info"></i>Update Supplier Info</a>';
	}
}
?>
<script>

$(document).ready(function() {

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
				 
						var _opp_url = app_base_url+'index.php/voucher/car/';
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
	$(".get_car_hb_status").on("click",function(e){
  		
		 	app_reference = $(this).data('app-reference');
        book_reference = $(this).data('booking-source');
        app_status = $(this).data('status');
        var _opp_url = app_base_url+'index.php/car/get_pending_booking_status/';
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
</script>
