<?php
// error_reporting(E_ALL);
// debug($export_data);exit;

?>
<table class="table table-condensed table-bordered" id="b2c_report_hotel_table">
		<thead>
		<tr>
			<th>Sl. No.</th>
			<th>Appreference</th>
			<th>Lead Pax Name</th>
			<th>lead_pax_email</th>
			<th>lead_pax_phone_number</th>
			<th>PNR</th>
			<th>operator</th>
			<th>From</th>
			<th>To</th>
			<th>Seat Type</th>
			<th>commision Fare</th>
			<th>Netfare</th>
			<th>Admin_markup</th>
			<th>Agent_markup</th>	
			<th>Admin_tds</th>
			<th>Agent_tds</th>
			<th>Admin_commission</th>
			<th>Agent_commission</th>
			<th>Price Deducted From Agent</th>
			<th>Total Price</th>
			<?php
				if($record_type=="all"){

			?>
			<th>Status</th>
			<?php
				}
			?>
			<th>Booked On</th>
		</tr>

		</thead>
		<tbody>
			<?php

				// debug($export_data);exit;
				if(!empty($export_data))
				{
					$i=1;

					foreach($export_data as $key => $v) {
						// debug($v);
							
					?>
					<tr>
							 <td><?=$i?></td>
							<td><?=$v['app_reference']?></td>
							<td><?=$v['lead_pax_name']?></td>
							<td><?=$v['lead_pax_email']?></td>
							<td><?=$v['lead_pax_phone_number']?></td>
							<td><?=$v['pnr']?></td>
							<td><?=$v['operator']?></td>
							<td><?=$v['departure_from']?></td>
							<td><?=$v['arrival_to']?></td>
							<td><?=$v['bus_type']?></td>
							<td><?=$v['fare']?></td>
							<td><?=$v['admin_buying_price']?></td>
							<td><?=$v['admin_tds']?></td>
							<td><?=$v['admin_buying_price']?></td>
							<td><?=$v['convinence_amount']?></td>
							<td><?=$v['admin_markup']?></td>
							<td><?=$v['agent_markup']?></td>
							<td><?=$v['admin_commission']?></td>
							<td><?=$v['agent_commission']?></td>
							<td><?=$v['admin_tds']?></td>
							<td><?=$v['agent_tds']?></td>
							<td><?=$v['agent_buying_price']?></td>
							<td><?=$v['grand_total']?></td>
							<?php
								if($record_type=="all"){

							?>
							<td><?=$v['status']?></td>
							<?php
								}
							?>
							<td><?= date('d-m-Y', strtotime($v['booked_date']))?></td>
					</tr>
					<?php
						}
					}
					?>

		</tbody></table>