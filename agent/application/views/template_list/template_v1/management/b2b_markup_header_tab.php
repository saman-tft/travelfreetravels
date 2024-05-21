<?php
$default_view = $GLOBALS ['CI']->uri->segment ( 2 );
?>
<ul class="nav nav-tabs  b2b_navul" role="tablist" id="myTab">
	<?php if (is_active_airline_module()) { ?>
	<li class="<?=(($default_view == 'b2b_airline_markup') ? 'active' : '')?>">
		 <a href="<?php echo base_url();?>management/b2b_airline_markup"><img alt="Flight Icon" src="<?php echo $GLOBALS['CI']->template->template_images('icons/'.META_AIRLINE_COURSE.'-nav-icon.png')?>"> Flight</a>
	</li>
	<?php } ?>
	<?php if (is_active_hotel_module()) { ?>
	<li class="<?=(($default_view == 'b2b_hotel_markup') ? 'active' : '')?>">
		<a href="<?php echo base_url();?>management/b2b_hotel_markup"><img alt="Hotel Icon" src="<?php echo $GLOBALS['CI']->template->template_images('icons/'.META_ACCOMODATION_COURSE.'-nav-icon.png')?>"> Hotel</a>
	</li>
	<!--<li class="<?=(($default_view == 'b2b_hotelcrs_markup') ? 'active' : '')?>">
		<a href="<?php echo base_url();?>management/b2b_hotelcrs_markup"><img alt="Hotel Icon" src="<?php echo $GLOBALS['CI']->template->template_images('icons/'.META_ACCOMODATION_COURSE.'-nav-icon.png')?>"> Hotel CRS</a>
	</li>-->
	<?php } ?>

	<?php if (is_active_bus_module()) { ?>
	<li class="<?=(($default_view == 'b2b_bus_markup') ? 'active' : '')?>">
		<a href="<?php echo base_url();?>management/b2b_bus_markup"><img alt="BUS Icon" src="<?php echo $GLOBALS['CI']->template->template_images('icons/'.META_BUS_COURSE.'-nav-icon.png')?>"> Bus</a>
	</li>
	<?php } ?>


	<?php if (is_active_transferv1_module()) { ?>
	<li class="<?=(($default_view == 'b2b_transfer_markup') ? 'active' : '')?>">
		<a href="<?php echo base_url();?>management/b2b_transfer_markup"><img alt="sightseeing" src="<?php echo $GLOBALS['CI']->template->template_images('icons/'.META_TRANSFERV1_COURSE.'-nav-icon.png')?>"> Transfers</a>
	</li>
	
	<?php } ?>


	<?php if (is_active_sightseeing_module()) { ?>
	<li class="<?=(($default_view == 'b2b_sightseeing_markup') ? 'active' : '')?>">
		<a href="<?php echo base_url();?>management/b2b_sightseeing_markup"><img alt="sightseeing" src="<?php echo $GLOBALS['CI']->template->template_images('icons/'.META_SIGHTSEEING_COURSE.'-nav-icon.png')?>"> Activities</a>
	</li>
	<?php } ?>

	<?php if (is_active_car_module()) { ?>
	
	</li>
	<?php } ?>
	
</ul>
