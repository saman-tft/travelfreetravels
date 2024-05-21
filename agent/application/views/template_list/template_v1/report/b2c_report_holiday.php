<!--  <script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/datatables/dataTables.bootstrap.min.js"></script> -->
<?=$GLOBALS['CI']->template->isolated_view('report/email_popup')?>
<?php
if (is_array($search_params)) {
	extract($search_params);
}
$_datepicker = array(array('created_datetime_from', PAST_DATE), array('created_datetime_to', PAST_DATE));
$this->current_page->set_datepicker($_datepicker);
$this->current_page->auto_adjust_datepicker(array(array('created_datetime_from', 'created_datetime_to')));
?>
<div class="modal fade" id="pax_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 880px;">
        <div class="modal-content">
            <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-users"></i>
                    Customer Details</h4>
            </div>
            <div class="modal-body">
                <div id="customer_parameters">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="bodyContent col-md-12">
    <div class="panel panel-default clearfix">
        <!-- PANEL WRAP START -->
        <div class="panel-heading">
            <!-- PANEL HEAD START -->
            <?=$GLOBALS['CI']->template->isolated_view('share/report_navigator_tab')?>
        </div><!-- PANEL HEAD START -->
        <div class="panel-body hide">
            <div class="clearfix">
                <?php //echo $GLOBALS['CI']->template->isolated_view('report/make_search_easy'); ?>

            </div>
            <hr>
            <h4 class="advncpnl">Advanced Search Panel <button class="btn btn-primary btn-sm toggle-btn" data-toggle="collapse"
                    data-target="#show-search">+
                </button> </h4>
            <hr>
            <div id="show-search" class="collapse">
                <form method="GET" autocomplete="off">
                    <input type="hidden" name="created_by_id" value="<?=@$created_by_id?>">
                    <div class="clearfix form-group">
                        <div class="col-xs-4">
                            <label>
                                Application Reference
                            </label>
                            <input type="text" class="form-control" name="app_reference" value="<?=@$app_reference?>"
                                placeholder="Application Reference">
                        </div>
                        <!-- <div class="col-xs-4">
							<label>
							Phone
							</label>
							<input type="text" class="form-control numeric" name="phone" value="<?=@$phone?>" placeholder="Phone">
						</div>
						<div class="col-xs-4">
							<label>
							Email
							</label>
							<input type="text" class="form-control" name="email" value="<?=@$email?>" placeholder="Email">
						</div> -->
                        <div class="col-xs-4">
                            <label>
                                Status
                            </label>
                            <select class="form-control" name="status">
                                <option>All</option>
                                <?=generate_options($status_options, array(@$status))?>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            <label>
                                Booked From Date
                            </label>
                            <input type="text" readonly id="created_datetime_from" class="form-control"
                                name="created_datetime_from" value="<?=@$created_datetime_from?>"
                                placeholder="Request Date">
                        </div>
                        <div class="col-xs-4">
                            <label>
                                Booked To Date
                            </label>
                            <input type="text" readonly id="created_datetime_to"
                                class="form-control disable-date-auto-update" name="created_datetime_to"
                                value="<?=@$created_datetime_to?>" placeholder="Request Date">
                        </div>
                    </div>
                    <div class="col-sm-12 well well-sm">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <button type="reset" class="btn btn-warning">Reset</button>
                        <a href="<?php echo base_url().'index.php/report/b2c_hotel_report?' ?>" id="clear-filter"
                            class="btn btn-primary">Clear Filter</a>
                    </div>
                </form>
            </div>
            <?php if($total_records > 0){ ?>
            <div class="clearfix"></div>
            <div class="dropdown col-xs-1">
                <button class="btn btn-info dropdown-toggle" type="button" id="excel_imp_drop" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="true">
                    <i class="fa fa-download" aria-hidden="true"></i> Excel
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="excel_imp_drop">
                    <li>
                        <a
                            href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_holiday_report/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed
                            Booking</a>
                    </li>

                    <li>
                        <a
                            href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_holiday_report/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Cancelled
                            Booking</a>
                    </li>

                </ul>
            </div>
            <div class="dropdown col-xs-1">
                <button class="btn btn-info dropdown-toggle" type="button" id="pdf_imp_drop" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="true">
                    <i class="fa fa-download" aria-hidden="true"></i> Pdf
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="pdf_imp_drop">
                    <li>
                        <a
                            href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_holiday_report/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed
                            Booking download</a>
                    </li>

                    <li>
                        <a
                            href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_holiday_report/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Cancelled
                            Booking download</a>
                    </li>

                </ul>
            </div>
            <div class="dropdown col-xs-1">
                <button class="btn btn-info dropdown-toggle" type="button" id="csv_imp_drop" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="true">
                    <i class="fa fa-download" aria-hidden="true"></i> CSV
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="csv_imp_drop">
                    <li>
                        <a
                            href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_holiday_report/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed
                            Booking download</a>
                    </li>

                    <li>
                        <a
                            href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_holiday_report/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Cancelled
                            Booking download</a>
                    </li>

                </ul>



            </div>

            <?php } ?>
        </div>

        <div class="clearfix col-md-12" style="overflow: auto">
            <!-- PANEL BODY START -->
            <?php echo get_table($table_data, $total_rows);?>
        </div>
    </div>
