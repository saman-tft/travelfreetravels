<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css">
<?php

   $selected_module = $GLOBALS ['CI']->uri->segment(1);
$active_domain_modules = $this->active_domain_modules;
$default_active_tab = $default_view;

// if($_SERVER['REMOTE_ADDR']=='157.49.255.101'){
// 	echo $selected_module;exit;
// }
function set_default_active_tab($module_name, &$default_active_tab) {
	if (empty ( $default_active_tab ) == true || $module_name == $default_active_tab) {
		if (empty ( $default_active_tab ) == true) {
			$default_active_tab = $module_name; // Set default module as current active module
		}
		return 'active';
	}
}
//add to js of loader
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('backslider.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');
 Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('backslider.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/index.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');
?>
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js">
var tmpl_imgs = '';
</script>
<style>


</style>
<style type="text/css">
.importantRule {
    margin-bottom: 0px !important;
}

.importantRule_re {
    margin-bottom: 15px !important;
}

.importantRule1 {
    margin-bottom: 0px !important;
}

.importantRule_re1 {
    margin-bottom: 15px !important;
}

.importantRule2 {
    margin-bottom: 0px !important;
}

.importantRule_re2 {
    margin-bottom: 15px !important;
}
</style>
<div class="searcharea">
    <div class="srchinarea">
        <div class="container-fluid nopad">
            <div class="captngrp">
                <div id="big1" class="bigcaption">&nbsp;</div>
                <div id="desc" class="smalcaptn">&nbsp;<span class="boder" style="width: 174px;"></span></div>
            </div>
        </div>
        <div class="allformst">

            <div class="tab_border">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs tabstab flex-container">
                    <?php if (is_active_airline_module()) { ?>
                    <li class="item auto <?=($selected_module=="flights")? 'active' : ''?>"><a href="#flights" role="tab"
                            data-toggle="tab" id="flightModules" class="script_data" data-id="flights">
                            <span class="sprte iconcmn icimgal"><i class="fal fa-plane"></i></span><span
                                class="txt_label">Flights</span></a></li>
                    <?php } ?>
                    <?php if (is_active_hotel_module()) { ?>
                    <li class="item auto <?=($selected_module =="hotels")? 'active' : ''?>">
                        <a href="#hotels" role="tab" data-toggle="tab" id="hotelModules" class="script_data"
                            data-id="hotels">
                            <span class="sprte iconcmn icimgal"><i class="fal fa-building"></i></span><span
                                class="txt_label">Hotels</span></a>
                    </li>
                    <?php } ?>

                    <?php if (is_active_bus_module()) { ?>
                    <li class="<?php echo set_default_active_tab(META_BUS_COURSE, $default_active_tab)?>"><a href="#bus"
                            role="tab" data-toggle="tab" id="busesModules" class="script_data" data-id="buses">
                            <span class="sprte iconcmn icimgal"><i class="fal fa-bus"></i></span><span
                                class="txt_label">Buses</span></a></li>
                    <?php } ?>
                    <?php if (is_active_transferv1_module()) { ?>
                    <li class="item auto <?=($selected_module =="transfers")? 'active' : ''?>"><a href=" #transferv1" role="tab"
                            data-toggle="tab" id="transfersModules" class="script_data" data-id="transfers">
                            <span class="sprte iconcmn icimgal"><i class="fal fa-taxi"></i></span><span
                                class="txt_label">Transfers</span></a>
                    </li>
                    <?php } ?>
                    <?php if (is_active_car_module()) { ?>
                    <li class="<?=($selected_module =="car")? 'active' : ''?>"><a href="#car" role="tab"
                            data-toggle="tab" id="carsModules" class="script_data" data-id="car">
                            <span class="sprte iconcmn icimgal"><i class="fal fa-car"></i></span><span
                                class="txt_label">Car</span></a></li>
                    <?php } ?>
                    <?php if (is_active_sightseeing_module()) { ?>
                    <li class="item auto <?=($selected_module=="activities")? 'active' : ''?>"><a href="#sightseeing" role="tab"
                            data-toggle="tab" id="activitiesModules" class="script_data" data-id="activities">
                            <span class="sprte iconcmn icimgal"><i class="fal fa-binoculars"></i></span><span
                                class="txt_label">Activities</span></a></li>
                    <?php } ?>
                    <?php if (is_active_package_module()) { ?>
                    <li class="item auto <?=($selected_module =="holidays")? 'active' : ''?>"><a href="#holiday" role="tab"
                            data-toggle="tab" id="holidaysModules" class="script_data" data-id="holidays"> <span
                                class="sprte iconcmn icimgal"><i class="fal fa-tree"></i></span><span
                                class="txt_label">Holidays</span></a></li>
                    <?php } ?>
                    <?php if (is_active_package_module()) { ?>
                    <li class="<?php $default_view =="comingsoon"? 'active' : ''?>"><a
                            href="<?php echo base_url() ?>cruise" class="script_data"> <span
                                class="sprte iconcmn icimgal"><i class="fal fa-ship"></i></span><span
                                class="txt_label">Cruise</span></a></li>
                    <?php } ?>

                    <?php  {
                        ?>
                    <li class="<?=($selected_module=="privatejet")? 'active' : ''?>"><a
                            href="https://www.alkhaleejtours.com/user/comingsoonpj" class="script_data">
                            <span class="sprte iconcmn icimgal"><i class="fal fa-plane"></i></span><span
                                class="txt_label">Private Jet</span></a></li>
                    <?php } ?>
                    <?php if (is_active_hotel_crs_module()) { ?>
                    <li class="<?=($selected_module =="villasapartment")? 'active' : ''?>"><a href="#accommodation"
                            role="tab" data-toggle="tab" id="hotelModules2" class="script_data hoteltabModules2">
                            <span class="sprte iconcmn icimgal"><i class="fal fa-building"></i></span><span
                                class="txt_label">Villas & Apts</span></a></li>
                    <?php } ?>
                    <?php if (is_active_package_module()) { ?>
                    <li class="<?=($selected_module =="privatecar")? 'active' : ''?>"><a href="#privatecar" role="tab"
                            data-toggle="tab" id="privatecarmodules" data-id="privatecar" class="script_data"> <span
                                class="sprte iconcmn icimgal"><i class="fal fa-car"></i></span><span
                                class="txt_label">Private Car</span></a></li>
                    </li>
                    <?php } ?>

                    <?php if (is_active_package_module()) { ?>
                    <li class="<?=($selected_module =="private-transfer")? 'active' : ''?>"><a href="#privatetransfer"
                            class="ptclick" role="tab" data-toggle="tab" id="privatetransfermodules" class="script_data"
                            data-id="buses">
                            <span class="sprte iconcmn icimgal"><i class="fal fa-taxi"></i></span><span
                                class="txt_label">Private Transfer</span></a></li>

                    <?php } ?>
                </ul>
            </div>

            <div class="secndblak">
                <div class="container nopad inspad">
                    <div class="tab-content custmtab">
                        <?php if (is_active_airline_module()) { ?>
                        <div class="tab-pane <?php echo set_default_active_tab(META_AIRLINE_COURSE, $default_active_tab)?> flight_modules"
                            id="flights" style="display: none;">
                            <?php echo $GLOBALS['CI']->template->isolated_view('share/flight_search')?>
                        </div>
                        <?php } ?>

                        <?php if (is_active_flight_crs_module()) { ?>
                        <div class="tab-pane <?php echo set_default_active_tab(META_PRIVATEJET_COURSE, $default_active_tab)?> jet_modules"
                            id="privatejet" style="display: none;">
                            <?php echo $GLOBALS['CI']->template->isolated_view('flight_crs/flight_search')?>
                        </div>
                        <?php } ?>

                        <?php if (is_active_hotel_module()) { ?>
                        <div class="tab-pane <?php echo set_default_active_tab(META_ACCOMODATION_COURSE, $default_active_tab)?> hotel_modules"
                            id="hotels" style="display: none;">
                            <?php echo $GLOBALS['CI']->template->isolated_view('share/hotel_search')?>
                        </div>
                        <?php } ?>
                        <?php if (is_active_hotel_module()) { ?>
                        <?php 
                        if($selected_module =="villasapartment")
                        {
                        ?>
                        <div class="tab-pane testte <?=($selected_module =="villasapartment")? 'active' : ''?> "
                            id="accommodation" style="display:block !important">
                            <?php echo $GLOBALS['CI']->template->isolated_view('share/hotel_crs_search')?>
                        </div>
                        <?php
                        }  
                        else
                        {
                        ?>
                        <div class="tab-pane <?=($selected_module =="villasapartment")? 'active' : ''?> hotel_modules2"
                            id="accommodationf">
                            <?php echo $GLOBALS['CI']->template->isolated_view('share/hotel_crs_search')?>
                        </div>
                        <?php
                        }
                        ?>
                        <?php } ?>
                        <?php if (is_active_bus_module()) { ?>
                        <div class="tab-pane <?php echo set_default_active_tab(META_BUS_COURSE, $default_active_tab)?> buses_modules"
                            id="bus" style="display:none;">
                            <?php echo $GLOBALS['CI']->template->isolated_view('share/bus_search')?>
                        </div>
                        <?php } ?>
                         <div class="tab-pane <?php if($selected_module=="privatecar"){ echo "active"; } ?> private_car_modules"
                            id="privatecar">
                            <?php echo $GLOBALS['CI']->template->isolated_view('share/private_car')?>
                        </div>
                        <?php if (is_active_transferv1_module()) { ?>
                        <div class="tab-pane <?php echo set_default_active_tab(META_TRANSFERV1_COURSE, $default_active_tab)?> transfers_modules"
                            id="transferv1" style="display: none;">
                            <?php echo $GLOBALS['CI']->template->isolated_view('share/transferv1_search')?>
                        </div>
                        <?php } ?>
                        <?php if (is_active_car_module()) { ?>
                        <div class="tab-pane <?php echo set_default_active_tab(META_CAR_COURSE, $default_active_tab)?> cars_modules"
                            id="car" style="display: none;">
                            <?php echo $GLOBALS['CI']->template->isolated_view('share/car_search')?>
                        </div>
                        <?php } ?>
                        <?php if (is_active_package_module()) { ?>
                        <div class="tab-pane <?php echo set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab)?> holidays_modules"
                            id="holidays" style="display: none;">
                            <?php echo $GLOBALS['CI']->template->isolated_view('share/holiday_search',$holiday_data)?>
                        </div>
                        <?php } ?>
                        <div class="tab-pane <?php if($selected_module=="private-transfer"){ echo "active"; } ?> "
                            id="privatetransfer">
                            <?php echo $GLOBALS['CI']->template->isolated_view('transfer/transfer_search')?>
                        </div>
                        <?php if (is_active_sightseeing_module()) { ?>
                        <div class="tab-pane <?php echo set_default_active_tab(META_SIGHTSEEING_COURSE, $default_active_tab)?> activities_modules"
                            id="sightseeing" style="display: none;">
                            <?php echo $GLOBALS['CI']->template->isolated_view('share/sightseeing_search',$holiday_data)?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="dot-overlay"></div>
</div>

<?php
if(!empty($search_history_data)){
?>
<div class="my-history-sec hide">
    <div class="container nopad">
        <div class="pagehdwrap">
            <h3 class="history-sec-pagehding">My search history</h3>
        </div>
        <div class="retmnus">
            <?php
				foreach ($search_history_data as $key => $value) {
					$data_details=json_decode($value['search_data'],TRUE);				
			?>
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 nopad">
                <a
                    href="<?php echo base_url();?>general/pre_flight_search_history?trip_type=<?=@$data_details['trip_type']?>&from=<?=@$data_details['from']?>&from_loc_id=<?=@$data_details['from_loc_id']?>&to=<?=@$data_details['to']?>&to_loc_id=<?=@$data_details['to_loc_id']?>&depature=<?=@$data_details['depature']?>&return=<?=@$data_details['return']?>&v_class=<?=@$data_details['v_class']?>&carrier=<?=@urlencode(json_encode($data_details['carrier']))?>&adult=<?=@$data_details['adult']?>&child=<?=@$data_details['child']?>&infant=<?=@$data_details['infant']?>&search_flight=<?=@$data_details['search_flight']?>">
                    <div class="my-history-sec-align">
                        <h5 class="deprtus depwidth"><?=$data_details['from']?> to <?=$data_details['to']?></h5>
                        <p><span>Departs : </span><?=date('l d M',strtotime($data_details['depature']))?></p>
                        <?php 
					 	if($data_details['trip_type']=="circle")
					 	{
					 ?>
                        <p><span>Return : </span> <?=date('l d M',strtotime($data_details['return']))?></p>
                        <?php } 
						?>
                    </div>
                </a>
            </div>
            <?php
				}
			?>
        </div>
    </div>
</div>
<div class="gallery js-flickity">
    <?php
				foreach ($search_history_data as $key => $value) {
					$data_details=json_decode($value['search_data'],TRUE);				
			?>
    <div class="gallery-cell">
        <a
            href="<?php echo base_url();?>general/pre_flight_search_history?trip_type=<?=@$data_details['trip_type']?>&from=<?=@$data_details['from']?>&from_loc_id=<?=@$data_details['from_loc_id']?>&to=<?=@$data_details['to']?>&to_loc_id=<?=@$data_details['to_loc_id']?>&depature=<?=@$data_details['depature']?>&return=<?=@$data_details['return']?>&v_class=<?=@$data_details['v_class']?>&carrier=<?=@urlencode(json_encode($data_details['carrier']))?>&adult=<?=@$data_details['adult']?>&child=<?=@$data_details['child']?>&infant=<?=@$data_details['infant']?>&search_flight=<?=@$data_details['search_flight']?>">
            <div class="my-history-sec-align-mob">
                <h5 class="deprtus-mob depwidth"><?=$data_details['from']?> to <?=$data_details['to']?></h5>
                <p><span>Departs : </span><?=date('l d M',strtotime($data_details['depature']))?></p>
                <?php 
	if($data_details['trip_type']=="circle")
	{
    ?>
                <p><span>Return : </span> <?=date('l d M',strtotime($data_details['return']))?></p>
                <?php } 
    ?>
            </div>
        </a>
    </div>
    <?php } ?>
</div>
<?php
}
?>

<div class="clearfix"></div>
<!--Top offers start-->
<!--
<div class="top-offer-sec">
    <div class="container">
        <div class="pagehdwrap">
            <h1 class="pagehding">Top Deals & Offers</h1>
        </div>
        <div class="retmnus_new" style="display: none;">
            <?php
      $i=1;
      foreach ($promocode_all['data'] as $key => $value) {
        # code...
      ?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="top-con-align">
                    <div class="top-off-img">
                        <img src="<?php echo $GLOBALS['CI']->template->domain_promo_images($value['promo_code_image']); ?>"
                            alt="Book a tour to India" loading="lazy">
                    </div>
                    <div class="top-off-cont">
                        <i class="fas fa-star"></i>
                        <div class="deals_info_heading">
                            <h3>Get <?php echo $value['value'] ?> Off on <?php echo $value['module'] ?></h3>
                        </div>
                        <div class="deals_info_footer">
                            <div class="pull-left validDate">Valid till : <?php echo $value['expiry_date'] ?></div>
                        </div>
                        <div class="deals_info_subheading">
                            <h4>Use Coupon: <?php echo $value['promo_code'] ?>.</h4>
                        </div>
                    </div>
                </div>
            </div>
            <?php
      $i++;
      }
      ?>
        </div>
    </div>
</div>
<div class="top-offer-sec_head" style="display: none;">
    <div class="container">
        <div class="pagehdwrap">
            <h1 class="pagehding">Top Deals & Offers</h1>
        </div>
    </div>
</div>
    -->
<!--Top offers end-->
<!--Top offers end-->
<div class="clearfix"></div>
<!--Top offers start-->
<?php
if(!empty($promo_code_list)){
?>
<div class="top-offer-sec" style="display: none;">
    <div class="container">
        <div class="retmnus_transfer" style="display: none;">
            <?php
			$i=1;
			foreach ($promo_code_list as $key => $value) {
				# code...			
			?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="top-con-align">
                    <div class="top-off-img">
                        <img src="<?php echo $GLOBALS['CI']->template->domain_promo_images($value['promo_code_image']); ?>"
                            alt="Book a tour to India" loading="lazy">
                    </div>
                    <div class="top-off-cont">
                        <i class="fas fa-star"></i>
                        <div class="deals_info_heading">
                            <h3>Get 14% Off on Flights</h3>
                        </div>
                        <div class="deals_info_footer">
                            <div class="pull-left validDate">Valid till : Jun 18, 2018</div>
                        </div>
                        <div class="deals_info_subheading">
                            <h4>Use Coupon: FLIGHT14OFF.</h4>
                        </div>
                    </div>
                </div>
            </div>
            <?php
			$i++;
			}
			?>
        </div>
    </div>
</div>
<?php
}
?>
<!--Top offers end-->
<!--Top offers end-->
<div class="clearfix"></div>
<!--Top offers start-->
<?php
if(!empty($promo_code_list)){
?>
<div class="top-offer-sec" style="display: none;">
    <div class="container">
        <div class="retmnus_activities" style="display: none;">
            <?php
			$i=1;
			foreach ($promo_code_list as $key => $value) {
				# code...			
			?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="top-con-align">
                    <div class="top-off-img">
                        <img src="<?php echo $GLOBALS['CI']->template->domain_promo_images($value['promo_code_image']); ?>"
                            alt="Book a tour to India" loading="lazy">
                    </div>
                    <div class="top-off-cont">
                        <i class="fas fa-star"></i>
                        <div class="deals_info_heading">
                            <h3>Get 14% Off on Flights</h3>
                        </div>
                        <div class="deals_info_footer">
                            <div class="pull-left validDate">Valid till : Jun 18, 2018</div>
                        </div>
                        <div class="deals_info_subheading">
                            <h4>Use Coupon: FLIGHT14OFF.</h4>
                        </div>
                    </div>
                </div>
            </div>
            <?php
			$i++;
			}
			?>
        </div>
    </div>
</div>
<?php
}
?>
<!--Top offers end-->
<!--Top offers end-->
<div class="clearfix"></div>
<!--Top offers start-->
<?php
if(!empty($promo_code_list)){
?>
<div class="top-offer-sec" style="display: none;">
    <div class="container">
        <div class="retmnus_holiday" style="display: none;">
            <?php
			$i=1;
			foreach ($promo_code_list as $key => $value) {
				# code...			
			?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="top-con-align">
                    <div class="top-off-img">
                        <img src="<?php echo $GLOBALS['CI']->template->domain_promo_images($value['promo_code_image']); ?>"
                            alt="Book a tour to India" loading="lazy">
                    </div>
                    <div class="top-off-cont">
                        <i class="fas fa-star"></i>
                        <div class="deals_info_heading">
                            <h3>Get 14% Off on Flights</h3>
                        </div>
                        <div class="deals_info_footer">
                            <div class="pull-left validDate">Valid till : Jun 18, 2018</div>
                        </div>
                        <div class="deals_info_subheading">
                            <h4>Use Coupon: FLIGHT14OFF.</h4>
                        </div>
                    </div>
                </div>
            </div>
            <?php
			$i++;
			}
			?>
        </div>
    </div>
</div>
<?php
}
?>
<!--Top offers end-->
<?php 
if(in_array( 'Perfect Holidays', $headings )) : ?>
<div class="perhldys hide">
    <div class="container">
        <div class="pagehdwrap">
            <h3 class="pagehding">Top Destination</h3>
            <span>Choose Your Next Destination</span>
        </div>
        <div class="retmnus">
            <div id="holiday_destinations" class="owl-carousel owl-theme">
                <?php
					$k = 0;
					while ( @$total > 0 ) {
						?>
                <div class="col-xs-12 nopad">
                    <div class="col-xs-12 nopad">
                        <?php for($i=1;$i<=1;$i++) { 
							if(isset($top_destination_package[$k])){
							$package_country = $this->package_model->getCountryName($top_destination_package[$k]->package_country);
			
							?>
                        <div class="topone">
                            <div class="inspd2 effect-lexi">
                                <div class="imgeht2">
                                    <div class="dealimg">
                                        <img class="lazy lazy_loader"
                                            data-src="<? echo $GLOBALS['CI']->template->domain_upload_pckg_images(basename($top_destination_package[$k]->image)); ?>"
                                            alt="<?php echo $top_destination_package[$k]->package_name; ?>"
                                            src="<? echo $GLOBALS['CI']->template->domain_upload_pckg_images(basename($top_destination_package[$k]->image)); ?>"
                                            loading="lazy" />
                                    </div>
                                    <?php if(($k % 2) == 0) {?>
                                    <div class="absint2 absintcol1 ">
                                        <?php } else {?>
                                        <div class="absint2 absintcol2 ">
                                            <?php } ?>
                                            <div class="absinn">
                                                <div class="smilebig2">

                                                    <h4><?php echo $top_destination_package[$k]->package_city;?>,
                                                        <?php echo $package_country->name; ?></h4>

                                                </div>
                                                <div class="clearfix"></div>

                                            </div>
                                        </div>
                                    </div>
                                    <figcaption>
                                        <div class="deal_txt">
                                            <div class="col-xs-12 nopad">
                                                <h3><?php echo $top_destination_package[$k]->package_name; ?> </h3>
                                                <h4><?php echo isset($top_destination_package[$k]->duration)?($top_destination_package[$k]->duration-1):0; ?>
                                                    Nights /
                                                    <?php echo isset($top_destination_package[$k]->duration)?$top_destination_package[$k]->duration:0; ?>
                                                    Days</h4>
                                                <a class="package_dets_btn"
                                                    href="<?=base_url().'tours/details/'.$top_destination_package[$k]->package_id?>">
                                                    Details
                                                </a>
                                            </div>
                                        </div>
                                    </figcaption>
                                </div>
                            </div>
                            <?php } $k++ ;	} $total = $total-1;
								?>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>




<div class="clearfix"></div>

<!--Top hotel offers start-->
<?php
if($hotel_promo['status']):
?>
<div class="top-offer-sec hotel_modules custom_hotel_deals">
    <div class="container">
        <div id="custom_all" class="retmnus_hotels_1 owl-carousel owl-theme">

            <?php 
            if(!empty($hotel_promo['data'])):
                for($p=0; $p<count($hotel_promo['data']); $p++):
                    $hotel_promo_code=$hotel_promo['data'][$p];
                ?>
            <div class="col-xs-12">
                <div class="top-con-align">
                    <div class="custom-off-img">
                        <img src="<?php echo $GLOBALS ['CI']->template->domain_promo_images($hotel_promo_code['promo_code_image']); ?>"
                            alt="Book a tour to India" loading="lazy">
                    </div>
                    <div class="custom-off-cont">
                        <div class="custom_info_heading">
                            <h3>Get
                                <?=$hotel_promo_code['value']?><?=($hotel_promo_code['value_type']=='plus')? '': '%' ?>
                                OFF on the hotels with Alkhaleej</h3>

                        </div>


                        <div class="deals_img">
                            <img src="<?php echo $GLOBALS['CI']->template->domain_images('TMX1512291534825461logo-loginpg.png'); ?>"
                                alt="Book a tour to India" loading="lazy">
                        </div>
                        <div class="custom_info_button">
                            <div class="button_book">Use Coupon Code: <?=$hotel_promo_code['promo_code']?>
                                <?php if(!empty($hotel_promo_code['promo_for_city'])): ?>
                                <?=' | '.$hotel_promo_code['promo_for_city']?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endfor;
            endif;
            ?>




        </div>
    </div>
</div>

<!--Top hotel offers end-->
<?php endif; ?>


<div class="clearfix"></div>
<?php 
if(true) : ?>
<?php 
  if (in_array ( META_ACCOMODATION_COURSE, $active_domain_modules ) and valid_array ( $top_destination_hotel ) == true and $selected_module=="hotels") : // TOP DESTINATION
  ?>
<div class="htldeals custom_hotel_destinations">

    <div class="pagehdwrap">
        <h3 class="pagehding">Top Hotel Destinations</h3>
    </div>
    <div class="container">
        <div class="tophtls">
            <div class="grid">
                <div id="owl-demo2" class="owl-carousel1 owlindex2">
                    <?php  
       //  debug( $top_destination_hotel);
            foreach ( $top_destination_hotel as $tk => $tv ) :
              
              ?>

                    <?php if(($tk-0)%10 == 0){?>
                    <div class="item">
                        <div class="col-md-12 col-sm-12 col-xs-12 pdfve htd-wrap">
                            <div class="effect-marley figure">
                                <img class="lazy lazy_loader"
                                    src="<?php echo $GLOBALS['CI']->template->domain_images($tv['image']); ?>"
                                    data-src="<?php echo $GLOBALS['CI']->template->domain_images($tv['image']); ?>"
                                    alt="<?=$tv['city_name']?>" loading="lazy" />
                                <div class="hotel-custom-info">
                                    <input type="hidden" class="top_des_id" value="<?php echo $tv['origin']?>">
                                    <input type="hidden" class="top-des-val hand-cursor"
                                        value="<?=hotel_suggestion_value($tv['city_name'], $tv['country_name'])?>">
                                    <h4><?=$tv['city_name']?></h4>
                                    <div class="button-hotel-custom">
                                        <h5>Book Now</h5>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <?php } elseif (($tk-6)%10 == 0){ ?>
                    <div class="item">
                        <div class="col-md-12 col-sm-12 col-xs-12 pdfve htd-wrap">
                            <div class="effect-marley figure">
                                <img class="lazy lazy_loader"
                                    src="<?php echo $GLOBALS['CI']->template->domain_images($tv['image']); ?>"
                                    data-src="<?php echo $GLOBALS['CI']->template->domain_images($tv['image']); ?>"
                                    alt="<?=$tv['city_name']?>" loading="lazy" />
                                <div class="hotel-custom-info">
                                    <input type="hidden" class="top_des_id" value="<?php echo $tv['origin']?>">
                                    <input type="hidden" class="top-des-val hand-cursor"
                                        value="<?=hotel_suggestion_value($tv['city_name'], $tv['country_name'])?>">
                                    <h4><?=$tv['city_name']?></h4>
                                    <div class="button-hotel-custom">
                                        <h5>Book Now</h5>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php } else {?>
                    <div class="item">
                        <div class="col-md-12 col-sm-12 col-xs-12 pdfve htd-wrap">
                            <div class="effect-marley figure">
                                <img class="lazy lazy_loader"
                                    src="<?php echo $GLOBALS['CI']->template->domain_images($tv['image']); ?>"
                                    data-src="<?php echo $GLOBALS['CI']->template->domain_images($tv['image']); ?>"
                                    alt="<?=$tv['city_name']?>" loading="lazy" />
                                <div class="hotel-custom-info">
                                    <input type="hidden" class="top_des_id" value="<?php echo $tv['origin']?>">
                                    <input type="hidden" class="top-des-val hand-cursor"
                                        value="<?=hotel_suggestion_value($tv['city_name'], $tv['country_name'])?>">
                                    <h4><?=$tv['city_name']?></h4>
                                    <div class="button-hotel-custom">
                                        <h5>Book Now</h5>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php
          }
          endforeach;
          endif; // TOP DESTINATION
        ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<div class="clearfix"></div>


<?php 
if($selected_module=="hotels") : ?>
<?php 
  //if (in_array ( META_ACCOMODATION_COURSE, $active_domain_modules ) and valid_array ( $top_destination_hotel ) == true) : // TOP DESTINATION
  ?>
<div class="htldeals hotel_modules2">

    <div class="pagehdwrap">
        <h3 class="pagehding">Top Hotel Destinations</h3>
    </div>
    <div class="container">
        <div class="tophtls">
            <div class="grid">
                <div id="owl-demo2" class="owl-carousel1 owlindex2" style="
    display: inline-flex;
">
                    <?php   

       // $top_destination_hotel = array_slice($top_destination_hotel,5) ;
   //    debug($top_destination_hotel);exit;
            foreach ( $acco_hotel_crs_display as $tk => $tv ) :
              ?>
                    <?php if(($tk-0)%10 == 0){?>
                    <div class="item" style="width:280px">
                        <div class="col-md-12 col-sm-12 col-xs-12 pdfve htd-wrap">
                            <div class="effect-marley figure">
                                <img class="lazy lazy_loader"
                                    src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$tv['image'];?>"
                                    data-src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$tv['image'];?>"
                                    alt="<?=$tv['city']?>" loading="lazy" />
                                <div class="figcaption">
                                    <div class="width_70">
                                        <h3 class="clasdstntion"><?=$tv['city']?></h3>
                                        <p>(<?=$tv['cache_hotels_count']?> Hotels)</p>
                                        <input type="hidden" class="top_des_id" value="<?php echo $tv['id']?>">
                                        <input type="hidden" class="top-des-val hand-cursor"
                                            value="<?=hotel_suggestion_value($tv['city'], $tv['country'])?>">
                                        <a href="#">View more</a>
                                    </div>
                                </div>
                                <div class="slider-feature">
                                    <ul class="hotel-feature">
                                        <li>
                                            <div class="tbl-wrp">
                                                <div class="text-middle">
                                                    <div class="tbl-cell">
                                                        <i class="fal fa-car"></i> <span>CAR PARK</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="tbl-wrp">
                                                <div class="text-middle">
                                                    <div class="tbl-cell">
                                                        <i class="fal fa-wifi"></i> <span>INTERNET</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="tbl-wrp">
                                                <div class="text-middle">
                                                    <div class="tbl-cell">
                                                        <i class="fal fa-utensils"></i> <span>BREAKFAST</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="tbl-wrp">
                                                <div class="text-middle">
                                                    <div class="tbl-cell">
                                                        <i class="fal fa-dumbbell"></i> <span>FITNESS CENTER</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } elseif (($tk-6)%10 == 0){ ?>
                    <div class="item" style="width:280px">
                        <div class="col-md-12 col-sm-12 col-xs-12 pdfve htd-wrap">
                            <div class="effect-marley figure">
                                <img class="lazy lazy_loader"
                                    src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$tv['image'];?>"
                                    data-src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$tv['image'];?>"
                                    alt="<?=$tv['city']?>" loading="lazy" />
                                <div class="figcaption">
                                    <div class="width_70">
                                        <h3 class="clasdstntion"><?=$tv['city']?></h3>
                                        <p>(<?=$tv['cache_hotels_count']?> Hotels)</p>
                                        <input type="hidden" class="top_des_id" value="<?php echo $tv['origin']?>">
                                        <input type="hidden" class="top-des-val hand-cursor"
                                            value="<?=hotel_suggestion_value($tv['city'], $tv['country'])?>">
                                        <a href="#">View more</a>
                                    </div>
                                </div>
                                <div class="slider-feature">
                                    <ul class="hotel-feature">
                                        <li>
                                            <div class="tbl-wrp">
                                                <div class="text-middle">
                                                    <div class="tbl-cell">
                                                        <i class="fal fa-car"></i> <span>CAR PARK</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="tbl-wrp">
                                                <div class="text-middle">
                                                    <div class="tbl-cell">
                                                        <i class="fal fa-wifi"></i> <span>INTERNET</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="tbl-wrp">
                                                <div class="text-middle">
                                                    <div class="tbl-cell">
                                                        <i class="fal fa-utensils"></i> <span>BREAKFAST</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="tbl-wrp">
                                                <div class="text-middle">
                                                    <div class="tbl-cell">
                                                        <i class="fal fa-dumbbell"></i> <span>FITNESS CENTER</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } else {?>
                    <div class="item" style="width:280px">
                        <div class="col-md-12 col-sm-12 col-xs-12 pdfve htd-wrap">
                            <div class="effect-marley figure">
                                <img class="lazy lazy_loader"
                                    src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$tv['image'];?>"
                                    data-src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$tv['image'];?>"
                                    alt="<?=$tv['city']?>" loading="lazy" />
                                <div class="figcaption">
                                    <div class="width_70">
                                        <h3 class="clasdstntion"><?=$tv['city']?></h3>
                                        <p>(<?=$tv['cache_hotels_count']?> Hotels)</p>
                                        <input type="hidden" class="top_des_id" value="<?php echo $tv['origin']?>">
                                        <input type="hidden" class="top-des-val hand-cursor"
                                            value="<?=hotel_suggestion_value($tv['city'], $tv['country'])?>">
                                        <a href="#">View more</a>
                                    </div>
                                </div>
                                <div class="slider-feature">
                                    <ul class="hotel-feature">
                                        <li>
                                            <div class="tbl-wrp">
                                                <div class="text-middle">
                                                    <div class="tbl-cell">
                                                        <i class="fal fa-car"></i> <span>CAR PARK</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="tbl-wrp">
                                                <div class="text-middle">
                                                    <div class="tbl-cell">
                                                        <i class="fal fa-wifi"></i> <span>INTERNET</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="tbl-wrp">
                                                <div class="text-middle">
                                                    <div class="tbl-cell">
                                                        <i class="fal fa-utensils"></i> <span>BREAKFAST</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="tbl-wrp">
                                                <div class="text-middle">
                                                    <div class="tbl-cell">
                                                        <i class="fal fa-dumbbell"></i> <span>FITNESS CENTER</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
          }
          endforeach;
          //endif; // TOP DESTINATION
        ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<div class="clearfix"></div>
<?php
if(!empty($top_destination_package))
{
?>
<div class="poplr-holy-packg holidays_modules">

    <div class="pagehdwrap">
        <h3 class="pagehding">Top Holiday Destinations</h3>
    </div>
    <div class="container">
        <div class="col-lg-12 col-md-12 nopad">
            <div class="owl-carousel owl-theme" id="owl-demo9">
                <div class="grid-layout item">
                    <?php
		 		$count = 1;
		 		$countimg = 1;
		 		$spanclass="";
		 		foreach ($top_destination_package as $k => $pk)
		 		{
		 			if($countimg==1)
		 			{
		 				$spanclass="grid-item-2";
		 				$imgwidth="540px";
		 				$priceclass="tptop";
		 				$span2="span-2";
		 				
		 			}
		 			elseif($countimg==2)
		 			{
		 				$spanclass="grid-item-4";
		 				$imgwidth="265px";
		 				$priceclass="";
		 				$span2="";
		 			}
		 			elseif($countimg==3)
		 			{
		 				$spanclass="grid-item-5";
		 				$imgwidth="265px";
		 				$priceclass="";
		 				$span2="";
		 			}
		 			elseif($countimg==4)
		 			{
		 				$spanclass="grid-item-4";
		 				$imgwidth="265px";
		 				$priceclass="";
		 				$span2="";
		 			}
		 			elseif($countimg==5)
		 			{
		 				$spanclass="grid-item-5";
		 				$imgwidth="265px";
		 				$priceclass="";
		 				$span2="";
		 			}

					$adult_count=1;
					$child_count=0; 
					$tour_id=$top_destination_package[$k]->id;
					$tour_price_changed = tour_price_list($currency_obj,$tour_id,$adult_count,$child_count);
					foreach($tour_price_changed as $price_key => $price_data){
					$tour_price_list[$price_key]['from'] = $price_data['from_date'];
					$tour_price_list[$price_key]['to'] = $price_data['to_date'];
					$tour_price_list[$price_key]['price'] = $price_data['changed_price'];
					}
					if(valid_array($tour_price_changed)){
					$min_numbers = array_column($tour_price_changed, 'changed_price');
					$min_price = min($min_numbers);        
					}
					$this->load->model('private_management_model');
					$tours_crs_markup = $this->private_management_model->get_markup('holiday');

					$min_price_1 =   isset($min_price)? (get_converted_currency_value ( $currency_obj->force_currency_conversion ( $min_price ) )):0;    

					$admin_markup_holidaylcrs = $this->domain_management_model->addHolidayCrsMarkup($min_price_1, $tours_crs_markup,$currency_obj);
					$min_price_1=str_replace(',','', $min_price_1); 

					$min_price=$min_price_1+$admin_markup_holidaylcrs;     

					$Markup=$admin_markup_holidaylcrs;
					$gst_value = 0;
					//adding gst
					if($Markup > 0 ){
					$gst_details = $this->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));
						if($gst_details['status'] == true){
							if($gst_details['data'][0]['gst'] > 0){

							$gst_value = ($Markup/100) * $gst_details['data'][0]['gst'];
							}	
						}

					}
					$min_price=$min_price+ $gst_value;
					$price= number_format($min_price,2);

					$tour_type = explode(",",$top_destination_package[$k]->tour_type);
					$tour_type_id=$tour_type[0];
					$tour_type_name = $this->db->select('tour_type_name')->from('tour_type')->where("id = '$tour_type_id'")->get()->row_array();		 		
					?>
                    <div class="grid-item inspd2 effect-lexi <?=$span2?> <?=$spanclass?> grdlay">
                        <a href="<?=base_url().'tours/details/'.base64_encode($top_destination_package[$k]->id)?>">
                            <img src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/images/'.basename($pk->banner_image);?>"
                                width="<?=$imgwidth?>" alt="Book a tour to India" loading="lazy">
                            <div class="poplr-holy-top-left">
                                <h4 class="<?=$priceclass?>">
                                    <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>
                                    <?php echo isset($price)?$price :0; ?> <span>per person</span></h4>
                            </div>
                            <div class="poplr-holy-bottom-left">
                                <h4><?php echo $top_destination_package[$k]->package_name; ?>
                                    <?=$tour_type_name['tour_type_name']?></h4>
                                <span><?php echo isset($top_destination_package[$k]->duration)?($top_destination_package[$k]->duration+1):0; ?>
                                    Days /
                                    <?php echo isset($top_destination_package[$k]->duration)?$top_destination_package[$k]->duration:0; ?>
                                    <?php if($top_destination_package[$k]->duration >1){echo "Nights";}else{echo "Night";}?></span>
                            </div>
                        </a>
                    </div>
                    <?php
				
					if (fmod($count,5) == 0)
				    {
				        echo "</div>";
				        if($count <count($top_destination_package)){

				         echo '<div class="grid-layout item">';        
				        }
				       
				        $countimg=0;
				        $spanclass="";
				    }
					$count++;
					$countimg++;
				}			
				?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
?>
<div class="clearfix"></div>


<?php if($holiday_promo['status']): ?>

<div class="clearfix"></div>
<!--Top holidays Offers start-->
<div class="top-offer-sec holidays_modules custom-holiday-deals">
    <div class="container">
        <div id="custom_all" class="retmnus_holidays_1 owl-carousel owl-theme">

            <?php 
          
            if(!empty($holiday_promo['data'])):
                for($p=0; $p<count($holiday_promo['data']); $p++):
                    $holiday_promo_code=$holiday_promo['data'][$p];
                ?>
                <div class="col-xs-12">
                    <div class="top-con-align">
                        <div class="custom-off-img">
                            <img src="<?php echo $GLOBALS ['CI']->template->domain_promo_images($holiday_promo_code['promo_code_image']); ?>"
                                alt="Book a tour to India" loading="lazy">
                        </div>
                        <div class="custom-off-cont">
                            <div class="custom_info_heading">
                                <h3>Get
                                    <?=$holiday_promo_code['value']?><?=($holiday_promo_code['value_type']=='plus')? '': '%' ?>
                                    OFF on the Holidays with Alkhaleej</h3>
                                <p>Use Coupon Code: <?=$holiday_promo_code['promo_code']?> <?php if(!empty($holiday_promo_code['promo_for_city'])): ?>
                            <span><?=' <br /> '.$holiday_promo_code['promo_for_city']?></span>
                            <?php endif; ?> </p>
                               
                            </div>
                        </div>
                        <div class="deals_img">
                            <div class="img_sec">
                                <img src="<?php echo $GLOBALS['CI']->template->domain_images('TMX1512291534825461logo-loginpg.png'); ?>"
                                    alt="Book a tour to India" loading="lazy">
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                endfor;
            endif;
            ?>
           


        </div>
    </div>
</div>
<!--Top holidays Offers end-->
<?php endif; ?>

<div class="clearfix"></div>
<?php
if(!empty($top_destination_package))
{
?>
<div class="poplr-holy-packg-mob holidays_modules custom-holiday-services" id="demos" style="display:none">

    <div class="pagehdwrap">
        <h3 class="pagehding">Popular Holiday Packages</h3>
    </div>
    <div class="container">
        <div class="owl-carousel owlindex3 owl-theme" id="owl-demomobile">
            <?php
		 		$count = 1;
		 		$countimg = 1; 
            	$divCodeVal = 3;
		 		foreach ($top_destination_package as $k => $pk)
		 		{
				                echo '<div class="item">';

					$adult_count=1;
					$child_count=0;
					$tour_id=$top_destination_package[$k]->id;
					$tour_price_changed = tour_price_list($currency_obj,$tour_id,$adult_count,$child_count);
					foreach($tour_price_changed as $price_key => $price_data){
					$tour_price_list[$price_key]['from'] = $price_data['from_date'];
					$tour_price_list[$price_key]['to'] = $price_data['to_date'];
					$tour_price_list[$price_key]['price'] = $price_data['changed_price'];
					}
					if(valid_array($tour_price_changed)){
					$min_numbers = array_column($tour_price_changed, 'changed_price');
					$min_price = min($min_numbers);        
					}
					$this->load->model('private_management_model');
					$tours_crs_markup = $this->private_management_model->get_markup('holiday');

					$min_price_1 =   isset($min_price)? (get_converted_currency_value ( $currency_obj->force_currency_conversion ( $min_price ) )):0;    

					$admin_markup_holidaylcrs = $this->domain_management_model->addHolidayCrsMarkup($min_price_1, $tours_crs_markup,$currency_obj);
					$min_price_1=str_replace(',','', $min_price_1); 

					$min_price=$min_price_1+$admin_markup_holidaylcrs;     

					$Markup=$admin_markup_holidaylcrs;
					$gst_value = 0;
					//adding gst
					if($Markup > 0 ){
					$gst_details = $this->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));
						if($gst_details['status'] == true){
							if($gst_details['data'][0]['gst'] > 0){

							$gst_value = ($Markup/100) * $gst_details['data'][0]['gst'];
							}	
						}
					}
					$min_price=$min_price+ $gst_value;
					$price= number_format($min_price,2);

					$tour_type = explode(",",$top_destination_package[$k]->tour_type);
					$tour_type_id=$tour_type[0];
					$tour_type_name = $this->db->select('tour_type_name')->from('tour_type')->where("id = '$tour_type_id'")->get()->row_array();

					?>
            <div class="f-deal">
                <a href="">
                    <div class="f-deal-img">
                        <a href="<?=base_url().'tours/details/'.base64_encode($top_destination_package[$k]->id)?>"><img class="star_rat"
                                src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/images/'.basename($pk->banner_image);?>"
                                alt="Happy Summer Holidays" loading="lazy" /></a>
                    </div>
                    <a href="<?=base_url().'tours/details/'.base64_encode($top_destination_package[$k]->id)?>">
                        <div class="flight-info">
                            <div class="holiday-name">
                                <h3> <i
                                        class="fas fa-star"></i><?php echo $top_destination_package[$k]->package_name; ?>
                                    <?=$tour_type_name['tour_type_name']?></h3>
                            </div>


                            <div class="holiday-check">
                                <h4>Check Now</h4>
                                <div class="icon-holiday">
                                    <i class="fa fa-umbrella">

                                    </i>
                                </div>
                            </div>
                        </div>

                    </a>
            </div>
            <?php
            					 echo '</div>';
	 			
       					 }
			
				?>
        </div>
    </div>
