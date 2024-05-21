<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('custom_tour_package.css') ?>" rel="stylesheet">

<link

	href="<?php echo $GLOBALS['CI']->template->template_css_dir('custom_sky.css') ?>"

	rel="stylesheet">



<style type="text/css">

	.padselct {padding-left: 15px;

    border: 1px solid #ddd;

    margin-bottom: 10px; }

</style>

<?php    /*$markup=@$mark_up['generic_markup_list'][0]['value'];*/

          foreach ($mark_up['generic_markup_list'] as $key => $value) {
                  	$markup=@$value['value'];
                  	$markup_type=$value['value_type'];
                  }        
// debug($country);//die;
// debug($countries);die;
                 ?>

<div class="full witcontent  marintopcnt">

	<div class="container">

		<div class="container offset-0">

			<div class="cnclpoly">

				<div class="col-md-3 col-xs-12 nopad">

				<h1 id="contentTitle" class="h3">Activities</h1>

				<div class="clear"></div>

				<div class="tourfilter">

					<form action="<?php echo base_url().'index.php/activities/search'?>"

						autocomplete="off" id="holiday_search">

						<div class="tabspl forhotelonly">

							<div class="tabrow">

								<div class="col-md-12 padfive">

									<div class="lbl_txt">Destination</div>

									<select class="normalsel padselct arimo" id="country"

										name="country">
										
										<option value="">All</option>
										

					<?php 

					if(isset($holiday_data['countries'])){

						$country_data = $holiday_data['countries'];

					}else{

						$country_data = $countries;

					}

					if(!empty($country_data)){ ?>

					<?php foreach ($country_data as $pkgcountry) { ?>

					<option value="<?php echo $pkgcountry->package_country; ?>"

											<?php if($country != ""){ if($country == $pkgcountry->package_country) echo "selected"; }?>><?php echo $pkgcountry->country_name; ?></option>

					<?php } } ?>

				</select>

								</div>

								<div class="col-md-12 padfive">

									<div class="lbl_txt">Activity</div>

									<select class="normalsel padselct arimo" id="package_type"

										name="package_type">

										<option value="">All Activity Types</option>

					<?php 

					if(isset($holiday_data['package_types'])){

						$holiday = $holiday_data['package_types'];

					}else{

						$holiday = $package_types;

					}

					if(!empty($holiday)){ ?>

					<?php foreach ($holiday as $package_type) { ?>

					<option value="<?php echo $package_type->package_types_id; ?>"

											<?php if(isset($spackage_type)){ if($spackage_type == $package_type->package_types_id) echo "selected"; } ?>><?php echo $package_type->package_types_name; ?></option>

					<?php } ?>

					<?php } ?>

				</select>

								</div>

								<div class="col-xs-12 padfive">

						<div class="lablform">Departure</div>

						<div class="plcetogo datemark sidebord datepicker_new1" iditem="datepicker3">

							<input type="text" readonly class="normalinput auto-focus hand-cursor form-control b-r-0" id="datepicker3" placeholder="Departure Date" value="<?=$departure_date?>" name="departure_date"/>

						</div>

					</div> 

								<div class="col-md-12 padfive">

									<div class="">&nbsp;</div>

									<div class="searchsbmtfot">

										<input type="submit" class="searchsbmt" value="search" />

									</div>

								</div>

							</div>

						</div>



					</form>

				</div>

				</div>



		<div class="col-md-9 col-xs-12 nopad">

				<div id="packgtr" class="packgtr">

					<ul id="container" class="row"> 

          <?php if(!empty($packages)){ ?>

          <?php foreach($packages as $pack){?>

          <?php $country_name = $this->Package_Model->getCountryName($pack->package_country); ?>

            <li class="col-md-12 nopadMob nopad">

							<div class="inlitp">

								<div class="tpimage col-md-3 col-xs-12 nopad">

									<img

										src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images(basename($pack->image)); ?>"

										alt="<?php echo $pack->package_name; ?>" />

								</div>

								<div class="tpcontent col-md-7 col-xs-12">

									<h3 class="tptitle txtwrapRow"><?php echo $pack->package_name; ?> </h3>

									<div class="htladrsxl"><?php echo $country_name->name; ?> | <?php echo $pack->package_city; ?>  </div>

									<div class="clear"></div>

									<p> <?php echo substr($pack->package_description, 0,300); ?></p>

								</div>



								<div class="pkprice col-md-2 col-xs-12 nopad">

									<?php   if (@$markup_type=='percentage')
									 {
										$markup1=($pack->price)*($markup)/100;

             	                      } 
             	                      else
             	                      {
             	                      	$markup1=@$markup;
             	                      }
             	                       ?>
            

									<div class="pricebolk">	<strong> <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> </strong> <?php echo round(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $pack->price +@$markup1) ),2);?></div>

									<div class="durtio"><?php echo ($pack->duration-1); ?> <?=(($pack->duration-1) > 1) ? "Nights" : "Night"?> / <?php echo $pack->duration; ?> <?=($pack->duration > 1) ? "Days" : "Day"?></div>

				

								<a class="relativefmsub trssxl"

									href="<?php echo base_url(); ?>index.php/activities/details/<?php echo $pack->package_id; ?>">

									<span class="sfitlblx">View Detail</span> 

								</a>

								</div>

							</div>
