<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="panel panel-default clearfix"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title"><i class="fa fa-credit-card"></i>  Convenience Fees</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body"><!-- PANEL BODY START -->
			<div class="table-responsive" id="checkbox_div">
			    <!--changes added following form for convenience fee-->
			    <form action="<?php echo base_url() . 'createConvenienceModule' ?>" method="POST">
					<label for="modulename"></label>
					<input name="modulename" id="modulename" type="text">
					<input type="submit" value="Add Module" class="btn btn-primary btn-sm" style="margin-left:20px;">
				</form>
			<form action="" method="POST" autocomplete="off" class="con_fesss_form">
				<table class="table table-striped">
					<tr>
						<th>Sl.No</th>
						<th>Module</th>
						<th>Fees Type</th>
						<th>Fees</th>
						<th>Added Per Pax</th>
						<!--changes added new th for convenience fee-->
						<th>Action</th>
					</tr>
				<?php
				// debug($convenience_fees);exit;
				if (isset($convenience_fees) and valid_array($convenience_fees)) {

					foreach($convenience_fees as $key => $raw_row) {

						if($raw_row['module']=='SIGHTSEEING'){
							
							$raw_row['module'] ='ACTIVITIES';
						}
						

						$table_row = get_table_row($raw_row);
						$sno = ($key+1);
						extract($table_row);

					?>
					<tr>
						<td><?=($sno).($row_origin)?></td>
						<td><?=$module?></td>
						<td><?=$fees_type?></td>
						<td><?=$fees?></td>
							<!--changes changed following for convenience fees-->
						<!--<if($raw_row['value_type'] =='plus'){?>-->
						<!--	<td class="perpax< echo $raw_row['origin'];?>"><$per_pax?></td>-->
						<!--< else {?>-->
						<!--	<td class="perpax<echo $raw_row['origin'];?> hide"><$per_pax?></td>-->
							<?php if ($raw_row['value_type'] == 'plus') { ?>
										<td class="perpax<?php echo $raw_row['origin']; ?>"><?= $per_pax ?></td>
									<?php } else { ?>
										<td class="perpax<?php echo $raw_row['origin']; ?> "></td>
									<?php } ?>
					
						<php } >
						<!--changes added following td for convenience fees-->
								<td>
										<form action="<?php echo base_url() . 'deleteConvinienceFee' ?>" method="POST">
											<input type="hidden" name="origin-del" value="<?php echo $raw_row['origin']; ?>">
											<input type="hidden" name="delreq" value="1">
											<input type="submit" value=Delete class="btn btn-danger btn-sm">
										</form>
									</td>
					</tr>
					<?php
					}//End Of Data Print
					?>
					<tr>
					<td colspan="2">
						<input type="submit" value="Update Convenience Fees" class="btn btn-primary btn-sm">
						<input type="reset" value=Reset class="btn btn-warning btn-sm">
					</td>
					</tr>
				<?php
				} else {
					echo '<tr><td colspan="5">No Data Found</td></tr>';
				}
				?>
				</table>
			</form>
			</div>
		</div><!-- PANEL BODY END -->
	</div><!-- PANEL END -->
</div>

<!-- Page Ends Here -->
<?php
function get_table_row($raw_row)
{
	$data['module'] = '<strong>'.$raw_row['fees'].'</strong> '.$raw_row['module'];
	$data['row_origin'] = '<input type="hidden" value="'.$raw_row['origin'].'" name="origin[]">';
	$data['fees'] = '<input type="text" class="numeric get_val_'.$raw_row['origin'].'" name="value[]" value="'.($raw_row['value']).'" maxlength="4">';
	if (empty($raw_row['per_pax']) == true) {
		$per_pax_no = 'checked="checked"';
		$per_pax_yes = '';
	} else {
		$per_pax_no = '';
		$per_pax_yes = 'checked="checked"';
	}
	$data['per_pax'] = '';
	$data['per_pax'] .= '<label><input type="radio" name="per_pax_'.$raw_row['origin'].'" '.$per_pax_yes.' value="1" > Yes</label>';
	$data['per_pax'] .= '<label><input type="radio" name="per_pax_'.$raw_row['origin'].'" '.$per_pax_no.' value="0" > No</label>';
	
	$data['fees_type'] = '';
	if ($raw_row['value_type'] == 'plus') {
		$perc = '';
		$plus = 'checked="checked"';
	} else {
		$perc = 'checked="checked"';
		$plus = '';
	}
	
	$data['fees_type'] .= '<label class="control-label"><input type="radio" '.$perc.' class="'.$raw_row['origin'].'" name="value_type_'.$raw_row['origin'].'" value="percentage"> perc% </label>';
	$data['fees_type'] .= '<label><input type="radio" '.$plus.' class="'.$raw_row['origin'].'" name="value_type_'.$raw_row['origin'].'" value="plus"> plus+ </label>';
	return $data;
}
?>
<script type="text/javascript">
// $(document).ready(function() {
// 	 $("#checkbox_div input:radio").click(function() {
// 		var value = $(this).val();
// 		var id_value = $(this).attr('class');
// 			if(value == 'percentage'){
				
// 				$('.perpax'+id_value).addClass('hide');
// 				$('.get_val_'+id_value).attr("maxlength","2");
// 			}
// 			else{
				
// 				$('.perpax'+id_value).removeClass('hide');
// 				$('.get_val_'+id_value).attr("maxlength","4");
// 			}
// 		});
	

// });
//changes changed following script for convenience fees
	$(document).ready(function() {
		$("#checkbox_div input:radio").click(function() {
			var value = $(this).val();
			var id_value = $(this).attr('class');
			if (value == 'percentage') {

				$('.perpax' + id_value).html('<td></td>');
				$('.get_val_' + id_value).attr("maxlength", "2");
			} else {

				$('.perpax' + id_value).removeClass('hide').html('<?=$per_pax?>');
				$('.get_val_' + id_value).attr("maxlength", "4");
			}
		});


	});
</script>
