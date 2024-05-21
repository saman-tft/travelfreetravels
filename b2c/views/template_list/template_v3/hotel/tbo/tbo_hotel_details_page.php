<?php

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/hotel_details_slider.js'), 'defer' => 'defer');

$booking_url = $GLOBALS['CI']->hotel_lib->booking_url($hotel_search_params['search_id']);





$mini_loading_image = '<div class="text-center loader-image"><img src="' . $GLOBALS['CI']->template->template_images('loader_v3.gif') . '" alt="Loading........"/></div>';

$loading_image = '<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="bounce4"></div></div>';

$_HotelDetails = $hotel_details['HotelInfoResult']['HotelDetails'];
//debug($hotel_details);exit;

$sanitized_data['HotelCode'] = $_HotelDetails['HotelCode'];

$sanitized_data['HotelName'] = $_HotelDetails['HotelName'];

$sanitized_data['StarRating'] = $_HotelDetails['StarRating'];

$sanitized_data['Description'] = $_HotelDetails['Description'];

$sanitized_data['Attractions'] = (isset($_HotelDetails['Attractions']) ? $_HotelDetails['Attractions'] : false);

$sanitized_data['HotelFacilities'] = (isset($_HotelDetails['HotelFacilities']) ? $_HotelDetails['HotelFacilities'] : false);
$sanitized_data['allowed_list'] = (isset($_HotelDetails['allowed_list']) ? $_HotelDetails['allowed_list'] : false);
$sanitized_data['not_allowed'] = (isset($_HotelDetails['not_allowed']) ? $_HotelDetails['not_allowed'] : false);

$sanitized_data['HotelPolicy'] = (isset($_HotelDetails['HotelPolicy']) ? $_HotelDetails['HotelPolicy'] : false);

$sanitized_data['SpecialInstructions'] = (isset($_HotelDetails['SpecialInstructions']) ? $_HotelDetails['SpecialInstructions'] : false);

$sanitized_data['Address'] = (isset($_HotelDetails['Address']) ? $_HotelDetails['Address'] : false);

$sanitized_data['PinCode'] = (isset($_HotelDetails['PinCode']) ? $_HotelDetails['PinCode'] : false);

$sanitized_data['HotelContactNo'] = (isset($_HotelDetails['HotelContactNo']) ? $_HotelDetails['HotelContactNo'] : false);

$sanitized_data['Latitude'] = (isset($_HotelDetails['Latitude']) ? $_HotelDetails['Latitude'] : 0);

$sanitized_data['Longitude'] = (isset($_HotelDetails['Longitude']) ? $_HotelDetails['Longitude'] : 0);

$sanitized_data['RoomFacilities'] = (isset($_HotelDetails['RoomFacilities']) ? $_HotelDetails['RoomFacilities'] : false);

$sanitized_data['Images'] = $_HotelDetails['Images'];



if ($sanitized_data['Images']) {

    $sanitized_data['Images'] = $sanitized_data['Images'];

} else {

    $sanitized_data['Images'] = $GLOBALS['CI']->template->template_images('default_hotel_img.jpg');

}

Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel-new.min.css'), 'media' => 'screen');

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel-new.min.js'), 'defer' => 'defer');



$base_url_image=base_url().'index.php/hotel/image_details_cdn';



//debug($_HotelDetails);exit;

?>



<?php

/**

 * Application VIEW

 */

// echo $GLOBALS['CI']->template->isolated_view('hotel/search_panel_summary');

?>

<script type="text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJfvWH36KY3rrRfopWstNfduF5-OzoywY"></script>

<script type="text/javascript">
/** Google Maps **/

var myCenter = new google.maps.LatLng(<?= floatval($sanitized_data['Latitude']) ?>,
    <?= floatval($sanitized_data['Longitude']); ?>);

var image_url = "<?php echo $GLOBALS['CI']->template->template_images() ?>";

var hotel_name = "<?php echo $sanitized_data['HotelName'] ?>";

/*function initialize()

 {

 var mapProp = {

 center:myCenter,

 zoom:10,

 mapTypeId:google.maps.MapTypeId.ROADMAP

 

 };

 

 var map = new google.maps.Map(document.getElementById("Map"), mapProp);

 

 var marker = new google.maps.Marker({

 position:myCenter,

 

 

 });

 

 marker.setMap(map);

 

 var infowindow = new google.maps.InfoWindow({

 content:hotel_name

 });

 

 google.maps.event.addListener(marker, "click", function() {

 infowindow.open(map, marker);

 });

 }

 google.maps.event.addDomListener(window, "load", initialize);*/