</div>
<?php
}
?>





<div class="clearfix"></div>


<!--Top offers start-->
<?php //debug($flight_promo['status']); exit();  
if($flight_promo['status']):
?>
<div class="top-offer-sec flight_modules custom_flight_deals">
    <div class="container">
        <div id="custom_all" class="retmnus_flights_1 owl-carousel owl-theme">

            <?php 
          
            if(!empty($flight_promo['data'])):
                for($p=0; $p<count($flight_promo['data']); $p++):
                    $flight_promo_code=$flight_promo['data'][$p];
                ?>
            <div class="col-xs-12">
                <div class="top-con-align">
                    <div class="top-off-img">
                        <img src="<?php echo $GLOBALS ['CI']->template->domain_promo_images($flight_promo_code['promo_code_image']); ?>"
                            alt="Book a tour to India" loading="lazy">
                    </div>
                    <div class="top-off-cont">
                        <div class="deals_info_heading">
                            <h3>Get
                                <?=$flight_promo_code['value']?><?=($flight_promo_code['value_type']=='plus')? '': '%' ?>
                                OFF on the Flight routes with Alkhaleej</h3>

                        </div>
                        <div class="deals_info_button">
                            <div class="button_book">Use Coupon Code: <?=$flight_promo_code['promo_code']?></div>
                        </div>

                        <div class="deals_img">
                            <img src="<?php echo $GLOBALS['CI']->template->domain_images('TMX1512291534825461logo-loginpg.png'); ?>"
                                alt="Book a tour to India" loading="lazy">
                        </div>
                        <div class="deals_city_name">
                            <?php if(!empty($flight_promo_code['promo_for_city']) && !empty($flight_promo_code['promo_to_city'])): ?>
                            <?=$flight_promo_code['promo_for_city'].' to '.$flight_promo_code['promo_to_city']?>
                            <?php else: ?>
                            <?=$flight_promo_code['description']?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endfor;
            endif;
            ?>



        </div>
    </div>
