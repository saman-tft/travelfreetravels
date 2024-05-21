<?php
class ServiceLocator {
	protected static $instance;
	protected $services = array();
	protected $contextualBindings = array();

	private function __construct() {}

	public static function getInstance() {
    	if (self::$instance === null) {
        	self::$instance = new ServiceLocator();
    	}
    	return self::$instance;
	}

	public function bind($name, $service) {
    	$this->services[$name] = $service;
	}

	public function bindFor($context, $name, $service) {
    	$this->contextualBindings[$context][$name] = $service;
	}

	public function resolve($name, $context = null) {
    	if ($context !== null && isset($this->contextualBindings[$context][$name])) {
        	return $this->contextualBindings[$context][$name];
    	} elseif (isset($this->services[$name])) {
        	return $this->services[$name];
    	}
    	throw new Exception("Service not found: $name");
	}
}