</script>

<div class="clearfix"></div>

<input type="hidden" id="latitude" value="<?= $sanitized_data['Latitude'] ?>">

<input type="hidden" id="longitude" value="<?= $sanitized_data['Longitude'] ?>">

<input type="hidden" id="api_base_url"
    value="<?= $GLOBALS['CI']->template->template_images('marker/green_hotel_map_marker.png') ?>">

<input type="hidden" id="hotel_name" value="<?php echo $sanitized_data['HotelName'] ?>">

<div class="search-result">

    <div class="container mobilepad">

        <div class="htl_dtls_cont htldetailspage">

            <div class="rowfstep">

                <!-- slider -->

                <div class="col-md-8 col-sm-12 col-xs-12 nopad">

                    <div class="col-md-12 nopad">

                        <div class="htladdet">

                            <span><?php echo strtoupper($sanitized_data['HotelName']) ?></span>

                            <div class="marhtldet">

                                <span class="locadres"><i class="fa fa-map-marker"
                                        aria-hidden="true"></i>&nbsp;<?php echo $sanitized_data['Address'] ?></span>

                                <ul class="htlratpz">

                                    <div class="stardetshtl"><span class="rating-no"><span class="hide"
                                                id="h-sr"><?= $sanitized_data['StarRating'] ?></span><?php echo print_star_rating($sanitized_data['StarRating']); ?></span>

                                    </div>

                                    <!-- <li><i class="fa fa-star" aria-hidden="true"></i></li> -->



                                </ul>

                                <?php if ($_HotelDetails['trip_adv_url']): ?>

                                <div class="triexcimg mobile_advisor">

                                    <a href="#"><img src="<?= $_HotelDetails['trip_adv_url'] ?>" alt=""></a>

                                </div>

                                <?php endif; ?>



                            </div>

                        </div>

                    </div>

                    <div class="col-md-12 nopad">

                        <div class="htldtdv">



                            <div id="hotel_top" class="owl-carousel owl-theme">

                                <?php if (valid_array($sanitized_data['Images']) == true) { ?>
                                <?php for($i=0;$i<count($_HotelDetails['Images']);$i++){?>

                                <div class="item">
                                    <?='<img src="'.$_HotelDetails['Images'][$i].'"/>'?>
                                </div>
                                <?php }?>

                                <?php }else { ?>

                                <?= '<img src="' . $GLOBALS['CI']->template->template_images('default_hotel_img.jpg') . '" alt="' . $sanitized_data['HotelName'] . '"/>' ?>

                                <?php } ?>

                            </div>

                            <div class="item hide" id="map_viewsld">

                                <div class="map_mobile_dets">

                                    <div id="Map" class="col-md-12" style="height:400px; width:100%;max-height: 400px;">
                                        Map</div>

                                </div>

                            </div>

                            <div id="hotel_bottom" class="owl-carousel owl-theme">

                                <?php if (valid_array($sanitized_data['Images']) == true) { ?>
                                <?php for($i=0;$i<count($_HotelDetails['Images']);$i++){?>
                                <div class="item">
                                    <?='<img src="'.$_HotelDetails['Images'][$i].'"/>'?>
                                </div>

                                <?php }?>

                                <?php }else { ?>

                                <?= '<img src="' . $GLOBALS['CI']->template->template_images('default_hotel_img.jpg') . '" alt="' . $sanitized_data['HotelName'] . '"/>' ?>

                                <?php } ?>

                                <!-- <div class="item hide">

                                  <div class="map_mobile_dets">

                                             <div id="Map" class="col-md-12" style="height:200px; width:100%">Map</div>

                                      </div> 

                               </div> -->

                            </div>

                            <div class="htlmapdtls" id="maphtlmapdtls">

                                <i class="fal fa-map-marker" aria-hidden="true"></i>

                            </div>

                            <div class="htlmapdtls hide" id="maphtlmapimages">

                                <i class="fas fa-images"></i>



                            </div>





                        </div>

                    </div>



                </div>

                <!-- slider end -->

                <div class="col-md-4 col-sm-12 col-xs-12 resmagfix">

                    <?php if ($_HotelDetails['trip_rating']): ?>

                    <div class="tridtls">

                        <div class="trirat">

                            <span class="trpratclr"><?= $_HotelDetails['trip_rating'] ?></span>

                            <?php

                            $star_rating = $_HotelDetails['trip_rating'];

                            $trip_text = '';

                            if ($star_rating == 3 || $star_rating == 3.5) {

                                $trip_text = 'Average';

                            } elseif ($star_rating == 4) {

                                $trip_text = 'Good';

                            } elseif ($star_rating == 4.5 || $star_rating == 5) {

                                $trip_text = 'Excellent';

                            } elseif ($star_rating == 2 || $star_rating == 2.5) {

                                $trip_text = 'Bad';

                            } elseif ($star_rating < 2) {

                                $trip_text = 'Very Bad';

                            }

                            ?>

                            <span class="triexcer"><?= $trip_text ?></span>

                        </div>



                        <?php if ($_HotelDetails['trip_adv_url']): ?>

                        <div class="triexcimg">

                            <span class="trptrvrat">TripAdvisor Traveler Rating</span>

                            <a href="#"><img src="<?= $_HotelDetails['trip_adv_url'] ?>" alt=""></a>

                        </div>

                        <?php endif; ?>

                    </div>

                    <?php endif; ?>

                    <div class="clearfix"></div>

                    <?php if (isset($_HotelDetails['first_room_details']) && empty($_HotelDetails['first_room_details']) == false): ?>

                    <div class="htlfull_dtls">

                        <div class="htlamtnyt">

                            <?php

                        //debug($_HotelDetails);exit;

                        //calculating room price per nights

                        $RoomPrice = $_HotelDetails['first_room_details']['Price']['RoomPrice'];

                        //echo $RoomPrice;

                        $no_of_nights = $hotel_search_params['no_of_nights'];

                        $per_night_price = ceil($RoomPrice / $no_of_nights);

                        //echo "per_night_price".$per_night_price;

                        $night_str = 'Night';

                        if ($no_of_nights > 1) {

                            $night_str = 'Nights';

                        }

                        //echo get_converted_currency_value($currency_obj->force_currency_conversion($RoomPrice));

                        ?>

                            <h2 class="amthtlrs"><strong>
                                    <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>
                                </strong></i>&nbsp;<?= roundoff_number($per_night_price) ?><span class="pernyt">/ Per
                                    Night</span></h2>



                            <div class="stdrmac">

                                <span class="stdnonaclt"><?= $_HotelDetails['first_room_details']['room_name'] ?></span>

                                <!-- <span class="bedndimg"><i class="fa fa-bed" aria-hidden="true"></i>&nbsp;Double Bed</span> -->

                            </div>

                        </div>



                        <div class="clearfix"></div>

                        <div class="htlamtnytstd">

                            <h4 class="amthtlrsstd"><i class=""
                                    aria-hidden="true"></i><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>&nbsp;<?= $RoomPrice ?><span
                                    class="pernytdet">( <?= $no_of_nights ?> <?= $night_str ?> )</span></h4>



                            <?php if ($_HotelDetails['first_rm_cancel_date']): ?>

                            <div class="stdrmac">

                                <span class="stdnonacltfre">Free Cancellation</span>

                                <span class="untdate">till
                                    <?php echo date('d M Y', strtotime($_HotelDetails['first_rm_cancel_date'])); ?></span>

                            </div>

                            <?php else: ?>

                            <div class="stdrmac">

                                <span class="stdnonacltfre">Cancellation Policy</span>

                                <span class="untdate">Non-Refundable</span>

                            </div>

                            <?php endif; ?>

                        </div>

                    </div>

                    <?php endif; ?>

                    <div class="clearfix"></div>

                    <div class="cheoutdv">

                        <div class="chkdatetacell">

                            <span class="chkin">Check-in</span>

                            <span class="chkdate"><?= date('d M Y', strtotime($_HotelDetails['checkin'])); ?></span>

                        </div>

                        <div class="chkdatetacell">

                            <span class="chkin">Check-out</span>

                            <span class="chkdate"><?= date('d M Y', strtotime($_HotelDetails['checkout'])); ?></span>

                        </div>

                        <div class="chkdatetacell">

                            <span class="chkin">Room Guests</span>

                            <?php

