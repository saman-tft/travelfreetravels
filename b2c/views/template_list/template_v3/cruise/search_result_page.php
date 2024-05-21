<style>
.second_sec {
    margin: 20px 0;
}

.second_sec a {
    text-decoration: none;
}

.airinage img {
    width: 100%;
    height: 80px;
}

.airinage {

    background: #F4F4F4 none repeat scroll 0 0;

    display: block;

    margin: 0 10px;

    min-height: 85px;

    overflow: hidden;

    padding: 5px;

    text-align: center;

    max-height: 180px;

    min-height: 180px;

}

.topmatrix_ailine {

    color: #333;

    margin-top: 5px;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: clip;

    margin: 12px 0;

}

#owl-demo2 .owl-prev {

    position: absolute;

    left: -35px;

    top: 30%;

    background: url(../images/prev_icon.png);

    width: 35px;

    height: 60px;

    background-repeat: no-repeat;

}

#owl-demo2 .owl-next {

    position: absolute;

    right: -35px;

    top: 30%;

    background: url(../images/next_icon.png);

    width: 35px;

    height: 60px;

    background-repeat: no-repeat;

}

.features {

    display: block;

    width: 50%;

    overflow: hidden;

    margin: 12px auto 5px;

}

.features li {

    float: left;

    padding: 0px 12px;

    border-right: 1px solid #cbcbcc;

    list-style: none;

}

.features li strong {

    color: #525252;

    display: block;

    float: left;

    font-size: 15px;

    font-weight: normal;

    line-height: 25px;

}

.features li.person span {

    background-position: 0 0;

}

.features li .mn-icon {



    display: block;

    float: left;

    height: 24px;

    margin: 0 0px 0 5px;

    width: 24px;

}

.airinage {

    transition: all 600ms ease-in-out;

}

#owl-demo2 .airinage:hover {

    border: 1px solid red;

    transition: all 600ms ease-in-out;

}



.madgrid .col-xs-12.nopad {

    width: 100%;

    display: table;

}



.sidenamedesc {

    display: block;

    width: 75%;

    display: table-cell;

}



.celhtl.width60 {

    float: none;

    display: table-cell;

    vertical-align: middle;

}



.width20 {

    float: none !important;

    vertical-align: middle;

    display: table-cell !important;

}
</style>

<?php

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/car_search.js'), 'defer' => 'defer');


Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_session_expiry_script.js'), 'defer' => 'defer');

Js_Loader::$js[] = array('src' => JAVASCRIPT_LIBRARY_DIR.'jquery.jsort.0.4.min.js', 'defer' => 'defer');

Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/car_result.css'), 'media' => 'screen');

Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/owl.carousel.css'), 'media' => 'screen');

Js_Loader::$js[] = array('src' => JAVASCRIPT_LIBRARY_DIR.'jquery.nicescroll.js', 'defer' => 'defer');

echo $this->template->isolated_view('share/js/lazy_loader');
/*
foreach ($active_booking_source as $t_k => $t_v) {

	$active_source[] = $t_v['source_id'];

}

$active_source = json_encode($active_source);
*/
?>

<script>


</script>



<?php




	echo $GLOBALS['CI']->template->isolated_view('cruise/search_panel_summary');

	?>



