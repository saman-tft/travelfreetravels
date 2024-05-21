<?php //(defined('BASEPATH')) or exit('No direct script access allowed');
error_reporting(E_ALL);
use Aws\S3\Exception\S3Exception as S3Exception;
require __DIR__ . '/../../supervision/application/third_party/vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\CommandPool;
use Aws\CommandInterface;
use Aws\ResultInterface;
use GuzzleHttp\Promise\PromiseInterface;

class Aws_S3
{
	private $bucket        = "travelomatix";
	private $region        = "ap-south-1";
	
	private $version       = "2006-03-01";
	private $log_file_path = 'application/logs/aws_errors.php';
	private $api_key = 'AKIAI574O5JL3LBGQLSA';
	private $secret_key = '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';

	const VALID_IMAGE_EXTENSION = array(
		'image/jpeg' => 'jpeg',
		'image/png'  => 'png',
	);

	const VALID_VIDEO_EXTENSION = array(
		'video/mp4' => 'mp4',
		'video/avi' => 'avi',
	);

	private function getClient()
	{
		return S3Client::factory(
			array(
				'credentials' => ['key' => $this->api_key, 'secret' => $this->secret_key],
				'region'      => $this->region,
				'version'     => $this->version,
			)
		);
	}

	function getMimeType($filePath)
	{
		if (file_exists($filePath)) {
			$finfo    = finfo_open(FILEINFO_MIME_TYPE);
			$mimetype = finfo_file($finfo, $filePath);
			finfo_close($finfo);
			return $mimetype;
		}
		return false;
	}

	function isValidExtension($fileExtension, $extensions = [])
	{
		if (empty($extensions)) {
			$extensions = array_merge(self::VALID_IMAGE_EXTENSION, self::VALID_VIDEO_EXTENSION);
		}
		if (in_array($fileExtension, $extensions)) {
			return true;
		}
		return false;
	}

	private function getExtension($mimeType){
		$validExt=array_merge(SELF::VALID_VIDEO_EXTENSION,SELF::VALID_IMAGE_EXTENSION);
		if(array_key_exists($mimeType, $validExt)){
			return $validExt[$mimeType];
		}
		return false;
		
	}

	function getFile($fileKey = '')
	{
		$response = ['status' => false, 'message' => '', 'url' => ''];
		if ((strcmp($fileKey, '') == 0) || is_null($fileKey)) {
			$response['message'] = "File key missing";
		} else {
			try {
				$client = $this->getClient();
                // Validate Key Existance
				$res = $client->GetObject([
					'Bucket' => $this->bucket,
					'Key'    => $fileKey,
				]);
				$command = $client->getCommand('GetObject', [
					'Bucket' => $this->bucket,
					'Key'    => $fileKey,
				]);
				$request      = $client->createPresignedRequest($command, '+5 minutes');
				$response['status']=true;
				$response['url']=(string) $request->getUri();
			} catch (S3Exception $ex) {
				$logError = $this->logError($ex);
				$response['message'] = "Error in Accessing remote data";
				$response['logError'] = $logError;
				
			}
		}
		return $response;
	}

	function getFiles($fileKeys)
	{
		$result=[];
		$response = ['status' => false, 'message' => '', 'result' => []];
		if (empty($fileKeys)) {
			$response['message'] = "File keys missing";
		} else {
			$client = $this->getClient();
			foreach ($fileKeys as $key) {
				$request=null;
				$command=null;
				if($client->doesObjectExist($this->bucket,$key)){
					$command = $client->getCommand('GetObject', [
						'Bucket' => $this->bucket,
						'Key'    => $key,
					]);
					$request=$client->createPresignedRequest($command, '+5 minutes');
					$result[]=['url'=>(string) $request->getUri(),'key'=>$key];
				}
			}
			if(empty($result)){
				$response['message']="Files not found on Remote server";
			}else{
				$response['status']=true;
				$response['result']=$result;
			}
		}
		return $response;
	}