$adult_count = 0;

$child_count = 0;

foreach ($hotel_search_params['adult_config'] as $a_value) {

    $adult_count += $a_value;

}

foreach ($hotel_search_params['child_config'] as $c_value) {

    $child_count += $c_value;

}

$adult_str = 'adult';

if ($adult_count > 1) {

    $adult_str = 'adults';

}

$child_str = 'child';

if ($child_count > 1) {

    $child_str = 'childrens';

}

?>

                            <span class="chkdate"><?= $hotel_search_params['room_count'] ?> room & <?= $adult_count ?>
                                <?= $adult_str ?> <?php if ($child_count): ?> & <?= $child_count ?> <?= $child_str ?>
                                <?php endif; ?></span>

                        </div>



                    </div>

                    <div class="clearfix"></div>

                    <div class="htlbkbtn">

                        <?php

$common_params_url = '';

$common_params_url .= '<input type="hidden" name="CancellationPolicy[]"	value="Cancellation">'; //Balu A

$common_params_url .= '<input type="hidden" name="booking_source"	value="' . $params['booking_source'] . '">';

$common_params_url .= '<input type="hidden" name="search_id"		value="' . $hotel_search_params['search_id'] . '">';

$common_params_url .= '<input type="hidden" name="ResultIndex"		value="' . $params['ResultIndex'] . '">';

