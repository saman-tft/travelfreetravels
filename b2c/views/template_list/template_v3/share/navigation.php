<?php
$active_domain_modules = $GLOBALS ['CI']->active_domain_modules;
$master_module_list = $GLOBALS ['CI']->config->item ( 'master_module_list' );
if (empty ( $default_view )) {
	$default_view = $GLOBALS ['CI']->uri->segment ( 2 );
}
?>
<ul id="myTab" role="tablist" class="nav nav-tabs  central_tab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
	<?php
		//debug($master_module_list);exit;
		foreach ( $master_module_list as $k => $v ) {
			if (in_array ( $k, $active_domain_modules )) {
				// if($v != 'holidays') {//FIXME: remove later
			?>
		
			<li
				class="<?=((@$default_view == $k || $default_view == $v) ? 'active' : '')?>"><a
				href="<?php echo base_url()?>index.php/report/<?php echo ($v)?>"> <?php echo ucfirst($v)?></a>
			</li>
	
	<?php
		/*}*/
		}
		}
		?>
		<!--<li  class="<?=((@$default_view =="b2b_reward_point_manager") ? 'active' : '')?>"><a href="https://www.travelfreetravels.com/index.php/management/b2b_reward_point_manager">Reward Points</a></li>
		<li class="<?=((@$default_view == $k || $defaul) ? 'active' : '')?>"><a href="https://www.travelfreetravels.com/index.php/loyalty_program/redeem_reward_point"> Redeem reward point</a></li>	-->	
</ul>