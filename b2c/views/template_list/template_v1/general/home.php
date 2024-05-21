<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css">

<script>
window.onload = function() {
    $('.homeflight').attr("style", "display: block !important");
};
</script>
<?php
$selected_module = $GLOBALS ['CI']->uri->segment(1);
$active_domain_modules = $this->active_domain_modules;
$default_active_tab = $default_view;
function set_default_active_tab($module_name, &$default_active_tab) {
	if (empty ( $default_active_tab ) == true || $module_name == $default_active_tab) {
		if (empty ( $default_active_tab ) == true) {
		  
			$default_active_tab = $module_name; // Set default module as current active module
		
		}
		return 'active';
	}
}

  if($selected_module =='user')
        {
            $default_view = $GLOBALS ['CI']->uri->segment(2);
           
            
        }
         if($selected_module =='villasapartment')
        {
            $default_view = 'accommodation';
            	$default_active_tab ='accomodation';
           
            
        }
//add to js of loader
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('backslider.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');
 Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('backslider.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/index.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');
?>
<style>
.villasapts.home_apartments button.slick-prev.slick-arrow {
    left: 0px;
    width: 40px;
    height: 40px;
}

.villasapts.home_apartments button.slick-next.slick-arrow {
    right: 0px;
    width: 40px;
    height: 40px;
}
</style>
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js">
var tmpl_imgs = '';
</script>

<div class="searcharea">
    <div class="srchinarea">
        <div class="container-fluid nopad">
            <div class="captngrp new_txt">
                <div id="big1" class="bigcaption">&nbsp;</div>
                <div id="desc" class="smalcaptn">&nbsp;<span class="boder"></span></div>
            </div>
            <div class="allformst">

                <div class="container nopad inspad">
                    <div class="tab_border">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tabstab">
                            <?php if (is_active_airline_module()) { ?>
                            <li class="<?php if($selected_module=="flights"){ echo "active"; } ?>">
                                <a href="#flights" role="tab" data-toggle="tab" id="flightModules" class="script_data"
                                    data-id="flights">
                                    <span class="sprte iconcmn icimgal"><i class="fal fa-plane"></i></span><span
                                        class="txt_label">Flights</span></a>
                            </li>
                            <?php } ?>
                            <?php if (is_active_hotel_module()) { ?>
                            <li
                                class="<?php if($selected_module=="hotels"){ echo "active"; } ?>">
                                <a href="#hotels" role="tab" data-toggle="tab" id="hotelModules" class="script_data"
                                    data-id="hotels">
                                    <span class="sprte iconcmn icimgal"><i class="fal fa-building"></i></span><span
                                        class="txt_label">Hotels</span></a>
                            </li>
                            <?php } ?>
