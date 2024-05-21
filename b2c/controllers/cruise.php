<?php
if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
error_reporting(0);

class Cruise extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        
        $this->load->model("user_model"); // we need to load user model to access provab sms library
       
        
    }
    function index(){
        $this->template->view('cruise/index');
    }
    /**
     * Search Result
     *
     * @param number $search_id
     */
    function search()
    {
        $this->template->view('cruise/search_result_page');
    }
    function cruise_detail()
    {
        $this->template->view('cruise/cruise_detail_page');
    }
}