</div>
<?php endif; ?>
<!--Top offers end-->





<div class="clearfix"></div>
<div class="flight_deals flight_modules custom_flights">

    <div class="pagehdwrap">
        <h3 class="pagehding">Top Flight Routes</h3>
    </div>
    <div class="container">
        <div id="fligt-all" class="owl-carousel owlindex3 owl-theme">
            <?php 

         //debug($top_destination_flight);die;
                $date = date("Y-m-d");
                $date = strtotime($date);
                $ddate = strtotime("+7 day", $date);
                $tdate = strtotime("+8 day", $date);
                $ddate = date('d-m-Y', $ddate);
                $tdate = date('d-m-Y', $tdate);
              // debug($top_destination_flight);
               for ($i=0; $i < count($top_destination_flight); $i++) {
                $psdestination = $this->custom_db->single_table_records('flight_airport_list', '*', array('airport_code' => $top_destination_flight[$i]['to_airport_code']));
                    $destination_country = $this->custom_db->single_table_records('flight_airport_list', '*', array('airport_code' => $top_destination_flight[$i]['to_airport_code']));
                    $sourse_country = $this->custom_db->single_table_records('flight_airport_list', '*', array('airport_code' => $top_destination_flight[$i]['from_airport_code']));
                        //debug($sourse_country);die;
                        //sourse details
                        $sourse_origin     =$sourse_country['data'][0]['origin'];
                        $sourse_airportcode =$sourse_country['data'][0]['airport_code'];
                        $sourse_airportname   =$sourse_country['data'][0]['airport_name'];
                        $sourse_city =$sourse_country['data'][0]['airport_city'];
                        $sourse_country   =$sourse_country['data'][0]['country'];
                            //destination detaiils
                        $dest_origin     =$destination_country['data'][0]['origin'];
                        $dest_airportcode =$destination_country['data'][0]['airport_code'];
                        $dest_airportname   =$destination_country['data'][0]['airport_name'];
                        $dest_city =$destination_country['data'][0]['airport_city'];
                        $dest_country   =$destination_country['data'][0]['country'];


                        //debug($destination_country);die;
                    $destination_country = $destination_country['data'][0]['country'];
                     $XDESTINATION = $psdestination['data'][0]['airport_city'];
                     $oxdestination_orgin = $psdestination['data'][0]['origin'];
                     $xdestination_airport = $psdestination['data'][0]['airport_code'];
                     $xdestination_country = $psdestination['data'][0]['country'];
                     $origin=$top_destination_flight[$i]['origin'];
                     $oxdestination_orgin = $psdestination['data'][0]['origin'];
                      $oxarrival_orgin = $psdestination['data'][0]['origin'];
              $f = base64_encode($top_destination_flight[$i]['from_airport_name'].' ('.$top_destination_flight[$i]['from_airport_code'].')');
              $t = base64_encode($top_destination_flight[$i]['to_airport_name'].' ('.$top_destination_flight[$i]['to_airport_code'].')');
                    // "https://www.travelsoho.com/myflyafrica/flight/search/2076?trip_type=oneway&from=bangalore+%28blr%29&from_loc_id=19&to=chennai+%28maa%29&to_loc_id=&depature=24-06-2021&v_class=Economy&carrier%5B%5D=&adult=1&child=0&infant=0&search_flight=search";
                     //debug($XDESTINATION);die;
                    // $url = base_url().'index.php/general/pre_flight_search?trip_type=oneway&from='.$top_destination_flight[$i]['from_airport_name'].'+%28'.$top_destination_flight[$i]['from_airport_code'].'%29&from_loc_id='.$top_destination_flight[$i]['origin'].'&to='.$top_destination_flight[$i]['to_airport_name'].'+%28'.$top_destination_flight[$i]['to_airport_code'].'%29&to_loc_id='.$oxdestination_orgin.'&depature='.$ddate.'&adult=1&child=0&infant=0&v_class=Economy&carrier%5B%5D=';
                    $url = base_url().'index.php/general/pre_flight_search?trip_type=oneway&from='.$sourse_city.' '.$sourse_airportcode.'&from_loc_id='.$sourse_origin.'&to='.$dest_city.' '.$dest_airportcode.'&to_loc_id='.$dest_origin.'&depature='.$ddate.'&adult=1&child=0&infant=0&v_class=Economy&carrier=';
                     // $url = base_url().'general/preflightsearch/'.$f.'/'.$oxarrival_orgin.'/'.$t.'/'.$oxdestination_orgin.'/'.$ddate;
                    // echo $url;die;
             ?>
            <div class="item">
                <a href="<?php echo $url ?>">
                    <div class="f-deal">
                        <div class="f-deal-img">
                            <a href="<?php echo $url ?>"><img class="star_rat"
                                    src="<?php echo $GLOBALS['CI']->template->domain_images($top_destination_flight[$i]['image']); ?>"
                                    alt="Book a affordable flights" loading="lazy" /></a>
                        </div>



                        <a href="<?php echo $url ?>">
                            <div class="flight-info">
                                <span
                                    class="flight_1info"><?php echo $top_destination_flight[$i]['from_airport_name'] ?>
                                    &nbsp; >> &nbsp; <?php echo $top_destination_flight[$i]['to_airport_name'] ?>
                                </span>
                            </div>
                        </a>
                    </div>
            </div>
            </a>
            <?php  if($i==9)break; } ?>
        </div>
    </div>
