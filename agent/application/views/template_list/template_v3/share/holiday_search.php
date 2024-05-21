<!-- holiday packages -->
<form action="<?php echo base_url().'index.php/general/pre_holiday_search'?>"
	autocomplete="off" id="holiday_search">
	<div class="tabspl forhotelonly">
		<div class="tabrow">
			<div class="whitebgrad ">
			     <div class="col-md-2 col-xs-12 padfive">
                    <span class="formlabel">Nationality</span>
                    <div class="selectedwrap nationlty sidebord">
                        <select  oninvalid="this.setCustomValidity('Please select Nationality')"
 oninput="setCustomValidity('')"   class="normalsel normalinput holyday_selct"  name="nationality"
                            required>
                           <option value="" selected>Select</option>
                            <option value="NP">Nepalese</option>
                            <?php if(!empty($holiday_data['country'])){?>
                            <?php 
															 foreach ($holiday_data['country'] as $country) { 
				 if($country['isocode']!="")
                                {
				   ?>
                            <option value="<?php echo $country['isocode']; ?>" ><?php echo $country['name']; ?>
                            </option>
                            <?php 
                                }
                            } } ?>
                        </select>
                    </div>
                </div>
			<div class="col-md-3 col-sm-3 col-xs-6 padfive full_smal_tab">
				<div class="formlabel">Destination</div>
				   <div class="plcetogo plcemark sidebord">
					<!-- <select class="normalsel holyday_selct brleft30" id="country" name="country">
						<option value="">All Destination</option>
						<?php if(!empty($holiday_data['countries'])){?>
						<?php foreach ($holiday_data['countries'] as $country) { ?>
						<option value="<?php echo $country->country_name; ?>"
							<?php if(isset($scountry)){ if($scountry == $country->country_id) echo "selected"; }?>><?php echo $country->country_name; ?>
						</option>
						<?php } } ?>
					</select> -->
					<input type="text" id="country" class="fromholiday1 normalinput form-control b-r-0 brleft30" placeholder="All Destination" name="city" value="<?php echo @$scountry;?>"/>

		            <input type="hidden" name="" id="name-search">

		            <input class="hide loc_id_holder" name="destination_id" type="hidden" id="destination_id" value="<?=@$scountry?>" >
				</div>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-6 padfive full_smal_tab">
				<div class="formlabel">Package Type</div>
				<div class="selectedwrap sidebord">
					<select class="normalsel holyday_selct" id="package_type"
						name="package_type">
						<option value="">All Package Types</option>
						<?php if(!empty($holiday_data['package_typestour'])){ ?>
						<?php foreach ($holiday_data['package_typestour'] as $package_type) { ?>
						<option value="<?php echo $package_type->id; ?>"
							<?php if(isset($spackage_type)){ if($spackage_type == $package_type->id) echo "selected"; } ?>><?php echo $package_type->tour_subtheme; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-4 padfive full_smal_tab">
				<div class="formlabel">Duration</div>
				<div class="selectedwrap sidebord">
					<select class="normalsel holyday_selct" id="duration"
						name="duration">
						<option value="">All Durations</option>
						<option value="1-3"
							<?php if(isset($sduration)){ if($sduration == '1-3') echo "selected"; } ?>>1-3</option>
						<option value="4-7"
							<?php if(isset($sduration)){ if($sduration == '4-7') echo "selected"; } ?>>4-7</option>
						<option value="8-12"
							<?php if(isset($sduration)){ if($sduration == '8-12') echo "selected"; } ?>>8-12</option>
						<option value="12"
							<?php if(isset($sduration)){ if($sduration == '12') echo "selected"; } ?>>12+</option>
					</select>
				</div>
			</div>
			<!-- <div class="col-md-2 col-sm-2 col-xs-4 padfive full_smal_tab">
				<div class="lablform">Budget</div>
				<div class="selectedwrap sidebord">
					<select class="normalsel holyday_selct" id="budget" name="budget">
						<option value="">All</option>
						<option value="100-500"
							<?php if(isset($sbudget)){ if($sbudget == '100-500') echo "selected"; } ?>><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> <?php echo get_converted_currency_value ( $currency_obj->force_currency_conversion ( '100' ) );?>-<?php echo get_converted_currency_value ( $currency_obj->force_currency_conversion ( '500' ) );?></option>
						<option value="500-1000"
							<?php if(isset($sbudget)){ if($sbudget == '500-1000') echo "selected"; } ?>><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> <?php echo get_converted_currency_value ( $currency_obj->force_currency_conversion ( '500' ) );?>-<?php echo get_converted_currency_value ( $currency_obj->force_currency_conversion ( '1000' ) );?></option>
						<option value="1000-5000"
							<?php if(isset($sbudget)){ if($sbudget == '1000-5000') echo "selected"; } ?>><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> <?php echo get_converted_currency_value ( $currency_obj->force_currency_conversion ( '1000' ) );?>-<?php echo get_converted_currency_value ( $currency_obj->force_currency_conversion ( '5000' ) );?></option>
						<option value="5000"
							<?php if(isset($sbudget)){ if($sbudget == '5000') echo "selected"; } ?>><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> <?php echo get_converted_currency_value ( $currency_obj->force_currency_conversion ( '5000' ) );?> <?php echo '> '?></option>
					</select>
				</div>
			</div> -->
			<div class="col-md-2 col-sm-2 col-xs-4 padfive full_smal_tab">
				<div class="formlabel">&nbsp;</div>
				<div class="searchsbmtfot">
					<input type="submit" class="searchsbmt" value="search" />
				</div>
			</div>
		</div>
	</div>
	  <div class="home_holidays">
            <ul class="radio_set ">
                <li class="radio">
                    <input id="international" name="radio" type="radio" value="international" >
                    <label for="international" class="radio-label">International</label>
                </li>

                <li class="radio">
                    <input id="residential" name="radio" type="radio" value="residential" >
                    <label for="residential" class="radio-label">Residential</label>
                </li>
            </ul>
        </div>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function(){
		$(".fromholiday1").catcomplete({

        source: function(request, response) {
            var term = request.term;
            //console.log(cache);
            // if (term in cache) {
            //     response(cache[term]);
            //     return
            // }
            $.getJSON(app_base_url + "index.php/ajax/get_holiday_city_list", request, function(data, status, xhr) {
                //cache[term] = data;
                response(data)
            })
        },
        minLength: 3,
        autoFocus: true,
        select: function(event, ui) {
            var label = ui.item.label;
            var category = ui.item.category;
            $(this).siblings('.loc_id_holder').val(ui.item.id);
            
           // $("#currency_code").val(ui.item.currency_code);         
            //$('#hotel_checkin').focus()
            $.ajax({
                url: app_base_url + "index.php/ajax/get_ss_category_list",
                data: {
                    city_id: ui.item.id
                },
                success: function(cate_res) {
                    
                   // $("#search_hotel_code").empty().html(hotel_res);
                    //$("#category_id").empty().html(cate_res);
                    var cat_str = JSON.parse(cate_res);
                   
                   // var option_cate_list = cat_str.cate_option_list;
                    var check_list_cate = cat_str.cate_check_list;
                     //var option_list =option_cate_list.replace(/\\/g, "");
                     var cate_list = check_list_cate.replace(/\\/g, "");

                    //$("#category_id").empty().html(option_list);
                    $(".category-list").html(cate_list);


                },
                error: function(hotel_res) {
                    console.log("AJAX ERROR");
                }
            });
        },
        change: function(ev, ui) {
            if (!ui.item) {
                $(this).val("")
            }
        }
    }).bind('focus', function() {
        $(this).catcomplete("search")
    }).catcomplete("instance")._renderItem = function(ul, item) {
        var auto_suggest_value = highlight_search_text(this.term.trim(), item.value, item.label);
        var hotel_count = '';
        var count = parseInt(item.count);
        if (count > 0) {
            var h_lab = '';
            if (count > 1) {
                h_lab = 'Hotels'
            } else {
                h_lab = 'Hotel'
            }
            hotel_count = '<span class="hotel_cnt">(' + parseInt(item.count) + ' ' + h_lab + ')</span>'
        }
        return $("<li class='custom-auto-complete'>").append('<a> <span class="fal fa-map-marker-alt"></span> ' + auto_suggest_value + ' ' + hotel_count + '</a>').appendTo(ul)
    };
	})
</script>