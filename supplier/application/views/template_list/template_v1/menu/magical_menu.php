<?php
// $this->CI = &get_instance ();
$active_domain_modules = $this->active_domain_modules;
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

// debug($any_domain_module	);
// die;
$sightseen_module = is_active_sightseeing_module();
$car_module = is_active_car_module();
$transferv1_module = is_active_transferv1_module();
$bb='b2b';
$bc='b2c';
$corp='corporate';
$subadmin='sub admin';
$b2b = is_active_module($bb);
$b2c = is_active_module($bc);
$corporate= is_active_module($corp);
$subadmin= is_active_module($subadmin);


// debug($subadmin);
// die;
$subagent='sub agent';
$subcorp='sub corporate';
$subagent=is_active_module($subagent);
$subcorp=is_active_module($subcorp);
//checking social login status 
$social_login = 'facebook';
$social = is_active_social_login($social_login);
//echo "ela".$accomodation_module;exit;
$accomodation_module = 1;

 
?>
<ul class="sidebar-menu" id="magical-menu">
        <?php if(check_user_previlege('p1')):?>
	<li class="treeview">
		<a href="<?php echo base_url()?>">
			<i class="far fa-tachometer-alt"></i> <span>Dashboard</span> </a>
	</li>
	 <?php endif; ?>
	<?php 

	// debug(is_domain_user());exit();
	if(is_domain_user() == false) { // ACCESS TO ONLY PROVAB ADMIN ?>
	<li class="treeview">
		<a href="#">
		<i class="far fa-wrench"></i> <span>Management</span> <i class="far fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
			<li><a href="<?php echo base_url().'index.php/user/user_management'?>"><i class="far fa-user"></i> User</a></li>
			<li><a href="<?php echo base_url().'index.php/user/domain_management'?>"><i class="far fa-laptop"></i> Domain</a></li>
			<li><a href="<?php echo base_url().'index.php/module/module_management'?>"><i class="far fa-sitemap"></i> Master Module</a></li>
		</ul>
	</li>  

	
	<li class="treeview">
		<a href="<?php echo base_url().'index.php/private_management/event_logs'?>">
			<i class="far fa-shield"></i> 
			<span> Event Logs </span>
		</a>
	</li>
	<?php } else if((is_domain_user() == true)) {
		// ACCESS TO ONLY DOMAIN ADMIN
	?>



 <?php  if(true){?>
<li class="treeview">
		<a href="#">
			<i class="fas fa-chart-bar"></i> 
			<span> Reports </span><i class="far fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">

		<!-- <li><a href="#"><i class="far fa-circle"></i> B2C</a> -->
				<!-- <ul class="treeview-menu"> -->


				
				<?php if (check_user_previlege('p120')) { ?>
				   <li class="<?php if($fun_name=="holiday") echo "active";?>" ><a href="<?php echo base_url().'index.php/report/holiday/';?>"><i class="<?=get_arrangement_icon(META_PACKAGE_COURSE)?>"></i> Tour CRS</a></li>
					<?php } ?>	
				
				<?php if (check_user_previlege('p22')) { ?>
				    <li><a href="<?php echo base_url() . 'index.php/report/b2b_activities_report/'; ?>"><i class="<?= get_arrangement_icon(META_SIGHTSEEING_COURSE) ?>"></i> Activities CRS</a></li>
					<?php } ?>		

				<?php if (check_user_previlege('p21')) { ?>
				   <!-- <li class="<?php if($fun_name=="holiday") echo "active";?>" ><a href="<?php echo base_url().'index.php/report/b2b_transfers_report/';?>"><i class="fa fa-taxi"></i> Transfers CRS</a></li> -->
				<?php } ?>
				 <?php if(check_user_previlege('p19')):?>
<li><a href="<?php echo base_url() . 'index.php/report/b2c_hotelcrs_report/'; ?>"><i class="far fa-bed"></i>Hotel CRS</a></li>
     <?php endif; ?>
                                 <?php if(check_user_previlege('p21')): ?>
    <li><a href="<?php echo base_url() . 'index.php/report/b2b_transfers_report/'; ?>"><i class="<?= get_arrangement_icon(META_TRANSFERV1_COURSE) ?>"></i> Transfer CRS</a></li>
	 <?php endif; 
                            ?>
				<li class=""><a href="<?php echo base_url().'index.php/supplier_management/payment_details'?>">
			<i class="fa fa-circle"></i> Payment Details </a></li>	
				<?php if (check_supplier_previlege('3')) { ?>
				<!-- <li><a href="<?php echo base_url().'index.php/report/b2c_crs_hotel_report/';?>"><i class="fa fa-bed"></i> Hotel CRS</a></li> -->
				<?php } ?>
				
				


			<!-- 	</ul> -->
			<!-- </li> -->



			<li class="hide"><a href="#"><i class="far fa-circle"></i> Agent</a>
				<ul class="treeview-menu">
				 
   
				<?php if ($sightseen_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/b2b_activities_report/';?>"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?>"></i> Activities</a></li>
				<?php } ?>
 
				<?php if ($car_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/b2b_car_report/';?>"><i class="<?=get_arrangement_icon(META_CAR_COURSE)?>"></i> Car</a></li>
				<?php } ?>
 
				<?php if($package_module):?>
					<li><a href="<?php echo base_url().'index.php/report/b2b_holiday/';?>"><i class="<?=get_arrangement_icon(META_PACKAGE_COURSE)?>"></i>Tours</a></li>
				<?php endif;?>
			 	<li><a href="<?php echo base_url() . 'index.php/report/b2b_hotelcrs_report/'; ?>"><i class="far fa-bed"></i>Hotel_crs</a></li>
				</ul>
			</li>
 
		</ul>
	</li>		
 <?php }  ?>



	<!-- USER ACCOUNT MANAGEMENT -->
	<?php if(check_user_previlege('1')):?>
	<li class="treeview">
		<a href="#">
			<i class="far fa-user"></i> 
			<span> Users </span><i class="far fa-angle-left pull-right"></i></a>
		<ul class="treeview-menu">
		<!-- USER TYPES -->
		<?php if(check_user_previlege('2')):?>
			<?php if($b2c){	?>
			<li><a href="<?php echo base_url().'index.php/user/b2c_user?filter=user_type&q='.B2C_USER;?>"><i class="far fa-circle"></i> B2C</a>
				<ul class="treeview-menu">
				<?php if(check_user_previlege('3')):?>
				<li><a href="<?php echo base_url().'index.php/user/b2c_user?filter=user_type&q='.B2C_USER.'&user_status='.ACTIVE;?>"><i class="far fa-check"></i> Active</a></li>
			<?php endif;?>
			<?php if(check_user_previlege('4')):?>
				<li><a href="<?php echo base_url().'index.php/user/b2c_user?filter=user_type&q='.B2C_USER.'&user_status='.INACTIVE;?>"><i class="far fa-times"></i> InActive</a></li>
			<?php endif;?>

			<?php if(check_user_previlege('5')):?>
				<li><a href="<?php echo base_url().'index.php/user/get_logged_in_users?filter=user_type&q='.B2C_USER;?>"><i class="far fa-circle"></i> Logged In User</a></li>
			<?php endif;?>
				</ul>
			</li>
			<?php } ?>
		<?php endif;?>
		<?php if(check_user_previlege('6')):?>
			<?php if($b2b){	?>
			<li><a href="<?php echo base_url().'index.php/user/b2b_user?filter=user_type&q='.B2B_USER ?>"><i class="far fa-circle"></i> Agents</a>
				<ul class="treeview-menu">
				<?php if(check_user_previlege('7')):?>
				<li><a href="<?php echo base_url().'index.php/user/b2b_user?user_status='.ACTIVE;?>"><i class="far fa-check"></i> Active</a></li>
			<?php endif;?>
			<?php if(check_user_previlege('8')):?>
				<li><a href="<?php echo base_url().'index.php/user/b2b_user?user_status='.INACTIVE;?>"><i class="far fa-times"></i> InActive</a></li>
			<?php endif;?>

			<?php if(check_user_previlege('9')):?>
				<li><a href="<?php echo base_url().'index.php/user/get_logged_in_users?filter=user_type&q='.B2B_USER;?>"><i class="far fa-circle"></i> Logged In User</a></li>
				
<?php endif;?>
				</ul>
			</li>
			<?php }?>
		<?php endif;?>
		<?php if(check_user_previlege('10')):?>
			<?php if($subagent){	?>
			 <li><a href="<?php echo base_url().'index.php/user/subb2b_user?filter=user_type&q='.SUB_AGENT ?>"><i class="far fa-circle"></i> Sub Agents</a> 
				<ul class="treeview-menu">
				<?php if(check_user_previlege('11')):?>
				<li><a href="<?php echo base_url().'index.php/user/subb2b_user?pl='.ACTIVE.'&user_status='.ACTIVE;?>"><i class="far fa-check"></i> Active</a></li>
			<?php endif;?>
			<?php if(check_user_previlege('12')):?>
				<li><a href="<?php echo base_url().'index.php/user/subb2b_user?pl='.ACTIVE.'&user_status='.INACTIVE;?>"><i class="far fa-times"></i> InActive</a></li>
			<?php endif;?>
			<?php if(check_user_previlege('13')):?>
				<li><a href="<?php echo base_url().'index.php/user/get_logged_in_users?filter=user_type&q='.SUB_AGENT;?>"><i class="far fa-circle"></i> Logged In User</a></li>
			<?php endif;?>
				</ul>
			</li>
			<?php }?>
<?php endif;?>
			

     

		</ul>
	</li>

			<?php endif;?>	

	<?php if ($any_domain_module) {?> 
	<?php if(check_user_previlege('20')):?>
	<li class="treeview">
		<a href="#">
			<i class="fas fa-chart-bar"></i> 
			<span> Reports </span><i class="far fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
		<!-- USER TYPES -->
		<?php if(check_user_previlege('21')):?>
			<li><a href="#"><i class="far fa-circle"></i> B2C</a>
				<ul class="treeview-menu">
				<?php if(check_user_previlege('22')):?>
				<?php if ($airline_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/b2c_flight_report/';?>"><i class="far fa-plane"></i> Flight</a></li>
				<?php } ?>
			<?php endif;?>
			<?php if(check_user_previlege('23')):?>
				<?php if ($accomodation_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/b2c_hotel_report/';?>"><i class="far fa-bed"></i> Hotel</a></li>
				<?php } ?>
			<?php endif;?>

			<?php if ($accomodation_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/b2c_crs_hotel_report/';?>"><i class="fa fa-bed"></i> Hotel CRS</a></li>
				<?php } ?>

				
			<?php if(check_user_previlege('24')):?>
				<?php if ($bus_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/b2c_bus_report/';?>"><i class="<?=get_arrangement_icon(META_BUS_COURSE)?>"></i> Bus</a></li>
				<?php } ?>
				<?php endif;?>
				<?php if(check_user_previlege('25')):?>
				<?php
				   if($transferv1_module){ ?>
				   <li><a href="<?php echo base_url().'index.php/report/b2c_transfers_report/';?>"><i class="<?=get_arrangement_icon(META_TRANSFERV1_COURSE)?>"></i> Transfer</a></li>


				     <li class="<?php if($fun_name=="holiday") echo "active";?>" ><a href="<?php echo base_url().'index.php/report/b2c_transfers_crs_report/';?>"><i class="<?=get_arrangement_icon(META_PACKAGE_COURSE)?>"></i> Transfers CRS</a></li>

				<?php    } 
				?>
			<?php endif;?>
<?php if(check_user_previlege('26')):?>
				<?php
				   if($sightseen_module){ ?>
				   <li><a href="<?php echo base_url().'index.php/report/b2c_activities_report/';?>"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?>"></i> Activities</a></li>


				     <li class="<?php if($fun_name=="holiday") echo "active";?>" ><a href="<?php echo base_url().'index.php/report/b2c_package_report/';?>"><i class="<?=get_arrangement_icon(META_PACKAGE_COURSE)?>"></i> Activity CRS</a></li>

				<?php    } 
				?>
			<?php endif;?>
				<?php if ($car_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/b2c_car_report/';?>"><i class="<?=get_arrangement_icon(META_CAR_COURSE)?>"></i> Car</a></li>
				<?php } ?>
				<!-- <?php if(check_user_previlege('27')):?>
				<?php if ($package_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/b2c_holiday_report/';?>"><i class="<?=get_arrangement_icon(META_PACKAGE_COURSE)?>"></i> Holiday</a></li>
				<?php } ?>
			<?php endif;?> -->
				
                                    
				<li><a href="<?php echo base_url().'index.php/report/holiday/';?>"><i class="fal fa-umbrella-beach"></i> Tours</a></li>
				
				</ul>
			</li>
		<?php endif;?>
		<?php if(check_user_previlege('28')):?>
           <li><a href="#"><i class="far fa-circle"></i> Agent</a>
				<ul class="treeview-menu">
				<?php if(check_user_previlege('29')):?>
				<?php if ($airline_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/b2b_flight_report/';?>"><i class="far fa-plane"></i> Flight</a></li>
				<?php } ?>
			<?php endif;?>

			<?php if(check_user_previlege('30')):?>
				<?php if ($accomodation_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/b2b_hotel_report/';?>"><i class="far fa-bed"></i> Hotel</a></li>
				<?php } ?>

			<?php endif; ?>

			<?php if(check_user_previlege('31')):?>
				<?php if ($bus_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/b2b_bus_report/';?>"><i class="<?=get_arrangement_icon(META_BUS_COURSE)?>"></i> Bus</a></li>
				<?php } ?>

			<?php endif;?>
<?php if(check_user_previlege('32')):?>
				<?php if ($transferv1_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/b2b_transfers_report/';?>"><i class="<?=get_arrangement_icon(META_TRANSFERV1_COURSE)?>"></i>Transfers</a></li>
				<?php } ?>

<?php endif;?>
			<?php if(check_user_previlege('33')):?>	
				<?php if ($sightseen_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/b2b_activities_report/';?>"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?>"></i> Activities</a></li>
				<?php } ?>

			<?php endif;?>
				<?php if ($car_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/b2b_car_report/';?>"><i class="<?=get_arrangement_icon(META_CAR_COURSE)?>"></i> Car</a></li>
				<?php } ?>

				 <?php if(check_user_previlege('34')):?>
				<?php if($package_module):?>
					<li><a href="<?php echo base_url().'index.php/report/b2b_holiday/';?>"><i class="<?=get_arrangement_icon(META_PACKAGE_COURSE)?>"></i>Tours</a></li>
				<?php endif;?>
			<?php endif;?> 
				</ul>
			</li>
<?php endif;?>
<?php if(check_user_previlege('35')):?>
			<li><a href="#"><i class="far fa-circle"></i> Corporate</a>
				<ul class="treeview-menu">
				<?php if(check_user_previlege('36')):?>
				<?php if ($airline_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/corporate_flight_report/';?>"><i class="fa fa-plane"></i> Flight</a></li>
				<?php } ?>
			<?php endif;?>

			<?php if(check_user_previlege('37')):?>
				<?php if ($accomodation_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/corporate_hotel_report/';?>"><i class="fa fa-bed"></i> Hotel</a></li>
				<?php } ?>
			<?php endif;?>

			<?php if(check_user_previlege('38')):?>
				<?php if ($bus_module) { ?>
				<li><a href="<?php echo base_url().'index.php/report/corporate_bus_report/';?>"><i class="<?=get_arrangement_icon(META_BUS_COURSE)?>"></i> Bus</a></li>
				<?php } ?>
			<?php endif;?>

			<?php if(check_user_previlege('37')):?>
				<?php if ($accomodation_module) { ?>
				<!-- <li><a href="<?php echo base_url().'index.php/report/corporate_crs_hotel_report/';?>"><i class="fa fa-bed"></i> Hotel CRS</a></li> -->
				<?php } ?>
				<?php endif;?>

				<?php if(check_user_previlege('39')):?>
				<?php
				   if($transferv1_module){ ?>
				   <li><a href="<?php echo base_url().'index.php/report/corporate_transfers_report/';?>"><i class="<?=get_arrangement_icon(META_TRANSFERV1_COURSE)?>"></i> Transfer</a></li>

				<?php    } 
				?>
<?php endif;?>

<?php if(check_user_previlege('40')):?>
				<?php
				   if($sightseen_module){ ?>
				   <li><a href="<?php echo base_url().'index.php/report/corporate_sightseeing_report/';?>"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?>"></i> Activities</a></li>

				<?php    } 
				?>

			<?php endif;?>
				<?php if (!$car_module) { ?>
				<!-- <li><a href="<?php echo base_url().'index.php/report/corporate_car_report/';?>"><i class="<?=get_arrangement_icon(META_CAR_COURSE)?>"></i> Car</a></li> -->
				<?php } ?>
				</ul>
			</li>

<?php endif;?>
		</ul>
		






		 
	</li>
<?php endif;?>
	<?php if(check_user_previlege('46')):?>
	<li class="treeview">
		<a href="#">
		<i class="far fa-money-bill"></i> <span>Account</span> <i class="far fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
		<?php if(check_user_previlege('47')):?>
			<li><a href="<?php echo base_url().'private_management/credit_balance'?>"><i class="far fa-circle"></i> Credit Balance</a></li>
		<?php endif;?>
		<?php if(check_user_previlege('48')):?>
			<li><a href="<?php echo base_url().'private_management/debit_balance'?>"><i class="far fa-circle"></i> Debit Balance</a></li>
		<?php endif;?>
		</ul>
	</li>
<?php endif;?>

	<?php if($b2b) {?>
<?php if(check_user_previlege('49')):?>
		<li class="treeview">
			<a href="#">
			<i class="far fa-briefcase"></i> <span>Commission</span> <i class="far fa-angle-left pull-right"></i>
			</a>
			<ul class="treeview-menu">
			<?php if(check_user_previlege('50')):?>
				<li><a href="<?php echo base_url().'index.php/management/agent_commission?default_commission='.ACTIVE;?>"><i class="far fa-circle"></i> Default Commission</a></li>
			<?php endif;?>
			<?php if(check_user_previlege('51')):?>
				<li><a href="<?php echo base_url().'index.php/management/agent_commission'?>"><i class="far fa-circle"></i> Agent's Commission</a></li>
			<?php endif;?>
			</ul>
		</li>
		<?php endif;?>
	<?php }?>

	<?php 
	if(check_user_previlege('52')): ?>
	<li class="treeview">
		<a href="#">
			<i class="far fa-plus-square"></i> 
			<span> Markup </span><i class="far fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
		<!-- Markup TYPES -->
		<?php if($b2c) {?>
		<?php 
	if(check_user_previlege('53')): ?>
			<li><a href="#"><i class="far fa-circle"></i> B2C</a>
				<ul class="treeview-menu">
				<?php 
	if(check_user_previlege('54')): ?>
				<?php if ($airline_module) { ?>
				<li><a href="<?php echo base_url().'index.php/management/b2c_airline_markup/';?>"><i class="<?=get_arrangement_icon(META_AIRLINE_COURSE)?>"></i> Flight</a></li>
				<?php } ?>
			<?php endif;?>
			<?php 
	if(check_user_previlege('55')): ?>
				<?php if ($accomodation_module) { ?>
				<li><a href="<?php echo base_url().'index.php/management/b2c_hotel_markup/';?>"><i class="<?=get_arrangement_icon(META_ACCOMODATION_COURSE)?>"></i> Hotel</a></li>
				<?php } ?>
				<?php endif;?>
				<?php 
	if(check_user_previlege('56')): ?>
				<?php if ($bus_module) { ?>
				<li><a href="<?php echo base_url().'index.php/management/b2c_bus_markup/';?>"><i class="<?=get_arrangement_icon(META_BUS_COURSE)?>"></i> Bus</a></li>
				<?php }  ?>
<?php endif;?>
<?php 
	if(check_user_previlege('57')): ?>
				<?php
				   if($transferv1_module){ ?>
				   <li><a href="<?php echo base_url().'index.php/management/b2c_transfer_markup/';?>"><i class="<?=get_arrangement_icon(META_TRANSFERV1_COURSE)?>"></i> Transfers</a></li>

				<?php    } 
				?>
<?php endif;?>
<?php 
	if(check_user_previlege('58')): ?>
				<?php
				   if($sightseen_module){ ?>
				   <li><a href="<?php echo base_url().'index.php/management/b2c_sightseeing_markup/';?>"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?>"></i> Activities</a></li>

				<?php    } 
				?>
				<?php endif;?>

				<?php
				   if($car_module){ ?>
				   <li><a href="<?php echo base_url().'index.php/management/b2c_car_markup/';?>"><i class="<?=get_arrangement_icon(META_CAR_COURSE)?>"></i> Car</a></li>

				<?php    } 
				?>
<?php 
	if(check_user_previlege('59')): ?>
				<?php if ($package_module) { ?>
			<!-- <li><a href="<?php echo base_url().'index.php/private_management/package_domain_markup'?>"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?>"></i>Holidays</a></li> -->
			<?php } ?>
			<?php endif;?>
				</ul>
			</li>
			<?php endif;?>
			<?php } 

	if(check_user_previlege('60')): 
			if($b2b){	?>
			<li><a href="#"><i class="far fa-circle"></i> B2B</a>
				<ul class="treeview-menu">
				<?php if(check_user_previlege('61')): 
				 if ($airline_module) { ?>
				<li><a href="<?php echo base_url().'index.php/management/b2b_airline_markup/';?>"><i class="<?=get_arrangement_icon(META_AIRLINE_COURSE)?>"></i> Flight</a></li>
				<?php } ?>
			<?php endif;?>
			<?php if(check_user_previlege('62')): 
				  if ($accomodation_module) { ?>
				<li><a href="<?php echo base_url().'index.php/management/b2b_hotel_markup/';?>"><i class="<?=get_arrangement_icon(META_ACCOMODATION_COURSE)?>"></i> Hotel</a></li>
				<?php } ?>
				<?php endif;?>
				<?php if(check_user_previlege('63')): 
				 if ($bus_module) { ?>
				<li><a href="<?php echo base_url().'index.php/management/b2b_bus_markup/';?>"><i class="<?=get_arrangement_icon(META_BUS_COURSE)?>"></i> Bus</a></li>
				<?php } ?>
<?php endif;?>
<?php if(check_user_previlege('64')):
				 if ($transferv1_module) { ?>
				<li><a href="<?php echo base_url().'index.php/management/b2b_transfer_markup/';?>"><i class="<?=get_arrangement_icon(META_TRANSFERV1_COURSE)?>"></i>Transfers</a></li>
				<?php } ?>
<?php endif;?>
<?php if(check_user_previlege('65')):
				
				   if($sightseen_module){ ?>
				   <li><a href="<?php echo base_url().'index.php/management/b2b_sightseeing_markup/';?>"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?>"></i> Activities</a></li>

				<?php    } 
				?>
				<?php endif;?>
				<?php
				   if($car_module){ ?>
				   <li><a href="<?php echo base_url().'index.php/management/b2b_car_markup/';?>"><i class="<?=get_arrangement_icon(META_CAR_COURSE)?>"></i> Car</a></li>

				<?php    } 

				?>
				<?php if(check_user_previlege('66')):
				if ($package_module) { ?>
			<!-- <li><a href="<?php echo base_url().'index.php/private_management/b2bpackage_domain_markup'?>"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?>"></i>Holidays</a></li> -->
			<?php } ?>
			<?php endif;?>
				</ul>
			</li>
			<?php } ?>
<?php endif;?>
<?php if(check_user_previlege('67')): 
			if($corporate){	?>
<li><a href="#"><i class="far fa-circle"></i> Corporate</a>
				<ul class="treeview-menu">
				<?php if(check_user_previlege('68')): 
				 if ($airline_module) { ?>
				<li><a href="<?php echo base_url().'index.php/management/corporate_airline_markup/';?>"><i class="<?=get_arrangement_icon(META_AIRLINE_COURSE)?>"></i> Flight</a></li>
				<?php } ?>
				<?php endif;?>
				<?php if(check_user_previlege('69')): 
				 if ($accomodation_module) { ?>
				<li><a href="<?php echo base_url().'index.php/management/corporate_hotel_markup/';?>"><i class="<?=get_arrangement_icon(META_ACCOMODATION_COURSE)?>"></i> Hotel</a></li>
				<?php } ?>
				<?php endif;?>
				<?php if(check_user_previlege('70')): 
				 if ($bus_module) { ?>
				<li><a href="<?php echo base_url().'index.php/management/corporate_bus_markup/';?>"><i class="<?=get_arrangement_icon(META_BUS_COURSE)?>"></i> Bus</a></li>
				<?php } ?>
<?php endif;?>
<?php if(check_user_previlege('71')): 
				 if ($transferv1_module) { ?>
				<li><a href="<?php echo base_url().'index.php/management/corporate_transfer_markup/';?>"><i class="<?=get_arrangement_icon(META_TRANSFERV1_COURSE)?>"></i>Transfers</a></li>
				<?php } ?>
<?php endif;?>
<?php if(check_user_previlege('72')): 
				
				   if($sightseen_module){ ?>
				   <li><a href="<?php echo base_url().'index.php/management/corporate_sightseeing_markup/';?>"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?>"></i> Activities</a></li>

				<?php    } 
				?>
				<?php endif;?>
				<?php
				   if($car_module){ ?>
				   <li><a href="<?php echo base_url().'index.php/management/corporate_car_markup/';?>"><i class="<?=get_arrangement_icon(META_CAR_COURSE)?>"></i> Car</a></li>

				<?php    } 

				?>
				<?php if(check_user_previlege('73')): 
				if (!$package_module) { ?>
			<li><a href="<?php echo base_url().'index.php/private_management/corporatepackage_domain_markup'?>"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?>"></i>Holidays</a></li>
			<?php } ?>
			<?php endif;?>
				</ul>
			</li>

			<?php
}
			?>
		<?php endif;?>
		</ul>
	</li>
	<?php
endif;
	?>
	<?php } ?>
	<?php if(check_user_previlege('74')):?>
	<li class="treeview">
		<a href="<?php echo base_url().'index.php/management/gst_master'?>">
			<i class="fa fa-globe"></i> 
			<span> GST Master </span>
		</a>
	</li>
	<?php if(check_user_previlege('75')):?>
	<?php if($b2b){	?>
	<li class="treeview">
		<a href="#">
			<i class="far fa-money-bill"></i> 
			<span> Master Balance Manager </span><i class="far fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
		<!-- USER TYPES -->
			<!--<li><a href="<?php echo base_url().'index.php/management/master_balance_manager'?>"><i class="far fa-circle-o"></i> API</a></li>-->
			<?php if(check_user_previlege('76')):?>
			<li><a href="<?php echo base_url().'index.php/management/b2b_balance_manager'?>"><i class="far fa-circle"></i> B2B</a></li>
			<?php
endif;
	?>
	<?php if(check_user_previlege('77')):?>
			<li><a href="<?php echo base_url().'index.php/management/corporate_balance_manager'?>"><i class="far fa-circle"></i> Corporate</a></li>
			<?php
endif;
	?>
		</ul>
		<?php if(check_user_previlege('78')):?>
		 <ul class="treeview-menu">
			<li><a href="<?php echo base_url().'index.php/management/b2b_credit_request'?>"><i class="far fa-circle"></i> B2B Credit Limt Requests</a></li>
		</ul> 
		<?php
endif;
	?>
	</li>
	
		<?php }

		endif;
		endif;

		?>
 <?php if(check_user_previlege('p134')){ ?>
<li class="treeview ">
      <a href="#"> <i class="fa fa-hourglass"></i> <span>
      Activities Management </span><i class="fa fa-angle-left pull-right"></i>
      </a>
      <ul class="treeview-menu">
         <!-- USER TYPES -->
         <!-- <li><a href="<?php echo base_url().'index.php/activity/enquiries'?>"><i
            class="fas fa-circle-notch"></i> View Excursion Enquiries </a></li> -->
           
         <li><a
            href="<?php echo base_url().'index.php/activity/add_with_price'?>"><i
            class="fas fa-circle-notch"></i> Add Activities </a></li>
          
         <!-- <li><a
            href="<?php echo base_url().'index.php/activity/view_with_price'?>"><i
            class="fas fa-circle-notch"></i> View Excursion </a></li> -->
        
         <li><a
            href="<?php echo base_url().'index.php/activity/cancellation_policy'?>"><i
            class="fas fa-circle-notch"></i> Cancellation Policy</a></li>
          
         <li><a href="<?php echo base_url().'index.php/activity/view_with_price'?>"><i class="fa fa-circle-notch"></i>Excursion List</a></li>
        
         <li class="treeview">
            <a href="#">
            <i class="fa fa-plus-square"></i>
            <span>Master Management</span><i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                  
                  <li><a
            href="<?php echo base_url().'index.php/activity/view_packages_types'?>"><i
            class="fas fa-circle-notch"></i> Activities Types </a></li>
                  
         <li><a
            href="<?php echo base_url().'index.php/activity/activity_subtheme'?>"><i
            class="fas fa-circle-notch"></i> Activities Themes</a></li>
         <li><a
            href="<?php echo base_url().'index.php/activity/activity_amenties'?>"><i
            class="fas fa-circle-notch"></i> Excursion Amenties</a></li>
         <li><a
            href="<?php echo base_url().'index.php/activity/health_instructions'?>"><i
            class="fas fa-circle-notch"></i> Health Restriction</a></li>
               <li class="treeview">
                  <a href="#">
                  <i class="fa fa-plus-square"></i>
                  <span>Nationality Management </span><i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                     <!--<li>
                  <a href="<?php echo base_url().'index.php/activity/nationality_region'?>"><i class="fa fa-circle-notch"></i>Region</a>
               </li>-->
               <li>
                  <a href="<?php echo base_url().'index.php/activity/view_notionality_country'?>"><i class="fa fa-circle-notch"></i>Country</a>
               </li>
                  </ul>
               </li>
            </ul>
         </li>
        
      </ul>
   </li>
   <?php } ?>
  <?php 
                      
                        if(check_user_previlege('p120')): ?>
	
       <li class="treeview">
            <a href="#"> <i class="far fa-plus-square"></i> <span>
            Tour CRS </span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">          
                      
                        <li class=""><a href="<?php echo base_url().'index.php/tours/tour_list'?>">
                        <i class="fas fa-circle-notch"></i> Tour List </a></li> 
                        
                             <!--             <li class=""><a href="--><?php //echo base_url().'index.php/tours/agent_tour_list'?><!--">-->
                            <!--            <i class="fas fa-circle-notch"></i> Agent Holiday List </a></li>    -->
                            <!--                        <li class=""><a href="--><?php //echo base_url().'index.php/tours/tour_list_pending'?><!--">-->
                            <!--            <i class="fas fa-circle-notch"></i>Unapproved Holiday List </a></li>-->
                        <!-- <li class=""><a href="<?php echo base_url().'index.php/tours/tours_enquiry';?>">
                        <i class="fas fa-circle-notch"></i> Inquiry</a></li> -->
                        <!-- <li class=""><a href="<?php echo base_url().'index.php/tours/quotation_list';?>">
                        <i class="fas fa-circle-notch"></i> Quotation List</a></li> -->
                        
                        <li class=""><a href="<?php echo base_url().'index.php/tours/tour_type'?>"> 
                        <i class="fas fa-circle-notch"></i>Activities  </a></li>
                    
                        <li class=""><a href="<?php echo base_url().'index.php/tours/tour_subtheme'?>">
                        <i class="fas fa-circle-notch"></i> Theme Type</a></li>
                  
                        <li class=""><a href="<?php echo base_url().'index.php/tours/tour_region'?>">
                        <i class="fas fa-circle-notch"></i> Continent </a></li>
                    
                        <li class=""><a href="<?php echo base_url().'index.php/tours/tour_country'?>">
                        <i class="fas fa-circle-notch"></i> Country </a></li>
                    
                        <li class=""><a href="<?php echo base_url().'index.php/tours/tour_city'?>">
                        <i class="fas fa-circle-notch"></i> City </a></li>
                    

                        <li><a href="<?php echo base_url() . 'index.php/supplier/enquiries' ?>"><i class="far fa-circle"></i> View Packages Enquiries </a></li>
                   
                        <li><a href="<?php echo base_url() . 'index.php/supplier/general_enquiries' ?>"><i class="far fa-circle"></i> General Enquiries </a></li>
                   
                     <li class="treeview">
                  <a href="#">
                  <i class="fa fa-plus-square"></i>
                  <span>Nationality Management </span><i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                      
                     <!--<li>
                  <a href="<?php echo base_url().'index.php/tours/nationality_region'?>"><i class="fa fa-circle-notch"></i>Region</a>
               </li>-->
               
               <li>
                  <a href="<?php echo base_url().'index.php/tours/view_notionality_country'?>"><i class="fa fa-circle-notch"></i>Country</a>
               </li>
                
                  </ul>
               </li>
          
                         
            </ul>
        </li>
         <?php endif; ?>
	 <li class="treeview">
    <?php if(check_user_previlege('p143')){
    ?>
            <a href="#"> <i class="fa fa-bed"></i> <span>
            Hotel CRS </span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              
                <li><a
                    href="<?php echo base_url().'index.php/hotel/hotel_crs_list'?>">
                    <i class="fas fa-circle-notch"></i>Hotel List & Room Allocation</a></li>
                   

           <!--      <li class=""><a href="<?php echo base_url().'index.php/hotels/hotels_enquiry';?>">
                        <i class="fas fa-circle-notch"></i> Inquiry</a></li> -->

             <!--   <li class=""><a href="<?php echo base_url().'index.php/hotels/quotation_list';?>">
                        <i class="fas fa-circle-notch"></i> Quotation List</a></li>-->
             

                <li><a
                    href="<?php echo base_url().'index.php/hotel/hotel_types'?>"><i class="fas fa-circle-notch"></i>Hotel Type</a></li>
            
                <li><a
                    href="<?php echo base_url().'index.php/hotel/room_types'?>"><i class="fas fa-circle-notch"></i></i>Room Type</a></li>
                 
                <li><a
                    href="<?php echo base_url().'index.php/hotel/board_types'?>"><i class="fas fa-circle-notch"></i></i>Board Type</a></li>  
               
                <li class=""><a
                    href="<?php echo base_url().'index.php/hotel/hotel_ammenities'?>"><i class="fas fa-circle-notch"></i> Hotel Amenities</a></li>

                <li class=""><a
                    href="<?php echo base_url().'index.php/hotel/room_ammenities'?>"><i class="fas fa-circle-notch"></i> Room Amenities</a></li>
                   
                     <li class=""><a
                            href="<?php echo base_url() . 'index.php/hotels/room_meal_type' ?>"><i class="fa fa-database"></i> Room Meal Type</a></li>
                       
 <li class="treeview">
                  <a href="#">
                  <i class="fa fa-plus-square"></i>
                  <span>Nationality Management </span><i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                  
                     <!--<li>
                  <a href="<?php echo base_url().'index.php/hotel/nationality_region'?>"><i class="fa fa-circle-notch"></i>Region</a>
               </li>-->
            
               <li>
                  <a href="<?php echo base_url().'index.php/hotel/view_notionality_country'?>"><i class="fa fa-circle-notch"></i>Country</a>
               </li>
               
                  </ul>
               </li>
             
            </ul>
        </li>
<?php
}
?>
     



<?php 
    if(check_user_previlege('p129'))
    {
?>
        <li class="treeview">
      <a href="#"> <i class="fa fa-car"></i> <span>
      Transfers Management </span><i class="fa fa-angle-left pull-right"></i></a>
      <ul class="treeview-menu">
     
         <li><a href="<?php echo base_url().'index.php/transfers/add_transfer'?>"><i class="fa fa-circle-notch"></i>Add Transfer</a></li>
         
      
         <li class="treeview">
            <a href="#">
            <i class="fa fa-plus-square"></i>
            <span>Cancellation Management</span><i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
               <!-- USER TYPES -->
               <li><a href="<?php echo base_url().'index.php/transfers/cancellation_policy'?>"><i class="fa fa-circle-notch"></i>Cancellation Policy</a></li>
            </ul>
         </li>
        
         <li><a href="<?php echo base_url().'index.php/transfers/view_transfer_list'?>"><i class="fa fa-circle-notch"></i>Transfer List</a></li>
       
         <li class="treeview">
            <a href="#">
            <i class="fa fa-plus-square"></i>
            <span>Transfers Master Management</span><i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                  
                  <li class="treeview">
                     <a href="#">
                     <i class="fa fa-plus-square"></i>
                     <span>Transfer Types</span><i class="fa fa-angle-left pull-right"></i>
                     </a>
                     <ul class="treeview-menu">
                        <!-- USER TYPES -->
                        <li><a href="<?php echo base_url().'index.php/transfers/view_packages_types'?>"><i
                           class="fas fa-circle-notch"></i> View Transfer Types </a></li>
                     </ul>
                  </li>
                  <li class="treeview">
                     <a href="#">
                     <i class="fa fa-plus-square"></i>
                     <span>Transfer Vehicle</span><i class="fa fa-angle-left pull-right"></i>
                     </a>
                     <ul class="treeview-menu">
                        <!-- USER TYPES -->
                        <li><a href="<?php echo base_url().'index.php/transfers/transfer_vehicle'?>"><i class="fa fa-circle-notch"></i>Add Vehicle</a></li>
                        <li><a href="<?php echo base_url().'index.php/transfers/view_vehicle_list'?>"><i class="fa fa-circle-notch"></i>List Vehicle</a></li>
                     </ul>
                  </li>
               <li class="treeview">
                  <a href="#">
                  <i class="fa fa-plus-square"></i>
                  <span>Transfer Driver</span><i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                     <!-- USER TYPES -->
                     <li><a href="<?php echo base_url().'index.php/transfers/transfer_driver'?>"><i class="fa fa-circle-notch"></i>Add Driver</a></li>
                     <li><a href="<?php echo base_url().'index.php/transfers/view_driver_list'?>"><i class="fa fa-circle-notch"></i>List Driver</a></li>
                  </ul>
               </li>
               <li class="treeview">
                  <a href="#">
                  <i class="fa fa-plus-square"></i>
                  <span>Nationality Management </span><i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                     <!--<li>
                        <a href="<?php echo base_url().'index.php/transfers/nationality_region'?>"><i class="fa fa-circle-notch"></i>Region</a>
                     </li>-->
                     <li>
                        <a href="<?php echo base_url().'index.php/transfers/view_notionality_country'?>"><i class="fa fa-circle-notch"></i>Country</a>
                     </li>
                  </ul>
               </li>
              
            </ul>
         </li>
   
      </ul>
   </li>
  
  <?php
  
  }
  ?>
<?php 

	if(check_supplier_previlege('p15'))
	{
		?>

<li class="treeview">
			<a href="<?php echo base_url().'index.php/management/bank_account_details'?>">
			<i class="far fa-university"></i> <span>Bank Account Details</span> </a>
	</li>

<?php
	}
?>
 

<li class="treeview hide" >
			<a href="#"> <i class="fa fa-car"></i> <span>
			Car CRS </span><i class="fa fa-angle-left pull-right"></i></a>
			<ul class="treeview-menu">
				<?php // if ($car_module) { ?>
					<li class="treeview">
					<a href="#">
						<i class="fa fa-plus-square"></i>
						<span>CAR CRS City Country List</span><i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<!-- USER TYPES -->
						<li><a href="<?php echo base_url().'index.php/branch_users/car_country_list'?>"><i class="fa fa-circle-o"></i>Active Country City List</a></li>

					</ul>
					</li>
					<li class="treeview">
						<a href="#">
							<i class="fa fa-plus-square"></i> 
							<span> CAR CRS Branch Management </span><i class="fa fa-angle-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
						<!-- USER TYPES -->
							<li><a href="<?php echo base_url().'index.php/branch_users/car_branch_list'?>"><i class="fa fa-circle-o"></i>Car Branch List </a></li>
							<li><a href="<?php echo base_url().'index.php/branch_users/add_car_branch'?>"><i class="fa fa-circle-o"></i>Add Branch Users</a></li>		

						</ul>
					</li>

					<li class="treeview">
						<a href="#">
							<i class="fa fa-plus-square"></i> 
							<span>Car Supplier/Vendor List</span><i class="fa fa-angle-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
						USER TYPES
							<li><a href="<?php echo base_url().'index.php/supplier/all_car_supplier_list'?>"><i class="fa fa-user"></i> <span>Vendor/Supplier List</span></a></li>

							<li><a href="<?php echo base_url().'index.php/car_supplier/get_branch_supplier_list'?>"><i class="fa fa-taxi"></i><span>View Vehicle List</span></a></li>

							<li><a href="<?php echo base_url().'index.php/car_supplier/car_make'?>"><i class="fa fa-taxi"></i> <span>Car Type</span></a></li>

							<li><a href="<?php echo base_url().'index.php/car_supplier/car_features'?>"><i class="fa fa-archive"></i> <span>Car Feature</span></a></li>
							<li><a href="<?php echo base_url().'index.php/car_supplier/car_transmission'?>"><i class="fa fa-arrows"></i> <span>Car Transmission</span></a></li>
							<li><a href="<?php echo base_url().'index.php/car_supplier/car_class'?>"><i class="fa fa-check-square"></i> <span>Car Class</span></a></li>


						</ul>

					</li>
							<li class="treeview">
								<a href="#">
									<i class="fa fa-plus-square"></i>
									<span>Driver List</span><i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li>
										<a href="<?php echo base_url().'index.php/car_supplier/driver_list'?>"><i class="fa fa-list-ul"></i> <span>Manage Driver</span></a>
									</li>

								</ul>


							</li>

					<?php// } ?>
			
			</ul>
			
		</li>


	<?php 

		if(check_supplier_previlege('2'))
		{
			?>
	<li class="treeview hide"><a href="#"> <i class="fa fa-suitcase"></i>  <span>
					Activity Management </span><i class="fa fa-angle-left pull-right"></i>
		</a>
			<ul class="treeview-menu">
				<!-- USER TYPES -->
				 <li><a
					href="<?php echo base_url().'index.php/supplier/view_packages_types'?>"><i
						class="fas fa-circle-notch"></i> View Activity Types </a></li>
				<li><a
					href="<?php echo base_url().'index.php/supplier/add_with_price'?>"><i
						class="fas fa-circle-notch"></i> Add Activity </a></li>
				<li><a
					href="<?php echo base_url().'index.php/supplier/view_with_price'?>"><i
						class="fas fa-circle-notch"></i> View Activity </a></li>
				<!--  <li><a href="<?php echo base_url().'index.php/supplier/enquiries'?>">
					<i
						class="fas fa-circle-notch"></i> View Activity Enquiries </a></li>  -->
			</ul>
	</li>

	<?php 
	}
	?>


	<?php 

		if(check_supplier_previlege('4'))
		{
			?>

	<li class="treeview hide"><a href="#"> <i class="fa fa-car"></i> <span>
					Transfers Management </span><i class="fa fa-angle-left pull-right"></i>
		</a>
			<ul class="treeview-menu">
				<!-- USER TYPES -->
				 <li><a
					href="<?php echo base_url().'index.php/transfers/view_packages_types'?>"><i
						class="fas fa-circle-notch"></i> View Transfers Types </a></li>  
				<li><a
					href="<?php echo base_url().'index.php/transfers/add_with_price'?>"><i
						class="fas fa-circle-notch"></i> Add Transfers </a></li>
				<li><a
					href="<?php echo base_url().'index.php/transfers/view_with_price'?>"><i
						class="fas fa-circle-notch"></i> View Transfers </a></li>	
			  <?php /*<li><a href="<?php echo base_url().'index.php/transfers/enquiries'?>"><i
						class="fas fa-circle-notch"></i> View Transfers Enquiries </a></li>  */?>
			</ul>
	</li>

	<?php 
	}
	?>





<?php 
// check_supplier_previlege('3') || 
	if(0)
	{
		?>

<li class="treeview ">
			<a href="#"> <i class="fa fa-bed"></i> <span>
			Hotel CRS </span><i class="fa fa-angle-left pull-right"></i></a>
			<ul class="treeview-menu">
				<?php if(0){?>
				<li><a
					href="<?php echo base_url().'index.php/hotels/hotel_crs_list'?>"><i
						class="fa fa-credit-card"></i>Hotel List & Room Allocation</a></li>
					<?php }?>

              <!--   <li class=""><a href="<?php echo base_url().'index.php/hotels/hotels_enquiry';?>">
                        <i class="fas fa-circle-notch"></i> Inquiry</a></li> -->

               <!--  <li class=""><a href="<?php echo base_url().'index.php/hotels/quotation_list';?>">
                        <i class="fas fa-circle-notch"></i> Quotation List</a></li>
 -->
				<li><a
					href="<?php echo base_url().'index.php/hotels/hotel_types'?>"><i
						class="fa fa-tag"></i>Hotel Type</a></li>

				<li><a
					href="<?php echo base_url().'index.php/hotels/room_types'?>"><i
						class="fa fa-tag"></i>Room Type</a></li>

				<li class=""><a
					href="<?php echo base_url().'index.php/hotels/hotel_ammenities'?>"><i class="fa fa-database"></i> Hotel Amenities</a></li>
				<li class=""><a
					href="<?php echo base_url().'index.php/hotels/room_ammenities'?>"><i class="fa fa-database"></i> Room Amenities</a></li>  
			</ul>
		</li>
<?php 
}
?>













<?php 
if(check_user_previlege('79')):

		if ($package_module) { ?>
	<li class="treeview hide">
		<!-- <a href="#">
			<i class="far fa-plus-square"></i> 
			<span> Package Management </span><i class="far fa-angle-left pull-right"></i>
		</a> -->
		<ul class="treeview-menu">
		<!-- USER TYPES -->
		<?php if(check_user_previlege('80')):?>
			<!-- <li><a href="<?php echo base_url().'index.php/supplier/view_packages_types'?>"><i class="far fa-circle"></i> View Package Types </a></li> -->
		<?php endif;
		?>
		<?php if(check_user_previlege('81')):?>
			<!-- <li><a href="<?php echo base_url().'index.php/supplier/add_with_price'?>"><i class="far fa-circle"></i> Add New Package </a></li> -->
		<?php endif;?>
		<?php if(check_user_previlege('82')):?>
			<!-- <li><a href="<?php echo base_url().'index.php/supplier/view_with_price'?>"><i class="far fa-circle"></i> View Packages </a></li> -->
		<?php endif;?>
		<?php if(check_user_previlege('83')):?>
			<!-- <li><a href="<?php echo base_url().'index.php/supplier/enquiries'?>"><i class="far fa-circle"></i> View Packages Enquiries </a></li> -->
		<?php endif;?>
		</ul>
	</li>
	<?php } ?>
<?php endif;?>


<?php if(check_user_previlege('84')):?>
	<li class="treeview">
		<a href="#">
			<i class="far fa-envelope"></i> 
			<span> Email Subscriptions </span><i class="far fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
		<?php if(check_user_previlege('85')):?>
		<!-- USER TYPES -->
			<li><a href="<?php echo base_url().'index.php/general/view_subscribed_emails'?>"><i class="far fa-circle"></i> View Emails </a></li>
		<?php endif;?>
			<!-- <li><a href="<?php echo base_url().'index.php/supplier/add_with_price'?>"><i class="far fa-circle"></i> Add New Package </a></li>
			<li><a href="<?php echo base_url().'index.php/supplier/view_with_price'?>"><i class="far fa-circle"></i> View Packages </a></li>
			<li><a href="<?php echo base_url().'index.php/supplier/enquiries'?>"><i class="far fa-circle"></i> View Packages Enquiries </a></li> -->
		</ul>
	</li>
<?php endif;?>
	<?php } ?>

	<?php if(check_user_previlege('86')):?>
	<li class="treeview">
		<a href="#">
			<i class="far fa-laptop"></i>
			<span>CMS</span><i class="far fa-angle-left pull-right"></i>
		</a>

		<ul class="treeview-menu">
		<?php if(check_user_previlege('87')):?>
			<li><a href="<?php echo base_url().'index.php/user/banner_images'?>"><i class="far fa-image"></i> <span>Main Banner Image</span></a></li>
		<?php endif;?>
		<?php if(check_user_previlege('88')):?>
			<li><a href="<?php echo base_url().'index.php/cms/add_cms_page'?>"><i class="far fa-file-alt"></i> <span>Static Page content</span></a></li>
			<?php endif;?>

			   <li class=""><a href="<?php echo base_url() . 'index.php/user/banner_adds_images' ?>"><i class="fas fa-image"></i> <span>Banner Adds</span></a></li>

			 
			<!-- Top Destinations START -->
				<?php if (!$airline_module) { ?>
				<li class=""><a href="<?php echo base_url().'index.php/cms/flight_top_destinations'?>"><i class="far fa-plane"></i> <span>Flight Top Destinations</span></a></li>
				<?php } ?>
				<?php if(check_user_previlege('89')):?>
				<?php if ($accomodation_module) { ?>
				<li class=""><a href="<?php echo base_url().'index.php/cms/hotel_top_destinations'?>"><i class="fas fa-bed"></i> <span>Hotel Top Destinations</span></a></li>
				<?php } ?>
			<?php endif;?>
				<?php if (!$bus_module) { ?>
				<li class=""><a href="<?php echo base_url().'index.php/cms/bus_top_destinations'?>"><i class="far fa-bus"></i> <span>Bus Top Destinations</span></a></li>
				<?php } ?>


				<li class="hide"><a href="<?php echo base_url().'index.php/cms/home_page_headings'?>"><i class="far fa-book"></i> <span>Home Page Headings</span></a></li>
				<li class="hide"><a href="<?php echo base_url().'index.php/cms/why_choose_us'?>"><i class="far fa-question"></i> <span>Why Choose Us</span></a></li>
				<?php if(check_user_previlege('90')):?>
				<!-- <li class=""><a href="<?php echo base_url().'index.php/cms/top_airlines'?>"><i class="far fa-plane"></i> <span>Testimonials</span></a></li> -->
			<?php endif;?>
				<li class="hide"><a href="<?php echo base_url().'index.php/cms/tour_styles'?>"><i class="far fa-binoculars"></i> <span>Tour Styles</span></a></li>
				<?php if(check_user_previlege('91')):?>
				<li class=""><a href="<?php echo base_url().'index.php/cms/add_contact_address'?>"><i class="far fa-address-card"></i> <span>Contact Address</span></a></li>
			<?php endif;?>
			<?php if(check_user_previlege('92')):?>
				<li class=""><a href="<?php echo base_url().'index.php/utilities/manage_blog'?>"><i class="far fa-address-card"></i> <span>Blog</span></a></li>
			<?php endif;?>
			<!-- Top Destinations END -->
		</ul>
	</li>