</div>



<div class="clearfix"></div>
<?php if(!empty($second_holiday_destination_package) and $selected_module=="holidays"){ ?>
<div class="flight_deals_perfect custom-holiday-packages">

    <div class="pagehdwrap">
        <h3 class="pagehding">Perfect <?php echo ucfirst($selected_module);?> Packages </h3>
    </div>
    <div class="container">
        <div class="holiday-packages" id="ssbb">
            <?php 
      	  for ($i=0; $i < count($second_holiday_destination_package); $i++) {
      	  	$origin=$second_holiday_destination_package[$i]['origin'];
      	  	$package_name=$second_holiday_destination_package[$i]['package_name'];
      	  	
      	  	if(empty($package_name)){
      	  	    $package_name="Holidays";
      	  	}
      	  	?>
            <div class="col-md-4">
                <div class="f-deal slide" data-slide-index="0">
                    <div class="f-deal-img">
                        <a href="<?=base_url().'tours/details/'.base64_encode($origin)?>"><img class="star_rat"
                                src="<?php echo $GLOBALS['CI']->template->domain_images($second_holiday_destination_package[$i]['image']); ?>"
                                alt="book a flight ticket online" loading="lazy" /></a>
                    </div>
                    <a href="<?php echo base_url(); ?>/tours/details/<?php echo base64_encode($origin); ?>">
                        <div class="flight-info">
                            <h3><i class="fas fa-star"></i><?=$package_name?></h3>

                        </div>
                    </a>
                </div>
            </div>
            <?php  
        } ?>
        </div>
    </div>
</div>
</div>
<? } ?>


<?php if(!empty($flight_perfect_packages)){ ?>
<div class="flight_deals_perfect flight_modules custom_flight_packages">

    <div class="pagehdwrap">
        <h3 class="pagehding">Perfect <?php echo ucfirst($selected_module);?> Packages </h3>
    </div>
    <div class="container">
        <div>


            <?php 

         //debug($top_destination_flight);die;
                $date = date("Y-m-d");
                $date = strtotime($date);
                $ddate = strtotime("+7 day", $date);
                $tdate = strtotime("+8 day", $date);
                $ddate = date('d-m-Y', $ddate);
                $tdate = date('d-m-Y', $tdate);
              // debug($top_destination_flight);
              
               for ($i=0; $i < count($flight_perfect_packages); $i++) {
                   if($flight_perfect_packages[$i]['perfect_packages_status'] == 1):
                   
                $psdestination = $this->custom_db->single_table_records('flight_airport_list', '*', array('airport_code' => $flight_perfect_packages[$i]['to_airport_code']));
                    $destination_country = $this->custom_db->single_table_records('flight_airport_list', '*', array('airport_code' => $flight_perfect_packages[$i]['to_airport_code']));
                    $sourse_country = $this->custom_db->single_table_records('flight_airport_list', '*', array('airport_code' => $flight_perfect_packages[$i]['from_airport_code']));
                        //debug($sourse_country);die;
                        //sourse details
                        $sourse_origin     =$sourse_country['data'][0]['origin'];
                        $sourse_airportcode =$sourse_country['data'][0]['airport_code'];
                        $sourse_airportname   =$sourse_country['data'][0]['airport_name'];
                        $sourse_city =$sourse_country['data'][0]['airport_city'];
                        $sourse_country   =$sourse_country['data'][0]['country'];
                            //destination detaiils
                        $dest_origin     =$destination_country['data'][0]['origin'];
                        $dest_airportcode =$destination_country['data'][0]['airport_code'];
                        $dest_airportname   =$destination_country['data'][0]['airport_name'];
                        $dest_city =$destination_country['data'][0]['airport_city'];
                        $dest_country   =$destination_country['data'][0]['country'];


                        //debug($destination_country);die;
                    $destination_country = $destination_country['data'][0]['country'];
                     $XDESTINATION = $psdestination['data'][0]['airport_city'];
                     $oxdestination_orgin = $psdestination['data'][0]['origin'];
                     $xdestination_airport = $psdestination['data'][0]['airport_code'];
                     $xdestination_country = $psdestination['data'][0]['country'];
                     $origin=$flight_perfect_packages[$i]['origin'];
                     $oxdestination_orgin = $psdestination['data'][0]['origin'];
                      $oxarrival_orgin = $psdestination['data'][0]['origin'];
              $f = base64_encode($flight_perfect_packages[$i]['from_airport_name'].' ('.$flight_perfect_packages[$i]['from_airport_code'].')');
              $t = base64_encode($flight_perfect_packages[$i]['to_airport_name'].' ('.$flight_perfect_packages[$i]['to_airport_code'].')');
                    
                    $url = base_url().'index.php/general/pre_flight_search?trip_type=oneway&from='.$sourse_city.' '.$sourse_airportcode.'&from_loc_id='.$sourse_origin.'&to='.$dest_city.' '.$dest_airportcode.'&to_loc_id='.$dest_origin.'&depature='.$ddate.'&adult=1&child=0&infant=0&v_class=Economy&carrier=';
                     
             ?>
            <div class="col-md-4 package-padding">
                <div class="f-deal slide" data-slide-index="0">
                    <div class="f-deal-img">
                        <a href="<?php echo $url ?>"><img class="star_rat"
                                src="<?php echo $GLOBALS['CI']->template->domain_images($flight_perfect_packages[$i]['image']); ?>"
                                alt="book a flight ticket online" loading="lazy" /></a>
                    </div>
                    <a href="<?php echo $url ?>">
                        <div class="flight-info">
                            <h3><i
                                    class="fas fa-star"></i><?php echo $flight_perfect_packages[$i]['from_airport_name'] ?>
                                &nbsp; >> &nbsp; <?php echo $flight_perfect_packages[$i]['to_airport_name'] ?></h3>
                            <h4 class="package_boook">Book Now</h4>
                        </div>
                    </a>
                </div>
            </div>

            <?php endif; if($i==12)break; } ?>

        </div>
    </div>
</div>
</div>
<? } ?>

<div class="clearfix"></div>
<?php if(!empty($hotel_perfect_packages)){ ?>
<div class="hotel_deals_perfect hotel_modules custom_hotel_packages">

    <div class="pagehdwrap">
        <h3 class="pagehding">Perfect <?php echo ucfirst($selected_module);?> Packages</h3>
    </div>
    <div class="container">
        <div id="hotel-all_perfect" class="owl-carousel owlindex3 owl-theme">
            <?php 
      	  for ($i=0; $i < count($hotel_perfect_packages); $i++) {
      	  	$origin=$hotel_perfect_packages[$i]['origin'];

	      	  		echo '<div class="item">';
	      	 
      	  	?>

            <div class="f-deal hpp-wrap">
                <input type="hidden" class="per-pac-val"
                    value="<?=$hotel_perfect_packages[$i]['city_name'].', '.$hotel_perfect_packages[$i]['country_name']?>" />
                <input type="hidden" class="per-pac-id" value="<?=$hotel_perfect_packages[$i]['ID']?>" />
                <div class="f-deal-img">
                    <img class="star_rat"
                        src="<?php echo $GLOBALS['CI']->template->domain_images($hotel_perfect_packages[$i]['image']); ?>"
                        alt="book hotels online" loading="lazy" />
                </div>

                <div class="flight-info">

                    <h3><?=$hotel_perfect_packages[$i]['city_name']?> <i class="fa fa-bed"></i> </h3>

                </div>

            </div>
            <?php
        	      	  		echo '</div>';
	    }
	      	  	?>
        </div>
    </div>
</div>
</div>
<? } ?>
<div class="clearfix"></div>

<div class="clearfix"></div>
<!--Top cars Offers start-->
<?php if($car_promo['status']): ?>
<div class="top-offer-sec cars_modules custom-car-deals">
    <div class="container">
        <div id="custom_all" class="retmnus_cars_1 owl-carousel owl-theme">

            <?php 
          
            if(!empty($car_promo['data'])):
                for($p=0; $p<count($car_promo['data']); $p++):
                    $car_promo_code=$car_promo['data'][$p];
                ?>
            <div class="col-xs-12">
                <div class="top-con-align">
                    <div class="custom-off-img">
                        <img src="<?php echo $GLOBALS ['CI']->template->domain_promo_images($car_promo_code['promo_code_image']); ?>"
                            alt="Book a tour to India" loading="lazy">
                    </div>
                    <div class="custom-off-cont">
                        <div class="custom_info_heading">
                            <h3>Get
                                <?=$car_promo_code['value']?><?=($car_promo_code['value_type']=='plus')? '': '%' ?>
                                OFF on the Cars with Alkhaleej</h3>
                            <p>Use Coupon Code: <?=$car_promo_code['promo_code']?></p>
                            <div class="from-city">
                                <h3>From
                                    <?php if(!empty($car_promo_code['promo_for_city'])): ?>
                                    <?=$car_promo_code['promo_for_city']?>
                                    <?php else: ?>
                                    <?=$car_promo_code['description']?>
                                    <?php endif; ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="deals_img">
                        <div class="img_sec">
                            <img src="<?php echo $GLOBALS['CI']->template->domain_images('TMX1512291534825461logo-loginpg.png'); ?>"
                                alt="Book a tour to India" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endfor;
            endif;
            ?>


        </div>
    </div>
