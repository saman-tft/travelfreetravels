<?php
    $discount_details = discount_details();
    $discount = array();
    if($discount_details['status'] == SUCCESS_STATUS){
        $discount = $discount_details['data'];
    }
?>
<script type="text/javascript">
	$("#show_inbound").click(function(){
        $("#t-w-i-1").show();
        $("#t-w-i-2").hide();
    });

    $("#show_outbound").click(function(){
        $("#t-w-i-2").show();
        $("#t-w-i-1").hide();
    });
	
	$(".dom_tab_div a").click(function(){
		$(".dom_tab_div a").removeClass("active");
		$(this).addClass("active");
	});
</script>
<?php
//Images Url
$template_images = $GLOBALS['CI']->template->template_images();
$journey_summary = $raw_flight_list['JourneySummary'];
$IsDomestic = $journey_summary['IsDomestic'];
$flights_data = $raw_flight_list['Flights'];
$mini_loading_image = '<div class="text-center loader-image">Please Wait</div>';
//Dividing cols
$col_parent_division = '';
$col_division = '';
if ($route_count == 1) {
	$col_division = 'rondnone';
	//check if not
	if ($trip_type != 'oneway') {
		$col_parent_division = 'round-trip';
	}$div_dom ='';
} elseif ($route_count == 2) {
	$col_division = 'rondnone';
	$col_parent_division = 'round-domestk';
	$div_dom =  '<div class="dom_tab"><div class="dom_tab_div"> <a href="#" class="active" id="show_inbound">'.$journey_summary["Origin"].' to '.$journey_summary["Destination"].'</a> <a href="#" id="show_outbound">'.$journey_summary["Destination"].' to '.$journey_summary["Origin"].'</a>
	             </div></div>';
}

