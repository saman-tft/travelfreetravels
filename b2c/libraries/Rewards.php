<?php

/**
 * Nikhil Das s
 * Library for rewards system
 */
class Rewards
{
    // private $CI;
    protected $CI;
    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->database();
    }

    //To get pending rewards based on user
    public function usable_rewards($user_id, $module, $usable_rewards)
    {
        $this->CI->db->where(array('user_id' => $user_id));
        $this->CI->db->select('user_id,general_reward, pending_reward, spefic_reward, used_reward');
        $query = $this->CI->db->get('user');
        //	echo $this->CI->db->last_query();
        //	die;
        if ($query) {
            $re = $query->row_array();

            $pending_rewards = $re['pending_reward'];
            ///usable rewards takes as percentage
            //debug($pending_rewards);debug($usable_rewards);exit;
            //$rewards_usable = round($pending_rewards*$usable_rewards)/100;
            // debug($rewards_usable);exit();
            return $pending_rewards;
        } else {
            return FALSE;
        }
    }
    //To get the convertion and limit details
    public function get_reward_coversion_and_limit_details()
    {
        $this->CI->db->select('*');
        $query = $this->CI->db->get('rewards');
        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
    //To find usable reward of user
    public function find_reward_amount($usable_rewards)
    {

        $re = $this->get_reward_coversion_and_limit_details();
        $convertor = $re[0]['currency_value'] / $re[0]['reward_point'];
        $amount = $convertor * $usable_rewards;
        return $amount;
    }
    //to update the reward_report
    public function update_reward_report_data($id, $data)
    {
        $this->CI->db->where(array('user_id' => $id));
        if ($this->CI->db->insert('rewards_report', $data)) {
            // echo $this->db->last_query();exit();
            return TRUE;
        } else {
            return FALSE;
        }
    }
    //to update the user table
    public function update_reward_record($id, $data)
    {
        // echo $id;die;
        $this->CI->db->where(array('user_id' => $id));
        if ($this->CI->db->update('user', $data)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function update_flight_rewards_details($temp_booking, $book_id)
    {
        $rewards_point = $temp_booking['book_attributes']['reward_earned'];
        $reward_usable = $temp_booking['book_attributes']['reward_usable'];
        $reducing_amount = $temp_booking['book_attributes']['reducing_amount'];
// new if else condition instead of the following ones for user profile rewards
        // $update_data = array("reward_amount" => $reducing_amount, "reward_points" => $reward_usable, "reward_earned" => $rewards_point);
        if ($temp_booking['book_attributes']['redeem_points_post'] == 1) {
            $update_data = array("reward_amount" => $reducing_amount, "reward_points" => $reward_usable, "reward_earned" => $rewards_point);
        } else {
            $update_data = array("reward_earned" => $rewards_point);
        }

        $this->CI->db->where('app_reference', $book_id);
        $this->CI->db->update('flight_booking_details', $update_data);
                //changes added following code for rewards section
                $query_pnr = "SELECT * FROM flight_booking_transaction_details WHERE app_reference = '{$book_id}'";
                $booking_record = $this->CI->db->query($query_pnr)->result_array()[0];
                $pnr_val = $booking_record['pnr'];
                if ($pnr_val != '') {
                    $update_query = "UPDATE rewards_report SET book_id = '{$pnr_val}' WHERE book_id = '{$book_id}'";
                    $this->CI->db->query($update_query);
                }
                //upto here
    }

    //for rewards
    public function get_reward_report($id)
    {

        $this->CI->db->where(array('user_id' => $id));
        $this->CI->db->select('*');
        $query = $this->CI->db->get('rewards_report');
        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    } //end 
    public function get_reward_report_module($id)
    {

        $this->CI->db->where(array('rewards_report.user_id' => $id));
        $this->CI->db->select('rewards_report.*');
        $this->CI->db->from('rewards_report');
        $this->CI->db->order_by('rewards_report.id', 'DESC');
        // $this->CI->db->join('booking_global','booking_global.user_id=rewards_report.user_id',"left");
        $this->CI->db->group_by('rewards_report.id');
        $query = $this->CI->db->get();
        if ($query) {
            // debug($query->result_array());exit();

            return $query->result_array();
        } else {
            return FALSE;
        }
    } //end 
    function get_total_reward_report($id)
    {

        $this->CI->db->where(array('user_id' => $id));
        $this->CI->db->select('SUM(used_rewardpoint) as used_reward,pending_rewardpoint');

        $query = $this->CI->db->get('rewards_report');
        $result = $query->result_array();
        return $result;
    }

    public function page_data_reward_details($module, $price, $convenience_fee = 0, $admin_markup = 0)
    {
        $user_id = $this->CI->entity_user_id;
        $page_data['total_price'] = $price + $convenience_fee + $admin_markup;
        //debug($convenience_fee);exit();
        $page_data['reward_earned'] = $this->find_reward($module, $page_data['total_price']);
        //debug($page_data['reward_earned']);exit();
        $reward_details = $this->get_reward_coversion_and_limit_details();
        $usable_rewards = $page_data['reward_earned'];
        $usable_rewards = $this->usable_rewards($user_id, $module, $usable_rewards);
        // debug($usable_rewards);exit('reward lib 107');
        if ($reward_details[0]['reward_min'] <= $usable_rewards && $reward_details[0]['reward_max'] >= $usable_rewards) {
            $page_data['reward_usable'] = $usable_rewards;
        } else {
            $page_data['reward_usable'] = 0;
        }
        if ($page_data['reward_usable']) {
            $reducing_amount = $this->find_reward_amount($page_data['reward_usable']);
            $page_data['total_price_with_rewards'] = $page_data['total_price'] - $reducing_amount;
            $page_data['reward_usable_amount'] = round($reducing_amount);
        }
        return $page_data;
    }

    //To fetch the reward range based on module
    public function find_reward($module, $amount)
    {
        // echo $module."--".$amount;exit();

        $flag = FALSE;
        $condition = array(
            'module' => $module,
            'status' => 1,
        );
        // debug($condition);exit();
        $this->CI->db->select('*');
        $this->CI->db->where($condition);
        $query = $this->CI->db->get('reward_range');
        // echo $this->CI->db->last_query();exit();
        $result = $query->result_array();
        //	debug($amount);
        //debug($result);exit();
        foreach ($result as $key => $value) {
            if ($value['reward_from'] <= $amount && $value['reward_to'] >= $amount) {
                $flag = TRUE;
                //	echo "tesrer";die;
                //$reward_values = $flag ? $value['reward_value'] : FLASE;
                if ($flag) {
                    $reward_values['earning_reward'] = $value['reward_getting'];
                    $reward_values['usable_reward'] = $value['reward_value'];
                } else {

                    $reward_values = FALSE;
                }
                //	debug($reward_values);die;
                return $reward_values;
                break;
            }
        }
    } //end	
    public function update_after_booking($temp_booking, $book_id)
    {
        /////fetching booking reward data///
        $this->CI->db->select('rewards_point,rewards_amount,reward_earned');
        $this->CI->db->where(array('app_reference' => $book_id));
        $query = $this->CI->db->get('payment_gateway_details');
        $res = $query->result_array();

        // debug($res[0]['reward_earned']);exit();
        ////end/////////////
        $user_id = $this->CI->entity_user_id;
        $data_rewards = $this->user_reward_details($user_id);

        $pending_rewards = $data_rewards['pending_reward'] + $res[0]['reward_earned'] - $res[0]['rewards_point'];
        $used_rewards = $data_rewards['used_reward'] + round($res[0]['rewards_point']);
        $data_upadte_rewards = array(
            'pending_reward' => round($pending_rewards),
            'used_reward' => round($used_rewards),

        );
        // debug($data_upadte_rewards);

        $module = $this->find_module_using_booking_source($temp_booking['booking_source']);
        $module = $this->find_module_name_using_meta_course_list_id($module);
        $data_upadte_rewards_report = array(
            'pending_rewardpoint' => $pending_rewards,
            'used_rewardpoint' => round($res[0]['rewards_point']),
            'reward_earned' => $res[0]['reward_earned'],
            'user_id' => $user_id,
            'module' => $module,
            'book_id' => $book_id,
            'created' => date('Y-m-d h:i:s')
        );
        $this->update_reward_report_data($user_id, $data_upadte_rewards_report);
        $re = $this->update_reward_record($user_id, $data_upadte_rewards);
        if ($re) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function update_hotel_rewards_details($temp_booking, $book_id)
    {
        $rewards_point = $temp_booking['book_attributes']['reward_earned'];
        $reward_usable = $temp_booking['book_attributes']['reward_usable'];
        $reducing_amount = $temp_booking['book_attributes']['reducing_amount'];

        $update_data = array("reward_amount" => $reducing_amount, "reward_points" => $reward_usable, "reward_earned" => $rewards_point);

        $this->CI->db->where('app_reference', $book_id);
        $this->CI->db->update('hotel_booking_details', $update_data);
    }
    public function update_car_rewards_details($temp_booking, $book_id)
    {
        $rewards_point = $temp_booking['book_attributes']['reward_earned'];
        $reward_usable = $temp_booking['book_attributes']['reward_usable'];
        $reducing_amount = $temp_booking['book_attributes']['reducing_amount'];

        $update_data = array("reward_amount" => $reducing_amount, "reward_points" => $reward_usable, "reward_earned" => $rewards_point);

        $this->CI->db->where('app_reference', $book_id);
        $this->CI->db->update('Car_booking_details', $update_data);
    }
    public function update_activity_rewards_details($temp_booking, $book_id)
    {
        $rewards_point = $temp_booking['book_attributes']['reward_earned'];
        $reward_usable = $temp_booking['book_attributes']['reward_usable'];
        $reducing_amount = $temp_booking['book_attributes']['reducing_amount'];

        $update_data = array("reward_amount" => $reducing_amount, "reward_points" => $reward_usable, "reward_earned" => $rewards_point);

        $this->CI->db->where('app_reference', $book_id);
        $this->CI->db->update('activity_booking_details', $update_data);
    }
    public function update_transfers_rewards_details($temp_booking, $book_id)
    {
        $rewards_point = $temp_booking['book_attributes']['reward_earned'];
        $reward_usable = $temp_booking['book_attributes']['reward_usable'];
        $reducing_amount = $temp_booking['book_attributes']['reducing_amount'];

        $update_data = array("reward_amount" => $reducing_amount, "reward_points" => $reward_usable, "reward_earned" => $rewards_point);

        $this->CI->db->where('app_reference', $book_id);
        $this->CI->db->update('transfer_booking_details', $update_data);
    }
    public function find_module_using_booking_source($booking_source = "")
    {
        $this->CI->db->select('	meta_course_list_id');
        $this->CI->db->where(array('source_id' => $booking_source));
        $query = $this->CI->db->get('booking_source');
        $res = $query->result_array();
        return $res[0]['meta_course_list_id'];
    }
    public function find_module_name_using_meta_course_list_id($module_id = "")
    {
        $this->CI->db->select('name as module');
        $this->CI->db->where(array('course_id' => $module_id));
        $query = $this->CI->db->get('meta_course_list');
        $res = $query->result_array();
        return $res[0]['module'];
    }
    public function user_reward_details($user_id)
    {
        $this->CI->db->where(array('user_id' => $user_id));
        $this->CI->db->select('*');
        $query = $this->CI->db->get('user');
        if ($query) {
            return $query->row_array();
        } else {
            return FALSE;
        }
    }
    public function update_earned_rewards_details($temp_booking, $book_id, $table_name)
    {
        $rewards_point = $temp_booking['book_attributes']['reward_earned'];

        $update_data = array("reward_earned" => $rewards_point);

        $this->CI->db->where('app_reference', $book_id);
        $this->CI->db->update($table_name, $update_data);
    }
    public function update_reward_earned_value($temp_booking, $book_id)
    {
        $this->CI->db->select('rewards_point,rewards_amount,reward_earned');
        $this->CI->db->where(array('app_reference' => $book_id));
        $query = $this->CI->db->get('payment_gateway_details');
        $res = $query->result_array();
        //echo $book_id;
        //debug($res);die;
        $user_id = $this->CI->entity_user_id;
        $data_rewards = $this->user_reward_details($user_id);
        $pending_rewards = $data_rewards['pending_reward'] + $res[0]['reward_earned'];

        $data_upadte_rewards = array(
            'pending_reward' => round($pending_rewards)
        );
        $user_id = $this->CI->entity_user_id;
        //	echo "test";
        //	debug($data_upadte_rewards);		debug($user_id);die;
        $this->CI->db->where(array('user_id' => $user_id));
        $this->CI->db->update('user', $data_upadte_rewards);
    }
}
