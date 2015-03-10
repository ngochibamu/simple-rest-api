<?php
require_once 'Configuration.php';

class Data {
	private $uploadDir =null;
	private static $settings = array();
	private $filePath;
	
	public function __construct($fileName = "data.txt"){
		$this->settings = Configuration::getInstance()->get();
		$this->uploadDir = $this->settings['upload_dir'];
		if(file_exists($this->uploadDir.$fileName))	{
			$this->filePath = $this->uploadDir.$fileName;
		}else{
			throw new Exception('Error :'+ $this->filePath + ' not found!');
		}
	}
	
	public function getStaffRecords(){
		$input = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);	
		if(!$input){
			throw new Exception('Error: Could not read file:'+$this->fileName);
		}	
		return $input;
	}
}
?>