$common_params_url .= '<input type="hidden" name="op"				value="block_room">';

$common_params_url .= '<input type="hidden" name="GuestNationality"	value="' . ISO_INDIA . '" >';

$common_params_url .= '<input type="hidden" name="HotelName"		value="" >';

$common_params_url .= '<input type="hidden" name="StarRating"		value="">';

$common_params_url .= '<input type="hidden" name="HotelImage"		value="">'; //Balu A

$common_params_url .= '<input type="hidden" name="HotelAddress"		value="">'; //



$dynamic_params_url[] = $_HotelDetails['first_room_details']['Room_data']['RoomUniqueId'];



$dynamic_params_url = serialized_data($dynamic_params_url);



$temp_dynamic_params_url = '';

$temp_dynamic_params_url .= '<input type="hidden" name="token" value="' . $dynamic_params_url . '">';

$temp_dynamic_params_url .= '<input type="hidden" name="token_key" value="' . md5($dynamic_params_url) . '">';

?>

                        <!-- <form method="POST" action="<?= $booking_url ?>">-->

                        <?php

                       // echo $common_params_url . $temp_dynamic_params_url;

                        ?>

                        <a href="#rooms">

                            <button class="bookallbtn htlbkftsz" type="submit" id="selectroom">Book Now</button>

                        </a>

                        <!--  </form>-->



                    </div>

                </div>





                <!-- <div class="col-sm-4 col-xs-6 nopad ">

                                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

                                                <div class="carousel-inner" role="listbox">

<?php

//loop images

if (valid_array($sanitized_data['Images']) == true) {

    $visible = 'active';

    foreach ($sanitized_data['Images'] as $i_k => $i_v) {

        ?>

                                                                        <div class="item <?php echo $visible;

        $visible = ''; ?> ">

                                                                                <img src=<?php echo $i_v ?> alt="<?php echo $i_k ?>" class="img-responsive" style="width:100%; height:200px">

                                                                                <div class="carousel-caption">

                                                                                        <p><?php echo $sanitized_data['HotelName'] ?></p>

                                                                                </div>

                                                                        </div>

    <?php

    }

}

