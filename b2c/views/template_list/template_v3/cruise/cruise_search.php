<?php
// debug($car_search_params);exit;
	//Read cookie when user has not given any search
// 	if ((isset($car_search_params) == false) || (isset($car_search_params) == true && valid_array($car_search_params) == false)) {
// 		$sparam = $this->input->cookie('sparam', TRUE);
// 		$sparam = unserialize($sparam);
// 		$sid = intval(@$sparam[META_CAR_COURSE]);
// 		// echo $sid;exit;
// 		if ($sid > 0) {
// 			$car_search_params = $this->car_model->get_safe_search_data($sid, true);
// 			$car_search_params = $car_search_params['data'];
// 		// debug($car_search_params);exit;
// 			if (strtotime(@$car_search_params['bus_date_1']) < time()) {
// 				$bus_search_params['bus_date_1'] = date('d-m-Y', strtotime(add_days_to_date(3)));
// 			}
// 		}
// 	}
	Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/car_suggest.js'), 'defer' => 'defer');
	
	//$car_datepicker = array(array('car_datepicker1', FUTURE_DATE_DISABLED_MONTH), array('car_datepicker2', FUTURE_DATE_DISABLED_MONTH));
	//$this->current_page->set_datepicker($car_datepicker);
	
?>
<!-- 
<style>

	
	/*.only_for_car .padselct { padding: 0px 22px; }
	.only_for_car .selctmark::after { background: none; right:-5px; }*/
</style> -->


