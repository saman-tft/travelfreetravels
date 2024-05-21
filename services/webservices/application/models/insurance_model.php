<?php

/**
 * Library which has generic functions to get data
 *
 * @package    Provab Application
 * @subpackage Car Model
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */
Class Insurance_Model extends CI_Model {

    function save_search_data($request) {
        $data['status'] = SUCCESS_STATUS;
        $cache_key = $this->redis_server->generate_cache_key();

        $search_history_data = array();
        $search_history_data['domain_origin'] = get_domain_auth_id();
        $search_history_data['cache_key'] = $cache_key;
        $search_history_data['search_type'] = META_INSURANCE_COURSE;
        $search_history_data['search_data'] = json_encode($request);
        $search_history_data['created_datetime'] = db_current_datetime();
        $insert_data = $this->custom_db->insert_record('search_history', $search_history_data);
        if ($insert_data['status'] == QUERY_SUCCESS) {
            $data['cache_key'] = $cache_key;
            $data['search_id'] = $insert_data['insert_id'];
        } else {
            $data['status'] = FAILURE_STATUS;
        }
        return $data;
    }

}
