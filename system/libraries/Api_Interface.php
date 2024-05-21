<?php

ini_set('memory_limit', '-1');

/**
 * Provab XML Class
 *
 * Handle XML Details
 *
 * @package	Provab
 * @subpackage	provab
 * @category	Libraries
 * @author		Balu A<balu.provab@gmail.com>
 * @link		http://www.provab.com
 */
class Api_Interface {

    /**
     *
     * @param array $query_details - array having details of query
     */
    public function __construct() {
        
    }

    /**
     * Get Domain Balance for Admin
     */
    function rest_service($method, $params = array()) {
       $CI = &get_instance();
        $system = $CI->external_service_system;
        $user_name = $system . '_username';
        $password = $system . '_password';
        $username = $CI->$user_name;
        $password = $CI->$password;
        // debug($system);exit;
        
        
        
        
        
        
     $params = array('domain_key' =>'TMX6244821650276433', 'username' => $username, 'password' => $password, 'system' => $system);
      // debug($params);exit;
        $params['domain_id'] = @$CI->entity_domain_id;

        $url = $CI->external_service;
        // debug($url);exit;
        $ch = curl_init($url . $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        curl_close($ch);
    //debug($res);exit;
        return $res;
    }

    /**
     * get response from server for the request
     *
     * @param $request 	   request which has to be processed
     * @param $url	   	   url to which the request has to be sent
     * @param $soap_action
     *
     * @return xml response
     */
            
    public function get_json_response($url, $request = array(), $header_details) {
        $header = array(
            'Content-Type:application/json',
            'Accept-Encoding:gzip, deflate',
            'x-Username:' . $header_details['UserName'], //Remove password later, sending basic/digest auth
            'x-DomainKey:' . $header_details['DomainKey'],
            'x-system:' . $header_details['system'],
            'x-Password:' . $header_details['Password']//Remove password later, sending basic/digest auth
        );
        /*$header = array(
            'Content-Type:application/json',
            'Accept-Encoding:gzip, deflate',
            'x-Username:' . $header_details['UserName'], //Remove password later, sending basic/digest auth
            'x-DomainKey:' . 'TMX1004111597231027',
            'x-system:' . $header_details['system'],
            'x-Password:' . $header_details['Password']//Remove password later, sending basic/digest auth
        );*/
   //   debug($header);
        
        // $url="http://test.services.travelomatix.com/webservices/index.php/car/service/Search";
     //    debug($url); 
	//	debug($request);
        $ch = curl_init($url);
        // debug($ch);exit;
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // debug($ch);exit;
        $res = curl_exec($ch);
        // debug($res);
        // exit; 
        $res = json_decode($res, true);
        curl_close($ch);
        return $res;
    }
    //added this, function was missing
    public function get_json_response_pay($url, $request = array()) {
        $ch = curl_init($url);
         $header = array(
            'Content-Type:application/json',
            'Accept-Encoding:gzip, deflate',
            'Username:' . NCHL_APP_ID, //Remove password later, sending basic/digest auth
            'Password:' .NCHL_APP_PASSWORD,
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_USERPWD,NCHL_APP_ID . ":" . NCHL_APP_PASSWORD);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //    debug($ch);
        $res = curl_exec($ch);
  //debug($res);
        $res = json_decode($res, true);
               //     debug($res);
                //      debug($url);
        ///   debug($request);
        //   die;
        curl_close($ch);
        return $res;
    }

    public function get_json_response_tripxml($url, $request = array(), $header_details,$token) {
   
        $authorization = "Authorization: Bearer ".$token;
        $header = array(
            'Content-Type: application/json; charset="utf-8"', 
        'Accept-Encoding: gzip,deflate',
            $authorization  //Remove password later, sending basic/digest auth
        );
      $ch = curl_init($url);
    //  debug($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
     //  debug($res);exit;
        
        //$CI->custom_db->insert_record('test', array('test' => $res ,'description'=> 'Response'));
       // debug($res);exit;

      
        $res = json_decode($res, true);
       
        curl_close($ch);

        return $res;
    }

    public function get_json_image_response($url, $json_data = array(), $header_details, $method) {

        $header = array(
            'api-key:07b9b13ecc82ace91324aa816496339d',
            'Content-Type:application/json',
            'Accept:application/json'
        );
        //echo $header_details['DomainKey'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        } elseif ($method == "delete") {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        $headers = curl_getinfo($ch);

        if ($headers['http_code'] != '200') {
            // echo "<pre>";
            // print_r(curl_error($ch));
            exit;
            return false;
        } else {
            $response = json_decode($response, true);
            //echo "<pre/>";
            //print_r($response);exit;
            return $response;
        }
        curl_close($ch);
    }
    
    
    public function get_search_response($url, $request = array(), $header_details) {
        // debug($url);exit;
        //debug($url);debug($request);debug($header_details);
        // $CI = &get_instance();

        //$CI->custom_db->insert_record('test', array('test' => $request,'description'=> $url));
        $header = array(
            'Content-Type:application/json',
            'Accept-Encoding:gzip, deflate',
            'auth_id:' . $header_details['UserName'], //Remove password later, sending basic/digest auth
            'auth_password:' . $header_details['Password'],
        );
       
        $ch = curl_init($url);
        // debug($ch);exit;
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // debug($ch);exit;
        $res = curl_exec($ch);
        
        //$CI->custom_db->insert_record('test', array('test' => $res ,'description'=> 'Response'));
       // debug($res);exit;

      
        $res = json_decode($res, true);
       
        curl_close($ch);

        return $res;
    }

    public function post_json_response($url, $request = array(), $header_details) {
        $header = array(
            'Content-Type:application/json',
            'Accept-Encoding:gzip, deflate',
            'auth_id:' . $header_details['UserName'], //Remove password later, sending basic/digest auth
            'auth_password:' . $header_details['Password'],
        );
       
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        $res = json_decode($res, true);
       
        curl_close($ch);

        return $res;
    }



    /**
     * get response from server for the request
     *
     * @param $request 	   request which has to be processed
     * @param $url	   	   url to which the request has to be sent
     * @param $soap_action
     *
     * @return xml response
     */
    public function debug_get_json_response($url, $request = array(), $header_details) {
        //echo "Url:";debug($url); echo "<br/>Request:";debug($request); echo "<br/>Header:";debug($header_details);
       /* $header = array(
            'Content-Type:application/json',
            'Accept-Encoding:gzip, deflate',
            'x-Username:' . $header_details['UserName'], //Remove password later, sending basic/digest auth
            'x-DomainKey:' . $header_details['DomainKey'],
            'x-system:' . $header_details['system'],
            'x-Password:' . $header_details['Password']//Remove password later, sending basic/digest auth
        ); */
        $header = array(
            'Content-Type:application/json',
            'Accept-Encoding:gzip, deflate',
            'x-Username:' . $header_details['UserName'], //Remove password later, sending basic/digest auth
            'x-DomainKey:' . 'TMX1004111597231027',
            'x-system:' . $header_details['system'],
            'x-Password:' . $header_details['Password']//Remove password later, sending basic/digest auth
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $res = curl_exec($ch);
        //echo $res;exit;
        $res = json_decode($res, true);
        curl_close($ch);
        return $res;
    }

    function get_json_insurance($method, $url, $data = false) {
        
        
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        return curl_exec($curl);
    }

    /**
     * Get xml response from URL for the request
     * @param string $url
     * @param xml	 $request
     */
    public function get_xml_response($url, $request, $convert_to_array = true) {
        // echo $url;echo $request;exit;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'Accept-Encoding:gzip, deflate',));
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$request");

        $xml = curl_exec($ch);
        //debug($xml);exit;
        if ($convert_to_array) {
            $data = Converter::createArray($xml);
        } else {
            $data = $xml;
        }
        return $data;
    }

    public function objectToArray($d) {
        if (is_object($d)) {
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            return array_map(array($this, 'objectToArray'), $d);
        } else {
            return $d;
        }
    }

    public function get_object_response($request_type, $request, $header_details) {

        //echo $request_type; exit;
        /* debug($request_type);
          debug($request);
          debug($header_details);exit; */
        $header = $header_details['header'];
        $credintials = $header_details['credintials'];

        //debug($header_details); exit;

        $_header[] = new SoapHeader("http://provab.com/soap/", 'AuthenticationData', $header, "");
        $client = new SoapClient(NULL, array('location' => $credintials['URL'],
            'uri' => 'http://provab.com/soap/', 'trace' => 1, 'exceptions' => 0));
        try {
            $result = $client->provab_api($request_type, $request, $_header);
            //debug(unserialize(base64_decode($result->GetFareQuoteResult->ProvabAuthKey)));
        } catch (Exception $err) {
            echo "<pre>";
            print_r($err->getMessage());
        }
        //print_r($client->__getLastResponse());
        //echo "<pre>"; print_r($result); exit;

        return $result;
    }
    
    
    public function get_tripxml_search_response($url, $request = array(), $header_details) {
        
        // $CI = &get_instance();

        //$CI->custom_db->insert_record('test', array('test' => $request,'description'=> $url));
        
        $header = array( 
        'Content-Type: application/json; charset="utf-8"', 
        'Accept-Encoding: gzip,deflate',
        'Authorization: Bearer '. $header_details['Token']
        );

        $ch = curl_init($url);
        // debug($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        // debug($res);exit;
        
        //$CI->custom_db->insert_record('test', array('test' => $res ,'description'=> 'Response'));
       // debug($res);exit;

      
        $res = json_decode($res, true);
       
        curl_close($ch);

        return $res;
    }

public function post_tripxml_json_response_sec($url, $request = array(),$header,$tokem) {
        $header = array( 
        'Content-Type: application/json; charset="utf-8"', 
        'Accept-Encoding: gzip,deflate',
        'Authorization: Bearer '. $tokem
        );
    // debug($header);die;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
   //     debug($res);exit;
        $res = json_decode($res, true);
       
        curl_close($ch);

        return $res;
    }
    public function post_tripxml_json_response($url, $request = array(), $header_details) {
        $header = array( 
        'Content-Type: application/json; charset="utf-8"', 
        'Accept-Encoding: gzip,deflate',
        'Authorization: Bearer '. $header_details['Token']
        );
        // debug(json_decode($request));

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        // debug($res);exit;
        $res = json_decode($res, true);
       
        curl_close($ch);

        return $res;
    }

}

// 
