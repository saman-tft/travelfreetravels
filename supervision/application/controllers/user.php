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
        $this->load->library('provab_sms');
        //$this->output->enable_profiler(TRUE);
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
    function b2b_reward_user()
    {
        error_reporting(E_ALL);
        $page_data = array();
        $this->template->view('user/b2b_loyalty_user', $page_data);
    }
    function b2b_loyalty_user()
    {
        $page_data['form_data']  = $this->input->post();
        //debug($page_data);exit();      
        //debug($total_records);exit;
        if ($row == 0) {
            $page_data['form_data']['name'] = $page_data['form_data']['name'];
            $page_data['form_data']['reward_point'] = $page_data['form_data']['reward_point'];
            $insert_id = $this->custom_db->insert_record('reward_points', $page_data['form_data']);
            $this->template->view('user/b2b_loyalty_user', $page_data['form_data']);
        } else {
            $page_data = array();
            $this->template->view('user/b2b_loyalty_user', $page_data);
        }
    }
    function b2c_user($offset = 0)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_username_check'); //Username to be unique
        $this->form_validation->set_rules('password', 'Password', 'matches[confirm_password]|min_length[5]|max_length[45]|required|callback_valid_password');
        $this->form_validation->set_rules('confirm_password', 'Confirm');

        $page_data['form_data'] = $this->input->post();
        $get_data = $this->input->get();

        $country_data = $this->custom_db->single_table_records('api_country_list', '*', array('origin' => $page_data['form_data']['country_code']));
        $country_code = $country_data['data'][0]['country_code'];

        $condition = array();
        //CHECKING DOMAIN ORIGIN SET OR NOT
        if (isset($get_data['domain_origin']) == true && intval($get_data['domain_origin']) > 0) {
            $domain_origin = intval($get_data['domain_origin']);
        } else {
            $domain_origin = 0;
        }

        $page_data['eid'] = intval(@$get_data['eid']);
        if (valid_array($page_data['form_data']) == false && intval(@$page_data['eid']) > 0) {
            /**
             * EDIT DATA
             */
            $edit_data = $this->custom_db->single_table_records('user', '*', array('user_id' => $page_data['eid']));
            if (valid_array($edit_data['data']) == true) {
                $page_data['form_data'] = $edit_data['data'][0];
                $page_data['form_data']['email'] = provab_decrypt($page_data['form_data']['email']);
                $page_data['form_data']['uuid'] = provab_decrypt($page_data['form_data']['uuid']);
            } else {
                redirect('security/log_event?event=Invalid user edit');
            }
        } elseif (valid_array($page_data['form_data']) == true) {
            /** AUTOMATE VALIDATOR **/
            $page_data['form_data']['language_preference'] = 'english';
            $this->current_page->set_auto_validator();
            $this->load->library('form_validation');

            if ($this->form_validation->run()) {
                //LETS UNSET DATA WHICH ARE NOT NEEDED FOR DB


                $image_data = array();
                // FILE UPLOAD
                if (valid_array($_FILES) == true and $_FILES['image']['error'] == 0 and $_FILES['image']['size'] > 0) {
                    $img_name = 'Agent_logo-' . time();
                    $config['upload_path'] = $this->template->domain_image_upload_path();
                    $temp_file_name = $_FILES['image']['name'];
                    $config['allowed_types'] = '*';
                    $config['file_name'] = 'IMG-' . $img_name;
                    $config['max_size'] = '1000000';
                    $config['max_width'] = '';
                    $config['max_height'] = '';
                    $config['remove_spaces'] = false;
                    // UPLOAD IMAGE
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('image')) {
                        echo $this->upload->display_errors();
                    } else {
                        $image_data = $this->upload->data();
                    }
                }
                $page_data['form_data']['image'] = (empty($image_data['file_name']) == false ? $image_data['file_name'] : '');
                $get_user_data = $this->custom_db->single_table_records('user', '*', array('email' => provab_encrypt($page_data['form_data']['email']), 'user_type' => B2C_USER));

                if ($get_user_data['status'] == 1) {
                    redirect('security/log_event?event=Email account already used');
                    exit;
                }


                unset($page_data['form_data']['FID']);
                if (intval($page_data['form_data']['user_id']) > 0) {
                    //Update Data
                    $email = provab_encrypt($page_data['form_data']['email']);
                    $this->custom_db->update_record('user', $page_data['form_data'], array('user_id' => $page_data['form_data']['user_id'], 'email' => $email));
                    $this->application_logger->profile_update($this->entity_name, $this->entity_name . ' Updated ' . $page_data['form_data']['first_name'] . ' Profile Details', array('user_id' => $page_data['form_data']['user_id'], 'uuid' => $page_data['form_data']['uuid']));
                    set_update_message();
                } elseif (intval($page_data['form_data']['user_id']) == 0) {



                    if ($user_type_form == B2C_USER) {
                        $this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_useremail_b2c_check');
                    } elseif ($user_type_form == B2B_USER) {

                        $this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_useremail_b2b_check');
                    } elseif ($user_type_form == SUB_ADMIN) {
                        $this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_useremail_subadmin_check');
                    }

                    $this->form_validation->set_rules('password', 'New Password', 'matches[confirm_password]|min_length[5]|max_length[45]|required|callback_valid_password');
                    $this->form_validation->set_rules('confirm_password', 'Confirm');
                    //Insert Data
                    //LETS UNSET DATA WHICH ARE NOT NEEDED FOR DB
                    unset($page_data['form_data']['confirm_password']);
                    if (intval($domain_origin) > 0) {
                        $domain_list_fk = $domain_origin; //DOMAIN ADMIN CREATION BY PROVAB ADMIN
                    } else if (get_domain_auth_id() > 0) {
                        $domain_list_fk = get_domain_auth_id(); //DOMAIN USERS CREATION BY DOMAIN ADMIN
                    } else {
                        $domain_list_fk = 0;
                    }



                    $page_data['form_data']['domain_list_fk'] = $domain_list_fk; //DOMAIN ORIGIN
                    $page_data['form_data']['email'] = provab_encrypt($page_data['form_data']['email']);
                    $page_data['form_data']['country_code'] = $country_code;
                    $page_data['form_data']['user_name'] = $page_data['form_data']['email'];
                    $page_data['form_data']['created_datetime'] = date('Y-m-d H:i:s');
                    $page_data['form_data']['created_by_id'] = $this->entity_user_id;
                    $page_data['form_data']['uuid'] = provab_encrypt(PROJECT_PREFIX . time());
                    $page_data['form_data']['password'] = provab_encrypt(md5(trim($page_data['form_data']['password'])));

                    $insert_id = $this->custom_db->insert_record('user', $page_data['form_data']);
                    /*  B2B User Details Records */
                    if ($page_data['form_data']['user_type'] == B2B_USER) {
                        $page_data['b2b_data']['user_oid'] = $insert_id['insert_id'];
                        $page_data['b2b_data']['balance'] = 0;
                        $page_data['b2b_data']['created_datetime'] = date('Y-m-d H:i:s');
                        $page_data['b2b_data']['created_by_id'] = $this->entity_user_id;
                        $this->custom_db->insert_record('b2b_user_details', $page_data['b2b_data']);
                    }
                    $page_data['form_data']['email'] = provab_decrypt($page_data['form_data']['email']);
                    $page_data['form_data']['uuid'] = provab_decrypt($page_data['form_data']['uuid']);
                    /* B2B User Details Ends */
                    $this->application_logger->registration($this->entity_name, $this->entity_name . ' Registered ' . $page_data['form_data']['email'] . ' From Admin Portal', $this->entity_user_id, array('user_id' => $insert_id['insert_id'], 'uuid' => $page_data['form_data']['uuid']));
                    set_insert_message();
                } else {
                    redirect('security/log_event?event=User Invalid CRUD');
                }
                if (intval(@$get_data['eid']) > 0) {
                    $temp_query_string = str_replace('&eid=' . intval($get_data['eid']), '', $_SERVER['QUERY_STRING']);
                } else {
                    $temp_query_string = $_SERVER['QUERY_STRING'];
                }
                redirect('user/' . __FUNCTION__ . '?' . $temp_query_string);
            }
        }


        //IF DOMAIN ORIGIN IS SET, THEN GET ONLY THAT DOMAIN ADMIN DETAILS
        if (intval($domain_origin) > 0) {
            $condition = array(
                array('U.domain_list_fk', '=', $domain_origin),
                array('U.user_type', '=', ADMIN)
            );
        } else if (valid_array($get_data) == true) {
            $condition = array();
            if (isset($get_data['user_status']) == true) {
                $condition[] = array('U.status', '=', $this->db->escape(intval($get_data['user_status'])));
                $condition[] = array('U.user_type', ' IN (', intval(4), ')');
            }
            if (isset($get_data['uuid']) == true && empty($get_data['uuid']) == false) {
                $condition[] = array('U.uuid', ' like ', $this->db->escape('%' . provab_encrypt($get_data['uuid']) . '%'));
            }
            if (isset($get_data['email']) == true && empty($get_data['email']) == false) {
                $condition[] = array('U.email', ' like ', $this->db->escape('%' . provab_encrypt($get_data['email']) . '%'));
            }
            if (isset($get_data['phone']) == true && empty($get_data['phone']) == false) {
                $condition[] = array('U.phone', ' like ', $this->db->escape('%' . $get_data['phone'] . '%'));
            }
            if (isset($get_data['created_datetime_from']) == true && empty($get_data['created_datetime_from']) == false) {
                $condition[] = array('U.created_datetime', '>=', $this->db->escape(db_current_datetime($get_data['created_datetime_from'])));
            }
            if (isset($get_data['filter']) == true && isset($get_data['q']) == true) {
                switch ($get_data['filter']) {
                    case 'user_type':
                        //Get Users Based on User Types(Active/Inactive Users)
                        if (intval($get_data['q']) > 0) {
                            $condition[] = array('U.user_type', ' IN (', intval($get_data['q']), ')');
                        }
                        break;
                }
            }
        }
        /** TABLE PAGINATION */
        $total_records = $this->user_model->get_domain_user_list($condition, true);
        $page_data['table_data'] = $this->user_model->get_domain_user_list($condition, false, $offset, RECORDS_RANGE_1);
        //echo $this->db->last_query();exit;
        //CHECKING DOMAIN ADMIN EXISTS, IF EXISTS DISABLE ADD FORM IN THE VIEW
        if (intval($domain_origin) > 0 && valid_array($page_data['table_data'])) {
            $page_data['domain_admin_exists'] = true;
        } else {
            $page_data['domain_admin_exists'] = false;
        }
        //debug($get_data);exit;
        if (!empty($get_data['user_status'])) {
            $page_data['user_status'] = $get_data['user_status'];
        }

        $this->load->library('pagination');
        if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['base_url'] = base_url() . 'index.php/user/b2c_user/';
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        $config['total_rows'] = $total_records->total;
        $config['per_page'] = RECORDS_RANGE_1;
        $this->pagination->initialize($config);
        $page_data['search_params'] = $get_data;
        /** TABLE PAGINATION */
        //Get Online User Count
        $this->template->view('user/user_management', $page_data);
    }

    /**
     * Separate Form Generations are used for  b2b alone
     * add/edit are done in b2b_user/b2b_user_edit forms --__
     *  ____________________________________________________/
     *  \__-->in b2b_user_management in ->page_configuration
     * @param number $offset
     */
    function b2b_user($offset = 0)
    {
        $page_data['form_data'] = $this->input->post();
        $this->current_page = new Provab_Page_Loader('b2b_user_management');
        $get_data = $this->input->get();
        $condition = array();
        $page_data['eid'] = intval(@$get_data['eid']);
        if (valid_array($page_data['form_data']) == false && intval(@$page_data['eid']) > 0) {
            /**
             * EDIT DATA
             */
            $e_condition[] = array('U.user_id', '=', $page_data['eid']);
            $edit_data = $this->user_model->get_domain_user_list($e_condition, false, 0, 1);
            if (valid_array($edit_data) == true) {
                $page_data['form_data'] = $edit_data[0];
                $page_data['form_data']['email'] = provab_decrypt($page_data['form_data']['email']);
                $page_data['form_data']['uuid'] = provab_decrypt($page_data['form_data']['uuid']);
            } else {
                redirect('security/log_event?event=Invalid user edit');
            }
        } elseif (valid_array($page_data['form_data']) == true) {
            /** AUTOMATE VALIDATOR **/
            $page_data['form_data']['language_preference'] = 'english';
            $this->current_page->set_auto_validator();
            $this->load->library('form_validation');
            $user_type_form = B2B_USER;
            if ($user_type_form == B2C_USER) {
                $this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_useremail_b2c_check');
            } elseif ($user_type_form == B2B_USER) {

                $this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_useremail_b2b_check');
            } elseif ($user_type_form == SUB_ADMIN) {
                $this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_useremail_subadmin_check');
            }



            $this->form_validation->set_rules('password', 'New Password', 'matches[confirm_password]|min_length[5]|max_length[45]|required|callback_valid_password');
            $this->form_validation->set_rules('confirm_password', 'Confirm');

            if ($this->form_validation->run()) {
                $image_data = array();
                // FILE UPLOAD
                if (valid_array($_FILES) == true and $_FILES['image']['error'] == 0 and $_FILES['image']['size'] > 0) {
                    $img_name = 'Agent_logo-' . time();
                    if (function_exists("check_mime_image_type")) {
                        if (!check_mime_image_type($_FILES['image']['tmp_name'])) {
                            echo "Please select the image files only (gif|jpg|png|jpeg)";
                            exit;
                        }
                    }
                    $config['upload_path'] = $this->template->domain_image_upload_path();
                    $temp_file_name = $_FILES['image']['name'];
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] = 'IMG-' . $img_name;
                    $config['max_size'] = MAX_DOMAIN_LOGO_SIZE;
                    $config['max_width']  = MAX_DOMAIN_LOGO_WIDTH;
                    $config['max_height']  = MAX_DOMAIN_LOGO_HEIGHT;
                    $config['remove_spaces'] = false;
                    // UPLOAD IMAGE
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('image')) {
                        echo $this->upload->display_errors();
                    } else {
                        $image_data = $this->upload->data();
                    }
                }
                $page_data['form_data']['image'] = (empty($image_data['file_name']) == false ? $image_data['file_name'] : '');
                //LETS UNSET DATA WHICH ARE NOT NEEDED FOR DB
                unset($page_data['form_data']['FID']);
                if (intval($page_data['form_data']['user_id']) > 0) {
                    $email = provab_encrypt($page_data['form_data']['email']);
                    $page_data['form_data']['email'] = $email;
                    unset($page_data['form_data']['email']);

                    // debug($page_data);exit;
                    //Update Data
                    $this->custom_db->update_record('user', $page_data['form_data'], array('user_id' => $page_data['form_data']['user_id'], 'email' => $email));
                    $this->application_logger->profile_update($this->entity_name, $this->entity_name . ' Updated ' . $page_data['form_data']['first_name'] . ' Profile Details', array('user_id' => $page_data['form_data']['user_id'], 'uuid' => $page_data['form_data']['uuid']));
                    set_update_message();
                } elseif (intval($page_data['form_data']['user_id']) == 0) {



                    //Insert Data
                    //LETS UNSET DATA WHICH ARE NOT NEEDED FOR DB
                    unset($page_data['form_data']['confirm_password']);
                    $domain_list_fk = get_domain_auth_id(); //DOMAIN USERS CREATION BY DOMAIN ADMIN
                    $page_data['form_data']['domain_list_fk'] = $domain_list_fk; //DOMAIN ORIGIN
                    $page_data['form_data']['email'] = provab_encrypt($page_data['form_data']['email']);

                    $page_data['form_data']['user_name'] = $page_data['form_data']['email'];
                    $page_data['form_data']['created_datetime'] = date('Y-m-d H:i:s');
                    $page_data['form_data']['created_by_id'] = $this->entity_user_id;
                    //$page_data['form_data']['uuid'] = provab_encrypt(PROJECT_PREFIX.time());
                    $get_rand = mt_rand(10000000, 99999999);
                    $page_data['form_data']['uuid'] = provab_encrypt(PROJECT_AGENT_PREFIX . $get_rand);
                    $page_data['form_data']['password'] = provab_encrypt(md5(trim($page_data['form_data']['password'])));
                    $insert_id = $this->custom_db->insert_record('user', $page_data['form_data']);
                    /*  B2B User Details Records */
                    /*get the admin currency*/
                    $get_admin_currency = $this->custom_db->single_table_records('domain_list', 'currency_converter_fk', array('domain_key' => CURRENT_DOMAIN_KEY));
                    $page_data['b2b_data']['currency_converter_fk'] = $get_admin_currency['data'][0]['currency_converter_fk'];

                    $page_data['b2b_data']['user_oid'] = $insert_id['insert_id'];
                    $page_data['b2b_data']['balance'] = 0;
                    $page_data['b2b_data']['created_datetime'] = date('Y-m-d H:i:s');
                    $page_data['b2b_data']['created_by_id'] = $this->entity_user_id;
                    $this->custom_db->insert_record('b2b_user_details', $page_data['b2b_data']);
                    /* B2B User Details Ends */
                    $page_data['form_data']['email'] = provab_decrypt($page_data['form_data']['email']);
                    $page_data['form_data']['uuid'] = provab_decrypt($page_data['form_data']['uuid']);

                    $this->application_logger->registration($this->entity_name, $this->entity_name . ' Registered ' . $page_data['form_data']['email'] . ' From Admin Portal', $this->entity_user_id, array('user_id' => $insert_id['insert_id'], 'uuid' => $page_data['form_data']['uuid']));
                    set_insert_message();
                } else {
                    redirect('security/log_event?event=User Invalid CRUD');
                }
                if (intval(@$get_data['eid']) > 0) {
                    $temp_query_string = str_replace('&eid=' . intval($get_data['eid']), '', $_SERVER['QUERY_STRING']);
                } else {
                    $temp_query_string = $_SERVER['QUERY_STRING'];
                }
                redirect('user/' . __FUNCTION__ . '?' . $temp_query_string);
            }
        }


        //IF DOMAIN ORIGIN IS SET, THEN GET ONLY THAT DOMAIN ADMIN DETAILS
        if (isset($get_data['user_status']) == true) {
            $condition[] = array('U.status', '=', $this->db->escape(intval($get_data['user_status'])));
            $condition[] = array('U.user_type', ' IN (', intval(3), ')');
        }
        if (isset($get_data['agency_name']) == true && empty($get_data['agency_name']) == false) {
            $condition[] = array('U.agency_name', ' like ', $this->db->escape('%' . $get_data['agency_name'] . '%'));
        }
        if (isset($get_data['uuid']) == true && empty($get_data['uuid']) == false) {
            $condition[] = array('U.uuid', ' like ', $this->db->escape('%' . provab_encrypt($get_data['uuid']) . '%'));
        }
        if (isset($get_data['pan_number']) == true && empty($get_data['pan_number']) == false) {
            $condition[] = array('U.pan_number', ' like ', $this->db->escape('%' . $get_data['pan_number'] . '%'));
        }
        if (isset($get_data['email']) == true && empty($get_data['email']) == false) {
            $condition[] = array('U.email', ' like ', $this->db->escape('%' . provab_encrypt($get_data['email']) . '%'));
        }
        if (isset($get_data['phone']) == true && empty($get_data['phone']) == false) {
            $condition[] = array('U.phone', ' like ', $this->db->escape('%' . $get_data['phone'] . '%'));
        }
        if (isset($get_data['created_datetime_from']) == true && empty($get_data['created_datetime_from']) == false) {
            $condition[] = array('U.created_datetime', '>=', $this->db->escape(db_current_datetime($get_data['created_datetime_from'])));
        }
        if (
            isset($get_data['filter']) == true && $get_data['filter'] == 'search_agent' &&
            isset($get_data['filter_agency']) == true && empty($get_data['filter_agency']) == false
        ) {
            $filter_agency = trim($get_data['filter_agency']);
            //Search Filter
            $condition[] = array('U.agency_name', ' like ', $this->db->escape('%' . $filter_agency . '%'));
        }

        //get domain country and city
        $temp_details = $this->custom_db->single_table_records('domain_list', '*', array('origin' => get_domain_auth_id()));
        $page_data['form_data']['api_country_list'] = $temp_details['data'][0];
        $condition[] = array('U.user_type', ' IN (', B2B_USER, ')');

        /** TABLE PAGINATION */

        $page_data['table_data'] = $this->user_model->b2b_user_list($condition, false, $offset, RECORDS_RANGE_3);
        // debug($page_data['table_data']);exit;
        $total_records = $this->user_model->b2b_user_list($condition, true);

        $this->load->library('pagination');
        if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['base_url'] = base_url() . 'index.php/user/b2b_user/';
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        $config['total_rows'] = $total_records->total;
        $config['per_page'] = RECORDS_RANGE_3;
        $this->pagination->initialize($config);
        $page_data['search_params'] = $get_data;
        $page_data['total_rows'] = $total_records->total;

        /** TABLE PAGINATION */
        //Get Online User Count

        $this->template->view('user/b2b_user_management', $page_data);
    }

    /**
     * manage user account in the system :p
     */
    function user_management($offset = 0)
    {
        $page_data['form_data'] = $this->input->post();
        $get_data = $this->input->get();
        if ($get_data['user_status'] == 1) {
            if ((!check_user_previlege('15'))) {
                set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
                    'override_app_msg' => true
                ));
                redirect(base_url());
            }
        }


        if ($get_data['user_status'] == 0) {
            if ((!check_user_previlege('16'))) {
                set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
                    'override_app_msg' => true
                ));
                redirect(base_url());
            }
        }
        // debug($get_data);exit;
        $condition = array();
        //CHECKING DOMAIN ORIGIN SET OR NOT
        if (isset($get_data['domain_origin']) == true && intval($get_data['domain_origin']) > 0) {
            $domain_origin = intval($get_data['domain_origin']);
        } else {
            $domain_origin = 0;
        }

        $page_data['eid'] = intval(@$get_data['eid']);
        if (valid_array($page_data['form_data']) == false && intval(@$page_data['eid']) > 0) {
            /**
             * EDIT DATA
             */
            $edit_data = $this->custom_db->single_table_records('user', '*', array('user_id' => $page_data['eid']));
            if (valid_array($edit_data['data']) == true) {
                $page_data['form_data'] = $edit_data['data'][0];
                $page_data['form_data']['email'] = provab_decrypt($page_data['form_data']['email']);
            } else {
                redirect('security/log_event?event=Invalid user edit');
            }
        } elseif (valid_array($page_data['form_data']) == true) {
            /** AUTOMATE VALIDATOR **/
            $page_data['form_data']['language_preference'] = 'english';
            $this->current_page->set_auto_validator();
            $this->load->library('form_validation');


            if ($this->form_validation->run()) {
                //LETS UNSET DATA WHICH ARE NOT NEEDED FOR DB
                unset($page_data['form_data']['FID']);
                if (intval($page_data['form_data']['user_id']) > 0) {
                    // debug($page_data['form_data']);exit;
                    //Update Data



                    $image_data = array();
                    // FILE UPLOAD
                    if (valid_array($_FILES) == true and $_FILES['image']['error'] == 0 and $_FILES['image']['size'] > 0) {
                        $img_name = 'Agent_logo-' . time();
                        if (function_exists("check_mime_image_type")) {
                            if (!check_mime_image_type($_FILES['image']['tmp_name'])) {
                                echo "Please select the image files only (gif|jpg|png|jpeg)";
                                exit;
                            }
                        }
                        $config['upload_path'] = $this->template->domain_image_upload_path();
                        $temp_file_name = $_FILES['image']['name'];
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] = 'IMG-' . $img_name;
                        $config['max_size'] = MAX_DOMAIN_LOGO_SIZE;
                        $config['max_width']  = MAX_DOMAIN_LOGO_WIDTH;
                        $config['max_height']  = MAX_DOMAIN_LOGO_HEIGHT;
                        $config['remove_spaces'] = false;
                        // UPLOAD IMAGE
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('image')) {
                            echo $this->upload->display_errors();
                        } else {
                            $image_data = $this->upload->data();
                        }
                    }
                    $page_data['form_data']['image'] = (empty($image_data['file_name']) == false ? $image_data['file_name'] : '');



                    $email = provab_encrypt($page_data['form_data']['email']);
                    unset($page_data['form_data']['email']);
                    // debug($page_data);exit;
                    $this->custom_db->update_record('user', $page_data['form_data'], array('user_id' => $page_data['form_data']['user_id'], 'email' => $email));
                    $this->application_logger->profile_update($this->entity_name, $this->entity_name . ' Updated ' . $page_data['form_data']['first_name'] . ' Profile Details', array('user_id' => $page_data['form_data']['user_id'], 'uuid' => $page_data['form_data']['uuid']));
                    set_update_message();
                } elseif (intval($page_data['form_data']['user_id']) == 0) {
                    if ($user_type_form == B2C_USER) {
                        $this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_useremail_b2c_check');
                    } elseif ($user_type_form == B2B_USER) {

                        $this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_useremail_b2b_check');
                    } elseif ($user_type_form == SUB_ADMIN) {
                        $this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_useremail_subadmin_check');
                    }

                    $this->form_validation->set_rules('password', 'New Password', 'matches[confirm_password]|min_length[5]|max_length[45]|required|callback_valid_password');
                    $this->form_validation->set_rules('confirm_password', 'Confirm');
                    //Insert Data
                    //LETS UNSET DATA WHICH ARE NOT NEEDED FOR DB
                    unset($page_data['form_data']['confirm_password']);
                    if (intval($domain_origin) > 0) {
                        $domain_list_fk = $domain_origin; //DOMAIN ADMIN CREATION BY PROVAB ADMIN
                    } else if (get_domain_auth_id() > 0) {
                        $domain_list_fk = get_domain_auth_id(); //DOMAIN USERS CREATION BY DOMAIN ADMIN
                    } else {
                        $domain_list_fk = 0;
                    }

                    $page_data['form_data']['domain_list_fk'] = $domain_list_fk; //DOMAIN ORIGIN
                    $page_data['form_data']['created_datetime'] = date('Y-m-d H:i:s');
                    $page_data['form_data']['created_by_id'] = $this->entity_user_id;
                    $page_data['form_data']['uuid'] = provab_encrypt(PROJECT_PREFIX . time());
                    $page_data['form_data']['email'] = provab_encrypt($page_data['form_data']['email']);
                    $page_data['form_data']['user_name'] = $page_data['form_data']['email'];

                    $page_data['form_data']['password'] = provab_encrypt(md5(trim($page_data['form_data']['password'])));

                    $insert_id = $this->custom_db->insert_record('user', $page_data['form_data']);
                    /*  B2B User Details Records */
                    if ($page_data['form_data']['user_type'] == B2B_USER) {
                        $page_data['b2b_data']['user_oid'] = $insert_id['insert_id'];
                        $page_data['b2b_data']['balance'] = 0;
                        $page_data['b2b_data']['created_datetime'] = date('Y-m-d H:i:s');
                        $page_data['b2b_data']['created_by_id'] = $this->entity_user_id;
                        $this->custom_db->insert_record('b2b_user_details', $page_data['b2b_data']);
                    }
                    /* B2B User Details Ends */
                    $page_data['form_data']['uuid'] = provab_decrypt($page_data['form_data']['uuid']);
                    $page_data['form_data']['email'] = provab_decrypt($page_data['form_data']['email']);

                    $this->application_logger->registration($this->entity_name, $this->entity_name . ' Registered ' . $page_data['form_data']['email'] . ' From Admin Portal', $this->entity_user_id, array('user_id' => $insert_id['insert_id'], 'uuid' => $page_data['form_data']['uuid']));
                    set_insert_message();
                } else {
                    redirect('security/log_event?event=User Invalid CRUD');
                }
                if (intval(@$get_data['eid']) > 0) {
                    $temp_query_string = str_replace('&eid=' . intval($get_data['eid']), '', $_SERVER['QUERY_STRING']);
                } else {
                    $temp_query_string = $_SERVER['QUERY_STRING'];
                }
                redirect('user/' . __FUNCTION__ . '?' . $temp_query_string);
            }
        }

        // debug($get_data);exit;
        //IF DOMAIN ORIGIN IS SET, THEN GET ONLY THAT DOMAIN ADMIN DETAILS
        if (intval($domain_origin) > 0) {
            $condition = array(
                array('U.domain_list_fk', '=', $domain_origin),
                array('U.user_type', '=', ADMIN)
            );
        } else if (valid_array($get_data) == true) {
            $condition = array();
            if (isset($get_data['user_status']) == true) {
                $condition[] = array('U.status', '=', $this->db->escape(intval($get_data['user_status'])));
                $condition[] = array('U.user_type', ' IN (', intval(2), ')');
            }
            if (isset($get_data['uuid']) == true && empty($get_data['uuid']) == false) {
                $condition[] = array('U.uuid', ' like ', $this->db->escape('%' . provab_encrypt($get_data['uuid']) . '%'));
            }
            if (isset($get_data['email']) == true && empty($get_data['email']) == false) {
                $condition[] = array('U.email', ' like ', $this->db->escape('%' . provab_encrypt($get_data['email']) . '%'));
            }
            if (isset($get_data['phone']) == true && empty($get_data['phone']) == false) {
                $condition[] = array('U.phone', ' like ', $this->db->escape('%' . $get_data['phone'] . '%'));
            }
            if (isset($get_data['created_datetime_from']) == true && empty($get_data['created_datetime_from']) == false) {
                $condition[] = array('U.created_datetime', '>=', $this->db->escape(db_current_datetime($get_data['created_datetime_from'])));
            }
            if (isset($get_data['filter']) == true && isset($get_data['q']) == true) {

                $condition = array();
                if (isset($get_data['user_status']) == true) {

                    $condition[] = array('U.status', '=', intval($get_data['user_status']));
                    $page_data['user_status'] = intval($get_data['user_status']);
                }
                switch ($get_data['filter']) {
                    case 'user_type':
                        //Get Users Based on User Types(Active/Inactive Users)
                        if (intval($get_data['q']) > 0) {
                            $condition[] = array('U.user_type', ' IN (', intval($get_data['q']), ')');
                        }
                        break;
                }
            }
        }
        // debug($condition);exit;
        /** TABLE PAGINATION */
        $total_records = $this->user_model->get_domain_user_list($condition, true);
        $page_data['table_data'] = $this->user_model->get_domain_user_list($condition, false, $offset, RECORDS_RANGE_1);

        //CHECKING DOMAIN ADMIN EXISTS, IF EXISTS DISABLE ADD FORM IN THE VIEW
        if (intval($domain_origin) > 0 && valid_array($page_data['table_data'])) {
            $page_data['domain_admin_exists'] = true;
        } else {
            $page_data['domain_admin_exists'] = false;
        }
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'index.php/general/user/';
        $config['total_rows'] = $total_records->total;
        $config['per_page'] = RECORDS_RANGE_1;
        $this->pagination->initialize($config);
        $page_data['search_params'] = $get_data;
        /** TABLE PAGINATION */
        //Get Online User Count
        $this->template->view('user/user_management', $page_data);
    }

    function invester_email_voucher($app_reference, $booking_status, $module, $email, $id)
    {
        if ($module == 'email_voucher') {
            // debug($app_reference);
            // debug($email);exit;
            $page_data['data']['id'] = $id;
            $this->load->library('provab_pdf');
            $create_pdf = new Provab_Pdf();
            $mail_template1 = $this->template->isolated_view('voucher/investor_email_pdf', $page_data);
            $mail_template2 = $this->template->isolated_view('voucher/investor_pdf', $page_data);
            $pdf = $create_pdf->create_pdf($mail_template2, 'F');
            $this->load->library('provab_mailer');
            $this->provab_mailer->send_mail($email, domain_name() . ' - Investor Reciept', $mail_template1, $pdf);
        }
    }

    function confirm_investor($uid)
    {
        $payment_status = 'accepted';
        $this->custom_db->update_record('plan_retirement', array('payment_status' => $payment_status), array('id' => $uid));
        $this->session->set_flashdata(array('message' => 'UL0013', 'type' => SUCCESS_MESSAGE));
        redirect('cms/plan_retirement');
    }

    /**
     * Activate User Account
     */
    function activate_account($user_id, $uuid)
    {
        $cond['user_id'] = intval($user_id);
        $cond['uuid'] = $uuid;
        $data['status'] = ACTIVE;
        $info = $this->user_model->update_user_data($data, $cond);
        if ($info['status'] == SUCCESS_STATUS) {
            $task = 'activate';
            $this->account_status($info, $task);
        }
        exit;
        /*redirect(base_url().'user/user_management?filter=user_type&q='.$info['data']['user_type']);*/
    }

    /**
     * Deactiavte User Account
     */
    function deactivate_account($user_id, $uuid)
    {
        $cond['user_id'] = intval($user_id);
        $cond['uuid'] = $uuid;
        $data['status'] = INACTIVE;
        $info = $this->user_model->update_user_data($data, $cond);
        if ($info['status'] == SUCCESS_STATUS) {
            $task = 'deactivate';
            $this->account_status($info, $task);
        }
        exit;
        /*redirect(base_url().'user/user_management?filter=user_type&q='.$info['data']['user_type']);*/
    }

    /**
     * Send Account Status Email To User
     * @param $data
     */
    function account_status($data, $task)
    {
        //echo APP_ROOT_DIR;
        //exit;
        if ($data['data']['user_type'] == B2C_USER) {
            $module_name = 'B2C';
        } else if ($data['data']['user_type'] == B2B_USER) {
            $module_name = 'B2B';
        }
        if ($task == 'deactivate') {
            //Sms config & Checkpoint
            if (active_sms_checkpoint('account_deactivate')) {
                $msg = "Dear " . $data['data']['first_name'] . " Your '.$module_name.' Account Has Been Deactivated. Details are sent to your email id";
                $msg = urlencode($msg);
                $this->provab_sms->send_msg($data['data']['phone'], $msg);
            } //sms will be sent

            //Email Configuration
            $mail_template = $this->template->isolated_view('user/account_deactivation_template', $data['data']);
            $email = provab_decrypt($data['data']['email']);
            $this->load->library('provab_mailer');

            if ($data['data']['user_type'] == '3') {
                $this->provab_mailer->send_mail($email, 'B2B Account Deactivation', $mail_template);
            } else {
                $this->provab_mailer->send_mail($email, 'B2C Account Deactivation', $mail_template);
            }
            //$this->provab_mailer->send_mail($email, $module_name.' Account Deactivation', $mail_template);
            //Email Will be sent

        } else {
            //Sms config & Checkpoint
            if (active_sms_checkpoint('account_activate')) {
                $msg = "Dear " . $data['data']['first_name'] . " Your '.$module_name.' Account Has Been Activated. Details are sent to your email id";
                $msg = urlencode($msg);
                $this->provab_sms->send_msg($data['data']['phone'], $msg);
            } //sms will be sent

            //Email Configuration
            //$mail_template = $this->template->isolated_view('user/account_activation_template', $data['data']);
            $email = provab_decrypt($data['data']['email']);
            $this->load->library('provab_mailer');
            //$this->provab_mailer->send_mail($email, $module_name.' Account Activation', $mail_template);
            $get_admin_currency = $this->custom_db->single_table_records('domain_list', '*', array('domain_key' => CURRENT_DOMAIN_KEY));
            $data['data']['admin_address'] = $get_admin_currency['data'][0]['address'];
            $data['data']['user_name'] = provab_decrypt($data['data']['user_name']);
            if ($data['data']['user_type'] == '3') {
                if ($data['data']['pwd_token'] == 1) {
                    $mail_template = $this->template->isolated_view('user/first_time_activation_template_agent', $data['data']);
                    $tokenStatus['pwd_token'] = 0;
                    $this->user_model->update_user_data($tokenStatus, array('user_id' => $data['data']['user_id']));
                    //$email = $data['data']['email'];

                } else {
                    $mail_template = $this->template->isolated_view('user/account_activation_template', $data['data']);
                    //$email = $data['data']['email'];
                }

                $this->provab_mailer->send_mail($email, ' B2B Travel Portal Activation ', $mail_template);
            } else {
                $mail_template = $this->template->isolated_view('user/account_activation_template', $data['data']);
                //$email = $data['data']['email'];
                $this->provab_mailer->send_mail($email, ' B2C Travel Portal Activation', $mail_template);
            }
            //Email Will be sent
        }
    }

    /**
     * Generate my account view to user
     */
    function account()
    {
        $page_data['form_data'] = $this->input->post();
        $get_data = $this->input->get();
        //debug($get_data);exit;
        /**
         * USE USER PAGE FOR MY ACCOUNT
         * @var unknown_type
         */
        $this->user_page = new Provab_Page_Loader('user_management');
        if (isset($get_data['uid']) == true) {
            $get_data['uid'] = intval($get_data['uid']);
            $user_id = intval($get_data['uid']);
            if (valid_array($page_data['form_data']) == false) {
                /*** EDIT DATA ***/
                //$cond = array(array('U.user_id', '=', intval($get_data['uid'])));
                $cond['user_id'] = intval($user_id);
                //debug($cond);exit;
                $edit_data = $this->user_model->get_user_details($cond);
                if (valid_array($edit_data) == true) {
                    $page_data['form_data'] = $edit_data[0];
                    // debug($page_data['form_data']);
                    // exit;
                    $page_data['form_data']['uuid'] = provab_decrypt($page_data['form_data']['uuid']);
                    $page_data['form_data']['email'] = provab_decrypt($page_data['form_data']['email']);
                } else {
                    redirect('security/log_event');
                }
            } elseif (valid_array($page_data['form_data']) == true && (check_default_edit_privilege($get_data['uid']) || super_privilege())) {
                /** AUTOMATE VALIDATOR **/
                $page_data['form_data']['language_preference'] = 'english';
                $this->user_page->set_auto_validator();
                if ($this->form_validation->run()) {
                    if (intval($get_data['uid']) === intval($page_data['form_data']['user_id']) && intval($page_data['form_data']['user_id']) > 0) {
                        //Update Data -- LETS UNSET POSTED DATA
                        unset($page_data['form_data']['FID']);
                        unset($page_data['form_data']['email']);
                        $this->custom_db->update_record('user', $page_data['form_data'], array('user_id' => $page_data['form_data']['user_id']));
                        $this->application_logger->profile_update($page_data['form_data']['first_name'], $page_data['form_data']['first_name'] . ' Updated Profile Details', array('user_id' => $this->entity_user_id, 'uuid' => $this->entity_uuid));
                        set_update_message();
                        //FILE UPLOAD
                        if (valid_array($_FILES) == true and $_FILES['image']['error'] == 0 and $_FILES['image']['size'] > 0) {
                            if (function_exists("check_mime_image_type")) {
                                if (!check_mime_image_type($_FILES['image']['tmp_name'])) {
                                    echo "Please select the image files only (gif|jpg|png|jpeg)";
                                    exit;
                                }
                            }
                            $config['upload_path'] = $this->template->domain_image_upload_path();
                            $config['allowed_types'] = 'gif|jpg|png|jpeg';
                            $config['file_name'] = time();
                            $config['max_size'] = '1000000';
                            $config['max_width']  = '';
                            $config['max_height']  = '';
                            $config['remove_spaces']  = false;
                            $user_id = $page_data['form_data']['user_id'];
                            //debug($config);exit;
                            //UPDATE
                            $temp_record = $this->custom_db->single_table_records('user', 'image', array('user_id' => $user_id));
                            $icon = $temp_record['data'][0]['image'];
                            //DELETE OLD FILES
                            if (empty($icon) == false) {
                                $temp_profile_image = $this->template->domain_image_full_path($icon); //GETTING FILE PATH
                                if (file_exists($temp_profile_image)) {
                                    unlink($temp_profile_image);
                                }
                            }
                            //UPLOAD IMAGE
                            $this->load->library('upload', $config);
                            if (!$this->upload->do_upload('image')) {
                                echo $this->upload->display_errors();
                            } else {
                                $image_data =  $this->upload->data();
                            }
                            $this->custom_db->update_record('user', array('image' => $image_data['file_name']), array('user_id' => $user_id));
                        }
                        refresh();
                    } else {
                        redirect('security/log_event');
                    }
                } else {
                }
            }
            /** ADD DISABLED STATE **/
            $this->template->view('user/account', $page_data);
        } else {
            redirect('security/log_event');
        }
    }

    function b2as_user($offset = 0)
    {


        $img_url = str_replace(PROJECT_FOLDER . '/supervision', '', base_url());
        // debug($img_url);exit();
        $url = $this->input->get();


        /*  if($url['user_status']==1)
    {
        if ((!check_user_previlege('11')))
         {
          set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
          'override_app_msg' => true
          ));
           redirect(base_url());
        }
    }


    if($url['user_status']==0)
    {
        if ((!check_user_previlege('12')))
         {
          set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
          'override_app_msg' => true
          ));
           redirect(base_url());
        }
    }
*/


        /*if (! check_user_previlege ( 'p13' )) {
            debug("fgdf");exit;
            set_update_message ( "You Don't have permission to do this action.", WARNING_MESSAGE, array (
                    'override_app_msg' => true
            ) );
            redirect ( base_url () );
        }*/
        // exit("asdsa");

        $page_data['form_data'] = $this->input->post();
        if ($_GET['m'] == 1) {
            debug($this->input->get());
            debug($page_data['form_data']);
            exit();
        }
        //debug($page_data ['form_data']);exit;


        // $this->current_page = new Provab_Page_Loader ( 'b2as_user_management' );
        $get_data = $this->input->get();
        //debug($get_data);exit;
        $condition = array();
        // $page_data ['eid'] = intval ( @$get_data ['eid'] );

        if (valid_array($page_data['form_data']) == false && intval($page_data['eid']) > 0) {
            //debug("if");exit;

            /**
             * EDIT DATA
             */
            $e_condition[] = array(
                'U.user_id',
                '=',
                $page_data['eid']
            );
            // $edit_data = $this->user_model->get_b2b_user_list ( $e_condition, false, 0, 1 );
            $edit_data = $this->custom_db->single_table_records('user', '', array('user_id' => $page_data['eid']));
            // debug($edit_data);exit();

            if (valid_array($edit_data) == true) {
                $page_data['form_data'] = $edit_data['data'][0];
                $page_data['form_data']['date_of_birth'] = date('d-m-Y', strtotime($page_data['form_data']['date_of_birth']));
                $page_data['form_data']['prev_group_fk'] = $page_data['form_data']['group_fk'];
            } else {

                // debug(valid_array ( $edit_data ));exit;
                redirect('security/log_event?event=Invalid user edit');
            }
        } elseif (valid_array($page_data['form_data']) == true) {
            //debug("else");exit;

            /**
             * AUTOMATE VALIDATOR *
             */

            $page_data['form_data']['language_preference'] = 'english';
            // $this->current_page->set_auto_validator ();


            if ($page_data['form_data']) {
                // debug($_FILES);exit;
                $image_data = array();
                // FILE UPLOAD
                if (valid_array($_FILES) == true and $_FILES['panimage']['error'] == 0 and $_FILES['panimage']['size'] > 0) {
                    $img_name = 'compid' . time();
                    $config['upload_path'] = $this->template->domain_image_upload_path();
                    $temp_file_name = $_FILES['panimage']['name'];
                    $config['allowed_types'] = '*';
                    $config['file_name'] = 'IMG-' . $img_name;
                    $config['max_size'] = '1000000';
                    $config['max_width'] = '';
                    $config['max_height'] = '';
                    $config['remove_spaces'] = false;
                    // UPLOAD IMAGE
                    //debug($config ['upload_path']);exit;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('panimage')) {
                        echo $this->upload->display_errors();
                        //debug("not uploaded");

                    } else {
                        $image_data = $this->upload->data();
                        //debug("uploaded");
                    }
                    //exit();
                    $page_data['form_data']['panimage'] = (empty($image_data['file_name']) == false ? $image_data['file_name'] : '');
                }

                if (valid_array($_FILES) == true and $_FILES['gstimage']['error'] == 0 and $_FILES['gstimage']['size'] > 0) {
                    $img_name = 'certifi' . time();
                    $config['upload_path'] = $this->template->domain_image_upload_path();
                    $temp_file_name = $_FILES['gstimage']['name'];
                    $config['allowed_types'] = '*';
                    $config['file_name'] = 'IMG-' . $img_name;
                    $config['max_size'] = '1000000';
                    $config['max_width'] = '';
                    $config['max_height'] = '';
                    $config['remove_spaces'] = false;
                    // UPLOAD IMAGE
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('gstimage')) {
                        echo $this->upload->display_errors();
                    } else {
                        $image_data2 = $this->upload->data();
                    }
                    $page_data['form_data']['gstimage'] = (empty($image_data2['file_name']) == false ? $image_data2['file_name'] : '');
                }
                // debug($image_data);
                // debug($image_data2);exit;

                // LETS UNSET DATA WHICH ARE NOT NEEDED FOR DB
                // unset ( $page_data ['form_data'] ['FID'] );

                if (intval($page_data['form_data']['user_id']) > 0) {
                    // Update Data

                    $crs_type = implode(',', $page_data['form_data']['user_type']);
                    // debug($crs_type);exit;
                    $page_data['form_data_2']['supplier_privailage'] = $crs_type;

                    // debug($page_data ['form_data_2']);exit();

                    $this->custom_db->update_record('supplier_crs_privilage', $page_data['form_data_2'], array(
                        'supplier_id' => $page_data['form_data']['user_id']
                    ));

                    // debug($page_data ['form_data']);exit;
                    // debug($crs_type);
                    // exit;
                    // $prev_group_fk = $page_data ['form_data'] ['prev_group_fk'];
                    // unset ( $page_data ['form_data'] ['prev_group_fk'] );
                    // unset ( $page_data ['form_data'] ['user_id'] );
                    unset($page_data['form_data']['uuid']);
                    unset($page_data['form_data']['user_type']);
                    // unset ( $page_data ['form_data'] ['image'] );
                    unset($page_data['form_data']['email']);
                    unset($page_data['form_data']['date_of_birth']);


                    $page_data['form_data']['date_of_birth'] = date('Y-m-d', strtotime($page_data['form_data']['date_of_birth']));


                    $page_data['form_data']['pan_no'] = $page_data['form_data']['pan_number'];
                    $page_data['form_data']['gst_no'] = $page_data['form_data']['pan_holdername'];
                    $page_data['form_data']['password'] = provab_encrypt(md5($page_data['form_data']['password']));
                    // $page_data['form_data']['pin_code'] = $page_data['form_data']['postal_code']; 
                    // $page_data['form_data']['comp_website_link'] = $page_data['form_data']['comp_website_link'];  
                    // $page_data['form_data']['comp_email'] = $page_data['form_data']['comp_email']; 

                    // $page_data['form_data']['city_name'] = $page_data['form_data']['city_name'];

                    $page_data['form_data']['state_txt'] = $page_data['form_data']['state_name'];

                    unset($page_data['form_data']['company_reg_id']);
                    unset($page_data['form_data']['travel_licence_no']);
                    unset($page_data['form_data']['postal_code']);
                    unset($page_data['form_data']['confirm_password']);
                    $curr_user_id = $page_data['form_data']['user_id'];
                    // unset($page_data['form_data']['user_id']); 
                    // unset($page_data['form_data']['country_code']); 
                    unset($page_data['form_data']['country']);




                    // debug($curr_user_id);
                    // debug($page_data ['form_data']);
                    $this->db->where('user_id', $curr_user_id);
                    $flag = $this->db->update('user', $page_data['form_data']);

                    // echo $this->db->last_query();exit;
                    // debug($flag);
                    // exit;

                    // $flag = $this->custom_db->update_record ( 'user', $page_data ['form_data'], array (
                    //      'user_id' => $curr_user_id
                    // ) );
                    // debug($flag);exit;


                    // debug($page_data ['form_data_2'] );







                    // $sup_insert_id = $this->custom_db->insert_record ( 'supplier_crs_privilage', $page_data ['form_data_2'] );


                    // if ($flag && $prev_group_fk != $page_data ['form_data'] ['group_fk']) {
                    //  $this->custom_db->delete_record ( 'markup_list', array (
                    //          'user_oid' => $page_data ['form_data'] ['user_id'],
                    //          'group_fk' => $prev_group_fk 
                    //  ) );
                    //  $this->custom_db->delete_record ( 'flight_commission', array (
                    //          'user_id' => $page_data ['form_data'] ['user_id'],
                    //          'group_fk !=' => $prev_group_fk 
                    //  ) );
                    //  $this->custom_db->delete_record ( 'bus_commission', array (
                    //          'user_id' => $page_data ['form_data'] ['user_id'],
                    //          'group_fk !=' => $prev_group_fk 
                    //  ) );
                    //  // exit;
                    // }
                    // $this->application_logger->profile_update ( $this->entity_name, $this->entity_name . ' Updated ' . $page_data ['form_data'] ['first_name'] . ' Profile Details', array (
                    //      'user_id' => $page_data ['form_data'] ['user_id'],
                    //      'uuid' => $page_data ['form_data'] ['uuid'] 
                    // ) );
                    // set_update_message ();
                }

                // debug(intval($page_data ['form_data'] ['user_id']));exit();

                if (intval($page_data['form_data']['user_id']) == 0) {
                    // Insert Data
                    // LETS UNSET DATA WHICH ARE NOT NEEDED FOR DB


                    $crs_type = implode(',', $page_data['form_data']['user_type']);

                    unset($page_data['form_data']['confirm_password']);
                    unset($page_data['form_data']['user_id']);
                    $domain_list_fk = get_domain_auth_id(); // DOMAIN USERS CREATION BY DOMAIN ADMIN
                    $page_data['form_data']['domain_list_fk'] = $domain_list_fk; // DOMAIN ORIGIN
                    $page_data['form_data']['created_datetime'] = date('Y-m-d H:i:s');
                    $page_data['form_data']['created_by_id'] = $this->entity_user_id;
                    $page_data['form_data']['uuid'] = generate_app_user_uuid(SUPPLIER);
                    $page_data['form_data']['creation_source'] = 'admin';
                    $page_data['form_data']['user_type'] = SUPPLIER;
                    $page_data['form_data']['country_name'] = $page_data['form_data']['country'];
                    unset($page_data['form_data']['country']);

                    $page_data['form_data']['date_of_birth'] = date('Y-m-d', strtotime($page_data['form_data']['date_of_birth']));

                    $page_data['form_data']['email'] = provab_encrypt($page_data['form_data']['email']);
                    // $page_data['form_data']['user_name'] = provab_encrypt($page_data['form_data']['email']);
                    $page_data['form_data']['user_name'] = $page_data['form_data']['email'];
                    $page_data['form_data']['uuid'] = provab_encrypt(PROJECT_PREFIX . time());
                    $page_data['form_data']['password'] = provab_encrypt(md5($page_data['form_data']['password']));
                    $emaill = provab_encrypt($page_data['form_data']['email']);


                    $service_type = $page_data['form_data']['service_type'];
                    $service_type = implode(',', $service_type);
                    $page_data['form_data']['service_type'] = $service_type;
                    $page_data['form_data']['tour_company_name'] = $page_data['form_data']['tour_company_name'];
                    $page_data['form_data']['tour_authorised_person'] = $page_data['form_data']['tour_authorised_person'];
                    $page_data['form_data']['tour_contact_person'] = $page_data['form_data']['tour_contact_person'];
                    $page_data['form_data']['tour_supplier_site'] = $page_data['form_data']['tour_supplier_site'];
                    $page_data['form_data']['tour_country'] = $page_data['form_data']['tour_country'];
                    $page_data['form_data']['tour_business_type'] = $page_data['form_data']['tour_business_type'];

                    $page_data['form_data']['hotel_company_name'] = $page_data['form_data']['hotel_company_name'];
                    $page_data['form_data']['hotel_authorised_person'] = $page_data['form_data']['hotel_authorised_person'];
                    $page_data['form_data']['hotel_contact_person'] = $page_data['form_data']['hotel_contact_person'];
                    $page_data['form_data']['hotel_star_rating'] = $page_data['form_data']['hotel_star_rating'];
                    $page_data['form_data']['hotel_num_room'] = $page_data['form_data']['hotel_num_room'];
                    $page_data['form_data']['hotel_supplier_site'] = $page_data['form_data']['hotel_supplier_site'];
                    $page_data['form_data']['hotel_country'] = $page_data['form_data']['hotel_country'];
                    $page_data['form_data']['hotel_business_type'] = $page_data['form_data']['hotel_business_type'];


                    $page_data['form_data']['transfer_company_name'] = $page_data['form_data']['transfer_company_name'];
                    $page_data['form_data']['transfer_authorised_person'] = $page_data['form_data']['transfer_authorised_person'];
                    $page_data['form_data']['transfer_contact_person'] = $page_data['form_data']['transfer_contact_person'];
                    $page_data['form_data']['transfer_supplier_site'] = $page_data['form_data']['transfer_supplier_site'];
                    $page_data['form_data']['transfer_country'] = $page_data['form_data']['transfer_country'];
                    $page_data['form_data']['transfer_business_type'] = $page_data['form_data']['transfer_business_type'];

                    $page_data['form_data']['car_company_name'] = $page_data['form_data']['car_company_name'];
                    $page_data['form_data']['car_authorised_person'] = $page_data['form_data']['car_authorised_person'];
                    $page_data['form_data']['car_contact_person'] = $page_data['form_data']['car_contact_person'];
                    $page_data['form_data']['car_supplier_site'] = $page_data['form_data']['car_supplier_site'];
                    $page_data['form_data']['car_country'] = $page_data['form_data']['car_country'];
                    $page_data['form_data']['car_business_type'] = $page_data['form_data']['car_business_type'];


                    $page_data['form_data']['jet_company_name'] = $page_data['form_data']['jet_company_name'];
                    $page_data['form_data']['jet_authorised_person'] = $page_data['form_data']['jet_authorised_person'];
                    $page_data['form_data']['jet_contact_person'] = $page_data['form_data']['jet_contact_person'];
                    $page_data['form_data']['jet_supplier_site'] = $page_data['form_data']['jet_supplier_site'];
                    $page_data['form_data']['jet_country'] = $page_data['form_data']['jet_country'];
                    $page_data['form_data']['jet_business_type'] = $page_data['form_data']['jet_business_type'];

                    $page_data['form_data']['tour_operator'] = $page_data['form_data']['tour_operator'];
                    $page_data['form_data']['transfer_type'] = $page_data['form_data']['transfer_type'];
                    $page_data['form_data']['transfer_quantity'] = $page_data['form_data']['transfer_quantity'];
                    $page_data['form_data']['car_type'] = $page_data['form_data']['car_type'];
                    $page_data['form_data']['car_quantity'] = $page_data['form_data']['car_quantity'];
                    $page_data['form_data']['jet_type'] = $page_data['form_data']['jet_type'];
                    $page_data['form_data']['jet_quantity'] = $page_data['form_data']['jet_quantity'];
















                    /*$this->db->select('*');
                $this->db->where('email',$emaill);
                $this->db->where('user_type',8);
                $query = $this->db->get('user');

                $num = $query->num_rows();
                
                if($num > 0){


                $this->session->set_flashdata(array('error_message' => 'Username id already registered.', 'type' => ERROR_MESSAGE, 'override_app_msg' => true));
                redirect('user/b2as_user?user_status=1');
                die;

                }*/

                    $insert_id = $this->custom_db->insert_record('user', $page_data['form_data']);
                    // debug($insert_id);exit;

                    $page_data['form_data_2']['supplier_id'] = $insert_id['insert_id'];
                    $page_data['form_data_2']['supplier_privailage'] = $crs_type;
                    // debug($page_data ['form_data_2'] );

                    // $sup_insert_id = $this->custom_db->insert_record ( 'supplier_crs_privilage', $page_data ['form_data_2'] );
                    // exit();
                    /* B2B User Details Records */
                    $page_data['b2b_data']['user_oid'] = $insert_id['insert_id'];
                    $page_data['b2b_data']['balance'] = 0;
                    $page_data['b2b_data']['credit_limit'] = 0;
                    $page_data['b2b_data']['due_amount'] = 0;
                    $get_admin_currency = $this->custom_db->single_table_records('domain_list', 'currency_converter_fk', array('domain_key' => CURRENT_DOMAIN_KEY));
                    $page_data['b2b_data']['currency_converter_fk'] = $get_admin_currency['data'][0]['currency_converter_fk'];
                    //$page_data ['b2b_data'] ['created_datetime'] = date ( 'Y-m-d H:i:s' );
                    $page_data['b2b_data']['created_datetime'] = date('Y-m-d H:i:s');
                    $page_data['b2b_data']['created_by_id'] = $this->entity_user_id;
                    $page_data['b2b_data']['reporting_to_id'] = 0;
                    // debug($page_data ['b2b_data']);exit;
                    $this->custom_db->insert_record('b2b_user_details', $page_data['b2b_data']);
                    /* B2B User Details Ends */
                    $this->application_logger->registration($this->entity_name, $this->entity_name . ' Registered ' . $page_data['form_data']['email'] . ' From Admin Portal', $this->entity_user_id, array(
                        'user_id' => $insert_id['insert_id'],
                        'uuid' => $page_data['form_data']['uuid']
                    ));
                    //  $email_templet = $this->template->isolated_view ('user/user_registration_template_admin');
                    // Send email 
                    //supervision_email('supervision',$page_data ['form_data'] ['email'],'b2b user-Activation',$email_templet);

                    // set_insert_message ();
                } else {
                    // debug(intval ( $page_data ['form_data'] ['user_id']));exit();
                    // redirect ( 'security/log_event?event=User Invalid CRUD' );

                }
                if (intval(@$get_data['eid']) > 0) {
                    $temp_query_string = str_replace('&eid=' . intval($get_data['eid']), '', $_SERVER['QUERY_STRING']);
                } else {
                    $temp_query_string = $_SERVER['QUERY_STRING'];
                }
                redirect('user/' . __FUNCTION__ . '?' . $temp_query_string);
            }
        }


        // IF DOMAIN ORIGIN IS SET, THEN GET ONLY THAT DOMAIN ADMIN DETAILS
        if (isset($get_data['user_status']) == true) {
            $condition[] = array(
                'U.status',
                '=',
                $this->db->escape(intval($get_data['user_status']))
            );
        }
        if (isset($get_data['agency_name']) == true && empty($get_data['agency_name']) == false) {
            $condition[] = array(
                'U.agency_name',
                ' like ',
                $this->db->escape('%' . $get_data['agency_name'] . '%')
            );
        }
        if (isset($get_data['service_type']) == true && empty($get_data['service_type']) == false) {
            $condition[] = array(
                'U.service_type',
                ' like ',
                $this->db->escape('%' . $get_data['service_type'] . '%')
            );
        }
        if (isset($get_data['uuid']) == true && empty($get_data['uuid']) == false) {
            $condition[] = array(
                'U.uuid',
                ' like ',
                $this->db->escape('%' . $get_data['uuid'] . '%')
            );
        }
        if (isset($get_data['pan_number']) == true && empty($get_data['pan_number']) == false) {
            $condition[] = array(
                'U.pan_number',
                ' like ',
                $this->db->escape('%' . $get_data['pan_number'] . '%')
            );
        }
        if (isset($get_data['email']) == true && empty($get_data['email']) == false) {
            $condition[] = array(
                'U.email',
                ' like ',
                $this->db->escape('%' . $get_data['email'] . '%')
            );
        }
        if (isset($get_data['phone']) == true && empty($get_data['phone']) == false) {
            $condition[] = array(
                'U.phone',
                ' like ',
                $this->db->escape('%' . $get_data['phone'] . '%')
            );
        }
        if (isset($get_data['created_datetime_from']) == true && empty($get_data['created_datetime_from']) == false) {
            $condition[] = array(
                'U.created_datetime',
                '>=',
                $this->db->escape(db_current_datetime($get_data['created_datetime_from']))
            );
        }
        if (isset($get_data['group_fk']) == true && empty($get_data['group_fk']) == false) {
            $condition[] = array(
                'U.group_fk',
                '=',
                $this->db->escape(intval($get_data['group_fk']))
            );
        }
        if (isset($get_data['reporting_to_id']) == true && empty($get_data['reporting_to_id']) == false) {
            $condition[] = array(
                'b2b_a.reporting_to_id',
                '=',
                $this->db->escape(intval($get_data['reporting_to_id']))
            );
        }
        if (isset($get_data['st']) == true && empty($get_data['st']) == false) {
            $st = $this->db->escape('%' . trim($get_data['st']) . '%');
            $or_c = 'U.agency_name like ' . $st . ' OR U.phone like ' . $st . ' OR U.email like ' . $st . ' OR U.pan_number like ' . $st . ' OR U.uuid like ' . $st . ' OR U.first_name like ' . $st . ' OR U.last_name like ' . $st . ' OR U.office_phone like ' . $st . ' OR U.address like ' . $st . ' OR U.address2 like ' . $st . ' OR U.pin_code like ' . $st . ' OR IUD.mac_id like ' . $st . ' OR IUD.irctc_username like ' . $st;
            $condition[] = array(
                '(',
                $or_c,
                ')'
            );
        }

        $condition[] = array(
            'U.user_type',
            ' IN (',
            SUPPLIER,
            ')'
        );

        //echo debug($condition);die;

        if ($this->check_operation($offset)) {

            $op_data = $this->user_model->b2b_user_list($condition);
            // debug($op_data); exit;
            $col = array(
                'agency_name' => 'Agency Name',
                'uuid' => 'Agency ID',
                'pan_number' => 'PAN',
                'agent_name' => array(
                    'title' => 'Agent Name',
                    'cols' => array(
                        'first_name',
                        'last_name'
                    ),
                    'sep' => ' '
                ),
                'agent_balance' => 'Balance',
                'agent_due_amount' => 'Due Amount',
                'agent_credit_limit' => 'Credit Limit',
                'phone' => 'Phone',
                'office_phone' => 'Office Phone',
                'email' => 'Email',
                'address' => array(
                    'title' => 'Address',
                    'cols' => array(
                        'address',
                        'address2'
                    ),
                    'sep' => '<br/>'
                )
            );
            $this->perform_operation($offset, $op_data, $col, 'agent_list');
        }

        $offset = intval($offset);
        /**
         * TABLE PAGINATION
         */

        // debug($condition);exit();
        $page_data['table_data'] = $this->user_model->b2b_user_list($condition, false, $offset, RECORDS_RANGE_3);
        $total_records = $this->user_model->b2b_user_list($condition, true);
        $this->load->library('pagination');
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['base_url'] = base_url() . 'index.php/user/b2b_user/';
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        $config['total_rows'] = $total_records->total;
        $config['per_page'] = RECORDS_RANGE_5;
        $this->pagination->initialize($config);
        $page_data['search_params'] = $get_data;
        $page_data['user_status'] = $offset;
        $page_data['total_rows'] = $total_records->total;
        $page_data['group_list'] = $this->db_cache_api->get_group_list();
        $page_data['dist_list'] = $this->db_cache_api->get_distributor_list();

        $page_data['uploaded_path'] = $this->template->domain_images();

        // debug($page_data['uploaded_path']);exit();
        // debug($total_records); exit;
        /**
         * TABLE PAGINATION
         */
        // Get Online User Count
        // echo "<pre>"; print_r($page_data); echo "</pre>"; die();
        // debug($page_data);exit;
        if (isset($get_data['eid']) && $get_data['eid'] > 0) {
            $page_data['form_data'] = $this->custom_db->single_table_records('user', '*', array('user_id' => $get_data['eid']))['data'][0];
            $page_data['eid'] = $get_data['eid'];
            $previ_res = $this->custom_db->single_table_records('supplier_crs_privilage', 'supplier_privailage', array('supplier_id' => $get_data['eid']));
            if ($previ_res['status']) {
                $previ = $previ_res['data'][0]['supplier_privailage'];
                $page_data['form_data']['supplier_privailage'] = explode(',', $previ);
            }
        }
        // debug($page_data['form_data']);exit;
        $this->template->view('user/b2as_user_management', $page_data);
    }

    private function check_operation($op)
    {
        //error_reporting(E_ALL);
        //ini_set('display_errors',1);
        //ini_set('display_startup_errors',1);
        return (strcmp($op, 'excel') == 0 || strcmp($op, 'pdf') == 0);
    }

    private function perform_operation($type, $data, $col = array(), $filename = '')
    {

        if (empty($filename)) {
            $filename = $this->uri->segment(2) . '_' . date('d-M-Y');
        }
        if (strcmp($type, 'excel') == 0) {
            // /$this->export_to_excel($filename,$data, $col);
            $this->load->library('excel');
            // $addn['row1']=array('merge'=>true,'data'=>'Ledger Report of '.$data_logs ['user']['uuid'].' From: '.app_friendly_date($from_date).' To: '.app_friendly_date($to_date));
            //debug($data);die;
            $this->excel->array_to_excel($data, $col, $filename);
        } else if (strcmp($type, 'pdf') == 0) {
            $this->export_to_pdf($filename, $data, $col);
        }
        exit();
    }

    /**
     * Logout function for logout from account and unset all the session variables
     */
    function initilize_logout()
    {
        if (is_logged_in_user()) {
            $this->general_model->update_login_manager($this->session->userdata(LOGIN_POINTER));
            $this->session->unset_userdata(array(AUTH_USER_POINTER => '', LOGIN_POINTER => ''));
            // added by nithin for unseting the email username
            $this->session->unset_userdata('mail_user');
            redirect('general/index');
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
        $data = array();
        $get_data = $this->input->get();
        if (isset($get_data['uid'])) {
            $user_id = $get_data['uid']; //intval($this->encrypt->decode($get_data['uid']));
        } else {
            redirect("general/initilize_logout");
        }
        $page_data['form_data'] = $this->input->post();
        if (valid_array($page_data['form_data']) == TRUE) {
            // $this->current_page->set_auto_validator();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('current_password', 'Current Password', 'required|min_length[5]|max_length[45]|callback_password_check');
            $this->form_validation->set_rules('new_password', 'New Password', 'matches[confirm_password]|min_length[5]|max_length[45]|required|callback_valid_password');
            $this->form_validation->set_rules('confirm_password', 'Confirm', 'callback_check_new_password');
            if ($this->form_validation->run()) {
                $table_name = "user";
                /** Checking New Password and Old Password Are Same OR Not **/
                $condition['password'] = provab_encrypt(md5(trim($this->input->post('new_password'))));
                $condition['user_id'] = $user_id;
                $check_pwd = $this->custom_db->single_table_records($table_name, 'password', $condition);
                if (!$check_pwd['status']) {
                    $condition['password'] = provab_encrypt(md5(trim($this->input->post('current_password'))));

                    $condition['user_id'] = $user_id;
                    $data['password'] = provab_encrypt(md5(trim($this->input->post('new_password'))));
                    $update_res = $this->custom_db->update_record($table_name, $data, $condition);
                    if ($update_res) {
                        $this->session->set_flashdata(array('message' => 'UL0010', 'type' => SUCCESS_MESSAGE));
                        refresh();
                    } else {
                        $this->session->set_flashdata(array('message' => 'UL0011', 'type' => ERROR_MESSAGE));
                        refresh();
                        /*$data['msg'] = 'UL0011';
                         $data['type'] = ERROR_MESSAGE;*/
                    }
                } else {
                    $this->session->set_flashdata(array('message' => 'UL0012', 'type' => WARNING_MESSAGE));
                    refresh();
                    //redirect('general/change_password?uid='.urlencode($get_data['uid']));
                }
            }
        }
        $this->template->view('user/change_password', $data);
    }

    /**
     * Manage user domain in the system
     * Jaganath (21-05-2015) - 21-05-2015
     */
    function domain_management()
    {
        $get_data = $this->input->get();
        $post_data = $this->input->post();
        if (valid_array($post_data) == false && isset($get_data['eid']) == true && intval($get_data['eid']) > 0) {
            //EDIT
            $edit_domain_data = $this->module_model->domain_details(intval($get_data['eid']));
            if (valid_array($edit_domain_data) == true) {
                $page_data['form_data'] = $edit_domain_data[0];
                $page_data['form_data']['domain_modules'] = explode(DB_SAFE_SEPARATOR, $page_data['form_data']['domain_modules']);
            } else {
                redirect('security/log_event?event=Domain Invalid CRUD');
            }
        } else if (valid_array($post_data) == true) {
            $this->current_page->set_auto_validator();
            if ($this->form_validation->run()) {
                unset($post_data['FID']);
                $origin = intval($post_data['origin']);
                unset($post_data['origin']);
                $active_modules = $post_data['domain_modules'];
                if ($origin > 0) {
                    //UPDATE
                    $domain_update_data['domain_name'] = $post_data['domain_name'];
                    $domain_update_data['domain_ip'] = $post_data['domain_ip'];
                    $domain_update_data['comment'] = $post_data['comment'];
                    $domain_update_data['status'] = $post_data['status'];
                    $domain_update_data['theme_id'] = $post_data['theme_id'];
                    $this->custom_db->update_record('domain_list', $domain_update_data, array('origin' => $origin, 'domain_key' => $post_data['domain_key']));
                    //delete domain modules
                    $this->custom_db->delete_record('domain_module_map', array('domain_list_fk' => $origin));
                    set_update_message();
                } else if ($origin == 0) {
                    //INSERT
                    $domain_list['domain_name'] = $post_data['domain_name'];
                    $domain_list['domain_ip'] = $post_data['domain_ip'];
                    $domain_list['comment'] = $post_data['comment'];
                    $domain_list['status'] = $post_data['status'];
                    $domain_list['theme_id'] = $post_data['theme_id'];
                    $domain_list['domain_key'] = $post_data['domain_ip'];
                    $domain_list['created_by_id'] = $this->entity_user_id;
                    $domain_list['created_datetime'] = date('Y-m-d H:i:s');
                    $origin = $this->custom_db->insert_record('domain_list', $domain_list);
                    $origin = intval($origin['insert_id']);
                    /**
                     * we need to create domain folder only when we are adding it for the first time :)
                     */
                    $this->create_default_domain($domain_list['domain_key']);
                    set_insert_message();
                }
                //Update domain modules and then redirect
                $this->module_model->create_domain_module_map(intval($origin), $active_modules);
                redirect('user/domain_management');
            }
        }
        $temp_domain_list = $this->user_model->get_domain_details();
        if (valid_array($temp_domain_list)) {
            $page_data['table_data'] = $temp_domain_list;
        } else {
            $page_data['table_data'] = '';
        }
        $this->template->view('user/domain_management', $page_data);
    }
    /**
     * Get Logged in Users
     * Jaganath (25-05-2015) - 25-05-2015
     */
    function get_logged_in_users($offset = 0)
    {
        //changes added the following for 
        cleanup_expired_sessions();
        $get_data = $this->input->get();
        if (
            isset($get_data['filter']) == true && empty($get_data['filter']) == false &&
            isset($get_data['q']) == true && intval($get_data['q']) > 0
        ) {
            $online_users = array();
            $logged_users = array();
            $condition = array(array('U.user_type', '=', intval($get_data['q'])));
            //changes added the following for supervision users
            // $total_records = $this->user_model->get_logged_in_users($condition, true);

            $total_records = 0;
            //upto here
            $temp_user_list = $this->user_model->get_logged_in_users($condition, false, $offset);

            // echo $this->db->last_query();die;
            if (valid_array($temp_user_list)) {

                //changes added these two lines for supervision users
                $total_records = intval(count($temp_user_list));
                $today_date = date('Y-m-d 00:00:00');
                $date_duration = date('Y-m-d H:i:s', (strtotime($today_date) - (15 * 24 * 60 * 60)));
                foreach ($temp_user_list as $k => $v) {
                    //debug($v);
                    //echo intval(strtotime($v['logout_time']));die;
                    //  die;
                    #echo provab_decrypt($v['email']);
                    if (intval(strtotime($v['logout_time'])) > 0) {
                        //changes added if condition for supervision users
                        if ($v['logout_time'] > $date_duration) {
                            //LOGGED USERS
                            $logged_users[] = $v;
                        }
                    } else {
                        //ONLINE USERS



                        //debug( $v);die;
                        $online_users[] = $v;
                    }
                }
            }
            #exit;
            $page_data['date_duration'] = date('d-m-Y', strtotime($date_duration));
            $page_data['online_users'] = $online_users;
            //debug($page_data['online_users']);die;
            $page_data['online_total_users'] = count($online_users);
            $page_data['logged_users'] = $logged_users;
            $page_data['logged_total_users'] = count($logged_users);
            $this->load->library('pagination');
            $config['base_url'] = base_url() . 'index.php/user/get_logged_in_users/';
            //changes changed for supervision users
            // $config['total_rows'] = intval(@$total_records->total);
            $config['total_rows'] = $total_records;
            $config['per_page'] = RECORDS_RANGE_1;
            $this->pagination->initialize($config);
            /** TABLE PAGINATION */

            $this->template->view('user/get_logged_in_users', $page_data);
        } else {
            redirect('security/log_event?event=Invalid Details');
        }
    }
    /**
     * Manage Domain Logo
     * Jaganath (25-05-2015) - 26-05-2015
     */
    function manage_domain()
    {
        //exit('s');
        $post_data = $this->input->post();
        // debug($post_data); die;
        if (valid_array($post_data) == true && isset($post_data['origin']) == true) {
            $GLOBALS['CI']->template->domain_images();
            if (intval($post_data['origin']) == get_domain_auth_id() && get_domain_auth_id() > 0) {
                $domain_origin = get_domain_auth_id();
                //FILE UPLOAD
                if (valid_array($_FILES) == true and $_FILES['domain_logo']['error'] == 0 and $_FILES['domain_logo']['size'] > 0) {
                    $config['upload_path'] = $this->template->domain_image_upload_path();
                    /*if( function_exists( "check_mime_image_type" ) ) {
                        if ( !check_mime_image_type( $_FILES['image']['tmp_name'] ) ) {
                            echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
                        }
                    }*/
                    $temp_file_name = $_FILES['domain_logo']['name'];
                    //debug($temp_file_name);exit;
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] = get_domain_key() . $temp_file_name;
                    $config['max_size'] = MAX_DOMAIN_LOGO_SIZE;
                    $config['max_width']  = MAX_DOMAIN_LOGO_WIDTH;
                    $config['max_height']  = MAX_DOMAIN_LOGO_HEIGHT;
                    $config['remove_spaces']  = false;
                    //UPDATE
                    $temp_record = $this->custom_db->single_table_records('domain_list', 'domain_logo', array('origin' => $domain_origin));
                    //debug($temp_record);exit;
                    $domain_logo = $temp_record['data'][0]['domain_logo'];
                    //debug($domain_logo);exit;
                    //DELETE OLD FILES
                    if (empty($domain_logo) == false) {
                        $temp_domain_logo = $this->template->domain_image_full_path($domain_logo); //GETTING FILE PATH
                        if (file_exists($temp_domain_logo)) {
                            unlink($temp_domain_logo);
                        }
                    }
                    //UPLOAD IMAGE
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('domain_logo')) {
                        echo $this->upload->display_errors();
                    } else {
                        $image_data =  $this->upload->data();
                    }
                    $this->custom_db->update_record('domain_list', array('domain_logo' => @$image_data['file_name'], 'domain_name' => $post_data['domain_name'], 'email' => $post_data['email'], 'address' => $post_data['address'], 'phone' => $post_data['phone'], 'api_country_list_fk' => $post_data['country'], 'api_city_list_fk' => $post_data['city']), array('origin' => $domain_origin));
                } else {
                    $this->custom_db->update_record('domain_list', array('domain_name' => $post_data['domain_name'], 'domain_webiste' => $post_data['domain_website'], 'email' => $post_data['email'], 'address' => $post_data['address'], 'phone' => $post_data['phone'], 'api_country_list_fk' => $post_data['country'], 'api_city_list_fk' => $post_data['city']), array('origin' => $domain_origin));
                }
                refresh();
            }
        }
        $temp_details = $this->custom_db->single_table_records('domain_list', '*', array('origin' => get_domain_auth_id()));
        // debug($temp_details);exit;
        $country_list = $this->custom_db->single_table_records('api_country_list', '*');
        $city_list = $this->custom_db->single_table_records('api_city_list', '*', array('country' => $temp_details['data'][0]['api_country_list_fk']));

        if ($temp_details['status'] == true) {
            $page_data['data']         = $temp_details['data'][0];
            $page_data['country_list'] = $country_list['data'];
            $page_data['city_list']    = $city_list['data'];
        }
        $this->template->view('user/manage_domain', $page_data);
    }
    function add_banner()
    {
        $this->template->view('user/banner_add', $data = array());
    }
    function  add_banner_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();
        //debug($post_data);exit;
        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('module' => $post_data['module'], 'title' => $post_data['banner_title'], 'status' => $post_data['status'], 'banner_order' => $post_data['banner_order'], 'added_by' => 1);

            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {

                    if (!check_mime_image_type($_FILES['banner_image']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_ban_image_upload_path();
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;

                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }

                /*UPDATING IMAGE */
                $insert_data['image'] = $image_data['file_name'];
                //debug($insert_data);exit;
                $this->custom_db->insert_record('banner_images', $insert_data);
            }
            //refresh();
        }
        redirect('user/banner_images');
    }
    function banner_images($offset = 0)
    {
        // Search Params(Country And City)
        // CMS - Image(On Home Page)
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $page_data = array();
            $filter = ['origin' => $id];
            $data_list = $this->custom_db->single_table_records('banner_images', '*', $filter, 0, 100000);

            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/banner_edit', $page_data);
        } else {
            $page_data = array();
            $filter = ['added_by' => 1];
            $data_list = $this->custom_db->single_table_records('banner_images', '*', $filter, 0, 100000);
            //debug($data_list);exit;
            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/banner_images_new', $page_data);
        }
    }
    function edit_banner($id)
    {
        $page_data = array();
        $filter = ['origin' => $id];
        $data_list = $this->custom_db->single_table_records('banner_images', '*', $filter, 0, 100000);
        $page_data['data_list'] = @$data_list['data'];
        $this->template->view('user/banner_edit', $page_data);
    }
    function update_banner_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();
        //debug($_FILES);exit;
        $BID = $post_data['BID'];
        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('module' => $post_data['module'], 'title' => $post_data['banner_title'], 'status' => $post_data['status'], 'banner_order' => $post_data['banner_order']);

            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {
                    if (!check_mime_image_type($_FILES['banner_image']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_ban_image_upload_path();
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;
                //UPDATE
                $temp_record = $this->custom_db->single_table_records('banner_images', 'image', array('added_by' => $domain_origin, 'origin' => $BID));
                //debug($temp_record);exit;
                $banner_image = $temp_record['data'][0]['image'];
                //DELETE OLD FILES
                if (empty($banner_image) == false) {
                    $temp_banner_image = $this->template->domain_ban_image_full_path($banner_image); //GETTING FILE PATH
                    if (file_exists($temp_banner_image)) {
                        unlink($temp_banner_image);
                    }
                }
                //echo $temp_banner_image;exit;
                //debug($config);exit;
                //echo $temp_banner_image;exit;
                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }
                //debug($image_data);exit;
                /*UPDATING IMAGE */
                $this->custom_db->update_record('banner_images', array('image' => $image_data['file_name']), array('origin' => $BID));
            }
            //refresh();
        }
        /*UPDATING OTHER FIELDS*/
        $this->custom_db->update_record('banner_images', $insert_data, array('origin' => $BID));
        $this->banner_images();
    }
    function banner_delete($BID)
    {
        if ($BID) {
            $temp_record = $this->custom_db->single_table_records('banner_images', 'image', array('origin' => $BID));
            //debug($temp_record);exit;
            $banner_image = $temp_record['data'][0]['image'];
            //DELETE OLD FILES
            if (empty($banner_image) == false) {
                $temp_banner_image = $this->template->domain_ban_image_full_path($banner_image); //GETTING FILE PATH
                if (file_exists($temp_banner_image)) {
                    unlink($temp_banner_image);
                }
            }
            $this->custom_db->delete_record('banner_images', array('origin' => $BID));
        }
        redirect('user/banner_images');
    }
    function banner_images_old()
    {
        $post_data = $this->input->post();
        if (valid_array($post_data) == true && isset($post_data['added_by']) == true) {
            $GLOBALS['CI']->template->domain_images();
            if (intval($post_data['added_by']) == get_domain_auth_id() && get_domain_auth_id() > 0) {
                $domain_origin = get_domain_auth_id();
                //FILE UPLOAD
                if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                    if (function_exists("check_mime_image_type")) {
                        if (!check_mime_image_type($_FILES['image']['tmp_name'])) {
                            echo "Please select the image files only (gif|jpg|png|jpeg)";
                            exit;
                        }
                    }
                    $config['upload_path'] = $this->template->domain_image_upload_path();
                    $temp_file_name = $_FILES['banner_image']['name'];
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] = get_domain_key() . $temp_file_name;
                    $config['max_size'] = '1000000';
                    $config['max_width']  = '';
                    $config['max_height']  = '';
                    $config['remove_spaces']  = false;
                    //UPDATE
                    $temp_record = $this->custom_db->single_table_records('banner_images', 'image', array('added_by' => $domain_origin));
                    //debug($temp_record);exit;
                    $banner_image = $temp_record['data'][0]['image'];
                    //DELETE OLD FILES
                    if (empty($banner_image) == false) {
                        $temp_banner_image = $this->template->domain_image_full_path($banner_image); //GETTING FILE PATH
                        if (file_exists($temp_banner_image)) {
                            unlink($temp_banner_image);
                        }
                    }
                    //UPLOAD IMAGE
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('banner_image')) {
                        echo $this->upload->display_errors();
                    } else {
                        $image_data =  $this->upload->data();
                    }
                    $this->custom_db->delete_record('banner_images', array('added_by' => $domain_origin));
                    $this->custom_db->insert_record('banner_images', array('image' => $image_data['file_name'], 'added_by' => $domain_origin));
                }
                refresh();
            }
        }
        $temp_details = $this->custom_db->single_table_records('banner_images', 'image', array('added_by' => get_domain_auth_id()));
        //debug($temp_details);exit;
        if ($temp_details['status'] == true) {
            $page_data['banner_image'] = $temp_details['data'][0]['image'];
        } else {
            $page_data['banner_image'] = '';
        }
        //debug($page_data);exit;
        $this->template->view('user/banner_images', $page_data);
    }


    function add_affiliate_partners()
    {
        $this->template->view('user/affiliate_partners_add', $data = array());
    }
    function  add_affiliate_partners_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();

        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('status' => $post_data['status'], 'banner_order' => $post_data['banner_order'], 'added_by' => 1);

            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {

                    if (!check_mime_image_type($_FILES['banner_image']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_ban_image_upload_path();
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;

                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }

                /*UPDATING IMAGE */
                $insert_data['image'] = $image_data['file_name'];
                //debug($insert_data);exit;
                $this->custom_db->insert_record('affiliate_partners_images', $insert_data);
            }
            //refresh();
        }
        redirect('user/affiliate_partners_images');
    }
    function affiliate_partners_images($offset = 0)
    {
        // Search Params(Country And City)
        // CMS - Image(On Home Page)
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $page_data = array();
            $filter = ['origin' => $id];
            $data_list = $this->custom_db->single_table_records('affiliate_partners_images', '*', $filter, 0, 100000);

            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/affiliate_partners_edit', $page_data);
        } else {
            $page_data = array();
            $filter = ['added_by' => 1];
            $data_list = $this->custom_db->single_table_records('affiliate_partners_images', '*', $filter, 0, 100000);
            //debug($data_list);exit;
            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/affiliate_partners_images_new', $page_data);
        }
    }
    function edit_affiliate_partners($id)
    {
        $page_data = array();
        $filter = ['origin' => $id];
        $data_list = $this->custom_db->single_table_records('affiliate_partners_images', '*', $filter, 0, 100000);
        $page_data['data_list'] = @$data_list['data'];
        $this->template->view('user/affiliate_partners_edit', $page_data);
    }
    function update_affiliate_partners_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();
        //debug($_FILES);exit;
        $BID = $post_data['BID'];
        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('status' => $post_data['status'], 'banner_order' => $post_data['banner_order']);

            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {
                    if (!check_mime_image_type($_FILES['banner_image']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_ban_image_upload_path();
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;
                //UPDATE
                $temp_record = $this->custom_db->single_table_records('affiliate_partners_images', 'image', array('added_by' => $domain_origin, 'origin' => $BID));
                //debug($temp_record);exit;
                $banner_image = $temp_record['data'][0]['image'];
                //DELETE OLD FILES
                if (empty($banner_image) == false) {
                    $temp_banner_image = $this->template->domain_ban_image_full_path($banner_image); //GETTING FILE PATH
                    if (file_exists($temp_banner_image)) {
                        unlink($temp_banner_image);
                    }
                }
                //echo $temp_banner_image;exit;
                //debug($config);exit;
                //echo $temp_banner_image;exit;
                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }
                //debug($image_data);exit;
                /*UPDATING IMAGE */
                $this->custom_db->update_record('affiliate_partners_images', array('image' => $image_data['file_name']), array('origin' => $BID));
            }
            //refresh();
        }
        /*UPDATING OTHER FIELDS*/
        $this->custom_db->update_record('affiliate_partners_images', $insert_data, array('origin' => $BID));
        $this->banner_images();
        redirect('user/affiliate_partners_images');
    }
    function affiliate_partners_delete($BID)
    {
        if ($BID) {
            $temp_record = $this->custom_db->single_table_records('affiliate_partners_images', 'image', array('origin' => $BID));
            //debug($temp_record);exit;
            $banner_image = $temp_record['data'][0]['image'];
            //DELETE OLD FILES
            if (empty($banner_image) == false) {
                $temp_banner_image = $this->template->domain_ban_image_full_path($banner_image); //GETTING FILE PATH
                if (file_exists($temp_banner_image)) {
                    unlink($temp_banner_image);
                }
            }
            $this->custom_db->delete_record('affiliate_partners_images', array('origin' => $BID));
        }
        redirect('user/affiliate_partners_images');
    }

    function add_trusted_by_experts()
    {
        $this->template->view('user/trusted_by_experts_add', $data = array());
    }
    function  add_trusted_by_experts_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();

        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('status' => $post_data['status'], 'banner_order' => $post_data['banner_order'], 'added_by' => 1);

            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {

                    if (!check_mime_image_type($_FILES['banner_image']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_ban_image_upload_path();
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;

                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }

                /*UPDATING IMAGE */
                $insert_data['image'] = $image_data['file_name'];
                //debug($insert_data);exit;
                $this->custom_db->insert_record('trusted_by_experts_images', $insert_data);
            }
            //refresh();
        }
        redirect('user/trusted_by_experts_images');
    }
    function trusted_by_experts_images($offset = 0)
    {
        // Search Params(Country And City)
        // CMS - Image(On Home Page)
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $page_data = array();
            $filter = ['origin' => $id];
            $data_list = $this->custom_db->single_table_records('trusted_by_experts_images', '*', $filter, 0, 100000);

            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/trusted_by_experts_edit', $page_data);
        } else {
            $page_data = array();
            $filter = ['added_by' => 1];
            $data_list = $this->custom_db->single_table_records('trusted_by_experts_images', '*', $filter, 0, 100000);
            //debug($data_list);exit;
            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/trusted_by_experts_images_new', $page_data);
        }
    }
    function edit_trusted_by_experts($id)
    {
        $page_data = array();
        $filter = ['origin' => $id];
        $data_list = $this->custom_db->single_table_records('trusted_by_experts_images', '*', $filter, 0, 100000);
        $page_data['data_list'] = @$data_list['data'];
        $this->template->view('user/trusted_by_experts_edit', $page_data);
    }
    function update_trusted_by_experts_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();
        //debug($_FILES);exit;
        $BID = $post_data['BID'];
        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('status' => $post_data['status'], 'banner_order' => $post_data['banner_order']);

            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {
                    if (!check_mime_image_type($_FILES['banner_image']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_ban_image_upload_path();
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;
                //UPDATE
                $temp_record = $this->custom_db->single_table_records('trusted_by_experts_images', 'image', array('added_by' => $domain_origin, 'origin' => $BID));
                //debug($temp_record);exit;
                $banner_image = $temp_record['data'][0]['image'];
                //DELETE OLD FILES
                if (empty($banner_image) == false) {
                    $temp_banner_image = $this->template->domain_ban_image_full_path($banner_image); //GETTING FILE PATH
                    if (file_exists($temp_banner_image)) {
                        unlink($temp_banner_image);
                    }
                }
                //echo $temp_banner_image;exit;
                //debug($config);exit;
                //echo $temp_banner_image;exit;
                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }
                //debug($image_data);exit;
                /*UPDATING IMAGE */
                $this->custom_db->update_record('trusted_by_experts_images', array('image' => $image_data['file_name']), array('origin' => $BID));
            }
            //refresh();
        }
        /*UPDATING OTHER FIELDS*/
        $this->custom_db->update_record('trusted_by_experts_images', $insert_data, array('origin' => $BID));
        $this->banner_images();
        redirect('user/trusted_by_experts_images');
    }
    function trusted_by_experts_delete($BID)
    {
        if ($BID) {
            $temp_record = $this->custom_db->single_table_records('trusted_by_experts_images', 'image', array('origin' => $BID));
            //debug($temp_record);exit;
            $banner_image = $temp_record['data'][0]['image'];
            //DELETE OLD FILES
            if (empty($banner_image) == false) {
                $temp_banner_image = $this->template->domain_ban_image_full_path($banner_image); //GETTING FILE PATH
                if (file_exists($temp_banner_image)) {
                    unlink($temp_banner_image);
                }
            }
            $this->custom_db->delete_record('trusted_by_experts_images', array('origin' => $BID));
        }
        redirect('user/trusted_by_experts_images');
    }

    function add_who_we_are()
    {
        $this->template->view('user/who_we_are_add', $data = array());
    }
    function  add_who_we_are_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();

        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('title' => $post_data['banner_title'], 'status' => $post_data['status'], 'banner_order' => $post_data['banner_order'], 'added_by' => 1);

            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {

                    if (!check_mime_image_type($_FILES['banner_image']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_ban_image_upload_path();
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;

                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }

                /*UPDATING IMAGE */
                $insert_data['image'] = $image_data['file_name'];
                //debug($insert_data);exit;
                $this->custom_db->insert_record('who_we_are_images', $insert_data);
            }
            //refresh();
        }
        redirect('user/who_we_are_images');
    }
    function who_we_are_images($offset = 0)
    {
        // Search Params(Country And City)
        // CMS - Image(On Home Page)
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $page_data = array();
            $filter = ['origin' => $id];
            $data_list = $this->custom_db->single_table_records('who_we_are_images', '*', $filter, 0, 100000);

            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/who_we_are_edit', $page_data);
        } else {
            $page_data = array();
            $filter = ['added_by' => 1];
            $data_list = $this->custom_db->single_table_records('who_we_are_images', '*', $filter, 0, 100000);
            //debug($data_list);exit;
            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/who_we_are_images_new', $page_data);
        }
    }
    function edit_who_we_are($id)
    {
        $page_data = array();
        $filter = ['origin' => $id];
        $data_list = $this->custom_db->single_table_records('who_we_are_images', '*', $filter, 0, 100000);
        $page_data['data_list'] = @$data_list['data'];
        $this->template->view('user/who_we_are_edit', $page_data);
    }
    function update_who_we_are_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();
        //debug($_FILES);exit;
        $BID = $post_data['BID'];
        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('title' => $post_data['banner_title'], 'status' => $post_data['status'], 'banner_order' => $post_data['banner_order']);

            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {
                    if (!check_mime_image_type($_FILES['banner_image']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_ban_image_upload_path();
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;
                //UPDATE
                $temp_record = $this->custom_db->single_table_records('who_we_are_images', 'image', array('added_by' => $domain_origin, 'origin' => $BID));
                //debug($temp_record);exit;
                $banner_image = $temp_record['data'][0]['image'];
                //DELETE OLD FILES
                if (empty($banner_image) == false) {
                    $temp_banner_image = $this->template->domain_ban_image_full_path($banner_image); //GETTING FILE PATH
                    if (file_exists($temp_banner_image)) {
                        unlink($temp_banner_image);
                    }
                }
                //echo $temp_banner_image;exit;
                //debug($config);exit;
                //echo $temp_banner_image;exit;
                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }
                //debug($image_data);exit;
                /*UPDATING IMAGE */
                $this->custom_db->update_record('who_we_are_images', array('image' => $image_data['file_name']), array('origin' => $BID));
            }
            //refresh();
        }
        /*UPDATING OTHER FIELDS*/
        $this->custom_db->update_record('who_we_are_images', $insert_data, array('origin' => $BID));
        $this->banner_images();
        redirect('user/who_we_are_images');
    }
    function who_we_are_delete($BID)
    {
        if ($BID) {
            $temp_record = $this->custom_db->single_table_records('who_we_are_images', 'image', array('origin' => $BID));
            //debug($temp_record);exit;
            $banner_image = $temp_record['data'][0]['image'];
            //DELETE OLD FILES
            if (empty($banner_image) == false) {
                $temp_banner_image = $this->template->domain_ban_image_full_path($banner_image); //GETTING FILE PATH
                if (file_exists($temp_banner_image)) {
                    unlink($temp_banner_image);
                }
            }
            $this->custom_db->delete_record('who_we_are_images', array('origin' => $BID));
        }
        redirect('user/who_we_are_images');
    }

    function add_testimonial()
    {
        $this->template->view('user/testimonial_add', $data = array());
    }
    function  add_testimonial_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();

        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('title' => $post_data['banner_title'], 'description' => $post_data['description'], 'status' => $post_data['status'], 'banner_order' => $post_data['banner_order'], 'added_by' => 1);

            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {

                    if (!check_mime_image_type($_FILES['banner_image']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_ban_image_upload_path();
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;

                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }

                /*UPDATING IMAGE */
                $insert_data['image'] = $image_data['file_name'];
                //debug($insert_data);exit;
                $this->custom_db->insert_record('testimonial_images', $insert_data);
            }
            //refresh();
        }
        redirect('user/testimonial_images');
    }
    function testimonial_images($offset = 0)
    {
        // Search Params(Country And City)
        // CMS - Image(On Home Page)
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $page_data = array();
            $filter = ['origin' => $id];
            $data_list = $this->custom_db->single_table_records('testimonial_images', '*', $filter, 0, 100000);

            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/testimonial_edit', $page_data);
        } else {
            $page_data = array();
            $filter = ['added_by' => 1];
            $data_list = $this->custom_db->single_table_records('testimonial_images', '*', $filter, 0, 100000);
            //debug($data_list);exit;
            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/testimonial_images_new', $page_data);
        }
    }
    function edit_testimonial($id)
    {
        $page_data = array();
        $filter = ['origin' => $id];
        $data_list = $this->custom_db->single_table_records('testimonial_images', '*', $filter, 0, 100000);
        $page_data['data_list'] = @$data_list['data'];
        $this->template->view('user/testimonial_edit', $page_data);
    }
    function update_testimonial_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();
        //debug($_FILES);exit;
        $BID = $post_data['BID'];
        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('title' => $post_data['banner_title'], 'description' => $post_data['description'], 'status' => $post_data['status'], 'banner_order' => $post_data['banner_order']);

            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {
                    if (!check_mime_image_type($_FILES['banner_image']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_ban_image_upload_path();
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;
                //UPDATE
                $temp_record = $this->custom_db->single_table_records('testimonial_images', 'image', array('added_by' => $domain_origin, 'origin' => $BID));
                //debug($temp_record);exit;
                $banner_image = $temp_record['data'][0]['image'];
                //DELETE OLD FILES
                if (empty($banner_image) == false) {
                    $temp_banner_image = $this->template->domain_ban_image_full_path($banner_image); //GETTING FILE PATH
                    if (file_exists($temp_banner_image)) {
                        unlink($temp_banner_image);
                    }
                }
                //echo $temp_banner_image;exit;
                //debug($config);exit;
                //echo $temp_banner_image;exit;
                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }
                //debug($image_data);exit;
                /*UPDATING IMAGE */
                $this->custom_db->update_record('testimonial_images', array('image' => $image_data['file_name']), array('origin' => $BID));
            }
            //refresh();
        }
        /*UPDATING OTHER FIELDS*/
        $this->custom_db->update_record('testimonial_images', $insert_data, array('origin' => $BID));
        $this->banner_images();
        redirect('user/testimonial_images');
    }
    function testimonial_delete($BID)
    {
        if ($BID) {
            $temp_record = $this->custom_db->single_table_records('testimonial_images', 'image', array('origin' => $BID));
            //debug($temp_record);exit;
            $banner_image = $temp_record['data'][0]['image'];
            //DELETE OLD FILES
            if (empty($banner_image) == false) {
                $temp_banner_image = $this->template->domain_ban_image_full_path($banner_image); //GETTING FILE PATH
                if (file_exists($temp_banner_image)) {
                    unlink($temp_banner_image);
                }
            }
            $this->custom_db->delete_record('testimonial_images', array('origin' => $BID));
        }
        redirect('user/testimonial_images');
    }

    function add_gallery()
    {
        $this->template->view('user/gallery_add', $data = array());
    }
    function  add_gallery_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();

        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('title' => $post_data['banner_title'], 'status' => $post_data['status'], 'banner_order' => $post_data['banner_order'], 'added_by' => 1);

            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {

                    if (!check_mime_image_type($_FILES['banner_image']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_ban_image_upload_path();
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;

                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }

                /*UPDATING IMAGE */
                $insert_data['image'] = $image_data['file_name'];
                //debug($insert_data);exit;
                $this->custom_db->insert_record('gallery_images', $insert_data);
            }
            //refresh();
        }
        redirect('user/gallery_images');
    }
    function gallery_images($offset = 0)
    {
        // Search Params(Country And City)
        // CMS - Image(On Home Page)
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $page_data = array();
            $filter = ['origin' => $id];
            $data_list = $this->custom_db->single_table_records('gallery_images', '*', $filter, 0, 100000);

            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/gallery_edit', $page_data);
        } else {
            $page_data = array();
            $filter = ['added_by' => 1];
            $data_list = $this->custom_db->single_table_records('gallery_images', '*', $filter, 0, 100000);
            //debug($data_list);exit;
            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/gallery_images_new', $page_data);
        }
    }
    function edit_gallery($id)
    {
        $page_data = array();
        $filter = ['origin' => $id];
        $data_list = $this->custom_db->single_table_records('gallery_images', '*', $filter, 0, 100000);
        $page_data['data_list'] = @$data_list['data'];
        $this->template->view('user/gallery_edit', $page_data);
    }
    function update_gallery_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();
        //debug($_FILES);exit;
        $BID = $post_data['BID'];
        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('title' => $post_data['banner_title'], 'status' => $post_data['status'], 'banner_order' => $post_data['banner_order']);

            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {
                    if (!check_mime_image_type($_FILES['banner_image']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_ban_image_upload_path();
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;
                //UPDATE
                $temp_record = $this->custom_db->single_table_records('gallery_images', 'image', array('added_by' => $domain_origin, 'origin' => $BID));
                //debug($temp_record);exit;
                $banner_image = $temp_record['data'][0]['image'];
                //DELETE OLD FILES
                if (empty($banner_image) == false) {
                    $temp_banner_image = $this->template->domain_ban_image_full_path($banner_image); //GETTING FILE PATH
                    if (file_exists($temp_banner_image)) {
                        unlink($temp_banner_image);
                    }
                }
                //echo $temp_banner_image;exit;
                //debug($config);exit;
                //echo $temp_banner_image;exit;
                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }
                //debug($image_data);exit;
                /*UPDATING IMAGE */
                $this->custom_db->update_record('gallery_images', array('image' => $image_data['file_name']), array('origin' => $BID));
            }
            //refresh();
        }
        /*UPDATING OTHER FIELDS*/
        $this->custom_db->update_record('gallery_images', $insert_data, array('origin' => $BID));
        $this->banner_images();
        redirect('user/gallery_images');
    }
    function gallery_delete($BID)
    {
        if ($BID) {
            $temp_record = $this->custom_db->single_table_records('gallery_images', 'image', array('origin' => $BID));
            //debug($temp_record);exit;
            $banner_image = $temp_record['data'][0]['image'];
            //DELETE OLD FILES
            if (empty($banner_image) == false) {
                $temp_banner_image = $this->template->domain_ban_image_full_path($banner_image); //GETTING FILE PATH
                if (file_exists($temp_banner_image)) {
                    unlink($temp_banner_image);
                }
            }
            $this->custom_db->delete_record('gallery_images', array('origin' => $BID));
        }
        redirect('user/gallery_images');
    }

    function add_gallery_url()
    {
        $this->template->view('user/gallery_url_add', $data = array());
    }
    function  add_gallery_url_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();

        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('status' => $post_data['status'], 'banner_order' => $post_data['banner_order'], 'added_by' => 1);
            $upload_path = realpath('../extras') . '/custom/' . CURRENT_DOMAIN_KEY . '/images/';
            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                // if( function_exists( "check_mime_image_type" ) ) {

                //     if ( !check_mime_image_type( $_FILES['banner_image']['tmp_name'] ) ) {
                //      echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
                //     }
                // }
                $domain_origin = 1;
                $config['upload_path'] = $upload_path;
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = '*';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;

                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }



                $insert_data['image'] = $image_data['file_name'];
                //debug($insert_data);exit;
                //$this->custom_db->insert_record('gallery_url',$insert_data);
                //debug($insert_data);exit;
            }
            $insert_data['url'] = $post_data['url'];
            //debug($insert_data);exit;
            $this->custom_db->insert_record('gallery_url', $insert_data);
            //refresh();
        }
        redirect('user/gallery_url_images');
    }
    function gallery_url_images($offset = 0)
    {
        // Search Params(Country And City)
        // CMS - Image(On Home Page)
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $page_data = array();
            $filter = ['origin' => $id];
            $data_list = $this->custom_db->single_table_records('gallery_url', '*', $filter, 0, 100000);

            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/gallery_url_edit', $page_data);
        } else {
            $page_data = array();
            $filter = ['added_by' => 1];
            $data_list = $this->custom_db->single_table_records('gallery_url', '*', $filter, 0, 100000);
            //debug($data_list);exit;
            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/gallery_url_images_new', $page_data);
        }
    }
    function edit_gallery_url($id)
    {
        $page_data = array();
        $filter = ['origin' => $id];
        $data_list = $this->custom_db->single_table_records('gallery_url', '*', $filter, 0, 100000);
        $page_data['data_list'] = @$data_list['data'];
        $this->template->view('user/gallery_url_edit', $page_data);
    }
    function update_gallery_url_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();
        //debug($_FILES);exit;
        $BID = $post_data['BID'];
        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('status' => $post_data['status'], 'banner_order' => $post_data['banner_order']);
            $upload_path = realpath('../extras') . '/custom/' . CURRENT_DOMAIN_KEY . '/images/';
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                // if( function_exists( "check_mime_image_type" ) ) {
                //     if ( !check_mime_image_type( $_FILES['banner_image']['tmp_name'] ) ) {
                //      echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
                //     }
                // }
                $domain_origin = 1;
                $config['upload_path'] = $upload_path;
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = '*';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;
                //UPDATE
                $temp_record = $this->custom_db->single_table_records('gallery_url', 'image', array('added_by' => $domain_origin, 'origin' => $BID));
                //debug($temp_record);exit;
                $banner_image = $temp_record['data'][0]['image'];
                //DELETE OLD FILES
                if (empty($banner_image) == false) {
                    $temp_banner_image = $this->template->domain_ban_image_full_path($banner_image); //GETTING FILE PATH
                    if (file_exists($temp_banner_image)) {
                        unlink($temp_banner_image);
                    }
                }
                //echo $temp_banner_image;exit;
                //debug($config);exit;
                //echo $temp_banner_image;exit;
                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }
                //debug($image_data);exit;
                /*UPDATING IMAGE */
                $this->custom_db->update_record('gallery_url', array('image' => $image_data['file_name']), array('origin' => $BID));
            }

            //debug($image_data);exit;
            /*UPDATING IMAGE */
            $this->custom_db->update_record('gallery_url', array('url' => $post_data['url']), array('origin' => $BID));
        }
        //refresh();
        /*UPDATING OTHER FIELDS*/
        $this->custom_db->update_record('gallery_url', $insert_data, array('origin' => $BID));
        //$this->banner_images();
        redirect('user/gallery_url_images');
    }
    function gallery_url_delete($BID)
    {
        if ($BID) {
            $temp_record = $this->custom_db->single_table_records('gallery_url', 'url', array('origin' => $BID));
            //debug($temp_record);exit;

            $this->custom_db->delete_record('gallery_url', array('origin' => $BID));
        }
        redirect('user/gallery_url_images');
    }

    function add_blog()
    {
        $this->template->view('user/blog_add', $data = array());
    }
    function  add_blog_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();

        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('title' => $post_data['banner_title'], 'description' => $post_data['description'], 'status' => $post_data['status'], 'banner_order' => $post_data['banner_order'], 'added_by' => 1);

            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {

                    if (!check_mime_image_type($_FILES['banner_image']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_ban_image_upload_path();
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;

                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }

                /*UPDATING IMAGE */
                $insert_data['image'] = $image_data['file_name'];
                //debug($insert_data);exit;
                $this->custom_db->insert_record('blog', $insert_data);
            }
            //refresh();
        }
        redirect('user/blog_images');
    }
    function blog_images($offset = 0)
    {
        // Search Params(Country And City)
        // CMS - Image(On Home Page)
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $page_data = array();
            $filter = ['origin' => $id];
            $data_list = $this->custom_db->single_table_records('blog', '*', $filter, 0, 100000);

            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/blog_edit', $page_data);
        } else {
            $page_data = array();
            $filter = ['added_by' => 1];
            $data_list = $this->custom_db->single_table_records('blog', '*', $filter, 0, 100000);
            //debug($data_list);exit;
            $page_data['data_list'] = @$data_list['data'];
            $this->template->view('user/blog_images_new', $page_data);
        }
    }
    function edit_blog($id)
    {
        $page_data = array();
        $filter = ['origin' => $id];
        $data_list = $this->custom_db->single_table_records('blog', '*', $filter, 0, 100000);
        $page_data['data_list'] = @$data_list['data'];
        $this->template->view('user/blog_edit', $page_data);
    }
    function update_blog_action()
    {
        $insert_data = [];
        $post_data = $this->input->post();
        //debug($_FILES);exit;
        $BID = $post_data['BID'];
        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('title' => $post_data['banner_title'], 'description' => $post_data['description'], 'status' => $post_data['status'], 'banner_order' => $post_data['banner_order']);

            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['banner_image']['error'] == 0 and $_FILES['banner_image']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {
                    if (!check_mime_image_type($_FILES['banner_image']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_ban_image_upload_path();
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;
                //UPDATE
                $temp_record = $this->custom_db->single_table_records('blog', 'image', array('added_by' => $domain_origin, 'origin' => $BID));
                //debug($temp_record);exit;
                $banner_image = $temp_record['data'][0]['image'];
                //DELETE OLD FILES
                if (empty($banner_image) == false) {
                    $temp_banner_image = $this->template->domain_ban_image_full_path($banner_image); //GETTING FILE PATH
                    if (file_exists($temp_banner_image)) {
                        unlink($temp_banner_image);
                    }
                }
                //echo $temp_banner_image;exit;
                //debug($config);exit;
                //echo $temp_banner_image;exit;
                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('banner_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }
                //debug($image_data);exit;
                /*UPDATING IMAGE */
                $this->custom_db->update_record('blog', array('image' => $image_data['file_name']), array('origin' => $BID));
            }
            //refresh();
        }
        /*UPDATING OTHER FIELDS*/
        $this->custom_db->update_record('blog', $insert_data, array('origin' => $BID));
        $this->banner_images();
        redirect('user/blog_images');
    }
    function blog_delete($BID)
    {
        if ($BID) {
            $temp_record = $this->custom_db->single_table_records('blog', 'image', array('origin' => $BID));
            //debug($temp_record);exit;
            $banner_image = $temp_record['data'][0]['image'];
            //DELETE OLD FILES
            if (empty($banner_image) == false) {
                $temp_banner_image = $this->template->domain_ban_image_full_path($banner_image); //GETTING FILE PATH
                if (file_exists($temp_banner_image)) {
                    unlink($temp_banner_image);
                }
            }
            $this->custom_db->delete_record('blog', array('origin' => $BID));
        }
        redirect('user/blog_images');
    }

    /**
     * Jaganath
     * Reset the Password and send the new Password to Agent Email
     * @param $user_id
     * @param $uuid
     */
    function send_agent_new_password($user_id, $uuid)
    {
        $cond['user_id'] = intval($user_id);
        $cond['uuid'] = $uuid;
        $data['status'] = ACTIVE;
        $user_record = $this->user_model->update_user_data($data, $cond);
        if ($user_record['status'] == SUCCESS_STATUS) {
            //Sms config & Checkpoint
            /* if(active_sms_checkpoint('forget_password'))
            {
            $msg = "Dear ".$user_record['data'][0]['first_name']." Your Password details has been sent to your email id";
            //print($msg); exit;
            $msg = urlencode($msg);
            $this->provab_sms->send_msg($phone,$msg);
            } */
            //sms will be sent

            $new_password = time();
            $user_record['data']['password'] = $new_password;
            //send email
            $user_record['data']['email'] = provab_decrypt($user_record['data']['email']);
            $email = $user_record['data']['email'];
            $mail_template = $this->template->isolated_view('user/forgot_password_template', $user_record['data']);
            $update_user_record['password'] = provab_encrypt(md5(trim($new_password)));

            $this->custom_db->update_record('user', $update_user_record, array('user_id' => intval($user_record['data']['user_id'])));
            $this->load->library('provab_mailer');
            $this->provab_mailer->send_mail($email, 'Password Reset', $mail_template);
        }
    }

    function show_investor_pdf()
    {
        $get_id = $this->input->post();
        $page_data['data'] = $get_id;
        //debug($page_data['data']);exit;
        $this->load->library('provab_pdf');
        $create_pdf = new Provab_Pdf();
        $get_view = $this->template->isolated_view('voucher/investor_pdf', $page_data);
        // debug($get_view);exit;
        $create_pdf->create_pdf_investor($get_view, 'show');
    }
    function edit_investor()
    {
        $post_data = $this->input->get();
        $filter = ['id' => $post_data['bid']];
        $data_list = $this->custom_db->single_table_records('plan_retirement', '*', $filter, 0, 100000);
        $page_data['data_list'] = @$data_list['data'];
        $temp_record = $this->custom_db->single_table_records('domain_list', '*');
        $data['active_data'] = $temp_record['data'][0];

        $temp_record = $this->custom_db->single_table_records('api_country_list', '*');
        $page_data['phone_code'] = $temp_record['data'];
        $city_record = $this->custom_db->single_table_records('api_city_list', 'destination', array('country' => $data['active_data']['api_country_list_fk']));
        $page_data['city_list'] = $city_record['data'][0];
        $page_data['country_code_list'] = $this->db_cache_api->get_country_code_list();
        $country_code = $this->db_cache_api->get_country_code_list_profile();
        // debug($country_code);exit;
        $phone_code_array = array();
        foreach ($country_code['data'] as $c_key => $c_value) {
            $phone_code_array[$c_value['origin']] = $c_value['name'] . ' ' . $c_value['country_code'];
        }
        // debug($phone_code_array);exit;
        $page_data['phone_code_array'] = $phone_code_array;
        $page_data['country_list'] = $this->db_cache_api->get_country_list();
        $this->template->view('cms/investor_edit', $page_data);
    }
    function update_investor_action()
    {
        $post_data = $this->input->post();
        //debug($post_data);exit;
        //debug($_FILES);exit;
        $BID = $post_data['BID'];
        if (valid_array($post_data) == true) {

            //POST DATA formating to update
            $insert_data = array('fullname' => $post_data['fullname'], 'email' => $post_data['email'], 'phone' => $post_data['phone'], 'country' => $post_data['country'], 'state' => $post_data['state'], 'city' => $post_data['city'], 'zipcode' => $post_data['zipcode'], 'address' => $post_data['address'], 'passno' => $post_data['passno'], 'message' => $post_data['message'], 'packselect' => $post_data['packselect'], 'accountno' => $post_data['accountno'], 'bankname' => $post_data['bankname'], 'sortcode' => $post_data['sortcode'], 'iban' => $post_data['iban'], 'package' => $post_data['package'], 'payment_status' => $post_data['payment_status']);

            //FILE UPLOAD
            if (valid_array($_FILES) == true and $_FILES['passid']['error'] == 0 and $_FILES['passid']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {
                    if (!check_mime_image_type($_FILES['passid']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_image_upload_path();
                $temp_file_name = $_FILES['banner_image']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;
                //UPDATE
                $temp_record = $this->custom_db->single_table_records('plan_retirement', 'passid', array('id' => $BID));
                //debug($temp_record);exit;
                $banner_image = $temp_record['data'][0]['passid'];
                //DELETE OLD FILES
                if (empty($banner_image) == false) {
                    $temp_banner_image = $this->template->domain_image_upload_path($banner_image); //GETTING FILE PATH
                    if (file_exists($temp_banner_image)) {
                        unlink($temp_banner_image);
                    }
                }
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('passid')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }
                //debug($image_data);exit;
                /*UPDATING IMAGE */
                $this->custom_db->update_record('plan_retirement', array('passid' => $image_data['file_name']), array('id' => $BID));
            }
            if (valid_array($_FILES) == true and $_FILES['passcopy']['error'] == 0 and $_FILES['passcopy']['size'] > 0) {
                if (function_exists("check_mime_image_type")) {
                    if (!check_mime_image_type($_FILES['passcopy']['tmp_name'])) {
                        echo "Please select the image files only (gif|jpg|png|jpeg)";
                        exit;
                    }
                }
                $domain_origin = 1;
                $config['upload_path'] = $this->template->domain_image_upload_path();
                $temp_file_name = $_FILES['passcopy']['name'];
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = get_domain_key() . $temp_file_name;
                $config['max_size'] = '1000000';
                $config['max_width']  = '';
                $config['max_height']  = '';
                $config['remove_spaces']  = false;
                //UPDATE
                $temp_record = $this->custom_db->single_table_records('plan_retirement', 'passcopy', array('id' => $BID));
                //debug($temp_record);exit;
                $banner_image = $temp_record['data'][0]['passcopy'];
                //DELETE OLD FILES
                if (empty($banner_image) == false) {
                    $temp_banner_image = $this->template->domain_image_upload_path($banner_image); //GETTING FILE PATH
                    if (file_exists($temp_banner_image)) {
                        unlink($temp_banner_image);
                    }
                }
                //echo $temp_banner_image;exit;
                //debug($config);exit;
                //echo $temp_banner_image;exit;
                //UPLOAD IMAGE
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('passcopy')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data =  $this->upload->data();
                }
                //debug($image_data);exit;
                /*UPDATING IMAGE */
                $this->custom_db->update_record('plan_retirement', array('passcopy' => $image_data['file_name']), array('id' => $BID));
            }
            /*UPDATING OTHER FIELDS*/
            $this->custom_db->update_record('plan_retirement', $insert_data, array('id' => $BID));
            $this->banner_images();
            $this->session->set_flashdata(array('message' => 'UL0013', 'type' => SUCCESS_MESSAGE));
            redirect('cms/plan_retirement');
        }
    }
    function delete_investor($BID)
    {
        $this->custom_db->delete_record('plan_retirement', array('id' => $BID));
        redirect('cms/plan_retirement');
    }

    /**
     * Jaganath
     * Delete Agent: Make it invisible
     * @param $user_id
     * @param $uuid
     */
    function delete_agent($user_id, $uuid)
    {
        $cond['user_id'] = intval($user_id);
        $cond['uuid'] = $uuid;
        $data['status'] = ACTIVE;
        $user_record = $this->user_model->update_user_data($data, $cond);
        if ($user_record['status'] == SUCCESS_STATUS) {
            //Sms config & Checkpoint
            /* if(active_sms_checkpoint('forget_password'))
            {
            $msg = "Dear ".$user_record['data'][0]['first_name']." Your Password details has been sent to your email id";
            //print($msg); exit;
            $msg = urlencode($msg);
            $this->provab_sms->send_msg($phone,$msg);
            } */
            //sms will be sent
            $update_user_record = array();
            $update_user_record['status'] = (-1); //Delete Agent
            $this->custom_db->update_record('user', $update_user_record, array('user_id' => intval($user_record['data']['user_id'])));
            $email = provab_decrypt($user_record['data']['email']);
            //send email
            $mail_template = $this->template->isolated_view('user/account_deactivation_template', $user_record['data']);
            $this->load->library('provab_mailer');
            $this->provab_mailer->send_mail($email, 'Account Deactivated', $mail_template);
        }
    }

    function get_city_list()
    {
        $country_id = $this->input->post('country_id');
        $city_list = $this->custom_db->single_table_records('api_city_list', '*', array('country' => $country_id), 0, 100000000, array('destination' => 'asc'));
        $options = '';
        $city_list = $city_list['data'];
        foreach ($city_list as $city) {
            $options .= "<option value=" . $city['origin'] . ">" . $city['destination'] . "</option>";
        }
        print_r($options);
    }
    /**
     * Call back function to check useremail availability
     * @param string $name
     */
    public function useremail_b2c_check($email)
    {
        $condition['email'] = provab_encrypt($email);
        $condition['user_type'] = B2C_USER;
        $condition['domain_list_fk'] = intval(get_domain_auth_id());
        $data = $this->custom_db->single_table_records('user', 'user_id', $condition);
        if ($data['status'] == SUCCESS_STATUS and valid_array($data['data']) == true) {
            $this->form_validation->set_message('username_check', $email . ' Already Registered!!!');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    /**
     * Call back function to check useremail availability
     * @param string $name
     */
    public function useremail_b2b_check($email)
    {

        $condition['email'] = provab_encrypt($email);
        $condition['user_type'] = B2B_USER;
        $condition['domain_list_fk'] = intval(get_domain_auth_id());
        $data = $this->custom_db->single_table_records('user', 'user_id', $condition);

        if ($data['status'] == SUCCESS_STATUS and valid_array($data['data']) == true) {
            $this->form_validation->set_message('username_check', $email . ' Already Registered!!!');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Call back function to check useremail availability
     * @param string $name
     */
    public function useremail_subadmin_check($email)
    {
        $condition['email'] = provab_encrypt($email);
        $condition['user_type'] = SUB_ADMIN;
        $condition['domain_list_fk'] = intval(get_domain_auth_id());
        $data = $this->custom_db->single_table_records('user', 'user_id', $condition);
        if ($data['status'] == SUCCESS_STATUS and valid_array($data['data']) == true) {
            $this->form_validation->set_message('username_check', $email . ' Already Registered!!!');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Validate the password
     *
     * @param string $password
     *
     * @return bool
     */
    public function valid_password($password)
    {
        $password = trim($password);
        $regex_lowercase = '/[a-z]/';
        $regex_uppercase = '/[A-Z]/';
        $regex_number = '/[0-9]/';
        $regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
        if (empty($password)) {
            $this->form_validation->set_message('valid_password', 'The Password field is required.');
            return FALSE;
        }
        if (preg_match_all($regex_lowercase, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'The Password field must be at least one lowercase letter.');
            return FALSE;
        }
        if (preg_match_all($regex_uppercase, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'The Password field must be at least one uppercase letter.');
            return FALSE;
        }
        if (preg_match_all($regex_number, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'The Password field must have at least one number.');
            return FALSE;
        }
        if (preg_match_all($regex_special, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'The Password field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>§~'));
            return FALSE;
        }
        if (strlen($password) < 5) {
            $this->form_validation->set_message('valid_password', 'The Password field must be at least 5 characters in length.');
            return FALSE;
        }
        if (strlen($password) > 32) {
            $this->form_validation->set_message('valid_password', 'The Password field cannot exceed 32 characters in length.');
            return FALSE;
        }
        return TRUE;
    }

    function change_admin_password()
    {

        $user_details = $this->custom_db->single_table_records('user', '*', array('user_type' => 1));

        foreach ($user_details['data'] as $key => $value) {
            $update = array();
            $condition = array();
            // $email = 'kashyap@mediblisstransactions.com';
            $email = 'info@alkhaleej.tours';
            $update['email'] = provab_encrypt($email);
            $update['user_name'] = $update['email'];
            $condition['user_id'] = $value['user_id'];
            if ($this->custom_db->update_record('user', $update, $condition)) {
                echo 'Email ID Updated';
            } else {
                echo 'Failed';
            }
        }
    }
    function user_privilege_supp($offset = 0)
    {
        error_reporting(E_ALL);
        if (!check_user_previlege('p60'))
            redirect(base_url());
        $page_data['form_data'] = $this->input->post();
        // debug($page_data);exit();
        $get_data = $this->input->get();
        $condition = array();
        // CHECKING DOMAIN ORIGIN SET OR NOT
        // exit;
        if (isset($get_data['domain_origin']) == true && intval($get_data['domain_origin']) > 0) {
            $domain_origin = intval($get_data['domain_origin']);
        } else {
            $domain_origin = 0;
        }

        $page_data['eid'] = intval(@$get_data['eid']);
        $condition = array();
        if (valid_array($page_data['form_data']['user_previlages']) == true && isset($page_data['form_data']['user_id'])) {
            /**
             * AUTOMATE VALIDATOR *
             */
            $page_data['eid'] = $page_data['form_data']['user_id'];
            $active_previlages = $page_data['form_data']['user_previlages'];
            $this->user_model->edit_user_privileges(intval($page_data['form_data']['user_id']), $active_previlages);
            // edit previlages------------------------------------------------
            if (intval(@$get_data['eid']) > 0) {
                $temp_query_string = str_replace('&eid=' . intval($get_data['eid']), '', $_SERVER['QUERY_STRING']);
            } else {
                $temp_query_string = $_SERVER['QUERY_STRING'];
            }

            redirect('user/' . __FUNCTION__ . '?' . $temp_query_string);
        }
        // $search_text = '';
        // if (isset ( $get_data ['previlage_text'] ) == true && $get_data ['previlage_text'] !== '') {
        //  $search_text = $get_data ['previlage_text'];
        //  $page_data ['previlage_text'] = $search_text;
        // }
        $search_text = ' WHERE PL.p_no!=0 && PL.supplier=1 ';
        // $user_info = $this->user_model->get_user_details($page_data ['eid']);
        //debug($user_info);exit;
        $user_info = $this->custom_db->single_table_records('user', '*', array('user_id' => $page_data['eid']));
        // debug($user_info);exit;
        $page_data['info'] = $this->template->isolated_view('user/info', array('info' => $user_info['data'][0]));
        $page_data['table_data'] = $this->user_model->get_privilage_list_supp($page_data['eid'], $search_text);
        /**
         * TABLE PAGINATION
         */
        // Get Online User Count
        // debug($page_data);exit;
        $this->template->view('user/user_privilege', $page_data);
    }
    /* User Privileges */
    function user_privilege($offset = 0)
    {

        if (!check_user_previlege('p60'))
            redirect(base_url());
        $page_data['form_data'] = $this->input->post();
        // debug($page_data);exit();
        $get_data = $this->input->get();
        $condition = array();
        // CHECKING DOMAIN ORIGIN SET OR NOT
        // exit;
        if (isset($get_data['domain_origin']) == true && intval($get_data['domain_origin']) > 0) {
            $domain_origin = intval($get_data['domain_origin']);
        } else {
            $domain_origin = 0;
        }

        $page_data['eid'] = intval(@$get_data['eid']);
        $condition = array();
        if (valid_array($page_data['form_data']['user_previlages']) == true && isset($page_data['form_data']['user_id'])) {
            /**
             * AUTOMATE VALIDATOR *
             */
            $page_data['eid'] = $page_data['form_data']['user_id'];
            $active_previlages = $page_data['form_data']['user_previlages'];
            $this->user_model->edit_user_privileges(intval($page_data['form_data']['user_id']), $active_previlages);
            // edit previlages------------------------------------------------
            if (intval(@$get_data['eid']) > 0) {
                $temp_query_string = str_replace('&eid=' . intval($get_data['eid']), '', $_SERVER['QUERY_STRING']);
            } else {
                $temp_query_string = $_SERVER['QUERY_STRING'];
            }

            redirect('user/' . __FUNCTION__ . '?' . $temp_query_string);
        }
        // $search_text = '';
        // if (isset ( $get_data ['previlage_text'] ) == true && $get_data ['previlage_text'] !== '') {
        //  $search_text = $get_data ['previlage_text'];
        //  $page_data ['previlage_text'] = $search_text;
        // }
        $search_text = ' WHERE PL.p_no!=0 ';
        // $user_info = $this->user_model->get_user_details($page_data ['eid']);
        //debug($user_info);exit;
        $user_info = $this->custom_db->single_table_records('user', '*', array('user_id' => $page_data['eid']));
        // debug($user_info);exit;
        $page_data['info'] = $this->template->isolated_view('user/info', array('info' => $user_info['data'][0]));
        $page_data['table_data'] = $this->user_model->get_privilage_list($page_data['eid'], $search_text);
        /**
         * TABLE PAGINATION
         */
        // Get Online User Count
        // debug($page_data);exit;
        $this->template->view('user/user_privilege', $page_data);
    }
    public function get_city_listsnew()
    {
        $country_id = $this->input->post('country_id');
        $city_id = $this->input->post('city');
        $get_resulted_data =  $this->custom_db->single_table_records('api_city_list', '*', array('country' => $country_id), 0, 100000000, array('destination' => 'asc'));
        if (!empty($get_resulted_data['data'])) {
            $html = "<option value=''>Select City</option>";
            foreach ($get_resulted_data['data'] as  $get_resulted_data_sub) {

                if ($get_resulted_data_sub['origin'] == $city_id) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                $html = $html . "<option value=" . $get_resulted_data_sub['origin'] . " " . $selected . ">" . $get_resulted_data_sub['destination'] . "</option>";
            }
        } else {
            $html = "<option value=''>No City Found</option>";
        }
        echo $html;
        exit;
    }
//new function for user export in pdf zip
    function user_export_pdf_zip($user_list, $module, $title)
    {
        $this->load->library('provab_pdf');
        // Split the array into chunks of EXPORT_CHUNK_COUNT
        $chunks = array_chunk($user_list, EXPORT_CHUNK_COUNT);

        // Get the number of chunks
        $numChunks = count($chunks);

        // Handle the remaining elements (less than EXPORT_CHUNK_COUNT)
        $remaining = count($user_list) % EXPORT_CHUNK_COUNT;
        if ($remaining > 0) {
            // Add the remaining elements to the last chunk
            $chunks[$numChunks - 1] = array_slice($user_list, - ($remaining));
        }

        // Initialize array to store file paths of generated PDFs
        $pdfFiles = [];
        $filenameArr = [];

        foreach ($chunks as $index => $chunk) {
            // Generate a unique filename for the PDF
            $filename = $title . '_' . uniqid() . date("Y-m-d") . '.pdf';
            $filenameArr[] = $filename;

            // Set the output name for the PDF
            $outputName = $filename;

            $pdf_data['export_data'] = $chunk;
            $pdf_data['module'] = $module;
            $pdf_data['user_status'] = $user_status;
            $mail_template = $this->template->isolated_view('report/users_export_pdf', $pdf_data);

            // Generate PDF with the dynamically generated output path
            $pdf = $this->provab_pdf->create_pdf($mail_template, "F", $outputName, "L");

            // Store the file path in the array
            $pdfFiles[] = $pdf;
        }

        // Set appropriate headers for multiple file download
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename=' . $title . date("Y-m-d") . '.zip');

        // Define the directory where you want to save the zip file
        $storageDirectory = DOMAIN_ZIP_DIR;

        // Specify the full path for the zip file
        $zipFilePath = $storageDirectory . $title . date("Y-m-d") . '.zip';

        // Create a zip archive
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            // Add each PDF to the zip file with a unique filename
            foreach ($pdfFiles as $index => $pdf) {
                $i = $index + 1;
                $zip->addFile($pdf, "$i" . "_" . "$filenameArr[$index]");
            }
            // Close the zip file
            $zip->close();

            // Read the zip file and output its contents
            readfile($zipFilePath);

            // delete the temporary zip file
            unlink($zipFilePath);
        } else {
            die("Failed to create zip file");
        }

        // Delete the temporary PDF files
        foreach ($pdfFiles as $pdf) {
            unlink($pdf);
        }
        exit;
    }
    //new function for user export
    function user_export_pdf($user_list, $module, $title)
    {
        $this->load->library('provab_pdf');
        $pdf_data['export_data'] = $user_list;
        $pdf_data['module'] = $module;
        $pdf_data['title'] = $title;
        $mail_template = $this->template->isolated_view('report/users_export_pdf', $pdf_data);
        $pdf = $this->provab_pdf->create_pdf($mail_template, "F", "", "L");
        // Set appropriate headers for multiple file download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename=' . $title . date("Y-m-d") . '.pdf');
        readfile($pdf);
        unlink($pdf);
        exit;
    }
    //new function for user export \
    function user_export()
    {
        $user_type = $_GET['q'];
        $user_status = $_GET['user_status'];
        $op = $_GET['op'];
        if ($user_type == B2C_USER) {
            $module = 'b2c';
            if ($user_status == 1) {
                $title = "Active_B2C_Users_";
            } else {
                $title = "Inactive_B2C_Users_";
            }
        } elseif ($user_type == B2B_USER) {
            $module = 'b2b';
            if ($user_status == 1) {
                $title = "Active_B2B_Users_";
            } else {
                $title = "Inactive_B2B_Users_";
            }
        } elseif ($user_type == SUB_ADMIN) {
            $module = 'subadmin';
            if ($user_status == 1) {
                $title = "Active_SUB_ADMINS_";
            } else {
                $title = "Inactive_SUB_ADMINS_";
            }
        } elseif ($user_type == SUPPLIER) {
            $module = 'supplier';
            if ($user_status == 1) {
                $title = "Active_SUPPLIERS_";
            } else {
                $title = "Inactive_SUPPLIERS_";
            }
        }
        $this->load->model('user_model');
        $user_list = $this->user_model->get_users($user_type, $user_status);
        if ($module == 'b2c') {
            if ($op == 'csv') {
                foreach ($user_list as $user_k => $user_v) {
                    $export_data[$user_k]['sr_no'] = $user_k + 1;
                    $export_data[$user_k]['name'] = $user_v['first_name'] . ' ' . $user_v['last_name'];
                    $export_data[$user_k]['phone'] = $user_v['phone'];
                    $export_data[$user_k]['email'] = provab_decrypt($user_v['email']);
                    $export_data[$user_k]['pending_reward'] = $user_v['pending_reward'] ? $user_v['pending_reward'] : 0;
                    $export_data[$user_k]['status'] = $user_v['status'] ? 'active' : 'inactive';
                }
                $headings = array("Sno", "Name", "Phone", "Email", "Pending Reward", "Status");
                $this->load->library('provab_csv');
                $this->provab_csv->csv_export($headings, $title, $export_data);
            } elseif ($op == 'pdf') {
                if (count($user_list) > EXPORT_CHUNK_COUNT) {
                    $this->user_export_pdf_zip($user_list, $module, $title);
                } else {
                    $this->user_export_pdf($user_list, $module, $title);
                }
            }
        } elseif ($module == 'b2b') {
            $user_id_list = array();
            foreach ($user_list as $k => $v) {
                $tmp_user_list[intval($v['user_id'])] = $v;
                $user_id_list[] = $v['user_id'];
            }
            $deposit_summ = $this->user_model->user_deposit_summary($user_id_list);
            $booking_summ = $this->user_model->user_booking_summary($user_id_list);
            $user_list = [];
            foreach ($user_id_list as $uk => $uv) {
                $user_list[$uv] = $tmp_user_list[$uv];
                if (isset($deposit_summ[$uv])) {
                    $user_list[$uv]['dep_req'] = $deposit_summ[$uv];
                } else {
                    $user_list[$uv]['dep_req'] = false;
                }

                //Booking
                if (isset($booking_summ[$uv])) {
                    $user_list[$uv]['booking_summ'] = $booking_summ[$uv];
                } else {
                    $user_list[$uv]['booking_summ'] = false;
                }
            }
            if ($op == 'csv') {
                $i = 0;
                foreach ($user_list as $user_k => $user_v) {

                    $dep_req = '';
                    if (isset($user_v['dep_req']) == true && isset($user_v['dep_req']['pending']) == true) {
                        $dep_req = intval($user_v['dep_req']['pending']['count']);
                    } else {
                        $dep_req = 0;
                    }
                    $export_data[$user_k]['sr_no'] = ++$i;
                    $export_data[$user_k]['agency_name'] = empty($user_v['agency_name']) == false ? $user_v['agency_name'] : 'Not Added';
                    $export_data[$user_k]['agency_id'] = provab_decrypt($user_v['uuid']);
                    $export_data[$user_k]['agent_name'] = get_enum_list('title', $user_v['title']) . ' ' . $user_v['first_name'] . ' ' . $user_v['last_name'];
                    $export_data[$user_k]['country'] = get_country_name($user_v['country_name']);
                    $export_data[$user_k]['city'] = get_city_name($user_v['city']);
                    $export_data[$user_k]['mobile'] = $user_v['phone'];
                    $export_data[$user_k]['email'] = provab_decrypt($user_v['email']);
                    $export_data[$user_k]['balance'] = roundoff_number($user_v['balance']);
                    $export_data[$user_k]['credit_limit'] = roundoff_number($user_v['credit_limit']);
                    $export_data[$user_k]['due_amount'] = roundoff_number($user_v['due_amount']);
                    $export_data[$user_k]['dep_req'] = $dep_req;
                    if (is_active_airline_module()) {
                        $export_data[$user_k]['flight'] = is_active_airline_module() == true ? intval(@$user_v['booking_summ']['flight']['BOOKING_CONFIRMED']['count']) : 0;
                    }
                    if (is_active_hotel_module()) {
                        $export_data[$user_k]['hotel'] = is_active_hotel_module() == true ? intval(@$user_v['booking_summ']['hotel']['BOOKING_CONFIRMED']['count']) : 0;
                    }
                    if (is_active_bus_module()) {
                        $export_data[$user_k]['bus'] = is_active_bus_module() == true ? intval(@$user_v['booking_summ']['bus']['BOOKING_CONFIRMED']['count']) : 0;
                    }
                    if (is_active_transferv1_module()) {
                        $export_data[$user_k]['transfers'] = is_active_transferv1_module() == true ? intval(@$user_v['booking_summ']['transfer']['BOOKING_CONFIRMED']['count']) : 0;
                    }
                    if (is_active_sightseeing_module()) {
                        $export_data[$user_k]['activity'] = is_active_sightseeing_module() == true ? intval(@$user_v['booking_summ']['sightseeing']['BOOKING_CONFIRMED']['count']) : 0;
                    }
                    $export_data[$user_k]['created_on'] = $user_v['created_datetime'];
                    $export_data[$user_k]['status'] = $user_v['status'] ? 'active' : 'inactive';
                }
                $headings = [];
                $headings[] .= "sno";
                $headings[] .= "Agency Name";
                $headings[] .= "Agency ID";
                $headings[] .= "Agent Name";
                $headings[] .= "Country";
                $headings[] .= "City";
                $headings[] .= "Mobile";
                $headings[] .= "Email";
                $headings[] .= "Balance";
                $headings[] .= "Credit Limit";
                $headings[] .= "Due Amount";
                $headings[] .= "Deposit Request";
                if (is_active_airline_module()) {
                    $headings[] .= "Flight";
                }
                if (is_active_hotel_module()) {
                    $headings[] .= "Hotel";
                }
                if (is_active_bus_module()) {
                    $headings[] .= "Bus";
                }
                if (is_active_transferv1_module()) {
                    $headings[] .= "Transfer";
                }
                if (is_active_sightseeing_module()) {
                    $headings[] .= "Activity";
                }
                $headings[] .= "Created On";
                $headings[] .= "Status";
                // $headings = array("Sno", "Agency Name", "Agency ID", "Agent Name", "Country", "City", "Balance", "Credit Limit", "Due Amount", "Mobile", "Email", is_active_airline_module() ? "Flight" : null, is_active_hotel_module() ? "Hotel" : null, is_active_bus_module() ? "Bus" : "", is_active_transferv1_module() ? "Transfer" : null, is_active_sightseeing_module() ? "Activity" : null, "Created On", "Status");
                $this->load->library('provab_csv');
                $this->provab_csv->csv_export($headings, $title, $export_data);
            } elseif ($op == 'pdf') {
                if (count($user_list) > EXPORT_CHUNK_COUNT) {
                    $this->user_export_pdf_zip($user_list, $module, $title);
                } else {
                    $this->user_export_pdf($user_list, $module, $title);
                }
            }
        } elseif ($module == 'supplier') {
            if ($op == 'csv') {
                foreach ($user_list as $user_k => $user_v) {
                    $export_data[$user_k]['sr_no'] = $user_k + 1;
                    $export_data[$user_k]['supplier_name'] = $user_v['first_name'] . ' ' . $user_v['last_name'];
                    $export_data[$user_k]['phone'] = $user_v['phone'];
                    $export_data[$user_k]['email'] = provab_decrypt($user_v['email']);
                    $export_data[$user_k]['country'] = get_country_name($user_v['country_name']) ? get_country_name($user_v['country_name']) : 'Malaysia';
                    $export_data[$user_k]['pin'] = $user_v['pin_code'];
                    $export_data[$user_k]['address'] = $user_v['address'];
                    $export_data[$user_k]['created_on'] = $user_v['created_datetime'];
                    $export_data[$user_k]['status'] = $user_v['status'] ? 'active' : 'inactive';
                }
                $headings = array("Sno", "Supplier Name", "Phone", "Email", "Country", "Pin", "Address", "Created On", "Status");
                $this->load->library('provab_csv');
                $this->provab_csv->csv_export($headings, $title, $export_data);
            } elseif ($op == 'pdf') {
                if (count($user_list) > EXPORT_CHUNK_COUNT) {
                    $this->user_export_pdf_zip($user_list, $module, $title);
                } else {
                    $this->user_export_pdf($user_list, $module, $title);
                }
            }
        } elseif ($module == 'subadmin') {
            if ($op == 'csv') {
                foreach ($user_list as $user_k => $user_v) {
                    $export_data[$user_k]['sr_no'] = $user_k + 1;
                    $export_data[$user_k]['name'] = get_enum_list('title', $user_v['title']) . ' ' . $user_v['first_name'] . ' ' . $user_v['last_name'];
                    $export_data[$user_k]['phone'] = $user_v['phone'];
                    $export_data[$user_k]['email'] = provab_decrypt($user_v['email']);
                    $export_data[$user_k]['status'] = $user_v['status'] ? 'active' : 'inactive';
                }
                $headings = array("Sno", "Name", "Phone", "Email", "Status");
                $this->load->library('provab_csv');
                $this->provab_csv->csv_export($headings, $title, $export_data);
            } elseif ($op == 'pdf') {
                if (count($user_list) > EXPORT_CHUNK_COUNT) {
                    $this->user_export_pdf_zip($user_list, $module, $title);
                } else {
                    $this->user_export_pdf($user_list, $module, $title);
                }
            }
        }
    }
  // changes start user crm: added function user_data_crm and users_crm_remarks_update
  function user_data_crm()
  {
      $ip_data = $_GET;
      $user_type_ip = $ip_data['q'];
      if ($user_type_ip == B2C_USER) {
          $module = 'b2c';
      } elseif ($user_type_ip == B2B_USER) {
          $module = 'b2b';
      }
      $user_status_ip = $ip_data['user_status'];
      $users_query = 'select * from user where user_type = ' . $user_type_ip . ' and status = ' . $user_status_ip . ' group by email order by created_datetime desc';
      $users = $this->db->query($users_query)->result_array();
      foreach ($users as $u_k => $u_v) {
          $users[$u_k]['flight_booking_data'] = array();
          $users[$u_k]['holiday_booking_data'] = array();
          $users[$u_k]['hotel_booking_data'] = array();
          $users[$u_k]['bus_booking_data'] = array();
          $users[$u_k]['transferv1_booking_data'] = array();
          $users[$u_k]['sightseeing_booking_data'] = array();
          if (is_active_airline_module()) {
              $flight_booking_data_query = 'select * from flight_booking_details BD where domain_origin=' . get_domain_auth_id() . ' and BD.created_by_id =' . $u_v['user_id'] . ' and (BD.status = ' . BOOKING_CONFIRMED . ') order by BD.created_datetime desc';
              $users[$u_k]['flight_booking_data'] = $this->db->query($flight_booking_data_query)->result_array();
              $flight_booking_data_query_inprogress = 'select * from flight_booking_details BD where domain_origin=' . get_domain_auth_id() . ' and BD.created_by_id =' . $u_v['user_id'] . ' and (BD.status = ' . BOOKING_INPROGRESS . ') order by BD.created_datetime desc';
              $users[$u_k]['flight_booking_data_inprogress'] = $this->db->query($flight_booking_data_query_inprogress)->result_array();
              $users[$u_k]['flight_booking_data_all'] = array_merge($users[$u_k]['flight_booking_data'], $users[$u_k]['flight_booking_data_inprogress']);
          }
          // if (is_active_package_module()) {
          $holiday_booking_data_query = 'select * from tour_booking_details BD where BD.booked_by_id =' . $u_v['user_id'] . ' and BD.status = 2 order by BD.created_datetime desc';
          $users[$u_k]['holiday_booking_data'] = $this->db->query($holiday_booking_data_query)->result_array();
          $holiday_booking_data_query_inprogress = 'select * from tour_booking_details BD where BD.booked_by_id =' . $u_v['user_id'] . ' and BD.status = 1 order by BD.created_datetime desc';
          $users[$u_k]['holiday_booking_data_inprogress'] = $this->db->query($holiday_booking_data_query_inprogress)->result_array();
          $users[$u_k]['holiday_booking_data_all'] = array_merge($users[$u_k]['holiday_booking_data'], $users[$u_k]['holiday_booking_data_inprogress']);
          // }
          if (is_active_hotel_module()) {
              $hotel_booking_data_query = 'select * from hotel_booking_details BD where domain_origin=' . get_domain_auth_id() . ' and BD.created_by_id =' . $u_v['user_id'] . ' and BD.status = ' . BOOKING_CONFIRMED . ' order by BD.created_datetime desc';
              $users[$u_k]['hotel_booking_data'] = $this->db->query($hotel_booking_data_query)->result_array();
              $hotel_booking_data_query_inprogress = 'select * from hotel_booking_details BD where domain_origin=' . get_domain_auth_id() . ' and BD.created_by_id =' . $u_v['user_id'] . ' and BD.status = ' . BOOKING_INPROGRESS . ' order by BD.created_datetime desc';
              $users[$u_k]['hotel_booking_data_inprogress'] = $this->db->query($hotel_booking_data_query_inprogress)->result_array();
              $users[$u_k]['hotel_booking_data_all'] = array_merge($users[$u_k]['hotel_booking_data'], $users[$u_k]['hotel_booking_data_inprogress']);
          }
          if (is_active_bus_module()) {
              $bus_booking_data_query = 'select * from bus_booking_details BD where domain_origin=' . get_domain_auth_id() . ' and BD.created_by_id =' . $u_v['user_id'] . ' and BD.status = ' . BOOKING_CONFIRMED . ' order by BD.created_datetime desc';
              $users[$u_k]['bus_booking_data'] = $this->db->query($bus_booking_data_query)->result_array();
              $bus_booking_data_query_inprogress = 'select * from bus_booking_details BD where domain_origin=' . get_domain_auth_id() . ' and BD.created_by_id =' . $u_v['user_id'] . ' and BD.status = ' . BOOKING_INPROGRESS . ' order by BD.created_datetime desc';
              $users[$u_k]['bus_booking_data_inprogress'] = $this->db->query($bus_booking_data_query_inprogress)->result_array();
              $users[$u_k]['bus_booking_data_all'] = array_merge($users[$u_k]['bus_booking_data'], $users[$u_k]['bus_booking_data_inprogress']);
          }
          if (is_active_transferv1_module()) {
              $transferv1_booking_data_query = 'select * from transferv1_booking_details BD where domain_origin=' . get_domain_auth_id() . ' and BD.created_by_id =' . $u_v['user_id'] . ' and BD.status = ' . BOOKING_CONFIRMED . ' order by BD.created_datetime desc';
              $users[$u_k]['transferv1_booking_data'] = $this->db->query($transferv1_booking_data_query)->result_array();
              $transferv1_booking_data_query_inprogress = 'select * from transferv1_booking_details BD where domain_origin=' . get_domain_auth_id() . ' and BD.created_by_id =' . $u_v['user_id'] . ' and BD.status = ' . BOOKING_INPROGRESS . ' order by BD.created_datetime desc';
              $users[$u_k]['transferv1_booking_data_inprogress'] = $this->db->query($transferv1_booking_data_query_inprogress)->result_array();
              $users[$u_k]['transferv1_booking_data_all'] = array_merge($users[$u_k]['transferv1_booking_data'], $users[$u_k]['transferv1_booking_data_inprogress']);
          }
          if (is_active_sightseeing_module()) {
              $sightseeing_booking_data_query = 'select * from sightseeing_booking_details BD where domain_origin=' . get_domain_auth_id() . ' and BD.created_by_id =' . $u_v['user_id'] . ' and BD.status = ' . BOOKING_CONFIRMED . ' order by BD.created_datetime desc';
              $users[$u_k]['sightseeing_booking_data'] = $this->db->query($sightseeing_booking_data_query)->result_array();
              $sightseeing_booking_data_query_inprogress = 'select * from sightseeing_booking_details BD where domain_origin=' . get_domain_auth_id() . ' and BD.created_by_id =' . $u_v['user_id'] . ' and BD.status = ' . BOOKING_INPROGRESS . ' order by BD.created_datetime desc';
              $users[$u_k]['sightseeing_booking_data_inprogress'] = $this->db->query($sightseeing_booking_data_query_inprogress)->result_array();
              $users[$u_k]['sightseeing_booking_data_all'] = array_merge($users[$u_k]['sightseeing_booking_data'], $users[$u_k]['sightseeing_booking_data_inprogress']);
          }
      }
      $page_data['users_data'] = $users;
      $page_data['module'] = $module;
      if ($user_status_ip == 1) {
          $status_text = 'Active';
      } else {
          $status_text = 'InActive';
      }
      $page_data['status_text'] = $status_text;
      $this->template->view('user/user_data_crm', $page_data);
  }


  function users_crm_remarks_update()
  {
      // handles the request for updating user CRM remarks attributes

      // Check for POST data
      if ($this->input->post()) {
          // Get the posted data
          $input_data = $this->input->post();
          $user_id = $input_data['user_id'];
          $emailSent = "";
          $called = "";
          $visited = "";
          $remarks = "";
          if ($input_data['emailSentCheckbox_' . $user_id]) {
              $emailSent = $input_data['emailSentCheckbox_' . $user_id];
              $elementId = 'emailSentCheckbox_' . $user_id;
          }
          if ($input_data['calledCheckbox_' . $user_id]) {
              $called = $input_data['calledCheckbox_' . $user_id];
              $elementId = 'calledCheckbox_' . $user_id;
          }
          if ($input_data['visitedCheckbox_' . $user_id]) {
              $visited = $input_data['visitedCheckbox_' . $user_id];
              $elementId = 'visitedCheckbox_' . $user_id;
          }
          if ($input_data['remarksInput_' . $user_id]) {
              $remarks = $input_data['remarksInput_' . $user_id];
              $elementId = 'remarksInput_' . $user_id;
          }
          $this->load->model('user_model');
          $this->user_model->users_crm_remarks_update($user_id, $emailSent, $called, $visited, $remarks);

          // You can also send a response back to your JavaScript
          $response['message'] = "Data updated successfully!";
          $response['elementId'] = $elementId;
          echo json_encode($response);
      } else {
          // Handle case when no POST data is received
          $response['message'] = "No data received!";
          echo json_encode($response);
      }
  }
  // changes end user crm: added function user_data_crm and users_crm_remarks_update
}
