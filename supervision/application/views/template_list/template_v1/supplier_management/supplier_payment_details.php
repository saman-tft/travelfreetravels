
<?php


$_datepicker = array (
  array (
    'date_of_transaction',
    PAST_DATE 
    )
  );
$this->current_page->set_datepicker ( $_datepicker );
$this->current_page->auto_adjust_datepicker ( array (
  array (
    'date_of_transaction'
    ) 
  ) );

?>
<?php 
Js_Loader::$js [] = array (
		'src' => $GLOBALS ['CI']->template->template_js_dir ( 'transaction_rollback.js' ),
		'defer' => 'defer'
);
Js_Loader::$js [] = array (
		'src' => $GLOBALS ['CI']->template->template_js_dir ( 'common_report.js' ),
		'defer' => 'defer'
);
Js_Loader::$js [] = array ('src' => $GLOBALS ['CI']->template->template_js_dir ( 'page_resource/table_fix.js' ),'defer' => 'defer');

?>

    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
<!-- HTML BEGIN -->
<div id="general_user" class="bodyContent">
	<div class="table_outer_wrper">
		<!-- PANEL WRAP START -->
		<div class="panel_custom_heading">
			<!-- PANEL HEAD START -->
			<div class="panel_title">
				<ul class="nav nav-tabs b2b_navul" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" ><a id="fromListHead"
						href="#fromList" aria-controls="home" role="tab" data-toggle="tab">
							<i class="fa fa-edit"></i> Pay to Supplier
					</a></li>
					<li role="presentation" class="active"><a href="#tableList"
						aria-controls="profile" role="tab" data-toggle="tab"> <i
							class="fa fa-money"></i> Supplier Payment Details
					</a></li>
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
				</ul>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="clearfix"></div>
		<div class="panel_bdy">
			<!-- PANEL BODY START -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane clearfix" id="fromList">

					<div class="panel_inside">
						
						<div class="clearfix"></div>

						<div class="section_deposite">
						<form name="request_form" autocomplete="off" action="https://travelfreetravels.com/supervision/index.php/supplier_management/payment_details" method="POST" enctype="multipart/form-data" id="request_form" role="form" class="form-horizontal">
						  
						   <fieldset form="request_form">
						   	  <br>
						      <legend class="form_legend">Pay To Supplier</legend>
						      
						      <?php
						      if(!empty($this->session->flashdata('success')))
						      {
						      	echo '<p class="form_legend"> Updated Successfully !</p>';
						      }
						      else if(!empty($this->session->flashdata('error')))
						      {
						      	echo '<p class="form_legend"> Something Went Wrong ! Please Try Again.</p>';
						      }
						      	
						      ?>
						      
						      <div class="form-group">
						      	<input name="origin" type="hidden" id="origin" class=" origin hiddenIp" required="" value="0">

						         <label class="col-sm-3 control-label" for="amount" form="request_form">Supplier<span class="text-danger">*</span></label>
						         <div class="col-sm-6">
						         	<select class="form-control" name="supplier_id" id="supplier_id" required>
						              <option>Select</option>
						                <?php
						                  foreach($supplier_list as $supplier)
						                  {
						                      echo '<option value="'.$supplier['user_id'].'">'.$supplier['first_name'].' '.$supplier['last_name'].'</option>';
						                  }
						                ?>
						            </select>
						         </div>
						      </div>
						      <div class="form-group">
						           
						         <label class="col-sm-3 control-label" for="amount" form="request_form">Module<span class="text-danger">*</span></label>
						          <div class="col-sm-6">
						         <select class="form-control moduler"   name="module" required>
                                      <option value="">Select a module</option>
                                      <option value="Tour" >Tour</option>
                                    <option value="hotel" >hotel</option>
                                      <option value="Transfer" >Transfer</option>
                                       <option value="Activity" >Activity</option>
                                        
                                    </select>
                                    </div>
						      </div>
						      <div class="form-group">
						         <label class="col-sm-3 control-label" for="amount" form="request_form">Paying For<span class="text-danger">*</span></label>
						         <div class="col-sm-3">
						        	 <label> Month <span class="text-danger">*</span></label> 
						            <select class="form-control" name="payment_for_month" id="paying_month">
						              <option value="">Select</option>
						              <?php
						                for($i = 1; $i <= 12; $i++)
						                {
						                   echo '<option value="0'.$i.'">'.date('F', strtotime('2020-'.$i.'-01')).'</option>';
						                }
						              ?>
						            </select>

						            <p id="error" style="color:red;margin-top:5%;display: none">* Already Paid For Given Month</p>
						           </div>
						           <div class="col-sm-3">
						           		<label> Year <span class="text-danger">*</span></label> 
							            <select class="form-control" name="payment_for_year" id="paying_year">
							              <option value="">Select</option>
							              <?php
							                for($i = 2020; $i <= date('Y'); $i++)
							                {
							                   echo '<option value="'.$i.'">'.$i.'</option>';
							                }

							              ?>
							            </select>
						           </div>
						        
						      </div>

						      <div class="form-group">
						         <label class="col-sm-3 control-label" for="amount" form="request_form">Amount (NPR)<span class="text-danger">*</span></label>
						         <div class="col-sm-6"><input value="" name="amount" required="" type="number" placeholder="" class=" numeric amount form-control" id="amount" data-original-title="" title=""></div>
						      </div>
						      <div class="form-group">
						         <label class="col-sm-3 control-label" for="date_of_transaction" form="request_form">Payment Date<span class="text-danger">*</span></label>
						         <div class="col-sm-6"><input value="" name="date_of_transaction" required="" type="text" placeholder="" class="date_of_transaction form-control" id="date_of_transaction" readonly="" data-original-title="" title=""></div>
						      </div>
						      <div class="form-group">
						         <label class="col-sm-3 control-label" for="date_of_transaction" form="request_form">Payment Type<span class="text-danger">*</span></label>
						         <div class="col-sm-6">
						         	<div class="panel_selcts selctmark_dash">
						         		<select id="balance_request_type" name="transaction_type" class="form-control" autocomplete="off">
						         			<option value="CASH_GIVEN" selected="selected">Cash Given</option>
						         			<option value="CASH_BANK_DEPOSIT">Deposited in Bank Account</option>
						         		</select></div>
						         </div>
						      </div>
						      <input name="currency_converter_origin" type="hidden" id="currency_converter_origin" class=" currency_converter_origin hiddenIp" required="" value="61"><input name="conversion_value" type="hidden" id="conversion_value" class=" conversion_value hiddenIp" required="" value="1">
						     
						      <div class="form-group">
						         <label class="col-sm-3 control-label" for="receipt_no" form="request_form">Receipt No<span class="text-danger">*</span></label>
						         <div class="col-sm-6"><input value="" name="receipt_no" required="" type="text" placeholder="" class=" receipt_no form-control" id="receipt_no" data-original-title="" title=""></div>
						      </div>
						      <div class="form-group">
						         <label class="col-sm-3 control-label" for="remarks" form="request_form">Remarks</label>
						         <div class="col-sm-6"><textarea dt="" name="remarks" id="remarks" rows="3" class=" remarks form-control" data-original-title="" title=""></textarea></div>
						      </div>
						   </fieldset>
						   <div class="form-group">
						      <div class="col-sm-8 col-sm-offset-4"> <button type="submit" id="request_form_submit" class=" btn btn-success ">Save</button> <button type="reset" id="request_form_reset" class=" btn btn-warning ">Reset</button></div>
						   </div>
						</form>
							<div><?=@$help_text?></div>
					
					</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane active clearfix" id="tableList">
					<div class="panel_inside">
						<legend class="form_legend">Supplier Payment Details</legend>
						<div class="panel-body">
        <h4>Search Panel</h4>
        <hr>
        <form action="<?=base_url().'supplier_management/payment_details/'?>" method="GET" autocomplete="off"> 
          <div class="clearfix form-group">
            
            <div class="col-xs-4">
            
              <label> Supplier </label> 
              <select class="form-control" name="supplier_name">
              <option>ALL</option>
                <?php
                  foreach($supplier_list as $supplier)
                  {
                    if($supplier_id == $supplier['user_id'])
                    {
                      echo '<option value="'.$supplier['user_id'].'" selected>'.$supplier['first_name'].' '.$supplier['last_name'].'</option>';
                    }
                    else
                    {
                      echo '<option value="'.$supplier['user_id'].'">'.$supplier['first_name'].' '.$supplier['last_name'].'</option>';
                    }
                    
                  }
                ?>
            </select>
          </div>
          <div class="col-xs-4">
            <label> Month </label> 
            <select class="form-control" name="month">
              <option>All</option>
              <?php
                for($i = 1; $i <= 12; $i++)
                {
                  if($month == $i)
                  {
                   echo '<option value="'.$i.'" selected>'.date('F', strtotime('2020-'.$i.'-01')).'</option>';
                  }
                  else
                  {
                    echo '<option value="'.$i.'">'.date('F', strtotime('2020-'.$i.'-01')).'</option>';
                  }
                }

              ?>
            </select>

           <!--  <input type="text" readonly
            id="created_datetime_from" class="form-control"
            name="created_datetime_from" value="<?=@$created_datetime_from?>"
            placeholder="Request Date"> -->
          </div>
          <div class="col-xs-4">
            <label> Year </label> 
            <select class="form-control" name="year">
              <option>All</option>
              <?php
                for($i = 2020; $i <= date('Y'); $i++)
                {
                  if($year == $i)
                  {
                    echo '<option value="'.$i.'" selected>'.$i.'</option>';
                  }
                 else
                 {
                   echo '<option value="'.$i.'">'.$i.'</option>';
                 }
                }

              ?>
            </select>
            <!-- <input type="text" readonly
            id="created_datetime_to"
            class="form-control disable-date-auto-update"
            name="created_datetime_to" value="<?=@$created_datetime_to?>"
            placeholder="Request Date"> -->
          </div>
        </div>
        <div class="col-sm-12 well well-sm">
          <button type="submit" class="btn btn-primary">Search</button>
          <a href="<?=base_url().'supplier_management/report'?>" class="btn btn-warning">Reset</a>
                  
        </div>
      </form>
      
      </div>
				<?php
				/**
				 * ********************** GENERATE CURRENT PAGE TABLE ***********************
				 */
					echo get_table ( $table_data );
				/**
				 * ********************** GENERATE CURRENT PAGE TABLE ***********************
				 */
				?>
					</div>
            </div>
			</div>
		</div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL WRAP END -->
