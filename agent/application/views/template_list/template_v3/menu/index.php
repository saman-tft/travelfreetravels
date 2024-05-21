<?php
$active_domain_modules = $this->active_domain_modules;
$selected_module = $GLOBALS['CI']->uri->segment(3);
//echo $selected_module;die;
$default_active_tab = $default_view;
// echo "<pre>"; print_r($holiday_data);exit;

/**
 * set default active tab
 *
 * @param string $module_name
 *        	name of current module being output
 * @param string $default_active_tab
 *        	default tab name if already its selected otherwise its empty
 */
function set_default_active_tab($module_name, &$default_active_tab)
{
	if (empty($default_active_tab) == true || $module_name == $default_active_tab) {
		if (empty($default_active_tab) == true) {
			$default_active_tab = $module_name; // Set default module as current active module
		}
		return 'active';
	}
}

// echo is_active_hotel_module();
?>

<div class="searcharea">
	<div class="srchinarea">
		<div class="allformst agnt-all">
			<div class="container-fluid nopad">
				<div class="tab_border">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs tabstab agent">
						<?php if (is_active_airline_module()) { ?>
							<li class="<?php echo set_default_active_tab(META_AIRLINE_COURSE, $default_active_tab) ?>"><a href="#flight" aria-controls="flight" class="flightModules" role="tab" data-toggle="tab"><span class="sprte iconcmn"><i class="fal fa-plane"></i></span><label>Flight</label></a></li>
						<?php } ?>
						<!-- is_active_hotel_module() -->
						<?php if (false) { ?>
							<li class="<?php echo set_default_active_tab(META_ACCOMODATION_COURSE, $default_active_tab) ?>"><a href="#hotel" aria-controls="hotel" id="hotelModules" role="tab" data-toggle="tab"><span class="sprte iconcmn"><i class="fal fa-building"></i></span><label>Hotel</label></a></li>
						<?php } ?>
						<!-- is_active_bus_module() -->
						<?php if (false) { ?>
							<li class="<?php echo set_default_active_tab(META_BUS_COURSE, $default_active_tab) ?>"><a href="#bus" aria-controls="bus" role="tab" data-toggle="tab"><span class="sprte iconcmn"><i class="fal fa-bus"></i></span><label>Bus</label></a></li>
						<?php } ?>
						<!-- is_active_transferv1_module() -->
						<?php if (false) : ?>
							<li class="<?php echo set_default_active_tab(META_TRANSFERV1_COURSE, $default_active_tab) ?> "><a href="#transfers" aria-controls="transfers" role="tab" id="transfersModules" data-toggle="tab"><span class="sprte iconcmn"><i class="fal fa-taxi"></i></span><label>Transfers</label></a></li>

						<?php endif; ?>
						<!-- is_active_sightseeing_module() -->
						<?php if (false) { ?>
							<li class="<?php echo set_default_active_tab(META_SIGHTSEEING_COURSE, $default_active_tab) ?> "><a href="#sightseeing" aria-controls="sightseeing" role="tab" id="activitiesModules" data-toggle="tab"><span class="sprte iconcmn"><i class="fal fa-binoculars"></i></span><label>Activities</label></a></li>
						<?php } ?>
						<!-- is_active_car_module() -->
						<?php if (false) { ?>

						<?php } ?>
						<!-- is_active_package_module() -->
						<?php if (false) { ?>
							<li class="<?php echo set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab) ?>"><a href="#holiday" id="holidaysModules" aria-controls="holiday" role="tab" data-toggle="tab"><span class="sprte iconcmn"><i class="fal fa-tree"></i></span><label>Holidays</label></a></li>
						<?php } ?>
						<!-- is_active_package_module() -->
						<?php if (false) { ?>
							<!--<li class="<?php $default_view == "comingsoon" ? 'active' : '' ?>"><a
                                href="https://www.alkhaleejtours.com/agent/index.php/user/comingsoon" class="script_data"> <span
                                    class="sprte iconcmn icimgal"><i class="fal fa-ship"></i></span><label
                                    >Cruise</label></a></li>-->
						<?php } ?>

						<?php if (is_active_package_module()) { ?>
							<!--<li class="<?= ($default_view == "coming_soon_pj") ? 'active' : '' ?>"><a
                                href="https://www.alkhaleejtours.com/agent/index.php/user/coming_soon_pj" class="script_data"> <span
                                    class="sprte iconcmn icimgal"><i class="fal fa-fighter-jet"></i></span><label
                                   >Private Jet</label></a></li>-->
						<?php } ?>
						<?php if (is_active_hotel_crs_module()) { ?>
							<?php
							if ($selected_module == "villas_apartment") {
							?>
								<li class="<?= ($selected_module == "villas_apartment") ? 'active' : '' ?>"><a href="#accommodationf" role="tab" data-toggle="tab" id="hotelModules2" class="script_data hoteltabModules2">
										<span class="sprte iconcmn icimgal"><i class="fal fa-building"></i></span><label>Villas & Apts</label></a></li>
							<?php
							} else {
							?>
								<li class="<?= ($selected_module == "villas_apartment") ? 'active' : '' ?>"><a href="#accommodation" role="tab" data-toggle="tab" id="hotelModules2" class="script_data hoteltabModules2">
										<span class="sprte iconcmn icimgal"><i class="fal fa-building"></i></span><label>Villas & Apts</label></a></li>
						<?php
							}
						} ?>
						<?php if (is_active_package_module()) { ?>

						<?php } ?>
						<?php if (is_active_package_module()) { ?>

						<?php } ?>
					</ul>
				</div>
			</div>
			<!-- Tab panes -->
			<div class="secndblak">
				<div class="container-fluid">
					<div class="tab-content custmtab">
						<?php if (is_active_airline_module()) { ?>
							<div class="tab-pane <?php echo set_default_active_tab(META_AIRLINE_COURSE, $default_active_tab) ?>" id="flight">
								<?php echo $GLOBALS['CI']->template->isolated_view('share/flight_search') ?>
							</div>
						<?php } ?>
						<?php if (is_active_hotel_module()) { ?>
							<div class="tab-pane <?php echo set_default_active_tab(META_ACCOMODATION_COURSE, $default_active_tab) ?>" id="hotel">
								<?php echo $GLOBALS['CI']->template->isolated_view('share/hotel_search') ?>
							</div>
						<?php } ?>

						<?php if (is_active_bus_module()) { ?>
							<div class="tab-pane <?php echo set_default_active_tab(META_BUS_COURSE, $default_active_tab) ?>" id="bus">
								<?php echo $GLOBALS['CI']->template->isolated_view('share/bus_search') ?>
							</div>
						<?php } ?>
						<?php if (is_active_transferv1_module()) : ?>
							<div class="tab-pane <?php echo set_default_active_tab(META_TRANSFERV1_COURSE, $default_active_tab) ?>" id="transfers">
								<?php echo $GLOBALS['CI']->template->isolated_view('transfer/transfer_search') ?>
							</div>

						<?php endif; ?>
						<?php if (is_active_sightseeing_module()) { ?>
							<div class="tab-pane <?php echo set_default_active_tab(META_SIGHTSEEING_COURSE, $default_active_tab) ?>" id="sightseeing">
								<?php echo $GLOBALS['CI']->template->isolated_view('share/activity_search') ?>

							</div>
						<?php } ?>
						<div class="tab-pane <?php if ($selected_module == "private-transfer") {
													echo "active";
												} ?> " id="privatetransfer">
							<?php echo $GLOBALS['CI']->template->isolated_view('transfer/transfer_search') ?>
						</div>
						<div class="tab-pane <?php if ($selected_module == "privatecar") {
													echo "active";
												} ?> private_car_modules" id="privatecar">
							<?php echo $GLOBALS['CI']->template->isolated_view('share/private_car') ?>
						</div>
						<?php if (is_active_car_module()) { ?>
							<div class="tab-pane <?php echo set_default_active_tab(META_CAR_COURSE, $default_active_tab) ?>" id="car">
								<?php echo $GLOBALS['CI']->template->isolated_view('share/car_search') ?>
							</div>
						<?php } ?>

						<?php if (is_active_package_module()) { ?>
							<div class="tab-pane <?php echo set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab) ?>" id="holiday">
								<?php echo $GLOBALS['CI']->template->isolated_view('share/holiday_search', $holiday_data) ?>
							</div>

						<?php } ?>
						<?php if (is_active_hotel_crs_module()) { ?>
							<?php
							if ($selected_module == "villas_apartment") {
							?>
								<div class="tab-pane taccommodationf <?php echo set_default_active_tab(META_ACCOMODATION_CRS, $default_active_tab) ?>" id="accommodationf" style="display: block;">
									<?php echo $GLOBALS['CI']->template->isolated_view('share/hotel_crs_search') ?>
								</div>
							<?php
							} else {
							?>
								<div class="tab-pane   <?php echo set_default_active_tab(META_ACCOMODATION_CRS, $default_active_tab) ?> hotel_modules2" id="accommodation" style="display: none;">
									<?php echo $GLOBALS['CI']->template->isolated_view('share/hotel_crs_search') ?>
								</div>
						<?php

							}
						} ?>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function($) {
		$("#hotelModules").on("click", function() {
			$(".hotel_modules2").hide();
			$(".hotel_modules").show();

			$("#accommodationf").hide();
		});
		$(".hoteltabModules2").on("click", function() {
			$(".hotel_modules").hide();
			$(".hotel_modules2").show();
			$("#accommodationf").show();

		});

		$("#carsModules").on("click", function() {
			$(".hotel_modules").hide();
			$(".hotel_modules2").hide();
			$("#accommodationf").hide();
		});
		$("#transfersModules").on("click", function() {
			$(".hotel_modules").hide();
			$(".hotel_modules2").hide();
			$("#accommodationf").hide();
		});
		$("#activitiesModules").on("click", function() {
			$(".hotel_modules").hide();
			$(".hotel_modules2").hide();
			$("#accommodationf").hide();
		});
		$("#holidaysModules").on("click", function() {
			$(".hotel_modules").hide();
			$(".hotel_modules2").hide();
			$("#accommodationf").hide();
		});
		$(".flightModules").on("click", function() {
			//alert("test");
			$(".taccommodationf").hide();
			$(".hotel_modules").hide();
			$(".hotel_modules2").hide();
			$("#accommodationf").hide();

		});
		$("#privatecar").on("click", function() {
			$(".hotel_modules").hide();
			$(".hotel_modules2").hide();
			$("#accommodationf").hide();
		});
		$("#privatejet").on("click", function() {
			$(".hotel_modules").hide();
			$(".hotel_modules2").hide();
			$("#accommodationf").hide();
		});
		$("#privatetransfer").on("click", function() {
			$(".hotel_modules").hide();
			$(".hotel_modules2").hide();
			$("#accomodationf").hide();
		});
		//Top Destination Functionality
		$('.htd-wrap').on('click', function(e) {
			e.preventDefault();
			var curr_destination = $('.top-des-val', this).val();
			var check_in = "<?= add_days_to_date(7) ?>";
			var check_out = "<?= add_days_to_date(10) ?>";

			$('#hotel_destination_search_name').val(curr_destination);
			$('#hotel_checkin').val(check_in);
			$('#hotel_checkout').val(check_out);
			$('#hotel_search').submit();
		});
	});
	//homepage slide show end
</script>

<?php
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');
echo $this->template->isolated_view('share/js/lazy_loader');
?>