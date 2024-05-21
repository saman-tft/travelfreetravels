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
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
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
                <ul class="nav nav-tabs tabstab">
                    <?php if (is_active_airline_module()) { ?>
                    <li class="<?=($selected_module=="flights")? 'active' : ''?>"><a href="#flights" role="tab"
                            data-toggle="tab" id="flightModules" class="script_data" data-id="flights">
                            <span class="sprte iconcmn icimgal"><i class="fal fa-plane"></i></span><span
                                class="txt_label">Flights</span></a></li>
                    <?php } ?>
                    <?php if (is_active_hotel_module()) { ?>
                    <li class="<?=($selected_module =="hotels")? 'active' : ''?>">
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
                    <li c class="<?=($selected_module =="transfers")? 'active' : ''?>"><a href=" #transferv1" role="tab"
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
                    <li class="<?=($selected_module=="activities")? 'active' : ''?>"><a href="#sightseeing" role="tab"
                            data-toggle="tab" id="activitiesModules" class="script_data" data-id="activities">
                            <span class="sprte iconcmn icimgal"><i class="fal fa-binoculars"></i></span><span
                                class="txt_label">Activities</span></a></li>
                    <?php } ?>
                    <?php if (is_active_package_module()) { ?>
                    <li class="<?=($selected_module =="holidays")? 'active' : ''?>"><a href="#holiday" role="tab"
                            data-toggle="tab" id="holidaysModules" class="script_data" data-id="holidays"> <span
                                class="sprte iconcmn icimgal"><i class="fal fa-tree"></i></span><span
                                class="txt_label">Holidays</span></a></li>
                    <?php } ?>
                    <?php if (is_active_package_module()) { ?>
                    <li class="<?php $default_view =="comingsoon"? 'active' : ''?>"><a
                            href="https://www.alkhaleejtours.com/dev/cruise" class="script_data"> <span
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
                        <div class="tab-pane <?php if($selected_module=="privatecar"){ echo "active"; } ?> private_car_modules"
                            id="privatecar">
                            <?php echo $GLOBALS['CI']->template->isolated_view('share/private_car')?>
                        </div>
                        <div class="tab-pane <?php if($selected_module=="private-transfer"){ echo "active"; } ?> "
                            id="privatetransfer">
                            <?php echo $GLOBALS['CI']->template->isolated_view('share/transfercrs')?>
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
<!--Top cars Offers start-->
<?php if($privatecar_promo['status']): ?>
<div class="top-offer-sec privatecarmodules custom-car-deals">
    <div class="container">
        <div id="custom_all" class="retmnus_private_cars_1 owl-carousel owl-theme">

            <?php 
          
            if(!empty($privatecar_promo['data'])):
                for($p=0; $p<count($privatecar_promo['data']); $p++):
                    $car_promo_code=$privatecar_promo['data'][$p];
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
<div class="car_deals privatecarmodules custom-car-services">

    <div class="pagehdwrap">
        <h3 class="pagehding"> Top Private Cars</h3>
    </div>
    <div class="container">
        <div id="private-car-all" class="owl-carousel owlindex3 owl-theme">
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
<div class="car_deals_perfect privatecarmodules custom-car-packages">

    <div class="pagehdwrap">
        <h3 class="pagehding"> Best Private Car Packages </h3>
    </div>
    <div class="container">
        <div id="private-car-all_perfect" class="owl-carousel owlindex3 owl-theme">
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
    $("#private-car-all_perfect").owlCarousel({

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
    $("#private-car-all").owlCarousel({

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
    $(".retmnus_p_transfers_1").owlCarousel({
        autoPlay: 3000, //Set AutoPlay to 3 seconds
        items: 2,
        itemsDesktop: [1199, 2],
        itemsDesktopSmall: [979, 1],
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
    $(".retmnus_private_cars_1").owlCarousel({

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
            "privatejet": "jet_modules",
            "privatecar": "privatecarmodules"
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
<?php
      if($selected_module =='privatecar')
        {
          ?>
<script>
$("#privatecar").trigger('click');
</script>
<?php
            
        }
    ?>