</div>
<!--Top cars Offers end-->
<?php endif; ?>


<div class="clearfix"></div>
<div class="car_deals cars_modules custom-car-services">

    <div class="pagehdwrap">
        <h3 class="pagehding"> Best Car Service</h3>
    </div>
    <div class="container">
        <div id="car-all" class="owl-carousel owlindex3 owl-theme">
            <?php  
      			$date = date("Y-m-d");
	            $date = strtotime($date);
	            $ddate = strtotime("+7 day", $date);
	            $tdate = strtotime("+8 day", $date);
	            $ddate = date('d-m-Y', $ddate);
	            $tdate = date('d-m-Y', $tdate);
		       for ($i=0; $i < count($top_destination_car); $i++) {
		      	
		       	//$from=$top_destination_car[$i]['Country_Name_EN'];
		       	/*  $from=$top_destination_car[$i]['Airport_Name_EN']; //new
		       	$image=$top_destination_car[$i]['image2'];
		       	$origin=$top_destination_car[$i]['origin'];
		       	$car_from_code=$top_destination_car[$i]['Airport_IATA'];
		       	$car_to_code=$top_destination_car[$i]['Airport_IATA'];
		       	$car_to_=$top_destination_car[$i]['origin'];
		       	$driver_age=35;*/

		       	 $from=$top_destination_car[$i]['Airport_Name_EN']; //new
            $showcountry=$top_destination_car[$i]['Country_Name_EN']; //new
            $image=$top_destination_car[$i]['image'];
            $origin=$top_destination_car[$i]['origin'];
            $car_from_code=$top_destination_car[$i]['Airport_IATA'];
            $car_to_code=$top_destination_car[$i]['Airport_IATA'];
            $car_to_=$top_destination_car[$i]['origin'];
            $driver_age=35;

   // $url5=base_url().'general/pre_car_search?car_from='.$from.'+%28'.$car_from_code.'%29%2C'.$from.'&from_loc_id='.$origin.'&car_from_loc_code='.$car_from_code.'.&car_to='.$from.'+%28'.$car_from_code.'%29%2'.$from.'&to_loc_id='.$origin.'.&car_to_loc_code='.$car_to_code.'.&depature='.$ddate.'.&depature_time=09%3A00&return='.$tdate.'.&return_time=10%3A30&driver_age=35&country=IN&search_flight=search';
		       	 $url5=base_url().'general/pre_car_search?car_from='.$from.'%2C'.$showcountry.'&from_loc_id='.$origin.'&car_from_loc_code='.$car_from_code.'&car_to='.$from.'%2C'.$showcountry.'&to_loc_id='.$origin.'&car_to_loc_code='.$car_to_code.'&depature='.$ddate.'&depature_time=09%3A00&return='.$tdate.'&return_time=10%3A30&driver_age=35&country=IN&search_flight=search';
      	?>
            <div class="item">
                <div class="f-deal">
                    <div class="f-deal-img">
                        <a href="<?php echo $url5; ?>"><img class="star_rat"
                                src="<?php echo $GLOBALS['CI']->template->domain_images($top_destination_car[$i]['image']); ?>"
                                alt="Book a tour to India" loading="lazy" /></a>
                    </div>
                    <a href="<?php 
            if(!empty($car_to_code)){ 
            	echo $url5;
            }else{           	
            	echo base_url(); ?>tours/details/<?php echo base64_encode($origin);             
            } ?>">
                        <div class="car-info">
                            <?php  if(!empty($from)){  ?>
                            <h3><i class="fas fa-star"></i> <?php echo $from ?> Tour</h3>
                            <?php	}else{  ?>
                            <h2> <i class="fas fa-star"></i>Car</h2>
                            <?php	}  ?>
                    </a>
                </div>
                <div class="icon-custom-car">
                    <i class="fa fa-car"></i>
                </div>
            </div>
            </a>
        </div>
        <?php  if($i==9)break; } ?>
    </div>
    <!-- <div class="explore_al">
      	<a href="">Explore All</a>
      </div> -->
</div>
</div>
<div class="clearfix"></div>
<?php if(!empty($perfect_car_packages)){ ?>
<div class="car_deals_perfect cars_modules custom-car-packages">

    <div class="pagehdwrap">
        <h3 class="pagehding"> Perfect <?php echo  ucfirst($selected_module); ?> Packages </h3>
    </div>
    <div class="container">
        <div id="car-all_perfect" class="owl-carousel owlindex3 owl-theme">
            <?php 
            $pcp_date = date("Y-m-d");
            $pcp_date = strtotime($pcp_date);
            $pcp_ddate = strtotime("+7 day", $pcp_date);
            $pcp_tdate = strtotime("+8 day", $pcp_date);
            $pcp_ddate = date('d-m-Y', $pcp_ddate);
            $pcp_tdate = date('d-m-Y', $pcp_tdate);
          for ($i=0; $i < count($perfect_car_packages); $i++) {
              
             $pcp_from=$perfect_car_packages[$i]['Airport_Name_EN']; //new
            $pcp_showcountry=$perfect_car_packages[$i]['Country_Name_EN']; //new
            $pcp_image=$perfect_car_packages[$i]['image'];
            $pcp_origin=$perfect_car_packages[$i]['origin'];
            $pcp_car_from_code=$perfect_car_packages[$i]['Airport_IATA'];
            $pcp_car_to_code=$perfect_car_packages[$i]['Airport_IATA'];
            $pcp_car_to_=$perfect_car_packages[$i]['origin'];
            $pcp_driver_age=35;
            $pcp_url=base_url().'general/pre_car_search?car_from='.$pcp_from.'%2C'.$pcp_showcountry.'&from_loc_id='.$pcp_origin.'&car_from_loc_code='.$pcp_car_from_code.'&car_to='.$pcp_from.'%2C'.$showcountry.'&to_loc_id='.$pcp_origin.'&car_to_loc_code='.$pcp_car_to_code.'&depature='.$pcp_ddate.'&depature_time=09%3A00&return='.$pcp_tdate.'&return_time=10%3A30&driver_age=35&country=IN&search_flight=search';
            
            if(empty($pcp_car_to_code)){ 
            	$pcp_url= base_url().'tours/details/'.base64_encode($origin);
            }
            if(empty($pcp_from)){ 
            	$pcp_from= 'Cars';
            }
                echo '<div class="item">';          
            ?>
            <div class="f-deal">
                <div class="f-deal-img">
                    <a href="<?=$pcp_url?>"><img class="star_rat"
                            src="<?php echo $GLOBALS['CI']->template->domain_images($perfect_car_packages[$i]['image']); ?>"
                            alt="car rental services" loading="lazy" /></a>
                </div>
                <a href="<?=$pcp_url?>">
                    <div class="car-info">
                        <h2><i class="fas fa-star"></i><?=$pcp_from?></h2>

                    </div>
                </a>
            </div>
            <?php       
                echo '</div>';       

      }
              ?>
        </div>
    </div>
</div>
</div>
<? } ?>



<div class="clearfix"></div>
<!--Top Transfer Offers start-->
<?php if($transfers_promo['status']): ?>
<div class="top-offer-sec transfers_modules custom-transfer-deals">
    <div class="container">
        <div id="custom_all" class="retmnus_transfers_1 owl-carousel owl-theme">

            <?php 
          
            if(!empty($transfers_promo['data'])):
                for($p=0; $p<count($transfers_promo['data']); $p++):
                    $transfers_promo_code=$transfers_promo['data'][$p];
                ?>
            <div class="col-xs-12">
                <div class="top-con-align">
                    <div class="custom-off-img">
                        <img src="<?php echo $GLOBALS ['CI']->template->domain_promo_images($transfers_promo_code['promo_code_image']); ?>"
                            alt="Book a tour to India" loading="lazy">
                    </div>
                    <div class="custom-off-cont">
                        <div class="custom_info_heading">
                            <h3>Get
                                <?=$transfers_promo_code['value']?><?=($transfers_promo_code['value_type']=='plus')? '': '%' ?>
                                OFF on the Transfers with Alkhaleej</h3>
                            <p>Use Coupon Code: <?=$transfers_promo_code['promo_code']?></p>
                        </div>


                        <div class="deals_img">
                            <img src="<?php echo $GLOBALS['CI']->template->domain_images('TMX1512291534825461logo-loginpg.png'); ?>"
                                alt="Book a tour to India" loading="lazy">
                        </div>
                        <div class="custom_info_button">
                            <div class="button_book">From
                                <?php if(!empty($transfers_promo_code['promo_for_city'])): ?>
                                <?=$transfers_promo_code['promo_for_city']?>
                                <?php else: ?>
                                <?=$transfers_promo_code['description']?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endfor;
            endif;
            ?>

        </div>
    </div>
</div>
<?php endif; ?>
<!--Top Transfer Offers end-->


<div class="clearfix"></div>
<div class="transfer_deals transfers_modules custom-transfer-services">

    <div class="pagehdwrap">
        <h3 class="pagehding"> Best Transfer Service</h3>
    </div>
    <div class="container">
        <div id="transfer-all" class="owl-carousel owlindex3 owl-theme">
            <?php 
     for ($i=0; $i < count($top_destination_tranfers); $i++) {
		       	$origin=$top_destination_tranfers[$i]['origin'];
		       	$from =	$top_destination_tranfers[$i]['destination_name'];
		       	$destination =	$top_destination_tranfers[$i]['origin'];
		       	$country_name =	$top_destination_tranfers[$i]['destination_name'];
		       	$category_id=0;
			$url2 = base_url().'general/pre_transferv1_search?&from='.$from.'&destination_id='.$destination.'&category_id='.$category_id.'';
	   ?>
            <div class="item">
                <a href="<?php echo $url2; ?>">
                    <div class="f-deal">
                        <div class="f-deal-img">
                            <a href="<?php echo $url2; ?>"><img class="star_rat"
                                    src="<?php echo $GLOBALS['CI']->template->domain_images($top_destination_tranfers[$i]['image_transfers']); ?>"
                                    alt="online travel booking services" loading="lazy" />
                        </div>
                        <div class="activities-info">
                            <h3> <i class="fas fa-star"></i> From <?php echo $country_name ?></h3>
                        </div>
                </a>
            </div>
            </a>
        </div>
        <?php  if($i==9)break; } ?>
    </div>
    <div class="explore_al">
        <a href="">Explore All</a>
    </div>
</div>
</div>
<div class="clearfix"></div>

<?php if(!empty($perfect_transfers_packages)){ ?>
<div class="transfer_deals_perfect transfers_modules custom-transfer-packages">

    <div class="pagehdwrap">
        <h3 class="pagehding"> Perfect <?php echo  ucfirst($selected_module); ?> Packages </h3>
    </div>
    <div class="container">
        <!-- <div id="transfer-all_perfect" class="owl-carousel owlindex3 owl-theme">-->
        <div class="packages_row">
            <?php 
      	  for ($i=0; $i < count($perfect_transfers_packages); $i++) {
      	  	$origin=$perfect_transfers_packages[$i]['origin'];
      	  		
		       	$from =	$perfect_transfers_packages[$i]['destination_name'];
		       	$destination =	$perfect_transfers_packages[$i]['origin'];
		       	$country_name =	$perfect_transfers_packages[$i]['destination_name'];
		       	$category_id=0;
			$url_perfect_transfer = base_url().'general/pre_transferv1_search?&from='.$from.'&destination_id='.$destination.'&category_id='.$category_id.'';
	      	 
      	  	?>
            <div class="col-md-4 col-xs-12 packages_row_inner">
                <a href="<?=$url_perfect_transfer?>">
                    <div class="f-deal-img">
                        <a href="<?=$url_perfect_transfer?>"><img class="star_rat"
                                src="<?php echo $GLOBALS['CI']->template->domain_images($perfect_transfers_packages[$i]['image_transfers']); ?>"
                                alt="Airport pick-up and drop-off Services" width="100%" loading="lazy" /></a>
                    </div>
                    <a href="<?php echo $url_perfect_transfer; ?>">
                        <div class="flight-info">
                            <h3><?=$country_name?></h3>
                        </div>
                    </a>
            </div>
            <?php if($i==9)break; } ?>
        </div>
    </div>
</div>
</div>
<? } ?>



<div class="clearfix"></div>
<?php if($activity_promo['status']): ?>
<!--Top Transfer Offers start-->
<div class="top-offer-sec activities_modules custom-activity-deals">
    <div class="container">
        <div id="custom_all" class="retmnus_activities_1 owl-carousel owl-theme">

            <?php 
          
            if(!empty($activity_promo['data'])):
                for($p=0; $p<count($activity_promo['data']); $p++):
                    $activity_promo_code=$activity_promo['data'][$p];
                ?>

            <div class="col-xs-12">
                <div class="top-con-align">
                    <div class="custom-off-img">
                        <img src="<?php echo $GLOBALS ['CI']->template->domain_promo_images($activity_promo_code['promo_code_image']); ?>"
                            alt="Book a tour to India" loading="lazy">
                    </div>
                    <div class="deals_img">
                        <img src="<?php echo $GLOBALS['CI']->template->domain_images('TMX1512291534825461logo-loginpg.png'); ?>"
                            alt="Book a tour to India" loading="lazy">
                    </div>
                    <div class="custom-off-cont">
                        <div class="custom_info_heading">
                            <h3>Get
                                <?=$activity_promo_code['value']?><?=($activity_promo_code['value_type']=='plus')? '': '%' ?>
                                OFF on the Activities with Alkhaleej</h3>
                            <p>Use Coupon Code: <?=$activity_promo_code['promo_code']?></p>
                        </div>



                        <div class="custom_info_button">
                            <div class="button_book">From
                                <?php if(!empty($activity_promo_code['promo_for_city'])): ?>
                                <?=$activity_promo_code['promo_for_city']?>
                                <?php else: ?>
                                <?=$activity_promo_code['description']?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endfor;
            endif;
            ?>

          




        </div>
    </div>
</div>

<!--Top Transfer Offers end-->
<?php endif; ?>

<div class="clearfix"></div>
<div class="activity_deals activities_modules custom-activity-services">

    <div class="pagehdwrap">
        <h3 class="pagehding"> Best Of Activities</h3>
    </div>
    <div class="container">
        <div id="activities-all" class="owl-carousel owlindex3 owl-theme">
            <?php 
     for ($i=0; $i < count($top_destination_activity); $i++) {
		       	
		       	$from =	$top_destination_activity[$i]['destination_name'];
		       	$destination =	$top_destination_activity[$i]['origin'];
		       	$country_name =	$top_destination_activity[$i]['destination_name'];
		       	$category_id=0;
			$url1 = base_url().'general/pre_sight_seen_search?&from='.$from.'&destination_id='.$destination.'&category_id='.$category_id.'';
	   ?>
            <div class="item">
                <a href="<?php echo $url1; ?>">
                    <div class="f-deal">
                        <div class="f-deal-img">
                            <a href="<?php  if(!empty($from)){ 
 						echo $url1; 
            	}else{
            		echo base_url(); ?>tours/details/<?php echo base64_encode($destination);
            	}
            ?>"><img class="star_rat"
                                    src="<?php echo $GLOBALS['CI']->template->domain_images($top_destination_activity[$i]['image_activity']); ?>"
                                    alt="Best Activities in al khaleej" loading="lazy" />
                        </div>
                        <div class="activities-info">
                            <?php  if(!empty($from)){  ?>
                            <h3> <i class="fa fa-binoculars"></i> <br /> <?php echo $country_name ?></h3>
                            <?php	}else{  ?>
                            <h2> <i class="fas fa-star"></i>Activities</h2>
                            <?php	}  ?>

                        </div>
                </a>
            </div>
            </a>
        </div>
        <?php  if($i==9)break; } ?>
    </div>
    <div class="explore_al">
        <a href="">Explore All</a>
    </div>
