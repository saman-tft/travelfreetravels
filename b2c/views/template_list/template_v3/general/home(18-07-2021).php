<script>
  window.onload = function () {
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
//add to js of loader
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('backslider.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');
 Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('backslider.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/index.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');
?>
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>

 <div class="searcharea">
	<div class="srchinarea">
		<div class="container">
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
          <li
            class="<?php echo set_default_active_tab(META_AIRLINE_COURSE, $default_active_tab)?> 111"><a
            href="#flights" role="tab" data-toggle="tab" id="flightModules" class="script_data" data-id="flights">
            <span class="sprte iconcmn icimgal"><i class="fal fa-plane"></i></span><span class="txt_label">Flights</span></a></li>
          <?php } ?>
          <?php if (is_active_hotel_module()) { ?>
          <li
            class="<?php echo set_default_active_tab(META_ACCOMODATION_COURSE, $default_active_tab)?>"  ><a
            href="#hotels" role="tab" data-toggle="tab" id="hotelModules" class="script_data" data-id="hotels">
            <span class="sprte iconcmn icimgal"><i class="fal fa-building"></i></span><span class="txt_label">Hotels</span></a></li>
          <?php } ?>
         
           <?php if (is_active_bus_module()) { ?>
          <li
            class="<?php echo set_default_active_tab(META_BUS_COURSE, $default_active_tab)?>"><a
            href="#bus" role="tab" data-toggle="tab"  id="busesModules" class="script_data" data-id="buses">
            <span class="sprte iconcmn icimgal"><i class="fal fa-bus"></i></span><span class="txt_label">Buses</span></a></li>
          <?php } ?>
          <?php if (is_active_transferv1_module()) { ?>
          <li
            class="<?php echo set_default_active_tab(META_TRANSFERV1_COURSE, $default_active_tab)?>"><a
            href="#transferv1" role="tab" data-toggle="tab"  id="transfersModules" class="script_data" data-id="transfers">
            <span class="sprte iconcmn icimgal"><i class="fal fa-taxi"></i></span><span class="txt_label">Transfers</span></a></li>
          <?php } ?>
          <?php if (is_active_car_module()) { ?>
          <li
            class="<?php echo set_default_active_tab(META_CAR_COURSE, $default_active_tab)?>"><a
            href="#car" role="tab" data-toggle="tab"  id="carsModules" class="script_data" data-id="car">
            <span class="sprte iconcmn icimgal"><i class="fal fa-car"></i></span><span class="txt_label">Car</span></a></li>
          <?php } ?>                   
          <?php if (is_active_sightseeing_module()) { ?>
          <li
            class="<?php echo set_default_active_tab(META_SIGHTSEEING_COURSE, $default_active_tab)?>"><a
            href="#sightseeing" role="tab" data-toggle="tab" id="activitiesModules" class="script_data" data-id="activities">
            <span class="sprte iconcmn icimgal"><i class="fal fa-binoculars"></i></span><span class="txt_label">Activities</span></a></li>
          <?php } ?> 
          <?php if (is_active_package_module()) { ?>
          <li
            class="<?php echo set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab)?>"><a
            href="#holiday" role="tab" data-toggle="tab" id="holidaysModules" class="script_data" data-id="holidays"> <span class="sprte iconcmn icimgal"><i class="fal fa-tree"></i></span><span class="txt_label">Holidays</span></a></li>
          <?php } ?> 
          
           <?php if (is_active_package_module()) { ?>
          <li
            class="<?php echo set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab)?>"><a
            href="#cruise" role="tab" data-toggle="tab" id="cruiseModules" class="script_data" data-id="cruise"> <span class="sprte iconcmn icimgal"><i class="fal fa-ship"></i></span><span class="txt_label">Cruise</span></a></li>
          <?php } ?> 
          
           <?php if (is_active_package_module()) { ?>
          <li
            class="<?php echo set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab)?>"><a
            href="#private_jet" role="tab" data-toggle="tab" id="privatejet" class="script_data" data-id="privatejet"> <span class="sprte iconcmn icimgal"><i class="fal fa-fighter-jet"></i></span><span class="txt_label">Private Jet</span></a></li>
          <?php } ?> 
           <?php if (is_active_hotel_module()) { ?>
          <li
            class="<?php echo set_default_active_tab(META_ACCOMODATION_CRS, $default_active_tab)?>" ><a
            href="#hotels2" role="tab" data-toggle="tab" id="hotelModules2" class="script_data" data-id="hotels2">
            <span class="sprte iconcmn icimgal"><i class="fal fa-building"></i></span><span class="txt_label">Accommodation</span></a></li>
          <?php } ?>
           <?php if (is_active_package_module()) { ?>
          <li
            class="<?php echo set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab)?>"><a
            href="#private_car" role="tab" data-toggle="tab" id="privatecar" class="script_data" data-id="privatecar"> <span class="sprte iconcmn icimgal"><i class="fal fa-car"></i></span><span class="txt_label">Private Car</span></a></li>
          <?php } ?> 
          
           <?php if (is_active_package_module()) { ?>
          <li
            class="<?php echo set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab)?>"><a
            href="#private_transfer" role="tab" data-toggle="tab" id="privatetransfer" class="script_data" data-id="privatetransfer"> <span class="sprte iconcmn icimgal"><i class="fal fa-taxi"></i></span><span class="txt_label">Private Transfer</span></a></li>
          <?php } ?> 
          
          
        </ul>
      </div>       
      <div class="secndblak">
          <div class="tab-content custmtab">
            <?php if (is_active_airline_module()) { ?>
            <div
              class="tab-pane <?php echo set_default_active_tab(META_AIRLINE_COURSE, $default_active_tab)?> flight_modules homeflight"
              id="flights1">
              <?php echo $GLOBALS['CI']->template->isolated_view('share/flight_search')?>
            </div>
            <?php } ?>
            <?php if (is_active_hotel_module()) { ?>
            <div
              class="tab-pane <?php echo set_default_active_tab(META_ACCOMODATION_COURSE, $default_active_tab)?> hotel_modules"
              id="hotels" style="display:none;">
              <?php echo $GLOBALS['CI']->template->isolated_view('share/hotel_search')?>
            </div>
            <?php } ?>
            <?php if (is_active_hotel_module()) { ?>
            <div
              class="tab-pane <?php echo set_default_active_tab(META_ACCOMODATION_CRS, $default_active_tab)?> hotel_modules2"
              id="hotels2">
              <?php echo $GLOBALS['CI']->template->isolated_view('share/hotel_crs_search')?>
            </div>
            <?php } ?>
            <?php if (is_active_bus_module()) { ?>
            <div
              class="tab-pane <?php echo set_default_active_tab(META_BUS_COURSE, $default_active_tab)?> buses_modules"
              id="bus">
              <?php echo $GLOBALS['CI']->template->isolated_view('share/bus_search')?>
            </div>
            <?php } ?>
            <?php if (is_active_transferv1_module()) { ?>
            <div
              class="tab-pane <?php echo set_default_active_tab(META_TRANSFERV1_COURSE, $default_active_tab)?> transfers_modules"
              id="transferv1">
              <?php echo $GLOBALS['CI']->template->isolated_view('share/transferv1_search')?>
            </div>
            <?php } ?>
            <?php if (is_active_car_module()) { ?>
            <div
              class="tab-pane <?php echo set_default_active_tab(META_CAR_COURSE, $default_active_tab)?> cars_modules"
              id="car1">
              <?php echo $GLOBALS['CI']->template->isolated_view('share/car_search')?>
            </div>
            <?php } ?>
            <?php if (is_active_package_module()) { ?>
            <div
              class="tab-pane <?php echo set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab)?> holidays_modules"
              id="holiday">
              <?php echo $GLOBALS['CI']->template->isolated_view('share/holiday_search',$holiday_data)?>
            </div>
            <?php } ?>
            <?php if (is_active_sightseeing_module()) { ?>
            <div
              class="tab-pane <?php echo set_default_active_tab(META_SIGHTSEEING_COURSE, $default_active_tab)?> activities_modules"
              id="sightseeing">
              <?php echo $GLOBALS['CI']->template->isolated_view('share/sightseeing_search',$holiday_data)?>
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
<?php if(1){ ?>
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
          <img src="<?php echo $GLOBALS['CI']->template->domain_promo_images($value['promo_code_image']); ?>" alt="Book a tour to India" loading="lazy">
        </div>
        <div class="top-off-cont">
          <i class="fas fa-star"></i>
          <div class="deals_info_heading"><h3>Get <?php echo $value['value'] ?> Off on  <?php echo $value['module'] ?></h3></div>
          <div class="deals_info_footer">
        <div class="pull-left validDate">Valid till :  <?php echo $value['expiry_date'] ?></div>
    </div>
    <div class="deals_info_subheading"><h4>Use Coupon: <?php echo $value['promo_code'] ?>.</h4></div>
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
<?php }?>
<!--Top offers end-->
<!--Top offers end-->
<div class="clearfix"></div>
<div class="secndblak" style="display:none;">
          <div class="">            
            <?php if (is_active_hotel_module()) { ?>
            <div
              class="tab-pane <?php echo set_default_active_tab(META_ACCOMODATION_COURSE, $default_active_tab)?> hotel_modules"
              id="hotels">
              <?php echo $GLOBALS['CI']->template->isolated_view('share/hotel_search')?>
            </div>
            <?php } ?>
          </div>
        </div>
  <div class="clearfix"></div>
<?php //}
$show="1";
 ?>
<?php if(!empty($top_destination_flight)){ ?>
<div class="flight_deals_perfect">
  <div class="container">
    <div class="pagehdwrap">
            <h1 class="pagehding">Top Flight Routes</h1>
      </div>
      <div id="fligt-all_perfect" class="owl-carousel owlindex3 owl-theme">
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
            <a href="">
            <div class="f-deal-img">
            <a href="" target="_blank"><img class="star_rat" src="<?php echo $GLOBALS['CI']->template->domain_images($top_destination_flight[$i]['image']); ?>" alt="Book a affordable flights" loading="lazy"/></a>
            </div>
            <a href="<?php echo $url ?>">
            <div class="flight-info">
            <h3><?php echo $top_destination_flight[$i]['from_airport_name'] ?><span>>></span><?php echo $top_destination_flight[$i]['to_airport_name'] ?></h3>
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
<?php 
if(in_array( 'Top Hotel Destinations', $headings )) : ?>
  <?php 
  if (in_array ( META_ACCOMODATION_COURSE, $active_domain_modules ) and valid_array ( $top_destination_hotel ) == true) : // TOP DESTINATION
  ?>
  <div class="htldeals">
  <div class="container">
    <div class="pagehdwrap">
      <h3 class="pagehding">Top Hotel Destinations</h3>
    </div>
    <div class="tophtls">
      <div class="grid">
      <div id="owl-demo2" class="owl-carousel1 owlindex2">
        <?php   

       // $top_destination_hotel = array_slice($top_destination_hotel,5) ;
   //    debug($top_destination_hotel);exit;
            foreach ( $top_destination_hotel as $tk => $tv ) :
              ?>             
        <?php if(($tk-0)%10 == 0){?>
        <div class="item">
        <div class="col-md-12 col-sm-12 col-xs-12 pdfve htd-wrap">
          <div class="effect-marley figure">
            <img
              class="lazy lazy_loader"
              src="<?php echo $GLOBALS['CI']->template->domain_images($tv['image']); ?>"
              data-src="<?php echo $GLOBALS['CI']->template->domain_images($tv['image']); ?>"
              alt="<?=$tv['city_name']?>" loading="lazy"/>
            <div class="figcaption">
            <div class="width_70">
              <h3 class="clasdstntion"><?=$tv['city_name']?></h3>
              <p>(<?=$tv['cache_hotels_count']?> Hotels)</p>
              <input type="hidden" class="top_des_id" value="<?php echo $tv['origin']?>">
              <input type="hidden"
                class="top-des-val hand-cursor"
                value="<?=hotel_suggestion_value($tv['city_name'], $tv['country_name'])?>">
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
                        <div class="item">
        <div class="col-md-12 col-sm-12 col-xs-12 pdfve htd-wrap">
          <div class="effect-marley figure">
            <img
              class="lazy lazy_loader"
              src="<?php echo $GLOBALS['CI']->template->domain_images($tv['image']); ?>"
              data-src="<?php echo $GLOBALS['CI']->template->domain_images($tv['image']); ?>"
              alt="<?=$tv['city_name']?>" loading="lazy"/>
            <div class="figcaption">
            <div class="width_70">
              <h3 class="clasdstntion"><?=$tv['city_name']?></h3>
              <p>(<?=rand(99, 500)?> Hotels)</p>
              <input type="hidden" class="top_des_id" value="<?php echo $tv['origin']?>">
              <input type="hidden"
                class="top-des-val hand-cursor"
                value="<?=hotel_suggestion_value($tv['city_name'], $tv['country_name'])?>">
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
        <div class="item">
        <div class="col-md-12 col-sm-12 col-xs-12 pdfve htd-wrap">
          <div class="effect-marley figure">
            <img
              class="lazy lazy_loader"
              src="<?php echo $GLOBALS['CI']->template->domain_images($tv['image']); ?>"
              data-src="<?php echo $GLOBALS['CI']->template->domain_images($tv['image']); ?>"
              alt="<?=$tv['city_name']?>" loading="lazy"/>
            <div class="figcaption">
            <div class="width_70">
              <h3 class="clasdstntion"><?=$tv['city_name']?></h3>
              <p>(<?=rand(99, 500)?> Hotels)</p>
              <input type="hidden" class="top_des_id" value="<?php echo $tv['origin']?>">
              <input type="hidden" class="top-des-val hand-cursor"
                value="<?=hotel_suggestion_value($tv['city_name'], $tv['country_name'])?>">
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
          endif; // TOP DESTINATION
        ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<div class="clearfix"></div>
<?php if(!empty($top_destination_tranfers) && $show==0){ ?>
<div class="transfer_deals_perfect">
  <div class="container">
    <div class="pagehdwrap">
                <h3 class="pagehding"> Best Transfer Service</h3>
      </div>
      <div id="transfer-all_perfect" class="owl-carousel owlindex3 owl-theme">
    <?php 
       for ($i=0; $i < count($top_destination_tranfers); $i++) {
            $origin=$top_destination_tranfers[$i]['origin'];
            $from = $top_destination_tranfers[$i]['destination_name'];
            $destination =  $top_destination_tranfers[$i]['origin'];
            $country_name = $top_destination_tranfers[$i]['destination_name'];
            $category_id=0;
      $url2 = '';
      $url2 = base_url().'general/pretransferv1search/'.$from.'/'.$destination.'/'.$category_id;
          
                echo '<div class="item">';
    
            ?>
          <div class="f-deal slide" data-slide-index="0">
            <a href="">
            <div class="f-deal-img">
            <a href="" target="_blank"><img class="star_rat" src="<?php echo $GLOBALS['CI']->template->domain_images($top_destination_tranfers[$i]['image_transfers']); ?>" alt="online travel booking services" loading="lazy"/></a>
            </div>
            <a href="<?php echo $url2; ?>">
            <div class="flight-info">
            <h3><!--  <i class="fas fa-star"> --></i> From <?php echo $country_name ?></h3>
            </div>
            </a>
          </div>
        <?php   
               echo '</div>';
      
      if($i==9)break; }
              ?>
    </div>
 </div>
</div>
</div> 
<? } ?>
<div class="clearfix"></div> 
<?php if($show==0){ ?>

<div class="car_deals">
  <div class="container">
    <div class="pagehdwrap">
            <h3 class="pagehding"> Best Car Service</h3>
      </div>
      <div id="car-all" class="owl-carousel owlindex3 owl-theme">
        <?php  
            $date = date("Y-m-d");
              $date = strtotime($date);
              $ddate = strtotime("+7 day", $date);
              $tdate = strtotime("+8 day", $date);
              $ddate = date('d-m-Y', $ddate);
              $tdate = date('d-m-Y', $tdate);
           for ($i=0; $i < count($top_destination_car); $i++) {
            
            //old $from=$top_destination_car[$i]['Country_Name_EN'];
            $from=$top_destination_car[$i]['Airport_Name_EN']; //new
            $from_ud=base64_encode($top_destination_car[$i]['Airport_Name_EN'].' '.$showcountry); //new
            $showcountry=$top_destination_car[$i]['Country_Name_EN']; //new
            $image=$top_destination_car[$i]['image'];
            $origin=$top_destination_car[$i]['origin'];
            $car_from_code=$top_destination_car[$i]['Airport_IATA'];
            $car_to_code=$top_destination_car[$i]['Airport_IATA'];
            $car_to=$top_destination_car[$i]['origin'];
            $driver_age=35;
      $url5='';
      $url5=base_url().'general/precarsearch/'.$from_ud.'/'.$origin.'/'.$car_from_code.'/'.$from_ud.'/'.$car_to.'/'.$car_to_code.'/'.$ddate.'/'.$tdate;
         ?>
        <div class="item">
          <div class="f-deal">
            <div class="f-deal-img">
           <a href="<?php echo $url5; ?>" ><img class="star_rat" src="<?php echo $GLOBALS['CI']->template->domain_images($top_destination_car[$i]['image']); ?>" alt="travels car booking" loading="lazy"/></a>
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
          <?php }else{  ?>
            <h2> <i class="fas fa-star"></i>Car</h2>
          <?php }  ?>
</a>
            </div>
          </div>
         </a>
        </div>
          <?php  if($i==9)break; } ?>      
      </div>
  </div>
</div>
<?php }?>
<div class="clearfix"></div>
<?php if(!empty($top_destination_activity)  && $show==0){ ?>
<div class="activities_deals_perfect">
  <div class="container">
    <div class="pagehdwrap">
                    <h3 class="pagehding"> Best Of Activities</h3>
      </div>
      <div id="activities-all_perfect" class="owl-carousel owlindex3 owl-theme">
    <?php 
           for ($i=0; $i < count($top_destination_activity); $i++) {
            
            $from = $top_destination_activity[$i]['destination_name'];
            $destination =  $top_destination_activity[$i]['origin'];
            $country_name = $top_destination_activity[$i]['destination_name'];
            $category_id=0;
      $url1 = '';
      $url1 = base_url().'general/presightseensearch/'.$from.'/'.$destination.'/'.$category_id;
        
                echo '<div class="item">';
           
            ?>
          <div class="f-deal slide" data-slide-index="0">
            <a href="">
            <div class="f-deal-img">
            <a href="" target="_blank"><img class="star_rat" src="<?php echo $GLOBALS['CI']->template->domain_images($top_destination_activity[$i]['image_activity']); ?>" alt="Best Activities in al khaleej"  loading="lazy"/></a>
            </div>
            <a href="<?php  if(!empty($from)){ 
            echo $url1; 
              }else{
                echo base_url(); ?>tours/details/<?php echo base64_encode($destination);
              }
            ?>">
            <div class="activities-info">
            <?php  if(!empty($from)){  ?>
          <h3> <!-- <i class="fas fa-star"> --></i> From <?php echo $country_name ?></h3>
            <?php }else{  ?>
                <h2><!--  <i class="fas fa-star"> --></i>Activities</h2>
            <?php }  ?>

            </div>
            </a>
          </div>
        <?php      
                echo '</div>';
          
      if($i==9)break;}
              ?>
    </div>
 </div>
</div>
</div> 
<? } ?>
<div class="clearfix"></div>
<?php
if(!empty($top_destination_package) && $show==0)
{
?>
<div class="poplr-holy-packg-mob" id="demos">
  <div class="container">
    <div class="pagehdwrap">
         <h3 class="pagehding">Popular Holiday Packages</h3>
        </div>
          <div class="owl-carousel owlindex3 owl-theme" id="owl-demomobile">
            <?php 
            ?>
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
          <div class="f-deal slide" data-slide-index="0">
                <a href="">
                <div class="f-deal-img">
                <a href="" target="_blank"><img class="star_rat" src="<?=base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/images/'.basename($pk->banner_image);?>" alt="Happy Summer test  Holidays" loading="lazy"/></a>
                </div>
                <a href="<?=base_url().'tours/details/'.base64_encode($top_destination_package[$k]->id)?>">
                <div class="flight-info">
                <h3> <i class="fas fa-star"></i><?php echo $top_destination_package[$k]->package_name; ?> <?=$tour_type_name['tour_type_name']?></h3>

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
//exit();
}
?>
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
        <img src="<?php echo $GLOBALS['CI']->template->domain_images($adv_image); ?>" alt="holidays packages from uae" loading="lazy">
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
<div class="ychoose">
    <div class="container">
        <div class="pagehdwrap">
            <h3 class="pagehding">We Offer</h3>
        </div>
        <div class="allys">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="threey">
                    <div class="apritopty"><img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/route.png" alt="Book a tour to India" loading="lazy"></div>
                    <div class="dismany">
                        <div class="number">Route Selection</div>
                        <div class="hedsprite">Alkhaleej is an all-in-one travel booking service provider for destinations across the globe.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="threey">
                    <div class="apritopty"><img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/support.png" alt="Book a tour to India" loading="lazy"></div>
                    <div class="dismany">
                        <div class="number">24/7 Support</div>
                        <div class="hedsprite">Alkhaleej is a global flight tracking service that provides you with real-time information about thousands of aircraft around the world.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="threey">
                   <div class="apritopty"><img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/choice.png" alt="Book a tour to India" loading="lazy"></div>
                    <div class="dismany">
                        <div class="number">Wide choice</div>
                        <div class="hedsprite">Alkhaleej is an all-in-one travel booking service provider for destinations across the globe.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="threey">
                    <div class="apritopty"><img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/tickets.png" alt="Book a tour to India" loading="lazy"></div>
                    <div class="dismany">
                        <div class="number">Buy Tickets</div>
                        <div class="hedsprite">Alkhaleej is an all-in-one travel booking service provider for destinations across the globe.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
      <div id="all_deal" class="owl-carousel owlindex3 owl-theme" >
     <?php foreach($promo_code_list as $pcode) { ?>
     <div class="gridItems">
            <div class="outerfullfuture flight_dl">
            	<h4><i class="fas fa-circle"></i> <?php echo strtoupper($pcode['module']); ?></h4>
               <div class="thumbnail_deal thumbnail_small_img">
                  <img class="" src="<?php echo $GLOBALS['CI']->template->template_images('promocode/'.$pcode['promo_code_image'])?>" alt="Lazy Owl Image" loading="lazy">
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

<div class="foot-above">
	<div class="foot-above-img">
	<img src="<?php echo $GLOBALS['CI']->template->domain_images('pexels-photo-672358.jpg'); ?>" alt="Book a tour to India" loading="lazy">
	</div>
</div>
<div class="clearfix"></div>
<?=$this->template->isolated_view('share/js/lazy_loader')?>
<script>
	
    $(document).ready(function() {
        var owl3 = $("#owl-demo3");
        owl3.owlCarousel({      
            itemsCustom : [
                [0, 1],
                [450, 2],
                [551, 3],
                [700, 4],
                [1000, 5],
                [1200, 6],
                [1400, 6],
                [1600, 6]
            ],
            navigation : false
        });
        var owl5 = $("#holiday_destinations");
        owl5.owlCarousel({      
            itemsCustom : [
                [0, 1],
                [450, 2],
                [551, 3],
                [700, 4],
                [1000, 4],
                [1200, 4],
                [1400, 4],
                [1600, 4]
            ],
            navigation : true,
            pagination: false
        });

    });
</script>
<script>
	  $(document).ready(function() {
        var owl9 = $("#owl-demo9");
        owl9.owlCarousel({      
            itemsCustom : [
                [0, 1],
                [450, 1],
                [551, 2],
                [700, 3],
                [1000, 3],
                [1200, 3],
                [1400, 3],
                [1600, 3]
            ],
            navigation : true,       
        });
    });
</script>
<!---mason laypout-->
<script>
$(document).ready(function(){
  $(".dropdown").on("hide.bs.dropdown", function(){
    $(".btn").html('Dropdown <span class="caret"></span>');
  });
  $(".dropdown").on("show.bs.dropdown", function(){
    $(".btn").html('Dropdown <span class="caret caret-up"></span>');
  });
});
 function copyElementText(data){
  		var text = document.getElementById('text'+data).innerText;
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
      items : 4,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
        navigation:true,
        pagination:false,
        autoPlay:true
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
      items : 4,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
        navigation:true,
        pagination:false,
        autoPlay:true
  });
         $("#fligt-all_perfect").owlCarousel({ 
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 4,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
        navigation:true,
        pagination:false,
        autoPlay:true
  });
         $("#hotel-all_perfect").owlCarousel({ 
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 4,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
        navigation:true,
        pagination:false,
        autoPlay:true
  });
         $("#car-all_perfect").owlCarousel({
      autoPlay: 3000, //Set AutoPlay to 3 seconds 
      items : 4,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
        navigation:true,
        pagination:false,
        autoPlay:true
  });
         $("#transfer-all_perfect").owlCarousel({
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 4,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
        navigation:true,
        pagination:false,
        autoPlay:true
  });
         $("#activities-all_perfect").owlCarousel({
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 4,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
        navigation:true,
        pagination:false,
        autoPlay:true
  });
         $("#car-all").owlCarousel({
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 4,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
        navigation:true,
        pagination:false,
        autoPlay:true
  });
         $("#transfer-all").owlCarousel({
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 4,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
        navigation:true,
        pagination:false,
        autoPlay:true
  });
      $("#activities-all").owlCarousel({
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 4,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
        navigation:true,
        pagination:false,
        autoPlay:true
  });
         $(".retmnus").owlCarousel({ 
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 3,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
      itemsTablet : [650,1],
      itemsMobile : [550,1],
      itemsMobile : [450,1],
        navigation:true,
        pagination:false,
        autoPlay:true
  });
         $("#TopAirLine_new").owlCarousel({
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 4,
      itemsDesktop : [1199,4],
      itemsDesktopSmall : [979,2],
      itemsTablet : [650,2],
      itemsMobile : [550,1],
      itemsMobile : [450,1],
 		navigation:true,
 		pagination:false,
    autoPlay:true
  });
 		$(".retmnus_new").owlCarousel({ 
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 3,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
      itemsTablet : [650,1],
      itemsMobile : [550,1],
      itemsMobile : [450,1],
        navigation:true,
        pagination:false,
        autoPlay:true
  });
 		$(".retmnus_car").owlCarousel({ 
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 3,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
        navigation:true,
        pagination:false,
        autoPlay:true
  });
 		$(".retmnus_transfer").owlCarousel({ 
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 3,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
        navigation:true,
        pagination:false,
        autoPlay:true
  });
 		$(".retmnus_activities").owlCarousel({
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 3,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
        navigation:true,
        pagination:false,
        autoPlay:true
  });
 		$(".retmnus_holiday").owlCarousel({ 
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 3,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,2],
        navigation:true,
        pagination:false,
        autoPlay:true
  });
    $("#TopAirLine_new").owlCarousel({
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 4,
      itemsDesktop : [1199,4],
      itemsDesktopSmall : [979,2],
      itemsTablet : [650,2],
      itemsMobile : [550,1],
      itemsMobile : [450,1],
    navigation:true,
    pagination:false,
        autoPlay:true
  });
    });
	  $('._numeric_only').on('keydown focus blur keyup change cut copy paste', function (e) {
				isNumber(e, e.keyCode, e.ctrlKey, e.metaKey, e.shiftKey);
			});
	  $(document).on('keyup','#phone', function() {
      var numvalue=$("#phone").val();
      let isnum = /^\d+$/.test(numvalue);
      if(!isnum){
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
<script >
  $(document).ready(function () {
    $("#flights").show();
   $("#hotelModules").on("click",function(){
    $(".hotel_modules2").hide();
      $(".hotel_modules").show();
});
$("#hotelModules2").on("click",function(){
   $(".hotel_modules").hide();
      $(".hotel_modules2").show();
});
$("#flightModules").on("click",function(){
   $(".hotel_modules").hide();
      $(".hotel_modules2").hide();
});
$("#carsModules").on("click",function(){
   $(".hotel_modules").hide();
      $(".hotel_modules2").hide();
});
$("#transfersModules").on("click",function(){
   $(".hotel_modules").hide();
      $(".hotel_modules2").hide();
});
$("#activitiesModules").on("click",function(){
   $(".hotel_modules").hide();
      $(".hotel_modules2").hide();
});
$("#holidaysModules").on("click",function(){
   $(".hotel_modules").hide();
      $(".hotel_modules2").hide();
});
$("#privatecar").on("click",function(){
   $(".hotel_modules").hide();
      $(".hotel_modules2").hide();
});
$("#privatejet").on("click",function(){
   $(".hotel_modules").hide();
      $(".hotel_modules2").hide();
});
$("#privatetransfer").on("click",function(){
   $(".hotel_modules").hide();
      $(".hotel_modules2").hide();
});
  });
</script>
<script>
$(document).ready(function(){
	var c_module = "<?=$selected_module?>";
	var c_module = (c_module != '')? c_module : 'flights';
module_content(c_module);

$('.script_data').click(function(){
   var type = $(this).data('id');
   module_content(type);
});
function module_content(c_module){
   var ret = {"flights":"flight_modules", "hotels":"hotel_modules", "transfers":"transfers_modules", "activities":"activities_modules", "buses":"buses_modules", "car":"cars_modules", "holidays":"holidays_modules"};

   $.each(ret, function(key, value) {
	   if(key == c_module){
		   $('.'+value).show();
	   }else{
		   $('.'+value).hide(); 
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
     navigation : true,
    slideBy: '8',
    slideBy: slidesPerPage,
    scrollPerPage : true,
     animateOut: 'fadeOut',
              smartSpeed: 200,
              slideSpeed: 500, 
    itemsCustom : [
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
        rows:   1 //custom option not used by Owl Carousel, but used by the algorithm below
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
$(".f-deal.slide").each( function() {  
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
    orderedBreakpoints.sort(function (a, b) {
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
      var updateRowsColsNb = function () {
        var width =  viewport();
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

      var updateCarousel = function () {
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
          var fakeColsNb = pageIndex * colsNb + (slidesNb >= (pageIndex * perPage + colsNb) ? colsNb : (slidesNb % colsNb));

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
                slides.filter('[data-slide-index=' + index + ']').detach().appendTo(fakeCol);
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