<li class="<?php if($selected_module=="transfers"){ echo "active"; } ?>"><a
                                href="#privatetransfer" role="tab" data-toggle="tab" id="privatetransfermodules"
                                class="script_data" data-id="buses">
                                <span class="sprte iconcmn icimgal"><i class="fal fa-taxi"></i></span><span
                                    class="txt_label">Transfer</span></a></li>

                            <?php if (is_active_transferv1_module()) { ?>
                           <!--<li class="<?php echo set_default_active_tab(META_BUS_COURSE, $default_active_tab)?>"><a
                                href="#privatetransfer" role="tab" data-toggle="tab" id="privatetransfermodules"
                                class="script_data" data-id="buses">
                                <span class="sprte iconcmn icimgal"><i class="fal fa-taxi"></i></span><span
                                    class="txt_label">Transfer</span></a></li>-->
                            <?php } ?>
                            <!-- <?php if (is_active_car_module()) { ?>
                            <li class="<?php echo set_default_active_tab(META_CAR_COURSE, $default_active_tab)?>"><a
                                    href="#car" role="tab" data-toggle="tab" id="carsModules" class="script_data"
                                    data-id="car">
                                    <span class="sprte iconcmn icimgal"><i class="fal fa-car"></i></span><span
                                        class="txt_label">Car</span></a></li>
                            <?php } ?> -->
                            <?php if (is_active_sightseeing_module()) { ?>
                            <li
                                class="<?php if($selected_module=="activities"){ echo "active"; } ?>">
                                <a href="#sightseeing" role="tab" data-toggle="tab" id="activitiesModules"
                                    class="script_data" data-id="activities">
                                    <span class="sprte iconcmn icimgal"><i class="fal fa-binoculars"></i></span><span
                                        class="txt_label">Activities</span></a>
                            </li>
                            <?php } ?>
                            <?php if (is_active_package_module()) { ?>
                            <li class="<?php if($selected_module=="holidays"){ echo "active"; } ?>"><a
                                    href="#holiday" role="tab" data-toggle="tab" id="holidaysModules"
                                    class="script_data" data-id="holidays"> <span class="sprte iconcmn icimgal"><i
                                            class="fal fa-tree"></i></span><span class="txt_label">Holidays</span></a>
                            </li>
                            <?php } ?>





                        </ul>
                    </div>
                </div>
                <div class="container nopad inspad">
                    <div class="secndblak">

                        <div class="tab-content custmtab">
                            <?php if (is_active_airline_module()) { ?>
                            <div class="tab-pane <?php echo set_default_active_tab(META_AIRLINE_COURSE, $default_active_tab)?> flight_modules"
                                >
                                <?php echo $GLOBALS['CI']->template->isolated_view('share/flight_search')?>
                            </div>
                            <?php } ?>
                            <?php if (is_active_hotel_module()) { ?>
                            <div class="tab-pane <?php echo set_default_active_tab(META_ACCOMODATION_COURSE, $default_active_tab)?> hotel_modules"
                                id="hotels" style="display:none;">
                                <?php echo $GLOBALS['CI']->template->isolated_view('share/hotel_search')?>
                            </div>
                            <?php } ?>


                            <?php if (is_active_bus_module()) { ?>
                            <div class="tab-pane <?php echo set_default_active_tab(META_BUS_COURSE, $default_active_tab)?> buses_modules"
                                id="bus" style="display: none;">
                                <?php echo $GLOBALS['CI']->template->isolated_view('share/bus_search')?>
                            </div>
                            <?php } ?>
                            <?php if (is_active_transferv1_module()) { ?>
                            <div class="tab-pane <?php echo set_default_active_tab(META_TRANSFERV1_COURSE, $default_active_tab)?> transfers_modules"
                                id="transferv1">
                                <?php echo $GLOBALS['CI']->template->isolated_view('transfer/transfer_search')?>
                            </div>
                            <?php } ?>
                            <?php if (is_active_car_module()) { ?>
                            <div class="tab-pane <?php echo set_default_active_tab(META_CAR_COURSE, $default_active_tab)?> cars_modules"
                                id="car1" style="display: none;">
                                <?php echo $GLOBALS['CI']->template->isolated_view('share/car_search')?>
                            </div>
                            <?php } ?>
                            <?php if (is_active_package_module()) { ?>
                            <div class="tab-pane <?php echo set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab)?> holidays_modules"
                                id="holiday" style="display: none;">
                                <?php echo $GLOBALS['CI']->template->isolated_view('share/holiday_search',$holiday_data)?>
                            </div>
                            <?php } ?>
                            <div class="tab-pane <?php if($selected_module=="privatecar"){ echo "active"; } ?> private_car_modules"
                                id="privatecar">
                                <?php echo $GLOBALS['CI']->template->isolated_view('share/private_car')?>
                            </div>
                            <div class="tab-pane <?php if($selected_module=="private-transfer"){ echo "active"; } ?> private_transfers_modules"
                                id="privatetransfer">
                                <?php echo $GLOBALS['CI']->template->isolated_view('transfer/transfer_search')?>
                            </div>
                            <?php if (is_active_sightseeing_module()) { ?>
                            <div class="tab-pane <?php echo set_default_active_tab(META_SIGHTSEEING_COURSE, $default_active_tab)?> activities_modules"
                                id="sightseeing" style="display: none;">
                                <?php echo $GLOBALS['CI']->template->isolated_view('share/activity_search',$holiday_data)?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<?php 
if(true) : ?>
<?php 
  if (in_array ( META_ACCOMODATION_COURSE, $active_domain_modules ) and valid_array ( $top_destination_hotel ) == true) : // TOP DESTINATION
  ?>
<div class="htldeals ">
    <div class="pagehdwrap">
        <h3 class="pagehding">Top Hotel Destinations</h3>
    </div>
    <div class="container">

        <div class="tophtls">
            <div class="grid">
                <div id="owl-demo2" class="owl-carousel owl-theme owlindex2">
                    <?php   

       // $top_destination_hotel = array_slice($top_destination_hotel,5) ;
   //    debug($top_destination_hotel);exit;
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

                                <div class="hotel_caption">
                                    <h4 class="hotel_name"><?=$tv['city_name']?>
                                    </h4>
                                    <input type="hidden" class="top_des_id" value="<?php echo $tv['origin']?>">
                                    <input type="hidden" class="top-des-val hand-cursor"
                                        value="<?=hotel_suggestion_value($tv['city_name'], $tv['country_name'])?>">
                                    <button class="hotel_button">Check Now</button>
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
                                <div class="hotel_caption">
                                    <h4 class="hotel_name"><?=$tv['city_name']?>
                                    </h4>
                                    <input type="hidden" class="top_des_id" value="<?php echo $tv['origin']?>">
                                    <input type="hidden" class="top-des-val hand-cursor"
                                        value="<?=hotel_suggestion_value($tv['city_name'], $tv['country_name'])?>">
                                    <button class="hotel_button">Check Now</button>
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
                                <div class="hotel_caption">
                                    <h4 class="hotel_name"><?=$tv['city_name']?>
                                    </h4>
                                    <input type="hidden" class="top_des_id" value="<?php echo $tv['origin']?>">
                                    <input type="hidden" class="top-des-val hand-cursor"
                                        value="<?=hotel_suggestion_value($tv['city_name'], $tv['country_name'])?>">
                                    <button class="hotel_button">Check Now</button>
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



<div class="holidays_section">
    <div class="pagehdwrap">
        <h1 class="pagehding">Top Holiday Destinations</h1>
    </div>
    <div class="container">
        <div id="hldy-all_perfect" class="owl-carousel owlindex3 owl-theme">
        
                <?php
                foreach ( $top_destination_package as $tk => $tv ) :
                  ?>
                 

                 <div class="item">
                <div class="f-deal slide" data-slide-index="0">
                    <a href="#">
                        <div class="f-deal-img">
                            <img class="star_rat"
                                src="<?php echo $GLOBALS['CI']->template->domain_images($tv->banner_image); ?>"
                                alt="<?=$tv->package_name?>" loading="lazy" />
                        </div>
                        <div class="holiday_info">
                            <h4><?=$tv->package_name?></h4>
                            <div class="button-holiday">
                                <a href="<?php echo base_url(); ?>index.php/tours/details/<?php echo base64_encode($top_destination_package[$tk]->id);?>">
                                <h5 class="holiday-btn">Check Now</h5></a>
                                <i class="fa fa-chevron-right"></i>
                            </div>
                        </div>

                    </a>
                </div>
                
            </div>
            <?php endforeach;
            ?>
        </div>
        
    </div>
</div>

<div class="clearfix"></div>

<?php if(1){ ?>
<div class="best_deals">
    <div class="pagehdwrap">
        <h1 class="pagehding">Best Deals & Offers</h1>
    </div>
    <div class="container">

        <div class="retmnus_new" style="display: none;">
            <?php
      $i=1;
      foreach ($promocode_all['data'] as $key => $value) {
        $url ='';
        if($value['module']=='hotel' || $value['module']=='flight' || $value['module']=='holiday'){
            $url = base_url().$value['module'].'s';
        }else{
            $url = base_url().$value['module'];
        }
      ?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="top-con-align">
                    <a href="<?php echo $url ?>">


                        <div class="top-off-cont text-center">

                            <div class="special_deals">
                                <h4>Monsoon Special <br /><span><?=$value['value']?> OFF</span></h4>
                            </div>
                            <div class="special_vacation">
                                <!-- <h6>Spend the best vacation with us <br /><span>Trip to Newyork</span></h6> -->
                                <h6><span><?=$value['description']?></span></h6>
                            </div>
                            <div class="special_button">
                                <button>Use Coupon Code: <?=$value['promo_code']?></button>
                            </div>


                        </div>
                    </a>
                </div>
            </div>
            <?php
      $i++;
      }
      ?>
        </div>
    </div>
</div>
<?php }?>
<!--Top offers end-->




<div class="clearfix"></div>



<!-- <div class="activities_section">
    <div class="pagehdwrap">
        <h1 class="pagehding">Best of Activities</h1>
    </div>
    <div class="container">
        <div id="activities-all_perfect" class="owl-carousel owlindex3 owl-theme">
             <?php 
     for ($i=0; $i < count($top_destination_activity); $i++) {
                
                $from = $top_destination_activity[$i]['destination_name'];
                $destination =  $top_destination_activity[$i]['origin'];
                $country_name = $top_destination_activity[$i]['destination_name'];
                $category_id=0;
            $url1 = base_url().'general/pre_sight_seen_search?&from='.$from.'&destination_id='.$destination.'&category_id='.$category_id.'';
       ?>
            <div class="item">
                <div class="f-deal slide" data-slide-index="0">
                    <a href="<?php echo $url1; ?>">
                        <div class="f-deal-img">
                            <img class="star_rat"
                                src="<?php echo $GLOBALS['CI']->template->domain_images($top_destination_activity[$i]['image_activity']); ?>"
                                alt="Best Activities in travelfreetravel" loading="lazy" />
                        </div>
                        <div class="holiday_info">
                            <h4><?php echo $country_name ?></h4>
                        </div>

                    </a>
                </div>
            </div>

             <?php   } ?>
        </div>
    </div>
</div>

<div class="clearfix"></div> -->

<div class="activities_section">
    <div class="pagehdwrap">
        <h1 class="pagehding">Best of Activities</h1>
    </div>
    <div class="container">
        <div id="activities-all_perfect" class="owl-carousel owlindex3 owl-theme">

            <div class="item">
                <div class="f-deal slide" data-slide-index="0">
                    <a href="#">
                        <div class="f-deal-img">
                            <img class="star_rat"
                                src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v1/images/promocode/TMX9604421616070986istanbul-airport-transfer-company-h1-2.jpg"
                                alt="Book a affordable flights" loading="lazy" />
                        </div>
                        <div class="holiday_info">
                            <h4>Turkey</h4>
                        </div>

                    </a>
                </div>
            </div>

            <div class="item">
                <div class="f-deal slide" data-slide-index="0">
                    <a href="#">
                        <div class="f-deal-img">
                            <img class="star_rat"
                                src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v1/images/promocode/TMX9604421616070986istanbul-airport-transfer-company-h1-2.jpg"
                                alt="Book a affordable flights" loading="lazy" />
                        </div>
                        <div class="holiday_info">
                            <h4>Turkey</h4>
                        </div>

                    </a>
                </div>
            </div>

            <div class="item">
                <div class="f-deal slide" data-slide-index="0">
                    <a href="#">
                        <div class="f-deal-img">
                            <img class="star_rat"
                                src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v1/images/promocode/TMX9604421616070986istanbul-airport-transfer-company-h1-2.jpg"
                                alt="Book a affordable flights" loading="lazy" />
                        </div>
                        <div class="holiday_info">
                            <h4>Turkey</h4>
                        </div>

                    </a>
                </div>
            </div>

            <div class="item">
                <div class="f-deal slide" data-slide-index="0">
                    <a href="#">
                        <div class="f-deal-img">
                            <img class="star_rat"
                                src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v1/images/promocode/TMX9604421616070986istanbul-airport-transfer-company-h1-2.jpg"
                                alt="Book a affordable flights" loading="lazy" />
                        </div>
                        <div class="holiday_info">
                            <h4>Turkey</h4>
                        </div>

                    </a>
                </div>
            </div>

            <div class="item">
                <div class="f-deal slide" data-slide-index="0">
                    <a href="#">
                        <div class="f-deal-img">
                            <img class="star_rat"
                                src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v1/images/promocode/TMX9604421616070986istanbul-airport-transfer-company-h1-2.jpg"
                                alt="Book a affordable flights" loading="lazy" />
                        </div>
                        <div class="holiday_info">
                            <h4>Turkey</h4>
                        </div>

                    </a>
                </div>
            </div>



        </div>
    </div>
</div>

<div class="clearfix"></div>




<div class="transfers_section">
    <div class="pagehdwrap">
        <h1 class="pagehding">Top Transfer Packages</h1>
    </div>
    <div class="container">
        <div id="transfer-all_perfect" class="owl-carousel owlindex3 owl-theme">

            <div class="item">
                <div class="f-deal slide" data-slide-index="0">
                    <a href="#">
                        <div class="f-deal-img">
                            <img class="star_rat"
                                src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v1/images/promocode/TMX9604421616070986istanbul-airport-transfer-company-h1-2.jpg"
                                alt="Book a affordable flights" loading="lazy" />
                        </div>
                        <div class="holiday_info">
                            <h4>From Miami</h4>
                        </div>

                    </a>
                </div>
            </div>
            <div class="item">
                <div class="f-deal slide" data-slide-index="0">
                    <a href="#">
                        <div class="f-deal-img">
                            <img class="star_rat"
                                src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v1/images/promocode/TMX9604421616070986istanbul-airport-transfer-company-h1-2.jpg"
                                alt="Book a affordable flights" loading="lazy" />
                        </div>
                        <div class="holiday_info">
                            <h4>From Miami</h4>
                        </div>

                    </a>
                </div>
            </div>
            <div class="item">
                <div class="f-deal slide" data-slide-index="0">
                    <a href="#">
                        <div class="f-deal-img">
                            <img class="star_rat"
                                src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v1/images/promocode/TMX9604421616070986istanbul-airport-transfer-company-h1-2.jpg"
                                alt="Book a affordable flights" loading="lazy" />
                        </div>
                        <div class="holiday_info">
                            <h4>From Miami</h4>
                        </div>

                    </a>
                </div>
            </div>
            <div class="item">
                <div class="f-deal slide" data-slide-index="0">
                    <a href="#">
                        <div class="f-deal-img">
                            <img class="star_rat"
                                src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v1/images/promocode/TMX9604421616070986istanbul-airport-transfer-company-h1-2.jpg"
                                alt="Book a affordable flights" loading="lazy" />
                        </div>
                        <div class="holiday_info">
                            <h4>From Miami</h4>
                        </div>

                    </a>
                </div>
            </div>
            <div class="item">
                <div class="f-deal slide" data-slide-index="0">
                    <a href="#">
                        <div class="f-deal-img">
                            <img class="star_rat"
                                src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v1/images/promocode/TMX9604421616070986istanbul-airport-transfer-company-h1-2.jpg"
                                alt="Book a affordable flights" loading="lazy" />
                        </div>
                        <div class="holiday_info">
                            <h4>From Miami</h4>
                        </div>

                    </a>
                </div>
            </div>





        </div>
    </div>
</div>

<div class="clearfix"></div>

<?php //}
$show="1";
 ?>
<?php if(!empty($top_destination_flight)){ ?>
<div class="flight_deals_perfect">
    <div class="pagehdwrap">
        <h1 class="pagehding">Top Flight Routes</h1>
    </div>
    <div class="container">

        <div id="fligt-all_perfect" class=" owl-carousel owlindex3 owl-theme">
            <?php 
            $date = date("Y-m-d");
              $date = strtotime($date);
              $ddate = strtotime("+7 day", $date);
              $tdate = strtotime("+8 day", $date);
              $ddate = date('d-m-Y', $ddate);
              $tdate = date('d-m-Y', $tdate);
             // $top_destination_flight = array_slice($top_destination_flight,6) ;

           for ($i=0; $i < count($top_destination_flight); $i++) {
               $psdestination = $this->custom_db->single_table_records('flight_airport_list', 'airport_city,origin,airport_code,country', array('airport_code' => $top_destination_flight[$i]['to_airport_code']));            
          
               $XDESTINATION = $psdestination['data'][0]['airport_city'];
               $oxdestination_orgin = $psdestination['data'][0]['origin'];
               $xdestination_airport = $psdestination['data'][0]['airport_code'];
               $xdestination_country = $psdestination['data'][0]['country'];
               
               $psarrival = $this->custom_db->single_table_records('flight_airport_list', 'airport_city,origin,airport_code,country', array('airport_code' => $top_destination_flight[$i]['from_airport_code']));            
               $oxarrival_orgin = $psarrival['data'][0]['origin'];
              $f = base64_encode($top_destination_flight[$i]['from_airport_name'].' ('.$top_destination_flight[$i]['from_airport_code'].')');
              $t = base64_encode($top_destination_flight[$i]['to_airport_name'].' ('.$top_destination_flight[$i]['to_airport_code'].')');
               $url ='';
               $url = base_url().'general/preflightsearch/'.$f.'/'.$oxarrival_orgin.'/'.$t.'/'.$oxdestination_orgin.'/'.$ddate;

            ?>
            <div class="item">
                <div class="f-deal slide" data-slide-index="0">
                    <a href="<?php echo $url ?>">
                        <div class="f-deal-img">
                            <img class="star_rat"
                                src="<?php echo $GLOBALS['CI']->template->domain_images($top_destination_flight[$i]['image']); ?>"
                                alt="Book a affordable flights" loading="lazy" />
                        </div>
                        <div class="flight-info flight-route">
                            <h3><?php echo $top_destination_flight[$i]['from_airport_name'] ?> >>
                                <?php echo $top_destination_flight[$i]['to_airport_name'] ?> <span class="plane-icon">
                                    <img
                                        src="<?php echo base_url()?>extras/system/template_list/template_v3/images/flight_icon.png"></span>
                            </h3>


                        </div>
                    </a>
                </div>
            </div>
            <?php
        if($i==9)break;
      }
              ?>
        </div>
    </div>
</div>
</div>
<? } ?>

<div class="clearfix"></div>




<div class="testimonials_section">
    <div class="pagehdwrap">
        <h1 class="pagehding">What Client says about us?</h1>
    </div>
    <div class="container">
        <div id="testimonials" class="owl-carousel owlindex3 owl-theme">
 <?php $query = "SELECT * FROM testimonial_images WHERE status='1' ORDER BY banner_order ASC";
            $get_data= $this->db->query($query)->result_array(); 
            foreach ($get_data as $key => $value) { ?>
            <div class="item">
                <div class="col-sm-12 col-xs-12 nopad htd-wrap plctstyspc-testi">
                    <div class="threeyy">
                        <div class="apritoptyy"> <img
                                src="<?php echo base_url().IMG_UPLOAD_DIR.$value['image']; ?>"
                                alt="best travel agency platform">
                            <div class="testiname"><span style="font-size:30px"></span><?php echo $value['title'];?><br><i
                                    class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                    class="fa fa-star"></i> <i class="fa fa-star"></i> </div>
                        </div>
                        <div class="dismanyy">
                            <div class="hedspritee"> <?php echo $value['description'];?></div>
                        </div>
                    </div>
                </div>
            </div>
             <?php } ?>
            <!-- <div class="item">
                <div class="col-sm-12 col-xs-12 nopad htd-wrap plctstyspc-testi">
                    <div class="threeyy">
                        <div class="apritoptyy"> <img
                                src="<?php echo base_url()?>extras/system/template_list/template_v3/images/evans.jpg"
                                alt="best travel agency platform">
                            <div class="testiname"><span style="font-size:30px"></span>Chris Evans<br><i
                                    class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                    class="fa fa-star"></i> <i class="fa fa-star"></i> </div>
                        </div>
                        <div class="dismanyy">
                            <div class="hedspritee">Lorem Ipsum is simply dummy text of the printing and typesetting
                                industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                                when an unknown printer took a galley of type and scrambled it to make a type specimen
                                book. </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="col-sm-12 col-xs-12 nopad htd-wrap plctstyspc-testi">
                    <div class="threeyy">
                        <div class="apritoptyy"> <img
                                src="<?php echo base_url()?>extras/system/template_list/template_v3/images/evans.jpg"
                                alt="best travel agency platform">
                            <div class="testiname"><span style="font-size:30px"></span>Chris Evans<br><i
                                    class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                    class="fa fa-star"></i> <i class="fa fa-star"></i> </div>
                        </div>
                        <div class="dismanyy">
                            <div class="hedspritee">Lorem Ipsum is simply dummy text of the printing and typesetting
                                industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                                when an unknown printer took a galley of type and scrambled it to make a type specimen
                                book. </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="col-sm-12 col-xs-12 nopad htd-wrap plctstyspc-testi">
                    <div class="threeyy">
                        <div class="apritoptyy"> <img
                                src="<?php echo base_url()?>extras/system/template_list/template_v3/images/evans.jpg"
                                alt="best travel agency platform">
                            <div class="testiname"><span style="font-size:30px"></span>Chris Evans<br><i
                                    class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                    class="fa fa-star"></i> <i class="fa fa-star"></i> </div>
                        </div>
                        <div class="dismanyy">
                            <div class="hedspritee">Lorem Ipsum is simply dummy text of the printing and typesetting
                                industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                                when an unknown printer took a galley of type and scrambled it to make a type specimen
                                book. </div>
                        </div>
                    </div>
                </div>
            </div> -->

        </div>
    </div>
</div>

<div class="clearfix"></div>
<div class="testimonials_section review_section">
    <div class="pagehdwrap">
        <h1 class="pagehding">Reviews</h1>
    </div>
    <div class="container">
        <div id="testimonials_review" class="">
 <?php $query = "SELECT * FROM general_user_review WHERE status='1' ORDER BY origin DESC";
            $get_data= $this->db->query($query)->result_array(); 

            //debug($get_data);exit;
            foreach ($get_data as $key => $value) { 
                 $dep_date = changeDateFormat($value['created']);
                ?>
            <div class="item">
                <div class="col-sm-12 col-xs-12 nopad htd-wrap plctstyspc-testi">
                    <div class="threeyy">
                        <div class="apritoptyy"> <!-- <img
                                src="<?php echo base_url().IMG_UPLOAD_DIR.$value['image']; ?>"
                                alt="best travel agency platform"> -->
                            <div class="testiname"><span style="font-size:30px"></span><?php echo $value['user_name'].' '.$value['user_lname'];?><br><!-- <i
                                    class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                    class="fa fa-star"></i> <i class="fa fa-star"></i> --> </div>
                            <div class="testiname"><span style="font-size:15px">Module: </span><?php echo $value['module'];?><br></div> 
                            <div class="testiname"><span style="font-size:15px">Posted On: </span><?php echo $dep_date;?><br></div>        
                                     <div class="dismanyy">
                            <div class="hedspritee"> <?php echo $value['comment'];?></div>
                        </div>
                        </div>
                       
                    </div>
                </div>
            </div>
             <?php } ?>

        </div>
    </div>
</div>


<div class="choose_section">
    <div class="pagehdwrap">
        <h1 class="pagehding">Why Choose Us?</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="choose-us text-center">
                    <img src="<?php echo base_url()?>extras/system/template_list/template_v3/images/price.png"
                        alt="best travel agency platform">
                    <h3>Best Price Guarantee</h3>
                    <p>Apply the Coupon codes provided by us and avail offers to save money.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="choose-us text-center">
                    <img src="<?php echo base_url()?>extras/system/template_list/template_v3/images/help.png"
                        alt="best travel agency platform">
                    <h3>Get Help</h3>
                    <p>Contact us in any emergency to get help regarding any info.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="choose-us text-center">
                    <img src="<?php echo base_url()?>extras/system/template_list/template_v3/images/client.png"
                        alt="best travel agency platform">
                    <h3>Client Satisfaction</h3>
                    <p>We ensure that we help our best to fulfill the client’s satisfaction.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clearfix"></div>

<?php if($show==0){ ?>

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
                    <img src="<?php echo $GLOBALS['CI']->template->domain_images($adv_image); ?>"
                        alt="holidays packages from uae" loading="lazy">
                </div>
            </div>
            <?php
                     echo '</div>';
      }
    ?>
        </div>
    </div>
</div>
<?php } ?>




