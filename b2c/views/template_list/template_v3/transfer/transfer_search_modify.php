<?php 
//error_reporting(E_ALL);
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/transfer_suggest_modify_search.js'), 'defer' => 'defer');

// Read cookie when user has not given any search
if ((isset ( $flight_search_params ) == false) || (isset ( $flight_search_params ) == true && valid_array ( $flight_search_params ) == false)) {
	// parse_str(get_cookie('flight_search'), $flight_search_params);
	$sparam = $this->input->cookie ( 'sparam', TRUE );
	$sparam = unserialize ( $sparam );
	$sid = intval ( @$sparam [META_TRANSFERS_COURSE] );
	$flight_search_params = array ();
	if ($sid > 0) {
		$GLOBALS['CI']->load->model('transfer_model');
		$transfer_search_params = $GLOBALS['CI']->transfer_model->get_safe_search_data($sid, true);
		$transfer_search_params = $transfer_search_params['data'];
	    //$transfer_search_params
		if (isset($transfer_search_params) && valid_array($transfer_search_params) == true) {
			if (isset($transfer_search_params['from_date']))
				$transfer_search_params['depature'] = date('d-m-Y H:i', strtotime($transfer_search_params['from_date']));
			if(isset($transfer_search_params['to_date']))
	        	$transfer_search_params['return'] = date('d-m-Y H:i', strtotime($transfer_search_params['to_date']));	
		}
	}
}
$GLOBALS['CI']->load->model('visa_model');
$visa_country_data["country"] = $GLOBALS['CI']->visa_model->get_countries();
// debug($transfer_search_params);exit;
$scountry = $transfer_search_params['native_country'];
if(!isset($transfer_search_params['adult']) && empty($transfer_search_params['adult'])) {
	$transfer_search_params['adult'] = 1;
}
// $transfer_datepicker = array(array('transfer_datepicker1', FUTURE_DATE_TIME), array('transfer_datepicker2', FUTURE_DATE_TIME));
// $GLOBALS['CI']->current_page->set_datepicker($transfer_datepicker);
// $GLOBALS['CI']->current_page->auto_adjust_datetimepicker(array(array('transfer_datepicker1', 'transfer_datepicker2')));


// // debug(FUTURE_DATE_TIME);exit();
// $transfer_datepicker = array(array('transfer_datepicker1', FUTURE_DATE_DISABLED_MONTH), array('transfer_datepicker2', FUTURE_DATE_DISABLED_MONTH));
// // debug($transfer_datepicker);exit();
// $GLOBALS['CI']->current_page->set_datepicker($transfer_datepicker);
// $GLOBALS['CI']->current_page->auto_adjust_datetimepicker(array(array('transfer_datepicker1', 'transfer_datepicker2')));


$strAdultCnt = empty($transfer_search_params['adult'])?"1":$transfer_search_params['adult'];
$strChildCnt = empty($transfer_search_params['child'])?"0":$transfer_search_params['child'];
$strPaxCnts  = $strAdultCnt+$strChildCnt;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['CI']->template->template_system_library('datetimepicker/jquery.datetimepicker.css');?>">
<style>
	.inpu{padding:0px 0px 0px 5px!important;}
	.ghy{padding:0px 10px 10px 10px;}
    .dfg{padding:5px 10px 5px 8px;}
	.moditran{background:rgba(45, 62, 82, 0.5);padding:10px 5px!important;}
	
</style>

