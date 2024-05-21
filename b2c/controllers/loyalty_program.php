<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loyalty_program extends CI_Controller {
	public function __construct()
	{

		parent::__construct();
		//error_reporting(E_ALL);
		$this->load->model('loyalty_program_model');

		
		
	}

	public function ajax_total_reward(){
		error_reporting(E_ALL);
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		// $loyalty_program_on_off = $GLOBALS['CI']->loyalty_program_on_off;
		 // echo $loyalty_program_on_off;exit;
		$result=$this->loyalty_program_model->get_reward_point($current_user_id);
		if($result)
		{
			echo $result['t_reward'];

		}
		else
		{
			echo "0";
		}
	}
	public function test(){
		echo $GLOBALS['CI']->loyalty_program_on_off;
		// echo add_months_to_date(1);
	}
	public function redeem_reward_point()
	{
		error_reporting(0);
		$this->load->model('loyalty_program_model');
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		// echo $current_user_id;exit;
		$page_data['total_point']=$this->loyalty_program_model->get_agent_point($current_user_id);


			$this->template->view ( 'management/b2b_reward_redeem_page', $page_data );
		
	}
	public function get_redeem_amout()
	{
		$post_data=$this->input->post();
		$module_conversion_value=$this->loyalty_program_model->module_conversion_value($post_data['module_type']);
		$agent_base_currency =agent_base_currency();


		// echo $agent_base_currency;exit;

		if($agent_base_currency=='AED')
		{
			// echo "j";exit;
			echo $module_conversion_value;
		}
		else
		{
			$currency_obj_m = new Currency(array('module_type' => $post_data['module_type'], 'from' => 'AED', 'to' => $agent_base_currency));
			$strCurrency_m  = $currency_obj_m->get_currency($module_conversion_value, true, false, true, false, 1);
			echo $strCurrency_m['default_value'];
		}

		// debug($agent_base_currency);
	}
	public function get_product_list()
	{

		$entity_country_code = $GLOBALS['CI']->entity_country_code;
		// debug($entity_country_code);exit;
		$post_data=$this->input->post();
		$product_list=$this->loyalty_program_model->get_product_list($post_data['total_reward'],$entity_country_code);
		// $page_data['total_point']=$post_data['total_reward'];

		$response['data'] = get_compressed_output($this->template->isolated_view('management/show_product_redeem_page',
						array('product_list' => $product_list, 'total_point' => $post_data['total_reward']
						)));
		echo $response['data'];
	}
	public function redeem_request(){
		$post_data=$this->input->post();
		// debug($post_data);  
		$pro_id=array();
		$total_product_point=0;
		$user_redeem_point=0;
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		// echo $current_user_id;exit;
		$total_point=$this->loyalty_program_model->get_agent_point($current_user_id);
		$user_redeem_point=$total_point['t_redeem'];
		if($post_data['product_id']){

			foreach ($post_data['product_id'] as $key => $value) {
				$product_details=$this->loyalty_program_model->get_product_details($value);
				$total_product_point +=$product_details['point'];
			}

			if($total_product_point <=$user_redeem_point)
			{
				$dd=array('agent_id'=>$current_user_id,'product_id'=>json_encode($post_data['product_id']));
				$this->db->insert('loyalty_redeem_request',$dd);
				$t_reward=$user_redeem_point-$total_product_point;
				$pending_amt=$total_product_point;
				$dt=array('t_redeem'=>$t_reward);
				$pendingvalue=0;
				if($total_point['rhotel'] >0)
				{
					if($total_product_point >$total_point['rhotel'])
					{

						$pending_amt=$total_product_point-$total_point['rhotel'];
						$dt['rhotel']=0;
					}
					else
					{
						$hvalue=$total_point['rhotel']-$total_product_point;
						$dt['rhotel']=$hvalue;
						$pending_amt=0;

					}

					
				}
				if($pending_amt >0){
					if($total_point['rholiday'] >0)
					{
						if($pending_amt >$total_point['rholiday'])
						{

							$pending_amt=$pending_amt-$total_point['rholiday'];
							$dt['rholiday']=0;
						}
						else
						{
							$hvalue=$total_point['rholiday']-$pending_amt;
							$dt['rholiday']=$hvalue;
							$pending_amt=0;

						}

						
					}
				}
				if($pending_amt >0){
					if($total_point['ractivities'] >0)
					{
						if($pending_amt >$total_point['ractivities'])
						{

							$pending_amt=$pending_amt-$total_point['ractivities'];
							$dt['ractivities']=0;
						}
						else
						{
							$hvalue=$total_point['ractivities']-$pending_amt;
							$dt['ractivities']=$hvalue;
							$pending_amt=0;

						}

						
					}
				}
				if($pending_amt >0){
					if($total_point['rtransfer'] >0)
					{
						if($pending_amt >$total_point['rtransfer'])
						{

							$pending_amt=$pending_amt-$total_point['rtransfer'];
							$dt['rtransfer']=0;
						}
						else
						{
							$hvalue=$total_point['rtransfer']-$pending_amt;
							$dt['rtransfer']=$hvalue;
							$pending_amt=0;

						}

						
					}
				}
				if($pending_amt >0){
					if($total_point['rvisa'] >0)
					{
						if($pending_amt >$total_point['rvisa'])
						{

							$pending_amt=$pending_amt-$total_point['rvisa'];
							$dt['rvisa']=0;
						}
						else
						{
							$hvalue=$total_point['rvisa']-$pending_amt;
							$dt['rvisa']=$hvalue;
							$pending_amt=0;

						}

						
					}
				}

				$this->db->where('agent_id',$current_user_id);
				$this->db->update('reward_point_total',$dt);
				redirect ('loyalty_program/redeem_reward_point');
			}
			else
			{
				redirect ('loyalty_program/redeem_reward_point');
			}
		}
		else
		{
				redirect ('loyalty_program/redeem_reward_point');
		}
	}
	

	
}
