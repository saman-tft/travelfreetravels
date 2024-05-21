<?php
// error_reporting(E_ALL);
// debug($export_data);exit;

?>
<table class="table table-condensed table-bordered" id="b2c_report_hotel_table">
		<thead>
		<tr>

			<th>Sl. No.</th>
			<th>APP reference</th>
			<th>Confirmation_Reference</th>
			<th>Lead Pax Name</th>
			<th>Lead Pax Email</th>
			<th>Lead Pax Phone</th>
			<th>Product Name</th>
			<th>No of Adults</th>
			<th>No of Child</th>
			<th>No of youth</th>
			<th>No of Senior</th>
			<th>No of infant</th>
			<th>City</th>
			<th>Travel Date</th>	
			<th>Commission Fare</th>
			<th>Commission</th>
			<th>Tds</th>
			<th>Admin NetFare</th>
			<th>Admin Markup</th>
			<th>Convinence amount</th>
			<th>GST</th>
			<th>Discount</th>
			<th>Customer Paid amount</th>
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
							<td><?=$v['confirmation_reference']?></td>
							<td><?=$v['lead_pax_name']?></td>
							<td><?=$v['lead_pax_email']?></td>
							<td><?=$v['lead_pax_phone_number']?></td>
							<td><?=$v['product_name']?></td>
							<td><?=$v['adult_count']?></td>
							<td><?=$v['child_count']?></td>
							<td><?=$v['youth_count']?></td>
							<td><?=$v['senior_count']?></td>
							<td><?=$v['infant_count']?></td>
							<td><?=$v['cutomer_city']?></td>
							<td><?=$v['travel_date']?></td>
							<td><?=$v['fare']?></td>
							<td><?=$v['admin_commission']?></td>
							<td><?=$v['admin_tds']?></td>
							<td><?=$v['admin_net_fare']?></td>
							<td><?=$v['admin_markup']?></td>
							<td><?=$v['convinence_amount']?></td>
							<td><?=$v['gst']?></td>
							<td><?=$v['discount']?></td>
							<td><?=$v['grand_total']?></td>
							<td><?= date('d-m-Y', strtotime($v['voucher_date']))?></td>
					</tr>
					<?php
						}
					}
					?>

		</tbody></table>