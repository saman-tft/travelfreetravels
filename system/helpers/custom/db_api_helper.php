<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * custom converter for form generator
 * @param array $from array('k' => 'id', 'v' => 'name')
 * @param array $condition
 *
 * @return array in following format array(array('k' => array('first_name', 'last_name'), 'v' => 'Name'), array('k' => 'email', 'v' => 'Email'))
 */
function magical_converter($from, $data)
{
	$cache_data = array();
	if (isset($data['data'])) {
		if (valid_array($data['data'])  == true) {
			foreach ($data['data'] as $k => $v) {
				$temp_k = $temp_v = '';
				if (is_string($from['k'])) {
					$temp_k = $v[$from['k']];
				} else { 				foreach ($from['v'] as $k_ik => $k_iv) {
					$temp_k .= $v[$k_iv].' ';
				}
				}
				if (is_string($from['v'])) {
					$temp_v = $v[$from['v']];
				} else {
					foreach ($from['v'] as $v_ik => $v_iv) {
						$temp_v .= $v[$v_iv].' ';
					}
				}
				$cache_data[$temp_k] = $temp_v;
			}
		}
	}
	return $cache_data;
}

function magical_converter_b2b($from, $data)
{
	$cache_data = array();
	if (isset($data['data'])) {
		if (valid_array($data['data'])  == true) {
			foreach ($data['data'] as $k => $v) {
				$temp_k = $temp_v = '';
				if (is_string($from['k'])) {
					$temp_k = $v[$from['k']];
				} else { 				foreach ($from['v'] as $k_ik => $k_iv) {
					$temp_k .= $v[$k_iv].' ';
				}
				}
				if (is_string($from['v'])) {
					$temp_v = $v[$from['v']].'-'.provab_decrypt($v['uuid']);
				} else {
					foreach ($from['v'] as $v_ik => $v_iv) {
						$temp_v .= $v[$v_iv].'-'.provab_decrypt($v['uuid']);
					}
				}
				// $cache_data[$temp_k] = $temp_v - provab_decrypt($v['uuid']);
				$cache_data[$temp_k] = $temp_v;
			}
		}
	}
	return $cache_data;
}