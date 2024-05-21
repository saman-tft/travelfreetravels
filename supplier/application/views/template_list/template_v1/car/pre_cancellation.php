<?php
	$booking_details = $data['booking_details'][0];
	extract($booking_details);

	$default_view['default_view'] = $GLOBALS ['CI']->uri->segment (1);
	$user_type = $GLOBALS ['CI']->entity_user_type;
    if($this->entity_user_id==ADMIN){
        $price = $grand_total   ; 
    }elseif($this->entity_user_id==CAR_SUPPLIER){
        $price = $admin_buying_price;  
    }else{
      $price = $total_price;
    }
    
?>
<div class="search-result">
	<div class="container">
	<div class="bakrd_color">
         <div class="clearfix"></div>
    	<div class="cancellation_page">
        	<div class="head_can">
            	<h3 class="canc_hed">Cancellation</h3>
                <div class="ref_number">
                	<div class="rows_cancel">Booking ID: <strong><?=$app_reference?></strong></div>
                    <div class="rows_cancel">Booking Date: <?=$voucher_date?></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <!-- Hotel Booking Details starts-->
			<div class="toprom">
			    <div class="col-xs-8 nopad full_room_buk">
			        <div class="bookcol">
			            <div class="hotelistrowhtl">
			                <div class="col-md-4 nopad xcel">
			                    <div class="imagehotel"><img src="<?=$car_image?>" alt="HotelImage"></div>
			                </div>
			                <div class="col-md-8 padall10 xcel">
			                    <div class="hotelhed"><?=$car_name?></div>
			                    <div class="clearfix"></div>
			                   
			                    <div class="clearfix"></div>
			                    <div class="mensionspl"> 
			                    	Pickup Location :<?=$pickup_location?><br/>
			                    	Drop Location :<?=$drop_location?> <br/>

			                    	Pickup Date : <?=$trip_start_date?> <br/>
			                    	Pickup Time : <?=$trip_start_time?> <br/>
			                    	Total No Of Paxes : <?=$total_pax?>
			                    </div>
			                 </div>
			            </div>
			        </div>
			    </div>
			   
			</div>
            <!-- Hotel Booking Details Ends-->
            <div class="clearfix"></div>
            <div class="cancel_bkd">
            <div class="row_can_table hed_table">
            	<div class="col-xs-2 nopad">
                	<div class="can_pads">Passenger Name</div>
                </div>
                
                <div class="col-xs-2 nopad">
                	<div class="can_pads">Confirmation RefNumber</div>
                </div>
                <div class="col-xs-2 nopad">
                	<div class="can_pads">Booking ID</div>
                </div>
                 <div class="col-xs-2 nopad">
                    <div class="can_pads">Booking Status</div>
                </div>
            </div>
		   <div class="row_can_table">
	            	<div class="col-xs-2 nopad">
	                	<div class="can_pads"><?=$lead_pax_name?></div>
	                </div>	               
	                <div class="col-xs-2 nopad">
	                	<div class="can_pads"><?=$booking_reference?></div>
	                </div>
	                <div class="col-xs-2 nopad">
	                	<div class="can_pads"><?=$app_reference?></div>
	                </div>
                    
                     <div class="col-xs-2 nopad">
                        <div class="can_pads"><?=$booking_details['booking_status']?></div>
                    </div>
	           	 </div>

            </div>
            <div class="clearfix"></div>

            <div class="ritside_can col-xs-4 nopad">
            
            <div class="col-xs-6 nopad">
            	<div class="btn_continue">
                	<div class="amnt_disply">
                    	Total Amount Paid:
                    	<div class="amnt_paid"><?php echo $booking_details['currency'];?> <?=$price?></div>
                    </div>
                </div>
             </div>
             <?php if ($booking_details['booking_status'] != "BOOKING_CANCELLED") { ?>
             <div class="col-xs-6 nopad">   
                <div class="btn_continue">
                	<button data-toggle="modal" data-target="#confirm_cancel" class="btn btn-warning" type="button">Confirm</button>
                </div>
             </div>
             <?php } ?>
            </div>
            
            
        </div>
    </div>
    </div>
</div>

<!-- Confirm Cancealltion Starts-->
<div class="modal fade" tabindex="-1" role="dialog" id="confirm_cancel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Do you want to cancel the Booking?</h4>
      </div>
      <!--<div class="modal-body"></div>-->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <a href="<?=base_url().'index.php/car/cancel_booking/'.$app_reference.'/'.$booking_source?>" class="btn btn-danger">Yes</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Confirm Cancealltion Ends-->