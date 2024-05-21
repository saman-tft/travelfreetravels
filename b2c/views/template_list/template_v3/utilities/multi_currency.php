<?php
$app_supported_currency = $this->db_cache_api->get_currency(array('k' => 'country', 'v' => array('currency_symbol', 'country')), array('status' => ACTIVE));
$application_preferred_currency = get_application_currency_preference();
 //debug($app_supported_currency);
foreach ($app_supported_currency as $cur_key => $cur_val) {
	$currency = explode(' ',$cur_val);
	if ($currency[0] != ''){
		$symbol = $currency[0];
	}else{
		$symbol = $currency[1];
		}
	if ($application_preferred_currency == $cur_key) {
		$selected_currency = 'active';
	} else {
		$selected_currency = '';
	}
	echo '<li class="currency_li '.$selected_currency.'">
<a href="'.base_url().'utilities/setpreferredcurrency/'.$cur_key.'" class="app-preferred-currency" data-currency="'.$cur_key.'">
	<span class="curncy_img sprte '.strtolower($cur_key).'"></span>
	<span class="name_currency"> '.$cur_key .'</span>
	<span class="side_curency">'.$symbol.'</span>
</a>
		</li>';
}
?>
<script>
/*$(document).ready(function($){
	$('.app-preferred-currency').on('click',function(e){
		// alert("c");
		e.preventDefault();
		var _update_currency_url=$(this).data('currency');
		// alert(_update_currency_url);
		// '.base_url().'utilities/set_preferred_currency/'.$cur_key.'
		$.get('<?php echo base_url()?>utilities/set_preferred_currency/'+_update_currency_url,function(response){
			if(response.status==true){
				$('.wrapper').css('opacity','.1');
				location.reload();
			}
		})

	})});*/
</script>