</div>
</div>
<div class="clearfix"></div>

<?php if(!empty($perfect_activity_packages)){ ?>
<div class="activities_deals_perfect activities_modules custom-activity-packages">

    <div class="pagehdwrap">
        <h3 class="pagehding"> Perfect <?php echo ucfirst($selected_module); ?> Packages </h3>
    </div>
    <div class="container">
        <div class="custom-activity">
            <?php 
      	  for ($i=0; $i < count($perfect_activity_packages); $i++) {
      	  	
      	  	
      	  		$from_ppa =	$perfect_activity_packages[$i]['destination_name'];
		       	$destination_ppa =	$perfect_activity_packages[$i]['origin'];
		       	$country_name_ppa =	$perfect_activity_packages[$i]['destination_name'];
		       	$category_id=0;
			    $url_ppa = base_url().'general/pre_sight_seen_search?&from='.$from_ppa.'&destination_id='.$destination_ppa.'&category_id='.$category_id.'';
			    
			    if(empty($from_ppa)){
			        $url_ppa = base_url().'tours/details/'.base64_encode($destination_ppa);
			        $country_name_ppa ='Activities';
			    }
      	  	
	      	  		echo '<div class="col-xs-4">';
	      	
      	  	?>
            <div class="f-deal">
                <a href="<?=$url_ppa?>">
                    <div class="f-deal-img">
                        <a href="<?=$url_ppa?>"><img class="star_rat"
                                src="<?php echo $GLOBALS['CI']->template->domain_images($perfect_activity_packages[$i]['image_activity']); ?>"
                                alt="Best Activities in al khaleej" loading="lazy" /></a>
                    </div>
                    <a href="<?=$url_ppa?>">
                        <div class="flight-info">
                            <h3> <i class="fas fa-star"></i> <?=$country_name_ppa?></h3>
                            <button class="check-activity">Check Now</button>
                        </div>
                    </a>
            </div>
            <?php
        	      	  		echo '</div>';
	   
	    }
	      	  	?>
        </div>
    </div>
</div>
</div>
<? } ?>




<!--<div class="clearfix"></div>
<div class="advertisement">
	<div class="container">
		<div class="pagehdwrap">
            <h3 class="pagehding">Perfect Deals</h3>
      </div>
		<div id="TopAirLine_new" class="topAirSlider owl-carousel owl-theme">
			<?php 
			for ($i=0; $i <count($adv_banner) ; $i++) { 
				$adv_text=$adv_banner[$i]['adv_text'];
				$adv_image=$adv_banner[$i]['image'];
				$adv_module=$adv_banner[$i]['module'];				
	      	  		echo '<div class="item">';	
			 ?>
		<div class="item col-md-12">
			<div class="advertise_image">
				<img src="<?php echo $GLOBALS['CI']->template->domain_images($adv_image); ?>" alt="best site to book flight tickets" loading="lazy">
			</div>
		</div>
		  <?php  
	      	  		echo '</div>';	  

	    }
		?>		
</div>
	</div>
</div>-->






<!--hotels-->

<div class="clearfix"></div>
<?php if($selected_module=="villasapartment"){ ?>


<div class="perhldys advertisement hotels_villa_section">

    <div class="pagehdwrap">
        <h2 class="pagehding">Top Hotel Destinations</h2>
    </div>
    <div class="container">
        <div class="villasapts hotels_section">
            <div class="slick-wrapper">
                <div id="slick_hotels">
                    <!--block1-->
                    <?php  //debug($homepage_hotel_crs_display);exit; 
$deltacommand=count($top_villa_display)/2;
     for ($i=0; $i < count($top_villa_display); $i++) {
    if(!empty($top_villa_display[$i]['RPrice'])){

?>
                    <div class="slide-item">
                        <div class="topone htd-wrap2c">
                            <input type="hidden" class="top_des_id2c"
                                value="<?php echo $top_villa_display[$i]['cid']?>">
                            <input type="hidden" class="top-des-val2c hand-cursor"
                                value="<?=hotel_suggestion_value($top_villa_display[$i]['city'], $top_villa_display[$i]['country'])?>">
                            <a href="#" class="inspd2 effect-lexi">
                                <div class="imgeht2">
                                    <div class="dealimg">
                                        <img class="lazy lazy_loader"
                                            data-src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$top_villa_display[$i]['image'];?>"
                                            alt="Villas"
                                            src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$top_villa_display[$i]['image'];?>" />
                                    </div>
                                    <div class="absint2 absintcol1 ">
                                        <div class="absint2 absintcol2 ">
                                            <div class="absinn">
                                                <div class="smilebig2">
                                                    <span class="star-hotel"><i class="fas fa-star"></i></span>
                                                    <h3>
                                                        <?=$top_villa_display[$i]['city']?>, <span
                                                            class="country"><?=$top_villa_display[$i]['country']?></span>
                                                    </h3>


                                                </div>

                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </a>
                        </div>
                    </div>

                    <?php }
    } ?>
                    <!--end block1-->

                </div>
            </div>
        </div>







        <!--end block1-->

    </div>
</div>
</div>



</div>
</div>
<?php
}
?>

<!--hotels end-->





<!--Villas-->

<div class="clearfix"></div>
<?php if($selected_module=="villasapartment"){ ?>


<div class="perhldys advertisement">

    <div class="pagehdwrap">
        <h2 class="pagehding">Top Villa Destinations</h2>
    </div>
    <div class="container">
        <div class="villasapts villas_section">
            <div class="slick-wrapper">
                <div id="slick_villas">
                    <!--block1-->
                    <?php  //debug($homepage_hotel_crs_display);exit; 
$deltacommand=count($top_villa_display)/2;
     for ($i=0; $i < count($top_villa_display); $i++) {
    if(!empty($top_villa_display[$i]['RPrice'])){

?>
                    <div class="slide-item">
                        <div class="topone htd-wrap2c">
                            <input type="hidden" class="top_des_id2c"
                                value="<?php echo $top_villa_display[$i]['cid']?>">
                            <input type="hidden" class="top-des-val2c hand-cursor"
                                value="<?=hotel_suggestion_value($top_villa_display[$i]['city'], $top_villa_display[$i]['country'])?>">
                            <a href="#" class="inspd2 effect-lexi">
                                <div class="imgeht2">
                                    <div class="dealimg">
                                        <img class="lazy lazy_loader"
                                            data-src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$top_villa_display[$i]['image'];?>"
                                            alt="Villas"
                                            src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$top_villa_display[$i]['image'];?>" />
                                    </div>
                                    <div class="absint2 absintcol1 ">
                                        <div class="absint2 absintcol2 ">
                                            <div class="absinn">
                                                <div class="smilebig2">
                                                    <h3><?php echo $top_villa_display[$i]['hotel_name']; ?>,
                                                        <br /><span>
                                                            <?=$top_villa_display[$i]['city']?>,<?=$top_villa_display[$i]['country']?></span>
                                                    </h3>
                                                    <div class="smileredirect">
                                                        <i class="fas fa-arrow-right"></i>

                                                    </div>
                                                </div>

                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </a>
                        </div>
                    </div>

                    <?php }
    } ?>
                    <!--end block1-->

                </div>
            </div>
        </div>







        <!--end block1-->

    </div>
</div>
</div>



</div>
</div>
<?php
}
?>

<!--apartments end-->

<?php if($selected_module=="villasapartment"){ ?>


<div class="perhldys advertisement apartment_villas">

    <div class="pagehdwrap">
        <h2 class="pagehding">Top Apartment Destinations</h2>
    </div>
    <div class="container">
        <div class="villasapts">
            <div class="slick-wrapper">
                <div id="slick2">
                    <!--block1-->
                    <?php  //debug($homepage_hotel_crs_display);exit; 
$deltacommand=count($top_Apts_display)/2;
     for ($i=0; $i < count($top_Apts_display); $i++) {
    if(!empty($top_Apts_display[$i]['RPrice'])){

?>
                    <div class="slide-item">
                        <div class="topone htd-wrap2c">
                            <input type="hidden" class="top_des_id2c" value="<?php echo $top_Apts_display[$i]['cid']?>">
                            <input type="hidden" class="top-des-val2c hand-cursor"
                                value="<?=hotel_suggestion_value($top_Apts_display[$i]['city'], $top_Apts_display[$i]['country'])?>">
                            <a href="#" class="inspd2 effect-lexi">
                                <div class="imgeht2">
                                    <div class="dealimg">
                                        <img class="lazy lazy_loader"
                                            data-src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$top_Apts_display[$i]['image'];?>"
                                            alt="Villas"
                                            src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$top_Apts_display[$i]['image'];?>" />
                                    </div>
                                    <div class="absint2 absintcol1 ">
                                        <div class="absint2 absintcol2 ">
                                            <div class="absinn">
                                                <div class="smilebig2">

                                                    <h4 class="all_cont">
                                                        <?=$top_Apts_display[$i]['city']?>
                                                    </h4>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <figcaption>
                                        <div class="deal_txt">
                                            <div class="col-xs-6 nopad">

                                                <h4 class="deal_price"><span>Starting at</span> <strong>$
                                                    </strong>
                                                    <?php echo $top_Apts_display[$i]['RPrice']; ?>
                                                </h4>
                                            </div>

                                            <div class="col-xs-6 nopad">
                                                <h4></h4>
                                                <!-- <a class="package_dets_btn" href="#">
                                            View details
                                        </a> -->
                                            </div>
                                        </div>
                                    </figcaption>
                                </div>
                            </a>
                        </div>
                    </div>

                    <?php }
    } ?>
                    <!--end block1-->

                </div>
            </div>
        </div>



    </div>
</div>
<?php
}
?>
<div class="clearfix"></div>
<!--apartments-->






<!--resorts-->

<div class="clearfix"></div>
<?php if($selected_module=="villasapartment"){ ?>


<div class="perhldys advertisement resort_villa_section">

    <div class="pagehdwrap">
        <h2 class="pagehding">Top Resort Destinations</h2>
    </div>
    <div class="container">
        <div class="villasapts resorts_section">
            <div class="slick-wrapper">
                <div id="slick_resorts">
                    <!--block1-->
                    <?php  //debug($homepage_hotel_crs_display);exit; 
$deltacommand=count($top_resort_display)/2;
     for ($i=0; $i < count($top_resort_display); $i++) {
    if(!empty($top_resort_display[$i]['RPrice'])){

?>
                    <div class="slide-item">
                        <div class="topone htd-wrap2c">
                            <input type="hidden" class="top_des_id2c"
                                value="<?php echo $top_resort_display[$i]['cid']?>">
                            <input type="hidden" class="top-des-val2c hand-cursor"
                                value="<?=hotel_suggestion_value($top_resort_display[$i]['city'], $top_resort_display[$i]['country'])?>">
                            <a href="#" class="inspd2 effect-lexi">
                                <div class="imgeht2">
                                    <div class="dealimg">
                                        <img class="lazy lazy_loader"
                                            data-src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$top_resort_display[$i]['image'];?>"
                                            alt="Villas"
                                            src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$top_resort_display[$i]['image'];?>" />
                                    </div>
                                    <div class="resort-row">
                                        <div class="resort-inner">
                                            <h3><?php echo $top_villa_display[$i]['hotel_name']; ?></h3>
                                            <h4 class="all_cont"><i class="fas fa-map-marker-alt"></i>
                                                <?=$top_villa_display[$i]['city']?>
                                            </h4>
                                            <h5 class="deal_price"><span>Starting at</span> <strong>$
                                                </strong>
                                                <?php echo $top_resort_display[$i]['RPrice']; ?>
                                            </h5>
                                        </div>
                                        <div class="resort-footer">
                                            <div class="resort-details">
                                                <h5>View Details</h5>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </a>
                        </div>
                    </div>

                    <?php }
    } ?>
                    <!--end block1-->

                </div>
            </div>
        </div>







        <!--end block1-->

    </div>
</div>
</div>



</div>
</div>
<?php
}
?>

<!--resorts end-->






<!--cabins-->

<div class="clearfix"></div>
<?php if($selected_module=="villasapartment"){ ?>


<div class="perhldys advertisement cabin_villa_section">

    <div class="pagehdwrap">
        <h2 class="pagehding">Top Cabin Destinations</h2>
    </div>
    <div class="container">
        <div class="villasapts cabins_section">
            <div class="slick-wrapper">
                <div id="slick_cabins">
                    <!--block1-->
                    <?php  //debug($homepage_hotel_crs_display);exit; 
$deltacommand=count($top_cabin_display)/2;
     for ($i=0; $i < count($top_cabin_display); $i++) {
    if(!empty($top_cabin_display[$i]['RPrice'])){

?>
                    <div class="slide-item">
                        <div class="topone htd-wrap2c">
                            <input type="hidden" class="top_des_id2c"
                                value="<?php echo $top_cabin_display[$i]['cid']?>">
                            <input type="hidden" class="top-des-val2c hand-cursor"
                                value="<?=hotel_suggestion_value($top_cabin_display[$i]['city'], $top_cabin_display[$i]['country'])?>">
                            <a href="#" class="inspd2 effect-lexi">
                                <div class="imgeht2">
                                    <div class="dealimg">
                                        <img class="lazy lazy_loader"
                                            data-src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$top_cabin_display[$i]['image'];?>"
                                            alt="Villas"
                                            src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/uploads/hotel/'.$top_cabin_display[$i]['image'];?>" />
                                    </div>
                                    <div class="absint2 absintcol1 ">
                                        <div class="absint2 absintcol2 ">
                                            <div class="absinn">
                                                <div class="smilebig2">
                                                    <img class="lazy lazy_loader"
                                                        data-src="<?=base_url().'extras/system/template_list/template_v3/images/cabin_icon.png';?>">
                                                    <h3>
                                                        <?=$top_cabin_display[$i]['city']?>
                                                    </h3>


                                                </div>

                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </a>
                        </div>
                    </div>

                    <?php }
    } ?>
                    <!--end block1-->

                </div>
            </div>
        </div>







        <!--end block1-->

    </div>
</div>
</div>



</div>
</div>
<?php
}
?>

