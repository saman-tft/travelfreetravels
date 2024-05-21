<!--  The entire list of Checkout fields is available at
 https://docs.razorpay.com/docs/checkout-form#checkout-fields -->
 <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<style type="text/css">
  .razorpay-payment-button {
    display: none;
  }
 
</style>

<?php 

require('config.php');
require('Razorpay.php');
include 'src/Api.php';


use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

// echo"testttt";

// debug($pay_data);exit;

$orderData = [
    'receipt'         => 'Receipt#'.time(),
    'amount'          =>  $pay_data['amount'] * 100, // 2000 rupees in paise
    'currency'        =>  'INR', //'USD',
    'payment_capture' => 1 // auto capture
];
$displayAmount = $amount = $orderData['amount'];
$razorpayOrder = $api->order->create($orderData);
$razorpayOrderId = $razorpayOrder->id;
$CI =& get_instance();
$CI->load->model('module_model');
$CI->module_model->update_razorpayid($pay_data['txnid'],$razorpayOrderId);
$displayAmount = $amount = round($orderData['amount']);

?>
<div class="text" style="text-align: center;color:#ff0000;font-size: 20px;line-height: 102px;">
  <p> *We have rounded off the amount to the closest value</p>
</div>
<form action="<?php echo  base_url(); ?>index.php/payment_gateway/verify/<?=$pay_data['txnid'];?>/<?=$pay_data['productinfo'];?>" method="POST" id="checkout_form">
  
  <!-- Any extra fields to be submitted with the form but not sent to Razorpay data-order_id="order_8ehF77FhMNkmVx" -->

  <!-- data-order_id="<?php echo $razorpayOrderId?>" -->
  <input type="hidden" name="order_id" value="<?=$razorpayOrderId?>">
  <input type="hidden" name="shopping_order_id" value="<?php echo $pay_data['txnid']?>">
  <script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?php echo $pay_data['key']?>"
    data-amount="<?php echo $orderData['amount']?>"
    data-currency="<?php echo $currency; ?>"
    data-name="<?php echo $pay_data['firstname']?>"
   data-prefill.email="<?php echo $pay_data['email']?>"
    data-prefill.contact="<?php echo $pay_data['phone']?>"
    
    > 
  </script>
</form>
<!-- data-theme.close_button="false" -->
<script>

  $(document).ready(function(){
    $('#checkout_form').submit();
    
    setTimeout(function(){
      
    },500);

  });

 /* window.onkeydown = function(e){
    if(e.keyCode === 27){ // Key code for ESC key
        e.preventDefault();
    }
};*/

</script>
<style>
</style>
