

<style type="text/css">
	
	 .normalinput, .totlall {
                     
                     display: block;
                     height: 45px;
                     border-radius: 8px !important;
                     padding: 0 10px 0 15px;
                     width: 100%;
                     }
                     .padfive{ padding: 0px !important; }
                     .wament { color:#fff; }
                     .searchsbmtfot { width:100%; }
                     .searchsbmt { padding: 2px 15px !important; height: 45px; }
                     .lablform { color:#fff; }
                     .araeinner {padding: 15px;}
                     .normalsel { border-radius: 0px !important;}
                  
  .searchsbmtfot input.searchsbmt, .searchsbmtfot button.searchsbmt 
  {
          z-index: 1000;
  }
	.normalinput{border-radius:8px!important;}
  body{
    font-family: 'ubuntu-r'
  }

</style>
<div class="modfictions for_holiday_modi layout_modification">
   <div class="modinew">
      <div class="container">
         <div class="contentsdw">
            <div class="smldescrptn">
               <div class="col-sm-10 col-xs-10 nopad">
                  <!--<div class="col-xs-3 boxpad none_boil_full">
                     <div class="loc_i"><i class="fal fa-map-marker-alt"></i></div>
                     <h3 class="placenameflt"><?=$location?> <span class="close_fil_box"><i class="fas fa-times"></i></span></h3>
                  </div>-->
                  <div class="col-xs-12 col-md-3 boxpad none_boil mobview">

                            <h4 class="contryname">Nationality</h4>

                            <h3 class="placenameflt">
                                <?php echo $_GET['nationality']; ?>
                            </h3>

                        </div>
				   <div class="col-xs-3 boxpad none_boil">
					   <h4 class="contryname">Destination</h4>
					   <h3 class="placenameflt"><?php if($tour_search_params['city_name'] !=""){ echo $tour_search_params['city_name']; }else{ echo "All Destination"; } ?></h3>
				   </div>
				   <div class="col-xs-3 boxpad none_boil">
					   <div class="mdityp">
						   <h4 class="contryname">Package Type</h4>
						    <h3 class="placenameflt">
                  <?php 
                          if(isset($holiday_data['package_types'])){
                            $holiday = $holiday_data['package_types'];
                          }else{
                            $holiday = $package_types;
                          }
                          if(!empty($holiday)){ ?>
                          <?php foreach ($holiday as $package_type) { ?>
                        
                              <?php if(isset($tour_search_params['package_type'])){ if($tour_search_params['package_type'] == $package_type->id)
                              {
                                $ptype=$package_type->tour_subtheme;
                              }
                              

                               } ?>
                          <?php } 

                            if($ptype !=""){
                              echo $ptype;
                            }
                            else
                            {
                              echo "All Package Types";
                            }


                          ?>


                          <?php } ?>




                </h3>
					   </div>
				 </div>
				    <div class="col-xs-3 boxpad none_boil">
					   <div class="mdityp">
						   <h4 class="contryname">Duration</h4>
						    <h3 class="placenameflt"><?php if($tour_search_params['duration'] !=""){ echo $tour_search_params['duration']; }else{ echo "All Durations"; }?></h3>
					   </div>
					   
				   </div>
				   <!--  <div class="col-xs-3 boxpad none_boil">
					   <div class="mdityp">
						   <h4 class="contryname">Budget</h4>
						    <h3 class="placenameflt">All</h3>
					   </div>
					   
				   </div> -->
				   
             <!--<div class="col-xs-3 boxpad none_boil">
                     <div class="cal_i"><i class="fal fa-calendar-check"></i></div>
                     <div class="boxlabl">CHECK IN</div>
                     <div class="datein"><span class="calinn"><?php echo $this->session->userdata('holiday_checkin') ?></span></div>
                  </div>-->
                  
               </div>
             <!--  <div class="col-sm-3 col-xs-2 nopad">
                  <div class="pas_i"><i class="fal fa-users"></i></div>
                  
                 <div class="col-xs-8 boxpad none_mody">
                     <div class="boxlabl">Guests</div>
                     <div class="countlbl" style="float: left"><i style="color: #8c8c8c;"></i><?php echo $this->session->userdata('adult_count')+$this->session->userdata('child_count') ?></div>
                    </div>
               </div>-->
             <div class="col-xs-2 boxpad pull-right"><a class="modifysrch collapsed" data-toggle="collapse" data-target="#modify" aria-expanded="false"> Modify Search</a>    </div>
          
			 
			 </div>
            <div class="clearfix"></div>
         </div>
      </div>
   </div>
   <div class="clearfix"></div>
   <div class="modify_s" style="position: relative;">
      <div class="nopad">
         <div class="splmodify">
			 <div class="container">
           <div id="modify" class="araeinner collapse" aria-expanded="false">
               <div class="insplarea">
				   
				  <div class="col-xs-12 col-md-12 nopad">
                     <form action="<?php echo base_url().'index.php/general/pre_holiday_search'?>"
                            autocomplete="off" id="holiday_search">
                            <div class="tabspl forhotelonly modifdrp">
                              <div class="tabrow">
                                     <div class="col-md-2 col-xs-12 padfive">
                                                <span class="formlabel">Nationality</span>
                                                <div class="selectedwrap nationlty sidebord">
                                                    <select class="normalsel normalinput holyday_selct" id="nationality"
                                                        name="nationality" required>
                                                        <option value="">Select</option>
                                                        <?php if(!empty($holiday_data['country'])){?>
                                                        <?php 
															 foreach ($holiday_data['country'] as $country) { 
				
				   ?>
                                                        <option value="<?php echo $country['isocode']; ?>"  <?php if($country['isocode']==$_GET['nationality']){ echo "selected"; } ?>>
                                                            <?php echo $country['nationality']; ?>
                                                        </option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                <div class="col-md-3 col-sm-6 col-xs-12 bpd mobile_width padfive">
                                  <div class="lbl_txt">Country</div>
                                  <!-- <select class="normalsel padselct form-control normalinput  arimo" id="country"
                                    name="country">
                                    <option value="">All</option>
                          <?php 
                          if(isset($holiday_data['countries'])){
                            $country_data = $holiday_data['countries'];
                          }else{
                            $country_data = $countries;
                          }
                          if(!empty($country_data)){ ?>
                          <?php foreach ($country_data as $country) { ?>
                          <option value="<?php echo $country->country_name; ?>"
                              <?php if(isset($tour_search_params['country'])){ if($tour_search_params['country'] == $country->country_name) echo "selected"; }?>><?php echo $country->country_name; ?>
                            </option>
                          <?php } } ?>
                        </select> -->
                                <input type="text" id="country" class="fromholiday1 normalinput form-control b-r-0 brleft30" placeholder="All Destination" name="city" value="<?php echo @$scountry;?>"/>

                <input type="hidden" name="" id="name-search">

                <input class="hide loc_id_holder" name="destination_id" type="hidden" id="destination_id" value="<?=@$scountry?>" >


                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-12 bpd mobile_width padfive">
                                  <div class="lbl_txt">Package Type</div>
                                  <select class="normalsel form-control normalinput padselct arimo" id="package_type"
                                    name="package_type">
                                    <option value="">All Package Types</option>
                          <?php 
                          if(isset($holiday_data['package_types'])){
                            $holiday = $holiday_data['package_types'];
                          }else{
                            $holiday = $package_types;
                          }
                          if(!empty($holiday)){ ?>
                          <?php foreach ($holiday as $package_type) { ?>
                        <option value="<?php echo $package_type->id; ?>"
                              <?php if(isset($tour_search_params['package_type'])){ if($tour_search_params['package_type'] == $package_type->id) echo "selected"; } ?>><?php echo $package_type->tour_subtheme; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                                </div>
                                <div class="col-md-2 col-sm-6 bpd col-xs-12 mobile_width padfive">
                                  <div class="lbl_txt">Duration</div>
                                  <select class="normalsel padselct normalinput arimo form-control " id="duration"
                                    name="duration">
                                    <option value="">All Durations</option>
                                    <option value="1-3"
                                      <?php if(isset($tour_search_params['duration'])){ if($tour_search_params['duration'] == '1-3') echo "selected"; } ?>>1-3</option>
                                    <option value="4-7"
                                      <?php if(isset($tour_search_params['duration'])){ if($tour_search_params['duration'] == '4-7') echo "selected"; } ?>>4-7</option>
                                    <option value="8-12"
                                      <?php if(isset($tour_search_params['duration'])){ if($tour_search_params['duration'] == '8-12') echo "selected"; } ?>>8-12</option>
                                    <option value="12"
                                      <?php if(isset($tour_search_params['duration'])){ if($tour_search_params['duration'] == '12') echo "selected"; } ?>>12</option>
                                  </select>
                                </div>
                                <!-- <div class="col-md-2 col-sm-6 col-xs-12 bpd mobile_width padfive">
                                  <div class="lbl_txt">Budget</div>
                                  <select class="normalsel padselct arimo form-control normalinput" id="budget"
                                    name="budget">
                                    <option value="">All</option>
                                    <option value="100-500"
                                      <?php if(isset($sbudget)){ if($sbudget == '100-500') echo "selected"; } ?>>100-500</option>
                                    <option value="500-1000"
                                      <?php if(isset($sbudget)){ if($sbudget == '500-1000') echo "selected"; } ?>>500-1000</option>
                                    <option value="1000-5000"
                                      <?php if(isset($sbudget)){ if($sbudget == '1000-5000') echo "selected"; } ?>>1000-5000</option>
                                    <option value="5000"
                                      <?php if(isset($sbudget)){ if($sbudget == '5000') echo "selected"; } ?>>5000</option>
                                  </select>
                                </div> -->
                                <div class="col-md-2 col-sm-6 col-xs-12 bpd padfive">
                                  <div class="">&nbsp;</div>
                                  <div class="searchsbmtfot">
                                    <input type="submit" class="searchsbmt" value="search" />
                                  </div>
                                </div>
                              </div>
                            </div>

                          </form>

                     
                  </div>
                     
                     <span class="hide"> <input type="hidden" id="pri_visible_room" value="1"></span>
				   
				   
                  
                  </div>
                  <script type="text/javascript">$(document).ready(function() {
                     $(".chk_in_summery").click(function(){
                           $('#holiday_checkin').datepicker('show');
                     });
                     
                     // $(".chk_out").click(function(){
                     //       $('#holiday_checkout').datepicker('show');
                     // });





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
                     });
                  </script>
               </div>
			 
			 
            </div>
		  </div>
         </div>
      </div>
   </div>