	private function logError(S3Exception $ex)
	{
		$error['error_time']       = microtime();
		$error['error_connecting'] = ($ex->isConnectionError() == false) ? 'false' : 'true';
		$error['error_status']     = $ex->getStatusCode();
		$error['error_type']       = $ex->getAwsErrorType();
		$error['error_code']       = $ex->getAwsErrorCode();
		$error['error_message']    = $ex->getAwsErrorMessage();
		
		file_put_contents($this->log_file_path, implode('<->', $error) . PHP_EOL, FILE_APPEND);
		return $error;
	}

	private function validateFile($filePath,$validExtensions=[]){
		$ext=$this->getExtension($this->getMimeType($filePath));
		if($ext && $this->isValidExtension($ext,$validExtensions)){
			return true;
		}
		return false;
	}

	private function generateFileKey(){
		return md5(uniqid(microtime()));
	}

	function deleteFile($fileKey=''){
		$response=['status'=>false,'message'=>''];
		if((strcmp($fileKey,'')==0) || is_null($fileKey)){
			$response['message']="File Key is Missing";
		}else{
			try{
				$client=$this->getClient();

				$client->deleteObject([
					'Bucket' => $this->bucket,
					'Key'    => $fileKey
				]);
				$this->update_file_key($fileKey);
			}catch(S3Exception $ex){
				$logError = $this->logError($ex);
				$response['message'] = "Error in deleting remote data";
				$response['logError'] = $logError;
			}
		}
		return $response;
	}

	function uploadFile($filePath='',$validExtensions=[])
	{
		$response=array('status'=>false,'message'=>'','id'=>null);

		if(!$this->validateFile($filePath,$validExtensions)){
			$response['message']='Unsupported File.';
		}else{
			$client=$this->getClient();
			$fileKey=$this->generateFileKey();
			try {
				$client->putObject(array(
					'Bucket'       => $this->bucket,
					'Key'          => $fileKey,
					'SourceFile'   => $filePath,
					'StorageClass' => 'STANDARD',
				));
				$this->store_file_key($fileKey,$this->bucket,$this->region);
				$response['status']=true;
				$response['message']="Success";
				$response['id']=$fileKey;
			} catch (S3Exception $ex) {
				$logError = $this->logError($ex);
				$response['message'] = "Error in uploading to remote";
				$response['logError'] = $logError;
			}
		}
		return $response;
	}

	function getFilePath($fileKey){	
		if(array_key_exists($fileKey, $_FILES) && ($_FILES[$fileKey]['error']==0)){
			return $_FILES[$fileKey]['tmp_name'];
		}
		return false;
	}

	private function store_file_key($fileKey,$bucket,$region){
		$CI = &get_instance();
		$CI->db->set('file_key',$fileKey);
		$CI->db->set('bucket',$bucket);
		$CI->db->set('region',$region);
		$CI->db->set('status',1);
		$CI->db->set('created_on',date('Y-m-d H:i:s'));
		$CI->db->set('updated_on',date('Y-m-d H:i:s'));
		$CI->db->insert('aws_objects');
	}

	private function store_file_keys($fileKeys,$bucket,$region){
		$res=[];
		foreach ($fileKeys as $key) {
			$res[]=[
				'file_key'=>$key,
				'bucket'=>$bucket,
				'region'=>$region,
				'status'=>1,
				'created_on'=>date('Y-m-d H:i:s'),
				'updated_on'=>date('Y-m-d H:i:s')
			];
		}
		$CI = &get_instance();
		$CI->db->insert_batch('aws_objects',$res);
	}

	private function update_file_key($fileKey){
		$CI = &get_instance();
		$CI->db->where('file_key',$fileKey);
		$CI->db->set('status',0);
		$CI->db->set('updated_on',date('Y-m-d H:i:s'));
		$CI->db->update('aws_objects');
	}

	private function update_file_keys($fileKeys){
		$res=[];
		foreach ($fileKeys as $key) {
			$res[]=[
				'file_key'=>$key,
				'status'=>0,
				'updated_on'=>date('Y-m-d H:i:s')
			];
		}
		$CI = &get_instance();
		return $CI->db->update_batch('aws_objects',$res,'file_key');
	}


