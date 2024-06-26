<?php
include_once 'process_tbo_response.php';
$template_images = $GLOBALS['CI']->template->template_images();
$tmp_summary = $GLOBALS['CI']->flight_lib->get_trip_segment_summary($pre_booking_params['token'], $currency_obj);
$summary = $tmp_summary['summary'];
$price = $tmp_summary['price'];
$cur_Currency = $tmp_summary['currency'];
$is_domestic = $pre_booking_params['is_domestic'];
if ($is_domestic != true) {
	$pass_mand = '<sup class="text-danger">*</sup>';
	$pass_req = 'required';
} else {
	$pass_mand = '';
	$pass_req = '';
}
?>
<section class="passenger-details">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="panel panel-default b-r-0 m-0">
					<div class="panel-body p-b-0">
						<h1 class="h3 m-t-0">Travel Details</h1>
						<h2 class="h4 text-p">Fare Details</h2>
					</div>
					<table class="table">
						<tbody>
							<tr>
								<td>Total Base Fare</td>
								<td class="text-right"><?php echo $cur_Currency?> <span class="h4 text-i"><?=$price['BaseFare']?></span></td>
							</tr>
							<tr>
								<td>Taxes & Fees</td>
								<td class="text-right"><?php echo $cur_Currency?> <span class="h4 text-i"><?=$price['TotalTax']?></span></td>
							</tr>
							<tr>
								<td>Grand Total</td>
								<td class="text-right"><?php echo $cur_Currency?> <span class="h4 text-i"><?=$price['TotalPrice']?></span></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="panel panel-default b-r-0 m-0">
					<div class="panel-body p-t-0 p-b-0 text-center">
						<h2 class="h4 text-p text-left">Booking Summary
						 <?=$search_data['from']?>-<?=$search_data['to']?>(<?=get_trip_type($search_data['trip_type'])?>)</h2>
					</div>
					<?php foreach($summary as $___k => $___v) { //START FOR LOOP?>
					<div class="panel-body p-t-0 p-b-0 text-center <?php echo ($___k > 0 ? 'more-details hide' : ''); ?>">
						<span class="label label-primary">Segment <?=($___k+1)?></span> <h3 class="h5"><b><?php echo $___v['from_loc']; ?></b></h3>
						<img src="<?=$GLOBALS['CI']->template->template_images('icons/passenger-flight-icon.png'); ?>" alt="Passenger Flight Icon">
						<h3 class="h5"><b><?php echo $___v['to_loc']; ?></b></h3>
						<h4 class="h6"><?php echo $___v['from_date']; ?> <span class="fa fa-clock-o"></span> <?php echo $___v['to_date']; ?></h4>
						<h4 class="h6">Stops : <?php echo $___v['stops'];?></h4>
						<img src="<?php echo SYSTEM_IMAGE_DIR.'airline_logo/'.$___v['airline_code']; ?>.gif" alt="Flight Image">
						<p><?php echo $___v['airline_name']; ?></p>
					<table class="table text-left">
						<tbody>
							<tr>
								<td>Passenger</td>
								<td class="text-right">
									<div role="" class="">
										<div role="group" class="btn-group btn-group-xs">
											<button class="btn btn-default b-r-0" type="button">
											<img alt="Male Icon" src="<?=$template_images?>icons/male-icon.png"> <?=$search_data['adult_config']?></button>
										</div>
										<div role="group" class="btn-group btn-group-xs">
											<button class="btn btn-default b-r-0" type="button">
											<img alt="Child Icon" src="<?=$template_images?>icons/child-icon.png"> <?=$search_data['child_config']?></button>
										</div>
										<div role="group" class="btn-group btn-group-xs">
											<button class="btn btn-default b-r-0" type="button">
											<img alt="Infant Icon" src="<?=$template_images?>icons/infant-icon.png"> <?=$search_data['infant_config']?></button>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>Departure Time</td>
								<td class="text-right"><b><?php echo $___v['from_time']; ?></b></td>
							</tr>
							<tr>
								<td>Arrival Time</td>
								<td class="text-right"><b><?php echo $___v['to_time']; ?></b></td>
							</tr>
							<tr class="hide">
								<td>Class</td>
								<td class="text-right"><b>Economy</b></td>
							</tr>
							<tr>
								<td>Duration</td>
								<td class="text-right"><b><?php echo get_duration_label($___v['duration']); ?></b></td>
							</tr>
						</tbody>
					</table>
					</div>
				<?php } //END FOR LOOP ?>
				<div class="text-center <?php echo ($___k == 0 ? 'hide' : ''); ?>">
					<a href="#" class="more-flight-details">More Details</a>
					<a href="#" class="less-flight-details" style="display:none;">Hide More Details</a>
				</div>
				</div>
			</div>
			<div class="col-md-8"><!-- Passenger Details Start -->
				<div class="panel panel-default b-r-0">
					<div class="panel-body">
						<form action="<?=base_url().'index.php/flight/pre_booking/'.$search_data['search_id']?>" method="POST" autocomplete="off">
							<div class="hide">
								<?php $dynamic_params_url = serialized_data($pre_booking_params);?>
								<input type="hidden" required="required" name="token"		value="<?=$dynamic_params_url;?>" />
								<input type="hidden" required="required" name="token_key"	value="<?=md5($dynamic_params_url);?>" />
								<input type="hidden" required="required" name="op"			value="book_room">
								<input type="hidden" required="required" name="booking_source"		value="<?=$booking_source?>" readonly="readonly">
							</div>
							<div class="clearfix">
								<div class="pull-left">
									<h1 class="h3 m-t-0"><img src="<?=$GLOBALS['CI']->template->template_images('icons/angle-arrow-down.png')?>" alt="Angle Arrow Down Icon"> Passenger Details</h1>
								</div>
							</div>
