<?php

require_once 'Rest.inc.php';
require_once 'Data.php';

class Api extends REST {
	const DB_HOST = "localhost";
	const DB_USER = "silver";
	const DB_PASS = "treesilver";
	const DB_NAME = "silvertree";
	const DB_TYPE = "mysql";
	private static $db = null;
	public function __construct() {
		parent::__construct ();
		if (self::$db === false) {
			$this->dbConnect ();
		}
		return self::$db;
	}
	
	/**
	 * Connect to the database, select the database using the database link and database name
	 */
	private function dbConnect() {
		$dsn = DB_TYPE .":dbname=" . DB_NAME.";host=" .DB_HOST;
		try {
			self::$db = new PDO ( $dsn, DB_USER, DB_PASS, array (PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'') );
			self::$db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			 print_r($e->errorInfo);
		}
	}
	/**
	 * public method available exposed to consumers of this api.Resolves the action
	 * to be executed from the request.
	 */
	public function processApi() {
		$action = strtolower ( trim ( str_replace ( "/", "", $_REQUEST ["request"] ) ) );
		var_dump($action);
		if (method_exists ( $this, $action )) {
			$this->$action();
		} else {
			$this->response ('', 404 );
		}
		
	}
	private function save() {
				
		$data = new Data();
		$dataInput = $data->getStaffRecords();
		
		$sqlDep = "INSERT INTO departments (department_name,contact_name,contact_emailaddress) VALUES (:department_name, :contact_name, :contact_emailaddress)";
		$sqlCon = "INSERT INTO contacts (first_name,last_name,email_address,gender,department_id) VALUES (:first_name, :last_name, :email_address, :gender, :department_id)";
		
		$stmtDep = self::$db->prepare($sqlDep);
		$stmtCon = self::$db->prepare($sqlCon);
		foreach($dataInput as $line){
			list($firstName, $lastName, $emailAddress, $gender, $departmentName, $contactName, $contactEmailaddress) = explode("\t",$line);
			echo "<pre>";
				echo $firstName."<br/>";
				echo $lastName."<br/>";
				echo $emailAddress."<br/>";
				echo $gender."<br/>";
				echo $departmentName."<br/>";
				echo $contactName."<br/>";
				echo $contactEmailaddress."<br/>";
			
			echo "</pre>";
			try{			
				self::$db->beginTransaction();
				$stmtDep->execute(array(':department_name'=>$departmentName, ':contact_name'=>$contactName, ':contact_emailaddress'=>$contactEmailaddress));
				$departmentId = self::$db->lastInsertId();
				self::$db->commit();
	
				self::$db->beginTransaction();
				$stmtCon->execute(array(':first_name' => $firstName, ':last_name'=>$lastName, ':email_address' => $emailAddress, ':gender' => $gender, ':department_id'=>$departmentId));
				self::$db->commit();			
				
			}catch(PDOException $e){
				error_log($e);
			}
		}
	}
	
	private function removeExistingData(){
		$sql = "DELETE FROM departments";
		$numrows = self::$db->exec($sql);
		$sql = "DELETE FROM contacts";
		$numrows = self::$db->exc($sql);
	}
}

$api = new Api ();
$api->processApi ();

?>