<div class="clearfix"></div>


<div class="subscribe-section">
    <div class="container-fluid nopad">
        <div class="col-md-12 col-xs-12 sign_bg">
            <div class="frtbest1 centertio">
                <div class="subscribe-box">
                    <div class="col-md-5 col-xs-12 nopad">
                        <h2>Keep yourself updated!</h2>
                        <h4 class="sub_news">Subscribe to our News Letter</h4>
                    </div>
                    <div class="col-md-7 col-xs-12 nopad">
                        <div class="signfomup clr_change">
                            <div class="formbtmns">
                                <input type="text" name="email" id="exampleInputEmail1"
                                    class="form-control ft_subscribe" value="" required="required"
                                    placeholder="Enter Your Email">
                                <button type="button" class="btn btn_sub subsbtm"
                                    onclick="check_newsletter()">Subscribe</button>
                            </div>
                            <span class="msgNewsLetterSubsc12"
                                style="font-size: 13px; color: #fff; display: none;"><b>Please
                                    Provide Valid Email ID</b></span> <span class="succNewsLetterSubsc"
                                style="font-size: 13px; color: #fff; display: none;"><b>Thank you
                                    for subscribe.We will be in touch with newsletter.</b></span>
                            <span class="msgNewsLetterSubsc" style="font-size: 13px; color: #fff; display: none;"><b>You
                                    are
                                    already subscribed to Newsletter feed.</b></span> <span class="msgNewsLetterSubsc1"
                                style="font-size: 13px; color: #fff; display: none;"><b>Activated to
                                    Newsletter feed.Thank you</b></span>


                        </div>
                    </div>
                </div>
                <div class="footrlogo hide">
                    <!--<img src="<?php echo $GLOBALS['CI']->template->domain_images('footer_' . $GLOBALS['CI']->template->get_domain_logo()); ?>" alt="" />-->
                </div>

            </div>
        </div>
    </div>
