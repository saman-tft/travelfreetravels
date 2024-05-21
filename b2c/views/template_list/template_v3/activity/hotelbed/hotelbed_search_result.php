<style>
/*#tour_search_result .madgrid .forhtlpopover .celhtl .width30{height: 45px!important;}*/
	
	/*.rowresult r-r-i col-xs-4 .celhtl.width30 {height: 45px!important;}*/
	/*.acthgrd .celhtl.width30 {height: 45px!important;}*/
	.rowresult.col-xs-4 .celhtl.width30{height: 40px!important;}
	.rowresult.col-xs-4 .loc_see.refund {line-height: 53px!important;}
	.shtlnamehotl .h-name{overflow: hidden!important;
    position: relative!important;
     text-overflow: ellipsis!important; 
    white-space: nowrap!important;}
</style>
<?php 
$template_images = $GLOBALS['CI']->template->template_images();
$mini_loading_image = '<div class="text-center loader-image">Please Wait <img src="'.$template_images.'loader_v3.gif" alt="Loading........"/></div>';
	//debug($raw_sightseeing_list['SSSearchResul
foreach ($raw_sightseeing_list['SSSearchResult']['SightSeeingResults'] as $product_key=>$product) {


	$product['booking_source']=PROVAB_SIGHTSEEN_SOURCE_CRS;
	$booking_source= isset($product['booking_source'])?PROVAB_SIGHTSEEN_SOURCE_CRS:HOTELBED_ACTIVITIES_BOOKING_SOURCE;
		$current_hotel_rate = $product['StarRating'];

?>
<div class="rowresult r-r-i col-xs-4 acthgrd"  >

	<div class="madgrid forhtlpopover" data-key="<?=$product_key?>" data-product-name="<?=$product['ProductName']?>" data-product-code="<?=@$product['ProductCode']?>" data-booking-source="<?=$booking_source?>" data-search-id="<?=$search_id?>" data-result-token="<?=$product['ResultToken']?>">

		<?php
			
		   if($product['Promotion']){
		   		$d_offer = 'discount_offer';
		   }else{
		   		$d_offer ='';
		   }
		?>

		<div class="col-xs-4 nopad listimage">
			<div class="imagehtldis">
				
				<?php if($product['ImageHisUrl']):?>

					<a href="<?php echo base_url()?>index.php/activity/activity_details?op=get_details&booking_source=<?=urlencode($booking_source)?>&search_id=<?=$search_id?>&product_code=<?=@$product['ProductCode']?>&result_token=<?=$product['ResultToken']?>&enquiry_origin=<?=$enquiry_origin?>">

					<img src="<?=$product['ImageHisUrl']?>" alt="Image" data-src="<?=$product['ImageHisUrl']?>" title="<?=$product['ProductName']?>" class="lazy h-img">


					<img class="spcl_offr <?=$d_offer?>" src="<?php echo $GLOBALS['CI']->template->template_images('special_offer.png'); ?>" /></a>				
				<?php else:?>				 		
				 <a href="<?php echo base_url()?>index.php/activity/activity_details?op=get_details&booking_source=<?=urlencode($booking_source)?>&search_id=<?=$search_id?>&product_code=<?=@$product['ProductCode']?>&result_token=<?=$product['ResultToken']?>&enquiry_origin=<?=$enquiry_origin?>">

				 <img src="<?php echo $GLOBALS['CI']->template->template_images('no_image_available.jpg'); ?>" alt="Image" data-src="<?=$product['ImageUrl']?>" title="<?=$product['ProductName']?>" class="lazy h-img">
				 
				 <img class="spcl_offr <?=$d_offer?>" src="<?php echo $GLOBALS['CI']->template->template_images('special_offer.png'); ?>" />

				 </a>	

				<?php endif;?>
				
			</div>
		</div>
		<div class="col-xs-8 nopad listfull">
			<div class="sidenamedesc">
				<div class="celhtl width70">
					<div class="innd acttbosrch">
						<div class="property-type" data-property-type="hotel"></div>
						<div class="shtlnamehotl">
							<span class="h-name"><a  href="<?php echo base_url()?>index.php/activity/activity_details?op=get_details&booking_source=<?=urlencode($booking_source)?>&search_id=<?=$search_id?>&product_code=<?=@$product['ProductCode']?>&result_token=<?=$product['ResultToken']?>&enquiry_origin=<?=$enquiry_origin?>"><?php echo $product['ProductName']?></a></span>
							<?php
								// $filter_arr = array_unshift($product['Cat_Ids'],0);
								//debug($filter_arr);
								$filter_str = implode(",",$product['Cat_Ids']);

							?>
							<span class="activity-cate hide"><?=$filter_str?></span>
							
						</div>
						<div class="clearfix"></div>
						<div class="strrat">
							
							 <?php
											
						    	$current_hotel_rate = round($product['StarRating']);	
							?>
							<ul class="std">
								<li class="starrtinghotl rating-no">
										<span class="h-sr hide"><?php echo $current_hotel_rate?></span>
										<?php echo print_star_rating($current_hotel_rate);?>
								</li>
							 <?php 
							 /*if(!empty($product['ReviewCount']))
							 {
							 ?>
							 <li><span class="review"><?=$product['ReviewCount']?> Reviews</span></i></li>
							 
							 <?php }
							 else { ?>								 
								 <li><span class="review">0 Reviews</span></i></li>
								 <?php }*/ ?>
							</ul> 
					    </div>
						<div class="desc hide">
							<p><?=$product['Description']?></p>
						</div>
						<div class="adreshotle h-adr">
						 
						 <p><i class="fal fa-map-marker-alt"></i><?php echo $product['DestinationName']?></p>
						</div>
						<div class="clearfix"></div>
                        <div class="col-md-12 col-xs-12 nopad">
						   <div class="loc_see">
							<?php if($product['Duration']):?>
								<span>Duration: <?=$product['Duration']?></span>
							<?php endif;?>
						</div>
						</div>
						<div class="loc_see refund">
						<?php
// debug($product);die;
						 if($product['Cancellation_available']==1):?>
							<span>Refundable</span>
						<?php else:?>
							<span>Non-Refundable</span>
						<?php endif;?>
						</div>
					</div>
				</div>
				<?php
				
					$search_id = intval($attr['search_id']);
					$ProductPrice= $product['Price']['TotalDisplayFare'];
					
					?>
				<div class="celhtl width30">
					<div class="sidepricewrp">	
					<?php
					if($product['booking_source']==PROVAB_SIGHTSEEN_SOURCE_CRS)
					{ ?>				
						<div class="priceflights">
							<span class="currency_symbol" style="font-size: 12px;font-weight: lighter;">
								<?php
								if($product['transfer_type']=='W')
								{ $transfer_type = 'Without Transfers'; }
								if($product['transfer_type']=='S')
								{ $transfer_type = 'Sharing Transfers'; }
								if($product['transfer_type']=='P')
								{ $transfer_type = 'Private Transfers'; }
							?><?=$transfer_type?></span>
							
						</div>
						<?php 
						}
						 ?>
                        <div class="priceflights">
							<div class="prcstrtingt">starting @ </div>
							<strong class="hide"> <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> </strong>
							<strong class="currency_symbol"><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?></strong>
							<span class="h-p"><?php echo $ProductPrice; ?></span>
							<?php							
							$offer = 0;
							   if($product['Promotion']){
							   		$offer = $product['Promotion'];
							   }
							   
							?>
							<p class="special-offer hide"><?=$offer?></p>
							<div class="clearfix"></div>
							<?php if($product['PromotionAmount']):?>
								<span class="saving-amount">Save <span>
								<?=$product['PromotionAmount']?></span></span>
							<?php endif;?>
							
						</div>
                        <div class="snf_hnf hide">
							<?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> 
                        	<?php echo $ProductPrice; ?>
                        	
                        </div>
                        
						<form method="GET" action="<?php echo base_url().'index.php/activity/activity_details'?>">
							<div class="hide">
								<input type="hidden" value="get_details"									name="op" class="operation">						
								<input type="hidden" value="<?=urlencode($booking_source)?>"				name="booking_source" class="booking_source">	
								<input type="hidden" name="search_id" value="<?=$search_id?>">					
								<input type="hidden" name="result_token" value="<?=$product['ResultToken']?>">		
								<input type="hidden" name="product_code" value="<?=@$product['ProductCode']?>">
								<input type="hidden" name="enquiry_origin" value="<?=@$enquiry_origin?>">
							</div>							
							<button class="confirmBTN b-btn bookallbtn plhotltoy" type="submit">Check Dates</button>							
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>

<?php
	
}//foreach end
?>
<script type="text/javascript">
	$(function(){
		$('[data-toggle="tooltip"]').tooltip(); 
	})
	 $(document).on("click", ".madgrid", function () {
                
                var search_id = $(this).data('search-id');
                var booking_source = $(this).data('booking-source');
                var product_code = $(this).data('product-code');
                var result_token = $(this).data('result-token');
                window.location ='<?php echo base_url()?>index.php/activity/activity_details?op=get_details&search_id='+search_id+'&booking_source='+booking_source+'&product_code='+product_code+'&result_token='+result_token;

                //alert(url_text);


            });
</script>