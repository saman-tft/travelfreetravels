<?php

/**
 * FORM START
 */
$form_configuration ['inputs'] = array (
		'user_id' => array (
				'type' => 'hidden',
				'label_line_code' => - 1,
				'DT' => 'PROVAB_SOLID_I10' 
		),
		'uuid' => array (
				'type' => 'hidden',
				'label_line_code' => - 1
		),
		'email' => array (
				'type' => 'email',
				'label_line_code' => 6,
				'DT' => 'PROVAB_SOLID_V80' 
		),
		'password' => array (
				'type' => 'password',
				'label_line_code' => 2,
				'DT' => 'PROVAB_SOLID_V45' 
		),
		'confirm_password' => array (
				'type' => 'password',
				'label_line_code' => 14,
				'DT' => 'PROVAB_SOLID_V45' 
		),
		'status' => array (
				'type' => 'radio',
				'label_line_code' => 21,
				'source' => 'enum',
				'source_id' => 'status',
				'DT' => 'PROVAB_SOLID_B01' 
		),
		/*'date_of_birth' => array (
				'type' => 'text',
				'label_line_code' => 15,
				'mandatory' => false,
				'readonly' => true,
				'enable' => PAST_DATE,
				'DT' => 'PROVAB_SOLID_DATE',
				'enable_dp' => true 
		),*/
		'title' => array (
				'type' => 'select',
				'label_line_code' => 16,
				'source' => 'enum',
				'source_id' => 'title',
				'DT' => 'PROVAB_SOLID_SB01' 
		),
		'language_preference' => array (
				'type' => 'hidden',
				'label_line_code' => 33,
				'mandatory' => false,
				'source' => 'enum',
				'source_id' => 'language_preference' 
		),
		'first_name' => array (
				'type' => 'text',
				'label_line_code' => 4,
				'DT' => 'PROVAB_SOLID_V45' 
		),
		'last_name' => array (
				'type' => 'text',
				'label_line_code' => 5,
				'DT' => 'PROVAB_SOLID_V45' 
		),
		'agency_name' => array (
				'type' => 'text',
				'label_line_code' => 214,
				'mandatory' => true,
				'DT' => 'PROVAB_SOLID_V45' 
		),
		'address' => array (
				'type' => 'textarea',
				'label_line_code' => 17,
				'DT' => 'PROVAB_SOLID_V255' 
		),
		'address2' => array (
				'type' => 'text',
				'label_line_code' => 17,
				'DT' => 'PROVAB_SOLID_V255',
				'mandatory' => false 
		),
		'country_code' => array (
				'type' => 'select',
				'label_line_code' => 19,
				'source' => 'db',
				'source_location' => 'db_cache_api::get_country_code_list',
				'DT' => 'PROVAB_SOLID_SB03' 
		),
		'country_name' => array (
				'type' => 'text',
				'label_line_code' => 213,
				'mandatory' => false,
				'readonly' => false 
		),
		'state_name' => array (
				'type' => 'text',
				'label_line_code' => 239,
				'mandatory' => true,
				'readonly' => false
		),
		'city_name' => array (
				'type' => 'text',
				'label_line_code' => 18,
				'mandatory' => true,
				'readonly' => false
		),
		'pin_code' => array (
				'type' => 'text',
				'label_line_code' => 240,
				'mandatory' => true,
				'maxlength' => 6
		),
		'phone' => array (
				'type' => 'text',
				'label_line_code' => 20,
				'DT' => 'PROVAB_SOLID_I10',
				'maxlength' => 15,
				'minlength' => 10,
				'mandatory' => true
		),
		'office_phone' => array (
				'type' => 'text',
				'maxlength' => 15,
				'label_line_code' => 216,
				'DT' => 'PROVAB_SOLID_I10'
		),
		'image' => array (
				'type' => 'hidden',
				'label_line_code' => - 1,
				'mandatory' => false 
		),
		'user_type' => array (
				'type' => 'hidden',
				'label_line_code' => - 1 
		),
	'company_reg_id' 		   => array('type' => 'text',    'label_line_code' => 259, 'DT' => 'PROVAB_SOLID_V80'),
	'travel_licence_no' 		   => array('type' => 'text',    'label_line_code' => 260, 'DT' => 'PROVAB_SOLID_V80'),
	'postal_code' 		   => array('type' => 'text',    'label_line_code' => 261, 'DT' => 'PROVAB_SOLID_V80'),
	'comp_website_link' 		   => array('type' => 'text',    'label_line_code' => 262, 'DT' => 'PROVAB_SOLID_V80'),
	'comp_email' 		   => array('type' => 'text',    'label_line_code' => 263, 'DT' => 'PROVAB_SOLID_V80'),
		'group_fk' => array (
				'type' => 'select',
				'label_line_code' => 238,
				'source' => 'db',
				'source_location' => 'db_cache_api::get_group_list',
				'DT' => 'PROVAB_SOLID_SB03',
				'mandatory' => true 
		),
		'prev_group_fk' => array (
				'type' => 'hidden',
				'label_line_code' => - 1,
				'mandatory' => true 
		),
		'iata_code' => array (
				'type' => 'text',
				'label_line_code' => 243,
				'DT' => 'PROVAB_SOLID_SB03' ,
				'mandatory' => false
		),
		'user_type' => array('type' => 'checkbox', 'label_line_code' => 258, 'source' => 'db', 'source_location' => 'db_cache_api::get_crs_type','mandatory' => false) 
)

