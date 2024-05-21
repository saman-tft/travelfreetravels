<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loyalty_program extends CI_Controller {
	public function __construct()
	{

		parent::__construct();
		error_reporting(E_ALL);
		$this->load->model('loyalty_program_model');
$this->load->library('rewards');

$this->load->model('transaction');
				
		
	}
	public function redeem_report()
	{
	    		$checkproduct = "SELECT * From loyalty_redeem_request inner join loyalty_product on loyalty_redeem_request.product_id=loyalty_product.id  where loyalty_redeem_request.agent_id=".$this->entity_user_id;
    	$page_data['getproduct']= $this->db->query($checkproduct)->result_array();
    	$this->template->view('product/report', $page_data);
	}
	
	public function referral_report()
	{
	    $page_data=array();
	     $query = "SELECT * From commissions inner join user on user.email=commissions.ref_email where user.email='$this->entity_email'";
	     		$specific_data_list = $this->db->query($query)->result_array();
	    $page_data['users_data'] =$specific_data_list;
	    $this->template->view('product/referral',$page_data);
	}
 public function redeem_product($id,$point)
   {
       error_reporting(0);
       $user_id=$this->entity_user_id;
       $usable_rewards = $this->rewards->usable_rewards($user_id,META_ACCOMODATION_COURSE,$reward_values['usable_reward']);
      $data_rewards = $this->rewards->user_reward_details($user_id);
       if($usable_rewards>=$point)
       {
    	$data=array(
    	    "product_id"=>$id,
    	    "status"=>1,
    	    "created_date"=>date("Y-m-d"),
    	    "agent_id" =>$this->entity_user_id
    	    );
    	    	$pending_rewards = $data_rewards['pending_reward']-$point;
		$used_rewards= $data_rewards['used_reward']+$point;
		$data_upadte_rewards = array(
			'pending_reward'=>round($pending_rewards),
			'used_reward'=>round($used_rewards),

		);
    	$data_upadte_rewards = array(
			'pending_reward'=>round($pending_rewards),
			'used_reward'=>round($used_rewards),

		);
			$data_upadte_rewards_report = array(
			'pending_rewardpoint'=>0,
			'used_rewardpoint'=>$point,
			'reward_earned'=>0,
			'user_id'=>$user_id,
			'module'=>"redeem-reward",
			'book_id'=>'redeem01',
			'created'=>date('Y-m-d h:i:s')
		);
		$this->rewards->update_reward_report_data($user_id,$data_upadte_rewards_report);
		$this->rewards->update_reward_record($user_id,$data_upadte_rewards);
    	$this->custom_db->insert_record('loyalty_redeem_request', $data);
    	$this->template->view('product/receipt');
       }
       else
       {
          	$this->session->set_flashdata(array('message' => 'AL0040', 'type' => SUCCESS_MESSAGE));
          	$page_data=array();
    	$query = "SELECT * From loyalty_product  where status='1'";
    	$checkproduct = "SELECT product_id From loyalty_redeem_request  where agent_id=".$this->entity_user_id;
    	$getproduct= $this->db->query($checkproduct)->result_array();
    	$prod=array();
		$specific_data_list = $this->db->query($query)->result_array();
	    $page_data['product_data'] =$specific_data_list;
	    for($i=0;$i<count($getproduct);$i++)
	    {
	        array_push($prod,$getproduct[$i]['product_id']);
	    }
	    $page_data['checkproduct_data'] =$prod;
	     
	 
        $this->template->view('product/product-list', $page_data);
       }
	     
   }
     function process_reward_points($book_id,$book_origin)
    {
       
        
        $pg_record = $this->transaction->read_payment_record($book_id);
     //   debug($pg_record);die;
        $checkpoints = "SELECT * From wallet_setting  where price=".$pg_record['amount'];
    	$getpoints= $this->db->query($checkpoints)->result_array();
        $this->rewards->update_reward_earned_value($temp_booking,$book_id);
        $data=array(
    	    "transactionid"=>$book_id,
    	    "paymentstatus"=>$pg_record['status'],
    	    "amount"=>$pg_record['amount'],
    	    "earned_rewards"=>$getpoints[0]['reward-points'],
    	    "created_at"=>date("Y-m-d"),
    	    "created_by_id" =>$this->entity_user_id
    	    );
        $user_id=$this->entity_user_id;
        $data_rewards = $this->rewards->user_reward_details($user_id);
        $pending_rewards = $data_rewards['pending_reward']+$getpoints[0]['reward-points'];
		$used_rewards= 0;
    	$data_upadte_rewards = array(
			'pending_reward'=>round($pending_rewards),
			'used_reward'=>round($used_rewards),

		);
		$this->domain_management_model->update_transaction_details('hotel', $book_id, $pg_record['amount'],0, 0, $data['convinence'], 0,$pg_record['currency'], 0);
	    $this->rewards->update_reward_record($user_id,$data_upadte_rewards);
    	$this->custom_db->insert_record('wallet_transaction', $data);
        $this->session->set_flashdata(array('message' => 'UL0010', 'type' => SUCCESS_MESSAGE));
        redirect('loyalty_program/reward_wallet');
    }
    function process_reward_points_faile($book_id,$book_origin)
    {
        $pg_record = $this->transaction->read_payment_record($book_id);
        $data=array(
    	    "transactionid"=>$book_id,
    	    "paymentstatus"=>$pg_record['status'],
    	    "created_date"=>date("Y-m-d"),
    	    "created_by_id" =>$this->entity_user_id
    	    );
    
    	$this->custom_db->insert_record('wallet_transaction', $data);
        $this->session->set_flashdata(array('message' => 'UL0010', 'type' => ERROR_MESSAGE));
        redirect('loyalty_program/reward_wallet');
    }
    function buyrewards()
    {
       
        
        	$this->load->model('transaction');
    	$currency_obj = new Currency ( array (
						'module_type' => 'hotel',
						'from' => admin_base_currency (),
						'to' => admin_base_currency () 
			) );
        $data=$this->input->post();
        $book_id=$this->getGUIDnoHash();
        $book_origin="rewardwallet";
        $verification_amount=$data['rewardpoints_amount'];
        $firsname=$this->entity_first_name;
        $productinfo="rewardwallet";
        $promocode_discount="";
        $convenience_fees="";
        $email=$this->entity_email;
       	$pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
		$this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate,$data['rewardpoints'],$data['rewardpoints_amount'],$data['rewardpoints']);
        //debug($data);die;
        redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin);
    }
    function getGUIDnoHash(){
            mt_srand((double)microtime()*10000);
            $charid = md5(uniqid(rand(), true));
            $c = unpack("C*",$charid);
            $c = implode("",$c);

            return substr($c,0,20);
    }
    public function product()
    {
       $page_data=array();
    	$query = "SELECT * From loyalty_product  where status='1'";
    	$checkproduct = "SELECT product_id From loyalty_redeem_request  where agent_id=".$this->entity_user_id;
    	$getproduct= $this->db->query($checkproduct)->result_array();
    	$prod=array();
		$specific_data_list = $this->db->query($query)->result_array();
	    $page_data['product_data'] =$specific_data_list;
	    for($i=0;$i<count($getproduct);$i++)
	    {
	        array_push($prod,$getproduct[$i]['product_id']);
	    }
	    $page_data['checkproduct_data'] =$prod;
	     
	 
        $this->template->view('product/product-list', $page_data);
    }
    public function productreceipt()
    {
        $post_data[]='';
        $this->template->view('product/receipt', $post_data);
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
	public function reward_wallet()
	{
	    	$page_data ['user'] = $this->custom_db->get_result_by_query("SELECT * FROM user WHERE user_id=".$this->entity_user_id);
	    	$page_data ['user'] = json_decode(json_encode($page_data ['user'][0]),true);
	    	$walletquery = "SELECT * From  wallet_setting";
			$walletquery2= "SELECT * From wallet_transaction  where created_by_id=".$this->entity_user_id;
	$page_data['reward_total_report'] = $this->rewards->get_total_reward_report($this->entity_user_id);
		$specific_data_list1 = $this->db->query($walletquery)->result_array();
			$specific_data_list2 = $this->db->query($walletquery2)->result_array();
	$page_data['wallet_settings'] =$specific_data_list1;
	$page_data['wallet_report'] =$specific_data_list2;
		$this->template->view('product/rewardwallet', $page_data);
	}
	public function redeem_reward_point()
	{
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