<div id="page-parent">



    <div class="allpagewrp top80">



        <div class="clearfix"></div>

        <div class="search-result car_search_results">

            <div class="container">

                <?php // echo $GLOBALS['CI']->template->isolated_view('share/loader/car_result_pre_loader', $car_search_params); ?>

                <div class="filtrsrch layout_upgrade">

                    <div class="coleft">

                        <div class="flteboxwrp">





                            <div class="filtersho">

                                <div class="avlhtls"><strong id="filter_records">223</strong> Cruise found

                                </div>

                                <span class="close_fil_box"><i class="fa fa-close"></i></span>

                            </div>



                            <div class="fltrboxin">

                                <a id="reset_filters" class="pull-right">Reset All</a>

                                <div class="bnwftr">

                                    <div class="rangebox"><button data-target="#collapse501" data-toggle="collapse"
                                            class="collapsebtn" type="button">Price</button><strong><span
                                                id="total_result_count"></span></strong>
                                        <div id="collapse501" class="in collapse price_slider1">
                                            <div class="price_slider1">
                                                <div id="core_min_max_slider_values" class="hide"><input type="hidden"
                                                        id="core_minimum_range_value" value="13"><input type="hidden"
                                                        id="core_maximum_range_value" value="33997.91"></div>
                                                <p id="car-price" class="level">$ 13.00 - $ 33997.91</p>
                                                <div id="price-range"
                                                    class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"
                                                    aria-disabled="false">
                                                    <div class="ui-slider-range ui-widget-header ui-corner-all"
                                                        style="left: 0%; width: 100%;"></div><span
                                                        class="ui-slider-handle ui-state-default ui-corner-all"
                                                        tabindex="0" style="left: 0%;"></span><span
                                                        class="ui-slider-handle ui-state-default ui-corner-all"
                                                        tabindex="0" style="left: 100%;"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="septor"></div>

                                    <div class="rangebox cruise-range">

                                        <button data-target="#collapse505" data-toggle="collapse" class="collapsebtn"
                                            type="button">Destination</button>

                                        <div id="collapse505" class="collapse in">

                                            <div class="boxins">


                                                <ul class="locationul region">

                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="7"
                                                                class="regionCheckbox invalid-ip" id="region7"> <label
                                                                for="region7"></label> </div> <label for="dur1"
                                                            class="lbllbl">Mediterranean</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region8"> <label
                                                                for="region8"></label> </div> <label for="dur2"
                                                            class="lbllbl">Caribbean</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region9"> <label
                                                                for="region9"></label> </div> <label for="dur3"
                                                            class="lbllbl">North Europe</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region10"> <label
                                                                for="region10"></label> </div> <label for="dur4"
                                                            class="lbllbl">North America</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region11"> <label
                                                                for="region11"></label> </div> <label for="dur4"
                                                            class="lbllbl">Middle East</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region12"> <label
                                                                for="region12"></label> </div> <label for="dur4"
                                                            class="lbllbl">Asia / Far East</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region13"> <label
                                                                for="region13"></label>
                                                        </div> <label for="dur5" class="lbllbl">Transoceanic</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region14"> <label
                                                                for="region14"></label>
                                                        </div> <label for="dur6" class="lbllbl">Transpacific</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region15"> <label
                                                                for="region15"></label>
                                                        </div> <label for="dur7" class="lbllbl">World Tour</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region16"> <label
                                                                for="region16"></label>
                                                        </div> <label for="dur5" class="lbllbl">Atlantic</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region17"> <label
                                                                for="region17"></label>
                                                        </div> <label for="dur8" class="lbllbl">Repositioning</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region15"> <label
                                                                for="region18"></label>
                                                        </div> <label for="dur9" class="lbllbl">Egypt</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region19"> <label
                                                                for="region19"></label>
                                                        </div> <label for="dur10" class="lbllbl">Peaceful</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="1"
                                                                class="regionCheckbox invalid-ip" id="region20"> <label
                                                                for="region20"></label>
                                                        </div> <label for="dur11" class="lbllbl">Oceania</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="1"
                                                                class="regionCheckbox invalid-ip" id="region21"> <label
                                                                for="region21"></label>
                                                        </div> <label for="dur21" class="lbllbl">European rivers</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="1"
                                                                class="regionCheckbox invalid-ip" id="region22"> <label
                                                                for="region22"></label>
                                                        </div> <label for="dur13" class="lbllbl">Poles</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="1"
                                                                class="regionCheckbox invalid-ip" id="region23"> <label
                                                                for="region23"></label>
                                                        </div> <label for="dur14" class="lbllbl">Rivers of the
                                                            World</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="1"
                                                                class="regionCheckbox invalid-ip" id="region24"> <label
                                                                for="region24"></label>
                                                        </div> <label for="dur15" class="lbllbl">South Africa</label>
                                                    </li>


                                                </ul>

                                            </div>

                                        </div>

                                    </div>
                                    <div class="septor"></div>
                                    <div class="rangebox">

                                        <button data-target="#collapse506" data-toggle="collapse" class="collapsebtn"
                                            type="button">Departure Month</button>

                                        <div id="collapse506" class="collapse in">

                                            <div class="boxins">

                                                <ul class="locationul region">

                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="7"
                                                                class="regionCheckbox invalid-ip" id="region97"> <label
                                                                for="region97"></label> </div> <label for="dur1"
                                                            class="lbllbl">October 2021</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region98"> <label
                                                                for="region98"></label> </div> <label for="dur2"
                                                            class="lbllbl">November 2021</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region99"> <label
                                                                for="region99"></label> </div> <label for="dur3"
                                                            class="lbllbl">December 2021</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region910"> <label
                                                                for="region910"></label> </div> <label for="dur4"
                                                            class="lbllbl">January 2022</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region911"> <label
                                                                for="region911"></label> </div> <label for="dur4"
                                                            class="lbllbl">February 2022</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region912"> <label
                                                                for="region912"></label> </div> <label for="dur4"
                                                            class="lbllbl">March 2022</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region913"> <label
                                                                for="region913"></label>
                                                        </div> <label for="dur5" class="lbllbl">April 2022</label>
                                                    </li>
                                                </ul>

                                            </div>

                                        </div>

                                    </div>
                                    <div class="septor"></div>
                                    <div class="rangebox cruise-range">

                                        <button data-target="#collapse507" data-toggle="collapse" class="collapsebtn"
                                            type="button">Company</button>

                                        <div id="collapse507" class="collapse in">

                                            <div class="boxins">

                                                <ul class="locationul region">

                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region814"> <label
                                                                for="region814"></label></div> <label for="dur5"
                                                            class="lbllbl">MSC Cruises</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region815"> <label
                                                                for="region815"></label></div> <label for="dur5"
                                                            class="lbllbl">Costa Cruises</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region816"> <label
                                                                for="region816"></label></div> <label for="dur5"
                                                            class="lbllbl">Ponant</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region817"> <label
                                                                for="region817"></label></div> <label for="dur5"
                                                            class="lbllbl">Royal Caribbean</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region818"> <label
                                                                for="region818"></label></div> <label for="dur5"
                                                            class="lbllbl">Norwegian Cruise Line</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region819"> <label
                                                                for="region819"></label></div> <label for="dur5"
                                                            class="lbllbl">CroisiEurope</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region820"> <label
                                                                for="region820"></label></div> <label for="dur5"
                                                            class="lbllbl">Star clippers</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region821"> <label
                                                                for="region821"></label></div> <label for="dur5"
                                                            class="lbllbl">Celebrity cruises</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region822"> <label
                                                                for="region822"></label></div> <label for="dur5"
                                                            class="lbllbl">Aranui</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region823"> <label
                                                                for="region823"></label></div> <label for="dur5"
                                                            class="lbllbl">Lüftner Cruises</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region824"> <label
                                                                for="region824"></label></div> <label for="dur5"
                                                            class="lbllbl">Seabourn</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region825"> <label
                                                                for="region825"></label></div> <label for="dur5"
                                                            class="lbllbl">Holland America Line</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region826"> <label
                                                                for="region826"></label></div> <label for="dur5"
                                                            class="lbllbl">Princess cruises</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region827"> <label
                                                                for="region827"></label></div> <label for="dur5"
                                                            class="lbllbl">Regent Seven Seas Cruises</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region828"> <label
                                                                for="region828"></label></div> <label for="dur5"
                                                            class="lbllbl">Cruise</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region829"> <label
                                                                for="region829"></label></div> <label for="dur5"
                                                            class="lbllbl">Anakonda Amazon Cruises</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region830"> <label
                                                                for="region830"></label></div> <label for="dur5"
                                                            class="lbllbl">Nicko cruises</label>
                                                    </li>
                                                </ul>

                                            </div>

                                        </div>

                                    </div>
                                    <div class="septor"></div>
                                    <div class="rangebox">

                                        <button data-target="#collapse508" data-toggle="collapse" class="collapsebtn"
                                            type="button">Duration</button>

                                        <div id="collapse508" class="collapse in">

                                            <div class="boxins">
                                                <ul class="locationul region">
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region913"> <label
                                                                for="region913"></label></div> <label for="dur5"
                                                            class="lbllbl">Less than 7 days</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region914"> <label
                                                                for="region914"></label></div> <label for="dur5"
                                                            class="lbllbl">7 to 8 days</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region915"> <label
                                                                for="region915"></label></div> <label for="dur5"
                                                            class="lbllbl">9 to 12 days</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region916"> <label
                                                                for="region916"></label></div> <label for="dur5"
                                                            class="lbllbl">13 to 100 days</label>
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region917"> <label
                                                                for="region917"></label></div> <label for="dur5"
                                                            class="lbllbl">More than 100 days</label>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="septor"></div>
                                    <div class="rangebox cruise-range">

                                        <button data-target="#collapse509" data-toggle="collapse" class="collapsebtn"
                                            type="button">Departure Port</button>

                                        <div id="collapse509" class="collapse in">

                                            <div class="boxins">
                                                <ul class="locationul region">
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region1013">
                                                            <label for="region1013"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Cannes
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region1014">
                                                            <label for="region1014"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Marseilles
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region1015">
                                                            <label for="region1015"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Nice
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region1016">
                                                            <label for="region1016"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Le Havre
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region1017">
                                                            <label for="region1017"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Bordeaux
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region1018">
                                                            <label for="region1018"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Honfleur
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region1019">
                                                            <label for="region1019"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Paris
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region100"> <label
                                                                for="region100"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Sète
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region101"> <label
                                                                for="region101"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Strasbourg
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region102"> <label
                                                                for="region102"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Lyon
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region103"> <label
                                                                for="region103"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Avignon
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region104"> <label
                                                                for="region104"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Nantes
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region105"> <label
                                                                for="region105"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Arles
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region106"> <label
                                                                for="region106"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Martigues
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region107"> <label
                                                                for="region107"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Aigues Mortes
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region108"> <label
                                                                for="region108"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Mulhouse
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region109"> <label
                                                                for="region109"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Xouaxange
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region110"> <label
                                                                for="region110"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Montbeliard
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region111"> <label
                                                                for="region111"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Saverne
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region112"> <label
                                                                for="region112"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Chalon-sur-Sa
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region113"> <label
                                                                for="region113"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Nevers
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region114"> <label
                                                                for="region114"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Briare
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region115"> <label
                                                                for="region115"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Dijon
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region116"> <label
                                                                for="region116"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Besancon
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region117"> <label
                                                                for="region117"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Abu Dhabi
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region118"> <label
                                                                for="region118"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Amsterdam
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region119"> <label
                                                                for="region119"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>to sourt out
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region120"> <label
                                                                for="region120"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Oltenita
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region121"> <label
                                                                for="region121"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Salzburg
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region122"> <label
                                                                for="region122"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Tokay
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region123"> <label
                                                                for="region123"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Tegel
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region124"> <label
                                                                for="region124"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Johannesburg
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region125"> <label
                                                                for="region125"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Iquitos
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region126"> <label
                                                                for="region126"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Siem Reap
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region127"> <label
                                                                for="region127"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Toronto
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region128"> <label
                                                                for="region128"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>San Antonio
                                                    </li>
                                                    <li class="">
                                                        <div class="squaredThree"> <input type="checkbox" value="8"
                                                                class="regionCheckbox invalid-ip" id="region129"> <label
                                                                for="region129"></label></div> <label for="dur5"
                                                            class="lbllbl"></label>Milwaukee
                                                    </li>
                                                </ul>

                                            </div>

                                        </div>

                                    </div>













                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="colrit">

                        <div class="filters-cruise">
                            <div class="insidebosc">
                                <div class="topmisty hote_reslts">

                                    <div class="col-xs-12 nopad fullshort">

                                        <button class="filter_show"><i class="fa fa-filter"></i> <span
                                                class="text_filt">Filter</span></button>



                                        <div class="insidemyt">

                                            <div class="col-xs-12 nopad">

                                                <ul class="sortul cruise-sort">

                                                    <li class="sortli" data-sort="hn">

                                                        <a class="sorta asc name-l-2-h" data-order="asc">
                                                            <!-- <span class="fa fa-sort-alpha-asc"></span> -->
                                                            Departure
                                                        </a>

                                                        <a class="sorta des name-h-2-l hide" data-order="desc">
                                                            <!-- <span class="sirticon fa fa-sort-alpha-desc"></span> -->
                                                            Departure
                                                        </a>

                                                    </li>



                                                    <li class="sortli" data-sort="s">

                                                        <a class="sorta asc supplier-l-2-h" data-order="asc">
                                                            <!-- <span class="sirticon fa fa-user"></span> --> Duration
                                                        </a>

                                                        <a class="sorta des supplier-h-2-l hide" data-order="desc">
                                                            <!-- <span class="sirticon fa fa-user"></span> --> Duration
                                                        </a>

                                                    </li>



                                                    <li class="sortli" data-sort="sr">

                                                        <a class="sorta asc cartype-l-2-h" data-order="asc">
                                                            <!-- <span class="sirticon fa fa-star-o"></span> --> Price
                                                        </a>

                                                        <a class="sorta des cartype-h-2-l hide" data-order="desc">
                                                            <!-- <span class="sirticon fa fa-star-o"></span> --> Price
                                                        </a>

                                                    </li>




                                                </ul>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>




                        <!--All Available cars result comes here -->

                        <div class="cruise_results" id="cruise_search_result">

                            <div class="cruise-result-block">
                                <div class="ship-cruise">
                                    <div class="ship-image">
                                        <img src="https://www.alkhaleejtours.com/dev/extras/system/template_list/template_v3/images/cruise/cruise_image1.jpg"
                                            alt="">
                                    </div>
                                    <div class="ship-content">
                                        <h3>Civitavecchia (Rome) - Civitavecchia (Rome)</h3>
                                        <p>Western Mediterranean - 8 days aboard the ship Norwegian Epic<br />
                                            Voyage from 09/29/2021 to 10/06/2021 from Civitavecchia / Rome</p>
                                    </div>
                                    <div class="ship-company">
                                        <img src="https://www.alkhaleejtours.com/dev/extras/system/template_list/template_v3/images/cruise/c_logo_1.jpg"
                                            alt="">
                                    </div>
                                </div>
                                <hr />
                                <div class="ship-amenities">
                                    <div class="cruiser-amenity">

                                        <h4>Amenities Provided</h4>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-beer"></i> Drinks Included</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-cloud"></i> Smart and Cool Atmosphere</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-binoculars"></i> Shows on Board</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-music"></i> Activities for teen & Children</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-globe"></i> French Excursion</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-dollar-sign"></i> Wide Choices of comfort and Price</p>
                                        </div>
                                    </div>
                                    <div class="cruiser-price">
                                        <div class="cruise-price">
                                            <h3>$1580 <br /><span>per head</span></h3>
                                            <button><a href="<?=base_url().'cruise/cruise_detail'?>"
                                                    style="color:#fff;">View Details</a></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="ship-label tripxml">
                                    <p>
                                        Go on a cruise with complete peace of mind - <a href="#">See the protocols
                                            and sanitary measures taken by this company</a>
                                    </p>
                                </div>
                            </div>

                            <div class="cruise-result-block">
                                <div class="ship-cruise">
                                    <div class="ship-image">
                                        <img src="https://www.alkhaleejtours.com/dev/extras/system/template_list/template_v3/images/cruise/cruise_image2.jpg"
                                            alt="">
                                    </div>
                                    <div class="ship-content">
                                        <h3>Western mediterranean</h3>
                                        <p>Western Mediterranean - 8 days aboard the ship Norwegian Epic<br />
                                            Voyage from 09/29/2021 to 10/06/2021 from Civitavecchia / Rome</p>
                                    </div>
                                    <div class="ship-company">
                                        <img src="https://www.alkhaleejtours.com/dev/extras/system/template_list/template_v3/images/cruise/c_logo_2.jpg"
                                            alt="">
                                    </div>
                                </div>
                                <hr />
                                <div class="ship-amenities">
                                    <div class="cruiser-amenity">

                                        <h4>Amenities Provided</h4>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-beer"></i> Drinks Included</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-cloud"></i> Smart and Cool Atmosphere</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-binoculars"></i> Shows on Board</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-music"></i> Activities for teen & Children</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-globe"></i> French Excursion</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-dollar-sign"></i> Wide Choices of comfort and Price</p>
                                        </div>
                                    </div>
                                    <div class="cruiser-price">
                                        <div class="cruise-price">
                                            <h3>$1580 <br /><span>per head</span></h3>
                                            <button><a href="<?=base_url().'cruise/cruise_detail'?>"
                                                    style="color:#fff;">View Details</a></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="ship-label">
                                    <p>
                                        Go on a cruise with complete peace of mind - <a href="#">See the protocols
                                            and sanitary measures taken by this company</a>
                                    </p>
                                </div>
                            </div>


                            <div class="cruise-result-block">
                                <div class="ship-cruise">
                                    <div class="ship-image">
                                        <img src="https://www.alkhaleejtours.com/dev/extras/system/template_list/template_v3/images/cruise/cruise_image3.jpg"
                                            alt="">
                                    </div>
                                    <div class="ship-content">
                                        <h3>Caribbean Yacth Experience</h3>
                                        <p>Western Mediterranean - 8 days aboard the ship Norwegian Epic<br />
                                            Voyage from 09/29/2021 to 10/06/2021 from Civitavecchia / Rome</p>
                                    </div>
                                    <div class="ship-company">
                                        <img src="https://www.alkhaleejtours.com/dev/extras/system/template_list/template_v3/images/cruise/c_logo_3.jpg"
                                            alt="">
                                    </div>
                                </div>
                                <hr />
                                <div class="ship-amenities">
                                    <div class="cruiser-amenity">

                                        <h4>Amenities Provided</h4>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-beer"></i> Drinks Included</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-cloud"></i> Smart and Cool Atmosphere</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-binoculars"></i> Shows on Board</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-music"></i> Activities for teen & Children</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-globe"></i> French Excursion</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-dollar-sign"></i> Wide Choices of comfort and Price</p>
                                        </div>
                                    </div>
                                    <div class="cruiser-price">
                                        <div class="cruise-price">
                                            <h3>$1580 <br /><span>per head</span></h3>
                                            <button><a href="<?=base_url().'cruise/cruise_detail'?>"
                                                    style="color:#fff;">View Details</a></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="ship-label api">
                                    <p>
                                        Go on a cruise with complete peace of mind - <a href="#">See the protocols
                                            and sanitary measures taken by this company</a>
                                    </p>
                                </div>
                            </div>



                            <div class="cruise-result-block">
                                <div class="ship-cruise">
                                    <div class="ship-image">
                                        <img src="https://www.alkhaleejtours.com/dev/extras/system/template_list/template_v3/images/cruise/cruise_image1.jpg"
                                            alt="">
                                    </div>
                                    <div class="ship-content">
                                        <h3>Civitavecchia (Rome) - Civitavecchia (Rome)</h3>
                                        <p>Western Mediterranean - 8 days aboard the ship Norwegian Epic<br />
                                            Voyage from 09/29/2021 to 10/06/2021 from Civitavecchia / Rome</p>
                                    </div>
                                    <div class="ship-company">
                                        <img src="https://www.alkhaleejtours.com/dev/extras/system/template_list/template_v3/images/cruise/c_logo_1.jpg"
                                            alt="">
                                    </div>
                                </div>
                                <hr />
                                <div class="ship-amenities">
                                    <div class="cruiser-amenity">

                                        <h4>Amenities Provided</h4>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-beer"></i> Drinks Included</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-cloud"></i> Smart and Cool Atmosphere</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-binoculars"></i> Shows on Board</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-music"></i> Activities for teen & Children</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-globe"></i> French Excursion</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-dollar-sign"></i> Wide Choices of comfort and Price</p>
                                        </div>
                                    </div>
                                    <div class="cruiser-price">
                                        <div class="cruise-price">
                                            <h3>$1580 <br /><span>per head</span></h3>
                                            <button><a href="<?=base_url().'cruise/cruise_detail'?>"
                                                    style="color:#fff;">View Details</a></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="ship-label">
                                    <p>
                                        Go on a cruise with complete peace of mind - <a href="#">See the protocols
                                            and sanitary measures taken by this company</a>
                                    </p>
                                </div>
                            </div>

                            <div class="cruise-result-block">
                                <div class="ship-cruise">
                                    <div class="ship-image">
                                        <img src="https://www.alkhaleejtours.com/dev/extras/system/template_list/template_v3/images/cruise/cruise_image2.jpg"
                                            alt="">
                                    </div>
                                    <div class="ship-content">
                                        <h3>Western mediterranean</h3>
                                        <p>Western Mediterranean - 8 days aboard the ship Norwegian Epic<br />
                                            Voyage from 09/29/2021 to 10/06/2021 from Civitavecchia / Rome</p>
                                    </div>
                                    <div class="ship-company">
                                        <img src="https://www.alkhaleejtours.com/dev/extras/system/template_list/template_v3/images/cruise/c_logo_2.jpg"
                                            alt="">
                                    </div>
                                </div>
                                <hr />
                                <div class="ship-amenities">
                                    <div class="cruiser-amenity">

                                        <h4>Amenities Provided</h4>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-beer"></i> Drinks Included</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-cloud"></i> Smart and Cool Atmosphere</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-binoculars"></i> Shows on Board</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-music"></i> Activities for teen & Children</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-globe"></i> French Excursion</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-dollar-sign"></i> Wide Choices of comfort and Price</p>
                                        </div>
                                    </div>
                                    <div class="cruiser-price">
                                        <div class="cruise-price">
                                            <h3>$1580 <br /><span>per head</span></h3>
                                            <button><a href="<?=base_url().'cruise/cruise_detail'?>"
                                                    style="color:#fff;">View Details</a></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="ship-label api">
                                    <p>
                                        Go on a cruise with complete peace of mind - <a href="#">See the protocols
                                            and sanitary measures taken by this company</a>
                                    </p>
                                </div>
                            </div>


                            <div class="cruise-result-block">
                                <div class="ship-cruise">
                                    <div class="ship-image">
                                        <img src="https://www.alkhaleejtours.com/dev/extras/system/template_list/template_v3/images/cruise/cruise_image3.jpg"
                                            alt="">
                                    </div>
                                    <div class="ship-content">
                                        <h3>Caribbean Yacth Experience</h3>
                                        <p>Western Mediterranean - 8 days aboard the ship Norwegian Epic<br />
                                            Voyage from 09/29/2021 to 10/06/2021 from Civitavecchia / Rome</p>
                                    </div>
                                    <div class="ship-company">
                                        <img src="https://www.alkhaleejtours.com/dev/extras/system/template_list/template_v3/images/cruise/c_logo_3.jpg"
                                            alt="">
                                    </div>
                                </div>
                                <hr />
                                <div class="ship-amenities">
                                    <div class="cruiser-amenity">

                                        <h4>Amenities Provided</h4>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-beer"></i> Drinks Included</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-cloud"></i> Smart and Cool Atmosphere</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-binoculars"></i> Shows on Board</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-music"></i> Activities for teen & Children</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-globe"></i> French Excursion</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-dollar-sign"></i> Wide Choices of comfort and Price</p>
                                        </div>
                                    </div>
                                    <div class="cruiser-price">
                                        <div class="cruise-price">
                                            <h3>$1580 <br /><span>per head</span></h3>
                                            <button><a href="<?=base_url().'cruise/cruise_detail'?>"
                                                    style="color:#fff;">View Details</a></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="ship-label">
                                    <p>
                                        Go on a cruise with complete peace of mind - <a href="#">See the protocols
                                            and sanitary measures taken by this company</a>
                                    </p>
                                </div>
                            </div>

                            <div class="cruise-result-block">
                                <div class="ship-cruise">
                                    <div class="ship-image">
                                        <img src="https://www.alkhaleejtours.com/dev/extras/system/template_list/template_v3/images/cruise/cruise_image1.jpg"
                                            alt="">
                                    </div>
                                    <div class="ship-content">
                                        <h3>Caribbean Yacth Experience</h3>
                                        <p>Western Mediterranean - 8 days aboard the ship Norwegian Epic<br />
                                            Voyage from 09/29/2021 to 10/06/2021 from Civitavecchia / Rome</p>
                                    </div>
                                    <div class="ship-company">
                                        <img src="https://www.alkhaleejtours.com/dev/extras/system/template_list/template_v3/images/cruise/c_logo_3.jpg"
                                            alt="">
                                    </div>
                                </div>
                                <hr />
                                <div class="ship-amenities">
                                    <div class="cruiser-amenity">

                                        <h4>Amenities Provided</h4>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-beer"></i> Drinks Included</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-cloud"></i> Smart and Cool Atmosphere</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-binoculars"></i> Shows on Board</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-music"></i> Activities for teen & Children</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-globe"></i> French Excursion</p>
                                        </div>
                                        <div class="cruiser-a1">
                                            <p><i class="fa fa-dollar-sign"></i> Wide Choices of comfort and Price</p>
                                        </div>
                                    </div>
                                    <div class="cruiser-price">
                                        <div class="cruise-price">
                                            <h3>$1580 <br /><span>per head</span></h3>
                                            <button><a href="<?=base_url().'cruise/cruise_detail'?>"
                                                    style="color:#fff;">View Details</a></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="ship-label">
                                    <p>
                                        Go on a cruise with complete peace of mind - <a href="#">See the protocols
                                            and sanitary measures taken by this company</a>
                                    </p>
                                </div>
                            </div>






                        </div>

                        <!-- End of result -->

                        <!--Map view indipendent hotel-->


                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<div id="empty-search-result" class="jumbotron container" style="display:none">

    <img src="/extras/system/template_list/template_v3/images/oops-bg.png" alt="">


</div>

<?php // echo $GLOBALS['CI']->template->isolated_view('share/flight_session_expiry_popup');?>



<script>
/*  Mobile Filter  */

$('.filter_show').click(function() {

    $('.filtrsrch').stop(true, true).toggleClass('open');

    $('.col30').addClass('round_filt');

    $(".col30.round_filt").show();

});

$(".close_fil_box").click(function() {

    $(".col30.round_filt").hide();

});
</script>

<script>
$(document).ready(function() {

    $("#owl-demo2").owlCarousel({

        items: 6,

        itemsDesktop: [1000, 6],

        itemsDesktopSmall: [900, 4],

        itemsTablet: [600, 2],

        itemsMobile: [479, 1],

        navigation: true,

        navigationText: [],

        pagination: false,

        autoPlay: 5000

    });





});
</script>





<script type="text/javascript">
$(document).ready(function() {

    $('[data-toggle="tooltip"]').tooltip();

});
</script>