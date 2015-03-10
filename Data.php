<?php
require_once 'Configuration.php';

class Data {
	private $uploadDir =null;
	private $filePath;
	
	public function __construct($fileName = "data.txt"){
		$this->uploadDir = Configuration::getInstance()->get()['InputData']['upload_dir'];
		if(file_exists($this->uploadDir.$filePath))	{
			$this->$filePath = $fileName;
		}else{
			throw new Exception('Error :'+ $this->fileName + ' not found!');
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