</div>

<div class="clearfix"></div>

<div class="foot-above">
    <div class="foot-above-img">
        <img src="<?php echo $GLOBALS['CI']->template->domain_images('pexels-photo-672358.jpg'); ?>"
            alt="Book a tour to India" loading="lazy">
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
    var owl87 = $("#owl-villasapts");
    owl87.owlCarousel({
        itemsCustom: [
            [0, 1],
            [450, 2],
            [551, 3],
            [700, 3],
            [1000, 3],
            [1200, 3],
            [1400, 3],
            [1600, 3]
        ],
        navigation: true,
        pagination: false,
        responsive: {
            0: {
                items: 1,
                rows: 1
            },
            768: {
                items: 1,
                rows: 1
            },
            991: {
                items: 3,
                rows: 2
            }
        }
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
        items: 3,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    // var owl9 = $("#owl-demomobile");
    // owl9.owlCarousel({      
    //     itemsCustom : [
    //         [0, 1],
    //         [450, 1],
    //         [551, 2],
    //         [700, 2],
    //         [1000, 4],
    //         [1200, 4],
    //         [1400, 4],
    //         [1600, 4]
    //     ],
    //     navigation : true,
    //     dots:false,
    //     autoPlay: 3000, 
    //     autoPlay:true      

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
    $("#hldy-all_perfect").owlCarousel({
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
        items: 4,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $("#car-all_perfect").owlCarousel({
        autoPlay: 3000, //Set AutoPlay to 3 seconds 
        items: 4,
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
    $("#testimonials").owlCarousel({
        autoPlay: 3000, //Set AutoPlay to 3 seconds
        items: 3,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $("#testimonials_review").owlCarousel({
        autoPlay: 3000, //Set AutoPlay to 3 seconds
        items: 2,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2],
        navigation: true,
        pagination: false,
        autoPlay: true
    });
    $("#car-all").owlCarousel({
        autoPlay: 3000, //Set AutoPlay to 3 seconds
        items: 2,
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
        itemsDesktop: [1199, 3],
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
        items: 2,
        itemsDesktop: [1199, 3],
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
        itemsDesktop: [1199, 3],
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
</script>
<script>
$(document).ready(function() {

});
</script>
<script>
$(document).ready(function() {
    $("#flightModules").on("click", function() {
 $(".private_transfers_modules ").hide();
});
$("#hotelModules").on("click", function() {
  $(".private_transfers_modules ").hide();
});
    $("#privatetransfermodules").on("click", function() {
   
    $(".private_transfers_modules").show();
});


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
            "privatetransfers": "ss"
        };

        $.each(ret, function(key, value) {
            if (key == c_module) {
                $('.' + value).show();
            } else {
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
                            var index = Math.floor(count / perPage) * perPage + (i % colsNb) + j *
                                colsNb;
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
$('#jet_deals_1').slick({
    rows: 1,
    dots: false,
    arrows: true,
    centerMode: false,
    infinite: true,
    speed: 300,
    slidesToShow: 1,
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

$('#holidays-slick').slick({
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


<?php
      if($selected_module !='flights')
        {
          ?>
<script>
$(".flight_modules").hide();
</script>
<?php
            
        }
    ?>