<style type="text/css">
	.disabled_active {
	    pointer-events:none; //This makes it not clickable
	    opacity:0.6;         //This grays it out to look disabled
	}
</style>
<?php 
//debug($current);exit;
// debug($active);exit;
	$step1_active='disabled_active';
	$step2_active='disabled_active';
	$step3_active='disabled_active';
	$step4_active='disabled_active';
	$step5_active='disabled_active';
	$step1_current='';
	$step2_current='';
	$step3_current='';
	$step4_current='';
	$step5_current='';
	foreach ($active as $akey => $avalue) {
		if($avalue=='step1')
		{
			$step1_active='active';
			
		}
		if($avalue=='step2')
		{
			$step2_active='active';

		}
		if($avalue=='step3')
		{

			$step3_active='active';
		}
		if($avalue=='step4')
		{

			$step4_active='active';
		}
		// echo $avalue;
		if($avalue=='step5')
		{

			$step5_active='active';
		}
	}
	// debug($current);exit;
	// debug($step5_active);die;
	if($current=='step1')
	{
		$step1_current='current';
		
	}
	if($current=='step2')
	{
		$step2_current='current';

	}
	if($current=='step3')
	{

		$step3_current='current';
	}
	if($current=='step4')
	{

		$step4_current='current';
	}
	if($current=='step5')
	{

		$step5_current='current';
	}
	// debug($step5_current);debug($step5_active);exit;
?>


<div class="card card-page stepsBar-wrap">
	<div class="card-body">
		<ul class="stepsBar">
			<?php 
			if(!isset($hotel_id) || empty($hotel_id)){
				?>
				<li id="step1" class="active current"><a href="<?=base_url()?>index.php/hotels/add_hotel"><i class="fal fa-file-alt"></i><i class="icon-Check-Circle"></i><h3>Add Hotel </h3></a></li>
				<li id="step2"><a href="javascript:;"><i class="glyphicon glyphicon-user"></i><i class="icon-Check-Circle"></i><h3>Add Age groups</h3></a></li>
				<li id="step3"><a href="javascript:;"><i class="glyphicon glyphicon-home"></i><i class="icon-Check-Circle"></i><h3>Room details</h3></a></li>
				<li id="step4"><a href="javascript:;"><i class="glyphicon glyphicon-tree-conifer"></i><i class="icon-Check-Circle"></i><h3>Seasons</h3></a></li>
				<li id="step5"><a href="javascript:;"><i class="glyphicon glyphicon-usd"></i><i class="icon-Check-Circle"></i><h3>Add Price</h3></a></li>
			<?php
			}else{?>

			<li id="step1" class="<?=$step1_active?> <?=$step1_current?> "><a href="<?=base_url()?>index.php/hotels/edit_hotel/<?=base64_encode(json_encode($hotel_id))?>"><i class="fal fa-file-alt"></i><i class="icon-Check-Circle"></i><h3>Add Hotel </h3></a></li>
			
			<li id="step2" class="<?=$step2_active?> <?=$step2_current?> "><a href="<?=base_url()?>index.php/hotels/manage_hotelchildgroup/<?=base64_encode(json_encode($hotel_id))?>"><i class="glyphicon glyphicon-user"></i><i class="icon-Check-Circle"></i><h3>Add Age groups</h3></a></li>
			<li id="step3"  class="<?=$step3_active?> <?=$step3_current?>"><a href="<?=base_url()?>index.php/hotels/hotel_room_types/<?=base64_encode(json_encode($hotel_id))?>"><i class="glyphicon glyphicon-home"></i><i class="icon-Check-Circle"></i><h3>Room details</h3></a></li>
			<li id="step4" class="<?=$step4_active?> <?=$step4_current?>"><a href="<?=base_url()?>index.php/seasons/seasons_list/<?=base64_encode(json_encode($hotel_id))?>"><i class="glyphicon glyphicon-tree-conifer"></i><i class="icon-Check-Circle"></i><h3>Seasons</h3></a></li>
			<li id="step5" class="<?=$step5_active?> <?=$step5_current?>"><a href="<?=base_url()?>index.php/roomrate/list_room_rate/<?=base64_encode(json_encode($hotel_name))?>"><i class="glyphicon glyphicon-usd"></i><i class="icon-Check-Circle"></i><h3>Add Price</h3></a></li>
		  <?php 
			}
		  ?>
		</ul>
	</div>
</div>
<div class="clearfix"></div>