<?php endif;?>
<?php if(check_user_previlege('93')):?>
	<li class="treeview">
			<a href="<?php echo base_url().'index.php/management/bank_account_details'?>">
			<i class="far fa-university"></i> <span>Bank Account Details</span> </a>
	</li>
<?php endif;?>
	<!-- 
	<li class="treeview">
			<a href="<?php //echo base_url().'index.php/utilities/deal_sheets'?>">
				<i class="far fa-hand-o-right "></i> <span>Deal Sheets</span>
			</a>
	</li>
	 -->
	 <?php if(check_user_previlege('94')):?>
	 <li class="treeview">
		<a href="<?php echo base_url().'index.php/user/b2c_enquiry'?>">
			<!-- <i class="fa fa-google-wallet"></i>  --><i class="far fa-question-circle"></i>
			<span> B2C Enquiry </span>
		</a>
	</li>
	<!--<li class="treeview">
		<a href="<?php echo base_url().'index.php/user/b2e_enquiry'?>">
			 <i class="fa fa-google-wallet"></i>  <i class="far fa-question-circle"></i>
			<span> Corporate Enquiry </span>
		</a>
	</li>-->
<?php endif;?>
<?php if(check_user_previlege('95')):?>
	<li class="treeview">
		<a href="#">
			<i class="far fa-cogs"></i> 
			<span> Settings </span><i class="far fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
		<?php if(check_user_previlege('96')):?>
			<li>
				<a href="<?php echo base_url().'index.php/utilities/convenience_fees'?>"><i class="far fa-credit-card"></i>Convenience Fees</a>
			</li>
