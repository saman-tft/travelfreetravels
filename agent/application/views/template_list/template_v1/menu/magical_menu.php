<?php 
$active_domain_modules = $this->active_domain_modules;
// debug($active_domain_modules);exit;
/**
 * Need to make privilege based system
 * Privilege only for loading menu and access of the web page
 * 
 * Data loading will not be based on privilege.
 * Data loading logic will be different.
 * It depends on many parameters
 */
$menu_list = array();
if (count($active_domain_modules) > 0) {
	$any_domain_module = true;
} else {
	$any_domain_module = false;
}
$airline_module = is_active_airline_module();

$accomodation_module = is_active_hotel_module();

$bus_module = is_active_bus_module();

$package_module = is_active_package_module();

$sightseeing_module = is_active_sightseeing_module();

$car_module = is_active_car_module();
$transfer_module =is_active_transferv1_module();
// debug($transfer_module);exit;
// debug($car_module);exit;
?>
<ul class="sidebar-menu">
	<li class="header">MAIN NAVIGATION</li>
	<li class="active treeview">
		<a href="<?php echo base_url()?>">
			<i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
		</a>
	</li>
	<!-- USER ACCOUNT MANAGEMENT -->
	<li class="treeview">
		<a href="#">
			<i class="fa fa-search"></i><span> Search </span><i class="fa fa-angle-left pull-right"></i></a>
		<ul class="treeview-menu">
		<!-- USER TYPES -->
			<?php if ($airline_module) { ?>
			<li><a href="<?=base_url().'menu/index/flight/?default_view='.META_AIRLINE_COURSE?>"><i class="<?=get_arrangement_icon(META_AIRLINE_COURSE)?> "></i> <span class="hidden-xs">Flight</span></a></li>
			<?php } ?>
			<!-- $accomodation_module -->
			<?php if (false) { ?>
			<li><a href="<?=base_url().'menu/index/hotel/?default_view='.META_ACCOMODATION_COURSE?>"><i class="<?=get_arrangement_icon(META_ACCOMODATION_COURSE)?>"></i> <span class="hidden-xs">Hotel</span></a></li>
		
			<?php } ?>
			<!-- $bus_module -->
			<?php if (false) { ?>
			<li><a href="<?=base_url().'menu/index/bus/?default_view='.META_BUS_COURSE?>"><i class="<?=get_arrangement_icon(META_BUS_COURSE)?>"></i> <span class="hidden-xs">Bus</span></a></li>
			<?php } ?>
			<!-- $transfer_module -->
			<?php if(false){?>
				<li><a href="<?=base_url().'menu/index/transfers/?default_view='.META_TRANSFERV1_COURSE?>"><i class="<?=get_arrangement_icon(META_TRANSFERV1_COURSE)?>"></i> <span class="hidden-xs">Transfers</span></a></li>
					
			
			<?php }?>
			<!-- $sightseeing_module -->
			<?php if(false){?>
				<li><a href="<?=base_url().'menu/index/sightseeing/?default_view='.META_SIGHTSEEING_COURSE?>"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?> "></i> <span class="hidden-xs">Activities</span></a></li>
			<?php }?>
			<!-- $car_module -->
			<?php if(false){?>
	
			<?php }?>
			<!-- $package_module -->
			<?php if (false) { ?>
			<li><a href="<?=base_url().'menu/index/package/?default_view='.META_PACKAGE_COURSE?>"><i class="<?=get_arrangement_icon(META_PACKAGE_COURSE)?>"></i> <span class="hidden-xs">Holiday</span></a></li>
			<?php } ?>
		</ul>
	</li>
	<?php if ($any_domain_module) {?>
	<li class="treeview">
		<a href="#">
			<i class="far fa-chart-bar"></i> 
			<span> Reports </span><i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
		<!-- USER TYPES -->
			<li><a href="#"><i class="fa fa-book"></i> Booking Details</a>
				<ul class="treeview-menu">
				<?php if ($airline_module) { ?>
				<li><a href="<?php echo base_url().'report/flight/';?>"><i class="<?=get_arrangement_icon(META_AIRLINE_COURSE)?>"></i> Flight</a></li>
				<?php } ?>
				<?php if ($accomodation_module) { ?>
				<li><a href="<?php echo base_url().'report/hotel/';?>"><i class="<?=get_arrangement_icon(META_ACCOMODATION_COURSE)?>"></i> Hotel</a></li>
				<?php } ?>
				<?php if ($bus_module) { ?>
				<li><a href="<?php echo base_url().'report/bus/';?>"><i class="<?=get_arrangement_icon(META_BUS_COURSE)?>"></i> Bus</a></li>
				<?php } ?>
				<?php if($transfer_module){?>
					<li><a href="<?php echo base_url().'report/transfers_crs/';?>"><i class="<?=get_arrangement_icon(META_TRANSFERV1_COURSE)?>"></i>Transfers CRS</a></li>
					
				<?php }?>
				<?php if($sightseeing_module):?>
					<li><a href="<?php echo base_url().'report/activities_crs/';?>"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?>"></i>Activities CRS</a></li>
				<?php endif;?>
				<?php if($car_module):?>
			
				<?php endif;?>
				<?php if($package_module):?>
					<li><a href="<?php echo base_url().'report/package/';?>"><i class="<?=get_arrangement_icon(META_PACKAGE_COURSE)?>"></i>Tour</a></li>
				<?php endif;?>
				<?php if($package_module):?>
					<li><a href="<?php echo base_url().'report/villasapartment/';?>"><i class="<?=get_arrangement_icon(META_ACCOMODATION_COURSE)?>"></i>Hotel CRS</a></li>
				<?php endif;?>
				</ul>
			</li>
			<li><a href="<?php echo base_url().'management/pnr_search'?>"><i class="fa fa-search"></i> <span>PNR Search</span></a></li>
			<li><a href="<?php echo base_url().'report/flight?filter_booking_status=BOOKING_PENDING'?>"><i class="far fa-ticket"></i> <span>Pending Ticket</span></a></li>
			<li><a href="<?php echo base_url().'report/flight?daily_sales_report='.ACTIVE?>"><i class="far fa-chart-bar"></i> <span>Daily Sales Report</span></a></li>
			<li><a href="<?php echo base_url().'management/account_ledger'?>"><i class="far fa-calculator"></i> <span>Account Ledger</span></a></li>
			<li class="treeview"><a href="<?php echo base_url().'index.php/transaction/logs'?>"><i class="far fa-list-alt"></i> <span> Transaction Logs </span></a></li>
		</ul>
	</li>
	<?php
	if($airline_module || $bus_module || $sightseeing_module) {
	?>
	
	<?php } ?>
	<li class="treeview">
		<a href="#">
			<i class="fa fa-plus-square"></i> 
			<span> My Markup </span><i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
		<!-- USER TYPES -->
				<?php if ($airline_module) { ?>
				<li><a href="<?php echo base_url().'management/b2b_airline_markup/';?>"><i class="<?=get_arrangement_icon(META_AIRLINE_COURSE)?>"></i> Flight</a></li>
				<?php } ?>
				<?php if ($accomodation_module) { ?>
				<li><a href="<?php echo base_url().'management/b2b_hotel_markup/';?>"><i class="<?=get_arrangement_icon(META_ACCOMODATION_COURSE)?>"></i> Hotel</a></li>
				<!--<li><a href="<?php echo base_url().'management/b2b_hotelcrs_markup/';?>"><i class="<?=get_arrangement_icon(META_ACCOMODATION_COURSE)?>"></i> Hotel CRS</a></li>-->
				<?php } ?>
				<?php if ($bus_module) { ?>
				<li><a href="<?php echo base_url().'management/b2b_bus_markup/';?>"><i class="<?=get_arrangement_icon(META_BUS_COURSE)?>"></i> Bus</a></li>
				<?php } ?>

				<?php
								   $transfer_module=true;
								   if ($transfer_module) { ?>
				<li><a href="<?php echo base_url().'management/b2b_transfer_markup/';?>"><i class="<?=get_arrangement_icon(META_TRANSFERV1_COURSE)?>"></i>Transfers</a></li>
				
				<?php } ?>

				<?php 
								   $sightseeing_module=true;
								   if ($sightseeing_module) { ?>
				<li><a href="<?php echo base_url().'management/b2b_sightseeing_markup/';?>"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?>"></i>Activities</a></li>
				<?php } ?>
					<?php if($car_module){?>
			
			<?php }?>
				<?php if($car_module){?>
				
			<?php }?>

		</ul>
	</li>
	<?php } ?>
	
	<li class="treeview">
		<a href="#">
			<i class="fab fa-google-wallet"></i> 
			<span> Payment </span><i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
		<!-- USER TYPES -->
				<li><a href="<?php echo base_url().'management/b2b_topup'?>"><i class="fas fa-rupee-sign"></i>Balance Topup</a></li>
			<li><a href="<?php echo base_url().'management/b2b_balance_manager'?>"><i class="fas fa-rupee-sign"></i> Update Balance</a></li>
			<li><a href="<?php echo base_url().'management/b2b_credit_limit'?>"><i class="fas fa-rupee-sign"></i> Update Credit Limit</a></li>
			<li><a href="<?php echo base_url().'index.php/management/bank_account_details'?>"><i class="fas fa-university"></i> Bank Account Details</a></li>
		</ul>
	</li>
			<li><a href="<?php echo base_url().'management/b2b_topup'?>"><i class="fa fa-shopping-cart"></i> <span>Balance Topup</span></a></li>
	   <!--<li class="treeview">
            <a href="#"> <i class="fa fa-bed"></i> <span>
            Hotel CRS </span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a
                    href="<?php echo base_url().'index.php/hotel/hotel_crs_list'?>">
                    <i class="fas fa-circle-notch"></i>Hotel List & Room Allocation</a></li>

              <li class=""><a href="<?php echo base_url().'index.php/hotels/hotels_enquiry';?>">
                        <i class="fas fa-circle-notch"></i> Inquiry</a></li>

             <li class=""><a href="<?php echo base_url().'index.php/hotels/quotation_list';?>">
                        <i class="fas fa-circle-notch"></i> Quotation List</a></li>

                <li><a
                    href="<?php echo base_url().'index.php/hotel/hotel_types'?>"><i class="fas fa-circle-notch"></i>Hotel Type</a></li>

                <li><a
                    href="<?php echo base_url().'index.php/hotel/room_types'?>"><i class="fas fa-circle-notch"></i></i>Room Type</a></li>

                <li class=""><a
                    href="<?php echo base_url().'index.php/hotel/hotel_ammenities'?>"><i class="fas fa-circle-notch"></i> Hotel Amenities</a></li>
                <li class=""><a
                    href="<?php echo base_url().'index.php/hotel/room_ammenities'?>"><i class="fas fa-circle-notch"></i> Room Amenities</a></li>
            </ul>
        </li>-->
	<!-- <li><a href="<?php //echo base_url().'management/set_balance_alert'?>"><i class="fa fa-bell"></i> <span>Set Balance Alert</span></a></li>
	<li><a href="<?php //echo base_url().'management/b2b_reward_point_manager'?>"><i class="fa fa-certificate"></i> <span>Reward point</span></a></li>
	<li><a href="<?php //echo base_url().'loyalty_program/reward_wallet'?>"><i class="fa fa-shopping-cart"></i> <span>Buy Reward</span></a></li>
	<li><a href="<?php //echo base_url().'loyalty_program/product'?>"><i class="fa fa-trophy"></i> <span>Redeem Reward point</span></a></li>
		<li><a href="<?php //echo base_url().'loyalty_program/redeem_report'?>"><i class="fa fa-file"></i> <span>Redeem Reward Report</span></a></li>
		<li><a href="<?php //echo base_url().'loyalty_program/referral_report'?>"><i class="fa fa-file"></i> <span>Referral Report</span></a></li> -->
	<li><a href="<?php echo base_url().'user/domain_logo'?>"><i class="fa fa-image"></i> <span>Logo</span></a></li>
	</ul>