</div>
<script>
$(document).ready(function() {
	$('.balance_request_type').on('change', function() {
		//reload window with new parameter
		var _request_type = $(this).val();
		if (_request_type != '') {
			window.location.href = app_base_url+'index.php/management/master_balance_manager/'+_request_type;
		} else {
			location.reload();
		}
	});
	//Get Branch based on Bank -- Jaganath
	$('#bank_id').change(function(){
		var bank_id = $(this).val().trim();
		if(bank_id!="" && isNaN(bank_id) == false && parseInt(bank_id) >= 1) {
			$('#bank').val($('#bank_id option:selected').text());
			$.get(app_base_url+'index.php/ajax/get_bank_branches/'+bank_id, function(response){
				var bank_branch = '';
				if(response.status == true) {
					bank_branch = response.branch;
				}
				$('#branch').val(bank_branch);
			});
		} else {
			$('#branch').val('');
			$('#bank').val('');
		}
	});

	$('#amount, #ebs_payment_mode, #ebs_card_type').on('change keyup keydown blur', function() {
		var t_charge = 0;
		var amount = parseInt($('#amount').val());
		if (amount > 0) {
			//depending on card type, payment mode and amount set T_CHARGE
			var payment_mode = parseInt($('#ebs_payment_mode').val());
			var card_type = parseInt($('#ebs_card_type').val());
			if (payment_mode != 'INVALIDIP') {
				if ((payment_mode) == 3) {
					//NB - Free
					t_charge = 0;
				} else if (card_type != 'INVALIDIP') {
					if (payment_mode == 1) {// && (card_type == 1 || card_type == 2)
						//CC - 1.80% charge
						var multiplier = 1.80;
						t_charge = ((amount/100)*multiplier);
					} else if (payment_mode == 2) {
						//DC - upto 1l Free
						//- 1l to 2.5l -> 50rs
						//- 2.5l & above -> 100rs
						if (amount > 100000 && amount <= 250000) {
							t_charge = 50
						} else if (amount > 250000) {
							t_charge = 100;
						}
					}
				}
			}
		}
		//Math.ceil
		t_charge = (parseFloat(t_charge).toFixed(2));
		$('#transaction_charges').val(t_charge);
	});

	$('#ebs_payment_mode').on('change', function(){		
		var $card = $('#ebs_card_type');
		var $card_wrapper = $card.closest('.form-group');
		if($(this).val() == 3 || $(this).val() == 100 || $(this).val() == 101) {
			$card_wrapper.hide(0) && $card.val(0).prop('disabled', true);
		} else if ($card_wrapper.is(':hidden')){			
			$card_wrapper.show(0) && $card.prop('disabled', false);
		}
	});
	
	$('#request_form_submit').on('click', function(e) {
		//alert('test');
		var payment_mode = $('#ebs_payment_mode').val(); // != 3
		var card_type = $('#ebs_card_type').val();//this is mandatory and should not be equall to INVALIDIP
		if (parseFloat($('#amount').val()) < 100) {
			e.preventDefault();
			alert('Minimum amount 100');
		}
		if(payment_mode != 3 && card_type == 'INVALIDIP') {
			e.preventDefault();
			alert('Please Select Card.');
			$('#ebs_card_type').focus();
		}
		//e.preventDefault();
	});
});
</script>
<script>
	$("#supplier_id").on('change', function()
	{
	    	var moduler=$(".moduler").val();
	    	if(moduler=="Tour")
	    	{
		get_supplier_price();
	    	}
	    	if(moduler=="hotel")
	    	{
		get_hotel_supplier_price();
	    	}
	    		if(moduler=="Transfer")
	    	{
		get_transfer_supplier_price();
	    	}
	    		if(moduler=="Activity")
	    	{
		get_activity_supplier_price();
	    	}
	});
	$("#paying_month").on('change', function()
	{
	    	var moduler=$(".moduler").val();
	   
	  if(moduler=="Tour")
	    	{
		get_supplier_price();
	    	}
	    	if(moduler=="hotel")
	    	{
		get_hotel_supplier_price();
	    	}
	    		if(moduler=="Transfer")
	    	{
		get_transfer_supplier_price();
	    	}
	    		if(moduler=="Activity")
	    	{
		get_activity_supplier_price();
	    	}
	});
	$("#paying_year").on('change', function()
	{
	    	var moduler=$(".moduler").val();
			if(moduler=="Tour")
	    	{
		get_supplier_price();
	    	}
	    	if(moduler=="hotel")
	    	{
		get_hotel_supplier_price();
	    	}
	    		if(moduler=="Transfer")
	    	{
		get_transfer_supplier_price();
	    	}
	    		if(moduler=="Activity")
	    	{
		get_activity_supplier_price();
	    	}
	});
	function get_supplier_price()
	{
		var supplier_id=$("#supplier_id").val();
		var paying_month=$("#paying_month").val();
		var paying_year=$("#paying_year").val();
		
		if((supplier_id !="") && (paying_month !="") && (paying_year !=""))
		{
			jQuery.ajax({
						type:"POST",
						url:app_base_url+'index.php/supplier_management/get_supplier_price/',
						datatype:"text",
						data:{supplier_id:supplier_id,paying_month:paying_month,paying_year:paying_year},
						success:function(response)
						{
							if(response)
							{
								$("#amount").attr("readonly",false);
								$("#error").css("display","none");		
								$("#amount").val(""+response);
							}
							else
							{
								$("#amount").attr("readonly",true);
								$("#error").css("display","");
							}
						},
						error:function (xhr, ajaxOptions, thrownError){}
						});
		}
	}
		function get_activity_supplier_price()
	{
		var supplier_id=$("#supplier_id").val();
		var paying_month=$("#paying_month").val();
		var paying_year=$("#paying_year").val();
		
		if((supplier_id !="") && (paying_month !="") && (paying_year !=""))
		{
			jQuery.ajax({
						type:"POST",
						url:app_base_url+'index.php/supplier_management/get__activity_supplier_price/',
						datatype:"text",
						data:{supplier_id:supplier_id,paying_month:paying_month,paying_year:paying_year},
						success:function(response)
						{
							if(response)
							{
								$("#amount").attr("readonly",false);
								$("#error").css("display","none");		
								$("#amount").val(""+response);
							}
							else
							{
								$("#amount").attr("readonly",true);
								$("#error").css("display","");
							}
						},
						error:function (xhr, ajaxOptions, thrownError){}
						});
		}
	}
	$('#holiday_table_part2').DataTable({
                dom: 'Bfrtip',
                buttons:  [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0,2,3,4,5,6,7,8 ]
                    ,
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0,2,3,4,5,6,7,8],
                   
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0,2,3,4,5,6,7,8],
                   
                }
            }
        ]
            });
		function get_transfer_supplier_price()
	{
		var supplier_id=$("#supplier_id").val();
		var paying_month=$("#paying_month").val();
		var paying_year=$("#paying_year").val();
		
		if((supplier_id !="") && (paying_month !="") && (paying_year !=""))
		{
			jQuery.ajax({
						type:"POST",
						url:app_base_url+'index.php/supplier_management/get_transfer_supplier_price/',
						datatype:"text",
						data:{supplier_id:supplier_id,paying_month:paying_month,paying_year:paying_year},
						success:function(response)
						{
							if(response)
							{
								$("#amount").attr("readonly",false);
								$("#error").css("display","none");		
								$("#amount").val(""+response);
							}
							else
							{
								$("#amount").attr("readonly",true);
								$("#error").css("display","");
							}
						},
						error:function (xhr, ajaxOptions, thrownError){}
						});
		}
	}
		function get_hotel_supplier_price()
	{
		var supplier_id=$("#supplier_id").val();
		var paying_month=$("#paying_month").val();
		var paying_year=$("#paying_year").val();
		
		if((supplier_id !="") && (paying_month !="") && (paying_year !=""))
		{
			jQuery.ajax({
						type:"POST",
						url:app_base_url+'index.php/supplier_management/get_hotel_supplier_price/',
						datatype:"text",
						data:{supplier_id:supplier_id,paying_month:paying_month,paying_year:paying_year},
						success:function(response)
						{
							if(response)
							{
								$("#amount").attr("readonly",false);
								$("#error").css("display","none");		
								$("#amount").val(""+response);
							}
							else
							{
								$("#amount").attr("readonly",true);
								$("#error").css("display","");
							}
						},
						error:function (xhr, ajaxOptions, thrownError){}
						});
		}
	}
