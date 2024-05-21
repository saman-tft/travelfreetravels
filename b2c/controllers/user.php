<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab
 * @subpackage General
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('module_model');
        //$this->load->model('package_model');

        $this->load->model('transaction');
        $this->load->library('rewards');
    }

    public function comingsoon()
    {
        $post_data[] = '';
        $this->template->view('general/coming_soon', $post_data);
    }
    function process_reward_points($book_id, $book_origin)
    {


        $pg_record = $this->transaction->read_payment_record($book_id);
        $checkpoints = "SELECT * From wallet_setting  where price=" . $pg_record['amount'];
        $getpoints = $this->db->query($checkpoints)->result_array();
        $this->rewards->update_reward_earned_value($temp_booking, $book_id);
        $data = array(
            "transactionid" => $book_id,
            "paymentstatus" => $pg_record['status'],
            "amount" => $pg_record['amount'],
            "earned_rewards" => $getpoints[0]['reward-points'],
            "created_at" => date("Y-m-d"),
            "created_by_id" => $this->entity_user_id
        );
        $user_id = $this->entity_user_id;
        $data_rewards = $this->rewards->user_reward_details($user_id);
        $pending_rewards = $data_rewards['pending_reward'] + $getpoints[0]['reward-points'];
        $used_rewards = 0;
        $data_upadte_rewards = array(
            'pending_reward' => round($pending_rewards),
            'used_reward' => round($used_rewards),

        );
        $this->rewards->update_reward_record($user_id, $data_upadte_rewards);
        $this->custom_db->insert_record('wallet_transaction', $data);
        $this->session->set_flashdata(array('message' => 'UL0010', 'type' => SUCCESS_MESSAGE));
        redirect('index.php/user/profile?active=wallets');
    }
    function process_reward_points_faile($book_id, $book_origin)
    {
        $pg_record = $this->transaction->read_payment_record($book_id);
        $data = array(
            "transactionid" => $book_id,
            "paymentstatus" => $pg_record['status'],
            "created_date" => date("Y-m-d"),
            "created_by_id" => $this->entity_user_id
        );

        $this->custom_db->insert_record('wallet_transaction', $data);
        $this->session->set_flashdata(array('message' => 'UL0010', 'type' => ERROR_MESSAGE));
        redirect('index.php/user/profile?active=wallets');
    }
    function buyrewards()
    {

        $this->load->model('transaction');
        $currency_obj = new Currency(array(
            'module_type' => 'hotel',
            'from' => admin_base_currency(),
            'to' => admin_base_currency()
        ));
        $data = $this->input->post();
        $book_id = $this->getGUIDnoHash();
        $book_origin = "rewardwallet";
        $verification_amount = $data['rewardpoints_amount'];
        $firsname = $this->entity_first_name;
        $productinfo = "rewardwallet";
        $promocode_discount = "";
        $convenience_fees = "";
        $email = $this->entity_email;
        $pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
        $this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate, $data['rewardpoints'], $data['rewardpoints_amount'], $data['rewardpoints']);
        //debug($data);die;
        redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin);
    }
    function getGUIDnoHash()
    {
        mt_srand((float)microtime() * 10000);
        $charid = md5(uniqid(rand(), true));
        $c = unpack("C*", $charid);
        $c = implode("", $c);

        return substr($c, 0, 20);
    }
    public function redeem_product($id, $point)
    {
        $user_id = $this->entity_user_id;
        // changes   made $reward_values['usable_reward']='' in following line
        $usable_rewards = $this->rewards->usable_rewards($user_id, META_ACCOMODATION_COURSE, $reward_values['usable_reward'] = '');
        $data_rewards = $this->rewards->user_reward_details($user_id);
        if ($usable_rewards >= $point) {
            $data = array(
                "product_id" => $id,
                "status" => 1,
                "created_date" => date("Y-m-d"),
                "agent_id" => $this->entity_user_id
            );
            $pending_rewards = $data_rewards['pending_reward'] - $point;
            $used_rewards = $data_rewards['used_reward'] + $point;
            $data_upadte_rewards = array(
                'pending_reward' => round($pending_rewards),
                'used_reward' => round($used_rewards),

            );
            $data_upadte_rewards_report = array(
                'pending_rewardpoint' => 0,
                'used_rewardpoint' => $point,
                'reward_earned' => 0,
                'user_id' => $user_id,
                'module' => "redeem-reward",
                'book_id' => 'redeem01',
                'created' => date('Y-m-d h:i:s')
            );
            $this->rewards->update_reward_report_data($user_id, $data_upadte_rewards_report);
            $this->rewards->update_reward_record($user_id, $data_upadte_rewards);
            $this->custom_db->insert_record('loyalty_redeem_request', $data);
            //changes added following lines instead of commented one for rewards section
            // $this->template->view('product/receipt');
            $this->session->set_flashdata(array('message' => 'AL0041', 'type' => SUCCESS_MESSAGE));
            $query = "SELECT * From loyalty_product  where status='1' and id = $id order by point asc";
            $product_data = $this->db->query($query)->result_array()[0];
            $page_data = array();
            $page_data = array(
                'name' => $data_rewards['first_name'] . ' ' . $data_rewards['last_name'],
                'email' => provab_decrypt($data_rewards['email']),
                'phone' => $data_rewards['phone'],
                'product_data' => $product_data
            );
            $this->load->library('provab_mailer');
            $mail_template = $this->template->isolated_view('product/redeem_mail_template', $page_data);
            $this->provab_mailer->send_mail('saman.teamtft@gmail.com', domain_name() . ' - Redeem Request', $mail_template);
            $mail_template = $this->template->isolated_view('product/redeem_mail_template_user', $page_data);
            $this->provab_mailer->send_mail($page_data['email'], domain_name() . ' - Redeem Request', $mail_template);
            redirect('user/product/');
            //upto here
        } else {
            //changes all lines commented in this condition are for rewards section
            $this->session->set_flashdata(array('message' => 'AL0040', 'type' => SUCCESS_MESSAGE));
            //   	$page_data=array();
            // $query = "SELECT * From loyalty_product  where status='1'";
            // $checkproduct = "SELECT product_id From loyalty_redeem_request  where agent_id=".$this->entity_user_id;
            // $getproduct= $this->db->query($checkproduct)->result_array();
            // $prod=array();
            // $specific_data_list = $this->db->query($query)->result_array();
            // $page_data['product_data'] =$specific_data_list;
            // for($i=0;$i<count($getproduct);$i++)
            // {
            //     array_push($prod,$getproduct[$i]['product_id']);
            // }
            // $page_data['checkproduct_data'] =$prod;

            redirect('user/product');
            // $this->template->view('product/product-list', $page_data);
        }
    }
    public function product()
    {
        if (isset($this->entity_user_id)) {
            $page_data = array();
            //    changes added order by point asc in below query for rewards section
            $query = "SELECT * From loyalty_product  where status='1' order by point asc";
            $checkproduct = "SELECT product_id From loyalty_redeem_request  where agent_id=" . $this->entity_user_id;
            $getproduct = $this->db->query($checkproduct)->result_array();
            $prod = array();
            $specific_data_list = $this->db->query($query)->result_array();
            $page_data['product_data'] = $specific_data_list;
            for ($i = 0; $i < count($getproduct); $i++) {
                array_push($prod, $getproduct[$i]['product_id']);
            }
            $page_data['checkproduct_data'] = $prod;
            //changes added following for rewards section
            $page_data['usable_rewards'] = $this->rewards->usable_rewards($this->entity_user_id, '', '');
            $page_data['user'] = $this->rewards->user_reward_details($this->entity_user_id);
            //upto here


            $this->template->view('product/product-list', $page_data);
        } else {
            redirect('general/home');
        }
    }
    public function productreceipt()
    {
        $post_data[] = '';
        $this->template->view('product/receipt', $post_data);
    }
    public function comingsoonpj()
    {
        $post_data[] = '';
        $this->template->view('general/coming_soon_pj.php', $post_data);
    }
    public function comingsoonpt()
    {
        $post_data[] = '';
        $this->template->view('general/coming_soon_pt.php', $post_data);
    }
    public function comingsoonpc()
    {
        $post_data[] = '';
        $this->template->view('general/coming_soon_pc.php', $post_data);
    }
    public function coming_soon_ac()
    {
        $post_data[] = '';
        $this->template->view('general/coming_soon_ac.php', $post_data);
    }
    function create_default_domain($domain_key_name = '192.168.0.26')
    {
        include_once DOMAIN_CONFIG . 'default_domain_configuration.php';
    }

    /**
     * index page of application will be loaded here
     */
    function index()
    {
        if (is_logged_in_user()) {
            redirect('menu/index');
        }
    }

    function plan_retirement()
    {
        $page_data = $this->input->post();

        $id_image_data = array();
        $passport_image_data = array();
        // FILE UPLOAD
        if (valid_array($_FILES) == true and $_FILES['passid']['error'] == 0 and $_FILES['passid']['size'] > 0) {
            $img_name = '-' . time();
            $config['upload_path'] = $this->template->domain_image_upload_path();
            $temp_file_name1 = $_FILES['passid']['name'];
            $config['allowed_types'] = '*';
            $config['file_name'] = 'IMG' . $img_name;
            $config['max_size'] = '1000000';
            $config['max_width'] = '';
            $config['max_height'] = '';
            $config['remove_spaces'] = false;
            // UPLOAD IMAGE
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('passid')) {
                echo $this->upload->display_errors();
            } else {
                $id_image_data = $this->upload->data();
            }
        }
        if (valid_array($_FILES) == true and $_FILES['passcopy']['error'] == 0 and $_FILES['passcopy']['size'] > 0) {
            $img_name = '-' . time();
            $config2['upload_path'] = $this->template->domain_image_upload_path();
            $temp_file_name2 = $_FILES['passcopy']['name'];
            $config2['allowed_types'] = '*';
            $config2['file_name'] = 'IMG' . $img_name;
            $config2['max_size'] = '1000000';
            $config2['max_width'] = '';
            $config2['max_height'] = '';
            $config2['remove_spaces'] = false;
            // UPLOAD IMAGE
            $this->load->library('upload', $config2);
            $this->upload->initialize($config2);
            if (!$this->upload->do_upload('passcopy')) {
                echo $this->upload->display_errors();
            } else {
                $passport_image_data = $this->upload->data();
            }
        }
        $page_data['passid'] = $id_image_data['file_name'];
        $page_data['passcopy'] = $passport_image_data['file_name'];
        $page_data['payment_method'] = PAY_NOW;
        $page_data['get_package'] = ltrim($page_data['packselect'], 'USD ');

        $get_users = $this->user_model->get_plan_retirement($page_data);

        switch ($page_data['payment_method']) {
            case PAY_NOW:

                $this->load->model('transaction');
                $currency_obj = new Currency(array(
                    'module_type' => 'flight',
                    'from' => get_application_currency_preference(),
                    'to' => get_application_default_currency()
                ));
                $module = PLAN_RETIREMENT;
                $book_id = $module . date('d-His') . '-' . rand(1, 1000000);
                $temp_booking['book_id']            = $book_id;
                $temp_booking['booking_source']        = PLANRETIREMENT_BOOKING_SOURCE;
                $temp_booking['booking_ip']            = $_SERVER['REMOTE_ADDR'];
                $temp_booking['created_datetime']    = date('Y-m-d H:i:s');
                $temp_booking_origin = $this->custom_db->insert_record('temp_booking', $temp_booking);
                $book_origin = $temp_booking_origin['insert_id'];
                $productinfo = META_RETIREMENT_COURSE;
                $convenience_fees = 0;
                $promocode_discount = 0;
                $insurance_amount = 0;
                $pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
                $user_app_reference['app_reference'] = $book_id;
                $this->custom_db->update_record('plan_retirement', $user_app_reference, array('id' => $get_users['insert_id']));
                $this->transaction->create_payment_record($book_id, $page_data['get_package'], $page_data['fullname'], $page_data['email'], $page_data['phone'], $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate, $insurance_amount);

                redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin);


                break;
            case PAY_AT_BANK:
                echo 'Under Construction - Remote IO Error';
                exit();
                break;
        }
        $post_data = array();
        $this->template->view('general/investors', $post_data);
    }

    function invester_process_booking()
    {
        $post_data = array();
        $this->template->view('general/invester_booking', $post_data);
    }
    function invester_cancel_booking()
    {
        $post_data = array();
        $this->template->view('general/invester_cancel_booking', $post_data);
    }
    function cancel_booking()
    {
        $post_data = array();
        $this->template->view('general/cancel_booking', $post_data);
    }

    /**
     * User Profile Management
     */
    function profile()
    {

        validate_user_login();
        $op_data = $this->input->post();

        $page_data = array();
        $this->load->model('transaction_model');
        $currency_obj = new Currency();
        $page_data['currency_obj'] = $currency_obj;
        if (
            valid_array($op_data) == true && empty($op_data['title']) == false && empty($op_data['first_name']) == false && empty($op_data['last_name']) == false &&
            empty($op_data['country_code']) == false && empty($op_data['phone']) == false && empty($op_data['address']) == false
        ) {
            //Application Logger
            $notification_users = $this->user_model->get_admin_user_id();
            $remarks = $op_data['first_name'] . ' Updated Profile Details';
            $action_query_string = array();
            $action_query_string['user_id'] = $this->entity_user_id;
            $action_query_string['uuid'] = $this->entity_uuid;
            $action_query_string['user_type'] = B2C_USER;

            $this->application_logger->profile_update($op_data['first_name'], $remarks, $action_query_string, array(), $this->entity_user_id, $notification_users);

            $this->custom_db->update_record('user', $op_data, array('user_id' => $this->entity_user_id, 'uuid' => provab_encrypt($this->entity_uuid)));
            //PROFILE IMAGE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['image']['error'] == 0 and $_FILES['image']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {
                    if (!check_mime_image_type($_FILES['image']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }


                $config['upload_path'] = $this->template->domain_image_upload_path(); //FIXME: Balu A get Correct Path

                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = $image_name = $_FILES['image']['name'];
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;

                //UPDATE
                $temp_record = $this->custom_db->single_table_records('user', 'image', array('user_id' => $this->entity_user_id));
                $icon = $temp_record['data'][0]['image'];

                //DELETE OLD FILES
                if (empty($icon) == false) {
                    if (file_exists($config['upload_path'] . $icon)) {
                        unlink($config['upload_path'] . $icon);
                    }
                }
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }

                $this->custom_db->update_record('user', array('image' => @$image_data['file_name']), array('user_id' => $this->entity_user_id));
            }


            $this->session->set_flashdata(array('message' => 'AL004', 'type' => SUCCESS_MESSAGE));
            if (empty($_SERVER['QUERY_STRING']) == false) {
                $query_string = '?' . $_SERVER['QUERY_STRING'];
            } else {
                $query_string = '';
            }

            redirect(base_url() . 'index.php/user/profile' . $query_string);
        } else {
            $page_data['title'] = $this->entity_title;
            $page_data['first_name'] = $this->entity_first_name;
            $page_data['last_name'] = $this->entity_last_name;
            $page_data['full_name'] = $this->entity_name;

            $mobile_code = $this->db_cache_api->get_mobile_code($this->entity_country_code);


            $page_data['mobile_code'] = $mobile_code;
            $page_data['user_country_code'] = $this->entity_country_code;
            $page_data['date_of_birth'] = date('d-m-Y', strtotime($this->entity_date_of_birth));
            $page_data['address'] = $this->entity_address;
            $page_data['phone'] = $this->entity_phone;
            $page_data['email'] = $this->entity_email;
            $page_data['profile_image'] = $this->entity_image;
            $page_data['signature'] = $this->entity_signature;
        }
        $this->load->library('booking_data_formatter');
        $booking_counts = $this->booking_data_formatter->get_booking_counts();
        $page_data['booking_counts'] = $booking_counts['data'];
        $country_code = $this->db_cache_api->get_country_code_list_profile();

        $phone_code_array = array();
        foreach ($country_code['data'] as $c_key => $c_value) {
            $phone_code_array[$c_value['country_code']] = $c_value['name'] . ' ' . $c_value['country_code'];
        }


        $page_data['phone_code_array'] = $phone_code_array;

        $latest_transaction = $this->transaction_model->logs(array(), false, 0, 5);
        $page_data['user'] = $this->custom_db->get_result_by_query("SELECT * FROM user WHERE user_id=" . $this->entity_user_id);
        $page_data['user'] = json_decode(json_encode($page_data['user'][0]), true);
        //	debug($page_data);
        $latest_transaction = $this->booking_data_formatter->format_recent_transactions($latest_transaction, 'b2c');
        $page_data['latest_transaction'] = $latest_transaction['data']['transaction_details'];
        $traveller_details = $this->traveller_details();
        $page_data['user_passport_visa_details'] = $traveller_details['user_passport_visa_details'];
        $page_data['traveller_details'] = $traveller_details['traveller_details'];
        $page_data['iso_country_list'] = $this->db_cache_api->get_iso_country_code();
        $page_data['country_list'] = $this->db_cache_api->get_iso_country_code();
        foreach ($page_data['latest_transaction'] as $key => $value) {

            $temp_record = $this->custom_db->single_table_records('payment_gateway_details', 'amount', array('app_reference' => $value['app_reference']));

            $page_data['latest_transaction'][$key]['grand_total'] = $temp_record['data'][0]['amount'];
        }
        ////For rewards report//////////////
        $page_data['reward_booking_report'] = $this->rewards->get_reward_report($this->entity_user_id);
        $page_data['reward_booking_report_data'] = $this->rewards->get_reward_report_module($this->entity_user_id);
        $walletquery = "SELECT * From  wallet_setting";
        $walletquery2 = "SELECT * From wallet_transaction  where created_by_id=" . $this->entity_user_id;
        $referralquery = "SELECT * From commissions where ref_email='$this->entity_email'";
        $specific_data_listreferral = $this->db->query($referralquery)->result_array();
        //echo $referralquery;
        $page_data['usersreferral_data'] = $specific_data_listreferral;
        //debug($page_data['usersreferral_data']);die;
        $specific_data_list1 = $this->db->query($walletquery)->result_array();
        $specific_data_list2 = $this->db->query($walletquery2)->result_array();
        $page_data['wallet_settings'] = $specific_data_list1;
        $page_data['wallet_report'] = $specific_data_list2;
        $page_data['reward_total_report'] = $this->rewards->get_total_reward_report($this->entity_user_id);
        //	debug(	$page_data['wallet_report']);die;
        $checkproduct = "SELECT * From loyalty_redeem_request inner join loyalty_product on loyalty_redeem_request.product_id=loyalty_product.id  where loyalty_redeem_request.agent_id=" . $this->entity_user_id;
        $page_data['getproduct'] = $this->db->query($checkproduct)->result_array();
        $total_referral = $this->custom_db->single_table_records('affiliates', '*', array('aff_email' => $this->entity_user_id));
        //	debug($total_referral);die;
        if ($total_referral['status'] == 0) {
            $referralcode = $this->random_strings(10);

            $data = array(
                "ref_code" => $referralcode,
                "aff_email" => $this->entity_user_id,
                "aff_name" => $this->entity_first_name,
            );
            // debug($data);die;
            $this->custom_db->insert_record('affiliates', $data);
            $page_data['referral_code'] = $referralcode;
        } else {
            $page_data['referral_code'] = $total_referral['data'][0]['ref_code'];
        }
        //	echo "asd";
        //debug($page['getproduct']);exit();

        ////////////end report//////////////
        //	debug(	$page_data['wallet_report']);die;
        $this->template->view('user/profile', $page_data);
    }



    function delete_traveller($delid)
    {
        $delete_condition['origin'] = $delid;
        $delete_condition['created_by_id'] = $this->entity_user_id;
        $this->custom_db->delete_record('user_traveller_details', $delete_condition);
        redirect('index.php/user/profile?active=traveller');
    }
    public function deleteuser()
    {
        $this->db->where('user_type', B2C_USER);
        $this->db->delete('user');
    }

    /**
     * Logout function for logout from account and unset all the session variables
     */
    function initilize_logout()
    {
        redirect('auth/initilize_logout');
        if (is_logged_in_user()) {
            $this->general_model->update_login_manager($this->session->userdata(LOGIN_POINTER));
            $this->session->unset_userdata(array(AUTH_USER_POINTER => '', LOGIN_POINTER => ''));
            // added by nithin for unseting the email username
            $this->session->unset_userdata('mail_user');
        }
    }

    /**
     * oops page of application will be loaded here
     */
    public function ooops()
    {
        $this->template->view('utilities/404.php');
    }


    /**
     * Function to Change the Password of a User
     */
    public function change_password()
    {
        validate_user_login();
        $data = array();
        $get_data = $this->input->get();
        if (isset($get_data['uid'])) {
            $user_id = intval($this->encrypt->decode($get_data['uid']));
        } else {
            redirect("general/initilize_logout");
        }
        $page_data['form_data'] = $this->input->post();
        if (valid_array($page_data['form_data']) == TRUE) {
            $this->current_page->set_auto_validator();
            if ($this->form_validation->run()) {
                $table_name = "user";
                /** Checking New Password and Old Password Are Same OR Not **/
                $condition['password'] = md5($this->input->post('new_password'));
                $condition['user_id'] = $user_id;
                $check_pwd = $this->custom_db->single_table_records($table_name, 'password', $condition);
                if (!$check_pwd['status']) {
                    $condition['password'] = md5($this->input->post('current_password'));
                    $condition['user_id'] = $user_id;
                    $data['password'] = md5($this->input->post('new_password'));
                    $update_res = $this->custom_db->update_record($table_name, $data, $condition);
                    if ($update_res) {
                        $this->session->set_flashdata(array('message' => 'UL0010', 'type' => SUCCESS_MESSAGE));
                        refresh();
                    } else {
                        $this->session->set_flashdata(array('message' => 'UL0011', 'type' => ERROR_MESSAGE));
                        refresh();
                    }
                } else {
                    $this->session->set_flashdata(array('message' => 'UL0012', 'type' => WARNING_MESSAGE));
                    refresh();
                }
            }
        }
        $this->template->view('user/change_password', $data);
    }
    /**
     * Balu A
     * Add Traveller
     */
    function add_traveller()
    {
        //FIXME:Make Codeigniter Validations -- Balu A
        validate_user_login();
        $post_data = $this->input->post();
        if (
            valid_array($post_data) == true && isset($post_data['traveller_first_name']) == true && empty($post_data['traveller_first_name']) == false && isset($post_data['traveller_date_of_birth']) == true && empty($post_data['traveller_date_of_birth']) == false
            && isset($post_data['traveller_email']) == true && isset($post_data['traveller_last_name']) == true
        ) {
            $user_traveller_details = array();
            $user_traveller_details['first_name'] = $first_name = trim($post_data['traveller_first_name']);
            $user_traveller_details['last_name'] = trim($post_data['traveller_last_name']);
            $user_traveller_details['date_of_birth'] = $date_of_birth = date('Y-m-d', strtotime(trim($post_data['traveller_date_of_birth'])));
            $user_traveller_details['email'] = trim($post_data['traveller_email']);
            $user_traveller_details['created_by_id'] = $this->entity_user_id;
            $user_traveller_details['created_datetime'] = date('Y-m-d H:i:s');

            $check_traveller_data = $this->custom_db->single_table_records('user_traveller_details', '*', array('created_by_id' => $this->entity_user_id, 'first_name' => $first_name, 'date_of_birth' => $date_of_birth));
            if ($check_traveller_data['status'] == FAILURE_STATUS) {
                $this->custom_db->insert_record('user_traveller_details', $user_traveller_details);
            }
        }
        if (empty($_SERVER['QUERY_STRING']) == false) {
            $query_string = '?' . $_SERVER['QUERY_STRING'];
        } else {
            $query_string = '';
        }
        redirect('index.php/user/profile' . $query_string);
    }
    /**
     * Balu A
     */
    function update_traveller_details()
    {
        //FIXME:Make Codeigniter Validations -- Balu A
        $post_data = $this->input->post();
        if (
            valid_array($post_data) == true && isset($post_data['origin']) == true && intval($post_data['origin']) > 0 &&
            isset($post_data['traveller_first_name']) == true && empty($post_data['traveller_first_name']) == false && isset($post_data['traveller_date_of_birth']) == true && empty($post_data['traveller_date_of_birth']) == false
            && isset($post_data['traveller_email']) == true && isset($post_data['traveller_last_name']) == true
        ) {
            $user_traveller_details = array();

            $ex_day = "";
            $ex_month = "";
            $ex_year = "";
            if ($post_data['traveller_passport_exp_date'] != "") {
                $pass_ex_date = explode('-', $post_data['traveller_passport_exp_date']);
                $ex_day = $pass_ex_date[0];
                $ex_month = $pass_ex_date[1];
                $ex_year = $pass_ex_date[2];
            }
            $user_traveller_details['first_name'] = trim($post_data['traveller_first_name']);
            $user_traveller_details['last_name'] = trim($post_data['traveller_last_name']);
            $user_traveller_details['date_of_birth'] = date('Y-m-d', strtotime(trim($post_data['traveller_date_of_birth'])));
            $user_traveller_details['email'] = trim($post_data['traveller_email']);

            $user_traveller_details['passport_user_name'] = trim($post_data['passport_user_name']);
            $user_traveller_details['passport_nationality'] = trim($post_data['passport_nationality']);
            $user_traveller_details['passport_expiry_day'] = trim($ex_day);
            $user_traveller_details['passport_expiry_month'] = trim($ex_month);
            $user_traveller_details['passport_expiry_year'] = trim($ex_year);

            $user_traveller_details['passport_number'] = trim($post_data['passport_number']);
            $user_traveller_details['passport_issuing_country'] = trim($post_data['passport_issuing_country']);
            $user_traveller_details['updated_by_id'] = $this->entity_user_id;
            $user_traveller_details['updated_datetime'] = date('Y-m-d H:i:s');
            $this->custom_db->update_record('user_traveller_details', $user_traveller_details, array('origin' => intval($post_data['origin'])));
        }
        if (empty($_SERVER['QUERY_STRING']) == false) {
            $query_string = '?' . $_SERVER['QUERY_STRING'];
        } else {
            $query_string = '';
        }
        redirect('index.php/user/profile' . $query_string);
    }
    function random_strings($length_of_string)
    {
        $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str_result), 0, $length_of_string);
    }
    /**
     * Balu A
     */
    function traveller_details()
    {
        $data = array();
        $data['user_passport_visa_details'] = array();
        $data['traveller_details'] = array();
        //traveller details
        $traveller_details = $this->custom_db->single_table_records('user_traveller_details', '*', array('created_by_id' => $this->entity_user_id, 'user_id' => 0));
        if ($traveller_details['status'] == true) {
            $data['traveller_details'] = $traveller_details['data'];
        }
        //User PassportVisa details
        $user_passport_visa_details = $this->custom_db->single_table_records('user_traveller_details', '*', array('created_by_id' => $this->entity_user_id, 'user_id' => $this->entity_user_id));
        if ($user_passport_visa_details['status'] == true) {
            $data['traveller_details'] = $user_passport_visa_details['data'][0];
        }
        return $data;
    }
}
