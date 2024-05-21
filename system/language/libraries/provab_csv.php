<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * provab
 *
 * Travel Portal Application
 *
 * @package provab
 * @author Sachin J<sachin@provab.com>
 * @copyright Copyright (c) 2017
 * @link http://provab.com
 */

class Provab_csv{

	public $CI; // instance of codeigniter super object

	public function __construct($data = '') {

		$this->CI = & get_instance ();
	}

	/**
	 * excel export the data 
	 */
	public function csv_export($headings,$filename, $export_data,$type="") {
        // error_reporting(E_ALL);
	   $filename =$filename.date('Ymd').'.csv'; 
       // debug($headings);exit;

       if($type=='F')
       {
             $file = fopen(BASEPATH.'reportcsv/'.$filename, 'w');
         
          
           fputcsv($file, $headings);
           foreach ($export_data as $key=>$line){ 
             fputcsv($file,$line); 
           }
           fclose($file);
           return $filename;
       }
       else
       {
           header("Content-Description: File Transfer"); 
           header("Content-Disposition: attachment; filename=$filename"); 
           header("Content-Type: application/csv; ");
           
           // get data 
           // debug($export_data);exit;
           

           // file creation 
           $file = fopen('php://output', 'w');
         
          // debug($export_data);exit;
           fputcsv($file, $headings);
           foreach ($export_data as $key=>$line){ 
             fputcsv($file,$line); 
           }
           fclose($file); 
           exit;
       }

       
		
	}
	
}
?>
