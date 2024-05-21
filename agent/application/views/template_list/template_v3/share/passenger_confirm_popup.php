<!--changes start agent booking page and sidebar: commented the code and added new-->

<!-- Modal for passenger confirm -->
<!--<div id="passenger-confirm-modal" class="modal fade" role="dialog" data-keyboard="false">-->
<!--	<div class="modal-dialog modal-lg large-details">-->

	    <!-- Modal content-->
<!--		<div class="modal-content">-->
<!--	      	<div class="modal-header">-->
<!--	        	<button type="button" data-dismiss="modal" class="close">&times;</button>-->
<!--	        	<h4 class="modal-title" id="passenger-confirm-header"></h4>-->
<!--	      	</div>-->
<!--	      	<div class="modal-body" id="passenger-confirm-body">-->
<!--	      	</div>-->
<!--	      	<div class="modal-footer">-->
<!--	        	<button type="button" class="btn btn-primary edit-user-details-btn" data-dismiss="modal" id="edit-user-details-btn">Edit Details</button>-->
<!--	        	<button type="button" class="btn btn-success make-payment-btn" id="make-payment-btn">Continue</button>-->
<!--	      	</div>-->
<!--	    </div>-->

<!--	</div>-->
<!--</div>-->

<!-- Modal for passenger confirm -->
<div id="passenger-confirm-modal" tabindex="-1" class="modal fade" role="dialog" data-keyboard="false" aria-labelledby="passenger-confirm-modalLabel">
    <div class="modal-dialog modal-lg large-details" style="min-width: 50%;">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background-color: #8F53A1;">
                <button style="background: #8F53A1 !important;" type="button" data-dismiss="modal" class="close">&times;</button>
                <h6 class="modal-title" id="passenger-confirm-header" style="color: white;font-size:20px;"></h6>
            </div>
            <div class="modal-body">
                <div class="modal-content-scrollable">
                    <div style="font-size:18px;" id="passenger-confirm-body"></div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- changes button id changed to make-payment-button class btn-danger removed, now head to extras\system\template_list\template_v1\javascript\page_resource\booking_script.js -->
                <button type="button" class="btn btn-primary btn-sm edit-user-details-btn text-left" data-dismiss="modal" id="edit-user-details-btn" style="background-color:#8F53A1; justify-content: start; padding: 5px 10px; font-size: 14px;">EDIT DETAILS</button>
                <!-- <button type="button" class="btn btn-sm confirm-passenger-btn text-right" id="confirm-passenger-btn" style="background-color: green; justify-content: end; padding: 5px 10px; font-size: 14px;">CONTINUE <i class="fa fa-arrow-right"></i></button> -->
                <button type="button" class="btn btn-sm make-payment-btn text-right" id="make-payment-btn" style="background-color: #8F53A1; justify-content: end; padding: 5px 10px; font-size: 14px;">CONTINUE <i class="fa fa-arrow-right"></i></button>
            </div>
        </div>
    </div>
</div>
<!--changes start agent booking page and sidebar: commented the code and added new-->
