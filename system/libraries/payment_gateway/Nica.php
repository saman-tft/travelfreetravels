<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Nica
{
    private $url = NICA_URL;
    private $secretKey = NICA_SECRET_KEY;
    private $accessKey = NICA_ACCESS_KEY;
    private $profileId = NICA_PROFILE_ID;

    private $transactionType;
    private $addressCountry;
    private $locale;
    private $paymentMethod;
    private $cardType;

    public $signedDateTime = '';
    public $signature = '';
    public $currency = '';
    public $uuid = '';
    public $refNumber = '';
    public $firstName = '';
    public $lastName = '';
    public $email = '';
    public $phone = '';
    public $pgi_amount = '';
    public $addressLine1 = '';
    public $addressCity = '';
    public $addressState = '';
    public $addressPostalCode = '';

    public $cardNumber = '';
    public $cardExpiryDate = '';

    public $signed_field_names = '';
    public $unsigned_field_names = '';

    public $productinfo = '';

    private function getGMTDate()
    {
        return gmdate("Y-m-d\TH:i:s\Z");
    }

    function initialize($data)
    {


        $this->transactionType = 'sale';
        $this->addressCountry = 'NP';
        $this->paymentMethod = 'card';
        $this->cardType = '001';
        $this->cardNumber = '';
        $this->cardExpiryDate = '';
        $this->currency = 'NPR';
        $this->locale = 'en';
        $this->signedDateTime = $this->getGMTDate();
        $this->pgi_amount = $data['pgi_amount'];
        // $this->pgi_amount = 1;
        $this->productinfo = $data['productinfo'];
        $this->uuid = $data['txnid'];
        $this->refNumber = $data['txnid'];
        list($this->firstName, $this->lastName) = explode(" ", $data['firstname'], 2);
        $this->email = $data['email'];
        $this->phone = $data['phone'];
        $this->addressLine1 = 'N/A';
        $this->addressCity = 'N/A';
        $this->addressState = 'N/A';
        $this->addressPostalCode = 'N/A';
        $this->signed_field_names = 'access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency,payment_method,bill_to_forename,bill_to_surname,bill_to_email,bill_to_phone,bill_to_address_line1,bill_to_address_city,bill_to_address_state,bill_to_address_country,bill_to_address_postal_code';
        $this->unsigned_field_names = 'card_type,card_number,card_expiry_date';

        $signed_field_values = [
            $this->accessKey,
            $this->profileId,
            $this->uuid,
            $this->signed_field_names,
            $this->unsigned_field_names,
            $this->signedDateTime,
            $this->locale,
            $this->transactionType,
            $this->refNumber,
            $this->pgi_amount,
            $this->currency,
            $this->paymentMethod,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->phone,
            $this->addressLine1,
            $this->addressCity,
            $this->addressState,
            $this->addressCountry,
            $this->addressPostalCode
        ];

        $this->signature = $this->confirm($this->signed_field_names, $signed_field_values);
    }

    public function confirm($signed_field_names, $signed_field_values)
    {
        $signed_array = explode(',', $signed_field_names);
        $signed_string = '';
        foreach ($signed_array as $key => $value) {
            $key_val = $value . '=' . $signed_field_values[$key];
            if ($key == 0)
                $signed_string = $key_val;
            else
                $signed_string = $signed_string . ',' . $key_val;
        }
        $hash_code = hash_hmac('sha256', $signed_string, $this->secretKey, true);
        $hash_encode = base64_encode($hash_code);
        return $hash_encode;
    }

    function process_payment()
    {
        $post_data = array();
        $post_data['productinfo'] = $this->productinfo;
        $post_data['url'] = $this->url;
        $post_data['accessKey'] = $this->accessKey;
        $post_data['profileId'] = $this->profileId;
        $post_data['signedDateTime'] = $this->signedDateTime;
        $post_data['amount'] = $this->pgi_amount;
        $post_data['refNumber'] = $this->refNumber;
        $post_data['transactionType'] = $this->transactionType;
        $post_data['addressCountry'] = $this->addressCountry;
        $post_data['locale'] = $this->locale;
        $post_data['paymentMethod'] = $this->paymentMethod;
        $post_data['cardType'] = $this->cardType;
        $post_data['cardNumber'] = $this->cardNumber;
        $post_data['cardExpiryDate'] = $this->cardExpiryDate;
        $post_data['currency'] = $this->currency;
        $post_data['uuid'] = $this->uuid;
        $post_data['firstName'] = $this->firstName;
        $post_data['lastName'] = $this->lastName;
        $post_data['email'] = $this->email;
        $post_data['phone'] = $this->phone;
        $post_data['addressLine1'] = $this->addressLine1;
        $post_data['addressCity'] = $this->addressCity;
        $post_data['addressState'] = $this->addressState;
        $post_data['addressPostalCode'] = $this->addressPostalCode;
        $post_data['signature'] = $this->signature;
        $post_data['signed_field_names'] = $this->signed_field_names;
        $post_data['unsigned_field_names'] = $this->unsigned_field_names;
        return $post_data;
    }
}
