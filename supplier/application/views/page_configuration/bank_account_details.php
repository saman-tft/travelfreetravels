<?php
/**
 * FORM INPUT ELEMENTS
 */
$form_configuration['inputs'] =array(
    'origin' => array('type' => 'hidden',  'label_line_code' => -1),
    'en_account_name' => array('type' => 'text', 'label_line_code' => 218),
	'en_bank_name' => array('type' => 'text',  'label_line_code' =>220),
	'en_branch_name' => array('type' => 'text',  'label_line_code' =>225),
	'iben_number' => array('type' => 'number',  'label_line_code' =>264),
	'sort_code' => array('type' => 'number',  'label_line_code' =>265),
	'swift_code' => array('type' => 'number',  'label_line_code' =>267),
	
    'account_number' => array('type' => 'number',  'label_line_code' => 219, 'class' => array('numeric')),
    
    //'ifsc_code' => array('type' => 'text',  'label_line_code' => 223),
    'beneficiary_name' => array('type' => 'text',  'label_line_code' => 268),
    //'pan_number' => array('type' => 'text',  'label_line_code' => 224),
	'bank_icon' => array('type' => 'file',  'label_line_code' => 221, 'mandatory' => false),
	'country_name' 	   => array('type' => 'select', 'label_line_code' => 213,'source'=>'db','source_location'=>'db_cache_api::get_country_list','mandatory' => true),
	'city'			   => array('type' => 'select','label_line_code'=>244,'mandatory'=>true),
	'address' 		   => array('type' => 'textarea', 'label_line_code' => 17, 'DT' => 'PROVAB_SOLID_V255'),
    'status'	  => array('type' => 'radio', 	 'label_line_code' => 222, 'source' => 'enum', 'source_id' => 'status'));
/**
 * FORM ATTRIBUTES AND SECTIONS
 */
$form_attributes = array('method' => 'POST', 'action' => '');
$form_configuration['form']['bank_account_details'] = array(
	'form_header' => $form_attributes,
	'sections' => array(array('elements' => array('origin','en_account_name','iben_number','sort_code','swift_code', 'account_number', 'beneficiary_name',  'en_bank_name', 'en_branch_name', 'bank_icon','country_name','city','address', 'status'), 'fieldset' => 'FFL0050')),
	'form_footer' => array(array('type' => 'submit', 'lang_line_code' => 13), 'reset')
);
$form_configuration['form']['bank_account_details_edit'] = array(
	'form_header' => $form_attributes,
	'sections' => array(array('elements' => array('origin','en_account_name','iben_number','sort_code','swift_code', 'account_number','beneficiary_name',  'en_bank_name', 'en_branch_name', 'bank_icon','country_name','city','address', 'status'), 'fieldset' => 'FFL0050')),
	'form_footer' => array(array('type' => 'submit', 'lang_line_code' => 13), 'reset')
);
/**
 * FORM VALIDATION SETTINGS
 */
$auto_validator['origin'] = 'trim|min_length[1]|numeric';
$auto_validator['en_account_name'] = 'trim|required|min_length[3]|max_length[100]';
$auto_validator['en_bank_name'] = 'trim|required|min_length[3]|max_length[80]';
$auto_validator['account_number'] = 'trim|required|min_length[10]|numeric';
$auto_validator['status'] = 'trim|required|min_length[1]|max_length[1]';
$auto_validator['ifsc_code'] = 'trim|required|min_length[3]';
$auto_validator['pan_number'] = 'trim|required|min_length[3]';

