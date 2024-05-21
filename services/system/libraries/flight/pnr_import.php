<?php
Class Pnr_Import {

    function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library('multi_curl');
    }

    function get_tbo_pnr_details() {
        echo "get_tbo_pnr_details";
    }

    function get_mystifly_pnr_details() {
        echo "get_tbo_pnr_details";
    }

    function get_goair_pnr_details() {
        echo "get_tbo_pnr_details";
    }

    function get_travelport_pnr_details() {
        echo "get_tbo_pnr_details";
    }

}
