<style>

.referandearn a.addbutton {
    float: none;
    border: none;
    height: 50px;
    padding: 16px 20px;
    border-radius: 10px;
}

input#refercode {
    min-width: 380px;
    height: 50px;
    border: 1px solid #7c7c7c;
    border-radius: 7px;
    color: #acacac;
    padding: 0 10px;
    font-size: 15px;
}
</style>


<div class="dashdiv">
    <div class="alldasbord">
        <div class="userfstep">
            <div class="col-md-12">
                <div class="referandearn">
                    <h3>Refer and Earn Now:</h3>
                    <span><input type="text" id="refercode" name="refercode"
                            value="https://travelfreetravels.com/agent/index.php/user/agentRegister?refercode=<?php echo  $referral_code; ?>"
                            readonly></span>
                    <a class="addbutton" onclick="callCopy()" target="_blank">Copy link</a>
                </div>
            </div>
            <div class="col-md-12 ">

                <?php
						$pending = array_column($reward_booking_report,'pending_rewardpoint');
					?>
                <div class="col-md-12 nopad">
                    <div class="top_boxes">
                        <ul class="top_box_ul">
                            <li>Total Used
                                Rewards : <strong><?php echo $reward_total_report[0]['used_reward'];?></strong></li>
                            <!-- <li>Available Rewards<br><?php echo end($pending);?></li> -->
                            <li>Available Rewards : <strong><?php echo $user['pending_reward'];?></strong></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-12 nopad">
                    <ul class="nav nav-tabs tabssyb">
                        <li class="active"><a data-toggle="tab" href="#menu1">Booking Details</a>
                        </li>
                        <li class="hide"><a data-toggle="tab" href="#menu1">Spend/Redeem
                    </ul>

                    <div id="menu1" class="tab-pane fade in active">
                        <div class="col-md-12 nopad">
                            <?php if($reward_booking_report){ ?>
                            <table class="table table-bordered" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Sl.No</th>
                                        <th>Module</th>
                                        <th>Appreference</th>
                                        <th>Previous</th>
                                        <th style="color:red">Spend</th>
                                        <th style="color:green">Earned Rewards</th>
                                        <th>Date</th>
                                        <th>Redeem points</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
$i=1;
$total_used =0; 
$pending = array_column($reward_booking_report,'pending_rewardpoint');
// debug($reward_booking_report_data);
foreach ($reward_booking_report_data as $key => $value) { ?>
                                    <tr>
                                        <td><?=$i;?></td>
                                        <td><?=$value['module']?></td>
                                        <td><?=$value['book_id']?></td>
                                        <td><?=($value['pending_rewardpoint']+$value['used_rewardpoint'])-$value['reward_earned']?>
                                        </td>
                                        <td style="color:red"><?=$value['used_rewardpoint']?></td>
                                        <td style="color:green"><?=$value['reward_earned']?></td>
                                        <td><?=$value['created']?></td>
                                        <td><?=$value['pending_rewardpoint']?></td>
                                        <td>
                                            <?php
				$function="";
				if($value['module'] =="Flight")
				{
					$function="flight";
				}
				else if($value['module'] =="Hotel")
				{
					$function="hotel";
				}
				else if($value['module'] =="Car")
				{
					$function="car";
				}
				else if($value['module'] =="Activities")
				{
					$function="sightseeing";
				}
				else if($value['module'] =="Transfers")
				{
					$function="transferv1";
				}
				else if($value['module'] =="Holiday")
				{
					$function="voucher";
				}
			?>

                                        </td>
                                        <?php 
		$total_used +=$value['used_rewardpoint']; 
		?>
                                    </tr>
                                    <?php $i++; } 
?>
                                </tbody>
                            </table>

                            <?php }else{ ?>

                            <div class="alert alert-info">
                                <strong>Booking not found</strong>
                            </div>

                            <?php 	} ?>
                        </div>
                    </div>



                    <div class="tab-content" style="padding-top: 20px;display: none">
                        <div id="home" class="tab-pane fade in active">
                            <div class="col-md-12 nopad">
                                <?php if($reward_booking_report){ ?>
                                <table class="table table-bordered" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Booking No</th>
                                            <th>Pending Rewards</th>
                                            <th>Earned Rewards</th>
                                            <th>Used rewards</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
$i=1;
$total_used =0; 
$pending = array_column($reward_booking_report,'pending_rewardpoint');
foreach ($reward_booking_report as $key => $value) { 
// debug($value);
	?>
                                        <tr>
                                            <td><?=$i?></td>
                                            <td><?=$value['pending_rewardpoint']?></td>
                                            <td><?=$value['reward_earned']?></td>
                                            <td><?=$value['used_rewardpoint']?></td>
                                            <?php 
		$total_used +=$value['used_rewardpoint']; 

		?>
                                        </tr>
                                        <?php $i++; } 
?>
                                        <tr>
                                            <td colspan="3"><span class="tot">Total Used
                                                    Rewards</span></td>
                                            <td><span class="tot"><?=$total_used?></span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><span class="tot">Pending Rewards</span>
                                            </td>
                                            <td><span class="tot"><?=end($pending)?></span></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <?php }else{ ?>

                                <div class="alert alert-info">
                                    <strong>Booking not found</strong>
                                </div>

                                <?php 	} ?>
                            </div>
                        </div>
                    </div>







                </div>
            </div>
        </div>
    </div>
</div>


<script>
function callCopy(element) {
    var codeCopy = document.getElementById('refercode');
    codeCopy.select();
    document.execCommand('copy');
}
</script>