$loc_dir_icon = '<div class="arocl fas fa-long-arrow-alt-right"></div>';
//Change booking button based on type of flight
if ($domestic_round_way_flight) {
	$booking_button = '<button class="bookallbtn mfb-btn" type="button">Book</button>';//multi flight booking
} else {
	$booking_button = '<button class="b-btn bookallbtn" type="submit">Book Now</button>';
}
if(($trip_type == 'circle') && ($IsDomestic == true)){
    $flights = '<div class="row ' . $col_parent_division . '">' . $div_dom;
}else{
    $flights = '';// FIXME
}
//$flights = '<div class="row '.$col_parent_division.'">'.$div_dom;
$__root_indicator = 1000;
foreach ($flights_data as $__tirp_indicator => $__trip_flights) {
	$__root_indicator++;
	//$flights .= '<div class="'.$col_division.' r-w-g nopad" id="t-w-i-'.$__root_indicator.'">';
	if(($trip_type == 'circle') && ($IsDomestic == true)){
        $flights .= '<div class="' . $col_division . ' r-w-g nopad" id="t-w-i-' . $__root_indicator . '">';
    }
	foreach ($__trip_flights as $__trip_flight_k => $__trip_flight_v) {
		$segme_cnt = 0;
        foreach($__trip_flight_v['SegmentDetails'] as $sdk => $sdv){
            $segme_cnt += count($sdv);
        }
		if($__trip_flight_v['SegmentSummary'][0]['TotalStops'] > 0){
		     $stop_air='';
			$stop_air = $__trip_flight_v['SegmentDetails'][0][0]['DestinationDetails']['AirportCode'];
			 if(isset($__trip_flight_v['SegmentDetails'][0][1]['DestinationDetails']['AirportCode']) && $__trip_flight_v['SegmentSummary'][0]['TotalStops'] > 1) {
                $stop_air.= ' | '.$__trip_flight_v['SegmentDetails'][0][1]['DestinationDetails']['AirportCode'];
                 
            }
			$stop_air1 ='<div class="city_code1">'.$stop_air.'</div>';
		}
		else{
			$stop_air1 = '';
		}
		$cur_ProvabAuthKey = $__trip_flight_v['ProvabAuthKey'];
                
		$cur_AirlineRemark = trim($__trip_flight_v['AirlineRemark']);
		$remark_separator = empty($cur_AirlineRemark) == false ? '| ' : '';
		$cur_FareDetails = $__trip_flight_v['FareDetails']['b2b_PriceDetails'];
		$cur_SegmentDetails = $__trip_flight_v['SegmentDetails'];
		$cur_SegmentSummary = $__trip_flight_v['SegmentSummary'];
		$cur_IsRefundable = $__trip_flight_v['Attr']['IsRefundable'];
		$booking_source = $__trip_flight_v['booking_source'];
		//Reset This Everytime
		$inner_summary = $outer_summary = '';
		$cur_Origin					= $journey_summary['Origin'];
		$cur_Destination			= $journey_summary['Destination'];
		$Refundable_lab = ($cur_IsRefundable == false ? 'Non-Refundable' : 'Refundable');
		if(isset($__trip_flight_v['Attr']['FareType'])){
            if(empty($__trip_flight_v['Attr']['AirlineRemark']) == false){
                $cur_AirlineRemark = $__trip_flight_v['Attr']['AirlineRemark'];
            }
            else{
                $cur_AirlineRemark = $__trip_flight_v['Attr']['FareType'];
            }
            
        }
		//Price Details
		$final_disc = 0;
		$cus_total_fare = $cur_FareDetails['_CustomerBuying'];	
		if(@$discount['value_type'] == 'plus'){
			$disc_val = $discount['value'];
            $final_disc = $disc_val * $segme_cnt;
            $cus_total_fare = $cus_total_fare - $final_disc;
		}
		$agent_buying_fare = $cur_FareDetails['_AgentBuying'];
		if(@$discount['value_type'] == 'plus'){
			$disc_val = $discount['value'];
            $final_disc = $disc_val * $segme_cnt;
            $agent_buying_fare = $agent_buying_fare - $final_disc;
		}
		$__trip_flight_v['segdiscount']=$final_disc;
		$agent_markup = $cur_FareDetails['_Markup'];
		$agent_commission = $cur_FareDetails['_Commission'];
		$tds_oncommission = $cur_FareDetails['_tdsCommission'];
		$agent_earning = $cur_FareDetails['_AgentEarning'];
		$cus_total_tax = $cur_FareDetails['_TaxSum'];
		$cus_base_fare = $cur_FareDetails['_BaseFare'];
		$cur_Currency				= $cur_FareDetails['CurrencySymbol'];
		
		
		//VIEW START
		//SegmentIndicator used to identifies one way or return or multi stop
		$inner_summary .= '<div class="propopum" id="fdp_'.$__root_indicator.$__trip_flight_k.'">';
		$inner_summary .= '<div class="comn_close_pop closepopup">X</div>';
		$inner_summary .= '<div class="p_i_w">';
		$inner_summary .= '<div class="popuphed"><div class="hdngpops">'.$cur_Origin.' <span class="fas fa-exchange-alt"></span> '.$cur_Destination.' </div></div>';
		$inner_summary .= '<div class="popconyent">';
		$inner_summary .= '<div class="contfare">';
		
		$inner_summary .= '
			<ul role="tablist" class="nav nav-tabs flittwifil">
				<li class="active" data-role="presentation"><a data-toggle="tab" data-role="tab" href="#iti_det_'.$__root_indicator.$__trip_flight_k.'">Itinerary</a></li>
				<li data-role="presentation"><a data-toggle="tab" data-form-id="form-id-'.$__root_indicator.$__trip_flight_k.'" class="iti-fare-btn" data-role="tab" href="#fare_det_'.$__root_indicator.$__trip_flight_k.'">Fare Details</a></li>
			</ul>
		';
		
		$inner_summary .= '<div class="tab-content">';
		$inner_summary .= '<div id="fare_det_'.$__root_indicator.$__trip_flight_k.'" class="tab-pane i-i-f-s-t' . add_special_class('xs-font', '', $domestic_round_way_flight) . '">';
		$inner_summary .= $mini_loading_image;
		$inner_summary .= '<div class="i-s-s-c tabmarg"></div>';
		$inner_summary .= '</div>';
		$inner_summary .= '<div id="iti_det_'.$__root_indicator.$__trip_flight_k.'" class="tab-pane active i-i-s-t ' . add_special_class('xs-font', '', $domestic_round_way_flight) . '">';
		$inner_summary .= '<div class="tabmarg">';//summary wrapper start
		$inner_summary .= '<div class="alltwobnd">';
		$inner_summary .= '<div class="col-xs-8 nopad full_wher">';//airline summary start
		foreach ($cur_SegmentDetails as $__segment_k => $__segment_v) {
			$segment_summary = $cur_SegmentSummary[$__segment_k];
			$inner_summary .= '<div class="inboundiv seg-'.$__segment_k.'">';
				//Way Summary in one line - Start
				$inner_summary .= '<div class="hedtowr">';
				$inner_summary .= $segment_summary['OriginDetails']['CityName'].' to '.$segment_summary['DestinationDetails']['CityName'].' <strong> ('.$segment_summary['TotalDuaration'].')</strong>';
				$inner_summary .= '</div>';
			//Way Summary in one line - End
			foreach ($__segment_v as $__stop => $__segment_flight) {
				$Baggage = trim($__segment_flight['Baggage']);
				$AvailableSeats = isset($__segment_flight['AvailableSeats']) ? $__segment_flight['AvailableSeats'].' seats' : '';
				//Summary of Way - Start
				$inner_summary .= '<div class="flitone">';
					//airline
					$inner_summary .= '<div class="col-xs-3 nopad5">
										<div class="imagesmflt">
										<img  alt="'.$__segment_flight['AirlineDetails']['AirlineCode'].' icon" src="'.SYSTEM_IMAGE_DIR.'airline_logo/'.$__segment_flight['AirlineDetails']['AirlineCode'].'.gif" >
										</div>
										<div class="flitsmdets">'.$__segment_flight['AirlineDetails']['AirlineName'].'<strong>'.$__segment_flight['AirlineDetails']['AirlineCode'].' '.$__segment_flight['AirlineDetails']['FlightNumber'].'</strong></div>
										</div>';
					//Between Content -----
					//depart
					$inner_summary .= '<div class="col-xs-7 nopad5">';
					$inner_summary .= '<div class="col-xs-5 nopad5">
										<div class="dateone">'.$__segment_flight['OriginDetails']['_DateTime'].'</div>
										<div class="termnl">'.$__segment_flight['OriginDetails']['AirportName'].' ('.$__segment_flight['OriginDetails']['AirportCode'].')</div>
										</div>';
					//direction indicator
					$inner_summary .= '<div class="col-xs-2 nopad">
					'.$loc_dir_icon.'</div>';
					//arrival
					$inner_summary .= '<div class="col-xs-5 nopad5">
										<div class="dateone">'.$__segment_flight['DestinationDetails']['_DateTime'].'</div>
										<div class="termnl">'.$__segment_flight['DestinationDetails']['AirportName'].' ('.$__segment_flight['DestinationDetails']['AirportCode'].')</div>
										</div>';
					$inner_summary .= '</div>';
					//Between Content -----
					$inner_summary .= '<div class="col-xs-2 nopad5">
										<div class="ritstop">
										<div class="termnl">'.$__segment_flight['SegmentDuration'].'</div>
										<div class="termnl1">Stop : '.($__stop).'</div>';
					if(empty($Baggage) == false){
						$inner_summary .= '<div class="termnl1 flo_w"><em><i class="fas fa-suitcase bag_icon"></i>'.($Baggage).'</em></div>';
					}
					if(empty($AvailableSeats) == false){
						$inner_summary .= '<div class="termnl1 flo_w"><em><i class="air_seat timings icseats" ></i>'.$AvailableSeats.'</em></div>';
					}
					$inner_summary .= '</div>
										</div>';

				//Summary of Way - End
				$inner_summary .= '</div>';
				if (isset($__segment_v['WaitingTime']) == true) {
					$next_seg_info = $seg_v[$seg_details_k+1];
					$waiting_time = $__segment_v['WaitingTime'];
					$inner_summary .= '
				<div class="clearfix"></div>
				<div class="layoverdiv">
					<div class="centovr">
					<span class="fas fa-plane"></span>Plane change at '.$next_seg_info['OriginDetails']['CityName'].' | <span class="far fa-clock"></span> Waiting: '.$waiting_time.'
				</div></div>
				<div class="clearfix"></div>';
				}
			}
			$inner_summary .= '</div>';
		}
				$inner_summary .= '</div>';//airline summary end
				$inner_summary .= '<div class="col-xs-4 nopad full_wher">';//price summary start
				$inner_summary .= '<div class="inboundiv sidefare">';

				$inner_summary .= '<h4 class="farehdng">Total Fare Breakup</h4>';

				$inner_summary .= '<div class="inboundivinr">';
				$inner_summary .= '
						<div class="rowfare"><div class="col-xs-8 nopad">
						<span class="infolbl">Total Base Fare</span>
						</div>
						<div class="col-xs-4 nopad">
						<span class="pricelbl">'.$cur_Currency.' '.round($cus_base_fare).'</span>
						</div></div>';
				$inner_summary .= '
						<div class="rowfare"><div class="col-xs-8 nopad">
						<span class="infolbl">Taxes &amp; Fees</span>
						</div>
						<div class="col-xs-4 nopad">
						<span class="pricelbl">'.$cur_Currency.' '.round($cus_total_tax).'</span>
						</div></div>';
				$inner_summary .= '
                        <div class="rowfare"><div class="col-xs-8 nopad">
                        <span class="infolbl">Discount(-)</span>
                        </div>
                        <div class="col-xs-4 nopad">
                        <span class="pricelbl"><span class="display_currency">' . $cur_Currency . '</span> <span class="tax_price"> ' . round($final_disc) . '</span></span>
                        </div></div>';
				$inner_summary .= '
						<div class="rowfare grandtl"><div class="col-xs-8 nopad">
						<span class="infolbl">Grand Total</span>
						</div>
						<div class="col-xs-4 nopad">
						<span class="pricelbl">'.$cur_Currency.' '.round($cus_total_fare).'</span>
						</div></div>';
				$inner_summary .= '</div>';
				$inner_summary .= '</div>';

				$inner_summary .= '</div>';//price summary end
			$inner_summary .= '</div>';//summary wrapper end
		$inner_summary .= '</div>';
		$inner_summary .= '</div>';
		$inner_summary .= '</div>';//tab-content

		$inner_summary .= '</div>';//contfare
		$inner_summary .= '</div>';//popconyent

		$inner_summary .= '<div class="popfooter"><div class="futrcnt"><button class="norpopbtn closepopup">Close</button>  </div></div>';
		$inner_summary .= '</div>';//inned wrap
		$inner_summary .= '</div>';//propopum

		//Outer Summary - START
		//$outer_summary .= '<div class="madgrid ' . add_special_class('', '', $domestic_round_way_flight) . '">';
		$outer_summary .= '<div class="madgrid" data-key="'.$__root_indicator.$__trip_flight_k.'">
		<div class="onlmob"><form method="POST" id="form-id-'.$__root_indicator.$__trip_flight_k.'" action="'.$booking_url.'" class="book-form-wrapper">
									'.$GLOBALS['CI']->flight_lib->booking_form($IsDomestic, $__trip_flight_v['Token'], $__trip_flight_v['TokenKey'], $cur_ProvabAuthKey,'',$booking_source).'
									'.$booking_button.'
								</form></div>';
		$outer_summary .= '<div class="f-s-d-w col-xs-8 nopad wayeght full_same">';
			$total_stop_count = 0;
			foreach ($cur_SegmentSummary as $__segment_k => $__segment_v) {
				$total_segment_travel_duration = $__segment_v['TotalDuaration'];
                                  $dur = $total_segment_travel_duration;

            $dur = explode(' ', $dur);
            $count = count($dur);
//print_r($count);

            $check = (strrpos($dur[0], 'm')) ? $dur[0] * 1 : $dur[0] * 60;

            $h = str_replace('h', '', $dur[0]);
            if (!empty($dur[1])) {
               
                $m = $dur[1] * 1;
                $h = $dur[0] * 60;
                $d = $h + $m;
            } else {
                $d = $check;
            }
            
            $duration = $d;
				$__stop_count = $__segment_v['TotalStops'];
				$total_stop_count	+= $__stop_count;
				$stop_image ='';
				for ($image_name=0; $image_name <5 ; $image_name++) { 
					if($__stop_count==$image_name){
						$stop_image =$GLOBALS['CI']->template->template_images('stop_'.$image_name.'.png');
					}
			
				}
				if($__stop_count>4){
					$stop_image =$GLOBALS['CI']->template->template_images('more_stop.png');
				}

				$outer_summary .= '<div class="allsegments outer-segment-'.$__segment_k.'">';
					//airline
					$outer_summary .= '<div class="quarter_wdth nopad ' . add_special_class('col-xs-3', 'col-xs-3', $domestic_round_way_flight) . '">
										<div class="fligthsmll"><img class="airline-logo" alt="'.$__segment_v['AirlineDetails']['AirlineCode'].' icon" src="'.SYSTEM_IMAGE_DIR.'airline_logo/'.$__segment_v['AirlineDetails']['AirlineCode'].'.gif"></div>
										<div class="m-b-0 text-center">
											<div class="a-n airlinename" data-code="'.$__segment_v['AirlineDetails']['AirlineCode'].'">
												'.$__segment_v['AirlineDetails']['AirlineName'].'
											</div>
											<strong> '.$__segment_v['AirlineDetails']['AirlineCode'].' '.$__segment_v['AirlineDetails']['FlightNumber'].'</strong>
										</div>
									  </div>';
					//depart
					$outer_summary .= '<div class="col-xs-3 nopad quarter_wdth">
											<div class="insidesame">
												<span class="fdtv hide">'.date('Hi', strtotime($__segment_v['OriginDetails']['DateTime'])).'</span>
												<div class="f-d-t bigtimef">'.$__segment_v['OriginDetails']['_DateTime'].'</div>
												<div class="from-loc smalairport_code">'.$__segment_v['OriginDetails']['AirportCode'].'</div>
												<div class="from-loc smalairport">'.$__segment_v['OriginDetails']['CityName'].' ('.$__segment_v['OriginDetails']['AirportCode'].')</div>
												<span class="dep_dt hide" data-category="'.time_filter_category($__segment_v['OriginDetails']['DateTime']).'" data-datetime="'.(number_format((strtotime($__segment_v['OriginDetails']['DateTime'])*1000), 0, null, '')).'"></span>
											</div>
										</div>';
					//direction indicator
					//$outer_summary .= '<div class="clearfix visible-sm-block"></div>';
					$outer_summary .= '<div class="col-md-1 p-tb-10 hide">'.$loc_dir_icon.'</div>';
					if($__stop_count >0){$stopcount_calss="stop-value";}else{$stopcount_calss="stop_zero";}
					//arrival
							$outer_summary .= '<div class="smal_udayp nopad ' . add_special_class('col-xs-3', 'col-xs-3', $domestic_round_way_flight) . '"><span class="f-d hide">'.$duration.'</span>
											<div class="insidesame">
												<div class="durtntime">'.($total_segment_travel_duration).'</div>
												<div class="stop_image"><img src='.$stop_image.' alt="stop_0"></div>
												<div class="'.$stopcount_calss.'">Stop:'.($__stop_count).'</div>'.$stop_air1.
												'<div class="cabinclass hide">'.($cabin_class).'</div>
											</div>
								</div>';

					$outer_summary .= '<div class="col-xs-3 nopad quarter_wdth">
											<div class="insidesame">
												<span class="fatv hide">'.date('Hi', strtotime($__segment_v['DestinationDetails']['DateTime'])).'</span>
												<div class="f-a-t bigtimef">'.$__segment_v['DestinationDetails']['_DateTime'].'</div>
												<div class="to-loc smalairport">'.$__segment_v['DestinationDetails']['CityName'].' ('.$__segment_v['DestinationDetails']['AirportCode'].')</div>
												<div class="smalairport_code">'.$__segment_v['DestinationDetails']['AirportCode'].'</div>
												<span class="arr_dt hide" data-category="'.time_filter_category($__segment_v['DestinationDetails']['DateTime']).'" data-datetime="'.(number_format((strtotime($__segment_v['DestinationDetails']['DateTime'])*1000), 0, null, '')).'"></span>
											</div>
										</div>';
					//$outer_summary .= '<div class="clearfix visible-sm-block"></div>';
			
					//$outer_summary .= '<div class="clearfix visible-sm-block"></div>';
				$outer_summary .= '</div>';
			}
			if($cur_IsRefundable==0){$ref_code= "nre";}else{$ref_code= "re";}
		$outer_summary .= '</div>';
		$outer_summary .= '
					<div class="col-xs-4 nopad wayfour full_same">
						<span class="hide stp" data-stp="'.$total_stop_count.'" data-category="'.stop_filter_category($total_stop_count).'"></span>
						<div class="priceanbook">
							<div class="col-xs-6 nopad wayprice">
								<div class="insidesame">
									<div class="priceflights"><strong> '.$cur_Currency.' </strong><span class="f-p">'.round($cus_total_fare).'</span></div>
									<div style="display:none" class="snf_hnf net-fare-tag" title="C '.($agent_commission-$tds_oncommission).'+M '.$agent_markup.' = '.$agent_earning.'"> '.$cur_Currency.' '.round($agent_buying_fare).' </div>
									<span class="hide price" data-price="'.$cus_total_fare.'" data-currency="'.$cur_Currency.'"></span>
									<div data-val="'.intval($cur_IsRefundable).'" class="n-r n-r-t" data-code="' .$ref_code. '">'.$Refundable_lab.'</div>
								</div>
							</div>
							<div class="col-xs-6 nopad waybook">
								<div class="form-wrapper bookbtlfrt">
								<form method="POST" id="form-id-'.$__root_indicator.$__trip_flight_k.'" action="'.$booking_url.'" class="book-form-wrapper">
									'.$GLOBALS['CI']->flight_lib->booking_form($IsDomestic, $__trip_flight_v['Token'], $__trip_flight_v['TokenKey'], $cur_ProvabAuthKey,'',$booking_source).'
									'.$booking_button.'
								</form>

								</div>
							</div>
						</div>
					</div>';
		$outer_summary .= '<div class="clearfix"></div>';




		//Load Flight Details Button
		$bg_class = '';
         if($booking_source==PROVAB_AEROCRS_BOOKING_SOURCE){
            $bg_class = 'mrinfrmtnflightcrs';
        }else if($booking_source==AMADEUS_FLIGHT_BOOKING_SOURCE){
            $bg_class = 'mrinamadeus';
        }        //debug();exit;
        $outer_summary .= '<div class="mrinfrmtn '.$bg_class.'">
									 <a class="detailsflt iti-btn" data-toggle="modal" data-target="#sendmail_' . $__root_indicator . $__trip_flight_k . '"" data-backdrop="static"><span class="fal fa-envelope"></span>Send Mail</a>
									<div id="sendmail_' . $__root_indicator . $__trip_flight_k . '" class="modal fade" role="dialog" data-id="'.$__root_indicator . $__trip_flight_k.'">
									  <div class="modal-dialog" style="margin:200px auto">

										<!-- Modal content-->
										<div class="modal-content">
										  <div class="modal-header">
											<button type="button" class="close" id="close_modal'.$__root_indicator . $__trip_flight_k.'">&times;</button>
											<h4 class="modal-title mdltitl">Send Flight Information</h4>
										  </div>
										  <div class="modal-body">
										   <form action="">
												<div class="form-group">
												 <input type="email" class="form-control mfc" id="email_' . $__root_indicator . $__trip_flight_k . '" placeholder="Enter Email ID" name="email">
                                                 <input type="hidden" class="form-control mfc" id="flightdetails_' . $__root_indicator . $__trip_flight_k . '" placeholder="Enter email" name="email" value="'.htmlspecialchars(json_encode($__trip_flight_v)).'">
                                                 <input type="hidden" id="pkey_' . $__root_indicator . $__trip_flight_k . '" value="'.$cur_ProvabAuthKey.'">
                                                 <input type="hidden" id="bsource_' . $__root_indicator . $__trip_flight_k . '" value="'.$booking_source.'">
                                                 <span id="errormsg_' . $__root_indicator . $__trip_flight_k . '"></span>
                                                 <div id="send_email_loading_image_' . $__root_indicator . $__trip_flight_k . '" style="display:none;">
                                                 <div class="text-center loader-image" style="display: none;"><img src="'.$GLOBALS['CI']->template->template_images('loader_v3.gif').'" alt="please wait"></div></div>
												</div>
												<button type="button" id="send_email_btn_not' . $__root_indicator . $__trip_flight_k . '" class="btn btn-default flteml" style="background:#1fb53a;border-radius:8px!important;border:1px solid #1fb53a!important;" onclick="sendflightdetails(' . $__root_indicator . $__trip_flight_k . ')">Send</button>
										  </form>
										  </div>

										</div>

									  </div>
									</div>
									<a class="detailsflt iti-btn" data-id="fdp_'.$__root_indicator.$__trip_flight_k.'"><span class="fal fa-info-circle fldetail"></span>Flight Details '.$remark_separator.'</a>
									<i class="f-t" data-code=' . $cur_AirlineRemark . '>'.$cur_AirlineRemark.'</i>
							</div>';
		//Outer Summary - END
		$outer_summary .= '</div>';

		$flights .= '<div class="rowresult p-0 r-r-i t-w-i-'.$__root_indicator.'">
						'.$outer_summary.'
						'.$inner_summary.'
					</div>';
	}
	if(($trip_type == 'circle') && ($IsDomestic == true)){
		$flights .= '</div>';
	}
}
if(($trip_type == 'circle') && ($IsDomestic == true)){
	$flights .= '</div>';
}
echo $flights;

