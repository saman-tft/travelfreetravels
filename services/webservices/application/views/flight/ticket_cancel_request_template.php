<style>
	th,td{padding:5px;}
</style>
<table style="border-collapse: collapse; background: #ffffff;font-size: 12pt; margin: 0 auto; font-family: arial;" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tbody>
		<tr>		
			<td style="border-collapse: collapse; padding:10px 20px 20px" >
				<table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td style="font-size:15pt; line-height:30px; width:100%; display:block; font-weight:600; text-align:center"></td>
					</tr>
					<tr>
						<td>
							<table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td style="padding: 10px; width:65%"></td>
									<td style="padding: 10px; width:35%">
										<table width="100%" style="border-collapse: collapse;text-align: right; line-height:15px;" cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td style="font-size:12pt;"><span style="width:100%; float:left"></span>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><h4><u>Cancellation Request Details</u></h4></td>
					</tr>
					<tr>
						<td style="padding: 10px;">
							<table cellpadding="5" cellspacing="0" border="0" width="100%" style="border-collapse: collapse;">
								<tr>
									<td width="100%" style="padding: 10px;border: 1px solid #cccccc; font-size: 11pt; font-weight: bold;">Reservation Lookup</td>
								</tr>
								<tr>
									<td style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5" style="padding: 10px;font-size: 11pt;">
											<tr>
												<td><strong>Domain Name</strong></td>
												<td><strong>Booking API</strong></td>
												<td><strong>API Booking ID</strong></td>
												<?php if(isset($booking_transaction_details['pnr']) == true && empty($booking_transaction_details['pnr']) == false){ ?>
												<td><strong>PNR</strong></td>
												<?php }?>
												<td><strong>Booking Reference</strong></td>
											</tr>
											<tr>
												<td><?=$domain_name; ?></td>
												<td><?=$booking_transaction_details['booking_api_name']; ?></td>
												<td><?=$booking_transaction_details['book_id']; ?></td>
												<?php if(isset($booking_transaction_details['pnr']) == true && empty($booking_transaction_details['pnr']) == false){ ?>
												<td><?=$booking_transaction_details['pnr']?></td>
												<?php }?>
												<td><?=$booking_transaction_details['app_reference']; ?></td>
											</tr>
										</table>
									</td>
								</tr>
							 
							</table>
						</td>
					</tr>
					<!-- Passenger Details Starts -->
					<tr>
						<td style="padding: 10px;">
							<table cellpadding="5" cellspacing="0" border="0" width="100%" style="border-collapse: collapse;">
								<tr>
									<td width="100%" style="padding: 10px;border: 1px solid #cccccc; font-size: 11pt; font-weight: bold;">Requested to Cancel Following Passenger Tickets</td>
								</tr>
								<tr>
									<td style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5" style="padding: 10px;font-size: 11pt;">
											<tr>
												<td><strong>Sl.no.</strong></td>
												<td><strong>Passenger Name</strong></td>
												<td><strong>Ticket Number</strong></td>
											</tr>
											<?php foreach ($passenger_ticket_details as $tk => $tv){ ?>
											<tr>
												<td><?=($tk+1); ?></td>
												<td><?=$tv['title'].' '.$tv['first_name'].' '.$tv['last_name'].' - '.$tv['passenger_type']; ?></td>
												<td><?=$tv['TicketNumber']; ?></td>
											</tr>
											<?php }?>
										</table>
									</td>
								</tr>
							 
							</table>
						</td>
					</tr>
					<!-- Passenger Details Ends -->
				</table>
			</td>
		</tr>
	</tbody>	
</table>