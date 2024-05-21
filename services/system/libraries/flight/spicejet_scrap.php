<?php
require_once BASEPATH . 'libraries/flight/Common_api_flight.php';
class Spicejet_scrap extends Common_Api_Flight {

    protected $operator = 'Spicejet';
    protected $source_code;
    protected $carrier_code;
    protected $AGPKey;
    protected $QPKey;
    protected $ReportsKey;
    protected $domain_origin;

    function __construct($code) {
        $CI = &get_instance();
        error_reporting(0);
        $this->domain_origin = get_domain_auth_id();
        parent::__construct(META_AIRLINE_COURSE, $code);

        $CI = &get_instance();
        $CI->load->library('converter');
        if (empty($this->AGPKey) == true) {
            $this->signature = $this->read_session_id($this->source_code);
            if (empty($this->signature) == TRUE) {

                // $this->login ();
            }
        }
    }

    /*
     * Process Agent Login
     */

    // public function login() {
    public function get_authentication_request() {

        $status = false;
        $CI = & get_instance();
        
        //$signature = $this->read_session_id($this->source_code);

        if (isset($this->config['agent_id'])) {
           
            $username = trim($this->config ['agent_id']);
            $password = trim($this->config ['password']);
            $url = $this->config ['end_point'] ['session'];
            $url = 'https://book.spicejet.com/LoginAgent.aspx';
            $__RequestVerificationToken = time() . 'INZ' . rand(0, 10000);
            $postinfo = '__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=%2FwEPDwUBMGRkMSHDB101%2B52k3J%2FFmXSyR78uK%2Fg%3D&pageToken=&ControlGroupLoginAgentView%24AgentLoginView%24TextBoxUserID=' . $username . '&ControlGroupLoginAgentView%24AgentLoginView%24PasswordFieldPassword=' . $password . '&ControlGroupLoginAgentView%24AgentLoginView%24ButtonLogIn=Log+In';
           // echo $postinfo;exit;
            /*$postinfo = '__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=%2FwEPDwUBMGRkMSHDB101%2B52k3J%2FFmXSyR78uK%2Fg%3D&pageToken=&
                AvailabilitySearchInputAgentView%24RadioButtonMarketStructure=RoundTrip&AvailabilitySearchInputAgentView%24TextBoxMarketOrigin1=&originStation1=&AvailabilitySearchInputAgentView%24TextBoxMarketDestination1=&destinationStation1=&AvailabilitySearchInputAgentView%24DropDownListMarketDay1=10&AvailabilitySearchInputAgentView%24DropDownListMarketMonth1=2017-11&AvailabilitySearchInputAgentView%24DropDownListMarketDateRange1=0%7C0&custom_date_picker=10-11-2017&date_picker=10-11-2017&AvailabilitySearchInputAgentView%24TextBoxMarketOrigin2=&originStation2=&AvailabilitySearchInputAgentView%24TextBoxMarketDestination2=&destinationStation2=&AvailabilitySearchInputAgentView%24DropDownListMarketDay2=17&AvailabilitySearchInputAgentView%24DropDownListMarketMonth2=2017-11&AvailabilitySearchInputAgentView%24DropDownListMarketDateRange2=0%7C0&custom_date_picker=17-11-2017&date_picker=17-11-2017&AvailabilitySearchInputAgentView%24DropDownListPassengerType_ADT=1&AvailabilitySearchInputAgentView%24DropDownListPassengerType_CHD=0&AvailabilitySearchInputAgentView%24DropDownListPassengerType_INFANT=0&AvailabilitySearchInputAgentView%24DropDownListCurrency=none&
                ControlGroupLoginAgentView%24AgentLoginView%24TextBoxUserID=BLRAS00864&ControlGroupLoginAgentView%24AgentLoginView%24PasswordFieldPassword=Travelo%402017%40&ControlGroupLoginAgentView%24AgentLoginView%24ButtonLogIn=Log+In&
                ControlGroupLoginAgentView%24SubAgentLoginView%24TextBoxUserID=&ControlGroupLoginAgentView%24SubAgentLoginView%24TextBoxSubAgentUserId=&ControlGroupLoginAgentView%24SubAgentLoginView%24PasswordFieldPassword=';*/
    
            //$postinfo ='__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=%2FwEPDwUBMGRkMSHDB101%2B52k3J%2FFmXSyR78uK%2Fg%3D&pageToken=&ControlGroupLoginAgentView%24AgentLoginView%24TextBoxUserID=BLRAS00864&ControlGroupLoginAgentView%24AgentLoginView%24PasswordFieldPassword=rwrtetrtyr&ControlGroupLoginAgentView%24AgentLoginView%24ButtonLogIn=Log+In&ControlGroupLoginAgentView%24SubAgentLoginView%24TextBoxUserID=&ControlGroupLoginAgentView%24SubAgentLoginView%24TextBoxSubAgentUserId=&ControlGroupLoginAgentView%24SubAgentLoginView%24PasswordFieldPassword=';
            $header_details = array();

          
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);            
       
            curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:42.0) Gecko/20100101 Firefox/42.0' );
            curl_setopt ( $ch, CURLOPT_HEADER, 0 );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt ( $ch, CURLOPT_POST, 1 );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postinfo );
            $cookie_file = 'application/cache/'.$this->source_code.'SG.txt';

            curl_setopt ( $ch, CURLOPT_COOKIEJAR, $cookie_file );
            curl_setopt ( $ch, CURLOPT_COOKIEFILE, $cookie_file );
            $content = curl_exec ( $ch );
    
            $cookies = array ();

            preg_match_all ( '/Set-Cookie?\s{0,}.*)$/im', $content, $cookies );
            // file_put_contents ( $cookie_file, serialize ( $cookies ['cookie'] ) );
            $search_data = array();


            $data = $this->login_service($url, $postinfo, $header_details);
            $headers = explode("\n", $data);
            // echo "<pre>";print_r($data);exit;
            $cookie = $headers[6];
            $cookie1 = explode(': ', $cookie);
            $strCookie = substr($cookie1[1], 0, -11);
            // debug($cookie1);exit;
            $cookie_arr = array('value' => $strCookie);
            $CI->db->empty_table('sg_cookie');
            $CI->db->insert('sg_cookie', $cookie_arr);
          
            $search_data = $this->get_login_search_service($postinfo);
           // echo "--------------";
            //debug($search_data);
           // echo "Results";
            //debug($data);die;

            if (isset($data) && !empty($data)) {
                $_doc = new DOMDocument ();
                $_doc->loadHTML($data);

                $classname = "UserWelcome";
                $_xpath = new DomXPath($_doc);
                $_pag = $_xpath->query("//div[@class='" . $classname . "']");

                if (isset($_pag->length) && $_pag->length > 0) {
                    $div = $_pag->item(0);
                    $inputs = $div->getElementsByTagName('input');

                    $input_cnt = 1;
                    if (isset($inputs->length) && $inputs->length > 0) {
                        foreach ($inputs as $__ik => $input_k) {
                            $input_nema = $input_k->getAttribute('name');
                            $input_value = $input_k->getAttribute('value');
                            if (isset($input_nema) && !empty($input_nema)) {
                                switch ($input_nema) {
                                    case 'AGPKey' :
                                        $this->AGPKey = $input_value;
                                        break;
                                    case 'QPKey' :
                                        $this->QPKey = $input_value;
                                        break;
                                    case 'ReportsKey' :
                                        $this->ReportsKey = $input_value;
                                        break;
                                }
                            }
                        }
                        if (isset($this->AGPKey) && !empty($this->AGPKey) && isset($this->QPKey) && !empty($this->QPKey) && isset($this->ReportsKey) && !empty($this->ReportsKey)) {
                            $signature = array(
                                'AGPKey' => $this->AGPKey,
                                'QPKey' => $this->QPKey,
                                'ReportsKey' => $this->ReportsKey
                            );
                            $signature = json_encode($signature);

                            $this->save_session_id($signature, $this->source_code);
                            $status = true;
                        }
                    }
                }

                // echo 'inside';
                // $ret = $data->find('div[AGPKey]');
                // debug($ret);exit;
//              $status = true;
            } else {
                $status = false;
            }
        } else {
            $status = true;
        }
        $this->signature = $signature;
        // debug($status);exit;
        return $status;
    }
    public function get_login_search_service($request){
        $header = array ();
       $header [] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
        $header [] ="Accept-Language: en-US,en;q=0.5";
       // $header [] = "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header [] = "Cache-Control: max-age=0";
        $header [] = "Connection: keep-alive";
        $header [] = "Keep-Alive: 300";
        $header [] = "Upgrade-Insecure-Requests: 1";
        $header [] = "Content-Type: application/x-www-form-urlencoded"; 
        $url = "https://book.spicejet.com/Search.aspx";   
        $ch = curl_init ( $url );
       // debug($header);exit;
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        //curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_ENCODING, "gzip,deflate,br" );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
//      $cookie_file = 'spjt'.$this->source_code.'.txt';
        $cookie_file = 'application/cache/'.$this->source_code.'SG.txt';
        curl_setopt ( $ch, CURLOPT_COOKIEJAR, $cookie_file );
        curl_setopt ( $ch, CURLOPT_COOKIEFILE, $cookie_file );
       // curl_setopt ( $ch, CURLOPT_POSTFIELDS, $request );
        curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt ( $ch, CURLOPT_VERBOSE, true );
        $res = curl_exec ( $ch );
        // debug(curl_getinfo($ch));
        // debug(curl_error($ch));
        // echo "--------------";
        // debug($res);
       // exit;
        curl_close ( $ch );
        return $res;
    }
    public function get_login_search_service_old($request){
         $header = array ();
        $header [] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
      //  $header [0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        //$header [] = "Cache-Control: max-age=0";
       
        $header [] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header [] = "Accept-Language: en-us,en;q=0.5";
        $header [] ="Accept-Encoding: gzip, deflate, br";
        //$header [] ="Upgrade-Insecure-Requests: 1";
        $header [] ="Referer: https://book.spicejet.com/LoginAgent.aspx";       
        //$header [] ="Pragma: no-cache";
       //$header [] = "Pragma: ";
      //  $header = array ();     

        $header [] ="Content-Type:text/html;charset=utf-8";
        $header [] = "Connection:keep-alive";
        $header [] ="Content-Length:100000";
        $url = "https://book.spicejet.com/Search.aspx";
       
        $ch_new = curl_init ( $url );
        curl_setopt ( $ch_new, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt ( $ch_new, CURLOPT_SSL_VERIFYPEER, 0 );
       // curl_setopt ( $ch_new, CURLOPT_POST, 1 );
       // curl_setopt ( $ch_new, CURLOPT_ENCODING, "gzip" );
        
        curl_setopt ( $ch_new, CURLOPT_HTTPHEADER, $header );
//      $cookie_file = 'spjt'.$this->source_code.'.txt';
        $cookie_file = 'application/cache/'.$this->source_code.'SG.txt';
       curl_setopt ( $ch_new, CURLOPT_COOKIEJAR, $cookie_file );
       curl_setopt ( $ch_new, CURLOPT_COOKIEFILE, $cookie_file );
        //curl_setopt ( $ch_new, CURLOPT_POSTFIELDS, json_encode(array()) );
        curl_setopt ( $ch_new, CURLOPT_AUTOREFERER, true );
        curl_setopt ( $ch_new, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch_new, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt ( $ch_new, CURLOPT_VERBOSE, true );
        $res = curl_exec ( $ch_new );
        // echo $res;exit;
        debug(curl_getinfo($ch_new));
        debug(curl_error($ch_new));
        echo "---------------------";
        debug($res);exit;
        curl_close ( $ch_new );
        
        return $res;
    }
    public function login_service($url, $request = array(), $header_details) {
        $CI = &get_instance();
        $header = array ();
        $header [0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header [0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header [] = "Cache-Control: max-age=0";
        $header [] = "Connection: keep-alive";
        $header [] = "Keep-Alive: 300";
        $header [] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header [] = "Accept-Language: en-us,en;q=0.5";
        $header [] = "Pragma: ";

        $ch = curl_init ( $url );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_ENCODING, "gzip,deflate,br" );

        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt ( $ch, CURLOPT_HEADER, 1 );
        $cookie = $CI->flight_model->get_cookie_value();
        $cookie_file = 'application/cache/'.$this->source_code.'SG.txt';
        //curl_setopt ( $ch, CURLOPT_COOKIEFILE, $cookie_file );
        curl_setopt ( $ch, CURLOPT_COOKIEJAR, $cookie );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $request );
        curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt ( $ch, CURLOPT_VERBOSE, true );
        $res = curl_exec ( $ch );
       
        curl_close ( $ch );
        return $res;
    }
    public function login_service_old($url, $request = array(), $header_details) {
        $header = array();
        $header [] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
        $header [] ="Accept-Language: en-US,en;q=0.5";
       // $header [] = "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header [] = "Cache-Control: max-age=0";
        $header [] = "Connection: keep-alive";
        $header [] = "Keep-Alive: 300";
        $header [] = "Upgrade-Insecure-Requests: 1";
        $header [] = "Content-Type: application/x-www-form-urlencoded";     
        
      //  $header = array();
       // echo "second_url....".$url;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate,br");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//      $cookie_file = 'spjt'.$this->source_code.'.txt';
        $cookie_file = 'application/cache/' . get_domain_auth_id() . $this->source_code . 'SG.txt';
//        echo $cookie_file;;exit;
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $res = curl_exec($ch);
        //debug(curl_getinfo($ch));
        //debug(curl_error($ch));

        curl_close($ch);
        debug($res);exit;
        echo "2222222222222";
        return $res;
    }

    public function get_flight_list($flight_raw_data, $search_id) {
        // echo 'herre';
        // debug($flight_raw_data);exit;

        if (isset($flight_raw_data)) {

            $search_data = $this->search_data($search_id);
            




            $from = $search_data ['from'];
            $to = $search_data ['to'];

            $explode_from_city = explode(' ', $search_data ['from']);
            $from_city = @$explode_from_city [0] + @$explode_from_city [1];

            $explode_to_city = explode(' ', $search_data ['to']);
            $to_city = @$explode_to_city [0] + @$explode_to_city [1];

            $adult = $search_data ['adult'];
            $child = $search_data ['child'];
            $infant = $search_data ['infant'];
            $family_fare = 'false';
            if ($child > 0) {
                $family_fare = 'true';
            }
            $departure = date('d-M-Y', strtotime($search_data ['depature']));
            $day = date('d', strtotime($search_data ['depature']));
            $year_month = date('Y-m', strtotime($search_data ['depature']));
            $post_params = '__EVENTTARGET=
&__EVENTARGUMENT=
&__VIEWSTATE=%2FwEPDwUBMGRktapVDbdzjtpmxtfJuRZPDMU9XYk%3D&pageToken=';

            if ($search_data ['trip_type'] == 'oneway') {
                $trip_type = 'OneWay';
            } else {
                $trip_type = 'RoundTrip';
            }

            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24RadioButtonMarketStructure=' . $trip_type;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin1=' . $from;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation1=' . $from;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation1_CTXT=' . $from_city;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination1=' . $to;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation1=' . $to;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation1_CTXT=' . $to_city;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay1=' . $day;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth1=' . $year_month;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange1=0|0';
            $post_params .= '&custom_date_picker=' . $departure;
            $post_params .= '&date_picker=' . $departure;

            if ($trip_type == 'RoundTrip') {
                $origin2 = $to;
                $origin_city = $to_city;
                $destination2 = $from;
                $destination_city = $from_city;
                $return = date('d-M-Y', strtotime($search_data ['return']));
                $return_day = date('d', strtotime($return));
                $year_month = date('Y-m', strtotime($return));

                $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin2=' . $origin2;
                $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation2=' . $origin2;
                $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation2_CTXT=' . $origin_city;
                $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination2=' . $destination2;
                $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation2=' . $destination2;
                $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation2_CTXT=' . $destination_city;
                $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay2=' . $return_day;
                $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth2=' . $year_month;
                $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange2=0|0';
                $post_params .= '&custom_date_picker=' . $return;
                $post_params .= '&date_picker=' . $return;
            } else {
                $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin2=';
                $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation2=';
                $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation2_CTXT=Leaving+from...';
                $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination2=';
                $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation2=';
                $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation2_CTXT=Going+to...';
                $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay2=' . $day;
                $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth2=' . $year_month;
                $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange2=0|0';
                $post_params .= '&custom_date_picker=' . $departure;
                $post_params .= '&date_picker=' . $departure;
            }

            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListPassengerType_ADT=' . $adult;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListPassengerType_CHD=' . $child;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListPassengerType_INFANT=' . $infant;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListCurrency=' . get_application_default_currency();
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24Promocode=Promocode';
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24ButtonSubmit=Find+Flights';

            $post_params .= '&AGPKey=' . $this->AGPKey;
            $post_params .= '&QPKey=' . $this->QPKey;
            $post_params .= '&ReportsKey=' . $this->ReportsKey;


            $header = array();
            $header [0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
            $header [0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
            $header [] = "Cache-Control: max-age=0";
            $header [] = "Connection: keep-alive";
            $header [] = "Keep-Alive: 300";
            $header [] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
            $header [] = "Accept-Language: en-us,en;q=0.5";
            $header [] = "Pragma: ";
            $cookie_file = 'application/cache/' . get_domain_auth_id() . $this->source_code . 'SG.txt';

            $ch = curl_init('https://book.spicejet.com/Select.aspx');
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
            curl_setopt($ch, CURLOPT_ENCODING, "gzip");
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            $data = curl_exec($ch);

            file_put_contents('spicejet_scrap_data.php', $data);

            curl_close($ch);

            // $data = $this->format_search_result_data($rget, $search_data);

            if (valid_array($data)) {
                $response['data'] = $data;
                $response['status'] = SUCCESS_STATUS;
            }
        }
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned

        $search_data = $this->search_data($search_id);

        if ($search_data ['status'] == SUCCESS_STATUS) {
            
            if (isset($flight_raw_data)) {
                $clean_format_data = $this->format_search_result_data($flight_raw_data, $search_data ['data']);
                // debug($clean_format_data);
                // echo "break_mmm";
                  if ($clean_format_data) {
                        $clean_format_data = $this->format_search_data_response($clean_format_data, $search_data ['data']);
                  }
                
                // debug($clean_format_data);exit;
                
                if ($clean_format_data) {
                    $response ['status'] = SUCCESS_STATUS;
                } else {
                    $response ['status'] = FAILURE_STATUS;
                }
            } else {
                $response ['status'] = FAILURE_STATUS;
            }

            if ($response ['status'] == SUCCESS_STATUS) {
                $response ['data'] = $clean_format_data;
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
        }
        // debug($response);exit;

        return $response;
    }

    function get_flight_list_OLD($flight_raw_data, $search_id) {

        // error_reporting(0);
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $login_status = $this->login();

        if ($login_status == false) {
            // return failure object as the login signature is not set
            return $response;
        }
//  echo $search_id;debug($login_status);exit;

        $CI = & get_instance();
        $CI->load->driver('cache');
        $response ['data'] = array();


        /* get search criteria based on search id */
        $search_data = $this->search_data($search_id);
        // generate unique searchid string to enable caching
        $cache_search = $CI->config->item('cache_flight_search');
        $search_hash = $this->search_hash;

        if ($cache_search) {
            $cache_contents = $CI->cache->file->get($search_hash);
        }

        if ($search_data ['status'] == SUCCESS_STATUS) {
            if ($cache_search == FALSE || ($cache_search === true && empty($cache_contents) == true)) {
//              if($search_data['data']['child'] == 0 && $search_data['data']['infant'] == 0) {
                $search_request = $this->search_airline($search_data ['data']);

                if ($search_request['status'] == SUCCESS_STATUS) {
                    $response ['data'] = $search_request['data'];
                    $response ['status'] = SUCCESS_STATUS;
                }
//              }
            } else {
                $response ['data'] = $cache_contents;
                $response ['status'] = SUCCESS_STATUS;
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
        }
        return $response;
    }

    /* Search flight */

    function search_request($search_data) {
        // error_reporting(E_ALL);
        $CI = &get_instance();
        // debug($search_data);exit;
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();

        $header_details = array();
        $url = 'https://book.spicejet.com/Search.aspx';
        $from = $search_data ['from'];
        $to = $search_data ['to'];

        $explode_from_city = explode(' ', $search_data ['from']);
        $from_city = @$explode_from_city [0] + @$explode_from_city [1];

        $explode_to_city = explode(' ', $search_data ['to']);
        $to_city = @$explode_to_city [0] + @$explode_to_city [1];

        $adult = $search_data ['adult'];
        $child = $search_data ['child'];
        $infant = $search_data ['infant'];
        $family_fare = 'false';
        if ($child > 0) {
            $family_fare = 'true';
        }
        $departure = date('d-M-Y', strtotime($search_data ['depature']));
        $departure1 = date('d-m-Y', strtotime($search_data ['depature']));
        $departure2 = str_replace('-', '%2F', $departure1);
        $day = date('d', strtotime($search_data ['depature']));
        $day = intval($day);
        $month = date('m', strtotime($search_data ['depature']));
        $year = date('Y', strtotime($search_data ['depature']));
        $year_month = date('Y-m', strtotime($search_data ['depature']));
        $day_month = date('d-m', strtotime($search_data ['depature']));
        $day_month1 = str_replace('-', '%2F', $day_month);

        $reurndate = date('d-M-Y', strtotime($search_data ['return']));
        $reurndate1 = date('d-m-Y', strtotime($search_data ['return']));
        $reurndate2 = str_replace('-', '%2F', $reurndate1);
        $rday = date('d', strtotime($search_data ['return']));
        $rday = intval($rday);
        $rmonth = date('m', strtotime($search_data ['return']));
        $ryear = date('Y', strtotime($search_data ['return']));
        $ryear_month = date('Y-m', strtotime($search_data ['return']));
        $rday_month = date('d-m', strtotime($search_data ['return']));
        $rday_month1 = str_replace('-', '%2F', $rday_month);

        $from_city_val = $CI->flight_model->get_airport_city_name($search_data['from']);
        //debug($from_city_val);exit;
        $from_city_data = $from_city_val->airport_city.'('.$from_city_val->airport_code.')';
        $from_city_data1 = str_replace('(', '+%28', $from_city_data);
        $from_city_data1 = str_replace(')', '%29', $from_city_data1);
        $to_city_val = $CI->flight_model->get_airport_city_name($search_data['to']);
        $to_city_data = $to_city_val->airport_city.'('.$to_city_val->airport_code.')';
        $to_city_data1 = str_replace('(', '+%28', $to_city_data);
        $to_city_data1 = str_replace(')', '%29', $to_city_data1);
        $date_range = str_replace('|', '%7C', '0|0');
        if ($search_data ['trip_type'] == 'oneway') {
            $trip_type = 'OneWay';
        } else {
            $trip_type = 'RoundTrip';
        }
        $today_date = date("d-m-Y");
        $today_day = date("d");
        $today_mon_year = date("Y-m");
        $today_month = date("m");
        $today_year = date("Y");
        $date = strtotime($today_date);
        $date = strtotime("+7 day", $date);
        $weekdate = date('d-m-Y', $date);
        // echo 'herre'.$weekdate;exit;
        $weekday = date("d", strtotime($weekdate));
        $weekday = intval($weekday);
        $weekday_mon_year = date("m-Y", strtotime($weekdate));
        $weekday_year_mon = date("Y-m", strtotime($weekdate));
        // anitha code
        $post_params = '__EVENTTARGET=ControlGroupSearchView%24AvailabilitySearchInputSearchView%24ButtonSubmit&__EVENTARGUMENT=&__VIEWSTATE=%2FwEPDwUBMGRkMSHDB101%2B52k3J%2FFmXSyR78uK%2Fg%3D&pageToken=';
      
        // debug($search_data);exit;
         if ($search_data ['trip_type'] == 'oneway') {
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24RadioButtonMarketStructure='.$trip_type;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24Date2='.$departure2;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin1='.$from;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation1='.$from;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation1_CTXT='.$from_city_data1;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination1='.$to;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation1='.$to;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation1_CTXT='.$to_city_data1;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay1='.$day;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth1='.$year_month;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange1='.$date_range;
            $post_params .= '&custom_date_picker='.$day_month1.'&custom_date_picker=&date_picker='.$departure1;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay2='.$day;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth2='.$year_month;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange2='.$date_range;
            $post_params .= '&custom_date_picker='.$day_month1.'&custom_date_picker=&date_picker='.$departure1;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListPassengerType_ADT='.$adult;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListPassengerType_CHD='.$child;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListPassengerType_INFANT='.$infant;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListCurrency=none';
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin2=';
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation2=';
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation2_CTXT=Departure+City';
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination2=';
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation2=';
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation2_CTXT=Arrival+City';
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24Promocode=Promocode';
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24ButtonSubmit=';
            $post_params .= '&FlifoSearchInputSearchView%24TextBoxMarketOrigin=';
            $post_params .= '&originStation=&FlifoSearchInputSearchView%24TextBoxMarketDestination=&destinationStation=&FlifoSearchInputSearchView%24DropDownListFlightDate=&FlifoSearchInputSearchView%24TextBoxFlightNumber=&BookingRetrieveInputSearch1WebCheckinSearchView%24ConfirmationNumber=&BookingRetrieveInputSearch1WebCheckinSearchView%24CONFIRMATIONNUMBER1=&BookingRetrieveInputSearch1WebCheckinSearchView%24CONTACTEMAIL1=&BookingRetrieveInputSearch1WebCheckinSearchView%24CONFIRMATIONNUMBER2=&BookingRetrieveInputSearch1WebCheckinSearchView%24PAXFIRSTNAME2=&BookingRetrieveInputSearch1WebCheckinSearchView%24PAXLASTNAME2=&BookingRetrieveInputSearch1WebCheckinSearchView%24ORIGINCITY2=none&BookingRetrieveInputSearch1WebCheckinSearchView%24DESTINATIONCITY2=none&MySpiceTripSearchView%24RadioButtonMarketStructure=RoundTrip&MySpiceTripSearchView%24tripType=FlightHotel&MySpiceTripSearchView%24TextBoxMarketOrigin1=&MySpiceTripSearchView%24TextBoxMarketDestination1=Going+To&MySpiceTripSearchViewdestinationStation1=&MySpiceTripSearchViewdestinationStation1_CTXT=Going+to...';
            $post_params .= '&MySpiceTripSearchView%24DropDownListMarketDay1='.$today_day;
            $post_params .= '&MySpiceTripSearchView%24DropDownListMarketMonth1='.$today_mon_year;
            $post_params .= '&MySpiceTripSearchView%24DropDownListMarketDateRange1='.$date_range;
            $post_params .= '&custom_date_picker='.$today_date;
            $post_params .= '&date_picker='.$today_date;
            $post_params .= '&MySpiceTripSearchView%24TextBoxMarketOrigin1=&MySpiceTripSearchView%24TextBoxMarketDestination2=&MySpiceTripSearchViewdestinationStation2=&MySpiceTripSearchViewdestinationStation2_CTXT=Going+to...';
            $post_params .= '&MySpiceTripSearchView%24DropDownListMarketDay2='.$weekday;
            $post_params .= '&MySpiceTripSearchView%24DropDownListMarketMonth2='.$weekday_year_mon.'&MySpiceTripSearchView%24DropDownListMarketDateRange2=0%7C0&custom_date_picker='.$weekdate.'&date_picker='.$weekdate.'&MySpiceTripSearchView%24DropDownListPassengerType_ADT='.$adult.'&MySpiceTripSearchView%24DropDownListPassengerType_CHD='.$child.'&MySpiceTripSearchView%24DropDownListPassengerType_INFANT='.$infant.'&HolidayPackageSearchView%24DropDownListPackage=&HolidayPackageSearchView_DropDownListPackage_CTXT=Destination+City&HolidayPackageSearchView%24DropDownListFrom=&HolidayPackageSearchView_DropDownListFrom_CTXT=Departure+City&HolidayPackageSearchView%24DropDownListTo=&HolidayPackageSearchView_DropDownListTo_CTXT=Return+City';
            $post_params .= '&custom_date_picker='.$today_date;
            $post_params .= '&date_picker='.$today_date;
            $post_params .= '&HolidayPackageSearchView%24DropDownListDay='.$today_day;
            $post_params .= '&HolidayPackageSearchView%24DropDownListMonth='.$today_month;
            $post_params .= '&HolidayPackageSearchView%24DropDownListYear='.$today_year;
       }elseif ($search_data ['trip_type'] == 'return') {
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24RadioButtonMarketStructure='.$trip_type;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24Date2='.$reurndate2;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin1='.$from;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation1='.$from;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation1_CTXT='.$from_city_data1;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination1='.$to;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation1='.$to;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation1_CTXT='.$to_city_data1;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay1='.$day;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth1='.$year_month;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange1='.$date_range;
            $post_params .= '&custom_date_picker='.$day_month1.'&custom_date_picker=&date_picker='.$departure1;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay2='.$rday;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth2='.$ryear_month;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange2='.$date_range;
            $post_params .= '&custom_date_picker='.$rday_month1.'&custom_date_picker=&date_picker='.$reurndate1;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListPassengerType_ADT='.$adult;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListPassengerType_CHD='.$child;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListPassengerType_INFANT='.$infant;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListCurrency=none';
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin2=';
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation2=';
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation2_CTXT=Departure+City';
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination2=';
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation2=';
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation2_CTXT=Arrival+City';
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24Promocode=Promocode';
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24ButtonSubmit=';
            $post_params .= '&FlifoSearchInputSearchView%24TextBoxMarketOrigin=';
            $post_params .= '&originStation=&FlifoSearchInputSearchView%24TextBoxMarketDestination=&destinationStation=&FlifoSearchInputSearchView%24DropDownListFlightDate=&FlifoSearchInputSearchView%24TextBoxFlightNumber=&BookingRetrieveInputSearch1WebCheckinSearchView%24ConfirmationNumber=&BookingRetrieveInputSearch1WebCheckinSearchView%24CONFIRMATIONNUMBER1=&BookingRetrieveInputSearch1WebCheckinSearchView%24CONTACTEMAIL1=&BookingRetrieveInputSearch1WebCheckinSearchView%24CONFIRMATIONNUMBER2=&BookingRetrieveInputSearch1WebCheckinSearchView%24PAXFIRSTNAME2=&BookingRetrieveInputSearch1WebCheckinSearchView%24PAXLASTNAME2=&BookingRetrieveInputSearch1WebCheckinSearchView%24ORIGINCITY2=none&BookingRetrieveInputSearch1WebCheckinSearchView%24DESTINATIONCITY2=none&MySpiceTripSearchView%24RadioButtonMarketStructure=RoundTrip&MySpiceTripSearchView%24tripType=FlightHotel&MySpiceTripSearchView%24TextBoxMarketOrigin1=&MySpiceTripSearchView%24TextBoxMarketDestination1=Going+To&MySpiceTripSearchViewdestinationStation1=&MySpiceTripSearchViewdestinationStation1_CTXT=Going+to...';
            $post_params .= '&MySpiceTripSearchView%24DropDownListMarketDay1='.$today_day;
            $post_params .= '&MySpiceTripSearchView%24DropDownListMarketMonth1='.$today_mon_year;
            $post_params .= '&MySpiceTripSearchView%24DropDownListMarketDateRange1='.$date_range;
            $post_params .= '&custom_date_picker='.$today_date;
            $post_params .= '&date_picker='.$today_date;
            $post_params .= '&MySpiceTripSearchView%24TextBoxMarketOrigin1=&MySpiceTripSearchView%24TextBoxMarketDestination2=&MySpiceTripSearchViewdestinationStation2=&MySpiceTripSearchViewdestinationStation2_CTXT=Going+to...';
            $post_params .= '&MySpiceTripSearchView%24DropDownListMarketDay2='.$weekday;
            $post_params .= '&MySpiceTripSearchView%24DropDownListMarketMonth2='.$weekday_year_mon.'&MySpiceTripSearchView%24DropDownListMarketDateRange2=0%7C0&custom_date_picker='.$weekdate.'&date_picker='.$weekdate.'&MySpiceTripSearchView%24DropDownListPassengerType_ADT='.$adult.'&MySpiceTripSearchView%24DropDownListPassengerType_CHD='.$child.'&MySpiceTripSearchView%24DropDownListPassengerType_INFANT='.$infant.'&HolidayPackageSearchView%24DropDownListPackage=&HolidayPackageSearchView_DropDownListPackage_CTXT=Destination+City&HolidayPackageSearchView%24DropDownListFrom=&HolidayPackageSearchView_DropDownListFrom_CTXT=Departure+City&HolidayPackageSearchView%24DropDownListTo=&HolidayPackageSearchView_DropDownListTo_CTXT=Return+City';
            $post_params .= '&custom_date_picker='.$today_date;
            $post_params .= '&date_picker='.$today_date;
            $post_params .= '&HolidayPackageSearchView%24DropDownListDay='.$today_day;
            $post_params .= '&HolidayPackageSearchView%24DropDownListMonth='.$today_month;
            $post_params .= '&HolidayPackageSearchView%24DropDownListYear='.$today_year;
       }
       // debug($post_params);exit;
      // echo "<br/>";
        /*$post_params = '__EVENTTARGET=
&__EVENTARGUMENT=
&__VIEWSTATE=%2FwEPDwUBMGRktapVDbdzjtpmxtfJuRZPDMU9XYk%3D&pageToken=';

        if ($search_data ['trip_type'] == 'oneway') {
            $trip_type = 'OneWay';
        } else {
            $trip_type = 'RoundTrip';
        }

        $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24RadioButtonMarketStructure=' . $trip_type;
        $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin1=' . $from;
        $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation1=' . $from;
        $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation1_CTXT=' . $from_city;
        $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination1=' . $to;
        $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation1=' . $to;
        $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation1_CTXT=' . $to_city;
        $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay1=' . $day;
        $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth1=' . $year_month;
        $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange1=0|0';
        $post_params .= '&custom_date_picker=' . $departure;
        $post_params .= '&date_picker=' . $departure;

        if ($trip_type == 'RoundTrip') {
            $origin2 = $to;
            $origin_city = $to_city;
            $destination2 = $from;
            $destination_city = $from_city;
            $return = date('d-M-Y', strtotime($search_data ['return']));
            $return_day = date('d', strtotime($return));
            $year_month = date('Y-m', strtotime($return));

            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin2=' . $origin2;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation2=' . $origin2;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation2_CTXT=' . $origin_city;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination2=' . $destination2;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation2=' . $destination2;
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation2_CTXT=' . $destination_city;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay2=' . $return_day;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth2=' . $year_month;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange2=0|0';
            $post_params .= '&custom_date_picker=' . $return;
            $post_params .= '&date_picker=' . $return;
        } else {
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin2=';
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation2=';
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation2_CTXT=Departure+City';
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination2=';
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation2=';
            $post_params .= '&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation2_CTXT=Arrival+City';
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay2=' . $day;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth2=' . $year_month;
            $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange2=0|0';
            $post_params .= '&custom_date_picker=' . $departure;
            $post_params .= '&date_picker=' . $departure;
        }

        $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListPassengerType_ADT=' . $adult;
        $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListPassengerType_CHD=' . $child;
        $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListPassengerType_INFANT=' . $infant;
        $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListCurrency=' . get_application_default_currency();
        $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24Promocode=Promocode';
        $post_params .= '&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24ButtonSubmit=Find+Flights';

        $post_params .= '&AGPKey=' . $this->AGPKey;
        $post_params .= '&QPKey=' . $this->QPKey;
        $post_params .= '&ReportsKey=' . $this->ReportsKey;*/

        $header = array();
        $header [0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header [0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header [] = "Cache-Control: max-age=0";
        $header [] = "Connection: keep-alive";
        $header [] = "Keep-Alive: 300";
        $header [] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header [] = "Accept-Language: en-us,en;q=0.5";
        $header [] = "Pragma:";
        $cookie_file = 'application/cache/' . get_domain_auth_id() . $this->source_code . 'SG.txt';

        //testing  code
        // $request1 = '__EVENTTARGET=ControlGroupSearchView%24AvailabilitySearchInputSearchView%24ButtonSubmit&__EVENTARGUMENT=&__VIEWSTATE=%2FwEPDwUBMGRkMSHDB101%2B52k3J%2FFmXSyR78uK%2Fg%3D&pageToken=&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24RadioButtonMarketStructure=OneWay&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24Date2=27%2F12%2F2017&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin1=BLR&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation1=BLR&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation1_CTXT=Bengaluru+%28BLR%29&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination1=DEL&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation1=DEL&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation1_CTXT=Delhi+%28DEL%29&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay1=27&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth1=2017-12&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange1=0%7C0&custom_date_picker=27%2F12&custom_date_picker=&date_picker=27-12-2017&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay2=27&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth2=2017-12&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange2=0%7C0&custom_date_picker=27%2F12&custom_date_picker=&date_picker=27-12-2017&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListPassengerType_ADT=1&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListPassengerType_CHD=0&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListPassengerType_INFANT=0&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListCurrency=none&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin2=&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation2=&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation2_CTXT=Departure+City&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination2=&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation2=&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation2_CTXT=Arrival+City&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin3=&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation3=&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation3_CTXT=Departure+City&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination3=&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation3=&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation3_CTXT=Arrival+City&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay3=25&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth3=2017-12&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange3=0%7C0&custom_date_picker=25%2F12&custom_date_picker=&date_picker=27-12-2017&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin4=&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation4=&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation4_CTXT=Departure+City&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination4=&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation4=&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation4_CTXT=Arrival+City&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay4=1&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth4=2018-01&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange4=0%7C0&custom_date_picker=01%2F01&custom_date_picker=&date_picker=01-01-2018&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin5=&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation5=&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation5_CTXT=Departure+City&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination5=&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation5=&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation5_CTXT=Arrival+City&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay5=8&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth5=2018-01&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange5=0%7C0&custom_date_picker=08%2F01&custom_date_picker=&date_picker=08-01-2018&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketOrigin6=&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation6=&ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation6_CTXT=Departure+City&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24TextBoxMarketDestination6=&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation6=&ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation6_CTXT=Arrival+City&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDay6=15&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketMonth6=2018-01&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24DropDownListMarketDateRange6=0%7C0&custom_date_picker=15%2F01&custom_date_picker=&date_picker=15-01-2018&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24Promocode=Promocode&ControlGroupSearchView%24AvailabilitySearchInputSearchView%24ButtonSubmit=&FlifoSearchInputSearchView%24TextBoxMarketOrigin=&originStation=&FlifoSearchInputSearchView%24TextBoxMarketDestination=&destinationStation=&FlifoSearchInputSearchView%24DropDownListFlightDate=&FlifoSearchInputSearchView%24TextBoxFlightNumber=&BookingRetrieveInputSearch1WebCheckinSearchView%24ConfirmationNumber=&BookingRetrieveInputSearch1WebCheckinSearchView%24CONFIRMATIONNUMBER1=&BookingRetrieveInputSearch1WebCheckinSearchView%24CONTACTEMAIL1=&BookingRetrieveInputSearch1WebCheckinSearchView%24CONFIRMATIONNUMBER2=&BookingRetrieveInputSearch1WebCheckinSearchView%24PAXFIRSTNAME2=&BookingRetrieveInputSearch1WebCheckinSearchView%24PAXLASTNAME2=&BookingRetrieveInputSearch1WebCheckinSearchView%24ORIGINCITY2=none&BookingRetrieveInputSearch1WebCheckinSearchView%24DESTINATIONCITY2=none&MySpiceTripSearchView%24RadioButtonMarketStructure=RoundTrip&MySpiceTripSearchView%24tripType=FlightHotel&MySpiceTripSearchView%24TextBoxMarketOrigin1=&MySpiceTripSearchView%24TextBoxMarketDestination1=Going+To&MySpiceTripSearchViewdestinationStation1=&MySpiceTripSearchViewdestinationStation1_CTXT=Going+to...&MySpiceTripSearchView%24DropDownListMarketDay1=11&MySpiceTripSearchView%24DropDownListMarketMonth1=2017-12&MySpiceTripSearchView%24DropDownListMarketDateRange1=0%7C0&custom_date_picker=11-12-2017&date_picker=11-12-2017&MySpiceTripSearchView%24TextBoxMarketOrigin1=&MySpiceTripSearchView%24TextBoxMarketDestination2=&MySpiceTripSearchViewdestinationStation2=&MySpiceTripSearchViewdestinationStation2_CTXT=Going+to...&MySpiceTripSearchView%24DropDownListMarketDay2=18&MySpiceTripSearchView%24DropDownListMarketMonth2=2017-12&MySpiceTripSearchView%24DropDownListMarketDateRange2=0%7C0&custom_date_picker=18-12-2017&date_picker=18-12-2017&MySpiceTripSearchView%24DropDownListPassengerType_ADT=1&MySpiceTripSearchView%24DropDownListPassengerType_CHD=0&MySpiceTripSearchView%24DropDownListPassengerType_INFANT=0&HolidayPackageSearchView%24DropDownListPackage=&HolidayPackageSearchView_DropDownListPackage_CTXT=Destination+City&HolidayPackageSearchView%24DropDownListFrom=&HolidayPackageSearchView_DropDownListFrom_CTXT=Departure+City&HolidayPackageSearchView%24DropDownListTo=&HolidayPackageSearchView_DropDownListTo_CTXT=Return+City&custom_date_picker=11-12-2017&date_picker=11-12-2017&HolidayPackageSearchView%24DropDownListDay=11&HolidayPackageSearchView%24DropDownListMonth=12&HolidayPackageSearchView%24DropDownListYear=2017';
// echo $request1;exit;

        $request ['request'] = $post_params;
        $request ['url'] = $url;
        $request ['status'] = SUCCESS_STATUS;
        $request['header'] = $header;
        $request['cookie'] = $cookie_file;

        //anitha code
        //$request2 = '__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=%2FwEPDwUBMGRkMSHDB101%2B52k3J%2FFmXSyR78uK%2Fg%3D&pageToken=&AvailabilitySearchInputSelectView%24RadioButtonMarketStructure=OneWay&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin1=BLR&originStation1=BLR&AvailabilitySearchInputSelectView%24TextBoxMarketDestination1=DEL&destinationStation1=DEL&AvailabilitySearchInputSelectView%24DropDownListMarketDay1=27&AvailabilitySearchInputSelectView%24DropDownListMarketMonth1=2017-12&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange1=0%7C0&date_picker=27-Dec-2017&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin2=&originStation2=&AvailabilitySearchInputSelectView%24TextBoxMarketDestination2=&destinationStation2=&AvailabilitySearchInputSelectView%24DropDownListMarketDay2=3&AvailabilitySearchInputSelectView%24DropDownListMarketMonth2=2018-01&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange2=0%7C0&date_picker=--NaN&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin3=&originStation3=&AvailabilitySearchInputSelectView%24TextBoxMarketDestination3=&destinationStation3=&AvailabilitySearchInputSelectView%24DropDownListMarketDay3=10&AvailabilitySearchInputSelectView%24DropDownListMarketMonth3=2018-01&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange3=0%7C0&date_picker=10-01-2018&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin4=&originStation4=&AvailabilitySearchInputSelectView%24TextBoxMarketDestination4=&destinationStation4=&AvailabilitySearchInputSelectView%24DropDownListMarketDay4=17&AvailabilitySearchInputSelectView%24DropDownListMarketMonth4=2018-01&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange4=0%7C0&date_picker=17-01-2018&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin5=&originStation5=&AvailabilitySearchInputSelectView%24TextBoxMarketDestination5=&destinationStation5=&AvailabilitySearchInputSelectView%24DropDownListMarketDay5=24&AvailabilitySearchInputSelectView%24DropDownListMarketMonth5=2018-01&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange5=0%7C0&date_picker=24-01-2018&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin6=&originStation6=&AvailabilitySearchInputSelectView%24TextBoxMarketDestination6=&destinationStation6=&AvailabilitySearchInputSelectView%24DropDownListMarketDay6=31&AvailabilitySearchInputSelectView%24DropDownListMarketMonth6=2018-01&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange6=0%7C0&date_picker=31-01-2018&AvailabilitySearchInputSelectView%24Date2=&AvailabilitySearchInputSelectView%24Date3=&AvailabilitySearchInputSelectView%24Date4=&AvailabilitySearchInputSelectView%24Date5=&AvailabilitySearchInputSelectView%24DropDownListPassengerType_ADT=1&AvailabilitySearchInputSelectView%24DropDownListPassengerType_CHD=0&AvailabilitySearchInputSelectView%24DropDownListPassengerType_INFANT=0&AvailabilitySearchInputSelectView%24DropDownListCurrency=none&ControlGroupSelectView%24AvailabilityInputSelectView%24market1=0%7EA%7E%7EASAVER%7E2022%7E%7E12%7EX%7CSG%7E+136%7E+%7E%7EBLR%7E12%2F27%2F2017+05%3A45%7EDEL%7E12%2F27%2F2017+08%3A35%7E&ControlGroupSelectView%24ContactInputGSTViewSelectView%24ControlGroupSelectView_ContactInputGSTViewSelectViewHtmlInputHiddenAntiForgeryTokenField=fa9946a3-3384-42cb-b79e-fe76b249461f&ControlGroupSelectView%24ContactInputGSTViewSelectView%24CheckBoxGST=on&ControlGroupSelectView%24ButtonSubmit=Continue';
        //$request2 = '__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=%2FwEPDwUBMGRkMSHDB101%2B52k3J%2FFmXSyR78uK%2Fg%3D&pageToken=&AvailabilitySearchInputSelectView%24RadioButtonMarketStructure=OneWay&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin1=BLR&originStation1=BLR&AvailabilitySearchInputSelectView%24TextBoxMarketDestination1=DEL&destinationStation1=DEL&AvailabilitySearchInputSelectView%24DropDownListMarketDay1=28&AvailabilitySearchInputSelectView%24DropDownListMarketMonth1=2017-12&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange1=0%7C0&date_picker=28-Dec-2017&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin2=&originStation2=&AvailabilitySearchInputSelectView%24TextBoxMarketDestination2=&destinationStation2=&AvailabilitySearchInputSelectView%24DropDownListMarketDay2=4&AvailabilitySearchInputSelectView%24DropDownListMarketMonth2=2018-01&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange2=0%7C0&date_picker=--NaN&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin3=&originStation3=&AvailabilitySearchInputSelectView%24TextBoxMarketDestination3=&destinationStation3=&AvailabilitySearchInputSelectView%24DropDownListMarketDay3=11&AvailabilitySearchInputSelectView%24DropDownListMarketMonth3=2018-01&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange3=0%7C0&date_picker=11-01-2018&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin4=&originStation4=&AvailabilitySearchInputSelectView%24TextBoxMarketDestination4=&destinationStation4=&AvailabilitySearchInputSelectView%24DropDownListMarketDay4=18&AvailabilitySearchInputSelectView%24DropDownListMarketMonth4=2018-01&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange4=0%7C0&date_picker=18-01-2018&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin5=&originStation5=&AvailabilitySearchInputSelectView%24TextBoxMarketDestination5=&destinationStation5=&AvailabilitySearchInputSelectView%24DropDownListMarketDay5=25&AvailabilitySearchInputSelectView%24DropDownListMarketMonth5=2018-01&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange5=0%7C0&date_picker=25-01-2018&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin6=&originStation6=&AvailabilitySearchInputSelectView%24TextBoxMarketDestination6=&destinationStation6=&AvailabilitySearchInputSelectView%24DropDownListMarketDay6=1&AvailabilitySearchInputSelectView%24DropDownListMarketMonth6=2018-02&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange6=0%7C0&date_picker=01-02-2018&AvailabilitySearchInputSelectView%24Date2=&AvailabilitySearchInputSelectView%24Date3=&AvailabilitySearchInputSelectView%24Date4=&AvailabilitySearchInputSelectView%24Date5=&AvailabilitySearchInputSelectView%24DropDownListPassengerType_ADT=1&AvailabilitySearchInputSelectView%24DropDownListPassengerType_CHD=0&AvailabilitySearchInputSelectView%24DropDownListPassengerType_INFANT=0&AvailabilitySearchInputSelectView%24DropDownListCurrency=none&ControlGroupSelectView%24AvailabilityInputSelectView%24market1=0%7EV%7E%7EVSAVER%7E2022%7E%7E11%7EX%7CSG%7E+136%7E+%7E%7EBLR%7E12%2F28%2F2017+05%3A45%7EDEL%7E12%2F28%2F2017+08%3A35%7E&ControlGroupSelectView%24ContactInputGSTViewSelectView%24ControlGroupSelectView_ContactInputGSTViewSelectViewHtmlInputHiddenAntiForgeryTokenField=1652048f-ce7e-4ac8-85b4-c86a8a9dd6f5&ControlGroupSelectView%24ContactInputGSTViewSelectView%24CheckBoxGST=on&ControlGroupSelectView%24ButtonSubmit=Continue';
        /*$request2 = '__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=%2FwEPDwUBMGRkMSHDB101%2B52k3J%2FFmXSyR78uK%2Fg%3D&pageToken=&AvailabilitySearchInputSelectView%24RadioButtonMarketStructure=OneWay&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin1=BLR&originStation1=BLR&AvailabilitySearchInputSelectView%24TextBoxMarketDestination1=DEL&destinationStation1=DEL&AvailabilitySearchInputSelectView%24DropDownListMarketDay1=28&AvailabilitySearchInputSelectView%24DropDownListMarketMonth1=2017-12&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange1=0%7C0&date_picker=28-Dec-2017&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin2=&originStation2=&AvailabilitySearchInputSelectView%24TextBoxMarketDestination2=&destinationStation2=&AvailabilitySearchInputSelectView%24DropDownListMarketDay2=4&AvailabilitySearchInputSelectView%24DropDownListMarketMonth2=2018-01&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange2=0%7C0&date_picker=--NaN&AvailabilitySearchInputSelectView%24Date2=&AvailabilitySearchInputSelectView%24Date3=&AvailabilitySearchInputSelectView%24Date4=&AvailabilitySearchInputSelectView%24Date5=&AvailabilitySearchInputSelectView%24DropDownListPassengerType_ADT=1&AvailabilitySearchInputSelectView%24DropDownListPassengerType_CHD=0&AvailabilitySearchInputSelectView%24DropDownListPassengerType_INFANT=0&AvailabilitySearchInputSelectView%24DropDownListCurrency=none&ControlGroupSelectView%24AvailabilityInputSelectView%24market1=0%7EV%7E%7EVSAVER%7E2022%7E%7E11%7EX%7CSG%7E+136%7E+%7E%7EBLR%7E12%2F28%2F2017+05%3A45%7EDEL%7E12%2F28%2F2017+08%3A35%7E&ControlGroupSelectView%24ContactInputGSTViewSelectView%24ControlGroupSelectView_ContactInputGSTViewSelectViewHtmlInputHiddenAntiForgeryTokenField=1658042f-ce7e-4ac8-85b4-c86a8a9dd6f5&ControlGroupSelectView%24ContactInputGSTViewSelectView%24CheckBoxGST=on&ControlGroupSelectView%24ButtonSubmit=Continue';
        $cookie = 'ASP.NET_SessionId=gt5bud45kdc0v4451tcauxnn; path=/';
        $url1 = 'https://book.spicejet.com/Select.aspx';
        $ch = curl_init ( $url1 );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_ENCODING, "gzip,deflate,br" );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );

       
        // curl_setopt ( $ch, CURLOPT_COOKIEJAR, $cookie_file );
        curl_setopt ( $ch, CURLOPT_COOKIE, $cookie );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $request2 );
        curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt ( $ch, CURLOPT_VERBOSE, true );
        $res = curl_exec ( $ch );
        debug($res);exit;
        curl_close ( $ch );
        return $res;*/

        // echo 'hrre';
        // debug($request);exit;
        return $request;

        # Commented BY Balu
        exit;

        /*  $header = array();
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_ENCODING, "gzip");
          curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
          curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
          curl_setopt($ch, CURLOPT_AUTOREFERER, true);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
          curl_setopt($ch, CURLOPT_VERBOSE, true);
          $res = curl_exec($ch);

          if (isset($res)) {
          $ch = curl_init('https://book.spicejet.com/Select.aspx');
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
          curl_setopt($ch, CURLOPT_POST, 0);
          curl_setopt($ch, CURLOPT_HTTPGET, 1);
          curl_setopt($ch, CURLOPT_ENCODING, "gzip");
          curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
          //            $cookie_file = 'spjt'.$this->source_code.'.txt';
          $cookie_file = 'application/cache/' . get_domain_auth_id() . $this->source_code . 'SG.txt';
          // curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
          curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
          curl_setopt($ch, CURLOPT_AUTOREFERER, true);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
          curl_setopt($ch, CURLOPT_VERBOSE, true);
          $rget = curl_exec($ch);

          file_put_contents('spicejet_scrap_data.php', $rget);

          curl_close($ch);

          //    exit;
          //error_reporting(E_ALL);
          $data = $this->format_search_result_data($rget, $search_data);

          if (valid_array($data)) {
          $response['data'] = $data;
          $response['status'] = SUCCESS_STATUS;
          }
          //            $response = $data;
          }
          //        debug($response);exit;
          // print file_get_contents($cookie_file);
          // echo 'arjun';
          // echo $res;exit;
          // debug($ch);

          return $response; */
    }

    function get_fare_details_scrap($journey_key, $search_data) {
        $CI = &get_instance();
        $strCookie = $CI->flight_model->get_cookie_value();
        // echo 'nvnvv'.$strCookie;
        $post_params = '';

        $header = array();
        $header [] = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header [] = "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header [] = "Cache-Control: no-cache, no-store";
        $header [] = "Connection: keep-alive";
        $header [] = "Keep-Alive: 300";
        $header [] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header [] = "Accept-Language: en-us,en;q=0.5";
        $header [] = "Pragma: ";
        // $header = array();
        // debug($header);exit;
        $ch = curl_init();
        $journey_api_key = curl_escape($ch, $journey_key);
      
        // $strCookie = 'ASP.NET_SessionId=hpsf0yq2vhb2wj45fo3fc1rt; path=/';

        //anitha code
        $url = 'https://book.spicejet.com/TaxAndFeeInclusiveDisplayAjax-resource.aspx?flightKeys='.$journey_key.'&numberOfMarkets=1&keyDelimeter=%2C';
        // echo $url;
        // echo "<br/>";
        //$url = 'https://book.spicejet.com/TaxAndFeeInclusiveDisplayAjax-resource.aspx?flightKeys=0~K~~KMAX~2022~~8~X%7CSG~3310~+~~BLR~12%2F11%2F2017+22%3A00~MAA~12%2F11%2F2017+22%3A45~&numberOfMarkets=1&keyDelimeter=%2C';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_HEADER, 1 );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//      $cookie_file = 'spjt'.$this->source_code.'.txt';
        //$cookie_file = 'application/cache/' . get_domain_auth_id() . $this->source_code . 'SG.txt';
        $cookie_file = 'application/cache/4PTBSID0000000008SG.txt';
        // debug($cookie_file);exit;
        // curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIE, $strCookie);
//      curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_params );
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        // debug(curl_error($ch));exit;
        $rget = curl_exec($ch);
        // debug($rget);exit;
        $info = curl_getinfo($ch);

//      error_reporting(E_ALL);
        $headers = $this->get_headers_from_curl_response($rget);

        $price = array();
        if (isset($rget) && !empty($rget)) {
            $price_dom = new DOMDocument();
            $price_dom->loadHTML($rget);
            $class = 'priceSummary';
            $price_xpath = new DOMXPath($price_dom);
            $price_tag = $price_xpath->query("//table[@class='" . $class . "']");

            $cnt = 0;
            $total_tax = 0;
            $price_array = array();
            $pax_breakup = array();
            if ($price_tag->length > 0) {
                $tr_tag = $price_tag->item(0)->getElementsByTagName('tr');

                if ($tr_tag->length > 0) {
                    foreach ($tr_tag as $tr) {
                        $td_tag = $tr->getElementsByTagName('td');

                        if ($td_tag->length > 0) {
                            foreach ($td_tag as $td) {
                                $td_class = $td->getAttribute('class');
                                if (preg_match("/lft/", $td_class)) {

                                    $td_head = str_replace(',', '', trim($td->nodeValue));
                                } else if (preg_match("/rht/", $td_class)) {
                                    //check table tag
                                    //$a_href = $td->getElementsByTagName('a');
                                    $a_href = $td->nodeValue;

                                    //  if ($a_href->length > 0) {
                                    $price_val__H1 = str_replace(',', '', trim($a_href));
                                    $price_val__H1 = explode(' ', $price_val__H1);
                                    $price_val__H = trim($price_val__H1[0]);
                                    
                                    if (strcasecmp($price_val__H, 'AgencyTransactionFee') != 0) {
                                        $price_array[preg_replace('!\s+!', '', $td_head)] = $price_val__H;

                                        if (preg_match("/Adult/", $td_head)) {
                                            $pax_breakup['ADT'] = array(
                                                'base_price' => $price_val__H,
                                                'total_price' => $price_val__H,
                                                'tax' => ($price_val__H - $price_val__H),
                                                'pass_no' => @$search_data['adult']);
                                        } else if (preg_match("/Child/", $td_head)) {
                                            $pax_breakup['CNN'] = array(
                                                'base_price' => $price_val__H,
                                                'total_price' => $price_val__H,
                                                'tax' => ($price_val__H - $price_val__H),
                                                'pass_no' => @$search_data['child']);
                                        } else if (preg_match("/Infant/", $td_head)) {
                                            $pax_breakup['INF'] = array(
                                                'base_price' => $price_val__H,
                                                'total_price' => $price_val__H,
                                                'tax' => ($price_val__H - $price_val__H),
                                                'pass_no' => @$search_data['infant']);
                                        }
                                    }
                                    // debug($pax_breakup);exit;
                                    //  }

                                    $td_table = $td->getElementsByTagName('table');

                                    if ($td_table->length > 0) {

                                        foreach ($td_table as $__table) {
                                            $inner_tr = $__table->getElementsByTagName('tr');
                                            if ($inner_tr->length > 0) {
                                                foreach ($inner_tr as $i__tr) {
                                                    $td_cnt = array();
                                                    $inner_td = $i__tr->getElementsByTagName('td');
                                                    if ($inner_td->length > 0) {
                                                        foreach ($inner_td as $i__td) {
                                                            $td_cnt[] = trim($i__td->nodeValue);
                                                        }
                                                        if (valid_array($td_cnt) && COUNT($td_cnt) == 2) {
                                                            $key = str_replace('1Â  ', '', $td_cnt[0]);
                                                            $val = str_replace(',', '', $td_cnt[1]);

                                                            $key = preg_replace('!\s+!', '', $key);
                                                            if (!array_key_exists($key, $price_array) && strcasecmp($key, 'AgencyTransactionFee') != 0) {
                                                                $price_array[preg_replace('!\s+!', '', $key)] = $val;

                                                                if (preg_match("/Adult/", $key) && array_key_exists('ADT', $pax_breakup)) {
                                                                    $pax_breakup['ADT'] = array(
                                                                        'base_price' => $val,
                                                                        'total_price' => $val,
                                                                        'tax' => ($val - $val),
                                                                        'pass_no' => @$search_data['adult']);
                                                                } else if (preg_match("/Child/", $key) && array_key_exists('CNN', $pax_breakup)) {
                                                                    $pax_breakup['CNN'] = array(
                                                                        'base_price' => $val,
                                                                        'total_price' => $val,
                                                                        'tax' => ($val - $val),
                                                                        'pass_no' => @$search_data['child']);
                                                                } else if (preg_match("/Infant/", $key) && array_key_exists('INF', $pax_breakup)) {
                                                                    $pax_breakup['INF'] = array(
                                                                        'base_price' => $val,
                                                                        'total_price' => $val,
                                                                        'tax' => ($val - $val),
                                                                        'tax1' => ($val - $val),
                                                                        'pass_no' => @$search_data['infant']);
                                                                }
                                                            } else if (array_key_exists('Government Service Tax', $price_array)) {
//                                                                  debug($price_array);
                                                                $price_array[preg_replace('!\s+!', '', $key)] = $price_array[$key] + $val;
//                                                                  echo 'inside if';
//                                                                  debug($price_array);
//                                                                  exit;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {

                                        $td_val = str_replace(',', '', trim($td->nodeValue));
                                        /* if(preg_match('/Government Service Tax/',$td_head) && array_key_exists('Government Service Tax',$price_array)) {
                                          $price_array1[str_replace('1Â  ','',$td_head)] = $price_array['Government Service Tax'] + $td_val;
                                          } else {

                                          $price_array1[str_replace('1Â  ','',$td_head)] = $td_val;
                                          } */
                                        // debug($price_array1);exit;

                                        if (preg_match("/Infant/", $td_head)) {
                                            $pax_breakup['INF'] = array(
                                                'base_price' => ($td_val),
                                                'total_price' => $td_val,
                                                'tax' => ($td_val - $td_val),
                                                'pass_no' => @$search_data['infant']);
                                        }
                                    }
                                    //else take nodevalue
                                }
                                $cnt++;
                            }
                        }
                    }
                }
            }
            $total_tax = 0;
            if (isset($price_array) && valid_array($price_array)) {
                if (Valid_array($price_array)) {
                    foreach ($price_array as $pk => $p_Ar) {

                        if (preg_match('/Adult/', $pk) != true && preg_match('/Child/', $pk) != true && preg_match('/Infant/', $pk) != true) {
                            $total_tax = $total_tax + $p_Ar;
//                          echo '<pre>';echo $pk;echo '<pre>';
                        }
                    }
                }
                $price['price_breakup'] = $price_array;
                $total_pax = @$search_data['adult'] + @$search_data['child'];
                $per_pax_tax = $total_tax / $total_pax;

                if (valid_array($pax_breakup)) {
                    foreach ($pax_breakup as $p_bk => $breakup) {
                        if (strcasecmp($p_bk, 'INF') != 0) {
                            $pax_breakup[$p_bk]['tax'] = $per_pax_tax;
                            $pax_breakup[$p_bk]['total_price'] = $pax_breakup[$p_bk]['base_price'] + $per_pax_tax;
                        }
                    }
                }
                $price['passenger_breakup'] = $pax_breakup;
            }
            //total price
            $h4_class = 'selet-total-price';
            $h4_tag = $price_xpath->query("//h4");
            if ($h4_tag->length > 0) {
                $h4_span = $h4_tag->item(0)->getElementsByTagName('span');
                if ($h4_span->length > 0) {
                    $span_tag = $h4_span->item(0)->nodeValue;
                    $price['total_price'] = str_replace(',', '', $span_tag);
                }
            }
        }

        return $price;
    }

    function get_headers_from_curl_response($response) {
        $headers = array();

        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

        foreach (explode("\r\n", $header_text) as $i => $line)
            if ($i === 0)
                $headers ['http_code'] = $line;
            else {
                list ( $key, $value ) = explode(': ', $line);

                $headers [$key] = $value;
            }

        return $headers;
    }

    function format_search_result_data($file_content, $search_data) {
        // echo 'fff';
        // debug($file_content);exit;
        // $headers = explode("\n", $file_content);
        // // echo "<pre>";print_r($headers);exit;
        // $cookie = $headers[8];
        // $cookie1 = explode(': ', $cookie);
        // echo 'jrer'.$cookie1[1];exit;
        $total_price = 0;
        $flight_array = array();
        $flight_final_arry = array();
        $operator_code = $this->carrier_code;
        $operator_name = 'Spicejet';
        $departure_date = date('d-m-Y', strtotime($search_data ['depature'])); //FIXME - date to be dynamic
        $CI = &get_instance();
        $flight_detail_arr = array();
        // $flight_detail_arr1 = array();
        $response = array();
        $response_new = array();
        $cache_city = array();

        if (isset($file_content) && !empty($file_content)) {
            $html = $file_content;
            $pokemon_doc = new DOMDocument ();
            $pokemon_doc->loadHTML($html);

            $classname = "availabilityTable margin-bottom-25 altRowItem";
            $pokemon_xpath = new DomXPath($pokemon_doc);
            $pokemon_pag = $pokemon_xpath->query("//table[@class='" . $classname . "']");
            // echo 'herre';
            // debug($pokemon_pag);exit;
            $row_cnt = 0;

            $flight_list ['journey_list'] = array();
            $journey_key = 0;
//          
            
            foreach ($pokemon_pag as $date_k => $__Dk) {

                 // debug($date_rows);exit;
                $flight_final_arry = array();
                // $flight_array = array();
                $header_content = array();
          
               
               
                $date_rows = $__Dk->getElementsByTagName('tr');
              
                if (isset($date_rows) && $date_rows->length > 0) {
                    foreach ($date_rows as $tr_k => $__tr) {
                        // echo 'hhhh';
                        // echo '<br/>';
                        $flight_no_arr = array();
                        $summary_cnt = 0;
                        $flight_detail = array();
                        $price_Array = array();

                        $_name = $__tr->getAttribute('name');
                        // debug($_name);
                        $tr_cls_out = $__tr->getAttribute('class');
                        if (isset($_name) && !empty($_name)) {
                            // echo 'nnggg';
                            // echo "<br/>";
                            $td_rows = $__tr->getElementsByTagName('td');
                            // echo 'herre'.debug($td_rows);exit;
                            if (isset($td_rows) && $td_rows->length > 0) {
                                $price_td_cnt = 0;
                                $journey_cnt = 0;
                                $price_tot_arr = array();
                                $price_count = 0;
                                foreach ($td_rows as $__td_k => $__td) {
                                    // echo 'ggg'.$journey_cnt;
                                    // echo '<br/>';
                                   
                                    // city-time-info div scraping
                                    $div = $__td->getElementsByTagName('div')->item(0);

                                    if (isset($div) && !empty($div)) {
                                        $class_name = $div->getAttribute('class');

                                        if ($class_name == 'city-time-info') {
                                            $inner_div = $div->getElementsByTagName('div');

                                            if (isset($inner_div) && $inner_div->length > 0) {
                                                foreach ($inner_div as $_ik => $__inner) {
                                                    $inr_cls = $__inner->getAttribute('class');

                                                    if (isset($inr_cls) && $inr_cls == 'deptStation') {
                                                        $nodeval = $__inner->nodeValue;
                                                        preg_match('/([0-9]+):([0-9]+)\ /', $nodeval, $match);
                                                        $departure_time = $match [0];

                                                        preg_match('/(AM)|(PM)/', $nodeval, $time);
                                                        $departure_time = $departure_time . ' ' . $time [0];
                                                        $departure_dt = $departure_date . ' ' . $departure_time;

                                                        $departure_dt = date('Y-m-d H:i', strtotime($departure_dt));

                                                        preg_match('^[a-zA-Z-\s]+$^', $nodeval, $location);
                                                        $origin = $city = trim(str_replace('PM', '', str_replace('AM', '', $location [0])));

                                                        // get airport code
                                                        if ($origin == 'Bengaluru') {
                                                            $city = 'Bangalore';
                                                        }
                                                        // $query = 'SELECT `airport_code` FROM `flight_airport_list` WHERE `airport_city` LIKE "%' . trim ( $city ) . '%" ';
                                                        // $origin_term = $CI->db->query ( $query )->row ();
                                                        if (isset($cache_city[$city]) == false) {
                                                            $origin_term = $CI->flight_model->get_airport_list($city)->row();

                                                            if (isset($origin_term->airport_code) && !empty($origin_term->airport_code)) {
                                                                $cache_city[$city] = $origin_term->airport_code;
                                                            } else {
                                                                $cache_city[$city] = $city;
                                                            }
                                                        }

                                                        // $origin = $city;
                                                    }
                                                    if (isset($inr_cls) && $inr_cls == 'arrvStation') {
                                                        $nodeval = $__inner->nodeValue;
                                                        preg_match('/([0-9]+):([0-9]+)\ /', $nodeval, $match);
                                                        $arrival_time = $match [0];

                                                        preg_match('/(AM)|(PM)/', $nodeval, $atime);
                                                        $arrival_time = $arrival_time . ' ' . $atime [0];

                                                        $arrival_dt = $departure_date . ' ' . $arrival_time;
                                                        $arrival_dt = date('Y-m-d H:i', strtotime($arrival_dt));

//                                                      $arrivalTime

                                                        preg_match('^[a-zA-Z-\s]+$^', $nodeval, $delocation);
                                                        $destination = $d_city = trim(str_replace('PM', '', str_replace('AM', '', $delocation [0])));

                                                        // get airport code
                                                        if ($destination == 'Bengaluru') {
                                                            $d_city = 'Bangalore';
                                                        }

                                                        if (isset($cache_city[$d_city]) == false) {

                                                            $destination_term = $CI->flight_model->get_airport_list_from_city_name($d_city)->row();
                                                            if (isset($destination_term->airport_code) && !empty($destination_term->airport_code)) {
                                                                $cache_city[$d_city] = $destination_term->airport_code;
                                                            } else {
                                                                $cache_city[$d_city] = $d_city;
                                                            }
                                                        }

                                                        $destination = $cache_city[$d_city];

                                                        // $query = 'SELECT `airport_code` FROM `flight_airport_list` WHERE `airport_city` LIKE "%' . trim ( $d_city ) . '%" ';
//                                                      $destination_term = $CI->flight_model->get_airport_list_from_city_name ( $d_city )->row ();
//                                                      if (isset ( $destination_term->airport_code ) && ! empty ( $destination_term->airport_code )) {
//                                                          $destination = $destination_term->airport_code;
//                                                      }
                                                        // $destination = $d_city;
                                                    }
                                                }
                                            }
                                            $journey_number = trim($origin) . '_' . trim($destination);
                                        }
                                        //
                                        $cabin_class = 'Economy';
                                        $no_of_stops = 0;
                                        $flight_number = '';
                                        $det_arr = array();

                                        $flight_summary = $this->format_summary_array($journey_number, $origin, $destination, $departure_dt, $arrival_dt, $operator_code, $operator_name, $flight_number, $no_of_stops, $cabin_class, $origin = '', $destination = '', $duration, $is_leg = true, $attr = array(), '', '', $this->carrier_code, '', $det_arr);
                                        
                                    }
                                    // span bold for flight numbers

                                    $span = $__td->getElementsByTagName('span');
                                    if (isset($span) && $span->length > 0) {
                                        foreach ($span as $s__k => $__span_no) {

                                            $span_class_l = $__span_no->getAttribute('class');
                                            //debug($span_class_l);exit;
                                            $span_c_node_v = $__span_no->nodeValue;
                                            if ($span_class_l == 'white-space-nowrap') {
                                                $flight_no_arr [] = trim(str_replace('SG', '', str_replace(',', '', $span_c_node_v)));
                                            } else if ($span_class_l == 'bold') {
                                                $flight_details_div = $__span_no->getElementsByTagName('div');
                                                if (isset($flight_details_div) && $flight_details_div->length > 0) {
                                                    foreach ($flight_details_div as $f_kk => $__flight_detail) {
                                                        $det_cls = $__flight_detail->getAttribute('class');
                                                        $stop_cnt = 0;
                                                        if ($det_cls == 'flightInfo') {
                                                            $p_tag_cntent = $__flight_detail->getElementsByTagName('p');
                                                            if (isset($p_tag_cntent) && $p_tag_cntent->length > 0) {
                                                                foreach ($p_tag_cntent as $__p_kkk => $__ptag) {
                                                                    $__p_tag_val_node = $__ptag->nodeValue;

                                                                    if (strpos($__p_tag_val_node, 'SG') !== false) {
                                                                        // if(in_array(trim(str_replace('SG','',$__p_tag_val_node)),$flight_no_arr)) {
                                                                        $flight_number_det = trim(str_replace('SG', '', $__p_tag_val_node));

                                                                        // }
                                                                    } else if (strpos($__p_tag_val_node, 'AM') !== false || strpos($__p_tag_val_node, 'PM') !== false) {
                                                                        $explode = explode('to', $__p_tag_val_node);

                                                                        preg_match('/([0-9]+):([0-9]+) (AM)|([0-9]+):([0-9]+) (PM)/', $explode [0], $time_Str);
                                                                        $departure_time = $departure_date . ' ' . $time_Str [0];
                                                                        $departure_time = date('Y-m-d H:i', strtotime($departure_time));

                                                                        $origin_Str = substr(trim($explode [0]), 0, 3);

                                                                        preg_match('/([0-9]+):([0-9]+) (AM)|([0-9]+):([0-9]+) (PM)/', $explode [1], $time_Str);
                                                                        $arrival_time = $departure_date . ' ' . $time_Str [0];
                                                                        $arrival_time = date('Y-m-d H:i', strtotime($arrival_time));

                                                                        $dest_Str = substr(trim($explode [1]), 0, 3);
                                                                        $journey_number_str = $origin_Str . '_' . $dest_Str;
                                                                        $stops = 0;
                                                                        $cabin_class = 'Economy';
                                                                        $flight_detail [] = $this->format_summary_array($journey_number_str, $origin_Str, $dest_Str, $departure_time, $arrival_time, $operator_code, $operator_name, $flight_number_det, $stops, $cabin_class, $origin = '', $destination = '', $duration = '', $is_leg = true, $attr = array(), '', '', $this->carrier_code, '', $flight_detail);
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                } // debug($flight_detail);exit;
                                                // exit;
                                            } else {
                                                if (strpos($span_c_node_v, 'Total Duration') !== false) {
                                                    $duration = trim(str_replace('Total Duration:', '', $span_c_node_v));
                                                } else {
//                                                  $flight_array[++$row_cnt] = array('flight_details'=>'Fare Sold Out');
//                                                  echo 'Fare Sold Out';
                                                }
                                            }
                                            if ($span_class_l == 'travel-duration') {

                                                $duration1 = preg_replace('#\s+#', ' ', $span_c_node_v);
                                            }
                                        }
//                                      debug($flight_summary);
//                                      $bg_dep_time = $flight_summary['origin']['datetime'];
//                                      $duration_seconds = '5h 10m';
//                                      $duration_seconds = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "$1:$2:00", $duration_seconds);
//                                      sscanf($duration_seconds, "%d:%d:%d", $hours, $minutes, $seconds);
//                                      debug($duration_seconds);
//                                      echo $hours;
//                                      echo '$hours';
//                                      echo $minutes;
//                                      echo '$minutes';
//                                      echo $seconds;
//                                      echo '$seconds';
//                                      $time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
//                                      $arrivalTime = date('Y-m-d', strtotime($bg_dep_time) + $time_seconds);
//                                      $duration = str_replace(array("\n", "\r"), ' ', preg_replace('!\s+!', ' ', $duration));
//                                      debug($bg_dep_time);
//                                      echo '$duration';
//                                      debug($duration);
//                                      debug($arrivalTime);
//                                      $arr_dt = $arrivalTime . ' ' .$flight_summary['destination']['time'];
//                                      debug($time_seconds);
//                                      echo '$arr_dt';
//                                      debug($arr_dt);
//                                      exit;
//                                      $flight_summary['destination']['datetime'] = $arr_dt;
                                        $flight_summary['duration'] = $duration1;
                                        $flight_summary['OperatorCode'] = 'SG';
                                        $flight_summary ['flight_number'] = @$flight_no_arr [0];
                                        $flight_number = @$flight_no_arr [0];
                                        $no_of_stops = COUNT(@$flight_no_arr);
                                    }
                                    // echo 'jjjjj';
                                    // debug($flight_summary);exit;
                                    $flight_array [$row_cnt] ['flight_details'] ['summary'] [$summary_cnt] = $flight_summary;
                                    $flight_array [$row_cnt] ['flight_details'] ['summary'] [$summary_cnt] ['flight_number'] = $flight_no_arr [0];
                                    $flight_array [$row_cnt] ['flight_details'] ['details'] [$summary_cnt] = $flight_detail;
                                    $flight_array [$row_cnt] ['flight_details'] ['summary'] [$summary_cnt] ['no_of_stops'] = COUNT($flight_detail) - 1;

                                    // price array
                                    $td_cls_b = $__td->getAttribute('class');

                                    // echo 'nmmm'.$td_cls_b;echo "<br/>";
                                    if (isset($td_cls_b) && !empty($td_cls_b) && strpos($td_cls_b, 'fareCol') !== false) {

                                        $td_fare_span = $__td->getElementsByTagName('span');

                                        if (isset($td_fare_span->length) && $td_fare_span->length > 0) {
                                           
                                            $clas = $td_fare_span->item(0);
                                            $class_cls = $clas->getAttribute('class');
                                            if (isset($class_cls) && $class_cls == 'flightfare') {
                                               
                                                $flight_fare = $clas->nodeValue;
                                                if (isset($flight_fare) && !empty($flight_fare)) {
                                                    $flight_fare = str_replace(',', '', str_replace('INR', '', $flight_fare));
                                                    $api_currency = explode(' ',$flight_fare);
//                          $price_test[] = $flight_fare;
                                                    $adult_count = $search_data['adult'];
                                                    $child_count = $search_data['child'];
                                                    $infant_count = $search_data['infant'];

                                                    if ($search_data['is_domestic'] == true) {
                                                        $infant_fare = '1260';
                                                    } else {
                                                        //FIXME
                                                        $infant_fare = '2501';
                                                    }

                                                    $per_pax_fare = $flight_fare;
                                                    $flight_fare = ($adult_count * $per_pax_fare) + ($child_count * $per_pax_fare) + ($infant_count * $infant_fare);

                                                    if ($price_td_cnt == 0) {

                                                        $price_obj = $this->get_price_object();
                                                        $price_obj ['api_currency'] = $api_currency[1];
                                                        $price_obj ['api_total_display_fare'] = @$flight_fare;
                                                        $price_obj ['total_breakup'] ['api_total_fare'] = @$flight_fare;
                                                    }

                                                    $price_td_cnt ++;
                                                }

                                               
                                                
                                                // echo "<br/>";
                                                // echo 'herre'.$journey_cnt;
                                                // echo "<br/>";
                                                $par_in = $__td->getElementsByTagName('p');
                                                $par_class = $par_in->item(0)->getAttribute('productclass');
                                                if ($par_class == 'Regular Saver' || $par_class == 'Summer Sale' || $par_class == 'RT') {
                                                    // journey_key
                                                    $journey_key_input = $__td->getElementsByTagName('input');
                                                    

                                                    $journey_api_key = $journey_key_input->item(0)->getAttribute('value');
                                                    $journey_api_key = str_replace(' ', '+', $journey_api_key);
                                                    //$journey_api_key = str_replace('|', '%7C', $journey_api_key);
                                                    $journey_api_key = str_replace('/', '%2F', $journey_api_key);
                                                    $journey_api_key = str_replace(':', '%3A', $journey_api_key);

                                                    // echo 'dddd';
                                                    // FIX IT for multi pax

                                                    $ajax_response = $this->get_fare_details_scrap($journey_api_key, $search_data);
                                                    // $ajax_response ='';
                                                    $price_tot_arr[] = $ajax_response;
//                                                   debug($ajax_response);exit;
                                                    $flight_array [$row_cnt] ['key'] [$journey_number] ['JourneySellKey'] [0] = $journey_api_key;
                                                    $flight_array [$row_cnt] ['token'] = serialized_data($flight_array [$row_cnt] ['key']);
                                                    $flight_array [$row_cnt] ['token_data'] = '';
                                                    $flight_array [$row_cnt] ['token_key'] = md5($flight_array [$row_cnt] ['token']);
                                                    $flight_array [$row_cnt] ['price_key'] = $journey_api_key;
                                                }
                                              
                                             $journey_cnt ++;  
                                            }

                                        }
                                       
                                    }
                                    $price_count++;
                                }
                                // exit;

                                if (valid_array($price_tot_arr)) {
                                    for ($i = 0; $i < COUNT($price_tot_arr); $i++) {
                                        $price_tot_arr[$i]['attr'] = @$header_content[$i];


                                        //  if (preg_match('/Special Fares/', $header_content[$i]) == true) {

                                        $flight_array [$row_cnt]['price_breakup'] = $price_tot_arr[$i];

                                        $pass_brek_upi = $flight_array [$row_cnt]['price_breakup']['passenger_breakup'];
                                        $total_bs_fr = 0;
                                        if (valid_array($pass_brek_upi)) {
                                            foreach ($pass_brek_upi as $u_key => $u_det) {
                                                $total_bs_fr += $u_det['base_price'];
                                            }
                                        }


                                        //                                      debug($price_tot_arr[$i]);
                                        $price_obj = $this->get_price_object();
                                        $api_total_tax = $price_tot_arr[$i]['total_price'] - $total_bs_fr;
                                        $price_obj ['api_currency'] = $api_currency[1];
                                        $price_obj ['api_total_display_fare'] = $price_tot_arr[$i]['total_price'];
                                        $price_obj ['total_breakup'] ['api_total_tax'] = @$api_total_tax;
                                        $price_obj ['total_breakup'] ['api_total_fare'] = $total_bs_fr;
                                        $price_obj ['price_breakup']['basic_fare'] = $total_bs_fr;

                                        $flight_array [$row_cnt] ['price'] = $price_obj;
                                        $flight_array [$row_cnt] ['passenger_breakup'] = $price_tot_arr[$i]['passenger_breakup'];
                                        $flight_array [$row_cnt] ['fare'] [0] = $price_obj;
                                        $flight_array [$row_cnt] ['attr'] ['tag'] = substr($price_tot_arr[$i]['attr'], 0, 50);
                                        $flight_array [$row_cnt] ['attr'] ['operator'] = 'Spicejet';
                                        $flight_array [$row_cnt]['attr']['isrefundable'] = 'Refundable';
                                        // debug($flight_array);exit;
                                        $flight_final_arry[] = $flight_array [$row_cnt];
                                        //  } else {
                                        //  unset($flight_array [$row_cnt]);
                                        //  }
                                        //  debug($flight_array);exit;
//                                      debug($flight_array [$row_cnt] ['attr'] ['tag']);
//                                          echo '<pre>';
//                                          debug($flight_array);
//                                      if(isset($flight_array [$row_cnt] ['attr'] ['tag']) && preg_match('/Hand Baggage Only/',$flight_array [$row_cnt] ['attr'] ['tag']) != false) {
//                                          echo 'if';debug($flight_array [$row_cnt] ['attr'] ['tag']);
//                                          $summary = $flight_array [$row_cnt]['flight_details']['summary'];
//                                          if(isset($summary) && valid_array($summary)) {
//                                              foreach($summary as $__sk => $summary_arr) {
//                                                  $flight_array [$row_cnt]['flight_details']['summary'][$__sk]['display_operator_code'] = 'SGH';
//                                              }
//                                          }
//                                          $details = $flight_array [$row_cnt]['flight_details']['details'];
//                                          if(isset($details) && valid_array($details)) {
//                                              foreach($details as $__dk => $f_details_arr) {
//                                                  if(valid_array($f_details_arr)) {
//                                                      foreach($f_details_arr as $__fk => $detail) {
//                                                          $flight_array [$row_cnt]['flight_details']['details'][$__dk][$__fk]['display_operator_code'] = 'SGH';
//                                                      }
//                                                  }
//                                              }
//                                          }
//                                          
//                                      }
                                    }
                                }

//                              $flight_array [$row_cnt]['price'] = $price_tot_arr;
                            }
                            $row_cnt ++;
                            $summary_cnt ++;
                        } else if (isset($tr_cls_out) && $tr_cls_out == 'thheadingbg2') {
                            $th_tag = $__tr->getElementsByTagName('th');
//                          debug($th_tag);exit;
                            if ($th_tag->length > 0) {
                                foreach ($th_tag as $th) {
                                    $th_Tag_class = $th->getAttribute('class');
                                    if (preg_match('/fare/', $th_Tag_class)) {
                                        $th_content = $th->nodeValue;
                                        $header_content[] = $th_content;
                                    }
                                }
                            }
                        }
                    }
                } 
                if(valid_array($flight_final_arry)) {
                    foreach($flight_final_arry as $f____k => $___flight) {
                        if(preg_match('/Hand Baggage/',$___flight['attr']['tag']) == true) {
                            $summary__f = $flight_final_arry[$f____k]['flight_details']['summary'];
                            foreach($summary__f as $__sk => $__summary ) {
                                $flight_final_arry[$f____k]['flight_details']['summary'][$__sk]['display_operator_code'] = 'SGH';
                            }
                            $details_f = $flight_final_arry[$f____k]['flight_details']['details'];
                            foreach($details_f as $__fk => $__f_Details) {
                                foreach($__f_Details as $__fgk => $__fdetail) {
                                    $flight_final_arry[$f____k]['flight_details']['details'][$__fk][$__fgk]['display_operator_code'] = 'SGH';
                                }
                            }
                        }
                    }
                }
                
              // debug($flight_array);exit;
                // $label = $date_rows->getElementsByTagName('label');
                // $travel_date = @$label->item(0)->nodeValue;
                // debug($flight_array);
                if(valid_array($flight_final_arry)) {
                    $flight_detail_arr ['journey_list'] [$journey_key] = $flight_final_arry;
                }
                
                $flight_array = array ();
                $journey_key ++;
              
               
            // }
        }
        }
        
        if (isset($flight_detail_arr)) {
            $response = $flight_detail_arr;
        }
        // debug($flight_detail_arr);exit;
        // debug($flight_detail_arr);exit;
        // If Round way international then make combinations else return as it is.
        if ((empty($search_data ['is_domestic']) == true && $search_data ['trip_type'] == 'return') == true && !empty($flight_detail_arr)) {
            // echo 'nfffd';exit;
            // Combine data
            $response['flight_data_list']['journey_list'] [0] = Common_Api_Flight::form_flight_combination($flight_detail_arr ['journey_list'] [0], $flight_detail_arr ['journey_list'] [1]);
        } elseif ($search_data ['trip_type'] == 'multicity' && !empty($flight_detail_arr)) {
            // Need to do dynamic combination
            exit();
            Common_Api_Grind::form_flight_combination($flight_detail_arr ['journey_list'] [0], $flight_detail_arr ['journey_list'] [1]);
        } else {
            if (valid_array($flight_detail_arr)) {

                $response ['flight_data_list'] = $flight_detail_arr;
            }
        }
        // echo 'fbbfb';
        // debug($response);exit;
        return $response;
    
}

    /**
     * Returns price default price object
     */
    function get_price_object() {
        $price_obj = array(
            "Currency" => false,
            "TotalDisplayFare" => 0,
           
            "PriceBreakup" => array(
                'BasicFare' => 0,
                'Tax' => 0,
                'AgentCommission' => 0,
                'AgentTdsOnCommision' => 0
            )
        );
        return $price_obj;
    }

    /**
     *
     * @param unknown_type $search_id           
     */
    function search_data($search_id) {
        $response ['status'] = true;
        $response ['data'] = array();
        $CI = & get_instance();
        if (empty($this->master_search_data) == true and valid_array($this->master_search_data) == false) {
            $clean_search_details = $CI->flight_model->get_safe_search_data($search_id);
            if ($clean_search_details ['status'] == true) {
                $response ['status'] = true;
                $response ['data'] = $clean_search_details ['data'];
                // 28/12/2014 00:00:00 - date format
                /*
                 * $response['data']['from'] = substr(chop(substr($clean_search_details['data']['from'], -5), ')'), -3);
                 * $response['data']['to'] = substr(chop(substr($clean_search_details['data']['to'], -5), ')'), -3);
                 */
                $response ['data'] ['from_city'] = $clean_search_details ['data'] ['from'];
                $response ['data'] ['to_city'] = $clean_search_details ['data'] ['to'];
                $response ['data'] ['depature'] = date("Y-m-d", strtotime($clean_search_details ['data'] ['depature'])) . 'T00:00:00';
                $response ['data'] ['return'] = date("Y-m-d", strtotime($clean_search_details ['data'] ['return'])) . 'T00:00:00';
                switch ($clean_search_details ['data'] ['trip_type']) {

                    case 'oneway' :
                        $response ['data'] ['type'] = 'OneWay';
                        break;

                    case 'circle' :
                        $response ['data'] ['type'] = 'Return';
                        $response ['data'] ['return'] = date("Y-m-d", strtotime($clean_search_details ['data'] ['return'])) . 'T00:00:00';
                        break;

                    default :
                        $response ['data'] ['type'] = 'OneWay';
                }
                $response ['data'] ['adult'] = $clean_search_details ['data'] ['adult_config'];
                $response ['data'] ['child'] = $clean_search_details ['data'] ['child_config'];
                $response ['data'] ['infant'] = $clean_search_details ['data'] ['infant_config'];
                $response ['data'] ['ac_total_pax'] = ($clean_search_details ['data'] ['total_pax'] - $clean_search_details ['data'] ['infant_config']);
                $response ['data'] ['total_pax'] = $clean_search_details ['data'] ['total_pax'];
                $response ['data'] ['v_class'] = $clean_search_details ['data'] ['v_class'];
                $response ['data'] ['carrier'] = implode($clean_search_details ['data'] ['carrier']);
                $this->master_search_data = $response ['data'];
            } else {
                $response ['status'] = false;
            }
        } else {
            $response ['data'] = $this->master_search_data;
        }
        $this->search_hash = md5(serialized_data($response ['data']));
        return $response;
    }

    function update_markup_currency(&$price_summary, &$currency_obj) {
        
    }

    function total_price($price_summary) {
        
    }

    //function process_booking($book_id, $booking_params) {
    function process_booking($booking_params, $app_reference, $sequence_number, $search_id) {
        // debug($booking_params);exit;
        $this->save_flight_ticket_details($booking_params, $app_reference, $sequence_number, $search_id);
       
        $CI = &get_instance();
        // update booking status
        // return success
        $response ['status'] = SUCCESS_STATUS;
       
        $flight_booking_status = 'BOOKING_HOLD';
        $CI->common_flight->update_flight_booking_status($flight_booking_status, $app_reference, $sequence_number, $this->booking_source);
       
        return $response;
    }
    private function save_flight_ticket_details($booking_params, $app_reference, $sequence_number, $search_id){
        $CI = &get_instance();
        $flight_booking_transaction_details_fk = $CI->custom_db->single_table_records('flight_booking_transaction_details', 'origin', array('app_reference' => $app_reference, 'sequence_number' => $sequence_number));
        $flight_booking_transaction_details_fk = $flight_booking_transaction_details_fk['data'][0]['origin'];
        $passenger_details = $CI->custom_db->single_table_records('flight_booking_passenger_details', '', array('app_reference' => $app_reference));
        $passenger_details =$passenger_details['data'];
        // debug($passenger_details);exit;
        $itineray_price_details = $booking_params['flight_data']['Price'];
        $airline_code = 'SG1';
        $flight_price_details = $CI->common_flight->final_booking_transaction_fare_details($itineray_price_details, $search_id, $this->booking_source, $airline_code);
        // debug($flight_price_details);exit;
        $fare_details = $flight_price_details['Price'];
        $fare_breakup = $flight_price_details['PriceBreakup'];
        $passenger_breakup = $fare_breakup['passenger_breakup'];
        $single_pax_fare_breakup = $CI->common_flight->get_single_pax_fare_breakup($passenger_breakup);
        

        // $passenger_details = force_multple_data_format($passenger_details);
        $get_passenger_details_condition = array();
        $get_passenger_details_condition['flight_booking_transaction_details_fk'] = $flight_booking_transaction_details_fk;
        $passenger_details_data = $GLOBALS['CI']->custom_db->single_table_records('flight_booking_passenger_details', 'origin, passenger_type', $get_passenger_details_condition);
        $passenger_details_data = $passenger_details_data['data'];
        $passenger_origins = group_array_column($passenger_details_data, 'origin');
        $passenger_types = group_array_column($passenger_details_data, 'passenger_type');
        // echo 'mnngng';
        // debug($passenger_details);exit;
        foreach ($passenger_details as $pax_k => $pax_v) {
            $passenger_fk = intval(array_shift($passenger_origins));
            $pax_type = array_shift($passenger_types);
            
            switch ($pax_type) {
                case 'Adult':
                
                    $pax_type = 'ADT';
                    break;
                case 'Child':
                    $pax_type = 'CHD';
                    break;
                case 'Infant':
                    $pax_type = 'INF';
                    break;
            }
            $ticket_id = '';
            $ticket_number = '';
            
           
            //Update Passenger Ticket Details
            $CI->common_flight->update_passenger_ticket_info($passenger_fk, $ticket_id, $ticket_number, $single_pax_fare_breakup[$pax_type]);
        }
    }
    function get_fare_details($access_key) {
        $response ['data'] = array();

        $CI = & get_instance();
        $data = $CI->custom_db->single_table_records('flight_fare_rules', 'fare_rule', array(
            'operator' => $this->carrier_code
        ));
        $response ['data'] = $data ['data'] [0] ['fare_rule'];
        $response ['status'] = SUCCESS_STATUS;
        return $response;
    }

    /**
     * Search Request
     * @param unknown_type $search_id
     */
    public function get_search_request($search_id) {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        /* get search criteria based on search id */
        $search_data = $this->search_data($search_id);



        if ($search_data ['status'] == SUCCESS_STATUS) {
            // Flight search RQ
            $search_request = $this->search_request($search_data ['data']);

            if ($search_request ['status'] = SUCCESS_STATUS) {
                $response ['status'] = SUCCESS_STATUS;
                $curl_request = $this->form_curl_params($search_request ['request'], $search_request ['url'], $search_request ['soap_action'], $search_request['header'], $search_request['cookie']);

                $response ['data'] = $curl_request['data'];
            }
        }

        return $response;
    }

    /**
     * process soap API request
     *
     * @param string $request
     */
    function form_curl_params($request, $url, $soap_action = '', $header, $cookie) {
        $data['status'] = SUCCESS_STATUS;
        $data['message'] = '';
        $data['data'] = array();

        $curl_data = array();
        $curl_data['booking_source'] = $this->booking_source;
        $curl_data['request'] = $request;
        $curl_data['url'] = $url;
        $curl_data['header'] = $header;
        $curl_data['cookie'] = $cookie;

        $data['data'] = $curl_data;
        return $data;
    }
    
    
    # New Format for Travelomatix
    
    function format_search_data_response($search_result, $search_data)
    {
        
        $trip_type = isset ( $search_data ['is_domestic'] ) && ! empty ( $search_data ['is_domestic'] ) ? 'domestic' : 'international';
        $Results = $search_result['flight_data_list'] ['journey_list'];
        // echo 'test';
        // debug($Results);exit;
        $flight_list = array ();
        $TraceId = $search_result['Response']['TraceId'];
        foreach ($Results as $result_k => $result_v) {
            foreach ($result_v as $journey_array_k => $journey_array_v) {
                // debug($journey_array_v);exit;
                $flight_details = array ();
                $key = array ();
                $key['key'][$journey_array_k]['booking_source'] = $this->booking_source;
                $key['key'][$journey_array_k]['TraceId'] = $TraceId;
                                
                $flight_details = $this->flight_segment_summary($journey_array_v, $journey_array_k, $key);
//echo 'I am HERE',debug($flight_details);exit;
                if(valid_array($flight_details)) {
                    foreach($flight_details as $f__key => $flight_detail) {
                        $flight_list['JourneyList'] [$result_k] [] = $flight_detail;
                    }
                }
            }
        }
        $response ['FlightDataList'] = $flight_list;
        return $response;
    }
        /**
     * Get flight details only
     *
     * @param array $segment
     */
    private function flight_segment_summary($journey_array, $journey_number, & $key, $cache_fare_object = false)
    {
        // echo 'nffee';
        // debug($journey_array);exit;
        $summary = array ();
        $flight_details = array ();
        $price_details = array ();
        $final_flight = array();
        // Loop on data to form details array
        $details = array ();
        $flightNumberList = array ();
        $segment_intl = array ();
        $FareSellKey = array ();
        $fare_object = array ();
        $core_fare_object = $journey_array['fare'];
        $core_fare_break_down_object = $journey_array['passenger_breakup'];
        $itineray_price['Fare'] = $core_fare_object;
        $itineray_price['FareBreakdown'] = $core_fare_break_down_object;
        $segments = $journey_array['flight_details']['details'];
        $IsRefundable = $journey_array['IsRefundable'];
        $ResultIndex = $journey_array['ResultIndex'];
        $IsLCC = $journey_array['IsLCC'];
        $AirlineRemark = $journey_array['AirlineRemark'];
        
        foreach ( $segments as $k => $v ) {
            // legs Loop
            $legs = force_multple_data_format ($v);
                        
            $is_leg = true;
            $attr = array ();
            foreach ( $legs as $l_k => $l_v ) {
                
                $origin_code = $l_v ['Origin']['AirportCode'];
                $destination_code = $l_v ['Destination']['AirportCode'];
                $departure_dt = db_current_datetime ( $l_v ['Origin']['DateTime'] );
                $departure_date =  explode(' ', $l_v ['Origin']['DateTime']);
                $arrival_dt = db_current_datetime ( $l_v ['Destination']['DateTime'] );

                $attr['Baggage'] = '15 Kilograms';
                $attr['CabinBaggage'] = '0';
                $attr['AvailableSeats'] = '0';
                $attr[$origin_code.'_'.$destination_code] = $departure_date[0];
                if(isset($l_v['NoOfSeatAvailable']) == true){
                    $attr['AvailableSeats'] = $l_v['NoOfSeatAvailable'];
                }
                if(isset($l_v['AirlinePNR']) == true) {
                    $attr['AirlinePNR'] = $l_v['AirlinePNR'];//In Ticket Method and GetBooking Details We will get AirlinePNR
                }
                $no_of_stops = 0;
                $cabin_class = $l_v['CabinClass'];
                $operator_code = $l_v['OperatorCode'];
                $operator_name = $l_v['OperatorName'];
                $flight_number = $l_v['FlightNumber'];

                $details[$k][] = $this->format_summary_array ( $journey_number, $origin_code, $destination_code, $departure_dt, $arrival_dt, $operator_code, $operator_name, $flight_number, $no_of_stops, $cabin_class, '', '', '', $is_leg, $attr );
                                
                $flightNumberList [] = $l_v['Airline']['FlightNumber'];
                $is_leg = false;
            }
        }
        //Fare
        $price = $this->format_itineray_price_details($itineray_price);
        // echo 'hggg';
        // debug($journey_array);exit;
        $flight_details ['Details'] = $details;
        $flight_ar ['FlightDetails'] = $flight_details;
        $flight_ar ['Price'] = $price;
        $flight_ar ['Price_Key'] = $journey_array['price_key'];
        $token_data[0]['booking_source'] = $this->booking_source;
        
        $token_data[0][0] = $flight_ar;
        $token_data[0][0]['booking_source'] = $this->booking_source;
        // debug($flight_ar);exit;
        $token_data[0]['ResultToken'] = serialized_data($token_data);
       
        $flight_ar ['ResultToken'] = serialized_data($token_data);

        if($cache_fare_object == true){
            $key ['key'] [$journey_number]['Fare'] = $core_fare_object;
            $key ['key'] [$journey_number]['FareBreakdown'] = $core_fare_break_down_object;
        }
        $key ['key'] [$journey_number]['ResultIndex'] = $ResultIndex;
        $key ['key'] [$journey_number]['IsLCC'] = $IsLCC;
        $token_data [0] = $flight_ar;
        // $token_data [0]['booking_source'] = $this->booking_source;
        // $token_data [0]['booking_source'] = $this->booking_source;
        // $flight_ar ['ResultToken']  = serialized_data($token_data);
        $is_refundable = $IsRefundable;
        $flight_ar ['Attr']['IsRefundable'] = $is_refundable;
        $flight_ar ['Attr']['AirlineRemark'] = $AirlineRemark;
        $final_flight[] = $flight_ar;
        $response = $final_flight;
        // debug($response);exit;
        return $response;
    }
        /**
     * Formates Itineray Price Details
     * @param unknown_type $itineray_price
     */
    private function format_itineray_price_details($itineray_price, $ticket_details_fare = false)
    {
            
        if($ticket_details_fare){
            $itineray_price['FareBreakdown'] = $this->format_ticket_detail_fare_breakdown($itineray_price['FareBreakdown']);
        }
       
        $price = array();
        $passenger_breakup = array();
        if(count($itineray_price['Fare']) > 1){
            $OtherCharges = $itineray_price['Fare'][0]['price_breakup']['handling_charge']+$itineray_price['Fare'][1]['price_breakup']['handling_charge'];
            $currency_code = $itineray_price['Fare'][0]['api_currency'];
       
            $base_fare = $itineray_price['Fare'][0]['price_breakup']['basic_fare']+$itineray_price['Fare'][1]['price_breakup']['basic_fare'];
            $tax = ($itineray_price['Fare'][0]['total_breakup']['api_total_tax']+$itineray_price['Fare'][1]['total_breakup']['api_total_tax']+$OtherCharges);//FIXME: check where to add Other Charges
            $total_fare = $itineray_price['Fare'][0]['api_total_display_fare']+$itineray_price['Fare'][1]['api_total_display_fare'];
            $agent_commission = ($itineray_price['Fare'][0]['api_total_display_fare']+$itineray_price['Fare'][1]['api_total_display_fare'])-($itineray_price['Fare'][1]['api_total_display_fare']+$itineray_price['Fare'][0]['api_total_display_fare']);

        }
        else{
            $OtherCharges = $itineray_price['Fare'][0]['price_breakup']['handling_charge'];
            $currency_code = $itineray_price['Fare'][0]['api_currency'];
       
            $base_fare = $itineray_price['Fare'][0]['price_breakup']['basic_fare'];
            $tax = ($itineray_price['Fare'][0]['total_breakup']['api_total_tax']+$OtherCharges);//FIXME: check where to add Other Charges
            $total_fare = $itineray_price['Fare'][0]['api_total_display_fare'];
            $agent_commission = ($itineray_price['Fare'][0]['api_total_display_fare']-$itineray_price['Fare'][0]['api_total_display_fare']);

        }
      
        $pax_fare_breakdown = $itineray_price['FareBreakdown'];
        $pax_wise_other_charges = $this->pax_wise_othercharges($pax_fare_breakdown, $OtherCharges);
        
        foreach ($pax_fare_breakdown as $k => $v) {
                    
            $pax_type = $this->get_passenger_type($k);
            $pax_count = $v['pass_no'];
            $pax_base_fare = $v['base_price'];
            $pax_tax = ($v['tax']+($pax_wise_other_charges*$pax_count));//FIXME: check where to add Other Charges
            $pax_total_fare = ($pax_base_fare+$pax_tax);
                
            $passenger_breakup[$pax_type]['BasePrice'] = $pax_base_fare;
            $passenger_breakup[$pax_type]['Tax'] = $pax_tax;
            $passenger_breakup[$pax_type]['TotalPrice'] = $pax_total_fare;
            $passenger_breakup[$pax_type]['PassengerCount'] = $pax_count;
        }
        $AgentCommission = roundoff_number($agent_commission, 2);
        $AgentTdsOnCommision = roundoff_number((($agent_commission*5)/100), 2);//Calculate it from currency library
        
        //Assigning to Fare Object
        $price = $this->get_price_object ();
        $price['Currency'] = $currency_code;
        $price['TotalDisplayFare'] = $total_fare;
        $price['PriceBreakup']['Tax'] = $tax;
        $price['PriceBreakup']['BasicFare'] = $base_fare;
        $price['PriceBreakup']['AgentCommission'] = $AgentCommission;
        $price['PriceBreakup']['AgentTdsOnCommision'] = $AgentTdsOnCommision;
        // debug($price);exit;
        $price['PassengerBreakup'] = $passenger_breakup;        
        
        return $price;
    }
        /**
     * Formates Ticket Fare Details
     * @param unknown_type $FareBreakdown
     */
    private function format_ticket_detail_fare_breakdown($FareBreakdown)
    {
        $pax_type = array_column($FareBreakdown, 'PaxType');
        $pax_type = array_count_values($pax_type);
        
        $formatted_fare_breakdown = array();
        $stored_pax_type = array();
        foreach ($FareBreakdown as $k => $v){
            $PassengerType = $v['PaxType'];
            if(in_array($PassengerType, $stored_pax_type) == false){
                array_push($stored_pax_type, $PassengerType);
                $PassengerCount = $pax_type[$PassengerType];
                
                $formatted_fare_breakdown[$k]['BaseFare'] = ($v['Fare']['base_price']*$PassengerCount);
                $formatted_fare_breakdown[$k]['Tax'] = ($v['Fare']['tax']*$PassengerCount);
                $formatted_fare_breakdown[$k]['PassengerType'] = $PassengerType;
                $formatted_fare_breakdown[$k]['PassengerCount'] = $PassengerCount;
            }
        }
        return $formatted_fare_breakdown;
    }
        /**
     * Calculates Passenger-Wise Other Charges
     *
     * @param unknown_type $pax_fare_breakdown
     * @param unknown_type $OtherCharges
     */
    private function pax_wise_othercharges($pax_fare_breakdown, $OtherCharges)
    {
        $pax_wise_other_charges = 0;
        $total_pax_count = 0;
        foreach ($pax_fare_breakdown as $k => $v) {
            $total_pax_count += $v['PassengerCount'];
        }
        $pax_wise_other_charges = round(($OtherCharges/$total_pax_count), 3);
        return $pax_wise_other_charges;
    }
        /**
     * Rerurns Passenger Type
     * @param unknown_type $PassengerType
     */
    private function get_passenger_type($PassengerType)
    {
            
        $type = '';
        switch($PassengerType) {
            case 1;
            $type = 'ADT';
            break;
            case 2;
            $type = 'CNN';
            break;
            case 3;
            $type = 'INF';
            break;
        }
                //debug($type);exit;
        return $PassengerType;
    }
    
    public function get_update_fare_quote($request) {
        $booking_source = $request['booking_source'];
        $result_token = $request['ResultToken'];
        unset($request['booking_source']);
        $request['0']['booking_source'] = $booking_source;
        unset($request['ResultToken']);
        $request['0']['ResultToken'] = $result_token;
        
        if (valid_array($request) == true ) {

            if(isset($request[0]['FlightDetails'])){
               
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['FareQuoteDetails']['JourneyList'][0] = $request;
            }
            else {
                $response ['message'] = 'Not Available';
            }
        }
        else{
            $response ['status'] = FAILURE_STATUS;
        }
    
    return $response; 
    }
    public function get_extra_services($request, $search_id){
        // echo 'herre'.$search_id;exit;
       
        error_reporting(0);

        $CI = &get_instance();
        $price_data['search_id'] = $search_id;
        $search_data = $this->search_data($search_id);
        $search_data = $search_data['data'];
        
        if($search_data['trip_type'] == 'oneway'){
            $price_data['price_key'] = $request['0']['Price_Key'];
            $price_key = $request['0']['Price_Key'];
            $price_key1 = str_replace('~', '%7E', $price_key);
            $price_key1 = str_replace('|', '%7C', $price_key);
            $price_key1 = str_replace('^', '%5E', $price_key1);
        }
        else if(($search_data['trip_type'] =='return') && ($search_data['is_domestic'] == 1)){
            // echo 'nghhg';exit;
            $price_count = $CI->flight_model->get_price_data($search_id);
            // debug($price_count);exit;
            if(count($price_count) > 2){
                $CI->flight_model->delete_price_key($search_id);
               
            }

            if(count($price_count) <= 2){
                $CI->flight_model->inesrt_price_key($price_data);
            }

            $get_price_data = $CI->flight_model->get_price_data($search_id);
            if(count($get_price_data) == 2){
               
                $price_key1 = $get_price_data[0]['price_key'];
                $price_key2 = $get_price_data[1]['price_key'];
                $price_key1 = str_replace('~', '%7E', $price_key1);
                $price_key1 = str_replace('|', '%7C', $price_key1);
                $price_key1 = str_replace('^', '%5E', $price_key1);
                $price_key2 = str_replace('~', '%7E', $price_key2);
                $price_key2 = str_replace('|', '%7C', $price_key2);
                $price_key2 = str_replace('^', '%5E', $price_key2);
            }
            $get_price_key = $CI->flight_model->get_price_key();
            // debug($get_price_key);exit;  
        }
        else if(($search_data['trip_type'] =='return') && empty($search_data['is_domestic']) == true){
            $price_key = $request['0']['Price_Key'];
            $price_key = explode('&&', $price_key);
            $price_key1 = $price_key[0];
            $price_key1 = str_replace('~', '%7E', $price_key1);
            $price_key1 = str_replace('|', '%7C', $price_key1);
            $price_key1 = str_replace('^', '%5E', $price_key1);
            
            $price_key2 = $price_key[1];
            $price_key2 = str_replace('~', '%7E', $price_key2);
            $price_key2 = str_replace('|', '%7C', $price_key2);
            $price_key2 = str_replace('^', '%5E', $price_key2);
            // echo $price_key1;
            // echo "<br/>";
            // echo $price_key2;
        }
        // exit;
      
        // debug($search_data);exit;
        $from = $search_data ['from'];
        $to = $search_data ['to'];

        $explode_from_city = explode(' ', $search_data ['from']);
        $from_city = @$explode_from_city [0] + @$explode_from_city [1];

        $explode_to_city = explode(' ', $search_data ['to']);
        $to_city = @$explode_to_city [0] + @$explode_to_city [1];

        $adult = $search_data ['adult'];
        $child = $search_data ['child'];
        $infant = $search_data ['infant'];
        $family_fare = 'false';
        if ($child > 0) {
            $family_fare = 'true';
        }
        $departure = date('d-M-Y', strtotime($search_data ['depature']));
        $departure1 = date('d-m-Y', strtotime($search_data ['depature']));
        $departure2 = str_replace('-', '%2F', $departure1);
        $day = date('d', strtotime($search_data ['depature']));
        $month = date('m', strtotime($search_data ['depature']));
        $year = date('Y', strtotime($search_data ['depature']));
        $year_month = date('Y-m', strtotime($search_data ['depature']));
        $day_month = date('d-m', strtotime($search_data ['depature']));
        $day_month1 = str_replace('-', '%2F', $day_month);
        if($search_data['trip_type'] == 'return'){
            $reurndate = date('d-M-Y', strtotime($search_data ['return']));
            $reurndate1 = date('d-m-Y', strtotime($search_data ['return']));
            $reurndate2 = str_replace('-', '%2F', $reurndate1);
            $rdate = $reurndate;
            $rdate1 = date('d-m-Y', strtotime($search_data ['return']));
           
        }
        else{

            $rdate = strtotime("+7 day", strtotime($departure1)); 
            $rdate1 = '--NaN';
           
        }
        
        $rday = date('d', $rdate);
        $rmonth = date('m',$rdate);
        $ryear = date('Y',$rdate);
       
        $ryear_month = date('Y-m', $rdate);
        $rday_month = date('d-m', $rdate);
        $rday_month1 = str_replace('-', '%2F', $rday_month);

        $from_city_val = $CI->flight_model->get_airport_city_name($search_data['from']);
        //debug($from_city_val);exit;
        $from_city_data = $from_city_val->airport_city.'('.$from_city_val->airport_code.')';
        $from_city_data1 = str_replace('(', '+%28', $from_city_data);
        $from_city_data1 = str_replace(')', '%29', $from_city_data1);
        $to_city_val = $CI->flight_model->get_airport_city_name($search_data['to']);
        $to_city_data = $to_city_val->airport_city.'('.$to_city_val->airport_code.')';
        $to_city_data1 = str_replace('(', '+%28', $to_city_data);
        $to_city_data1 = str_replace(')', '%29', $to_city_data1);
        $date_range = str_replace('|', '%7C', '0|0');

        if ($search_data ['trip_type'] == 'oneway') {
            $trip_type = 'OneWay';
        } else {
            $trip_type = 'RoundTrip';
        }
        $today_date = date("d-m-Y");
        $today_day = date("d");
        $today_mon_year = date("Y-m");
        $today_month = date("m");
        $today_year = date("Y");
        $date = strtotime($today_date);
        $date = strtotime("+7 day", $date);
        $weekdate = date('d-m-Y', $date);
        // echo 'herre'.$weekdate;exit;
        $weekday = date("d", strtotime($weekdate));

        $weekday_mon_year = date("m-Y", strtotime($weekdate));

        // debug($request);exit;

        $post_params = '__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=%2FwEPDwUBMGRkMSHDB101%2B52k3J%2FFmXSyR78uK%2Fg%3D&pageToken=';

       
        $post_params .= '&AvailabilitySearchInputSelectView%24RadioButtonMarketStructure='.$trip_type;
        $post_params .= '&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin1='.$from;
        $post_params .= '&originStation1='.$from;
        $post_params .= '&AvailabilitySearchInputSelectView%24TextBoxMarketDestination1='.$to;
        $post_params .= '&destinationStation1='.$to;
        $post_params .= '&AvailabilitySearchInputSelectView%24DropDownListMarketDay1='.$day;
        $post_params .= '&AvailabilitySearchInputSelectView%24DropDownListMarketMonth1='.$year_month;
        $post_params .= '&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange1='.$date_range;
        $post_params .= '&date_picker='.$departure;
        $post_params .= '&AvailabilitySearchInputSelectView%24TextBoxMarketOrigin2=';
        $post_params .= '&originStation2=';
        $post_params .= '&AvailabilitySearchInputSelectView%24TextBoxMarketDestination2=';
        $post_params .= '&destinationStation2=';
        $post_params .= '&AvailabilitySearchInputSelectView%24DropDownListMarketDay2='.$rday;
        $post_params .= '&AvailabilitySearchInputSelectView%24DropDownListMarketMonth2='.$ryear_month;
        $post_params .= '&AvailabilitySearchInputSelectView%24DropDownListMarketDateRange2='.$date_range;
        $post_params .= '&date_picker='.$rdate1;
        $post_params .= '&AvailabilitySearchInputSelectView%24Date2=';
        $post_params .= '&AvailabilitySearchInputSelectView%24DropDownListPassengerType_ADT='.$search_data['adult_config'];
        $post_params .= '&AvailabilitySearchInputSelectView%24DropDownListPassengerType_CHD='.$search_data['child_config'];
        $post_params .= '&AvailabilitySearchInputSelectView%24DropDownListPassengerType_INFANT='.$search_data['infant_config'];
        $post_params .= '&AvailabilitySearchInputSelectView%24DropDownListCurrency=none';
        $post_params .= '&ControlGroupSelectView%24AvailabilityInputSelectView%24market1='.$price_key1;
        if($search_data['trip_type'] == 'return' && $search_data['is_domestic'] == 1 && count($get_price_data) > 1){
            $post_params .= '&ControlGroupSelectView%24AvailabilityInputSelectView%24market2='.$price_key2;

        }
        if(($search_data['trip_type'] == 'return') && (empty($search_data['is_domestic']) == true)){
            $post_params .= '&ControlGroupSelectView%24AvailabilityInputSelectView%24market2='.$price_key2;

        }
        $post_params .= '&ControlGroupSelectView%24ContactInputGSTViewSelectView%24ControlGroupSelectView_ContactInputGSTViewSelectViewHtmlInputHiddenAntiForgeryTokenField=78c5b689-6a42-49ba-a968-f062e576c7d5';
        $post_params .= '&ControlGroupSelectView%24ContactInputGSTViewSelectView%24CheckBoxGST=on';
        $post_params .= '&ControlGroupSelectView%24ButtonSubmit=Continue';
        // debug($post_params);exit;

        $header = array();
        $header [0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header [0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header [] = "Cache-Control: max-age=0";
        $header [] = "Connection: keep-alive";
        $header [] = "Keep-Alive: 300";
        $header [] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header [] = "Accept-Language: en-us,en;q=0.5";
        $header [] = "Pragma:";
        
        $strCookie = $CI->flight_model->get_cookie_value();
        // echo 'here'.$strCookie;exit;
        //anitha code
        /*$request2 ='__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=%2FwEPDwUBMGRkMSHDB101%2B52k3J%2FFmXSyR78uK%2Fg%3D&pageToken=&
ControlGroupLoginModelPopUpView%24MemberLoginView%24TextBoxUserID=BLRAS00864&
ControlGroupLoginModelPopUpView%24MemberLoginView%24PasswordFieldPassword=Travelo%402017%40&
ControlGroupLoginModelPopUpView%24MemberLoginView%24CheckBoxRemember=on&ControlGroupLoginModelPopUpView%24MemberLoginView%24SMDropDownListTitle=MR&radioSMTitle=on&ControlGroupLoginModelPopUpView%24MemberLoginView%24SMDropDownListCountry=IN&ControlGroupLoginModelPopUpView%24MemberLoginView%24txtCountryCode=91&ControlGroupLoginModelPopUpView%24MemberLoginView%24SMDropDownListDOBDay=&ControlGroupLoginModelPopUpView%24MemberLoginView%24SMDropDownListDOBMonth=&ControlGroupLoginModelPopUpView%24MemberLoginView%24SMDropDownListDOBYear=&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24CONTROLGROUPPASSENGER_ContactInputPassengerViewHtmlInputHiddenAntiForgeryTokenField=7f835f55-81d8-4dc6-a36f-36476c2a6066&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24DropDownListTitle=MR&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxFirstName=Balu&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxLastName=Vijay&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxCountryCodeHomePhone=91&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxHomePhone=8123573796&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxEmailAddress=balu.provab%40gmail.com&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24DropDownListCountry=IN&contact_cities_list_india=Bengaluru&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxCity=Bengaluru&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxAddressLine3=182.156.244.142&CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24DropDownListTitle_0=MR&CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24DropDownListGender_0=1&CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24TextBoxFirstName_0=Balu&CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24TextBoxLastName_0=Vijay&CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24DropDownListDocumentType0_0=A&CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24TextBoxDocumentNumber0_0=&CONTROLGROUPPASSENGER%24MealLegInputViewPassengerView%24SsrQuantity_passengerNumber_0_flightReference_20171227-SG-136-BLRDEL=CONTROLGROUPPASSENGER%24MealLegInputViewPassengerView%24SsrQuantity_passengerNumber_0_ssrCode_VCC2_ssrNum_1_flightReference_20171227-SG-136-BLRDEL%401%23%23&Select_CONTROLGROUPPASSENGER%24MealLegInputViewPassengerView%24SsrQuantity_passengerNumber_0_flightReference_20171227-SG-136-BLRDEL=CONTROLGROUPPASSENGER%24MealLegInputViewPassengerView%24SsrQuantity_passengerNumber_0_ssrCode_VCC2_ssrNum_1_flightReference_20171227-SG-136-BLRDEL&CONTROLGROUPPASSENGER%24MealLegInputViewPassengerView%24SsrQuantity_passengerNumber_0_ssrCode_VCC2_ssrNum_1_flightReference_20171227-SG-136-BLRDEL=1&hdn_BengaluruDelhi=&CONTROLGROUPPASSENGER%24SeatMealComboPassengerView%24_Bengaluru_Delhi_passengerNumber_0_ssrCode_STML_ssrNum_0_flightReference_20171227-SG-136-BLRDEL=on&hdn_CONTROLGROUPPASSENGER%24SeatMealComboPassengerView%24_Bengaluru_Delhi_passengerNumber_=passengerNumber_0_ssrCode_STML_ssrNum_0_flightReference_20171227-SG-136-BLRDEL&hdn_BengaluruDelhi=&hdn_CONTROLGROUPPASSENGER%24PRCKBOFComboPassengerView%24SsrQuantity_passengerNumber_=&CONTROLGROUPPASSENGER%24BaggageInputViewPassengerView%24SsrQuantity_passengerNumber_0_flightReference_20171227-SG-136-BLRDEL=&CONTROLGROUPPASSENGER%24SpiceJetAssurancePassengerView%24SsrQuantity_passengerNumber_0_ssrCode_SASR_ssrNum_1_flightReference_20171227-SG-136-BLRDEL=on&CONTROLGROUPPASSENGER%24FirstBagSSRViewPassengerView%24SsrQuantity_passengerNumber_0_flightReference_20171227-SG-136-BLRDEL=&hdn_CONTROLGROUPPASSENGER%24ExcessCabinBaggageView%24SsrQuantity_passengerNumber_0_ssrCode_EXCB_ssrNum_1_flightReference_20171227-SG-136-BLRDEL=hdn_CONTROLGROUPPASSENGER%24ExcessCabinBaggageView%24SsrQuantity_passengerNumber_0_ssrCode_EXCB_ssrNum_1_flightReference_20171227-SG-136-BLRDEL%400&CONTROLGROUPPASSENGER%24CakeInputViewPassengerView%24SsrQuantity_passengerNumber_-_flightReference_20171227-SG-136-BLRDEL=&InsuranceSingle=No&CONTROLGROUPPASSENGER%24CakeInputViewPassengerView%24SsrQuantity_passengerNumber_-_flightReference_20171227-SG-136-BLRDEL=&CONTROLGROUPPASSENGER%24ButtonSubmit=Continue';

$cookie = 'ASP.NET_SessionId=gt5bud45kdc0v4451tcauxnn; path=/';*/
$request2 = '__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=%2FwEPDwUBMGRkMSHDB101%2B52k3J%2FFmXSyR78uK%2Fg%3D&pageToken=&ControlGroupLoginModelPopUpView%24MemberLoginView%24TextBoxUserID=BLRAS00864&ControlGroupLoginModelPopUpView%24MemberLoginView%24PasswordFieldPassword=Travelo%402017%40&ControlGroupLoginModelPopUpView%24MemberLoginView%24CheckBoxRemember=on&ControlGroupLoginModelPopUpView%24MemberLoginView%24SMDropDownListTitle=MR&radioSMTitle=on&ControlGroupLoginModelPopUpView%24MemberLoginView%24SMDropDownListCountry=IN&ControlGroupLoginModelPopUpView%24MemberLoginView%24txtCountryCode=91&ControlGroupLoginModelPopUpView%24MemberLoginView%24SMDropDownListDOBDay=&ControlGroupLoginModelPopUpView%24MemberLoginView%24SMDropDownListDOBMonth=&ControlGroupLoginModelPopUpView%24MemberLoginView%24SMDropDownListDOBYear=&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24CONTROLGROUPPASSENGER_ContactInputPassengerViewHtmlInputHiddenAntiForgeryTokenField=55c41dbc-d8f0-4663-9563-f7cd06090c01&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24DropDownListTitle=MR&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxFirstName=Balu&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxLastName=Vijay&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxCountryCodeHomePhone=91&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxHomePhone=8123573796&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxEmailAddress=balu.provab%40gmail.com&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24DropDownListCountry=IN&contact_cities_list_india=Bengaluru&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxCity=Bengaluru&CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxAddressLine3=&CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24DropDownListTitle_0=MR&CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24DropDownListGender_0=1&CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24TextBoxFirstName_0=Balu&CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24TextBoxLastName_0=Vijay&CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24DropDownListDocumentType0_0=A&CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24TextBoxDocumentNumber0_0=&CONTROLGROUPPASSENGER%24MealLegInputViewPassengerView%24SsrQuantity_passengerNumber_0_flightReference_20171228-SG-136-BLRDEL=CONTROLGROUPPASSENGER%24MealLegInputViewPassengerView%24SsrQuantity_passengerNumber_0_ssrCode_NBF1_ssrNum_1_flightReference_20171228-SG-136-BLRDEL%401&Select_CONTROLGROUPPASSENGER%24MealLegInputViewPassengerView%24SsrQuantity_passengerNumber_0_flightReference_20171228-SG-136-BLRDEL=&CONTROLGROUPPASSENGER%24MealLegInputViewPassengerView%24SsrQuantity_passengerNumber_0_ssrCode_NBF1_ssrNum_1_flightReference_20171228-SG-136-BLRDEL=1&hdn_BengaluruDelhi=&CONTROLGROUPPASSENGER%24SeatMealComboPassengerView%24_Bengaluru_Delhi_passengerNumber_0_ssrCode_STML_ssrNum_0_flightReference_20171228-SG-136-BLRDEL=on&hdn_CONTROLGROUPPASSENGER%24SeatMealComboPassengerView%24_Bengaluru_Delhi_passengerNumber_=&hdn_BengaluruDelhi=&CONTROLGROUPPASSENGER%24ButtonSubmit=Continue';
$cookie = 'ASP.NET_SessionId=juomuh45gctyqm45cn2u3w45; path=/';
        $url1 = 'https://book.spicejet.com/Select.aspx';
        $url2 = 'https://book.spicejet.com/Contact.aspx';
        $ch = curl_init ( $url1 );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_ENCODING, "gzip,deflate,br" );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );

       
        // curl_setopt ( $ch, CURLOPT_COOKIEJAR, $cookie_file );
        curl_setopt ( $ch, CURLOPT_COOKIE, $strCookie );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_params );
        curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt ( $ch, CURLOPT_VERBOSE, true );
        $res = curl_exec ( $ch );
        // debug($res);exit;
        curl_close ( $ch );
        if (isset($res) && !empty($res)) {
            $html = $res;
            $pokemon_doc = new DOMDocument ();
            $pokemon_doc->loadHTML($html);
            $classname = "mealdropdown  mealidentity";
            $classname1 = "add-ons-acc-item excess-baggage";
            $pokemon_xpath = new DomXPath($pokemon_doc);
            $pokemon_pag = $pokemon_xpath->query("//div[@class='" . $classname . "']");
            // debug($pokemon_pag);exit;
            $pokemon_pag1 = $pokemon_xpath->query("//li[@class='" . $classname1 . "']");
            $pokemon_pag2 = $pokemon_xpath->query("//div[@class='seat-meal-sector']");
            // debug($pokemon_pag2);exit;
            $div = $pokemon_pag->item(0);
            if($pokemon_pag2->length > 0){
                // echo 'herre'.$div->getAttribute('id');exit;
                $inputs = $div->getElementsByTagName('input');
                $div1 = $pokemon_pag2->item(0);
                $inputs1 = $div1->getElementsByTagName('input');
                $input_cnt = 1;
                $input_cnt1 = 1;
               
                if (isset($inputs1->length) && $inputs1->length > 0) {
                    foreach ($inputs1 as $__ik => $input_k) {
                      if($input_cnt1 == 2){

                            $input_id1 = $input_k->getAttribute('id');
                      }
                
                    $input_cnt1++;
                    }
                }
            }
            else{
               $input_id1 =''; 
            }
            
            
           
            
            if (isset($inputs->length) && $inputs->length > 0) {
                foreach ($inputs as $__ik => $input_k) {
                    if($input_cnt == 1){
                        $input_id = $input_k->getAttribute('id');
                    }
                $input_cnt++;  
                    
                }
            }
           
            $meals_list_arr = array();
            $g = 0;
            foreach ($pokemon_pag as $date_k => $__Dk) {
                //$from_city = $request['0']['FlightDetails']['Details']['0'][$g]['Origin']['AirportCode'];
                //$to_city = $request['0']['FlightDetails']['Details']['0'][$g]['Destination']['AirportCode'];
                $selection_value = $__Dk->getElementsByTagName('select');
                $meals_list_data = array();
                // debug($selection_value);exit;
                // $select_id = $selection_rows->getAttribute('id');
                
                // $option_rows = $__Dk->getElementsByTagName('option');
                // debug($option_rows);exit;
                //if (isset($option_rows) && $option_rows->length > 0) {
                    foreach ($selection_value as $select_k => $__select) {
                    $id1 = $__select->getAttribute('id');
                    $select_id_val1 = substr($id1, -6);
                    $from_city = substr($id1, -6, 3);
                    $to_city = substr($id1, -3);
                    $option_rows = $pokemon_xpath->query("//select[@id='".$id1."']/option");
                    if (isset($option_rows) && $option_rows->length > 0) {
                    $j= 0;
                    foreach ($option_rows as $tr_k => $__tr) {
                        $string = $__tr->nodeValue;
                        if($string!=''){
                            $description = preg_replace('/\s\s+/', ' ', $string);
                            preg_match('#\((.*?)\)#', $description, $match);
                            $price = str_replace(',', '', $match[1]);
                            $price = explode('.', $price);
                            $meals_list_data1[0] = $meals_list_data[$j]['Description'] = preg_replace('/\s\s+/', ' ', $string);
                            $meals_list_data1[0] = $meals_list_data[$j]['Code'] = $__tr->getAttribute('value');
                            $meals_list_data[$j]['selectid'] = $input_id;
                            $meals_list_data[$j]['Origin'] = $from_city;
                            $meals_list_data[$j]['Destination'] = $to_city;
                            $meals_list_data1[0]['Type'] = 'static';
                            $meals_list_data1[0]['selectid'] = $input_id;
                            $meals_list_data[$j]['seatmealcom'] = $input_id1;
                            $meals_list_data[$j]['Price'] = $price[0]; 
                            $meals_list_data[$j]['MealId'] = base64_encode(serialize($meals_list_data1));
                        }
                        // 
                        // echo "<br/>";
                        
                        // debug($__tr);
                        // echo "<br/>";
                        $j++;
                        }
                    }
                    // echo 'herre'.$select_id_val1;
                    // echo "<br/>";
                    array_shift($meals_list_data);
                    $meals_list_arr[$select_id_val1] = $meals_list_data;
                        
                    }
                //}
                
               $g++; 
            }
            // debug($meals_list_arr);exit;
            // debug(array_keys($meals_list_arr));
            $meals_list_arr1 = array_values($meals_list_arr);
         
            $bagg_list_arr1 = array();
            foreach ($pokemon_pag1 as $date_k => $__Dk) {
                $bagg_list_data = array();
              
                $selection_value = $__Dk->getElementsByTagName('select');
                // debug($selection_value);
                  foreach ($selection_value as $select_k => $__select) {
                    $id = $__select->getAttribute('id');
                    $select_id_val = substr($id, -6);
                    $from_city = substr($id, -6, 3);
                    $to_city = substr($id, -3);
                    $option_rows = $pokemon_xpath->query("//select[@id='".$id."']/option");
                    // debug($option_rows);
                    $k = 0;
                    foreach ($option_rows as $tr_k => $__tr) {
                        $string = $__tr->nodeValue;
                        if($string!=''){
                            $description = preg_replace('/\s\s+/', ' ', $string);
                           
                            $baggage_w = explode('Excess Baggage', $description);
                            $baggage_w1 = explode('(', $baggage_w[1]);
                            preg_match_all('!\d+!', $description, $matches);
                            $bag_price = explode(')', $baggage_w1[1]);
                            preg_match('#\((.*?)\)#', $description, $match);
                            $price = str_replace(',', '', $match[1]);
                            $price = explode('.', $price);
                            // debug($match);
                            
                            if (strpos($description, 'KG') !== false) {
                                $weight = $matches[0][0];
                                // echo 'hetrre'.$weight;
                                if($weight > 1){
                                    $weight = $weight.' KG';
                                }
                               
                            }
                            else{
                              
                                $weight = $matches[0][0];

                                if($weight > 1){
                                    $weight = $weight.' Bags';
                                }
                                else{
                                    $weight = $weight.' Bag';
                                }
                            }
                          
                            $bagg_list_data[$k]['Description'] = $description;
                            $bagg_list_data[$k]['Code'] = $__tr->getAttribute('value');
                            $bagg_list_data[$k]['Origin'] = $from_city;
                            $bagg_list_data[$k]['Destination'] = $to_city;
                            $bagg_list_data[$k]['Price'] = $price[0];
                            $bagg_list_data[$k]['Weight'] = $weight;
                            $bagg_list_data[$k]['BaggageId'] = (serialize($bagg_list_data));
                        }
                        $k++;
                    }
                   
                    array_shift($bagg_list_data);
                    $bagg_list_arr1[$select_id_val] = $bagg_list_data;
                  }
               
                
                
            }
            $bagg_list_arr1 = array_values($bagg_list_arr1);
           
            if(count($bagg_list_arr1) == 1){

                foreach($bagg_list_arr1 as $key => $bagg_list_data1){
                    foreach ($bagg_list_data1 as $key1 => $value) {
                        $bagg_list_arr1[$key][$key1]['Origin'] = $from;
                        $bagg_list_arr1[$key][$key1]['Destination'] = $to;
                       
                    }
                   
                }

                
            }
            // debug($meals_list_arr1);exit;
            if(count($meals_list_arr1) == 1){

                foreach($meals_list_arr1 as $key => $meal_list_data1){
                    foreach ($meal_list_data1 as $key1 => $value) {
                        $meals_list_arr1[$key][$key1]['Origin'] = $from;
                        $meals_list_arr1[$key][$key1]['Destination'] = $to;
                       
                    }
                   
                }

                
            }
            if($search_data['trip_type'] == 'return' && $search_data['is_domestic'] == 1 && count($get_price_data) < 1){
                $meals_list_arr1 = array();
                $bagg_list_arr1 = array();
            }
            else{
               $meals_list_arr1 = $meals_list_arr1;
               $bagg_list_arr1 = $bagg_list_arr1;
            }
            
            // debug($meals_list_arr1);exit;
            // debug($bagg_list_arr1);exit;
         
            //array_shift($meals_list_data);
            //array_shift($bagg_list_data);
            //$meals_list_arr[0] = $meals_list_data;
            // $bagg_list_arr[0] = $bagg_list_data;
            // echo "<pre";debug($bagg_list_arr);exit;
            $response ['status'] = SUCCESS_STATUS;
            $response ['data']['ExtraServiceDetails']['Seat'] = array();
            $response ['data']['ExtraServiceDetails']['MealPreference'] = $meals_list_arr1;
            $response ['data']['ExtraServiceDetails']['Baggage'] = $bagg_list_arr1;
            //exit;
        }
        // $response1 = json_encode($response);
        // $insert_data['price_data'] = $post_params;
        // $insert_data['search_id'] = $search_id;
        // $CI->flight_model->inesrt_price_data($insert_data);
        // debug($response);exit;
       return $response;

    }
    public function get_seat_map_details($seat_details){
    $CI = &get_instance();
    $flight_meal_data = json_decode($seat_details[0], true);
    $meal_selected = str_replace('CONTROLGROUPPASSENGER_', 'CONTROLGROUPPASSENGER%24', $flight_meal_data['selectid']);
    $meal_selected = str_replace('MealLegInputViewPassengerView$', 'MealLegInputViewPassengerView%24', $meal_selected);
    $meal_selected_value = str_replace('CONTROLGROUPPASSENGER$', 'CONTROLGROUPPASSENGER%24', $flight_meal_data['Code']);
    $meal_selected_value = str_replace('MealLegInputViewPassengerView$', 'MealLegInputViewPassengerView%24', $flight_meal_data['Code']);
    $meal_seat_combo = $meal_seat_combofirst= str_replace('CONTROLGROUPPASSENGER$', 'CONTROLGROUPPASSENGER%24', $flight_meal_data['seatmealcom']);
    $meal_seat_combo = $meal_seat_combofirst= str_replace('SeatMealComboPassengerView$', 'SeatMealComboPassengerView%24', $meal_seat_combo);


    $meal_seat_combo = explode('hdn_', $meal_seat_combo);
   
    $meal_seat_combo3 = $meal_seat_combo[1];
    $meal_seat_combo1 = str_replace('$', '%24', $meal_seat_combo1);
    $meal_seat_combo2 = explode('_0_ssrCode',$meal_seat_combofirst);
    // debug($flight_meal_data);exit;
    $post_params ='__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=%2FwEPDwUBMGRkMSHDB101%2B52k3J%2FFmXSyR78uK%2Fg%3D&pageToken=&';
    $post_params .='ControlGroupLoginModelPopUpView%24MemberLoginView%24TextBoxUserID=&';
    $post_params .='ControlGroupLoginModelPopUpView%24MemberLoginView%24PasswordFieldPassword=&';
    $post_params .='ControlGroupLoginModelPopUpView%24MemberLoginView%24CheckBoxRemember=&';
    $post_params .='ControlGroupLoginModelPopUpView%24MemberLoginView%24SMDropDownListTitle=MR&';
    $post_params .='radioSMTitle=on&ControlGroupLoginModelPopUpView%24MemberLoginView%24SMDropDownListCountry=IN&';
    $post_params .='ControlGroupLoginModelPopUpView%24MemberLoginView%24txtCountryCode=91&';
    $post_params .='ControlGroupLoginModelPopUpView%24MemberLoginView%24SMDropDownListDOBDay=&';
    $post_params .='ControlGroupLoginModelPopUpView%24MemberLoginView%24SMDropDownListDOBMonth=&';
    $post_params .='ControlGroupLoginModelPopUpView%24MemberLoginView%24SMDropDownListDOBYear=&';
    $post_params .='CONTROLGROUPPASSENGER%24ContactInputPassengerView%24CONTROLGROUPPASSENGER_ContactInputPassengerViewHtmlInputHiddenAntiForgeryTokenField=55c41dbc-d8f0-4663-9563-f7cd06090c01&';
    $post_params .='CONTROLGROUPPASSENGER%24ContactInputPassengerView%24DropDownListTitle=MR&';
    $post_params .='CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxFirstName=Balu&';
    $post_params .='CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxLastName=Vijay&';
    $post_params .='CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxCountryCodeHomePhone=91&';
    $post_params .='CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxHomePhone=8123573796&';
    $post_params .='CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxEmailAddress=balu.provab%40gmail.com&';
    $post_params .='CONTROLGROUPPASSENGER%24ContactInputPassengerView%24DropDownListCountry=IN&';
    $post_params .='contact_cities_list_india=Bengaluru&';
    $post_params .='CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxCity=Bengaluru&';
    $post_params .='CONTROLGROUPPASSENGER%24ContactInputPassengerView%24TextBoxAddressLine3=&';
    $post_params .='CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24DropDownListTitle_0=MR&';
    $post_params .='CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24DropDownListGender_0=1&';
    $post_params .='CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24TextBoxFirstName_0=Balu&';
    $post_params .='CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24TextBoxLastName_0=Vijay&';
    $post_params .='CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24DropDownListDocumentType0_0=A&';
    $post_params .='CONTROLGROUPPASSENGER%24PassengerInputViewPassengerView%24TextBoxDocumentNumber0_0=&';
    $post_params .= $meal_selected.'=';
    $post_params .= $meal_selected_value.'%401&Select_';
    $post_params .= $meal_selected.'=';
    $post_params .= $meal_selected_value.'=1';
    $post_params .= '&hdn_BengaluruDelhi=&';
    $post_params .= $meal_seat_combo3.'=on&';
    $post_params .= $meal_seat_combo2[0].'_=';
    $post_params .= '&hdn_BengaluruDelhi=&CONTROLGROUPPASSENGER%24ButtonSubmit=Continue';
    // debug($post_params);exit;
    $header = array();
    $header [0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
    $header [0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
    $header [] = "Cache-Control: max-age=0";
    $header [] = "Connection: keep-alive";
    $header [] = "Keep-Alive: 300";
    $header [] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $header [] = "Accept-Language: en-us,en;q=0.5";
    $header [] = "Pragma:";
        
    $strCookie = $CI->flight_model->get_cookie_value();
    $url1 = 'https://book.spicejet.com/Contact.aspx';
    $ch = curl_init ( $url1 );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_ENCODING, "gzip,deflate,br" );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );

   
    // curl_setopt ( $ch, CURLOPT_COOKIEJAR, $cookie_file );
    curl_setopt ( $ch, CURLOPT_COOKIE, $strCookie );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_params );
    curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt ( $ch, CURLOPT_VERBOSE, true );
    $res = curl_exec ( $ch );
    // debug($res);exit;
    curl_close ( $ch );

   
    }
    
}
