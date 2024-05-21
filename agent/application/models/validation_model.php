<?php
class Validation_Model extends CI_Model {
   function __construct(){
         parent::__construct();
    }
   
   function alphabetValidation($name){
	   $regex = '/^[a-zA-Z ]*$/';
	   
	   if(strlen($name) < 3){
		 return false;  
	   } elseif(!preg_match($regex, $name)){
		   return false;
	   } else {
		   return true; 
	   }
	  
   }
   
    function alphanumericValidation($name){
	   $regex = '/^[a-zA-Z0-9]+$/';
	   if(strlen($name) < 3){
		   return false;
	   } elseif(!preg_match($regex, $name)){
		   return false;
	   } else {
		   return true; 
	   }
	 }
	 
	 
	  function numberWithSpecialCharacter($name){
	   $regex = '/^[0-9 ()-_]*$/';
	   if(strlen($name) < 8){
		   return false;
	   } elseif(!preg_match($regex, $name)){
		   return false;
	   } else {
		   return true; 
	   }
	 }

	 

	 function numberWithHyphen($name){ 
	 //debug($name); exit();
	   $regex = '/^[0-9]+(-[0-9]+)+$/';
	   if(strlen($name) < 1){
		   return false;
	   } elseif(!preg_match($regex, $name)){
		   return false;
	   } else {
		   return true; 
	   }
	 }
	 
	   function emailIdFormat($name){
		    
	   $regex = '/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/';
	   if(strlen($name) < 8){
		   return false;
	   } elseif(!preg_match($regex, $name)){
		   return false;
	   } else {
		   return true; 
	   }
	 }
	
	 
	 function passwordValidation($password) {
		 $regex = '/^([a-zA-Z0-9@*#]{6,15})$/';
		 if(strlen($password) < 6){
		   return false;
	   } elseif(!preg_match($regex, $password)){
		   return false;
	   } else {
		   return true; 
	   }
	 }
	 
	 function checkPasswords($password, $cpassword) {
		 if($password == $cpassword){
			 return true;
		 }else{
			 return false;
		 }
	 }
	 
	 
	
}
?>
