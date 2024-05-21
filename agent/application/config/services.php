<?php
$serviceLocator = ServiceLocator::getInstance();
$serviceLocator->bind('InsuranceInterface', new Protect()); 

$serviceLocator->bindFor('Insurance', 'InsuranceInterface', new Protect());
// $serviceLocator->bindFor('ClassB', 'MyInterface', new AnotherImplementation());