<form autocomplete="off" name="trasfer" id="trasfer" action="<?=base_url()?>index.php/general/pre_transfer_search" method="get" class="activeForm" style="">
	<div class="tabspl moditran">
		<div class="tabrow">
			<div class="waywy">
				<div class="smalway">
					<label class="wament hand-cursor" style="display: none;"> 
						<input class="" type="radio" name="transfer_type" <?=(empty($transfer_search_params['trip_type']) ? 'checked' : ($transfer_search_params['trip_type']) == 'oneway' ? 'checked="checked"' : '')?> id="oneway-trp" value="oneway" /> One Way
					</label>
					<!-- <label class="wament hand-cursor"> 
						<input class="" type="radio" name="transfer_type" <?=(@$transfer_search_params['trip_type'] == 'circle' ? 'checked="checked"' : '')?> id="rond-trp" value="circle" /> Round Trip
					</label> -->					
				</div>
			</div>
			<div id="onw_rndw_fieldset" class="col-md-8 col-xs-12 nopad">
				<!-- Oneway/Roundway Fileds Starts-->
			<div class="col-md-3 col-sm-10 col-xs-10 padfive full_smal_tab">
			<div class="lablform">Nationality</div>
				<div class="selectedwrap plcemark nationlty sidebord">    
	            <select class="normalsel holyday_selct" id="native_country" name="native_country" onchange="get_visa_country(visa_country.value,this.value);" required="required">
							<option value="">Select Nationality</option>
							<?php if(!empty($visa_country_data['country'])){?>
							<?php foreach ($visa_country_data['country'] as $country) { ?>
							<option value="<?php echo $country->country_list; ?>"
								<?php if(isset($scountry)){ if($scountry == $country->country_list) echo "selected"; }?>><?php echo $country->nationality; ?>
							</option>
							<?php } } ?>
						</select>
          </div>
			</div>
				<div class="col-md-6 col-xs-7 nopad placerows">
					<!-- <div class="col-xs-6 padfive">
						<div class="lablform">From</div>
						<div class="plcetogo plcemark deprtures sidebord">
							<input type="text" autocomplete="off" name="transfer_from" class="normalinput auto-focus hotelin valid_class form-control b-r-0 airport_location" id="transfer_from" placeholder="From, Airport, Hotel, City" value="<?php echo @$transfer_search_params['from'] ?>" required /> 
							<input class="hide loc_id_holder" name="from_loc_id" type="hidden" value="<?php echo @$transfer_search_params['from_code']; ?>">
							<input class="hide transfer_type" name="from_transfer_type" type="hidden" value="<?=@$transfer_search_params['from_transfer_type']?>" >
						</div>
					</div>
					<div class="col-xs-6 padfive">
						<div class="lablform">To</div>
						<div class="plcetogo plcemark destinatios sidebord">
							<input type="text" autocomplete="off" name="transfer_to" class="normalinput hotelin auto-focus valid_class  form-control b-r-0 airport_location" id="transfer_to" placeholder="To, Airport, Hotel, City" value="<?php echo @$transfer_search_params['to'] ?>" required /> 
							<input class="hide loc_id_holder" name="to_loc_id" type="hidden" value="<?=@$transfer_search_params['to_code']?>" >
                    		<input class="hide transfer_type" name="to_transfer_type" type="hidden" value="<?=@$transfer_search_params['to_transfer_type']?>"> 
						</div>
					</div> -->
					<div class="col-xs-6 padfive">
						<div class="lablform">From</div>
						<div class="plcetogo plcemark sidebord">
							<input type="text" autocomplete="off" name="transfer_from" class="normalinput auto-focus hotelin valid_class fromtransfer form-control b-r-0" id="transfer_from" placeholder="From, Airport, Hotel, City" value="<?php echo @$transfer_search_params['from'] ?>" required /> 
							<input class="hide loc_id_holder" name="from_loc_id" type="hidden" value="<?php echo @$transfer_search_params['from_code']; ?>">
							<input class="hide transfer_type" name="from_transfer_type" type="hidden" value="<?=@$transfer_search_params['from_transfer_type']?>" >
						</div>
					</div>
					<div class="col-xs-6 padfive">
						<div class="lablform">To</div>
						<div class="plcetogo plcemark sidebord">
							<input type="text" autocomplete="off" name="transfer_to" class="normalinput hotelin auto-focus valid_class departtransfer form-control b-r-0" id="transfer_to" placeholder="To, Airport, Hotel, City" value="<?php echo @$transfer_search_params['to'] ?>" required /> 
							<input class="hide loc_id_holder" name="to_loc_id" type="hidden" value="<?=@$transfer_search_params['to_code']?>" >
                    		<input class="hide transfer_type" name="to_transfer_type" type="hidden" value="<?=@$transfer_search_params['to_transfer_type']?>"> 
						</div>
					</div>
				</div>
				<div class="col-md-3 col-xs-5 secndates padfive full_smal_tab">
					<!-- <div class="col-xs-6 padfive"> -->
						<div class="lablform">Departure</div>
						<div class="plcetogo datemark sidebord dep">
							<input type="text" readonly class="normalinput auto-focus hand-cursor form-control b-r-0 date_picker" id="transfer_datepicker1" placeholder="Departure Date" value="<?php echo @$transfer_search_params['from_date'] ?>" name="depature" required/>
						</div>
					<!-- </div> -->
					<!-- <div class="col-xs-6 padfive date-wrapper" id='preturnx'>
						<div class="lablform">Return</div>
						<div class="plcetogo datemark sidebord ret">
							<input type="text" readonly class="normalinput auto-focus hand-cursor form-control b-r-0 date_picker" id="transfer_datepicker2" name="return" placeholder="Return Date" value="<?php echo @$transfer_search_params['to_date'] ?>" <?=(@$transfer_search_params['transfer_type'] != 'circle' ? 'disabled="disabled"' : '')?> />
						</div>
					</div> -->
				</div>
			</div>
			<!-- Oneway/Roundway Fileds Ends-->
			<div class="col-md-4 col-xs-12 nopad srch">
				<div class="col-md-6 col-xs-12 padfive pasngr1">
					<div class="lablform">Traveller Details</div>
					<div class="totlall pas_icon">
						<span class="remngwd ">
							<span class="total_pax_count"><?php echo $strPaxCnts; ?></span>
							<span id='pax_pinfo'> Passenger</span>
						</span>
						<div class="roomcount pax_count_div">
							<div class="inallsn">
								<div class="oneroom fltravlr trans_traveler">
									<div class="roomrow transfer_row">
										<div class="celroe col-xs-4"> Adult</div>
										<div class="celroe col-xs-8">
											<div class="input-group countmore pax-count-wrapper adult_count_div">
												<span class="input-group-btn">
													<button data-field="adult" data-type="minus"  class="btn btn-default btn-number btnpot minusValue_t adult" data-id="OWT_transfer_adult" onclick="total_count('trasfer','minus','adult');">  <span class="fa fa-minus" ></span> </button>
												</span>
												<input type="text" id="OWT_transfer_adult" name="adult" required="" class="form-control input-number centertext valid_class pax_count_value" value="<?php echo $strAdultCnt; ?>" min="1" max="6" readonly>
	                                            <span class="input-group-btn">
													<button data-field="adult" data-type="plus" class="btn btn-default btn-number btnpot btn_right plusValue_t" data-id="OWT_transfer_adult" onclick="total_count('trasfer','plus','adult');"> <span class="fa fa-plus"></span> </button>
												</span>	
											</div>
										</div>
									</div>
									<div class="roomrow transfer_row">
										<div class="celroe col-xs-4"> Child <span class="agemns">(2-11)</span> </div>
										<div class="celroe col-xs-8">
											<div class="input-group countmore pax-count-wrapper child_count_div">
												<span class="input-group-btn">
													<button type="button" class="btn btn-default btn-number btnpot minusValue_t child" data-type="minus" data-field="child" data-id="OWT_transfer_child" onclick="total_count('trasfer','minus','child');"> <span class="fa fa-minus"></span> </button>
												</span>	
												<input type="text" id="OWT_transfer_child" name="child" class="form-control input-number centertext pax_count_value" value="<?php echo $strChildCnt; ?>" min="0" max="6" readonly>
												<span class="input-group-btn">	
													<button type="button" class="btn btn-default btn-number btnpot btn_right plusValue_t" data-field="child" data-type="plus" data-id="OWT_transfer_child" onclick="total_count('trasfer','plus','child');"> <span class="fa fa-plus"></span> </button>
												</span>	
											</div>
										</div>

									</div>
									<div class="col-xs-6 nopad padfive hide">
										<div class="formlabel padfive_adult" style='color:#333;'>Adult age at time of travel</div>
											<?php 
												for($d = 1;$d<=6;$d++) {
													$classname = 'hide';
													$disable = 'disabled=""';
													if($d == 1 || isset($transfer_search_params['adult_ages'][$d-1])) {
														$classname='';
														$disable = '';
													}
											?>
											<div class="col-xs-6 fiveh padfive adult-ages">
												<div class="selectedwrap transfer_adult_ageId<?=$d?> <?=$classname?>" >
													<select class="flyinputsnor marginbotom10 selectpicker <?=$classname?>" <?=$disable?> id="transfer_adult_ageId<?=$d?>" name="adult_ages[]">
														<?php 
															for ($i=12; $i<=100; $i++) {
																if(isset($transfer_search_params['adult_ages'][$d-1]) && $transfer_search_params['adult_ages'][$d-1] == $i) {
																	$selected = "selected";
																}else {
																	$selected = '';
																}
														?>
														<option <?=$selected?>><?=$i?></option>
														<?php } ?>
													</select>
												</div>
											</div>
										<?php } ?>
									</div>

										<?php 
											if(false){
												$child_age_label_clss = '';
												if(isset($transfer_search_params['child']) && $transfer_search_params['child'] == 0) {
													$child_age_label_clss = 'hide';
												}
											}
											$child_age_label_clss = 'hide';
											if($strChildCnt > 0){
												$child_age_label_clss = '';
											}
										?>
									<div class="col-xs-6 nopad pull-right padfive">
										<div class="formlabel padfive_child <?php echo $child_age_label_clss; ?>" style='color:#333;'>Child age at time of travel</div>
										<?php 	
											for($c = 1;$c<=6;$c++) {
												if(isset($transfer_search_params['child_ages'][$c-1])) {
													$classname='';
													$disable = '';
												}else {
													$disable = 'disabled=""';
													$classname = "hide";
												}
										?>
										<div class="col-xs-6 fiveh padfive child-ages">
											<div class="selectedwrap transfer_child_ageId<?=$c?> <?=$classname?>">
												<select class="flyinputsnor marginbotom10 selectpicker <?=$classname?>" <?=$disable?> id="transfer_child_ageId<?=$c?>" name="child_ages[]">
													<?php 
														for ($j=2; $j<=11; $j++) {
															if(isset($transfer_search_params['child_ages'][$c-1]) && $transfer_search_params['child_ages'][$c-1] == $j) {
																$selected = "selected";
															}else {
																$selected = '';
															}
													?>
													<option <?=$selected?>><?=$j?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<?php } ?>
									</div> 


									<!-- Infant Error Message-->
									<div class="roomrow">
										<div class="celroe col-xs-8">
											<a class="done1 comnbtn_room">
												<span class="fa fa-check"></span>Done
											</a>
										</div>
									</div>
									<!-- Infant Error Message-->
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--<div class="col-xs-4 padfive">
					<?php
					$percntg = '';
					$plus = '';
					if($transfer_search_params['markup_type']=='percentage'){
						$percntg = 'selected';
						$plus = '';
						}
					if($transfer_search_params['markup_type']=='plus'){
						$percntg = '';
						$plus = 'selected';
						}
						?>
            <div class="col-md-7 col-sm-9 col-xs-9 mobile_width padfive">
					<div class="lablform">Markup Type</div>
				 
					<div class="plcetogo selctmark sidebord">
						<select class=" inpu normalsel arimo" id="markup_type" name="markup_type" onchange="check_markup();">
							 <option value="">Select Type</option>
							 <option value="percentage" <?=$percntg?> >%</option>
							<option value="plus" <?=$plus?>>Value</option>
						</select>
					</div>

				</div>

				<div class="col-md-5 col-sm-3 col-xs-3 mobile_width padfive">
					<div class="lablform">Value</div>
					<div class=""> 
						<input type="text" id="markup_value" name="markup_value" maxlength="5" class="normalinput inpu form-control numeric" value="<?=$transfer_search_params['markup_value']?>" onpaste="return false;" />
					</div>
				</div>

          
         </div>-->
				<div class="col-xs-5 padfive">
            <span class="formlabel">&nbsp;</span>
            <div class="formsubmit">
               <input type="submit" name="search_transfer" id="flight-form-submit" class="searchsbmt transfer_search_btn" value="Search" />
            </div>
		  </div>
				<!-- <div class="col-xs-6 padfive trnsfr_srch">
					<div class="lablform">&nbsp;</div>
					<div class="searchsbmtfot">
						<input type="submit" name="search_transfer" id="flight-form-submit" class="searchsbmt transfer_search_btn" value="Search" />
					</div>
				</div> -->
			</div>
		</div>
	</div>
</form>
<?php
//Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/transfer_suggest_modify_search.js'), 'defer' => 'defer');
?> 
<script defer="" src="<?php echo $GLOBALS['CI']->template->template_system_library('datetimepicker/jquery.datetimepicker.js');?>"></script>
<script type="text/javascript">
	// $(function(){
	// 		$('#transfer_datepicker1').datetimepicker({
	// 		    format: 'H:m:s'
	// 		});

	// 	});
	$(document).ready(function() {
futureDateTimepicker("transfer_datepicker1");
futureDateTimepicker("transfer_datepicker2");
function caradultDatePicker(elementId)
{

$('#'+elementId).datepicker({
yearRange:"-100:-24",
changeYear:true,
changeMonth:true,
numberOfMonths:1,
dateFormat:'dd-mm-yy',
// minDate: '-100Y',
// maxDate: '-23Y',
});
}


});
	$("#transfer_datepicker1").click(function() { 
					$("#ui-datepicker-div").css('display','none');
				});
   function total_count(form_id,type,pass_type)
 {
 		
    if (form_id != '') {
        var pax_count = $('input.pax_count_value', 'form#' + form_id + ' div.pax_count_div').map(function() {
        	if(this.value != '') {
        		return parseInt(this.value)
        	}
        }).get();
        var total_pax_count = 0;
        $.each(pax_count, function() {
            total_pax_count += this
        });
        if(type=='plus')
            {
             total_pax_count +=1; 
             var min_val = $('#OWT_transfer_adult').val();
            if((min_val==6) &&(pass_type=='adult')){
        			return false;
        	}
        	var min_val_child = $('#OWT_transfer_child').val();
        	if((min_val_child==6) &&(pass_type=='child')){
        			return false;
        	}  
            }else if(type=='minus')
            {
            var min_val = $('#OWT_transfer_adult').val();
            if((min_val==1) &&(pass_type=='adult')){
        			return false;
        	}
        	var min_val_child = $('#OWT_transfer_child').val();
        	if((min_val_child==0) &&(pass_type=='child')){
        			return false;
        	}
             total_pax_count =total_pax_count-1;
            }
        if(total_pax_count > 1){
            $('#travel_text').text('Travellers');
        }
        else{
              $('#travel_text').text('Traveller');
        }
       
        $('.total_pax_count', 'form#' + form_id).empty().text(total_pax_count)
    }
    // var a = $('.total_pax_count').val();
 	// alert(pax_count)
 }
</script>