?>

                                                </div>

                                                

                                                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">

                                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>

                                                        <span class="sr-only">Previous</span>

                                                </a>

                                                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">

                                                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>

                                                        <span class="sr-only">Next</span>

                                                </a>

                                        </div>

                                </div>

                                <div class="col-sm-4 col-xs-6 nopad">

                                        <div class="innerdetspad">

                                                <div class="hoteldetsname"><?php echo strtoupper($sanitized_data['HotelName']); ?></div>

                                                <div class="stardetshtl"><span class="rating-no"><span class="hide" id="h-sr"><?= $sanitized_data['StarRating'] ?></span><?php echo print_star_rating($sanitized_data['StarRating']); ?></span></div>

                                                <div class="adrshtlo"><?php echo $sanitized_data['Address'] ?></div>

                                                <div class="butnbigs">

                                                        <a class="tonavtorum movetop">Select Rooms</a>

                                                </div>

                                        </div>

                                </div> -->

                <!-- <div class="col-sm-4 nopad map_mobile_dets">

                        <div id="Map" class="col-md-12" style="height:200px; width:100%">Map</div>

                </div>  -->

            </div>

        </div>

    </div>

    <div class="clearfix"></div>

    <div class="fulldowny">

        <div class="container mobilepad">

            <div class="fuldownsct">

                <div class="col-xs-12 nopad tab_htl_detail">

                    <div class="detailtab fulldetab shdoww">

                        <ul class="nav nav-tabs responsive-tabs">

                            <li class=""><a href="#htldets" data-toggle="tab">Hotel Details</a></li>

                            <li class="active roomstab"><a href="#rooms" data-toggle="tab">Rooms</a></li>

                            <li><a href="#facility" data-toggle="tab">Facilities</a></li>

                            <!-- <li><a href="#htlpolicy" data-toggle="tab">Hotel Policy</a></li> -->

                        </ul>

                        <div class="tab-content">

                            <!-- Hotel Detail-->

                            <div class="tab-pane" id="htldets">



                                <div class="innertabs">

                                    <h3 class="mobile_view_header">Description</h3>

                                    <!-- <div class="htldesdv">Hotel Description</div> -->

                                    <div id="hotel-additional-info" class="padinnerntb">

                                        <div class="lettrfty short-text"><?php echo $sanitized_data['Description'] ?>
                                        </div>

                                        <?php 

                                            if(strlen($sanitized_data['Description']) >582)

                                            {



                                            

                                            ?>

                                        <div class="show-more">

                                            <a href="#">Show More</a>



                                        </div>

                                        <?php }?>

                                    </div>

                                </div>

                            </div>

                            <!-- Hotel Detail End-->

                            <!-- Rooms-->

                            <div class="tab-pane active" id="rooms">



                                <div class="innertabs">

                                    <h3 class="mobile_view_header">Rooms</h3>



                                    <div class="">

                                        <div id="room-list" class="room-list romlistnh short-text1">
                                        <?php
                                        
                                        if($_GET["booking_source"]!="PTBSID0000000004201")
                                        {
                                        echo $loading_image;
                                        
                                        }
                                        ?>
                                        <?php
                                        for($i=0;$i<count($_HotelDetails['raw_room_data']);$i++)
                                        {
                                            $dynamic_params_url= $_HotelDetails['raw_room_data'][$i]['Id'];
                                            //debug($hotel_search_params);die;
$no_of_nights = $hotel_search_params['no_of_nights'];
	$night_str = 'Night';
	if($no_of_nights >1){
		$night_str = 'Nights';
	}
                                           	$dynamic_params_url = serialized_data($dynamic_params_url);
                                        ?>
                                            <div class="romconoutdv">
                                                <div class="col-sm-4 col-xs-6 nopad full_mobile">
                                                    <div class="romsfst"> <span class="romtypestd"><?=$_HotelDetails['raw_room_data'][$i]['RoomText'][1]?></span><span
                                                            class="noof_adult"><i class="fa fa-users"
                                                                aria-hidden="true"></i><?=$adult_count?><span
                                                                class="mobile_hide">adults</span></span>
                                                                <span
                                                            class="noof_adult"><i class="fa fa-users"
                                                                aria-hidden="true"></i><?=$child_count?><span
                                                                class="mobile_hide">Child</span></span><span
                                                            class="noof_adult"><i class="fa fa-bed"
                                                                aria-hidden="true"></i><span class="mobile_hide">No Of
                                                                Rooms:</span><?=$_HotelDetails['raw_room_data'][$i]['NumberOfRooms']?></span></div>
                                                </div>
                                                <div class="col-sm-4 col-xs-6 nopad full_mobile">
                                                    <div class="romsfst"> <span
                                                            class="romtypefrecan">Non-Refundable</span><a
                                                            id="cancel_10925193_0" class="shwrmsdv cancel-policy-btn"
                                                            data-hotel-code="10925193" data-key="0"
                                                            data-target="#roomCancelModal" data-toggle="modal"
                                                            data-cancellation-policy="W3siQ2hhcmdlIjo4MCwiQ2hhcmdlVHlwZSI6MiwiRnJvbURhdGUiOiIyMDIxLTA5LTE5IiwiVG9EYXRlIjoiMjAyMS0wOS0yMiIsIkN1cnJlbmN5IjoiVVNEIn1d"
                                                            data-policy-code="" data-rate-key="4"
                                                            data-search-id="6bad9cb0-9e9b-4457-90b1-c3708db4e36e"
                                                            data-room-price="80" data-booking-source="PTBSID0000000001"
                                                            data-tb-search-id="107421" data-non-refundable="0"
                                                            data-today-cancel-date="1">View Cancellation Policy</a>
                                                        <div class="clearfix"></div><span class="noof_ave"
                                                            data-toggle="tooltip"
                                                            data-title="Breakfast Buffet,Free breakfast,Free WiFi Free breakfast"
                                                            data-placement="right" data-original-title="" title=""><i
                                                                class="fa fa-check" aria-hidden="true"></i>Breakfast
                                                            Buffet,Free breakfast,Free WiFi Free breakfast</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-xs-12 nopad full_mobile">
                                                    <div class="romsfst mobile_bg">
                                                        <form method="POST"
                                                            action="https://www.alkhaleejtours.com/index.php/hotel/booking/<?php echo $hotel_search_params['search_id']; ?>">
                                                            <input type="hidden" name="booking_source"
                                                                value="<?=$_GET["booking_source"]?>"><input type="hidden"
                                                                name="search_id" value="<?=$hotel_search_params['search_id']?>"><input type="hidden"
                                                                name="ResultIndex"
                                                                value="<?=$_HotelDetails['raw_room_data'][$i]['Id']?>"><input
                                                                type="hidden" name="op" value="block_room"><input
                                                                type="hidden" name="GuestNationality" value="IN"><input
                                                                type="hidden" name="HotelName"
                                                                value="<?=$_HotelDetails['HotelName']?>"><input
                                                                type="hidden" name="StarRating" value="<?=$_HotelDetails['StarRating']?>"><input
                                                                type="hidden" name="HotelImage"
                                                                value="<?=$_HotelDetails['HotelPicture'][0]?>"><input
                                                                type="hidden" name="HotelAddress"
                                                                value="<?=$_HotelDetails['Address']?>"><input
                                                                type="hidden" name="CancellationPolicy[]"
                                                                value="<?=$_HotelDetails['raw_room_data']?>">
                                                                <input
                                                                type="hidden" name="sessionid"
                                                                value="<?=$_HotelDetails['SessionId']?>">
                                                                 <input
                                                                type="hidden" name="adult"
                                                                value="<?=$adult_count?>">
                                                                 <input
                                                                type="hidden" name="child"
                                                                value="<?=$child_count?>">
                                                                <input
                                                                type="hidden" name="token"
                                                                value="<?=$dynamic_params_url?>"><input
                                                                type="hidden" name="token_key"
                                                                value="<?=md5($dynamic_params_url)?>"><span
                                                                class="romtyprice"><i class="" aria-hidden="true"></i><?=$_HotelDetails['raw_room_data'][$i]['RoomRate']?><p class="ninenyt"><?=$no_of_nights?><?=$night_str?></p></span><button
                                                                class="b-btn bookallbtn book-now-btn rombtndv"
                                                                type="submit">Book</button></form>
                                                    </div>
                                                </div>
                                            </div>
<?php 
}?>

                                        </div>



                                        <div class="show-rooms">

                                            <a href="#" id="show-more-link" class="hide">Show More Rooms</a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- Rooms End-->

                            <!-- Facilities-->

                            <div class="tab-pane" id="facility">



                                <div class="innertabs">

                                    <h3 class="mobile_view_header">Facilities</h3>

                                    <div class="padinnerntb htlfac_lity">

                                        <?php