</div>

<?php
function get_table($table_data, $total_rows)
{
	$pagination = '<div class="pull-left">'.$GLOBALS['CI']->pagination->create_links().' <span class="">Total '.$total_rows.' Bookings</span></div>';
	$report_data = '';
	$report_data .= '<div id="tableList" class="clearfix">';
	$report_data .= $pagination;
	
	$report_data .= '<table class="table table-condensed table-bordered" id="b2c_report_hotel_table">
		<thead>
		<tr>
			<th>Sno</th>
			<th>Reference No</th>
			
			<th>Lead Pax details</th>
			<th>Holiday Name</th>
			
			<th>City</th>
			<th>Date of Travel </th>
            <th>Agent Netfare</th>	
            <th>Agent Markup</th>
			<th>Grand Total</th>	
			<th>Booked On</th>
			<th>Status</th>
			<!--<th>Payment Status</th>-->
			<th>Action</th>
		</tr>
		</thead><tbody>';
		
		if (isset($table_data) == true and valid_array($table_data['data']) == true) {
			$segment_3 = $GLOBALS['CI']->uri->segment(3);
			$current_record = (empty($segment_3) ? 1 : $segment_3);
			$booking_details = $table_data['data'];
			$package_details=$table_data['package_details'];
		 
		    foreach($booking_details as $parent_k => $parent_v) { 
		    	
		        	extract($parent_v);

$pack_attr=json_decode($parent_v['booking_details']['attributes'],1);
$user_attr=json_decode($parent_v['booking_details']['user_attributes'],1);

				$action = '';
				$email='';
	$voucher_btn= '<a href="'.base_url().'voucher/b2b_holiday/'.$parent_v['booking_details']['app_reference'].'/'.$parent_v['booking_details']['status'].'/show_voucher" target="_blank" class="btn btn-sm btn-primary"><i class="far fa-file"></i> Voucher</a>';
   $email_btn='<a class="btn btn-sm btn-primary send_email_voucher" data-app-status="'.$parent_v['booking_details']['status'].'"   data-app-reference="'.$parent_v['booking_details']['app_reference'].'" data-recipient_email="'.$user_attr['billing_email'].'" id=""><i class="far fa-envelope"></i> Email Voucher</a>';
   $pdf_btn='<a href="'.base_url().'voucher/b2b_holiday/'.$parent_v['booking_details']['app_reference'].'/'.$parent_v['booking_details']['status'].'/show_pdf" target="_blank" class="btn btn-sm btn-primary"><i class="far fa-file"></i> PDF</a>';
   if($parent_v['booking_details']['status']=="BOOKING_CONFIRMED")
   {
   	$cancel_btn= '<a href="'.base_url().'report/tours_pre_cancellation/'.$parent_v['booking_details']['app_reference'].'/'.$parent_v['booking_details']['status'].'/" id="cancel" class="btn btn-sm btn-warning"><i class="far fa-close"></i>Cancel</a>';
   }
		
		//debug($parent_v);exit();	
		$tdy_date = date ( 'Y-m-d' );
				$diff = get_date_difference($tdy_date,$hotel_check_in);
				$customer_details = customer_details($app_reference, $booking_source, $status);
				// $action .= holiday_voucher($parent_v['booking_details']['app_reference'],$parent_v['booking_details']['status'],'b2b');

				$action.=$voucher_btn;
				$action.='<br/>';
				$action .=$pdf_btn;
				$action.='<br/>';
				// $action .= holiday_voucher_email($app_reference, $booking_source,$status,$parent_v['email']);
				$action.=$email_btn;
				$action.=$cancel_btn;
				//$action .= '<br />' . $customer_details;
				$action.='<br/>';
		    	if($status == 'BOOKING_CONFIRMED' && $diff>0) {
					$action .= cancel_holiday_booking($app_reference, $booking_source, $status,'b2b');
				}
				$action .=get_booking_pending_status($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);
				
				$email = hotel_email_voucher($app_reference, $booking_source, $status);
			$add=date('d-M-Y',strtotime($parent_v['booking_details']['departure_date']));
			//debug($add);
//debug($tours_details);
 
foreach($tours_details as $m)
{
	if($pack_attr['tour_id']==$tours_details['id'])
	{
		$pack_name=$tours_details['package_name'];
		$pack_loc=$tours_details['city_name'][0];
		$pack_duration=$tours_details['duration'];
		break;
	}
}
  //debug($pack_attr); die;
$total_no_of_night= $pack_duration;
// debug($total_no_of_night);

 $hotel_checkout_date = date('Y-m-d', strtotime($add. ' + '.$total_no_of_night.' days'));
 
 $hotel_checkout_date = date('d-m-Y',strtotime($hotel_checkout_date));


 $hotel_checkin_date = explode('-', $parent_v['booking_details']['departure_date']);
 $hotel_checkout_date = explode('-', $hotel_checkout_date);
 //debug($hotel_checkin_date);exit();
$voucher_date=$parent_v['booking_details']['created_datetime'];
 // $grand_total=$pack_attr['total_markup']+$pack_attr['cv']+$pack_attr['tour_totalamount']+$pack_attr['gst']-$pack_attr['discount'];
 $grand_total=$parent_v['booking_details']['basic_fare']-$parent_v['booking_details']['discount'];
 $agent_fare=$grand_total-$pack_attr['agent_markup'];
 $agent_markup=$pack_attr['agent_markup'];

		$report_data .= '<tr>
					<td>'.($current_record++).'</td>
					<td>'.$parent_v['booking_details']['app_reference'].'</td>
				
					<td>'.$user_attr['first_name'][0]. '<br/>'.
						  $user_attr['billing_email'].'<br/>'.
						  $user_attr['passenger_contact'].'
					</td>
					<td>'.$pack_name.'</td>
					
					<td>'.$pack_loc.'</td>
					<td>'.$hotel_checkin_date[2].'-'.$hotel_checkin_date[1].'-'.$hotel_checkin_date[0].'<br>'.$hotel_checkout_date[0].'-'.$hotel_checkout_date[1].'-'.$hotel_checkout_date[2].'</td>
				     <td>'.number_format($agent_fare,2).'</td>
				      <td>'.number_format($agent_markup,2).'</td>
					<td>'.$parent_v['booking_details']['currency_code'].'&nbsp;'.number_format($grand_total,2).'</td>
					<td>'.date('d-m-Y', strtotime($voucher_date)).'</td>
					<td><span class="'.booking_status_label($parent_v['booking_details']['status']).'">'.$parent_v['booking_details']['status'].'</span></td>
					
					<td><div class="" role="group">'.$action.'</div></td>
				</tr>';
			}
		} else {
			$report_data .= '<tr><td colspan="10"> No Records Found</td></tr>';
		}
	$report_data .= '</tbody></table>
			</div>';
	return $report_data;
}
function get_accomodation_cancellation($courseType, $refId)
{
	return '<a href="'.base_url().'index.php/booking/accomodation_cancellation?courseType='.$courseType.'&refId='.$refId.'" class="col-md-12 btn btn-sm btn-danger "><i class="far fa-exclamation-triangle"></i> Cancel</a>';
}
function hotel_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="btn btn-sm btn-primary send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="far fa-envelope"></i> Email Voucher</a>';
}
// function holiday_voucher_email($app_reference, $booking_source,$status,$recipient_email)
// {

