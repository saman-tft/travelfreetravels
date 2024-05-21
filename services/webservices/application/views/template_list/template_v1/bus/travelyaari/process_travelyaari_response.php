<?php
//--------------------------------------------------------------------------------------------------------------------

/**
 * get bus type based on params code
 * @param string $type
 */
function get_bus_type($HasAC, $HasNAC, $HasSeater, $HasSleeper, $IsVolvo)
{
	$bus_type = '';
	if (empty($IsVolvo) == false && $IsVolvo != 'false') {
		$bus_type .= '<span class="VOLVO bus-type">VOLVO</span> ';
	}

	if (empty($HasAC) == false && $HasAC != 'false') {
		$bus_type .= '<span class="AC bus-type">AC</span> ';
	}

	if (empty($HasNAC) == false && $HasNAC != 'false') {
		$bus_type .= '<span class="NON_AC bus-type">NON_AC</span> ';
	}

	if (empty($HasSleeper) == false && $HasSleeper != 'false') {
		$bus_type .= '<span class="SLEEPER bus-type">SLEEPER</span> ';
	}

	if (empty($HasSeater) == false && $HasSeater != 'false') {
		$bus_type .= '<span class="SEATER bus-type">SEATER</span> ';
	}
	return substr($bus_type, 0, -1);
}

function get_bus_type_count($HasAC, $HasNAC, $HasSeater, $HasSleeper, $IsVolvo)
{
	$count = 0;
	foreach (func_get_args() as $k => $v) {
		if ($v == 'true') {
			$count++;
		}
	}
	return $count;
}