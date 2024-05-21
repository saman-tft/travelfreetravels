<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab - Provab Application
 * @subpackage Travel Portal
 * @author     Balu A<balu.provab@gmail.com> on 01-06-2015
 * @version    V2
 */

class Management extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model ( 'Custom_Db' );
	}
	public function b2b_reward_point_manager(){
		$this->load->model('loyalty_program_model');
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		// echo $current_user_id;exit;
		$page_data['total_point']=$this->loyalty_program_model->get_agent_point($current_user_id);
		// debug($page_data['total_point']);exit;
		$this->template->view ( 'management/b2b_reward_point', $page_data );
	}
	// added new function for limit and sectorwise promocode application
	public function promocode()
    {
        // echo ("hii");die;
        $all_post = $this->input->post();
        //changes start: added new code for to handle promocode for multicity to fix database error on the booking page
        $all_post['promo_from_loc'] = explode(',', $all_post['promo_from_loc']);
        $all_post['promo_to_loc'] = explode(',', $all_post['promo_to_loc']);
        $all_post['promo_from_country'] = explode(',', $all_post['promo_from_country']);
        $all_post['promo_to_country'] = explode(',', $all_post['promo_to_country']);
             //changes end: added new code for to handle promocode for multicity to fix database error on the booking page
        $application_default_currency = admin_base_currency();
        $currency_obj = new Currency(array('module_type' => 'flight', 'from' => admin_base_currency(), 'to' => $all_post['currency']));
        $condition['promo_code'] = $all_post['promocode'];
        $condition['status'] = 1;
        $promo_code_res = $this->Custom_Db->single_table_records('promo_code_list', '*', $condition);
        if ($promo_code_res['data'][0]['used_limit'] == '') {
            $promo_code_res['data'][0]['used_limit'] = 0;
        }
        if ($promo_code_res['status'] == 1 && $promo_code_res['data'][0]['limit'] > $promo_code_res['data'][0]['used_limit']) {

            $promo_code = $promo_code_res['data'][0];
               //changes start: added new code for to handle promocode for multicity to fix database error on the booking page
               foreach ($all_post['promo_from_country'] as $p_k => $p_v) {
                $e_c = 0;
                if (isset($all_post['promo_from_country'][$p_k]) && !empty($promo_code['for_country']) && md5($promo_code['for_country']) != $all_post['promo_from_country'][$p_k]) {
                    $e_c++;
                    $result['status'] = 0;
                    // $result['error_msg'] = 'Invalid Promo Code for Origin Country';
                        $result['error_msg'] = "Sorry!! This Promo-Code isn't applicable from the place you're trying to travel from!!";
                    $result['step'] = 23;
                }
                if (isset($all_post['promo_from_loc'][$p_k]) && !empty($promo_code['promo_for_city']) && md5($promo_code['promo_for_city']) != $all_post['promo_from_loc'][$p_k]) {
                    //  debug($all_post['promo_from_loc'][$p_k]);die;
                    $e_c++;
                    $result['status'] = 0;
                    // $result['error_msg'] = 'Invalid Promo Code for Origin City';
                              $result['error_msg'] = "Sorry!! This Promo-Code isn't applicable from the place you're trying to travel from!!";
                    $result['step'] = 23;
                }
                if (isset($all_post['promo_to_country'][$p_k]) && !empty($promo_code['to_country']) && md5($promo_code['to_country']) != $all_post['promo_to_country'][$p_k]) {
                    $e_c++;
                    $result['status'] = 0;
                    // $result['error_msg'] = 'Invalid Promo Code for Destination Country';
                          $result['error_msg'] = "Sorry!! This Promo-Code isn't applicable for the destination you're trying to travel!!";
                    $result['step'] = 23;
                }
                if (isset($all_post['promo_to_loc'][$p_k]) && !empty($promo_code['promo_to_city']) && md5($promo_code['promo_to_city']) != $all_post['promo_to_loc'][$p_k]) {
                    $e_c++;
                    $result['status'] = 0;
                    // $result['error_msg'] = 'Invalid Promo Code for Destination City';
                           $result['error_msg'] = "Sorry!! This Promo-Code isn't applicable for the destination you're trying to travel!!";
                    $result['step'] = 23;
                }
                if ($e_c == 0) {
                    break;
                }
            }
            if ($e_c == 0) {
                 //changes end: added new code for to handle promocode for multicity to fix database error on the booking page added bracket before the final else of the fun
            if (md5($promo_code['module']) != $all_post['moduletype']) {

                $result['status'] = 0;
                $result['error_msg'] = 'Sorry!! This is an invalid Promo-Code';
            } elseif ($promo_code['expiry_date'] <= date('Y-m-d') && $promo_code['expiry_date'] != '0000-00-00') {

                $result['status'] = 0;
                $result['error_msg'] = 'Sorry!! This Promo-Code has Expired';
                //changes start: removed previous code to handle promocode for multicity to fix database error on the booking page
            // } elseif (isset($all_post['promo_from_country']) && !empty($promo_code['for_country']) && md5($promo_code['for_country']) != $all_post['promo_from_country']) {

            //     $result['status'] = 0;
            //     // $result['error_msg'] = 'Invalid Promo Code for Origin Country';
            //     $result['error_msg'] = "Sorry!! This Promo-Code isn't applicable from the place you're trying to travel from!!";
            //     $result['step'] = 23;
            // } elseif (isset($all_post['promo_from_loc']) && !empty($promo_code['promo_for_city']) && md5($promo_code['promo_for_city']) != $all_post['promo_from_loc']) {

            //     $result['status'] = 0;
            //     // $result['error_msg'] = 'Invalid Promo Code for Origin City';
            //     $result['error_msg'] = "Sorry!! This Promo-Code isn't applicable from the place you're trying to travel from!!";
            //     $result['step'] = 23;
            // } elseif (isset($all_post['promo_to_country']) && !empty($promo_code['to_country']) && md5($promo_code['to_country']) != $all_post['promo_to_country']) {

            //     $result['status'] = 0;
            //     $result['error_msg'] = "Sorry!! This Promo-Code isn't applicable for the destination you're trying to travel!!";
            //     $result['step'] = 23;
            // } elseif (isset($all_post['promo_to_loc']) && !empty($promo_code['promo_to_city']) && md5($promo_code['promo_to_city']) != $all_post['promo_to_loc']) {

            //     $result['status'] = 0;
            //     // $result['error_msg'] = 'Invalid Promo Code for Destination City';
            //     $result['error_msg'] = "Sorry!! This Promo-Code isn't applicable for the destination you're trying to travel!!";
            //     $result['step'] = 23;
                // } elseif (isset($all_post['promo_for_city']) && !empty($promo_code['promo_for_city']) && md5($promo_code['promo_for_city']) != $all_post['promo_for_city']) {

                //     $result['status'] = 0;
                //     $result['error_msg'] = 'Invalid Promo Code4';
                //     $result['step'] = 23;
                // } elseif (isset($all_post['promo_to_city']) && !empty($promo_code['promo_to_city']) && $promo_code['module'] == 'flight' && md5($promo_code['promo_to_city']) != $all_post['promo_to_city']) {

                //     $result['status'] = 0;
                //     $result['error_msg'] = 'Invalid Promo Code5';
                //     $result['step'] = 24;
                  //changes end: removed previous code to handle promocode for multicity to fix database error on the booking page
            } else {
                if ($promo_code['module'] == 'car') {
                    $booking_table = 'car_booking_details';
                } elseif ($promo_code['module'] == 'hotel') {
                    $booking_table = 'hotel_booking_details';
                } elseif ($promo_code['module'] == 'flight') {
                    $booking_table = 'flight_booking_details';
                } elseif ($promo_code['module'] == 'activities') {
                    $booking_table = 'sightseeing_booking_details';
                } elseif ($promo_code['module'] == 'transfers') {
                    $booking_table = 'transferv1_booking_details';
                } elseif ($promo_code['module'] == 'bus') {
                    $booking_table = 'bus_booking_details';
                } elseif ($promo_code['module'] == 'holiday') {
                    $booking_table = 'tour_booking_details';
                }
                ###################################################################################
                if (is_logged_in_user()) {
                    $query = "SELECT BD.origin FROM payment_gateway_details AS PGD RIGHT JOIN " . $booking_table . " AS BD ON PGD.app_reference = BD.app_reference WHERE BD.created_by_id='" . $this->entity_user_id . "' ";
                } else {
                    $email = $all_post['email'];
                    $query = "SELECT BD.origin FROM payment_gateway_details AS PGD RIGHT JOIN " . $booking_table . " AS BD ON PGD.app_reference = BD.app_reference WHERE BD.email='" . $email . "' and PGD.status!='pending'";
                }
                ###################################################################################
                $user_promocode_check = $this->Custom_Db->get_result_by_query($query);
                $user_promocode_check = 0;
                if ($user_promocode_check > 0) {
                    $result['status'] = 0;
                    $result['error_msg'] = 'Already used';
                } else {
                    $minimum_amount = get_converted_currency_value($currency_obj->force_currency_conversion($promo_code['minimum_amount']));
                    $total_amount_val_org = str_replace(',', '', $all_post['total_amount_val']);
                    if ($total_amount_val_org > $minimum_amount) {
                        if ($minimum_amount == 0) {
                            if ($total_amount_val_org < 10000) {
                                // $promo_code['value'] = $promo_code['value'] * 1.1;
                            } elseif (10000 <= $total_amount_val_org and $total_amount_val_org < 20000) {
                                $promo_code['value'] = $promo_code['value'] * 1.1;
                            } else {
                                $promo_code['value'] = $promo_code['value'] * 1.5;
                            }
                        }
                        if ($promo_code['value_type'] == 'percentage') {
                            $result['value'] = ($total_amount_val_org * round($promo_code['value'])) / 100;
                            // $result['value'] = $result['value'];
                            $result['actual_value'] = round($result['value']);
                        } else {
                            $result['value'] = $promo_code['value'];
                            $result['actual_value'] = get_converted_currency_value($currency_obj->force_currency_conversion(round($promo_code['value'])));
                            $result['value'] = get_converted_currency_value($currency_obj->force_currency_conversion($result['value']));
                            // $result['value'] = $result['value'];
                        }
                        if ($result['value'] < $total_amount_val_org) {
                            $total_amount_val = ($total_amount_val_org + $all_post['convenience_fee'] + $all_post['convenience_fee_gst']) - $result['value'];
                            if (isset($all_post['extra_baggage'])) {
                                $total_amount_val += $all_post['extra_baggage'];
                            }
                            if (isset($all_post['extra_meal'])) {
                                $total_amount_val += $all_post['extra_meal'];
                            }
                            if (isset($all_post['extra_seat'])) {
                                $total_amount_val += $all_post['extra_seat'];
                            }
                            $total_amount_val = ($total_amount_val > 0) ? $total_amount_val : 0;
                            //$result['total_amount_val'] = number_format($total_amount_val,2);
                            //$result['total_amount_data'] = $all_post['currency_symbol']." ".number_format($total_amount_val, 2);
                            $result['total_amount_val'] = round($total_amount_val);
                            $result['total_amount_data'] = $all_post['currency_symbol'] . " " . round($total_amount_val);
                            $result['convenience_fee'] = $all_post['convenience_fee'];
                            $result['promocode'] = $all_post['promocode'];
                            $result['discount_value'] = $all_post['currency_symbol'] . " " . number_format($result['value'], 2);
                            $result['module'] = $all_post['moduletype'];
                            $result['status'] = 1;
                            //  debug($promo_code);die;
                            /*  $this->custom_db->update_record('promo_code_list',array('used_limit'=>$promo_code['used_limit']+1,'limit'=>$promo_code['limit']-1),array('origin'=>$promo_code['origin']));
                                if($promo_code['limit']-1==0)
                                {
                                    $this->custom_db->update_record('promo_code_list',array('status'=>0),array('origin'=>$promo_code['origin']));
                                }*/
                            $this->custom_db->insert_record('promo_code_doscount_applied', array('discount_value' => $result['actual_value'], 'promocode' => $result['promocode'], 'module' => $result['module'], 'search_key' => provab_encrypt($all_post['booking_key']), 'created_datetime' => date('Y-m-d H:i:s')));
                        } else {
                            $result['status'] = 0;
                            $result['error_msg'] = 'Invalid Promo Code6';
                        }
                    } else {
                        $result['status'] = 0;
                        // $result['error_msg'] = 'Invalid Promo Code7';
                        $result['error_msg'] = 'Sorry!! The Promo-Code can only be applied to flight having minimum amount of Rs.'.round($minimum_amount);
                    }
                }
            }
        } }else {
            $result['status'] = 0;
            $result['error_msg'] = 'Sorry!! This is an invalid Promo-Code';
        }
        echo json_encode($result);
    }
	public function promocode_old19Jan2024()
    {
        // echo ("hii");die;
        $all_post = $this->input->post();
        $application_default_currency = admin_base_currency();
        $currency_obj = new Currency(array('module_type' => 'flight', 'from' => admin_base_currency(), 'to' => $all_post['currency']));
        $condition['promo_code'] = $all_post['promocode'];
        $condition['status'] = 1;
        $promo_code_res = $this->Custom_Db->single_table_records('promo_code_list', '*', $condition);
        if ($promo_code_res['data'][0]['used_limit'] == '') {
            $promo_code_res['data'][0]['used_limit'] = 0;
        }
        if ($promo_code_res['status'] == 1 && $promo_code_res['data'][0]['limit'] > $promo_code_res['data'][0]['used_limit']) {

            $promo_code = $promo_code_res['data'][0];
            if (md5($promo_code['module']) != $all_post['moduletype']) {

                $result['status'] = 0;
                $result['error_msg'] = 'Sorry!! This is an invalid Promo-Code';
            } elseif ($promo_code['expiry_date'] <= date('Y-m-d') && $promo_code['expiry_date'] != '0000-00-00') {

                $result['status'] = 0;
                $result['error_msg'] = 'Sorry!! This Promo-Code has Expired';
            } elseif (isset($all_post['promo_from_country']) && !empty($promo_code['for_country']) && md5($promo_code['for_country']) != $all_post['promo_from_country']) {

                $result['status'] = 0;
                // $result['error_msg'] = 'Invalid Promo Code for Origin Country';
                $result['error_msg'] = "Sorry!! This Promo-Code isn't applicable from the place you're trying to travel from!!";
                $result['step'] = 23;
            } elseif (isset($all_post['promo_from_loc']) && !empty($promo_code['promo_for_city']) && md5($promo_code['promo_for_city']) != $all_post['promo_from_loc']) {

                $result['status'] = 0;
                // $result['error_msg'] = 'Invalid Promo Code for Origin City';
                $result['error_msg'] = "Sorry!! This Promo-Code isn't applicable from the place you're trying to travel from!!";
                $result['step'] = 23;
            } elseif (isset($all_post['promo_to_country']) && !empty($promo_code['to_country']) && md5($promo_code['to_country']) != $all_post['promo_to_country']) {

                $result['status'] = 0;
                $result['error_msg'] = "Sorry!! This Promo-Code isn't applicable for the destination you're trying to travel to!!";
                $result['step'] = 23;
            } elseif (isset($all_post['promo_to_loc']) && !empty($promo_code['promo_to_city']) && md5($promo_code['promo_to_city']) != $all_post['promo_to_loc']) {

                $result['status'] = 0;
                // $result['error_msg'] = 'Invalid Promo Code for Destination City';
                $result['error_msg'] = "Sorry!! This Promo-Code isn't applicable for the destination you're trying to travel to!!";
                $result['step'] = 23;
                // } elseif (isset($all_post['promo_for_city']) && !empty($promo_code['promo_for_city']) && md5($promo_code['promo_for_city']) != $all_post['promo_for_city']) {

                //     $result['status'] = 0;
                //     $result['error_msg'] = 'Invalid Promo Code4';
                //     $result['step'] = 23;
                // } elseif (isset($all_post['promo_to_city']) && !empty($promo_code['promo_to_city']) && $promo_code['module'] == 'flight' && md5($promo_code['promo_to_city']) != $all_post['promo_to_city']) {

                //     $result['status'] = 0;
                //     $result['error_msg'] = 'Invalid Promo Code5';
                //     $result['step'] = 24;
            } else {
                if ($promo_code['module'] == 'car') {
                    $booking_table = 'car_booking_details';
                } elseif ($promo_code['module'] == 'hotel') {
                    $booking_table = 'hotel_booking_details';
                } elseif ($promo_code['module'] == 'flight') {
                    $booking_table = 'flight_booking_details';
                } elseif ($promo_code['module'] == 'activities') {
                    $booking_table = 'sightseeing_booking_details';
                } elseif ($promo_code['module'] == 'transfers') {
                    $booking_table = 'transferv1_booking_details';
                } elseif ($promo_code['module'] == 'bus') {
                    $booking_table = 'bus_booking_details';
                } elseif ($promo_code['module'] == 'holiday') {
                    $booking_table = 'tour_booking_details';
                }
                ###################################################################################
                if (is_logged_in_user()) {
                    $query = "SELECT BD.origin FROM payment_gateway_details AS PGD RIGHT JOIN " . $booking_table . " AS BD ON PGD.app_reference = BD.app_reference WHERE BD.created_by_id='" . $this->entity_user_id . "' ";
                } else {
                    $email = $all_post['email'];
                    $query = "SELECT BD.origin FROM payment_gateway_details AS PGD RIGHT JOIN " . $booking_table . " AS BD ON PGD.app_reference = BD.app_reference WHERE BD.email='" . $email . "' and PGD.status!='pending'";
                }
                ###################################################################################
                $user_promocode_check = $this->Custom_Db->get_result_by_query($query);
                $user_promocode_check = 0;
                if ($user_promocode_check > 0) {
                    $result['status'] = 0;
                    $result['error_msg'] = 'Already used';
                } else {
                    $minimum_amount = get_converted_currency_value($currency_obj->force_currency_conversion($promo_code['minimum_amount']));
                    $total_amount_val_org = str_replace(',', '', $all_post['total_amount_val']);
                    if ($total_amount_val_org > $minimum_amount) {
                        if ($minimum_amount == 0) {
                            if ($total_amount_val_org < 10000) {
                                // $promo_code['value'] = $promo_code['value'] * 1.1;
                            } elseif (10000 <= $total_amount_val_org and $total_amount_val_org < 20000) {
                                $promo_code['value'] = $promo_code['value'] * 1.1;
                            } else {
                                $promo_code['value'] = $promo_code['value'] * 1.5;
                            }
                        }
                        if ($promo_code['value_type'] == 'percentage') {
                            $result['value'] = ($total_amount_val_org * round($promo_code['value'])) / 100;
                            // $result['value'] = $result['value'];
                            $result['actual_value'] = round($result['value']);
                        } else {
                            $result['value'] = $promo_code['value'];
                            $result['actual_value'] = get_converted_currency_value($currency_obj->force_currency_conversion(round($promo_code['value'])));
                            $result['value'] = get_converted_currency_value($currency_obj->force_currency_conversion($result['value']));
                            // $result['value'] = $result['value'];
                        }
                        if ($result['value'] < $total_amount_val_org) {
                            $total_amount_val = ($total_amount_val_org + $all_post['convenience_fee'] + $all_post['convenience_fee_gst']) - $result['value'];
                            if (isset($all_post['extra_baggage'])) {
                                $total_amount_val += $all_post['extra_baggage'];
                            }
                            if (isset($all_post['extra_meal'])) {
                                $total_amount_val += $all_post['extra_meal'];
                            }
                            if (isset($all_post['extra_seat'])) {
                                $total_amount_val += $all_post['extra_seat'];
                            }
                            $total_amount_val = ($total_amount_val > 0) ? $total_amount_val : 0;
                            //$result['total_amount_val'] = number_format($total_amount_val,2);
                            //$result['total_amount_data'] = $all_post['currency_symbol']." ".number_format($total_amount_val, 2);
                            $result['total_amount_val'] = round($total_amount_val);
                            $result['total_amount_data'] = $all_post['currency_symbol'] . " " . round($total_amount_val);
                            $result['convenience_fee'] = $all_post['convenience_fee'];
                            $result['promocode'] = $all_post['promocode'];
                            $result['discount_value'] = $all_post['currency_symbol'] . " " . number_format($result['value'], 2);
                            $result['module'] = $all_post['moduletype'];
                            $result['status'] = 1;
                            //  debug($promo_code);die;
                            /*  $this->custom_db->update_record('promo_code_list',array('used_limit'=>$promo_code['used_limit']+1,'limit'=>$promo_code['limit']-1),array('origin'=>$promo_code['origin']));
                                if($promo_code['limit']-1==0)
                                {
                                    $this->custom_db->update_record('promo_code_list',array('status'=>0),array('origin'=>$promo_code['origin']));
                                }*/
                            $this->custom_db->insert_record('promo_code_doscount_applied', array('discount_value' => $result['actual_value'], 'promocode' => $result['promocode'], 'module' => $result['module'], 'search_key' => provab_encrypt($all_post['booking_key']), 'created_datetime' => date('Y-m-d H:i:s')));
                        } else {
                            $result['status'] = 0;
                            $result['error_msg'] = 'Invalid Promo Code6';
                        }
                    } else {
                        $result['status'] = 0;
                        // $result['error_msg'] = 'Invalid Promo Code7';
                        $result['error_msg'] = 'Sorry!! The Promo-Code can only be applied to flight having minimum amount of Rs.'.round($minimum_amount);
                    }
                }
            }
        } else {
            $result['status'] = 0;
            $result['error_msg'] = 'Sorry!! This is an invalid Promo-Code';
        }
        echo json_encode($result);
    }
	public function promocode_old28Dec2023() {
		$all_post=$this->input->post();
		
		$application_default_currency = admin_base_currency();
		$currency_obj = new Currency ( array ('module_type' => 'flight','from' => admin_base_currency (),'to' => $all_post['currency']));
		
		$condition['promo_code'] = $all_post['promocode'];
		$condition['status'] = 1;
		$promo_code_res=$this->Custom_Db->single_table_records('promo_code_list', '*', $condition );
		//debug($promo_code_res);die;
		//debug(	$promo_code_res);die;
		if($promo_code_res['data'][0]['used_limit']==''){
			$promo_code_res['data'][0]['used_limit']=0;
		}

		if($promo_code_res['status']==1 && $promo_code_res['data'][0]['limit']>$promo_code_res['data'][0]['used_limit'] )
		{
			
			$promo_code=$promo_code_res['data'][0];
			if(md5($promo_code['module'])!=$all_post['moduletype'])
			{

				$result['status']=0;
				$result['error_msg']='Invalid Promo Code';
			}elseif($promo_code['expiry_date']<=date('Y-m-d') && $promo_code['expiry_date']!='0000-00-00'){
				
				$result['status']=0;
				$result['error_msg']='Promo Code Expired';
				
			}elseif(isset($all_post['promo_for_city']) && !empty($promo_code['promo_for_city']) && md5($promo_code['promo_for_city'])!=$all_post['promo_for_city']){
			    
                        $result['status']=0;
                        $result['error_msg']='Invalid Promo Code';
                        $result['step']=23;
                        
            }elseif( isset($all_post['promo_to_city']) && !empty($promo_code['promo_to_city']) && $promo_code['module']=='flight' && md5($promo_code['promo_to_city'])!=$all_post['promo_to_city'] ){
                    
                        $result['status']=0;
                        $result['error_msg']='Invalid Promo Code';
                        $result['step']=24;
			}else{
			
				if($promo_code['module']=='car')
				{
					$booking_table = 'car_booking_details';
					
				}elseif($promo_code['module']=='hotel')
				{
					$booking_table = 'hotel_booking_details';
				}elseif($promo_code['module']=='flight')
				{
					$booking_table = 'flight_booking_details';
				}elseif ($promo_code['module']=='activities') {
					$booking_table = 'sightseeing_booking_details';
				}
				elseif ($promo_code['module']=='transfers') {
					$booking_table = 'transferv1_booking_details';
				}
				elseif ($promo_code['module']=='bus') {
					$booking_table = 'bus_booking_details';
				}
				elseif ($promo_code['module']=='holiday') {
					$booking_table = 'tour_booking_details';
				}
				###################################################################################
				if(is_logged_in_user()){
					$query = "SELECT BD.origin FROM payment_gateway_details AS PGD RIGHT JOIN ".$booking_table." AS BD ON PGD.app_reference = BD.app_reference WHERE BD.created_by_id='".$this->entity_user_id."' ";
				}else{
					$email = $all_post['email'];
					$query = "SELECT BD.origin FROM payment_gateway_details AS PGD RIGHT JOIN ".$booking_table." AS BD ON PGD.app_reference = BD.app_reference WHERE BD.email='".$email."' and PGD.status!='pending'";
					
				}
				###################################################################################
				
				$user_promocode_check=$this->Custom_Db->get_result_by_query($query);
				
				$user_promocode_check = 0;
				if($user_promocode_check > 0){ 
				
					$result['status']=0;
					$result['error_msg']='Already used';
				}else{
					$minimum_amount = get_converted_currency_value($currency_obj->force_currency_conversion($promo_code['minimum_amount']));

					$total_amount_val_org = str_replace(',', '', $all_post['total_amount_val']);
					
					if($total_amount_val_org > $minimum_amount){
						
						if($promo_code['value_type']=='percentage'){
							$result['value']=($total_amount_val_org*round($promo_code['value']))/100;
							
							$result['value'] = $result['value'];
							$result['actual_value']= round($result['value']);
						}else
						{
							$result['value']= $promo_code['value'];
							$result['actual_value']= get_converted_currency_value($currency_obj->force_currency_conversion(round($promo_code['value'])));
							$result['value'] = get_converted_currency_value($currency_obj->force_currency_conversion($result['value']));
							$result['value'] = $result['value'];
						}					
						if($result['value'] < $total_amount_val_org){
							$total_amount_val=($total_amount_val_org+$all_post['convenience_fee']+$all_post['convenience_fee_gst'])-$result['value'];
							
							if(isset($all_post['extra_baggage'])){
								$total_amount_val += $all_post['extra_baggage'];
							}
							if(isset($all_post['extra_meal'])){
								$total_amount_val += $all_post['extra_meal'];
							}		
							if(isset($all_post['extra_seat'])){
								$total_amount_val += $all_post['extra_seat'];
							}
							$total_amount_val=($total_amount_val>0)? $total_amount_val: 0;
							//$result['total_amount_val'] = number_format($total_amount_val,2);
							//$result['total_amount_data'] = $all_post['currency_symbol']." ".number_format($total_amount_val, 2);
							$result['total_amount_val'] = round($total_amount_val);
							
							$result['total_amount_data'] = $all_post['currency_symbol']." ".round($total_amount_val);
							
							$result['convenience_fee']=$all_post['convenience_fee'];
							$result['promocode']=$all_post['promocode'];	
							$result['discount_value']= $all_post['currency_symbol']." ".number_format($result['value'],2);
							$result['module']=$all_post['moduletype'];
							$result['status']=1;
						//	debug($promo_code);die;
						
									/*	$this->custom_db->update_record('promo_code_list',array('used_limit'=>$promo_code['used_limit']+1,'limit'=>$promo_code['limit']-1),array('origin'=>$promo_code['origin']));
								if($promo_code['limit']-1==0)
								{
									$this->custom_db->update_record('promo_code_list',array('status'=>0),array('origin'=>$promo_code['origin']));
								}*/
							
                                                        $this->custom_db->insert_record('promo_code_doscount_applied', array('discount_value' => $result['actual_value'], 'promocode' => $result['promocode'],'module'=>$result['module'],'search_key'=> provab_encrypt($all_post['booking_key']),'created_datetime' => date('Y-m-d H:i:s')));
						}
						else{

							$result['status']= 0;
							$result['error_msg']='Invalid Promo Code';	
							
						}
					
					}
					else{
						
						$result['status']= 0;
						$result['error_msg']='Invalid Promo Code';	
					}
				}

			}
		}
		else{
			$result['status']=0;
			$result['error_msg']='Invalid Promo Code';
		}
		//debug($result);die;
		echo json_encode($result);
	}
	public function promocodebackupfeb2023() {
		$all_post=$this->input->post();
		
		$application_default_currency = admin_base_currency();
		$currency_obj = new Currency ( array ('module_type' => 'flight','from' => admin_base_currency (),'to' => $all_post['currency']));
		
		$condition['promo_code'] = $all_post['promocode'];
		$condition['status'] = 1;
		$promo_code_res=$this->Custom_Db->single_table_records('promo_code_list', '*', $condition );
		//echo $this->db->last_query();
		//debug(	$promo_code_res);die;
		if($promo_code_res['status']==1)
		{
			
			$promo_code=$promo_code_res['data'][0];
			if(md5($promo_code['module'])!=$all_post['moduletype'])
			{

				$result['status']=0;
				$result['error_msg']='Invalid Promo Code';
			}elseif($promo_code['expiry_date']<=date('Y-m-d') && $promo_code['expiry_date']!='0000-00-00'){
				
				$result['status']=0;
				$result['error_msg']='Promo Code Expired';
				
			}elseif(isset($all_post['promo_for_city']) && !empty($promo_code['promo_for_city']) && md5($promo_code['promo_for_city'])!=$all_post['promo_for_city']){
			    
                        $result['status']=0;
                        $result['error_msg']='Invalid Promo Code';
                        $result['step']=23;
                        
            }elseif( isset($all_post['promo_to_city']) && !empty($promo_code['promo_to_city']) && $promo_code['module']=='flight' && md5($promo_code['promo_to_city'])!=$all_post['promo_to_city'] ){
                    
                        $result['status']=0;
                        $result['error_msg']='Invalid Promo Code';
                        $result['step']=24;
			}else{
			
				if($promo_code['module']=='car')
				{
					$booking_table = 'car_booking_details';
					
				}elseif($promo_code['module']=='hotel')
				{
					$booking_table = 'hotel_booking_details';
				}elseif($promo_code['module']=='flight')
				{
					$booking_table = 'flight_booking_details';
				}elseif ($promo_code['module']=='activities') {
					$booking_table = 'sightseeing_booking_details';
				}
				elseif ($promo_code['module']=='transfers') {
					$booking_table = 'transferv1_booking_details';
				}
				elseif ($promo_code['module']=='bus') {
					$booking_table = 'bus_booking_details';
				}
				elseif ($promo_code['module']=='holiday') {
					$booking_table = 'tour_booking_details';
				}
				###################################################################################
				if(is_logged_in_user()){
					$query = "SELECT BD.origin FROM payment_gateway_details AS PGD RIGHT JOIN ".$booking_table." AS BD ON PGD.app_reference = BD.app_reference WHERE BD.created_by_id='".$this->entity_user_id."' ";
				}else{
					$email = $all_post['email'];
					$query = "SELECT BD.origin FROM payment_gateway_details AS PGD RIGHT JOIN ".$booking_table." AS BD ON PGD.app_reference = BD.app_reference WHERE BD.email='".$email."' and PGD.status!='pending'";
					
				}
				###################################################################################
				
				$user_promocode_check=$this->Custom_Db->get_result_by_query($query);
				
				$user_promocode_check = 0;
				if($user_promocode_check > 0){ 
				
					$result['status']=0;
					$result['error_msg']='Already used';
				}else{
					$minimum_amount = get_converted_currency_value($currency_obj->force_currency_conversion($promo_code['minimum_amount']));

					$total_amount_val_org = str_replace(',', '', $all_post['total_amount_val']);
					
					if($total_amount_val_org > $minimum_amount){
						
						if($promo_code['value_type']=='percentage'){
							$result['value']=($total_amount_val_org*round($promo_code['value']))/100;
							
							$result['value'] = $result['value'];
							$result['actual_value']= round($result['value']);
						}else
						{
							$result['value']= $promo_code['value'];
							$result['actual_value']= get_converted_currency_value($currency_obj->force_currency_conversion(round($promo_code['value'])));
							$result['value'] = get_converted_currency_value($currency_obj->force_currency_conversion($result['value']));
							$result['value'] = $result['value'];
						}					
						if($result['value'] < $total_amount_val_org){
							$total_amount_val=($total_amount_val_org+$all_post['convenience_fee']+$all_post['convenience_fee_gst'])-$result['value'];
							
							if(isset($all_post['extra_baggage'])){
								$total_amount_val += $all_post['extra_baggage'];
							}
							if(isset($all_post['extra_meal'])){
								$total_amount_val += $all_post['extra_meal'];
							}		
							if(isset($all_post['extra_seat'])){
								$total_amount_val += $all_post['extra_seat'];
							}
							$total_amount_val=($total_amount_val>0)? $total_amount_val: 0;
							//$result['total_amount_val'] = number_format($total_amount_val,2);
							//$result['total_amount_data'] = $all_post['currency_symbol']." ".number_format($total_amount_val, 2);
							$result['total_amount_val'] = round($total_amount_val);
							
							$result['total_amount_data'] = $all_post['currency_symbol']." ".round($total_amount_val);
							
							$result['convenience_fee']=$all_post['convenience_fee'];
							$result['promocode']=$all_post['promocode'];	
							$result['discount_value']= $all_post['currency_symbol']." ".number_format($result['value'],2);
							$result['module']=$all_post['moduletype'];
							$result['status']=1;
                                                        $this->custom_db->insert_record('promo_code_doscount_applied', array('discount_value' => $result['actual_value'], 'promocode' => $result['promocode'],'module'=>$result['module'],'search_key'=> provab_encrypt($all_post['booking_key']),'created_datetime' => date('Y-m-d H:i:s')));
						}
						else{

							$result['status']= 0;
							$result['error_msg']='Invalid Promo Code';	
							
						}
					
					}
					else{
						
						$result['status']= 0;
						$result['error_msg']='Invalid Promo Code';	
					}
				}

			}
		}
		else{
			$result['status']=$promo_code_res['status'];
			$result['error_msg']='Invalid Promo Code';
		}
		echo json_encode($result);
	}
}