;

/**
 * Add FORM
 */
$form_attributes = array (
		'method' => 'POST',
		'action' => '' 
);
$form_configuration ['form'] ['b2as_user'] = array (
		'form_header' => $form_attributes,
		'sections' => array (
				// array (
				// 		'elements' => array (
				// 				'agency_name',
				// 				'group_fk',
				// 				'iata_code',
				// 				'office_phone',
				// 				'image' 
				// 		),
				// 		'fieldset' => 'FFL0049' 
				// ),
				array ( 
						'elements' => array (
								'user_id',
								'title',
								'first_name',
								'last_name',
								/*'date_of_birth',*/
								'country_code',
								'phone',								
								'address',
								// 'address2',
								'city_name',
								'state_name',
								'country_name',
								'pin_code',
								'status',
								'language_preference' 
						),
						'fieldset' => 'FFL003' 
				),
				array (
						'elements' => array (
								'user_type',
								'email',
								'password',
								'confirm_password' 
						),
						'fieldset' => 'FFL002' 
				) 
		),
		'form_footer' => array (
				'submit',
				'reset' 
		) 
);
/**
 * Update FORM
 */
$form_configuration ['form'] ['b2as_user_edit'] = array (
		'form_header' => $form_attributes,
		'sections' => array (
				// array (
				// 		'elements' => array (
				// 				'agency_name',
				// 				'group_fk',
				// 				'prev_group_fk',
				// 				'iata_code',
				// 				'office_phone',
				// 				'image' 
				// 		),
				// 		'fieldset' => 'FFL0049' 
				// ),
				array (
						'elements' => array (
								'user_id',
								'uuid',
								'title',
								'first_name',
								'last_name',
								/*'date_of_birth',*/
								'country_code',
								'phone',								
								'address',
								
								'city_name',
								'state_name',
								'country_name', 
								'company_reg_id','travel_licence_no','postal_code','comp_website_link','comp_email', 
								'status',
								'language_preference'
								
						),
						'fieldset' => 'FFL003' 
				),
				array (
						'elements' => array (
								'user_type',
								'email' 
						),
						'fieldset' => 'FFL002' 
				) 
		),
		'form_footer' => array (
				'submit',
				'reset' 
		) 
);

/**
 * * Form End **
 */
// LETS CLEAN DISABLED LABEL DATA
/**
 * adding to disabled and email make sure that no validation is done for update
 */
$disabled ['b2as_user_edit'] = array (
		'email',
		'country_name'
);

/**
 * * Form End **
 */
/**
 * FORM VALIDATION SETTINGS
 */
$auto_validator ['title'] = 'trim|required|min_length[1]|max_length[4]';
$auto_validator ['first_name'] = 'trim|required|min_length[2]|max_length[45]|xss_clean';
$auto_validator ['last_name'] = 'trim|required|min_length[1]|max_length[45]|xss_clean';
$auto_validator ['country_code'] = 'trim|required|min_length[1]|max_length[3]';
$auto_validator ['phone'] = 'trim|required|callback_phone_check['.ACTIVITY_SUPPLIER.']';
$auto_validator ['address'] = 'trim|required|min_length[5]|max_length[500]|xss_clean';
$auto_validator ['email'] = 'trim|required|valid_email|min_length[5]|max_length[80]|xss_clean|callback_username_check['.ACTIVITY_SUPPLIER.']';
$auto_validator ['password'] = 'trim|required|min_length[5]|max_length[80]|matches[confirm_password]';
$auto_validator ['confirm_password'] = 'trim|required';
$auto_validator ['agency_name'] = 'trim|required|min_length[2]|max_length[150]';
$auto_validator ['agent_name'] = 'trim|required|min_length[2]|max_length[80]';
$auto_validator ['office_phone'] = 'required';
$auto_validator ['status'] = 'trim|required|min_length[1]|max_length[2]|numeric';
$auto_validator ['language_preference'] = 'trim';
/*$auto_validator ['date_of_birth'] = 'trim|min_length[5]|xss_clean';*/
$auto_validator ['user_id'] = 'trim|min_length[1]|max_length[10]|numeric';
$auto_validator ['user_type'] = 'trim|required|min_length[1]|max_length[3]|numeric';
$auto_validator ['group_fk'] = 'trim|required|min_length[1]|max_length[3]|numeric';
$auto_validator ['prev_group_fk'] = 'trim|required|min_length[1]|max_length[3]|numeric';
$auto_validator ['iata_code'] = 'trim|exact_length[10]|numeric';