if (valid_array($sanitized_data['HotelFacilities']) == true) {

    //:p Did this for random color generation

    //$color_code = string_color_code('Balu A');

    $color_code = '#00a0e0';

    ?>

                                        <!-- <div class="hedinerflty">

                                                    Hotel Facilities

                                            </div> -->

                                        <?php

                                            //-- List group -->

                                            if ($sanitized_data['HotelFacilities']) {





                                                foreach ($sanitized_data['HotelFacilities'] as $ak => $av) {

                                                    ?>

                                        <div class="col-xs-4 col-md-3 nopad">

                                            <div class="facltyid">

                                                <span class="glyphicon glyphicon-check"
                                                    style="color:<?php echo $color_code ?>"></span> <?php echo $av; ?>
                                            </div>
                                        </div>

                                        <?php }

    }

    ?>





                                        <?php

}

?>

                                        <?php

                                        if (valid_array($sanitized_data['Attractions']) == true) {

                                            //:p Did this for random color generation

                                            //$color_code = string_color_code('Balu A');

                                            $color_code = '#00a0e0';

                                            ?>

                                        <div class="subfty">

                                            <div class="hedinerflty">

                                                Attractions

                                            </div>

                                            <?php

    //-- List group -->

    foreach ($sanitized_data['Attractions'] as $ak => $av) {

        ?>

                                            <div class="col-xs-4 nopad">
                                                <div class="facltyid"><span class="glyphicon glyphicon-check"
                                                        style="color:<?php echo $color_code ?>"></span>
                                                    <?php echo $av['Value']; ?></div>
                                            </div>

                                            <?php }

                                                ?>

                                        </div>

                                        <?php

                                        }

                                        ?>

                                    </div>

                                </div>

                            </div>

                            <!-- Facilities End-->





                            <!-- Allowed things-->
                                 <?php

