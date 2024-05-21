<section class="passenger-details">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="panel panel-default b-r-0 m-0">
					<div class="panel-body p-b-0">
						<h1 class="h3 m-t-0">Travel Details</h1>
						<h2 class="h4 text-p">Hotel Price</h2>
					</div>
					<table class="table">
						<tbody>
							<tr>
								<td>Hotel Price</td>
								<td class="text-right"><?=$pre_booking_params['default_currency']?> <span class="h4 text-i"><?=$pre_booking_params['markup_price_summary']['RoomPrice']?></span></td>
							</tr>
							<tr>
								<td>Taxes and Fees</td>
								<td class="text-right"><?=$pre_booking_params['default_currency']?> <span class="h4 text-i"><?=($tax_service_sum)?></span></td>
							</tr>
							<tr>
								<td><strong>Grand Total</strong></td>
								<td class="text-right"><?=$pre_booking_params['default_currency']?> <span class="h4 text-i"><?=$total_price?></span></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="panel panel-default b-r-0 m-0">
					<div class="panel-body p-t-0 p-b-0 text-center">
						<h2 class="h4 text-p text-left">Hotel Details</h2>
						<div class="clearfix">
							<div class="pull-left">
								<h3 class="h5"><b><?=$pre_booking_params['HotelName']?></b></h3>
							</div>
							<div class="pull-right">
								<h5>
									<span class="rating-no">
										<?=print_star_rating($pre_booking_params['StarRating'])?>
									</span>
								</h5>
							</div>
						</div>
					</div>
					<table class="table">
						<tbody>
							<tr>
								<td>Check-In Date</td>
								<td class="text-right"><b><?=app_friendly_day($search_data['from_date'])?></b></td>
							</tr>
							<tr>
								<td>Check-Out Date</td>
								<td class="text-right"><b><?=app_friendly_day($search_data['to_date'])?></b></td>
							</tr>
							<tr>
								<td>Night(s)</td>
								<td class="text-right"><b><?=$search_data['no_of_nights']?></b></td>
							</tr>
							<tr>
								<td>Total Room(s)</td>
								<td class="text-right"><b><?=$search_data['room_count']?></b></td>
							</tr>
							<tr class="hide">
								<td><a href="#">More Details</a></td>
								<td class="text-right"><a href="#">Fare Breakup</a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-8">
				<div class="panel panel-default b-r-0">
					<div class="panel-body">
						<form action="<?=base_url().'index.php/hotel/pre_booking/'.$search_data['search_id']?>" method="POST" autocomplete="off">
							<div class="hide">
								<?php $dynamic_params_url = serialized_data($pre_booking_params);?>
								<input type="hidden" required="required" name="token"		value="<?=$dynamic_params_url;?>" />
								<input type="hidden" required="required" name="token_key"	value="<?=md5($dynamic_params_url);?>" />
								<input type="hidden" required="required" name="op"			value="book_flight">
								<input type="hidden" required="required" name="booking_source"		value="<?=$booking_source?>" readonly="readonly">
							</div>
							<div class="clearfix">
								<div class="pull-left">
									<h1 class="h3 m-t-0"><img src="<?=$GLOBALS['CI']->template->template_images('icons/angle-arrow-down.png')?>" alt="Angle Arrow Down Icon"> Passenger Details</h1>
								</div>
								<div class="pull-right">
									<a href="#" class="btn btn-p b-r-0">Login</a>
								</div>
							</div>
<?php
/**
 * Collection field name 
 */
//Title, Firstname, Middlename, Lastname, Phoneno, Email, PaxType, LeadPassenger, Age, PassportNo, PassportIssueDate, PassportExpDate
$total_adult_count	= array_sum($search_data['adult_config']);
$total_child_count	= array_sum($search_data['child_config']);
//------------------------------ DATEPICKER START
$i = 1;
$datepicker_list = array();
if ($total_adult_count > 0) {
	for ($i=1; $i<=$total_adult_count; $i++) {
		$datepicker_list[] = array('adult-date-picker-'.$i, ADULT_DATE_PICKER);
	}
}

