<style>

.toolwhite+ .tooltip > .tooltip-inner {background-color: #fff;color:#000;}
.toolwhite + .tooltip > .tooltip-arrow {border-top-color: #fff;}
.transf .a-textfree {
    padding: 9px 0 0;
    /* display: inline-table; */
    margin-left: 10px;
    position: absolute;
    top: 28px;}
    .a-price,.m-media__price {
    display: block;
}.a-price__description,.a-price__description,.m-media__price{text-align: right;}
.m-info-display__item {
    width: auto;
    }.m-media__title{padding: 10px 10px 0;}
    .m-media__container {
    box-shadow: 0 0 5px 0 rgb(0 0 0 / 11%);
}#tour_search_result .col-xs-4 .m-media__image{
	width: 100%
}.hondycar li::before{    font-family: "Font Awesome 5 Pro";}
.m-media__text{    padding: 5px 6px;margin: 3px 0 0;}
.booknow{    margin: 2px;    padding: 5px 8px;}
.a-price__description, .a-price__description, .m-media__price {
    text-align: left;
}
</style>

<?php
$template_images = $GLOBALS['CI']->template->template_images();
$mini_loading_image = '<div class="text-center loader-image">Please Wait <img src="'.$template_images.'loader_v3.gif" alt="Loading........"/></div>';
$add_to_service_array = array();
// debug($raw_transfer_list);exit;
$i=1;
foreach ($raw_transfer_list['SSSearchResult']['TransferResults'] as $product_key=>$product) 
{ 
	if($product['booking_source'] == PROVAB_TRANSFER_SOURCE_CRS)
		{
			// debug($product);exit;
			?>
			<div class="rowresult r-r-i col-xs-4  transf">
				<div class="m-media__container">
				    <div class="m-media__headline">
				        <div class="col-xs-3 nopad listimage full_mobile">
				            <div class="m-media_headlineimage">
				                <img src="<?php echo $product['ImageUrl']; ?>" class="m-media__image" alt="">
				            </div>

				        </div>

				        <div class="col-xs-9 nopad listfull full_mobile">
				            <div class="sidenamedesc">
				                <div class="celhtl width70">
				                    <div class="m-media__headline-item">
				                        <span class="m-media__title toolwhite" data-toggle="tooltip" data-placement="top" title="<?php echo $product['transfer_name']; ?>"><?php echo $product['transfer_name']; ?></span>
				                    </div>

				                    <div class="m-media__headline-item">
				                        <div class="m-media__list">
				                            <div class="m-media__list-item">
				                                <span class="a-text-highlight">
				                                    <span class="a-text_list"> <i class="fa fa-users"></i><?php echo $product['TotalPax']; ?> passengers</span>
				                                </span>
				                            </div>
				                        </div>
				                    </div>
				                    <div class="m-media__headline-item">
				                        <div class="m-media__list">
				                            <div class="m-media__list-item">
				                                <div class="strrat">
							
							 <?php
											
						    	$current_hotel_rate = round($product['StarRating']);	
							?>
							<ul class="std">
								<li class="starrtinghotl rating-no">
										<span class="h-sr hide"><?php echo $current_hotel_rate?></span>
										<?php echo print_star_rating($current_hotel_rate);?>
								</li>
							</ul> 
					    </div>
				                            </div>
				                        </div>
				                    </div>
				                </div>

				                <div class="celhtl width30 transferprice">
				                    <div class="m-media__price">

				                        <div class="a-price">
				                            <span class="currency a-price__price" id="span_total_price_144227" rel="177.9932">
<?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>
				                            <?php
				                            // echo $strCurrency_m['default_value'];
				                            $agent_base_currency = agent_base_currency();
				                            echo $product['Price']['TotalDisplayFare'];
				                             	
				                             //echo $product['Price']['TotalDisplayFare']; 

				                             ?>&nbsp;<?php echo $agent_base_currency; ?></span>
				                        </div>
				                         <?php if($product['Cancellation_available']==1){ ?>
				                        <div class="a-textfree">
				                            <span class="a-text-highlight__price"><?php echo  $product['cancellation_details']; ?></span>
				                        </div>
				                        <?php } 
				                        else{
				                        	?>
				                        	<div class="a-textfree">
				                            <span class="a-text-highlight__price"><?php echo  $product['cancellation_details']; ?></span>
				                        </div>
				                        	<?php
				                        }?>
				                    </div>
				                </div>

				            </div>
				        </div>
				    </div>

				    <div class="m-media__content">
				        <div class="m-media__menu">
				            <div class="m-media__highlights">
				            	<?php
				            	if($product['exclusive_ride']=='Y'){
				            		$display_status = '';
				            	}else{
				            		$display_status = 'style="display:none;"';
				            	}
				            	if($product['meetup_location']=='Y'){
				            		$meetup_status = '';
				            	}else{
				            		$meetup_status = 'style="display:none;"';
				            	}
				            	?>
				            	<div class="m-info-display__item" <?=$display_status?>>
				                    <span class="m-info-display__text"><i class="fa fa-thumbs-up"></i> Exclusive ride for you</span>
				                </div>
				                <div class="m-info-display__item" <?=$meetup_status?>>
				                    <span class="m-info-display__text"><i class="fa fa-thumbs-up"></i> Meet Up At Location</span>
				                </div>
				            </div>

				            <div class="m-media__cta">
				                <a class="m-media__cta-item"  data-toggle="collapse" href="#transfer_details<?=$i?>" >
				                    <span class="m-media__text" >more info <i class="fa fa-angle-down"></i></span>
				                    <span class="m-media__icon"></span></a>

				                <div class="m-media__right pull-right">

				                <form  method="post" action="<?php echo base_url()?>index.php/transfer/transfer_details?op=get_details&search_id=<?=$search_id?>&booking_source=<?=urlencode($product['booking_source'])?>&price_id=<?=@$product['price_id']?>&result_token=<?=$product['ResultToken']?>" name="form_transfer_<?= $product_key ?>">
			                       
			                        <?php

			                        foreach ($add_to_service_array as $add_key => $add_to_service) {
			                            ?><input type="hidden" name="search_id" value="<?= $search_id ?>" />
			                            <input type="hidden" name="booking_source" value="<?= $booking_source ?>" />
			                            <input type="hidden" name="avai_token" value="<?= @$product['avail_token'] ?>" />
			                            <input type="hidden" name="transfer_code[]" value="<?= @$add_to_service['transfer_code']; ?>" />
			                            <input type="hidden" name="transfer_type_code[]" value="<?= @$add_to_service['transfer_type_code']; ?>" />
			                            <input type="hidden" name="vehicle_type_code[]" value="<?= @$add_to_service['vehicle_type_code'] ?>" />
			                            <input type="hidden" name="adult_count[]" value="<?= @$add_to_service['adult_count'] ?>" />
			                            <input type="hidden" name="child_count[]" value="<?= @$add_to_service['child_count'] ?>" />
			                            <input type="hidden" name="transfer_type[]" value="<?= @$add_to_service['transfer_type'] ?>" />
			                            <input type="hidden" name="name[]" value="<?= @$add_to_service['name'] ?>" />
			                            <input type="hidden" name="incoming_office_code[]" value="<?= @$add_to_service['incoming_office_code'] ?>" />
			                            <input type="hidden" name="from_date[]" value="<?= @$add_to_service['from_date'] ?>" />
			                            <input type="hidden" name="from_date_time[]" value="<?= @$add_to_service['from_date_time'] ?>" />
			                            <input type="hidden" name="pickup_location_code[]" value="<?= @$add_to_service['pickup_location_code'] ?>" />
			                            <input type="hidden" name="pickup_location_name[]" value="<?= @$add_to_service['pickup_location_name'] ?>" />
			                            <input type="hidden" name="pickup_location_transfer_zone[]" value="<?= @$add_to_service['pickup_location_transfer_zone'] ?>" />
			                            <input type="hidden" name="destin_location_code[]" value="<?= @$add_to_service['destin_location_code'] ?>" />
			                            <input type="hidden" name="destin_location_name[]" value="<?= @$add_to_service['destin_location_name'] ?>" />
			                            <input type="hidden" name="destin_location_transfer_zone[]" value="<?= @$add_to_service['destin_location_transfer_zone'] ?>" />
			                            <input type="hidden" name="currency" id="currency" value="<?= @$product['currency'] ?>" />
			                               <input type="hidden" name="currency_symbol" id="currency_symbol" value="<?= @$currency_symbol ?>" />
			                            <input type="hidden" name="customer_waiting_time" id="customer_waiting_time" value="<?= @$product['customer_waiting_time'] ?>" />
			                            <?php }
			                        ?>
			                        <input type="submit" value="Select this vehicle" class="booknow" />
			                    </form> 

				                    <!-- <div class="a-button--primary">
				                        <a type="button" class="a-button__container" title="Book Now">
				                            <span class="a-button__text a-button__text--inherit">Select this vehicle</span>
				                        </a>
				                    </div> -->
				                </div>
				            </div>
				        </div>

				        <div id="transfer_details<?=$i?>" class="collapse">
				            <div class="col-xs-12 nopad">                                
				                <div class="sidenamedesc">                                                                                         
				                    <div class="col-sm-12 col-xs-12 nopad">                                        
				                        <div class="car_sideprice">                                                                               
				                            <span class="inclusions">General Info List</span>                                            
				                            <ul class="hondycar">
				                                <li><?php echo $product['general_list_info']; ?></li>  
				                            </ul>                                        
				                        </div>                                    
				                    </div>                                
				                </div>  

				                <div class="clearfix"></div>    

				                <div class="rentcondition">                                    
				                    <span class="conhead">Pickup Information</span>                                    
				                    <p><?php echo $product['pick_up_info']; ?></p>                                    
				                    <span class="conhead">Guidelines List</span>
                                        
				                    <p><?php echo $product['guidelines_list'];?></p>              
				                </div>                            
				            </div>

				        </div>
				    </div>
				</div>
			</div>	
			<?php 
		}
		else
		{
			$add_to_service_array[] = $product['add_to_service_array']; ?>
			<div class="rowresult r-r-i col-xs-4">
				<div class="m-media__container">
				    <div class="m-media__headline">
				        <div class="col-xs-3 nopad listimage full_mobile">
				            <div class="m-media_headlineimage">
				                <img src="<?php echo $product['ImageUrl']; ?>" class="m-media__image" alt="">
				            </div>

				        </div>

				        <div class="col-xs-9 nopad listfull full_mobile">
				            <div class="sidenamedesc">
				                <div class="celhtl width70">
				                    <div class="m-media__headline-item">
				                        <span class="m-media__title"><?php echo $product['ProductSpecifications']['MasterServiceType']['@attributes']['name']; ?> <?php echo $product['ProductSpecifications']['MasterVehicleType']['@attributes']['name']; ?></span>
				                    </div>

				                    <div class="m-media__headline-item">
				                        <div class="m-media__list">
				                            <div class="m-media__list-item">
				                                <span class="a-text-highlight">
				                                    <span class="a-text_list"> <i class="fa fa-users"></i><?php echo $product['TotalPax']; ?> passengers</span>
				                                </span>
				                            </div>

				                           <!--  <div class="m-media__list-item">
				                                <span class="a-text-highlight">
				                                    <span class="a-text_list"><i class="fa fa-suitcase"></i>3 medium suitcases</span>
				                                </span>
				                            </div>

				                            <div class="m-media__list-item">
				                                <span class="a-text-highlight">
				                                    <span class="a-text_list"><i class="fa fa-suitcase"></i> Door to Door</span>
				                                </span>
				                            </div>

				                            <div class="m-media__list-item">
				                                <span class="a-text-highlight">
				                                    <span class="a-text_list"><i class="fa fa-clock-o"></i>1 Hour 45 Mins</span>
				                                </span>
				                            </div> -->
				                        </div>
				                    </div>
				                </div>

				                <div class="celhtl width30 transferprice">
				                    <div class="m-media__price">

				                        <div class="a-price">
				                            <span class="currency a-price__price" id="span_total_price_144227" rel="177.9932">

				                            <?php
				                            $agent_base_currency = agent_base_currency();
				                            // echo $agent_base_currency;
				                            $currency_obj_m = new Currency(array('module_type' => 'transfers', 'from' => $product['currency'], 'to' => $agent_base_currency));
										 $strCurrency_m  = $currency_obj_m->get_currency($product['Price']['TotalDisplayFare'], true, false, true, false, 1);
				                             	echo $product['Price']['TotalDisplayFare'];

				                             	
				                             //echo $product['Price']['TotalDisplayFare']; 

				                             ?><?php echo $agent_base_currency; ?></span>
				                            <span class="a-price__description">Return price</span>
				                        </div>
				                        <?php if($product['Cancellation_available']==1){ ?>
				                        <div class="a-textfree">
				                            <span class="a-text-highlight__price"><?php echo  $product['cancellation_details']; ?></span>
				                        </div>
				                        <?php } 
				                        else{
				                        	?>
				                        	<div class="a-textfree">
				                            <span class="a-text-highlight__price"><?php echo  $product['cancellation_details']; ?></span>
				                        </div>
				                        	<?php
				                        }?>
				                        <div class="a-text-highlight--clean">
				                            <span class="a-text-highlight__text">Zero fees</span>
				                        </div>
				                    </div>
				                </div>

				            </div>
				        </div>
				    </div>

				    <div class="m-media__content">
				        <div class="m-media__menu">
				            <div class="m-media__highlights">
				            <?php foreach ($product['ProductSpecifications']['TransferGeneralInfoList']['TransferBulletPoint'] as $key => $info) {
				            if($key<2){ ?>
				                <div class="m-info-display__item">
				                    <span class="m-info-display__text"><i class="fa fa-thumbs-up"></i> <?php echo $info['Description']; ?></span>
				                </div>

				                <!-- <div class="m-info-display__item">
				                    <span class="m-info-display__text"><i class="fa fa-thumbs-up"></i> Exclusive ride for you</span>
				                </div> -->
				            <?php } } ?>
				            </div>

				            <div class="m-media__cta">
				                <a class="m-media__cta-item"  data-toggle="collapse" href="#transfer_details<?=$i?>">
				                    <span class="m-media__text">more info <i class="fa fa-angle-down"></i></span>
				                    <span class="m-media__icon"></span></a>

				                <div class="m-media__right pull-right">

				                <form  method="post" action="<?= base_url() ?>index.php/transfer/transfer_details_api/<?=$booking_source;?>?search_id=<?=$search_id?>" name="form_transfer_<?= $product_key ?>">
			                       
			                        <?php

			                        foreach ($add_to_service_array as $add_key => $add_to_service) {
			                            ?><input type="hidden" name="search_id" value="<?= $search_id ?>" />
			                            <input type="hidden" name="booking_source" value="<?= $booking_source ?>" />
			                            <input type="hidden" name="avai_token" value="<?= @$product['avail_token'] ?>" />
			                            <input type="hidden" name="transfer_code[]" value="<?= @$add_to_service['transfer_code']; ?>" />
			                            <input type="hidden" name="transfer_type_code[]" value="<?= @$add_to_service['transfer_type_code']; ?>" />
			                            <input type="hidden" name="vehicle_type_code[]" value="<?= @$add_to_service['vehicle_type_code'] ?>" />
			                            <input type="hidden" name="adult_count[]" value="<?= @$add_to_service['adult_count'] ?>" />
			                            <input type="hidden" name="child_count[]" value="<?= @$add_to_service['child_count'] ?>" />
			                            <input type="hidden" name="transfer_type[]" value="<?= @$add_to_service['transfer_type'] ?>" />
			                            <input type="hidden" name="name[]" value="<?= @$add_to_service['name'] ?>" />
			                            <input type="hidden" name="incoming_office_code[]" value="<?= @$add_to_service['incoming_office_code'] ?>" />
			                            <input type="hidden" name="from_date[]" value="<?= @$add_to_service['from_date'] ?>" />
			                            <input type="hidden" name="from_date_time[]" value="<?= @$add_to_service['from_date_time'] ?>" />
			                            <input type="hidden" name="pickup_location_code[]" value="<?= @$add_to_service['pickup_location_code'] ?>" />
			                            <input type="hidden" name="pickup_location_name[]" value="<?= @$add_to_service['pickup_location_name'] ?>" />
			                            <input type="hidden" name="pickup_location_transfer_zone[]" value="<?= @$add_to_service['pickup_location_transfer_zone'] ?>" />
			                            <input type="hidden" name="destin_location_code[]" value="<?= @$add_to_service['destin_location_code'] ?>" />
			                            <input type="hidden" name="destin_location_name[]" value="<?= @$add_to_service['destin_location_name'] ?>" />
			                            <input type="hidden" name="destin_location_transfer_zone[]" value="<?= @$add_to_service['destin_location_transfer_zone'] ?>" />
			                            <input type="hidden" name="currency" id="currency" value="<?= @$product['currency'] ?>" />
			                               <input type="hidden" name="currency_symbol" id="currency_symbol" value="<?= @$currency_symbol ?>" />
			                            <input type="hidden" name="customer_waiting_time" id="customer_waiting_time" value="<?= @$product['customer_waiting_time'] ?>" />
			                            <?php }
			                        ?>
			                        <input type="submit" value="Select this vehicle" class="booknow" />
			                    </form> 

				                    <!-- <div class="a-button--primary">
				                        <a type="button" class="a-button__container" title="Book Now">
				                            <span class="a-button__text a-button__text--inherit">Select this vehicle</span>
				                        </a>
				                    </div> -->
				                </div>
				            </div>
				        </div>

				        <div id="transfer_details<?=$i?>" class="collapse">
				            <div class="col-xs-12 nopad">                                
				                <div class="sidenamedesc">                                                                                         
				                    <div class="col-sm-12 col-xs-12 nopad">                                        
				                        <div class="car_sideprice">                                                                               
				                            <span class="inclusions">General Info List</span>                                            
				                            <ul class="hondycar">      
				                            <?php foreach ($product['ProductSpecifications']['TransferGeneralInfoList']['TransferBulletPoint'] as $key => $info) { ?>
				                                <li><?php echo $info['Description']; ?></li> 
				                            <?php } ?>  
				                            </ul>                                        
				                        </div>                                    
				                    </div>                                
				                </div>  

				                <div class="clearfix"></div>    

				                <div class="rentcondition">                                    
				                    <span class="conhead">Pickup Information</span>                                    
				                    <p><?php echo $product['TransferPickupInformation']['Description']; ?></p>                                    
				                    <span class="conhead">Guidelines List</span>

				                    <?php foreach ($product['GenericTransferGuidelinesList'] as $key => $guideline) { ?>                                               
				                    <span class="hotel_address elipsetool"><?php echo $guideline['Description']; ?> </span>                                            
				                    <p><?php echo $guideline['DetailedDescription'];?></p>   

				                    <?php } ?>                                                                  
				                </div>                            
				            </div>

				        </div>
				    </div>
				</div>
			</div>
			<?php 	
		}
$i++;}//foreach end ?>
<script type="text/javascript">
	$(function(){
		$('[data-toggle="tooltip"]').tooltip(); 
	})

	$(document).ready(function () { 
        $(".m-media__cta-item").click(function () {
            $(".list_click").addClass("active");
            $(".grid_click").removeClass("active");
            $(".rowresult").removeClass("col-xs-4");
        });
    });
</script>
<!-- <script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script> -->