<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Esewa
{
    static $sharedSecretKey; //merchant code
    static $secretKey; //secret key
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

        //echo 'hi';exit;
        if ($this->active_payment_system == 'test') {
            // self::$sharedSecretKey = 'EPAYTEST';
            self::$sharedSecretKey = 'NP-ES-TFTPL ';
            // self::$secretKey = '8gBm/:&EnhH.1/q';
            self::$secretKey = 'AAAAAABdDhUYHAVXWRIWCBccEV0xMTUjJw==';
            self::$url = 'https://epay.esewa.com.np/api/epay/main/v2/form ';
        } else {
            //live
            // self::$sharedSecretKey = 'EPAYTEST';
            self::$sharedSecretKey = 'NP-ES-TFTPL ';
            // self::$secretKey = '8gBm/:&EnhH.1/q';
            self::$secretKey = 'AAAAAABdDhUYHAVXWRIWCBccEV0xMTUjJw==';
            self::$url = 'https://epay.esewa.com.np/api/epay/main/v2/form';
        }
        // debug($data); exit();
        $this->book_id = $data['txnid'];
        $this->pgi_amount = $data['pgi_amount'];
        $this->firstname = $data['firstname'];
        $this->email = $data['email'];
        $this->phone = $data['phone'];
        $this->productinfo = $data['productinfo'];
        //echo 'hi2';exit;
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
        $post_data['secretKey'] = self::$secretKey;
        return $post_data;
    }
}
