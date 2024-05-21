<?php 
//application/libraries/CreatorJwt.php
    // require BASEPATH . 'libraries/jwt.php';
    ini_set('display_errors',1);
     error_reporting(E_ALL);
    class creatorJwt
    {
       
        public function __construct() {
            // parent::__construct();
          
            
            $this->set_api_credentials();
            
        }
        private $secret = "my-secret-token-to-change-in-production"; 
        private function set_api_credentials() {
       
                $this->header = json_encode([
                                    'typ' => 'JWT',
                                    'alg' => 'HS512'
                                ]);
       
        
        }
        private function base64UrlEncode($text)
        {
            return str_replace(
                ['+', '/', '='],
                ['-', '_', ''],
                base64_encode($text)
            );
        }
        /*************This function generate token private key**************/ 

        /*PRIVATE $key = "my-secret-token-to-change-in-production"; 
        public function GenerateToken($data)
        {    
            // debug();      
            $jwt = jwt::encode($data, $this->key,array('HS256'));
            return $jwt;
        }*/
        

       /*************This function DecodeToken token **************/

        /*public function DecodeToken($token)
        {       

            $decoded = jwt::decode($token, $this->key, array('HS256'));
            $decodedData = (array) $decoded;
            return $decodedData;
        }*/
        
        public function generate_token($payload)
        {
            // debug($this->header);exit;
            $base64UrlHeader = $this->base64UrlEncode($this->header);

            // Encode Payload
            $base64UrlPayload = $this->base64UrlEncode($payload);

            // Create Signature Hash
            $signature = hash_hmac('SHA512', $base64UrlHeader . "." . $base64UrlPayload, $this->secret, true);

            // Encode Signature to Base64Url String
            $base64UrlSignature = $this->base64UrlEncode($signature);

            // Create JWT
            $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

            return $jwt;
        }
        public function decode_token($jwt)
        {
            $tokenParts = explode('.', $jwt);
            $header = base64_decode($tokenParts[0]);
            $payload = base64_decode($tokenParts[1]);
            $signatureProvided = $tokenParts[2];

            // check the expiration time
            $expiration = json_decode($payload)->exp;
            // debug($expiration);exit;
            // echo date('d-m-Y h:i:s',$expiration);
            $curnttime=date('d-m-Y h:i:s');
            $curnttime=strtotime($curnttime);
            if($curnttime > $expiration){
                echo "Token has expired.";
            }
            else
            {
                echo "Token not has expired.";
            }
            /*debug($curnttime);*/
            // $tokenExpired = (Carbon::now()->diffInSeconds($expiration, false) < 0);

            // build a signature based on the header and payload using the secret
            $base64UrlHeader = $this->base64UrlEncode($header);
            $base64UrlPayload = $this->base64UrlEncode($payload);
            $signature = hash_hmac('SHA512', $base64UrlHeader . "." . $base64UrlPayload, $this->secret, true);
            $base64UrlSignature = $this->base64UrlEncode($signature);

            // verify it matches the signature provided in the token
            $signatureValid = ($base64UrlSignature === $signatureProvided);

            echo "Header:\n" . $header . "\n";
            echo "Payload:\n" . $payload . "\n";

            /*if ($tokenExpired) {
                echo "Token has expired.\n";
            } else {
                echo "Token has not expired yet.\n";
            }*/

            if ($signatureValid) {
                echo "The signature is valid.\n";
            } else {
                echo "The signature is NOT valid\n";
            }
        }
        

            // Create the token payload
            





    }




    ?>