<?php
/**
 * Collection field name 
 */
//Title, Firstname, Middlename, Lastname, Phoneno, Email, PaxType, LeadPassenger, Age, PassportNo, PassportIssueDate, PassportExpDate
$total_adult_count	= is_array($search_data['adult_config']) ? array_sum($search_data['adult_config']) : intval($search_data['adult_config']);
$total_child_count	= is_array($search_data['child_config']) ? array_sum($search_data['child_config']) : intval($search_data['child_config']);
$total_infant_count	= is_array($search_data['infant_config']) ? array_sum($search_data['infant_config']) : intval($search_data['infant_config']);
//------------------------------ DATEPICKER START
$i = 1;
$datepicker_list = array();
if ($total_adult_count > 0) {
	for ($i=1; $i<=$total_adult_count; $i++) {
		$datepicker_list[] = array('adult-date-picker-'.$i, ADULT_DATE_PICKER);
	}
}

if ($total_child_count > 0) {
	//id should be auto picked so initialize $i to previous value of $i
	for ($i=$i; $i<=($total_child_count+$total_adult_count); $i++) {
		$datepicker_list[] = array('child-date-picker-'.$i, CHILD_DATE_PICKER);
	}
}

if ($total_infant_count > 0) {
	//id should be auto picked so initialize $i to previous value of $i
	for ($i=$i; $i<=($total_child_count+$total_adult_count+$total_infant_count); $i++) {
		$datepicker_list[] = array('infant-date-picker-'.$i, INFANT_DATE_PICKER);
	}
}

$GLOBALS['CI']->current_page->set_datepicker($datepicker_list);
//------------------------------ DATEPICKER END

$total_pax_count	= $total_adult_count+$total_child_count+$total_infant_count;
//First Adult is Primary and and Lead Pax
$adult_enum = $child_enum = get_enum_list('title');
$gender_enum = get_enum_list('gender');
unset($adult_enum[MASTER_TITLE]); // Master is for child so not required
unset($child_enum[MASTER_TITLE]); // Master is not supported in TBO list
$adult_title_options = generate_options($adult_enum, false, true);
$child_title_options = generate_options($child_enum, false, true);

$gender_options	= generate_options($gender_enum);
$nationality_options = generate_options($iso_country_list, array(INDIA_CODE));//FIXME get ISO CODE --- ISO_INDIA
$passport_issuing_country_options = generate_options($country_list);

//lowest year wanted
$cutoff = date('Y', strtotime('+20 years'));
//current year
$now = date('Y');
$day_options	= generate_options(get_day_numbers());
$month_options	= generate_options(get_month_names());
$year_options	= generate_options(get_years($now, $cutoff));
/**
 * check if current print index is of adult or child by taking adult and total pax count
 * @param number $total_pax		total pax count
 * @param number $total_adult	total adult count
 */
function pax_type($pax_index, $total_adult, $total_child, $total_infant)
{
	if ($pax_index <= $total_adult) {
		return 'adult';
	} elseif ($pax_index <= ($total_adult+$total_child)) {
		return 'child';
	} else {
		return 'infant';
	}
}

/**
 * check if current print index is of adult or child by taking adult and total pax count
 * @param number $total_pax		total pax count
 * @param number $total_adult	total adult count
 */
