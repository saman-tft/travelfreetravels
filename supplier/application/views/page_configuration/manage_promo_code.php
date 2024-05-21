<?php
//debug($from_data);exit;
/**
* FORM START
 */
$form_configuration['inputs'] = array(
	'origin' 		=> array('type' => 'hidden',   'label_line_code' => -1),
	//'module'	=>	array('type' => 'select','label_line_code' => 227, 'source' => 'db', 'source_location'  => 'module_model::promocode_module_options'),
	'module'	=>	array('type' => 'select','label_line_code' => 227, 'source' => 'db', 'source_location'  => 'module_model::promocode_module_options'),
	'promo_code'	=>	array('type' => 'text','label_line_code' => 228, 'maxlength' => '10'),
	'description'	=>	array('type' => 'textarea','label_line_code' => 233),
	'value_type'	=>	array('type' => 'radio','label_line_code' => 229, 'source' => 'enum', 'source_id' => 'value_type'),
	'value'			=>	array('type' => 'text','label_line_code' => 230, array('class' => 'phone')),
	'minimum_amount'=>	array('type' => 'text','label_line_code' => 252, array('class' => 'phone')),
	'expiry_date'	=>	array('type' => 'text','label_line_code' => 231, 'mandatory' => true,'readonly' => true,'enable' => FUTURE_DATE, 'enable_dp' => true),
	'promo_code_image'	=>	array('type' => 'file','label_line_code' => 253,'mandatory' => false),
	'promo_code_image1'	=>	array('type' => 'hidden','label_line_code' => 253,'mandatory' => false),
	'display_home_page'	=>	array('type' => 'radio','label_line_code' => 254, 'source' => 'enum', 'source_id' => 'display_home_page'),
	'status' 		=>	array('type' => 'radio','label_line_code' => 232, 'source' => 'enum', 'source_id' => 'status')
);
// debug($form_configuration);exit;
/**
 * ADD FORM
 */
$form_configuration['form']['promo_codes_form'] = array(
'form_header' => '',
		'sections' => array(
			array('elements' => array(
					'origin' ,'promo_code', 'description', 'module', 'value_type', 'value', 'minimum_amount', 'expiry_date','promo_code_image','promo_code_image1','display_home_page','status'
				),
				'fieldset' => 'FFL0051'
			)
		),
		'form_footer' => array('submit', 'reset')
	);
	/**
 * EDIT FORM
 */
$form_configuration['form']['promo_codes_form_edit'] = array(
'form_header' => '',
		'sections' => array(
			array('elements' => array(
					'origin' ,'promo_code','description', 'module', 'value_type', 'value', 'minimum_amount', 'expiry_date','promo_code_image','promo_code_image1','display_home_page','status'
				),
				'fieldset' => 'FFL0051'
			)
		),
		'form_footer' => array('update', 'reset')
	);
/*** Form End ***/
// DISABLED FIELDS
$disabled['promo_codes_form_edit'] = array('promo_code');
/**
 * FORM VALIDATION SETTINGS
 */
$auto_validator['origin'] = 'trim|required';
$auto_validator['module'] = 'trim|required';
//$auto_validator['promo_code'] = 'trim|required';
$auto_validator['promo_code'] = 'trim|required|is_unique[promo_code_list.promo_code]';
$auto_validator['description'] = 'trim';
$auto_validator['value_type'] = 'trim|required';
$auto_validator['promo_code_image'] = 'required';
$auto_validator['value'] = 'trim|numeric|required';
$auto_validator['amount'] = 'trim|numeric|required';
$auto_validator['expiry_date'] = 'trim|required';
$auto_validator['status'] = 'trim|required';
// debug($auto_validator);exit;