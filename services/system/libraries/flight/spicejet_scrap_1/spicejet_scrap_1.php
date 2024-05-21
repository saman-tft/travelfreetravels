<?php
require_once BASEPATH . 'libraries/flight/spicejet_scrap.php';
class Spicejet_scrap_1 extends Spicejet_scrap {
	
	protected $operator = 'Spicejet';
	protected $source_code = SPICEJET_SCRAP;
	protected $carrier_code = 'SG1';
	protected $AGPKey;
	protected $QPKey;
	protected $ReportsKey;
	function __construct() {
		parent::__construct ( $this->source_code );
	}
}