function is_adult($pax_index, $total_adult)
{
	return ($pax_index>$total_adult ?	false : true);
}

/**
 * check if current print index is of adult or child by taking adult and total pax count
 * @param number $total_pax		total pax count
 * @param number $total_adult	total adult count
 */
function is_lead_pax($pax_count)
{
	return ($pax_count == 1 ? true : false);
}

$pax_index = 1;
for($pax_index=1; $pax_index <= $total_pax_count; $pax_index++) {//START FOR LOOP FOR PAX DETAILS?>
	<div class="hide">
		<input type="hidden" name="passenger_type[]" value="<?=ucfirst(pax_type($pax_index, $total_adult_count, $total_child_count, $total_infant_count))?>">
		<input type="hidden" name="lead_passenger[]" value="<?=(is_lead_pax($pax_index) ? true : false)?>">
	</div>
	<hr>
	<h2 class="h4 text-p">Passenger <?=$pax_index?> / <?=ucfirst(pax_type($pax_index, $total_adult_count, $total_child_count, $total_infant_count))?> <?=(is_lead_pax($pax_index) ? '- Lead Pax' : '')?></h2>
	<div role="passenger">
		<div class="row">
			<div class="col-md-2">
				<div class="form-group">
					<label for="passenger-title-1">Title <sup class="text-danger">*</sup></label>
					<select class="form-control b-r-0" name="name_title[]" required="required">
					 <?php echo (is_adult($pax_index, $total_adult_count) ? $adult_title_options : $child_title_options)?>
					</select>
				</div>
			</div>
			<div class="col-md-10">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="passenger-first-name-<?=$pax_index?>">First Name <sup class="text-danger">*</sup></label>
							<input required="required" type="text" value="" id="passenger-first-name-<?=$pax_index?>" class="form-control b-r-0" placeholder="First Name" name="first_name[]">
						</div>
					</div>
					<!--<div class="col-md-4 hide">
						<div class="form-group">
							<label for="passenger-middle-name-1">Middle Name</label>
							<input type="text" id="passenger-middle-name-<?=$pax_index?>" class="form-control b-r-0" placeholder="Middle Name" name="middle_name[]">
						</div>
					</div>-->
					<div class="col-md-4">
						<div class="form-group">
							<label for="passenger-last-name-1">Last Name <sup class="text-danger">*</sup></label>
							<input required="required" type="text" value="" id="passenger-last-name-<?=$pax_index?>" class="form-control b-r-0" placeholder="Last Name" name="last_name[]">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Date of Birth <?=(is_adult($pax_index, $total_adult_count) ? '<sup class="text-danger">*</sup>' : '<sup class="text-danger">*</sup>')?></label>
					<div class="row">
						<div class="col-md-12">
							<input type="text" class="form-control b-r-0" value="" name="date_of_birth[]" readonly="readonly" <?=(is_adult($pax_index, $total_adult_count) ? 'required="required"' : 'required="required"')?> id="<?=strtolower(pax_type($pax_index, $total_adult_count, $total_child_count, $total_infant_count))?>-date-picker-<?=$pax_index?>">
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="passenger-gender-<?=$pax_index?>">Gender <sup class="text-danger">*</sup></label>
					<select class="form-control b-r-0" name="gender[]" id="passenger-gender-<?=$pax_index?>">
						<?=$gender_options;?>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="passenger-nationality-<?=$pax_index?>">Nationality <sup class="text-danger">*</sup></label>
					<select  data-lead-pax="<?=(is_lead_pax($pax_index) ? ACTIVE : INACTIVE)?>"required="required" class="form-control b-r-0" name="passenger_nationality[]" id="passenger-nationality-<?=$pax_index?>">
						<?=$nationality_options?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<small class="text-danger">(Note:Passport Details Needed For International Travel)</small>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="passenger-passport-number-<?=$pax_index?>">Passport Number <?=$pass_mand?></label>
					<input type="text" name="passenger_passport_number[]" <?=$pass_req?> class="form-control b-r-0 number" value="" id="passenger-passport-number-<?=$pax_index?>">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="passenger-passport-issuing-country-<?=$pax_index?>">Issuing Country <?=$pass_mand?></label>
					<select name="passenger_passport_issuing_country[]" <?=$pass_req?> class="form-control b-r-0" id="passenger-passport-issuing-country-<?=$pax_index?>">
						<option value="INVALIDIP">Please Select</option>
						<?=$passport_issuing_country_options?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Expiry Date <?=$pass_mand?></label>
					<div class="row">
						<div class="col-md-4">
							<select name="passenger_passport_expiry_day[]" <?=$pass_req?> class="form-control b-r-0">
								<option value="INVALIDIP">DD</option>
								<?=$day_options;?>
							</select>
						</div>
						<div class="col-md-4">
							<select name="passenger_passport_expiry_month[]" <?=$pass_req?> class="form-control b-r-0">
								<option value="INVALIDIP">MM</option>
								<?=$month_options;?>
							</select>
						</div>
						<div class="col-md-4">
							<select name="passenger_passport_expiry_year[]" <?=$pass_req?> class="form-control b-r-0">
								<option value="INVALIDIP">YYYY</option>
								<?=$year_options;?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}//END FOR LOOP FOR PAX DETAILS