if ($total_child_count > 0) {
	for ($i=$i; $i<=($total_child_count+$total_adult_count); $i++) {
		$datepicker_list[] = array('child-date-picker-'.$i, CHILD_DATE_PICKER);
	}
}
$GLOBALS['CI']->current_page->set_datepicker($datepicker_list);
//------------------------------ DATEPICKER END


$total_pax_count	= $total_adult_count+$total_child_count;
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
function is_adult($total_pax, $total_adult)
{
	return ($total_pax>$total_adult ?	false : true);
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
		<input type="hidden" name="passenger_type[]" value="<?=(is_adult($pax_index, $total_adult_count) ? 1 : 2)?>">
		<input type="hidden" name="lead_passenger[]" value="<?=(is_lead_pax($pax_index) ? true : false)?>">
	</div>
	<hr>
	<h2 class="h4 text-p">Passenger <?=$pax_index?> / <?=(is_adult($pax_index, $total_adult_count) ? 'Adult' : 'Child')?> <?=(is_lead_pax($pax_index) ? '- Lead Pax' : '')?></h2>
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
							<input required="required" type="text" id="passenger-first-name-<?=$pax_index?>" class="form-control b-r-0" placeholder="First Name" name="first_name[]">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="passenger-middle-name-1">Middle Name</label>
							<input type="text" id="passenger-middle-name-<?=$pax_index?>" class="form-control b-r-0" placeholder="Middle Name" name="middle_name[]">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="passenger-last-name-1">Last Name <sup class="text-danger">*</sup></label>
							<input required="required" type="text" id="passenger-last-name-<?=$pax_index?>" class="form-control b-r-0" placeholder="Last Name" name="last_name[]">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Date of Birth <sup class="text-danger">*</sup></label>
					<div class="row">
						<div class="col-md-12">
							<input type="text" class="form-control b-r-0" name="date_of_birth[]" readonly="readonly" <?=(is_adult($pax_index, $total_adult_count) ? '' : 'required="required"')?> id="<?php echo is_adult($pax_index, $total_adult_count) ? 'adult' : 'child'?>-date-picker-<?=$pax_index?>">
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
					<label for="passenger-passport-number-<?=$pax_index?>">Passport Number</label>
					<input type="text" name="passenger_passport_number[]" class="form-control b-r-0" id="passenger-passport-number-<?=$pax_index?>">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="passenger-passport-issuing-country-<?=$pax_index?>">Issuing Country</label>
					<select name="passenger_passport_issuing_country[]" class="form-control b-r-0" id="passenger-passport-issuing-country-<?=$pax_index?>">
						<option>Please Select</option>
						<?=$passport_issuing_country_options?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Expiry Date</label>
					<div class="row">
						<div class="col-md-4">
							<select name="passenger_passport_expiry_day[]" class="form-control b-r-0">
								<option value="none">DD</option>
								<?=$day_options;?>
							</select>
						</div>
						<div class="col-md-4">
							<select name="passenger_passport_expiry_month[]" class="form-control b-r-0">
								<option value="none">MM</option>
								<?=$month_options;?>
							</select>
						</div>
						<div class="col-md-4">
							<select name="passenger_passport_expiry_year[]" class="form-control b-r-0">
								<option value="none">YYYY</option>
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
										<input type="text" required="required" id="billing-zipcode" class="form-control b-r-0" placeholder="Zipcode" name="billing_zipcode">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="billing-email">Email <sup class="text-danger">*</sup></label>
										<input type="email" required="required" id="billing-email" class="form-control b-r-0" placeholder="Email" name="billing_email">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="passenger-contact">Mobile <sup class="text-danger">*</sup></label>
										<input type="text" required="required" id="passenger-contact" class="form-control b-r-0 numeric " placeholder="Contact Number" name="passenger_contact">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="billing-address-1">Address <sup class="text-danger">*</sup></label>
										<textarea id="billing-address-1" required="required" name="billing_address_1" class="form-control b-r-0"></textarea>
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
			</div>
		</div>
	</div>
</section>
<?php
include_once COMMON_SHARED_JS.'/booking_script.php';
?>