<!--cabins end-->
<!--
<div class="ychoose">
    <div class="container">
        <div class="pagehdwrap">
            <h3 class="pagehding">We Offer</h3>
        </div>
        <div class="allys">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="threey">
                    <div class="apritopty"><img
                            src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/route.png"
                            alt="Book a tour to India" loading="lazy"></div>
                    <div class="dismany">
                        <div class="number">Route Selection</div>
                        <div class="hedsprite">Alkhaleej is an all-in-one travel booking service provider for
                            destinations across the globe.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="threey">
                    <div class="apritopty"><img
                            src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/support.png"
                            alt="Book a tour to India" loading="lazy"></div>
                    <div class="dismany">
                        <div class="number">24/7 Support</div>
                        <div class="hedsprite">Alkhaleej is a global flight tracking service that provides you with
                            real-time information about thousands of aircraft around the world.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="threey" alt="">
                    <div class="apritopty"><img
                            src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/choice.png"
                            alt="Book a tour to India" loading="lazy"></div>
                    <div class="dismany">
                        <div class="number">Wide choice</div>
                        <div class="hedsprite">Alkhaleej is an all-in-one travel booking service provider for
                            destinations across the globe.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="threey" alt="">
                    <div class="apritopty"><img
                            src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/tickets.png"
                            alt="Book a tour to India" loading="lazy"></div>
                    <div class="dismany">
                        <div class="number">Buy Tickets</div>
                        <div class="hedsprite">Alkhaleej is an all-in-one travel booking service provider for
                            destinations across the globe.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
-->





<!--transfer crs image -->

<div class="clearfix"></div>
<?php if($selected_module=="private-transfer"){ ?>
<div class="transfer_deals_perfect">
    <div class="container">
        <img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/flight_crs/private-crs-transfers.jpg"
            width="100%" alt="">
    </div>
</div>
<? } ?>


<!--transfer crs image end-->


<!--transfer deals-->

<div class="clearfix"></div>
<?php if($selected_module=="private-transfer"){ ?>
<div class="villasapts transfer_deals_crs">

    <div class="pagehdwrap">
        <h3 class="pagehding">Top Private Transfer Deals</h3>
    </div>
    <div class="container">
        <div id="transfer_crs_1" class="owlindex3 owl-theme">
            <div class="f-deal slide" data-slide-index="0">
                <a href="">
                    <div class="f-deal-img">
                        <a href=""><img class="star_rat"
                                src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/flight_crs/crs_miami.jpg"
                                alt="online travel booking services" loading="lazy" /></a>
                    </div>
                    <a href="#">
                        <div class="flight-info">
                            <h3>
                                <span>From</span><br /> Miami
                            </h3>
                        </div>
                    </a>
            </div>

            <div class="f-deal slide" data-slide-index="0">
                <a href="">
                    <div class="f-deal-img">
                        <a href=""><img class="star_rat"
                                src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/flight_crs/crs_melbourne.jpg"
                                alt="online travel booking services" loading="lazy" /></a>
                    </div>
                    <a href="#">
                        <div class="flight-info">
                            <h3>
                                <span>From</span><br /> Melbourne
                            </h3>
                        </div>
                    </a>
            </div>

            <div class="f-deal slide" data-slide-index="0">
                <a href="">
                    <div class="f-deal-img">
                        <a href=""><img class="star_rat"
                                src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/flight_crs/crs_croatia.jpg"
                                alt="online travel booking services" loading="lazy" /></a>
                    </div>
                    <a href="#">
                        <div class="flight-info">
                            <h3>
                                <span>From</span><br /> Croatia
                            </h3>
                        </div>
                    </a>
            </div>

            <div class="f-deal slide" data-slide-index="0">
                <a href="">
                    <div class="f-deal-img">
                        <a href=""><img class="star_rat"
                                src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/flight_crs/crs_miami.jpg"
                                alt="online travel booking services" loading="lazy" /></a>
                    </div>
                    <a href="#">
                        <div class="flight-info">
                            <h3>
                                <span>From</span><br /> Miami
                            </h3>
                        </div>
                    </a>
            </div>
            <div class="f-deal slide" data-slide-index="0">
                <a href="">
                    <div class="f-deal-img">
                        <a href=""><img class="star_rat"
                                src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/flight_crs/crs_croatia.jpg"
                                alt="online travel booking services" loading="lazy" /></a>
                    </div>
                    <a href="#">
                        <div class="flight-info">
                            <h3>
                                <span>From</span><br /> Croatia
                            </h3>
                        </div>
                    </a>
            </div>


        </div>
    </div>
</div>
<? } ?>


<!--transfer deals end-->



<!--transfer deals second-->

<div class="clearfix"></div>
<?php if($selected_module=="private-transfer"){ ?>
<div class="villasapts transfer_crs_packages">

    <div class="pagehdwrap">
        <h3 class="pagehding">Perfect Private Transfer Packages</h3>
    </div>
    <div class="container">
        <div id="transfer_crs_2" class="owlindex3 owl-theme">
            <div class="f-deal slide" data-slide-index="0">
                <a href="">
                    <div class="f-deal-img">
                        <a href=""><img class="star_rat"
                                src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/flight_crs/crs_armenia.jpg"
                                alt="online travel booking services" loading="lazy" /></a>
                    </div>
                    <a href="#">
                        <div class="flight-info">
                            <h3>
                                <span>From</span> Armenia
                            </h3>
                        </div>
                    </a>
            </div>

            <div class="f-deal slide" data-slide-index="0">
                <a href="">
                    <div class="f-deal-img">
                        <a href=""><img class="star_rat"
                                src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/flight_crs/crs_dubai.jpg"
                                alt="online travel booking services" loading="lazy" /></a>
                    </div>
                    <a href="#">
                        <div class="flight-info">
                            <h3>
                                <span>From</span> Dubai
                            </h3>
                        </div>
                    </a>
            </div>

            <div class="f-deal slide" data-slide-index="0">
                <a href="">
                    <div class="f-deal-img">
                        <a href=""><img class="star_rat"
                                src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/flight_crs/crs_croatia.jpg"
                                alt="online travel booking services" loading="lazy" /></a>
                    </div>
                    <a href="#">
                        <div class="flight-info">
                            <h3>
                                <span>From</span> Croatia
                            </h3>
                        </div>
                    </a>
            </div>

            <div class="f-deal slide" data-slide-index="0">
                <a href="">
                    <div class="f-deal-img">
                        <a href=""><img class="star_rat"
                                src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/flight_crs/crs_miami.jpg"
                                alt="online travel booking services" loading="lazy" /></a>
                    </div>
                    <a href="#">
                        <div class="flight-info">
                            <h3>
                                <span>From</span> Miami
                            </h3>
                        </div>
                    </a>
            </div>



        </div>
    </div>
</div>
<? } ?>


<!--transfer deals second end-->


<!--transfer crs choose us -->

<div class="clearfix"></div>
<?php if($selected_module=="private-transfer"){ ?>
<div class="transfer_deals_choose">

    <div class="pagehdwrap">
        <h3 class="pagehding"> Why Choose Us?</h3>
    </div>
    <div class="container">
        <div class="boxed-row-transfer">
            <div class="col-md-3 col-xs-12">
                <div class="crs_choose">
                    <img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/flight_crs/crs_transfer.png"
                        width="100%" alt="">
                    <p>Best Transfers Provided</p>
                </div>
            </div>
            <div class="col-md-3 col-xs-12">
                <div class="crs_choose">
                    <img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/flight_crs/crs_price.png"
                        width="100%" alt="">
                    <p>Best Price Guaranteed</p>
                </div>
            </div>
            <div class="col-md-3 col-xs-12">
                <div class="crs_choose">
                    <img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/flight_crs/crs_time.png"
                        width="100%" alt="">
                    <p>On Time Pick Up & Drop</p>
                </div>
            </div>
            <div class="col-md-3 col-xs-12">
                <div class="crs_choose">
                    <img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/flight_crs/crs_customer.png"
                        width="100%" alt="">
                    <p>100% Customer Satisfaction</p>
                </div>
            </div>
        </div>
    </div>
</div>
<? } ?>


<!--transfer crs choose us end-->