/**
 * Return class based on type of page
 */
function add_special_class($col_2x_class, $col_1x_class, $domestic_round_way_flight)
{
	if ($domestic_round_way_flight) {
		return $col_2x_class;
	} else {
		return $col_1x_class;
	}
}

function time_filter_category($time_value)
{
	$category = 1;
	$time_offset = intval(date('H', strtotime($time_value)));
	if ($time_offset < 6) {
		$category = 1;
	} elseif ($time_offset < 12) {
		$category = 2;
	} elseif ($time_offset < 18) {
		$category = 3;
	} else {
		$category = 4;
	}
	return $category;
}
/**
 * Generate Category For Stop
 */
function stop_filter_category($stop_count)
{
	$category = 1;
	switch (intval($stop_count)) {
		case 0 : $category = 1;
		break;
		case 1 : $category = 2;
		break;
		default : $category = 3;
		break;
	}
	return $category;
}

?>
<script type="text/javascript">

$(document).keypress(function(e) {
  if ($(".modal").hasClass('in') && (e.keycode == 13 || e.which == 13)) {

    /*if ('div.modal.hide.in').is(":visible"){*/

        currentId = $('.modal.fade.in').attr('id');
        
         var res=currentId.split("_");
       var dd=res[1];
       // alert(dd);
       sendflightdetails(dd);
    /*}*/
    
  }
});
$(".close").on('click',function(){
     currentId = $('.modal.fade.in').attr('id');
     // alert(currentId);
     $("#"+currentId).modal('hide');
     
})
</script>