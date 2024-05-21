<?php

/**
 * FORM START
 */
$form_configuration['inputs'] = array(
	'user_id' 		   => array('type' => 'hidden',   'label_line_code' => -1, 'DT' => 'PROVAB_SOLID_I10'),
	'email' 		   => array('type' => 'email',    'label_line_code' => 6, 'DT' => 'PROVAB_SOLID_V80'),
	'password' 		   => array('type' => 'password', 'label_line_code' => 2, 'DT' => 'PROVAB_SOLID_V45'),
	'confirm_password' => array('type' => 'password', 'label_line_code' => 14, 'DT' => 'PROVAB_SOLID_V45'),
	'status' 		   => array('type' => 'radio', 	  'label_line_code' => 21, 'source' => 'enum', 'source_id' => 'status', 'DT' => 'PROVAB_SOLID_B01'),
	'date_of_birth'   => array('type' => 'text',     'label_line_code' => 15, 'mandatory' => false, 'readonly' => true, 'enable' => ADULT_DATE_PICKER, 'DT' => 'PROVAB_SOLID_DATE', 'enable_dp' => true),
	'title'			   => array('type' => 'select',   'label_line_code' => 16, 'source' => 'enum', 'source_id' => 'title', 'DT' => 'PROVAB_SOLID_SB01'),
	'language_preference' => array('type' => 'hidden',   'label_line_code' => 33, 'mandatory' => false, 'source' => 'enum', 'source_id' => 'language_preference'),
	'first_name' 	   => array('type' => 'text',     'label_line_code' => 4, 'DT' => 'PROVAB_SOLID_V45'),
	'last_name' 	   => array('type' => 'text',     'label_line_code' => 5, 'DT' => 'PROVAB_SOLID_V45'),
	'agency_name' 	   => array('type' => 'text',     'label_line_code' => 214, 'mandatory' => true, 'DT' => 'PROVAB_SOLID_V45'),
	'pan_number' 	   => array('type' => 'text',     'label_line_code' => 226, 'mandatory' => true, 'maxlength' => 10),
	'address' 		   => array('type' => 'textarea', 'label_line_code' => 17, 'DT' => 'PROVAB_SOLID_V255'),
	'country_code' 	   => array('type' => 'select',   'label_line_code' => 19, 'source' => 'db', 'source_location'  => 'db_cache_api::get_postal_code_list', 'DT' => 'PROVAB_SOLID_SB03'),
	'country_name' 	   => array('type' => 'select', 'label_line_code' => 213,'source'=>'db','source_location'=>'db_cache_api::get_country_list','mandatory' => true),
	'city'			   => array('type' => 'select','label_line_code'=>244,'mandatory'=>true),
	'phone' 		   => array('type' => 'text',   'label_line_code' => 20, 'DT' => 'PROVAB_SOLID_I10','maxlength' => 10),	
	'office_phone'	   => array('type' => 'number',   'label_line_code' => 216, 'DT' => 'PROVAB_SOLID_I10'),
	'image' 		   => array('type' => 'hidden',     'label_line_code' => -1, 'mandatory' => false ),
	'user_type' 	   => array('type' => 'hidden',   'label_line_code' => -1)
	//'user_type' 	   => array('type' => 'hidden',   'label_line_code' => 34, 'source' => 'db', 'source_location'  => 'db_cache_api::get_user_type')
);

/**
 * Add FORM
 */
$form_attributes = array('method' => 'POST', 'action' => '');
$form_configuration['form']['b2b_user'] = array(
'form_header' => $form_attributes,
		'sections' => array(
			array('elements' => array('agency_name','pan_number','office_phone','image'),
					'fieldset' => 'FFL0049'
			),
			array('elements' => array('user_id', 'title', 'first_name', 'last_name', 'date_of_birth', 'country_code', 'phone','country_name', 'city','address', 'status', 'language_preference'),
				'fieldset' => 'FFL003'
			),
			array('elements' => array('user_type', 'email', 'password', 'confirm_password'),
				'fieldset' => 'FFL002'
			)
		),
		'form_footer' => array('submit', 'reset')
	);
/**
 * Update FORM
 */
$form_configuration['form']['b2b_user_edit'] = array(
		'form_header' => $form_attributes,
		'sections' => array(
			array('elements' => array('agency_name', 'pan_number','office_phone','image'),
					'fieldset' => 'FFL0049'
			),
			array('elements' => array('user_id', 'title', 'first_name', 'last_name', 'date_of_birth', 'country_code', 'phone','country_name','city','address', 'status', 'language_preference'),
				'fieldset' => 'FFL003'
			),
			array('elements' => array('user_type', 'email'),
				'fieldset' => 'FFL002'
			)
		),
		'form_footer' => array('update', 'reset')
	);

/*** Form End ***/
// LETS CLEAN DISABLED LABEL DATA
/**
* adding to disabled and email make sure that no validation is done for update
*/
$disabled['b2b_user_edit'] = array('email');

/*** Form End ***/
/**
 * FORM VALIDATION SETTINGS
 */
$auto_validator['title'] = 'trim|required|min_length[1]|max_length[4]';
$auto_validator['first_name'] = 'trim|required|min_length[2]|max_length[45]|xss_clean';
$auto_validator['last_name'] = 'trim|required|min_length[1]|max_length[45]|xss_clean';
$auto_validator['country_code'] = 'trim|required|min_length[1]|max_length[3]';
$auto_validator['phone'] = 'trim|required|min_length[7]|max_length[10]|numeric';
$auto_validator['address'] = 'trim|required|min_length[5]|max_length[500]|xss_clean';
$auto_validator['email'] = 'trim|required|valid_email|min_length[5]|max_length[45]|is_unique[user.email]|xss_clean';
$auto_validator['password'] = 'trim|required|min_length[5]|max_length[80]|matches[confirm_password]';
$auto_validator['confirm_password'] = 'trim|required';
$auto_validator['agency_name'] = 'trim|required|min_length[2]|max_length[45]';
$auto_validator['agent_name'] = 'trim|required|min_length[2]|max_length[45]';
$auto_validator['office_phone'] = 'required';
$auto_validator['status'] = 'trim|required|min_length[1]|max_length[2]|numeric';
$auto_validator['language_preference'] = 'trim';
$auto_validator['date_of_birth'] = 'trim|min_length[5]|xss_clean';
$auto_validator['user_id'] = 'trim|min_length[1]|max_length[10]|numeric';
$auto_validator['user_type'] = 'trim|required|min_length[1]|max_length[3]|numeric';
$auto_validator['country_name'] = 'trim|required';
$auto_validator['city'] = 'trim|required';