<div class="clearfix"></div>
<!---popular holiday mobile end-->
<div class="clearfix"></div>
<div class="top_airline">
    <div class="container">
        <div class="org_row">
            <div class="pagehdwrap">
                <h3 class="pagehding">Promocodes</h3>
                <span>Get offers and discounts on latest promocode.</span>
            </div>
            <div id="all_deal" class="owl-carousel owlindex3 owl-theme">
                <?php foreach($promo_code_list as $pcode) { ?>
                <div class="gridItems">
                    <div class="outerfullfuture flight_dl">
                        <h4><i class="fas fa-circle"></i> <?php echo strtoupper($pcode['module']); ?></h4>
                        <div class="thumbnail_deal thumbnail_small_img">
                            <img class=""
                                src="<?php echo $GLOBALS['CI']->template->template_images('promocode/'.$pcode['promo_code_image'])?>"
                                alt="Lazy Owl Image" loading="lazy">
                            <div class="deals_info">
                                <div class="deals_info_heading">
                                    <h1>PROMO CODE: <?php echo $pcode['promo_code']; ?></h1>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="cust-holidy-pack">
    <div class="container">
        <div class="col-md-12 col-xs-12 cust-holidy-bg nopad">
            <div class="cust-holidy-sec">
                <div class="col-md-7 col-xs-12 nopad">
                    <img src="<?php echo $GLOBALS['CI']->template->domain_images('Group 16496.svg'); ?>"
                        alt="Book a tour to India" loading="lazy">
                </div>
                <div class="col-md-5 col-xs-12 nopad">
                    <div class="cust-holi-form">
                        <form method="post" action="" id="generaltourenquiry">
                            <p class="success_msg" style="font-size: 30px;font-weight: bold;"></p>
                            <div class="form-row">
                                <div class="form-group gnrlfrm inpname" style="height: 50px;">
                                    <input type="text" class="form-control cust-holi-form-lay alpha"
                                        style="color: #505565" id="inputAddress" placeholder="Full Name" name="tname"
                                        id="tname" maxlength="25" required>
                                    <span id="tname_error" style="color:red;"></span>
                                </div>
                            </div>
                            <div class="form-row ">
                                <div class="form-group gnrlfrm col-md-6 bxpd-rt" style="" id="from_place_div">
                                    <input type="text" class="form-control cust-holi-form-lay mar-rght alpha"
                                        style="color: #505565" placeholder="Destination From Address" name="fromplace"
                                        id="fromplace" maxlength="40" required>
                                    <span id="fromplace_error" style="color:red;"></span>
                                </div>
                                <div class="form-group gnrlfrm col-md-6 bxpd-lft" style="">
                                    <input type="text" class="form-control cust-holi-form-lay alpha"
                                        style="color: #505565" placeholder="Destination To Address" name="toplace"
                                        id="toplace" maxlength="40" required="">
                                    <span id="toplace_error" style="color:red;"></span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group gnrlfrm inpdate" style="height: 105px;">
                                    <input type="text" class="form-control cust-holi-form-lay" style="color: #505565"
                                        placeholder="Departure Date" name="departure_date" id="departure_date"
                                        readonly="" required="">
                                    <span id="departure_date_error" style="color:red;"></span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group gnrlfrm col-md-6 bxpd-rt" style="" id="email454_div">
                                    <input type="email" class="form-control cust-holi-form-lay mar-rght"
                                        style="color: #505565" placeholder="Enter Your Email Address" name="email"
                                        id="email454" required>
                                    <span id="email454_error" style="color:red;"></span>
                                </div>
                                <div class="form-group gnrlfrm col-md-6 bxpd-lft" style="">
                                    <input type="text" class="form-control cust-holi-form-lay _numeric_only"
                                        style="color: #505565" placeholder="Enter Your Contact Number" name="phone"
                                        maxlength='10' id="phone" required>
                                    <span id="phone_error" style="color:red;"></span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group gnrlfrm col-md-6 bxpd-rt" style="margin-bottom: 0px"
                                    id="buget_error_div">
                                    <select id="buget" name="buget" class="form-control cust-holi-form-lay mar-rght"
                                        required="">
                                        <option selected value="" style="display: none">Select Budget</option>
                                        <option value="all">All</option>
                                        <option value="100-500">100-500</option>
                                        <option value="500-1000">500-1000</option>
                                        <option value="1000-5000">1000-5000</option>
                                        <option value="5000">5000</option>
                                    </select>
                                    <span id="buget_error" style="color:red;"></span>
                                </div>
                                <div class="form-group gnrlfrm col-md-6 bxpd-lft" style="margin-bottom: 0px">
                                    <select name="duration" id="durationst" class="form-control cust-holi-form-lay"
                                        required>
                                        <option selected value="" style="display: none">Select Duration</option>
                                        <option value="1-3">1-3</option>
                                        <option value="4-7">4-7</option>
                                        <option value="8-12">8-12</option>
                                        <option value="12">12</option>
                                    </select>
                                    <span id="duration_error" style="color:red;"></span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group gnrlfrm" style="margin-bottom: 0px">
                                    <textarea type="text" class="form-control cust-holi-form-lay alpha"
                                        style="color: #505565" rows="6" placeholder="Write your message" id="message"
                                        name="message" required maxlength="200"></textarea>
                                    <span id="message_error" style="color:red;"></span>
                                </div>
                            </div>
                            <button type="submit" class="btn cust-holi-btn" id="toursubmit">Submit</button>
                            <button type="reset" class="btn cust-holi-btn" style="margin: 10px 15px;"
                                id="tourenquryformreset">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>


<?=$this->template->isolated_view('share/js/lazy_loader')?>
<script>
$(document).ready(function() {
    var owl3 = $("#owl-demo3");

    owl3.owlCarousel({
        itemsCustom: [
            [0, 1],
            [450, 2],
            [551, 3],
            [700, 4],
            [1000, 5],
            [1200, 6],
            [1400, 6],
            [1600, 6]
        ],
        navigation: false
    });
    var owl5 = $("#holiday_destinations");
    owl5.owlCarousel({
        itemsCustom: [
            [0, 1],
            [450, 2],
            [551, 3],
            [700, 4],
            [1000, 4],
            [1200, 4],
            [1400, 4],
            [1600, 4]
        ],
        navigation: true,
        pagination: false

    });
});
</script>
<script>
$(document).ready(function() {
    var owl9 = $("#owl-demo9");
    owl9.owlCarousel({
        itemsCustom: [
            [0, 1],
            [450, 1],
            [551, 2],
            [700, 3],
            [1000, 3],
            [1200, 3],
            [1400, 3],
            [1600, 3]
        ],
        navigation: true,
    });
});
</script>
<!---mason laypout-->
<script>
$(document).ready(function() {
    $(".dropdown").on("hide.bs.dropdown", function() {
        $(".btn").html('Dropdown <span class="caret"></span>');
    });
    $(".dropdown").on("show.bs.dropdown", function() {
        $(".btn").html('Dropdown <span class="caret caret-up"></span>');
    });
});

function copyElementText(data) {
    var text = document.getElementById('text' + data).innerText;
    var elem = document.createElement("textarea");
    document.body.appendChild(elem);
    elem.value = text;
    elem.select();
    document.execCommand("copy");
    document.body.removeChild(elem);
}
</script>
<script>
$(document).ready(function() {

    $("#owl-demomobile").owlCarousel({
        autoPlay: 3000, //Set AutoPlay to 3 seconds 
        items: 4,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });

    //var owl9 = $("#owl-demomobile");
    //owl9.owlCarousel({      
    //   itemsCustom : [
    //      [0, 1],
    //      [450, 1],
    //      [551, 2],
    //      [700, 2],
    //      [1000, 4],
    //      [1200, 4],
    //     [1400, 4],
    //     [1600, 4]
    // ],
    // navigation : true,
    // dots:false,
    // });


    $("#fligt-all").owlCarousel({
        autoPlay: 3000, //Set AutoPlay to 3 seconds 
        items: 4,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $("#fligt-all_perfect").owlCarousel({
        autoPlay: 3000, //Set AutoPlay to 3 seconds
        items: 4,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $("#fligt-all_perfect_new").owlCarousel({
        autoPlay: 3000, //Set AutoPlay to 3 seconds 
        items: 4,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $("#hotel-all_perfect").owlCarousel({
        autoPlay: 3000, //Set AutoPlay to 3 seconds
        items: 3,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $("#car-all_perfect").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds

        items: 3,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $("#transfer-all_perfect").owlCarousel({
        autoPlay: 3000, //Set AutoPlay to 3 seconds
        items: 4,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $("#activities-all_perfect").owlCarousel({
        autoPlay: 3000, //Set AutoPlay to 3 seconds
        items: 4,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $("#car-all").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds

        items: 4,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $("#transfer-all").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds

        items: 4,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $("#activities-all").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds

        items: 4,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $(".retmnus").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds

        items: 3,
        itemsDesktop: [1199, 2],
        itemsDesktopSmall: [979, 2],
        itemsTablet: [650, 1],
        itemsMobile: [550, 1],
        itemsMobile: [450, 1],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $(".retmnus_flights_1").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds
        loop: true,
        items: 2,
        itemsDesktop: [1199, 2],
        itemsDesktopSmall: [979, 2],
        itemsTablet: [650, 1],
        itemsMobile: [550, 1],
        itemsMobile: [450, 1],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $(".retmnus_hotels_1").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds
        loop: true,
        items: 2,
        itemsDesktop: [1199, 2],
        itemsDesktopSmall: [979, 2],
        itemsTablet: [650, 1],
        itemsMobile: [550, 1],
        itemsMobile: [450, 1],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $(".retmnus_cars_1").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds
        loop: true,
        items: 2,
        itemsDesktop: [1199, 2],
        itemsDesktopSmall: [979, 2],
        itemsTablet: [650, 1],
        itemsMobile: [550, 1],
        itemsMobile: [450, 1],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $(".retmnus_holidays_1").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds
        loop: true,
        items: 2,
        itemsDesktop: [1199, 2],
        itemsDesktopSmall: [979, 2],
        itemsTablet: [650, 1],
        itemsMobile: [550, 1],
        itemsMobile: [450, 1],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $(".retmnus_transfers_1").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds
        loop: true,
        items: 2,
        itemsDesktop: [1199, 2],
        itemsDesktopSmall: [979, 2],
        itemsTablet: [650, 1],
        itemsMobile: [550, 1],
        itemsMobile: [450, 1],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $(".retmnus_activities_1").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds
        loop: true,
        items: 2,
        itemsDesktop: [1199, 2],
        itemsDesktopSmall: [979, 2],
        itemsTablet: [650, 1],
        itemsMobile: [550, 1],
        itemsMobile: [450, 1],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $("#TopAirLine_new").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds

        items: 4,
        itemsDesktop: [1199, 4],
        itemsDesktopSmall: [979, 2],
        itemsTablet: [650, 2],
        itemsMobile: [550, 1],
        itemsMobile: [450, 1],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $(".retmnus_new").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds

        items: 3,
        itemsDesktop: [1199, 2],
        itemsDesktopSmall: [979, 2],
        itemsTablet: [650, 1],
        itemsMobile: [550, 1],
        itemsMobile: [450, 1],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $(".retmnus_car").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds

        items: 3,
        itemsDesktop: [1199, 2],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $(".retmnus_transfer").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds

        items: 3,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $(".retmnus_activities").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds

        items: 3,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $(".retmnus_holiday").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds

        items: 3,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
});

$('._numeric_only').on('keydown focus blur keyup change cut copy paste', function(e) {
    isNumber(e, e.keyCode, e.ctrlKey, e.metaKey, e.shiftKey);
});

$(document).on('keyup', '#phone', function() {
    var numvalue = $("#phone").val();
    let isnum = /^\d+$/.test(numvalue);
    if (!isnum) {
        $("#phone").val('');
    }
    // code
});
</script>
<script>
$('#accordion').click(function() {
    $('.collapseOne').toggle('1000');
    $("i", this).toggleClass("fa-angle-down fa-angle-up");
});

$("#hotelModules").on("click", function() {
    $(".hotel_modules2").hide();
    $(".hotel_modules").show();
    $("#privatecar").hide();
    $(".testte").hide();
});
$(".hoteltabModules2").on("click", function() {
    $(".hotel_modules").hide();
    $(".hotel_modules2").show();
    $(".testte").show();
    $("#privatecar").hide();

});
$("#flightModules").on("click", function() {
    $(".hotel_modules").hide();
    $(".hotel_modules2").hide();
    $("#privatecar").hide();
    $(".testte").hide();
});
$("#privatecarmodules").on("click", function() {
    $(".hotel_modules").hide();
    $(".hotel_modules2").hide();
    $("#privatecar").show();
});
$("#carsModules").on("click", function() {
    $(".hotel_modules").hide();
    $(".hotel_modules2").hide();
    $(".testte").hide();
    $("#privatecar").hide();
});
$("#transfersModules").on("click", function() {
    $(".hotel_modules").hide();
    $(".hotel_modules2").hide();
    $(".testte").hide();
    $("#privatecar").hide();
});
$("#activitiesModules").on("click", function() {
    $(".hotel_modules").hide();
    $(".hotel_modules2").hide();
    $(".testte").hide();
    $("#privatecar").hide();
});
$("#holidaysModules").on("click", function() {
    $(".hotel_modules").hide();
    $(".hotel_modules2").hide();
    $("#privatecar").hide();
});
$("#privatecar").on("click", function() {
    $(".hotel_modules").hide();
    $(".hotel_modules2").hide();
    $(".testte").hide();
    $("#privatecar").show();
});
$("#privatejet").on("click", function() {
    $(".hotel_modules").hide();
    $(".hotel_modules2").hide();
    $(".testte").hide();
    $("#privatecar").hide();
});
$("#privatetransfer").on("click", function() {
    $(".hotel_modules").hide();
    $(".hotel_modules2").hide();
    $(".testte").hide();
    $("#privatecar").hide();
});
$(".ptclick").on("click", function() {
    $(".hotel_modules").hide();
    $(".hotel_modules2").hide();
    $(".testte").hide();
    $("#privatecar").hide();
});
</script>
<script>
$(document).ready(function() {
    var c_module = "<?=$selected_module?>";
    var c_module = (c_module != '') ? c_module : 'flights';
    module_content(c_module);

    $('.script_data').click(function() {
        var type = $(this).data('id');
        module_content(type);
    });

    function module_content(c_module) {

        var ret = {
            "flights": "flight_modules",
            "hotels": "hotel_modules",
            "transfers": "transfers_modules",
            "activities": "activities_modules",
            "buses": "buses_modules",
            "car": "cars_modules",
            "holidays": "holidays_modules",
            "accommodation": "hotel_modules2",
            "privatejet": "jet_modules"
        };

        $.each(ret, function(key, value) {
            if (key == c_module) {
                $('.at_' + key).addClass('active');
                $('.' + value).show();
            } else {
                $('.at_' + key).removeClass('active');
                $('.' + value).hide();
            }
        });

    }
});
</script>
<script>
$(document).ready(function() {
    var el = $('.nn');

    var carousel;
    var slidesPerPage = 6;
    var carouselOptions = {
        margin: 20,
        nav: true,
        dots: true,
        navigation: true,
        slideBy: '8',
        slideBy: slidesPerPage,
        scrollPerPage: true,
        animateOut: 'fadeOut',
        smartSpeed: 200,
        slideSpeed: 500,
        itemsCustom: [
            [0, 1],
            [450, 1],
            [551, 1],
            [700, 3],
            [1000, 4],
            [1200, 4],
            [1400, 4],
            [1600, 4]
        ],
        responsive: {
            0: {
                items: 1,
                rows: 1 //custom option not used by Owl Carousel, but used by the algorithm below
            },
            768: {
                items: 2,
                rows: 3 //custom option not used by Owl Carousel, but used by the algorithm below
            },
            991: {
                items: 4,
                rows: 2 //custom option not used by Owl Carousel, but used by the algorithm below
            }
        }
    };
    var i = 0;
    $(".f-deal.slide").each(function() {
        $(this).attr("data-slide-index", +i);
        i++;
    });
    //Taken from Owl Carousel so we calculate width the same way
    var viewport = function() {
        var width;
        if (carouselOptions.responsiveBaseElement && carouselOptions.responsiveBaseElement !== window) {
            width = $(carouselOptions.responsiveBaseElement).width();
        } else if (window.innerWidth) {
            width = window.innerWidth;
        } else if (document.documentElement && document.documentElement.clientWidth) {
            width = document.documentElement.clientWidth;
        } else {
            console.warn('Can not detect viewport width.');
        }
        return width;
    };
    var severalRows = false;
    var orderedBreakpoints = [];
    for (var breakpoint in carouselOptions.responsive) {
        if (carouselOptions.responsive[breakpoint].rows > 1) {
            severalRows = true;
        }
        orderedBreakpoints.push(parseInt(breakpoint));
    }
    //Custom logic is active if carousel is set up to have more than one row for some given window width
    if (severalRows) {
        orderedBreakpoints.sort(function(a, b) {
            return b - a;
        });
        var slides = el.find('[data-slide-index]');
        var slidesNb = slides.length;
        if (slidesNb > 0) {
            var rowsNb;
            var previousRowsNb = undefined;
            var colsNb;
            var previousColsNb = undefined;
            //Calculates number of rows and cols based on current window width
            var updateRowsColsNb = function() {
                var width = viewport();
                for (var i = 0; i < orderedBreakpoints.length; i++) {
                    var breakpoint = orderedBreakpoints[i];
                    if (width >= breakpoint || i == (orderedBreakpoints.length - 1)) {
                        var breakpointSettings = carouselOptions.responsive['' + breakpoint];
                        rowsNb = breakpointSettings.rows;
                        colsNb = breakpointSettings.items;
                        break;
                    }
                }
            };
            var updateCarousel = function() {
                updateRowsColsNb();
                //Carousel is recalculated if and only if a change in number of columns/rows is requested
                if (rowsNb != previousRowsNb || colsNb != previousColsNb) {
                    var reInit = false;
                    if (carousel) {
                        //Destroy existing carousel if any, and set html markup back to its initial state
                        carousel.trigger('destroy.owl.carousel');
                        carousel = undefined;
                        slides = el.find('[data-slide-index]').detach().appendTo(el);
                        el.find('.fake-col-wrapper').remove();
                        reInit = true;
                    }
                    //This is the only real 'smart' part of the algorithm
                    //First calculate the number of needed columns for the whole carousel
                    var perPage = rowsNb * colsNb;
                    var pageIndex = Math.floor(slidesNb / perPage);
                    var fakeColsNb = pageIndex * colsNb + (slidesNb >= (pageIndex * perPage + colsNb) ?
                        colsNb : (slidesNb % colsNb));
                    //Then populate with needed html markup
                    var count = 0;
                    for (var i = 0; i < fakeColsNb; i++) {
                        //For each column, create a new wrapper div
                        var fakeCol = $('<div class="fake-col-wrapper"></div>').appendTo(el);
                        for (var j = 0; j < rowsNb; j++) {
                            //For each row in said column, calculate which slide should be present
                            var index = Math.floor(count / perPage) * perPage + (i % colsNb) + j * colsNb;
                            if (index < slidesNb) {
                                //If said slide exists, move it under wrapper div
                                slides.filter('[data-slide-index=' + index + ']').detach().appendTo(
                                    fakeCol);
                            }
                            count++;
                        }
                    }
                    //end of 'smart' part
                    previousRowsNb = rowsNb;
                    previousColsNb = colsNb;
                    if (reInit) {
                        //re-init carousel with new markup
                        carousel = el.owlCarousel(carouselOptions);
                    }
                }
            };
            //Trigger possible update when window size changes
            $(window).on('resize', updateCarousel);

            //We need to execute the algorithm once before first init in any case
            updateCarousel();
        }
    }
    //init
    carousel = el.owlCarousel(carouselOptions);
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.js"></script>
<script>
$('#slick1').slick({
    rows: 1,
    dots: false,
    arrows: true,
    infinite: true,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 3,
    responsive: [{
        breakpoint: 500,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
        }
    }]
});
</script>

<script>
$('#slick_villas').slick({
    rows: 1,
    dots: false,
    arrows: true,
    centerMode: true,
    infinite: true,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 1,
    responsive: [{
        breakpoint: 500,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
        }
    }]
});
</script>

<script>
$('#slick_hotels').slick({
    rows: 1,
    dots: false,
    arrows: true,
    centerMode: false,
    infinite: true,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    responsive: [{
        breakpoint: 500,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
        }
    }]
});
</script>

<script>
$('#slick_resorts').slick({
    rows: 1,
    dots: false,
    arrows: true,
    centerMode: false,
    infinite: true,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    responsive: [{
        breakpoint: 500,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
        }
    }]
});
</script>

<script>
$('#transfer_crs_1').slick({
    rows: 1,
    dots: false,
    arrows: true,
    centerMode: false,
    infinite: true,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 1,
    responsive: [{
        breakpoint: 500,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
        }
    }]
});
</script>

<script>
$('#transfer_crs_2').slick({
    rows: 1,
    dots: false,
    arrows: true,
    centerMode: false,
    infinite: true,
    speed: 300,
    slidesToShow: 2,
    slidesToScroll: 1,
    responsive: [{
        breakpoint: 500,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
        }
    }]
});
</script>

<script>
$('#slick_cabins').slick({
    rows: 1,
    dots: false,
    arrows: true,
    centerMode: false,
    infinite: true,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    responsive: [{
        breakpoint: 500,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
        }
    }]
});
</script>

<script>
$('#slick2').slick({
    rows: 1,
    dots: false,
    arrows: true,
    infinite: true,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 4,
    responsive: [{
        breakpoint: 500,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
        }
    }]
});
</script>

<?php
      if($selected_module =='villasapartment')
        {
          ?>
<script>
$(".hoteltabModules2").trigger('click');
</script>
<?php
            
        }
    ?>