<?php endif;?>
<?php if(check_user_previlege('97')):?>
			<li>
				<a href="<?php echo base_url().'index.php/utilities/manage_promo_code'?>"><i class="far fa-tag"></i>Promo Code</a>
			</li>
<?php endif;?>
			<li class="hide">
				<a href="<?php echo base_url().'index.php/utilities/manage_source'?>"><i class="far fa-database"></i> Manage API</a>
			</li>
<?php if(check_user_previlege('98')):?>
			<li>
				<a href="<?php echo base_url().'index.php/utilities/sms_checkpoint'?>"><i class="far fa-envelope"></i> Manage SMS</a>
			</li>
<?php endif;?>
			<?php if(is_domain_user() == false) { // ACCESS TO ONLY PROVAB ADMIN ?>
			<li>
				<a href="<?php echo base_url().'index.php/utilities/module'?>"><i class="far fa-circle"></i> <span>Manage Modules</span>
				</a>
			</li>
			<?php }?>
<?php if(check_user_previlege('99')):?>
			<li>
				<a href="<?php echo base_url().'index.php/utilities/currency_converter'?>"><i class="fas fa-rupee-sign"></i> Currency Conversion </a>
			</li>
<?php endif;?>
<?php if(check_user_previlege('100')):?>
			<li>
				<a href="<?php echo base_url().'index.php/management/event_logs'?>"><i class="far fa-shield"></i> <span> Event Logs </span></a>
			</li>
<?php endif;?>
			<!-- <li>
				<a href="<?php echo base_url().'index.php/utilities/app_settings'?>"><i class="far fa-laptop"></i> Appearance </a>
			</li> -->

			<li>
				<a href="<?php echo base_url().'index.php/utilities/social_network'?>"><i class="fab fa-facebook-square"></i> Social Networks </a>
			</li>

			<li>
				<a href="<?php echo base_url().'index.php/utilities/social_login'?>"><i class="fab fa-facebook-f"></i> Social Login </a>
			</li>

			<li>
				<a href="<?php echo base_url().'index.php/user/manage_domain'?>">
					<i class="far fa-image"></i> <span>Manage Domain</span>
				</a>
			</li>

			<li>
				<a href="<?php echo base_url()?>index.php/utilities/timeline"><i class="far fa-desktop"></i> <span>Live Events</span></a>
			</li>

			<!-- <li>
				<a href="<?=base_url().'index.php/utilities/trip_calendar'?>"><i class="far fa-calendar"></i> <span>Trip Calendar</span></a>
            </li> -->			
		</ul>
	</li>
<?php endif;?>
</ul>