if (valid_array($sanitized_data['allowed_list']) == true) {
    ?>
                            <div class="tab-pane" id="facility">



                                <div class="innertabs">

                                        <?php

if (valid_array($sanitized_data['allowed_list']) == true) {
    ?>
                                    <h3 class="mobile_view_header">Allowed Things</h3>
                                    <?php
}
?>
                                    
        

                                    <div class="padinnerntb htlfac_lity">

                                        <?php

if (valid_array($sanitized_data['allowed_list']) == true) {

    //:p Did this for random color generation

    //$color_code = string_color_code('Balu A');

    $color_code = '#00a0e0';

    ?>

                                        <!-- <div class="hedinerflty">

                                                    Hotel Facilities

                                            </div> -->

                                        <?php

                                            //-- List group -->

                                            if ($sanitized_data['allowed_list']) {





                                                foreach ($sanitized_data['allowed_list'] as $ak => $av) {

                                                    ?>

                                        <div class="col-xs-4 col-md-3 nopad">

                                            <div class="facltyid">

                                                <span class="glyphicon glyphicon-check"
                                                    style="color:<?php echo $color_code ?>"></span> <?php echo $av; ?>
                                            </div>
                                        </div>

                                        <?php }

    }

    ?>





                                        <?php

}

?>

                                    </div>

                                </div>

                            </div>
                                  <?php
}
?>
                            <!-- Allowed things End-->



                            <!-- Non Allowed things-->
                <?php