// 	return '<a class="btn btn-sm btn-primary send_email_voucher" data-app-status="'.$parent_v['booking_details']['status'].'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="far fa-envelope"></i> Email Voucher</a>';
// }
function get_booking_pending_status($app_reference, $booking_source, $status)
{
	if($status == 'BOOKING_HOLD'){
		return '<a class="get_hotel_hb_status col-md-12 btn btn-sm btn-info flight_u" id="pending_status_'.$app_reference.'" data-booking-source="'.$booking_source.'"
			data-app-reference="'.$app_reference.'" data-status="'.$status.'"><i class="far fa-info"></i> Update Supplier Info</a>';
	}
}
function customer_details($app_reference, $booking_source = '', $status = '') {
        return '<a  target="_blank" data-app-reference="' . $app_reference . '" data-booking-status="' . $status . '" data-booking-source="' . $booking_source . '" class="btn btn-sm btn-primary flight_u customer_details"><i class="fa fa-file-o"></i> <small>Pax profile</small></a>';
}
?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
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

        app_status = $(this).data('app-status');

        $("#send_mail_btn").off('click').on('click', function(e) {

            email = $("#voucher_recipient_email").val();

            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            if (email != '') {
                if (!emailReg.test(email)) {
                    $('#mail_voucher_error_message').empty().text(
                        'Please Enter Correct Email Id');
                    return false;
                }
                var _opp_url = app_base_url + 'index.php/voucher/b2b_holiday/';
                _opp_url = _opp_url + app_reference + '/' + app_status + '/email_voucher/' +
                    email;

                toastr.success('Please Wait!!!');
                <?php ?>
                $.get(_opp_url, function() {
                    //alert("ok");
                    toastr.success('Email sent  Successfully!!!');

                    $("#mail_voucher_modal").modal('hide');
                });

            } else {
                $('#mail_voucher_error_message').empty().text('Please Enter Email ID');
            }
        });

    });
    $(".get_hotel_hb_status").on("click", function(e) {

        app_reference = $(this).data('app-reference');
        book_reference = $(this).data('booking-source');
        app_status = $(this).data('status');
        var _opp_url = app_base_url + 'index.php/hotel/get_pending_booking_status/';
        _opp_url = _opp_url + app_reference + '/' + book_reference + '/' + app_status;
        toastr.info('Please Wait!!!');
        $.get(_opp_url, function(res) {
            if (res == 1) {
                toastr.info('Status Updated Successfully!!!');
                location.reload();
            } else {
                toastr.info('Status not updated');
            }

            $("#mail_voucher_modal").modal('hide');
        });
    });
});
$(document).on('click', '.customer_details', function(e) {

    e.preventDefault();
    //$(this).attr('disabled', 'disabled');//disable button
    var app_ref = $(this).data('app-reference');
    var booking_src = $(this).data('booking-source');
    var status = $(this).data('booking-status');
    var module = 'hotel';

    jQuery.ajax({
        type: "GET",
        url: app_base_url + 'index.php/report/get_customer_details/' + app_ref + '/' + booking_src +
            '/' + status + '/' + module + '/',
        dataType: 'json',
        success: function(res) {

            $('#customer_parameters').html(res.data);
            $('#pax_modal').modal('show');
        }
    });
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $("#cancel").click(function() {
        confirm('are you sure?');

    });
});
</script>