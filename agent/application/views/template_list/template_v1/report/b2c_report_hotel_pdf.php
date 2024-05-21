<?php
// error_reporting(E_ALL);
// debug($export_data);exit;

?>
<table class="table table-condensed table-bordered" id="b2c_report_hotel_table">
		<thead>
		<tr>
			<th>Sno</th>
			<th>Reference No</th>
			<th>Confirmation/<br/>Reference</th>
			<th>lead_pax_name</th>
			<th>lead_pax_email</th>
			<th>lead_pax_phone_number</th>
			<th>Hotel Name</th>
			<th>No.of rooms</th>
			<th>No.of Adult</th>
			<th>No.of Child</th>
			<th>city</th>
			<th>check_in</th>
			<th>check_out</th>
			<th>commission_fare</th>
			<th>TDS</th>	
			<th>Admin_markup</th>
			<th>convinence_amount</th>
			<th>gst</th>
			<th>Discount</th>
			<th>grand_total</th>
			<th>booked_on</th>
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
							<td><?=$v['hotel_name']?></td>
							<td><?=$v['total_rooms']?></td>
							<td><?=$v['adult_count']?></td>
							<td><?=$v['child_count']?></td>
							<td><?=$v['hotel_location']?></td>
							<td><?=$v['hotel_check_in']?></td>
							<td><?=$v['hotel_check_out']?></td>
							<td><?=$v['fare']?></td>
							<td><?=$v['TDS']?></td>
							<td><?=$v['admin_markup']?></td>
							<td><?=$v['gst']?></td>
							<td><?=$v['convinence_amount']?></td>
							<td><?=$v['discount']?></td>
							<td><?=$v['grand_total']?></td>
							<td><?=date('d-m-Y', strtotime($v['voucher_date']))?></td>
					</tr>
					<?php
						}
					}
					?>

		</tbody></table>