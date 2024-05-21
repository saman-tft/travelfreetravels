<?php
// error_reporting(E_ALL);
// debug($export_data);exit;

?>
<table class="table table-condensed table-bordered" id="b2c_report_hotel_table">
		<thead>
		<tr>

			<th>Sl. No.</th>
			<th>APP reference</th>
			<th>Agency Name</th>
			<th>Lead Pax Name</th>
			<th>Lead Pax Email</th>
			<th>Lead Pax Phone</th>
			<th>Activity Name</th>
			
			<th>Acitvity Location</th>
			<th>BookedOn</th>	
			<th>JourneyDate</th>
			<th>Confirmation Reference</th>
			<th>Commission Fare</th>
			<th>Commission</th>
			<th>TDS</th>
			<th>Admin NetFare</th>
			<th>Admin Profit</th>
			<th>Admin Markup</th>
			<th>Agent Commission</th>
			<th>Agent TDS</th>
			<th>Agent Net Fare</th>
			<th>Agent Markup</th>
			<th>GST</th>
			<?php
		        if($record_type=="all"){

		      ?>
		      <th>Status</th>
		      <?php
		        }
		      ?>
			<th>TotalFare</th>
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
							<td><?=$v['agency_name']?></td>
							<td><?=$v['lead_pax_name']?></td>
							<td><?=$v['lead_pax_email']?></td>
							<td><?=$v['lead_pax_phone_number']?></td>
							<td><?=$v['product_name']?></td>
							<td><?=$v['destination_name']?></td>
							<td><?=$v['voucher_date']?></td>
							<td><?=$v['travel_date']?></td>
							<td><?=$v['confirmation_reference']?></td>
							<td><?=$v['fare']?></td>
							
							<td><?=$v['net_commission']?></td>
							<td><?=$v['net_commission_tds']?></td>
							<td><?=$v['net_fare']?></td>
							<td><?=$v['admin_commission']?></td>							
							<td><?=$v['admin_markup']?></td>
							<td><?=$v['agent_commission']?></td>
							<td><?=$v['agent_tds']?></td>
							<td><?=$v['agent_buying_price']?></td>
							<td><?=$v['agent_markup']?></td>
							<td><?=$v['gst']?></td>
							<?php
				                if($record_type=="all"){

				              ?>
				              <td><?=$v['status']?></td>
				              <?php
				                }
				              ?>
							<td><?= $v['grand_total']?></td>
					</tr>
					<?php
						}
					}
					?>

		</tbody></table>