?>
							<hr>
							<h1 class="h3 m-t-0"><img src="<?=$GLOBALS['CI']->template->template_images('icons/angle-arrow-down.png')?>" alt="Angle Arrow Down Icon"> Billing Details</h1>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="billing-country">Country <sup class="text-danger">*</sup></label>
										<select id="billing-country" required name="billing_country" class="form-control b-r-0">
											<?=$nationality_options?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="billing-city">City <sup class="text-danger">*</sup></label>
										<select class="form-control b-r-0" required id="billing-city" name="billing_city">
											<option>Please Select</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="billing-zipcode">Zipcode <sup class="text-danger">*</sup></label>
										<input type="text" required="required" id="billing-zipcode" class="form-control b-r-0" value="" placeholder="Zipcode" name="billing_zipcode">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="billing-email">Email <sup class="text-danger">*</sup></label>
										<input type="email" required="required" id="billing-email" class="form-control b-r-0" value="" placeholder="Email" name="billing_email">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="passenger-contact">Mobile <sup class="text-danger">*</sup></label>
										<input type="text" required="required" id="passenger-contact" class="form-control b-r-0 numeric " value="" placeholder="Contact Number" name="passenger_contact">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="billing-address-1">Address <sup class="text-danger">*</sup></label>
										<textarea id="billing-address-1" name="billing_address_1" class="form-control b-r-0"></textarea>
									</div>
								</div>
							</div>
							<?php
							//If single payment option then hide selection and select by default
							if (count($active_payment_options) == 1) {
								$payment_option_visibility = 'hide';
								$default_payment_option = 'checked="checked"';
							} else {
								$payment_option_visibility = 'show';
								$default_payment_option = '';
							}
							
							?>
							<div class="row <?=$payment_option_visibility?>">
							<?php if (in_array(PAY_NOW, $active_payment_options)) {?>
								<div class="col-md-3">
									<div class="form-group">
										<label for="payment-mode-<?=PAY_NOW?>">
											<input <?=$default_payment_option?> name="payment_method" type="radio" required="required" value="<?=PAY_NOW?>" id="payment-mode-<?=PAY_NOW?>" class="form-control b-r-0" placeholder="Payment Mode">
											Pay Now
										</label>
									</div>
								</div>
							<?php } ?>
							<?php if (in_array(PAY_AT_BANK, $active_payment_options)) {?>
								<div class="col-md-3">
									<div class="form-group">
										<label for="payment-mode-<?=PAY_AT_BANK?>">
											<input <?=$default_payment_option?> name="payment_method" type="radio" required="required" value="<?=PAY_AT_BANK?>" id="payment-mode-<?=PAY_AT_BANK?>" class="form-control b-r-0" placeholder="Payment Mode">
											Pay At Bank
										</label>
									</div>
								</div>
							<?php } ?>
							</div>
							<div class="clearfix">
								<div class="pull-left alert-wrapper hide">
									<div role="alert" class="alert alert-danger">
									  <strong>Note :</strong> <span class="alert-content"></span>
									</div>
								</div>
								<div class="pull-right text-right">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="tc" required="required"> I accept the <a href="#">Terms and Conditions</a>
										</label>
									</div>
									<button type="submit" class="btn btn-t b-r-0">Confirm</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div><!-- Passenger Details End -->
		</div>
	</div>
</section>
<script>
$(document).ready(function() {
	$('.more-flight-details').on('click', function(e) {
		e.preventDefault();
		$(this).hide();
		$('.less-flight-details').show();
		$('.more-details').removeClass('hide');
	});
	$('.less-flight-details').on('click', function(e) {
		e.preventDefault();
		$(this).hide();
		$('.more-flight-details').show();
		$('.more-details').addClass('hide');
	});
});
</script>
<?php
include_once COMMON_SHARED_JS.'/booking_script.php';
?>