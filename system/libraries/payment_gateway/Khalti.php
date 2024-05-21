<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Khalti
{
    static $sharedSecretKey;
    static $url;
    public $active_payment_system;
    public $book_id = '';
    public $book_origin = '';
    public $pgi_amount = '';
    public $firstname = '';
    public $email = '';
    public $phone = '';
    public $productinfo = '';
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->active_payment_system = $this->CI->config->item('active_payment_system');
    }

    function initialize($data)
    {

        if ($this->active_payment_system == 'test') {
            // self::$sharedSecretKey = 'live_secret_key_f1065b0e5e7d45cc90cdd8b64997a124';
            self::$sharedSecretKey = 'live_secret_key_17daa0221d28431b86b0ed9a74da82d7';
            self::$url = 'https://khalti.com/api/v2/epayment/initiate/';
        } else {
            // self::$sharedSecretKey = 'live_secret_key_f1065b0e5e7d45cc90cdd8b64997a124';
            self::$sharedSecretKey = 'live_secret_key_17daa0221d28431b86b0ed9a74da82d7';
            self::$url = 'https://khalti.com/api/v2/epayment/initiate/';
        }
        $this->book_id = $data['txnid'];
        $this->pgi_amount = $data['pgi_amount'];
        $this->firstname = $data['firstname'];
        $this->email = $data['email'];
        $this->phone = $data['phone'];
        $this->productinfo = $data['productinfo'];
    }
    function process_payment()
    {
        $post_data = array();
        $post_data['txnid'] = $this->book_id;
        $post_data['amount'] = $this->pgi_amount;
        $post_data['firstname'] = $this->firstname;
        $post_data['email'] = $this->email;
        $post_data['phone'] = $this->phone;
        $post_data['productinfo'] = $this->productinfo;
        $post_data['pay_target_url'] = self::$url;
        $post_data['sharedSecretKey'] = self::$sharedSecretKey;
        return $post_data;
    }
}