if (valid_array($sanitized_data['not_allowed']) == true) {
    ?>
                            <div class="tab-pane" id="facility">



                                <div class="innertabs">
                                <?php

if (valid_array($sanitized_data['not_allowed']) == true) {
    ?>
                                    <h3 class="mobile_view_header">Non Allowed Things</h3>
                                    <?php
                                    
}
?>

                                    <div class="padinnerntb htlfac_lity">

                                        <?php

if (valid_array($sanitized_data['not_allowed']) == true) {

//:p Did this for random color generation

//$color_code = string_color_code('Balu A');

$color_code = '#00a0e0';

?>

                                        <!-- <div class="hedinerflty">

                    Hotel Facilities

            </div> -->

                                        <?php

            //-- List group -->

            if ($sanitized_data['not_allowed']) {





                foreach ($sanitized_data['not_allowed'] as $ak => $av) {

                    ?>

                                        <div class="col-xs-4 col-md-3 nopad">

                                            <div class="facltyid">

                                                <span class="glyphicon glyphicon-check"
                                                    style="color:<?php echo $color_code ?>"></span> <?php echo $av; ?>
                                            </div>
                                        </div>

                                        <?php }

}

?>





                                        <?php

}

?>

                                    </div>

                                </div>

                            </div>
                                <?php
                                    
}
?>
                            <!-- Non Allowed things End-->


                            <!-- Policy-->

                            <!-- <div class="tab-pane" id="htlpolicy">

                                    <div class="innertabs hote_plcys">

                                            <div class="padinnerntb">

                                            <p><?php echo (empty($sanitized_data['HotelPolicy']) == false ? $sanitized_data['HotelPolicy'] : '---'); ?></p>

                                            </div>

                                            

                                    </div>

                            </div> -->

                            <!-- Policy End-->



                        </div>

                    </div>

                </div>

                <div class="col-xs-4 hide">

                    <div class="innertabs">

                        <?php if (valid_array($sanitized_data['HotelFacilities']) == true) { ?>

                        <div class="hedinerflty">

                            Hotel Facilities

                        </div>

                        <?php } ?>



                        <div class="padinnerntb htlfac_lity">

                            <?php

                            if (valid_array($sanitized_data['HotelFacilities']) == true) {

                                //:p Did this for random color generation

                                //$color_code = string_color_code('Balu A');

                                $color_code = '#00a0e0';

                                ?>



                            <?php

                                //-- List group -->

                                foreach ($sanitized_data['HotelFacilities'] as $ak => $av) {

                                    ?>

                            <div class="col-xs-12 nopad">

                                <div class="facltyid">

                                    <span class="glyphicon glyphicon-check"
                                        style="color:<?php echo $color_code ?>"></span> <?php echo $av; ?>
                                </div>
                            </div>

                            <?php }

                                ?>





                            <?php

                            }

                            ?>

                            <?php

                            if (valid_array($sanitized_data['Attractions']) == true) {

                                //:p Did this for random color generation

                                //$color_code = string_color_code('Balu A');

                                $color_code = '#00a0e0';

                                ?>

                            <div class="subfty">



                                <?php

                                    //-- List group -->

                                    foreach ($sanitized_data['Attractions'] as $ak => $av) {

                                        ?>

                                <div class="col-xs-4 nopad">
                                    <div class="facltyid"><span class="glyphicon glyphicon-check"
                                            style="color:<?php echo $color_code ?>"></span> <?php echo $av['Value']; ?>
                                    </div>
                                </div>

                                <?php }

                                    ?>

                            </div>

                            <?php

                            }

                            ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

/**

 * This is used only for sending hotel room request - AJAX

 */

$hotel_room_params['ResultIndex'] = $params['ResultIndex'];

$hotel_room_params['booking_source'] = $params['booking_source'];

$hotel_room_params['search_id'] = $hotel_search_params['search_id'];

$hotel_room_params['op'] = 'get_room_details';



?>



<script>
$(document).ready(function() {

    //Load hotel Room Details

    var ResultIndex = '';

    var HotelCode = '';

    var TraceId = '';

    var booking_source = '';

    var op = 'get_room_details';

    function load_hotel_room_details()

    {

        var _q_params = <?php echo json_encode($hotel_room_params) ?>;

        if (booking_source) {

            _q_params.booking_source = booking_source;

        }

        if (ResultIndex) {

            _q_params.ResultIndex = ResultIndex;

        }

        $.post(app_base_url + "index.php/ajax/get_room_details", _q_params, function(response) {

            if (response.hasOwnProperty('status') == true && response.status == true) {

                $('#room-list').html(response.data);

                var _hotel_name =
                    "<?php echo preg_replace('/^\s+|\n|\r|\s+$/m', '', $sanitized_data['HotelName']); //Hotel Name comes from hotel info response  ?>";

                var _hotel_star_rating = <?php echo abs($sanitized_data['StarRating']) ?>;

                var _hotel_image = "<?php echo $sanitized_data['Images'][0]; ?>";

                var _hotel_address =
                    "<?php echo preg_replace('/^\s+|\n|\r|\s+$/m', '', $sanitized_data['Address']); ?>";

                $('[name="HotelName"]').val(_hotel_name);

                $('[name="StarRating"]').val(_hotel_star_rating);

                $('[name="HotelImage"]').val(_hotel_image); //Balu A

                $('[name="HotelAddress"]').val(_hotel_address); //Balu A

            }

        });

    }

    load_hotel_room_details();

    $('.hotel_search_form').on('click', function(e) {

        e.preventDefault();

        $('#hotel_search_form').slideToggle(500);

    });





    $('.movetop').click(function() {

        $('html, body').animate({
            scrollTop: $('.fulldowny').offset().top - 60
        }, 'slow');

    });

    $(".close_fil_box").click(function() {

        $(".coleft").hide();

        $(".resultalls").removeClass("open");

    });





});
</script>



<?php

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');

?>