	function getBucketObjects(){
		$response=['status'=>false,'result'=>[],'message'=>'No Objects Found'];
		try{
			$client=$this->getClient();
			$ids=[];
			$objects = $client->getPaginator('ListObjects', [
				'Bucket' => $this->bucket
			]);
			foreach ($objects as $object) {
				if(!is_null($object['Contents'])){
					foreach ($object['Contents'] as $content) {
						$ids[]=$content['Key'];
					}
				}
			}
			if(!empty($ids)){
				$response['status']=true;
				$response['result']=$ids;
				$response['message']='Bucket Objects key';
			}
		}catch(S3Exception $ex){
			$this->logError($ex);
			$response['message']="Error in listing objects";
		}
		return $response;
	}


	function uploadMultipleFiles($fileKey,$validExtensions=[]){
		$response=['status'=>false,'result'=>[],'message'=>'Upload Failed'];
		if(!isset($_FILES[$fileKey]) || !is_array($_FILES[$fileKey]['name'])){
			$response['message']="Invalid File Object";
		}else{
			$filePaths=[];
			for($i=0;$i<count($_FILES[$fileKey]['name']);$i++){
				if($this->validateFile($_FILES[$fileKey]['tmp_name'][$i],$validExtensions)){
					$filePaths[]=$_FILES[$fileKey]['tmp_name'][$i];
				}
			}
			if(!empty($filePaths)){
				$response=$this->uploadFilesToS3($filePaths);
			}else{
				$response['message']="Unsupported Files";	
			}
		}
		return $response;
	}

	function uploadFilesToS3($filePaths=[]){
		$response=['status'=>false,'result'=>[],'message'=>'Upload Failed'];
		if(empty($filePaths)){
			$response['message']="Empty Upload";
		}
		try{
			$keys=[];
			$commands=[];
			$client=$this->getClient();
			foreach ($filePaths as $filePath) {
				$key=$this->generateFileKey();
				$keys[]=$key;
				$commands[]=$client->getCommand('putObject',array(
					'Bucket'       => $this->bucket,
					'Key'          => $key,
					'SourceFile'   => $filePath,
					'StorageClass' => 'STANDARD',
				)
			);
			}
			$success=[];
			$failed=[];
			if(!empty($commands)){
				$commandPool = new CommandPool($client, $commands, [
					'fulfilled' => function (
						ResultInterface $result,
						$iterKey,
						PromiseInterface $aggregatePromise
					) {
						$GLOBALS['success'][]=$iterKey;
					}
				]);
				$promise = $commandPool->promise();
				$result = $promise->wait();
			}else{
				$response['message']="Object creation failed";
			}
			if(empty($GLOBALS['success'])){
				$response['message']="Error in Uploading Files";
			}else{
				$res=[];
				foreach ($GLOBALS['success'] as $key) {
					$res[]=$keys[$key];
				}
				$response['message']="Success";
				$response['status']=true;
				$response['result']=$res;
				$this->store_file_keys($res,$this->bucket,$this->region);
			}
		} catch (S3Exception $ex) {
			$this->logError($ex);
			$response['message']=$ex->getAwsErrorMessage;
		}
		return $response;
	}

	function deleteFiles($fileKeys=[]){

		$response=['status'=>false,'message'=>""];
		if(empty($fileKeys)){
			$response['message']="Files not not defined";
		}else{
			try{
				$client=$this->getClient();
				$client->deleteObjects([
					'Bucket'  => $this->bucket,
					'Delete' => [
						'Objects' => array_map(function ($key) {
							return ['Key' => $key];
						}, $fileKeys)
					],
				]);
				$this->update_file_keys($fileKeys);
				$response['status']=true;
			}
			catch (S3Exception $ex) {
				$this->logError($ex);
				$response['message']=$ex->getAwsErrorMessage;
			}
		}
		return $response;
	}

}