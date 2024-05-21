<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------
/**
 * Stratosphere
 *
 * A Travel portal
 *
 * @package		custom validation helper
 * @author		Arjun<arjunjgowda260389@gmail.com>
 * @copyright	provab
 * @link		http://www.provab.com
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------


/**
*check if the user has entered valid number
*
*@param number $number number which has to be validated
*
*@return status of validation
*/
function positive_number($number) {
	$status = '';
	if ($number >= 0) {
		$status = true;
	} else {
		$status = false;
	}
	return $status;
}


/**
*check if the user has entered valid number
*
*@param number $number number which has to be validated
*
*@return status of validation
*/
function valid_integer($number) {
	$status = '';
	if (positive_number($number)) {
		$status = true;
	} else {
		redirect('general/redirect_login');
	}
	return $status;
}

/**
*check if the valid array
*
*@param array $array array which has to be validated
*
*@return status of validation
*/
function valid_array($array='') {
	$status = '';
	if (is_array($array) == true and count($array) > 0) {
		$status = true;
	} else {
		$status = false;
	}
	return $status;
}

/*AES Encryption method */
function credit_encrypt($string){
    #echo $string.'<br/>';
    $CI = & get_instance ();
    $output = false;
    $encrypt_method = "AES-256-CBC";    
    $enc_password =trim(CREDIT_ENC_KEY);// stored in config file with encryption method 
    $md5_sec_key = trim(CREDIT_MD5_SECRET);
    $decrypt_password = $CI->db->query("SELECT AES_DECRYPT($enc_password,SHA2('".$md5_sec_key."',512)) AS decrypt_data");
    $db_data = $decrypt_password->row();
     $secret_iv = trim(CREDIT_SECRET_IV);
    $secret_key = trim($db_data->decrypt_data); 
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($output);
    
    return $output;

}
/*AES Decryption method*/
function credit_decrypt($string){

    $CI = & get_instance ();
    $output = false;
    $encrypt_method = "AES-256-CBC";   
    $enc_password =trim(CREDIT_ENC_KEY);// stored in config file with encryption method 
    $md5_sec_key = trim(CREDIT_MD5_SECRET);
    $decrypt_password = $CI->db->query("SELECT AES_DECRYPT($enc_password,SHA2('".$md5_sec_key."',512)) AS decrypt_data");
    $db_data = $decrypt_password->row();
    $secret_key = trim($db_data->decrypt_data); 
    $secret_iv = trim(CREDIT_SECRET_IV);
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);       
    return $output;
}