</script>
<!-- HTML END -->
<?php
function get_table($table_data = '') {
	$currency=get_application_default_currency();
	$table = '
	
	
   <div class="table-responsive col-md-12" >
   
   <table class="table table-hover table-striped table-bordered table-condensed" id="holiday_table_part2">';
	$table .= '<thead><tr>
   <th>Sno</th>
   <th>Supplier ID</th>
   <th>Module</th>
   <th>Name</th>
   <th>Email</th>
   <th>Amount Paid</th>
   <th>Amount Paid For</th>
   <th>Payment Type</th>
   <th>Receipt No</th>
   <th>Remarks</th>
   <th>Created At</th>
   </tr></thead><tbody>';
	if (valid_array ( $table_data ) == true) 
	{
		foreach ( $table_data as $k => $v ) 
		{
			$current_request_status = strtoupper ( $v ['status'] );
			$type_of_payment = "";
			
			$table .= '<tr>
			<td>' . ($k + 1) . '</td>
			<td>' .provab_decrypt($v ['uuid']) . '</td>
				<td>' .$v['module']. '</td>
			<td>' . $v ['first_name'].' '.$v['last_name'] . '</td>
			<td>' .provab_decrypt($v ['email']) . '</td>
			<td>' . number_format($v ['amount']) .' '.$currency. '</td>
			<td>' .date('F', mktime(0, 0, 0, $v['payment_for_month'], 10)).' '.$v['payment_for_year'].'</td><td>';
				if($v['transaction_type'] == "CASH_BANK_DEPOSIT")
				{
					$table .='Deposited in Bank Account';
				}
				else if($v['transaction_type'] =="CASH_GIVEN")
				{
					$table .='Cash Given';
				}
			$table .= '</td><td>' . $v ['receipt_no'] . '</td>
			<td>' . $v ['remarks'] . '</td>
			<td>' . app_friendly_absolute_date ( $v ['created_datetime'] ) . '</td>
	</tr>';
		}
	} 
	else {
		$table .= '<tr><td colspan="10">No Records</td></tr>';
	}
	$table .= '</tbody></table></div>';
	return $table;
}
?>

 <script>
         $(function() {
            $( "#date_of_transaction" ).datepicker();
         });
      </script>