<!--  novalidate="novalidate" -->
<form id="trasfer" name="car" autocomplete="off" action="<?=base_url()?>cruise/search">
    <div class="tabspl forbusonly car_form only_for_car" id="car_form">
        <div class="clearfix"></div>
        <div class="outsideserach custom_divclass">
            <div class="clearfix"></div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 cr_plce">
                <div class="col-sm-12 col-xs-12 padfive mobile_width">
                    <div class="marginbotom10">
                        <span class="formlabel">Destination</span>
                        <select name="depature_time" id="depature_time" class="normalsel padselct dep_t arimo">
                            <option value="1">Any Destination</option>
                            <option value="1">Mediterranean</option>
                            <option value="11">Caribbean</option>
                            <option value="6">North Europe</option>
                            <option value="29">South America</option>
                            <option value="25">North America</option>
                            <option value="16">Middle East</option>
                            <option value="38">Asia / Far East</option>
                            <option value="19">Indian Ocean</option>
                            <option value="43">Transoceanic</option>
                            <option value="44">Transpacific</option>
                            <option value="45">World Tour</option>
                            <option value="20">Atlantic</option>
                            <option value="53">Repositioning</option>
                            <option value="96">Egypt</option>
                            <option value="30">Peaceful</option>
                            <option value="34">Oceania</option>
                            <option value="46">European rivers</option>
                            <option value="50">Poles</option>
                            <option value="58">Rivers of the World</option>
                            <option value="61">South Africa</option>

                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 cr_plce">
                <div class="col-sm-12 col-xs-12 padfive mobile_width">
                    <div class="marginbotom10">
                        <span class="formlabel">Departure Month</span>
                        <select name="depature_time" id="depature_time" class="normalsel padselct dep_t arimo">

                            <option value="">Date of departure</option>
                            <option value="2021-09-01">September 2021</option>
                            <option value="2021-10-01">October 2021</option>
                            <option value="2021-11-01">November 2021</option>
                            <option value="2021-12-01">December 2021</option>
                            <option value="2022-01-01">January 2022</option>
                            <option value="2022-02-01">February 2022</option>
                            <option value="2022-03-01">March 2022</option>
                            <option value="2022-04-01">April 2022</option>
                            <option value="2022-05-01">May 2022</option>
                            <option value="2022-06-01">June 2022</option>
                            <option value="2022-07-01">July 2022</option>
                            <option value="2022-08-01">August 2022</option>
                            <option value="2022-09-01">September 2022</option>
                            <option value="2022-10-01">October 2022</option>
                            <option value="2022-11-01">November 2022</option>
                            <option value="2022-12-01">December 2022</option>
                            <option value="2023-01-01">January 2023</option>
                            <option value="2023-02-01">February 2023</option>
                            <option value="2023-03-01">March 2023</option>
                            <option value="2023-04-01">April 2023</option>
                            <option value="2023-05-01">May 2023</option>
                            <option value="2023-06-01">June 2023</option>
                            <option value="2023-07-01">July 2023</option>
                            <option value="2023-08-01">August 2023</option>
                            <option value="2023-09-01">September 2023</option>
                            <option value="2023-10-01">October 2023</option>
                            <option value="2023-11-01">November 2023</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 cr_plce">
                <div class="col-sm-12 col-xs-12 padfive mobile_width">
                    <div class="marginbotom10">
                        <span class="formlabel">Company Name</span>
                        <select name="depature_time" id="depature_time" class="normalsel padselct dep_t arimo">

                            <option value="">Company</option>
                            <option value="9">MSC Cruises</option>
                            <option value="3">Costa Cruises</option>
                            <option value="2">Ponant</option>
                            <option value="16">Royal Caribbean</option>
                            <option value="28">Norwegian Cruise Line</option>
                            <option value="5">CroisiEurope</option>
                            <option value="12">Star clippers</option>
                            <option value="15">Celebrity cruises</option>
                            <option value="19">Aranui</option>
                            <option value="20">Lüftner Cruises</option>
                            <option value="23">Seabourn</option>
                            <option value="25">Holland America Line</option>
                            <option value="26">Princess cruises</option>
                            <option value="31">Regent Seven Seas Cruises</option>
                            <option value="41">Cruise</option>
                            <option value="42">Anakonda Amazon Cruises</option>
                            <option value="44">Nicko cruises</option>

                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 cr_plce">
                <div class="col-sm-12 col-xs-12 padfive mobile_width">
                    <div class="marginbotom10">
                        <span class="formlabel">Cruise Length</span>
                        <select name="depature_time" id="depature_time" class="normalsel padselct dep_t arimo">

                            <option value="">Duration</option>
                            <option value="0-6">Less than 7 days</option>
                            <option value="7-8">7 to 8 days</option>
                            <option value="9-12">9 to 12 days</option>
                            <option value="13-100">13 to 100 days</option>
                            <option value="101-300">More than 100 days</option>

                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 cr_plce">
                <div class="col-sm-12 col-xs-12 padfive mobile_width">
                    <div class="marginbotom10">
                        <span class="formlabel">Departure Port</span>
                        <select name="depature_time" id="depature_time" class="normalsel padselct dep_t arimo">

                            <option value="">Departure port</option>
                            <option value="54">Cannes</option>
                            <option value="208">Marseilles</option>
                            <option value="411">Nice</option>
                            <option value="424">Le Havre</option>
                            <option value="772">Bordeaux</option>
                            <option value="894">Honfleur</option>
                            <option value="964">Paris</option>
                            <option value="1145">Sète</option>
                            <option value="1263">Strasbourg</option>
                            <option value="1292">Lyon</option>
                            <option value="1296">Avignon</option>
                            <option value="1624">Nantes</option>
                            <option value="2826">Arles </option>
                            <option value="3031">Martigues</option>
                            <option value="3107">Aigues Mortes</option>
                            <option value="3114">Mulhouse</option>
                            <option value="3217">Xouaxange</option>
                            <option value="3222">Montbeliard</option>
                            <option value="3227">Saverne</option>
                            <option value="3232">Chalon-sur-Saone</option>
                            <option value="3237">Nevers</option>
                            <option value="3240">Briare</option>
                            <option value="3241">Dijon</option>
                            <option value="3245">Besancon</option>
                            <option value="2">Abu Dhabi</option>
                            <option value="12">Amsterdam</option>
                            <option value="3032">to sourt out</option>
                            <option value="3041">Oltenita</option>
                            <option value="3050">Salzburg</option>
                            <option value="3105">Tokay</option>
                            <option value="3269">Tegel</option>
                            <option value="3280">Johannesburg</option>
                            <option value="3283">Iquitos</option>
                            <option value="3288">Siem Reap</option>
                            <option value="3464">Toronto</option>
                            <option value="3574">San Antonio</option>
                            <option value="3583">Milwaukee</option>
                            <option value="3615">Old Fort</option>
                            <option value="3619">Remich</option>
                            <option value="3624">Brussel (brussels)</option>
                            <option value="3626">Berlin Spandau</option>
                            <option value="3708">Hurghada</option>
                            <option value="3792">Mantua</option>
                            <option value="3867">Stuttgart</option>
                            <option value="3904">Cairo</option>
                            <option value="3983">Setùbal</option>
                            <option value="4051">Jeddah</option>
                            <option value="4227">Port Rashid</option>
                        </select>
                    </div>
                </div>
            </div>



            <div class="col-lg-2 col-md-2 col-xs-12 nopad">
                <div class="formlabel">&nbsp;</div>

                <div class="searchsbmtfot">
                    <input type="submit" name="search_flight" id="" class="searchsbmt comncolor flight_search_btn"
                        value="search" />
                </div>
                <!-- <button class="searchsbmt comncolor" id="car_form_btn">Search<span class="srcharow"></span></button> -->

            </div>



        </div>
    </div>
</form>

<script>
$(function() {
    $("#diff_loc").click(function() {
        if ($(this).is(":checked")) {
            $("#Drop-of").show();
            $("#car_to").prop('required', true);
        } else {
            $("#Drop-of").hide();
            $("#car_to").prop('required', false);
        }
    });
});

$(function() {
    $("#driver_age").click(function() {
        if ($(this).is(":not(:checked)")) {
            $("#add_age").show();
        } else {
            $("#add_age").hide();
        }
        $(".remove_age").hide();
    });
});


// var age_type = "<?php echo @$car_search_params['age_type']; ?>"

// if(age_type == true){
// 	$('#add_age').removeClass("hide");
// }else{
// 	$('#remove_age').removeClass("hide");
// }


// $( "#trasfer" ).submit(function( event ) {
// 	alert();
// 	var age = $('#age_type').val();
// 	var age_val = $('#cus_numeric').val()

// 	if((age == 1) && (isNaN(age_val) == false) && (age_val >= 16)){

// 	  }else{

// 	  	if(age == 1){
// 	  		alert("Enter age (Minimum driver's age is 16) ");
// 	  		event.preventDefault();
// 	  	}
// 	}	
// });
</script>