<?php
$template_images = $GLOBALS['CI']->template->template_images();
if (@$details['Pickup']['clsPickup'] == true) {
	$CUR_Pickup = force_multple_data_format(@$details['Pickup']['clsPickup']);
} else {
	$CUR_Pickup = '';
}
if (@$details['Dropoff']['clsDropoff'] == true) {
	$CUR_Dropoff = force_multple_data_format(@$details['Dropoff']['clsDropoff']);
} else {
	$CUR_Dropoff = '';
}
$CUR_CancellationCharges = force_multple_data_format($details['CancellationCharges']['clsCancellationCharge']);
?>
<div>
<table class="table table-condensed table-striped">
	<caption><img class="media-object pull-left" src="<?php echo $template_images?>icons/bus-boarding-dropping-point-icon.png" alt="Bus Boarding Dropping Point Icon"><h4>Boarding Point Details</h4></caption>
	<tr>
		<th>Sno</th>
		<th>Pick Up</th>
		<th>Time</th>
		<th>Details</th>
	</tr>
	<?php
	if (valid_array($CUR_Pickup) == true) {
		foreach ($CUR_Pickup as $pk => $pv) {
			?>
	<tr>
		<td><?=($pk+1)?></td>
		<td><?=$pv['PickupName']?></td>
		<td><?=get_time($pv['PickupTime'])?></td>
		<td><?=($pv['Address'])?> <?=($pv['Landmark'])?> <?=($pv['Phone'])?></td>
	</tr>
	<?php
		}
	} else {
		echo '<tr><td colspan="4">NA</td></tr>';
	}
	?>
</table>
</div>
<hr>
<div>
<table class="table table-condensed table-striped">
	<caption><img class="media-object pull-left" src="<?php echo $template_images?>icons/bus-boarding-dropping-point-icon.png" alt="Bus Boarding Dropping Point Icon"><h4>Drop Point Details</h4></caption>
	<tr>
		<th>Sno</th>
		<th>Drop</th>
		<th>Time</th>
		<th>Details</th>
	</tr>
	<?php
	if (valid_array($CUR_Dropoff) == true) {
		foreach ($CUR_Dropoff as $dk => $dv) {
			?>
	<tr>
		<td><?=($dk+1)?></td>
		<td><?=$dv['DropoffName']?></td>
		<td><?=get_time($dv['DropoffTime'])?></td>
		<td><?=($dv['Address'])?> <?=($dv['Landmark'])?> <?=($dv['Phone'])?></td>
	</tr>
	<?php
		}
	} else {
		echo '<tr><td colspan="4">NA</td></tr>';
	}
	?>
</table>
</div>
<?php
include_once 'travelyaari_cancellation_policy.php';
?>